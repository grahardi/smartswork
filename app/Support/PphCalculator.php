<?php

namespace App\Support;

class PphCalculator
{
    /**
     * Hitung estimasi PPh berdasarkan skema.
     *
     * @param string $skema 'umkm_final' | 'badan_normal' | 'custom'
     * @param float $omzet Total pendapatan/omzet setahun
     * @param float $laba Laba (bisa negatif)
     * @param float|null $customPersen Persentase custom (0-100), dipakai kalau $skema = 'custom'
     * @param string|null $customBasis 'omzet' atau 'laba', dipakai kalau $skema = 'custom'
     * @return array{pajak: float, label: string, detail: string|null}
     */
    public static function hitung(
        string $skema,
        float $omzet,
        float $laba,
        ?float $customPersen = null,
        ?string $customBasis = null
    ): array {
        if ($skema === 'umkm_final') {
            return [
                'pajak' => $omzet * 0.005,
                'label' => 'PPh Final UMKM (0,5% x Omzet)',
                'detail' => 'PP 55/2022 - berlaku untuk omzet ≤ Rp4,8 miliar/tahun, maksimal 3-4 tahun pemakaian.',
            ];
        }

        if ($skema === 'custom') {
            $persen = $customPersen ?? 0;
            $basis = $customBasis === 'omzet' ? $omzet : max($laba, 0);
            $basisLabel = $customBasis === 'omzet' ? 'Omzet' : 'Laba Kena Pajak';

            return [
                'pajak' => $basis * ($persen / 100),
                'label' => "Persentase Sendiri ({$persen}% x {$basisLabel})",
                'detail' => 'Tarif ditentukan manual, bukan dari aturan resmi tertentu.',
            ];
        }

        // badan_normal - dengan fasilitas Pasal 31E (diskon 50% untuk bagian
        // laba dari omzet sampai Rp4,8 miliar, kalau omzet total ≤ Rp50 miliar).
        $labaKenaPajak = max($laba, 0);

        if ($omzet <= 0 || $labaKenaPajak <= 0) {
            return [
                'pajak' => 0,
                'label' => 'PPh Badan (22%, dengan fasilitas Pasal 31E)',
                'detail' => 'Tidak ada laba kena pajak.',
            ];
        }

        $batasFasilitas = 4_800_000_000; // Rp4,8 miliar
        $batasMaksimalOmzet = 50_000_000_000; // Rp50 miliar

        if ($omzet > $batasMaksimalOmzet) {
            // Omzet di atas 50M, tidak ada fasilitas sama sekali - flat 22%.
            return [
                'pajak' => $labaKenaPajak * 0.22,
                'label' => 'PPh Badan (22%, tanpa fasilitas - omzet > Rp50 miliar)',
                'detail' => null,
            ];
        }

        // Proporsi laba yang kena diskon 50% = (4,8M / omzet) x laba kena pajak, dibatasi maksimal 100%.
        $proporsiFasilitas = min($batasFasilitas / $omzet, 1);
        $labaFasilitas = $labaKenaPajak * $proporsiFasilitas;
        $labaNonFasilitas = $labaKenaPajak - $labaFasilitas;

        $pajak = ($labaFasilitas * 0.22 * 0.5) + ($labaNonFasilitas * 0.22);

        return [
            'pajak' => $pajak,
            'label' => 'PPh Badan (22%, dengan fasilitas Pasal 31E)',
            'detail' => 'Diskon 50% (jadi efektif 11%) untuk bagian laba dari omzet s.d. Rp4,8 miliar.',
        ];
    }
}
