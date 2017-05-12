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
		// return $material->get('v0cHk6qiUS5WuU66AHpEKC4yBhpgN1p86mAjDr-7T54');
	}
	public function menu() {
		$menu = EasyWeChat::menu();
		// return $menu->current()->selfmenu_info;
		$raw = '
{"button":[{"name":"\u533b\u7597\u670d\u52a1","sub_button":{"list":[{"type":"text","name":"\u533b\u9662\u7b80\u4ecb","value":"todo"},{"type":"text","name":"\u7279\u8272\u4e13\u79d1","value":"todo"},{"type":"text","name":"\u54c1\u724c\u5efa\u8bbe","value":"todo"}]}},{"name":"\u4fbf\u6377\u5c31\u533b","sub_button":{"list":[{"type":"text","name":"\u5c31\u533b\u6307\u5357","value":"todo"},{"type":"text","name":"\u5987\u513f\u79d1","value":"todo"},{"type":"text","name":"\u624b\u5916\u79d1","value":"todo"}]}}]}';
		return json_decode($raw, true);
	}
	public function summary(Request $request) {

		$from = Carbon::now()->subDays(7)->toDateString();
		$to = Carbon::now()->subDays(1)->toDateString();
		$stats = EasyWeChat::stats();
		// $cumulate = $stats->userCumulate($from, $to);
		// $summary = $stats->userShareSummary($from, $to);
		$cumulate = response()->json([
			"list" => [["ref_date" => "2017-05-03", "user_source" => 0, "cumulate_user" => 6], ["ref_date" => "2017-05-04", "user_source" => 0, "cumulate_user" => 6], ["ref_date" => "2017-05-05", "user_source" => 0, "cumulate_user" => 600], ["ref_date" => "2017-05-06", "user_source" => 0, "cumulate_user" => 6], ["ref_date" => "2017-05-07", "user_source" => 0, "cumulate_user" => 699], ["ref_date" => "2017-05-08", "user_source" => 0, "cumulate_user" => 6], ["ref_date" => "2017-05-09", "user_source" => 0, "cumulate_user" => 6]],
		], 200);
		$summary = response()->json([
			'list' => [],
		]);
		if ($request->input('type') == 'cumulate') {
			return $cumulate;
		}
		return $summary;
	}
}