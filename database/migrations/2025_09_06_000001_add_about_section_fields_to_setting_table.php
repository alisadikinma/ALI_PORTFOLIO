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
            $table->string('about_section_title')->nullable()->after('success_rate');
            $table->string('about_section_subtitle')->nullable()->after('about_section_title');
            $table->text('about_section_description')->nullable()->after('about_section_subtitle');
            $table->string('about_section_image')->nullable()->after('about_section_description');
            $table->string('award_section_title')->nullable()->after('about_section_image');
            $table->string('award_section_subtitle')->nullable()->after('award_section_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting', function (Blueprint $table) {
            $table->dropColumn([
                'about_section_title',
                'about_section_subtitle', 
                'about_section_description',
                'about_section_image',
                'award_section_title',
                'award_section_subtitle'
            ]);
        });
    }
};
