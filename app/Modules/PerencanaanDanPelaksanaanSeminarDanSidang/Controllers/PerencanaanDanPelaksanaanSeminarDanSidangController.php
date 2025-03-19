<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminarDanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PerencanaanDanPelaksanaanSeminarDanSidangController extends Controller
{
    public function index(): View
    {
        // Simulasikan waktu sekarang
        $sekarang = Carbon::parse('2025-06-22 12:30:00'); // Untuk simulasi

        // Data presensi sementara
        $presensiSeminar3 = [
            [
                'id_kehadiran' => 'sm3_221524033', // ID unik untuk Seminar 3
                'nim' => '221524033',
                'mahasiswa' => 'Bang Jay',
                'id_kota' => '2',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu_sidang' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524033', 'belum_absensi'),//default status hadir
                'dokumentasi' => session('dok_sm3_221524033', '') // Ambil data dokumentasi dari session
            ],
        ];

        $presensiSidangTA = [
            [
                'id_kehadiran' => 'sa_221524033', // ID unik untuk Sidang TA
                'nim' => '221524033',
                'mahasiswa' => 'Bang Jay',
                'id_kota' => '2',
                'tanggal' => '22-08-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu_sidang' => '2025-08-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sa_221524033', 'belum_absensi'),//default status hadir
                'dokumentasi' => session('dok_sa_221524033', '') // Ambil data dokumentasi dari session
            ],
        ];

        // Teruskan $sekarang dan $presensi ke view
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.view', compact('presensiSeminar3','presensiSidangTA', 'sekarang'));
    }

    public function rekapPresensi(): View
    {
        // Data presensi sementara
        $presensiSeminar3 = [
            [
                'id_kehadiran' => 'sm3_221524033', // ID unik untuk Seminar 3
                'nim' => '221524033',
                'mahasiswa' => 'Bang Jay',
                'id_kota' => '2',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu_sidang' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524033', 'belum_absensi'),
                'dokumentasi' => session('dok_sm3_221524033', '') // Ambil data dokumentasi dari session
            ],
            [
                'id_kehadiran' => 'sm3_221524034',
                'nim' => '221524034',
                'mahasiswa' => 'Bang Jono',
                'id_kota' => '2',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu_sidang' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524034', 'belum_absensi'),
                'dokumentasi' => session('dok_sm3_221524034', '') // Ambil data dokumentasi dari session
            ],
            [
                'id_kehadiran' => 'sm3_221524035', 
                'nim' => '221524035',
                'mahasiswa' => 'Bang Jarwo',
                'id_kota' => '2',
                'tanggal' => '22-06-2025',
                'ruangan' => '22jtk44',
                'sesi' => '2',
                'waktu_sidang' => '2025-06-22 12:00:00', // Waktu sidang
                'status_hadir' => session('status_hadir_sm3_221524035', 'belum_absensi'),
                'dokumentasi' => session('dok_sm3_221524035', '') // Ambil data dokumentasi dari session
            ]
        ];

        // Teruskan $presensi ke view rekap
        return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.rekap_presensi_seminar_3', compact('presensiSeminar3'));
    }
    
    public function rekapPresensiSidangTA(): View
{
    // Data presensi Sidang TA
    $presensiSidangTA = [
        [
            'id_kehadiran' => 'sa_221524033', // ID unik untuk Sidang TA
            'nim' => '221524033',
            'mahasiswa' => 'Bang Jay',
            'id_kota' => '2',
            'tanggal' => '22-08-2025',
            'ruangan' => '22jtk44',
            'sesi' => '2',
            'waktu_sidang' => '2025-08-22 12:00:00', // Waktu sidang
            'status_hadir' => session('status_hadir_sa_221524033', 'belum_absensi'),
            'dokumentasi' => session('dok_sa_221524033', '') // Ambil data dokumentasi dari session
        ],
        [
            'id_kehadiran' => 'sa_221524034',
            'nim' => '221524034',
            'mahasiswa' => 'Bang Jono',
            'id_kota' => '2',
            'tanggal' => '22-08-2025',
            'ruangan' => '22jtk44',
            'sesi' => '2',
            'waktu_sidang' => '2025-08-22 12:00:00', // Waktu sidang
            'status_hadir' => session('status_hadir_sa_221524034', 'belum_absensi'),
            'dokumentasi' => session('dok_sa_221524034', '') // Ambil data dokumentasi dari session
        ],
        [
            'id_kehadiran' => 'sa_221524035', // ID unik untuk Sidang TA
            'nim' => '221524035',
            'mahasiswa' => 'Bang Jarwo',
            'id_kota' => '2',
            'tanggal' => '22-08-2025',
            'ruangan' => '22jtk44',
            'sesi' => '2',
            'waktu_sidang' => '2025-08-22 12:00:00', // Waktu sidang
            'status_hadir' => session('status_hadir_sa_221524035', 'belum_absensi'),
            'dokumentasi' => session('dok_sa_221524035', '') // Ambil data dokumentasi dari session
        ]
    ];

    // Teruskan $presensiSidangTA ke view rekap
    return view('PerencanaanDanPelaksanaanSeminarDanSidang.views.rekap_presensi_sidang_ta', compact('presensiSidangTA'));
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
}
