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
        // 1. Tambahkan kolom baru pada tabel kota yaitu jenis_ta dengan tipe data enum (penelitian, pengembangan)
        Schema::table('kota', function (Blueprint $table) {
            $table->enum('jenis_ta', ['penelitian', 'pengembangan'])->nullable()->after('id_kota');
        });

        // 2. Tambahkan kolom baru pada tabel form_penilaian yaitu jenis_ta dengan tipe data enum (penelitian, pengembangan)
        Schema::table('form_penilaian', function (Blueprint $table) {
            $table->enum('jenis_ta', ['penelitian', 'pengembangan'])->nullable()->after('id_fta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Hapus kolom jenis_ta pada tabel kota
        Schema::table('kota', function (Blueprint $table) {
            $table->dropColumn('jenis_ta');
        });

        // 2. Hapus kolom jenis_ta pada tabel form_penilaian
        Schema::table('form_penilaian', function (Blueprint $table) {
            $table->dropColumn('jenis_ta');
        });
    }
};
