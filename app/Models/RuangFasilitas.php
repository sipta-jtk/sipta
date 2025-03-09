<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangFasilitas extends Model
{
    use HasFactory;

    protected $table = 'ruang_fasilitas';
    
    // Jika menggunakan single primary key
    protected $primaryKey = 'id'; // Asumsi ada kolom id sebagai primary key
    public $timestamps = false;
    
    protected $fillable = [
        'id_ruangan',
        'id_fasilitas',
        'jumlah_fasilitas'
    ];

    // Relasi ke model Ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    // Relasi ke model Fasilitas
    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }
}
