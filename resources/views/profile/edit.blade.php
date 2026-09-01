<x-app-layout>
    <x-slot name="header">Akun</x-slot>

    <div class="px-4 py-5 space-y-5">
        @if (session('status') === 'profile-updated')
            <div class="text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">Profil berhasil diperbarui.</div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">Kata sandi berhasil diperbarui.</div>
        @endif

        {{-- Update nama & email --}}
        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading mb-1">Info Akun</h3>
            <p class="text-xs text-[#7B7F99] mb-4">Nama dan email untuk login.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <x-input-label for="name" value="Nama" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', auth()->user()->name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', auth()->user()->email) }}" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <x-primary-button>Simpan</x-primary-button>
            </form>
        </div>

        {{-- Update password --}}
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
