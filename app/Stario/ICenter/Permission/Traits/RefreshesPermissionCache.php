<?php

namespace Star\ICenter\Permission\Traits;

use Star\ICenter\Permission\PermissionRegistrar;

trait RefreshesPermissionCache {
	public static function bootRefreshesPermissionCache() {
		static::created(function ($model) {
			$model->forgetCachedPermissions();
		});

		static::updated(function ($model) {
			$model->forgetCachedPermissions();
		});

		static::deleted(function ($model) {
			$model->forgetCachedPermissions();
		});
	}

	/**
	 *  Forget the cached permissions.
	 */
	public function forgetCachedPermissions() {
		app(PermissionRegistrar::class)->forgetCachedPermissions();
	}
}
