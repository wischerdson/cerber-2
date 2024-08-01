<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('secrets', function (Blueprint $table) {
			$table->id();
			// $table->string('alias');
			$table->string('name');
			$table->text('notes')->nullable();
			$table->boolean('is_uptodate')->default(true);
			$table->timestamps();
		});

		Schema::create('secret_fields', function (Blueprint $table) {
			$table->id();
			$table->foreignId('secret_id')->references('id')->on('secrets')->cascadeOnDelete();
			$table->string('label');
			$table->string('short_description')->nullable();
			$table->mediumText('value')->default('');
			$table->boolean('multiline')->default(0);
			$table->boolean('secure')->default(0);
			$table->smallInteger('sort')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('secret_fields');
		Schema::dropIfExists('secrets');
	}
};
