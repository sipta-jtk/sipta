<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'template_notifikasi';
    protected $primaryKey = 'id_template_notifikasi';

    public $timestamps = false;

    protected $fillable = [
        'judul_notifikasi',
        'jenis_notifikasi',
        'isi_in_apps',
        'isi_in_email',
    ];
}
