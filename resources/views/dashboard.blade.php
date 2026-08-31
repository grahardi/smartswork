<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
        $todayCount = $user->dailyActions()->whereDate('tanggal', now()->toDateString())->count();
        $workplaceCount = $user->workplaces()->count();
        $recentActions = $user->dailyActions()->with('project.workplace')->orderByDesc('tanggal')->orderByDesc('waktu')->limit(5)->get();
    @endphp

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('daily-actions.create') }}" class="bg-white shadow sm:rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Aksi hari ini</p>
                    <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $todayCount }}</p>
                    <p class="text-xs text-indigo-600 mt-2">+ Catat aksi baru</p>
                </a>
                <a href="{{ route('workplaces.index') }}" class="bg-white shadow sm:rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Tempat kerja</p>
                    <p class="text-2xl font-semibold text-gray-800 mt-1">{{ $workplaceCount }}</p>
                    <p class="text-xs text-indigo-600 mt-2">Lihat semua →</p>
                </a>
                <a href="{{ route('daily-actions.index') }}" class="bg-white shadow sm:rounded-lg p-5 hover:shadow-md transition">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Jurnal harian</p>
                    <p class="text-2xl font-semibold text-gray-800 mt-1">→</p>
                    <p class="text-xs text-indigo-600 mt-2">Lihat semua riwayat</p>
                </a>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Aksi terakhir</h3>
                    <a href="{{ route('daily-actions.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
                </div>

                @forelse ($recentActions as $action)
                    <div class="flex items-center justify-between py-2 border-b last:border-b-0 border-gray-100">
                        <div>
                            <p class="text-sm text-gray-700">{{ $action->keterangan }}</p>
                            <p class="text-xs text-gray-400">{{ $action->project->workplace->nama }} · {{ $action->project->nama }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $action->tanggal->translatedFormat('d M') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada aksi harian. <a href="{{ route('daily-actions.create') }}" class="text-indigo-600 hover:underline">Catat yang pertama</a>.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
