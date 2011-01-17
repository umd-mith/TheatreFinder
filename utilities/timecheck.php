<?php
    $timestampInSeconds = $_SERVER['REQUEST_TIME'];
	echo "TimeStamp seconds: ".$timestampInSeconds."<br>";
	
	$mySqlDateTime= date("Y-m-d H:i:s", $timestampInSeconds);
	
	echo " MYSQL datetime: ".$mySqlDateTime."<br>";
?>
