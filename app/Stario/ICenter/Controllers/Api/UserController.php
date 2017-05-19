<?php

namespace Star\ICenter\Controllers\Api;

use Excel;
use Route;
use Star\ICenter\Repository\Eloquent\UserRepo;
use Star\ICenter\Requests\UserManageFormRequest;
use Star\ICenter\Transformers\UserInfoTransformer;
use Star\ICenter\Transformers\UserTransformer;
use Star\utils\StarJson;

class UserController extends ApiController {
	protected $userRepo;

	public function __construct(UserRepo $userRepo) {
		parent::__construct();
		$this->userRepo = $userRepo;
		$this->middleware('permission:manage_users');
	}

	public function index() {
		$users = $this->userRepo->dataTableProvider(['unit']);
		return $this->respondWithPaginator($users, new UserTransformer());
	}

	public function update(UserManageFormRequest $request) {
		$id = Route::current()->parameter('user');
		// $this->userRepo->find($id)->syncPermissions($request->permissions);
		return $request->persist($id);
	}
	public function edit($id) {
		$user = $this->userRepo->find($id);
		return $this->respondWithItem($user, new UserInfoTransformer());
	}
	public function store(UserManageFormRequest $request) {
		return $request->persist();
	}
	public function destroy() {
		// 前端传来的id有可能是字符串或数组
		if (!is_array(request()->input('ids'))) {
			$ids = explode(',', request()->input('ids'));
		} else {
			$ids = request()->input('ids');
		}
		foreach ($ids as $id) {
			$this->userRepo->delete($id);
		}
		return StarJson::create('成功删除用户', 200);
	}

	// export excel
	public function export() {
		$users = $this->userRepo->with(['unit'])->all();
		$data = $users->map(function ($u) {
			return [
				'姓名' => $u->name,
				'手机号码' => $u->mobile,
				'部门' => $u->unit->name,
				'创建日期' => $u->created_at,
				'登录日期' => $u->last_login,
			];
		})->toArray();

		$export = Excel::create('内部人员列表', function ($excel) use ($data) {

			$excel->sheet('内部人员列表', function ($sheet) use ($data) {
				$sheet->fromArray($data)
					->prependRow(1, [
						'内部人员管理表',
					])
					->mergeCells('A1:E1')
					->cell('A1', function ($cell) {
						$cell->setFont([
							'size' => '16',
							'bold' => true,
						])
							->setAlignment('center');
					})
					->setWidth([
						'A' => 10,
						'B' => 20,
						'C' => 10,
						'D' => 30,
						'E' => 30,
					]);
			});
		})->store('xls', false, true);

		return StarJson::create([
			'url' => url('storage/exports/' . $export['file']),
		], 200);
	}

}