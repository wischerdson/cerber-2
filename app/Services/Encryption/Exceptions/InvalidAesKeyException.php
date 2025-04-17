<?php

namespace App\Services\Encryption\Exceptions;

use App\Exceptions\BadRequestException;
use App\Services\Encryption\RequestEncrypter;

class InvalidAesKeyException extends BadRequestException
{
	public function __construct()
	{
		$header = RequestEncrypter::HTTP_HEADER_KEY;

		parent::__construct("HTTP header \"{$header}\" is missing or key is invalid");
	}
}
