<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Carbon\Carbon;

class PerekrutanAnggotaKoTAController extends Controller
{
    public function index()
    {
        $mahasiswaAnggota1 = Mahasiswa::join('user', 'mahasiswa.nim', '=', 'user.username')
            ->where('mahasiswa.status_ta', 'mahasiswa_ta')
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
                ->where('mahasiswa.status_ta', 'mahasiswa_ta')
                ->where('mahasiswa.nim', '!=', $mahasiswaAnggota1->nim)
                ->whereNull('mahasiswa.id_kota')
                ->select('mahasiswa.*', 'user.nama')
                ->get();
        } else {
            $mahasiswa = [];
        }

        return view('UserManagement.views.perekrutan-anggota-kota', compact('mahasiswa', 'mahasiswaAnggota1', 'maksimalAnggota'));
    }

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
        $lastKoTA = Kota::where('tahun_kota', $currentYear)
            ->orderBy('id_kota', 'desc')
            ->first();

        // Generate nama KoTA
        $newKoTANumber = $lastKoTA ? intval(substr($lastKoTA->nama_kota, 4)) + 1 : 101;
        $namaKoTA = 'KoTA ' . $newKoTANumber;

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
        $anggota1->save();

        // Anggota 2
        if ($request->input('anggota2')) {
            $anggota2 = Mahasiswa::where('nim', $request->input('anggota2'))->first();
            $anggota2->id_kota = $koTA->id_kota;
            $anggota2->save();
        }

        if ($request->input('anggota3')) {
            $anggota3 = Mahasiswa::where('nim', $request->input('anggota3'))->first();
            $anggota3->id_kota = $koTA->id_kota;
            $anggota3->save();
        }

        // Mendapatkan Nama Lengkap untuk setiap anggota
        $anggota1Data = User::where('username', $request->input('anggota1'))->first();
        $anggota2Data = $request->input('anggota2') ? User::where('username', $request->input('anggota2'))->first() : null;
        $anggota3Data = $request->input('anggota3') ? User::where('username', $request->input('anggota3'))->first() : null;

        session([
            'anggota1' => $anggota1Data->nama . ' - ' . $anggota1Data->username,
            'anggota2' => $anggota2Data ? $anggota2Data->nama . ' - ' . $anggota2Data->username : 'Tidak Dipilih',
            'anggota3' => $anggota3Data ? $anggota3Data->nama . ' - ' . $anggota3Data->username : 'Tidak Dipilih',
            'nama_kota' => $namaKoTA,
            'tahun_kota' => $currentYear,
            'id_kota' => $koTA->id_kota
        ]);

        return redirect()->route('konfirmasi-kota');
    }
}
