<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_reset';
    protected $primaryKey = 'email';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'updated_at'
    ];
}
