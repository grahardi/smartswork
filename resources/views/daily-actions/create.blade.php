<x-app-layout>
    <x-slot name="header">Catat Aksi</x-slot>

    <div class="px-4 py-5">
        @if ($projects->isEmpty())
            <p class="text-sm text-[#6E675A]">
                Belum ada project. Tambahkan dulu di halaman
                <a href="{{ route('workplaces.index') }}" class="text-[#3E5C4E] underline">Tempat Kerja</a>.
            </p>
        @else
            <form method="POST" action="{{ route('daily-actions.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="project_id" value="Project" />
                    <select id="project_id" name="project_id" required
                        class="mt-1 block w-full rounded-lg border-[#DAD4C4] focus:border-[#3E5C4E] focus:ring-[#3E5C4E] text-sm">
                        <option value="">Pilih project</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                                {{ $project->workplace->nama }} — {{ $project->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                            value="{{ old('tanggal', now()->toDateString()) }}" required />
                        <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="waktu" value="Waktu" />
                        <x-text-input id="waktu" name="waktu" type="time" class="mt-1 block w-full"
                            value="{{ old('waktu', now()->format('H:i')) }}" />
                        <x-input-error :messages="$errors->get('waktu')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="foto" value="Foto (opsional)" />
                    <input id="foto" name="foto" type="file" accept="image/*" capture="environment"
                        class="mt-1 block w-full text-sm text-[#6E675A]" />
                    <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="keterangan" value="Keterangan" />
                    <textarea id="keterangan" name="keterangan" rows="3" required
                        class="mt-1 block w-full rounded-lg border-[#DAD4C4] focus:border-[#3E5C4E] focus:ring-[#3E5C4E] text-sm"
                        placeholder="Contoh: Antar anak ke sekolah">{{ old('keterangan') }}</textarea>
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <a href="{{ route('daily-actions.index') }}" class="text-sm text-[#6E675A]">Batal</a>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
