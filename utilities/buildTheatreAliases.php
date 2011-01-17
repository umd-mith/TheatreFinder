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

$select = "SELECT id, theatre_name from theatres;";
$selectStmt =& genericSQLPrep($cnxn, $select);

$insertAlias = "INSERT INTO theatre_aliases SET theatre_id=?, theatre_alias=?;";
$insAliasStmt =& genericSQLPrep($cnxn, $insertAlias);

$update = "UPDATE theatres set has_alias=1 where id=?";
$updateStmt =& genericSQLPrep($cnxn, $update);

$selectStmt->execute();
$selectStmt->bind_result($id, $theatre_name);
$selectStmt->store_result();

// OLD $parenRegex = '/\(|\)|,/';
$parenRegex = '/\(|\)/';
$comma = '/,/';
$spacePat = '/[[:space:]]+/';
$original_theatre_names = array();
$theatre_aliases = array();

while ($selectStmt->fetch()) {
	$theatre_name = trim($theatre_name);
	if (preg_match($parenRegex, $theatre_name)) {
		$alias = "";
		echo "Theatre WITH ALIAS: [".$id."][".$theatre_name."]<BR>";
		$aliasList = preg_split($parenRegex, $theatre_name);
		
		$original_theatre_names[$id] = trim($aliasList[0]);
		for($i=1; $i<count($aliasList); $i++) {
			$aliasList[$i] = trim($aliasList[$i]);
			if (($aliasList[$i]==='') || is_null($aliasList[$i]) || preg_match('/\./', $aliasList[$i])) {
				//echo "POSS BLANK: [".$aliasList[$i]."]<br>";
			} else {
				
				if (preg_match($comma, $aliasList[$i])) {
					$aliasWithinList = preg_split($comma, $aliasList[$i]);
					foreach($aliasWithinList as $index=>$alias) {
						$alias = trim($alias);
						$alias = preg_replace('/\./', '', $alias);
						if (($alias==='') || is_null($alias)) {
							//blank
						} else {
							echo "[".$i."][".$index."]=>".$alias."<br>";
							$theatre_aliases[$alias] = $id;	
						}
					}
				} else {
					echo "ALIAS LIST[".$i."] ".$aliasList[$i]."<br>";
					if (preg_match('/^theatre$/', $aliasList[$i]) || preg_match('/^small$/', $aliasList[$i])
					    || preg_match('/^north$/', $aliasList[$i]) || preg_match('/^large$/', $aliasList[$i])
						|| preg_match('/^south$/', $aliasList[$i]) || preg_match('/^2nd$/',$aliasList[$i])
						|| preg_match('/^1$/', $aliasList[$i])) {
						// nada
					} else {
						$theatre_aliases[$aliasList[$i]] = $id;
					}
				}
			}
		}		
	} else {
		echo "Single Theatre name: [".$id."][".$theatre_name."]<br>";	
		$original_theatre_names[$theatre_name] = $id;
	}
echo "<br><hr>";
}

$selectStmt->free_result();
$selectStmt->close();

echo "<hr><hr><hr>";
// key is alias_name; value is theatre_id
// First insert into the theatre_aliases table
foreach ($theatre_aliases as $k=>$v) {
	echo "[".$k."][".$v."]<br>";
	echo "INSERTING ALIAS: ".$k." For theatre_id: ".$v."<br>";
	$insAliasStmt->bind_param('is', $v, $k);
	$insAliasStmt->execute()
				or die ("Could not execute city_alias INSERT for alias: ".$k."<br>");
}

$insAliasStmt->free_result();
$insAliasStmt->close();

echo "<hr><hr><hr>";
// NOW Update the theatres table to make the has_alias
foreach ($theatre_aliases as $k=>$v) {
	echo "UPDATING theatre id:[".$v."] to has_alias=1<br>";
	$updateStmt->bind_param('i', $v);
	$updateStmt->execute()
				or die ("Could not execute update for id: ".$v."<br>");
}

$updateStmt->free_result();
$updateStmt->close();
$cnxn->close();

?>
