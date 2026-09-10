<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessMember
{
    /**
     * Route bisnis selalu punya {business} di parameter - pastikan user
     * yang login memang anggota business itu (owner/staff/akuntan).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $business = $request->route('business');

        if ($business instanceof Business) {
            abort_unless(
                $request->user()->businesses->contains($business->id),
                403,
                'Kamu bukan anggota business ini.'
            );
        }

        return $next($request);
    }
}
