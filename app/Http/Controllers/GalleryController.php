<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $photos = $request->user()->photos()->orderByDesc('created_at')->paginate(24);

        return view('gallery.index', compact('photos'));
    }

    public function create(): View
    {
        return view('gallery.create');
    }

    /**
     * Upload satu atau beberapa foto sekaligus.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'foto' => ['required', 'array', 'min:1'],
            'foto.*' => ['image', 'max:10240'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($request->file('foto') as $file) {
            $path = $file->store('galeri', 'public');

            $request->user()->photos()->create([
                'path' => $path,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('gallery.index')
            ->with('status', count($request->file('foto')).' foto berhasil diupload.');
    }

    public function destroy(Request $request, Photo $photo): RedirectResponse
    {
        abort_unless($photo->user_id === $request->user()->id, 403);

        \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return redirect()->route('gallery.index')->with('status', 'Foto dihapus.');
    }
}
