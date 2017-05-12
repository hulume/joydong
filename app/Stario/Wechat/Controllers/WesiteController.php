<?php

namespace Star\Wechat\Controllers;

use App\Http\Controllers\Controller;
use EasyWeChat;
use Illuminate\Http\Request;
use Star\Wechat\Wesite;

class WesiteController extends Controller {

	// public function __construct(Wesite $wesite) {
	// 	if ($wesite->first()) {
	// 		$this->settings = $wesite->first()->settings();
	// 	}
	// 	$this->settings = $wesite->settings();
	// }

	public function linkMaterial(Request $request) {

		$data = $request->except('id');
		$material = EasyWeChat::material();
		foreach ($data as $key => $item) {
			$thumb = $material->get($item['thumb_media_id']);
			$type = $this->findWxfmt($item['thumb_url']);
			file_put_contents(public_path() . '/upload/wesite/' . $item['thumb_media_id'] . '.' . $type, $thumb);
			$data[$key]['realpath'] = url('/upload/wesite/' . $item['thumb_media_id'] . '.' . $type);
		}
		if ($wesite = Wesite::first()) {
			$wesite->settings()->delete('one');
			$wesite->settings()->set('one', $data);
		}
		$wesite = new Wesite;
		$wesite->settings()->set('one', $data);
	}
	private function findWxfmt($url) {
		return explode('?wx_fmt=', $url)[1];
	}
}
