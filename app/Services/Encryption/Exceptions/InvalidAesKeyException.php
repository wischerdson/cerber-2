<?php

namespace App\Services\Encryption\Exceptions;

use App\Exceptions\BadRequestException;
use App\Services\Encryption\RequestEncrypter;

class InvalidAesKeyException extends BadRequestException
{
	public function getErrorMessage()
	{
		$header = RequestEncrypter::HTTP_HEADER_KEY;

		return "HTTP header \"{$header}\" is missing or key is invalid";
	}
}
