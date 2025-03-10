<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPenilaian extends Model
{
    protected $table = 'form_penilaian';
    public $timestamps = false;
    protected $primaryKey = 'id_form_penilaian';
    protected $fillable = ['nama_formulir_penilaian', 'nip', 'tahun_ajaran', 'status_form', 'created_at', 'updated_at'];
}
