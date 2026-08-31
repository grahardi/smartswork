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
        $validated = $this->validated($request);

        $workplace = Workplace::create([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'type' => 'formal',
            'is_default' => false,
        ]);

        $request->user()->workplaces()->syncWithoutDetaching([
            $workplace->id => [
                'jabatan' => $validated['jabatan'] ?? null,
                'tanggal_gabung' => now(),
            ],
        ]);

        return redirect()->route('workplaces.index')
            ->with('status', 'Tempat kerja "'.$workplace->nama.'" berhasil ditambahkan.');
    }

    public function edit(Request $request, Workplace $workplace): View
    {
        $this->authorizeMember($request, $workplace);

        $pivot = $request->user()->workplaces()->where('workplaces.id', $workplace->id)->first()->pivot;

        return view('workplaces.edit', compact('workplace', 'pivot'));
    }

    public function update(Request $request, Workplace $workplace): RedirectResponse
    {
        $this->authorizeMember($request, $workplace);

        $validated = $this->validated($request);

        $workplace->update([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $request->user()->workplaces()->syncWithoutDetaching([
            $workplace->id => ['jabatan' => $validated['jabatan'] ?? null],
        ]);

        return redirect()->route('workplaces.index')
            ->with('status', 'Tempat kerja "'.$workplace->nama.'" berhasil diperbarui.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }

    /**
     * Pastikan user memang tergabung di workplace ini.
     */
    protected function authorizeMember(Request $request, Workplace $workplace): void
    {
        abort_unless(
            $request->user()->workplaces()->where('workplaces.id', $workplace->id)->exists(),
            403,
            'Kamu tidak tergabung di tempat kerja ini.'
        );
    }
}
