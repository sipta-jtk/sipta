<?php

namespace App\Modules\NotificationAndReminder\helper;

class PlaceholderHelper
{
    // Ambil path absolut file JSON placeholder
    private static function getJsonPath(): string
    {
        return __DIR__ . '/placeholders.json';
    }

    // Ambil data placeholder berdasar judul template
    public static function get(string $templateJudul): array
    {
        $path = self::getJsonPath();

        if (!file_exists($path)) {
            return [];
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);

        return $data[$templateJudul] ?? [];
    }

    // Simpan/update placeholder ke file JSON
    public static function save(string $templateJudul, array $placeholders): void
    {
        $path = self::getJsonPath();

        $data = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        $data[$templateJudul] = $placeholders;

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
