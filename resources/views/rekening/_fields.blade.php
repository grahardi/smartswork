@php $rekening = $rekening ?? null; @endphp

<div>
    <x-input-label for="nama_bank" value="Nama Bank" />
    <x-text-input id="nama_bank" name="nama_bank" type="text" class="mt-1 block w-full" value="{{ old('nama_bank', $rekening->nama_bank ?? '') }}" required autofocus placeholder="Contoh: BCA, Mandiri, BRI" />
    <x-input-error :messages="$errors->get('nama_bank')" class="mt-2" />
</div>

<div>
    <x-input-label for="no_rekening" value="No. Rekening (opsional)" />
    <x-text-input id="no_rekening" name="no_rekening" type="text" class="mt-1 block w-full" value="{{ old('no_rekening', $rekening->no_rekening ?? '') }}" />
    <x-input-error :messages="$errors->get('no_rekening')" class="mt-2" />
</div>

<div>
    <x-input-label for="saldo_awal" value="Saldo Sekarang" />
    <x-text-input id="saldo_awal" name="saldo_awal" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('saldo_awal', $rekening->saldo_awal ?? '') }}" required />
    <p class="text-[11px] text-[#9CA3AF] mt-1">Isi saldo yang ada sekarang di rekening ini. Transaksi berikutnya yang ditandai ke rekening ini akan menambah/mengurangi dari angka ini.</p>
    <x-input-error :messages="$errors->get('saldo_awal')" class="mt-2" />
</div>
