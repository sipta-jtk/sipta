<?php

namespace App\Modules\NotificationAndReminder\Jobs;

use App\Models\Penjadwalan;
use App\Models\User;
use App\Notifications\TestEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PenjadwalanReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $today = Carbon::today();
            $hMinus1 = Carbon::today()->addDays(1);
            $hMinus3 = Carbon::today()->addDays(3);
            $hMinus7 = Carbon::today()->addDays(7);

            // Notifikasi untuk jadwal yang akan dilaksanakan hari ini
            $jadwalToday = Penjadwalan::whereDate('tanggal', $today)
                ->where('status', 'aktif')
                ->with('kota')
                ->get();

            foreach ($jadwalToday as $jadwal) {
                // Get participants through kehadiran relation
                $participants = User::whereHas('kehadiran', function($query) use ($jadwal) {
                    $query->where('id_penjadwalan', $jadwal->id_penjadwalan);
                })->get();

                foreach ($participants as $user) {
                    try {
                        $user->notify(new TestEmailNotification(
                            '[Pemberitahuan] ' . $jadwal->agenda . ' Dilaksanakan Hari Ini',
                            [
                                'agenda' => $jadwal->agenda,
                                'nama_ruangan' => $jadwal->nama_ruangan,
                                'id_ruangan' => $jadwal->id_ruangan,
                                'waktu' => $jadwal->sesi,
                                'tanggal' => Carbon::parse($jadwal->tanggal)->format('d F Y'),
                                'kota' => $jadwal->kota->nama_kota ?? '-'
                            ]
                        ));
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi jadwal hari ini: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'jadwal_id' => $jadwal->id_penjadwalan
                        ]);
                    }
                }
            }

            // Notifikasi H-1
            $jadwalHMinus1 = Penjadwalan::whereDate('tanggal', $hMinus1)
                ->where('status', 'aktif')
                ->with('kota')
                ->get();

            foreach ($jadwalHMinus1 as $jadwal) {
                $participants = User::whereHas('kehadiran', function($query) use ($jadwal) {
                    $query->where('id_penjadwalan', $jadwal->id_penjadwalan);
                })->get();

                foreach ($participants as $user) {
                    try {
                        $user->notify(new TestEmailNotification(
                            '[Reminder] ' . $jadwal->agenda . ' Akan Dilaksanakan Besok',
                            [
                                'agenda' => $jadwal->agenda,
                                'nama_ruangan' => $jadwal->nama_ruangan,
                                'id_ruangan' => $jadwal->id_ruangan,
                                'waktu' => $jadwal->sesi,
                                'tanggal' => Carbon::parse($jadwal->tanggal)->format('d F Y'),
                                'kota' => $jadwal->kota->nama_kota ?? '-'
                            ]
                        ));
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-1: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'jadwal_id' => $jadwal->id_penjadwalan
                        ]);
                    }
                }
            }

            // Notifikasi H-3
            $jadwalHMinus3 = Penjadwalan::whereDate('tanggal', $hMinus3)
                ->where('status', 'aktif')
                ->with('kota')
                ->get();

            foreach ($jadwalHMinus3 as $jadwal) {
                $participants = User::whereHas('kehadiran', function($query) use ($jadwal) {
                    $query->where('id_penjadwalan', $jadwal->id_penjadwalan);
                })->get();

                foreach ($participants as $user) {
                    try {
                        $user->notify(new TestEmailNotification(
                            '[Reminder] ' . $jadwal->agenda . ' Akan Dilaksanakan dalam 3 Hari',
                            [
                                'agenda' => $jadwal->agenda,
                                'nama_ruangan' => $jadwal->nama_ruangan,
                                'id_ruangan' => $jadwal->id_ruangan,
                                'waktu' => $jadwal->sesi,
                                'tanggal' => Carbon::parse($jadwal->tanggal)->format('d F Y'),
                                'kota' => $jadwal->kota->nama_kota ?? '-'
                            ]
                        ));
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-3: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'jadwal_id' => $jadwal->id_penjadwalan
                        ]);
                    }
                }
            }

            // Notifikasi H-7
            $jadwalHMinus7 = Penjadwalan::whereDate('tanggal', $hMinus7)
                ->where('status', 'aktif')
                ->with('kota')
                ->get();

            foreach ($jadwalHMinus7 as $jadwal) {
                $participants = User::whereHas('kehadiran', function($query) use ($jadwal) {
                    $query->where('id_penjadwalan', $jadwal->id_penjadwalan);
                })->get();

                foreach ($participants as $user) {
                    try {
                        $user->notify(new TestEmailNotification(
                            '[Reminder] ' . $jadwal->agenda . ' Akan Dilaksanakan dalam 7 Hari',
                            [
                                'agenda' => $jadwal->agenda,
                                'nama_ruangan' => $jadwal->nama_ruangan,
                                'id_ruangan' => $jadwal->id_ruangan,
                                'waktu' => $jadwal->sesi,
                                'tanggal' => Carbon::parse($jadwal->tanggal)->format('d F Y'),
                                'kota' => $jadwal->kota->nama_kota ?? '-'
                            ]
                        ));
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-7: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'jadwal_id' => $jadwal->id_penjadwalan
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Gagal menjalankan PenjadwalanReminderJob: ' . $e->getMessage());
        }
    }
}
