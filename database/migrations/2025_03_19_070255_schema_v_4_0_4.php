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
        // Menghapus tabel rekapitulasi_nilai_akhir
        Schema::dropIfExists('rekapitulasi_nilai_akhir');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Membuat kembali tabel rekapitulasi_nilai_akhir
        Schema::create('rekapitulasi_nilai_akhir', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->string('nim', 22);
            $table->float('nilai_uts')->nullable(false);
            $table->float('nilai_uas')->nullable(false);
            $table->float('nilai_lain_lain')->nullable(false);
            $table->float('nilai_akhir')->nullable(false);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }
};
