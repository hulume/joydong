<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class WxPost extends Model {
	protected $table = 'wx_posts';
	protected $guarded = ['id'];

	public function wxMenu() {
		return $this->belongsTo('Star\Wechat\WxMenu');
	}
}
