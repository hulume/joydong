<?php

namespace Star\Wechat;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {

	protected $namespace = 'Star\Wechat\Controllers';

	public function boot() {

		parent::boot();
	}

	public function map() {
		$this->mapApiRoutes();
	}

	protected function mapApiRoutes() {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(dirname(__FILE__) . '/routes/api.php');
	}
}
