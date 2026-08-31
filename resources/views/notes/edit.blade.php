<x-app-layout>
    <x-slot name="header">Edit Coretan</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-4" style="background: {{ $note->warna }}; border-radius: 12px; padding: 16px;" id="note-form">
            @csrf
            @method('PUT')

            <input type="text" name="judul" value="{{ old('judul', $note->judul) }}" placeholder="Judul (opsional)"
                class="w-full border-0 bg-transparent focus:ring-0 text-base font-semibold text-[#262135] placeholder:text-[#9CA3AF] px-0">
            <textarea name="isi" rows="8" placeholder="Tulis catatan..."
                class="w-full border-0 bg-transparent focus:ring-0 text-sm text-[#262135] placeholder:text-[#9CA3AF] px-0 resize-none">{{ old('isi', $note->isi) }}</textarea>

            <div class="flex gap-1.5">
                @foreach (['#FFFFFF','#FEF3C7','#FDE1EC','#DBEAFE','#DCFCE7'] as $c)
                    <label class="w-6 h-6 rounded-full border border-[#00000022] cursor-pointer flex items-center justify-center" style="background: {{ $c }}">
                        <input type="radio" name="warna" value="{{ $c }}" class="hidden warna-radio" {{ old('warna', $note->warna) === $c ? 'checked' : '' }}>
                    </label>
                @endforeach
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('notes.index') }}" class="text-sm text-[#7B7F99]">Kembali</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.warna-radio').forEach(radio => {
            radio.closest('label').addEventListener('click', () => {
                document.querySelectorAll('.warna-radio').forEach(r => r.checked = false);
                radio.checked = true;
                document.getElementById('note-form').style.background = radio.value;
            });
        });
    </script>
</x-app-layout>
