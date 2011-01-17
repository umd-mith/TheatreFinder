<?php
require("./dbFunctions.php");

echo "<?xml version='1.0' encoding='UTF-8'?>";

$periodList = array("Baroque",
					"Classical Revival",
					"Post 1815",
					"Renaissance",
					"Medieval",
					"Roman",
					"Roman Odeon",
					"Roman Amphiteatre wt Scaena",
					"Gallo-Roman",
					"Graeco-Roman",
					"Hellenistic",
					"Hellenistic Odeon",
					"Greek",
					"Greek Odeon",
					"Greek Bouleuterion",
					"Minoan",
					"Chinese",
					"Indian",
					"Japanese",
					"Other-see description"
				);

$cnxn = new mysqli("localhost","root","root","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 

$insert = "INSERT INTO period SET period_rep=?;";

$insertStmt =& genericSQLPrep($cnxn, $insert);

// Loop through our array, show HTML source as HTML source; and line numbers too.
foreach ($periodList as $p) {
	
	echo "INSERTING: ".$p."<br>";
	
	$insertStmt->bind_param('s', $p);
    $insertStmt->execute()
		or die ("Could not execute periods INSERT: period: ".$p."<br>");	
    echo "PERIOD ENTERED: ".$p."<br />";
	
}

mysql_close($cnxn);
?>
