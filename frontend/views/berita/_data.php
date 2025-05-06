<?php

use yii\helpers\Html;
?>



<div class="col-lg-4 col-md-6 col-sm-12 margin-30px-bottom">
    <div class="card border-0 shadow h-100">
        <a href="#">

            <?= Html::a(Html::img('@web/common/dokumen/' . $model->image, ['class' => 'card-img-top rounded']), ['berita/view', 'id' => $model->id]); ?>

        </a>
        <div class="card-body padding-30px-all">
            <div class="margin-10px-bottom">
                <span><?= $model->getTanggal($model->tanggal); ?></span>

            </div>
            <h5 class="card-title font-size22 font-weight-500 magin-20px-bottom">
                <a href="blog-details.html" class="text-extra-dark-gray">
                    <?=
                        Html::a(implode(" ", array_slice(explode(" ", htmlentities($model->judul)), 0, 4)) . '....', ['berita/view', 'id' => $model->id]);
                    ?>
                </a>
            </h5>

            <p class="no-margin-bottom"> <?= implode(" ", array_slice(explode(" ", strip_tags($model->isi)), 0, 20)) . '....'; ?></p>
        </div>
    </div>
</div>