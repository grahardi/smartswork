@props(['latitude' => null, 'longitude' => null])

@php
    $mapId = 'swk-map-' . uniqid();
    $lat = old('latitude', $latitude) ?: -8.1983;
    $lng = old('longitude', $longitude) ?: 112.6778;
    $hasInitial = !is_null(old('latitude', $latitude));
@endphp

<div
    x-data="swkCoordPicker({
        lat: {{ $lat }},
        lng: {{ $lng }},
        hasInitial: {{ $hasInitial ? 'true' : 'false' }},
        mapId: '{{ $mapId }}'
    })"
    x-init="init()"
>
    <x-input-label value="Titik Koordinat (opsional)" />

    <div id="{{ $mapId }}" class="mt-2 rounded-lg overflow-hidden border border-[#E5E7F5]" style="height: 220px;"></div>

    <div class="grid grid-cols-2 gap-3 mt-2">
        <x-text-input name="latitude" type="text" inputmode="decimal" placeholder="Latitude"
            x-model="lat" @change="updateFromInputs()" class="block w-full" />
        <x-text-input name="longitude" type="text" inputmode="decimal" placeholder="Longitude"
            x-model="lng" @change="updateFromInputs()" class="block w-full" />
    </div>

    <div class="flex flex-wrap items-center gap-3 mt-2">
        <button type="button" @click="pakaiLokasiSaatIni()" class="text-xs text-[#2563EB] font-medium flex items-center gap-1">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>
            Lokasi saat ini
        </button>
        <button type="button" @click="showPaste = !showPaste" class="text-xs text-[#7B7F99] font-medium">
            Tempel dari Google Maps
        </button>
        <a :href="googleMapsUrl()" target="_blank" class="text-xs text-[#7B7F99] font-medium">
            Buka di Google Maps ↗
        </a>
    </div>

    <div x-show="showPaste" x-cloak class="mt-2">
        <input type="text" x-model="pasteText" @input="parsePaste()"
            placeholder="Tempel link atau koordinat dari Google Maps di sini"
            class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
        <p class="text-[11px] mt-1" :class="pasteStatus.includes('berhasil') ? 'text-[#2563EB]' : 'text-[#9CA3AF]'" x-text="pasteStatus"></p>
    </div>

    <p class="text-[11px] text-[#9CA3AF] mt-1" x-text="status"></p>

    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
</div>

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endonce

<script>
function swkCoordPicker({ lat, lng, hasInitial, mapId }) {
    return {
        lat: lat,
        lng: lng,
        map: null,
        marker: null,
        status: hasInitial ? 'Klik/geser pin di peta untuk mengubah titik.' : 'Klik di peta, pakai tombol lokasi, atau tempel dari Google Maps.',
        showPaste: false,
        pasteText: '',
        pasteStatus: '',

        init() {
            this.$nextTick(() => {
                this.map = L.map(mapId).setView([this.lat, this.lng], hasInitial ? 16 : 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(this.map);

                this.marker = L.marker([this.lat, this.lng], { draggable: true }).addTo(this.map);

                this.marker.on('dragend', () => {
                    const pos = this.marker.getLatLng();
                    this.lat = pos.lat.toFixed(7);
                    this.lng = pos.lng.toFixed(7);
                    this.status = 'Titik dipindah lewat peta.';
                });

                this.map.on('click', (e) => {
                    this.lat = e.latlng.lat.toFixed(7);
                    this.lng = e.latlng.lng.toFixed(7);
                    this.marker.setLatLng(e.latlng);
                    this.status = 'Titik dipilih dari peta.';
                });
            });
        },

        updateFromInputs() {
            if (this.map && this.marker && this.lat && this.lng) {
                const ll = [parseFloat(this.lat), parseFloat(this.lng)];
                this.marker.setLatLng(ll);
                this.map.setView(ll, this.map.getZoom());
            }
        },

        pakaiLokasiSaatIni() {
            if (!navigator.geolocation) {
                this.status = 'Browser tidak mendukung GPS.';
                return;
            }
            this.status = 'Mengambil lokasi...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.lat = pos.coords.latitude.toFixed(7);
                    this.lng = pos.coords.longitude.toFixed(7);
                    this.updateFromInputs();
                    this.map.setView([this.lat, this.lng], 16);
                    this.status = 'Lokasi berhasil diambil.';
                },
                (err) => { this.status = 'Gagal mengambil lokasi: ' + err.message; },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        parsePaste() {
            // Pola umum link Google Maps: .../@-8.198,112.678,17z  atau  ?q=-8.198,112.678  atau cuma "-8.198, 112.678"
            const patterns = [
                /@(-?\d+\.\d+),(-?\d+\.\d+)/,
                /q=(-?\d+\.\d+),(-?\d+\.\d+)/,
                /(-?\d+\.\d+),\s*(-?\d+\.\d+)/,
            ];
            for (const re of patterns) {
                const m = this.pasteText.match(re);
                if (m) {
                    this.lat = parseFloat(m[1]).toFixed(7);
                    this.lng = parseFloat(m[2]).toFixed(7);
                    this.updateFromInputs();
                    this.map.setView([this.lat, this.lng], 16);
                    this.pasteStatus = 'Koordinat berhasil dibaca.';
                    this.status = 'Titik diisi dari Google Maps.';
                    return;
                }
            }
            this.pasteStatus = this.pasteText ? 'Belum ketemu koordinat di teks ini.' : '';
        },

        googleMapsUrl() {
            return `https://www.google.com/maps?q=${this.lat},${this.lng}`;
        },
    };
}
</script>
