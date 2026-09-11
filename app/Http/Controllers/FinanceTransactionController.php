<?php

namespace App\Http\Controllers;

use App\Models\FinanceTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $periode = \Illuminate\Support\Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();

        $query = $request->user()->financeTransactions()
            ->with('category', 'workplace', 'bankAccount')
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month);

        $transactions = (clone $query)->orderByDesc('tanggal')->orderByDesc('id')->paginate(20)->withQueryString();

        $totalMasuk = (float) (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))->sum('jumlah');
        $totalKeluar = (float) (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))->sum('jumlah');

        // Saldo riil saat ini (semua waktu, bukan cuma bulan yang difilter).
        $user = $request->user();
        $cashMasuk = (float) $user->financeTransactions()->whereNull('bank_account_id')->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))->sum('jumlah');
        $cashKeluar = (float) $user->financeTransactions()->whereNull('bank_account_id')->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))->sum('jumlah');
        $cashFisik = $cashMasuk - $cashKeluar;
        $rekenings = $user->bankAccounts()->get();
        $totalRekening = $rekenings->sum(fn ($r) => $r->saldoSekarang());

        return view('finance.transactions.index', [
            'transactions' => $transactions,
            'bulan' => $bulan,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $totalMasuk - $totalKeluar,
            'cashFisik' => $cashFisik,
            'rekenings' => $rekenings,
            'totalRekening' => $totalRekening,
        ]);
    }

    public function create(Request $request): View
    {
        $categories = $request->user()->financeCategories()->orderBy('type')->orderBy('nama')->get();
        $workplaces = $request->user()->workplaces()->get();
        $rekenings = $request->user()->bankAccounts()->get();

        return view('finance.transactions.create', compact('categories', 'workplaces', 'rekenings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $request->user()->financeTransactions()->create($validated);

        return redirect()->route('finance.transactions.index')
            ->with('status', 'Transaksi berhasil dicatat.');
    }

    public function edit(Request $request, FinanceTransaction $transaction): View
    {
        $this->authorizeOwner($request, $transaction);

        $categories = $request->user()->financeCategories()->orderBy('type')->orderBy('nama')->get();
        $workplaces = $request->user()->workplaces()->get();
        $rekenings = $request->user()->bankAccounts()->get();

        return view('finance.transactions.edit', compact('transaction', 'categories', 'workplaces', 'rekenings'));
    }

    public function update(Request $request, FinanceTransaction $transaction): RedirectResponse
    {
        $this->authorizeOwner($request, $transaction);

        $validated = $this->validated($request);

        $transaction->update($validated);

        return redirect()->route('finance.transactions.index')
            ->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Request $request, FinanceTransaction $transaction): RedirectResponse
    {
        $this->authorizeOwner($request, $transaction);

        $transaction->delete();

        return redirect()->route('finance.transactions.index')
            ->with('status', 'Transaksi berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $validated = $request->validate([
            'finance_category_id' => [
                'required',
                'exists:finance_categories,id,user_id,'.$request->user()->id,
            ],
            'workplace_id' => ['nullable', 'exists:workplaces,id'],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id,user_id,'.$request->user()->id],
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);

        // Kalau sumber dana dipilih "cash", pastikan bank_account_id tetap null
        // walau ada sisa value lama nyangkut di select.
        if ($request->input('sumber_dana') === 'cash') {
            $validated['bank_account_id'] = null;
        }

        return $validated;
    }

    protected function authorizeOwner(Request $request, FinanceTransaction $transaction): void
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);
    }
}
