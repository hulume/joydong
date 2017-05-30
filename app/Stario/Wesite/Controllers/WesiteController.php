<?php

namespace Star\Wesite\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Carbon\Carbon;
use EasyWeChat;
use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use Star\Services\FileManager\UploadManager;
use Star\Utils\StarJson;
use Star\Wesite\WeMenu;

class WesiteController extends Controller {

	protected $menu;
	protected $upload;
	public function __construct(WeMenu $menu, UploadManager $upload) {
		$this->menu = $menu;
		$this->upload = $upload;
	}
	/**
	 * 对外入口，因微信js-sdk安卓低版本问题，单页面获取有问题，故：
	 * 访问页面就获取用户微信数据，当单页面跳至user页面时前端根据cookie、localStorage判断是否已绑定
	 * 控制器只返回cookie(保存refresnToken), 以及localStorage数据
	 * @return view
	 */
	public function pub(CookieJar $cookie) {
		$user = session('wechat.oauth_user'); // 拿到授权用户资料
		$user['id'] = 'Test must to be deleted'; // 需要删除
		if (empty($user)) {
			return StarJson::create(401);
		}
		$cookie->queue(
			'wesite_openid',
			$user['id'],
			864000,
			null,
			null,
			false,
			true// HttpOnly
		);
		return view('wesite::app');
	}
	public function summary(Request $request) {
		$from = Carbon::now()->subDays(6)->toDateString();
		$to = Carbon::now()->subDays(1)->toDateString();
		$stats = EasyWeChat::stats();
		$cumulate = $stats->userCumulate($from, $to);
		$summary = $stats->userShareSummary($from, $to);
		if ($request->input('type') == 'cumulate') {
			return $cumulate;
		}
		return $summary;
	}

	public function index() {

		$main = $this->menu->main()->orderBy('order')->get()->map(function ($item) {
			return [
				'id' => $item->id,
				'label' => $item->label,
				'type' => $item->type,
				'order' => $item->order,
				'icon' => $item->icon,
				'is_url' => $item->is_url,
				'link' => $item->link,
			];
		});
		$guide = $this->menu->guide()->orderBy('order')->get()->map(function ($item) {
			return [
				'id' => $item->id,
				'label' => $item->label,
				'type' => $item->type,
				'order' => $item->order,
				'icon' => $item->icon,
				'color' => $item->color,
				'is_url' => $item->is_url,
				'link' => $item->link,
			];
		});
		$theme = $this->menu->theme()->orderBy('order')->get()->map(function ($item) {
			return [
				'id' => $item->id,
				'type' => $item->type,
				'order' => $item->order,
				'is_url' => $item->is_url,
				'theme_img' => $item->theme_img,
				'link' => $item->link,
			];
		});
		$result = [
			'main' => $main,
			'guide' => $guide,
			'theme' => $theme,
		];
		return StarJson::create($result, 200);
	}

	public function store() {
		return $this->menu->create(request()->all());
	}

	public function update($id) {
		if (request()->input('do') === 'link') {
			$links = request()->input('link');
			$this->menu->find($id)->update(['link' => $links]);
			return StarJson::create('更新成功', 200);
		} else if (request()->input('do') === 'url') {
			$link = request()->input('url');
			$this->menu->find($id)->update(['link' => $link, 'is_url' => true]);
			return StarJson::create('更新成功', 200);
		} else {
			$this->menu->find($id)->update(request()->all());
			return StarJson::create('更新成功', 200);
		}
	}

	public function destroy($id) {
		return $this->menu->destroy($id);
	}

	// 处理图片上传
	public function uploadImg() {
		$img = request('file');
		$validator = \Validator::make(['file' => $img], ['file' => 'image']);
		if ($validator->fails()) {
			return StarJson::create(304);
		}
		$path = 'wesite/upload/' . date('Ymd');
		$result = $this->upload->store($img, $path);
		if (!$result['success']) {
			return StarJson::create(400);
		}
		return $result;
	}
	// 保存主题图设置
	public function saveTheme($value = '') {
		# code...
	}

	// 默认通过缓存读取，可以添加?get=force强行刷新
	public function material() {
		if (request()->input('get') == 'force' || !Cache::has('WeMaterials')) {
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
		// 定义缩略图存放路径，如果不存在则创建
		$path = public_path() . '/upload/wesite/';
		if (!is_dir($path)) {
			mkdir($path);
		}
		$index = -1;
		foreach ($materials as $item) {
			$index++;
			$thumb = $material->get($item['thumb_media_id']);
			$type = $this->findWxfmt($item['thumb_url']);
			file_put_contents($path . $item['thumb_media_id'] . '.' . $type, $thumb);
			$materials[$index]['thumb_url'] = url('upload/wesite/' . $item['thumb_media_id'] . '.' . $type);
			$id = $this->findId($item['url']);
			$materials[$index]['id'] = $id;
		}
		Cache::forever('WeMaterials', $materials);
		return $materials;
	}

	// 从微信thumb_url中解析出图片格式
	private function findWxfmt($url) {
		return explode('?wx_fmt=', $url)[1];
	}
	// 从微信url中解析出图文ID（未必是微信的ID，只做前端标识）
	private function findId($url) {
		preg_match('/sn=\w+/', $url, $match);
		return str_replace('sn=', '', $match)[0];
	}
}
