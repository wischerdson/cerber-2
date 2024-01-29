<?php

namespace App\Services\Auth;

class TokensPair
{
	public function __construct(
		public readonly AccessTokenFactory $accessTokenFactory,
		public readonly RefreshTokenFactory $refreshTokenFactory
	)
	{

	}

	public function issueAccessToken(): string
	{
		return $this->accessTokenFactory->issueToken()->toString();
	}

	public function issueRefreshToken(): string
	{
		return $this->refreshTokenFactory->issueToken()->toString();
	}
}
