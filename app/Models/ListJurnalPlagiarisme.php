<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListJurnalPlagiarisme extends Model
{
    protected $table = 'list_jurnal_plagiarisme'; 
    protected $primaryKey = 'id_jurnal'; 

    public $timestamps = false; 

    protected $fillable = [
        'link_jurnal',
        'judul',
        'persentase_kemunculan'
    ];

    public function listKalimatPlagiarisme()
    {
        return $this->hasMany(ListKalimatPlagiarisme::class, 'id_jurnal', 'id_jurnal');
    }
}
