<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BadRequestException extends Exception
{
	protected ?string $reason = null;

	protected ?array $details = null;

	protected int $httpCode = 422;

	public static function message(string $message): self
	{
		return new static($message);
	}

	public function report(): bool
	{
		return true;
	}

	/**
	 * Render the exception as an HTTP response.
	 */
	public function render(): JsonResponse
	{
		$responseData = [
			'status' => 'error',
			'error_reason' => $this->reason ?: $this->guessErrorReason()
		];

		if ($message = $this->message) {
			$responseData['message'] = $message;
		}

		if (($details = $this->details) !== null) {
			$responseData['details'] = $details;
		}

		return response()->json($responseData, $this->httpCode);
	}

	/**
	 * Set the reason for the bad request.
	 *
	 * This is a technical name, it should have only letters of the Latin alphabet,
	 * numbers and the sign '_'.
	 */
	public function reason(string $reason): self
	{
		$this->reason = $reason;

		return $this;
	}

	public function httpCode(int $code): self
	{
		$this->httpCode = $code;

		return $this;
	}

	public function details(array $details): self
	{
		$this->details = $details;

		return $this;
	}

	private function guessErrorReason()
	{
		$exploded = explode('\\', static::class);
		$className = array_pop($exploded);

		return Str::snake(preg_replace("/Exception$/", '', $className));
	}
}
