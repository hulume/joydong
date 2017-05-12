<?php

Route::get('wechat/user', '\Star\Wechat\Controllers\WechatController@user')->middleware('wechat.oauth');
Route::get('wechat/material', '\Star\Wechat\Controllers\WechatController@material');
Route::get('wechat/summary', '\Star\Wechat\Controllers\WechatController@summary');
Route::get('wechat/menu', '\Star\Wechat\Controllers\WechatController@menu');
