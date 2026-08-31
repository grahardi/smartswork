<?php

namespace App\Http\Controllers;

use App\Models\DailyAction;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyActionController extends Controller
{
    /**
     * Timeline aksi harian milik user, terbaru dulu.
     */
    public function index(Request $request): View
    {
        $actions = $request->user()->dailyActions()
            ->with('project.workplace')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(15);

        return view('daily-actions.index', compact('actions'));
    }

    public function create(Request $request): View
    {
        // Hanya project dari workplace yang diikuti user.
        $projects = Project::whereHas('workplace.users', fn ($q) => $q->where('users.id', $request->user()->id))
            ->with('workplace')
            ->orderBy('nama')
            ->get();

        return view('daily-actions.create', compact('projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'tanggal' => ['required', 'date'],
            'waktu' => ['nullable', 'date_format:H:i'],
            'foto' => ['nullable', 'image', 'max:4096'],
            'keterangan' => ['required', 'string'],
        ]);

        // Pastikan project yang dipilih memang milik salah satu workplace user.
        $project = Project::whereHas('workplace.users', fn ($q) => $q->where('users.id', $request->user()->id))
            ->findOrFail($validated['project_id']);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('aksi-harian', 'public');
        }

        DailyAction::create([
            'user_id' => $request->user()->id,
            'project_id' => $project->id,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['waktu'] ?? null,
            'foto' => $path,
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('daily-actions.index')
            ->with('status', 'Aksi harian berhasil dicatat.');
    }
}
