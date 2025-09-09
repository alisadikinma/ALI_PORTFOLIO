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
        Schema::table('setting', function (Blueprint $table) {
            // Add section visibility columns
            $table->boolean('about_section_active')->default(true)->after('about_section_image');
            $table->boolean('services_section_active')->default(true)->after('about_section_active');
            $table->boolean('portfolio_section_active')->default(true)->after('services_section_active');
            $table->boolean('testimonials_section_active')->default(true)->after('portfolio_section_active');
            $table->boolean('gallery_section_active')->default(true)->after('testimonials_section_active');
            $table->boolean('articles_section_active')->default(true)->after('gallery_section_active');
            $table->boolean('awards_section_active')->default(true)->after('articles_section_active');
            $table->boolean('contact_section_active')->default(true)->after('awards_section_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn([
                'about_section_active',
                'services_section_active', 
                'portfolio_section_active',
                'testimonials_section_active',
                'gallery_section_active',
                'articles_section_active',
                'awards_section_active',
                'contact_section_active'
            ]);
        });
    }
};
