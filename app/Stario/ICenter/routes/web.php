<?php

Route::get('home', function () {
	return redirect('/dashboard');
});
// Auth
Route::get('login', 'Auth\LoginController@showLoginform')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::get('reset', 'Auth\ResetPasswordController@reset');
Route::get('logout', 'Auth\LoginController@logout');

/* Dashboard Index */
Route::group(['prefix' => '/dashboard', 'middleware' => ['auth', 'auth.status']], function () {
	Route::get('{path?}', 'HomeController@index')->where('path', '[\/\w\.-]*');
});
