<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Aksi Harian
            </h2>
            <a href="{{ route('daily-actions.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                + Catat Aksi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-3">
                @forelse ($actions as $action)
                    <div class="bg-white shadow sm:rounded-lg p-5 flex gap-4">
                        @if ($action->foto)
                            <img src="{{ Storage::url($action->foto) }}" alt="" class="w-16 h-16 rounded-md object-cover flex-shrink-0">
                        @endif
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-800">
                                    {{ $action->tanggal->translatedFormat('d M Y') }}
                                    @if ($action->waktu) · {{ \Illuminate\Support\Carbon::parse($action->waktu)->format('H:i') }} @endif
                                </span>
                                <span class="text-xs text-gray-400">{{ $action->project->workplace->nama }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $action->keterangan }}</p>
                            <span class="text-xs text-indigo-600 mt-1 inline-block">{{ $action->project->nama }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada aksi harian yang dicatat.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $actions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
