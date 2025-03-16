<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';

    public $timestamps = false;

    protected $fillable = [
        'judul', // 1
        'persentase_plagiarisme',
        'highlight_dokumen',
        'status_plagiarisme',
        'review',
        'kategori', // 2
        'deskripsi', // 3
        'versi', // 4
        'file_path',
        'ukuran_file', // 5
        'kode_fta',
        'notes', // 6
        'id_kota',
        'id_label',
        'id_subkategori',
        'username',
        'status_berkas',
        'uploaded_at', // 7
        'updated_at' // 8
    ];
    
}
