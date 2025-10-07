<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(User $user, Request $request)
    {
        $authenticatedUser = $request->user();

        if ($authenticatedUser->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        if ($authenticatedUser->isFollowing($user)) {
            $authenticatedUser->following()->detach($user->id);
            return response()->json([
                'following' => false,
                'followers_count' => $user->followers()->count(),
            ]);
        }

        $authenticatedUser->following()->attach($user->id);
        return response()->json([
            'following' => true,
            'followers_count' => $user->followers()->count(),
        ]);
    }
}
