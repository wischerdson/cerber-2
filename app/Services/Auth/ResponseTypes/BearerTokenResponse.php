<?php

namespace App\Services\Auth\ResponseTypes;

use Illuminate\Http\JsonResponse;

class BearerTokenResponse extends AbstractResponseType
{
	public function makeHttpResponse(): JsonResponse
	{
		$responseParams = [
			'token_type'   => 'Bearer',
			'access_token' => (string) $this->tokensPair->accessTokenFactory->issueToken(),
			'refresh_token' => (string) $this->tokensPair->refreshTokenFactory->issueToken(),
		];

		return response()
			->header('pragma', 'no-cache')
			->header('cache-control', 'no-store')
			->json($responseParams);
	}
}
