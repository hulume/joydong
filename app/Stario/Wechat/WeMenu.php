<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class WeMenu extends Model {
	protected $table = 'we_menus';
	protected $guarded = ['id'];

	public function pages() {
		return $this->hasMany('Star\Wechat\WePage');
	}
}
