<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function follow(User $user, Request $request)
    {
        $follower = $request->user();

        // Prevent following yourself
        if ($follower->id === $user->id) {
            return response()->json(['message' => 'Cannot follow yourself.'], 422);
        }

        // Attach from the authenticated user's side; idempotent (no duplicate due to syncWithoutDetaching)
        DB::transaction(function () use ($follower, $user) {
            $follower->following()->syncWithoutDetaching($user->id);
        });

        // Return minimal payload to avoid recursive relationship serialization
        return response()->json([
            'followers_count' => $user->followers()->count(),
            'is_following' => $follower->isFollowing($user),
        ]);
    }

    public function unfollow(User $user, Request $request)
    {
        $follower = $request->user();

        // Prevent unfollowing yourself
        if ($follower->id === $user->id) {
            return response()->json(['message' => 'Cannot unfollow yourself.'], 422);
        }

        DB::transaction(function () use ($follower, $user) {
            $follower->following()->detach($user->id);
        });

        return response()->json([
            'followers_count' => $user->followers()->count(),
            'is_following' => $follower->isFollowing($user),
        ]);
    }
}
