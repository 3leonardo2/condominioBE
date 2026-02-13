<?php

return [

    'default' => env('REVERB_SERVER', 'reverb'),

    'servers' => [

        'reverb' => [
            'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
            'port' => env('REVERB_SERVER_PORT', 8080),
            'hostname' => env('REVERB_HOST', '127.0.0.1'),
            'options' => [
                'tls' => [],
            ],
            'scaling' => [
                'enabled' => env('REVERB_SCALING_ENABLED', false),
                'channel' => env('REVERB_SCALING_CHANNEL', 'reverb'),
            ],
            'pulse_ingest_interval' => env('REVERB_PULSE_INGEST_INTERVAL', 15),
            'telescope_ingest_interval' => env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),
        ],

    ],

    'apps' => [

        'provider' => 'config',

        'apps' => [
            [
                'key' => 'my_app_key',  // ← HARDCODED
                'secret' => 'qrstuvwxyz123456',  // ← HARDCODED
                'app_id' => '123456',  // ← HARDCODED
                'options' => [
                    'host' => '127.0.0.1',  // ← HARDCODED
                    'port' => 8080,  // ← HARDCODED
                    'scheme' => 'http',  // ← HARDCODED
                    'useTLS' => false,  // ← HARDCODED
                ],
                'allowed_origins' => ['*'],
                'ping_interval' => 60,
                'max_message_size' => 10000,
            ],
        ],

    ],

];