<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Node;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Node>
 */
class NodeFactory extends Factory
{
	/** @var class-string<\Illuminate\Database\Eloquent\Model> */
	protected $model = Node::class;

	public function configure(): static
	{
		return $this->afterCreating(function (Node $node) {
			$node->type === Node::TYPE_DOCUMENT && $this->setFieldsSort($node);
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
			'type' => fake()->randomElement([Node::TYPE_DOCUMENT, Node::TYPE_LINK, Node::TYPE_GROUP]),
			'name' => mb_ucfirst(fake()->words(
				fake()->randomElement([1, 2, 3, 4]),
				true
			)),
			'notes' => fake()->randomElement([null, fake()->text(255)]),
			'is_effective' => fake()->boolean(),
			'deleted_at' => fake()->randomElement([null, now()->timestamp(fake()->unixTime())]),
		];
	}

	public function asGroup(): Factory
	{
		return $this->state(fn (array $attrs) => ['type' => Node::TYPE_GROUP]);
	}

	public function asDocument(): Factory
	{
		return $this->state(fn (array $attrs) => ['type' => Node::TYPE_DOCUMENT]);
	}

	public function asLink(): Factory
	{
		return $this->state(fn (array $attrs) => ['type' => Node::TYPE_LINK]);
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

	private function setFieldsSort(Node $node): void
	{
		$fields = $node->document_fields()->get();

		foreach ($fields as $i => $field) {
			$field->sort = $i + 1;
		}

		$node->document_fields()->saveMany($fields);
	}
}
