<?php

namespace Star\Wesite;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {

	protected $namespace = 'Star\Wesite\Controllers';

	public function boot() {

		parent::boot();
	}

	public function map() {
		$this->mapApiRoutes();
		$this->mapWebRoutes();
	}

	protected function mapWebRoutes() {
		Route::middleware('web')
			->namespace($this->namespace)
			->group(dirname(__FILE__) . '/routes/web.php');
	}

	protected function mapApiRoutes() {
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group(dirname(__FILE__) . '/routes/api.php');
	}
}
