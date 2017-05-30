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
	protected function params() {
		return [
			'client_id' => env('PASSWORD_CLIENT_ID'),
			'client_secret' => env('PASSWORD_CLIENT_SECRET'),
			'provider' => 'patients',
			'scope' => '',
		];
	}
	public function attemptLogin($request) {
		$user = $this->user->where('mobile', $request['mobile'])->first();
		if (!empty($user)) {
			return $this->proxy('password', $request);
		}
		return StarJson::create(401);
	}
	public function attemptRefresh() {
		$refreshToken = request()->cookie('refresh_token');
		return $this->proxy('refresh_token', [
			'refresh_token' => $refreshToken,
		]);
	}
}