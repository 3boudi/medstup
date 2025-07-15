<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'sanctum',
        'provider' => 'users',
    ],

    'user' => [ // 🧑‍💻 المستخدم العادي
        'driver' => 'sanctum',
        'provider' => 'users',
    ],

    'admin' => [ // 🛡️ الأدمن
        'driver' => 'sanctum',
        'provider' => 'admins',
    ],

    'doctor' => [ // 🩺 الطبيب
        'driver' => 'sanctum',
        'provider' => 'doctors',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],

    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],

    'doctors' => [
        'driver' => 'eloquent',
        'model' => App\Models\Doctor::class,
    ],
],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'doctors' => [
            'provider' => 'doctors',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
