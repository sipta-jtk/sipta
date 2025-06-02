<?php

namespace App\Modules\CekPlagiarisme\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\AmbangBatas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\Models\User;
use App\Notifications\TestEmailNotification;

Carbon::setLocale('id');

class AmbangBatasController extends Controller
{
    public function getData()
    {
        // Ambil semua data dari database beserta relasi dosen
        $ambangBatas = AmbangBatas::with('dosen.user')->get();

        // Format data agar sesuai dengan struktur jsGrid
        $data = $ambangBatas->map(function ($item) {
            return [
                'id' => $item->id_ambang_batas,
                'ambang_batas' => $item->ambang_batas,
                'tanggal' => $item->updated_at
                    ? Carbon::parse($item->updated_at)->translatedFormat('d F Y H:i')
                    : Carbon::now()->translatedFormat('d F Y H:i'),
                'koordinator' => $item->dosen && $item->dosen->user ? $item->dosen->user->nama : 'Tidak Ada', // Ambil nama dosen dari user
                'status' => ucfirst(str_replace('_', ' ', $item->status_ambang_batas)) // Ubah menjadi format yang lebih rapi
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $nip = auth()->user()->username;

        $request->validate([
            'ambang_batas' => 'required|numeric|min:1|max:100',
        ]);

        try {
            // Variabel untuk menyimpan response
            $response = null;

            DB::transaction(function () use ($request, $nip, &$response) {
                $existing = AmbangBatas::where('ambang_batas', $request->ambang_batas)->first();

                if ($existing) {
                    if ($existing->status_ambang_batas !== 'digunakan') {
                        // Kalau sudah ada, tapi belum digunakan, reset semua
                        AmbangBatas::where('status_ambang_batas', 'digunakan')->update([
                            'status_ambang_batas' => 'tidak_digunakan'
                        ]);

                        // Ubah yang ini jadi digunakan
                        $existing->update([
                            'status_ambang_batas' => 'digunakan',
                            'nip' => $nip,
                            'updated_at' => now()
                        ]);
                    }
                    $response = [
                        'success' => true,
                        'message' => 'Ambang Batas sudah pernah ditambahkan, status diubah menjadi digunakan!',
                        'data' => $existing
                    ];
                } else {
                    // Kalau tidak ada ambang batas ini, reset semua lalu create baru
                    AmbangBatas::where('status_ambang_batas', 'digunakan')->update([
                        'status_ambang_batas' => 'tidak_digunakan'
                    ]);

                    $new = AmbangBatas::create([
                        'ambang_batas' => $request->ambang_batas,
                        'status_ambang_batas' => 'digunakan',
                        'nip' => $nip,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $response = [
                        'success' => true,
                        'message' => 'Ambang Batas baru berhasil ditambahkan!',
                        'data' => $new
                    ];
                }
            });

            try {
                // Kirim notifikasi ke semua user
                $allUsers = User::all();
                foreach ($allUsers as $user) {
                    $user->notify(new \App\Notifications\TestEmailNotification(
                        '[Pemberitahuan] Ambang Batas Baru',
                        ['AmbangBatas' => $request->ambang_batas]
                    ));
                }
            } catch (\Exception $notifEx) {
                \Log::error('Gagal mengirim notifikasi Ambang Batas: ' . $notifEx->getMessage());
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan Ambang Batas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}