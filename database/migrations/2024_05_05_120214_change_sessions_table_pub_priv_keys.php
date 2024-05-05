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
		Schema::table('auth_sessions', function (Blueprint $table) {
			$table->text('server_private_key')->after('ip');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('auth_sessions', function (Blueprint $table) {
			$table->dropColumn('server_private_key');
		});
	}
};
