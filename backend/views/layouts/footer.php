<?php

use yii\helpers\Html;
use mdm\admin\models\User;

$versionPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'VERSION';
$appVersion = file_exists($versionPath) ? trim(file_get_contents($versionPath)) : 'dev';

$user = !Yii::$app->user->isGuest
    ? User::find()->where(['id' => Yii::$app->user->id])->one()
    : null;
$assignments = $user ? Yii::$app->authManager->getAssignments($user->id) : [];
?>
<footer class="main-footer">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 6px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <?php if ($user): ?>
                <span style="display: inline-flex; align-items: center; gap: 5px; color: #555; font-size: 13px;">
                    <i class="fa fa-user-circle-o" style="opacity: .55;"></i>
                    <?= Html::encode($user->username) ?>
                </span>
                <?php foreach ($assignments as $assignment): ?>
                    <span style="
                        display: inline-block;
                        padding: 1px 8px;
                        font-size: 11px;
                        line-height: 18px;
                        border-radius: 3px;
                        background: #f39c1233;
                        color: #a3760d;
                        border: 1px solid #f39c1244;
                        font-weight: 500;
                    "><?= Html::encode($assignment->roleName) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="
                display: inline-block;
                padding: 1px 8px;
                font-size: 11px;
                line-height: 18px;
                border-radius: 3px;
                background: #e8e8e8;
                color: #666;
                font-weight: 500;
                letter-spacing: .3px;
            ">ILDIS <?= Html::encode($appVersion) ?></span>
        </div>
    </div>
</footer>