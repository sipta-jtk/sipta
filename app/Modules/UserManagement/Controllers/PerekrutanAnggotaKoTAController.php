<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 *  class PerekrutanAnggotaKoTAController
 */
class PerekrutanAnggotaKoTAController extends Controller
{    
    /**
     * index
     * 
     * Menampilkan halaman perekrutan anggota KoTA
     *
     * @return void
     */
    public function index()
    {
        $mahasiswaAnggota1 = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.nim', '=', auth()->user()->username)
            ->select('mahasiswa.*', 'user.nama')
            ->first();

        // Mendapatkan maksimal anggota dari prodi mahasiswa
        $maksimalAnggota = 3; // Default jika tidak ada data prodi

        if ($mahasiswaAnggota1) 
        {
            // Ambil nilai maksimal anggota dari prodi mahasiswa
            $prodi = Prodi::find($mahasiswaAnggota1->id_prodi);
            if ($prodi) 
            {
                $maksimalAnggota = $prodi->maksimal_anggota_kota;
            }

            $mahasiswa = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
                ->where('mahasiswa.nim', '!=', $mahasiswaAnggota1->nim)
                ->where('mahasiswa.id_prodi', '=', $mahasiswaAnggota1->id_prodi)
                ->whereNull('mahasiswa.id_kota')
                ->select('mahasiswa.*', 'user.nama')
                ->get();
        } else {
            $mahasiswa = [];
        }

        return view('UserManagement.views.perekrutan-anggota-kota', compact('mahasiswa', 'mahasiswaAnggota1', 'maksimalAnggota'));
    }

        
    /**
     * submit
     * 
     * Proses rekrut anggota KoTA dengan menambahkan anggota ke dalam KoTA
     *
     * @param  mixed $request
     * @return void
     */
    public function submit(Request $request)
    {
        $request->validate([
            'anggota2' => 'nullable|different:anggota1|required_with:anggota3',
            'anggota3' => 'nullable|different:anggota1,anggota2',
        ]);

        // Cek apakah ada anggota yang sudah memiliki kelompok TA
        $existingMembers = Mahasiswa::whereIn('nim', [
            $request->input('anggota1'),
            $request->input('anggota2'),
            $request->input('anggota3')
        ])->whereNotNull('id_kota')->get();

        if ($existingMembers->isNotEmpty()) {
            return back()->with('error', 'Salah satu anggota sudah memiliki kelompok TA.');
        }

        $currentYear = Carbon::now()->year;

        // Cari nomor KoTA terakhir dengan tahun yang sama
        // $lastKoTA = Kota::where('tahun_kota', $currentYear)
        //     ->orderBy('id_kota', 'desc')
        //     ->first();

        // // Generate nama KoTA
        // $newKoTANumber = $lastKoTA ? intval(substr($lastKoTA->nama_kota, 4)) + 1 : 101;
        // $namaKoTA = 'KoTA ' . $newKoTANumber;

        // Ambil data mahasiswa pertama untuk mendapatkan prodi dan kelas
        $anggota1 = Mahasiswa::where('nim', $request->input('anggota1'))->first();
        $prodi = Prodi::find($anggota1->id_prodi);

        // Generate kode kelas berdasarkan prodi dan kelas
        $kodeKelas = $this->generateKodeKelas($anggota1->id_prodi, $anggota1->kelas);

        // Cari nomor urut KoTA terakhir dengan tahun yang sama
        $lastKoTA = Kota::where('tahun_kota', $currentYear)
            ->where('nama_kota', 'LIKE', 'Kota ' . $kodeKelas . '%')
            ->get();
        
        // Extract nomor urut dari nama KoTA yang ada dan cari yang terbesar
        $maxNomor = 0;
        foreach ($lastKoTA as $kota) {
            // Extract 2 digit terakhir dari nama KoTA
            if (preg_match('/Kota ' . $kodeKelas . '(\d{2})$/', $kota->nama_kota, $matches)) {
                $nomor = intval($matches[1]);
                if ($nomor > $maxNomor) {
                    $maxNomor = $nomor;
                }
            }
        }

        // Generate nomor urut berikutnya
        $nomorUrut = $maxNomor + 1;

        // Format nama KoTA: KoTA + kode kelas + nomor urut (2 digit)
        $namaKoTA = 'Kota ' . $kodeKelas . str_pad($nomorUrut, 2, '0', STR_PAD_LEFT);

        // Buat Kelompok TA baru
        $koTA = Kota::create([
            'judul_ta' => null,
            'id_bidang' => null,
            'nama_kota' => $namaKoTA,
            'tahun_kota' => $currentYear,
            'status_kota' => 'pra_kota',
        ]);

        // Assign id_kota ke Mahasiswa
        // Anggota 1
        $anggota1 = Mahasiswa::where('nim', $request->input('anggota1'))->first();
        $anggota1->id_kota = $koTA->id_kota;
        $anggota1->status_ta = 'mahasiswa_ta';
        $anggota1->save();

        // Anggota 2
        if ($request->input('anggota2')) {
            $anggota2 = Mahasiswa::where('nim', $request->input('anggota2'))->first();
            $anggota2->id_kota = $koTA->id_kota;
            $anggota2->status_ta = 'mahasiswa_ta';
            $anggota2->save();
        }

        if ($request->input('anggota3')) {
            $anggota3 = Mahasiswa::where('nim', $request->input('anggota3'))->first();
            $anggota3->id_kota = $koTA->id_kota;
            $anggota3->status_ta = 'mahasiswa_ta';
            $anggota3->save();
        }

        // Mendapatkan Nama Lengkap untuk setiap anggota
        $anggota1Data = User::where('username', $request->input('anggota1'))->first();
        $anggota2Data = $request->input('anggota2') ? User::where('username', $request->input('anggota2'))->first() : null;
        $anggota3Data = $request->input('anggota3') ? User::where('username', $request->input('anggota3'))->first() : null;

        // Ambil informasi prodi dan maksimal anggota
        $prodi = Prodi::find($anggota1->id_prodi);
        $maksimalAnggota = $prodi ? $prodi->maksimal_anggota_kota : 3;

        session([
            'anggota1' => $anggota1Data->nama . ' - ' . $anggota1Data->username,
            'anggota2' => $anggota2Data ? $anggota2Data->nama . ' - ' . $anggota2Data->username : 'Tidak Dipilih',
            'anggota3' => $anggota3Data ? $anggota3Data->nama . ' - ' . $anggota3Data->username : 'Tidak Dipilih',
            'nama_kota' => $namaKoTA,
            'tahun_kota' => $currentYear,
            'id_kota' => $koTA->id_kota,
            'maksimal_anggota' => $maksimalAnggota
        ]);

        return redirect()->route('konfirmasi-kota');
    }

    /**
     * Generate kode kelas berdasarkan prodi dan kelas
     * 
     * @param int $idProdi
     * @param string $kelas
     * @return int
     */
    private function generateKodeKelas($idProdi, $kelas)
    {
        // Ambil semua kombinasi prodi dan kelas yang unik, diurutkan berdasarkan id_prodi dan kelas
        $kombinasiProdiKelas = Mahasiswa::select('id_prodi', 'kelas')
            ->distinct()
            ->orderBy('id_prodi', 'asc')
            ->orderBy('kelas', 'asc')
            ->get();

        // Cari posisi kombinasi prodi dan kelas yang sedang diproses
        $kodeKelas = 1;
        foreach ($kombinasiProdiKelas as $kombinasi) {
            if ($kombinasi->id_prodi == $idProdi && $kombinasi->kelas == $kelas) {
                return $kodeKelas;
            }
            $kodeKelas++;
        }
        
        // Fallback jika tidak ditemukan
        return 1;
    }

    /**
     * Menampilkan form untuk menambah anggota ke KoTA yang sudah ada
     *
     * @param int $id ID KoTA
     * @return \Illuminate\View\View
     */
    public function showTambahAnggotaForm($id)
    {
        // Ambil data KoTA berdasarkan ID
        $kota = Kota::find($id);
        if (!$kota) {
            return redirect()->back()->with('error', 'Kelompok TA tidak ditemukan.');
        }

        // Pastikan user adalah anggota KoTA ini
        $user = Auth::user();
        $mahasiswaAnggota1 = Mahasiswa::where('nim', $user->username)->first();

        if (!$mahasiswaAnggota1 || $mahasiswaAnggota1->id_kota != $id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menambah anggota ke KoTA ini.');
        }

        // Ambil data anggota yang sudah ada
        $anggotaExisting = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $id)
            ->select('mahasiswa.*', 'user.nama')
            ->get();

        // Dapatkan mahasiswa dari prodi yang sama yang belum masuk KoTA
        $mahasiswa = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_prodi', $mahasiswaAnggota1->id_prodi)
            ->where('mahasiswa.nim', '!=', $mahasiswaAnggota1->nim)
            ->whereNull('mahasiswa.id_kota')
            ->select('mahasiswa.*', 'user.nama')
            ->get();
        
        // Ambil nilai maksimal anggota dari prodi mahasiswa
        $prodi = Prodi::find($mahasiswaAnggota1->id_prodi);
        $maksimalAnggota = $prodi ? $prodi->maksimal_anggota_kota : 3;

        // Hitung slot tersedia
        $slotTersedia = $maksimalAnggota - count($anggotaExisting);

        return view('UserManagement.views.tambah-anggota-kota', compact(
            'kota', 
            'mahasiswa', 
            'mahasiswaAnggota1', 
            'anggotaExisting', 
            'maksimalAnggota',
            'slotTersedia'
        ));
    }

    /**
     * Proses tambah anggota ke KoTA yang sudah ada
     *
     * @param Request $request
     * @param int $id ID KoTA
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tambahAnggota(Request $request, $id)
    {
        // Ambil data KoTA
        $kota = Kota::find($id);
        if (!$kota) {
            return redirect()->back()->with('error', 'Kelompok TA tidak ditemukan.');
        }

        // Ambil prodi untuk mendapatkan maksimal anggota
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
        $prodi = Prodi::find($mahasiswa->id_prodi);
        $maksimalAnggota = $prodi ? $prodi->maksimal_anggota_kota : 3;

        // Ambil data anggota yang sudah ada
        $anggotaExisting = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.id_kota', $id)
            ->select('mahasiswa.*', 'user.nama')
            ->get();

        // Validasi input
        $rules = [];
        // Buat aturan dinamis berdasarkan jumlah anggota yang ditambahkan
        for ($i = 2; $i <= $maksimalAnggota; $i++) {
            if ($request->has('anggota'.$i)) {
                $rules['anggota'.$i] = 'nullable|different:anggota1';
                
                // Tambahkan aturan different untuk anggota sebelumnya
                for ($j = 2; $j < $i; $j++) {
                    if ($request->has('anggota'.$j)) {
                        $rules['anggota'.$i] .= ',anggota'.$j;
                    }
                }
            }
        }

        $request->validate($rules);

        // Ambil data anggota yang sudah ada
        $existingMembers = Mahasiswa::where('id_kota', $id)->count();

        // Hitung berapa anggota yang akan ditambahkan
        $newMembersCount = 0;
        $startIndex = count($anggotaExisting) + 1;
        for ($i = $startIndex; $i <= $maksimalAnggota; $i++) {
            if ($request->filled('anggota'.$i)) {
                $newMembersCount++;
            }
        }

        // Cek jika melebihi maksimal
        if ($existingMembers + $newMembersCount > $maksimalAnggota) {
            return back()->with('error', 'Jumlah anggota melebihi batas maksimal.');
        }

        // Proses tambah anggota
        $startIndex = count($anggotaExisting) + 1;
        for ($i = $startIndex; $i <= $maksimalAnggota; $i++) {
            if ($request->filled('anggota'.$i)) {
                $anggota = Mahasiswa::where('nim', $request->input('anggota'.$i))->first();

                if ($anggota && !$anggota->id_kota) {
                    $anggota->id_kota = $id;
                    $anggota->status_ta = 'mahasiswa_ta';
                    $anggota->save();
                }
            }
        }

        return redirect()->route('kota.saya')->with('success', 'Anggota berhasil ditambahkan ke kelompok.');
    }
}
