<?php
require("./dbFunctions.php");

echo "<?xml version='1.0' encoding='UTF-8'?>";


$cnxn = new mysqli("localhost","tf_admin","tfed1t0r","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 

$select = "SELECT id, est_earliest, earliestdate_bce_ce, "
		."est_latest, latestdate_bce_ce "
		."FROM theatres where est_latest=0;";

$selectStmt =& genericSQLPrep($cnxn, $select);

$insert = "UPDATE theatres SET est_latest=?, latestdate_bce_ce=? "
		." WHERE est_earliest=? and earliestdate_bce_ce=? and id=?;";

$insertStmt =& genericSQLPrep($cnxn, $insert);

$earlyList = array();
$selectStmt->execute();
$selectStmt->bind_result($id, $estEarliest, $earlyBCE_CE, $estLatest, $lateBCE_CE);
$selectStmt->store_result();
while ($selectStmt->fetch()) {

//	echo $estEarliest.": ".$earlyBCE_CE." - ".$estLatest.": ".$lateBCE_CE."<br>";

	if ($estEarliest !== 0) {
			
		$earlyList[$id] = $estEarliest.":".$earlyBCE_CE;
	}

}

	$selectStmt->close();

	foreach ($earlyList as $key => $value) {
		
		//echo "KEY/ID: ".$key."--> ".$value."<br>";
		list($estEarliest, $earlyBCE_CE) = split(":", $value);
		echo "VALUE SPLIT: ".$estEarliest."--> ".$earlyBCE_CE."<br>";
		
		// Weirdness -- assign new 'late' date vars to the
		// early one -- see if this updates database
		$estLatest = $estEarliest;
		$lateBCE_CE = $earlyBCE_CE;
		
		$insertStmt->bind_param('isisi', $estLatest, $lateBCE_CE, $estEarliest, $earlyBCE_CE, $key)
				or die("PROBLEM BINDING!<BR>");
	
			echo "AFTER BIND error is: [".$cnxn->error."]<BR>";
			echo "PRE-INSERT: ".$estLatest." -> ".$estEarliest." and earlyBCE_CE = ".$earlyBCE_CE." and lateBCE_CE = ".$lateBCE_CE."<BR>";
			$insertStmt->execute()
				or die ("Could not execute theatres INSERT: estLatest: ".$estEarliest."<br>");
			echo "POST INSERT: [".$cnxn->error."]<br>";
			/* commit transaction */
			$cnxn->commit();
			
		
	}
$insertStmt->close();

$cnxn->close();

?>
