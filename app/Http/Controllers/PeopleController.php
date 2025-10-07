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

        return inertia('People/People', [
            'people' => $userQuery->withCount(['followers', 'following', 'tracks'])
                                ->with(['tracks', 'playlists', 'albums', 'followers' => function($query) {
                                    $query->where('follower_id', auth()->id());
                                }, 'following'])
                                ->latest()
                                ->paginate(100)
        ]);
    }

    public function show(User $user)
    {
        $user = User::where('id', $user->id)
                    ->withCount([
                        'followers', 
                        'following', 
                        'tracks'
                    ])
                    ->with([
                        'tracks' => function($query) {
                            $query->latest();
                        }, 
                        'playlists', 
                        'albums', 
                        'followers' => function($query) {
                            $query->where('follower_id', auth()->id());
                        }
                    ])
                    ->firstOrFail();

        return inertia('People/Profile', [
            'profile' => $user
        ]);
    }
}
