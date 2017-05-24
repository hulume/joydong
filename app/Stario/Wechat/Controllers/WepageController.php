<?php

namespace Star\Wechat\Controllers;

use App\Http\Controllers\Controller;
use Star\Utils\StarJson;
use Star\Wechat\WePage;

class WemenuController extends Controller {

	protected $page;
	public function __construct(WePage $page) {
		$this->page = $page;
	}

	public function index() {

	}

	public function store() {
		return $this->menu->create(request()->all());
	}

	public function update($id) {
		if ($this->menu->find($id)->update(request()->all())) {
			return StarJson::create('更新成功', 200);
		}
		return StarJson::create(304);
	}

	public function destroy($id) {
		return $this->menu->destroy($id);
	}
}
