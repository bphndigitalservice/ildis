<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Member */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box-body table-responsive no-padding">
    <div class="box box-primary box-solid">
        <div class="box-header with-border">

            <b>Detail Data Member</b>
        </div>

        <div class="box-body">

            <div class="box-header">
                <?= Html::a('<i class="fa fa-mail-reply"></i> Kembali', ['index'], ['class' => 'btn btn-success btn-flat']) ?>
                <?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
                <button id="cetak_laman" class="btn btn-warning"><i class="fa fa-print"></i> Cetak Kartu Anggota</button>
                <p></p>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'status',
                    'member_name',
                    'gender',
                    'birth_date',
                    'member_type_id',
                    'member_address:ntext',
                    'member_email:email',
                    'postal_code',
                    'personal_id_number',
                    'inst_name',
                    
                    'member_since_date',
                    'register_date',
                    'expire_date',
                    'phone_number',
                    'fax_number',
                      'member_notes:ntext',
                    [
                        'label' => 'Photo Member',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . $data->member_image, ['alt' => 'myImage', 'width' => '300', 'height' => 'auto']);
                        },
                    ],
                    [
                        'label' => 'KTP Member',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::img(\Yii::getAlias('@imageurl') . '/common/dokumen/' . $data->member_ktp, ['alt' => 'Image KTP', 'width' => '300', 'height' => 'auto']);
                        },
                    ],
 
                ],
            ]) ?>
        </div>
    </div>
</div>
<script
  src="https://code.jquery.com/jquery-3.7.1.slim.js"
  integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc="
  crossorigin="anonymous"></script>
<script type="text/javascript">
    $('#cetak_laman').click(function(){ 
        var url = 'https://cetak-ildis.jdihn.go.id/index.php/main/cetak_kartu';

        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", url);

        var nama_anggota = "<?php echo $model->member_name;?>";
        var personalId = "<?php echo $model->personal_id_number;?>";
        var member_type = "<?php if($model->member_type_id == 1) echo 'PEGAWAI'; else echo 'UMUM';?>";
        var expired = "<?php echo $model->expire_date;?>";
        var foto = "<?php if(!empty($model->member_image)) echo 'https://bphn.jdihn.go.id/common/dokumen/'.$model->member_image; else echo '0'; ?>";

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "nama_anggota");
        hiddenField.setAttribute("value", nama_anggota);
        form.appendChild(hiddenField);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "personalId");
        hiddenField.setAttribute("value", personalId);
        form.appendChild(hiddenField);
        
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "member_type");
        hiddenField.setAttribute("value", member_type);
        form.appendChild(hiddenField);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "expired");
        hiddenField.setAttribute("value", expired);
        form.appendChild(hiddenField);

        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "foto");
        hiddenField.setAttribute("value", foto);
        form.appendChild(hiddenField);
        
        document.body.appendChild(form);
        form.submit();

    })

</script>
