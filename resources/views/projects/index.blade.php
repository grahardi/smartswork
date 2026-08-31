<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-1">
                    <a href="{{ route('workplaces.index') }}" class="hover:underline">Tempat Kerja</a> / {{ $workplace->nama }}
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Project — {{ $workplace->nama }}
                </h2>
            </div>
            <a href="{{ route('workplaces.projects.create', $workplace) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Tambah Project
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
                @forelse ($projects as $project)
                    <div class="bg-white shadow sm:rounded-lg p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">{{ $project->nama }}</h3>
                            <span @class([
                                'text-xs px-2 py-0.5 rounded-full',
                                'bg-gray-100 text-gray-600' => $project->status === 'planning',
                                'bg-blue-100 text-blue-700' => $project->status === 'berjalan',
                                'bg-green-100 text-green-700' => $project->status === 'selesai',
                            ])>{{ ucfirst($project->status) }}</span>
                        </div>
                        @if ($project->planning)
                            <p class="text-sm text-gray-500 mt-2"><span class="font-medium">Planning:</span> {{ $project->planning }}</p>
                        @endif
                        @if ($project->target)
                            <p class="text-sm text-gray-500 mt-1"><span class="font-medium">Target:</span> {{ $project->target }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada project di tempat kerja ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
