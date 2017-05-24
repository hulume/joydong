<?php

Route::group([
	// 'middleware' => ['auth:api_manager', 'permission:manage_wx'],
], function () {
	Route::resource('wechat/menu', 'WemenuController', ['except' => ['create', 'show']]);
	Route::get('wechat/material', 'WemenuController@material');
});