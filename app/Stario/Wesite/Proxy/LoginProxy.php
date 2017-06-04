<?php
namespace Star\Wesite\Proxy;

use App\Patient;
use Star\ICenter\Proxy\BaseProxy;
use Star\Utils\StarJson;

class LoginProxy extends BaseProxy {
	private $user;
	public function __construct(Patient $user) {
		$this->user = $user;
	}
	public function params() {
		return [
			'client_id' => env('PASSWORD_CLIENT_ID'),
			'client_secret' => env('PASSWORD_CLIENT_SECRET'),
			'provider' => 'api',
			'grant_type' => 'password',
			'scope' => '',
		];
	}
	// 实现抽象类方法，传递参数必须为'username'和'password'
	public function attemptLogin($request) {
		$user = $this->user->where('mobile', '=', $request['mobile'])->first();
		if (!empty($user)) {
			return $this->proxy('password', ['username' => $request['mobile'], 'password' => $request['password']]);
		}
		return StarJson::create(401);
	}

}