<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        return inertia('Favorite/Favorite');
    }

    public function store(Request $request, Music $music)
    {
        $request->user()->favorites()->create([
            'music_id' => $music->id,
        ]);

        back();
    }

    public function destroy(Request $request, Music $music)
    {
        $request->user()->favorites()->where('music_id', $music->id)->delete();

        back();
    }
}
