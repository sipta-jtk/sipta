<?php

namespace App\Modules\NotificationAndReminder\Controllers\PlaceholderSettings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlaceholderController extends Controller
{
    public function index()
    {
        $path = base_path('app/Modules/NotificationAndReminder/helper/placeholders.json');
        $json = file_get_contents($path);
        $placeholders = json_decode($json, true);

        $data = [];
        foreach ($placeholders as $judul => $place) {
            $data[] = [
                'judul' => $judul,
                'placeholders' => $place,
            ];
        }

        return view('NotificationAndReminder::PlaceholderSettings.index', ['placeholders' => $data]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'judul_notifikasi' => 'required|string',
            'placeholders' => 'required|json',
        ]);

        $path = base_path('app/Modules/NotificationAndReminder/helper/placeholders.json');
        $currentData = json_decode(file_get_contents($path), true);

        $judul = $request->input('judul_notifikasi');
        $placeholders = json_decode($request->input('placeholders'), true);

        $currentData[$judul] = $placeholders;

        file_put_contents($path, json_encode($currentData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->back()->with('success', 'Placeholder berhasil diperbarui.');
    }
}
