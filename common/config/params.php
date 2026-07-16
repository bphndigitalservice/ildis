<?php
$recaptchaEnabled = getenv('RECAPTCHA_ENABLED');
if ($recaptchaEnabled === false || $recaptchaEnabled === '') {
    $recaptchaEnabled = false;
} else {
    $recaptchaEnabled = filter_var($recaptchaEnabled, FILTER_VALIDATE_BOOLEAN);
}

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'recaptcha.enabled' => $recaptchaEnabled,
    'recaptcha.siteKey' => getenv('RECAPTCHA_SITE_KEY') ?: '',
    'recaptcha.secretKey' => getenv('RECAPTCHA_SECRET_KEY') ?: '',
    // Google Analytics GA4 — per-satker Measurement ID from .env (template: ISI_MEASUREMENT_ID_DI_SINI)
    'ga.measurementId' => getenv('GA_MEASUREMENT_ID') ?: '',
];
