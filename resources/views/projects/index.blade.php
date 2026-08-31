<x-app-layout>
    <x-slot name="header">{{ $workplace->nama }}</x-slot>

    <div class="px-4 py-5 relative">
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

        <a href="{{ route('workplaces.projects.create', $workplace) }}"
           class="fixed bottom-24 right-5 w-14 h-14 rounded-full bg-[#3E5C4E] text-white flex items-center justify-center shadow-lg text-2xl leading-none">
            +
        </a>
    </div>
</x-app-layout>
