<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // === Heaplow ===
        // 1. Modify status_pengajuan enum in pengajuan_pembimbing table
        DB::statement("ALTER TABLE `pengajuan_pembimbing` MODIFY `status_pengajuan` ENUM('diproses', 'diterima') NOT NULL DEFAULT 'diproses'");
    
        // 1.1 Rename alokasi_pembimbing table to alokasi_dosen, rename primary key, and add tipe_alokasi column
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('alokasi_pembimbing') && !Schema::hasTable('alokasi_dosen')) {
            // Step 1: Rename the table
            Schema::rename('alokasi_pembimbing', 'alokasi_dosen');

            // Step 2: Add tipe_alokasi column
            Schema::table('alokasi_dosen', function (Blueprint $table) {
                $table->enum('tipe_alokasi', ['penguji', 'pembimbing'])->default('pembimbing');
            });
            
            // Step 3: Rename the primary key column
            DB::statement('ALTER TABLE alokasi_dosen CHANGE id_alokasi_pembimbing id_alokasi INT AUTO_INCREMENT');
        }

        Schema::enableForeignKeyConstraints();
        // === Lemini ===
        // 2. Modify preferensi_notifikasi table
        if (Schema::hasColumn('preferensi_notifikasi', 'tipe_notifikasi')) {
            Schema::table('preferensi_notifikasi', function (Blueprint $table) {
                $table->dropColumn('tipe_notifikasi');
            });
        }
        
        Schema::table('preferensi_notifikasi', function (Blueprint $table) {
            if (Schema::hasColumn('preferensi_notifikasi', 'in_app')) {
                $table->renameColumn('in_app', 'reminder_h5');
            }
        });
        
        // 3. Remove sumber_notifikasi from notifikasi table
        if (Schema::hasColumn('notifikasi', 'sumber_notifikasi')) {
            Schema::table('notifikasi', function (Blueprint $table) {
                $table->dropColumn('sumber_notifikasi');
            });
        }
        
        // === Indomilk ===
        // 4. Add columns to dokumen table
        Schema::table('dokumen', function (Blueprint $table) {
            $table->string('file_path', 255)->nullable();
            $table->string('kode_fta', 255)->nullable();
        });
        
        // 5. Modify foreign key for list_kalimat_plagiarisme
        Schema::table('list_kalimat_plagiarisme', function (Blueprint $table) {
            $table->dropForeign(['id_dokumen']);
            $table->foreign('id_dokumen')
                  ->references('id_dokumen')
                  ->on('dokumen')
                  ->onDelete('cascade');
        });
        
        // === Ambasing ===
        // 6. Make id_kota nullable in mahasiswa table
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kota')->nullable()->change();
        });
        
        // 7. Make columns nullable in kota table
        Schema::table('kota', function (Blueprint $table) {
            $table->text('judul_ta')->nullable()->change();
            $table->unsignedBigInteger('id_bidang')->nullable()->change();
        });
        
        // 8. Add fta_20 to pengajuan_pisah_kota table
        Schema::table('pengajuan_pisah_kota', function (Blueprint $table) {
            $table->string('fta_20', 255)->nullable();
        });
        
        // === Tenggo ===
        // 9. Modify status_plagiarisme enum and remove review column in dokumen table
        DB::statement("ALTER TABLE `dokumen` MODIFY `status_plagiarisme` ENUM('plagiarisme', 'tidak_plagiarisme', 'sedang_proses')");
        
        if (Schema::hasColumn('dokumen', 'review')) {
            Schema::table('dokumen', function (Blueprint $table) {
                $table->dropColumn('review');
            });
        }
        
        // === TM ===
        // 10. Remove nip from penjadwalan table
        Schema::table('penjadwalan', function (Blueprint $table) {
            if (Schema::hasColumn('penjadwalan', 'nip')) {
                $table->dropForeign(['nip']);
                $table->dropColumn('nip');
            }
        });
        
        // 11. Remove nip from pengajuan_jadwal_kota table
        Schema::table('pengajuan_jadwal_kota', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_jadwal_kota', 'nip')) {
                $table->dropForeign(['nip']);
                $table->dropColumn('nip');
            }
        });
        
        // 12. Add columns to kehadiran table
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->id('id_kehadiran');
            $table->enum('status_kelulusan', [
                'lulus_tanpa_perbaikan_laporan', 
                'lulus_dengan_perbaikan_laporan', 
                'mengulang_sidang_tugas_akhir', 
                'tidak_lulus'
            ]);
            $table->dateTime('batas_revisi')->nullable();
            $table->string('foto_sidang', 255)->nullable();
        });
        
        // 13. Create pembatalan table
        Schema::create('pembatalan', function (Blueprint $table) {
            $table->id('id_pembatalan');
            $table->unsignedBigInteger('id_penjadwalan');
            $table->text('alasan_pembatalan')->nullable();
            $table->string('nip', 22);
            $table->boolean('status_pembatalan');
            
            $table->foreign('id_penjadwalan')->references('id_penjadwalan')->on('penjadwalan');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        // 14. Add id_kota to verifikasi_berkas_pengajuan table
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kota')->nullable();
            $table->foreign('id_kota')->references('id_kota')->on('kota');
        });
        
        // === Romusa ===
        // 15. Add columns to form_penilaian table and modify kode_fta
        Schema::disableForeignKeyConstraints();
        
        // First drop foreign keys that reference kode_fta
        
        Schema::table('aspek_feedback', function (Blueprint $table) {
            //ALTER TABLE `aspek_feedback` DROP INDEX `aspek_feedback_kode_fta_foreign`
            DB::statement("ALTER TABLE aspek_feedback DROP FOREIGN KEY aspek_feedback_kode_fta_foreign");
            DB::statement("ALTER TABLE `aspek_feedback` DROP `kode_fta`");
            $table->unsignedBigInteger('id_fta')->nullable();
        });

        Schema::table('kategori_penilaian', function (Blueprint $table) {
            DB::statement("ALTER TABLE `kategori_penilaian` DROP FOREIGN KEY kategori_penilaian_kode_fta_foreign");
            DB::statement("ALTER TABLE `kategori_penilaian` DROP `kode_fta`");
            $table->unsignedBigInteger('id_fta')->nullable();
        });

        Schema::table('kriteria_penilaian', function (Blueprint $table) {
            DB::statement("ALTER TABLE `kriteria_penilaian` DROP FOREIGN KEY kriteria_penilaian_kode_fta_foreign");
            DB::statement("ALTER TABLE `kriteria_penilaian` DROP `kode_fta`");
            $table->unsignedBigInteger('id_fta')->nullable();
        });
        
        // First drop primary key from kode_fta and change its type
        DB::statement("ALTER TABLE form_penilaian DROP PRIMARY KEY, MODIFY kode_fta VARCHAR(10)");

        // Then add the new id_fta as primary key and other column
        Schema::table('form_penilaian', function (Blueprint $table) {
            $table->id('id_fta');
            $table->time('waktu_tenggat_pengisian');
        });
        
        // Now update all foreign keys to use id_fta instead of kode_fta
        // Add foreign key constraints to each table
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->foreign('id_fta')->references('id_fta')->on('form_penilaian')->onDelete('cascade');
        });

        Schema::table('kriteria_penilaian', function (Blueprint $table) {
            $table->foreign('id_fta')->references('id_fta')->on('form_penilaian')->onDelete('cascade');
        });

        Schema::table('aspek_feedback', function (Blueprint $table) {
            $table->foreign('id_fta')->references('id_fta')->on('form_penilaian')->onDelete('cascade');
        });
        
        // 16. Add kunci_penilaian column to kategori_penilaian table
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->boolean('kunci_penilaian')->default(false);
        });
        
        // 17. Modify status_penilaian enum in detail_feedback table
        DB::statement("ALTER TABLE `detail_feedback` MODIFY `status_penilaian` ENUM('draf', 'dipublikasikan', 'belum_dinilai') NOT NULL DEFAULT 'belum_dinilai'");
        // Update existing values before renaming
        DB::statement("UPDATE detail_feedback SET status_penilaian = 'belum_dinilai' WHERE status_penilaian = 'draf'");
        
        // Rename column and set constraints
        Schema::table('detail_feedback', function (Blueprint $table) {
            $table->renameColumn('status_penilaian', 'status_penilaian_dosen');
        });
        // 18. Rename kode_fta to id_fta in kategori_penilaian and update foreign key
        // Sudah dilakukan di no 15
        
        // 19. Rename kode_fta to id_fta in kriteria_penilaian and update foreign key
        // Sudah dilakukan di no 15
        
        // 20. Rename kode_fta to id_fta in aspek_feedback and update foreign key
        // Sudah dilakukan di no 15
        
        // 21. Change data types in rentang_nilai table
        DB::statement("ALTER TABLE `rentang_nilai` MODIFY `batas_bawah` FLOAT");
        DB::statement("ALTER TABLE `rentang_nilai` MODIFY `batas_atas` FLOAT");
        
        // 22. Rename status_penilaian to status_penilaian_dosen in nilai_kriteria and update default
        Schema::table('nilai_kriteria', function (Blueprint $table) {
            $table->renameColumn('status_penilaian', 'status_penilaian_dosen');
        });
        
        DB::statement("ALTER TABLE `nilai_kriteria` MODIFY `status_penilaian_dosen` ENUM('draf', 'dipublikasikan', 'belum_dinilai') NOT NULL DEFAULT 'belum_dinilai'");
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        
        // === Romusa (Reverse) ===
        // 22. Revert status_penilaian_dosen back to status_penilaian in nilai_kriteria
        DB::statement("ALTER TABLE `nilai_kriteria` MODIFY `status_penilaian_dosen` ENUM('draf', 'dipublikasikan') NOT NULL DEFAULT 'draf'");
        
        Schema::table('nilai_kriteria', function (Blueprint $table) {
            $table->renameColumn('status_penilaian_dosen', 'status_penilaian');
        });
        
        // 21. Revert data types in rentang_nilai table
        DB::statement("ALTER TABLE `rentang_nilai` MODIFY `batas_bawah` TINYINT");
        DB::statement("ALTER TABLE `rentang_nilai` MODIFY `batas_atas` TINYINT");
        
        // 20. Revert id_fta back to kode_fta in aspek_feedback
        Schema::table('aspek_feedback', function (Blueprint $table) {
            $table->dropForeign(['id_fta']);
            $table->renameColumn('id_fta', 'kode_fta');
            $table->unsignedBigInteger('kode_fta')->change();
            $table->foreign('kode_fta')->references('kode_fta')->on('form_penilaian')->onDelete('cascade');
        });
        
        // 19. Revert id_fta back to kode_fta in kriteria_penilaian
        Schema::table('kriteria_penilaian', function (Blueprint $table) {
            $table->dropForeign(['id_fta']);
            $table->renameColumn('id_fta', 'kode_fta');
            $table->unsignedBigInteger('kode_fta')->change();
            $table->foreign('kode_fta')->references('kode_fta')->on('form_penilaian')->onDelete('cascade');
        });
        
        // 18. Revert id_fta back to kode_fta in kategori_penilaian
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->dropForeign(['id_fta']);
            $table->renameColumn('id_fta', 'kode_fta');
            $table->unsignedBigInteger('kode_fta')->change();
            $table->foreign('kode_fta')->references('kode_fta')->on('form_penilaian')->onDelete('cascade');
        });
        
        // 17. Revert status_penilaian_dosen back to status_penilaian in detail_feedback
        Schema::table('detail_feedback', function (Blueprint $table) {
            $table->renameColumn('status_penilaian_dosen', 'status_penilaian');
        });
        
        DB::statement("ALTER TABLE `detail_feedback` MODIFY `status_penilaian` ENUM('draf', 'dipublikasikan') NOT NULL DEFAULT 'draf'");
        DB::statement("UPDATE detail_feedback SET status_penilaian = 'draf' WHERE status_penilaian = 'belum_dinilai'");
        
        // 16. Remove kunci_penilaian column from kategori_penilaian
        Schema::table('kategori_penilaian', function (Blueprint $table) {
            $table->dropColumn('kunci_penilaian');
        });
        
        // 15. Revert changes to form_penilaian table
        // Copy data back from id_fta to kode_fta where needed
        DB::statement("UPDATE form_penilaian SET kode_fta = id_fta WHERE kode_fta IS NULL OR kode_fta = ''");
        DB::statement("ALTER TABLE form_penilaian MODIFY kode_fta INT AUTO_INCREMENT");
        
        Schema::table('form_penilaian', function (Blueprint $table) {
            $table->dropColumn('waktu_tenggat_pengisian');
            $table->dropColumn('id_fta');
        });
        
        // === TM (Reverse) ===
        // 14. Remove id_kota from verifikasi_berkas_pengajuan
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            if (Schema::hasColumn('verifikasi_berkas_pengajuan', 'id_kota')) {
                $table->dropForeign(['id_kota']);
                $table->dropColumn('id_kota');
            }
        });
        
        // 13. Drop pembatalan table
        Schema::dropIfExists('pembatalan');
        
        // 12. Remove added columns from kehadiran table
        Schema::table('kehadiran', function (Blueprint $table) {
            $table->dropColumn('foto_sidang');
            $table->dropColumn('batas_revisi');
            $table->dropColumn('status_kelulusan');
            $table->dropColumn('id_kehadiran');
        });
        
        // 11. Add nip back to pengajuan_jadwal_kota
        Schema::table('pengajuan_jadwal_kota', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_jadwal_kota', 'nip')) {
                $table->string('nip', 22)->nullable();
                $table->foreign('nip')->references('nip')->on('dosen');
            }
        });
        
        // 10. Add nip back to penjadwalan
        Schema::table('penjadwalan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjadwalan', 'nip')) {
                $table->string('nip', 22)->nullable();
                $table->foreign('nip')->references('nip')->on('dosen');
            }
        });
        
        // === Tenggo (Reverse) ===
        // 9. Add review column back and revert status_plagiarisme enum
        Schema::table('dokumen', function (Blueprint $table) {
            $table->text('review')->nullable();
        });
        
        DB::statement("ALTER TABLE `dokumen` MODIFY `status_plagiarisme` ENUM('plagiarisme', 'tidak_plagiarisme', 'proses_cek')");
        
        // === Ambasing (Reverse) ===
        // 8. Remove fta_20 from pengajuan_pisah_kota
        Schema::table('pengajuan_pisah_kota', function (Blueprint $table) {
            $table->dropColumn('fta_20');
        });
        
        // 7. Revert nullable columns in kota table
        Schema::table('kota', function (Blueprint $table) {
            $table->text('judul_ta')->nullable(false)->change();
            $table->unsignedBigInteger('id_bidang')->nullable(false)->change();
        });
        
        // 6. Make id_kota non-nullable in mahasiswa table
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kota')->nullable(false)->change();
        });
        
        // === Indomilk (Reverse) ===
        // 5. Revert foreign key for list_kalimat_plagiarisme
        Schema::table('list_kalimat_plagiarisme', function (Blueprint $table) {
            $table->dropForeign(['id_dokumen']);
            $table->foreign('id_dokumen')
                  ->references('id_dokumen')
                  ->on('dokumen');
        });
        
        // 4. Remove columns from dokumen table
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn('kode_fta');
            $table->dropColumn('file_path');
        });
        
        // === Lemini (Reverse) ===
        // 3. Add sumber_notifikasi back to notifikasi table
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->string('sumber_notifikasi', 255)->nullable(false);
        });
        
        // 2. Revert changes to preferensi_notifikasi table
        Schema::table('preferensi_notifikasi', function (Blueprint $table) {
            if (Schema::hasColumn('preferensi_notifikasi', 'reminder_h5')) {
                $table->renameColumn('reminder_h5', 'in_app');
            }
            $table->string('tipe_notifikasi', 255)->nullable(false);
        });
        
        // === Heaplow (Reverse) ===
        // 1. Revert status_pengajuan enum in pengajuan_pembimbing table
        DB::statement("ALTER TABLE `pengajuan_pembimbing` MODIFY `status_pengajuan` ENUM('pending', 'diterima') NOT NULL DEFAULT 'pending'");
        
        Schema::enableForeignKeyConstraints();
    }
};