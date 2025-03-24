<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Kota;
use App\Models\User;
use App\Models\PengajuanPembimbing;
use App\Models\PrioritasPembimbing;
use App\Models\AlokasiDosen;
use App\Models\Dosen;
use App\Models\Bidang;
use App\Models\Mahasiswa;
use App\Models\KetertarikanBidang;
use App\Models\KuotaMembimbing;
use Illuminate\Support\Facades\DB;
use App\Models\PreferensiKota;
use App\Services\NotifikasiService;
use App\Models\TemplateNotifikasi;
use App\Models\Notifikasi;

class AlokasiPembimbingController extends Controller
{protected $notifikasiService;

    public function __construct(NotifikasiService $notifikasiService)
    {
        $this->notifikasiService = $notifikasiService;
    }
    public function index(): View
    {
        $data_pengajuan = PengajuanPembimbing::join('kota', 'pengajuan_pembimbing.id_kota', '=', 'kota.id_kota')
            ->join('bidang', 'kota.id_bidang', '=', 'bidang.id_bidang')
            ->select('pengajuan_pembimbing.*', 'kota.nama_kota', 'kota.id_bidang', 'kota.judul_ta', 'bidang.bidang')
            ->get();

        foreach ($data_pengajuan as $key => $value) {
            $listMahasiswaOnKelompok = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
                ->where('id_kota', $value->id_kota)
                ->get();

            $listUsulanDosen = PrioritasPembimbing::join('dosen', 'prioritas_pembimbing.nip', '=', 'dosen.nip')
                ->where('id_pengajuan', $value->id_pengajuan_pembimbing)
                ->get();

                $preferensiDosen = PreferensiKota::join('dosen', 'preferensi_kota.nip', '=', 'dosen.nip')
                ->where('preferensi_kota.id_kota', $value->id_kota)
                ->select('dosen.id_dosen', 'dosen.nip')
                ->limit(2)
                ->get();

            $data_pengajuan[$key]['mahasiswa'] = $listMahasiswaOnKelompok;
            $data_pengajuan[$key]['usulan_dosen'] = $listUsulanDosen;
            $data_pengajuan[$key]['preferensi_dosen'] = $preferensiDosen; // inject ke view
        }

        $dosenList = Dosen::join('user', 'dosen.nip', '=', 'user.username')->get();

        foreach ($dosenList as $key => $value) {
            $dosenBidang = KetertarikanBidang::join('bidang', 'ketertarikan_bidang.id_bidang', '=', 'bidang.id_bidang')
                ->where('nip', $value->nip)
                ->get();

            $dosenList[$key]['ketertarikan_bidang'] = $dosenBidang;
        }

        return view('PengajuanAlokasiPembimbing.views.AlokasiPembimbing.AlokasiPembimbing', [
            'list_pengajuan' => $data_pengajuan,
            'dosenList' => $dosenList
        ]);
    }

    public function getDetailDosen($nip): JsonResponse
    {
        $dosen = User::with('dosen')
            ->whereHas('dosen', function ($query) use ($nip) {
                $query->where('id_dosen', $nip);
            })
            ->where('role_user', 'dosen')
            ->select('username', 'nama')
            ->first();

    if (!$dosen) {
        return response()->json(['error' => 'Dosen tidak ditemukan'], 404);
    }

        $pembimbing1_KoTA = AlokasiDosen::where('nip', $dosen->dosen->nip)
            ->where('urutan_prioritas_terpilih', '1')
            ->where('status_alokasi', 'fix')
            ->count();

        $pembimbing2_KoTA = AlokasiDosen::where('nip', $dosen->dosen->nip)
            ->where('urutan_prioritas_terpilih', '2')
            ->where('status_alokasi', 'fix')
            ->count();

        $jumlah_KoTA = $pembimbing1_KoTA + $pembimbing2_KoTA;


        $pembimbing1_Mhs = Mahasiswa::whereHas('kota.pengajuanPembimbing.alokasiDosen', function ($query) use ($dosen) {
            $query->where('nip',  $dosen->dosen->nip)
                  ->where('urutan_prioritas_terpilih', '1')
                  ->where('status_alokasi', 'fix');
        })->count();

        $pembimbing2_Mhs = Mahasiswa::whereHas('kota.pengajuanPembimbing.alokasiDosen', function ($query) use ($dosen) {
            $query->where('nip',  $dosen->dosen->nip)
                  ->where('urutan_prioritas_terpilih', '2')
                  ->where('status_alokasi', 'fix');
        })->count();

        $jumlahMahasiswa = $pembimbing1_Mhs + $pembimbing2_Mhs;

        $kuota = KuotaMembimbing::where('nip', $dosen->dosen->nip)->sum('jumlah');

        $kelebihan = $jumlahMahasiswa > $kuota ? ($jumlahMahasiswa - $kuota) . " (Overload)" : "Aman";

        return response()->json([
            "nama" => $dosen->nama,
            'nip' => $dosen->username,
            'id_kbk' => $dosen->dosen->id_kbk ?? null,
            "pembimbing1_KoTA" => $pembimbing1_KoTA,
            "pembimbing2_KoTA" => $pembimbing2_KoTA,
            "jumlah_KoTA" => $jumlah_KoTA,
            "pembimbing1_Mhs" => $pembimbing1_Mhs,
            "pembimbing2_Mhs" => $pembimbing2_Mhs,
            "jumlahMahasiswa" => $jumlahMahasiswa,
            "kuota" => $kuota,
            "kelebihan" => $kelebihan,
            'status_dosen' => $dosen->dosen->status_dosen ?? null,
            'role_dosen' => $dosen->dosen->role_dosen ?? null,
            'bersedia_membimbing' => $dosen->dosen->bersedia_membimbing ?? null

        ]);
    }
    public function submit(Request $request)
    {
        $data = json_decode($request->input('dataToSend'));

        foreach ($data as $value) {
            $id_kota = PengajuanPembimbing::where('id_pengajuan_pembimbing', $value->id_pengajuan_pembimbing)
                ->pluck('id_kota')
                ->first();

            // Ambil NIP pembimbing dan penguji
            $pembimbing1 = $value->pembimbing1 ?? null;
            $pembimbing2 = $value->pembimbing2 ?? null;

            $penguji1 = $value->penguji1 ?? null;
            $penguji2 = $value->penguji2 ?? null;
            $penguji3 = $value->penguji3 ?? null;

            // Validasi: Minimal harus ada 1 pembimbing dan 1 penguji
            if (empty($pembimbing1) && empty($pembimbing2)) {
                return back()->with('error', 'Minimal 1 pembimbing harus diisi sebelum finalisasi.');
            }

            if (empty($penguji1) && empty($penguji2) && empty($penguji3)) {
                return back()->with('error', 'Minimal 1 penguji harus diisi sebelum finalisasi.');
            }

            // Validasi: Tidak boleh ada pembimbing yang sama dalam satu kelompok
            if (!empty($pembimbing1) && !empty($pembimbing2) && $pembimbing1 == $pembimbing2) {
                return back()->with('error', 'Pembimbing 1 dan Pembimbing 2 tidak boleh sama dalam satu KoTA.');
            }

            // Validasi: Tidak boleh ada penguji yang sama dalam satu kelompok
            $pengujiSet = array_filter([$penguji1, $penguji2, $penguji3]); // Buang null atau kosong
            if (count($pengujiSet) !== count(array_unique($pengujiSet))) {
                return back()->with('error', 'Penguji tidak boleh sama dalam satu KoTA.');
            }

            // Proses penyimpanan data
            $nip_dosen_1 = Dosen::where('id_dosen', $pembimbing1)->select('nip')->first();
            $nip_dosen_2 = Dosen::where('id_dosen', $pembimbing2)->select('nip')->first();

            $nip_penguji_1 = Dosen::where('id_dosen', $penguji1)->select('nip')->first();
            $nip_penguji_2 = Dosen::where('id_dosen', $penguji2)->select('nip')->first();
            $nip_penguji_3 = Dosen::where('id_dosen', $penguji3)->select('nip')->first();

            $catatan = $value->catatan ?? null;

            // **Alokasi Pembimbing**
            if ($nip_dosen_1) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_dosen_1->nip],
                    [
                        'urutan_prioritas_terpilih' => 1,
                        'status_alokasi' => $value->status_pembimbing1 ?? 'belum_fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'pembimbing'
                    ]
                );
            }

            // Panggil function kirim notifikasi
            $this->kirimNotifikasiAlokasi($nip_dosen_1, auth()->user()->username);

            if ($nip_dosen_2) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_dosen_2->nip],
                    [
                        'urutan_prioritas_terpilih' => 2,
                        'status_alokasi' => $value->status_pembimbing2 ?? 'belum_fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'pembimbing'
                    ]
                );
            }

            // Panggil function kirim notifikasi
            $this->kirimNotifikasiAlokasi($nip_dosen_2, auth()->user()->username);

            // **Alokasi Penguji**
            if ($nip_penguji_1) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_penguji_1->nip],
                    [
                        'urutan_prioritas_terpilih' => 1,
                        'status_alokasi' => 'fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'penguji'
                    ]
                );
            }

            if ($nip_penguji_2) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_penguji_2->nip],
                    [
                        'urutan_prioritas_terpilih' => 2,
                        'status_alokasi' => 'fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'penguji'
                    ]
                );
            }

            if ($nip_penguji_3) {
                AlokasiDosen::updateOrCreate(
                    ['id_pengajuan_pembimbing' => $value->id_pengajuan_pembimbing, 'nip' => $nip_penguji_3->nip],
                    [
                        'urutan_prioritas_terpilih' => 3,
                        'status_alokasi' => 'fix',
                        'catatan' => $catatan,
                        'tipe_alokasi' => 'penguji'
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Data alokasi pembimbing dan penguji berhasil disimpan.');
    }

    public function kirimNotifikasiAlokasi($dosenUsername, $mahasiswaUsername) 
    {
        $templateDosen = TemplateNotifikasi::where('judul_notifikasi', '[Pemberitahuan] Dosen Pembimbing Tugas AKhir Telah Ditetapkan!')->first();
        $templateMahasiswa = TemplateNotifikasi::where('judul_notifikasi', '[Pemberitahuan] Dosen Pembimbing Tugas AKhir Telah Ditetapkan!')->first();
    
        if (!$dosenUsername || !$mahasiswaUsername || !$templateDosen || !$templateMahasiswa) {
            return response()->json(['error' => 'Semua field wajib diisi'], 400);
        }
    
        // Cari user berdasarkan username
        $dosen = User::where('username', $dosenUsername)->first();
        $mahasiswa = User::where('username', $mahasiswaUsername)->first();
    
        if (!$dosen || !$mahasiswa) {
            return response()->json(['error' => 'Dosen atau Mahasiswa tidak ditemukan'], 404);
        }
    
        // Ambil template notifikasi
        $templateDosen = TemplateNotifikasi::where('judul_notifikasi', $templateDosen)->first();
        $templateMahasiswa = TemplateNotifikasi::where('judul_notifikasi', $templateMahasiswa)->first();        
    
        if (!$templateDosen || !$templateMahasiswa) {
            return response()->json(['error' => 'Template notifikasi tidak ditemukan'], 404);
        }

        // Prepare email content by dynamically replacing placeholders
        $isiInEmailDosen = str_replace(
            ['{nama_dosen}', '{nama_mahasiswa}', '{nim_mahasiswa}'],
            [$dosen->nama, $mahasiswa->nama, $mahasiswa->username],
            $templateDosen->isi_in_email
        );
    
        // Kirim email ke dosen
        $this->notifikasiService->kirimEmail(
            $templateDosen->judul_notifikasi,
            $dosen->username,
            [
                'nama_dosen' => $dosen->nama,
                'nama_mahasiswa' => $mahasiswa->nama,
                'nim_mahasiswa' => $mahasiswa->nim,
                'email_content' => $isiInEmailDosen
            ]
        );
    
        // Simpan notifikasi ke database
        Notifikasi::create([
            'tipe_notifikasi' => $templateDosen->jenis_notifikasi,
            'judul' => $templateDosen->judul_notifikasi,
            'isi_notifikasi' => $isiInEmailDosen,
        ]);

        // Prepare email content by dynamically replacing placeholders
        $isiInEmailMahasiswa = str_replace(
            ['{nama_mahasiswa}', '{nama_dosen}'],
            [$mahasiswa->nama, $dosen->nama],
            $templateMahasiswa->isi_in_email
        );
    
        // Kirim email ke mahasiswa
        $this->notifikasiService->kirimEmail(
            $templateMahasiswa->judul_notifikasi,
            $mahasiswa->username,
            [
                'nama_mahasiswa' => $mahasiswa->nama,
                'nama_dosen' => $dosen->nama,
                'email_content' => $isiInEmailMahasiswa
            ]
        );
    
        // Simpan notifikasi untuk mahasiswa (dengan user_id)
        Notifikasi::create([
            'tipe_notifikasi' => $templateMahasiswa->jenis_notifikasi,
            'judul' => $templateMahasiswa->judul_notifikasi,
            'isi_notifikasi' => $isiInEmailMahasiswa,
        ]);
    
        return response()->json(['success' => 'Notifikasi berhasil dikirim'], 200);
    }    

    // public function kirimNotifikasiAlokasi(Request $request) 
    // {
    //     // Gunakan username, bukan ID
    //     $dosenUsername = $request->input('dosen_username');
    //     $mahasiswaUsername = $request->input('mahasiswa_username');
    //     $templateDosen = $request->input('template_dosen_id');
    //     $templateMahasiswa = $request->input('template_mahasiswa_id');
    
    //     if (!$dosenUsername || !$mahasiswaUsername || !$templateDosen || !$templateMahasiswa) {
    //         return response()->json(['error' => 'Semua field wajib diisi'], 400);
    //     }
    
    //     // Cari user berdasarkan username
    //     $dosen = User::where('username', $dosenUsername)->first();
    //     $mahasiswa = User::where('username', $mahasiswaUsername)->first();
    
    //     if (!$dosen || !$mahasiswa) {
    //         return response()->json(['error' => 'Dosen atau Mahasiswa tidak ditemukan'], 404);
    //     }
    
    //     // Ambil template notifikasi
    //     $templateDosen = TemplateNotifikasi::where('judul_notifikasi', $templateDosen)->first();
    //     $templateMahasiswa = TemplateNotifikasi::where('judul_notifikasi', $templateMahasiswa)->first();        
    
    //     if (!$templateDosen || !$templateMahasiswa) {
    //         return response()->json(['error' => 'Template notifikasi tidak ditemukan'], 404);
    //     }

    //     // Prepare email content by dynamically replacing placeholders
    //     $isiInEmailDosen = str_replace(
    //         ['{nama_dosen}', '{nama_mahasiswa}', '{nim_mahasiswa}'],
    //         [$dosen->nama, $mahasiswa->nama, $mahasiswa->username],
    //         $templateDosen->isi_in_email
    //     );
    
    //     // Kirim email ke dosen
    //     $this->notifikasiService->kirimEmail(
    //         $templateDosen->judul_notifikasi,
    //         $dosen->username,
    //         [
    //             'nama_dosen' => $dosen->nama,
    //             'nama_mahasiswa' => $mahasiswa->nama,
    //             'nim_mahasiswa' => $mahasiswa->nim,
    //             'email_content' => $isiInEmailDosen
    //         ]
    //     );
    
    //     // Simpan notifikasi ke database
    //     Notifikasi::create([
    //         'tipe_notifikasi' => $templateDosen->jenis_notifikasi,
    //         'judul' => $templateDosen->judul_notifikasi,
    //         'isi_notifikasi' => $isiInEmailDosen,
    //     ]);

    //     // Prepare email content by dynamically replacing placeholders
    //     $isiInEmailMahasiswa = str_replace(
    //         ['{nama_mahasiswa}', '{nama_dosen}'],
    //         [$mahasiswa->nama, $dosen->nama],
    //         $templateMahasiswa->isi_in_email
    //     );
    
    //     // Kirim email ke mahasiswa
    //     $this->notifikasiService->kirimEmail(
    //         $templateMahasiswa->judul_notifikasi,
    //         $mahasiswa->username,
    //         [
    //             'nama_mahasiswa' => $mahasiswa->nama,
    //             'nama_dosen' => $dosen->nama,
    //             'email_content' => $isiInEmailMahasiswa
    //         ]
    //     );
    
    //     // Simpan notifikasi untuk mahasiswa (dengan user_id)
    //     Notifikasi::create([
    //         'tipe_notifikasi' => $templateMahasiswa->jenis_notifikasi,
    //         'judul' => $templateMahasiswa->judul_notifikasi,
    //         'isi_notifikasi' => $isiInEmailMahasiswa,
    //     ]);
    
    //     return response()->json(['success' => 'Notifikasi berhasil dikirim'], 200);
    // }   
    // Route::post('/api/kirim-notifikasi-alokasi', [AlokasiPembimbingController::class, 'kirimNotifikasiAlokasi']);

}
