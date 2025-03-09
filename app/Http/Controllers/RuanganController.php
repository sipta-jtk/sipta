<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Gedung;
use App\Models\Fasilitas;
use App\Models\RuangFasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class RuanganController extends Controller
{
    private $uploadPath = 'image/ruangan';  // Folder untuk menyimpan foto

    /**
     * Menyimpan foto dan mengembalikan path relatifnya
     */
    private function uploadFoto($foto)
    {
        // Generate nama file yang unik
        $fileName = time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
        
        // Pindahkan file ke folder public/ruangan
        $foto->move(public_path('ruangan'), $fileName);
        
        // Return hanya nama filenya saja
        return $fileName; // Ini akan menyimpan hanya nama file di database
    }

    /**
     * Menghapus foto dari storage
     */
    private function deleteFoto($fileName)
    {
        if ($fileName && file_exists(public_path('ruangan/' . $fileName))) {
            unlink(public_path('ruangan/' . $fileName));
        }
    }

    /**
     * Menampilkan daftar ruangan
     */
    public function index(Request $request)
    {
        $query = Ruangan::query();
        
        // Pencarian berdasarkan tipe
        if ($request->filled('search_type') && $request->filled('keyword')) {
            $searchType = $request->search_type;
            $keyword = $request->keyword;
            
            if ($searchType === 'nama_ruangan') {
                $query->where('nama_ruangan', 'like', "%{$keyword}%");
            } elseif ($searchType === 'kode_ruangan') {
                $query->where('kode_ruangan', 'like', "%{$keyword}%");
            }
        }
        
        $ruangan = $query->with('gedung')->get();
        
        return view('ruangan.index', compact('ruangan'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru
     */
    public function create()
    {
        $gedung = Gedung::all();
        $fasilitas = Fasilitas::all();
        return view('ruangan.create', compact('gedung', 'fasilitas'));
    }

    /**
     * Menyimpan ruangan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:127|unique:ruangan,nama_ruangan',
            'kode_ruangan' => 'required|string|max:6',
            'status_ruangan' => 'required|in:tersedia,tidak_tersedia',
            'kode_gedung' => 'required|exists:gedung,kode_gedung',
            'fasilitas' => 'required|array',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();
            
            // Generate nama file dengan UUID
            $imageName = Str::uuid() . '.' . $request->foto->extension();
            $path = public_path('image/ruangan');
            
            // Pastikan direktori ada
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }

            // Upload file
            $request->foto->move($path, $imageName);
            
            try {
                // Buat ruangan baru dan simpan ID-nya
                $ruangan = Ruangan::create([
                    'nama_ruangan' => $request->nama_ruangan,
                    'kode_ruangan' => $request->kode_ruangan,
                    'status_ruangan' => $request->status_ruangan,
                    'kode_gedung' => $request->kode_gedung,
                    'link_ruangan' => $imageName
                ]);

                // Tambahkan fasilitas ke ruangan
                if ($request->has('fasilitas')) {
                    foreach ($request->fasilitas as $idFasilitas => $jumlah) {
                        if ($jumlah > 0) {
                            RuangFasilitas::create([
                                'id_ruangan' => $ruangan->id_ruangan,
                                'id_fasilitas' => $idFasilitas,
                                'jumlah_fasilitas' => $jumlah
                            ]);
                        }
                    }
                }
                
                DB::commit();
                return redirect()->route('ruangan.index')
                    ->with('success', 'Ruangan berhasil ditambahkan!');

            } catch (\Exception $e) {
                // Jika terjadi error saat menyimpan data, hapus file yang sudah diupload
                if (File::exists($path . '/' . $imageName)) {
                    File::delete($path . '/' . $imageName);
                }
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Menampilkan form untuk mengedit ruangan
     */
    public function edit($id)
    {
        try {
            // Ambil data ruangan beserta relasinya
            $ruangan = Ruangan::with(['gedung', 'fasilitas'])->findOrFail($id);
            
            // Ambil data gedung dan fasilitas untuk dropdown
            $gedung = Gedung::all();
            $fasilitas = Fasilitas::all();
            
            return view('ruangan.edit', compact('ruangan', 'gedung', 'fasilitas'));
            
        } catch (\Exception $e) {
            return redirect()->route('ruangan.index')
                ->withErrors(['error' => 'Ruangan tidak ditemukan']);
        }
    }

    /**
     * Mengupdate data ruangan di database
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $ruangan = Ruangan::findOrFail($id);
            
            // Validasi input
            $request->validate([
                'nama_ruangan' => 'required|string|max:127|unique:ruangan,nama_ruangan,'.$id.',id_ruangan',
                'kode_ruangan' => 'required|string|max:6',
                'status_ruangan' => 'required|in:tersedia,tidak_tersedia',
                'kode_gedung' => 'required|exists:gedung,kode_gedung',
                'fasilitas' => 'array',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            try {
                // Handle foto
                if ($request->remove_foto == '1' && $ruangan->link_ruangan) {
                    // Hapus foto lama jika diminta
                    $oldImagePath = public_path('image/ruangan/' . $ruangan->link_ruangan);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                    $ruangan->link_ruangan = null;
                } 
                elseif ($request->hasFile('foto')) {
                    // Upload foto baru
                    $imageName = Str::uuid() . '.' . $request->foto->extension();
                    $path = public_path('image/ruangan');

                    // Pastikan direktori ada
                    if (!File::exists($path)) {
                        File::makeDirectory($path, 0777, true);
                    }

                    // Hapus foto lama jika ada
                    if ($ruangan->link_ruangan) {
                        $oldImagePath = $path . '/' . $ruangan->link_ruangan;
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }

                    // Upload foto baru
                    $request->foto->move($path, $imageName);
                    $ruangan->link_ruangan = $imageName;
                }

                // Update data ruangan
                $ruangan->update([
                    'nama_ruangan' => $request->nama_ruangan,
                    'kode_ruangan' => $request->kode_ruangan,
                    'status_ruangan' => $request->status_ruangan,
                    'kode_gedung' => $request->kode_gedung,
                    'link_ruangan' => $ruangan->link_ruangan
                ]);

                // Update fasilitas
                // Hapus semua fasilitas yang ada
                RuangFasilitas::where('id_ruangan', $ruangan->id_ruangan)->delete();

                // Tambahkan fasilitas baru
                if ($request->has('fasilitas')) {
                    foreach ($request->fasilitas as $idFasilitas => $jumlah) {
                        if ($jumlah > 0) {
                            RuangFasilitas::create([
                                'id_ruangan' => $ruangan->id_ruangan,
                                'id_fasilitas' => $idFasilitas,
                                'jumlah_fasilitas' => $jumlah
                            ]);
                        }
                    }
                }

                DB::commit();
                return redirect()->route('ruangan.index')
                    ->with('success', 'Ruangan berhasil diperbarui!');

            } catch (\Exception $e) {
                // Jika ada foto yang baru diupload, hapus
                if (isset($imageName) && isset($path) && File::exists($path . '/' . $imageName)) {
                    File::delete($path . '/' . $imageName);
                }
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Menghapus ruangan dari database
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $ruangan = Ruangan::findOrFail($id);
            $path = public_path('image/ruangan');
            
            // Hapus foto jika ada
            if ($ruangan->link_ruangan) {
                $imagePath = $path . '/' . $ruangan->link_ruangan;
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
            
            try {
                // Hapus relasi fasilitas
                $ruangan->fasilitas()->detach();
                
                // Hapus ruangan
                $ruangan->delete();
                
                DB::commit();
                return redirect()->route('ruangan.index')
                    ->with('success', 'Ruangan berhasil dihapus!');
                
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
