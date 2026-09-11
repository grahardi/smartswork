@php $account = $account ?? null; @endphp

<div>
    <x-input-label for="kode" value="Kode Akun" />
    <x-text-input id="kode" name="kode" type="text" class="mt-1 block w-full" value="{{ old('kode', $account->kode ?? '') }}" required placeholder="Contoh: 1-1500" />
    <x-input-error :messages="$errors->get('kode')" class="mt-2" />
</div>

<div>
    <x-input-label for="nama" value="Nama Akun" />
    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" value="{{ old('nama', $account->nama ?? '') }}" required />
    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
</div>

<div>
    <x-input-label value="Tipe" />
    <select name="tipe" required class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
        @foreach (['aset' => 'Aset', 'kewajiban' => 'Kewajiban', 'ekuitas' => 'Ekuitas', 'pendapatan' => 'Pendapatan', 'beban' => 'Beban'] as $val => $lbl)
            <option value="{{ $val }}" @selected(old('tipe', $account->tipe ?? '') === $val)>{{ $lbl }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('tipe')" class="mt-2" />
</div>

<div>
    <x-input-label value="Saldo Normal" />
    <div class="mt-2 flex gap-4">
        <label class="flex items-center gap-2 text-sm text-[#101828]">
            <input type="radio" name="saldo_normal" value="debit" @checked(old('saldo_normal', $account->saldo_normal ?? 'debit') === 'debit')>
            Debit
        </label>
        <label class="flex items-center gap-2 text-sm text-[#101828]">
            <input type="radio" name="saldo_normal" value="kredit" @checked(old('saldo_normal', $account->saldo_normal ?? '') === 'kredit')>
            Kredit
        </label>
    </div>
    <p class="text-[11px] text-[#98A2B3] mt-1">Aset & Beban normalnya Debit. Kewajiban, Ekuitas & Pendapatan normalnya Kredit.</p>
    <x-input-error :messages="$errors->get('saldo_normal')" class="mt-2" />
</div>

@if ($account)
    <label class="flex items-center gap-2 text-sm text-[#101828]">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account->is_active))>
        Akun aktif
    </label>
@endif
