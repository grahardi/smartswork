<x-business-layout :business="$business">
    <x-slot name="header">Pengaturan Bisnis</x-slot>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#465FFF] bg-[#ECF3FF] border border-[#C2D6FF] rounded-lg px-4 py-3">{{ session('status') }}</div>
    @endif

    <div class="bg-white border border-[#E4E7EC] rounded-xl p-6 max-w-xl">
        <form method="POST" action="{{ route('business.settings.update', $business) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="nama_usaha" value="Nama Usaha" />
                <x-text-input id="nama_usaha" name="nama_usaha" type="text" class="mt-1 block w-full" value="{{ old('nama_usaha', $business->nama_usaha) }}" required />
                <x-input-error :messages="$errors->get('nama_usaha')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jenis_usaha" value="Jenis Usaha" />
                <x-text-input id="jenis_usaha" name="jenis_usaha" type="text" class="mt-1 block w-full" value="{{ old('jenis_usaha', $business->jenis_usaha) }}" />
                <x-input-error :messages="$errors->get('jenis_usaha')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="npwp" value="NPWP" />
                <x-text-input id="npwp" name="npwp" type="text" class="mt-1 block w-full" value="{{ old('npwp', $business->npwp) }}" />
                <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="alamat" value="Alamat" />
                <textarea id="alamat" name="alamat" rows="3"
                    class="mt-1 block w-full rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">{{ old('alamat', $business->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="mata_uang" value="Mata Uang" />
                <select id="mata_uang" name="mata_uang" class="mt-1 block w-full rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                    @foreach (['IDR' => 'IDR - Rupiah', 'USD' => 'USD - US Dollar'] as $val => $lbl)
                        <option value="{{ $val }}" @selected(old('mata_uang', $business->mata_uang) === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('mata_uang')" class="mt-2" />
            </div>

            <x-primary-button>Simpan Pengaturan</x-primary-button>
        </form>
    </div>

    <div class="mt-6 bg-white border border-[#E4E7EC] rounded-xl p-6 max-w-xl">
        <h3 class="text-sm font-semibold text-[#101828] mb-1">Pengelola Business</h3>
        <p class="text-xs text-[#667085] mb-4">Owner: {{ $business->owner->name }}</p>

        @if (session('error'))
            <div class="mb-4 text-sm text-[#D92D20] bg-[#FEF3F2] border border-[#FEE4E2] rounded-lg px-4 py-3">{{ session('error') }}</div>
        @endif

        {{-- Cari user via email --}}
        <form method="GET" action="{{ route('business.settings.search-member', $business) }}" class="flex gap-2 mb-4">
            <input type="email" name="email" placeholder="Cari via email untuk ditambahkan"
                class="flex-1 rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
            <button type="submit" class="bg-[#465FFF] text-white text-sm px-4 py-2 rounded-lg">Cari</button>
        </form>

        @if ($searchQuery)
            @if ($searchResult)
                <div class="bg-[#F9FAFB] border border-[#E4E7EC] rounded-lg p-3 mb-4">
                    <p class="text-sm font-medium text-[#101828]">{{ $searchResult->name }}</p>
                    <p class="text-xs text-[#667085]">{{ $searchResult->email }}</p>

                    @if ($sudahAnggota)
                        <p class="text-xs text-[#465FFF] mt-2">Sudah jadi anggota business ini.</p>
                    @else
                        <form method="POST" action="{{ route('business.settings.add-member', $business) }}" class="flex items-center gap-2 mt-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $searchResult->id }}">
                            <select name="role" class="text-sm rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF]">
                                <option value="staff">Staff</option>
                                <option value="akuntan">Akuntan</option>
                            </select>
                            <button type="submit" class="text-sm font-medium text-white bg-[#465FFF] px-3 py-1.5 rounded-lg">Tambahkan</button>
                        </form>
                    @endif
                </div>
            @else
                <p class="text-sm text-[#667085] mb-4">Tidak ditemukan user dengan email "{{ $searchQuery }}". Pastikan orangnya sudah punya akun SMARTS Work (personal) dulu.</p>
            @endif
        @endif

        <div class="space-y-1.5">
            @foreach ($business->users as $u)
                <div class="flex items-center justify-between text-sm py-1.5 border-t border-[#F2F4F7] first:border-t-0">
                    <div>
                        <span class="text-[#101828]">{{ $u->name }}</span>
                        <span class="text-xs text-[#667085] ml-2">{{ $u->pivot->role }}</span>
                    </div>
                    @if ($u->id !== $business->owner_id)
                        <form method="POST" action="{{ route('business.settings.remove-member', [$business, $u]) }}" onsubmit="return confirm('Keluarkan {{ $u->name }} dari business ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-[#D92D20]">Keluarkan</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-business-layout>
