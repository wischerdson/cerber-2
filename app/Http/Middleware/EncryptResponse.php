<?php

namespace App\Http\Middleware;

use App\Services\RsaEncryption;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EncryptResponse
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$response = $next($request);

		$response->setContent(
			app(RsaEncryption::class)
				->encrypt($response->getContent())
		);

		$response->headers->set('X-Encrypted', '1');

		return $response;
	}
}
