<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_tracks' => Music::count(),
            'total_albums' => Album::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_tracks' => Music::with('creator')->latest()->take(5)->get(),
            'top_creators' => User::withCount('tracks')
                ->orderBy('tracks_count', 'desc')
                ->take(5)
                ->get(),
            'monthly_tracks' => Music::select(
                DB::raw('COUNT(*) as count'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(6)
                ->get()
        ];

        return inertia('Dashboard', $stats);
    }
}
