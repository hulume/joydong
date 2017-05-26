<?php

namespace Star\Wechat\Controllers;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use EasyWeChat;
use Illuminate\Http\Request;

class WechatController extends Controller {
	// TODO 接口入缓存
	public function serve() {
		$server = EasyWeChat::server();
		return $server->serve();
	}
	public function user() {
		$user = session('wechat.oauth_user'); // 拿到授权用户资料
		return response()->json([
			'openid' => $user['id'],
			'name' => $user['name'],
			'nickname' => $user['nickname'],
			'avatar' => $user['avatar'],
		], 200);
	}

	// foreach ($data as $key => $item) {
	// 	$thumb = $material->get($item['thumb_media_id']);
	// 	$type = $this->findWxfmt($item['thumb_url']);
	// 	file_put_contents(public_path() . '/upload/wesite/' . $item['thumb_media_id'] . '.' . $type, $thumb);
	// 	$data[$key]['realpath'] = url('/upload/wesite/' . $item['thumb_media_id'] . '.' . $type);
	// }
	public function material() {
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
		// $thumbs = array_combine(array_column($materials, 'thumb_media_id'), array_column($materials, 'thumb_url'));
		$index = -1;
		foreach ($materials as $item) {
			$index++;
			$thumb = $material->get($item['thumb_media_id']);
			$type = $this->findWxfmt($item['thumb_url']);
			file_put_contents(public_path() . '/upload/wesite/' . $item['thumb_media_id'] . '.' . $type, $thumb);
			$materials[$index]['thumb_url'] = url('upload/wesite/' . $item['thumb_media_id'] . '.' . $type);
			$id = $this->findId($item['url']);
			$materials[$index]['id'] = $id;
		}
		return $materials;
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
	// 获取微信图片格式
	private function findWxfmt($url) {
		return explode('?wx_fmt=', $url)[1];
	}
	// 从微信url中解析出图文ID（未必是微信的ID，只做前端标识）
	private function findId($url) {
		preg_match('/sn=\w+/', $url, $match);
		return str_replace('sn=', '', $match)[0];
	}
}