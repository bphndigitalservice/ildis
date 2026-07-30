<?php

/**
 * Google Analytics (GA4) snippet for JDIH satker deployments.
 *
 * Set GA_MEASUREMENT_ID in .env with the satker Measurement ID (e.g. G-ABC123XYZ).
 * Leave the template placeholder as-is (or empty) to keep analytics disabled.
 *
 * @var yii\web\View $this
 */

use yii\helpers\Html;

$gaId = trim((string) (Yii::$app->params['ga.measurementId'] ?? ''));

$isPlaceholder = $gaId === ''
    || strcasecmp($gaId, 'ISI_MEASUREMENT_ID_DI_SINI') === 0
    || preg_match('/^G-X+$/i', $gaId);

if ($isPlaceholder) {
    return;
}
?>
<!-- Google Analytics Start -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= Html::encode($gaId) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= Html::encode($gaId) ?>');
</script>
<!-- Google Analytics End -->
