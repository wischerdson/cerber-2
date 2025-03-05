<?php

namespace Database\Factories;

use App\Models\Document;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
	/** @var class-string<\Illuminate\Database\Eloquent\Model> */
	protected $model = Document::class;

	public function configure(): static
	{
		return $this->afterCreating(function (Document $document) {
			$this->setFieldsSort($document);
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
			'is_group' => fake()->boolean(),
			'name' => mb_ucfirst(fake()->words(
				fake()->randomElement([1, 2, 3, 4]),
				true
			)),
			'notes' => fake()->randomElement([null, fake()->text(255)]),
			'is_effective' => fake()->boolean(),
			'deleted_at' => fake()->randomElement([null, now()->timestamp(fake()->unixTime())]),
		];
	}

	public function group(bool $yes = true): Factory
	{
		return $this->state(fn (array $attrs) => ['is_group' => $yes]);
	}

	public function deleted(DateTime|int|string|null $when = 'now'): Factory
	{
		return $this->state(fn (array $attrs) => [
			'deleted_at' => $when === null ? null : now()->timestamp(fake()->unixTime($when))
		]);
	}

	public function effective(bool $yes = true): Factory
	{
		return $this->state(fn (array $attrs) => ['is_effective' => $yes]);
	}

	private function setFieldsSort(Document $document): void
	{
		$fields = $document->fields()->get();

		foreach ($fields as $i => $field) {
			$field->sort = $i + 1;
		}

		$document->fields()->saveMany($fields);
	}
}
