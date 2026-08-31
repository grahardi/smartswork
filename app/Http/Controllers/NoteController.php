<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $notes = $request->user()->notes()
            ->orderByDesc('is_pinned')
            ->orderByDesc('updated_at')
            ->get();

        return view('notes.index', compact('notes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $request->user()->notes()->create($validated);

        return redirect()->route('notes.index');
    }

    public function edit(Request $request, Note $note): View
    {
        $this->authorizeOwner($request, $note);

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $this->authorizeOwner($request, $note);

        $validated = $this->validated($request);

        $note->update($validated);

        return redirect()->route('notes.index');
    }

    public function togglePin(Request $request, Note $note): RedirectResponse
    {
        $this->authorizeOwner($request, $note);

        $note->update(['is_pinned' => ! $note->is_pinned]);

        return redirect()->route('notes.index');
    }

    public function destroy(Request $request, Note $note): RedirectResponse
    {
        $this->authorizeOwner($request, $note);

        $note->delete();

        return redirect()->route('notes.index');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['nullable', 'string', 'max:255'],
            'isi' => ['nullable', 'string'],
            'warna' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
    }

    protected function authorizeOwner(Request $request, Note $note): void
    {
        abort_unless($note->user_id === $request->user()->id, 403);
    }
}
