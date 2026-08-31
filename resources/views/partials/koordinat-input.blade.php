@props(['latitude' => null, 'longitude' => null])

<div>
    <x-input-label value="Titik Koordinat (opsional)" />
    <div class="grid grid-cols-2 gap-3 mt-1">
        <x-text-input id="latitude" name="latitude" type="text" inputmode="decimal" placeholder="Latitude"
            value="{{ old('latitude', $latitude) }}" class="block w-full" />
        <x-text-input id="longitude" name="longitude" type="text" inputmode="decimal" placeholder="Longitude"
            value="{{ old('longitude', $longitude) }}" class="block w-full" />
    </div>
    <button type="button" onclick="swkAmbilLokasi(this)"
        class="mt-2 text-xs text-[#2563EB] font-medium flex items-center gap-1">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
        Gunakan lokasi saat ini
    </button>
    <p class="text-[11px] text-[#9CA3AF] mt-1" id="swk-lokasi-status"></p>
    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
</div>

<script>
function swkAmbilLokasi(btn) {
    const status = document.getElementById('swk-lokasi-status');
    if (!navigator.geolocation) {
        status.textContent = 'Browser tidak mendukung GPS.';
        return;
    }
    status.textContent = 'Mengambil lokasi...';
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
            document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
            status.textContent = 'Lokasi berhasil diambil.';
        },
        (err) => {
            status.textContent = 'Gagal mengambil lokasi: ' + err.message;
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}
</script>
