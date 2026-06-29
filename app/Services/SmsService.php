<?php

namespace App\Services;

use App\Classes\SMPP;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $smppHost;
    protected $smppPort;
    protected $smppLogin;
    protected $smppPassword;
    protected $smppSender;

    public function __construct()
    {
        $this->smppHost = config('services.sms.smpp_host', '196.46.122.141');
        $this->smppPort = config('services.sms.smpp_port', 9001);
        $this->smppLogin = config('services.sms.smpp_login', 'FCT');
        $this->smppPassword = config('services.sms.smpp_password', 'fct@dmin@2023');
        $this->smppSender = config('services.sms.smpp_sender', 'FCT');
    }

    /**
     * Send SMS using SMPP protocol
     *
     * @param string $to Recipient phone number
     * @param string $message Message body
     * @param object|null $notifiable Related model instance
     * @param string $smsType Type of SMS for logging
     * @return array Result with status and message
     */
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
            'sent_by' => auth()->id() ?? null,
        ]);

        try {
            $result = $this->sendViaSmpp($to, $message);

            if ($result['success']) {
                $log->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'provider_response' => $result['status_message'] ?? 'OK',
                ]);
            } else {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $result['error'] ?? 'Unknown error',
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("SMS sending failed: " . $e->getMessage());

            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS via SMPP protocol using the SMPP class
     *
     * @param string $to Recipient phone number
     * @param string $message Message body
     * @return array Result array
     */
    protected function sendViaSmpp($to, $message)
    {
        $smpp = null;

        try {
            // Initialize SMPP connection
            $smpp = new SMPP($this->smppHost, $this->smppPort);
            $smpp->debug = config('app.debug', false);

            // Bind as transmitter
            if (!$smpp->bindTransmitter($this->smppLogin, $this->smppPassword)) {
                return [
                    'success' => false,
                    'error' => 'Failed to bind to SMPP server as transmitter',
                ];
            }

            // Send the SMS
            $result = $smpp->sendSMS($this->smppSender, $to, $message);

            // Get status message
            $statusMessage = $smpp->getStatusMessage($result);

            // Close connection
            $smpp->close();
            unset($smpp);

            if ($result === false) {
                return [
                    'success' => false,
                    'error' => 'SMPP sendSMS returned false. Message may be too long or invalid parameters.',
                ];
            }

            // Check if status is OK (0x00000000 = ESME_ROK)
            if ($result === 0) {
                return [
                    'success' => true,
                    'status_code' => $result,
                    'status_message' => $statusMessage,
                ];
            }

            return [
                'success' => false,
                'status_code' => $result,
                'error' => $statusMessage,
            ];

        } catch (\Exception $e) {
            // Ensure connection is closed on error
            if ($smpp) {
                try {
                    $smpp->close();
                } catch (\Exception $closeEx) {
                    // Ignore close errors
                }
                unset($smpp);
            }

            Log::error("SMPP SMS sending error: " . $e->getMessage());

            return [
                'success' => false,
                'error' => 'SMPP Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send bulk SMS to multiple recipients
     *
     * @param array $recipients Array of ['phone' => ..., 'message' => ...] or phone numbers
     * @param string|null $message Common message (if recipients is just phone numbers)
     * @param string $smsType Type of SMS for logging
     * @return array Results summary
     */
    public function sendBulk(array $recipients, $message = null, $smsType = 'bulk')
    {
        $results = ['sent' => 0, 'failed' => 0, 'errors' => []];

        foreach ($recipients as $recipient) {
            $phone = is_array($recipient) ? ($recipient['phone'] ?? $recipient) : $recipient;
            $msg = is_array($recipient) && isset($recipient['message']) ? $recipient['message'] : $message;

            try {
                $result = $this->send($phone, $msg, null, $smsType);
                if ($result['success']) {
                    $results['sent']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = $phone . ': ' . ($result['error'] ?? 'Unknown error');
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = $phone . ': ' . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Format phone number to international format
     *
     * @param string $number
     * @return string
     */
    protected function formatPhoneNumber($number)
    {
        // Remove any non-numeric characters except +
        $number = preg_replace('/[^0-9+]/', '', $number);

        // If starts with 0, replace with 255
        if (str_starts_with($number, '0')) {
            $number = '255' . substr($number, 1);
        }

        // Remove + if present
        $number = ltrim($number, '+');

        return $number;
    }
}
