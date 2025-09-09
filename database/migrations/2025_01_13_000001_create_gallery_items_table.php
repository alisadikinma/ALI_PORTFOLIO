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
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id('id_gallery_item');
            $table->unsignedBigInteger('id_galeri');
            $table->unsignedBigInteger('id_award')->nullable(); // Link to award table
            $table->enum('type', ['image', 'youtube'])->default('image');
            $table->string('file_name')->nullable(); // For image/video files
            $table->string('youtube_url')->nullable(); // For YouTube URLs
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('sequence')->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();

            $table->foreign('id_galeri')->references('id_galeri')->on('galeri')->onDelete('cascade');
            $table->foreign('id_award')->references('id_award')->on('award')->onDelete('set null');
            
            $table->index(['id_galeri', 'sequence']);
            $table->index(['status', 'sequence']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
