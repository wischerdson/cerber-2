<?php

namespace App\Services\Encryption\Exceptions;

use App\Exceptions\BadRequestException;
use App\Services\Encryption\RequestEncrypter;

class HandshakeNotFoundException extends BadRequestException
{
	public function __construct()
	{
		$header = RequestEncrypter::HTTP_HEADER_HANDSHAKE_ID;

		parent::__construct("The HTTP header \"{$header}\" is missing or has an invalid identifier");
	}
}
