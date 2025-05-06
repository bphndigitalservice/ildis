<?php
$dir = 'assets/';
array_map('unlink', glob("{$dir}*.*"));

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
//use app\assets\AppAsset;
use backend\assets_b\AdminLteAsset;

//AppAsset::register($this);
$asset      = AdminLteAsset::register($this);
$baseUrl    = $asset->baseUrl;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition skin-red sidebar-mini">
    <?php $this->beginBody() ?>

    <div class="wrapper" style="background-color: white;">
        <?= $this->render('header-buku-tamu.php', ['baserUrl' => $baseUrl, 'title' => Yii::$app->name]) ?>

        <?= $this->render('content-buku-tamu.php', ['content' => $content]) ?>

    </div>


    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>