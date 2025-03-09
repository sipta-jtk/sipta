<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';
    protected $primaryKey = 'id_fasililtas'; // Perhatikan typo di database
    
    protected $fillable = [
        'nama_fasilitas',
        'jumlah_total_fasilitas',
    ];
    
    // Relasi dengan model Ruangan melalui tabel pivot
    public function ruangan()
    {
        return $this->belongsToMany(Ruangan::class, 'ruang_fasilitas', 'id_fasilitas', 'id_ruangan')
                    ->withPivot('jumlah_fasilitas');
    }
}