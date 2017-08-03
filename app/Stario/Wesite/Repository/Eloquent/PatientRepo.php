<?php

namespace Star\Wesite\Repository\Eloquent;

use App\Patient;
use Star\ICenter\Repository\Eloquent\BaseRepository;

class PatientRepo extends BaseRepository {
	protected $model;

	// 定义字段用于前台vuetable检索
	protected $fieldSearchable = [
		'name', 'mobile', 'identify', 'address',
	];

	/**
	 * 必须在构造器中声明一下使用的模型
	 */
	public function __construct(Patient $patient) {
		$this->model = $patient;
	}

	// public function withScope($scope) {
	// 	return $this->model->$scope();
	// }
	/**
	 * 针对Patient模型分为floating和resident两种scope，特制此方法
	 * 返回用于前端表格检索的数据源
	 * @param  string $scope scope的名称
	 * @return Collection
	 */
	public function withScopeTableProvider($scope) {
		$fields = $this->getFieldsSearchable();
		$request = request();
		$perpage = $request['per_page'];
		$sorts = $request['sort'] == '' ? ['id', 'asc'] : explode('|', $request['sort']);
		$filter = $request['filter'];

		$model = $this->model->$scope();

		if (!empty($filter)) {
			foreach ($fields as $k => $value) {
				if ($k == 0) {
					$model->where($fields[$k], 'LIKE', "%$filter%");
				}
				$model->orWhere($fields[$k], 'LIKE', "%$filter%");
			}
		}
		return $model->paginate($perpage);
	}
}