<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;

class Wesite extends Model {
	protected $table = 'wesite_posts';
	protected $guarded = ['id'];

	public function wesiteMenu() {
		return $this->belongsTo('Star\Wechat\WesiteMenu');
	}
}
