<x-app-layout>
    <x-slot name="header">Tempat Kerja — {{ $friend->name }}</x-slot>

    <div class="px-4 py-5">
        <div class="mb-3 text-xs text-[#8A8377] bg-[#F5F6FD] border border-[#E5E7F5] rounded-lg px-3 py-2">
            👁 Mode lihat saja (read-only) — data milik {{ $friend->name }}.
        </div>

        <div class="space-y-3">
            @forelse ($workplaces as $workplace)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <h3 class="font-semibold text-[#262135] text-sm swk-heading">{{ $workplace->nama }}</h3>
                    <p class="text-xs text-[#7B7F99] mt-1">{{ $workplace->pivot->jabatan ?? '—' }}</p>
                    <p class="text-xs text-[#2563EB] mt-2">{{ $workplace->projects_count }} project</p>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada tempat kerja.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
