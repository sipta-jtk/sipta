CREATE DATABASE IF NOT EXISTS sipta;
USE sipta;

CREATE TABLE `gedung` (
  `kode_gedung` varchar(1) PRIMARY KEY,
  `nama_gedung` varchar(100) UNIQUE
);

CREATE TABLE `ruangan` (
  `id_ruangan` int(4) PRIMARY KEY AUTO_INCREMENT,
  `kode_ruangan` varchar(6) NOT NULL,
  `nama_ruangan` varchar(127) UNIQUE,
  `status_ruangan` ENUM ('tersedia', 'tidak_tersedia') NOT NULL,
  `kode_gedung` varchar(1),
  `link_ruangan` varchar(45) NOT NULL
);

CREATE TABLE `penjadwalan` (
  `id_penjadwalan` int(7) PRIMARY KEY AUTO_INCREMENT,
  `sesi` int(3) NOT NULL,
  `agenda` ENUM ('seminar_1', 'seminar_2', 'seminar_3', 'sidang') NOT NULL,
  `id_ruangan` int(4),
  `tanggal` date NOT NULL,
  `id_kota` integer,
  `nip` varchar(22)
);

CREATE TABLE `fasilitas` (
  `id_fasililtas` int(4) PRIMARY KEY AUTO_INCREMENT,
  `nama_fasilitas` varchar(100),
  `jumlah_total_fasilitas` int(4) NOT NULL
);

CREATE TABLE `ruang_fasilitas` (
  `id_fasilitas` int(4),
  `id_ruangan` int(3),
  `jumlah_fasilitas` int(4) NOT NULL,
  PRIMARY KEY (`id_ruangan`, `id_fasilitas`)
);

CREATE TABLE `user` (
  `username` varchar(22) PRIMARY KEY,
  `nama` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_user` ENUM ('mahasiswa', 'dosen') NOT NULL,
  `no_whatsapp` varchar(15) NOT NULL,
  `photo` varchar(255) NOT NULL
);

CREATE TABLE `dosen` (
  `maks_bimbingan_d4` int(4) NOT NULL,
  `maks_bimbingan_d3` int(4) NOT NULL,
  `id_kbk` int(4),
  `nip` varchar(22) PRIMARY KEY,
  `id_dosen` varchar(3) NOT NULL,
  `kode_dosen` varchar(8) NOT NULL,
  `status_dosen` ENUM ('aktif', 'nonaktif') NOT NULL,
  `role_dosen` ENUM ('dosen', 'koordinator_ta', 'kajur', 'dosen_pembimbing') NOT NULL
);

CREATE TABLE `mahasiswa` (
  `nim` varchar(22) PRIMARY KEY,
  `tahun_masuk` year NOT NULL,
  `kelas` varchar(4) NOT NULL,
  `id_prodi` int(4),
  `status_ta` ENUM ('mahasiswa_ta', 'mahasiswa_non_ta') NOT NULL,
  `nilai_akhir_ta` float NOT NULL,
  `id_kota` int(4)
);

CREATE TABLE `prodi` (
  `id_prodi` int(4) PRIMARY KEY AUTO_INCREMENT,
  `nama_prodi` varchar(255) NOT NULL,
  `maksimal_anggota_kota` int(3) NOT NULL
);

CREATE TABLE `kaprodi` (
  `nip` varchar(22),
  `id_prodi` int(4)
);

CREATE TABLE `pengajuan_pisah_kota` (
  `id_pengajuan` int(4) PRIMARY KEY AUTO_INCREMENT,
  `nim` varchar(22),
  `id_kota` int(7)
);

CREATE TABLE `kbk` (
  `id_kbk` int(4) PRIMARY KEY AUTO_INCREMENT,
  `kbk` varchar(100) NOT NULL
);

CREATE TABLE `jadwal_dosen_pembimbing` (
  `id_jadwal_dosbim` int(7) PRIMARY KEY AUTO_INCREMENT,
  `nip` varchar(22),
  `hari` ENUM ('senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
);

CREATE TABLE `ketertarikan_bidang` (
  `id_ketertarikan_bidang` int(7) PRIMARY KEY AUTO_INCREMENT,
  `nip` varchar(22),
  `id_bidang` int(5)
);

CREATE TABLE `bidang` (
  `id_bidang` int(5) PRIMARY KEY AUTO_INCREMENT,
  `bidang` text NOT NULL
);

CREATE TABLE `kota` (
  `id_kota` int(7) PRIMARY KEY AUTO_INCREMENT,
  `judul_ta` text NOT NULL,
  `id_bidang` int(5),
  `nama_kota` varchar(255) NOT NULL,
  `tahun_kota` year NOT NULL,
  `status_kota` ENUM ('pra_kota', 'aktif', 'lulus', 'bubar')
);

CREATE TABLE `prioritas_pembimbing` (
  `id_prioritas_pembimbing` int(7) PRIMARY KEY AUTO_INCREMENT,
  `id_pengajuan` int,
  `nip` varchar(22),
  `urutan_prioritas` int(2)
);

CREATE TABLE `pengajuan_pembimbing` (
  `id_pengajuan_pembimbing` int(7) PRIMARY KEY AUTO_INCREMENT,
  `id_kota` int(7),
  `status_pengajuan` ENUM ('pending', 'diterima') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE `alokasi_pembimbing` (
  `id_alokasi_pembimbing` int(7) PRIMARY KEY AUTO_INCREMENT,
  `id_pengajuan_pembimbing` int(7),
  `nip` varchar(22),
  `urutan_prioritas_terpilih` int(4)
);

CREATE TABLE `periode_pengajuan` (
  `id_periode_pengajuan` int PRIMARY KEY AUTO_INCREMENT,
  `periode_mulai` date NOT NULL,
  `periode_akhir` date NOT NULL
);

CREATE TABLE `form_penilaian` (
  `id_form_penilaian` int(5) PRIMARY KEY AUTO_INCREMENT,
  `nama_formulir_penilaian` varchar(50) NOT NULL,
  `nip` varchar(22),
  `tahun_ajaran` year NOT NULL,
  `status_form` ENUM ('pending', 'draft', 'published', 'used', 'finished') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT (now()),
  `updated_at` timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE `kategori_penilaian` (
  `id_kategori` int(5) PRIMARY KEY AUTO_INCREMENT,
  `id_form_penilaian` int,
  `nama_kategori` varchar(50) NOT NULL,
  `bobot_kategori` int NOT NULL
);

CREATE TABLE `rubrik_penilaian` (
  `id_rubrik` int(5) PRIMARY KEY AUTO_INCREMENT,
  `id_kategori` int(5),
  `judul_rubrik` varchar(100) NOT NULL,
  `detail_rubrik` text,
  `bobot_rubrik` int(4) NOT NULL
);

CREATE TABLE `penilaian_rubrik` (
  `nim` varchar(22),
  `nip` varchar(22),
  `id_rubrik` int(4),
  `nilai_rubrik` float NOT NULL,
  `detail_feedback` text,
  `created_at` timestamp NOT NULL DEFAULT (now()),
  `updated_at` timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE `penilaian_kategori` (
  `nim` varchar(22),
  `id_kategori` int(4),
  `nilai_kategori` float NOT NULL
);

CREATE TABLE `kehadiran` (
  `id_penjadwalan` int(7),
  `username` varchar(22),
  `status_hadir` ENUM ('hadir', 'tidak_hadir') NOT NULL
);

CREATE TABLE `konfirmasi` (
  `id_penjadwalan` int(7),
  `nip` varchar(22),
  `status_konfirmasi` ENUM ('disetujui', 'tidak_disetujui') NOT NULL
);

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(10) PRIMARY KEY AUTO_INCREMENT,
  `tipe_notifikasi` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi_notifikasi` text NOT NULL,
  `sumber_notifikasi` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE `notifikasi_kirim` (
  `id_kirim` int(10) PRIMARY KEY AUTO_INCREMENT,
  `id_notifikasi` int(10),
  `username` varchar(22),
  `kanal` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `waktu_kirim` datetime NOT NULL,
  `respon_log` text NOT NULL
);

CREATE TABLE `preferensi_notifikasi` (
  `id_preferensi` int(10) PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(22),
  `tipe_notifikasi` varchar(255) NOT NULL,
  `whatsapp` boolean NOT NULL,
  `in_app` boolean NOT NULL,
  `email` boolean NOT NULL
);

CREATE TABLE `dokumen` (
  `id_dokumen` int(10) PRIMARY KEY AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `persentase_plagiarisme` float NOT NULL,
  `highlight_dokumen` boolean NOT NULL,
  `status_plagiarisme` ENUM ('plagiarisme', 'tidak_plagiarisme') NOT NULL,
  `review` text NOT NULL,
  `kategori` ENUM ('laporan', 'poster', 'presentasi'),
  `deskripsi` text,
  `versi` int(4) NOT NULL,
  `ukuran_file` float NOT NULL,
  `notes` text,
  `id_kota` int(7) NOT NULL,
  `id_label` int(7),
  `id_subkategori` int(7),
  `username` varchar(22) NOT NULL,
  `status_berkas` ENUM ('valid', 'tidak_valid', 'belum_unggah', 'ditunda'),
  `uploaded_at` timestamp NOT NULL DEFAULT (now()),
  `updated_at` timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE `list_kalimat_plagiarisme` (
  `id_kalimat` int(10) PRIMARY KEY AUTO_INCREMENT,
  `id_dokumen` int(10),
  `id_jurnal` int(10),
  `kalimat_plagiat` text NOT NULL
);

CREATE TABLE `list_jurnal_plagiarisme` (
  `id_jurnal` int(10) PRIMARY KEY AUTO_INCREMENT,
  `link_jurnal` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `persentase_kemunculan` float NOT NULL
);

CREATE TABLE `ambang_batas` (
  `id_ambang_batas` varchar(255) PRIMARY KEY,
  `ambang_batas` float NOT NULL
);

CREATE TABLE `mahasiswa_dosen_dokumen` (
  `nip` varchar(22),
  `nim` varchar(22),
  `id_dokumen` int(10)
);

CREATE TABLE `label` (
  `id_label` int(7) PRIMARY KEY AUTO_INCREMENT,
  `nama_label` varchar(255) UNIQUE NOT NULL,
  `id_kota` int(7) NOT NULL
);

CREATE TABLE `subkategori` (
  `id_subkategori` int(7) PRIMARY KEY AUTO_INCREMENT,
  `nama_subkategori` varchar(100) NOT NULL
);

CREATE TABLE `log_aktivitas` (
  `id_log_aktivitas` int(10) PRIMARY KEY AUTO_INCREMENT,
  `id_kota` int(7) NOT NULL,
  `username` varchar(22) NOT NULL,
  `id_dokumen` int(10) NOT NULL,
  `action` ENUM ('upload', 'edit', 'delete', 'download', 'review'),
  `waktu_aktivitas` timestamp NOT NULL DEFAULT (now())
);

ALTER TABLE `ruangan` ADD FOREIGN KEY (`kode_gedung`) REFERENCES `gedung` (`kode_gedung`);

ALTER TABLE `penjadwalan` ADD FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`);

ALTER TABLE `penjadwalan` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`);

ALTER TABLE `penjadwalan` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `ruang_fasilitas` ADD FOREIGN KEY (`id_fasilitas`) REFERENCES `fasilitas` (`id_fasililtas`);

ALTER TABLE `ruang_fasilitas` ADD FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`);

ALTER TABLE `dosen` ADD FOREIGN KEY (`id_kbk`) REFERENCES `kbk` (`id_kbk`);

ALTER TABLE `dosen` ADD FOREIGN KEY (`nip`) REFERENCES `user` (`username`);

ALTER TABLE `mahasiswa` ADD FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`);

ALTER TABLE `mahasiswa` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`);

ALTER TABLE `mahasiswa` ADD FOREIGN KEY (`nim`) REFERENCES `user` (`username`);

ALTER TABLE `kaprodi` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `kaprodi` ADD FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`);

ALTER TABLE `pengajuan_pisah_kota` ADD FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`);

ALTER TABLE `pengajuan_pisah_kota` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`);

ALTER TABLE `jadwal_dosen_pembimbing` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `ketertarikan_bidang` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `ketertarikan_bidang` ADD FOREIGN KEY (`id_bidang`) REFERENCES `bidang` (`id_bidang`);

ALTER TABLE `kota` ADD FOREIGN KEY (`id_bidang`) REFERENCES `bidang` (`id_bidang`);

ALTER TABLE `prioritas_pembimbing` ADD FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan_pembimbing` (`id_pengajuan_pembimbing`);

ALTER TABLE `prioritas_pembimbing` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `pengajuan_pembimbing` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`);

ALTER TABLE `alokasi_pembimbing` ADD FOREIGN KEY (`id_pengajuan_pembimbing`) REFERENCES `pengajuan_pembimbing` (`id_pengajuan_pembimbing`);

ALTER TABLE `alokasi_pembimbing` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `form_penilaian` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `kategori_penilaian` ADD FOREIGN KEY (`id_form_penilaian`) REFERENCES `form_penilaian` (`id_form_penilaian`) ON DELETE CASCADE;

ALTER TABLE `rubrik_penilaian` ADD FOREIGN KEY (`id_kategori`) REFERENCES `kategori_penilaian` (`id_kategori`) ON DELETE CASCADE;

ALTER TABLE `penilaian_rubrik` ADD FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`);

ALTER TABLE `penilaian_rubrik` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `penilaian_rubrik` ADD FOREIGN KEY (`id_rubrik`) REFERENCES `rubrik_penilaian` (`id_rubrik`);

ALTER TABLE `penilaian_kategori` ADD FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`);

ALTER TABLE `kehadiran` ADD FOREIGN KEY (`id_penjadwalan`) REFERENCES `penjadwalan` (`id_penjadwalan`);

ALTER TABLE `kehadiran` ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`);

ALTER TABLE `konfirmasi` ADD FOREIGN KEY (`id_penjadwalan`) REFERENCES `penjadwalan` (`id_penjadwalan`);

ALTER TABLE `konfirmasi` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `notifikasi_kirim` ADD FOREIGN KEY (`id_notifikasi`) REFERENCES `notifikasi` (`id_notifikasi`);

ALTER TABLE `notifikasi_kirim` ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`);

ALTER TABLE `preferensi_notifikasi` ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`);

ALTER TABLE `dokumen` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`) ON DELETE CASCADE;

ALTER TABLE `dokumen` ADD FOREIGN KEY (`id_label`) REFERENCES `label` (`id_label`) ON DELETE CASCADE;

ALTER TABLE `dokumen` ADD FOREIGN KEY (`id_subkategori`) REFERENCES `subkategori` (`id_subkategori`) ON DELETE CASCADE;

ALTER TABLE `dokumen` ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE;

ALTER TABLE `list_kalimat_plagiarisme` ADD FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen` (`id_dokumen`);

ALTER TABLE `list_kalimat_plagiarisme` ADD FOREIGN KEY (`id_jurnal`) REFERENCES `list_jurnal_plagiarisme` (`id_jurnal`);

ALTER TABLE `mahasiswa_dosen_dokumen` ADD FOREIGN KEY (`nip`) REFERENCES `dosen` (`nip`);

ALTER TABLE `mahasiswa_dosen_dokumen` ADD FOREIGN KEY (`nim`) REFERENCES `mahasiswa` (`nim`);

ALTER TABLE `mahasiswa_dosen_dokumen` ADD FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen` (`id_dokumen`);

ALTER TABLE `label` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`) ON DELETE CASCADE;

ALTER TABLE `log_aktivitas` ADD FOREIGN KEY (`id_kota`) REFERENCES `kota` (`id_kota`) ON DELETE CASCADE;

ALTER TABLE `log_aktivitas` ADD FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE;

ALTER TABLE `log_aktivitas` ADD FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen` (`id_dokumen`) ON DELETE CASCADE;
