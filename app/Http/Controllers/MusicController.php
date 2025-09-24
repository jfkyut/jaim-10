<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMusicRequest;
use App\Models\Music;

class MusicController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Explore/Explore', [
            'musics' => Music::with('creator')
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
            ]);
        } 

        return back();
    }
}
