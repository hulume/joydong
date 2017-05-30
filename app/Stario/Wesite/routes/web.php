<?php
// 微网站对外入口,微网站只调取openid
Route::get('wesite', 'WesiteController@pub');
// ->middleware('wechat.oauth:snsapi_base');