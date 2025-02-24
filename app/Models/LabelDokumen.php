<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelDokumen extends Model
{
    use HasFactory;

    protected $table = 'label_dokumen'; // Nama tabel

    protected $primaryKey = 'id_label_dokumen'; // Primary key

    public $timestamps = false; // Tidak menggunakan created_at & updated_at

    protected $fillable = [
        'id_dokumen',
        'id_label'
    ];
}
