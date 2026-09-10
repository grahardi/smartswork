<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\ChartOfAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChartOfAccountController extends Controller
{
    public function index(Business $business): View
    {
        $accounts = $business->accounts()->orderBy('kode')->get()->groupBy('tipe');

        return view('business.coa.index', compact('business', 'accounts'));
    }

    public function create(Business $business): View
    {
        return view('business.coa.create', compact('business'));
    }

    public function store(Request $request, Business $business): RedirectResponse
    {
        $validated = $this->validated($request, $business);

        $business->accounts()->create($validated);

        return redirect()->route('business.coa.index', $business)
            ->with('status', 'Akun "'.$validated['nama'].'" berhasil ditambahkan.');
    }

    public function edit(Business $business, ChartOfAccount $account): View
    {
        $this->authorizeAccount($business, $account);

        return view('business.coa.edit', compact('business', 'account'));
    }

    public function update(Request $request, Business $business, ChartOfAccount $account): RedirectResponse
    {
        $this->authorizeAccount($business, $account);

        $validated = $this->validated($request, $business, $account->id);

        $account->update($validated);

        return redirect()->route('business.coa.index', $business)
            ->with('status', 'Akun berhasil diperbarui.');
    }

    public function destroy(Business $business, ChartOfAccount $account): RedirectResponse
    {
        $this->authorizeAccount($business, $account);

        if ($account->lines()->exists()) {
            return back()->with('error', 'Akun ini sudah dipakai di jurnal, tidak bisa dihapus. Nonaktifkan saja lewat form edit.');
        }

        $account->delete();

        return redirect()->route('business.coa.index', $business)->with('status', 'Akun berhasil dihapus.');
    }

    protected function validated(Request $request, Business $business, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode' => [
                'required', 'string', 'max:20',
                'unique:chart_of_accounts,kode,'.($ignoreId ?? 'NULL').',id,business_id,'.$business->id,
            ],
            'nama' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'in:aset,kewajiban,ekuitas,pendapatan,beban'],
            'saldo_normal' => ['required', 'in:debit,kredit'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    protected function authorizeAccount(Business $business, ChartOfAccount $account): void
    {
        abort_unless($account->business_id === $business->id, 403);
    }
}
