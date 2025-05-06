<?php


use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;


$this->title = 'Berita';
$this->params['breadcrumbs'][] = $this->title;
?>

<section>
    <img src="frontend/assets/img/bluetop.jpg" class="img-fluid" alt="">
   <br><br><br>
    <div class="container">
    </div>

    <div class="container">
        <div class="text-center">
            <!-- <p><?= Html::a('Home', ['/']); ?></p> -->
            <p><span class="active">Berita</span>
            </p>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row">
            <?= ListView::widget(
                [
                    'summary' => false,
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    // 'itemOptions' => ['tag' => null],
                    'options'      => [
                        'tag' => false,
                    ],
                    'itemOptions'  => [
                        'tag' => false,
                    ],
                    'itemView' => function ($model, $key, $index, $widget) {
                        $itemContent = $this->render(
                            '_data',
                            [
                                'model' => $model,
                                'index' => $index,
                                'key' => $key
                            ]
                        );
                        return $itemContent;
                    }
                ]
            );
            ?>
        </div>
    </div>
</section>
