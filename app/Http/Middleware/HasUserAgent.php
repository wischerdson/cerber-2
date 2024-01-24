<?php

namespace App\Http\Middleware;

use App\Exceptions\UserAgentRequiredException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasUserAgent
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (!$request->hasHeader('User-Agent')) {
			throw new UserAgentRequiredException();
		}

		return $next($request);
	}
}
