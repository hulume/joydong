<?php
Route::get('wesite', function () {
	return view('wesite::app');
});

/*
Wechat
 */
// Route::group(['prefix' => 'wechat', 'middleware' => 'auth'], function () {
// 	Route::any('/', '\Star\Wechat\Controllers\WechatController@serve');
// 	Route::get('user', '\Star\Wechat\Controllers\WechatController@user')->middleware('wechat.oauth');
// 	Route::get('material', '\Star\Wechat\Controllers\WechatController@material');
// 	Route::get('summary', '\Star\Wechat\Controllers\WechatController@summary');
// });