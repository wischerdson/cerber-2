<?php

namespace App\Exceptions;

use App\Services\RsaEncryption;

class HandshakeNotFound extends BadRequestException
{
	public function getErrorMessage()
	{
		$header = RsaEncryption::HTTP_HEADER_HANDSHAKE_ID;

		return "The HTTP header \"{$header}\" is missing or has an invalid identifier";
	}
}
