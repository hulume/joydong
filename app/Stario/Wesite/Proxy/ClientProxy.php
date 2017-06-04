<?php
namespace Star\Wesite\Proxy;

use Star\ICenter\Proxy\BaseProxy;

class ClientProxy extends BaseProxy {
	public function params() {
		return [
			'client_id' => env('PASSWORD_CLIENT_ID'),
			'client_secret' => env('PASSWORD_CLIENT_SECRET'),
			'provider' => 'api',
			'scope' => '',
		];
	}

	public function attemptLogin($request) {
		$request = explode(',', env('CLIENT_CREDENTIALS_ALLOWED_IP'));

		if (in_array(request()->ip(), $request)) {
			return $this->proxy('client_credentials');
		}
		return response()->json('操作非法', 200);
	}

}