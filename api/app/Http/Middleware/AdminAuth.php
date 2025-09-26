<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuthAdmin as AuthService;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = AuthService::admin();

		if(empty($admin)){
			return response()->json([
				'code'    => 401,
				'message' => '失败',
			]);
		}

        return $next($request);
    }
}
