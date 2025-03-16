<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRubrik extends Model
{    
    protected $table = 'detail_rubrik';
    protected $primaryKey = 'id_detail_rubrik';

    public $timestamps = false;
    
    protected $fillable = [
        'id_rubrik',
        'detail_rubrik_penilaian',
        'id_nilai',
    ];

    public function rubrik()
    {
        return $this->belongsTo(Rubrik::class, 'id_rubrik', 'id_rubrik');
    }

    public function nilai()
    {
        return $this->belongsTo(RentangNilai::class, 'id_nilai', 'id_nilai');
    }
}
