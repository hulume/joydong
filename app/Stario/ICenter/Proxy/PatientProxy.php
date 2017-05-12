<?php
namespace Star\ICenter\Proxy;

use App\Patient;
use Star\utils\StarJson;

class PatientProxy extends BaseProxy {
	private $user;

	public function __construct(Patient $user) {
		$this->user = $user;
	}
	protected function params() {
		return [
			'client_id' => env('PASSWORD_CLIENT_ID'),
			'client_secret' => env('PASSWORD_CLIENT_SECRET'),
			'provider' => 'api',
			'scope' => 'patient',
		];
	}
	public function attemptLogin($request) {
		$user = $this->user->where('mobile', $request['mobile'])->first();
		if (!empty($user)) {
			return $this->proxy($request);
		}
		return StarJson::create(401);
	}
}