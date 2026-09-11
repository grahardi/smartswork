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
     * Setor Tunai (cash -> rekening) atau Tarik Tunai (rekening -> cash).
     * Membuat 2 baris transaksi otomatis, dikelompokkan lewat kategori
     * "Setor/Tarik Tunai" yang dibuat otomatis kalau belum ada.
     */
    public function pindahStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'arah' => ['required', 'in:setor,tarik'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $rekening = $user->bankAccounts()->findOrFail($validated['bank_account_id']);

        DB::transaction(function () use ($user, $rekening, $validated) {
            $kategoriKeluar = FinanceCategory::firstOrCreate(
                ['user_id' => $user->id, 'nama' => 'Setor/Tarik Tunai', 'type' => 'pengeluaran'],
                ['warna' => '#667085']
            );
            $kategoriMasuk = FinanceCategory::firstOrCreate(
                ['user_id' => $user->id, 'nama' => 'Setor/Tarik Tunai', 'type' => 'pemasukan'],
                ['warna' => '#667085']
            );

            $ket = $validated['keterangan'] ?? null;
            $labelRekening = $rekening->nama_bank.($rekening->no_rekening ? ' ('.$rekening->no_rekening.')' : '');

            if ($validated['arah'] === 'setor') {
                // Cash berkurang, rekening bertambah.
                $user->financeTransactions()->create([
                    'finance_category_id' => $kategoriKeluar->id,
                    'bank_account_id' => null,
                    'tanggal' => now()->toDateString(),
                    'jumlah' => $validated['jumlah'],
                    'keterangan' => 'Setor tunai ke '.$labelRekening.($ket ? ': '.$ket : ''),
                ]);
                $user->financeTransactions()->create([
                    'finance_category_id' => $kategoriMasuk->id,
                    'bank_account_id' => $rekening->id,
                    'tanggal' => now()->toDateString(),
                    'jumlah' => $validated['jumlah'],
                    'keterangan' => 'Setor tunai dari cash'.($ket ? ': '.$ket : ''),
                ]);
            } else {
                // Rekening berkurang, cash bertambah.
                $user->financeTransactions()->create([
                    'finance_category_id' => $kategoriKeluar->id,
                    'bank_account_id' => $rekening->id,
                    'tanggal' => now()->toDateString(),
                    'jumlah' => $validated['jumlah'],
                    'keterangan' => 'Tarik tunai dari '.$labelRekening.($ket ? ': '.$ket : ''),
                ]);
                $user->financeTransactions()->create([
                    'finance_category_id' => $kategoriMasuk->id,
                    'bank_account_id' => null,
                    'tanggal' => now()->toDateString(),
                    'jumlah' => $validated['jumlah'],
                    'keterangan' => 'Tarik tunai ke cash'.($ket ? ': '.$ket : ''),
                ]);
            }
        });

        return redirect()->route('rekening.index')->with('status', 'Berhasil dicatat.');
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
