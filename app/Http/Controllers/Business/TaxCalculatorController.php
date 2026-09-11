<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Support\PphCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxCalculatorController extends Controller
{
    public function index(Request $request, Business $business): View
    {
        $hasil = null;

        if ($request->filled('pemasukan') || $request->filled('pengeluaran')) {
            $periode = $request->input('periode', 'bulanan'); // bulanan | tahunan
            $pemasukan = (float) $request->input('pemasukan', 0);
            $pengeluaran = (float) $request->input('pengeluaran', 0);
            $skema = $request->input('skema_pajak', $business->skema_pajak);
            $customPersen = $request->input('pajak_custom_persen');
            $customBasis = $request->input('pajak_custom_basis');

            if ($periode === 'bulanan') {
                $omzetTahunan = $pemasukan * 12;
                $pengeluaranTahunan = $pengeluaran * 12;
            } else {
                $omzetTahunan = $pemasukan;
                $pengeluaranTahunan = $pengeluaran;
            }

            $labaTahunan = $omzetTahunan - $pengeluaranTahunan;

            $hasilPph = PphCalculator::hitung(
                $skema,
                $omzetTahunan,
                $labaTahunan,
                $customPersen !== null ? (float) $customPersen : null,
                $customBasis
            );

            $hasil = [
                'periode' => $periode,
                'skema' => $skema,
                'omzet_tahunan' => $omzetTahunan,
                'pengeluaran_tahunan' => $pengeluaranTahunan,
                'laba_tahunan' => $labaTahunan,
                'pajak_setahun' => $hasilPph['pajak'],
                'pajak_per_bulan' => $hasilPph['pajak'] / 12,
                'label' => $hasilPph['label'],
                'detail' => $hasilPph['detail'],
            ];
        }

        return view('business.tax.kalkulator', compact('business', 'hasil'));
    }
}
