<?php

namespace App\Modules\Artefak\Controllers;

use App\Models\Artefak;
use App\Modules\Controller;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Storage;

class ArtefakController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        // dd($user);
        $artefaks = Artefak::all();
        foreach ($artefaks as $artefak) {
            $artefak->formatted_tenggat_waktu = Carbon::parse($artefak->tenggat_waktu)->format('l, d F Y H:i');

            // Cek apakah artefak sudah dikumpulkan oleh user
            $kumpul = DB::table('kota_artefak')
                        ->where('id_artefak', $artefak->id_artefak)
                        ->where('id_kota', function ($query) use ($user) {
                            $query->select('id_kota')
                                ->from('kota_user')
                                ->where('username', $user->username)
                                ->first();
                        })
                        ->first();

            // Menyimpan informasi pengumpulan di dalam objek artefak
            $artefak->kumpul = $kumpul;
        }

        $masterArtefaks = DB::table('kategori_artefak')->pluck('jenis_artefak');
        return view('Artefak.views.index', compact('artefaks', 'masterArtefaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_artefak' => 'required',
            'deskripsi' => 'required',
            'kategori_artefak' => 'required',
            'tanggal_tenggat' => 'required|date',
            'waktu_tenggat' => 'required|date_format:H:i',
        ]);

        $existingArtefak = DB::table('artefak')->where('nama_artefak', $request->nama_artefak)->exists();
        if($existingArtefak) {
            session()->flash('error', 'Artefak sudah terdaftar');
            return redirect()->back()->withInput();
        }

        $tanggalTenggat = $request->tanggal_tenggat;
        $waktuTenggat = $request->waktu_tenggat;
        $tenggat_waktu = $tanggalTenggat . ' ' . $waktuTenggat . ':00';

        DB::table('artefak')->insert([
            'nama_artefak' => $request->nama_artefak,
            'deskripsi' => $request->deskripsi,
            'kategori_artefak' => $request->kategori_artefak,
            'tenggat_waktu' => $tenggat_waktu,
        ]);

        // Artefak::create($request->all());

        return redirect()->route('artefak')
                        ->with('success', 'Artefak berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $artefak = Artefak::findOrFail($id);
        if (!$artefak) {
            return redirect()->route('artefak')->withErrors('Data tidak ditemukan.');
        }

        return view('artefak.edit', compact('artefak'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_artefak' => 'required',
            'deskripsi' => 'required',
            'kategori_artefak' => 'required',
            'tenggat_waktu' => 'required',
        ]);

        $artefak = Artefak::findOrFail($id);

        $artefak->update($request->all());

        session()->flash('success', 'Data artefak berhasil dirubah');

        return redirect()->route('artefak');
    }

    public function destroy($id)
    {
        $artefak = Artefak::findOrFail($id);
        Storage::delete('/artefak'. $artefak->id_artefak);
        $artefak->delete();

        session()->flash('success', 'Data artefak berhasil dihapus');

        return redirect()->route('artefak')->with('success', 'Data Artefak berhasil dihapus');
    }

}
