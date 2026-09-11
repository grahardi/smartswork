<x-app-layout>
    <x-slot name="header">Tambah Rekening</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('rekening.store') }}" class="space-y-5">
            @csrf
            @include('rekening._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('rekening.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
