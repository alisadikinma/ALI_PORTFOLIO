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
            // Add new fields for dynamic images
            $table->json('images')->nullable()->after('gambar_project2');
            $table->string('featured_image')->nullable()->after('images');
            $table->string('status')->default('Active')->after('featured_image');
            $table->integer('sequence')->default(0)->after('status');
            
            // Add timestamps if they don't exist
            if (!Schema::hasColumn('project', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project', function (Blueprint $table) {
            $table->dropColumn(['images', 'featured_image', 'status', 'sequence']);
        });
    }
};
