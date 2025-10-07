<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index(Request $request)
    {
        $userQuery = User::query();

        $userQuery->where(function ($userSubQuery) use ($request) {
            if ($request->filled('keyword')) {
                $userSubQuery->where('first_name', 'LIKE', '%' . $request->query('keyword') . '%');
            }
        });

        return inertia('Creator/Creators', [
            'creators' => $userQuery->whereHas('role', function ($roleQuery) {
                                        $roleQuery->where('name', 'creator');
                                    })
                                    ->withCount([
                                        'tracks',
                                        'playlists',
                                        'albums',
                                        'followers',
                                        'following',
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function show(User $creator)
    {
        $creator->loadCount(['tracks', 'playlists', 'albums', 'followers', 'following']);

        return inertia('Creator/CreatorDetail', [
            'creator' => $creator->load(['albums', 'playlists', 'tracks'])
        ]);
    }
}
