<?php

// 对内部管理提供
Route::group([
	'middleware' => ['auth:api_manager', 'permission:manage_wx'],
], function () {
	Route::resource('wesite/menu', 'WesiteController', ['except' => ['create', 'show']]);
	Route::get('wesite/material', 'WesiteController@material');
	Route::post('wesite/upload', 'WesiteController@uploadImg');
	Route::get('wesite/summary', 'WesiteController@summary');
});
// 老年人及流动人口管理
Route::group([
	'middleware' => ['auth:api_manager', 'permission:manage_pops', 'permission:manage_aged'],
], function () {
	Route::resource('patient', 'PatientController');
	Route::get('archive/{name}', 'ArchiveController@show');
});

// 对外部访客提供
Route::group([
	'middleware' => ['api'],
], function () {
	Route::get('wesite', 'WesiteController@index');
	Route::post('wesite/oauth/token', 'AuthController@getToken');
	Route::post('wesite/oauth/refresh', 'AuthController@refreshToken');
	Route::get('wesite/oauth/logout', 'AuthController@logout');
	Route::get('wesite/check', 'AuthController@checkIfBound');
	Route::post('wesite/bind', 'AuthController@bind');
	Route::post('wesite/sms', 'SmsController@authcode');
});

// 对lis数据上传服务器提供
Route::get('wesite/oauth/client_token', 'AuthController@getClientToken');
Route::post('wesite/lisUpload', 'HospitalController@lis')->middleware('auth.client');
// 公卫数据上传
Route::post('wesite/publicHealth/upload', 'PublicHealthController@upload');
// Route::get('publicHealth/reporter', 'PublicHealthController@reporter');
