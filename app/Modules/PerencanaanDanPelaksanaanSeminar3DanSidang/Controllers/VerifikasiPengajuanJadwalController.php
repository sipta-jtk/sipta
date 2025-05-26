<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Penjadwalan;
use App\Models\Mahasiswa;
use App\Models\PengajuanJadwalKota;
use App\Models\AlokasiDosen;
use App\Models\Dosen;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Carbon\Carbon;
Carbon::setLocale('id');


class VerifikasiPengajuanJadwalController extends Controller
{
    public function getListAsKoordinatorTA(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = Dosen::where('nip', $nip)->value('role_dosen');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';
        
        $dataPengajuan = PengajuanJadwalKota::with(['penjadwalan', 'kota'])
        ->whereHas('penjadwalan', function ($query) use ($agenda) {
            $query->where('agenda', $agenda)
                  ->where('status','pending');
        })
        ->where('status_dosen_penguji_1', 1)
        ->where('status_dosen_penguji_2', 1)
        ->whereNull('status_koordinator_ta')
        ->get()
        ->map(function ($item) {
            return [
                'id_penjadwalan' => $item->id_penjadwalan,
                'nama_kota' => $item->kota->nama_kota,
                'judul_ta' => $item->kota->judul_ta,
                'agenda' => $item->penjadwalan->agenda,
                'tanggal' => $item->penjadwalan->tanggal,
                'id_ruangan' => $item->penjadwalan->id_ruangan,
                'nama_ruangan' => $item->penjadwalan->nama_ruangan,
                'sesi' => $item->penjadwalan->sesi,
                'start' => \Carbon\Carbon::parse($item->penjadwalan->start)->format('H:i'),
                'end' => \Carbon\Carbon::parse($item->penjadwalan->end)->format('H:i'),
                'status_koordinator_ta' => $item->status_koordinator_ta,
            ];
        });

        $dataPengajuan = $this->formatTanggalPengajuan($dataPengajuan);

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaPengajuanJadwal.ListPengajuanJadwalKoordinator', compact('dataPengajuan', 'tipe'));
    }

    public function getListAsDosenPembimbing(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = AlokasiDosen::where('nip', $nip)->value('tipe_alokasi');
    
        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';  

        $dataPengajuan = Penjadwalan::where('agenda', $agenda)
        ->where('status', 'pending')
        ->with(['pengajuanJadwalKota.kota.pengajuanPembimbing.alokasiDosen' => function ($query) use ($nip) {
            $query->where('status_alokasi', 'fix')
                ->where('tipe_alokasi', 'pembimbing')
                ->where('nip', $nip);
        }])
        ->get()
        ->flatMap(function ($penjadwalan) {
            return $penjadwalan->pengajuanJadwalKota
                ->flatMap(function ($pengajuan) use ($penjadwalan) {
                    $pengajuanPembimbing = $pengajuan->kota?->pengajuanPembimbing;

                    return collect($pengajuanPembimbing)->flatMap(function ($pembimbing) use ($pengajuan, $penjadwalan) {
                        return $pembimbing->alokasiDosen->filter(function ($alokasi) use ($pengajuan) {
                            return (
                                ($alokasi->urutan_prioritas_terpilih == 1 && is_null($pengajuan->status_dosen_pembimbing_1) && $pengajuan->status_dosen_pembimbing_2 !== 0) 
                                ||
                                ($alokasi->urutan_prioritas_terpilih == 2 && is_null($pengajuan->status_dosen_pembimbing_2) && $pengajuan->status_dosen_pembimbing_1 !== 0)
                            );
                        })->map(function ($alokasi) use ($penjadwalan, $pengajuan) {
                            return [
                                'id_penjadwalan' => $pengajuan->id_penjadwalan,
                                'nama_kota' => $pengajuan->kota?->nama_kota,
                                'judul_ta' => $pengajuan->kota?->judul_ta,
                                'agenda' => $penjadwalan->agenda,
                                'tanggal' => $penjadwalan->tanggal,
                                'id_ruangan' => $penjadwalan->id_ruangan,
                                'nama_ruangan' => $penjadwalan->nama_ruangan,
                                'sesi' => $penjadwalan->sesi,
                                'start' => \Carbon\Carbon::parse($penjadwalan->start)->format('H:i'),
                                'end' => \Carbon\Carbon::parse($penjadwalan->end)->format('H:i'),
                                'status_dosen_pembimbing_1' => $pengajuan->status_dosen_pembimbing_1,
                                'status_dosen_pembimbing_2' => $pengajuan->status_dosen_pembimbing_2,
                                'urutan_prioritas_terpilih' => $alokasi->urutan_prioritas_terpilih,
                            ];
                        });
                    });
                });
            });

        $dataPengajuan = $this->formatTanggalPengajuan($dataPengajuan);
    
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaPengajuanJadwal.ListPengajuanJadwalPembimbing', compact('dataPengajuan', 'tipe'));
    }
    
    public function getListAsDosenPenguji(Request $request, String $tipe): View
    {
        $nip = auth()->user()->username;
        
        $role = AlokasiDosen::where('nip', $nip)->value('tipe_alokasi');

        $agenda = ($tipe == 'seminar-3') ? 'seminar_3' : 'sidang';

        $dataPengajuan = Penjadwalan::where('agenda', $agenda)
        ->where('status', 'pending')
        ->with(['pengajuanJadwalKota.kota.pengajuanPembimbing.alokasiDosen' => function ($query) use ($nip) {
            $query->where('status_alokasi', 'fix')
                ->where('tipe_alokasi', 'penguji')
                ->where('nip', $nip);
        }])
        ->get()
        ->flatMap(function ($penjadwalan) {
            return $penjadwalan->pengajuanJadwalKota
                ->flatMap(function ($pengajuan) use ($penjadwalan) {
                    $pengajuanPembimbing = $pengajuan->kota?->pengajuanPembimbing;

                    return collect($pengajuanPembimbing)->flatMap(function ($pembimbing) use ($pengajuan, $penjadwalan) {
                        return $pembimbing->alokasiDosen->filter(function ($alokasi) use ($pengajuan) {
                            return (
                                ((
                                    $alokasi->urutan_prioritas_terpilih == 1 && is_null($pengajuan->status_dosen_penguji_1) && $pengajuan->status_dosen_penguji_2 !== 0
                                ) 
                                ||
                                (
                                    $alokasi->urutan_prioritas_terpilih == 2 && is_null($pengajuan->status_dosen_penguji_2) && $pengajuan->status_dosen_penguji_2 !== 0
                                )) 
                                &&
                                (
                                    $pengajuan->status_dosen_pembimbing_1 === 1 && $pengajuan->status_dosen_pembimbing_2 ===1
                                )
                            );
                        })->map(function ($alokasi) use ($penjadwalan, $pengajuan) {
                            return [
                                'id_penjadwalan' => $pengajuan->id_penjadwalan,
                                'nama_kota' => $pengajuan->kota?->nama_kota,
                                'judul_ta' => $pengajuan->kota?->judul_ta,
                                'agenda' => $penjadwalan->agenda,
                                'tanggal' => $penjadwalan->tanggal,
                                'id_ruangan' => $penjadwalan->id_ruangan,
                                'nama_ruangan' => $penjadwalan->nama_ruangan,
                                'sesi' => $penjadwalan->sesi,
                                'start' => \Carbon\Carbon::parse($penjadwalan->start)->format('H:i'),
                                'end' => \Carbon\Carbon::parse($penjadwalan->end)->format('H:i'),
                                'status_dosen_pembimbing_1' => $pengajuan->status_dosen_penguji_1,
                                'status_dosen_pembimbing_2' => $pengajuan->status_dosen_penguji_2,
                                'urutan_prioritas_terpilih' => $alokasi->urutan_prioritas_terpilih,
                            ];
                        });
                    });
                });
            });

        $dataPengajuan = $this->formatTanggalPengajuan($dataPengajuan);
    
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.kelolaPengajuanJadwal.ListPengajuanJadwalPenguji', compact('dataPengajuan', 'tipe'));
    }


    public function verifikasiAsKoordinatorTA(Request $request, string $tipe, int $idPenjadwalan)
    {
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        if ($status == true) {
            $roomInformation = Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
                ->select('id_ruangan', 'tanggal', 'agenda', 'nama_ruangan', 'status','sesi','tanggal','start', 'end','id_kota')
                ->first();

            if ($roomInformation) {
                $token = auth()->user()->createToken(auth()->user()->username . '_token')->plainTextToken;

                $data = [
                    'type' => 'add',
                    'agenda' => $roomInformation->agenda,
                    'start' => $roomInformation->start,
                    'end' => $roomInformation->end,
                    'id_ruangan' => $roomInformation->id_ruangan,
                    'id_kota' => $roomInformation->id_kota,
                    'nip' => auth()->user()->username,
                ];

                try {
                    $response = Http::withHeaders([
                            'X-Requested-With' => 'XMLHttpRequest',
                            'content-type' => 'application/json',
                            'Accept' => 'application/json',
                        ])
                        ->withToken($token)
                        ->timeout(10)
                        ->post('https://polban-space.cloudias79.com/penjadwalan-ruangan/api/v1/schedule/action', $data);

                    if ($response->successful()) {
                        PengajuanJadwalKota::where('id_penjadwalan', $idPenjadwalan)
                            ->update(['status_koordinator_ta' => $status]);

                        Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
                            ->update(['status' => 'fix']);

                        $mahasiswa = Mahasiswa::where('id_kota', $roomInformation->id_kota)
                            ->select('nim')
                            ->first();

                        if ($mahasiswa){
                            Kehadiran::create([
                                'id_penjadwalan' => $idPenjadwalan,
                                'username' => $mahasiswa->nim,
                                'status_hadir' => 'belum_absen',
                                'status_kelulusan' => 'pending',
                                'batas_revisi' => null,
                                'foto_sidang' => null,
                            ]);
                        }
                    } else {
                        $responseData = $response->json();
                        $errorMessage = $responseData['message'] ?? 'Terjadi kesalahan saat memproses permintaan.';
                        
                        \Log::error('Gagal memverifikasi penjadwalan', [
                            'data_dikirim' => $data,
                            'response' => $response->body(),
                            'status' => $response->status()
                        ]);

                        return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
                            ->with('error', "Gagal memverifikasi: " . $errorMessage);
                    }
                } catch (\Exception $e) {
                    return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
                        ->with('error', "Terjadi kesalahan: " . $e->getMessage());
                }
            }
        } else {
            PengajuanJadwalKota::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_koordinator_ta' => $status]);

            Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status' => 'batal']);
        }

        return redirect()->route('kelola.jadwal.list', ['tipe' => $tipe])
            ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }

    public function verifikasiAsPembimbing(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        $nip = auth()->user()->username;

        $prioritas = Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
        ->whereHas('pengajuanJadwalKota.kota.pengajuanPembimbing.alokasiDosen', function ($query) use ($nip) {
            $query->where('nip', $nip)
                ->where('status_alokasi', 'fix');
        })
        ->with(['pengajuanJadwalKota.kota.pengajuanPembimbing.alokasiDosen' => function ($query) use ($nip) {
            $query->where('nip', $nip)
                ->where('status_alokasi', 'fix');
        }])
        ->first()
        ?->pengajuanJadwalKota
        ->flatMap(fn($pjk) => $pjk->kota->pengajuanPembimbing)
        ->flatMap(fn($pp) => $pp->alokasiDosen)
        ->where('nip', $nip)
        ->where('status_alokasi', 'fix')
        ->first()
        ?->urutan_prioritas_terpilih;

        if ($prioritas == 1) {
            PengajuanJadwalKota::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_pembimbing_1' => $status]);

        } elseif ($prioritas == 2) {
            PengajuanJadwalKota::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_pembimbing_2' => $status]);
        }

        // Update status penjadwalan menjadi batal jika status ditolak
        if ($status == false) {
            // Jika status disetujui, update status penjadwalan menjadi fix
            Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status' => 'batal']);
        }
        
        // Redirect kembali ke halaman dengan pesan
        return redirect()->route('kelola-pembimbing.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }

    public function verifikasiAsPenguji(Request $request, string $tipe, int $idPenjadwalan)
    {
        // Ambil status verifikasi dari request
        $status = $request->input('status_verifikasi') === 'Ditolak' ? false : true;

        $nip = auth()->user()->username;

        $prioritas = Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
        ->whereHas('pengajuanJadwalKota.kota.pengajuanPembimbing.alokasiDosen', function ($query) use ($nip) {
            $query->where('nip', $nip)
                ->where('status_alokasi', 'fix');
        })
        ->with(['pengajuanJadwalKota.kota.pengajuanPembimbing.alokasiDosen' => function ($query) use ($nip) {
            $query->where('nip', $nip)
                ->where('status_alokasi', 'fix');
        }])
        ->first()
        ?->pengajuanJadwalKota
        ->flatMap(fn($pjk) => $pjk->kota->pengajuanPembimbing)
        ->flatMap(fn($pp) => $pp->alokasiDosen)
        ->where('nip', $nip)
        ->where('status_alokasi', 'fix')
        ->first()
        ?->urutan_prioritas_terpilih;

        if ($prioritas == 1) {
            PengajuanJadwalKota::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_penguji_1' => $status]);
        } elseif ($prioritas == 2) {
            PengajuanJadwalKota::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status_dosen_penguji_2' => $status]);
        }

        // Update status penjadwalan menjadi batal jika status ditolak
        if ($status == false) {
            // Jika status disetujui, update status penjadwalan menjadi fix
            Penjadwalan::where('id_penjadwalan', $idPenjadwalan)
                ->update(['status' => 'batal']);
        }
        
        // Redirect kembali ke halaman dengan pesan
        return redirect()->route('kelola-penguji.jadwal.list', ['tipe' => $tipe])
                        ->with('success', "Status verifikasi: " . ($status ? 'Disetujui' : 'Ditolak'));
    }

    private function formatTanggalPengajuan(Collection $dataPengajuan): Collection
    {
        return $dataPengajuan->map(function ($item) {
            if (isset($item->penjadwalan) && $item->penjadwalan->tanggal) {
                $item->penjadwalan->tanggal = Carbon::parse($item->penjadwalan->tanggal)->translatedFormat('d F Y');
            }
            return $item;
        });
    }

}