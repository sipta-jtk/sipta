<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListJurnalPlagiarisme extends Model
{
    use HasFactory;

    protected $table = 'list_jurnal_plagiarisme'; // Nama tabel

    protected $primaryKey = 'id_jurnal'; // Primary key

    public $timestamps = false; // Tidak menggunakan created_at & updated_at

    protected $fillable = [
        'link_jurnal',
        'judul',
        'persentase_kemunculan'
    ];
}
