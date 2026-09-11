<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\FinanceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RekeningController extends Controller
{
    public function index(Request $request): View
    {
        $rekenings = $request->user()->bankAccounts()->get();

        $cashFisik = $this->hitungCashFisik($request->user());

        return view('rekening.index', compact('rekenings', 'cashFisik'));
    }

    public function create(): View
    {
        return view('rekening.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $request->user()->bankAccounts()->create($validated);

        return redirect()->route('rekening.index')->with('status', 'Rekening berhasil ditambahkan.');
    }

    public function edit(Request $request, BankAccount $rekening): View
    {
        $this->authorizeOwner($request, $rekening);

        return view('rekening.edit', compact('rekening'));
    }

    public function update(Request $request, BankAccount $rekening): RedirectResponse
    {
        $this->authorizeOwner($request, $rekening);

        $validated = $this->validated($request);

        $rekening->update($validated);

        return redirect()->route('rekening.index')->with('status', 'Rekening berhasil diperbarui.');
    }

    public function destroy(Request $request, BankAccount $rekening): RedirectResponse
    {
        $this->authorizeOwner($request, $rekening);

        if ($rekening->financeTransactions()->exists()) {
            return back()->with('error', 'Rekening ini sudah dipakai di transaksi, tidak bisa dihapus.');
        }

        $rekening->delete();

        return redirect()->route('rekening.index')->with('status', 'Rekening berhasil dihapus.');
    }

    public function pindahForm(Request $request): View
    {
        $rekenings = $request->user()->bankAccounts()->get();

        return view('rekening.pindah', compact('rekenings'));
    }

    /**
     * Pindah Saldo - generalisasi dari Cash/Bank/E-wallet manapun ke
     * Cash/Bank/E-wallet manapun. Total kekayaan tidak berubah, cuma
     * pindah "kantong". 2 baris transaksi otomatis dibuat, dikelompokkan
     * lewat kategori "Pindah Saldo" yang dibuat otomatis kalau belum ada.
     */
    public function pindahStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dari' => ['required', 'string'], // 'cash' atau id rekening
            'ke' => ['required', 'string', 'different:dari'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $ambilRekening = function ($val) use ($user) {
            return $val === 'cash' ? null : $user->bankAccounts()->findOrFail($val);
        };

        $dariRekening = $ambilRekening($validated['dari']);
        $keRekening = $ambilRekening($validated['ke']);

        DB::transaction(function () use ($user, $dariRekening, $keRekening, $validated) {
            $kategoriKeluar = FinanceCategory::firstOrCreate(
                ['user_id' => $user->id, 'nama' => 'Pindah Saldo', 'type' => 'pengeluaran'],
                ['warna' => '#667085']
            );
            $kategoriMasuk = FinanceCategory::firstOrCreate(
                ['user_id' => $user->id, 'nama' => 'Pindah Saldo', 'type' => 'pemasukan'],
                ['warna' => '#667085']
            );

            $ket = $validated['keterangan'] ?? null;
            $labelDari = $dariRekening ? $dariRekening->nama_bank : 'Cash';
            $labelKe = $keRekening ? $keRekening->nama_bank : 'Cash';

            $user->financeTransactions()->create([
                'finance_category_id' => $kategoriKeluar->id,
                'bank_account_id' => $dariRekening?->id,
                'tanggal' => now()->toDateString(),
                'jumlah' => $validated['jumlah'],
                'keterangan' => 'Pindah ke '.$labelKe.($ket ? ': '.$ket : ''),
            ]);
            $user->financeTransactions()->create([
                'finance_category_id' => $kategoriMasuk->id,
                'bank_account_id' => $keRekening?->id,
                'tanggal' => now()->toDateString(),
                'jumlah' => $validated['jumlah'],
                'keterangan' => 'Pindah dari '.$labelDari.($ket ? ': '.$ket : ''),
            ]);
        });

        return redirect()->route('rekening.index')->with('status', 'Saldo berhasil dipindah - total kekayaan tidak berubah.');
    }

    /**
     * Cash fisik = semua transaksi TANPA bank_account_id (dianggap tunai).
     */
    protected function hitungCashFisik($user): float
    {
        $masuk = (float) $user->financeTransactions()
            ->whereNull('bank_account_id')
            ->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))
            ->sum('jumlah');

        $keluar = (float) $user->financeTransactions()
            ->whereNull('bank_account_id')
            ->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))
            ->sum('jumlah');

        return $masuk - $keluar;
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'jenis' => ['required', 'in:bank,ewallet'],
            'provider' => ['nullable', 'required_if:jenis,ewallet', 'in:ovo,gopay,dana,shopeepay,linkaja,lainnya'],
            'nama_bank' => ['required', 'string', 'max:255'],
            'no_rekening' => ['nullable', 'string', 'max:50'],
            'saldo_awal' => ['required', 'numeric'],
        ]);
    }

    protected function authorizeOwner(Request $request, BankAccount $rekening): void
    {
        abort_unless($rekening->user_id === $request->user()->id, 403);
    }
}
