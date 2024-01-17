<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * The list of the inputs that are never flashed to the session on validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	/**
	 * Register the exception handling callbacks for the application.
	 */
	public function register(): void
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	protected function invalidJson($request, ValidationException $exception)
	{
		$errors = [];

		foreach ($exception->validator->failed() as $field => $rule) {
			$errors[$field] = array_map(fn (string $rule) => Str::lower($rule), array_keys($rule));
		}

		$badRequestException = new BadRequestException();
		$badRequestException->errorReason = 'validation_failed';
		$badRequestException->errorDetails = $errors;
		$badRequestException->errorMessage = $exception->getMessage();

		return $badRequestException->render();
	}

	/**
	 * @throws \App\Exceptions\UnauthenticatedException
	 */
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		throw new UnauthenticatedException();
	}
}
