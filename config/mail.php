<?php

return [

    'mailers' => [
        'mailcoach' => [
            'transport' => 'mailcoach',
            'domain' => env('MAILCOACH_CLOUD_DOMAIN'),
            'token' => env('MAILCOACH_CLOUD_API_KEY'),
        ],

        'mailgun' => [
            'transport' => 'mailgun',
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'postmark' => [
            'transport' => 'postmark',
            'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],
    ],

];
