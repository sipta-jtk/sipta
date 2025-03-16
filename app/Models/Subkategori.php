<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subkategori extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'subkategori';
    protected $primaryKey = 'id_subkategori';

    protected $fillable = [
        'nama_subkategori'
    ];

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_subkategori', 'id_subkategori');
    }
}