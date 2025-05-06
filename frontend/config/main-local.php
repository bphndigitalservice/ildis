<?php

require __DIR__ . '/../../common/config/env.php';

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY_FE'),
        ],
    ],
];

//Menghapus debug pada frontend

return $config;