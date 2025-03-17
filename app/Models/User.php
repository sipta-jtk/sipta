<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{     
    use Notifiable, HasRoles;

    protected $table = 'user';
    protected $primaryKey = 'username';

    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'role_user',
        'no_whatsapp',
        'photo'
    ];

    protected $hidden = [
        'password'
    ];

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'nip', 'username');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'nim', 'username');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'username', 'username');
    }

    public function notifikasiKirim()
    {
        return $this->hasMany(NotifikasiKirim::class, 'username', 'username');
    }

    public function preferensiNotifikasi()
    {
        return $this->hasMany(PreferensiNotifikasi::class, 'username', 'username');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'username', 'username');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'username', 'username');
    }

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return 'I\'m a nice guy';
    }

    public function adminlte_profile_url()
    {
        return 'profile';
    }
}
