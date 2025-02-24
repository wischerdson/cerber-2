<?php

namespace Database\Factories;

use App\Models\SecretGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecretGroup>
 */
class SecretGroupFactory extends Factory
{
	/** @var class-string<\Illuminate\Database\Eloquent\Model> */
	protected $model = SecretGroup::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => fake()->words(
				fake()->randomElement([1, 2, 3, 4]),
				true
			),
			'description' => fake()->randomElement([null, fake()->text(255)]),
			'created_at' => fake()->unixTime()
		];
	}
}
