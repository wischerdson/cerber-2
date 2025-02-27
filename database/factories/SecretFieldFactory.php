<?php

namespace Database\Factories;

use App\Models\SecretField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecretFieldFactory>
 */
class SecretFieldFactory extends Factory
{
	/** @var class-string<\Illuminate\Database\Eloquent\Model> */
	protected $model = SecretField::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$multiline = fake()->boolean();

		return [
			'label' => fake()->words(
				fake()->randomElement([1, 2, 3]),
				true
			),
			'short_description' => fake()->randomElement([null, fake()->text(100)]),
			'value' => $multiline ? fake()->paragraphs(3, true) : fake()->text(100),
			'multiline' => $multiline,
			'secure' => fake()->boolean(),
			'sort' => 1
		];
	}

	public function multiline(bool $yes = true): Factory
	{
		return $this->state(fn (array $attrs) => [
			'multiline' => $yes
		]);
	}

	public function secure(bool $yes = true): Factory
	{
		return $this->state(fn (array $attrs) => [
			'secure' => $yes
		]);
	}
}
