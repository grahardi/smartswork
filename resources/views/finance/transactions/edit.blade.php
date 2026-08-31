<x-app-layout>
    <x-slot name="header">Edit Transaksi</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('finance.transactions.update', $transaction) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('finance.transactions._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('finance.transactions.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
