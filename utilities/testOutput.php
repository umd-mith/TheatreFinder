<?php

require("./dbFunctions.php");

echo "<?xml version='1.0' encoding='UTF-8'?>";
   

//$cnxn = new mysqli("localhost","root","root","theatrefinder");
$cnxn = new mysqli("localhost","tf_admin","tfed1t0r","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 

$select = "SELECT id, theatre_name, country_name, region, city FROM theatres;";

$selectStmt =& genericSQLPrep($cnxn, $select);
//$selectStmtCheck =& genericSQLPrep($cnxn, $selectChk);
//$insertStmt =& genericSQLPrep($cnxn, $insert);

$selectStmt->execute();
$selectStmt->bind_result($id, $t_name, $country, $region, $city);
$selectStmt->store_result();


while ($selectStmt->fetch()) {
	echo $id.": [".$t_name."] ".$country.", ".$region.", ".$city."<br>";
}

$selectStmt->close();	
$cnxn->close();

?>