<?php

namespace Database\Factories\Auth;

use App\Models\Auth\PasswordGrant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\PasswordGrant>
 */
class PasswordGrantFactory extends Factory
{
	/** @var string */
	protected $model = PasswordGrant::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'login' => fake()->userName(),
			'password' => fake()->password()
		];
	}
}
