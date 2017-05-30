<?php
namespace Star\Wesite\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Star\Wesite\Repository\Eloquent\PatientRepo;

class AuthRequest extends FormRequest {
	protected $user;

	public function __construct(PatientRepo $user) {
		$this->user = $user;
	}
	public function authorize() {
		return true;
	}

	public function wantsJson() {
		return true;
	}
	public function rules() {
		return [
			'mobile' => 'required',
			'authcode' => 'required',
		];
	}
}