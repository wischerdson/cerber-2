<?php

namespace App\Services\Auth\Exceptions;

use App\Exceptions\BadRequestException;

class AuthCredentialsErrorException extends BadRequestException
{
	protected int $httpCode = 401;
}
