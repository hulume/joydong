<?php

use App\Services\Identify\Identify;
use Star\Services\Weather\WeatherClient;

// 手机验证用
Route::post('signup', 'Api\AuthController@signup');
Route::post('getsms', 'Api\SmsController@authcode');
Route::post('login', 'Auth\LoginController@login');

/**
 * 后台管理部分
 */
Route::group([
	'middleware' => ['auth:api_manager'],
	'namespace' => 'Api',
], function () {
	Route::group(['prefix' => 'home', 'middleware' => 'auth.user'], function () {
		Route::get('statistics', 'HomeController@index');
		Route::get('/', 'HomeController@info');
		Route::post('/', 'HomeController@update');
		Route::get('menu', 'HomeController@menu');
		Route::post('avatar', 'HomeController@avatar');
		Route::post('changePassword', 'HomeController@changePassword');
		Route::post('changeMobile', 'HomeController@changeMobile');
		Route::post('updateMeInfo', 'HomeController@updateMeInfo');
		Route::get('timeline', 'HomeController@timeline');
		// Notifications
		Route::get('notification', 'NotificationController@index');
		Route::get('notification/clear', 'NotificationController@clear');
		Route::post('notification/mark', 'NotificationController@mark');
		Route::post('notification/delete', 'NotificationController@delete');
	});

	Route::get('weather', function () {
		return WeatherClient::get();
	});
	Route::get('checkPid', function () {
		return Identify::parse(request('pid'));
	});

	// Export Excel
	Route::post('users/excel', 'UserController@export');

	// User
	Route::get('user', 'UserController@index');
	Route::post('user/delete', 'UserController@destroy');
	Route::post('user/create', 'UserController@store');
	Route::post('user/update', 'UserController@update');

	Route::resource('unit', 'UnitController', ['except' => ['create', 'show']]);
	Route::resource('permission', 'PermissionController', ['except' => ['create', 'show']]);

	// 流动人口管理
	// Route::resource('pop', 'PopController', ['except' => ['create', 'show']]);
	// Route::post('lis', 'LisController@all');
});

/**
 * 患者部分
 */
Route::group([
	'middleware' => ['auth:api'],
	'namespace' => 'Api',
], function () {
	Route::group(['prefix' => 'patient'], function () {
		Route::get('/', function () {
			return 'asdf';
		});
	});
});
