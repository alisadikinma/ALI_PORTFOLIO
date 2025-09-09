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
        Schema::create('berita_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('article_id');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('article_id');
            $table->index('ip_address');
            $table->index(['article_id', 'created_at']);
            
            // Foreign key constraint
            $table->foreign('article_id')->references('id_berita')->on('berita')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_visits');
    }
};
