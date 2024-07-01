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
		Schema::create('handshakes', function (Blueprint $table) {
			$table->uuid()->primary();
			$table->text('server_private_key');
			$table->text('client_public_key');
			$table->timestamp('last_used_at');
			$table->timestamp('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('handshakes');
	}
};
