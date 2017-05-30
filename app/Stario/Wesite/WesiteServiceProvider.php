<?php
namespace Star\Wesite;

use Illuminate\Support\ServiceProvider;

class WesiteServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		// Publish the migration
		$timestamp = '1949_07_15_100000';
		$this->publishes([
			__DIR__ . '/resources/migrations/create_wesite_table.php.stub' => $this->app->databasePath() . '/migrations/' . $timestamp . '_create_wesite_table.php',
		], 'migrations');

		$this->publishes([
			__DIR__ . '/public' => public_path('vendor/wesite'),
		], 'wesite');

		// $this->loadRoutesFrom(__DIR__ . '/routes.php');
		$this->loadViewsFrom(__DIR__ . '/resources/views', 'wesite');
	}
}
