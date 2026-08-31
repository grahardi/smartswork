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
        // Project dari workplace yang diikuti user, ATAU project tempat dia jadi kolaborator (cowork).
        $projects = Project::where(function ($q) use ($request) {
                $q->whereHas('workplace.users', fn ($q2) => $q2->where('users.id', $request->user()->id))
                    ->orWhereHas('collaborators', fn ($q2) => $q2->where('users.id', $request->user()->id));
            })
            ->with('workplace')
            ->orderBy('nama')
            ->get();

        $galleryPhotos = $request->user()->photos()->orderByDesc('created_at')->limit(20)->get();

        return view('daily-actions.create', compact('projects', 'galleryPhotos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'tanggal' => ['required', 'date'],
            'waktu' => ['nullable', 'date_format:H:i'],
            'foto' => ['nullable', 'image', 'max:10240'],
            'gallery_photo_id' => ['nullable', 'exists:photos,id'],
            'keterangan' => ['required', 'string'],
        ]);

        // Pastikan project yang dipilih memang bisa diakses user (anggota workplace atau kolaborator).
        $project = Project::where(function ($q) use ($request) {
                $q->whereHas('workplace.users', fn ($q2) => $q2->where('users.id', $request->user()->id))
                    ->orWhereHas('collaborators', fn ($q2) => $q2->where('users.id', $request->user()->id));
            })
            ->findOrFail($validated['project_id']);

        $path = null;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('aksi-harian', 'public');
        } elseif (! empty($validated['gallery_photo_id'])) {
            // Ambil path dari foto galeri milik user (bukan file baru, referensi saja).
            $galleryPhoto = $request->user()->photos()->findOrFail($validated['gallery_photo_id']);
            $path = $galleryPhoto->path;
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
