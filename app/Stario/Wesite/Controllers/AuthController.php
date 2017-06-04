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
 * 用于患者在微网站登录验证使用
 */
class AuthController extends Controller {
	private $loginProxy;
	private $user;
	public function __construct(PatientRepo $user, LoginProxy $loginProxy) {
		$this->loginProxy = $loginProxy;
		$this->user = $user;
	}
	// 使用了ICenter中的Request
	// 不存在密码，都是自动登录
	public function getToken(Request $request) {
		$params = [
			'mobile' => $request->get('mobile'),
			'password' => $request->get('password'),
		];
		return $this->loginProxy->attemptLogin($params);
	}

	public function refreshToken(Request $request) {
		return $this->loginProxy->attemptRefresh($request);
	}

	// 查看是否已经绑定
	public function checkIfBound(Request $request) {
		$openid = decrypt($request->cookie('wesite_openid'));
		if (empty($openid)) {
			return StarJson::create('您需要在微信客户端打开', 403);
		}
		// 如果已经绑定
		if ($item = $this->user->findBy('openid', $openid, ['mobile', 'openid'])) {
			$item = array_flatten($item->toArray());
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
	public function getClientToken() {
		return (new ClientProxy)->attemptLogin('null');
	}
}