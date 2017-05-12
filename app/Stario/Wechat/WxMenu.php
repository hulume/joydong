<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class WxMenu extends Model {
	protected $table = 'wx_menus';
	protected $guarded = ['id'];

	public function wxPosts() {
		return $this->hasMany('Star\Wechat\WxPost');
	}
}
