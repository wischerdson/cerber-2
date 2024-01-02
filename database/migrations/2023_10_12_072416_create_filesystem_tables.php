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
		Schema::create('storage_dirs', function (Blueprint $table) {
			$table->id();
			$table->bigInteger('parent_id')->unsigned()->nullable();
			$table->string('name');
			$table->string('category')->nullable();
			$table->string('path')->nullable();
			$table->string('disk');
			$table->integer('files_count')->unsigned()->default(0);
			$table->integer('dirs_count')->unsigned()->default(0);
			$table->timestamps();
		});

		Schema::table('storage_dirs', function (Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('storage_dirs')->cascadeOnDelete();
		});

		Schema::create('files', function (Blueprint $table) {
			$table->id();
			$table->nullableMorphs('fileable');
			$table->morphs('entity');
			$table->bigInteger('parent_id')->unsigned()->nullable();
			$table->foreignId('user_id')->constrained('users');
			$table->foreignId('storage_dir_id')->constrained('storage_dirs');
			$table->string('name');
			$table->string('path');
			$table->integer('size')->unsigned();
			$table->string('mime');
			$table->string('md5_hash', 32);
			$table->string('sha1_hash', 40);
			$table->timestamp('created_at');
		});

		Schema::table('files', function (Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('files')->nullOnDelete();
		});

		Schema::create('file_image_entities', function (Blueprint $table) {
			$table->id();
			$table->smallInteger('width')->unsigned();
			$table->smallInteger('height')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('storage_dirs');
		Schema::dropIfExists('file_image_entities');
		Schema::dropIfExists('files');
	}
};
