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

        $query = $request->user()->financeTransactions()
            ->with('category', 'workplace')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);

        $transactions = (clone $query)->orderByDesc('tanggal')->paginate(20)->withQueryString();

        $totalMasuk = (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))->sum('jumlah');
        $totalKeluar = (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))->sum('jumlah');

        return view('finance.transactions.index', [
            'transactions' => $transactions,
            'bulan' => $bulan,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $totalMasuk - $totalKeluar,
        ]);
    }

    public function create(Request $request): View
    {
        $categories = $request->user()->financeCategories()->orderBy('type')->orderBy('nama')->get();
        $workplaces = $request->user()->workplaces()->get();

        return view('finance.transactions.create', compact('categories', 'workplaces'));
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

        return view('finance.transactions.edit', compact('transaction', 'categories', 'workplaces'));
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
        return $request->validate([
            'finance_category_id' => [
                'required',
                'exists:finance_categories,id,user_id,'.$request->user()->id,
            ],
            'workplace_id' => ['nullable', 'exists:workplaces,id'],
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string'],
        ]);
    }

    protected function authorizeOwner(Request $request, FinanceTransaction $transaction): void
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);
    }
}
