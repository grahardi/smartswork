<?php

namespace App\Http\Controllers;

use App\Models\FriendPermission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendDataController extends Controller
{
    public function dailyActions(Request $request, User $friend): View
    {
        $this->authorizePermission($request, $friend, 'can_view_aksi_harian');

        $actions = $friend->dailyActions()
            ->with('project.workplace')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(15);

        return view('friends.aksi-harian', compact('friend', 'actions'));
    }

    public function finance(Request $request, User $friend): View
    {
        $this->authorizePermission($request, $friend, 'can_view_keuangan');

        $bulan = $request->input('bulan', now()->format('Y-m'));

        $periode = \Illuminate\Support\Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();

        $query = $friend->financeTransactions()
            ->with('category', 'workplace')
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month);

        $transactions = (clone $query)->orderByDesc('tanggal')->orderByDesc('id')->paginate(20)->withQueryString();

        $totalMasuk = (float) (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))->sum('jumlah');
        $totalKeluar = (float) (clone $query)->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))->sum('jumlah');

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
        $this->authorizePermission($request, $friend, 'can_view_tempat_kerja');

        $workplaces = $friend->workplaces()->withCount('projects')->get();

        return view('friends.tempat-kerja', compact('friend', 'workplaces'));
    }

    /**
     * Cek: sudah berteman DAN pemilik data ($friend) sudah mengizinkan
     * kategori tertentu untuk dilihat oleh $request->user(). Default
     * (belum ada baris FriendPermission sama sekali) = tersembunyi.
     */
    protected function authorizePermission(Request $request, User $friend, string $kolom): void
    {
        abort_unless($request->user()->isFriendsWith($friend), 403, 'Kamu harus berteman dulu untuk melihat data ini.');

        $izin = FriendPermission::where('user_id', $friend->id)
            ->where('friend_user_id', $request->user()->id)
            ->first();

        abort_unless($izin && $izin->{$kolom}, 403, $friend->name.' belum mengizinkanmu melihat data ini.');
    }
}
