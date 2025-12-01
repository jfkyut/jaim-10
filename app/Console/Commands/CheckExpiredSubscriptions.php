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
    protected $description = 'Mark expired subscriptions and update user roles accordingly';

    public function handle()
    {
        $expiredRoleId = config('subscription.expired_role_id') 
            ?? UserRole::where('name', 'listener')->value('id') 
            ?? null;

        // Process in chunks to avoid memory spikes on large datasets
        Subscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->with('user')
            ->chunkById(100, function ($subscriptions) use ($expiredRoleId) {
                DB::transaction(function () use ($subscriptions, $expiredRoleId) {
                    foreach ($subscriptions as $subscription) {
                        // mark subscription expired
                        $subscription->status = 'expired';
                        $subscription->save();

                        // update the related user's role if available
                        if ($expiredRoleId && $subscription->user) {
                            $subscription->user->role_id = $expiredRoleId;
                            $subscription->user->save();
                        }
                    }
                });
            });

        $this->info('Expired subscriptions processed.');
    }
}