<?php

use adminlte\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;



use mdm\admin\components\Helper;
use mdm\admin\components\MenuHelper;
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">

                <?= Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . \Yii::$app->user->identity->picture, ['class' => 'img-circle', 'alt' => 'myImage', 'width' => '160', 'height' => 'auto']); ?>

            </div>
            <div class="pull-left info">
                <p><?= \Yii::$app->user->identity->username ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form 
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
       search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php
        $menuItems = [['label' => 'MAIN NAVIGATION', 'options' => ['class' => 'header']]];

        $callback = function ($menu) {
            $data = $menu['data'];
            return [
                'label' => $menu['name'],
                'url' => [$menu['route']],
                'option' => $data,
                'icon' => $menu['data'],
                'items' => $menu['children'],
            ];
        };
        // $items2 = MenuHelper::getAssignedMenu(Yii::$app->user->id);
        $items2 = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback, true);

        //$items = $menuItems + $items2;
        ?>

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $menuItems,
            ]
        )
        ?>
        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items2,
            ]
        )
        ?>

    </section>
    <!-- /.sidebar -->
</aside>