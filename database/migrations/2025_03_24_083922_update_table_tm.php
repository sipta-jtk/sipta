<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah ENUM di tabel verifikasi_berkas_pengajuan
        DB::statement("ALTER TABLE verifikasi_berkas_pengajuan MODIFY COLUMN status_konfirmasi ENUM('pending', 'disetujui', 'tidak_disetujui') NOT NULL");

        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign('konfirmasi_nip_foreign');
            $table->text('catatan')->nullable()->change();
            $table->foreign('nip')->nullable()->references('nip')->on('dosen')->change();
            $table->date('tanggal_verifikasi')->nullable()->change();
        });

        Schema::table('pembatalan', function (Blueprint $table) {
            $table->string('status_pembatalan')->nullable()->change();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::table('pengajuan_jadwal_kota', function (Blueprint $table) {
            $table->boolean('status_mahasiswa')->nullable()->change();
            $table->boolean('status_dosen_pembimbing_1')->nullable()->change();
            $table->boolean('status_dosen_pembimbing_2')->nullable()->change();
            $table->boolean('status_dosen_penguji_1')->nullable()->change();
            $table->boolean('status_dosen_penguji_2')->nullable()->change();
            $table->boolean('status_koordinator_ta')->nullable()->change();
        });

        // Mengubah ENUM di tabel kehadiran
        DB::statement("ALTER TABLE kehadiran MODIFY COLUMN status_hadir ENUM('hadir', 'belum_absen', 'tidak_hadir') NOT NULL DEFAULT 'belum_absen'");
        DB::statement("ALTER TABLE kehadiran MODIFY COLUMN status_kelulusan ENUM('pending', 'lulus_tanpa_perbaikan_laporan','lulus_dengan_perbaikan_laporan','mengulang_sidang_tugas_akhir','tidak_lulus') NOT NULL DEFAULT 'pending'");

        Schema::table('kehadiran', function (Blueprint $table) {
            $table->date('batas_revisi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ENUM di tabel verifikasi_berkas_pengajuan ke nilai sebelumnya
        DB::statement("ALTER TABLE verifikasi_berkas_pengajuan MODIFY COLUMN status_konfirmasi ENUM('disetujui', 'tidak_disetujui') NOT NULL");

        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            $table->text('catatan')->nullable(false)->change();
            $table->dropForeign(['nip']);
            $table->string('nip')->nullable(false)->change();
            $table->date('tanggal_verifikasi')->nullable(false)->change();
        });

        Schema::table('pembatalan', function (Blueprint $table) {
            $table->string('status_pembatalan')->nullable(false)->change();
            $table->dropColumn('created_at');
        });

        Schema::table('pengajuan_jadwal_kota', function (Blueprint $table) {
            $table->boolean('status_mahasiswa')->nullable(false)->change();
            $table->boolean('status_dosen_pembimbing_1')->nullable(false)->change();
            $table->boolean('status_dosen_pembimbing_2')->nullable(false)->change();
            $table->boolean('status_dosen_penguji_1')->nullable(false)->change();
            $table->boolean('status_dosen_penguji_2')->nullable(false)->change();
            $table->boolean('status_koordinator_ta')->nullable(false)->change();
        });

        // Mengembalikan ENUM di tabel kehadiran ke nilai sebelumnya
        DB::statement("ALTER TABLE kehadiran MODIFY COLUMN status_hadir ENUM('hadir', 'tidak_hadir') NOT NULL");
        DB::statement("ALTER TABLE kehadiran MODIFY COLUMN status_kelulusan ENUM('lulus_tanpa_perbaikan_laporan','lulus_dengan_perbaikan_laporan','mengulang_sidang_tugas_akhir','tidak_lulus') NOT NULL DEFAULT 'lulus_tanpa_perbaikan_laporan'");

        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dateTime('batas_revisi')->nullable(false)->change();
        });
    }
};
