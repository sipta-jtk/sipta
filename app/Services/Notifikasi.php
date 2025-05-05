<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;

class Notifikasi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'notifikasi';
    }
}
