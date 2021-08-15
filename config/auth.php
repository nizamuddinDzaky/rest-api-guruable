<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'm_users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'm_users',
        ],
    ],

    'providers' => [
        'm_users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\MUsersModel::class
        ]
    ]
];