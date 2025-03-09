<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';
    protected $primaryKey = 'kode_gedung';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
    ];
    
    // Relasi dengan model Ruangan
    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'kode_gedung', 'kode_gedung');
    }
}
