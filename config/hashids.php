<?php

return [
    'default' => 'hashids',
    'connections' => [
        'hashids' => [
            'driver' => 'hashids',
            'salt' => 'abcdefghijklmnopqrstuvwxyz0123456789',
            'length' => 5,
        ]
    ],
];
