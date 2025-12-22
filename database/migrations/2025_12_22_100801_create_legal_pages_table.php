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
        Schema::dropIfExists('legal_page_revisions');
        Schema::dropIfExists('legal_pages');

        Schema::create('legal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('legal_page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_page_id')->constrained()->onDelete('cascade');
            $table->longText('content');
            $table->string('version')->default('1.0');
            $table->text('change_log')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_page_revisions');
        Schema::dropIfExists('legal_pages');
    }
};
