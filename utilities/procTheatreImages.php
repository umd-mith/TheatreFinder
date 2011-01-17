<?php
	// Resizes a set of images to fit entry views...
	// This script, like the procThumbnails,
	// resizes an image to fit best within the web size,
	// as well as converting it to *.png
	// First does some processing on the file-naming convention that Frank uses.
	// Tries to accommodate for human (typo) errors in these file-names
	
    require("./dbFunctions.php");
	require("./readdirUtils.php");
	
	echo "<?xml version='1.0' encoding='UTF-8'?>";
	
//	$sourcePath = "/Bee_School/CAMP/ESAlmagroImages/";
//	$destPath = "/Bee_School/CAMP/ESAlmagroResized/";
	$sourcePath = "/Bee_School/CAMP/GR_Thorikos/";
	$destPath = "/Bee_School/CAMP/GR_Thorikos/GR_Thorikos_resized/";
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
	/* TF FILENAME -- path the files will be stored on server - stored in DB */
	$TF_FilePath = "images/130px";
	
//	$rmPuncList = array(',', ';', '.','-'); 

// db stuff
/*$cnxn = new mysqli("localhost","root","root","theatrefinder");
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

*/
// BEGIN FILE PROC
	//$files = getFilesFmDir($sourcePath, $ext);
	$files = getFilesFmDir($sourcePath);
	
	foreach ($files as $file) {
		
		if (is_file($sourcePath.$file)) { // don't want directories; 
										 // don't want recursively going into subdirs
			echo $file.": ";
			$numFiles++;
			
			// get the extension and file info from pathinfo function,
			// because they're not always *.jpgs as Frank originally thought
			$info = pathinfo($file); 
			$baseName =  basename($file,'.'.$info['extension']);
			echo "Original Basename: ".$baseName."<br>";
/*			
 * The below foreach only needed when Frank provides a file with spaces and
 * the naming convention "CountryCode City type other_info.<ext>" 
 
			$fileBits = split($spaceRegex, $baseName);
			$endBit = "";
			$endBitSpace = "";
			
			//foreach ($fileBits as $bit) {
			for ($i=0;$i<count($fileBits);$i++) {
				//$bit = str_replace($rmPuncList, '', $bit);
				
				switch($i) {
					case 0:
						$country = $fileBits[$i];
					break;
					
					case 1:
						$city = $fileBits[$i];
					break;
					
					case 2:
						$type = $fileBits[$i];
					break;
					
					default:
						// glom 'rest' of bits in one endBit
						$endBit .= $fileBits[$i]."_";
						$endBitSpace .= $fileBits[$i]." ";
					break;
				}			
			} 
			
			$endBit = trim($endBit);
			$newFilename = ($endBit==='') ? $country."_".$city."_".$type : $country."_".$city."_".$type."_".$endBit;
			$newFilename = preg_replace('/[_]+$/','', $newFilename);
			echo "<b>$newFilename</b><br>";
*/			
			$newFilename=$baseName;
			echo "NEW file name: ".$newFilename."<br>";
			resize($sourcePath.$file, $destPath, $newFilename.".png");
			}
	}
	
	function resize($fn, $resizePath, $newFn) {
	
		echo "Let's do it<br>";
		$img = new Imagick($fn);
		$img->setFormat('PNG');
		$img->setImageOpacity(1.0);
		//main_images size
		$img->resizeImage(660,455,Imagick::FILTER_LANCZOS,1,$bestfit=true);
		$img->writeImage($resizePath.$newFn);
	
		echo "Just did it<br>";

		echo "<?xml version='1.0' encoding='UTF-8'?>";

		echo "<h3>ORIGINAL</h3>";
		echo "<img src=\"file:/".$fn."\" alt=\"thumb\" /></a>";

		echo "<h3>THUMB</h3>";
		echo "<img src=\"file:/".$resizePath.$newFn."\" alt=\"thumb\" /></a>";
	}
?>
