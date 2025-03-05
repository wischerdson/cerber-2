<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentField;
use Illuminate\Database\Seeder;

class DocumentsSeeder extends Seeder
{
	public function run(): void
	{
		Document::factory()
			->group()
			->has(
				Document::factory()->has(
					DocumentField::factory()->count(3), 'fields'
				)->group(false)->count(20),
				'descendants'
			)
			->for(
				Document::factory()->group()->for(
					Document::factory()->group(), 'parent'
				), 'parent'
			)
			->create();
	}
}
