<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    /**
     * Login langsung sebagai user demo (data sudah disiapkan lewat
     * DemoUserSeeder). Tidak perlu form/password.
     */
    public function __invoke(): RedirectResponse
    {
        $enabled = \App\Models\Setting::get('demo_enabled', config('demo.enabled') ? '1' : '0');
        abort_unless($enabled === '1', 404, 'Fitur demo sedang dinonaktifkan sementara.');

        $demoUser = User::where('is_demo', true)->first();

        abort_unless($demoUser, 404, 'Akun demo belum tersedia. Jalankan DemoUserSeeder terlebih dahulu.');

        Auth::login($demoUser);

        return redirect()->route('dashboard')
            ->with('status', 'Kamu sedang menjelajah SMARTS Work sebagai demo (read-only).');
    }
}
