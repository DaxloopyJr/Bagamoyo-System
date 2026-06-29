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

        $this->info('Starting license reminder SMS notifications...');
        $this->newLine();

        // 21 days reminder
        $this->sendRemindersForPeriod($smsService, 21, 'license_reminder_21', $sent, $failed);

        // 14 days reminder
        $this->sendRemindersForPeriod($smsService, 14, 'license_reminder_14', $sent, $failed);

        // 7 days reminder
        $this->sendRemindersForPeriod($smsService, 7, 'license_reminder_7', $sent, $failed);

        // 1 day after expiry
        $this->sendExpiredReminders($smsService, $sent, $failed);

        $this->newLine();
        $this->info("License reminder SMS completed: {$sent} sent, {$failed} failed");

        return $failed > 0 ? 1 : 0;
    }

    protected function sendRemindersForPeriod(SmsService $smsService, int $days, string $type, int &$sent, int &$failed)
    {
        $targetDate = now()->copy()->addDays($days)->format('Y-m-d');

        $licenses = BusinessLicense::where('is_active', true)
            ->whereDate('expiry_date', $targetDate)
            ->get();

        if ($licenses->isEmpty()) {
            $this->line("  No licenses expiring in {$days} days");
            return;
        }

        $this->info("  Sending {$days}-day reminders to {$licenses->count()} license(s)...");

        foreach ($licenses as $license) {
            $message = "Habari {$license->owner_name}, leseni yako ya biashara {$license->license_number} itaisha baada ya siku {$days} tarehe {$license->expiry_date->format('d M Y')}. Tafadhali fanya upya katika Ofisi ya Halmashauri ya Manispaa ya Bagamoyo. Asante.";

            $result = $smsService->send($license->phone, $message, $license, $type);

            if ($result['success']) {
                $sent++;
                $this->line("    OK: {$license->phone} - {$license->owner_name}");
            } else {
                $failed++;
                $this->error("    FAILED: {$license->phone} - " . ($result['error'] ?? 'Unknown error'));
            }
        }
    }

    protected function sendExpiredReminders(SmsService $smsService, int &$sent, int &$failed)
    {
        $now = now();

        $expiredYesterday = BusinessLicense::where('is_active', true)
            ->whereDate('expiry_date', $now->copy()->subDay())
            ->get();

        if ($expiredYesterday->isEmpty()) {
            $this->line("  No licenses expired yesterday");
            return;
        }

        $this->info("  Sending expiry reminders to {$expiredYesterday->count()} license(s)...");

        foreach ($expiredYesterday as $license) {
            $message = "Habari {$license->owner_name}, leseni yako ya biashara {$license->license_number} iliisha jana ({$license->expiry_date->format('d M Y')}). Tafadhali fanya upya mara moja katika Ofisi ya Halmashauri ya Manispaa ya Bagamoyo ili kuepuka adhabu. Asante.";

            $result = $smsService->send($license->phone, $message, $license, 'license_expired_1');

            if ($result['success']) {
                $sent++;
                $this->line("    OK: {$license->phone} - {$license->owner_name}");
            } else {
                $failed++;
                $this->error("    FAILED: {$license->phone} - " . ($result['error'] ?? 'Unknown error'));
            }
        }
    }
}
