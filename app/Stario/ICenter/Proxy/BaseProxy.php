<?php
namespace Star\ICenter\Proxy;

use GuzzleHttp\Client;
use Star\utils\StarJson;

abstract class BaseProxy {

	abstract protected function params();

	abstract public function attemptLogin($data);
	// abstract public function attemptRefresh();

	public function logout() {
		$accessToken = auth()->user()->token();
	}

	public function proxy(array $data = []) {
		$data = array_merge(
			[
				'username' => $data['mobile'],
				'password' => $data['password'],
			],
			[
				'client_id' => $this->params()['client_id'],
				'client_secret' => $this->params()['client_secret'],
				'grant_type' => 'password',
				'scope' => $this->params()['scope'],
				'provider' => $this->params()['provider'],
			]);
		$client = new Client();
		$response = $client->request('POST', url('/oauth/token'), [
			'form_params' => $data,
		]);

		if ($response->getStatusCode() != 200) {
			return StarJson::create(401);
		}

		$data = json_decode($response->getBody()->getContents());
		return StarJson::create([
			'token_type' => $data->token_type,
			'access_token' => $data->access_token,
			'refresh_token' => $data->refresh_token,
			'expires_in' => $data->expires_in,
			// 'provider' => 'patients',
		], 200);
	}
}