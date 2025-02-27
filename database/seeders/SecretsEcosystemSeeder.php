<?php

namespace Database\Seeders;

use App\Models\Secret;
use App\Models\SecretField;
use App\Models\SecretGroup;
use Illuminate\Database\Seeder;

class SecretsEcosystemSeeder extends Seeder
{
	public function run(): void
	{
		SecretGroup::factory()
			->has(
				Secret::factory()->has(
					SecretField::factory()->count(2), 'fields'
				)->count(200)
			)
			->has(SecretGroup::factory()->count(2), 'children')
			->for(
				SecretGroup::factory()->for(
					SecretGroup::factory(), 'parent'
				), 'parent'
			)
			->create();
	}
}
