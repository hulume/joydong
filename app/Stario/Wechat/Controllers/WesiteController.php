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
		$thumb_ids = array_pluck($request->except('id'), 'thumb_media_id');
		$material = EasyWeChat::material();
		foreach ($thumb_ids as $id) {
			$thumb = $material->get($id);
			file_put_contents(public_path() . '/upload/wechat' . $id, $thumb);
		}
		// $thumb = $material->get();
	}
}
