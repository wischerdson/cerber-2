<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/** @var string */
	protected $model = User::class;

	private int $createdAt;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'first_name' => fake()->firstName(),
			'last_name' => fake()->lastName(),
			'email' => fake()->unique()->safeEmail(),
			'timezone' => $timezone = fake()->timezone(),
			'timezone_offset' => now($timezone)->utcOffset(),
			'is_admin' => fake()->boolean(),
			'created_at' => $this->createdAt = fake()->unixTime()
		];
	}

	public function asAdmin(): Factory
	{
		return $this->state(fn () => ['is_admin' => true]);
	}

	public function asNotAdmin(): Factory
	{
		return $this->state(fn () => ['is_admin' => false]);
	}

	public function asDeleted(): Factory
	{
		return $this->state(fn () => ['deleted_at' => fake()->dateTimeBetween($this->createdAt)]);
	}
}
