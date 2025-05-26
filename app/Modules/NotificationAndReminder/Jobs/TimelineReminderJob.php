<?php

namespace App\Modules\NotificationAndReminder\Jobs;

use App\Models\Timeline;
use App\Models\User;
use App\Services\Notifikasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class TimelineReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $today = Carbon::today();
        $hMinus3 = Carbon::today()->addDays(3);

        try {
            // Notifikasi untuk timeline yang selesai hari ini
            $timelinesToday = Timeline::whereDate('tanggal_selesai', $today)->get();
            foreach ($timelinesToday as $timeline) {
                $users = User::all();
                foreach ($users as $user) {
                    try {
                        Notifikasi::kirim(
                            '[Pemberitahuan] Kegiatan Timeline Selesai Hari Ini',
                            $user->id,
                            [
                                'nama_kegiatan' => $timeline->nama_kegiatan,
                                'tanggal_selesai' => $timeline->tanggal_selesai,
                                'deskripsi' => $timeline->deskripsi
                            ]
                        );
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi timeline selesai: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'timeline_id' => $timeline->id_timeline,
                            'kegiatan' => $timeline->nama_kegiatan
                        ]);
                    }
                }
            }

            // Notifikasi untuk timeline yang selesai H-3
            $timelinesHMinus3 = Timeline::whereDate('tanggal_selesai', $hMinus3)->get();
            foreach ($timelinesHMinus3 as $timeline) {
                $users = User::all();
                foreach ($users as $user) {
                    try {
                        Notifikasi::kirim(
                            '[Reminder] 3 Hari Menuju Selesai Kegiatan Timeline',
                            $user->id,
                            [
                                'nama_kegiatan' => $timeline->nama_kegiatan,
                                'tanggal_selesai' => $timeline->tanggal_selesai,
                                'deskripsi' => $timeline->deskripsi
                            ]
                        );
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-3 timeline: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'timeline_id' => $timeline->id_timeline,
                            'kegiatan' => $timeline->nama_kegiatan
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Gagal menjalankan TimelineReminderJob: ' . $e->getMessage());
        }
    }
}