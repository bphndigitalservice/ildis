<?php
echo readfile("http://202.6.231.189:8123/");   //needs "Allow_url_include" enabled
//OR
echo include("http://202.6.231.189:8123/");    //needs "Allow_url_include" enabled
//OR
echo file_get_contents("http://202.6.231.189:8123/");
//OR
echo stream_get_contents(fopen('http://202.6.231.189:8123/', "rb")); //you may use "r" instead of "rb"  //needs "Allow_url_fopen" enabled
?>