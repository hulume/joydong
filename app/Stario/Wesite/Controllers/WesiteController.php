<?php

namespace Star\Wesite\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Carbon\Carbon;
use EasyWeChat;
use GuzzleHttp\Client;
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
	 * 对外入口，Route中的middleware限制只能在微信访问
	 * 访问自动获取openid并放到cookie中(session模式)
	 * @return view
	 */
	public function pub(CookieJar $cookie) {
		$user = session('wechat.oauth_user'); // 拿到授权用户资料
		if (empty($user)) {
			return StarJson::create(401);
		}
		$cookie->queue(
			'wesite_openid',
			$user['id']
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

	// 默认通过缓存读取，可以添加?get=force强行刷新
	public function material() {
		if (request()->input('get') == 'force' || !Cache::has('WeMaterials')) {
			return $this->getMaterial();
		}
		return Cache::get('WeMaterials');
	}

	// 获取微信公众号图文素材
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
		$path = public_path() . '/upload/wesite/';
		$index = -1;
		foreach ($materials as $item) {
			$index++;
			// try {
			// 	$thumb = $material->get($item['thumb_media_id']);
			// } catch (HttpException $e) {
			// 	$temp = EasyWeChat::material_temporary();
			// 	$thumb = $temp->getStream($item['thumb_media_id']);
			// }
			// file_put_contents($path . $item['thumb_media_id'] . '.' . $type, $thumb);
			$type = $this->findWxfmt($item['thumb_url']);
			$id = $this->findId($item['url']);
			$filename = $id . '.' . $type;
			$materials[$index]['thumb_url'] = url('upload/wesite/' . $filename);
			$materials[$index]['id'] = $id;
			$this->downFile($item['thumb_url'], $path . $filename);
		}
		Cache::forever('WeMaterials', $materials);
		return $materials;
	}

	private function downFile($fromUrl, $filename) {
		$http = new Client();
		$http->request('GET', $fromUrl, ['sink' => $filename]);
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
