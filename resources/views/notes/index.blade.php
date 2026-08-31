<x-app-layout>
    <x-slot name="header">Coretan</x-slot>

    <div class="px-4 py-5">
        {{-- Quick add --}}
        <form method="POST" action="{{ route('notes.store') }}" class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-5">
            @csrf
            <input type="text" name="judul" placeholder="Judul (opsional)"
                class="w-full border-0 focus:ring-0 text-sm font-medium text-[#262135] placeholder:text-[#9CA3AF] px-0">
            <textarea name="isi" rows="2" placeholder="Tulis catatan..."
                class="w-full border-0 focus:ring-0 text-sm text-[#262135] placeholder:text-[#9CA3AF] px-0 resize-none"></textarea>
            <div class="flex items-center justify-between mt-2">
                <div class="flex gap-1.5">
                    @foreach (['#FFFFFF','#FEF3C7','#FDE1EC','#DBEAFE','#DCFCE7'] as $c)
                        <label class="w-5 h-5 rounded-full border border-[#E5E7F5] cursor-pointer" style="background: {{ $c }}">
                            <input type="radio" name="warna" value="{{ $c }}" class="hidden" {{ $c === '#FFFFFF' ? 'checked' : '' }}>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-1.5 rounded-full">Simpan</button>
            </div>
        </form>

        <div class="grid grid-cols-2 gap-3">
            @forelse ($notes as $note)
                <div class="rounded-xl p-3 border border-[#E7E9F5]" style="background: {{ $note->warna }};">
                    <div class="flex items-start justify-between gap-1">
                        @if ($note->judul)
                            <p class="text-sm font-semibold text-[#262135] swk-heading">{{ $note->judul }}</p>
                        @endif
                        <form method="POST" action="{{ route('notes.pin', $note) }}" class="flex-shrink-0">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs {{ $note->is_pinned ? 'text-[#2563EB]' : 'text-[#C7CBDA]' }}">📌</button>
                        </form>
                    </div>
                    @if ($note->isi)
                        <p class="text-xs text-[#3F3A50] mt-1 whitespace-pre-line">{{ Str::limit($note->isi, 200) }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-2">
                        <a href="{{ route('notes.edit', $note) }}" class="text-[11px] text-[#2563EB]">Edit</a>
                        <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Hapus coretan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[11px] text-[#DC2626]">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-2 text-sm text-[#7B7F99] text-center py-8">Belum ada coretan.</p>
            @endforelse
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="warna"]').forEach(radio => {
            radio.closest('label').addEventListener('click', () => {
                document.querySelectorAll('input[name="warna"]').forEach(r => r.checked = false);
                radio.checked = true;
            });
        });
    </script>
</x-app-layout>
