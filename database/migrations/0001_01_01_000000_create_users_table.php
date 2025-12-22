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
            $table->string('id', 32)->primary();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            
            // Avatar
            $table->string('avatar')->nullable();
            
            // Address Information
            $table->string('country')->nullable();
            $table->string('city_state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('tax_id')->nullable();
            
            // Social Links
            $table->string('facebook')->nullable();
            $table->string('x_link')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();

            // Social Auth - Google
            $table->string('google_id')->nullable();
            $table->text('google_token')->nullable();
            $table->text('google_refresh_token')->nullable();

            // Social Auth - GitHub
            $table->string('github_id')->nullable();
            $table->text('github_token')->nullable();
            $table->text('github_refresh_token')->nullable();
            


            $table->rememberToken();
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 32)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
