<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Schema Changes v3.0.3
 * 
 * This migration includes multiple team contributions for database structure improvements
*/
return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration includes multiple team contributions for database structure improvements
     */
    public function up(): void
    {
        // === Byte Bandits ===
        // 1. Fix typo in fasilitas table column
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->renameColumn('id_fasililtas', 'id_fasilitas');
        });

        // === Ambasing ===
        // 2. Modify role_user enum in user table and role_dosen in dosen table
        DB::statement("ALTER TABLE `user` MODIFY `role_user` ENUM('mahasiswa', 'dosen', 'admin') NOT NULL");
        DB::statement("ALTER TABLE `dosen` MODIFY `role_dosen` ENUM('dosen', 'koordinator_ta', 'kajur') NOT NULL");
        // 3. Add password_reset table
        Schema::create('password_reset', function (Blueprint $table) {
            $table->string('email', 255)->primary();
            $table->string('token', 255);
            $table->timestamp('created_at')->default(value: DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(value: DB::raw('CURRENT_TIMESTAMP'));
        });

        // === Heaplow ===
        // 4. Modify dosen table
        Schema::table('dosen', function (Blueprint $table) {
            $table->enum('bersedia_membimbing', ['bersedia', 'tidak_bersedia']);
            $table->unsignedBigInteger('id_prodi');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->dropColumn('maks_bimbingan_d4');
            $table->dropColumn('maks_bimbingan_d3');
        });
        // 4.1 Menambahkan kolom updated_at pada tabel pengajuan_pembimbing
        Schema::table('pengajuan_pembimbing', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
        
        // 5. Add kuota_membimbing table
        Schema::create('kuota_membimbing', function (Blueprint $table) {
            $table->string('nip', 22);
            $table->unsignedBigInteger('id_prodi');
            $table->integer('jumlah');
            
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
        });
        
        // 6. Modify prodi table
        Schema::table('prodi', function (Blueprint $table) {
            $table->integer('maksimal_mahasiswa_bimbingan');
        });
        
        // 7. Modify alokasi_pembimbing table
        Schema::table('alokasi_pembimbing', function (Blueprint $table) {
            $table->enum('status_alokasi', ['fix', 'belum_fix']);
            $table->text('catatan')->nullable();
        });
        
        // 8. Add preferensi_kota table
        Schema::create('preferensi_kota', function (Blueprint $table) {
            $table->string('nip', 22);
            $table->unsignedBigInteger('id_kota');
            
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('id_kota')->references('id_kota')->on('kota');
        });

        // === Tenggo ===
        // 9. Add review_dosen_pembimbing table
        Schema::create('review_dosen_pembimbing', function (Blueprint $table) {
            $table->id('id_review');
            $table->text('review');
            $table->timestamps();
            $table->unsignedBigInteger('id_dokumen');
            $table->string('nip', 22);
            
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        // 10. Modify ambang_batas table and change string to integer for id_ambang_batas
        Schema::table('ambang_batas', function (Blueprint $table) {
            // Change type data string to integer id_ambang_batas
            $table->unsignedBigInteger('id_ambang_batas')->autoIncrement()->change();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('status_ambang_batas', ['digunakan', 'tidak_digunakan']);
            $table->string('nip', 22);
            $table->foreign('nip')->references('nip')->on('dosen');
        });

        // === Indomilk ===
        // 11 & 12 . Delete label table (first remove foreign key constraints)
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropForeign(['id_label']);
            $table->dropColumn('id_label');
        });
        
        Schema::dropIfExists('label');

        // === Indomilk ===
        // 13. Update nullable attributes for dokumen columns
        Schema::table('dokumen', function (Blueprint $table) {
            // 1. Make fields nullable that weren't before
            $table->float('persentase_plagiarisme')->nullable()->change();
            $table->boolean('highlight_dokumen')->nullable()->change();
            $table->text('review')->nullable()->change();
            
            // 2. Add proses_cek to status_plagiarisme enum
            DB::statement("ALTER TABLE `dokumen` MODIFY `status_plagiarisme` ENUM('plagiarisme', 'tidak_plagiarisme', 'proses_cek')");
            
            // 3. Add new id_ambang_batas field
            $table->unsignedBigInteger('id_ambang_batas')->nullable()->after('status_plagiarisme');
            $table->foreign('id_ambang_batas')->references('id_ambang_batas')->on('ambang_batas');
            
            // Rename uploaded_at to created_at
            $table->renameColumn('uploaded_at', 'created_at');
        });

        // === Romusa ===
        // 14. Complete restructure of all Romusa-related tables

        Schema::disableForeignKeyConstraints();

        // Drop existing tables that will be restructured
        Schema::dropIfExists('penilaian_rubrik');
        Schema::dropIfExists('penilaian_kategori');
        Schema::dropIfExists('rubrik_penilaian');
        Schema::dropIfExists('kategori_penilaian');
        Schema::dropIfExists('form_penilaian');

        // Create new form_penilaian table with the new structure
        Schema::create('form_penilaian', function (Blueprint $table) {
            $table->id('kode_fta');
            $table->string('nama_fta', 50)->nullable(false);
            $table->unsignedBigInteger('id_prodi');
            $table->enum('jenis_form', ['penilaian', 'feedback']);
            $table->date('tanggal_tenggat_pengisian')->nullable(false);
            $table->timestamps();
            
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
        });

        // Create komponen_nilai_akhir table
        Schema::create('komponen_nilai_akhir', function (Blueprint $table) {
            $table->id('id_komponen');
            $table->enum('nama_komponen', ['uts', 'uas', 'lain_lain']);
            $table->tinyInteger('bobot_komponen')->nullable(false);
            $table->timestamps();
        });

        // Create sumber_nilai table
        Schema::create('sumber_nilai', function (Blueprint $table) {
            $table->id('id_sumber');
            $table->unsignedBigInteger('id_komponen');
            $table->unsignedBigInteger('sumber');
            
            $table->foreign('id_komponen')->references('id_komponen')->on('komponen_nilai_akhir')->onDelete('cascade');
            $table->foreign('sumber')->references('id_kategori')->on('kategori_penilaian')->onDelete('cascade');
        });

        // Create rekapitulasi_nilai_akhir table
        Schema::create('rekapitulasi_nilai_akhir', function (Blueprint $table) {
            $table->id('id_rekap');
            $table->string('nim', 22);
            $table->float('nilai_uts')->nullable(false);
            $table->float('nilai_uas')->nullable(false);
            $table->float('nilai_lain_lain')->nullable(false);
            $table->float('nilai_akhir')->nullable(false);
            
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });

        // Create kategori_penilaian table
        Schema::create('kategori_penilaian', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->unsignedBigInteger('kode_fta');
            $table->string('nama_kategori', 20)->nullable(false);
            
            $table->foreign('kode_fta')->references('kode_fta')->on('form_penilaian')->onDelete('cascade');
        });

        // Create nilai_kategori table
        Schema::create('nilai_kategori', function (Blueprint $table) {
            $table->string('nim', 22);
            $table->string('nip', 22);
            $table->unsignedBigInteger('id_kategori');
            $table->float('nilai')->nullable(false);
            
            $table->primary(['nim', 'nip', 'id_kategori']);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_penilaian')->onDelete('cascade');
        });

        // Create kriteria_penilaian table
        Schema::create('kriteria_penilaian', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->unsignedBigInteger('kode_fta');
            $table->string('nama_kriteria', 100)->nullable(false);
            $table->tinyInteger('bobot_kriteria')->nullable(false);
            
            $table->foreign('kode_fta')->references('kode_fta')->on('form_penilaian')->onDelete('cascade');
        });

        // Create nilai_kriteria table
        Schema::create('nilai_kriteria', function (Blueprint $table) {
            $table->string('nim', 22);
            $table->string('nip', 22);
            $table->unsignedBigInteger('id_kriteria');
            $table->float('nilai_kriteria')->nullable(false);
            $table->enum('status_penilaian', ['draf', 'dipublikasikan'])->default('draf')->nullable(false);
            $table->timestamps();
            
            $table->primary(['nim', 'nip', 'id_kriteria']);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria_penilaian')->onDelete('cascade');
        });

        // Create rubrik table
        Schema::create('rubrik', function (Blueprint $table) {
            $table->id('id_rubrik');
            $table->unsignedBigInteger('id_kriteria');
            $table->text('nama_rubrik');
            
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria_penilaian')->onDelete('cascade');
        });

        // Create rentang_nilai table
        Schema::create('rentang_nilai', function (Blueprint $table) {
            $table->string('id_nilai', 3)->primary();
            $table->tinyInteger('batas_bawah');
            $table->tinyInteger('batas_atas');
        });

        // Create detail_rubrik table
        Schema::create('detail_rubrik', function (Blueprint $table) {
            $table->id('id_detail_rubrik');
            $table->unsignedBigInteger('id_rubrik');
            $table->text('detail_rubrik_penilaian');
            $table->string('id_nilai', 3);
            
            $table->foreign('id_rubrik')->references('id_rubrik')->on('rubrik')->onDelete('cascade');
            $table->foreign('id_nilai')->references('id_nilai')->on('rentang_nilai')->onDelete('restrict');
        });

        // Create aspek_feedback table
        Schema::create('aspek_feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->unsignedBigInteger('kode_fta');
            $table->string('nama_aspek_feedback', 255);
            
            $table->foreign('kode_fta')->references('kode_fta')->on('form_penilaian')->onDelete('cascade');
        });

        // Create detail_feedback table
        Schema::create('detail_feedback', function (Blueprint $table) {
            $table->id('id_detail_feedback');
            $table->unsignedBigInteger('id_feedback');
            $table->unsignedBigInteger('id_kota');
            $table->string('nip', 22);
            $table->enum('status_penilaian', ['draf', 'dipublikasikan'])->default('draf')->nullable(false);
            $table->text('isi_feedback')->nullable(false);
            
            $table->foreign('id_feedback')->references('id_feedback')->on('aspek_feedback')->onDelete('cascade');
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('cascade');
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
        
        // === Lemini ===
        // 15. Add template_notifikasi table
        Schema::create('template_notifikasi', function (Blueprint $table) {
            $table->id('id_template_notifikasi');
            $table->string('judul_notifikasi', 255);
            $table->text('isi_in_apps');
            $table->text('isi_in_email');
            $table->enum('jenis_notifikasi', ['pemberitahuan', 'reminder']);
        });
        //15.1 Menambahkan kolom updated_at pada tabel notifikasi
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // === TM ===
        // 16. Add pengajuan_jadwal_kota table
        Schema::create('pengajuan_jadwal_kota', function (Blueprint $table) {
            $table->boolean('status_mahasiswa');
            $table->boolean('status_dosen_pembimbing_1');
            $table->boolean('status_dosen_pembimbing_2');
            $table->boolean('status_dosen_penguji_1');
            $table->boolean('status_dosen_penguji_2');
            $table->boolean('status_koordinator_ta');
            $table->unsignedBigInteger('id_penjadwalan');
            $table->unsignedBigInteger('id_kota');
            $table->string('nip', 22);
            
            $table->foreign('id_penjadwalan')->references('id_penjadwalan')->on('penjadwalan');
            $table->foreign('id_kota')->references('id_kota')->on('kota');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        // 17. Rename konfirmasi table to verifikasi_berkas_pengajuan and modify
            // Drop foreign key first while table is still named 'konfirmasi'
            Schema::table('konfirmasi', function (Blueprint $table) {
                Schema::disableForeignKeyConstraints();
                $table->dropForeign(['id_penjadwalan']);
                $table->dropColumn('id_penjadwalan');
                Schema::enableForeignKeyConstraints();
            });
        Schema::rename('konfirmasi', 'verifikasi_berkas_pengajuan');
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->text('catatan')->nullable(false);
            $table->foreign('nip')->references('nip')->on('dosen');
        });

        // 18. Change int to tinyint
            DB::statement("ALTER TABLE `penjadwalan` MODIFY `sesi` TINYINT NOT NULL");
            DB::statement("ALTER TABLE `prodi` MODIFY `maksimal_anggota_kota` TINYINT NOT NULL");
            DB::statement("ALTER TABLE `prodi` MODIFY `maksimal_mahasiswa_bimbingan` TINYINT NOT NULL");
            DB::statement("ALTER TABLE `prioritas_pembimbing` MODIFY `urutan_prioritas` TINYINT");
            DB::statement("ALTER TABLE `alokasi_pembimbing` MODIFY `urutan_prioritas_terpilih` TINYINT NOT NULL");
            DB::statement("ALTER TABLE `kuota_membimbing` MODIFY `jumlah` TINYINT NOT NULL");
            DB::statement("ALTER TABLE `dokumen` MODIFY `versi` TINYINT NOT NULL");
            DB::statement("ALTER TABLE `ruang_fasilitas` MODIFY `jumlah_fasilitas` TINYINT");
            DB::statement("ALTER TABLE `fasilitas` MODIFY `jumlah_total_fasilitas` TINYINT");
        //19. === TM ===
            // Menambahkan kolom baru di tabel verifikasi_berkas_pengajuan
            Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
                $table->dateTime('tanggal_pengajuan');
                $table->dateTime('tanggal_verifikasi');
                $table->enum('jenis_pengajuan', ['sidang_akhir', 'seminar_3']);
            });
        // 20. Menambah kolom di tabel penjadwalan dan menghapus foreign key untuk id_ruangan
        Schema::table('penjadwalan', function (Blueprint $table) {
            $table->dateTime('start');
            $table->dateTime('end');
            $table->dropForeign(['id_ruangan']);
        });
        // 21. Menghapus tabel gedung, ruangan, fasilitas, ruangan_fasilitas
        Schema::disableForeignKeyConstraints();
        // Drop in the reverse order of dependencies
        Schema::dropIfExists('ruang_fasilitas');  // Drop first as it references both ruangan and fasilitas
        Schema::dropIfExists('ruangan');          // Drop second as it references gedung
        Schema::dropIfExists('fasilitas');        // No references, can drop anytime
        Schema::dropIfExists('gedung');           // Drop last as it's referenced by ruangan
        Schema::enableForeignKeyConstraints();  
        // 22. Menghapus kolom id prodi dari tabel dosen
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropForeign(['id_prodi']);
            $table->dropColumn('id_prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all changes in reverse order
        // 22. Revert column prodi
        Schema::table('dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_prodi');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
        });
        // 21. Recreate gedung, ruangan, fasilitas, ruangan_fasilitas tables
        Schema::create('gedung', function (Blueprint $table) {
            $table->string('kode_gedung', 1)->primary();
            $table->string('nama_gedung', 100)->unique();
        });
        
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string('kode_ruangan', 6);
            $table->string('nama_ruangan', 127)->unique();
            $table->enum('status_ruangan', ['tersedia', 'tidak_tersedia']);
            $table->string('kode_gedung', 1);
            $table->string('link_ruangan', 45);
            
            $table->foreign('kode_gedung')->references('kode_gedung')->on('gedung');
        });
        
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id('id_fasilitas'); // Note: Using the corrected column name
            $table->string('nama_fasilitas', 100);
            $table->integer('jumlah_total_fasilitas');
        });
        
        Schema::create('ruang_fasilitas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_fasilitas');
            $table->unsignedBigInteger('id_ruangan');
            $table->integer('jumlah_fasilitas');
            
            $table->primary(['id_ruangan', 'id_fasilitas']);
            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas');
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan');
        });
        // 20. Revert column penjadwalan
        Schema::table('penjadwalan', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('end');
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan');
        });
        // 19. Mendrop kolom baru di tabel verifikasi_berkas_pengajuan
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            $table->dropColumn('tanggal_pengajuan');
            $table->dropColumn('tanggal_verifikasi');
            $table->dropColumn('jenis_pengajuan');
        });
        // 18. Revert tinyint changes
        // Revert tinyint back to int
            Schema::table('penjadwalan', function (Blueprint $table) {
                $table->integer('sesi')->change();
            });

            Schema::table('prodi', function (Blueprint $table) {
                $table->integer('maksimal_anggota_kota')->change();
                $table->integer('maksimal_mahasiswa_bimbingan')->change();
            });

            Schema::table('prioritas_pembimbing', function (Blueprint $table) {
                $table->integer('urutan_prioritas')->change();
            });

            Schema::table('alokasi_pembimbing', function (Blueprint $table) {
                $table->integer('urutan_prioritas_terpilih')->change();
            });

            Schema::table('kuota_membimbing', function (Blueprint $table) {
                $table->integer('jumlah')->change();
            });

            Schema::table('dokumen', function (Blueprint $table) {
                $table->integer('versi')->change();
            });

            Schema::table('ruang_fasilitas', function (Blueprint $table) {
                $table->integer('jumlah_fasilitas')->change();
            });

            Schema::table('fasilitas', function (Blueprint $table) {
                $table->integer('jumlah_total_fasilitas')->change();
            });
        
        // 17. Rename verifikasi_berkas_pengajuan back to konfirmasi and revert modifications
        Schema::disableForeignKeyConstraints();
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            $table->dropColumn('id_pengajuan');
            $table->unsignedBigInteger('id_penjadwalan');
            $table->dropColumn('catatan');
            $table->dropForeign(['nip']); // Only drop the foreign key, not the column
        });
        Schema::rename('verifikasi_berkas_pengajuan', 'konfirmasi');

        Schema::enableForeignKeyConstraints();
        // 16. Drop pengajuan_jadwal_kota table
        Schema::dropIfExists('pengajuan_jadwal_kota');
        
        // 15. Drop template_notifikasi table
        Schema::dropIfExists('template_notifikasi');
        
        // 14. Drop all Romusa-related tables
        Schema::disableForeignKeyConstraints();
        // Drop all new tables in reverse order
        Schema::dropIfExists('detail_feedback');
        Schema::dropIfExists('aspek_feedback');
        Schema::dropIfExists('detail_rubrik');
        Schema::dropIfExists('rentang_nilai');
        Schema::dropIfExists('rubrik');
        Schema::dropIfExists('nilai_kriteria');
        Schema::dropIfExists('kriteria_penilaian');
        Schema::dropIfExists('nilai_kategori');
        Schema::dropIfExists('kategori_penilaian');
        Schema::dropIfExists('rekapitulasi_nilai_akhir');
        Schema::dropIfExists('sumber_nilai');
        Schema::dropIfExists('komponen_nilai_akhir');
        Schema::dropIfExists('form_penilaian');
        
        // Recreate the original tables
        Schema::create('form_penilaian', function (Blueprint $table) {
            $table->id('id_form_penilaian');
            $table->string('nama_formulir_penilaian', 50);
            $table->string('nip', 20);
            $table->year('tahun_ajaran');
            $table->enum('status_form', ['pending', 'draft', 'published', 'used', 'finished']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::create('kategori_penilaian', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->unsignedBigInteger('id_form_penilaian');
            $table->string('nama_kategori', 50);
            $table->integer('bobot_kategori');
            
            $table->foreign('id_form_penilaian')->references('id_form_penilaian')->on('form_penilaian')->onDelete('cascade');
        });
        
        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id('id_rubrik');
            $table->unsignedBigInteger('id_kategori');
            $table->string('judul_rubrik', 100);
            $table->text('detail_rubrik')->nullable();
            $table->integer('bobot_rubrik');
            
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_penilaian')->onDelete('cascade');
        });
        
        Schema::create('penilaian_kategori', function (Blueprint $table) {
            $table->string('nim', 22);
            $table->unsignedBigInteger('id_kategori');
            $table->float('nilai_kategori');
            
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_penilaian');
        });
        
        Schema::create('penilaian_rubrik', function (Blueprint $table) {
            $table->string('nim', 22);
            $table->string('nip', 20);
            $table->unsignedBigInteger('id_rubrik');
            $table->float('nilai_rubrik');
            $table->text('detail_feedback')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('id_rubrik')->references('id_rubrik')->on('rubrik_penilaian');
        });
        
        Schema::enableForeignKeyConstraints();

        // 13. Revert nullable attributes for dokumen columns
        Schema::table('dokumen', function (Blueprint $table) {
            // 1. Rename created_at back to uploaded_at
            $table->renameColumn('created_at', 'uploaded_at');
            
            // 2. Remove id_ambang_batas field
            $table->dropForeign(['id_ambang_batas']);
            $table->dropColumn('id_ambang_batas');
            
            // 3. Restore original enum without 'proses_cek'
            DB::statement("ALTER TABLE `dokumen` MODIFY `status_plagiarisme` ENUM('plagiarisme', 'tidak_plagiarisme')");
            
            // 4. Make fields non-nullable again
            $table->float('persentase_plagiarisme')->nullable(false)->change();
            $table->boolean('highlight_dokumen')->nullable(false)->change();
            $table->text('review')->nullable(false)->change();
        });

        // 11-12. Recreate label table and add back references
        Schema::create('label', function (Blueprint $table) {
            $table->id('id_label');
            $table->string('nama_label', 255)->unique();
            $table->unsignedBigInteger('id_kota');
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('cascade');
        });
        
        Schema::table('dokumen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_label')->nullable();
            $table->foreign('id_label')->references('id_label')->on('label')->onDelete('cascade');
        });
        
        // 10. Revert changes to ambang_batas table
        
        Schema::table('ambang_batas', function (Blueprint $table) {
            $table->string('id_ambang_batas', 255)->change();
            $table->dropForeign(['nip']);
            $table->dropColumn('nip');
            $table->dropColumn('status_ambang_batas');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        
        // 9. Drop review_dosen_pembimbing table
        Schema::dropIfExists('review_dosen_pembimbing');
        
        // 8. Drop preferensi_kota table
        Schema::dropIfExists('preferensi_kota');
        
        // 7. Revert changes to alokasi_pembimbing table
        Schema::table('alokasi_pembimbing', function (Blueprint $table) {
            $table->dropColumn('catatan');
            $table->dropColumn('status_alokasi');
        });
        
        // 6. Revert changes to prodi table
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropColumn('maksimal_mahasiswa_bimbingan');
        });
        
        // 5. Drop kuota_membimbing table
        Schema::dropIfExists('kuota_membimbing');
        
        // 4. Revert changes to dosen table
        Schema::table('dosen', function (Blueprint $table) {
            $table->integer('maks_bimbingan_d4');
            $table->integer('maks_bimbingan_d3');
            $table->dropForeign(['id_prodi']);
            $table->dropColumn('id_prodi');
            $table->dropColumn('bersedia_membimbing');
        });
        
        // 3. Drop password_reset table
        Schema::dropIfExists('password_reset');
        
        // 2. Revert changes to user table role_user enum
        DB::statement("ALTER TABLE `user` MODIFY `role_user` ENUM('mahasiswa', 'dosen') NOT NULL");
        
        // 1. Revert column name change in fasilitas table
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->renameColumn('id_fasilitas', 'id_fasililtas');
        });
    }
};
