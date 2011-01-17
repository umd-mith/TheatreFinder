<?php

echo "Let's do it";
//exec('convert -thumbnail 130 -quality 90 Albania_Butrint.jpg testThumbnail.jpg');

$img = new Imagick('imageNeeded.png');
$img->setFormat('PNG');
$img->setImageOpacity(1.0);
$img->resizeImage(130,92,Imagick::FILTER_LANCZOS,1);
//$img->scaleImage(130,0);
//$img->cropThumbnailImage(130,92);
$img->writeImage('imageNeededThumbnail.png');

echo "<br>Just did it";

echo "<?xml version='1.0' encoding='UTF-8'?>";

echo "<h3>ORIGINAL</h3>";
echo "<img src=\"./imageNeeded.jpg\" alt=\"thumb\" /></a>";

echo "<h3>THUMB</h3>";
echo "<img src=\"./imageNeededThumbnail.png\" alt=\"thumb\" /></a>";

?>