<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'judul',
        'persentase_plagiarisme',
        'highlight_dokumen',
        'status_plagiarisme',
        'id_ambang_batas',
        'review',
        'kategori',
        'deskripsi',
        'versi',
        'ukuran_file',
        'notes',
        'id_kota',
        'id_subkategori',
        'username',
        'status_berkas',
        'created_at',
        'updated_at'
    ];

    public function ambangBatas()
    {
        return $this->belongsTo(AmbangBatas::class, 'id_ambang_batas', 'id_ambang_batas');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function subkategori()
    {
        return $this->belongsTo(Subkategori::class, 'id_subkategori', 'id_subkategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function listKalimatPlagiarisme()
    {
        return $this->hasMany(ListKalimatPlagiarisme::class, 'id_dokumen', 'id_dokumen');
    }

    public function mahasiswaDosenDokumen()
    {
        return $this->hasMany(MahasiswaDosenDokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function reviewDosenPembimbing()
    {
        return $this->hasMany(ReviewDosenPembimbing::class, 'id_dokumen', 'id_dokumen');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_dokumen', 'id_dokumen');
    }
}
