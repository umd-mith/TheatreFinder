<?php

require("./dbFunctions.php");

echo "<?xml version='1.0'>";   
// ensure that the internal encoding is UTF-8
mb_internal_encoding("UTF-8");
// ensure that the http output is UTF-8
//	mb_http_output( "UTF-8" );

$cnxn = new mysqli("localhost","root","root","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 

// ensure that the db has the right character set
$cnxn->query("set names utf8;") or die ("ERRor Setting names!<br>");

// Setting up the cities db (from original theatres)
$select = "SELECT city, orig_city FROM theatres;";
$update = "UPDATE theatres SET city=? where orig_city=?;";

$selectStmt =& genericSQLPrep($cnxn, $select);
$updateStmt =& genericSQLPrep($cnxn, $update);

$selectStmt->execute();
$selectStmt->bind_result($city, $orig_city);
$selectStmt->store_result();

$parenRegex = '/\(|\)|,/';
$spacePat = "[[:space:]]+";
//$cities = array();


while ($selectStmt->fetch()) {
	$orig_city = trim($orig_city);
	$city = trim($city);
	if (preg_match($parenRegex, $orig_city)) {
		$alias = "";
		$aliasList = preg_split($parenRegex, $orig_city);
		
		$justCity = trim($aliasList[0]);
		echo "CHANGING CITY WITH ALIAS[".$city."] TO: [".$justCity."]<BR>";
		$updateStmt->bind_param('ss', $justCity, $city) or die("PROBLEM BINDING!<BR>");
		$updateStmt->execute()
			or die ("Could not execute theatres UPDATE of [".$city."] to Just City:[".$justCity."]<br>");;

	} else {
		echo "JUST CITY:[".$city."] NO CHANGE[".$orig_city."]<br>";	
	}
}
$selectStmt->free_result();
$selectStmt->close();


$updateStmt->free_result();
$updateStmt->close();

$cnxn->close();

?>
