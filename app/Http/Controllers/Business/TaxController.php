<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Support\PphCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxController extends Controller
{
    public function index(Request $request, Business $business): View
    {
        $dari = $request->input('dari', now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $reportController = new ReportController();
        $labaRugi = $reportController->hitungLabaRugiPublic($business, $dari, $sampai);

        $omzet = $labaRugi['total_pendapatan'];
        $labaBersih = $labaRugi['laba_bersih'];

        $hasilPph = PphCalculator::hitung(
            $business->skema_pajak,
            $omzet,
            $labaBersih,
            $business->pajak_custom_persen ? (float) $business->pajak_custom_persen : null,
            $business->pajak_custom_basis
        );

        // PPN terutang = saldo PPN Keluaran (kredit) - saldo PPN Masukan (debit), sampai tanggal akhir periode.
        $ppnKeluaran = $business->accounts()->where('nama', 'PPN Keluaran')->first();
        $ppnMasukan = $business->accounts()->where('nama', 'PPN Masukan')->first();

        $totalPpnKeluaran = $ppnKeluaran ? $ppnKeluaran->saldo($sampai) : 0;
        $totalPpnMasukan = $ppnMasukan ? $ppnMasukan->saldo($sampai) : 0;
        $ppnTerutang = $totalPpnKeluaran - $totalPpnMasukan;

        return view('business.tax.index', [
            'business' => $business,
            'dari' => $dari,
            'sampai' => $sampai,
            'omzet' => $omzet,
            'labaBersih' => $labaBersih,
            'pphTerutang' => $hasilPph['pajak'],
            'pphLabel' => $hasilPph['label'],
            'pphDetail' => $hasilPph['detail'],
            'totalPpnKeluaran' => $totalPpnKeluaran,
            'totalPpnMasukan' => $totalPpnMasukan,
            'ppnTerutang' => $ppnTerutang,
        ]);
    }

    public function updateSkema(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'skema_pajak' => ['required', 'in:umkm_final,badan_normal,custom'],
            'pajak_custom_persen' => ['required_if:skema_pajak,custom', 'nullable', 'numeric', 'min:0', 'max:100'],
            'pajak_custom_basis' => ['required_if:skema_pajak,custom', 'nullable', 'in:omzet,laba'],
        ]);

        $business->update($validated);

        return redirect()->route('business.tax.index', $business)->with('status', 'Skema pajak berhasil diubah.');
    }
}
