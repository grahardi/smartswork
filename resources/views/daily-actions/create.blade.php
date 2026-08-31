<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catat Aksi Harian
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @if ($projects->isEmpty())
                    <p class="text-sm text-gray-500">
                        Belum ada project sama sekali. Tambahkan project dulu di halaman
                        <a href="{{ route('workplaces.index') }}" class="text-indigo-600 hover:underline">Tempat Kerja</a>.
                    </p>
                @else
                    <form method="POST" action="{{ route('daily-actions.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="project_id" value="Project" />
                            <select id="project_id" name="project_id" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Pilih project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                                        {{ $project->workplace->nama }} — {{ $project->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="tanggal" value="Tanggal" />
                                <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
                                    value="{{ old('tanggal', now()->toDateString()) }}" required />
                                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="waktu" value="Waktu (opsional)" />
                                <x-text-input id="waktu" name="waktu" type="time" class="mt-1 block w-full"
                                    value="{{ old('waktu', now()->format('H:i')) }}" />
                                <x-input-error :messages="$errors->get('waktu')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="foto" value="Foto (opsional)" />
                            <input id="foto" name="foto" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-600" />
                            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="keterangan" value="Keterangan" />
                            <textarea id="keterangan" name="keterangan" rows="3" required
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Contoh: Antar anak ke sekolah">{{ old('keterangan') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Simpan</x-primary-button>
                            <a href="{{ route('daily-actions.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
