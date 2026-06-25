<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $provider;
    protected $config;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'twilio');
        $this->config = config("services.sms.{$this->provider}", []);
    }

    public function send($to, $message, $notifiable = null, $smsType = 'custom')
    {
        // Format phone number
        $to = $this->formatPhoneNumber($to);

        // Log the SMS attempt
        $log = SmsLog::create([
            'recipient_phone' => $to,
            'message' => $message,
            'sms_type' => $smsType,
            'notifiable_type' => $notifiable ? get_class($notifiable) : null,
            'notifiable_id' => $notifiable ? $notifiable->id : null,
            'status' => 'pending',
            'sent_by' => auth()->id(),
        ]);

        try {
            $result = match ($this->provider) {
                'twilio' => $this->sendTwilio($to, $message),
                'beem' => $this->sendBeem($to, $message),
                default => $this->sendLogOnly($to, $message),
            };

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'provider_response' => is_string($result) ? $result : json_encode($result),
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error("SMS sending failed: " . $e->getMessage());

            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function sendTwilio($to, $message)
    {
        $sid = $this->config['sid'] ?? null;
        $authToken = $this->config['auth_token'] ?? null;
        $from = $this->config['phone_number'] ?? null;

        if (!$sid || !$authToken || !$from) {
            // Fallback to log-only mode for demo
            return $this->sendLogOnly($to, $message);
        }

        $response = Http::withBasicAuth($sid, $authToken)
            ->asForm()
            ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                'To' => $to,
                'From' => $from,
                'Body' => $message,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Twilio error: ' . $response->body());
        }

        return $response->json();
    }

    protected function sendBeem($to, $message)
    {
        $apiKey = $this->config['api_key'] ?? null;
        $secretKey = $this->config['secret_key'] ?? null;
        $senderId = $this->config['sender_id'] ?? 'BAGAMOYO';

        if (!$apiKey || !$secretKey) {
            return $this->sendLogOnly($to, $message);
        }

        $response = Http::withBasicAuth($apiKey, $secretKey)
            ->post('https://apisms.beem.africa/v1/send', [
                'source_addr' => $senderId,
                'encoding' => 0,
                'schedule_time' => '',
                'recipients' => [
                    ['recipient_id' => uniqid(), 'dest_addr' => $to]
                ],
                'message' => $message,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Beem error: ' . $response->body());
        }

        return $response->json();
    }

    protected function sendLogOnly($to, $message)
    {
        // For demo/development - log the SMS without actually sending
        Log::info("SMS would be sent to {$to}: {$message}");
        return ['message_sid' => 'demo_' . uniqid(), 'status' => 'sent'];
    }

    protected function formatPhoneNumber($number)
    {
        // Remove any non-numeric characters
        $number = preg_replace('/[^0-9+]/', '', $number);

        // Ensure it starts with country code
        if (str_starts_with($number, '0')) {
            $number = '+255' . substr($number, 1);
        }
        if (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }

        return $number;
    }
}
