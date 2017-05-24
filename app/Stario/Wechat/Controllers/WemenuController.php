<?php

namespace Star\Wechat\Controllers;

use App\Http\Controllers\Controller;
use EasyWeChat;
use Star\Utils\StarJson;
use Star\Wechat\WeMenu;

class WemenuController extends Controller {

	protected $menu;
	public function __construct(WeMenu $menu) {
		$this->menu = $menu;
	}

	public function index() {
		$main = $this->menu->main()->orderBy('order')->get();
		$guide = $this->menu->guide()->orderBy('order')->get();
		$theme = $this->menu->theme()->orderBy('order')->get();
		$result = [
			'main' => $main->toArray(),
			'guide' => $guide->toArray(),
			'theme' => $guide->toArray(),
		];
		return StarJson::create($result, 200);
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

	// 默认通过缓存读取，可以添加?get=force强行刷新
	public function material() {
		if (request()->input('get') == 'force' && !Cache::has('WeMaterials')) {
			return $this->getMaterial();
		}
		return Cache::get('WeMaterials');
	}

	private function getMaterial() {
		$material = EasyWeChat::material();
		$raw = $material->lists('news');
		$total = $raw->total_count;
		$list = $raw->item;
		$collection = array_column(array_column($list, 'content'), 'news_item');
		$materials = [];
		// 双层遍历，解决微信图文消息集合（一条图文消息里面有多条）
		foreach ($collection as $collect) {
			foreach ($collect as $item) {
				$materials[] = array_only($item, ['title', 'author', 'digest', 'thumb_media_id', 'thumb_url', 'url', 'media_id']);
			}
		}
		$index = -1;
		foreach ($materials as $item) {
			$index++;
			$thumb = $material->get($item['thumb_media_id']);
			$type = $this->findWxfmt($item['thumb_url']);
			file_put_contents(public_path() . '/upload/wesite/' . $item['thumb_media_id'] . '.' . $type, $thumb);
			$materials[$index]['thumb_url'] = url('upload/wesite/' . $item['thumb_media_id'] . '.' . $type);
		}
		Cache::forever('WeMaterials', $materials);
		return $materials;
	}

	private function findWxfmt($url) {
		return explode('?wx_fmt=', $url)[1];
	}
}
