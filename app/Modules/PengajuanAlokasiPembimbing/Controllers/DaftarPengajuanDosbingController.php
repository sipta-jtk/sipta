<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\Kota;
use App\Models\Dosen;
use App\Models\PengajuanPembimbing;
use App\Models\PreferensiKota;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Modules\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DaftarPengajuanDosbingController extends Controller
{
    // private $USER_ID = '197312271999031003';

    public function view_daftarPengajuanDosbing()
    {

        // dd(auth()->user()->username);
        // dd(auth()->user()->dosen->nip);
        $kotaList = Kota::pluck('nama_kota')->toArray();
        $namaKota = Kota::pluck('nama_kota')->first();
        $bidangList = Bidang::pluck('bidang')->toArray();
        $judulList = Kota::pluck('judul_ta')->toArray();
        $tanggalPengajuanList = PengajuanPembimbing::pluck('created_at')->toArray();

        $allKotaData = Kota::orderBy('nama_kota')->get()->keyBy('id_kota');
        $allBidangData = Bidang::get()->keyBy('id_bidang');

        Carbon::setLocale('id');
        $formattedTanggalList = array_map(function($tanggal) {
            return Carbon::parse($tanggal)->translatedFormat('H:i d F Y');
        }, $tanggalPengajuanList);
        

        $mahasiswaList = User::where('role_user', 'mahasiswa')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'user.username')
            ->join('kota', 'kota.id_kota', '=', 'mahasiswa.id_kota')
            ->join('pengajuan_pembimbing', 'kota.id_kota', '=', 'pengajuan_pembimbing.id_kota')
            ->select(
                'user.username as nim',
                'user.nama',
                'kota.nama_kota',
                'mahasiswa.id_prodi',
                'mahasiswa.id_kota'
            )
            ->orderBy('kota.nama_kota')
            ->get();

        $kotaProdi = Mahasiswa::select('id_kota', 'id_prodi')
            ->groupBy('id_kota', 'id_prodi')
            ->get()
            ->pluck('id_prodi', 'id_kota');

        $dosenNip = auth()->user()->dosen->nip ?? null;
        $preferredKotaIdsByDosen = [];
        if ($dosenNip) {
            $preferredKotaIdsByDosen = PreferensiKota::where('nip', $dosenNip)
                                            ->pluck('status', 'id_kota')
                                            ->all(); // Menghasilkan array [id_kota1, id_kota2, ...]
        }

        $kelompokData = ['table' => [], 'prodiList' => []];
        // $mahasiswaGrouped = $mahasiswaList->groupBy('nama_kota');
        $mahasiswaGroupedByIdKota = $mahasiswaList->groupBy('id_kota');


        $loopIteration = 1;
        foreach ($mahasiswaGroupedByIdKota as $idKota => $anggotaGrup) {
            $kotaInfo = $allKotaData->get($idKota);
            if (!$kotaInfo) continue;
            $adaPengajuan = PengajuanPembimbing::where('id_kota', $idKota)->exists();
            if (!$adaPengajuan) {
                continue;
            }
            $anggotaFormatted = $anggotaGrup->map(function ($mhs) {
                return [
                    'nama' => $mhs->nama,
                    'nim' => $mhs->nim,
                ];
            })->values()->toArray();
    
            $statusPeminatanDb = $preferredKotaIdsByDosen[$idKota] ?? null;
            $statusPeminatanUntukTampilan = 'none';

            if ($statusPeminatanDb === 1) {
                $statusPeminatanUntukTampilan = 'accepted';
            } elseif ($statusPeminatanDb === 0) {
                $statusPeminatanUntukTampilan = 'rejected';
            }


            // $index = array_search($namaKota, $kotaList);
            // if ($index === false) continue;
            
            // $idKota = $kelompokData['status_peminatan_aktual']->anggota->first()->id_kota ?? null;
            // $idProdi = $anggota->first()->id_prodi;

            // $dosenNip = auth()->user()->dosen->nip ?? null;
            // $statusPeminatan = 'none'; // Default status
            // if ($dosenNip) {
            //     $preferensi = PreferensiKota::where('nip', $dosenNip)->where('id_kota', $idKota)->first();
            //     if ($preferensi) {
            //         $statusPeminatan = 'accepted';
            //     }
            // }
            
            $kelompokData['table'][] = [
                'loop_no' => $loopIteration++,
                'id_kota_real' => $idKota,
                'anggota' => $anggotaFormatted, 
                'status_peminatan_aktual' => $statusPeminatanUntukTampilan,
                'id_prodi' => $anggotaGrup->first()->id_prodi ?? null,
                'bidang' => $kotaInfo->bidang->bidang ?? '-', // pastikan relasi `bidang` didefinisikan di model Kota
                'judul' => $kotaInfo->judul_ta ?? '-',         // asumsinya judul_ta adalah kolom di tabel `kota`
            ];

        }

        $kelompokData['prodiList'] = Prodi::orderBy('nama_prodi', 'asc')->get(['id_prodi', 'nama_prodi']);

        // dd($prodiList->toArray());

        // dd('SEBELUM RETURN VIEW DI CONTROLLER');
        return view('PengajuanAlokasiPembimbing.views.DaftarPengajuanDosbing.topik', compact('kelompokData'));
    }    public function handlePengajuan(Request $request, $id_kota, $action)
    {
        // Generate unique request ID untuk tracking
        $requestId = $request->header('X-Request-ID', uniqid());
        
        // Log setiap request yang masuk
        \Log::info('=== REQUEST MASUK ===', [
            'request_id' => $requestId,
            'timestamp' => now()->toISOString(),
            'user_id' => Auth::id(),
            'id_kota' => $id_kota,
            'action' => $action,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        $dosenNip = null;
        if (Auth::check() && Auth::user()->dosen) {
            $dosenNip = Auth::user()->dosen->nip;
        } else {
            \Log::warning('Unauthorized access attempt', [
                'request_id' => $requestId,
                'user_id' => Auth::id(),
                'id_kota' => $id_kota
            ]);
            return response()->json(['status' => 'error', 'message' => 'User tidak terautentikasi atau bukan dosen.'], 403);
        }
        
        // Validasi input
        if (!in_array($action, ['accept', 'reject'])) {
            \Log::error('Invalid action', [
                'request_id' => $requestId,
                'action' => $action,
                'nip' => $dosenNip
            ]);
            return response()->json(['status' => 'error', 'message' => 'Aksi tidak valid.'], 400);
        }
        
        $kota = Kota::where('id_kota', $id_kota)->first();        
        if (!$kota) {
            \Log::error('Kota not found', [
                'request_id' => $requestId,
                'id_kota' => $id_kota,
                'nip' => $dosenNip
            ]);
            return response()->json(['message' => 'Kota/Kelompok tidak ditemukan.'], 404);
        }
        
        // Log state database sebelum operasi
        $beforeState = DB::select('SELECT nip, id_kota, status FROM preferensi_kota WHERE id_kota = ?', [$id_kota]);
        \Log::info('Database state BEFORE operation', [
            'request_id' => $requestId,
            'id_kota' => $id_kota,
            'target_nip' => $dosenNip,
            'action' => $action,
            'before_state' => $beforeState
        ]);
    
        DB::beginTransaction();
        try {
            // Cek apakah record sudah ada dengan method yang aman
            $recordExists = PreferensiKota::existsRecord($dosenNip, $id_kota);
            
            \Log::info('Current preferensi check', [
                'request_id' => $requestId,
                'nip' => $dosenNip,
                'id_kota' => $id_kota,
                'record_exists' => $recordExists
            ]);
        
            if ($action === 'accept') {
                if ($recordExists) {
                    // Update existing record
                    $affected = PreferensiKota::updateStatus($dosenNip, $id_kota, 1);
                    
                    \Log::info('Preferensi updated (accept)', [
                        'request_id' => $requestId,
                        'nip' => $dosenNip,
                        'id_kota' => $id_kota,
                        'status' => 1,
                        'affected_rows' => $affected,
                        'operation' => 'update'
                    ]);
                } else {
                    // Insert new record
                    $result = DB::insert(
                        'INSERT INTO preferensi_kota (nip, id_kota, status) VALUES (?, ?, ?)',
                        [$dosenNip, $id_kota, 1]
                    );
                    
                    \Log::info('Preferensi created (accept)', [
                        'request_id' => $requestId,
                        'nip' => $dosenNip,
                        'id_kota' => $id_kota,
                        'status' => 1,
                        'result' => $result,
                        'operation' => 'insert'
                    ]);
                }
                
                $message = 'Peminatan berhasil diterima.';
                $newStatus = 'accepted';
            } 
            elseif ($action === 'reject') {
                if ($recordExists) {
                    // Update existing record
                    $affected = PreferensiKota::updateStatus($dosenNip, $id_kota, 0);
                    
                    \Log::info('Preferensi updated (reject)', [
                        'request_id' => $requestId,
                        'nip' => $dosenNip,
                        'id_kota' => $id_kota,
                        'status' => 0,
                        'affected_rows' => $affected,
                        'operation' => 'update'
                    ]);
                } else {
                    // Insert new record
                    $result = DB::insert(
                        'INSERT INTO preferensi_kota (nip, id_kota, status) VALUES (?, ?, ?)',
                        [$dosenNip, $id_kota, 0]
                    );
                    
                    \Log::info('Preferensi created (reject)', [
                        'request_id' => $requestId,
                        'nip' => $dosenNip,
                        'id_kota' => $id_kota,
                        'status' => 0,
                        'result' => $result,
                        'operation' => 'insert'
                    ]);
                }
                
                $message = 'Peminatan berhasil ditolak.';
                $newStatus = 'rejected';
            }
        
        // Log state database setelah operasi tapi sebelum commit
        $afterState = DB::select('SELECT nip, id_kota, status FROM preferensi_kota WHERE id_kota = ?', [$id_kota]);
        \Log::info('Database state AFTER operation (before commit)', [
            'request_id' => $requestId,
            'id_kota' => $id_kota,
            'target_nip' => $dosenNip,
            'action' => $action,
            'after_state' => $afterState
        ]);
        
        DB::commit();
        
        // Log final state setelah commit
        $finalState = DB::select('SELECT nip, id_kota, status FROM preferensi_kota WHERE id_kota = ?', [$id_kota]);
        \Log::info('Database state FINAL (after commit)', [
            'request_id' => $requestId,
            'id_kota' => $id_kota,
            'target_nip' => $dosenNip,
            'action' => $action,
            'final_state' => $finalState
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'new_status_peminatan' => $newStatus,
            'request_id' => $requestId
        ], 200);
        
        } 
        catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error mengubah preferensi kota: ' . $e->getMessage(), [
                'request_id' => $requestId,
                'nip' => $dosenNip,
                'id_kota' => $id_kota,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server saat memproses permintaan.',
            ], 500);
        }
    }

    public function testRawQuery(Request $request, $id_kota, $action)
    {
        // Test raw SQL untuk memastikan apakah masalahnya di level database
        $dosenNip = '198604212018031001';
        
        \Log::info('=== RAW QUERY TEST START ===', [
            'nip' => $dosenNip,
            'id_kota' => $id_kota,
            'action' => $action
        ]);
        
        // Ambil state sebelum
        $beforeState = DB::select('SELECT nip, id_kota, status FROM preferensi_kota WHERE id_kota = ?', [$id_kota]);
        \Log::info('Before RAW update', ['before_state' => $beforeState]);
        
        // Raw update dengan WHERE yang sangat spesifik
        $affected = DB::update('UPDATE preferensi_kota SET status = ? WHERE nip = ? AND id_kota = ?', [
            $action === 'accept' ? 1 : 0,
            $dosenNip,
            $id_kota
        ]);
        
        \Log::info('Raw update executed', [
            'affected_rows' => $affected,
            'expected_rows' => 1
        ]);
        
        // Ambil state setelah
        $afterState = DB::select('SELECT nip, id_kota, status FROM preferensi_kota WHERE id_kota = ?', [$id_kota]);
        \Log::info('After RAW update', ['after_state' => $afterState]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Raw query test completed',
            'affected_rows' => $affected,
            'before_state' => $beforeState,
            'after_state' => $afterState
        ]);
    }
}