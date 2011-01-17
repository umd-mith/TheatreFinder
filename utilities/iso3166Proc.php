<?php
$lines = file('/Bee_School/CAMP/ISO-3166countryCodes');

$cnxn = new mysqli("localhost","root","root","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 

$insert = "INSERT INTO country_codes SET country_digraph=?, country_name=?;";
//$select = "SELECT ";

$insertStmt =& genericSQLPrep($cnxn, $insert);
//$selectCountry =& genericSQLPrep($cnxn, $select);

// Loop through our array, show HTML source as HTML source; and line numbers too.
foreach ($lines as $line_num => $line) {
	
	list($country_name, $country_digraph) = split(';', $line);
	
	$country_name = ucwords(strtolower($country_name));
	
	echo "INSERTING: ".$country_digraph."/".$country_name."<br>";
	
	$insertStmt->bind_param('ss', $country_digraph, $country_name);
    $insertStmt->execute()
		or die ("Could not execute country_codes INSERT: country_digraph: ".$country_digraph." country_name:".$country_name."<br>");	
    echo "Line #<b>{$line_num}</b> : Digraph: " .$country_digraph." Name: ".$country_name."<br />";
	
}

$cnxn->close();


/* ****************************
 * Function:	&genericSQLPrep
 * @@Inputs:	1) ref to db connection
 * 				2) query of interest
 * @@Output:		Returns the REF to the prepared query statement
 * 
 * Notes:		Wrapper that prepares the db statement
 * 				Useful when you need to loop through
 * 				the same query lots of times
 * 				The prepared stmt reduces mysql overhead		
 * *****************************/
function &genericSQLPrep(&$dbObj, $query) {

echo "IN PREP, QUERY IS: ".$query."<Br><BR>";
	if ($queryStmt = $dbObj->prepare($query)) {
		return $queryStmt;
	} else {
		echo "###TILT TILT####<br>ERROR on prepp'ing Query: ".$query."<br>###ABORT ABORT###<br>";
	}
}
?>
