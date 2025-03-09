<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FileHelper
{
    public static function uploadFile($file)
    {
        // Generate nama file dengan UUID
        $uuid = Str::uuid()->toString();
        $extension = $file->getClientOriginalExtension();
        $fileName = $uuid . '.' . $extension;
        
        // Upload file ke folder public/ruangan
        $file->move(public_path('ruangan'), $fileName);
        
        // Return nama file untuk disimpan di database
        return $fileName;
    }

    public static function deleteFile($fileName)
    {
        $path = public_path('ruangan/' . $fileName);
        if (file_exists($path)) {
            unlink($path);
        }
    }
} 