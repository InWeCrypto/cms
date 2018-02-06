<?php

return [
    'host' => env('MAIL_HOST'),
    'port' => env('MAIL_PORT'),
    'username' => env('MAIL_USERNAME'),
    'password' => $_ENV['MAIL_PASSWORD'] ?? env('MAIL_PASSWORD'),
    'expire_time' => 360
];
