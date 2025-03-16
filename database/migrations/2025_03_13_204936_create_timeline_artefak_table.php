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
        Schema::create('timeline_artefak', function (Blueprint $table) {
            $table->unsignedBigInteger('id_timeline');
            $table->foreign('id_timeline')->references('id_timeline')->on('timeline')->onDelete('cascade');
            $table->unsignedBigInteger('id_kategori_artefak');
            $table->foreign('id_kategori_artefak')->references('id_kategori_artefak')->on('kategori_artefak')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeline_artefak');
    }
};
