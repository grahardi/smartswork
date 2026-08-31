<x-app-layout>
    <x-slot name="header">Catat Transaksi</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('finance.transactions.store') }}" class="space-y-5">
            @csrf
            @include('finance.transactions._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('finance.transactions.index') }}" class="text-sm text-[#6E675A]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
