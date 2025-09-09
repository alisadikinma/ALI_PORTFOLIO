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
        Schema::table('berita', function (Blueprint $table) {
            // SEO Meta Fields
            $table->string('meta_title')->nullable()->after('judul_berita');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('tags')->nullable()->after('meta_description');
            $table->string('focus_keyword')->nullable()->after('tags');
            
            // Content Enhancement Fields
            $table->text('featured_snippet')->nullable()->after('focus_keyword');
            $table->text('conclusion')->nullable()->after('featured_snippet');
            $table->json('faq_data')->nullable()->after('conclusion');
            
            // Article Metadata
            $table->integer('reading_time')->nullable()->after('faq_data');
            $table->boolean('is_featured')->default(false)->after('reading_time');
            $table->bigInteger('views')->default(0)->after('is_featured');
            
            // Add indexes for better performance
            $table->index('is_featured');
            $table->index('kategori_berita');
            $table->index('tanggal_berita');
            $table->index('views');
            
            // Full text index for search
            $table->fullText(['judul_berita', 'meta_title', 'meta_description', 'tags', 'isi_berita']);
        });
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
                'focus_keyword',
                'featured_snippet',
                'conclusion',
                'faq_data',
                'reading_time',
                'is_featured',
                'views'
            ]);
        });
    }
};
