<?php

namespace App\Exceptions;

class UnauthenticatedException extends BadRequestException
{
	protected int $httpCode = 401;
}
