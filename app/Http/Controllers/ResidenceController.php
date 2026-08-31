<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResidenceController extends Controller
{
    public function index(Request $request): View
    {
        $residences = $request->user()->residences()
            ->orderByDesc('is_default')
            ->orderBy('label')
            ->get();

        return view('residences.index', compact('residences'));
    }

    public function create(): View
    {
        return view('residences.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $residence = $request->user()->residences()->create($validated);

        if ($validated['is_default'] ?? false) {
            $this->makeOnlyDefault($request, $residence);
        } elseif ($request->user()->residences()->count() === 1) {
            // Tempat tinggal pertama otomatis jadi utama.
            $residence->update(['is_default' => true]);
        }

        return redirect()->route('residences.index')
            ->with('status', 'Tempat tinggal "'.$residence->label.'" berhasil ditambahkan.');
    }

    public function edit(Request $request, Residence $residence): View
    {
        $this->authorizeOwner($request, $residence);

        return view('residences.edit', compact('residence'));
    }

    public function update(Request $request, Residence $residence): RedirectResponse
    {
        $this->authorizeOwner($request, $residence);

        $validated = $this->validated($request);

        $residence->update($validated);

        if ($validated['is_default'] ?? false) {
            $this->makeOnlyDefault($request, $residence);
        }

        return redirect()->route('residences.index')
            ->with('status', 'Tempat tinggal "'.$residence->label.'" berhasil diperbarui.');
    }

    public function destroy(Request $request, Residence $residence): RedirectResponse
    {
        $this->authorizeOwner($request, $residence);

        $wasDefault = $residence->is_default;
        $residence->delete();

        if ($wasDefault) {
            $next = $request->user()->residences()->first();
            $next?->update(['is_default' => true]);
        }

        return redirect()->route('residences.index')
            ->with('status', 'Tempat tinggal berhasil dihapus.');
    }

    public function makeDefault(Request $request, Residence $residence): RedirectResponse
    {
        $this->authorizeOwner($request, $residence);

        $this->makeOnlyDefault($request, $residence);

        return redirect()->route('residences.index')
            ->with('status', '"'.$residence->label.'" dijadikan tempat tinggal utama.');
    }

    protected function makeOnlyDefault(Request $request, Residence $residence): void
    {
        $request->user()->residences()->where('id', '!=', $residence->id)->update(['is_default' => false]);
        $residence->update(['is_default' => true]);
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_default' => ['nullable', 'boolean'],
        ]);
    }

    protected function authorizeOwner(Request $request, Residence $residence): void
    {
        abort_unless($residence->user_id === $request->user()->id, 403);
    }
}
