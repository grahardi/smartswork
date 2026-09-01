<x-app-layout>
    <x-slot name="header">Teman</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-[#DC2626] bg-[#FEE2E2] border border-[#FCA5A5] rounded-lg px-4 py-3">{{ session('error') }}</div>
        @endif

        {{-- Cari & tambah teman via email --}}
        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-5">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading mb-2">Tambah Teman</h3>
            <form method="GET" action="{{ route('friends.search') }}" class="flex gap-2">
                <input type="email" name="email" placeholder="Cari via email" required
                    class="flex-1 rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                <button type="submit" class="bg-[#2563EB] text-white text-sm px-4 py-2 rounded-lg">Cari</button>
            </form>
        </div>

        @if ($incoming->isNotEmpty())
            <div class="mb-5">
                <p class="text-xs font-medium text-[#8A8377] mb-2">PERMINTAAN MASUK</p>
                <div class="space-y-2">
                    @foreach ($incoming as $req)
                        <div class="bg-white border border-[#E7E9F5] rounded-xl px-4 py-3 flex items-center justify-between">
                            <span class="text-sm text-[#262135]">{{ $req->requester->name }}</span>
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('friends.accept', $req) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#2563EB] font-medium">Terima</button>
                                </form>
                                <form method="POST" action="{{ route('friends.decline', $req) }}">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#DC2626] font-medium">Tolak</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($outgoing->isNotEmpty())
            <div class="mb-5">
                <p class="text-xs font-medium text-[#8A8377] mb-2">MENUNGGU DITERIMA</p>
                <div class="space-y-2">
                    @foreach ($outgoing as $req)
                        <div class="bg-white border border-[#E7E9F5] rounded-xl px-4 py-3">
                            <span class="text-sm text-[#7B7F99]">{{ $req->addressee->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <p class="text-xs font-medium text-[#8A8377] mb-2">TEMAN ({{ $friends->count() }})</p>
            <div class="space-y-2">
                @forelse ($friends as $friend)
                    <div class="bg-white border border-[#E7E9F5] rounded-xl px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-full bg-[#EFF6FF] flex items-center justify-center text-[#2563EB] font-semibold text-xs overflow-hidden flex-shrink-0">
                                    @if ($friend->profile?->foto_profil)
                                        <img src="{{ Storage::url($friend->profile->foto_profil) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-[#262135] swk-heading">{{ $friend->name }}</span>
                                    @if (!empty($labels[$friend->id]))
                                        <span class="text-[10px] bg-[#EFF6FF] text-[#2563EB] px-2 py-0.5 rounded-full ml-1">{{ $labels[$friend->id] }}</span>
                                    @endif
                                </div>
                            </div>
                            <form method="POST" action="{{ route('friends.destroy', $friend) }}" onsubmit="return confirm('Akhiri pertemanan dengan {{ $friend->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-[#DC2626]">Hapus</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('friends.label', $friend) }}" class="mt-2">
                            @csrf @method('PATCH')
                            <select name="label" onchange="this.form.submit()"
                                class="text-xs rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] py-1">
                                <option value="">Pilih hubungan...</option>
                                @foreach (['Suami', 'Istri', 'Anak', 'Orang Tua', 'Saudara', 'Rekan Kerja', 'Teman', 'Tetangga', 'Lainnya'] as $opt)
                                    <option value="{{ $opt }}" @selected(($labels[$friend->id] ?? '') === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </form>

                        <div class="flex items-center gap-3 mt-2">
                            <a href="{{ route('friends.aksi-harian', $friend) }}" class="text-xs text-[#2563EB]">Lihat Aksi Harian</a>
                            <a href="{{ route('friends.keuangan', $friend) }}" class="text-xs text-[#2563EB]">Lihat Keuangan</a>
                            <a href="{{ route('friends.tempat-kerja', $friend) }}" class="text-xs text-[#2563EB]">Lihat Tempat Kerja</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada teman. Cari lewat email di atas.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
