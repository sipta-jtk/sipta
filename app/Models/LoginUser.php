<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginUser extends Model
{
    protected $table = 'log_login';
    protected $primaryKey = 'id_log';
    public $timestamps = false; // Karena kita pakai kolom waktu_aktivitas manual

    protected $fillable = [
        'username',
        'ip_address',
        'waktu_aktivitas',
        'status',
        'waktu_logout'
    ];
 
    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    // Method untuk mengubah status menjadi offline saat logout
    public function markAsOffline()
    {
        $this->status = 'offline';
        $this->waktu_logout = now();
        $this->save();
    }

    // Accessor untuk status aktif yang lebih detail
    public function getStatusAktifAttribute()
    {
        if ($this->status === 'online') {
            return 'Online';
        }

        if ($this->waktu_logout) {
            $diff = Carbon::parse($this->waktu_logout)->diffInMinutes(now());
            
            if ($diff < 60) {
                return "Logout {$diff} menit yang lalu";
            } elseif ($diff < 1440) {
                $hours = floor($diff / 60);
                return "Logout {$hours} jam yang lalu";
            } else {
                $days = floor($diff / 1440);
                return "Logout {$days} hari yang lalu";
            }
        }

        return 'Tidak diketahui';
    }

    // Accessor untuk durasi login
    public function getDurasiLoginAttribute()
    {
        $endTime = $this->waktu_logout ?? now();
        $startTime = Carbon::parse($this->waktu_aktivitas);
        
        $diffInMinutes = $startTime->diffInMinutes($endTime);
        
        if ($this->status === 'online') {
            return 'Sedang Online (' . $this->formatDuration($diffInMinutes) . ')';
        }
        
        return $this->formatDuration($diffInMinutes);
    }

    private function formatDuration($diffInMinutes)
    {
        if ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit';
        } elseif ($diffInMinutes < 1440) {
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;
            return $hours . ' jam ' . $minutes . ' menit';
        } else {
            $days = floor($diffInMinutes / 1440);
            $hours = floor(($diffInMinutes % 1440) / 60);
            return $days . ' hari ' . $hours . ' jam';
        }
    }
}
