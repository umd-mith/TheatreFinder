<?php
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

//echo "IN PREP, QUERY IS: ".$query."<Br><BR>";
	if ($queryStmt = $dbObj->prepare($query)) {
		return $queryStmt;
	} else {
		echo "###TILT TILT####<br>ERROR on prepp'ing Query: ".$query."<br>###ABORT ABORT###<br>";
	}
}
?>
