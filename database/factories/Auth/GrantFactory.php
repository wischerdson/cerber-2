<?php

namespace Database\Factories\Auth;

use App\Models\Auth\Grant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\Grant>
 */
class GrantFactory extends Factory
{
	/** @var string */
	protected $model = Grant::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'is_active' => fake()->boolean(),
			'created_at' => $createdAt = fake()->unixTime(),
			// 'updated_at' => fake()->dateTimeBetween($createdAt)
		];
	}
}
