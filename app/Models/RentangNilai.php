<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentangNilai extends Model
{
    protected $table = 'rentang_nilai';
    protected $primaryKey = 'id_nilai';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'batas_bawah',
        'batas_atas'
    ];

    public function detailRubrik()
    {
        return $this->hasMany(DetailRubrik::class, 'id_nilai', 'id_nilai');
    }
}
