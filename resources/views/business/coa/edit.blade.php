<x-app-layout>
    <x-slot name="header">Edit Akun</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('business.coa.update', [$business, $account]) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('business.coa._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('business.coa.index', $business) }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>

        <form method="POST" action="{{ route('business.coa.destroy', [$business, $account]) }}" onsubmit="return confirm('Hapus akun {{ $account->nama }}?')" class="mt-4">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs text-[#DC2626]">Hapus Akun</button>
        </form>
    </div>
</x-app-layout>
