<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
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

        $incoming = $user->receivedFriendRequests()->where('status', 'pending')->with('requester')->get();
        $outgoing = $user->sentFriendRequests()->where('status', 'pending')->with('addressee')->get();

        return view('friends.index', compact('friends', 'incoming', 'outgoing'));
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

        return redirect()->route('friends.index')->with('status', 'Permintaan pertemanan terkirim.');
    }

    public function accept(Request $request, Friendship $friendship): RedirectResponse
    {
        abort_unless($friendship->addressee_id === $request->user()->id, 403);

        $friendship->update(['status' => 'accepted']);

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
}
