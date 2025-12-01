<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'app:check-expired-subscriptions';
    protected $description = 'Revert user roles when subscriptions expire and mark them as expired';

    public function handle()
    {
        // Get the role to revert expired subscribers to (default: 'listener')
        $expiredRoleId = config('subscription.expired_role_id') 
            ?? UserRole::where('name', 'listener')->value('id');

        if (!$expiredRoleId) {
            $this->error('No listener role found in the database. Cannot process expired subscriptions.');
            Log::error('CheckExpiredSubscriptions: listener role not found');
            return 1;
        }

        try {
            // Find all active subscriptions that have passed their end date
            $expiredSubscriptions = Subscription::where('status', 'active')
                ->where('ends_at', '<', now())
                ->with('user')
                ->get();

            $processedCount = 0;

            foreach ($expiredSubscriptions as $subscription) {
                DB::transaction(function () use ($subscription, $expiredRoleId, &$processedCount) {
                    // Mark subscription as expired
                    $subscription->status = 'expired';
                    $subscription->save();

                    // Revert user role to listener (or configured role)
                    if ($subscription->user) {
                        $subscription->user->role_id = $expiredRoleId;
                        $subscription->user->save();

                        $processedCount++;

                        Log::info("Subscription expired for user {$subscription->user->id} ({$subscription->user->email}). Role reverted to listener.");
                    }
                });
            }

            if ($processedCount === 0) {
                $this->info('No expired subscriptions to process.');
            } else {
                $this->info("Successfully processed {$processedCount} expired subscription(s).");
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error processing expired subscriptions: {$e->getMessage()}");
            Log::error("CheckExpiredSubscriptions error: {$e->getMessage()}", ['exception' => $e]);
            return 1;
        }
    }
}