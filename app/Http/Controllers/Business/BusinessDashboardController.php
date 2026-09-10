<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\View\View;

class BusinessDashboardController extends Controller
{
    public function index(Business $business): View
    {
        $totalAkun = $business->accounts()->count();
        $totalJurnalBulanIni = $business->journalEntries()
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        return view('business.dashboard', compact('business', 'totalAkun', 'totalJurnalBulanIni'));
    }
}
