<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlokasiPembimbing extends Model
{
    protected $table = 'alokasi_pembimbing';
    protected $primaryKey = 'id_alokasi_pembimbing';

    public $timestamps = false;

    protected $fillable = [
        'id_pengajuan_pembimbing',
        'nip',
        'urutan_prioritas_terpilih',
        'status_alokasi',
        'catatan'
    ];

    public function pengajuanPembimbing()
    {
        return $this->belongsTo(PengajuanPembimbing::class, 'id_pengajuan_pembimbing', 'id_pengajuan_pembimbing');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
