<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Dokumen Putusan Mahkamah Agung';
?>

<div class="dokumen-index-wrapper" style="background-color: #f8fafc; min-height: 100vh; padding-top: 80px;">
    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <?= $this->render('_search-sidebar', ['model' => $searchModel]) ?>

            <!-- Results List -->
            <div class="col-lg-9">
                <div class="results-container bg-white rounded-4 p-4 p-md-5" style="box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
                    <div class="results-summary mb-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            <?= $this->render('_results-summary', ['dataProvider' => $dataProvider]) ?>
                        </div>
                    </div>

                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'options' => ['class' => 'results-list'],
                        'itemOptions' => ['class' => 'item'],
                        'itemView' => '_data',
                        'summary' => false,
                        'pager' => require __DIR__ . '/_pager.php',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

