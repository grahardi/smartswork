<x-app-layout>
    <x-slot name="header">Edit Tempat Tinggal</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('residences.update', $residence) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('residences._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('residences.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
