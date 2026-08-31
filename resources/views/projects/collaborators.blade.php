<x-app-layout>
    <x-slot name="header">Kolaborator — {{ $project->nama }}</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <p class="text-xs text-[#8A8377] mb-4">{{ $project->workplace->nama }}</p>

        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-5">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading mb-2">Undang Teman untuk Cowork</h3>
            @php
                $availableFriends = $friends->whereNotIn('id', $project->collaborators->pluck('id'));
            @endphp
            @if ($availableFriends->isEmpty())
                <p class="text-xs text-[#7B7F99]">
                    @if ($friends->isEmpty())
                        Kamu belum punya teman. <a href="{{ route('friends.index') }}" class="text-[#2563EB] underline">Tambah teman dulu</a>.
                    @else
                        Semua temanmu sudah jadi kolaborator di project ini.
                    @endif
                </p>
            @else
                <form method="POST" action="{{ route('projects.collaborators.store', $project) }}" class="flex gap-2">
                    @csrf
                    <select name="user_id" required class="flex-1 rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                        <option value="">Pilih teman</option>
                        @foreach ($availableFriends as $friend)
                            <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-[#2563EB] text-white text-sm px-4 py-2 rounded-lg">Undang</button>
                </form>
            @endif
        </div>

        <p class="text-xs font-medium text-[#8A8377] mb-2">KOLABORATOR ({{ $project->collaborators->count() }})</p>
        <div class="space-y-2">
            @forelse ($project->collaborators as $collaborator)
                <div class="bg-white border border-[#E7E9F5] rounded-xl px-4 py-3 flex items-center justify-between">
                    <div>
                        <span class="text-sm text-[#262135]">{{ $collaborator->name }}</span>
                        <span class="text-[10px] text-[#7B7F99] ml-2">{{ $collaborator->pivot->role }}</span>
                    </div>
                    <form method="POST" action="{{ route('projects.collaborators.destroy', [$project, $collaborator]) }}" onsubmit="return confirm('Keluarkan {{ $collaborator->name }} dari project ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-[#DC2626]">Keluarkan</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-6">Belum ada kolaborator.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
