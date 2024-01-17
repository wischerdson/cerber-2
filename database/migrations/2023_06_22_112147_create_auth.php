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
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->nullable()->unique();
			$table->string('timezone')->nullable();
			$table->integer('timezone_offset')->nullable();
			$table->boolean('is_admin')->default(false);
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('deleted_at')->nullable();
		});

		Schema::create('auth_entry_points', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->string('provider');
			$table->bigInteger('provider_entry_point_id')->unsigned();
			$table->timestamp('created_at')->useCurrent();
		});

		Schema::create('login_provider_entry_points', function (Blueprint $table) {
			$table->id();
			$table->string('login')->unique();
			$table->text('password')->nullable();
			$table->string('password_hash')->nullable();
			$table->timestamp('password_changed_at')->nullable();
		});

		Schema::create('personal_access_tokens', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete();
			$table->string('access_token', 64);
			$table->string('refresh_token');
			$table->timestamp('last_used_at')->useCurrent();
			$table->timestamp('expires_at')->useCurrent();
			$table->timestamp('created_at')->useCurrent();
		});

		Schema::create('auth_sessions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('personal_access_token_id')->constrained('personal_access_tokens')->cascadeOnDelete();
			$table->foreignId('entry_point_id')->constrained('auth_entry_points')->cascadeOnDelete();
			$table->text('user_agent')->nullable();
			$table->ipAddress('ip')->nullable();
			$table->string('ip_country_code', 2)->nullable();
			$table->string('ip_region')->nullable();
			$table->string('ip_city')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('auth_details');
		Schema::dropIfExists('personal_access_tokens');
		Schema::dropIfExists('login_provider_entry_points');
		Schema::dropIfExists('auth_entry_points');
		Schema::dropIfExists('users');
	}
};
