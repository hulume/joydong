<?php

namespace Star\Wesite\Requests;

use Cache;
use Illuminate\Foundation\Http\FormRequest;
use Star\SimpleMessage\SmsClient;
use Star\Utils\StarJson;
use Star\Wesite\Repository\Eloquent\PatientRepo;

class FireSmsFormRequest extends FormRequest {
	protected $user;
	public function __construct(PatientRepo $user) {
		$this->user = $user;
	}

	public function authorize() {
		return true;
	}
	public function rules() {
		return [
			'mobile' => 'required|min:11|max:11',
		];
	}
	// 存储入库，如果发现?findpass则更新密码
	public function fire() {
		if (isset($_GET['findpass'])) {
			return $this->forReset();
		}
		return $this->forNormal();
	}

	private function forReset() {
		$mobile = $this->request->get('mobile');
		if ($this->user->pick('mobile', $mobile)) {
			return (new SmsClient)->to($mobile)->type('auth')->send();
		}

		if (Cache::has($mobile)) {
			return StarJson::create('短信已发送，新的验证码将在15分钟后刷新', 200);
		}
		return StarJson::create('该手机号没有绑定微信，请返回绑定', 403);
	}

	private function forNormal() {
		$mobile = $this->request->get('mobile');
		if ($this->user->pick('mobile', $mobile)) {
			return StarJson::create('该手机号已经被其它微信号绑定，请使用其他微信账号访问', 403);
		}
		if (Cache::has($mobile)) {
			return StarJson::create('短信已发送，新的验证码将在15分钟后刷新', 200);
		}
		return (new SmsClient)->to($mobile)->type('auth')->send();
	}

}