<?php

namespace Tests\Feature;

use App\Models\Node;
use App\Models\DocumentField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NodeAggregateTest extends TestCase
{
	use RefreshDatabase;

	public function test_restrict_without_authorization(): void
	{
		$this->getJson('/aggregates/nodes')->assertUnauthenticated();
	}

	public function test_aggregate_response(): void
	{
		$document = Node::factory()
			->asGroup()
			->has(
				Node::factory()->asDocument()->has(
					DocumentField::factory()->count(2), 'document_fields'
				)->count(3),
				'descendants'
			)
			->for(Node::factory()->asGroup(), 'parent')
			->create();

		$this->actingAs(self::createUser())
			->getJson("/aggregates/nodes?id={$document->id}")
			->assertOk()
			->assertJson(fn (AssertableJson $json) => $json
				->has(
					'parents',
					1,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'notes', 'type', 'is_effective', 'created_at', 'deleted_at')
						->where('id', $document->parent->id)
						->where('type', Node::TYPE_GROUP)
				)
				->has(
					'current',
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'notes', 'type', 'is_effective', 'created_at', 'deleted_at')
						->where('id', $document->id)
						->where('type', Node::TYPE_GROUP)
				)
				->has(
					'descendants',
					3,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'notes', 'type', 'is_effective', 'created_at', 'deleted_at')
				)
			);
	}

	public function test_aggregate_input_validation(): void
	{
		$this->actingAs($user = self::createUser())
			->getJson('/aggregates/nodes')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('message', 'Either the ID or alias of the node is required')
					->etc()
			);

		$response = $this->actingAs(self::createUser())
			->getJson('/aggregates/nodes?id=12345')
			->assertStatus(404);


			dd($response->json());



		// $this->actingAs($user)
		// 	->getJson('/aggregates/nodes?id=1ab')
		// 	->assertStatus(422)
		// 	->assertJson(fn (AssertableJson $json) =>
		// 		$json->where('error_reason', 'validation_failed')
		// 			->where('details.id.0', 'numeric')
		// 			->missing('details.alias.0')
		// 			->etc()
		// 	);

		// $this->actingAs($user)
		// 	->getJson('/aggregates/nodes?alias=')
		// 	->assertStatus(422)
		// 	->assertJson(fn (AssertableJson $json) =>
		// 		$json->where('error_reason', 'validation_failed')
		// 			->where('details.alias.0', 'required')
		// 			->missing('details.id.0')
		// 			->etc()
		// 	);

		// $this->actingAs($user)
		// 	->getJson('/aggregates/nodes?id=')
		// 	->assertStatus(422)
		// 	->assertJson(fn (AssertableJson $json) =>
		// 		$json->where('error_reason', 'validation_failed')
		// 			->where('details.id.0', 'required')
		// 			->missing('details.alias.0')
		// 			->etc()
		// 	);

		// $this->actingAs($user)
		// 	->getJson('/aggregates/nodes?id=123')
		// 	->assertNotFound();

		// $this->actingAs($user)
		// 	->getJson('/aggregates/nodes?alias=asd')
		// 	->assertNotFound();
	}
}
