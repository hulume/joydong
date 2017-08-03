<?php
namespace Star\Wesite\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;
use Star\Utils\StarJson;
use Star\Wesite\Proxy\ClientProxy;
use Star\Wesite\Proxy\LoginProxy;
use Star\Wesite\Repository\Eloquent\PatientRepo;
use Star\Wesite\Requests\AuthRequest;

/**
 * 对微网站的相关服务进行验证，包括：
 * 内外网数据服务器上传接口验证(client_credentials)
 * 微信微网站用户验证是否绑定，并获取相关token
 * 提供password和client_credentials两种获取token方式，基于Passport
 */
class AuthController extends Controller {
	private $loginProxy;
	private $user;
	public function __construct(PatientRepo $user, LoginProxy $loginProxy) {
		$this->loginProxy = $loginProxy;
		$this->user = $user;
	}
	/**
	 * 用于获取用户passport password类型的token
	 * @param  Request $request
	 * @return string
	 */
	public function getToken(Request $request) {
		$params = [
			'mobile' => $request->get('mobile'),
			'password' => $request->get('password'),
		];
		return $this->loginProxy->attemptLogin($params);
	}
	/**
	 * 用于刷新用户token
	 * @param  Request $request
	 * @return mixed
	 */
	public function refreshToken(Request $request) {
		return $this->loginProxy->attemptRefresh($request);
	}

	// 查看微信微网站访问用户是否已经与数据库绑定
	public function checkIfBound(Request $request) {
		$openid = decrypt($request->cookie('wesite_openid'));
		if (empty($openid)) {
			return StarJson::create('您需要在微信客户端打开', 403);
		}
		// 如果已经绑定
		if ($item = $this->user->findBy('openid', $openid, ['mobile', 'openid'])) {
			$item = array_flatten($item);
			return $this->getToken($item[0], $item[1]);
		}
	}

	// 未曾绑定过
	public function bind(AuthRequest $request) {
		$mobile = $request->get('mobile');
		$authcode = $request->get('authcode');
		if (Cache::get($mobile) != $authcode) {
			return StarJson::create('您输入的验证码不正确', 403);
		}
		try {
			if ($this->user->pick('mobile', $mobile)) {
				return StarJson::create('该手机号已经绑定微信号，请使用该微信号登录', 403);
			}
		} catch (Exception $e) {
			return StarJson::create($e, 403);
		}
		// 解密出cookie中微信用户的openid
		$openid = decrypt($request->cookie('wesite_openid'));
		// 创建此用户，用户密码为hash过的openid (因为使用aravel passport的password模式获取token)
		$result = $this->user->create([
			'mobile' => $mobile,
			'openid' => $openid,
			'password' => bcrypt($openid),
		]);
		if ($result) {
			// 获取token, 其中refreshToken会自动写入cookie
			$this->getToken($mobile, $openid);
		} else {
			return StarJson::create('绑定失败', 403);
		}
	}
	// 用于获取passport client_credentials 类型（如LIS）token
	public function getClientToken() {
		return (new ClientProxy)->attemptLogin('null');
	}
}