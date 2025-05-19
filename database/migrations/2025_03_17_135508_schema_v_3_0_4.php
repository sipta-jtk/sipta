<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modify dosen table, add enum belum_konfirmasi
        DB::statement("ALTER TABLE `dosen` MODIFY `bersedia_membimbing` ENUM('bersedia', 'tidak_bersedia', 'belum_konfirmasi') NOT NULL");

        // 2. Modify mahasiswa table, delete column nilai_akhir_ta
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('nilai_akhir_ta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `dosen` MODIFY `bersedia_membimbing` ENUM('bersedia', 'tidak_bersedia') NOT NULL");

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->integer('nilai_akhir_ta')->nullable();
        });
    }
};