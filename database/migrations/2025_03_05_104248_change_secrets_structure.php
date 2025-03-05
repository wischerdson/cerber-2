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
		Schema::create('documents', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('parent_id')->unsigned()->nullable();
			$table->boolean('is_group');
			$table->string('alias')->unique();
			$table->string('name');
			$table->text('notes')->nullable();
			$table->boolean('is_effective')->default(true);
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('deleted_at')->nullable();
		});

		Schema::table('documents', function (Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('documents');
		});

		Schema::create('document_fields', function (Blueprint $table) {
			$table->id();
			$table->foreignId('document_id')->references('id')->on('documents')->cascadeOnDelete();
			$table->string('label');
			$table->string('short_description')->nullable();
			$table->mediumText('value')->default('');
			$table->boolean('is_multiline')->default(0);
			$table->boolean('is_secure')->default(0);
			$table->smallInteger('sort')->unsigned();
		});

		Schema::disableForeignKeyConstraints();

		Schema::dropIfExists('secrets');
		Schema::dropIfExists('secrets_in_groups');
		Schema::dropIfExists('secret_fields');
		Schema::dropIfExists('secret_groups');

		Schema::enableForeignKeyConstraints();
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		//
	}
};
