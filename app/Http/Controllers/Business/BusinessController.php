<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ChartOfAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessController extends Controller
{
    /**
     * Daftar business yang diikuti user - jadi "pintu masuk" SMARTS Business.
     * User HARUS sudah punya akun personal (otomatis, karena route ini
     * di belakang middleware auth) sebelum bisa sampai ke sini.
     */
    public function index(Request $request): View
    {
        $businesses = $request->user()->businesses;

        return view('business.index', compact('businesses'));
    }

    public function create(): View
    {
        return view('business.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_usaha' => ['required', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'jenis_usaha' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
        ]);

        $business = Business::create([
            ...$validated,
            'owner_id' => $request->user()->id,
        ]);

        $business->users()->attach($request->user()->id, ['role' => 'owner']);

        $this->seedDefaultAccounts($business);

        $request->session()->put('current_business_id', $business->id);

        return redirect()->route('business.dashboard', $business)
            ->with('status', 'Business "'.$business->nama_usaha.'" berhasil dibuat, lengkap dengan Chart of Account standar.');
    }

    public function switchTo(Request $request, Business $business): RedirectResponse
    {
        abort_unless($request->user()->businesses->contains($business->id), 403);

        $request->session()->put('current_business_id', $business->id);

        return redirect()->route('business.dashboard', $business);
    }

    public function editSettings(Business $business): View
    {
        return view('business.settings', compact('business'));
    }

    public function updateSettings(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'nama_usaha' => ['required', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:30'],
            'jenis_usaha' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'mata_uang' => ['required', 'string', 'size:3'],
        ]);

        $business->update($validated);

        return redirect()->route('business.settings.edit', $business)->with('status', 'Pengaturan business berhasil disimpan.');
    }

    /**
     * Chart of Account standar Indonesia supaya business baru tidak mulai
     * dari kosong sama sekali.
     */
    protected function seedDefaultAccounts(Business $business): void
    {
        $default = [
            // Aset
            ['1-1000', 'Kas', 'aset', 'debit'],
            ['1-1100', 'Bank', 'aset', 'debit'],
            ['1-1200', 'Piutang Usaha', 'aset', 'debit'],
            ['1-1300', 'Persediaan', 'aset', 'debit'],
            ['1-1400', 'Perlengkapan', 'aset', 'debit'],
            ['1-2000', 'Peralatan', 'aset', 'debit'],
            ['1-2100', 'Akumulasi Depresiasi Peralatan', 'aset', 'kredit'],
            // Kewajiban
            ['2-1000', 'Utang Usaha', 'kewajiban', 'kredit'],
            ['2-1100', 'Utang Bank', 'kewajiban', 'kredit'],
            ['2-1200', 'Utang Pajak', 'kewajiban', 'kredit'],
            // Ekuitas
            ['3-1000', 'Modal Pemilik', 'ekuitas', 'kredit'],
            ['3-2000', 'Prive/Penarikan Pemilik', 'ekuitas', 'debit'],
            ['3-3000', 'Laba Ditahan', 'ekuitas', 'kredit'],
            // Pendapatan
            ['4-1000', 'Pendapatan Usaha', 'pendapatan', 'kredit'],
            ['4-2000', 'Pendapatan Lain-lain', 'pendapatan', 'kredit'],
            // Beban
            ['5-1000', 'Beban Gaji', 'beban', 'debit'],
            ['5-1100', 'Beban Sewa', 'beban', 'debit'],
            ['5-1200', 'Beban Listrik & Air', 'beban', 'debit'],
            ['5-1300', 'Beban Perlengkapan', 'beban', 'debit'],
            ['5-1400', 'Beban Depresiasi', 'beban', 'debit'],
            ['5-1500', 'Beban Operasional Lain', 'beban', 'debit'],
            ['5-2000', 'Beban Pajak', 'beban', 'debit'],
        ];

        foreach ($default as [$kode, $nama, $tipe, $saldoNormal]) {
            ChartOfAccount::create([
                'business_id' => $business->id,
                'kode' => $kode,
                'nama' => $nama,
                'tipe' => $tipe,
                'saldo_normal' => $saldoNormal,
            ]);
        }
    }
}
