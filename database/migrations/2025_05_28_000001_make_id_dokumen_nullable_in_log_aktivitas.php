<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIdDokumenNullableInLogAktivitas extends Migration
{
    public function up()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Ubah kolom id_dokumen menjadi nullable
            $table->unsignedBigInteger('id_dokumen')->nullable()->change();
        });

        // Hapus foreign key lama jika ada
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropForeign('log_aktivitas_id_dokumen_foreign');
        });

        // Tambah foreign key baru dengan ON DELETE SET NULL
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->foreign('id_dokumen')
                ->references('id_dokumen')
                ->on('dokumen')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        // Kembalikan kolom id_dokumen menjadi NOT NULL dan foreign key default
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropForeign('log_aktivitas_id_dokumen_foreign');
        });

        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dokumen')->nullable(false)->change();
        });

        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->foreign('id_dokumen')
                ->references('id_dokumen')
                ->on('dokumen')
                ->onDelete('cascade'); // Atur sesuai kondisi awal jika perlu
        });
    }
}
