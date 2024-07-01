<?php

namespace App\Exceptions;

class OpenSslPublicKeyInvalidException extends BadRequestException
{
	public ?string $errorReason = 'invalid_public_key';

	public ?string $errorMessage = 'Body content must be a valid openssl rsa public key';
}
