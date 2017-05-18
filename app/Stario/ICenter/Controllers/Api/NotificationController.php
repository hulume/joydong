<?php

namespace Star\ICenter\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Star\ICenter\Repository\Eloquent\NotificationsRepo;
use Star\utils\StarJson;

class NotificationController extends Controller {
	protected $notification;

	public function __construct(NotificationsRepo $notification) {
		$this->notification = $notification;
		// $this->middleware('auth.user');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$request = request()->input('per_page');
		$user = auth()->user();
		$notifications = $user->notifications()->paginate($request);
		// 定期清理，只保留200条
		$unreadNotifications = $user->unreadNotifications->take(200)->map(function ($item) {
			return [
				'type' => $item->notifiable_type,
				'data' => $item->data,
			];
		});
		// 返回包含一个独立的未读数据集合
		return collect($this->notification->transform($notifications))->merge(['unreadNotifications' => $unreadNotifications]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}
	/**
	 * 将消息设置为已读
	 */
	public function mark() {
		$ids = explode(',', request()->input('ids'));
		$user = auth()->user();
		foreach ($ids as $id) {
			$user->notifications()->where('id', $id)->update(['read_at' => Carbon::now()]);
		}
	}
	/**
	 * 消息删除（单条、批量）处理
	 */
	public function delete() {
		// 前端传来的id有可能是字符串或数组
		if (is_string(request()->input('ids'))) {
			$ids = explode(',', request()->input('ids'));
		} else {
			$ids = request()->input('ids');
		}
		$user = auth()->user();
		foreach ($ids as $id) {
			$user->notifications()->where('id', $id)->delete();
		}
		return StarJson::create('成功删除', 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function clear() {
		$user = auth()->user();
		if ($user->notifications()->delete()) {
			return StarJson::create('删除消息成功', 200);
		}
		return StarJson::create(403);
	}
}
