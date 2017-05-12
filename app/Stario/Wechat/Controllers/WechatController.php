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
	public function material() {
		$material = EasyWeChat::material();
		return $material->lists('news', 0, 20);
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
}