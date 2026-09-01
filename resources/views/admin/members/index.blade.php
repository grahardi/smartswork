<x-app-layout>
    <x-slot name="header">Kelola Member</x-slot>

    <div class="px-4 py-5">
        <div class="mb-4 text-xs text-[#8A8377] bg-[#F5F6FD] border border-[#E5E7F5] rounded-lg px-3 py-2">
            🔒 Hanya nama, email, dan status akun yang ditampilkan. Jurnal, keuangan, coretan, dan galeri milik member tidak bisa dibuka dari sini.
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <form method="GET" class="mb-4">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau email..."
                class="w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
        </form>

        <div class="space-y-2">
            @forelse ($members as $member)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#262135]">
                                {{ $member->name }}
                                @if ($member->is_admin)
                                    <span class="text-[10px] bg-[#F3E8FF] text-[#7C3AED] px-2 py-0.5 rounded-full ml-1">Admin</span>
                                @endif
                                @unless ($member->is_active)
                                    <span class="text-[10px] bg-[#FEE2E2] text-[#DC2626] px-2 py-0.5 rounded-full ml-1">Nonaktif</span>
                                @endunless
                            </p>
                            <p class="text-xs text-[#7B7F99] mt-0.5">{{ $member->email }}</p>
                            <p class="text-[11px] text-[#9CA3AF] mt-0.5">Daftar {{ $member->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-3 pt-3 border-t border-[#F0EBDF]">
                        <form method="POST" action="{{ route('admin.members.toggle-active', $member) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs {{ $member->is_active ? 'text-[#DC2626]' : 'text-[#2563EB]' }} font-medium">
                                {{ $member->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.members.toggle-admin', $member) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-[#7C3AED] font-medium">
                                {{ $member->is_admin ? 'Cabut Admin' : 'Jadikan Admin' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.members.destroy', $member) }}" onsubmit="return confirm('Hapus member {{ $member->name }}? Semua datanya ikut terhapus permanen.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-[#DC2626] font-medium">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Tidak ada member ditemukan.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $members->links() }}</div>
    </div>
</x-app-layout>
