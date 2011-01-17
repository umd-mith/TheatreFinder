<?php

	// originally procImages.php (3/3/2010) -- renamed to procThumbnails.php (may2010)
	// Processes the image files that Frank wanted in the list view 
	// to be the 130px thumbnail size
	// First does some processing on the file-naming convention that Frank uses.
	// Tries to accommodate for errors
	
    require("./dbFunctions.php");
	require("./readdirUtils.php");
	
	echo "<?xml version='1.0' encoding='UTF-8'?>";
	
	$sourcePath = "/Bee_School/CAMP/ImagesTheatreFinder_master/";
	$destPath = "/Bee_School/CAMP/ImagesTheatreFinder_resized/";
	$files = array();
	$numFiles = 0;
	
	//$ext = "jpg";
	$baseName = "";
	$spaceRegex = "[[:space:]]+";
	$punctStart = "/^,|^-/";
	$punctEnd = "/,$|-$/";
	$country=$city=$endBit=0;
	$imgList = array();
	$filesNotIns = array();
	/* **** TF FILENAME -- on the server, where these will be stored UNDER the TheatreFinder dir **** */
	$TF_FilePath = "images/130px";
	
//	$rmPuncList = array(',', ';', '.','-'); 

// db stuff
$cnxn = new mysqli("localhost","root","root","theatrefinder");
if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
} 

$selectT = "SELECT id, theatre_name, country_name, city "
		."FROM theatres WHERE country_name=? and city like CONCAT('%', ?, '%')"
		." AND (theatre_name like CONCAT('%', ?, '%'));";
		
$select0 = "SELECT id, theatre_name, country_name, city "
		."FROM theatres WHERE country_name=? and city like CONCAT('%', ?, '%');";
	
$selectTStmt =& genericSQLPrep($cnxn, $selectT);
$select0Stmt =& genericSQLPrep($cnxn, $select0);

$insert = "INSERT thumbnails SET t_id=?, image_file=?, file_path=?;";

$insertStmt =& genericSQLPrep($cnxn, $insert);


// BEGIN FILE PROC
	//$files = getFilesFmDir($sourcePath, $ext);
	$files = getFilesFmDir($sourcePath);
	
	foreach ($files as $file) {
		
		if (is_file($sourcePath.$file)) { // don't want directories; 
										 // don't want recursively going into subdirs
			//echo $file.": ";
			$numFiles++;
			
			//resize($sourcePath.$file, $destPath);
			$info = pathinfo($file);
			$baseName =  basename($file,'.'.$info['extension']);
			//echo $baseName."<br>";
			
			$fileBits = split($spaceRegex, $baseName);
			$endBit = "";
			$endBitSpace = "";
			
			//foreach ($fileBits as $bit) {
			for ($i=0;$i<count($fileBits);$i++) {
				//$bit = str_replace($rmPuncList, '', $bit);
				
				$fileBits[$i] = preg_replace($punctStart, "", $fileBits[$i]);
				$fileBits[$i] = preg_replace($punctEnd, "", $fileBits[$i]);
				
				switch($i) {
					case 0:
						$country = $fileBits[$i];
					break;
					
					case 1:
						$city = $fileBits[$i];
					break;
					
					default:
						// glom 'rest' of bits in one endBit
						$endBit .= $fileBits[$i]."_";
						$endBitSpace .= $fileBits[$i]." ";
					break;
				}			
			} 
			echo "Country: <b>".$country."</b> City: <b>".$city."</b> <b>Other:</b> [".$endBitSpace."]: ".strlen($endBitSpace)."</b><br>";
			$country = (preg_match('/^USA$/', $country)) ? "United States" : $country;
			$country = (preg_match('/^UK$/', $country)) ? "United Kingdom" : $country;
			$newFilename = $country."_".$city."_".$endBit;
			$newFilename = preg_replace('/_$/','', $newFilename);
			echo "<b>$newFilename</b><br>";
			$endBitSpace = trim($endBitSpace);
			//$endBit = (strlen($endBit)==0) ? 0 : $endBit;
			if (strlen($endBitSpace)>0) {
				echo "TRYING TO MATCH ON [".$endBitSpace."]<br>";
				$selectTStmt->bind_param	('sss', $country, $city, $endBitSpace);
				$selectTStmt->execute();
				$selectTStmt->bind_result($id, $theatre, $cntry, $cityname);
				$selectTStmt->store_result();
				echo "For File: ".$newFilename.":<br>";
				echo "<p>We have matches on:<br><ul>";
				$num=0;
				while ($selectTStmt->fetch()) {
					$num++;
					echo "<li>[".$id."]".$theatre.": ".$cntry." -- ".$cityname."</li>";
				}
			} else { // endBit is empty
				$select0Stmt->bind_param	('ss', $country, $city);
				$select0Stmt->execute();
				$select0Stmt->bind_result($id, $theatre, $cntry, $cityname);
				$select0Stmt->store_result();
				echo "For File: ".$newFilename.":<br>";
				echo "<p>We have matches on:<br><ul>";
				$num=0;
				while ($select0Stmt->fetch()) {
					$num++;
					echo "<li>[".$id."]".$theatre.": ".$cntry." -- ".$cityname."</li>";
				}
			}
			echo "</ul></p>";
			$newFilename = $newFilename.".png";
			if ($num==1) {
				echo "INSERT HERE!<BR>";
				//$insertStmt->bind_param('iss', $id, $newFilename, $TF_FilePath)
					//or die("PROBLEM BINDING!<BR>");
				//$insertStmt->execute()
				//or die ("Could not execute theatres INSERT for file:".$newFilename."<br>");
			} else {
				$filesNotIns[] = $newFilename;
				echo "GOT ".$num." NEED to resolve<br>";
			}
			// resize (SCALE, that is)
			//resize($sourcePath.$file, $destPath, $newFilename);
		} else {
			echo "DIR: ".$file."<br>";
		}
	}
	$select0Stmt->close();
	$selectTStmt->close();
	echo "<h3>Num files: ".$numFiles."</h3>";
	foreach ($filesNotIns as $index=>$f) {
		echo "[".$index."] ".$f."<br>";
	}
	
	
	function resize($fn, $resizePath, $newFn) {
	
		echo "Let's do it<br>";
		$img = new Imagick($fn);
		$img->setFormat('PNG');
		$img->setImageOpacity(1.0);
		//$img->resizeImage(130,0,Imagick::FILTER_LANCZOS,1);
		// scale to width 130, rest to fit aspect ratio
		//$img->scaleImage(130,0);
		$img->cropThumbnailImage(130,92);
		$img->writeImage($resizePath.$newFn);
	
		echo "Just did it<br>";

		echo "<?xml version='1.0' encoding='UTF-8'?>";

		echo "<h3>ORIGINAL</h3>";
		echo "<img src=\"file:/".$fn."\" alt=\"thumb\" /></a>";

		echo "<h3>THUMB</h3>";
		echo "<img src=\"file:/".$resizePath.$newFn."\" alt=\"thumb\" /></a>";
	}
?>
