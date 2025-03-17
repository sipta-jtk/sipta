<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PerencanaanDanPelaksanaanSeminar3DanSidangController extends Controller
{
    public function index(): View
    {
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.view');
    }

    // public function indexPengajuan(): View
    // {
    //     $artifact = (object) [
    //         'seminar1' => true,
    //         'seminar2' => true,
    //         'seminar3' => false, // Misalnya seminar 3 belum selesai
    //         'berkas' => true
    //     ];

    //     return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.daftarpengajuan', compact('artifact'));
    // }
    public function indexPengajuan(): View
    {
        // Ambil semua data verifikasi berdasarkan id_kota (tidak memfilter status)
        // $verifikasi = VerifikasiBerkasPengajuan::where('id_kota', $id_kota)->get(); 

        // Data dummy atau dari database
        $verifikasi = collect([
            (object) ['jenis_pangajuan' => 'seminar_3', 'status_konfirmasi' => 'disetujui'],
            (object) ['jenis_pangajuan' => 'sidang', 'status_konfirmasi' => 'tidak_disetujui'],
        ]);

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.DaftarPengajuan', compact('verifikasi'));
    }

    // public function indexPengajuanSeminar3($id_kota, Request $request): View
    // {
    //     // Ambil data kota dengan mahasiswa dan pembimbing
    //     $kota = Kota::with([
    //         'mahasiswa.user',
    //         'pengajuanPembimbing.alokasiPembimbing.dosen' => function ($query) {
    //             $query->where('status_alokasi', 'diterima');
    //         }
    //     ])->find($id_kota);

    //     if (!$kota) {
    //         return response()->json(['message' => 'Kota tidak ditemukan'], 404);
    //     }

    //     $mahasiswa = $kota->mahasiswa;
    //     $pembimbing = $kota->pengajuanPembimbing->flatMap->alokasiPembimbing;

    //     // Ambil data ruangan (dari database atau stub)
    //     $useStub = $request->query('stub', false);

    //     if ($useStub) {
    //         $ruangan = collect(RuanganStub::getData());
    //     } else {
    //         $ruangan = Ruangan::all();
    //     }

    //     // Filter hanya ruangan yang tersedia
    //     $ruanganTersedia = $ruangan->where('status_ruangan', 'tersedia');

    //     return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.seminar3', [
    //         'dataKota' => $kota,
    //         'mahasiswa' => $mahasiswa,
    //         'pembimbing' => $pembimbing,
    //         'ruanganTersedia' => $ruanganTersedia
    //     ]);
    // }

    public function indexPengajuanSeminar3(Request $request): View
    {
        $dataKota = (object) [
            'nama_kota' => '101',
            'judul_ta' => 'Pengembangan Aplikasi Monitoring Tugas Akhir di Jurusan Teknik Komputer dan Informatika',
            'mahasiswa' => collect([
                (object) [
                    'id' => 1,
                    'user' => (object) ['nama' => 'Mahasiswa 1', 'nim' => '101010101']
                ],
                (object) [
                    'id' => 2,
                    'user' => (object) ['nama' => 'Mahasiswa 2', 'nim' => '202020202']
                ],
                (object) [
                    'id' => 3,
                    'user' => (object) ['nama' => 'Mahasiswa 3', 'nim' => '303030303']
                ]
            ]),
            'pengajuanPembimbing' => collect([
                (object) [
                    'alokasiPembimbing' => collect([
                        (object) [
                            'dosen' => (object) ['nama' => 'Dosen Pembimbing 1', 'nip' => '101010101'],
                            'status_alokasi' => 'diterima'
                        ],
                        (object) [
                            'dosen' => (object) ['nama' => 'Dosen Pembimbing 2', 'nip' => '202020202'],
                            'status_alokasi' => 'diterima'
                        ]
                    ])
                ]
            ])
        ];

        // Simulasi filtering hanya pembimbing yang diterima
        $pembimbing = $dataKota->pengajuanPembimbing
            ->flatMap(fn($item) => $item->alokasiPembimbing)
            ->where('status_alokasi', 'diterima')
            ->map(fn($alokasi) => $alokasi->dosen);

        // Ambil data ruangan (dari stub atau database)
        $useStub = $request->query('stub', true);
        if ($useStub) {
            $ruangan = collect([
                [
                    'id_ruangan' => 1,
                    'kode_ruangan' => 'D224',
                    'nama_ruangan' => 'Lab Komputer',
                    'status_ruangan' => 'tersedia',
                    'kode_gedung' => 'A',
                    'link_photo' => 'https://example.com/photo1.jpg'
                ],
                [
                    'id_ruangan' => 2,
                    'kode_ruangan' => 'D225',
                    'nama_ruangan' => 'Ruang Seminar',
                    'status_ruangan' => 'tidak_tersedia',
                    'kode_gedung' => 'B',
                    'link_photo' => 'https://example.com/photo2.jpg'
                ],
                [
                    'id_ruangan' => 3,
                    'kode_ruangan' => 'D226',
                    'nama_ruangan' => 'Ruang Rapat',
                    'status_ruangan' => 'tersedia',
                    'kode_gedung' => 'C',
                    'link_photo' => 'https://example.com/photo3.jpg'
                ]
            ]);
        }


        // Filter hanya ruangan yang tersedia
        $ruanganTersedia = $ruangan->where('status_ruangan', 'tersedia');

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.PengajuanSeminar3', [
            'dataKota' => $dataKota,
            'mahasiswa' => $dataKota->mahasiswa,
            'pembimbing' => $pembimbing,
            'ruanganTersedia' => $ruanganTersedia
        ]);
    }


    // public function indexPengajuanSidang(): View
    // {
    //     $dataKota = (object) [
    //         'kota_no' => '101',
    //         'judul_ta' => 'Pengembangan Aplikasi Monitoring Tugas Akhir di Jurusan Teknik Komputer dan Informatika',
    //         'mahasiswa' => [
    //             (object) ['nama' => 'Mahasiswa 1', 'nim' => '101010101'],
    //             (object) ['nama' => 'Mahasiswa 2', 'nim' => '202020202'],
    //             (object) ['nama' => 'Mahasiswa 3', 'nim' => '303030303'],
    //         ],
    //         'pembimbing' => (object) [
    //             'nama' => 'Dosen Pembimbing',
    //             'nip' => '101010101'
    //         ],
    //         'penguji' => [
    //             (object) ['nama' => 'Penguji 1', 'nip' => '101010101'],
    //             (object) ['nama' => 'Penguji 2', 'nip' => '202020202']
    //         ]
    //     ];
    //     return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.PengajuanSidang', compact('dataKota'));
    // }
    public function indexPengajuanSidang(Request $request): View
    {
        $dataKota = (object) [
            'nama_kota' => '101',
            'judul_ta' => 'Pengembangan Aplikasi Monitoring Tugas Akhir di Jurusan Teknik Komputer dan Informatika',
            'mahasiswa' => collect([
                (object) [
                    'id' => 1,
                    'user' => (object) ['nama' => 'Mahasiswa 1', 'nim' => '101010101']
                ],
                (object) [
                    'id' => 2,
                    'user' => (object) ['nama' => 'Mahasiswa 2', 'nim' => '202020202']
                ],
                (object) [
                    'id' => 3,
                    'user' => (object) ['nama' => 'Mahasiswa 3', 'nim' => '303030303']
                ]
            ]),
            'pengajuanPembimbing' => collect([
                (object) [
                    'alokasiPembimbing' => collect([
                        (object) [
                            'dosen' => (object) ['nama' => 'Dosen Pembimbing 1', 'nip' => '101010101'],
                            'status_alokasi' => 'diterima'
                        ]
                    ])
                ]
            ]),
            'pengajuanPenguji' => collect([
                (object) [
                    'alokasiPenguji' => collect([
                        (object) [
                            'dosen' => (object) ['nama' => 'Penguji 1', 'nip' => '303030303'],
                            'status_alokasi' => 'diterima'
                        ],
                        (object) [
                            'dosen' => (object) ['nama' => 'Penguji 2', 'nip' => '404040404'],
                            'status_alokasi' => 'diterima'
                        ]
                    ])
                ]
            ])
        ];

        // Simulasi filtering hanya pembimbing yang diterima
        $pembimbing = $dataKota->pengajuanPembimbing
            ->flatMap(fn($item) => $item->alokasiPembimbing)
            ->where('status_alokasi', 'diterima')
            ->map(fn($alokasi) => $alokasi->dosen);

        // Simulasi filtering hanya penguji yang diterima
        $penguji = $dataKota->pengajuanPenguji
            ->flatMap(fn($item) => $item->alokasiPenguji)
            ->where('status_alokasi', 'diterima')
            ->map(fn($alokasi) => $alokasi->dosen);

        // Ambil data ruangan (dari stub atau database)
        $useStub = $request->query('stub', true);
        if ($useStub) {
            $ruangan = collect([
                [
                    'id_ruangan' => 1,
                    'kode_ruangan' => 'D224',
                    'nama_ruangan' => 'Lab Komputer',
                    'status_ruangan' => 'tersedia',
                    'kode_gedung' => 'A',
                    'link_photo' => 'https://example.com/photo1.jpg'
                ],
                [
                    'id_ruangan' => 2,
                    'kode_ruangan' => 'D225',
                    'nama_ruangan' => 'Ruang Seminar',
                    'status_ruangan' => 'tidak_tersedia',
                    'kode_gedung' => 'B',
                    'link_photo' => 'https://example.com/photo2.jpg'
                ],
                [
                    'id_ruangan' => 3,
                    'kode_ruangan' => 'D226',
                    'nama_ruangan' => 'Ruang Rapat',
                    'status_ruangan' => 'tersedia',
                    'kode_gedung' => 'C',
                    'link_photo' => 'https://example.com/photo3.jpg'
                ]
            ]);
        }

        // Filter hanya ruangan yang tersedia
        $ruanganTersedia = $ruangan->where('status_ruangan', 'tersedia');

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.PengajuanSidang', [
            'dataKota' => $dataKota,
            'mahasiswa' => $dataKota->mahasiswa,
            'pembimbing' => $pembimbing,
            'penguji' => $penguji, 
            'ruanganTersedia' => $ruanganTersedia
        ]);
    }

    public function tambahPengajuanPenjadwalan(Request $request, $id_kota)
    {
        // Validasi input
        $request->validate([
            'tanggal_pengajuan' => 'required|date',
            'sesi_pengajuan' => 'required|integer',
            'ruangan_pengajuan' => 'required|string|max:255',
            'agenda' => 'required|in:seminar_1,seminar_2,seminar_3,sidang',
        ]);

        // Cek apakah kota dengan id_kota ada
        $kota = Kota::find($id_kota);
        if (!$kota) {
            return redirect()->back()->with('error', 'Kota tidak ditemukan.');
        }

        // Tentukan waktu `start` dan `end` berdasarkan sesi
        $tanggal = Carbon\Carbon::parse($request->input('tanggal_pengajuan'));
        $jadwal_waktu = [
            1 => ['start' => '07:00', 'end' => '09:00'],
            2 => ['start' => '09:00', 'end' => '11:00'],
            3 => ['start' => '13:00', 'end' => '15:00'],
            4 => ['start' => '15:00', 'end' => '17:00'],
        ];

        $sesi = $request->input('sesi_seminar3');
        $start = $tanggal->copy()->setTimeFromTimeString($jadwal_waktu[$sesi]['start']);
        $end = $tanggal->copy()->setTimeFromTimeString($jadwal_waktu[$sesi]['end']);

        // Buat entry baru di tabel penjadwalan
        $penjadwalan = Penjadwalan::create([
            'sesi' => $rsesi,
            'agenda' => $request->input('agenda'),
            'id_ruangan' => $request->input('ruangan_pengajuan'),
            'tanggal' => $request->input('tanggal_pengajuan'),
            'id_kota' => $id_kota,
            'nip' => '',            //// belum tau
            'start' => $start->toISOString(),
            'end' => $end->toISOString(),
        ]);

        // Tambahkan pengajuan jadwal kota yang baru
        PengajuanJadwalKota::create([
            'id_kota' => $id_kota,
            'id_penjadwalan' => $penjadwalan->id_penjadwalan,
            'status_mahasiswa' => true,
            'status_dosen_pembimbing_1' => false,
            'status_dosen_pembimbing_2' => false,
            'status_dosen_penguji_1' => false,
            'status_dosen_penguji_2' => false,
            'status_koordinator_ta' => false,
        ]);

        return redirect()->back()->with('success', 'Penjadwalan berhasil dibuat dan status mahasiswa diperbarui.');
    }
}