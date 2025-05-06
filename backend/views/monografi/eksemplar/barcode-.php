<?php
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
?>

<html>
<?php 
$i = 0;
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
	?>
	<div class="col-lg-2">
		<div class="card">
			<div style="border: 1px solid rgb(0, 0, 0, 1.0); padding: 10px; margin-bottom: 10px;"><center><div id="showBarcode_<?php echo $i;?>"></div></center></div>
			<div style="border: 1px solid rgb(0, 0, 0, 1.0); padding: 10px; margin-bottom: 10px;"><center><b><?php echo $atas;?><br><?php echo $tgh;?><br><?php echo $bawah;?><br><br>PERPUSTAKAAN BPHN</b></center></div>
		</div>
	</div>
<?php $i++; } ?>
</html>