<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictDemoWrites
{
    /**
     * Semua request selain GET/HEAD ditolak kalau user login sedang
     * memakai akun demo. Read (lihat data) tetap bebas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_demo && ! $request->isMethod('get') && ! $request->isMethod('head')) {
            return back()->with('demo_blocked', 'Ini akun demo (read-only). Daftar akun sendiri untuk mencoba fitur ini.');
        }

        return $next($request);
    }
}
