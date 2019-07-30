<?php
return [
    'db' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
        ],
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => getenv('DB_NAME'),
        ]
    ],
];
