<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Friendship;
use App\Models\FriendLabel;
use App\Models\FriendPermission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $friends = $user->friends();
        $friendIds = $friends->pluck('id');

        $labels = FriendLabel::where('user_id', $user->id)->pluck('label', 'friend_user_id');

        // Izin yang SAYA berikan ke tiap teman (untuk ditampilkan/diedit di panel kontrol saya).
        $grantedByMe = FriendPermission::where('user_id', $user->id)
            ->whereIn('friend_user_id', $friendIds)
            ->get()
            ->keyBy('friend_user_id');

        // Izin yang tiap teman berikan ke SAYA (untuk tahu link "Lihat X" mana yang boleh dipakai).
        $grantedToMe = FriendPermission::where('friend_user_id', $user->id)
            ->whereIn('user_id', $friendIds)
            ->get()
            ->keyBy('user_id');

        $incoming = $user->receivedFriendRequests()->where('status', 'pending')->with('requester')->get();
        $outgoing = $user->sentFriendRequests()->where('status', 'pending')->with('addressee')->get();

        return view('friends.index', compact('friends', 'incoming', 'outgoing', 'labels', 'grantedByMe', 'grantedToMe'));
    }

    /**
     * Cari user lain berdasarkan email persis (bukan pencarian bebas, demi privasi).
     */
    public function search(Request $request): View
    {
        $request->validate(['email' => ['required', 'email']]);

        $found = User::where('email', $request->email)
            ->where('id', '!=', $request->user()->id)
            ->first();

        return view('friends.search', ['found' => $found, 'query' => $request->email]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'addressee_id' => ['required', 'exists:users,id', 'different:user_id'],
        ]);

        $user = $request->user();
        $addresseeId = $validated['addressee_id'];

        $exists = Friendship::where(function ($q) use ($user, $addresseeId) {
            $q->where('requester_id', $user->id)->where('addressee_id', $addresseeId);
        })->orWhere(function ($q) use ($user, $addresseeId) {
            $q->where('requester_id', $addresseeId)->where('addressee_id', $user->id);
        })->first();

        if ($exists) {
            return back()->with('error', 'Sudah ada permintaan pertemanan dengan user ini.');
        }

        Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $addresseeId,
            'status' => 'pending',
        ]);

        AppNotification::kirim(
            $addresseeId,
            'friend_request',
            $user->name.' mengirim permintaan pertemanan.',
            route('friends.index')
        );

        return redirect()->route('friends.index')->with('status', 'Permintaan pertemanan terkirim.');
    }

    public function accept(Request $request, Friendship $friendship): RedirectResponse
    {
        abort_unless($friendship->addressee_id === $request->user()->id, 403);

        $friendship->update(['status' => 'accepted']);

        AppNotification::kirim(
            $friendship->requester_id,
            'friend_accepted',
            $request->user()->name.' menerima permintaan pertemanan kamu.',
            route('friends.index')
        );

        return redirect()->route('friends.index')->with('status', 'Pertemanan diterima.');
    }

    public function decline(Request $request, Friendship $friendship): RedirectResponse
    {
        abort_unless($friendship->addressee_id === $request->user()->id, 403);

        $friendship->delete();

        return redirect()->route('friends.index')->with('status', 'Permintaan ditolak.');
    }

    public function destroy(Request $request, User $friend): RedirectResponse
    {
        Friendship::where('status', 'accepted')
            ->where(function ($q) use ($request, $friend) {
                $q->where('requester_id', $request->user()->id)->where('addressee_id', $friend->id);
            })
            ->orWhere(function ($q) use ($request, $friend) {
                $q->where('requester_id', $friend->id)->where('addressee_id', $request->user()->id);
            })
            ->delete();

        return redirect()->route('friends.index')->with('status', 'Pertemanan diakhiri.');
    }

    /**
     * Set label hubungan (Suami/Istri, Anak, Rekan Kerja, dst) - perspektif milik user sendiri,
     * tidak memengaruhi label yang dilihat teman untuk dirinya.
     */
    public function updateLabel(Request $request, User $friend): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
        ]);

        FriendLabel::updateOrCreate(
            ['user_id' => $request->user()->id, 'friend_user_id' => $friend->id],
            ['label' => $validated['label'] ?: null]
        );

        return redirect()->route('friends.index');
    }

    /**
     * Atur apa saja yang boleh dilihat teman ini dari data SAYA.
     * Default semua tersembunyi (false) sampai dicentang manual.
     */
    public function updatePermissions(Request $request, User $friend): RedirectResponse
    {
        FriendPermission::updateOrCreate(
            ['user_id' => $request->user()->id, 'friend_user_id' => $friend->id],
            [
                'can_view_aksi_harian' => $request->boolean('can_view_aksi_harian'),
                'can_view_keuangan' => $request->boolean('can_view_keuangan'),
                'can_view_tempat_kerja' => $request->boolean('can_view_tempat_kerja'),
            ]
        );

        return redirect()->route('friends.index')->with('status', 'Pengaturan akses untuk '.$friend->name.' disimpan.');
    }
}
