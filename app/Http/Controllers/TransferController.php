<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\FinanceCategory;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function create(Request $request): View
    {
        $friends = $request->user()->friends();

        return view('finance.transfer', compact('friends'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'to_user_id' => ['required', 'exists:users,id'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $sender = $request->user();
        $receiver = User::findOrFail($validated['to_user_id']);

        abort_if($receiver->id === $sender->id, 422, 'Tidak bisa transfer ke diri sendiri.');
        abort_unless($sender->isFriendsWith($receiver), 403, 'Hanya bisa transfer ke teman.');

        DB::transaction(function () use ($sender, $receiver, $validated) {
            $transfer = Transfer::create([
                'from_user_id' => $sender->id,
                'to_user_id' => $receiver->id,
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $kategoriKeluar = FinanceCategory::firstOrCreate(
                ['user_id' => $sender->id, 'nama' => 'Transfer ke Teman', 'type' => 'pengeluaran'],
                ['warna' => '#DC2626']
            );

            $kategoriMasuk = FinanceCategory::firstOrCreate(
                ['user_id' => $receiver->id, 'nama' => 'Transfer dari Teman', 'type' => 'pemasukan'],
                ['warna' => '#2563EB']
            );

            $keterangan = $validated['keterangan'] ?? null;

            $sender->financeTransactions()->create([
                'finance_category_id' => $kategoriKeluar->id,
                'transfer_id' => $transfer->id,
                'tanggal' => now()->toDateString(),
                'jumlah' => $validated['jumlah'],
                'keterangan' => 'Transfer ke '.$receiver->name.($keterangan ? ': '.$keterangan : ''),
            ]);

            $receiver->financeTransactions()->create([
                'finance_category_id' => $kategoriMasuk->id,
                'transfer_id' => $transfer->id,
                'tanggal' => now()->toDateString(),
                'jumlah' => $validated['jumlah'],
                'keterangan' => 'Transfer dari '.$sender->name.($keterangan ? ': '.$keterangan : ''),
            ]);

            AppNotification::kirim(
                $receiver->id,
                'transfer_received',
                $sender->name.' mentransfer Rp'.number_format($validated['jumlah'], 0, ',', '.').' ke kamu.',
                route('finance.transactions.index')
            );
        });

        return redirect()->route('finance.transactions.index')
            ->with('status', 'Transfer ke '.$receiver->name.' berhasil dicatat.');
    }
}
