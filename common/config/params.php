<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'recaptcha.siteKey' => getenv('RECAPTCHA_SITE_KEY'),
    'recaptcha.secretKey' => getenv('RECAPTCHA_SECRET_KEY'),
];
