<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
	return redirect('http://stario.net');
});

/*
Wechat
 */
Route::group(['prefix' => 'wechat', 'middleware' => 'auth'], function () {
	Route::any('/', '\Star\Wechat\Controllers\WechatController@serve');
	Route::get('user', '\Star\Wechat\Controllers\WechatController@user')->middleware('wechat.oauth');
	Route::get('material', '\Star\Wechat\Controllers\WechatController@material');
	Route::get('summary', '\Star\Wechat\Controllers\WechatController@summary');
});
