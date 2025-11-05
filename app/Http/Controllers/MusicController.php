<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMusicRequest;

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

            if ($request->filled('id')) {
                $subQuery->where('id', $request->input('id'));
            }
        });

        return inertia('Explore/Explore', [
            'musics' => $musicQuery->with([
                                        'creator', 
                                        'album'
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function store(StoreMusicRequest $request)
    {
        if ($request->hasFile("music")) {
            

            // Save the music details to the database
            $music = Music::create([
                'title' => $request->validated("title"),
                'generated_by' => $request->validated("generated_by"),
                'generated_at' => $request->validated("generated_at"),
                'description' => $request->validated("description"),
                'lyrics' => $request->validated("lyrics"),
                'user_id' => auth()->user()->id, // Assuming user is authenticated
                
                'album_id' => $request->validated("album_id"),
            ]);

            $file = $request->file("music");
            $path = $file->store("music_files/" . $music->id, "public");

            $music->update([
                'file_path' => $path,
            ]);
        } 

        return back();
    }

    public function stream(Request $request, Music $music)
    {
        DB::transaction(function () use ($request, $music) {
            // Record the stream
            $music->streams()->create([
                'user_id' => $request->user()?->id
            ]);

            // Increment creator's credits
            $music->creator->increment('credits', 1);

            // Record the transaction
            $music->creator->transactions()->create([
                'amount' => 1,
                'credits_change' => 1,
                'balance_after' => $music->creator->credits
            ]);
        });

        return response()->json(['success' => true]);
    }
}
