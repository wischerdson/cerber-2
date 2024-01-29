<?php

namespace App\Mixins;

/**
 * @mixin \Illuminate\Support\Str
 */
class Str
{
	/**
	 * Examples:
	 * Str::plural_ru(2, ['', 'а', 'ов'], 'кот') -> 'кота'
	 * Str::plural_ru(141, ['кошка', 'кошки', 'кошек']) -> 'кошка'
	 * Str::plural_ru(11, ['кошка', 'кошки', 'кошек']) -> 'кошек'
	 */
	public function plural_ru(): callable
	{
		return function ($number, $forms, $base = '') {
			$rest = $number % 10;
			$number = (int) substr($number, -2, 2);

			if ($rest === 1 && $number != 11) {
				return $base . $forms[0];
			}

			if (in_array($rest, [2, 3, 4]) && !in_array($number, [12, 13, 14])) {
				return $base . $forms[1];
			}

			return $base . $forms[2];
		};
	}

	/**
	 * Examples:
	 * Str::sliceUrlProtocol('https://apple.com/macbook') -> 'apple.com/macbook'
	 * Str::sliceUrlProtocol('file:///Users/osmuhin/Documents/pass.txt') -> '/Users/osmuhin/Documents/pass.txt'
	 */
	public function sliceUrlProtocol(): callable
	{
		return fn (string $url) => preg_replace("/^\w+:\/\//", '', $url);
	}

	/**
	 * Examples:
	 * Str::randHex() -> 'f03c'
	 * Str::randHex(2) -> '9b'
	 */
	public function randHex(): callable
	{
		$ch = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f'];

		return function ($length = 4) use ($ch) {
			$string = '';

			while (strlen($string) < $length) {
				$string = $ch[rand(0, 15)] . $string;
			}

			return $string;
		};
	}
}
