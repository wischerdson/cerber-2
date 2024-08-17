<?php

namespace App\Http\Middleware;

use App\Services\Encryption\RequestEncrypter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;

class DecryptRequest
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (!$request->hasHeader('X-Encrypted')) {
			return $next($request);
		}

		return $next($this->decryptRequest($request));
	}

	private function decryptRequest(Request $request): Request
	{
		$content = app(RequestEncrypter::class)->decrypt($request->getContent());

		$json = $request->isJson() ? (array) json_decode($content, true) : [];

		$symfonyRequest = new SymfonyRequest(
			$request->query(),
			$json,
			$request->attributes->all(),
			$request->cookie(),
			[],
			$request->server(),
			$content
		);

		return Request::createFromBase($symfonyRequest);
	}
}
