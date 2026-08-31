<x-app-layout>
    <x-slot name="header">Tambah Project</x-slot>

    <div class="px-4 py-5">
        <p class="text-xs text-[#7B7F99] mb-4">di {{ $workplace->nama }}</p>

        <form method="POST" action="{{ route('workplaces.projects.store', $workplace) }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="nama" value="Nama Project" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" value="{{ old('nama') }}" required autofocus />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="planning" value="Planning" />
                <textarea id="planning" name="planning" rows="3"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('planning') }}</textarea>
                <x-input-error :messages="$errors->get('planning')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="target" value="Target" />
                <textarea id="target" name="target" rows="3"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('target') }}</textarea>
                <x-input-error :messages="$errors->get('target')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="tanggal_mulai" value="Mulai" />
                    <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" class="mt-1 block w-full" value="{{ old('tanggal_mulai') }}" />
                    <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="tanggal_selesai" value="Selesai" />
                    <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" class="mt-1 block w-full" value="{{ old('tanggal_selesai') }}" />
                    <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <option value="planning" @selected(old('status') === 'planning')>Planning</option>
                    <option value="berjalan" @selected(old('status', 'berjalan') === 'berjalan')>Berjalan</option>
                    <option value="selesai" @selected(old('status') === 'selesai')>Selesai</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('workplaces.projects.index', $workplace) }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
