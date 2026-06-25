<?php

namespace App\Console\Commands;

use App\Models\License\BusinessLicense;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendLicenseReminders extends Command
{
    protected $signature = 'licenses:send-reminders';
    protected $description = 'Send SMS reminders for licenses expiring in 21, 14, 7 days and expired 1 day ago';

    public function handle(SmsService $smsService)
    {
        $now = now();
        $sent = 0;
        $failed = 0;

        // 21 days reminder
        $this->sendRemindersForPeriod($smsService, 21, 'license_reminder_21', $sent, $failed);

        // 14 days reminder
        $this->sendRemindersForPeriod($smsService, 14, 'license_reminder_14', $sent, $failed);

        // 7 days reminder
        $this->sendRemindersForPeriod($smsService, 7, 'license_reminder_7', $sent, $failed);

        // 1 day after expiry
        $expiredYesterday = BusinessLicense::where('is_active', true)
            ->whereDate('expiry_date', $now->copy()->subDay())
            ->get();

        foreach ($expiredYesterday as $license) {
            $message = "Hello {$license->owner_name}, your business license {$license->license_number} expired yesterday ({$license->expiry_date->format('d M Y')}). Please renew immediately to avoid penalties. - Bagamoyo Municipal Council";
            try {
                $smsService->send($license->phone, $message, $license, 'license_expired_1');
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed for {$license->phone}: " . $e->getMessage());
            }
        }

        $this->info("SMS reminders sent: {$sent}, failed: {$failed}");
        return 0;
    }

    protected function sendRemindersForPeriod(SmsService $smsService, int $days, string $type, int &$sent, int &$failed)
    {
        $targetDate = now()->copy()->addDays($days)->format('Y-m-d');

        $licenses = BusinessLicense::where('is_active', true)
            ->whereDate('expiry_date', $targetDate)
            ->get();

        foreach ($licenses as $license) {
            $message = "Hello {$license->owner_name}, your business license {$license->license_number} will expire in {$days} days on {$license->expiry_date->format('d M Y')}. Please renew at Bagamoyo Municipal Council. - Bagamoyo Municipal Council";
            try {
                $smsService->send($license->phone, $message, $license, $type);
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed for {$license->phone}: " . $e->getMessage());
            }
        }
    }
}
