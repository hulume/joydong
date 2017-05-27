<?php

Route::group([
	'middleware' => ['auth:api_manager', 'permission:manage_wx'],
], function () {
	Route::resource('wesite/menu', 'WesiteController', ['except' => ['create', 'show']]);
	Route::get('wesite/material', 'WesiteController@material');
	Route::post('wesite/upload', 'WesiteController@uploadImg');
});

// 对外部访客提供
Route::get('wesite', 'WesiteController@index')->middleware(['api', 'cors']);