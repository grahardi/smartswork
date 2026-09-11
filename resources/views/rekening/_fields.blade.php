@php $rekening = $rekening ?? null; @endphp

<div>
    <x-input-label value="Jenis" />
    <div class="mt-2 flex gap-4">
        <label class="flex items-center gap-2 text-sm text-[#262135]">
            <input type="radio" name="jenis" value="bank" onchange="document.getElementById('provider-wrap').classList.add('hidden')" {{ old('jenis', $rekening->jenis ?? 'bank') === 'bank' ? 'checked' : '' }}>
            🏦 Rekening Bank
        </label>
        <label class="flex items-center gap-2 text-sm text-[#262135]">
            <input type="radio" name="jenis" value="ewallet" onchange="document.getElementById('provider-wrap').classList.remove('hidden')" {{ old('jenis', $rekening->jenis ?? '') === 'ewallet' ? 'checked' : '' }}>
            📱 E-Wallet
        </label>
    </div>
</div>

<div id="provider-wrap" class="{{ old('jenis', $rekening->jenis ?? 'bank') === 'ewallet' ? '' : 'hidden' }}">
    <x-input-label for="provider" value="Provider E-Wallet" />
    <select id="provider" name="provider" class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
        @foreach (['ovo' => 'OVO', 'gopay' => 'GoPay', 'dana' => 'DANA', 'shopeepay' => 'ShopeePay', 'linkaja' => 'LinkAja', 'lainnya' => 'Lainnya'] as $val => $lbl)
            <option value="{{ $val }}" @selected(old('provider', $rekening->provider ?? '') === $val)>{{ $lbl }}</option>
        @endforeach
    </select>
</div>

<div>
    <x-input-label for="nama_bank" value="Nama Bank / E-Wallet" />
    <x-text-input id="nama_bank" name="nama_bank" type="text" class="mt-1 block w-full" value="{{ old('nama_bank', $rekening->nama_bank ?? '') }}" required autofocus placeholder="Contoh: BCA, ShopeePay, OVO" />
    <x-input-error :messages="$errors->get('nama_bank')" class="mt-2" />
</div>

<div>
    <x-input-label for="no_rekening" value="No. Rekening / No. HP (opsional)" />
    <x-text-input id="no_rekening" name="no_rekening" type="text" class="mt-1 block w-full" value="{{ old('no_rekening', $rekening->no_rekening ?? '') }}" />
    <x-input-error :messages="$errors->get('no_rekening')" class="mt-2" />
</div>

<div>
    <x-input-label for="saldo_awal" value="Saldo Sekarang" />
    <x-text-input id="saldo_awal" name="saldo_awal" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('saldo_awal', $rekening->saldo_awal ?? '') }}" required />
    <p class="text-[11px] text-[#9CA3AF] mt-1">Isi saldo yang ada sekarang. Transaksi berikutnya yang ditandai ke sini akan menambah/mengurangi dari angka ini.</p>
    <x-input-error :messages="$errors->get('saldo_awal')" class="mt-2" />
</div>
