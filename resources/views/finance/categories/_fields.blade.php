@php $category = $category ?? null; @endphp

<div>
    <x-input-label for="nama" value="Nama Kategori" />
    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
        value="{{ old('nama', $category->nama ?? '') }}" required autofocus placeholder="Contoh: Gaji, Transport, Makan" />
    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
</div>

<div>
    <x-input-label value="Jenis" />
    <div class="mt-2 flex gap-4">
        <label class="flex items-center gap-2 text-sm text-[#1F2333]">
            <input type="radio" name="type" value="pemasukan" @checked(old('type', $category->type ?? 'pemasukan') === 'pemasukan')>
            Pemasukan
        </label>
        <label class="flex items-center gap-2 text-sm text-[#1F2333]">
            <input type="radio" name="type" value="pengeluaran" @checked(old('type', $category->type ?? '') === 'pengeluaran')>
            Pengeluaran
        </label>
    </div>
    <x-input-error :messages="$errors->get('type')" class="mt-2" />
</div>

<div>
    <x-input-label for="warna" value="Warna" />
    <input id="warna" name="warna" type="color" value="{{ old('warna', $category->warna ?? '#4F46E5') }}"
        class="mt-1 block w-16 h-10 rounded-lg border border-[#E5E7F5]">
    <x-input-error :messages="$errors->get('warna')" class="mt-2" />
</div>
