<?php

namespace App\Http\Middleware;

use App\Exceptions\HandshakeNotFound;
use App\Models\Handshake;
use Closure;
use Illuminate\Http\Request;
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

		dd(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

		if (!$request->header('X-Encrypted')) {
			return $next($request);
		}

		$handshake = Handshake::findOr(
			$request->header('X-Handshake-ID'),
			fn () => throw new HandshakeNotFound()
		);

		openssl_private_decrypt(base64_decode($request->getContent()), $decrypted, $handshake->server_private_key, OPENSSL_PKCS1_OAEP_PADDING);

		dd($decrypted);
	}
}
