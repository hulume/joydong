<?php

namespace Star\Wesite\Repository\Eloquent;

use App\Lis;
use Star\ICenter\Repository\Eloquent\BaseRepository;

class LisRepo extends BaseRepository {
	protected $model;

	// 定义字段用于前台vuetable检索
	// protected $fieldSearchable = [
	// 	'name', 'mobile', 'identify',
	// ];

	/**
	 * 必须在构造器中声明一下使用的模型
	 */
	public function __construct(Lis $archive) {
		$this->model = $archive;
	}

}