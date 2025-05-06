<div id="showBarcode"></div>
<?php  

use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
 $optionsArray = array(
'elementId'=> 'showBarcode', 
'value'=> '12345678', 
'type'=>'code128',     
);
echo BarcodeGenerator::widget($optionsArray);
?>