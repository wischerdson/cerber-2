<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DocumentGroupAggregateTest extends TestCase
{
	use RefreshDatabase;

	public function test_restrict_without_authorization(): void
	{
		$this->getJson('/aggregates/document-group')->assertUnauthenticated();
	}

	public function test_aggregate_response(): void
	{
		$document = Document::factory()
			->group()
			->has(
				Document::factory()->group(false)->has(
					DocumentField::factory()->count(2), 'fields'
				)->count(3),
				'descendants'
			)
			->for(Document::factory()->group(), 'parent')
			->create();

		$response = $this->actingAs(self::createUser())
			->getJson("/aggregates/document-group?id={$document->id}")
			->assertOk()
			->assertJson(fn (AssertableJson $json) => $json
				->has(
					'parents',
					1,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'notes', 'is_group', 'is_effective', 'created_at', 'deleted_at')
						->where('id', $document->parent->id)
				)
				->has(
					'current',
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'notes', 'is_group', 'is_effective', 'created_at', 'deleted_at')
						->where('id', $document->id)
				)
				->has(
					'descendants',
					3,
					fn (AssertableJson $json) => $json
						->hasAll('id', 'name', 'alias', 'notes', 'is_group', 'is_effective', 'created_at', 'deleted_at')
				)
			);

		dd($response->json());
	}

	public function test_aggregate_input_validation(): void
	{
		$this->actingAs($user = self::createUser())
			->getJson('/aggregates/document-group')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->where('details.id.0', 'required')
					->where('details.alias.0', 'required')
					->etc()
			);

		$this->actingAs($user)
			->getJson('/aggregates/document-group?id=1ab')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->where('details.id.0', 'numeric')
					->missing('details.alias.0')
					->etc()
			);

		$this->actingAs($user)
			->getJson('/aggregates/document-group?alias=')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->where('details.alias.0', 'required')
					->missing('details.id.0')
					->etc()
			);

		$this->actingAs($user)
			->getJson('/aggregates/document-group?id=')
			->assertStatus(422)
			->assertJson(fn (AssertableJson $json) =>
				$json->where('error_reason', 'validation_failed')
					->where('details.id.0', 'required')
					->missing('details.alias.0')
					->etc()
			);

		$this->actingAs($user)
			->getJson('/aggregates/document-group?id=123')
			->assertNotFound();

		$this->actingAs($user)
			->getJson('/aggregates/document-group?alias=asd')
			->assertNotFound();
	}
}
