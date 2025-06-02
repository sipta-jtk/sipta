<?php

namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Controllers;

use Carbon\Carbon;
Carbon::setLocale('id');
use App\Modules\Controller;
use App\Models\Kota;
use App\Models\Mahasiswa;
use App\Models\Penjadwalan;
use App\Models\PengajuanJadwalKota;
use App\Models\VerifikasiBerkasPengajuan;
use App\Models\KotaArtefak;
use App\Models\Artefak;
use App\Models\Dokumen;
use App\Models\Subkategori;
use App\Models\Dosen;

use app\Notifications\TestEmailNotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VerifikasiBerkasPengajuanMahasiswaController extends Controller
{
    public function create()
    {

        $user = Auth::user();
        $idKota = $user->mahasiswa->id_kota;


        // Ambil pengajuan berdasarkan id_kota
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)->first();

        if ($pengajuan) {
            $pengajuan->formatted_tanggal_pengajuan = Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('H:i d F Y');
        }

        // Daftar dokumen yang harus di-upload (subkategori: 1=laporan, 2=fta, 3=presentasi)
        $dokumenWajib = [
            ['nama' => 'Laporan', 'id_subkategori' => 1],
            ['nama' => 'FTA 10', 'id_subkategori' => 2, 'kode_fta' => 'FTA-10'],
            ['nama' => 'FTA 10a', 'id_subkategori' => 2, 'kode_fta' => 'FTA-10a'],
            ['nama' => 'Presentasi', 'id_subkategori' => 3],
        ];

        $data = [];
        $adaBelumUpload = false;

        foreach ($dokumenWajib as $item) {
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

            if (!$isUploaded) {
                $adaBelumUpload = true;
            }
        }

        // Cek apakah pengajuan tidak bisa dilakukan
        $tidakBisaAjukan = ($pengajuan && in_array($pengajuan->status_konfirmasi, ['disetujui', 'pending'])) || $adaBelumUpload;

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.verifikasi.VerifikasiBerkasPengajuanMahasiswa', compact('pengajuan', 'data', 'tidakBisaAjukan', 'adaBelumUpload'));
    }

    public function store(Request $request)
    {
        $idKota = Auth::user()->mahasiswa->id_kota;

        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
            ->where('jenis_pengajuan', 'seminar_3')
            ->first();

        if ($pengajuan) {
            // Jika pengajuan sudah ada, tidak perlu membuat yang baru
            $pengajuan->update([
                'tanggal_pengajuan' => Carbon::now(),
                'status_konfirmasi' => 'pending',
                'nip' => null,
                'tanggal_verifikasi' => null,
            ]);
        } else{
            VerifikasiBerkasPengajuan::create([
                'tanggal_pengajuan' => Carbon::now(),
                'jenis_pengajuan' => 'seminar_3',
                'id_kota' => $idKota,
                'status_konfirmasi' => 'pending',
            ]);
        }

        // Ke Koordinator TA
        try {
            if ($idKota) {
                $koordinatorTA = Dosen::where('role_dosen', 'koordinator_ta')->pluck('id_kota');
                foreach($koordinatorTA as $nip){
                    if ($nip) {
                        $nip->notify(new TestEmailNotification(
                            'Perubahan Status Dokumen Tugas Akhir!',
                            [
                                'tanggal_pengajuan' => Carbon::now(),
                                'status_konfirmasi' => 'pending',
                                'id_kota' => $idKota
                            ]
                        ));
                    }
                }
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi pemberian feedback: ' . $notifEx->getMessage(), [
                'id_kota' => $idKota,
                'nip' => ''
            ]);
        }
        return redirect()->route('verifikasi3.create')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function create_sidang()
    {
        $user = Auth::user();
        $idKota = $user->mahasiswa->id_kota;

        // Ambil pengajuan berdasarkan id_kota
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
            ->where('jenis_pengajuan', 'sidang_akhir')
            ->first();

        if ($pengajuan) {
            $pengajuan->formatted_tanggal_pengajuan = Carbon::parse($pengajuan->tanggal_pengajuan)->translatedFormat('H:i d F Y');
        }

        // Daftar dokumen yang harus di-upload untuk sidang akhir
        $dokumenWajib = [
            ['nama' => 'FTA 14', 'id_subkategori' => 2, 'kode_fta' => 'FTA-14'],
            ['nama' => 'FTA 14a', 'id_subkategori' => 2, 'kode_fta' => 'FTA-14a'],
            ['nama' => 'Laporan Tugas Akhir', 'id_subkategori' => 1],
            ['nama' => 'Presentasi', 'id_subkategori' => 3],
        ];

        $data = [];
        $adaBelumUpload = false;

        foreach ($dokumenWajib as $item) {
            $query = Dokumen::where('id_kota', $idKota)
                ->where('id_subkategori', $item['id_subkategori'])
                ->where('kategori', 'sidang');

            // Untuk FTA, filter juga kode_fta
            if (isset($item['kode_fta'])) {
                $query->where('kode_fta', $item['kode_fta']);
            }

            $isUploaded = $query->exists();

            $data[] = [
                'nama_dokumen' => $item['nama'],
                'status' => $isUploaded ? 'Sudah diunggah' : 'Belum diunggah',
            ];

            if (!$isUploaded) {
                $adaBelumUpload = true;
            }
        }

        // Cek apakah pengajuan tidak bisa dilakukan
        $tidakBisaAjukan = ($pengajuan && in_array($pengajuan->status_konfirmasi, ['disetujui', 'pending'])) || $adaBelumUpload;

        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.verifikasi.VerifikasiBerkasPengajuanMahasiswaSidang', compact('pengajuan', 'data', 'tidakBisaAjukan', 'adaBelumUpload'));
    }

    public function store_sidang(Request $request)
    {
        $idKota = Auth::user()->mahasiswa->id_kota;
        $pengajuan = VerifikasiBerkasPengajuan::where('id_kota', $idKota)
            ->where('jenis_pengajuan', 'sidang_akhir')
            ->first();
        if ($pengajuan) {
            // Jika pengajuan sudah ada, tidak perlu membuat yang baru
            $pengajuan->update([
                'tanggal_pengajuan' => Carbon::now(),
                'status_konfirmasi' => 'pending',
                'nip' => null,
                'tanggal_verifikasi' => null,
            ]);
        } else {
            VerifikasiBerkasPengajuan::create([
                'tanggal_pengajuan' => Carbon::now(),
                'status_konfirmasi' => 'pending',
                'jenis_pengajuan' => 'sidang_akhir',
                'id_kota' => Auth::user()->mahasiswa->id_kota,
            ]);
        }

        // Ke Koordinator TA
        try {
            if ($idKota) {
                $koordinatorTA = Dosen::where('role_dosen', 'koordinator_ta')->pluck('id_kota');
                foreach($koordinatorTA as $nip){
                    if ($nip) {
                        $nip->notify(new TestEmailNotification(
                            'Perubahan Status Dokumen Tugas Akhir!',
                            [
                                'tanggal_pengajuan' => Carbon::now(),
                                'status_konfirmasi' => 'pending',
                                'jenis_pengajuan' => 'sidang_akhir',
                                'id_kota' => Auth::user()->mahasiswa->id_kota,
                            ]
                        ));
                    }
                }
            }
        } catch (\Exception $notifEx) {
            \Log::error('Gagal mengirim notifikasi pemberian feedback: ' . $notifEx->getMessage(), [
                'id_kota' => $idKota,
                'nip' => $nip
            ]);
        }
        return redirect()->route('verifikasi-sidang.create')->with('success', 'Pengajuan berhasil diajukan.');
    } //nambah
}