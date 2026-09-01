<x-app-layout>
    <x-slot name="header">Notifikasi</x-slot>

    <div class="px-4 py-5">
        <div class="space-y-2">
            @forelse ($notifications as $notif)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 {{ !$notif->is_read ? 'border-l-4 border-l-[#2563EB]' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <a href="{{ $notif->url ?? '#' }}" class="text-sm text-[#262135] flex-1">{{ $notif->message }}</a>
                        <form method="POST" action="{{ route('notifications.destroy', $notif) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-[#DC2626]">Hapus</button>
                        </form>
                    </div>
                    <p class="text-[11px] text-[#9CA3AF] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada notifikasi.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $notifications->links() }}</div>
    </div>
</x-app-layout>
