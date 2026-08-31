<?php

namespace App\Http\Controllers;

use App\Models\Workplace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkplaceController extends Controller
{
    /**
     * Daftar semua tempat kerja yang diikuti user (termasuk "Pribadi" default).
     */
    public function index(Request $request): View
    {
        $workplaces = $request->user()->workplaces()
            ->withCount('projects')
            ->orderByDesc('is_default')
            ->orderBy('nama')
            ->get();

        return view('workplaces.index', compact('workplaces'));
    }

    public function create(): View
    {
        return view('workplaces.create');
    }

    /**
     * Tambah tempat kerja formal baru, langsung diikutkan ke user yang bikin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
            'jabatan' => ['nullable', 'string', 'max:255'],
        ]);

        $workplace = Workplace::create([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'type' => 'formal',
            'is_default' => false,
        ]);

        $request->user()->workplaces()->attach($workplace->id, [
            'jabatan' => $validated['jabatan'] ?? null,
            'tanggal_gabung' => now(),
        ]);

        return redirect()->route('workplaces.index')
            ->with('status', 'Tempat kerja "'.$workplace->nama.'" berhasil ditambahkan.');
    }
}
