<?php
namespace Star\ICenter\Proxy;

use GuzzleHttp\Client;
use Star\Utils\StarJson;

/**
 * 基于Laravel Passport，用于获取token
 * 其中自动生成名为refreshToken的cookie（存放refresh_token）
 */
abstract class BaseProxy {
	abstract public function attemptLogin($request);
	/**
	 * attemptLogin 实现方法如：
	 *
	public function attemptLogin($request) {
	$user = $this->user->where('mobile', '=', $request['mobile'])->first();
	if (!empty($user)) {
	return $this->proxy('password', ['username' => $request['mobile'], 'password' => $request['password']]);
	}
	return StarJson::create(401);
	}
	 */
	abstract public function params();
	/**
	 * params 实现方法如：
	 *
	public function params() {
	return [
	'client_id' => env('PASSWORD_CLIENT_ID'),
	'client_secret' => env('PASSWORD_CLIENT_SECRET'),
	'provider' => 'api',
	'scope' => '',
	];
	}
	 */
	protected function proxy($grantType, array $data = []) {
		$params = array_merge(
			$data,
			[
				'client_id' => $this->params()['client_id'],
				'client_secret' => $this->params()['client_secret'],
				'grant_type' => $grantType,
				'scope' => $this->params()['scope'],
				'provider' => $this->params()['provider'],
			]);
		$client = new Client();
		$response = $client->request('POST', url('/oauth/token'), [
			'form_params' => $params,
		]);

		if ($response->getStatusCode() != 200) {
			return StarJson::create(401);
		}

		$data = json_decode($response->getBody()->getContents());
		// 如果是client_credentials 类型，返回下面的内容
		if ($grantType == 'client_credentials') {
			return response([
				'token_type' => $data->token_type,
				'access_token' => $data->access_token,
			]);
		}
		// 其它类型则返回：
		return response([
			'token_type' => $data->token_type,
			'access_token' => $data->access_token,
			'refresh_token' => $data->refresh_token,
			'expires_in' => $data->expires_in,
		])->cookie('refreshToken', $data->refresh_token, 864000, null, null, false, true);
	}
	/**
	 * 刷新token
	 * @param  $request 捕获请求cookie值
	 * @return  json
	 */
	public function attemptRefresh($request) {
		$refreshToken = request()->cookie('refreshToken');
		return $this->proxy('refresh_token', [
			'refresh_token' => $refreshToken,
		]);
	}

}