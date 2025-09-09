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
            // Add section visibility columns if they don't exist
            if (!Schema::hasColumn('setting', 'about_section_active')) {
                $table->boolean('about_section_active')->default(1)->after('award_section_subtitle');
            }
            if (!Schema::hasColumn('setting', 'services_section_active')) {
                $table->boolean('services_section_active')->default(1)->after('about_section_active');
            }
            if (!Schema::hasColumn('setting', 'portfolio_section_active')) {
                $table->boolean('portfolio_section_active')->default(1)->after('services_section_active');
            }
            if (!Schema::hasColumn('setting', 'testimonials_section_active')) {
                $table->boolean('testimonials_section_active')->default(1)->after('portfolio_section_active');
            }
            if (!Schema::hasColumn('setting', 'gallery_section_active')) {
                $table->boolean('gallery_section_active')->default(1)->after('testimonials_section_active');
            }
            if (!Schema::hasColumn('setting', 'articles_section_active')) {
                $table->boolean('articles_section_active')->default(1)->after('gallery_section_active');
            }
            if (!Schema::hasColumn('setting', 'awards_section_active')) {
                $table->boolean('awards_section_active')->default(1)->after('articles_section_active');
            }
            if (!Schema::hasColumn('setting', 'contact_section_active')) {
                $table->boolean('contact_section_active')->default(1)->after('awards_section_active');
            }
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
