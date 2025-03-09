<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';
    protected $primaryKey = 'id_ruangan';
    public $timestamps = false;
    protected $keyType = 'integer';
    public $incrementing = true;
    
    protected $fillable = [
        'nama_ruangan',
        'kode_ruangan',
        'status_ruangan',
        'kode_gedung',
        'link_ruangan'
    ];

    // Relasi dengan model Gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class, 'kode_gedung', 'kode_gedung');
    }

    // Relasi dengan model Fasilitas melalui tabel pivot
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'ruang_fasilitas', 'id_ruangan', 'id_fasilitas')
                    ->withPivot('jumlah_fasilitas');
    }
}
