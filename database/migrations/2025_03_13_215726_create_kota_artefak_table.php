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
        Schema::create('kota_artefak', function (Blueprint $table) {
            $table->id('id_kota_artefak');
            $table->unsignedBigInteger('id_kota');
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('restrict');
            $table->unsignedBigInteger('id_artefak');
            $table->foreign('id_artefak')->references('id_artefak')->on('artefak')->onDelete('restrict');
            $table->text('file_pengumpulan');
            $table->timestamp('waktu_pengumpulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kota_artefak');
    }
};
