<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateNotifikasi extends Model
{
    protected $table = 'template_notifikasi';
    protected $primaryKey = 'id_template_notifikasi';

    public $timestamps = false;

    protected $fillable = [
        'judul_notifikasi',
        'isi_in_apps',
        'isi_in_email',
        'jenis_notifikasi',
    ];
}
