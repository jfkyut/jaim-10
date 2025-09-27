<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class CreationController extends Controller
{
    public function myAlbums(Request $request)
    {
        $albumQuery = Album::query();

        $albumQuery->where(function ($albumSubQuery) use ($request) {
            $albumSubQuery->where('user_id', $request->user()->id);

            if ($request->has('keyword')) {
                $keyword = $request->input('keyword');
                $albumSubQuery->where('name', 'like', "%{$keyword}%")
                              ->orWhere('description', 'like', "%{$keyword}%");
            }
        });

        return inertia('Creation/MyAlbums', [
            'albums' => $albumQuery->with('musics')
                                    ->latest()
                                    ->paginate(100),
        ]);
    }
}
