<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileSetupController extends Controller
{
    /**
     * Tampilkan form Data Diri (dipanggil sekali setelah registrasi,
     * tapi juga bisa dibuka lagi untuk edit).
     */
    public function create(Request $request): View
    {
        $profile = $request->user()->profile;

        return view('profile.create', compact('profile'));
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

        // Lanjut ke dashboard/daftar tempat kerja setelah Data Diri lengkap.
        return redirect()->route('dashboard')->with('status', 'Data diri berhasil disimpan.');
    }
}
