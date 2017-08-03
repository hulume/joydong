<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 用于存放Lis系统抓取的数据并作为诊断依据，与库中表之间无关联关系
 */
class Lis extends Model {

	protected $table = 'lis';

	protected $fillable = [
		'result', 'name',
	];

	protected $casts = [
		'result' => 'array',
	];
}
