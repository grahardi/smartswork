<x-app-layout>
    <x-slot name="header">Edit Halaman Depan</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.landing.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="landing_headline" value="Judul Utama (Hero)" />
                <x-text-input id="landing_headline" name="landing_headline" type="text" class="mt-1 block w-full"
                    value="{{ old('landing_headline', $settings['landing_headline']) }}" placeholder="Setiap hari kerjamu, tercatat rapi." />
            </div>

            <div>
                <x-input-label for="landing_subtext" value="Subjudul (Hero)" />
                <textarea id="landing_subtext" name="landing_subtext" rows="3"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('landing_subtext', $settings['landing_subtext']) }}</textarea>
            </div>

            <div>
                <x-input-label for="landing_cta_headline" value="Judul Ajakan (CTA)" />
                <x-text-input id="landing_cta_headline" name="landing_cta_headline" type="text" class="mt-1 block w-full"
                    value="{{ old('landing_cta_headline', $settings['landing_cta_headline']) }}" placeholder="Mulai catat hari kerjamu sekarang" />
            </div>

            <div>
                <x-input-label for="landing_cta_subtext" value="Subjudul Ajakan (CTA)" />
                <textarea id="landing_cta_subtext" name="landing_cta_subtext" rows="2"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('landing_cta_subtext', $settings['landing_cta_subtext']) }}</textarea>
            </div>

            <x-primary-button>Simpan Halaman Depan</x-primary-button>
            <a href="{{ url('/') }}" target="_blank" class="text-sm text-[#7B7F99] ml-3">Lihat halaman depan ↗</a>
        </form>
    </div>
</x-app-layout>
