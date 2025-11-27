# Uptime Monitoring System

A comprehensive Laravel-based uptime monitoring system with CRUD operations and automated checks.

## Features

✅ **Site Management (CRUD)**
- Add, edit, delete, and manage monitored sites
- Configure check intervals per site
- Activate/deactivate monitoring for specific sites

✅ **Automated Monitoring**
- Periodic uptime checks via cron jobs
- Response time tracking
- HTTP status code logging
- Error message capture

✅ **Analytics**
- Uptime percentage calculation
- Average response time
- Detailed logging history

## Setup Instructions

### 1. Database Migration

Run the migrations to set up the database tables:

```bash
php artisan migrate
```

Or if you want to start fresh:

```bash
php artisan migrate:fresh
```

### 2. Access the Site Management Interface

Navigate to the Sites Livewire component route in your application.

### 3. Set Up Cron Job

The uptime checker runs every minute via Laravel's task scheduler. Add this cron entry to your server:

```bash
* * * * * cd /home/soybean15/work/uptime-monitoring && php artisan schedule:run >> /dev/null 2>&1
```

Or run the scheduler manually for testing:

```bash
php artisan schedule:work
```

## Usage

### Manual Uptime Check

Run an immediate check on all active sites:

```bash
php artisan uptime:check
```

Check a specific site by ID:

```bash
php artisan uptime:check --site-id=1
```

### Adding Sites via UI

1. Navigate to the Sites page
2. Click "Add New Site"
3. Fill in:
   - **Site Name**: Friendly name for the site
   - **URL**: Full URL including protocol (e.g., https://example.com)
   - **Check Interval**: How often to check (in minutes)
   - **Active**: Whether monitoring is enabled
4. Click "Create"

### Viewing Site Status

The Sites page displays:
- Current status (Up/Down/Pending)
- Last check time
- Uptime percentage
- Check interval
- Quick actions (Edit, Delete, Pause/Activate)

## Database Schema

### Sites Table
- `id`: Primary key
- `name`: Site name
- `url`: Site URL
- `check_interval`: Check frequency in minutes (default: 5)
- `is_active`: Whether monitoring is enabled
- `is_up`: Current status (true/false/null)
- `last_checked_at`: Timestamp of last check
- `last_response_time`: Last response time in milliseconds
- `last_status_code`: Last HTTP status code
- `timestamps`: Created/updated timestamps

### Site Logs Table
- `id`: Primary key
- `site_id`: Foreign key to sites table
- `is_up`: Status at check time
- `response_time`: Response time in milliseconds
- `status_code`: HTTP status code
- `error_message`: Error message if check failed
- `checked_at`: Timestamp of check
- `timestamps`: Created/updated timestamps

## Models

### Site Model
Located: `/app/Models/Site.php`

**Relationships:**
- `logs()`: Has many SiteLog records
- `latestLog()`: Has one latest SiteLog

**Computed Attributes:**
- `uptimePercentage`: Calculates uptime percentage based on logs
- `averageResponseTime`: Average response time from successful checks

### SiteLog Model
Located: `/app/Models/SiteLog.php`

**Relationships:**
- `site()`: Belongs to Site

## Livewire Component

### Sites Component
Located: `/app/Livewire/Sites.php`

**Features:**
- Full CRUD operations
- Real-time validation
- Modal-based create/edit forms
- Delete confirmation
- Toggle site active status
- Pagination support

## Console Command

### UptimeChecker Command
Located: `/app/Console/Commands/UptimeChecker.php`

**Features:**
- Checks all active sites or specific site
- Respects check_interval setting
- Measures response time
- Logs all results
- Updates site status
- Progress bar for batch checks

**Options:**
- `--site-id`: Check specific site by ID

## Monitoring Logic

The uptime checker:
1. Queries active sites that need checking based on their interval
2. Makes HTTP requests with 30-second timeout
3. Measures response time in milliseconds
4. Records HTTP status code
5. Considers 2xx and 3xx codes as "up"
6. Captures error messages for failed checks
7. Creates detailed log entry
8. Updates site's current status

## Customization

### Adjusting Check Intervals

Edit the check interval for each site:
- Minimum: 1 minute
- Maximum: 1440 minutes (24 hours)
- Default: 5 minutes

### SSL Verification

By default, SSL verification is disabled for testing. To enable in production, edit `UptimeChecker.php`:

```php
$response = Http::timeout(30)
    ->withOptions([
        'verify' => true, // Enable SSL verification
        'allow_redirects' => true,
    ])
    ->get($site->url);
```

### Notification Integration

To add notifications when sites go down, you can:

1. Listen for status changes in the `UptimeChecker` command
2. Send emails, Slack messages, or SMS alerts
3. Use Laravel's notification system

Example:
```php
if (!$isUp && $site->is_up) {
    // Site just went down
    // Send notification here
}
```

## Testing

Test the monitoring system:

```bash
# Add a test site via UI or tinker
php artisan tinker
>>> App\Models\Site::create(['name' => 'Google', 'url' => 'https://google.com', 'is_active' => true, 'check_interval' => 1])

# Run manual check
php artisan uptime:check

# Check the logs
>>> App\Models\SiteLog::latest()->first()
```

## Troubleshooting

**Sites not being checked:**
- Verify cron job is running: `php artisan schedule:work`
- Check site is active: `is_active = true`
- Verify check interval has elapsed

**HTTP errors:**
- Ensure URLs include protocol (http:// or https://)
- Check firewall/network connectivity
- Review error_message in site_logs table

**Performance:**
- For many sites, consider queuing checks
- Adjust timeout values as needed
- Monitor database size and implement log rotation

## Future Enhancements

- Email/SMS notifications
- Webhook support
- Uptime status page (public)
- Historical uptime graphs
- Multi-region checks
- Custom headers/authentication
- SSL certificate expiry monitoring
