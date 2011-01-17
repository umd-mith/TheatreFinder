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
$select = "SELECT city_id, city, orig_city FROM cities;";
//$selectChk = "SELECT count(*) from cities where city=?;";
//$insert = "UPDATE cities SET city=? where city_id=?;";
//$selCities = "SELECT city_id, city from cities;";

$selId = "SELECT city_id from cities where city=?;";
$insertAlias = "INSERT INTO city_aliases SET city_id=?, city_alias=?;";


$selectStmt =& genericSQLPrep($cnxn, $select);
//$selectStmtCheck =& genericSQLPrep($cnxn, $selectChk);
//$insertStmt =& genericSQLPrep($cnxn, $insert);
//$selCitiesStmt =& genericSQLPrep($cnxn, $selCities);

$selCityIdStmt =& genericSQLPrep($cnxn, $selId);
$insAliasStmt =& genericSQLPrep($cnxn, $insertAlias);


$selectStmt->execute();
$selectStmt->bind_result($id, $city, $orig_city);
$selectStmt->store_result();

$parenRegex = '/\(|\)|,/';
$spacePat = "[[:space:]]+";
$cities = array();
$cityAliases = array();

while ($selectStmt->fetch()) {
	
	if (preg_match($parenRegex, $orig_city)) {
		$alias = "";
		echo "CITY WITH ALIAS: [".$id."][".$city."]".$orig_city."<BR>";
		$aliasList = preg_split($parenRegex, $orig_city);
		
		$cities[$id] = trim($aliasList[0]);
		for($i=1; $i<count($aliasList); $i++) {
			if (($aliasList[$i]==='') || is_null($aliasList[$i])) {
				// skip
			} else {
				echo "ALIAS LIST[".$i."] ".$aliasList[$i]."<br>";
				$cityAliases[$aliasList[$i]] = $aliasList[0];
			}
		}		
	} else {
		echo "CITY: [".$id."][".$city."]".$orig_city."<br>";	
		$cities[$id] = $orig_city;
	}
}

$selectStmt->close();
echo "<hr>";
foreach ($cities as $k=>$v) {
	echo "[".$k."][".$v."]<br>";
}
echo "<hr><hr>";
/*	foreach ($cities as $id => $city) {
		$city = trim($city);
		// bind the city name to the select
		$selectStmtCheck->bind_param('s', $city);
		$selectStmtCheck->execute();
		// bind the result to our row count, $num
		$selectStmtCheck->bind_result($num);
		// check the count -- if it exists, don't re-load it
		$selectStmtCheck->store_result();
		$selectStmtCheck->fetch();
		if ($num>0) {
			echo "CITY: ".$city." Already EXISTS in cities db<br>";

		} else {
			echo "INSERTING CITY: ".$city."<br>";
			$insertStmt->bind_param('si', $city, $id);
			$insertStmt->execute()
			or die ("Could not execute cities INSERT: city: ".$city."<br>");	
		}
	}
	
	$selectStmtCheck->close();

	$selCitiesStmt->execute();
	$selCitiesStmt->bind_result($cityId, $city);
	while ($selCitiesStmt->fetch()) {
		echo "city[".$cityId."]=>".$city."<br>";
	}
	$selCitiesStmt->free_result();
	$selCitiesStmt->close();
*/

//	$cityId=0;
	echo "+++++++++++++++++++++++++<br>";
	foreach ($cityAliases as $alias=>$mainCity) {
		$alias = trim($alias);
		$mainCity = trim($mainCity);
		
		echo "MAIN CITY: [".$mainCity."] for ALIAS==>[".$alias."]<br>";
		
		$selCityIdStmt->bind_param('s', $mainCity);
		$selCityIdStmt->execute();
		$selCityIdStmt->bind_result($cityId);
		while ($selCityIdStmt->fetch()) {
			$selCityIdStmt->store_result();
			echo "CITY[".$cityId."] ==>".$mainCity." Alias: ".$alias."<br>";
			echo "INSERTING ALIAS: ".$alias."<br>";
			$insAliasStmt->bind_param('is', $cityId, $alias);
			$insAliasStmt->execute()
				or die ("Could not execute city_alias INSERT for alias: ".$alias."<br>");	
		}
		
	}
	
$selCityIdStmt->free_result();
$selCityIdStmt->close();
$insAliasStmt->free_result();
$insAliasStmt->close();

$cnxn->close();

?>
