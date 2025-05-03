<?php

namespace Tests\Feature;

use App\Models\Node;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NodeControllerTest extends TestCase
{
	use RefreshDatabase;

	public function test_restrict_without_authorization(): void
	{
		$this->getJson('/nodes')->assertUnauthenticated();
	}

	public function test_root_nodes_response(): void
	{
		$a = Node::factory()->create(['alias' => 'a', 'parent_id' => null]);
		$b = Node::factory()->create(['alias' => 'b', 'parent_id' => null]);
		$c = Node::factory()->create(['alias' => 'c', 'parent_id' => $a->id]);

		$this->actingAs(self::createUser())
			->getJson('/nodes')
			->assertOk()
			->assertJson(fn (AssertableJson $json) => $json
				->whereNull('current')
				->has('parents', 0)
				->has('descendants', 2)
				->whereAll([
					'descendants.0.id' => $a->id,
					'descendants.1.id' => $b->id,
				])
			);
	}

	public function test_node_chain_response(): void
	{
		$a = Node::factory()->create(['alias' => 'a', 'parent_id' => null]);
		$b = Node::factory()->create(['alias' => 'b', 'parent_id' => null]);
		$c = Node::factory()->create(['alias' => 'c', 'parent_id' => $a->id]);

		$this->actingAs(self::createUser())
			->getJson('/nodes?chain=a,c')
			->assertOk()
			->assertJson(fn (AssertableJson $json) => $json
				->where('current.id', $c->id)
				->has('parents', 1)
				->has('descendants', 0)
				->where('parents.0.id', $a->id)
			);
	}

	public function test_incorrect_node_chain_response(): void
	{
		Node::factory()->create(['alias' => 'a', 'parent_id' => null]);
		Node::factory()->create(['alias' => 'b', 'parent_id' => null]);

		$this->actingAs(self::createUser())
			->getJson('/nodes?chain=a,b')
			->assertStatus(422)
			->assertJson([
				'status' => 'error',
				'error_reason' => 'incorrect_node_chain',
				'message' => 'Node chain is incorrect',
			]);
	}
}
