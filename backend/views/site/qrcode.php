<?php

use 2amigos\qrcode\QrCode;

$qrCode = (new QrCode($finalUrl))
    ->setSize(100)
    ->setMargin(5)
    ->useForegroundColor(1, 1, 1);
echo $qrCode->writeDataUri();//The method can also generate pictures, this is to generate base64
