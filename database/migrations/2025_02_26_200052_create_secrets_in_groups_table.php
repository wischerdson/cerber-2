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
		Schema::create('secrets_in_groups', function (Blueprint $table) {
			$table->foreignId('secret_id')->constrained('secrets')->cascadeOnUpdate()->cascadeOnDelete();
			$table->foreignId('group_id')->constrained('secret_groups3')->cascadeOnUpdate()->cascadeOnDelete();

			$table->primary(['secret_id', 'group_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('secrets_in_groups');
	}
};
