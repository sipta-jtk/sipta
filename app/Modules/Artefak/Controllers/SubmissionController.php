<?php

namespace App\Modules\Artefak\Controllers;

use App\Models\Artefak;
use App\Models\KotaArtefak;
use App\Modules\Controller;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, $artefak_id)
    {
        $request->validate([
            'file_pengumpulan' => 'required|mimes:pdf|max:2048',
        ], [
            'file_pengumpulan.required' => 'File pengumpulan harus diunggah.',
            'file_pengumpulan.mimes' => 'File pengumpulan harus dalam format pdf.',
            'file_pengumpulan.max' => 'Ukuran file pengumpulan maksimal adalah 2MB.',
        ]);
    
        $file = $request->file('file_pengumpulan');
        $originalFileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('submissions', $originalFileName, 'public');
        // Mendapatkan id_kota dari user yang sedang login
        $user = auth()->user();
    
        // Query untuk mencari id_kota dari tbl_kota_has_user
        $id_kota = DB::table('kota_user')
                    ->where('username', operator: $user->username)
                    ->value('id_kota');
    
        // Pastikan id_kota valid sebelum menyimpan
        if ($id_kota) {
            KotaArtefak::create([
                'id_kota' => $id_kota,
                'id_artefak' => $artefak_id,
                'file_pengumpulan' => $filePath,
                'waktu_pengumpulan' => now(),
            ]);
    
            return redirect()->route('artefak')->with('success', 'Tugas berhasil dikumpulkan!');
        } else {
            // Handle jika id_kota tidak ditemukan atau tidak valid
            return redirect()->route('artefak')->with('error', 'Gagal menyimpan data: id_kota tidak valid.');
        }
    }

    public function destroy($id)
    {
        $kumpul = KotaArtefak::findOrFail($id);
        Storage::disk('public')->delete($kumpul->file_pengumpulan);
        $kumpul->delete();

        return redirect()->route('artefak')->with('success', 'Pengumpulan berhasil dibatalkan!');
    }

}
