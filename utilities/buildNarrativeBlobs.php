<?php
	require("./dbFunctions.php");

	$theatre_id=0;
	$text_cs=$text_ci=$narrative_cs = '';
	$id=0;
	
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
	$select = "SELECT id, narrative FROM theatres;";
	$insert = "insert INTO detailed_narratives SET theatre_id=?, text_narrative_cs=?, text_narrative_ci=?;";

	$selectStmt =& genericSQLPrep($cnxn, $select);
	$insertStmt =& genericSQLPrep($cnxn, $insert);

	$selectStmt->execute();
	$selectStmt->bind_result($theatre_id, $text_cs);
	$selectStmt->store_result();
	
	while ($selectStmt->fetch()) {
		
		echo ("[".$theatre_id."] ".$text_cs."<br>");
		
		// if/when you bind blob - must do it with a 'b'
		$insertStmt->bind_param('iss', $theatre_id, $text_cs, $text_cs) or die("PROBLEM BINDING!<BR>");
		$insertStmt->execute()
			or die ("Could not execute theatres UPDATE of [".$theatre_id."] and:[".$text_cs."]<br>");;

	}
	
	$selectStmt->free_result();
	$selectStmt->close();

	$insertStmt->free_result();
	$insertStmt->close();
	
	$cnxn->close();

	
?>
