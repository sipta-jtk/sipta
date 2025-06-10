<?php

namespace App\Modules\UserManagement\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User; 

class ForgotPasswordController extends Controller
{
    /**
     * Mengirim link reset password ke email pengguna.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi email
        $request->validate(['email' => 'required|email']);

        // 2. Cari pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Jika pengguna tidak ditemukan
        if (!$user) {
            return back()->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }

        // 4. Buat token reset password secara manual
        $token = Password::broker()->createToken($user);
        
        // 5. Buat URL lengkap untuk link reset password
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        // 6. Siapkan konten email
        $content = "<h1>Lupa Password Akun Anda?</h1>
                    <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
                    <p>Silakan klik tombol di bawah ini untuk mereset password Anda:</p>
                    <a href='{$resetUrl}' style='display: inline-block; background-color: black; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Reset Password</a>
                    <br><br>
                    <p>Link reset password ini akan kedaluwarsa dalam 60 menit.</p>
                    <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini.</p>";

        // 7. Kirim email menggunakan view 'emails.template_general'
        try {
            Mail::send('emails.template_general', ['content' => $content], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Notifikasi Reset Password');
            });
        } catch (\Exception $e) {
            // Jika email gagal dikirim, kembalikan pesan error
            \Log::error($e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email. Periksa konfigurasi Anda.']);
        }
        
        // 8. Berikan pesan sukses
        return back()->with(['status' => __('We have e-mailed your password reset link!')]);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('UserManagement.views.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                    DB::transaction(function () use ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                });
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}