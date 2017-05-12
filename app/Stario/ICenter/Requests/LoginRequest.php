<?php
namespace Star\ICenter\Requests;

use Star\ICenter\Requests\ApiRequest;

class LoginRequest extends ApiRequest {
	public function authorize() {
		return true;
	}
	public function rules() {
		return [
			'mobile' => 'required',
			'password' => 'required',
		];
	}
}