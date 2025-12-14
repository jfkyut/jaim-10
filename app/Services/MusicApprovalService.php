<?php

namespace App\Services;

class MusicApprovalService
{
    public function filterMusic($musicQuery, $request, $status = 'pending')
    {
        $musicQuery->where(function ($subQuery) use ($request, $status) {
            if ($status) {
                $subQuery->where('status', $status);
            }

            if ($request->filled('id')) {
                $subQuery->where('id', $request->input('id'));
            }

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
    }
}
