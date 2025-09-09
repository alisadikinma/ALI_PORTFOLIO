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
        Schema::table('galeri', function (Blueprint $table) {
            // Remove jenis_galeri and fixed image columns
            $table->dropColumn([
                'jenis_galeri',
                'gambar_galeri1',
                'gambar_galeri2', 
                'gambar_galeri3',
                'video_galeri'
            ]);
            
            // Rename gambar_galeri to thumbnail for main gallery image
            $table->renameColumn('gambar_galeri', 'thumbnail');
            
            // Add description column if not exists
            if (!Schema::hasColumn('galeri', 'description')) {
                $table->text('description')->nullable()->after('keterangan_galeri');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            // Add back removed columns
            $table->string('jenis_galeri')->nullable();
            $table->string('gambar_galeri1')->nullable();
            $table->string('gambar_galeri2')->nullable();
            $table->string('gambar_galeri3')->nullable();
            $table->string('video_galeri')->nullable();
            
            // Rename back thumbnail to gambar_galeri
            $table->renameColumn('thumbnail', 'gambar_galeri');
            
            // Remove description if it was added
            $table->dropColumn('description');
        });
    }
};
