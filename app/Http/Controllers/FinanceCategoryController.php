<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = $request->user()->financeCategories()
            ->withCount('transactions')
            ->orderBy('type')
            ->orderBy('nama')
            ->get();

        return view('finance.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('finance.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $request->user()->financeCategories()->create($validated);

        return redirect()->route('finance.categories.index')
            ->with('status', 'Kategori "'.$validated['nama'].'" berhasil ditambahkan.');
    }

    public function edit(Request $request, FinanceCategory $category): View
    {
        $this->authorizeOwner($request, $category);

        return view('finance.categories.edit', compact('category'));
    }

    public function update(Request $request, FinanceCategory $category): RedirectResponse
    {
        $this->authorizeOwner($request, $category);

        $validated = $this->validated($request, $category->id);

        $category->update($validated);

        return redirect()->route('finance.categories.index')
            ->with('status', 'Kategori "'.$category->nama.'" berhasil diperbarui.');
    }

    public function destroy(Request $request, FinanceCategory $category): RedirectResponse
    {
        $this->authorizeOwner($request, $category);

        if ($category->transactions()->exists()) {
            return redirect()->route('finance.categories.index')
                ->with('error', 'Kategori "'.$category->nama.'" masih dipakai di transaksi, tidak bisa dihapus.');
        }

        $category->delete();

        return redirect()->route('finance.categories.index')
            ->with('status', 'Kategori berhasil dihapus.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                'unique:finance_categories,nama,'.($ignoreId ?? 'NULL').',id,user_id,'.$request->user()->id.',type,'.$request->input('type'),
            ],
            'type' => ['required', 'in:pemasukan,pengeluaran'],
            'warna' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);
    }

    protected function authorizeOwner(Request $request, FinanceCategory $category): void
    {
        abort_unless($category->user_id === $request->user()->id, 403);
    }
}
