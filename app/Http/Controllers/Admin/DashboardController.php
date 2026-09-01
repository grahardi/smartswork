<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyAction;
use App\Models\FinanceTransaction;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Statistik agregat (jumlah/total) saja - TIDAK pernah menampilkan
     * isi/detail data personal user manapun.
     */
    public function index(): View
    {
        $stats = [
            'total_member' => User::where('is_demo', false)->count(),
            'member_aktif' => User::where('is_demo', false)->where('is_active', true)->count(),
            'member_baru_bulan_ini' => User::where('is_demo', false)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'total_aksi_harian' => DailyAction::count(),
            'total_transaksi_keuangan' => FinanceTransaction::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
