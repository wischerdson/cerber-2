<?php

namespace App\Services\Auth\ResponseTypes;

use App\Services\Auth\TokensPair;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponseType
{
	protected TokensPair $tokensPair;

	abstract public function makeHttpResponse(): Response;

	public function setTokensPair(TokensPair $pair)
	{
		$this->tokensPair = $pair;
	}
}
