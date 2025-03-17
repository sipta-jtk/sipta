<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\User;
use App\Modules\Controller;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DaftarKesediaanMembimbingController extends Controller
{
    public function view_minatTopik(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Bidang');
    }
    public function view_daftarKesediaanMembimbing(): View
    {
        $prodiList = collect(DB::table('prodi')->pluck('nama_prodi')->toArray());
    
        $caseStatements = $prodiList->map(function ($prodi) {
            return "MAX(CASE WHEN km_grouped.nama_prodi = '$prodi' THEN km_grouped.total_jumlah ELSE 0 END) AS `$prodi`";
        })->implode(", ");
    
        $kesediaanStatements = $prodiList->map(function ($prodi) {
            return "CASE 
                        WHEN MAX(CASE WHEN km_grouped.nama_prodi = '$prodi' THEN km_grouped.total_jumlah ELSE 0 END) = 0 
                        THEN 'Tidak Bersedia' 
                        ELSE 'Bersedia' 
                    END AS `Kesediaan_$prodi`";
        })->implode(", ");
    
        $query = 
        "SELECT d.nip AS nip, 
            u.nama AS nama,
            d.kode_dosen AS kode_dosen,
            d.id_dosen AS id_dosen,
            k.kbk,
            CASE 
                WHEN d.bersedia_membimbing = 'bersedia' THEN 'Sudah' 
                WHEN d.bersedia_membimbing = 'tidak_bersedia' THEN 'Sudah'
                WHEN d.bersedia_membimbing = 'belum_konfirmasi' THEN 'Belum'
            END AS Status_Pengumpulan,
            COALESCE(GROUP_CONCAT(DISTINCT b.bidang SEPARATOR ', '), '-') AS bidang, 
            $caseStatements,
            $kesediaanStatements, 
            (SELECT SUM(km2.jumlah) FROM kuota_membimbing km2 WHERE km2.nip = d.nip) AS total_jumlah 
        FROM dosen d
        LEFT JOIN user u ON d.nip = u.username
        LEFT JOIN kbk k ON d.id_kbk = k.id_kbk
        LEFT JOIN ketertarikan_bidang kb ON d.nip = kb.nip
        LEFT JOIN bidang b ON kb.id_bidang = b.id_bidang
        LEFT JOIN (
      
            SELECT km.nip, p.nama_prodi, SUM(km.jumlah) AS total_jumlah
            FROM kuota_membimbing km
            JOIN prodi p ON km.id_prodi = p.id_prodi
            GROUP BY km.nip, p.nama_prodi
        ) AS km_grouped ON d.nip = km_grouped.nip
        GROUP BY d.nip, u.nama, d.kode_dosen, d.id_dosen, k.kbk, d.bersedia_membimbing";
    
        $result = DB::select($query);

        $formattedResult = json_decode(json_encode($result), true);
    
        return view('PengajuanAlokasiPembimbing.views.DaftarKesediaanMembimbing.DaftarKesediaanMembimbing', [
            'data' => $formattedResult,
            'prodiList' => $prodiList
        ]);
    }
    
}
