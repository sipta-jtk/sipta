<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlokasiDosen extends Model
{
    protected $table = 'alokasi_dosen';
    protected $primaryKey = 'id_alokasi';

    public $timestamps = false;

    protected $fillable = [
        'id_pengajuan_pembimbing',
        'nip',
        'urutan_prioritas_terpilih',
        'status_alokasi',
        'catatan',
        'tipe_alokasi'
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
