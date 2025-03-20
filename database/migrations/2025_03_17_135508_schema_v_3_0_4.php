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
        // 1. Modify dosen table, add enum belum_konfirmasi
        Schema::table('dosen', function (Blueprint $table) {
            $table->enum('bersedia_membimbing', ['bersedia', 'tidak_bersedia', 'belum_konfirmasi'])->default('belum_konfirmasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('bersedia_membimbing');
        });
    }
};