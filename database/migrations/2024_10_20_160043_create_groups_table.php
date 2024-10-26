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
		Schema::create('groups', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
			$table->string('name');
			$table->string('alias');
			$table->text('description')->nullable();
			$table->bigInteger('parent_id')->unsigned()->nullable();
			$table->timestamp('created_at');
			$table->timestamp('deleted_at')->nullable();
		});

		Schema::table('groups', function (Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('groups');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('groups');
	}
};
