<?php

namespace Database\Factories;

use App\Models\Secret;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secret>
 */
class SecretFactory extends Factory
{
	/** @var class-string<\Illuminate\Database\Eloquent\Model> */
	protected $model = Secret::class;

	public function configure(): static
	{
		return $this->afterCreating(function (Secret $secret) {
			$this->setFieldsSort($secret);
		});
	}

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name' => mb_ucfirst(fake()->words(
				fake()->randomElement([1, 2, 3, 4]),
				true
			)),
			'notes' => fake()->randomElement([null, fake()->text(255)]),
			'is_uptodate' => fake()->boolean(),
			'deleted_at' => fake()->randomElement([null, now()->timestamp(fake()->unixTime())]),
		];
	}

	public function deleted(DateTime|int|string|null $when = 'now'): Factory
	{
		return $this->state(fn (array $attrs) => [
			'deleted_at' => $when === null ? null : now()->timestamp(fake()->unixTime($when))
		]);
	}

	public function upToDated(bool $yes = true): Factory
	{
		return $this->state(fn (array $attrs) => [
			'is_uptodate' => $yes
		]);
	}

	private function setFieldsSort(Secret $secret): void
	{
		$fields = $secret->fields()->get();

		foreach ($fields as $i => $field) {
			$field->sort = $i + 1;
		}

		$secret->fields()->saveMany($fields);
	}
}
