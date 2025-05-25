<?php

namespace App\Modules\NotificationAndReminder\Jobs;

use App\Models\FormPenilaian;
use App\Models\User;
use App\Services\Notifikasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class FormPenilaianReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $today = Carbon::today();
            $hMinus1 = Carbon::today()->addDays(1);
            $hMinus3 = Carbon::today()->addDays(3);
            $hMinus7 = Carbon::today()->addDays(7);

            // Notifikasi untuk form penilaian yang deadline hari ini
            $formsToday = FormPenilaian::whereDate('tanggal_tenggat_pengisian', $today)->get();
            foreach ($formsToday as $form) {
                $users = User::where('role_user', 'dosen')->get();
                foreach ($users as $user) {
                    try {
                        Notifikasi::kirim(
                            '[Pemberitahuan] Deadline Form Penilaian Hari Ini',
                            $user->id,
                            [
                                'nama_form' => $form->nama_fta,
                                'kode_form' => $form->kode_fta,
                                'deadline' => $form->tanggal_tenggat_pengisian
                            ]
                        );
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi deadline hari ini: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'form_id' => $form->id_form_penilaian
                        ]);
                    }
                }
            }

            // Notifikasi untuk form penilaian yang deadline H-1
            $formsHMinus1 = FormPenilaian::whereDate('tanggal_tenggat_pengisian', $hMinus1)->get();
            foreach ($formsHMinus1 as $form) {
                $users = User::where('role_user', 'dosen')->get();
                foreach ($users as $user) {
                    try {
                        Notifikasi::kirim(
                            '[Reminder] 1 Hari Menuju Deadline Form Penilaian',
                            $user->id,
                            [
                                'nama_form' => $form->nama_fta,
                                'kode_form' => $form->kode_fta,
                                'deadline' => $form->tanggal_tenggat_pengisian
                            ]
                        );
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-1: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'form_id' => $form->id_form_penilaian
                        ]);
                    }
                }
            }

            // Notifikasi untuk form penilaian yang deadline H-3
            $formsHMinus3 = FormPenilaian::whereDate('tanggal_tenggat_pengisian', $hMinus3)->get();
            foreach ($formsHMinus3 as $form) {
                $users = User::where('role_user', 'dosen')->get();
                foreach ($users as $user) {
                    try {
                        Notifikasi::kirim(
                            '[Reminder] 3 Hari Menuju Deadline Form Penilaian',
                            $user->id,
                            [
                                'nama_form' => $form->nama_fta,
                                'kode_form' => $form->kode_fta,
                                'deadline' => $form->tanggal_tenggat_pengisian
                            ]
                        );
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-3: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'form_id' => $form->id_form_penilaian
                        ]);
                    }
                }
            }

            // Notifikasi untuk form penilaian yang deadline H-7
            $formsHMinus7 = FormPenilaian::whereDate('tanggal_tenggat_pengisian', $hMinus7)->get();
            foreach ($formsHMinus7 as $form) {
                $users = User::where('role_user', 'dosen')->get();
                foreach ($users as $user) {
                    try {
                        Notifikasi::kirim(
                            '[Reminder] 7 Hari Menuju Deadline Form Penilaian',
                            $user->id,
                            [
                                'nama_form' => $form->nama_fta,
                                'kode_form' => $form->kode_fta,
                                'deadline' => $form->tanggal_tenggat_pengisian
                            ]
                        );
                    } catch (\Exception $notifEx) {
                        \Log::error('Gagal mengirim notifikasi H-7: ' . $notifEx->getMessage(), [
                            'user_id' => $user->id,
                            'form_id' => $form->id_form_penilaian
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Gagal menjalankan FormPenilaianReminderJob: ' . $e->getMessage());
        }
    }
}
