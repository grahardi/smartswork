<x-app-layout>
    <x-slot name="header">Akun</x-slot>

    @php $profile = auth()->user()->profile; @endphp

    <div class="px-4 py-5 space-y-5">
        @if (session('status') === 'profile-updated')
            <div class="text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">Profil berhasil diperbarui.</div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">Kata sandi berhasil diperbarui.</div>
        @endif
        @if (session('status'))
            <div class="text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        {{-- Data Diri --}}
        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading mb-1">Data Diri</h3>
            <p class="text-xs text-[#7B7F99] mb-4">Nama lengkap, kontak, dan foto profil.</p>

            <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <x-input-label for="foto_profil" value="Foto Profil" />
                    @if (!empty($profile?->foto_profil))
                        <img src="{{ Storage::url($profile->foto_profil) }}" alt="Foto profil" onclick="swkZoom(this.src)"
                            class="w-16 h-16 rounded-full object-cover mt-1 mb-2 cursor-zoom-in">
                    @endif
                    <input id="foto_profil" name="foto_profil" type="file" accept="image/*" class="mt-1 block w-full text-sm text-[#7B7F99]" />
                    <x-input-error :messages="$errors->get('foto_profil')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="nama_lengkap" value="Nama Lengkap" />
                    <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full"
                        value="{{ old('nama_lengkap', $profile->nama_lengkap ?? '') }}" required />
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
                    <textarea id="alamat" name="alamat" rows="2"
                        class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                    <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full"
                        value="{{ old('tanggal_lahir', optional($profile->tanggal_lahir ?? null)->format('Y-m-d')) }}" />
                    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                </div>

                <x-primary-button>Simpan Data Diri</x-primary-button>
            </form>
        </div>

        {{-- Info Akun --}}
        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading mb-1">Info Akun</h3>
            <p class="text-xs text-[#7B7F99] mb-4">Nama dan email untuk login.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="name" value="Nama Akun" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', auth()->user()->name) }}" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', auth()->user()->email) }}" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <x-primary-button>Simpan Info Akun</x-primary-button>
            </form>
        </div>

        {{-- Ubah Password --}}
        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading mb-1">Ubah Kata Sandi</h3>
            <p class="text-xs text-[#7B7F99] mb-4">Gunakan kata sandi yang panjang dan acak.</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="current_password" value="Kata Sandi Saat Ini" />
                    <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Kata Sandi Baru" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <x-primary-button>Ubah Kata Sandi</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
