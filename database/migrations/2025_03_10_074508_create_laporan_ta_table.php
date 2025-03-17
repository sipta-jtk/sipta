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
        Schema::create('laporan_ta', function (Blueprint $table) {
            $table->id(); // Kolom ID sebagai primary key
            $table->string('versi', 10);
            $table->string('judul');
            $table->date('tanggal_dibuat');
            $table->date('terakhir_diedit');
            $table->timestamps(); // Kolom created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_ta');
    }
};
