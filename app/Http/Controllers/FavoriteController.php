<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->favorites()
            ->with([
                'music.creator', 
                'music.album'
            ]);

        if ($request->filled('keyword')) {
            $query->whereHas('music', function($music) use ($request) {
                $music->where('title', 'like', '%' . $request->query('keyword') . '%');
            });
        }

        return inertia('Favorite/Favorite', [
            'favorites' => $query->latest()->paginate(100),
            'filters' => [
                'search' => $request->search
            ]
        ]);
    }

    public function store(Request $request, Music $music)
    {
        $request->user()->favorites()->create([
            'music_id' => $music->id,
        ]);

        return back();
    }

    public function destroy(Request $request, Music $music)
    {
        $request->user()->favorites()->where('music_id', $music->id)->delete();

        return back();
    }
}
