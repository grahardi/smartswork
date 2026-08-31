<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendDataController extends Controller
{
    public function dailyActions(Request $request, User $friend): View
    {
        $this->authorizeFriend($request, $friend);

        $actions = $friend->dailyActions()
            ->with('project.workplace')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(15);

        return view('friends.aksi-harian', compact('friend', 'actions'));
    }

    public function finance(Request $request, User $friend): View
    {
        $this->authorizeFriend($request, $friend);

        $bulan = $request->input('bulan', now()->format('Y-m'));

        $query = $friend->financeTransactions()
            ->with('category', 'workplace')
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);

        $transactions = (clone $query)->orderByDesc('tanggal')->paginate(20)->withQueryString();

        $totalMasuk = (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))->sum('jumlah');
        $totalKeluar = (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))->sum('jumlah');

        return view('friends.keuangan', [
            'friend' => $friend,
            'transactions' => $transactions,
            'bulan' => $bulan,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldo' => $totalMasuk - $totalKeluar,
        ]);
    }

    public function workplaces(Request $request, User $friend): View
    {
        $this->authorizeFriend($request, $friend);

        $workplaces = $friend->workplaces()->withCount('projects')->get();

        return view('friends.tempat-kerja', compact('friend', 'workplaces'));
    }

    protected function authorizeFriend(Request $request, User $friend): void
    {
        abort_unless($request->user()->isFriendsWith($friend), 403, 'Kamu harus berteman dulu untuk melihat data ini.');
    }
}
