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
		Schema::create('nodes', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('parent_id')->unsigned()->nullable();
			// $table->foreignId('creator_id')->constrained('users');
			// $table->foreignId('owner_id')->constrained('users');
			$table->enum('type', ['document', 'group', 'link'])->collation('ascii_bin');
			$table->string('alias')->unique()->collation('ascii_bin');
			$table->string('name');
			$table->text('notes')->nullable();
			$table->boolean('is_effective')->default(true);
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('deleted_at')->nullable();
		});

		Schema::table('nodes', function (Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('nodes');
		});

		Schema::create('document_fields', function (Blueprint $table) {
			$table->id();
			$table->foreignId('document_id')->references('id')->on('nodes')->cascadeOnDelete();
			$table->string('label');
			$table->string('short_description')->nullable();
			$table->mediumText('value')->default('');
			$table->boolean('is_multiline')->default(0);
			$table->boolean('is_secure')->default(0);
			$table->smallInteger('sort')->unsigned();
		});

		Schema::create('node_links', function (Blueprint $table) {
			$table->foreignId('link_id')->primary()->constrained('nodes');
			$table->foreignId('target_id')->constrained('nodes');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('nodes');
	}
};
