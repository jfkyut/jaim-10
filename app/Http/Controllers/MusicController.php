<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMusicRequest;
use App\Models\Music;

class MusicController extends Controller
{
    public function index(Request $request)
    {
        $musicQuery = Music::query();

        $musicQuery->where(function ($subQuery) use ($request) {
            if ($request->has('keyword')) {
                $keyword = $request->input('keyword');
                $subQuery->where('title', 'like', "%{$keyword}%")
                         ->orWhere('description', 'like', "%{$keyword}%")
                         ->orWhere('generated_by', 'like', "%{$keyword}%")
                         ->orWhere('generated_at', 'like', "%{$keyword}%")
                         ->orWhere('lyrics', 'like', "%{$keyword}%")
                         ->orWhereHas('creator', function ($userQuery) use ($keyword) {
                             $userQuery->where('first_name', 'like', "%{$keyword}%")
                                       ->orWhere('last_name', 'like', "%{$keyword}%");
                         });
            }
        });

        return inertia('Explore/Explore', [
            'musics' => $musicQuery->with('creator')
                                    ->with('album')
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function store(StoreMusicRequest $request)
    {
        if ($request->hasFile("music")) {
            $file = $request->file("music");
            $path = $file->store("music_files", "public");

            // Save the music details to the database
            Music::create([
                'title' => $request->validated("title"),
                'generated_by' => $request->validated("generated_by"),
                'generated_at' => $request->validated("generated_at"),
                'description' => $request->validated("description"),
                'lyrics' => $request->validated("lyrics"),
                'user_id' => auth()->user()->id, // Assuming user is authenticated
                'file_path' => $path,
                'album_id' => $request->validated("album_id"),
            ]);
        } 

        return back();
    }
}
