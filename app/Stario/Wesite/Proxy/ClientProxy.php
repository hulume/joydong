<?php
namespace Star\Wesite\Proxy;

use Illuminate\Support\Facades\Log;
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

		if (request()->ip() === env('CLIENT_CREDENTIALS_ALLOWED_IP'))) {
			return $this->proxy('client_credentials');
		}
		return response()->json('操作非法', 200);
	}

}