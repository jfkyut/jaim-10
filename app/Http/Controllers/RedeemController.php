<?php

namespace App\Http\Controllers;

use App\Http\Requests\Redeem\StoreRedeemRequest;
use App\Models\Redeem;
use App\Models\RedeemStatus;
use Illuminate\Http\Request;

class RedeemController extends Controller
{
    public function pending(Request $request)
    {
        $redeemQuery = Redeem::query();

        $redeemQuery->where(function ($redeemSubQuery) use ($request) {
            $redeemSubQuery->whereHas('status', function ($status) {
                $status->where('name', 'pending');
            });

            if ($request->filled('keyword')) {
                $redeemSubQuery->whereHas('user', function ($user) use ($request) {
                    $user->where('first_name', 'like', '%' . $request->query('keyword') . '%')
                        ->orWhere('last_name', 'like', '%' . $request->query('keyword') . '%');
                });
            }
        });

        return inertia('Redeem/Redeem', [
            'redeems' => $redeemQuery->with(['user', 'status'])->paginate(100),
            'pageTitle' => 'Pending Reedem Requests'
        ]);
    }

    public function approved(Request $request)
    {
        $redeemQuery = Redeem::query();

        $redeemQuery->where(function ($redeemSubQuery) use ($request) {
            $redeemSubQuery->whereHas('status', function ($status) {
                $status->where('name', 'approved');
            });

            if ($request->filled('keyword')) {
                $redeemSubQuery->whereHas('user', function ($user) use ($request) {
                    $user->where('first_name', 'like', '%' . $request->query('keyword') . '%')
                        ->orWhere('last_name', 'like', '%' . $request->query('keyword') . '%');
                });
            }
        });

        return inertia('Redeem/Redeem', [
            'redeems' => $redeemQuery->with(['user', 'status'])->paginate(100),
            'pageTitle' => 'Approved Reedem Requests'
        ]);
    }

    public function rejected(Request $request)
    {
        $redeemQuery = Redeem::query();

        $redeemQuery->where(function ($redeemSubQuery) use ($request) {
            $redeemSubQuery->whereHas('status', function ($status) {
                $status->where('name', 'rejected');
            });

            if ($request->filled('keyword')) {
                $redeemSubQuery->whereHas('user', function ($user) use ($request) {
                    $user->where('first_name', 'like', '%' . $request->query('keyword') . '%')
                        ->orWhere('last_name', 'like', '%' . $request->query('keyword') . '%');
                });
            }
        });

        return inertia('Redeem/Redeem', [
            'redeems' => $redeemQuery->with(['user', 'status'])->paginate(100),
            'pageTitle' => 'Rejected Reedem Requests'
        ]);
    }

    public function store(StoreRedeemRequest $request)
    {
        Redeem::create([
            'amount' => $request->validated('amount'),
            'credits' => $request->validated('credits'),
            'claim_through' => $request->validated('claim_through'), // Optional field for claim method
            'claim_number' => $request->validated('claim_number'), // Optional field for claim method
            'user_id' => $request->user()->id,
            'status_id' => 1 // pending
        ]);

        $request->user()->decrement('credits', $request->validated('credits'));

        $request->user()->transactions()->create([
            'amount' => $request->amount,
            'credit_balance' => $request->user()->credits,
            'credits_change' => -$request->credits,
        ]);

        return back();
    }

    public function approve(Redeem $redeem)
    {
        $redeem->update([
            'status_id' => RedeemStatus::where('name', 'approved')->first()->id,
        ]);

        return back();
    }

    public function reject(Redeem $redeem)
    {
        // Get the user associated with this redeem request
        $user = $user = $redeem->user;

        // Only process if the redeem request is still pending
        if ($redeem->status->name === 'pending') {
            // Update redeem status to rejected
            $redeem->update([
                'status_id' => RedeemStatus::where('name', 'rejected')->first()->id,
            ]);

            // Return credits to user
            $user->increment('credits', $redeem->credits);

            // Create transaction record for returned credits
            $user->transactions()->create([
                'amount' => $redeem->amount,
                'credit_balance' => $user->credits,
                'credits_change' => $redeem->credits, // Positive value since credits are returned
            ]);
        }

        return back();
    }
}
