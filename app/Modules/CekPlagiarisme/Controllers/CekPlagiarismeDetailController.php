<?php
namespace App\Modules\CekPlagiarisme\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\ReviewDosenPembimbing;
use App\Models\ListJurnalPlagiarisme;
use App\Models\ListKalimatPlagiarisme;
use App\Models\AlokasiDosen;
use Carbon\Carbon;
use App\Services\Notifikasi;
use App\Models\Mahasiswa;
use App\Notifications\TestEmailNotification;

Carbon::setLocale('id');

class CekPlagiarismeDetailController extends Controller
{

    public function show($id)
    {
        $dokumen = Dokumen::with(['user', 'ambangBatas', 'keywords'])->find($id);

        $digital_receipt = null;
        if ($dokumen) {
            $digital_receipt = Dokumen::where('username', $dokumen->username)
                ->where('kategori', 'digital_receipt')
                ->where('judul', 'like', $dokumen->judul . '%')
                ->latest()
                ->first();
        }

        $catatan = ReviewDosenPembimbing::with('dosen.user')
            ->where('id_dokumen', $id)
            ->get();

        $alokasiDosen = AlokasiDosen::all();

        // Ambil data jurnal plagiarisme berdasarkan id dokumen
        $jurnalPlagiarisme = ListJurnalPlagiarisme::where('id_dokumen', $id)->get();

        // Kirim data jurnal ke view
        return view('CekPlagiarisme.views.detail', compact('dokumen', 'digital_receipt', 'catatan', 'alokasiDosen', 'jurnalPlagiarisme'));
    }

    public function PenentuanAmbangBatas(): View
    {
        return view('CekPlagiarisme.views.PenentuanAmbangBatas');
    }

    public function storeCatatan(Request $request, $id_dokumen)
    {
        // Validasi input
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // Ambil NIP dosen yang sedang login
        $nip = auth()->user()->username;

        // Cek apakah dokumen dengan ID yang diberikan ada
        $dokumen = Dokumen::find($id_dokumen);
        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan!'
            ]);
        }

        // Simpan catatan baru
        $catatan = ReviewDosenPembimbing::create([
            'id_dokumen' => $id_dokumen,
            'nip' => $nip,
            'review' => $request->comment,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

                // Notifikasi Ke 3 Mahasiswa Dosen Sudah Memberikan Catatan
        $nimPemilik = $dokumen->username;
        try {
            // Kirim ke pemilik dokumen
            $userPemilik = \App\Models\Mahasiswa::where('nim', $nimPemilik)->first()?->user;
            if ($userPemilik) {
                $userPemilik->notify(new TestEmailNotification(
                    '[Pemberitahuan] Dosen Telah Menambahkan Catatan',
                    [
                        'catatan' => $catatan->review
                    ]
                ));
            }

            // Kirim ke anggota kelompok TA kedua dan ketiga jika ada
            $mahasiswa = Mahasiswa::where('nim', $nimPemilik)->first();
            if ($mahasiswa && $mahasiswa->id_kota) {
                // Ambil mahasiswa lain dalam kota yang sama
                $anggotaKelompok = Mahasiswa::where('id_kota', $mahasiswa->id_kota)
                    ->where('nim', '!=', $nimPemilik)
                    ->limit(2)
                    ->get();

                foreach ($anggotaKelompok as $anggota) {
                    if ($anggota->user) {
                        $anggota->user->notify(new TestEmailNotification(
                            '[Pemberitahuan] Dosen Telah Menambahkan Catatan',
                            [
                                'catatan' => $catatan->review
                            ]
                        ));
                    }
                }
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi catatan: ' . $notifEx->getMessage());
        }
        
        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan!',
            'catatan' => $catatan,
        ]);

    }

    public function deleteCatatan($id_dokumen, $id_catatan)
    {

        $catatan = ReviewDosenPembimbing::where('id_dokumen', $id_dokumen)->where('id_review', $id_catatan)->first();
        if (!$catatan) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan!'
            ]);
        }
        $catatan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil dihapus!'
        ]);
    }

    public function updateCatatan($id_dokumen, $id_catatan, Request $request)
    {
        // Validasi input
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // Ambil NIP dosen yang sedang login
        $nip = auth()->user()->username;

        // Cek apakah dokumen dengan ID yang diberikan ada
        $dokumen = Dokumen::find($id_dokumen);
        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan!'
            ]);
        }
        $catatan = ReviewDosenPembimbing::where('id_dokumen', $id_dokumen)->where('id_review', $id_catatan)->first();
        if (!$catatan) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan!'
            ]);
        }
        $catatan->update([
            'review' => $request->comment,
            'updated_at' => Carbon::now(),
            'nip' => $nip,
        ]);

        $nimPemilik = $dokumen->username;
        try {
            // Kirim ke pemilik dokumen
            $userPemilik = Mahasiswa::where('nim', $nimPemilik)->first()?->user;
            if ($userPemilik) {
                $userPemilik->notify(new TestEmailNotification(
                    '[Pemberitahuan] Dosen Telah Memperbarui Catatan',
                    [
                        'catatan' => $catatan->review
                    ]
                ));
            }

            // Kirim ke anggota kelompok TA kedua dan ketiga jika ada
            $mahasiswa = Mahasiswa::where('nim', $nimPemilik)->first();
            if ($mahasiswa && $mahasiswa->id_kota) {
                // Ambil mahasiswa lain dalam kota yang sama
                $anggotaKelompok = Mahasiswa::where('id_kota', $mahasiswa->id_kota)
                    ->where('nim', '!=', $nimPemilik)
                    ->limit(2)
                    ->get();

                foreach ($anggotaKelompok as $anggota) {
                    if ($anggota->user) {
                        $anggota->user->notify(new TestEmailNotification(
                            '[Pemberitahuan] Dosen Telah Memperbarui Catatan',
                            [
                                'catatan' => $catatan->review
                            ]
                        ));
                    }
                }
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi pembaruan catatan: ' . $notifEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui!',
            'catatan' => $catatan,
        ]);

    }
}