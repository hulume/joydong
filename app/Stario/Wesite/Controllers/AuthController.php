<?php
namespace Star\Wesite\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Star\Utils\StarJson;
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
	public function login(AuthRequest $request) {
		return $this->loginProxy->attemptLogin(['mobile' => $request->get('mobile'), 'password' => $request->get('password')]);
	}
	public function register(AuthRequest $request) {
		$openid = decrypt($request->cookie('wesite_openid'));
		if (empty($openid)) {
			return StarJson::create('您需要在微信客户端打开', 403);
		}
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
		$result = $this->user->create([
			'mobile' => $mobile,
			'openid' => $openid,
			'password' => bcrypt($openid),
		]);
		if ($result) {
			// 获取Response的原始数据
			$credentials = $this->loginProxy->attemptLogin(['mobile' => $mobile, 'password' => $openid])->original;
			return StarJson::create($credentials['access_token'], 200)->withCookie(cookie('refreshToken', $credentials['refresh_token']));
		} else {
			return StarJson::create('绑定失败', 403);
		}
	}
	public function refresh(Request $request) {
		return $this->loginProxy->attemptRefresh();
	}
}