<?php

namespace Star\Wesite\Repository\Eloquent;

use App\Archive;
use Star\ICenter\Repository\Eloquent\BaseRepository;

class ArchiveRepo extends BaseRepository {
	protected $model;

	// 定义字段用于前台vuetable检索
	protected $fieldSearchable = [
		'name', 'mobile', 'identify',
	];

	/**
	 * 必须在构造器中声明一下使用的模型
	 */
	public function __construct(Archive $archive) {
		$this->model = $archive;
	}

}