<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Ubah kolom nama_komponen menjadi varchar(25) di komponen_nilai_akhir
        Schema::table('komponen_nilai_akhir', function (Blueprint $table) {
            $table->string('nama_komponen', 25)->change();
        });

        // 2. Hapus kolom nama_kategori dari tabel kategori_penilaian
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->dropColumn('nama_kategori');
        });

        // 3. Pindahkan kolom status_penilaian dari tabel nilai_kriteria ke nilai_kategori
        Schema::table('nilai_kriteria', function (Blueprint $table) {
            $table->dropColumn('status_penilaian_dosen');
        });

        Schema::table('nilai_kategori', function (Blueprint $table) {
            $table->enum('status_penilaian_dosen', ['draf', 'dipublikasikan', 'belum_dinilai'])->default('belum_dinilai')->after('id_kategori');
        });

        // Tambahkan kolom status_user ke tabel user
        Schema::table('user', function (Blueprint $table) {
            $table->enum('status_user', ['aktif', 'nonaktif'])->after('photo');
        });

        // Hapus kolom status_dosen dari tabel dosen
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('status_dosen');
        });

        Schema::table('preferensi_kota', function (Blueprint $table) {
            $table->boolean('status')->default(false)->after('id_kota');
        });
    }

    public function down()
    {
        // 1. Kembalikan kolom nama_komponen menjadi enum di komponen_nilai_akhir
        Schema::table('komponen_nilai_akhir', function (Blueprint $table) {
            $table->enum('nama_komponen', ['uts', 'uas', 'lain_lain'])->change();
        });

        // 2. Tambahkan kembali kolom nama_kategori di tabel kategori_penilaian
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->string('nama_kategori', 20);
        });

        // 3. Pindahkan kolom status_penilaian kembali ke nilai_kriteria
        Schema::table('nilai_kriteria', function (Blueprint $table) {
            $table->enum('status_penilaian_dosen', ['draf', 'dipublikasikan', 'belum_dinilai'])->default('belum_dinilai');
        });

        Schema::table('nilai_kategori', function (Blueprint $table) {
            $table->dropColumn('status_penilaian');
        });

        // Kembalikan kolom status_dosen ke tabel dosen
        Schema::table('dosen', function (Blueprint $table) {
            $table->enum('status_dosen', ['aktif', 'nonaktif'])->after('kode_dosen');
        });

        // Hapus kolom status_user dari tabel user
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('status_user');
        });

        // Hapus kolom status dari tabel preferensi_kota
        Schema::table('preferensi_kota', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};