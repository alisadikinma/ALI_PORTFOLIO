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
        // Check if the table and column exist before making changes
        if (Schema::hasTable('setting')) {
            Schema::table('setting', function (Blueprint $table) {
                // Check if twitter_setting column exists
                if (Schema::hasColumn('setting', 'twitter_setting')) {
                    // Rename twitter_setting to linkedin_setting
                    $table->renameColumn('twitter_setting', 'linkedin_setting');
                } else {
                    // If twitter_setting doesn't exist, just add linkedin_setting
                    $table->string('linkedin_setting')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('setting')) {
            Schema::table('setting', function (Blueprint $table) {
                // Check if linkedin_setting column exists
                if (Schema::hasColumn('setting', 'linkedin_setting')) {
                    // Rename back to twitter_setting
                    $table->renameColumn('linkedin_setting', 'twitter_setting');
                }
            });
        }
    }
};
