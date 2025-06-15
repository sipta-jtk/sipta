<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferensiKota extends Model
{
    protected $table = 'preferensi_kota';
    
    // Untuk composite key, kita disable primary key Laravel dan handle manual
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nip',
        'id_kota',
        'status'
    ];

    // Override getKey() untuk composite key
    public function getKey()
    {
        return [$this->nip, $this->id_kota];
    }

    // Override getKeyName() 
    public function getKeyName()
    {
        return ['nip', 'id_kota'];
    }

    // Method untuk update yang aman dengan composite key
    public static function updateOrCreate($attributes, $values = [])
    {
        // Gabungkan attributes dan values
        $allAttributes = array_merge($attributes, $values);
        
        // Gunakan raw query untuk UPSERT yang aman
        return \DB::statement(
            'INSERT INTO preferensi_kota (nip, id_kota, status) VALUES (?, ?, ?) 
             ON DUPLICATE KEY UPDATE status = VALUES(status)',
            [
                $allAttributes['nip'],
                $allAttributes['id_kota'],
                $allAttributes['status']
            ]
        );
    }

    // Override create untuk tetap support seeder dan operasi normal
    public static function create(array $attributes = [])
    {
        // Validasi required fields
        if (!isset($attributes['nip']) || !isset($attributes['id_kota'])) {
            throw new \InvalidArgumentException('PreferensiKota create() requires both nip and id_kota');
        }

        // Untuk operasi normal (seperti seeder), gunakan insert biasa
        $instance = new static();
        $instance->fill($attributes);
        
        // Gunakan raw insert untuk menghindari masalah primary key
        $result = \DB::insert(
            'INSERT INTO preferensi_kota (nip, id_kota, status) VALUES (?, ?, ?)',
            [
                $attributes['nip'],
                $attributes['id_kota'],
                $attributes['status'] ?? 0
            ]
        );
        
        if ($result) {
            // Return instance yang sudah diisi untuk compatibility
            $instance->exists = true;
            return $instance;
        }
        
        return false;
    }

    // Override save untuk handle update yang aman
    public function save(array $options = [])
    {
        // Jika model sudah exists, gunakan update method yang aman
        if ($this->exists) {
            if (!$this->nip || !$this->id_kota) {
                throw new \InvalidArgumentException('Cannot update PreferensiKota without nip and id_kota');
            }
            
            $dirty = $this->getDirty();
            if (empty($dirty)) {
                return true; // No changes to save
            }
            
            // Update hanya field yang berubah
            $affected = static::updateStatus($this->nip, $this->id_kota, $this->status);
            
            if ($affected > 0) {
                $this->syncChanges();
                return true;
            }
            return false;
        }
        
        // Untuk model baru, gunakan insert
        return static::create($this->attributes) !== false;
    }

    // Method untuk update status yang spesifik
    public static function updateStatus($nip, $id_kota, $status)
    {
        return \DB::update(
            'UPDATE preferensi_kota SET status = ? WHERE nip = ? AND id_kota = ?',
            [$status, $nip, $id_kota]
        );
    }

    // Method untuk cek apakah record exists
    public static function existsRecord($nip, $id_kota)
    {
        return \DB::selectOne(
            'SELECT 1 FROM preferensi_kota WHERE nip = ? AND id_kota = ? LIMIT 1',
            [$nip, $id_kota]
        ) !== null;
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id_kota');
    }
}
