<?php

namespace App\Http\Middleware;

use App\Services\Encryption\RequestEncrypter;
use Closure;
use Illuminate\Encryption\Encrypter as AesEncrypter;
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

		if (config('app.disable_http_encryption')) {
			return $response;
		}

		$encrypter = $this->getRequestEncrypter();

		$response->setContent(
			$encrypter->encrypt($response->getContent())
		);

		$aesKey = $encrypter->getAesEncrypter()->getKey();

		$response->headers->set('X-Key', $encrypter->getRsaEncrypter()->encrypt($aesKey));
		$response->headers->set('X-Encrypted', '1');

		return $response;
	}

	private function getRequestEncrypter(): RequestEncrypter
	{
		$requestEncrypter = app(RequestEncrypter::class);

		$aesKey = AesEncrypter::generateKey(RequestEncrypter::$cipher);
		$aesEncrypter = new AesEncrypter($aesKey, RequestEncrypter::$cipher);

		$requestEncrypter->setAesEncrypter($aesEncrypter);

		return $requestEncrypter;
	}
}
