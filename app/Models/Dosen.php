<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{    
    protected $table = 'dosen';
    protected $primaryKey = 'nip';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_kbk',
        'id_dosen',
        'kode_dosen',
        'status_dosen',
        'role_dosen',
        'bersedia_membimbing'
    ];

    public function penjadwalan()
    {
        return $this->hasMany(Penjadwalan::class, 'nip', 'nip');
    }

    public function kbk()
    {
        return $this->belongsTo(Kbk::class, 'id_kbk', 'id_kbk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'username');
    }

    public function kaprodi()
    {
        return $this->hasOne(Kaprodi::class, 'nip', 'nip');
    }

    public function jadwalDosenPembimbing()
    {
        return $this->hasMany(JadwalDosenPembimbing::class, 'nip', 'nip');
    }

    public function ketertarikanBidang()
    {
        return $this->hasMany(KetertarikanBidang::class, 'nip', 'nip');
    }

    public function prioritasPembimbing()
    {
        return $this->hasMany(PrioritasPembimbing::class, 'nip', 'nip');
    }

    public function alokasiPembimbing()
    {
        return $this->hasMany(AlokasiPembimbing::class, 'nip', 'nip');
    }

    public function kuotaMembimbing()
    {
        return $this->hasMany(KuotaMembimbing::class, 'nip', 'nip');
    }

    public function preferensiKota()
    {
        return $this->hasMany(PreferensiKota::class, 'nip', 'nip');
    }

    public function nilaiKategori()
    {
        return $this->hasMany(NilaiKategori::class, 'nip', 'nip');
    }

    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteria::class, 'nip', 'nip');
    }

    public function detailFeedback()
    {
        return $this->hasMany(DetailFeedback::class, 'nip', 'nip');
    }

    public function verifikasiBerkasPengajuan()
    {
        return $this->hasMany(VerifikasiBerkasPengajuan::class, 'nip', 'nip');
    }

    public function pengajuanJadwalKota()
    {
        return $this->hasMany(PengajuanJadwalKota::class, 'nip', 'nip');
    }

    public function ambangBatas()
    {
        return $this->hasMany(AmbangBatas::class, 'nip', 'nip');
    }

    public function mahasiswaDosenDokumen()
    {
        return $this->hasMany(MahasiswaDosenDokumen::class, 'nip', 'nip');
    }

    public function reviewDosenPembimbing()
    {
        return $this->hasMany(ReviewDosenPembimbing::class, 'nip', 'nip');
    }
}
