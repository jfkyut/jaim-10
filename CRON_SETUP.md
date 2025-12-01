# Server Cron Setup for Task Scheduler

## Required for Production

Laravel's task scheduler requires a cron job that runs **every minute** on your server.

### Step 1: SSH into your server

```bash
ssh user@your-server-ip
```

### Step 2: Open crontab editor

```bash
crontab -e
```

### Step 3: Add this line

```bash
* * * * * cd /path/to/jaim-10 && php artisan schedule:run >> /dev/null 2>&1
```

Replace `/path/to/jaim-10` with the actual path to your Laravel project directory.

**Example:**
```bash
* * * * * cd /home/www/jaim-10 && php artisan schedule:run >> /dev/null 2>&1
```

### Step 4: Save and verify

- Press `Ctrl+X`, then `Y`, then `Enter` (if using nano editor)
- Verify the cron was added:
  ```bash
  crontab -l
  ```

---

## For Local Development (Optional)

You can test the scheduler without a cron job by running:

```bash
php artisan schedule:work
```

This runs the scheduler in the foreground and executes scheduled commands as they're due. Keep this terminal open during testing.

---

## Verify It's Working

### Method 1: Check Laravel logs
```bash
tail -f storage/logs/laravel.log
```

You should see entries like:
```
[2025-12-01 14:00:01] local.INFO: Subscription expired for user 5 (user@example.com). Role reverted to listener.
```

### Method 2: Manual test
```bash
php artisan app:check-expired-subscriptions
```

### Method 3: Check cron execution logs (server-dependent)
```bash
# On some systems:
grep CRON /var/log/syslog
```

---

## Troubleshooting

### Cron not running
- Verify PHP path: `which php`
- Check file permissions on Laravel project
- Ensure `storage/` directory is writable
- Check server's PHP error logs

### Command not executing at scheduled time
- Verify cron syntax: `crontab -l`
- Check system time is correct: `date`
- Ensure Laravel can write to logs: `touch storage/logs/laravel.log && chmod 666 storage/logs/laravel.log`

---
