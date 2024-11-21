<?php

namespace App\Services\HtmlMetaCrawler\Private;

class Str
{
	protected static array $camelCache = [];

	protected static array $studlyCache = [];

	public static function camel(string $value): string
	{
		if (isset(static::$camelCache[$value])) {
			return static::$camelCache[$value];
		}

		return static::$camelCache[$value] = lcfirst(static::studly($value));
	}

	public static function studly(string $value): string
	{
		$key = $value;

		if (isset(static::$studlyCache[$key])) {
			return static::$studlyCache[$key];
		}

		$words = explode(' ', static::replace(['-', '_'], ' ', $value));

		$studlyWords = array_map(fn ($word) => static::ucfirst($word), $words);

		return static::$studlyCache[$key] = implode($studlyWords);
	}

	/**
	 * Replace the given value in the given string.
	 *
	 * @param  string|string[]  $search
	 * @param  string|string[]  $replace
	 * @param  string|string[]  $subject
	 * @return string|string[]
	 */
	public static function replace(
		string|array $search,
		string|array $replace,
		string|array $subject,
		bool $caseSensitive = true): string|array
	{
		return $caseSensitive ?
			str_replace($search, $replace, $subject) :
			str_ireplace($search, $replace, $subject);
	}

	/**
	 * Make a string's first character uppercase.
	 *
	 * @param  string  $string
	 * @return string
	 */
	public static function ucfirst(string $string): string
	{
		return static::upper(static::substr($string, 0, 1)).static::substr($string, 1);
	}

	/**
	 * Convert the given string to upper-case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function upper(string $value): string
	{
		return mb_strtoupper($value, 'UTF-8');
	}

	/**
	 * Convert the given string to lower-case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function lower(string $value): string
	{
		return mb_strtolower($value, 'UTF-8');
	}

	/**
	 * Returns the portion of the string specified by the start and length parameters.
	 */
	public static function substr(string $string, int $start, int $length = null, string $encoding = 'UTF-8'): string
	{
		return mb_substr($string, $start, $length, $encoding);
	}
}
