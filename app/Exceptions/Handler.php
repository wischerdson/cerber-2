<?php

namespace App\Exceptions;

use App\Exceptions\ValidationException as CustomValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
		throw CustomValidationException::invalidJson($request, $exception);
	}

	/**
	 * @throws \App\Exceptions\UnauthenticatedException
	 */
	protected function unauthenticated($request, AuthenticationException $exception)
	{
		throw new UnauthenticatedException();
	}
}
