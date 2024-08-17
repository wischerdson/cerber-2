<?php

namespace App\Services\Encryption\Exceptions;

use App\Exceptions\BadRequestException;
use App\Services\Encryption\RequestEncrypter;

class HandshakeNotFoundException extends BadRequestException
{
	public function getErrorMessage()
	{
		$header = RequestEncrypter::HTTP_HEADER_HANDSHAKE_ID;

		return "The HTTP header \"{$header}\" is missing or has an invalid identifier";
	}
}
