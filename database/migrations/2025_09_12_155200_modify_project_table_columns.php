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
        Schema::table('project', function (Blueprint $table) {
            // Change info_project from TEXT to LONGTEXT to handle large content
            $table->longText('info_project')->nullable()->change();
            
            // Also ensure description can handle larger content
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->text('info_project')->nullable()->change();
            $table->string('description', 500)->nullable()->change();
        });
    }
};
