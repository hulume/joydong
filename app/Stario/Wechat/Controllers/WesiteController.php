<?php

namespace Star\Wechat\Controllers;

use App\Http\Controllers\Controller;
use Star\utils\StarJson;
use Star\Wechat\Wesite;

class WesiteController extends Controller {

	private $settings;
	public function __construct(Wesite $wesite) {
		$this->settings = $wesite->first()->settings();
	}

	public function getFocus() {
		return $this->settings->all();
	}

	public function createFocus() {
		if ($this->settings->set('focus', [['title' => 'aaa', 'url' => 'http://www.baidu.com'], ['title' => 'bbb', 'url' => 'http://stario.net']])) {
			StarJson::create('设置成功', 200);
		}
	}

	public function createMenu() {

	}
}
