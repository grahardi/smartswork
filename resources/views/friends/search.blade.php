<x-app-layout>
    <x-slot name="header">Hasil Pencarian</x-slot>

    <div class="px-4 py-5">
        @if ($found)
            <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                <p class="font-semibold text-sm text-[#262135] swk-heading">{{ $found->name }}</p>
                <p class="text-xs text-[#7B7F99] mt-1">{{ $found->email }}</p>

                <form method="POST" action="{{ route('friends.store') }}" class="mt-3">
                    @csrf
                    <input type="hidden" name="addressee_id" value="{{ $found->id }}">
                    <button type="submit" class="bg-[#2563EB] text-white text-sm px-4 py-2 rounded-full">Kirim Permintaan Pertemanan</button>
                </form>
            </div>
        @else
            <p class="text-sm text-[#7B7F99] text-center py-8">Tidak ditemukan user dengan email "{{ $query }}".</p>
        @endif

        <a href="{{ route('friends.index') }}" class="text-xs text-[#2563EB] mt-4 inline-block">← Kembali ke Teman</a>
    </div>
</x-app-layout>
