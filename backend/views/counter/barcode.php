<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
/** @var yii\web\View $this */
/** @var backend\models\PcounterUsers $model */

$this->params['breadcrumbs'][] = ['label' => 'Pcounter Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$optionsArray = array(
'elementId'=> 'showBarcode', /* div or canvas id*/
'value'=> '4797001018719', /* value for EAN 13 be careful to set right values for each barcode type */
'type'=>'ean13',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
 
);
echo BarcodeGenerator::widget($optionsArray);
?>

<html><div id="showBarcode"></div></html>
