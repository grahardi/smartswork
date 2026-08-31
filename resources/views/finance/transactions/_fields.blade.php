@php $transaction = $transaction ?? null; @endphp

<div>
    <x-input-label for="finance_category_id" value="Kategori" />
    <select id="finance_category_id" name="finance_category_id" required
        class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">
        <option value="">Pilih kategori</option>
        <optgroup label="Pemasukan">
            @foreach ($categories->where('type', 'pemasukan') as $cat)
                <option value="{{ $cat->id }}" @selected(old('finance_category_id', $transaction->finance_category_id ?? '') == $cat->id)>{{ $cat->nama }}</option>
            @endforeach
        </optgroup>
        <optgroup label="Pengeluaran">
            @foreach ($categories->where('type', 'pengeluaran') as $cat)
                <option value="{{ $cat->id }}" @selected(old('finance_category_id', $transaction->finance_category_id ?? '') == $cat->id)>{{ $cat->nama }}</option>
            @endforeach
        </optgroup>
    </select>
    @if ($categories->isEmpty())
        <p class="text-xs text-[#DC2626] mt-1">Belum ada kategori. <a href="{{ route('finance.categories.create') }}" class="underline">Tambah dulu</a>.</p>
    @endif
    <x-input-error :messages="$errors->get('finance_category_id')" class="mt-2" />
</div>

<div class="grid grid-cols-2 gap-3">
    <div>
        <x-input-label for="tanggal" value="Tanggal" />
        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full"
            value="{{ old('tanggal', optional($transaction->tanggal ?? null)->format('Y-m-d') ?? now()->toDateString()) }}" required />
        <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="jumlah" value="Jumlah (Rp)" />
        <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="0" class="mt-1 block w-full"
            value="{{ old('jumlah', $transaction->jumlah ?? '') }}" required />
        <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="workplace_id" value="Tempat Kerja (opsional)" />
    <select id="workplace_id" name="workplace_id" class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">
        <option value="">—</option>
        @foreach ($workplaces as $w)
            <option value="{{ $w->id }}" @selected(old('workplace_id', $transaction->workplace_id ?? '') == $w->id)>{{ $w->nama }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('workplace_id')" class="mt-2" />
</div>

<div>
    <x-input-label for="keterangan" value="Keterangan (opsional)" />
    <textarea id="keterangan" name="keterangan" rows="2"
        class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#4F46E5] focus:ring-[#4F46E5] text-sm">{{ old('keterangan', $transaction->keterangan ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
</div>
