<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use Carbon\Carbon;
use App\Modules\Controller;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Penjadwalan;
use App\Models\PengajuanJadwalKota;
use App\Models\VerifikasiBerkasPengajuan;
use App\Services\Notifikasi;
use App\Models\Timeline;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\IsFalse;
use Illuminate\Validation\ValidationException;

class PengajuanJadwalKotaSeminar3DanSidang extends Controller
{
    private $jadwal_waktu = [
        1 => ['start' => '07:00', 'end' => '09:00'],
        2 => ['start' => '09:00', 'end' => '11:00'],
        3 => ['start' => '13:00', 'end' => '15:00'],
        4 => ['start' => '15:00', 'end' => '17:00'],
    ];

    private function checkPengajuanSeminar3($id_kota)
    {
        return $this->checkPengajuanStatus($id_kota, 'seminar_3');
    }

    private function checkPengajuanSidang($id_kota)
    {
        return $this->checkPengajuanStatus($id_kota, 'sidang');
    }

    private function checkPengajuanStatus($id_kota, $agenda)
    {
        $pengajuan = PengajuanJadwalKota::where('id_kota', $id_kota)
            ->whereHas('penjadwalan', function ($query) use ($agenda) {
                $query->where('agenda', $agenda);
            })
            ->with('penjadwalan')
            ->where('status_mahasiswa', 1) 
            ->orderBy('id_penjadwalan', 'desc')
            ->get();

        $latest = $pengajuan->first();

        $formattedTanggal = null;
        if ($latest && $latest->penjadwalan) {
            try {
                $formattedTanggal = Carbon::parse($latest->penjadwalan->tanggal)->translatedFormat('d F Y');
            } catch (\Exception $e) {
                $formattedTanggal = 'Format Tanggal Tidak Valid';
            }
            $penjadwalanData = [
                'tanggal' => $formattedTanggal,
                'sesi'    => $latest->penjadwalan->sesi,
                'nama_ruangan' => $latest->penjadwalan->nama_ruangan,
            ];
        }

        // dd($penjadwalanData);

        if ($pengajuan->isEmpty()) {
            $penjadwalanData = [
                'tanggal' => null,
                'sesi'    => null,
                'ruangan' => null,
            ];

            return ['status' => 'Belum Ada Pengajuan', 'agenda' => $agenda, 'penjadwalan' => $penjadwalanData];
        }

        foreach ($pengajuan as $item) {
            if ($item->status_dosen_pembimbing_1 === 0 || $item->status_dosen_pembimbing_2 === 0) {
                return ['status' => 'Ditolak', 'agenda' => $agenda, 'rejected_step' => 2, 'penjadwalan' => $penjadwalanData];
            }

            if (is_null($item->status_dosen_pembimbing_1) || is_null($item->status_dosen_pembimbing_2)) {
                return ['status' => 'Diajukan', 'agenda' => $agenda, 'penjadwalan' => $penjadwalanData];
            }

            if ((is_null($item->status_dosen_penguji_1) || is_null($item->status_dosen_penguji_2)) && (($item->status_dosen_penguji_1 !== 0) && ($item->status_dosen_penguji_2 !== 0)) && (($item->status_dosen_pembimbing_1 === 1) && ($item->status_dosen_pembimbing_2 === 1))) {
                return ['status' => 'Pembimbing', 'agenda' => $agenda, 'penjadwalan' => $penjadwalanData];
            }

            if ($item->status_dosen_penguji_1 === 0 || $item->status_dosen_penguji_2 === 0) {
                return ['status' => 'Ditolak', 'agenda' => $agenda, 'rejected_step' => 3, 'penjadwalan' => $penjadwalanData];
            }

            if (is_null($item->status_koordinator_ta) && (($item->status_dosen_penguji_1 === 1) && ($item->status_dosen_penguji_2 === 1))) {
                return ['status' => 'Penguji', 'agenda' => $agenda, 'penjadwalan' => $penjadwalanData];
            }

            if ($item->status_koordinator_ta === 0) {
                return ['status' => 'Ditolak', 'agenda' => $agenda, 'rejected_step' => 4, 'penjadwalan' => $penjadwalanData];
            }

            if (
                ($item->status_dosen_pembimbing_1 === 1 && $item->status_dosen_pembimbing_2 === 1) &&
                ($item->status_dosen_penguji_1 === 1 && $item->status_dosen_penguji_2 === 1) &&
                $item->status_koordinator_ta === 1
            ) {
                return ['status' => 'Diterima', 'agenda' => $agenda, 'penjadwalan' => $penjadwalanData];
            }
        }

        return ['status' => 'Diajukan', 'agenda' => $agenda, 'penjadwalan' => $penjadwalanData];
    }
    
    public function indexPengajuan(): View
    {
        // mengambil id_kota dari mahasiswa by username
        $id_kota = Mahasiswa::find(Auth::User()->username)->id_kota;

        // mengambil pembimbing dari tabel user berdasarkan alokasi dosen yang diberikan pada kota yang mengajukan pembimbing
        $pembimbing = User::select('user.username', 'user.nama')
            ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
            ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
            ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->where('pengajuan_pembimbing.id_kota', $id_kota) 
            ->get();

        // mengambil penguji dari tabel user berdasarkan alokasi dosen yang diberikan pada kota yang mengajukan penguji
        $penguji = User::select('user.username', 'user.nama')
        ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
        ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
        ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'penguji')
        ->where('pengajuan_pembimbing.id_kota', $id_kota) // Ganti dengan id_kota yang diinginkan
        ->get();

        // dd($pembimbing);

        if (is_null($id_kota)) {
            $verifikasi = (object) ['kota' => null];
        } elseif ($pembimbing->isEmpty() || $penguji->isEmpty()) {
            $verifikasi = (object) ['kota' => $id_kota, 'dosen' => null];
        } else {
            // Ambil semua data verifikasi berdasarkan id_kota
            $verifikasi = VerifikasiBerkasPengajuan::with('kota')
                            ->where('id_kota', $id_kota)
                            ->get();

            if (!$verifikasi) {
                $verifikasi = (object) ['kota' => $id_kota, 'dosen' => $id_kota, 'pengajuan' => null];
            } else {
                $verifikasi->kota = $id_kota;
                $verifikasi->dosen = $id_kota;
                $verifikasi->pengajuan = true;
                $verifikasi->pengajuanSeminar3 = $this->checkPengajuanSeminar3($id_kota);
                $verifikasi->pengajuanSidang = $this->checkPengajuanSidang($id_kota);
            }
            // dd($verifikasi);
        }

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.DaftarPengajuan', compact('verifikasi'));
    }

    public function indexPengajuanSeminar3(Request $request): View
    {
        $minDate = null;
        $maxDate = null;

        // Find timeline where nama_kegiatan contains '3'
        $timelineEntry = Timeline::where('nama_kegiatan', 'like', '%3%')
                            ->latest('tanggal_mulai')
                            ->latest('tanggal_selesai')
                            ->first();
        if ($timelineEntry) {
            $minDate = Carbon::parse($timelineEntry->tanggal_mulai)->translatedFormat('d F Y');
            $maxDate = Carbon::parse($timelineEntry->tanggal_selesai)->translatedFormat('d F Y');
        }

        // mengambil id_kota dari mahasiswa by username
        $id_kota = Mahasiswa::find(Auth::User()->username)->id_kota;

        // mengambil data kota berdasarkan id_kota
        $dataKota = Kota::find($id_kota);
        
        $mahasiswa = Mahasiswa::select('mahasiswa.nim', 'user.nama', 'user.email', 'mahasiswa.kelas', 'mahasiswa.tahun_masuk')
            ->join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $id_kota) // Ganti dengan id_kota yang diinginkan
            ->get();

        // mengambil pembimbing dari tabel user berdasarkan alokasi dosen yang diberikan pada kota yang mengajukan pembimbing
        $pembimbing = User::select('user.username', 'user.nama')
            ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
            ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
            ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->where('pengajuan_pembimbing.id_kota', $id_kota) 
            ->get();

        // mengambil penguji dari tabel user berdasarkan alokasi dosen yang diberikan pada kota yang mengajukan penguji
        $penguji = User::select('user.username', 'user.nama')
        ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
        ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
        ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'penguji')
        ->where('pengajuan_pembimbing.id_kota', $id_kota) // Ganti dengan id_kota yang diinginkan
        ->get();

        // mengambil data ruangan dari API Topik 3
        try {
            $response = Http::timeout(10)->withoutVerifying()->get('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/rooms/names');
        
            if ($response->successful()) {
                $ruangan = collect($response->json())->map(function ($item) {
                    return [
                        'id_ruangan' => $item['id_ruangan'],
                        'nama_ruangan' => $item['nama_ruangan']
                    ];
                });
            } else {
                // Jika response tidak sukses, tetap gunakan data kosong
                $ruangan = collect([]);
            }
        } catch (\Exception $e) {
            // Jika request gagal karena timeout, koneksi gagal, dll
            // Gunakan data kosong supaya program tetap jalan
            $ruangan = collect([]);
            // Optional: log error untuk debugging
            \Log::error('Gagal ambil data ruangan dari API: ' . $e->getMessage());
        }

        // Filter hanya ruangan yang tersedia
        $ruanganTersedia = $ruangan;

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.PengajuanSeminar3', [
            'dataKota' => $dataKota,
            'mahasiswa' => $mahasiswa,
            'pembimbing' => $pembimbing,
            'penguji' => $penguji,
            'ruanganTersedia' => $ruanganTersedia,
            'tanggal_mulai' => $minDate,
            'tanggal_selesai' => $maxDate
        ]);
    }

    public function indexPengajuanSidang(Request $request): View
    {
        $minDate = null;
        $maxDate = null;

        // Find timeline where nama_kegiatan contains 'sidang'
        $timelineEntry = Timeline::where('nama_kegiatan', 'like', '%sidang%')
                                 ->latest('tanggal_mulai')
                                 ->latest('tanggal_selesai')
                                 ->first();
        if ($timelineEntry) {
            $minDate = Carbon::parse($timelineEntry->tanggal_mulai)->translatedFormat('d F Y');
            $maxDate = Carbon::parse($timelineEntry->tanggal_selesai)->translatedFormat('d F Y');
        }

        // dd($minDate, $maxDate);

        // mengambil id_kota dari mahasiswa by username
        $id_kota = Mahasiswa::find(Auth::User()->username)->id_kota;

        // mengambil data kota by id_kota
        $dataKota = Kota::find($id_kota);
        
        // mengambil data seluruh mahasiswa pada satu kota berdasarkan id_kota
        $mahasiswa = Mahasiswa::select('mahasiswa.nim', 'user.nama', 'user.email', 'mahasiswa.kelas', 'mahasiswa.tahun_masuk')
            ->join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $id_kota)
            ->get();

        // mengambil pembimbing dari tabel user berdasarkan alokasi dosen yang diberikan pada kota yang mengajukan pembimbing
        $pembimbing = User::select('user.username', 'user.nama')
            ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
            ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
            ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
            ->where('pengajuan_pembimbing.id_kota', $id_kota) 
            ->get();

        // mengambil penguji dari tabel user berdasarkan alokasi dosen yang diberikan pada kota yang mengajukan penguji
        $penguji = User::select('user.username', 'user.nama')
        ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
        ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
        ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
        ->where('alokasi_dosen.status_alokasi', 'fix')
        ->where('alokasi_dosen.tipe_alokasi', 'penguji')
        ->where('pengajuan_pembimbing.id_kota', $id_kota)
        ->get();

        // mengambil data ruangan dari API Topik 3
        try {
            $response = Http::timeout(10)->withoutVerifying()->get('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/rooms/names');
        
            if ($response->successful()) {
                $ruangan = collect($response->json())->map(function ($item) {
                    return [
                        'id_ruangan' => $item['id_ruangan'],
                        'nama_ruangan' => $item['nama_ruangan']
                    ];
                });
            } else {
                $ruangan = collect([]);
            }
        } catch (\Exception $e) {
            $ruangan = collect([]);
            \Log::error('Gagal ambil data ruangan dari API: ' . $e->getMessage());
        }

        // Filter hanya ruangan yang tersedia
        $ruanganTersedia = $ruangan;

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.PengajuanSidang', [
            'dataKota' => $dataKota,
            'mahasiswa' => $mahasiswa,
            'pembimbing' => $pembimbing,
            'penguji' => $penguji, 
            'ruanganTersedia' => $ruanganTersedia,
            'tanggal_mulai' => $minDate,
            'tanggal_selesai' => $maxDate
        ]);
    }

    public function tambahPengajuanPenjadwalan(Request $request, $id_kota)
    {
        $minDate = null;
        $maxDate = null;
        $agendaRequest = $request->input('agenda');

        if ($agendaRequest === 'seminar_3') {
            // Find timeline where nama_kegiatan contains '3'
            $timelineEntry = Timeline::where('nama_kegiatan', 'like', '%3%')
                                     ->latest('tanggal_selesai')
                                     ->first();
            if ($timelineEntry) {
                $minDate = $timelineEntry->tanggal_mulai;
                $maxDate = $timelineEntry->tanggal_selesai;
            }
        } elseif ($agendaRequest === 'sidang') {
            // Find timeline where nama_kegiatan contains 'sidang'
            $timelineEntry = Timeline::where('nama_kegiatan', 'like', '%sidang%')
                                     ->latest('tanggal_selesai')
                                     ->first();
            if ($timelineEntry) {
                $minDate = $timelineEntry->tanggal_mulai;
                $maxDate = $timelineEntry->tanggal_selesai;
            }
        }

        // Validasi input
        try {
            $request->validate([
                'tanggal_pengajuan' => ['required', 'date', 'after_or_equal:' . $minDate, 'before_or_equal:' . $maxDate],
                'sesi_pengajuan' => 'required|integer',
                'ruangan_pengajuan' => 'required|string|max:255',
                'agenda' => 'required|in:seminar_1,seminar_2,seminar_3,sidang',
            ], [
                'tanggal_pengajuan.after_or_equal' => 'Tanggal pengajuan tidak valid. Pastikan tanggal yang Anda pilih sesuai dengan timeline yang ditentukan.',
                'tanggal_pengajuan.before_or_equal' => 'Tanggal pengajuan tidak valid. Pastikan tanggal yang Anda pilih sesuai dengan timeline yang ditentukan.'
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('error', $e->validator->errors()->first())
                ->withInput();
        }

        // Cek apakah kota dengan id_kota ada
        $kota = Kota::find($id_kota);
        if (!$kota) {
            return redirect()->back()->with('error', 'Kota tidak ditemukan.');
        }

        // Tentukan waktu `start` dan `end` berdasarkan sesi
        $tanggal = Carbon::parse($request->input('tanggal_pengajuan'));

        // Decode JSON dari ruangan_pengajuan
        $ruangan = json_decode($request->input('ruangan_pengajuan'));

        if (!$ruangan) {
            return redirect()->back()->with('error', 'Data ruangan tidak valid.');
        }

        $id_ruangan = $ruangan->id;
        $nama_ruangan = $ruangan->nama;
        
        $sesi = $request->input('sesi_pengajuan');

        $start = $tanggal->copy()->setTimeFromTimeString($this->jadwal_waktu[$sesi]['start'])->format('Y-m-d H:i:s');
        $end = $tanggal->copy()->setTimeFromTimeString($this->jadwal_waktu[$sesi]['end'])->format('Y-m-d H:i:s');

        // mengambil data jadwal yang sudah dibuat dari API Topik 3
        $response = Http::get('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/schedules');

        if ($response->successful()) {
            $schedules = collect($response->json())->map(function ($item) {

                // Ambil jam dari start
                $startTime = isset($item['start']) ? Carbon::parse($item['start'])->format('H:i') : null;

                // Cari sesi berdasarkan start time
                $sesi = null;
                foreach ($this->jadwal_waktu as $key => $waktu) {
                    if ($startTime === $waktu['start']) {
                        $sesi = $key;
                        break;
                    }
                }

                return [
                    'id_penjadwalan' => $item['id_penjadwalan'] ?? null,
                    'agenda' => $item['agenda'] ?? 'Tidak ada agenda',
                    'sesi' => $sesi,
                    'start' => $item['start'] ?? null,
                    'end' => $item['end'] ?? null,
                    'tanggal' => $item['tanggal'] ?? null,
                    'id_ruangan' => $item['id_ruangan'],
                    'nama_ruangan' => $item['nama_ruangan'],
                    'id_kota' => $item['id_kota'] ?? null,
                ];
            });
        } else {
            $schedules = collect([]); // Handle jika API gagal
        }
        // Cek apakah jadwal bentrok
        $conflict = $schedules->contains(function ($schedule) use ($sesi, $id_ruangan, $tanggal) {
            return $schedule['sesi'] == $sesi && $schedule['id_ruangan'] == $id_ruangan && Carbon::parse($schedule['start'])->toDateString() == $tanggal->format('Y-m-d');
        });

        if ($conflict) {
            return redirect()->back()->with('error', 'Jadwal sudah terisi pada sesi dan ruangan yang dipilih. Silakan pilih sesi atau ruangan lain.');
        }

        // Buat entry baru di tabel penjadwalan
        $penjadwalan = Penjadwalan::create([
            'sesi' => $sesi,
            'agenda' => $request->input('agenda'),
            'id_ruangan' => $id_ruangan,
            'nama_ruangan' => $nama_ruangan,
            'tanggal' => $request->input('tanggal_pengajuan'),
            'id_kota' => $id_kota,   
            'start' => $start,
            'end' => $end,
        ]);

        // Tambahkan pengajuan jadwal kota yang baru 
        // Sebelum disetujui sampai koordinator akan disimpan pada tabel penjadwalan
        // Untuk selanjutnya di post pada topik 3
        PengajuanJadwalKota::create([
            'id_kota' => $id_kota,
            'id_penjadwalan' => $penjadwalan->id_penjadwalan,
            'status_mahasiswa' => true,
            'status_dosen_pembimbing_1' => null,
            'status_dosen_pembimbing_2' => null,
            'status_dosen_penguji_1' => null,
            'status_dosen_penguji_2' => null,
            'status_koordinator_ta' => null,
        ]);


        try {
            
        // Get dosen information
        $pembimbingPenguji = User::select('user.username', 'user.nama', 'alokasi_dosen.tipe_alokasi')
            ->join('alokasi_dosen', 'user.username', '=', 'alokasi_dosen.nip')
            ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
            ->where('pengajuan_pembimbing.status_pengajuan', 'diterima')
            ->where('alokasi_dosen.status_alokasi', 'fix')
            ->where('pengajuan_pembimbing.id_kota', $id_kota)
            ->get();
            // Get koordinator TA
            $koordinatorTA = User::where('role_user', 'koordinator')->first();
            // Kirim notifikasi ke pembimbing dan penguji
            foreach ($pembimbingPenguji as $dosen) {
                $tipeDosen = $dosen->tipe_alokasi === 'pembimbing' ? 'Pembimbing' : 'Penguji';
                
                Notifikasi::kirim(
                    '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                    $dosen->username,
                    [
                        'tipe_dosen' => $tipeDosen,
                        'nama_kota' => $kota->nama_kota,
                        'agenda' => $request->agenda,
                        'tanggal' => Carbon::parse($request->tanggal_pengajuan)->format('d-m-Y'),
                        'sesi' => $sesi,
                        'ruangan' => $nama_ruangan
                    ]
                );
            }

            // Kirim notifikasi ke koordinator TA
            if ($koordinatorTA) {
                Notifikasi::kirim(
                    '[Pemberitahuan] Pengajuan Jadwal Seminar/Sidang Baru Oleh Mahasiswa!',
                    $koordinatorTA->username,
                    [
                        'nama_kota' => $kota->nama_kota,
                        'agenda' => $request->agenda,
                        'tanggal' => Carbon::parse($request->tanggal_pengajuan)->format('d-m-Y'),
                        'sesi' => $sesi,
                        'ruangan' => $nama_ruangan
                    ]
                );
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi pengajuan jadwal: ' . $notifEx->getMessage(), [
                'id_kota' => $id_kota,
                'agenda' => $request->agenda
            ]);
        }

        return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil dibuat dan status pengajuan diperbarui.');
    }
}