<?php

namespace App\Modules\UserManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('UserManagement.views.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:user,email,' . Auth::user()->username . ',username',
            'no_whatsapp' => 'nullable|regex:/^[0-9]+$/|min:10|max:15',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = Auth::user();
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_whatsapp = $request->no_whatsapp;

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        $prefix = env('PREFIX_URL', 'sipta');
        return redirect($prefix . '/profile')->with('success', 'Profil berhasil diperbarui');
    }
}
