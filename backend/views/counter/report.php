<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PcounterUsers $model */

$this->title = "Rekap Pengunjung";
$this->params['breadcrumbs'][] = ['label' => 'Pcounter Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $judul?></h3>

    </div>
    <!-- /.box-header -->
    <div class="box-body">

        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <label>Pilih tanggal awal - akhir</label>
            <input type="text" id="dateselect_filter" name="tgls" class="form-control">
        </div>
   
        <div class="form-group">
            <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

        <table class="table table-bordered table-striped ">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Pengunjung</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total = 0; 
                    foreach ($model as $key) {
                        echo "<tr>";
                        echo "<td>".$key['tgl']."</td>";
                        echo "<td>".$key['jml']."</td>";
                        echo "</tr>";
                        $total += $key['jml'];
                    }
                ?>
                <tr>
                    <th>TOTAL PENGUNJUNG</th>
                    <th><?php echo $total;?></th>
                </tr> 
            </tbody>
        </table>
    </div>
</div>


<?php
$this->registerJS('$("#dateselect_filter").daterangepicker({autoUpdateInput: false, locale : {cancelLabel: "Clear"}});$("#dateselect_filter").on("apply.daterangepicker", function(ev, picker) {$(this).val(picker.startDate.format("DD-MM-YYYY") + " - " + picker.endDate.format("DD-MM-YYYY"));$("#dateselect_filter").trigger("change");});$("#dateselect_filter").on("cancel.daterangepicker", function(ev, picker) {$(this).val("");$("#dateselect_filter").trigger("change");});');
?>