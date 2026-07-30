<?php

use adminlte\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;



use common\components\DocumentGroup;
use common\models\DocumentType;
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

                <?= Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . (\Yii::$app->user->identity->picture ?? ''), ['class' => 'img-circle', 'alt' => 'myImage', 'width' => '160', 'height' => 'auto']); ?>

            </div>
            <div class="pull-left info">
                <p><?= \Yii::$app->user->identity->username ?? '' ?></p>
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

        $puuTypes = DocumentType::findByGroup(DocumentGroup::LEGISLATION_FORMATION);
        if ($puuTypes && Yii::$app->user->can('/document-group/legislation-formation')) {
            $puuChildren = array_map(static function (DocumentType $t) {
                return [
                    'label' => $t->name,
                    'url' => [
                        '/dokumen-pembentukan-puu/index',
                        'DokumenPembentukanPuuSearch[documentTypeId]' => $t->id,
                    ],
                ];
            }, $puuTypes);

            $puuGroup = [
                'label' => 'Dokumen Penyusunan PUU',
                'url' => ['#'],
                'icon' => 'fa fa-file-text-o',
                'items' => $puuChildren,
            ];

            $found = false;
            foreach ($items2 as $i => $item) {
                if ($item['label'] === 'Dokumen Hukum') {
                    $existingPuuGroupIndex = null;
                    foreach (($items2[$i]['items'] ?? []) as $childIndex => $child) {
                        if (($child['label'] ?? null) === 'Dokumen Penyusunan PUU') {
                            $existingPuuGroupIndex = $childIndex;
                            break;
                        }
                    }

                    if ($existingPuuGroupIndex !== null) {
                        $existingItems = $items2[$i]['items'][$existingPuuGroupIndex]['items'] ?? [];
                        $existingLabels = array_map(static function (array $menuItem) {
                            return $menuItem['label'] ?? null;
                        }, $existingItems);

                        foreach ($puuChildren as $childItem) {
                            if (!in_array($childItem['label'], $existingLabels, true)) {
                                $existingItems[] = $childItem;
                            }
                        }

                        $items2[$i]['items'][$existingPuuGroupIndex]['items'] = $existingItems;
                    } else {
                        $items2[$i]['items'][] = $puuGroup;
                    }

                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $items2[] = $puuGroup;
            }
        }

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