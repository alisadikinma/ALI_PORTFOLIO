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

        // Rename columns if they exist
        if (Schema::hasColumn('galeri', 'gambar_galeri')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->renameColumn('gambar_galeri', 'thumbnail');
            });
        }

        if (Schema::hasColumn('galeri', 'keterangan_galeri')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->renameColumn('keterangan_galeri', 'deskripsi_galeri');
            });
        }
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

        // Rename back columns if they exist
        if (Schema::hasColumn('galeri', 'thumbnail')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->renameColumn('thumbnail', 'gambar_galeri');
            });
        }

        if (Schema::hasColumn('galeri', 'deskripsi_galeri')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->renameColumn('deskripsi_galeri', 'keterangan_galeri');
            });
        }
    }
};
