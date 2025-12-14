<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    public function index(Request $request)
    {
        $userQuery = User::query();

        $userQuery->where(function ($userSubQuery) use ($request) {

            $userSubQuery->where('id', '!=', $request->user()->id);

            if ($request->filled('keyword')) {
                $userSubQuery->where(function ($query) use ($request) {
                    $query->where('first_name', 'LIKE', '%' . $request->query('keyword') . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->query('keyword') . '%');
                });
            }
        });

        // Add an is_following count (0 or 1) filtered by current user to avoid N+1 queries.
        return inertia('People/People', [
            'people' => $userQuery
                ->withCount([
                    'followers',
                    'following',
                    'tracks',
                    // alias a filtered followers count as `is_following`
                    'followers as is_following' => function ($q) {
                        $q->where('follower_id', auth()->id());
                    },
                ])
                ->with([
                    'tracks' => function ($trackQuery) {
                        $trackQuery->where('status', 'approved')
                                    ->latest();
                    },
                    'playlists',
                    'albums',
                    'following',
                    // you can keep this filtered followers relation if other code relies on it
                    'followers' => function ($followers) {
                        $followers->where('follower_id', auth()->id());
                    },
                ])
                ->latest()
                ->paginate(100)
        ]);
    }

    // For the profile page, use the isFollowing() method once and attach boolean to the model
    public function show(Request $request, User $user)
    {
        $user = User::where('id', $user->id)
                    ->withCount([
                        'followers',
                        'following',
                        'tracks',
                    ])
                    ->with([
                        'tracks' => function($query) {
                            $query->latest();
                        },
                        'playlists',
                        'albums' => function ($query) {
                            $query->withCount('musics')
                                  ->latest();
                        },
                        'followers' => function($query) {
                            $query->where('follower_id', auth()->id());
                        }
                    ])
                    ->firstOrFail();

        // Use the model method once to compute boolean for the current viewer
        $user->is_following = $request->user() ? $request->user()->isFollowing($user) : false;

        return inertia('People/Profile', [
            'profile' => $user
        ]);
    }
}