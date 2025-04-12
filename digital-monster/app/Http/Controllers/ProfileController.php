<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());
        $isAdmin = $user->role === 'admin';

        $friends = $user->friends();
        $friendIds = $friends->pluck('id');

        $requestedFriends = $user->requestedFriends();
        $pendingFriends = $user->pendingFriendRequests();
        $requestedIds = $requestedFriends->pluck('id');
        $pendingIds = $pendingFriends->pluck('id');
        $blockedIds = $user->blockedUserIds();

        $users = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $friendIds)
            ->whereNotIn('id', $blockedIds)
            ->whereNotIn('id', $requestedIds)
            ->WhereNotIn('id', $pendingIds)
            ->get();

        if ($isAdmin) {
            $users->push($user);
        }

        return view('profile.index', compact('users', 'isAdmin', 'friends', 'requestedFriends', 'pendingFriends'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->notification_accept = $request->input('notification_accept') == 'on' ? 1 : 0;
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function policy(): View
    {
        $user = User::find(Auth::id());
        return view('profile.policy', compact('user'));
    }

    public function updatePolicy(Request $request)
    {
        $user = User::find(Auth::id());
        $user->policy_accept = $request->input('policy_accept') == 'on' ? 1 : 0;
        $user->save();

        return view('profile.policy', compact('user'));
    }

    public function addFriend(Request $request)
    {
        $userId = Auth::id();
        $targetId = $request->input('user_id');

        $friendship = Friendship::where(function ($query) use ($userId, $targetId) {
            $query->where('requester_user_id', $userId)
                ->where('addressee_user_id', $targetId);
        })
            ->orWhere(function ($query) use ($userId, $targetId) {
                $query->where('requester_user_id', $targetId)
                    ->where('addressee_user_id', $userId);
            })
            ->first();

        if ($friendship) {
            if ($friendship->status === 'pending') {
                $friendship->update(['status' => 'accepted']);
                return response()->json(['success' => true, 'message' => 'Friend request accepted']);
            }
        }

        Friendship::create([
            'requester_user_id' => $userId,
            'addressee_user_id' => $targetId,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Friend request sent']);
    }

    public function cancelFriend(Request $request)
    {
        $userId = Auth::id();
        $targetId = $request->input('user_id');

        $friendship = Friendship::where(function ($query) use ($userId, $targetId) {
            $query->where('requester_user_id', $userId)
                ->where('addressee_user_id', $targetId);
        })
            ->orWhere(function ($query) use ($userId, $targetId) {
                $query->where('requester_user_id', $targetId)
                    ->where('addressee_user_id', $userId);
            })
            ->first();

        $friendship->delete();

        return response()->json([
            'success' => true,
            'message' => 'Friend request canceled',
        ]);
    }
}
