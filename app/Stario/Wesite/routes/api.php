<?php

Route::group([
	'middleware' => ['auth:api_manager', 'permission:manage_wx'],
], function () {
	Route::resource('wesite/menu', 'WesiteController', ['except' => ['create', 'show']]);
	Route::get('wesite/material', 'WesiteController@material');
	Route::post('wesite/upload', 'WesiteController@uploadImg');
	Route::get('wesite/summary', 'WesiteController@summary');
});

// 对外部访客提供
Route::group([
	'middleware' => ['api'],
], function () {
	Route::get('wesite', 'WesiteController@index');
	// Route::post('wesite/login', 'AuthController@login');
	Route::get('wesite/check', 'AuthController@checkIfBound');
	Route::post('wesite/bind', 'AuthController@bind');
	Route::post('wesite/sms', 'SmsController@authcode');
});
