<?php

namespace Star\Wesite\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Star\Wesite\Repository\Eloquent\ArchiveRepo;

class HospitalController extends Controller {

	protected $archive;
	public function __construct(ArchiveRepo $archive) {
		$this->archive = $archive;
	}
	public function lis(Request $request) {
		$mock = json_decode($request->all()[0]);
		$count;
		foreach ($mock as $key => $item) {
			$archive = $this->archive->findBy('name', $item->name);
			if ($archive) {
				$this->archive->update($archive[0]->id, [
					'name' => $item->name,
					'result' => $item->result,
				]);
			} else {
				$this->archive->create([
					'name' => $item->name,
					'result' => $item->result,
				]);
			}
			$count = $key;
		}
		return '成功写入' . $count . '条记录';
	}
}