<?php

namespace Star\Wechat\Controllers;

use App\Http\Controllers\Controller;
use EasyWeChat;
use Illuminate\Http\Request;
use Star\Wechat\Wesite;

class WesiteController extends Controller {

	private $settings;
	public function __construct(Wesite $wesite) {
		// $this->settings = $wesite->first()->settings();
	}

	public function linkMaterial(Request $request) {
		// $thumb_ids = array_pluck($request->except('id'), 'thumb_media_id');
		$data = $request->except('id');
		$material = EasyWeChat::material();
		foreach ($data as $key => $item) {
			$thumb = $material->get($item['thumb_media_id']);
			$type = $this->findWxfmt($item['thumb_url']);
			file_put_contents(public_path() . '/upload/wesite/' . $id . $type, $thumb);
		}
		// $thumb = $material->get();
	}
	private function findWxfmt($url) {
		return explode('?wx_fmt=', $url)[1];
	}
}
