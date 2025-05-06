<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
/** @var yii\web\View $this */
/** @var backend\models\PcounterUsers $model */

$this->title = 'Cetak Barcode per Eksemplar';
$this->params['breadcrumbs'][] = ['label' => 'Monografi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<html>
<button id="cetak_laman" class="btn btn-primary btn-lg"><i class="fa fa-print"></i> Cetak Laman</button>
<div id="printableArea" class="row printarea">
<?php 
$i = 0;
$isbn = array();

foreach ($result as $key) {
	$optionsArray = array(
	'elementId'=> 'showBarcode_'.$i, /* div or canvas id*/
	'value'=> $key->kode_eksemplar, /* value for EAN 13 be careful to set right values for each barcode type */
	'type'=>'code39',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
	
	);

	echo BarcodeGenerator::widget($optionsArray);
	$del = explode(' ', $key->no_panggil);
	$atas = "";
	$tgh = "";
	$bawah = "";

	for ($j=0; $j < sizeof($del); $j++) { 		
		if(sizeof($del) - $j == 1)
			$bawah = $del[$j];
		else if(sizeof($del) - $j == 2)
			$tgh = $del[$j];
		else
			$atas .= $del[$j];
	}

	$temp = array(
		'isbn' => $key->kode_eksemplar,
		'panggil_1' => $atas,
		'panggil_2' => $tgh,
		'panggil_3' => $bawah
	);
	array_push($isbn, $temp);
	
	?>
	<div class="col-lg-2">
		<div class="card">
			<div style="border: 1px solid rgb(0, 0, 0, 1.0); padding: 10px; margin-bottom: 10px;"><center><div id="showBarcode_<?php echo $i;?>"></div></center></div>
			<div style="border: 1px solid rgb(0, 0, 0, 1.0); padding: 10px; margin-bottom: 10px;"><center><b><?php echo $atas;?><br><?php echo $tgh;?><br><?php echo $bawah;?><br><br>PERPUSTAKAAN BPHN</b></center></div>
		</div>
	</div>
<?php $i++; } 
	
	$cetak = json_encode($isbn);
?>
	
</div>
</html>
<script
  src="https://code.jquery.com/jquery-3.7.1.slim.js"
  integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc="
  crossorigin="anonymous"></script>
<script type="text/javascript">
	$('#cetak_laman').click(function(){ 
		var url = 'https://cetak-ildis.jdihn.go.id/index.php/main/cetak_barcode';

		var form = document.createElement("form");
	    form.setAttribute("method", "post");
	    form.setAttribute("action", url);
	    var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "isbn");
        hiddenField.setAttribute("value", JSON.stringify(<?php echo $cetak;?>));
        form.appendChild(hiddenField);
        document.body.appendChild(form);
    	form.submit();

	})

</script>
