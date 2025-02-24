<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_kategori',
        'judul',
        'persentase_plagiarisme',
        'highlight_dokumen',
        'status_plagiarisme',
        'review',
        'deskripsi',
        'url_file',
        'versi',
        'ukuran_file',
        'id_kota',
        'status_berkas',
        'created_at',
        'updated_at'
    ];
}
