<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxController extends Controller
{
    public function index(Request $request, Business $business): View
    {
        $dari = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        // Omzet & laba dari mesin Laba Rugi yang sudah ada.
        $reportController = new ReportController();
        $labaRugi = $reportController->hitungLabaRugiPublic($business, $dari, $sampai);

        $omzet = $labaRugi['total_pendapatan'];
        $labaBersih = $labaRugi['laba_bersih'];

        // Estimasi PPh sesuai skema yang dipilih di Pengaturan Bisnis.
        if ($business->skema_pajak === 'umkm_final') {
            $pphTerutang = $omzet * 0.005; // PPh Final 0.5% x omzet (PP 55/2022)
            $pphLabel = 'PPh Final UMKM (0,5% x Omzet)';
        } else {
            $labaKenaPajak = max($labaBersih, 0);
            $pphTerutang = $labaKenaPajak * 0.22; // PPh Badan 22% x laba kena pajak (disederhanakan)
            $pphLabel = 'PPh Badan (22% x Laba Kena Pajak)';
        }

        // PPN terutang = saldo PPN Keluaran (kredit) - saldo PPN Masukan (debit), sampai tanggal akhir periode.
        $ppnKeluaran = $business->accounts()->where('nama', 'PPN Keluaran')->first();
        $ppnMasukan = $business->accounts()->where('nama', 'PPN Masukan')->first();

        $totalPpnKeluaran = $ppnKeluaran ? $ppnKeluaran->saldo($sampai) : 0;
        $totalPpnMasukan = $ppnMasukan ? $ppnMasukan->saldo($sampai) : 0;
        $ppnTerutang = $totalPpnKeluaran - $totalPpnMasukan;

        return view('business.tax.index', compact(
            'business', 'dari', 'sampai', 'omzet', 'labaBersih',
            'pphTerutang', 'pphLabel', 'totalPpnKeluaran', 'totalPpnMasukan', 'ppnTerutang'
        ));
    }

    public function updateSkema(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'skema_pajak' => ['required', 'in:umkm_final,badan_normal'],
        ]);

        $business->update($validated);

        return redirect()->route('business.tax.index', $business)->with('status', 'Skema pajak berhasil diubah.');
    }
}
