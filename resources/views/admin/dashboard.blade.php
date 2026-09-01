<x-app-layout>
    <x-slot name="header">Admin</x-slot>

    <div class="px-4 py-5">
        <div class="mb-4 text-xs text-[#8A8377] bg-[#F5F6FD] border border-[#E5E7F5] rounded-lg px-3 py-2">
            🔒 Panel admin hanya menampilkan ringkasan angka. Isi jurnal, keuangan, coretan, dan galeri milik user tidak bisa diakses lewat sini.
        </div>

        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                <p class="text-[10px] text-[#8A8377]">Total Member</p>
                <p class="text-xl font-semibold text-[#262135] mt-1">{{ $stats['total_member'] }}</p>
            </div>
            <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                <p class="text-[10px] text-[#8A8377]">Member Aktif</p>
                <p class="text-xl font-semibold text-[#262135] mt-1">{{ $stats['member_aktif'] }}</p>
            </div>
            <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                <p class="text-[10px] text-[#8A8377]">Member Baru Bulan Ini</p>
                <p class="text-xl font-semibold text-[#262135] mt-1">{{ $stats['member_baru_bulan_ini'] }}</p>
            </div>
            <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                <p class="text-[10px] text-[#8A8377]">Total Aksi Harian</p>
                <p class="text-xl font-semibold text-[#262135] mt-1">{{ $stats['total_aksi_harian'] }}</p>
            </div>
            <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 col-span-2">
                <p class="text-[10px] text-[#8A8377]">Total Transaksi Keuangan Tercatat</p>
                <p class="text-xl font-semibold text-[#262135] mt-1">{{ $stats['total_transaksi_keuangan'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <a href="{{ route('admin.members.index') }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E7E9F5] rounded-xl">
                <span class="w-11 h-11 rounded-full bg-[#EFF6FF] text-[#2563EB] flex items-center justify-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M2 20c0-3.5 3-5.5 7-5.5s7 2 7 5.5"/><circle cx="17" cy="9" r="2.5"/><path d="M16.5 14.5c2.5 0 5 1.5 5.5 4"/></svg>
                </span>
                <span class="text-[11px] text-[#1F2333]">Member</span>
            </a>
            <a href="{{ route('admin.settings.edit') }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E7E9F5] rounded-xl">
                <span class="w-11 h-11 rounded-full bg-[#F3E8FF] text-[#7C3AED] flex items-center justify-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.7 1.7 0 0 0 .34-1.87 1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.7 1.7 0 0 0 1.87.34H9a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87V9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1Z"/></svg>
                </span>
                <span class="text-[11px] text-[#1F2333]">Pengaturan</span>
            </a>
            <a href="{{ route('admin.landing.edit') }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E7E9F5] rounded-xl">
                <span class="w-11 h-11 rounded-full bg-[#FEF3C7] text-[#92400E] flex items-center justify-center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                </span>
                <span class="text-[11px] text-[#1F2333]">Halaman Depan</span>
            </a>
        </div>
    </div>
</x-app-layout>
