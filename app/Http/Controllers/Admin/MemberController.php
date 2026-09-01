<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Daftar member dengan kolom seperlunya saja (nama, email, tanggal
     * daftar, status). TIDAK ada relasi ke jurnal/keuangan/coretan/galeri
     * user - admin sengaja tidak diberi akses lihat isi data pribadi user.
     */
    public function index(Request $request): View
    {
        $query = User::where('is_demo', false)
            ->select('id', 'name', 'email', 'is_admin', 'is_active', 'created_at');

        if ($search = $request->input('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    public function toggleActive(Request $request, User $member): RedirectResponse
    {
        abort_if($member->id === $request->user()->id, 422, 'Tidak bisa menonaktifkan akun sendiri.');

        $member->update(['is_active' => ! $member->is_active]);

        return back()->with('status', $member->name.' berhasil '.($member->is_active ? 'diaktifkan' : 'dinonaktifkan').'.');
    }

    public function toggleAdmin(Request $request, User $member): RedirectResponse
    {
        abort_if($member->id === $request->user()->id, 422, 'Tidak bisa mengubah status admin diri sendiri.');

        $member->update(['is_admin' => ! $member->is_admin]);

        return back()->with('status', $member->name.' '.($member->is_admin ? 'dijadikan admin' : 'dicabut status admin-nya').'.');
    }

    public function destroy(Request $request, User $member): RedirectResponse
    {
        abort_if($member->id === $request->user()->id, 422, 'Tidak bisa menghapus akun sendiri.');

        $member->delete();

        return back()->with('status', 'Member berhasil dihapus.');
    }
}
