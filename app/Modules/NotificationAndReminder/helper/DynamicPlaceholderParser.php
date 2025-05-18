<?php

namespace App\Modules\NotificationAndReminder\helper;

use Carbon\Carbon;

class DynamicPlaceholderParser
{
    public static function parse(array $data): array
    {
        foreach ($data as $key => $value) {
            if (preg_match('/^\{(.*)\}$/', $value, $matches)) {
                $data[$key] = self::resolveDynamicValue($matches[1]);
            }
        }
        return $data;
    }

    private static function resolveDynamicValue(string $placeholder): string
    {
        $placeholder = trim($placeholder);

        if (strcasecmp($placeholder, 'Now') === 0) {
            return Carbon::now()->toDateTimeString();
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $placeholder)) {
            return Carbon::createFromFormat('d-m-Y', $placeholder)->toDateString();
        }

        if (preg_match('/^Today([+-])(\d+)$/i', $placeholder, $matches)) {
            $operator = $matches[1];
            $days = (int) $matches[2];
            $date = Carbon::today();
            return $operator === '+' ? $date->addDays($days)->toDateString() : $date->subDays($days)->toDateString();
        }

        if (preg_match('/^(\d+)(Days|Hours|Minutes)$/i', $placeholder, $matches)) {
            $amount = (int) $matches[1];
            $unit = strtolower($matches[2]);
            return match ($unit) {
                'days' => Carbon::now()->addDays($amount)->toDateString(),
                'hours' => Carbon::now()->addHours($amount)->toDateTimeString(),
                'minutes' => Carbon::now()->addMinutes($amount)->toDateTimeString(),
                default => $placeholder,
            };
        }

        return $placeholder;
    }
}
