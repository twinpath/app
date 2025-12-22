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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('user_id', 32);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('common_name');
            $table->string('organization')->nullable();
            $table->string('locality')->nullable();
            $table->string('state')->nullable();
            $table->string('country', 10)->nullable();
            $table->text('san')->nullable();
            $table->integer('key_bits')->default(2048);
            $table->string('serial_number')->unique();
            $table->text('cert_content');
            $table->text('key_content');
            $table->text('csr_content')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
