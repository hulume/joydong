<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class WePage extends Model {
	protected $table = 'we_pages';
	protected $guarded = ['id'];

	public function menus() {
		return $this->belongTo('Star\Wechat\WeMenu', 'wx_menu_id');
	}
}
