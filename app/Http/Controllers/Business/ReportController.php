<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Neraca Saldo (Trial Balance) - semua akun dengan saldo per tanggal.
     */
    public function neracaSaldo(Request $request, Business $business): View
    {
        $sampaiTanggal = $request->input('sampai', now()->toDateString());

        $accounts = $business->accounts()->orderBy('kode')->get()->map(function ($account) use ($sampaiTanggal) {
            $saldo = $account->saldo($sampaiTanggal);
            return [
                'account' => $account,
                'debit' => $saldo > 0 && $account->saldo_normal === 'debit' ? $saldo : ($saldo < 0 && $account->saldo_normal === 'kredit' ? abs($saldo) : 0),
                'kredit' => $saldo > 0 && $account->saldo_normal === 'kredit' ? $saldo : ($saldo < 0 && $account->saldo_normal === 'debit' ? abs($saldo) : 0),
            ];
        })->filter(fn ($row) => $row['debit'] != 0 || $row['kredit'] != 0);

        $totalDebit = $accounts->sum('debit');
        $totalKredit = $accounts->sum('kredit');

        return view('business.reports.neraca-saldo', compact('business', 'accounts', 'totalDebit', 'totalKredit', 'sampaiTanggal'));
    }

    /**
     * Buku Besar (General Ledger) - detail transaksi per akun tertentu.
     */
    public function bukuBesar(Request $request, Business $business, ChartOfAccount $account): View
    {
        abort_unless($account->business_id === $business->id, 403);

        $lines = $account->lines()
            ->with('journalEntry')
            ->whereHas('journalEntry')
            ->get()
            ->sortBy(fn ($l) => $l->journalEntry->tanggal);

        $saldoBerjalan = 0;
        $rows = $lines->map(function ($line) use (&$saldoBerjalan, $account) {
            $saldoBerjalan += $account->saldo_normal === 'debit'
                ? $line->debit - $line->kredit
                : $line->kredit - $line->debit;

            return [
                'tanggal' => $line->journalEntry->tanggal,
                'keterangan' => $line->keterangan ?? $line->journalEntry->keterangan,
                'debit' => $line->debit,
                'kredit' => $line->kredit,
                'saldo' => $saldoBerjalan,
            ];
        });

        return view('business.reports.buku-besar', compact('business', 'account', 'rows'));
    }

    /**
     * Neraca (Balance Sheet): Aset = Kewajiban + Ekuitas.
     */
    public function neraca(Request $request, Business $business): View
    {
        $sampaiTanggal = $request->input('sampai', now()->toDateString());

        $labaTahunIni = $this->hitungLabaRugi($business, null, $sampaiTanggal)['laba_bersih'];

        $kelompok = ['aset', 'kewajiban', 'ekuitas'];
        $data = [];
        foreach ($kelompok as $tipe) {
            $accounts = $business->accounts()->where('tipe', $tipe)->orderBy('kode')->get();
            $data[$tipe] = $accounts->map(fn ($a) => ['account' => $a, 'saldo' => $a->saldo($sampaiTanggal)])
                ->filter(fn ($row) => $row['saldo'] != 0);
        }

        $totalAset = $data['aset']->sum('saldo');
        $totalKewajiban = $data['kewajiban']->sum('saldo');
        $totalEkuitas = $data['ekuitas']->sum('saldo') + $labaTahunIni;

        return view('business.reports.neraca', compact(
            'business', 'data', 'totalAset', 'totalKewajiban', 'totalEkuitas', 'labaTahunIni', 'sampaiTanggal'
        ));
    }

    /**
     * Laporan Laba Rugi (Income Statement), dengan rentang tanggal.
     * Format ini juga bisa jadi acuan pengisian laporan pajak/Coretax
     * (bukan integrasi resmi, cuma referensi angka).
     */
    public function labaRugi(Request $request, Business $business): View
    {
        $dari = $request->input('dari', now()->startOfYear()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $hasil = $this->hitungLabaRugi($business, $dari, $sampai);

        return view('business.reports.laba-rugi', array_merge(compact('business', 'dari', 'sampai'), $hasil));
    }

    /**
     * Wrapper public supaya bisa dipakai controller lain (TaxController)
     * tanpa mengubah visibility method aslinya.
     */
    public function hitungLabaRugiPublic(Business $business, ?string $dari, string $sampai): array
    {
        return $this->hitungLabaRugi($business, $dari, $sampai);
    }

    protected function hitungLabaRugi(Business $business, ?string $dari, string $sampai): array
    {
        $pendapatanAccounts = $business->accounts()->where('tipe', 'pendapatan')->orderBy('kode')->get();
        $bebanAccounts = $business->accounts()->where('tipe', 'beban')->orderBy('kode')->get();

        $hitung = function ($account) use ($dari, $sampai) {
            $saldoSampai = $account->saldo($sampai);
            $saldoDari = $dari ? $account->saldo($dari) : 0;
            return $saldoSampai - $saldoDari;
        };

        $pendapatan = $pendapatanAccounts->map(fn ($a) => ['account' => $a, 'jumlah' => $hitung($a)])->filter(fn ($r) => $r['jumlah'] != 0);
        $beban = $bebanAccounts->map(fn ($a) => ['account' => $a, 'jumlah' => $hitung($a)])->filter(fn ($r) => $r['jumlah'] != 0);

        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalBeban = $beban->sum('jumlah');

        return [
            'pendapatan' => $pendapatan,
            'beban' => $beban,
            'total_pendapatan' => $totalPendapatan,
            'total_beban' => $totalBeban,
            'laba_bersih' => $totalPendapatan - $totalBeban,
        ];
    }
}
