<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Lab404\Impersonate\Models\Impersonate;


class User extends Authenticatable
{     
    use Notifiable, HasRoles, HasApiTokens, Impersonate;

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
        'photo',
        'status_user'
    ];

    protected $hidden = [
        'password'
    ];

    public function getRememberToken()
    {
        return null; // Jangan kembalikan apapun
    }

    public function setRememberToken($value)
    {
        // Tidak perlu melakukan apapun
    }

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
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            $prefix = env('PREFIX_URL');
            
            if (env('APP_ENV') == 'local') {
                return Storage::url($this->photo);
            }else{
                return $prefix . Storage::url($this->photo);
            }
        }

        return asset('default/default-profile.jpg');
    }

    public function adminlte_desc()
    {
        return $this->nama . ' - ' . ucfirst($this->role_user);
    }

    public function adminlte_profile_url()
    {
        return 'profile';
    }

        public function canImpersonate(): bool
    {
        return $this->role_user === 'admin';
    }

    public function canBeImpersonated(): bool
    {
        return $this->role_user !== 'admin';
    }
}
