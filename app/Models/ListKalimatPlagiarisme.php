<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListKalimatPlagiarisme extends Model
{
    protected $table = 'list_kalimat_plagiarisme'; 
    protected $primaryKey = 'id_kalimat'; 

    public $timestamps = false; 

    protected $fillable = [
        'id_dokumen',
        'id_jurnal',
        'kalimat_plagiat'
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen', 'id_dokumen');
    }

    public function listJurnalPlagiarisme()
    {
        return $this->belongsTo(ListJurnalPlagiarisme::class, 'id_jurnal', 'id_jurnal');
    }
}
