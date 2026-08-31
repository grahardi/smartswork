<x-app-layout>
    <x-slot name="header">Data Diri</x-slot>

    <div class="px-4 py-5">
        <p class="text-sm text-[#7B7F99] mb-5">
            Lengkapi data diri kamu sebelum mulai mencatat aksi harian.
        </p>

        <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="nama_lengkap" value="Nama Lengkap" />
                <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full"
                    value="{{ old('nama_lengkap', $profile->nama_lengkap ?? '') }}" required autofocus />
                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="no_hp" value="No. HP" />
                <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full"
                    value="{{ old('no_hp', $profile->no_hp ?? '') }}" />
                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="alamat" value="Alamat" />
                <textarea id="alamat" name="alamat" rows="3"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full"
                    value="{{ old('tanggal_lahir', optional($profile->tanggal_lahir ?? null)->format('Y-m-d')) }}" />
                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="foto_profil" value="Foto Profil" />
                @if (!empty($profile?->foto_profil))
                    <img src="{{ Storage::url($profile->foto_profil) }}" alt="Foto profil" onclick="swkZoom(this.src)" class="w-16 h-16 rounded-full object-cover mt-1 mb-2 cursor-zoom-in">
                @endif
                <input id="foto_profil" name="foto_profil" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[#7B7F99]" />
                <x-input-error :messages="$errors->get('foto_profil')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan &amp; Lanjut</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
