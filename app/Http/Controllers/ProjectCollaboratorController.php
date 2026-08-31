<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectCollaboratorController extends Controller
{
    public function index(Request $request, Project $project): View
    {
        $this->authorizeMember($request, $project);

        $project->load('collaborators', 'workplace');
        $friends = $request->user()->friends();

        return view('projects.collaborators', compact('project', 'friends'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorizeMember($request, $project);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $friend = User::findOrFail($validated['user_id']);

        abort_unless($request->user()->isFriendsWith($friend), 403, 'Hanya teman yang bisa diundang jadi kolaborator.');

        $project->collaborators()->syncWithoutDetaching([
            $friend->id => ['role' => 'kolaborator'],
        ]);

        return redirect()->route('projects.collaborators.index', $project)
            ->with('status', $friend->name.' berhasil diundang jadi kolaborator project ini.');
    }

    public function destroy(Request $request, Project $project, User $user): RedirectResponse
    {
        $this->authorizeMember($request, $project);

        $project->collaborators()->detach($user->id);

        return redirect()->route('projects.collaborators.index', $project)
            ->with('status', 'Kolaborator berhasil dikeluarkan dari project.');
    }

    protected function authorizeMember(Request $request, Project $project): void
    {
        $isWorkplaceMember = $request->user()->workplaces()->where('workplaces.id', $project->workplace_id)->exists();
        $isCollaborator = $project->collaborators()->where('users.id', $request->user()->id)->exists();

        abort_unless($isWorkplaceMember || $isCollaborator, 403);
    }
}
