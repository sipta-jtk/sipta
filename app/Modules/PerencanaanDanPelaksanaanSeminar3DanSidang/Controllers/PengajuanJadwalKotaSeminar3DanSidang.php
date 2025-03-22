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

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PengajuanJadwalKotaSeminar3DanSidang extends Controller
{
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

        dd($penguji);

        // dd($id_kota);
        if (is_null($id_kota)) {
            $verifikasi = (object) ['kota' => $id_kota];
        } elseif ($pembimbing->isEmpty() || $penguji->isEmpty()) {
            $verifikasi = (object) ['kota' => $id_kota, 'dosen' => null];
        } else {
            // Ambil semua data verifikasi berdasarkan id_kota
            $verifikasi = VerifikasiBerkasPengajuan::with('kota')
                            ->where('id_kota', $id_kota)
                            ->get();

            // dd($verifikasi);

            if (!$verifikasi) {
                $verifikasi = (object) ['kota' => $id_kota, 'dosen' => $id_kota, 'pengajuan' => null];
            } else {
                $verifikasi->kota = $id_kota;
                $verifikasi->dosen = $id_kota;
                $verifikasi->pengajuan = true; 
            }
        }

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.DaftarPengajuan', compact('verifikasi'));
    }

    public function indexPengajuanSeminar3(Request $request): View
    {
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
                    'id_ruangan' => 5,
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
            'mahasiswa' => $mahasiswa,
            'pembimbing' => $pembimbing,
            'penguji' => $penguji,
            'ruanganTersedia' => $ruanganTersedia
        ]);
    }

    public function indexPengajuanSidang(Request $request): View
    {
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
                    'id_ruangan' => 5,
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
            'mahasiswa' => $mahasiswa,
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
        $tanggal = Carbon::parse($request->input('tanggal_pengajuan'));
        $jadwal_waktu = [
            1 => ['start' => '07:00', 'end' => '09:00'],
            2 => ['start' => '09:00', 'end' => '11:00'],
            3 => ['start' => '13:00', 'end' => '15:00'],
            4 => ['start' => '15:00', 'end' => '17:00'],
        ];

        $sesi = $request->input('sesi_pengajuan');
        $id_ruangan = $request->input('ruangan_pengajuan');

        $start = $tanggal->copy()->setTimeFromTimeString($jadwal_waktu[$sesi]['start'])->format('Y-m-d H:i:s');
        $end = $tanggal->copy()->setTimeFromTimeString($jadwal_waktu[$sesi]['end'])->format('Y-m-d H:i:s');


        // Dummy API Response http://127.0.0.1:8080/api/v1/schedules
        $useStub = $request->query('stub', true);
        if ($useStub) {
            $schedules = collect([
                [                  
                    "id"=>1,
                    "title"=>"seminar_3",
                    "sesi"=>"2",
                    "start"=>"2025-03-22T09:00:00+00:00",
                    "end"=>"2025-03-22T11:00:00+00:00",
                    "id_ruangan"=>5,
                    "resourceId"=>5
                ],
                [                    
                    "id"=>2,
                    "title"=>"sidang",
                    "sesi"=>"4",
                    "start"=>"2025-03-20T15:00:00+00:00",
                    "end"=>"2025-03-20T17:00:00+00:00",
                    "id_ruangan"=>2,
                    "resourceId"=>2
                ],
                [
                    "id"=>3,
                    "title"=>"sidang",
                    "sesi"=>"3",
                    "start"=>"2025-03-20T13:00:00+00:00",
                    "end"=>"2025-03-20T15:00:00+00:00",
                    "id_ruangan"=>2,
                    "resourceId"=>2
                ],
                [ 
                    "id"=>4,
                    "title"=>"sidang",
                    "sesi"=>"4",
                    "start"=>"2025-03-19T15:00:00+00:00",
                    "end"=>"2025-03-19T17:00:00+00:00",
                    "id_ruangan"=>2,
                    "resourceId"=>2                    
                ],
                [
                    "id"=>5,
                    "title"=>"seminar_1",
                    "sesi"=>"1",
                    "start"=>"2025-03-17T07:00:00+00:00",
                    "end"=>"2025-03-17T09:00:00+00:00",
                    "id_ruangan"=>2,
                    "resourceId"=>2
                ],
                [
                    "id"=>6,
                    "title"=>"sidang",
                    "sesi"=>"1",
                    "start"=>"2025-03-22T07:00:00+00:00",
                    "end"=>"2025-03-22T09:00:00+00:00",
                    "id_ruangan"=>1,
                    "resourceId"=>1
                ],
                [
                    "id"=>8,
                    "title"=>"sidang",
                    "sesi"=>"3",
                    "start"=>"2025-03-17T13:00:00+00:00",
                    "end"=>"2025-03-17T15:00:00+00:00",
                    "id_ruangan"=>5,
                    "resourceId"=>5
                ]                
            ]);
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
            'id_ruangan' => $request->input('ruangan_pengajuan'),
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

        return redirect()->route('pengajuan')->with('success', 'Penjadwalan berhasil dibuat dan status mahasiswa diperbarui.');
    }
}