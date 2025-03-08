<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Base tables with no dependencies
            UserSeeder::class,
            GedungSeeder::class,
            FasilitasSeeder::class,
            KbkSeeder::class,
            BidangSeeder::class,
            ProdiSeeder::class,
            PeriodePengajuanSeeder::class,
            SubKategoriSeeder::class,
            AmbangBatasSeeder::class,

            // First level dependencies
            DosenSeeder::class, // depends on User, KBK
            RuanganSeeder::class, // depends on Gedung
            KotaSeeder::class, // depends on Bidang

            // Second level dependencies
            MahasiswaSeeder::class, // depends on User, Prodi, Kota
            KaprodiSeeder::class, // depends on Dosen, Prodi
            JadwalDosenPembimbingSeeder::class, // depends on Dosen
            KetertarikanBidangSeeder::class, // depends on Dosen, Bidang
            RuangFasilitasSeeder::class, // depends on Fasilitas, Ruangan
            LabelSeeder::class, // depends on Kota

            // Higher level dependencies
            PengajuanPembimbingSeeder::class, // depends on Kota
            PrioritasPembimbingSeeder::class, // depends on PengajuanPembimbing, Dosen
            AlokasiPembimbingSeeder::class, // depends on PengajuanPembimbing, Dosen
            PengajuanPisahKotaSeeder::class, // depends on Mahasiswa, Kota

            // Assessment related
            FormPenilaianSeeder::class, // depends on Dosen
            KategoriPenilaianSeeder::class, // depends on FormPenilaian
            RubrikPenilaianSeeder::class, // depends on KategoriPenilaian
            PenilaianRubrikSeeder::class, // depends on Mahasiswa, Dosen, RubrikPenilaian
            PenilaianKategoriSeeder::class, // depends on Mahasiswa

            // Document related
            DokumenSeeder::class, // depends on Kota, Label, SubKategori, User
            ListJurnalPlagiarismeSeeder::class, // no dependencies
            ListKalimatPlagiarismeSeeder::class, // depends on Dokumen, ListJurnalPlagiarisme
            MahasiswaDosenDokumenSeeder::class, // depends on Dosen, Mahasiswa, Dokumen

            // Scheduling related
            PenjadwalanSeeder::class, // depends on Ruangan, Kota, Dosen
            KehadiranSeeder::class, // depends on Penjadwalan, User
            KonfirmasiSeeder::class, // depends on Penjadwalan, Dosen

            // Notification related
            NotifikasiSeeder::class, // no dependencies
            NotifikasiKirimSeeder::class, // depends on Notifikasi, User
            PreferensiNotifikasiSeeder::class, // depends on User

            // Logs (should be last as they depend on many entities)
            LogAktivitasSeeder::class, // depends on Kota, User, Dokumen
        ]);
    }
}
