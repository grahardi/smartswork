<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
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

            if ($periode === 'bulanan') {
                $omzetTahunan = $pemasukan * 12;
                $pengeluaranTahunan = $pengeluaran * 12;
            } else {
                $omzetTahunan = $pemasukan;
                $pengeluaranTahunan = $pengeluaran;
            }

            $labaTahunan = $omzetTahunan - $pengeluaranTahunan;

            if ($business->skema_pajak === 'umkm_final') {
                $pajakSetahun = $omzetTahunan * 0.005;
                $label = 'PPh Final UMKM (0,5% x Omzet Setahun)';
            } else {
                $labaKenaPajak = max($labaTahunan, 0);
                $pajakSetahun = $labaKenaPajak * 0.22;
                $label = 'PPh Badan (22% x Laba Kena Pajak Setahun)';
            }

            $hasil = [
                'periode' => $periode,
                'pemasukan_input' => $pemasukan,
                'pengeluaran_input' => $pengeluaran,
                'omzet_tahunan' => $omzetTahunan,
                'pengeluaran_tahunan' => $pengeluaranTahunan,
                'laba_tahunan' => $labaTahunan,
                'pajak_setahun' => $pajakSetahun,
                'pajak_per_bulan' => $pajakSetahun / 12,
                'label' => $label,
            ];
        }

        return view('business.tax.kalkulator', compact('business', 'hasil'));
    }
}
