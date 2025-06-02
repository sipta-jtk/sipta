<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    public $timestamps = false; // Karena kita pakai kolom waktu_aktivitas manual

    protected $fillable = [
        'id_pengguna',
        'username',
        'ip_address',
        'waktu_aktivitas'
    ];
 
    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    // Accessor untuk status aktif (menghitung dari waktu_aktivitas ke sekarang)
    public function getStatusAktifAttribute()
    {
        $diff = Carbon::parse($this->waktu_aktivitas)->diffInMinutes(now());

        if ($diff < 60) {
            return $diff . ' menit yang lalu';
        } elseif ($diff < 1440) {
            return round($diff / 60) . ' jam yang lalu';
        } else {
            return round($diff / 1440) . ' hari yang lalu';
        }
    }
}
