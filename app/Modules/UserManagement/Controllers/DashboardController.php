<?php

namespace App\Modules\UserManagement\Controllers;

use App\Modules\Controller;
use App\Models\User;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kbk;
use App\Models\Prodi;
use App\Models\Bidang;
use App\Models\PengajuanPembimbing;
use App\Models\Penjadwalan;
use App\Models\PengajuanJadwalKota;
use App\Models\VerifikasiBerkasPengajuan;
use App\Models\Dokumen;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        if($user !== null){
            $role = $user->role_user;

            /* MAHASISWA */
            if ($role === 'mahasiswa') {
                $mahasiswa = Mahasiswa::where('nim', $user->username)->first();
                $prodi = Prodi::find($mahasiswa->id_prodi);
                $kota = $mahasiswa->kota ?? null;
                $anggotaKelompok = $mahasiswa->id_kota 
                    ? Mahasiswa::where('id_kota', $mahasiswa->id_kota)->where('nim', '!=', $mahasiswa->nim)->get()
                    : collect([]);

                $pembimbing = [
                    (object)['urutan_prioritas_terpilih' => 1, 'nama' => 'Pembimbing 1 belum ditentukan', 'nip' => '-'],
                    (object)['urutan_prioritas_terpilih' => 2, 'nama' => 'Pembimbing 2 Belum ditentukan', 'nip' => '-']
                ];

                try {
                    if (Schema::hasTable('alokasi_dosen')) {
                        $dosenPembimbing = DB::table('pengajuan_pembimbing')
                            ->join('alokasi_dosen', 'pengajuan_pembimbing.id_pengajuan_pembimbing', '=', 'alokasi_dosen.id_pengajuan_pembimbing')
                            ->join('dosen', 'alokasi_dosen.nip', '=', 'dosen.nip')
                            ->join('user', 'dosen.nip', '=', 'user.username')
                            ->where('pengajuan_pembimbing.id_kota', $kota->id_kota ?? null)
                            ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
                            ->where('alokasi_dosen.status_alokasi', 'fix')
                            ->orderBy('alokasi_dosen.urutan_prioritas_terpilih', 'asc')
                            ->select('alokasi_dosen.urutan_prioritas_terpilih', 'user.nama', 'dosen.nip')
                            ->get();

                        if ($dosenPembimbing->isNotEmpty()) {
                            $pembimbing = $dosenPembimbing;
                        }
                    }
                } catch (\Exception $e) {}

                // Berkas Seminar 3
                $idKota = $mahasiswa->id_kota;
                $pengajuanSeminar = VerifikasiBerkasPengajuan::where('id_kota', $idKota)->first();
                if($pengajuanSeminar) {
                    $pengajuanSeminar->formatted_tanggal_pengajuan = Carbon::parse($pengajuanSeminar->tanggal_pengajuan)->translatedFormat('d F Y H:i');
                }

                $dokumenWajibSeminar = [
                    ['nama' => 'Laporan', 'id_subkategori' => 1],
                    ['nama' => 'FTA 10', 'id_subkategori' => 2, 'kode_fta' => 'FTA-10'],
                    ['nama' => 'FTA 10a', 'id_subkategori' => 2, 'kode_fta' => 'FTA-10a'],
                    ['nama' => 'Presentasi', 'id_subkategori' => 3],
                ];
                $dokumenSeminar = $this->cekDokumen($dokumenWajibSeminar, $idKota);

                // Berkas Sidang
                $pengajuanSidang = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
                    ->where('jenis_pengajuan', 'sidang_akhir')
                    ->first();
                if($pengajuanSidang) {
                    $pengajuanSidang->formatted_tanggal_pengajuan = Carbon::parse($pengajuanSidang->tanggal_pengajuan)->translatedFormat('d F Y H:i');
                }

                $dokumenWajibSidang = [
                    ['nama' => 'FTA 14', 'id_subkategori' => 2, 'kode_fta' => 'FTA-14'],
                    ['nama' => 'FTA 14a', 'id_subkategori' => 2, 'kode_fta' => 'FTA-14a'],
                    ['nama' => 'Laporan Tugas Akhir', 'id_subkategori' => 1],
                    ['nama' => 'Presentasi', 'id_subkategori' => 3],
                ];

                $dokumenSidang = $this->cekDokumen($dokumenWajibSidang, $idKota);

                return view('welcome', compact(
                    'user', 'mahasiswa', 'prodi',
                    'kota', 'anggotaKelompok', 'pembimbing',
                    'pengajuanSeminar', 'dokumenSeminar',
                    'pengajuanSidang', 'dokumenSidang'
                ));
            }

            /* DOSEN */
            elseif ($role === 'dosen'){
                $dosen = Dosen::with('ketertarikanBidang.bidang')->where('nip', $user->username)->first();
                $idKota = DB::table('alokasi_dosen')
                    ->join('pengajuan_pembimbing', 'alokasi_dosen.id_pengajuan_pembimbing', '=', 'pengajuan_pembimbing.id_pengajuan_pembimbing')
                    ->where('alokasi_dosen.nip', $user->username)
                    ->where('alokasi_dosen.tipe_alokasi', 'pembimbing')
                    ->where('alokasi_dosen.status_alokasi', 'fix')
                    ->pluck('pengajuan_pembimbing.id_kota')
                    ->unique()
                    ->values();

                $kota = Kota::whereIn('id_kota', $idKota)->get();

                return view('welcome', compact('user', 'dosen', 'kota'));
            }

            /* ADMIN */
            elseif ($role === 'admin') {
                $dosenCount = Dosen::count();
                $mahasiswaCount = Mahasiswa::count();

                return view('welcome', compact('user', 'dosenCount', 'mahasiswaCount'));
            } 
        } else{
            $role = null;
            return view('welcome', compact('user'));
        }
    }

    private function cekDokumen(array $namaDokumen, $idKota)
    {
        $data = [];

        foreach ($namaDokumen as $item) {
            $query = Dokumen::where('id_kota', $idKota)
                ->where('id_subkategori', $item['id_subkategori'])
                ->where('kategori', 'seminar3');

            // Untuk FTA, filter juga kode_fta
            if (isset($item['kode_fta'])) {
                $query->where('kode_fta', $item['kode_fta']);
            }

            $isUploaded = $query->exists();
            
            $data[] = [
                'nama_dokumen' => $item['nama'],
                'status' => $isUploaded ? 'Sudah diunggah' : 'Belum diunggah',
            ];
        }

        return $data;
    }

}