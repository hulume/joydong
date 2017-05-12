<?php

namespace Star\ICenter\Permission;

use Illuminate\Support\ServiceProvider;
use Star\ICenter\Permission\Contracts\Permission as PermissionContract;
use Star\ICenter\Permission\Contracts\Role as RoleContract;

class PermissionServiceProvider extends ServiceProvider {
	/**
	 * @param \Star\ICenter\Permission\PermissionRegistrar $permissionLoader
	 */
	public function boot(PermissionRegistrar $permissionLoader) {
		$this->publishes([
			__DIR__ . '/permissions_menu.php' => $this->app->configPath() . '/' . 'permissions_menu.php',
		], 'config');

		// Publish the migration
		$timestamp = '1949_07_15_100001';
		$this->publishes([
			__DIR__ . '/migrations/create_permission_tables.php.stub' => $this->app->databasePath() . '/migrations/' . $timestamp . '_create_permission_tables.php',
		], 'migrations');

		$this->registerModelBindings();

		$permissionLoader->registerPermissions();
	}

	public function register() {
		$this->mergeConfigFrom(
			__DIR__ . '/permissions_menu.php',
			'permissions_menu'
		);

	}

	protected function registerModelBindings() {
		// $config = $this->app->config['laravel-permission.models'];

		$this->app->bind(PermissionContract::class, \Star\ICenter\Permission\Models\Permission::class);
		$this->app->bind(RoleContract::class, \Star\ICenter\Permission\Models\Role::class);
	}

}
