<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SetInitialPasswordController extends Controller
{
    /**
     * Untuk akun yang dibuat lewat Google (password_set = false) - buat
     * password sendiri TANPA perlu memasukkan password lama, karena
     * memang belum pernah tahu passwordnya (dulu di-generate random).
     */
    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user()->password_set, 422, 'Password sudah pernah diset, gunakan form Ubah Kata Sandi biasa.');

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_set' => true,
        ]);

        return redirect()->route('profile.edit')->with('status', 'Password berhasil dibuat. Sekarang kamu juga bisa login manual pakai email.');
    }
}
