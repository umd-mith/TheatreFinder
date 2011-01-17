<?php

echo "PRE CLASS INCLUDE <br>";
require("./phmagick.php");

echo "INCLUDED?<br>";
echo "Let's do it";

$phMagick = new phMagick('./auditorium.jpg', 'resized2.jpg');
$phMagick->debug=true;
$phMagick->resize(200,0);
echo '<pre>', print_r($phMagick->getLog()) , '</pre>';

echo "<br>We did it<br>";
echo "<html><head></head><body>";
echo '<img src="resized2.jpg">';
echo '<pre>', print_r($phMagick->getLog()) , '</pre>';
echo "</body></html>";
?>
