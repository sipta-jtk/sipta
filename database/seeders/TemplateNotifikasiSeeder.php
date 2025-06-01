<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\TemplateNotifikasi;

class TemplateNotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('template_notifikasi')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('template_notifikasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'judul_notifikasi' => '[Pemberitahuan] Dosen Pembimbing Tugas AKhir Telah Ditetapkan!',
                'isi_in_apps' => "NIP Dosen: {nip}\nNama Dosen: {nama}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa dosen pembimbing untuk Tugas Akhir Anda telah ditetapkan. Berikut detailnya:\n\nNIP Dosen: {nip}\nNama: {nama}\n\nJangan lupa mempersiapkan diri sebaik mungkin dan mulai koordinasi dengan pembimbing.\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Mahasiswa Bimbingan Telah Ditetapkan!',
                'isi_in_apps' => "Topik: {Topik}\nNama Mahasiswa: {nama}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa Anda telah ditetapkan sebagai pembimbing untuk mahasiswa berikut:\n\nTopik: {Topik}\nNama Mahasiswa: {nama}\n\nHarap mulai koordinasi dengan mahasiswa untuk bimbingan.\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'reminder',
            ],
            [
                'judul_notifikasi' => '[Pergantian] Penggantian Dosen Pembimbing!',
                'isi_in_apps' => "NIP Dosen Pengganti: {nip}\nNama Dosen Pengganti: {nama}\nAlasan Penggantian: {alasan}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa ada perubahan pembimbing untuk Tugas Akhir Anda. Berikut adalah detail perubahan pembimbing:\n\nTanggal Penggantian: {Tanggal_penggantian}\nNIP Dosen Pengganti: {nip}\nNama Dosen Pengganti: {nama}\nAlasan Penggantian: {alasan}\n\nHarap segera menghubungi dosen pembimbing baru Anda untuk melanjutkan bimbingan.\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Pergantian] Penggantian Dosen Pembimbing!',
                'isi_in_apps' => "Nama Mahasiswa: {nama}\nNIP Dosen Lama: {nip}\nNama Dosen Lama: {nama}\nAlasan Penggantian: {alasan}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa Anda telah ditunjuk sebagai dosen pembimbing baru untuk mahasiswa berikut:\n\nNama Mahasiswa: {nama}\nNIP Dosen Lama: {nip}\nNama Dosen Lama: {nama}\nAlasan Penggantian: {alasan}\n\nHarap segera melakukan koordinasi dengan mahasiswa dan memulai bimbingan.\n\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Reminder] Deadline Tugas Akhir {1/3/5} Hari Lagi!',
                'isi_in_apps' => "Deadline Tugas: {Deadline_TA}\nHari Tersisa: {Hari Tersisa}\nDokumen: {judul}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa deadline Tugas Akhir Anda sudah semakin dekat! Berikut rincian tenggat waktu yang perlu Anda perhatikan:\n\nDeadline Tugas: {Deadline_TA}\nHari Tersisa: {Hari Tersisa}\nDokumen: {judul}\n\nJangan lupa untuk segera menyelesaikan tugas Anda.\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'reminder',
            ],
            [
                'judul_notifikasi' => '[Reminder] Jadwal Ujian Sidang Tugas Akhir Telah Dijadwalkan!',
                'isi_in_apps' => "Tanggal: {tanggal}\nWaktu: {waktu}\nTempat: {id_ruangan}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan Anda bahwa jadwal ujian sidang Tugas Akhir Anda telah dijadwalkan. Berikut detailnya:\n\nTanggal: {tanggal}\nWaktu: {waktu}\nTempat: {id_ruangan}\n\nMohon konfirmasi kehadiran Anda dengan mengklik tombol di bawah ini.\n{Link Konfirmasi Kehadiran}\n\nJangan lupa mempersiapkan diri sebaik mungkin untuk ujian sidang Anda.\n\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'reminder',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Hasil Evaluasi Ujian Sidang Tugas Akhir Telah Tersedia!',
                'isi_in_apps' => "Status: {status_konfirmasi}\nNilai: {id_nilai}\nKota: {id_kota}",
                'isi_in_email' => "Halo {nama},\n\nHasil ujian sidang Tugas Akhir Anda telah tersedia. Berikut status hasil sidang Anda:\n\nStatus: {status_konfirmasi}\nNilai: {id_nilai}\nKota: {id_kota}\n\nBerikut instruksi yang harus Anda ikuti jika ada revisi:\n1. Instruksi Revisi\n2. Instruksi Revisi\n3. Instruksi Revisi\n\nUntuk status tunda, harap menunggu konfirmasi lebih lanjut terkait jadwal sidang ulang.\n\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                'isi_in_apps' => "Nama Mahasiswa: {nama}\nTopik: {topik}\nWaktu: {tanggal}\nTempat: {nama_ruangan}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa terdapat pengajuan jadwal seminar atau sidang baru. Berikut adalah detail pengajuannya:\n\nNama Mahasiswa: {nama}\nTopik: {topik}\nWaktu: {tanggal}\nTempat: {nama_ruangan}\n\nMohon untuk segera menyesuaikan jadwal.\n\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Perubahan Jadwal Sidang/Seminar Telah Diperbarui!',
                'isi_in_apps' => "Agenda: {agenda}\nTanggal Baru: {tanggal}\nKota: {id_kota}\nKode Ruangan: {kode_ruangan}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa ada perubahan jadwal untuk seminar yang akan Anda hadiri sebagai dosen penguji/pembimbing. Berikut adalah detail jadwal baru:\n\nAgenda: {agenda}\nTanggal Baru: {tanggal}\nKota: {id_kota}\nKode Ruangan: {kode_ruangan}\n\nHarap dicatat perubahan ini dan sesuaikan jadwal Anda.\n\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Permohonan Pemesanan Ruangan Telah Diajukan!',
                'isi_in_apps' => "Kode Ruangan: {kode_ruangan}\nNama Ruangan: {nama_ruangan}\nNama Gedung: {nama_gedung}\nTanggal Penggunaan: {tanggal}\nWaktu Penggunaan: {sesi}\nAcara: {agenda}\n\nKelompok TA: {topik}\nNama: {nama}",
                'isi_in_email' => "Halo Koordinator TA,\n\nKami memberitahukan Anda bahwa terdapat permohonan pemesanan ruangan yang baru diajukan oleh mahasiswa. Berikut detail pemesanan:\n\nKode Ruangan: {kode_ruangan}\nNama Ruangan: {nama_ruangan}\nNama Gedung: {nama_gedung}\nTanggal Penggunaan: {tanggal}\nWaktu Penggunaan: {sesi}\n\nKelompok Topik TA: {topik}\nNama: {nama}\n\nHarap segera tindak lanjuti permohonan pemesanan ruangan ini.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Jadwal Ruangan {agenda} Telah Ditetapkan!',
                'isi_in_apps' => "Kode Ruangan: {kode_ruangan}\nNama Ruangan: {nama_ruangan}\nNama Gedung: {nama_gedung}\nTanggal: {tanggal}\nWaktu: {sesi}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin menginformasikan bahwa ruangan untuk kegiatan {agenda} Anda telah dijadwalkan dan berikut adalah detailnya:\n\nKode Ruangan: {kode_ruangan}\nNama Ruangan: {nama_ruangan}\nNama Gedung: {nama_gedung}\nTanggal: {tanggal}\nWaktu: {sesi}\n\nMohon pastikan Anda hadir sesuai dengan waktu dan tempat yang telah ditentukan. Untuk bukti pendaftaran, Anda dapat mengaksesnya melalui website.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => '[Reminder] Detail Ruangan untuk Sidang/Seminar!',
                'isi_in_apps' => "Nama Ruangan: {nama_ruangan}\nNomor Ruangan: {id_ruangan}\nWaktu: {waktu}\nTanggal: {tanggal}\nAgenda Kegiatan: {agenda}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan terkait detail ruangan sidang/seminar yang akan digunakan:\n\nNama Ruangan: {nama_ruangan}\nNomor Ruangan: {id_ruangan}\nWaktu: {waktu}\nTanggal: {tanggal}\nAgenda Kegiatan: {agenda}\n\nPastikan Anda hadir tepat waktu di ruangan yang telah ditentukan. Jika ada perubahan atau masalah, segera hubungi pihak terkait.\n\nTerima kasih atas perhatian Anda,\n{Tim Sipta}",
                'jenis_notifikasi' => 'reminder',
            ],
            [
                'judul_notifikasi' => 'Jadwal dan Lokasi Ruangan Telah Diperbarui!',
                'isi_in_apps' => "Semula:\nNama Ruangan Lama: {nama_ruangan_lama}\nWaktu Lama: {waktu_lama}\n\nMenjadi:\nKode Ruangan Baru: {kode_ruangan_baru}\nNama Ruangan Baru: {nama_ruangan_baru}\nNama Gedung Baru: {nama_gedung_baru}\nWaktu Baru: {sesi}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan Anda bahwa jadwal dan ruangan untuk kegiatan {agenda} Anda telah diperbarui. Berikut adalah detail perubahan yang terjadi:\n\nSemula:\nNama Ruangan Lama: {nama_ruangan_lama}\nWaktu Lama: {sesi}\n\nMenjadi:\nKode Ruangan Baru: {kode_ruangan_baru}\nNama Ruangan Baru: {nama_ruangan_baru}\nNama Gedung Baru: {nama_gedung_baru}\nWaktu Baru: {sesi}\n\nHarap perhatikan perubahan ini dan pastikan Anda hadir sesuai dengan jadwal dan ruangan yang baru.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Dosen Telah Memperbarui Nilai Dokumen!',
                'isi_in_apps' => "Nilai Sebelumnya: {nilai_sebelumnya}\nNilai Baru: {nilai_dokumen}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa nilai untuk Tugas Akhir Anda telah diubah oleh dosen. Berikut detail perubahannya:\n\nNilai Sebelumnya: {nilai_dokumen_sebelumnya}\nNilai Baru: {nilai_dokumen}\n\nMohon perhatikan perubahan nilai ini.\nJika Anda membutuhkan klarifikasi lebih lanjut, harap menghubungi dosen yang bersangkutan.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Penilaian Telah Dilakukan, Periksa Feedback Dosen!',
                'isi_in_apps' => "Topik Tugas Akhir: {topik}\nFeedback Untuk Dokumen: {feedback_dokumen}\nFeedback Untuk Presentasi: {feedback_presentasi}\nFeedback Penguasaan Materi: {feed_penguasaan_materi}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa penilaian untuk dokumen Anda telah selesai dilakukan. Berikut adalah umpan balik dari dosen:\n\nFeedback untuk Dokumen: {feedback_dokumen}\nFeedback untuk Presentasi: {feedback_presentasi}\nFeedback Penguasaan Materi: {feed_penguasaan_materi}\n\nSilakan tinjau umpan balik dari dosen dan lakukan perbaikan atau langkah selanjutnya sesuai dengan saran yang diberikan.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Dosen Telah Memberikan Review untuk Dokumen Anda!',
                'isi_in_apps' => "Review Dosen Terkait Plagiarisme: {review}\nTautan: {url_file}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan Anda bahwa dosen telah memberikan review untuk dokumen Tugas Akhir Anda yang telah diperiksa plagiarisme. Berikut detailnya:\n\nReview Dosen Terkait Plagiarisme:\n{review}\n\nAnda dapat melihat dokumen melalui tautan berikut:\n{url_file}\n\nSilakan tinjau umpan balik dari dosen dan lakukan langkah selanjutnya sesuai instruksi yang diberikan.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Perubahan Status Dokumen Tugas Akhir!',
                'isi_in_apps' => "Nama Dokumen: {nama_file}\nTautan Dokumen: {url_berkas}\nStatus: {status_berkas}\nReview Dosen: {feedback_dokumen}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa status dokumen Anda telah diperbarui. Berikut adalah detail status terbaru dokumen Anda:\n\nNama Dokumen: {nama_file}\nTautan Dokumen: {url_berkas}\nStatus: {status_berkas}\nReview Dosen: {feedback_dokumen}\n\nSilakan tinjau umpan balik dari dosen dan lakukan langkah selanjutnya sesuai instruksi yang diberikan.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Mahasiswa Telah Memberikan Akses Dokumen Tugas Akhir!',
                'isi_in_apps' => "Topik Kelompok TA: {topik}\nNama Dokumen: {nama_file}\nTautan Dokumen: {url_berkas}\nHak Melihat: {view}\nHak Mengunduh: {download}\nHak Menyunting: {edit}\nHak Menghapus: {delete}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa Anda telah diberikan akses terhadap dokumen tugas akhir mahasiswa. Berikut adalah detail akses yang diberikan:\n\nTopik Kelompok TA: {topik}\nNama Dokumen: {nama_file}\nTautan Dokumen: {url_berkas}\n\nHak Akses:\n\nHak Melihat: {view}\nHak Mengunduh: {download}\nHak Menyunting: {edit}\nHak Menghapus: {delete}\n\nSilakan akses dokumen tersebut melalui tautan di atas dan lakukan tindakan sesuai dengan hak akses yang diberikan.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Revisi Dokumen Telah Diunggah oleh Mahasiswa!',
                'isi_in_apps' => "Nama Mahasiswa: {nama}\nTopik Kelompok TA: {topik}\nJudul Dokumen: {nama_file}\nTautan Dokumen: {url_berkas}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa mahasiswa Anda telah mengunggah versi baru dari dokumen tugas akhir mereka. Berikut adalah detailnya:\n\nNama Mahasiswa: {nama}\nTopik Kelompok TA: {topik}\nJudul Dokumen: {nama_file}\nTautan Dokumen: {url_berkas}\n\nSilakan tinjau dokumen yang telah diunggah dan berikan umpan balik sesuai dengan instruksi yang telah diberikan.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Pembaruan Sistem Akan Dilakukan!',
                'isi_in_apps' => "Tanggal dan Waktu Pembaruan: {tanggal_pembaruan}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa akan ada pembaruan sistem yang akan dilakukan pada:\n\nTanggal dan Waktu Pembaruan: {tanggal_pembaruan}\n\nHarap mengatur jadwal atau proses kerja Anda sesuai dengan pembaruan ini, agar tidak mengganggu aktivitas Anda.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],
            [
                'judul_notifikasi' => 'Perubahan Role Akses Anda Telah Diperbarui!',
                'isi_in_apps' => "Role Akses Baru: {role_baru}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan bahwa ada perubahan pada role akses akun Anda. Berikut adalah detail perubahan yang telah dilakukan:\n\nRole Akses Baru: {role_baru}\n\nPastikan Anda memeriksa hak akses baru Anda dan menyesuaikan proses kerja atau aktivitas yang diperlukan sesuai dengan perubahan ini.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'pemberitahuan',
            ],                                                                                      
            [
                'judul_notifikasi' => 'Reminder: Kata Sandi Anda Akan Kadaluarsa!',
                'isi_in_apps' => "Tanggal Perubahan Kata Sandi Terakhir: {tanggal_perubahan_kata_sandi}\nTanggal Kadaluarsa: {tanggal_kadaluarsa}\nTautan Reset Kata Sandi: {link_reset}",
                'isi_in_email' => "Halo {nama},\n\nKami ingin memberitahukan Anda bahwa kata sandi akun Anda akan kadaluarsa. Berikut adalah detailnya:\n\nTanggal Perubahan Kata Sandi Terakhir: {tanggal_perubahan_kata_sandi}\nTanggal Kadaluarsa: {tanggal_kadaluarsa}\n\nUntuk menjaga akses Anda, silakan perbarui kata sandi Anda melalui tautan berikut: {link_reset}\n\nPastikan Anda memperbarui kata sandi sebelum tanggal kadaluarsa untuk menghindari kehilangan akses ke akun Anda.\n\nTerima kasih atas perhatian Anda.",
                'jenis_notifikasi' => 'reminder',
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Ambang Batas Baru',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Ambang batas baru telah ditetapkan: {AmbangBatas}",
                'isi_in_email' => "Halo {nama},\n\nTerjadi penetapan ambang batas baru untuk suatu ketentuan.\nNilai ambang batas: {AmbangBatas}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Dosen Telah Menambahkan Catatan',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Catatan baru: {catatan}",
                'isi_in_email' => "Halo {nama},\n\nDosen telah menambahkan catatan baru untuk Anda.\nCatatan: {catatan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Dosen Telah Memperbarui Catatan',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Catatan telah diperbarui: {catatan}",
                'isi_in_email' => "Halo {nama},\n\nDosen telah memperbarui catatan yang ada.\nCatatan terbaru: {catatan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Pemberitahuan Hasil Sidang',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Hasil Sidang:\nStatus: {status_kelulusan}",
                'isi_in_email' => "Halo {nama},\n\nHasil sidang Anda telah tersedia.\nStatus: {status_kelulusan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Pembatalan Penjadwalan Seminar',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Seminar dibatalkan.\nAlasan: {alasan}",
                'isi_in_email' => "Halo {nama},\n\nJadwal seminar telah dibatalkan.\nAlasan pembatalan: {alasan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Pembatalan Penjadwalan Sidang',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Sidang dibatalkan.\nAlasan: {alasan}",
                'isi_in_email' => "Halo {nama},\n\nJadwal sidang telah dibatalkan.\nAlasan pembatalan: {alasan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Mahasiswa Telah Mengirimkan Dokumen',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Dokumen baru:\nJudul: {judul_dokumen}\nKategori: {kategori}",
                'isi_in_email' => "Halo {nama},\n\nMahasiswa telah mengirimkan dokumen baru.\nJudul: {judul_dokumen}\nKategori: {kategori}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Dosen Telah Memberikan Review untuk Dokumen Anda!',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Review dokumen: {catatan}",
                'isi_in_email' => "Halo {nama},\n\nDosen telah memberikan review untuk dokumen Anda.\nReview: {catatan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Role Berhasil Diperbarui',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Role baru Anda: {role_baru}",
                'isi_in_email' => "Halo {nama},\n\nRole akun Anda telah diperbarui menjadi: {role_baru}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Akun Berhasil Dibuat',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Selamat datang di SIPTA!\nUsername: {username}",
                'isi_in_email' => "Halo {nama},\n\nAkun SIPTA Anda telah berhasil dibuat.\nUsername: {username}\nEmail: {email}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Kegiatan Timeline Baru Ditambahkan',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Kegiatan: {nama_kegiatan}\nTanggal: {tanggal_mulai} - {tanggal_selesai}",
                'isi_in_email' => "Halo {nama},\n\nKegiatan baru telah ditambahkan.\nKegiatan: {nama_kegiatan}\nTanggal: {tanggal_mulai} - {tanggal_selesai}\nDeskripsi: {deskripsi}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Deadline Form Penilaian Hari Ini',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Form penilaian jatuh tempo hari ini!",
                'isi_in_email' => "Halo {nama},\n\nBatas waktu pengisian form penilaian adalah hari ini.\nSilakan segera mengisi form penilaian.\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Reminder] 1 Hari Menuju Deadline Form Penilaian',
                'jenis_notifikasi' => 'reminder',
                'isi_in_apps' => "Form penilaian jatuh tempo besok!",
                'isi_in_email' => "Halo {nama},\n\nBatas waktu form penilaian tinggal 1 hari lagi.\nSilakan segera mengisi form penilaian.\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Reminder] 3 Hari Menuju Deadline Form Penilaian',
                'jenis_notifikasi' => 'reminder',
                'isi_in_apps' => "Form penilaian jatuh tempo dalam 3 hari!",
                'isi_in_email' => "Halo {nama},\n\nBatas waktu form penilaian tinggal 3 hari lagi.\nSilakan segera mengisi form penilaian.\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Reminder] 7 Hari Menuju Deadline Form Penilaian',
                'jenis_notifikasi' => 'reminder',
                'isi_in_apps' => "Form penilaian jatuh tempo dalam 7 hari!",
                'isi_in_email' => "Halo {nama},\n\nBatas waktu form penilaian tinggal 7 hari lagi.\nSilakan segera mengisi form penilaian.\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Kegiatan Timeline Selesai Hari Ini',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Kegiatan berakhir hari ini:\n{nama_kegiatan}",
                'isi_in_email' => "Halo {nama},\n\nKegiatan berikut berakhir hari ini:\nKegiatan: {nama_kegiatan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Reminder] 3 Hari Menuju Selesai Kegiatan Timeline',
                'jenis_notifikasi' => 'reminder',
                'isi_in_apps' => "Kegiatan akan berakhir dalam 3 hari:\n{nama_kegiatan}",
                'isi_in_email' => "Halo {nama},\n\nKegiatan berikut akan berakhir dalam 3 hari:\nKegiatan: {nama_kegiatan}\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Penyimpanan Hampir Penuh',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Penyimpanan hampir penuh!\nPenggunaan: {used_storage}\nBatas: {storage_limit}",
                'isi_in_email' => "Halo {nama},\n\nPenyimpanan Anda hampir penuh.\nPenggunaan saat ini: {used_storage}\nBatas penyimpanan: {storage_limit}\n\nSilakan kelola ruang penyimpanan Anda.\n\nTerima kasih.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Alokasi Dosen Pembimbing TA Anda Telah Disetujui!',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps' => "Halo {nama_mahasiswa} ({nim_mahasiswa}), alokasi dosen pembimbing untuk topik '{topik}' telah disetujui pada tanggal {tanggal_penetapan} oleh koordinator {nama_koordinator}. Segera cek detail dan hubungi dosen pembimbing Anda.",
                'isi_in_email' => "Yth. {nama_mahasiswa} ({nim_mahasiswa}),\n\nKami dari tim koordinator Tugas Akhir ingin memberitahukan bahwa pengajuan alokasi dosen pembimbing Anda untuk topik '{topik}' telah **disetujui**.",
            ],
            [
                'judul_notifikasi' => '[Pemberitahuan] Penugasan Sebagai Dosen Pembimbing TA',
                'jenis_notifikasi' => 'pemberitahuan',
                'isi_in_apps'      => "Yth. Bapak/Ibu {nama_dosen},\nAnda telah dialokasikan sebagai dosen pembimbing Tugas Akhir pada {tanggal_penetapan} oleh Koordinator TA ({nama_koordinator}). Detail mahasiswa dan topik dapat dilihat di sistem.",
                'isi_in_email'     => "Yth. Bapak/Ibu {nama_dosen} (NIP: {nip_dosen}),\n\nDengan hormat,\n\nKami dari Tim Koordinator Tugas Akhir ingin memberitahukan bahwa Bapak/Ibu telah ditetapkan sebagai Dosen Pembimbing untuk pelaksanaan Tugas Akhir.\n\nDetail Penugasan:\n- Nama Dosen: {nama_dosen}\n- NIP: {nip_dosen}\n- Tanggal Penetapan: {tanggal_penetapan}\n- Ditetapkan oleh: {nama_koordinator}\n\nInformasi lebih lanjut mengenai mahasiswa yang akan Bapak/Ibu bimbing beserta detail topiknya dapat diakses melalui Sistem Informasi Akademik atau platform Tugas Akhir yang digunakan.\n\nKami sangat mengharapkan kerjasama dan bimbingan terbaik dari Bapak/Ibu untuk kelancaran studi mahasiswa. Mohon untuk dapat segera berkoordinasi dengan mahasiswa yang bersangkutan.\n\nAtas perhatian dan kesediaan Bapak/Ibu, kami ucapkan terima kasih.\n\nSalam hormat,\nTim Koordinator TA\n({nama_koordinator})",
            ]
        ];

        foreach ($data as $template) {
            TemplateNotifikasi::create($template);
        }
    }
}
