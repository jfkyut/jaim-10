# Subscription Scheduler Documentation

## Overview
This document explains how the subscription expiry scheduler works in the JAM application. When a user's subscription expires, their role is automatically reverted from premium (creator/artist) back to listener.

## How It Works

### 1. User Subscribes to Premium
- User navigates to **Subscription Plans** in the sidebar
- Selects a plan and completes payment via PayMongo
- On successful payment (`SubscriptionController@success`):
  - A `Subscription` record is created with:
    - `starts_at`: current date/time
    - `ends_at`: current date + 1 month (or plan duration)
    - `status`: 'active'
  - User's `role_id` is updated to `2` (creator/artist) — unless already an admin (role_id = 3)

### 2. Scheduler Runs Daily
- Laravel's task scheduler runs the `CheckExpiredSubscriptions` command once per day
- Command location: `app/Console/Commands/CheckExpiredSubscriptions.php`
- Scheduled in: `app/Console/Kernel.php` → `schedule()` method

### 3. Expiry Check & Role Revert
For each expired subscription (`status = 'active'` AND `ends_at < now()`):
1. Mark subscription `status` = 'expired'
2. Revert user's `role_id` to listener (role_id from config or database lookup)
3. Log the action in `storage/logs/laravel.log`

---

## Configuration

### Config File: `config/subscription.php`

```php
return [
    // Role ID or name to revert users to when subscription expires
    // If null, system defaults to role with name 'listener'
    'expired_role_id' => null,

    // Default subscription duration in days
    // (can be overridden per plan in the plans table)
    'default_duration_days' => 30,
];
```

### Environment Setup

To enable Laravel's task scheduler, your server must have a cron job that runs every minute:

```bash
* * * * * cd /path/to/jaim-10 && php artisan schedule:run >> /dev/null 2>&1
```

**For development/testing**, you can manually trigger the command:

```bash
php artisan app:check-expired-subscriptions
```

---

## Database Schema

### `subscriptions` Table
| Column | Type | Purpose |
|--------|------|---------|
| `id` | bigint | Primary key |
| `user_id` | bigint | Foreign key to users |
| `plan_id` | bigint | Foreign key to plans |
| `starts_at` | timestamp | Subscription start date |
| `ends_at` | timestamp | **Subscription expiry date** |
| `status` | varchar | 'active' or 'expired' |
| `created_at` | timestamp | Record creation time |
| `updated_at` | timestamp | Last update time |

### `users` Table
| Column | Type | Purpose |
|--------|------|---------|
| `id` | bigint | Primary key |
| `role_id` | bigint | Foreign key to user_roles |
| ... | ... | ... |

### `user_roles` Table
| Column | Value | Purpose |
|--------|-------|---------|
| `id` | 1 | 'admin' — full access |
| `id` | 2 | 'creator' — can upload music (requires subscription) |
| `id` | 3 | 'listener' — free tier, read-only |

---

## User Role Flow

```
Listener (free)
    ↓
[Subscribe to plan]
    ↓
Creator (premium) — role_id = 2
    ↓
[Subscription expires]
    ↓
(Scheduler runs nightly)
    ↓
Listener (free) — role_id reverted
```

---

## Testing

### Manual Test
1. Create a test user with an active subscription that has `ends_at` set to a past date:
   ```bash
   php artisan tinker
   ```
   ```php
   $user = User::find(5);  // or create one
   $sub = Subscription::create([
       'user_id' => $user->id,
       'plan_id' => 1,
       'starts_at' => now()->subMonths(2),
       'ends_at' => now()->subDay(),  // expired yesterday
       'status' => 'active'
   ]);
   ```

2. Run the command manually:
   ```bash
   php artisan app:check-expired-subscriptions
   ```

3. Check user's role was reverted:
   ```php
   $user->refresh();
   dd($user->role_id);  // should be listener (role 3)
   ```

4. Check logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Production Test
- Ensure your server cron is configured (see Environment Setup above)
- Monitor `storage/logs/laravel.log` for daily command execution
- Manually verify a few users' roles after subscriptions expire

---

## Troubleshooting

### Command doesn't run automatically
**Check:** Is the cron job configured on your server?
```bash
crontab -l
```
Should see:
```
* * * * * cd /path/to/jaim-10 && php artisan schedule:run >> /dev/null 2>&1
```

### Role not reverting
**Check:**
1. Is the subscription `status` set to 'active'?
2. Is `ends_at` in the past?
3. Does the 'listener' role exist in `user_roles` table?

```php
// Debug in tinker:
Subscription::where('status', 'active')->where('ends_at', '<', now())->count();
UserRole::where('name', 'listener')->first();
```

### Logs show "listener role not found"
**Fix:** Ensure your `user_roles` table has a role named 'listener':
```bash
php artisan tinker
```
```php
UserRole::create(['name' => 'listener']);
```

---

## Code Locations

- **Scheduler Command:** `app/Console/Commands/CheckExpiredSubscriptions.php`
- **Task Scheduler:** `app/Console/Kernel.php` → `schedule()` method
- **Config:** `config/subscription.php`
- **Models:**
  - `app/Models/Subscription.php`
  - `app/Models/User.php`
  - `app/Models/UserRole.php`
- **Controller (subscription creation):** `app/Http/Controllers/SubscriptionController.php` → `success()` method

---

## Future Enhancements

1. **Email notifications** — notify users X days before expiry
2. **Auto-renewal** — allow users to set up recurring subscriptions
3. **Grace period** — delay role revert for a few days after expiry
4. **Subscription tiers** — different roles/features for different plans
5. **Usage limits** — e.g., 'creator' can upload max 100 tracks per month

---
