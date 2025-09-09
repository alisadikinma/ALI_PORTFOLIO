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
        // Add sequence and status to award table
        Schema::table('award', function (Blueprint $table) {
            $table->integer('sequence')->default(0)->after('keterangan_award');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('sequence');
        });

        // Add sequence and status to layanan table
        Schema::table('layanan', function (Blueprint $table) {
            $table->integer('sequence')->default(0)->after('keterangan_layanan');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('sequence');
        });

        // Add sequence and status to galeri table
        Schema::table('galeri', function (Blueprint $table) {
            $table->integer('sequence')->default(0)->after('video_galeri');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('sequence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('award', function (Blueprint $table) {
            $table->dropColumn(['sequence', 'status']);
        });

        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn(['sequence', 'status']);
        });

        Schema::table('galeri', function (Blueprint $table) {
            $table->dropColumn(['sequence', 'status']);
        });
    }
};
