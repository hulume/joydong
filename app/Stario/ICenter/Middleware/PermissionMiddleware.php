<?php

namespace Star\ICenter\Middleware;

use Auth;
use Closure;

class PermissionMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $permission) {
		if (Auth::guest()) {
			return redirect('/login');
		}
		if (!$request->user()->can($permission)) {
			return response()->json([
				'error' => '您没有访问的权限',
			], 401);
		}
		return $next($request);
	}
}
