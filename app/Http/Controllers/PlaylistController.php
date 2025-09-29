<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request->user()->playlists()->create([
            'name' => $validated['name'],
        ]);

        return back();
    }

    public function show(Playlist $playlist)
    {
        return inertia('Playlist/PlaylistDetail', [
            'playlist' => $playlist->load(['musics' => function ($musicQuery) {
                                $musicQuery->with('creator')
                                            ->with('album');
                            }]),
        ]);
    }

    public function addMusic(Request $request, Playlist $playlist)
    {

        $request->validate([
            'music_id' => 'required|exists:music,id'
        ]);

        // Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Attach music to playlist if not already added
        if (!$playlist->musics()->where('music_id', $request->music_id)->exists()) {
            $playlist->musics()->attach($request->music_id);
        }

        return back();
    }
}
