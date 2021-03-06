<?php

namespace Star\ICenter\Notifications;

use Carbon\Carbon;
use Star\ICenter\Notifications\BaseNotification;

class UserOnChangedCredential extends BaseNotification {

	public function toArray($notifiable) {
		$notifiable->last_ip = request()->ip();
		$notifiable->last_login = Carbon::now();
		$notifiable->save();
		return [
			'title' => '您的账号登录凭证发生改变',
			'content' => '您的账号于登录凭证（手机号码或密码）曾于' . Carbon::now() . '在' . request()->ip() . '被修改。',
			'type' => 'danger',
		];
	}
}
