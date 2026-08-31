<?php

namespace App\Http\Controllers;

use App\Models\Workplace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Daftar project di bawah satu workplace tertentu.
     */
    public function index(Request $request, Workplace $workplace): View
    {
        $this->authorizeMember($request, $workplace);

        $projects = $workplace->projects()->latest()->get();

        return view('projects.index', compact('workplace', 'projects'));
    }

    public function create(Request $request, Workplace $workplace): View
    {
        $this->authorizeMember($request, $workplace);

        return view('projects.create', compact('workplace'));
    }

    public function store(Request $request, Workplace $workplace): RedirectResponse
    {
        $this->authorizeMember($request, $workplace);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'planning' => ['nullable', 'string'],
            'target' => ['nullable', 'string'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', 'in:planning,berjalan,selesai'],
        ]);

        $workplace->projects()->create($validated);

        return redirect()->route('workplaces.projects.index', $workplace)
            ->with('status', 'Project "'.$validated['nama'].'" berhasil ditambahkan.');
    }

    /**
     * Pastikan user memang tergabung di workplace ini sebelum bisa
     * melihat/menambah project-nya.
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
