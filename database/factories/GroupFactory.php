<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
	/** @var class-string<\Illuminate\Database\Eloquent\Model> */
	protected $model = Group::class;

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
