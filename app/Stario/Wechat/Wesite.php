<?php

namespace Star\Wechat;

use Illuminate\Database\Eloquent\Model;
use Star\Wechat\HasSettings;

class Wesite extends Model {
	protected $table = 'wesite';
	protected $fillable = ['settings'];

	use HasSettings;
}
