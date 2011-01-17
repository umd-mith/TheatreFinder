<?php
// Get Frank's *.csv into an array.  
//$lines = file('/Bee_School/CAMP/FrankDataTEST');

$cnxn = new mysqli("localhost","root","root","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 



$insert = "INSERT INTO theatres SET theatre_name=?, country_digraph=?, country_name=?, est_earliest=?, earliestdate_bc_ad=?, est_latest=?, latestdate_bc_ad=?, narrative=?, narrative_cs=?;";
$select = "SELECT country_digraph, country_name from country_codes where country_name=?";

$insertStmt =& genericSQLPrep($cnxn, $insert);
$selectCountry =& genericSQLPrep($cnxn, $select);

$row = 1;
$handle = fopen("/Bee_School/CAMP/FrankData.csv", "r");
$datePattern = '/^Date/';
$locPattern = '/^Location/';
$namePattern = '/^Name/';
$narrativePat = '/^Summary/';
$webPat = '/^Website/';
$ADBCpattern = '/BC/';
$hyphenPattern = '/-/';
$letterPattern = '/[A-Za-z.\s]/';
$paren   = '(';
$earliestDate = 0;
$latestDate = 0;
$earlyADBC=0;
$lateADBC=0;

while (($data = fgetcsv($handle, ",")) !== FALSE) {
    $num = count($data);
	if ($row == 1) {
		echo "ROW ".$row.": ";
		for ($i=0; $i<$num; $i++) {
			$dataKey[$i] = $data[$i];
			echo $i.":".$dataKey[$i].", "; 
		}
		echo "<br/>";
	} else {
		echo "ROW: ".$row.":<br/>";
		$narrative = "";
    	for ($c=0; $c < $num; $c++) {
        	echo $dataKey[$c].":[".$data[$c]."], ";
			
			if (preg_match($namePattern, $dataKey[$c])) {
				$theatre_name = $data[$c];
				echo "NAME: ".$theatre_name."<br>";
			}
			
			if ((preg_match($narrativePat, $dataKey[$c])) || (preg_match($webPat, $dataKey[$c]))) {
				$narrative = $narrative."  ".$data[$c];
				echo "NARRATIVE: ".$narrative."<BR>";
			}
			if (preg_match($datePattern,$dataKey[$c])) {
				$date = strtoupper($data[$c]);
				echo "<br>DATE IS: [".$date."]<br>";
				
				$hyphenPos = strpos($date,'-');
				if ($hyphenPos === false) 
				{
					echo "DATE IS SIMPLE: [".$date."]<br>";
					if (preg_match($ADBCpattern, $date)) {
						$adbc = 'BC';
						echo "DATE HAS BC!<BR>";
					} else {
							$adbc = 'AD';
					}
					
					$date = preg_replace($letterPattern, '', $date);
					$earliestDate = $latestDate = $date;
					$earlyADBC = $lateADBC = $adbc;
					echo "DATE'S STILL SIMPLE: [".$earliestDate."] ".$earlyADBC."-[".$latestDate."] ".$lateADBC."<br>";
					
				} else {
					$dateList = split('-',$date);
				
					echo "DATE HAS: ".count($dateList)." dates<br>";
					echo " and Values ".$dateList[0]."+".$dateList[1]."<br>";
					if ($dateList[0] === '') {
						$adbc = 'BC';
						$date = preg_replace($letterPattern, '', $dateList[1]);
						$earliestDate = $latestDate = $date;
						$earlyADBC = $lateADBC = $adbc;		
						echo "DATE'S SIMPLE NEG BC: [".$earliestDate."] ".$earlyADBC."-[".$latestDate."] ".$lateADBC."<br>";		
					} else {
						$date = preg_replace($letterPattern, '', $dateList[0]);
						$earliestDate = $date;
						if (preg_match($ADBCpattern, $dateList[0])) {
							$earlyADBC = 'BC';
							echo "LATE DATE HAS BC!<BR>";
						} else {
							$earlyADBC = 'AD';
						}
						$date = preg_replace($letterPattern, '', $dateList[1]);
						$latestDate = $date;
						if (preg_match($ADBCpattern, $dateList[1])) {
							$lateADBC = 'BC';
						} else {
							$lateADBC = 'AD';
						}
						echo "EARLY DATE: [".$earliestDate."] ".$earlyADBC."<BR>";
						echo "LATE DATE: [".$latestDate."] ".$lateADBC."<br>";
					}	
				}
				
			}
			if (preg_match($locPattern,$dataKey[$c])) {
				$pos = strpos($data[$c], $paren);
				if ($pos === false) {
    				echo "The string '$paren' was not found in the string '$data[$c]'";
					$cntry = $data[$c];
				} else {
    				echo "The string '$paren' was found in the string '$data[$c]'";
    				echo " and exists at position $pos";
					$cntry = substr($data[$c], 0, ($pos-1));
				}
				//$locArray = split('(', $data[$c]);
				//$cntry = $locArray[0];
				echo "<br>INDIVIDUAL CNTRY IS: [".$cntry."]<br>";
				
				
				$selectCountry->bind_param('s', $cntry);
    			$selectCountry->execute()
					or die ("Could not execute country_codes SELECT with country_name:".$cntry."<br>");	
				// bind any results to $tag_id var
				$selectCountry->bind_result($country_digraph, $country_name); 
				$selectCountry->store_result();
				if ($selectCountry->num_rows == 1) {
					$selectCountry->fetch();
					echo "COUNTRY DIG FOR ".$country_name." IS: ".$country_digraph."<BR>";
				} else {
					$country_name = 'NEED TO CHECK';
					$country_digraph = 'XX';
				}
				
			}
		}
		echo "<br />";
	}
	// INSERT STUFF HERE...
	// theatre_name=?, country_digraph=?, country_name=?, est_earliest=?, earliestdate_bc_ad=?, est_latest=?, latestdate_bc_ad=?, narrative=?, narrative_cs=?
	if ($row>1) {
		$insertStmt->bind_param('sssisisss', $theatre_name, $country_digraph, $country_name, $earliestDate, $earlyADBC, $latestDate, $lateADBC, $narrative, $narrative);
    	$insertStmt->execute()
			or die ("Could not execute country_codes INSERT!!<br>");
	}
	$row++;
}	
$selectCountry->close();
$insertStmt->close();
fclose($handle);


mysql_close($cnxn);


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
