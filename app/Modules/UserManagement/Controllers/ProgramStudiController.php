<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Kaprodi;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProgramStudiController extends Controller
{
    // Menampilkan daftar Prodi
    public function index()
    {
        $programStudi = Prodi::leftJoin('kaprodi as k', 'prodi.id_prodi', '=', 'k.id_prodi')
            ->leftJoin('dosen as d', 'k.nip', '=', 'd.nip')
            ->leftJoin('user as u', 'd.nip', '=', 'u.username')
            ->select(
                'prodi.id_prodi',
                'prodi.nama_prodi',
                DB::raw('MAX(u.nama) as ketua_prodi'), // Menggunakan MAX untuk menangani duplikasi
                'prodi.maksimal_anggota_kota as maksimal_mahasiswa_bimbingan'
            )
            ->groupBy('prodi.id_prodi', 'prodi.nama_prodi', 'prodi.maksimal_anggota_kota')
            ->limit(1000)
            ->get();

        return view('UserManagement.views.programstudi', compact('programStudi'));
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'kode_prodi' => 'required|string|max:10|unique:prodi',
            'maksimal_mahasiswa_bimbingan' => 'required|integer',
        ]);

        Prodi::create($request->all());

        return redirect()->route('program-studi.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('UserManagement.views.prodi_edit', compact('prodi'));
    }

    // Memperbarui data Prodi
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'kode_prodi' => 'required|string|max:10|unique:prodi,kode_prodi,' . $id . ',id_prodi',
            'maksimal_mahasiswa_bimbingan' => 'required|integer',
        ]);

        $prodi = Prodi::findOrFail($id);
        $prodi->update($request->all());

        return redirect()->route('program-studi.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    // Menghapus Prodi
    public function destroy($id)
    {
        Prodi::findOrFail($id)->delete();
        return redirect()->route('program-studi.index')->with('success', 'Program Studi berhasil dihapus.');
    }
}