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
				Document::factory()->has(
					DocumentField::factory()->count(2), 'fields'
				)->count(3),
				'descendants'
			)
			->for(Document::factory()->group(), 'parent')
			->create();

		dd(Document::with('parent', 'descendants')->get()->toArray());
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

		// dd($response->json());

		// $this->actingAs($user)
		// 	->getJson('/aggregates/secret-group?group_alias=123')
		// 	->assertNotFound();

		// $group = SecretGroup::factory()
		// 	->for($user, 'user')
		// 	->has(
		// 		Secret::factory()->has(
		// 			SecretField::factory()->count(2), 'fields'
		// 		)->count(3)
		// 	)
		// 	->has(SecretGroup::factory()->count(2), 'children')
		// 	->for(SecretGroup::factory(), 'parent')
		// 	->create();

		// $this->actingAs($user)
		// 	->getJson("/aggregates/secret-group?group_alias={$group->alias}")
		// 	->assertOk()
		// 	->assertJson(fn (AssertableJson $json) => $json
		// 		->has(
		// 			'current_group',
		// 			fn (AssertableJson $json) => $json
		// 				->hasAll('id', 'name', 'alias', 'description', 'created_at', 'deleted_at')
		// 				->where('id', $group->id)
		// 		)
		// 		->has(
		// 			'children_groups',
		// 			2,
		// 			fn (AssertableJson $json) => $json
		// 				->hasAll('id', 'name', 'alias', 'description', 'created_at', 'deleted_at')
		// 		)
		// 		->has(
		// 			'parent_groups',
		// 			2,
		// 			fn (AssertableJson $json) => $json
		// 				->hasAll('id', 'name', 'alias', 'description', 'created_at', 'deleted_at')
		// 		)
		// 		->has(
		// 			'secrets',
		// 			3,
		// 			fn (AssertableJson $json) => $json
		// 				->hasAll('id', 'alias', 'name', 'notes', 'is_uptodate', 'created_at', 'updated_at', 'deleted_at')
		// 		)
		// 	);
	}
}
