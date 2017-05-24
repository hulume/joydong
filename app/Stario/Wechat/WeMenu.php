<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class WeMenu extends Model {
	protected $table = 'we_menus';
	protected $guarded = ['id'];

	// 获取所有的图文消息集合
	public function pages() {
		return $this->hasMany('Star\Wechat\WePage', 'wx_menu_id');
	}
	// 主导航菜单
	public function scopeMain($query) {
		return $query->where('type', 1);
	}
	// 引导菜单
	public function scopeGuide($query) {
		return $query->where('type', 2);
	}
	// 主题菜单
	public function scopeTheme($query) {
		return $query->where('type', 3);
	}
}
