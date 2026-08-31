<x-app-layout>
    <x-slot name="header">{{ $workplace->nama }}</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-1">
            <a href="{{ route('workplaces.projects.create', $workplace) }}" class="text-sm font-medium text-white bg-[#3E5C4E] px-4 py-2 rounded-lg">+ Tambah</a>
        </div>
        <p class="text-xs text-[#6E675A] mb-3">
            <a href="{{ route('workplaces.index') }}" class="hover:underline">Tempat Kerja</a> / {{ $workplace->nama }}
        </p>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($projects as $project)
                <div class="bg-white border border-[#EAE4D6] rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-[#2A2621] text-sm">{{ $project->nama }}</h3>
                        <span @class([
                            'text-[10px] px-2 py-0.5 rounded-full',
                            'bg-[#EFEBE1] text-[#6E675A]' => $project->status === 'planning',
                            'bg-[#E3EBE6] text-[#3E5C4E]' => $project->status === 'berjalan',
                            'bg-[#E4EFE0] text-[#4B7A3A]' => $project->status === 'selesai',
                        ])>{{ ucfirst($project->status) }}</span>
                    </div>
                    @if ($project->planning)
                        <p class="text-xs text-[#6E675A] mt-2"><span class="font-medium">Planning:</span> {{ $project->planning }}</p>
                    @endif
                    @if ($project->target)
                        <p class="text-xs text-[#6E675A] mt-1"><span class="font-medium">Target:</span> {{ $project->target }}</p>
                    @endif
                </div>
            @empty
                <p class="text-sm text-[#6E675A] text-center py-8">Belum ada project di tempat kerja ini.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
