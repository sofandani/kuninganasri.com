<?php
$image = isset($_GET['url'])?$_GET['url']:false;
if($image==false) exit('Error');
$image = imagecreatefrompng($image);
$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
imageconvolution($image, $gaussian, 16, 0);
header('Content-Type: image/png');
imagepng($image, null, 9);
?>	