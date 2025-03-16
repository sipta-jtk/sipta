<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListKalimatPlagiarisme extends Model
{
    protected $table = 'list_kalimat_plagiarisme'; // Nama tabel

    protected $primaryKey = 'id_kalimat'; // Primary key

    public $timestamps = false; // Tidak menggunakan created_at & update_at

    protected $fillable = [
        'id_dokumen',
        'id_jurnal',
        'kalimat_plagiat'
    ];
}
