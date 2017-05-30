<?php
namespace Star\Wesite\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Star\Wesite\Repository\Eloquent\PatientRepo;

class RegisterFormRequest extends FormRequest {
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
			'mobile' => 'required|max:11',
			'authCode' => 'required',
		];
	}

	// 存储入库
	public function persist() {
		$request = $this->request->input('mobile', 'authcode');
		if (\Cache::get($mobile) != $authCode) {
			return StarJson::create('您输入的验证码不正确', 403);
		}
		return $this->register();
	}

	/**
	 * 注册用户，并自动分配为user角色
	 */
	private function register() {
		$request = $this->request->all();
		try {
			if ($this->user->pick('mobile', $request['mobile'])) {
				return StarJson::create('该手机号已经绑定微信号，请使用该微信号登录', 403);
			}
		} catch (Exception $e) {
			return StarJson::create(['result' => [$e]], 403);
		}

		return $this->user->create([
			'mobile' => $request['mobile'],
		]);
	}
}
