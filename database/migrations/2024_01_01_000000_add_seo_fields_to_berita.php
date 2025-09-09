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
        // Skip jika kolom sudah ada
        if (!Schema::hasColumn('berita', 'meta_title')) {
            Schema::table('berita', function (Blueprint $table) {
                $table->string('meta_title', 255)->nullable()->after('judul_berita');
                $table->text('meta_description')->nullable()->after('meta_title');
                $table->string('tags', 255)->nullable()->after('meta_description');
                $table->integer('reading_time')->nullable()->after('tags');
                $table->text('featured_snippet')->nullable()->after('reading_time');
                $table->text('conclusion')->nullable()->after('featured_snippet');
                $table->string('focus_keyword', 255)->nullable()->after('conclusion');
                $table->json('faq_data')->nullable()->after('focus_keyword');
                $table->boolean('is_featured')->default(false)->after('faq_data');
                $table->integer('views')->default(0)->after('is_featured');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description', 
                'tags',
                'reading_time',
                'featured_snippet',
                'conclusion',
                'focus_keyword',
                'faq_data',
                'is_featured',
                'views'
            ]);
        });
    }
};