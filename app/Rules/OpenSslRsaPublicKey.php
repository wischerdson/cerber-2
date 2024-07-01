<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

class OpenSslRsaPublicKey implements ValidationRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		try {
			$pkey = openssl_pkey_get_public($value);
			$details = openssl_pkey_get_details($pkey);

			if ($details) {
				return;
			}
		} catch (Throwable $th) {
		}

		$fail('The :attribute must be a valid openssl rsa public key');
	}
}
