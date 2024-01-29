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

		Schema::create('auth_grants', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
			$table->string('grant_type');
			$table->string('grant_id');
			$table->boolean('is_active')->default(true);
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->nullable();
		});

		Schema::create('auth_password_grants', function (Blueprint $table) {
			$table->id();
			$table->string('login')->unique();
			$table->text('password')->nullable();
			$table->string('password_hash')->nullable();
			$table->timestamp('password_changed_at')->nullable();
		});

		Schema::create('auth_refresh_token_grants', function (Blueprint $table) {
			$table->id();
			$table->string('code', 3);
			$table->timestamp('expires_at');
			$table->timestamp('updated_at');
		});

		Schema::create('auth_sessions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
			$table->foreignId('grant_id')->constrained('auth_grants')->cascadeOnDelete();
			$table->text('user_agent')->nullable();
			$table->ipAddress('ip')->nullable();
			$table->boolean('revoked')->default(false);
			$table->timestamp('last_seen_at')->useCurrent();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('auth_sessions');
		Schema::dropIfExists('auth_refresh_token_grants');
		Schema::dropIfExists('auth_password_grants');
		Schema::dropIfExists('auth_grants');
		Schema::dropIfExists('users');
	}
};
