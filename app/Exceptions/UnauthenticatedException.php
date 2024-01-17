<?php

namespace App\Exceptions;

class UnauthenticatedException extends BadRequestException
{
	public int $statusCode = 401;
}
