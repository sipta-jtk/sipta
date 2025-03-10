<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';

    public $timestamps = false;

    protected $fillable = [
        'judul',
        'persentase_plagiarisme',
        'highlight_dokumen',
        'status_plagiarisme',
        'review',
        'kategori',
        'deskripsi',
        'versi',
        'ukuran_file',
        'notes',
        'id_kota',
        'id_label',
        'id_subkategori',
        'username',
        'status_berkas',
        'uploaded_at',
        'updated_at'
    ];
}
