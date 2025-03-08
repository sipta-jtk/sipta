<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First batch: Base tables with no dependencies
        $this->call([
            UserSeeder::class,
            GedungSeeder::class,
            FasilitasSeeder::class,
            KbkSeeder::class,
            BidangSeeder::class,
            ProdiSeeder::class,
            PeriodePengajuanSeeder::class,
            SubKategoriSeeder::class,
            AmbangBatasSeeder::class,
        ]);

        // Second batch: First level dependencies
        $this->call([
            DosenSeeder::class, // depends on User, KBK
            RuanganSeeder::class, // depends on Gedung
            KotaSeeder::class, // depends on Bidang
        ]);

        // Third batch: Second level dependencies
        $this->call([
            MahasiswaSeeder::class, // depends on User, Prodi, Kota
            KaprodiSeeder::class, // depends on Dosen, Prodi
            JadwalDosenPembimbingSeeder::class, // depends on Dosen
            KetertarikanBidangSeeder::class, // depends on Dosen, Bidang
            RuangFasilitasSeeder::class, // depends on Fasilitas, Ruangan
            LabelSeeder::class, // depends on Kota
        ]);

        // Fourth batch: Higher level dependencies
        $this->call([
            PengajuanPembimbingSeeder::class, // depends on Kota
            PrioritasPembimbingSeeder::class, // depends on PengajuanPembimbing, Dosen
            AlokasiPembimbingSeeder::class, // depends on PengajuanPembimbing, Dosen
            PengajuanPisahKotaSeeder::class, // depends on Mahasiswa, Kota
        ]);

        // Fifth batch: Assessment related
        $this->call([
            FormPenilaianSeeder::class, // depends on Dosen
            KategoriPenilaianSeeder::class, // depends on FormPenilaian
            RubrikPenilaianSeeder::class, // depends on KategoriPenilaian
            PenilaianRubrikSeeder::class, // depends on Mahasiswa, Dosen, RubrikPenilaian
            PenilaianKategoriSeeder::class, // depends on Mahasiswa
        ]);

        // Sixth batch: Document related
        $this->call([
            DokumenSeeder::class, // depends on Kota, Label, SubKategori, User
            ListJurnalPlagiarismeSeeder::class, // no dependencies
            ListKalimatPlagiarismeSeeder::class, // depends on Dokumen, ListJurnalPlagiarisme
            MahasiswaDosenDokumenSeeder::class, // depends on Dosen, Mahasiswa, Dokumen
        ]);

        // Seventh batch: Scheduling related
        $this->call([
            PenjadwalanSeeder::class, // depends on Ruangan, Kota, Dosen
            KehadiranSeeder::class, // depends on Penjadwalan, User
            KonfirmasiSeeder::class, // depends on Penjadwalan, Dosen
        ]);

        // Eighth batch: Notification related
        $this->call([
            NotifikasiSeeder::class, // no dependencies
            NotifikasiKirimSeeder::class, // depends on Notifikasi, User
            PreferensiNotifikasiSeeder::class, // depends on User
        ]);

        // Ninth batch: Logs (should be last as they depend on many entities)
        $this->call([
            LogAktivitasSeeder::class, // depends on Kota, User, Dokumen
        ]);
    }
}