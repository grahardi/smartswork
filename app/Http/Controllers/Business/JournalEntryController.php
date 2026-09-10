<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    public function index(Business $business): View
    {
        $entries = $business->journalEntries()
            ->with('lines.account', 'creator')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(20);

        return view('business.journal.index', compact('business', 'entries'));
    }

    public function create(Business $business): View
    {
        $accounts = $business->accounts()->where('is_active', true)->orderBy('kode')->get();

        return view('business.journal.create', compact('business', 'accounts'));
    }

    public function store(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'nomor_referensi' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.chart_of_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.kredit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $totalDebit = collect($validated['lines'])->sum(fn ($l) => (float) ($l['debit'] ?? 0));
        $totalKredit = collect($validated['lines'])->sum(fn ($l) => (float) ($l['kredit'] ?? 0));

        if (round($totalDebit, 2) !== round($totalKredit, 2)) {
            throw ValidationException::withMessages([
                'lines' => 'Total debit (Rp'.number_format($totalDebit, 0, ',', '.').') harus sama dengan total kredit (Rp'.number_format($totalKredit, 0, ',', '.').').',
            ]);
        }

        if ($totalDebit == 0) {
            throw ValidationException::withMessages([
                'lines' => 'Jurnal tidak boleh kosong (semua debit/kredit nol).',
            ]);
        }

        DB::transaction(function () use ($validated, $business, $request) {
            $entry = $business->journalEntries()->create([
                'created_by' => $request->user()->id,
                'tanggal' => $validated['tanggal'],
                'nomor_referensi' => $validated['nomor_referensi'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            foreach ($validated['lines'] as $line) {
                if (empty($line['debit']) && empty($line['kredit'])) {
                    continue;
                }
                $entry->lines()->create([
                    'chart_of_account_id' => $line['chart_of_account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'kredit' => $line['kredit'] ?? 0,
                    'keterangan' => $line['keterangan'] ?? null,
                ]);
            }
        });

        return redirect()->route('business.journal.index', $business)
            ->with('status', 'Jurnal berhasil dicatat.');
    }

    public function destroy(Business $business, JournalEntry $entry): RedirectResponse
    {
        abort_unless($entry->business_id === $business->id, 403);

        $entry->delete();

        return redirect()->route('business.journal.index', $business)->with('status', 'Jurnal dihapus.');
    }
}
