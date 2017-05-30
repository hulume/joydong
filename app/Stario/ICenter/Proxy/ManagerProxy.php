<?php
namespace Star\ICenter\Proxy;

use App\User;
use Star\utils\StarJson;

class ManagerProxy extends BaseProxy {
	private $user;

	public function __construct(User $user) {
		$this->user = $user;
	}
	protected function params() {
		return [
			'client_id' => env('PASSWORD_CLIENT_ID'),
			'client_secret' => env('PASSWORD_CLIENT_SECRET'),
			'provider' => 'api_manager',
			'scope' => 'manager',
		];
	}
	public function attemptLogin($request) {
		$user = $this->user->where('mobile', $request['mobile'])->first();
		if (!empty($user)) {
			return $this->proxy('password', $request);
		}
		return StarJson::create(401);
	}
}