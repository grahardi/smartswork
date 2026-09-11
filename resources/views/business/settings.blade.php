<x-business-layout :business="$business">
    <x-slot name="header">Pengaturan Bisnis</x-slot>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#4F46E5] bg-[#EEF2FF] border border-[#C7D2FE] rounded-lg px-4 py-3">{{ session('status') }}</div>
    @endif

    <div class="bg-white border border-[#E5E7EB] rounded-xl p-6 max-w-xl">
        <form method="POST" action="{{ route('business.settings.update', $business) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="nama_usaha" value="Nama Usaha" />
                <x-text-input id="nama_usaha" name="nama_usaha" type="text" class="mt-1 block w-full" value="{{ old('nama_usaha', $business->nama_usaha) }}" required />
                <x-input-error :messages="$errors->get('nama_usaha')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jenis_usaha" value="Jenis Usaha" />
                <x-text-input id="jenis_usaha" name="jenis_usaha" type="text" class="mt-1 block w-full" value="{{ old('jenis_usaha', $business->jenis_usaha) }}" />
                <x-input-error :messages="$errors->get('jenis_usaha')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="npwp" value="NPWP" />
                <x-text-input id="npwp" name="npwp" type="text" class="mt-1 block w-full" value="{{ old('npwp', $business->npwp) }}" />
                <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="alamat" value="Alamat" />
                <textarea id="alamat" name="alamat" rows="3"
                    class="mt-1 block w-full rounded-lg border-[#D1D5DB] focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">{{ old('alamat', $business->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="mata_uang" value="Mata Uang" />
                <select id="mata_uang" name="mata_uang" class="mt-1 block w-full rounded-lg border-[#D1D5DB] focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">
                    @foreach (['IDR' => 'IDR - Rupiah', 'USD' => 'USD - US Dollar'] as $val => $lbl)
                        <option value="{{ $val }}" @selected(old('mata_uang', $business->mata_uang) === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('mata_uang')" class="mt-2" />
            </div>

            <x-primary-button>Simpan Pengaturan</x-primary-button>
        </form>
    </div>

    <div class="mt-6 bg-white border border-[#FECACA] rounded-xl p-6 max-w-xl">
        <h3 class="text-sm font-semibold text-[#DC2626] mb-1">Anggota Business</h3>
        <p class="text-xs text-[#6B7280] mb-3">Owner: {{ $business->owner->name }}</p>
        <div class="space-y-1.5">
            @foreach ($business->users as $u)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-[#111827]">{{ $u->name }}</span>
                    <span class="text-xs text-[#6B7280]">{{ $u->pivot->role }}</span>
                </div>
            @endforeach
        </div>
    </div>
</x-business-layout>
