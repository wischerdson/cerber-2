<?php

namespace App\Exceptions;

class NodeNotFoundException extends BadRequestException
{
	public int $statusCode = 404;
}
