<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AspekFeedback extends Model
{    
    protected $table = 'aspek_feedback';
    protected $primaryKey = 'id_feedback';

    public $timestamps = false;
    
    protected $fillable = [
        'id_feedback',
        'id_fta',
        'nama_aspek_feedback',
    ];

    public function formPenilaian()
    {
        return $this->belongsTo(FormPenilaian::class, 'id_fta', 'id_fta');
    }

    public function detailFeedback()
    {
        return $this->hasMany(DetailFeedback::class, 'id_feedback', 'id_feedback');
    }
}
