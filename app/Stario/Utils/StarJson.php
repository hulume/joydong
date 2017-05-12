<?php
namespace Star\utils;

/**
 * 生成Json静态类
 * 使用方法：
 * StarJson::create(STATUS_CODE)
 * 状态码必须传入
 * 状态码为200/403时可以自定义返回结果
 */
class StarJson {

	private static $data;
	private static $status;

	public static function create(...$params) {
		foreach ($params as $param) {
			if (is_string($param) || is_array($param)) {
				self::$data = $param;
			} elseif (is_int($param)) {
				self::$status = $param;
			}
		}
		switch (self::$status) {
		case 200:
			if (is_null(self::$data)) {
				return response()->json('操作成功', 200);
			}
			return response()->json(['data' => self::$data], 200);
			break;
		case 304:
			return response()->json(['error' => '更新失败'], 304);
			break;
		case 400:
			return response()->json(['error' => '请求错误'], 400);
			break;
		case 401:
			return response()->json(['error' => '认证失败'], 401);
			break;
		case 403:
			if (is_null(self::$data)) {
				return response()->json(['error' => '操作失败'], 403);
			}
			return response()->json(['error' => self::$data], 403);
			break;
		case 404:
			return response()->json(['error' => '资源未找到'], 404);
			break;
		case 406:
			return response()->json(['error' => '请求的格式有误'], 406);
			break;
		case 500:
			return response()->json(['error' => '服务器错误'], 500);
			break;
		default:
			return response()->json(['error' => '资源未找到'], 404);
			break;
		}
	}
}