<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favoriteQuery = $request->user()->favorites()
            ->with([
                'music.creator', 
                'music.album'
            ]);

        if ($request->filled('keyword')) {
            $favoriteQuery->whereHas('music', function($music) use ($request) {
                $music->where('title', 'like', '%' . $request->query('keyword') . '%');
            });
        }

        return inertia('Favorite/Favorite', [
            'favorites' => $favoriteQuery->latest()->paginate(100),
        ]);
    }

    public function store(Request $request, Music $music)
    {
        DB::transaction(function () use ($request, $music) {
            // Create the favorite
            $request->user()->favorites()->create([
                'music_id' => $music->id,
            ]);

            // Add 1 credit to the music creator
            $music->creator->increment('credits', 1);
        });

        return back();
    }

    public function destroy(Request $request, Music $music)
    {
        $request->user()->favorites()->where('music_id', $music->id)->delete();

        return back();
    }
}
