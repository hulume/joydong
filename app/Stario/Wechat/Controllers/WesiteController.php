<?php

namespace Star\Wechat\Controllers;

use App\Http\Controllers\Controller;
use EasyWeChat;
use Illuminate\Http\Request;
use Star\Wechat\Wesite;

class WesiteController extends Controller {

	protected $wesiteMenu;
	public function __construct(Wesite $wesiteMenu) {
		$this->wesiteMenu = $wesiteMenu;
	}
	// TODO 将微网站全部内容可自定义
	public function linkMaterial(Request $request) {
		$data = $request->except('id');
		$material = EasyWeChat::material();
		foreach ($data as $key => $item) {
			$thumb = $material->get($item['thumb_media_id']);
			$type = $this->findWxfmt($item['thumb_url']);
			file_put_contents(public_path() . '/upload/wesite/' . $item['thumb_media_id'] . '.' . $type, $thumb);
			$data[$key]['realpath'] = url('/upload/wesite/' . $item['thumb_media_id'] . '.' . $type);
		}

	}
	private function findWxfmt($url) {
		return explode('?wx_fmt=', $url)[1];
	}
}
