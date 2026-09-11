<x-business-layout :business="$business">
    <x-slot name="header">Tambah Akun</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('business.coa.store', $business) }}" class="space-y-5">
            @csrf
            @include('business.coa._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('business.coa.index', $business) }}" class="text-sm text-[#667085]">Batal</a>
            </div>
        </form>
    </div>
</x-business-layout>
