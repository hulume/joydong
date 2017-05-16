<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class WesiteMenu extends Model {
	protected $table = 'wesite_menus';
	protected $guarded = ['id'];

	public function wesitePosts() {
		return $this->hasMany('Star\Wechat\WesitePost');
	}
}
