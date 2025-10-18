<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user, Request $request)
    {
        $user->followers()->attach($request->user()->id);

        return $user->loadCount('followers');
    }

    public function unfollow(User $user, Request $request)
    {
        $user->followers()->detach($request->user()->id);

        return $user->loadCount('followers');
    }
}
