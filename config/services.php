<?php

return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'smpp'),
        // SMPP Configuration (Airtel Tanzania)
        'smpp_host' => env('SMPP_HOST', '196.46.122.141'),
        'smpp_port' => env('SMPP_PORT', 9001),
        'smpp_login' => env('SMPP_LOGIN', 'FCT'),
        'smpp_password' => env('SMPP_PASSWORD', 'fct@dmin@2023'),
        'smpp_sender' => env('SMPP_SENDER', 'FCT'),
        // Twilio Configuration (fallback)
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'phone_number' => env('TWILIO_PHONE_NUMBER'),
        ],
        // Beem Configuration (fallback)
        'beem' => [
            'api_key' => env('BEEM_API_KEY'),
            'secret_key' => env('BEEM_SECRET_KEY'),
            'sender_id' => env('BEEM_SENDER_ID'),
        ],
    ],
];
