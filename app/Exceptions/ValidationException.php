<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as IlluminateValidationException;
use Illuminate\Support\Str;

class ValidationException extends BadRequestException
{
	public static function invalidJson(Request $request, IlluminateValidationException $exception)
	{
		$errors = [];

		foreach ($exception->validator->failed() as $field => $rule) {
			$errors[$field] = array_map(fn (string $rule) => Str::lower($rule), array_keys($rule));
		}

		return self::message($exception->getMessage())
			->reason('validation_failed')
			->details($errors);
	}
}
