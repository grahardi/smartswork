<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileSetupController extends Controller
{
    /**
     * Alias lama - sekarang diarahkan ke halaman Akun gabungan
     * (Data Diri + Info Akun + Password jadi satu halaman).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('profile.edit');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'foto_profil' => ['nullable', 'image', 'max:10240'],
        ]);

        $path = null;
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profil', 'public');
        }

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'nama_lengkap' => $validated['nama_lengkap'],
                'no_hp' => $validated['no_hp'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'foto_profil' => $path ?? $request->user()->profile?->foto_profil,
            ]
        );

        // Lanjut ke halaman akun gabungan (Data Diri + Info Akun + Password).
        return redirect()->route('profile.edit')->with('status', 'Data diri berhasil disimpan.');
    }
}
