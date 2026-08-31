<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tempat Kerja
            </h2>
            <a href="{{ route('workplaces.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Tambah Tempat Kerja
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid gap-4">
                @forelse ($workplaces as $workplace)
                    <div class="bg-white shadow sm:rounded-lg p-5 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-gray-800">{{ $workplace->nama }}</h3>
                                @if ($workplace->is_default)
                                    <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Default</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ ucfirst($workplace->type) }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $workplace->pivot->jabatan ?? '—' }}
                                @if ($workplace->alamat) · {{ $workplace->alamat }} @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $workplace->projects_count }} project</p>
                        </div>
                        <a href="{{ route('workplaces.projects.index', $workplace) }}"
                            class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Project →
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada tempat kerja.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
