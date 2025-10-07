<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        $albumQuery = Album::query();

        $albumQuery->where(function ($albumSubQuery) use ($request) {

            if ($request->has('keyword')) {
                $keyword = $request->input('keyword');
                $albumSubQuery->where('name', 'like', "%{$keyword}%")
                              ->orWhere('description', 'like', "%{$keyword}%");
            }

            if ($request->filled('id')) {
                $albumSubQuery->where('id', $request->input('id'));
            }
        });

        return inertia('Album/Albums', [
            'albums' => $albumQuery->with('musics', function ($musicQuery) {
                                        $musicQuery->with('creator')
                                                    ->with('album');
                                    })
                                    ->latest()
                                    ->paginate(100),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image', // Optional photo upload
        ]);

        $path = null;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('album_photos', 'public');
        }

        $request->user()->albums()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'photo_path' => $path,
        ]);

        return back();
    }

    public function show(Album $album)
    {
        return inertia('Album/AlbumDetail', [
            'album' => $album->load(['musics' => function ($musicQuery) {
                                $musicQuery->with('creator')
                                            ->with('album');
                            }])
                            ->load('creator'),
        ]);
    }

    public function update(Request $request, Album $album)
    {
        // $this->authorize('update', $album);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image', // Optional photo upload
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('album_photos', 'public');
            $album->photo_path = $path;
        }

        $album->name = $validated['name'];
        $album->description = $validated['description'] ?? null;
        $album->save();

        return back();
    }

    public function destroy(Album $album)
    {
        // $this->authorize('delete', $album);

        $album->delete();
        
        return back();
    }
}
