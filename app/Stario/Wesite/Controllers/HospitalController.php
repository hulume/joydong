<?php

namespace Star\Wesite\Controllers;
use App\Http\Controllers\Controller;
use App\Lis;
use Illuminate\Http\Request;

class HospitalController extends Controller {

	protected $archive;
	public function __construct(Lis $archive) {
		$this->archive = $archive;
	}
	public function lis(Request $request) {
		$data = $request->all();
		if (empty($data)) {
			return '暂无新的数据需要同步';
		}
		$count = 0;
		$fails = [];
		foreach ($data as $key => $item) {
			$archive = $this->archive->where('name', '=', $item['name'])->first();
			if ($archive !== null) {
				$archive->name = $item['name'];
				$archive->result = $item['result'];
				if ($archive->save()) {
					$count++;
				}
			} else {
				$this->archive->name = $item['name'];
				$this->archive->result = $item['result'];
				if ($this->archive->save()) {
					$count++;
				}
			}
		}
		return '成功写入' . $count . '条记录';
	}
}