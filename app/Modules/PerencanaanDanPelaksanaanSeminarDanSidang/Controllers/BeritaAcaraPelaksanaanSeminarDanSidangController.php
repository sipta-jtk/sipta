<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraPelaksanaanSeminarDanSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    public function indexBeritaAcaraSeminar3(): View
    {
        // Simulasikan waktu sekarang
        $sekarang = Carbon::parse('2025-06-22 11:30:00'); // Untuk simulasi
        // $sekarang->setTimezone('Asia/Jakarta'); // Set timezone ke WIB
        //$sekarang = Carbon::now('Asia/Jakarta');

        // Data presensi sementara
        $beritaAcaraSeminar3 = [
            [
                'id_kehadiran' => 'sm3_221524033', // ID unik untuk Seminar 3
                'nim' => '221524033',
                'mahasiswa' => 'Mees Victor Joseph Hilgers',
                'id_kota' => 'KoTA 001',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524033', 'belum_absensi'),//default status hadir
                'dokumentasi' => session('dok_sm3_221524033', '') // Ambil data dokumentasi dari session
            ],
        ];

        // Teruskan $sekarang dan $presensi ke view
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.BeritaAcara.BeritaAcaraSeminar3', compact('beritaAcaraSeminar3', 'sekarang'));
    }


    public function indexBeritaAcaraSidangTA(): View
    {
        // Simulasikan waktu sekarang
        $sekarang = Carbon::parse('2025-08-22 11:30:00'); // Untuk simulasi
        // $sekarang->setTimezone('Asia/Jakarta'); // Set timezone ke WIB
        //$sekarang = Carbon::now('Asia/Jakarta');

        // Data presensi sementara
        $beritaAcaraSidangTA = [
            [
                'id_kehadiran' => 'sa_221524033', // ID unik untuk Sidang TA
                'nim' => '221524033',
                'mahasiswa' => 'Mees Victor Joseph Hilgers',
                'id_kota' => 'KoTA 001',
                'tanggal' => '22-08-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu' => '2025-08-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sa_221524033', 'belum_absensi'),//default status hadir
                'dokumentasi' => session('dok_sa_221524033', '') // Ambil data dokumentasi dari session

            ],
        ];

        // Teruskan $sekarang dan $presensi ke view
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.BeritaAcara.BeritaAcaraSidangTA', compact('beritaAcaraSidangTA', 'sekarang'));
    }

    public function rekapBeritaAcaraSeminar3(): View
    {
        // Data presensi sementara
        $beritaAcaraSeminar3 = [
            [
                'id_kehadiran' => 'sm3_221524033', // ID unik untuk Seminar 3
                'nim' => '221524033',
                'mahasiswa' => 'Mees Victor Joseph Hilgers',
                'id_kota' => 'KoTA 001',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524033', 'belum_absensi'),
                'dokumentasi' => session('dok_sm3_221524033', '') // Ambil data dokumentasi dari session
            ],
            [
                'id_kehadiran' => 'sm3_221524034',
                'nim' => '221524034',
                'mahasiswa' => 'Bang Jono',
                'id_kota' => 'KoTA 001',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524034', 'belum_absensi'),
                'dokumentasi' => session('dok_sm3_221524034', ''), // Ambil data dokumentasi dari session
            ],
            [
                'id_kehadiran' => 'sm3_221524035', 
                'nim' => '221524035',
                'mahasiswa' => 'Bang Jarwo',
                'id_kota' => 'KoTA 002',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '3',
                'waktu' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524035', 'belum_absensi'),
                'dokumentasi' => session('dok_sm3_221524035', '') // Ambil data dokumentasi dari session
            ]
        ];

        // Teruskan $presensi ke view rekap
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.RekapBeritaAcara.RekapBeritaAcaraSeminar3', compact('beritaAcaraSeminar3'));
    }
    
    public function rekapBeritaAcaraSidangTA(): View
{
    // Data Berita Acara Sidang TA Semua MHS Sementara
    $beritaAcaraSidangTA = [
        [
            'id_kehadiran' => 'sa_221524033', // ID unik untuk Sidang TA
            'nim' => '221524033',
            'mahasiswa' => 'Mees Victor Joseph Hilgers',
            'id_kota' => 'KoTA 001',
            'tanggal' => '22-08-2025',
            'ruangan' => '22jtk44',
            'sesi' => '2',
            'waktu' => '2025-08-22 12:00:00', // Waktu sidang
            'status_hadir' => session('status_hadir_sa_221524033', 'belum_absensi'),
            'dokumentasi' => session('dok_sa_221524033', ''), // Ambil data dokumentasi dari session
            'batas_revisi' => session('batas_revisi_sa_221524033', '') 
        ],
        [
            'id_kehadiran' => 'sa_221524034',
            'nim' => '221524034',
            'mahasiswa' => 'Bang Jono',
            'id_kota' => 'KoTA 001',
            'tanggal' => '22-08-2025',
            'ruangan' => '22jtk44',
            'sesi' => '2',
            'waktu' => '2025-08-22 12:00:00', // Waktu sidang
            'status_hadir' => session('status_hadir_sa_221524034', 'belum_absensi'),
            'dokumentasi' => session('dok_sa_221524034', ''), // Ambil data dokumentasi dari session
            'batas_revisi' => session('batas_revisi_sa_221524034', '')
        ],
        [
            'id_kehadiran' => 'sa_221524035', // ID unik untuk Sidang TA
            'nim' => '221524035',
            'mahasiswa' => 'Bang Jarwo',
            'id_kota' => 'KoTA 002',
            'tanggal' => '22-08-2025',
            'ruangan' => '22jtk44',
            'sesi' => '3',
            'waktu' => '2025-08-22 12:00:00', // Waktu sidang
            'status_hadir' => session('status_hadir_sa_221524035', 'belum_absensi'),
            'dokumentasi' => session('dok_sa_221524035', '') // Ambil data dokumentasi dari session
        ]
    ];

    // Teruskan $ ke view rekap
    return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.RekapBeritaAcara.RekapBeritaAcaraSidangTA', compact('beritaAcaraSidangTA'));
}


    public function simpanKehadiran(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|string', // Gunakan id_kehadiran sebagai identifier
        ]);

        // Simpan status kehadiran di session
        session(["status_hadir_{$request->input('id_kehadiran')}" => 'hadir']);

        return redirect()->back()->with('success', 'Status kehadiran berhasil disimpan.');
    }

    public function simpanDokumentasi(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|string',
            'dokumentasi' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5 MB
        ], [
            'dokumentasi.max' => 'Ukuran file tidak boleh lebih dari 5 MB.', // Pesan custom
        ]);

        // Ambil id_kehadiran dari request
        $idKehadiran = $request->input('id_kehadiran');

        // Cek apakah ada file lama di session
        $fileLama = session("dok_{$idKehadiran}");

        // Jika ada file lama, hapus dari storage
        if ($fileLama && Storage::disk('public')->exists($fileLama)) {
            Storage::disk('public')->delete($fileLama);
        }

        // Simpan file baru ke storage
        $dokumentasiPath = $request->file('dokumentasi')->store('dokumentasi', 'public');

        // Simpan path file baru ke session
        session(["dok_{$idKehadiran}" => $dokumentasiPath]);

        return redirect()->back()->with('success', 'Dokumentasi berhasil diupload.');
    }
<<<<<<< HEAD:app/Modules/PerencanaanDanPelaksanaanSeminarDanSidang/Controllers/BeritaAcaraPelaksanaanSeminarDanSidangController.php

    public function simpanBatasRevisi(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|string',
            'batas_revisi' => 'required|date',
        ]);

        // Simpan batas revisi di session
        session(["batas_revisi_{$request->input('id_kehadiran')}" => $request->input('batas_revisi')]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Batas revisi berhasil disimpan.');
    }

    public function simpanStatusKelulusan(Request $request)
    {
        // Validasi request
        $request->validate([
            'id_kehadiran' => 'required|string',
            'status_kelulusan' => 'required|string|in:Lulus Tanpa Perbaikan,Lulus Dengan Perbaikan,Mengulang Sidang,Tidak Lulus',
        ]);

        // Simpan status kelulusan di session
        session(["status_kelulusan_{$request->input('id_kehadiran')}" => $request->input('status_kelulusan')]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status kelulusan berhasil disimpan.');
    }

}
=======
}
>>>>>>> ccb2c1c70a56531448274efb5000b7781e575414:app/Modules/PerencanaanDanPelaksanaanSeminarDanSidang/Controllers/PerencanaanDanPelaksanaanSeminarDanSidangController.php
