<x-app-layout>
    <x-slot name="header">Calendar</x-slot>

    @php
        use Illuminate\Support\Carbon;

        $awalBulan = $periode->copy()->startOfMonth();
        $akhirBulan = $periode->copy()->endOfMonth();
        $mulaiGrid = $awalBulan->copy()->startOfWeek(Carbon::SUNDAY);
        $akhirGrid = $akhirBulan->copy()->endOfWeek(Carbon::SATURDAY);

        $bulanSebelum = $periode->copy()->subMonth()->format('Y-m');
        $bulanSesudah = $periode->copy()->addMonth()->format('Y-m');

        $hariLabel = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $warnaTanda = ['aksi' => '#2563EB', 'event' => '#DBA83B', 'deadline' => '#DC2626'];
    @endphp

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        {{-- Navigasi bulan --}}
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('calendar.index', ['bulan' => $bulanSebelum]) }}" class="text-[#2563EB] text-lg">‹</a>
            <span class="text-sm font-semibold text-[#262135] swk-heading">{{ $periode->translatedFormat('F Y') }}</span>
            <a href="{{ route('calendar.index', ['bulan' => $bulanSesudah]) }}" class="text-[#2563EB] text-lg">›</a>
        </div>

        {{-- Grid kalender --}}
        <div class="bg-white border border-[#E7E9F5] rounded-xl p-3">
            <div class="grid grid-cols-7 mb-1">
                @foreach ($hariLabel as $h)
                    <div class="text-center text-[10px] text-[#9CA3AF] font-medium py-1">{{ $h }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-y-1">
                @php $cursor = $mulaiGrid->copy(); @endphp
                @while ($cursor->lte($akhirGrid))
                    @php
                        $iso = $cursor->toDateString();
                        $isCurrentMonth = $cursor->month === $periode->month;
                        $isToday = $cursor->isToday();
                        $isSelected = $iso === $tanggalDipilih;
                        $tanda = array_unique($marks[$iso] ?? []);
                    @endphp
                    <a href="{{ route('calendar.index', ['tanggal' => $iso, 'bulan' => $bulan]) }}"
                       class="flex flex-col items-center py-1.5 rounded-lg {{ $isSelected ? 'bg-[#2563EB]' : ($isToday ? 'bg-[#EFF6FF]' : '') }}">
                        <span class="text-xs {{ $isSelected ? 'text-white font-semibold' : ($isCurrentMonth ? 'text-[#262135]' : 'text-[#D1D5DB]') }}">
                            {{ $cursor->day }}
                        </span>
                        <span class="flex gap-0.5 mt-0.5 h-1.5">
                            @foreach ($tanda as $t)
                                <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $isSelected ? '#fff' : $warnaTanda[$t] }}"></span>
                            @endforeach
                        </span>
                    </a>
                    @php $cursor->addDay(); @endphp
                @endwhile
            </div>
        </div>

        {{-- Legenda --}}
        <div class="flex gap-4 mt-3 text-[11px] text-[#7B7F99]">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#2563EB]"></span> Aksi Harian</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#DBA83B]"></span> Event</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#DC2626]"></span> Deadline</span>
        </div>

        {{-- Agenda tanggal terpilih --}}
        <div class="mt-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-[#262135] swk-heading">
                    {{ Carbon::parse($tanggalDipilih)->translatedFormat('d F Y') }}
                </h3>
                <button type="button" onclick="document.getElementById('swk-add-event').classList.toggle('hidden')" class="text-xs text-[#2563EB] font-medium">+ Tambah Event</button>
            </div>

            <form id="swk-add-event" method="POST" action="{{ route('calendar.store') }}" class="hidden bg-white border border-[#E7E9F5] rounded-xl p-4 mb-3 space-y-3">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggalDipilih }}">
                <input type="text" name="judul" placeholder="Judul event (mis. Rapat, Cuti)" required
                    class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                <textarea name="keterangan" rows="2" placeholder="Keterangan (opsional)"
                    class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm"></textarea>
                <button type="submit" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-lg">Simpan Event</button>
            </form>

            <div class="space-y-2">
                @forelse ($agendaEvents as $event)
                    <div class="rounded-xl p-3 flex items-start justify-between" style="background: {{ $event->warna }}22;">
                        <div>
                            <p class="text-sm font-medium text-[#262135]">{{ $event->judul }}</p>
                            @if ($event->keterangan)
                                <p class="text-xs text-[#7B7F99] mt-0.5">{{ $event->keterangan }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('calendar.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-[#DC2626]">Hapus</button>
                        </form>
                    </div>
                @endforeach

                @foreach ($agendaDeadline as $project)
                    <div class="rounded-xl p-3 bg-[#FEE2E2]">
                        <p class="text-sm font-medium text-[#262135]">🚩 Deadline: {{ $project->nama }}</p>
                        <p class="text-xs text-[#7B7F99] mt-0.5">{{ $project->workplace->nama }}</p>
                    </div>
                @endforeach

                @forelse ($agendaAksi as $aksi)
                    <div class="rounded-xl p-3 bg-[#EFF6FF]">
                        <p class="text-sm text-[#262135]">{{ $aksi->keterangan }}</p>
                        <p class="text-xs text-[#7B7F99] mt-0.5">{{ $aksi->project->nama }} · {{ $aksi->project->workplace->nama }}</p>
                    </div>
                @empty
                    @if ($agendaEvents->isEmpty() && $agendaDeadline->isEmpty())
                        <p class="text-sm text-[#7B7F99] text-center py-6">Tidak ada apa-apa di tanggal ini.</p>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
