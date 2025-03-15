<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailFeedback extends Model
{    
    protected $table = 'detail_feedback';
    protected $primaryKey = 'id_detail_feedback';

    public $timestamps = false;
    
    protected $fillable = [
        'id_feedback',
        'id_kota',
        'nip',
        'status_penilaian',
        'isi_feedback'
    ];

    public function aspekFeedback()
    {
        return $this->belongsTo(AspekFeedback::class, 'id_feedback', 'id_feedback');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }
}
