@php $residence = $residence ?? null; @endphp

<div>
    <x-input-label for="label" value="Nama Tempat Tinggal" />
    <x-text-input id="label" name="label" type="text" class="mt-1 block w-full"
        value="{{ old('label', $residence->label ?? '') }}" required autofocus placeholder="Contoh: Rumah, Kos, Rumah Orang Tua" />
    <x-input-error :messages="$errors->get('label')" class="mt-2" />
</div>

<div>
    <x-input-label for="alamat" value="Alamat" />
    <textarea id="alamat" name="alamat" rows="3"
        class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('alamat', $residence->alamat ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
</div>

<x-koordinat-input :latitude="$residence->latitude ?? null" :longitude="$residence->longitude ?? null" />

<label class="flex items-center gap-2 text-sm text-[#262135]">
    <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $residence->is_default ?? false))>
    Jadikan tempat tinggal utama
</label>
