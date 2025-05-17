<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Kaprodi;
use Illuminate\Support\Facades\DB;

class ProgramStudiController extends Controller
{

    public function index()
    {
    $programStudi = Prodi::leftJoin('kaprodi as k', 'prodi.id_prodi', '=', 'k.id_prodi')
        ->leftJoin('dosen as d', 'k.nip', '=', 'd.nip')
        ->leftJoin('user as u', 'd.nip', '=', 'u.username')
        ->select(
            'prodi.id_prodi',
            'prodi.nama_prodi',
            DB::raw('COALESCE(MAX(u.nama), "-") as ketua_prodi'),
            'prodi.maksimal_anggota_kota',
            'prodi.maksimal_mahasiswa_bimbingan',
            'k.nip as kaprodi_nip'
        )
        ->groupBy('prodi.id_prodi', 'prodi.nama_prodi', 'prodi.maksimal_anggota_kota', 'prodi.maksimal_mahasiswa_bimbingan', 'k.nip')
        ->limit(50)
        ->get();

        $dosen = Dosen::leftJoin('user as u', 'dosen.nip', '=', 'u.username')
        ->select('dosen.nip', 'u.nama as nama_dosen')
        ->where('status_dosen', 'aktif')
        ->where('role_dosen', '!=', 'kajur')
        ->whereNotExists(function ($query) {
             $query->select(DB::raw(1))
                   ->from('kaprodi')
                   ->whereColumn('kaprodi.nip', 'dosen.nip');
        })
        ->get();

    return view('UserManagement.views.programStudi', compact('programStudi', 'dosen'));
    }


        public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'id_kaprodi' => 'required|exists:dosen,nip',
            'maksimal_anggota_kota' => 'required|integer|min:1',
            'maksimal_mahasiswa_bimbingan' => 'required|integer|min:1',
        ]);

        // Simpan data ke tabel Prodi
        $prodi = Prodi::create([
            'nama_prodi' => $request->nama_prodi,
            'maksimal_anggota_kota' => $request->maksimal_anggota_kota,
            'maksimal_mahasiswa_bimbingan' => $request->maksimal_mahasiswa_bimbingan,
        ]);

        // Simpan data ke tabel Kaprodi
        Kaprodi::create([
            'nip' => $request->id_kaprodi,
            'id_prodi' => $prodi->id_prodi, // Ambil ID Prodi yang baru dibuat
        ]);

        return redirect()->route('program-studi.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    // Menghapus Prodi dan Kaprodi yang terkait
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        
        // Hapus data kaprodi yang terkait dengan prodi ini
        $prodi->kaprodi()->delete();

        // Hapus prodi
        $prodi->delete();

        return redirect()->route('program-studi.index')->with('success', 'Program Studi dan Kaprodi terkait berhasil dihapus.');
    }
    

    public function update(Request $request, $id)
    {   
    $request->validate([
        'nama_prodi' => 'required|string|max:255',
        'id_kaprodi' => 'required|exists:dosen,nip',
        'maksimal_anggota_kota' => 'required|integer|min:1',
        'maksimal_mahasiswa_bimbingan' => 'required|integer|min:1',
    ]);

    // Update data di tabel Prodi
    $prodi = Prodi::findOrFail($id);
    $prodi->update([
        'nama_prodi' => $request->nama_prodi,
        'maksimal_anggota_kota' => $request->maksimal_anggota_kota,
        'maksimal_mahasiswa_bimbingan' => $request->maksimal_mahasiswa_bimbingan,
    ]);

    // Hapus data kaprodi terkait dengan id_prodi tersebut
    Kaprodi::where('id_prodi', $id)->delete();

    // Buat baris baru di tabel Kaprodi dengan data yang baru
    Kaprodi::create([
        'id_prodi' => $id,
        'nip' => $request->id_kaprodi,
    ]);

    return redirect()->route('program-studi.index')->with('success', 'Program Studi berhasil diperbarui.');
    }


}