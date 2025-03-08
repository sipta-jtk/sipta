<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{     
    use HasFactory, Notifiable;

    public $timestamps = false;
    
    protected $table = 'user';
    protected $primaryKey = 'username';

    protected $keyType = 'string';
    public $incrementing = false;

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
}
