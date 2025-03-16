<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Kaprodi;
use App\Models\Dosen;

class ProgramStudiController extends Controller
{
    // Menampilkan daftar Prodi
    public function index()
    {
        $programStudi = Prodi::leftJoin('kaprodi', 'prodi.id_prodi', '=', 'kaprodi.id_prodi')
            ->leftJoin('dosen', 'kaprodi.nip', '=', 'dosen.nip')
            ->select('prodi.*', 'dosen.nama as ketua_prodi')
            ->get();

        return view('prodi.index', compact('programStudi'));
    }

    // Menampilkan form tambah Prodi
    public function create()
    {
        return view('prodi.create');
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
        return view('prodi.edit', compact('prodi'));
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
