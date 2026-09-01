<x-app-layout>
    <x-slot name="header">Pengaturan Situs</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="site_name" value="Nama Situs" />
                <x-text-input id="site_name" name="site_name" type="text" class="mt-1 block w-full" value="{{ old('site_name', $settings['site_name']) }}" placeholder="SMARTS Work" />
            </div>

            <div>
                <x-input-label for="kontak_email" value="Email Kontak" />
                <x-text-input id="kontak_email" name="kontak_email" type="email" class="mt-1 block w-full" value="{{ old('kontak_email', $settings['kontak_email']) }}" />
            </div>

            <div>
                <x-input-label for="kontak_whatsapp" value="No. WhatsApp Kontak" />
                <x-text-input id="kontak_whatsapp" name="kontak_whatsapp" type="text" class="mt-1 block w-full" value="{{ old('kontak_whatsapp', $settings['kontak_whatsapp']) }}" placeholder="6281234567890" />
            </div>

            <label class="flex items-center gap-2 text-sm text-[#262135]">
                <input type="checkbox" name="demo_enabled" value="1" @checked($settings['demo_enabled'] === '1')>
                Aktifkan fitur akun demo
            </label>

            <x-primary-button>Simpan Pengaturan</x-primary-button>
        </form>
    </div>
</x-app-layout>
