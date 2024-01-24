<?php

namespace App\Services\Auth;

class TokensPair
{
	public readonly TokenFactory $accessTokenFactory;

	public readonly TokenFactory $refreshTokenFactory;

	public static function make(TokenFactory $accessTokenFactory, TokenFactory $refreshTokenFactory): self
	{
		$instance = new self();
		$instance->accessTokenFactory = $accessTokenFactory;
		$instance->refreshTokenFactory = $refreshTokenFactory;

		return $instance;
	}
}
