<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiBerkasPengajuan extends Model
{
    protected $table = 'verifikasi_berkas_pengajuan';
    protected $primaryKey = 'id_pengajuan';

    public $timestamps = false;
    
    protected $fillable = [
        'nip',
        'status_konfirmasi',
        'catatan',
        'tanggal_pengajuan',
        'tanggal_verifikasi',
        'jenis_pangajuan'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
