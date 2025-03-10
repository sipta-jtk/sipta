<?php

namespace App\Modules\PenjadwalanRuangan\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Penjadwalan;

class PenjadwalanRuanganController extends Controller
{
    public function getEvent()
    {
        $sesiMapping = [
            1 => ['start' => '07:00', 'end' => '09:00'],
            2 => ['start' => '09:00', 'end' => '11:00'],
            3 => ['start' => '13:00', 'end' => '15:00'],
            4 => ['start' => '15:00', 'end' => '17:00'],
        ];

        $events = DB::table('penjadwalan')
            ->select('id_penjadwalan', 'sesi', 'agenda', 'id_ruangan', 'tanggal', 'id_kota', 'nip')
            ->get()
            ->map(function ($event) use ($sesiMapping) {
                $event->tanggal = \Carbon\Carbon::parse($event->tanggal)
                    ->locale('id')
                    ->translatedFormat('l, d F Y');

                // Ambil start dan end berdasarkan sesi
                if (isset($sesiMapping[$event->sesi])) {
                    $event->start = \Carbon\Carbon::parse($event->tanggal . ' ' . $sesiMapping[$event->sesi]['start'])
                        ->toIso8601String();
                    $event->end = \Carbon\Carbon::parse($event->tanggal . ' ' . $sesiMapping[$event->sesi]['end'])
                        ->toIso8601String();
                } else {
                    $event->start = null;
                    $event->end = null;
                }

                $event->resourceId = $event->id_ruangan;
                $event->title = $event->agenda;
                return $event;
            });

        return response()->json($events);
    }


    public function action(Request $request)
    {
        if ($request->ajax()) {
            if ($request->type == 'add') {
                $event = Event::create([
                    'sesi' => $request->sesi,
                    'agenda' => $request->title,
                    'id_ruangan' => $request->id_ruangan,
                    'tanggal' => $request->tanggal,
                    'id_kota' => $request->id_kota,
                    'nip' => $request->nip
                ]);

                return response()->json($event);
            }

            if ($request->type == 'update') {
                $event = Event::find($request->id);
                if ($event) {
                    $event->update([
                        'sesi' => $request->sesi,
                        'agenda' => $request->title,
                        'id_ruangan' => $request->id_ruangan,
                        'tanggal' => $request->tanggal,
                        'id_kota',
                        'nip'
                    ]);
                    return response()->json($event);
                }
            }

            if ($request->type == 'delete') {
                $event = Event::find($request->id);
                if ($event) {
                    $event->delete();
                    return response()->json(['message' => 'Event deleted successfully']);
                }
            }
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}