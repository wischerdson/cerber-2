<?php

namespace Tests\Feature;

use App\Exceptions\NodeTroublesException;
use App\Models\Node;
use App\Services\Node\NodeRepository;
use App\Services\Node\NodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class NodeServiceTest extends TestCase
{
	use RefreshDatabase;

	private NodeService $service;

	public function setUp(): void
	{
		parent::setUp();

		$this->service = new NodeService();
	}

	public function test_returns_nodes_for_correct_chain(): void
	{
		$a = Node::factory()->create(['alias' => 'a', 'parent_id' => null]);
		$b = Node::factory()->create(['alias' => 'b', 'parent_id' => $a->id]);
		$c = Node::factory()->create(['alias' => 'c', 'parent_id' => $b->id]);

		$result = $this->service->getNodesByChainOfAliases(['a', 'b', 'c']);

		assertCount(3, $result);
		assertEquals([$a->id, $b->id, $c->id], $result->pluck('id')->all());
	}

	public function test_throws_if_alias_not_found(): void
	{
		$this->expectException(NodeTroublesException::class);
		$this->expectExceptionMessage('Node chain is incorrect');

		Node::factory()->create(['alias' => 'a', 'parent_id' => null]);

		$this->service->getNodesByChainOfAliases(['a', 'b']);
	}

	public function test_throws_if_chain_is_broken()
	{
		$this->expectException(NodeTroublesException::class);
		$this->expectExceptionMessage('Node chain is incorrect');

		$a = Node::factory()->create(['alias' => 'a', 'parent_id' => null]);
		$b = Node::factory()->create(['alias' => 'b', 'parent_id' => null]);
		$c = Node::factory()->create(['alias' => 'c', 'parent_id' => $b->id]);

		$this->service->getNodesByChainOfAliases(['a', 'b', 'c']);
	}

	public function test_throws_if_chain_has_wrong_order()
	{
		$this->expectException(NodeTroublesException::class);
		$this->expectExceptionMessage('Node chain is incorrect');

		$a = Node::factory()->create(['alias' => 'a', 'parent_id' => null]);
		$b = Node::factory()->create(['alias' => 'b', 'parent_id' => $a->id]);
		$c = Node::factory()->create(['alias' => 'c', 'parent_id' => $b->id]);

		$this->service->getNodesByChainOfAliases(['a', 'c', 'b']);
	}
}
