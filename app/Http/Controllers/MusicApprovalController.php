<?php

namespace App\Http\Controllers;

use App\Models\Music;
use App\Services\MusicApprovalService;
use Illuminate\Http\Request;

class MusicApprovalController extends Controller
{
    public function __construct(protected MusicApprovalService $musicApprovalService) {}

    public function pending(Request $request)
    {
        $musicQuery = Music::query();

        $this->musicApprovalService->filterMusic(
            $musicQuery, 
            $request
        );

        return inertia('MusicApproval/MusicApproval', [
            'musics' => $musicQuery->with([
                                        'creator', 
                                        'album'
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function review(Request $request)
    {
        $musicQuery = Music::query();

        $this->musicApprovalService->filterMusic(
            $musicQuery, 
            $request, 
            'under review'
        );

        return inertia('MusicApproval/MusicApproval', [
            'musics' => $musicQuery->with([
                                        'creator', 
                                        'album'
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function approved(Request $request)
    {
        $musicQuery = Music::query();

        $this->musicApprovalService->filterMusic(
            $musicQuery, 
            $request,
            'approved'
        );

        return inertia('MusicApproval/MusicApproval', [
            'musics' => $musicQuery->with([
                                        'creator', 
                                        'album'
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function rejected(Request $request)
    {
        $musicQuery = Music::query();

        $this->musicApprovalService->filterMusic(
            $musicQuery, 
            $request, 
            'rejected'
        );

        return inertia('MusicApproval/MusicApproval', [
            'musics' => $musicQuery->with([
                                        'creator', 
                                        'album'
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function myMusics(Request $request)
    {
        $musicQuery = Music::query();

        $musicQuery->where('user_id', $request->user()->id);

        $this->musicApprovalService->filterMusic(
            $musicQuery, 
            $request,
            null
        );

        return inertia('MusicApproval/MusicApproval', [
            'musics' => $musicQuery->with([
                                        'creator', 
                                        'album'
                                    ])
                                    ->latest()
                                    ->paginate(100)
        ]);
    }

    public function update(Request $request, Music $music)
    {
        $request->validate([
            'status' => 'required|in:pending,under review,approved,rejected',
            'review_comments' => 'nullable|string',
        ]);

        $music->update([
            'status' => $request->input('status'),
            'review_comments' => $request->input('review_comments'),
        ]);

        return redirect()->back()->with('success', 'Music status updated successfully.');
    }
}
