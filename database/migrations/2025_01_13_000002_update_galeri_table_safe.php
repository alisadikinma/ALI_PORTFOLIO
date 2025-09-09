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
            // Check and remove columns if they exist
            if (Schema::hasColumn('galeri', 'jenis_galeri')) {
                $table->dropColumn('jenis_galeri');
            }
            if (Schema::hasColumn('galeri', 'gambar_galeri1')) {
                $table->dropColumn('gambar_galeri1');
            }
            if (Schema::hasColumn('galeri', 'gambar_galeri2')) {
                $table->dropColumn('gambar_galeri2');
            }
            if (Schema::hasColumn('galeri', 'gambar_galeri3')) {
                $table->dropColumn('gambar_galeri3');
            }
            if (Schema::hasColumn('galeri', 'video_galeri')) {
                $table->dropColumn('video_galeri');
            }
        });

        // Rename column if it exists
        if (Schema::hasColumn('galeri', 'gambar_galeri')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->renameColumn('gambar_galeri', 'thumbnail');
            });
        }

        // Add new columns if they don't exist
        Schema::table('galeri', function (Blueprint $table) {
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
            if (!Schema::hasColumn('galeri', 'jenis_galeri')) {
                $table->string('jenis_galeri')->nullable();
            }
            if (!Schema::hasColumn('galeri', 'gambar_galeri1')) {
                $table->string('gambar_galeri1')->nullable();
            }
            if (!Schema::hasColumn('galeri', 'gambar_galeri2')) {
                $table->string('gambar_galeri2')->nullable();
            }
            if (!Schema::hasColumn('galeri', 'gambar_galeri3')) {
                $table->string('gambar_galeri3')->nullable();
            }
            if (!Schema::hasColumn('galeri', 'video_galeri')) {
                $table->string('video_galeri')->nullable();
            }
        });

        // Rename back thumbnail to gambar_galeri if needed
        if (Schema::hasColumn('galeri', 'thumbnail')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->renameColumn('thumbnail', 'gambar_galeri');
            });
        }

        // Remove description if it exists
        if (Schema::hasColumn('galeri', 'description')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
