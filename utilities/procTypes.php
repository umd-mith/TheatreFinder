<?php

    require("./dbFunctions.php");
	require("./readdirUtils.php");
	
	$noneArray = array("[--none--]");
	$baroque = array("Baroque Public", 
					"Baroque Private/Court", 
					"Baroque Garden/Sylvan/Ruin");
	$post1815 = array("Phase II: 1816-1865", 
					  "Phase III: 1865-1915", 
					  "Phase IV: 100 Years Old Post-1915");
	
	$renaissance = array("Renaissance Public",
						"Renaissance Private/Court",
						"Renaissance Garden");
	
	$roman = array("Roman theatre",
					"Roman roofed theatre (Theatrum Tectum)",
					"Gallo-Roman",
					"Graeco-Roman (Greco-Roman) Italic",
					"Roman amphtitheatre wt scaena");
	
	$hellenistic = array("Hellenistic theatre",
						"Hellenistic odeion");
	
	$greek = array("Greek theatre",
					"Greek odeion",
					"Greek Council House (Bouleuterion)",
					"Greek Assembly House (Ecclesiasterion)");
	
	$minoan = array("Minoan theatrical area");
	
	$chinese = array("Chinese temple theatre",
					"Chinese court theatre",
					"Chinese garden theatre");
	
	$indian = array("Indian temple theatre",
					"Indian sanctuary theatre");
	
	$japanese = array("Japanese Noh theatre",
					 "Japanese Kabuki theatre");
					  					  
	echo "<?xml version='1.0' encoding='UTF-8'?>";
	
	// db stuff
	$cnxn = new mysqli("localhost","root","root","theatrefinder");
	if (mysqli_connect_errno()) {
		die ("Can't connect to MySQL Server. Errorcode: ". mysqli_connect_error(). "<br>");
	}
	
	$select = "SELECT p_id, period_rep from period;";
	$selectStmt =& genericSQLPrep($cnxn, $select);
	
	$selectStmt->execute();
	$selectStmt->bind_result($p_id, $period_rep);
	$selectStmt->store_result();

	while ($selectStmt->fetch()) {
		echo $p_id.": [".$period_rep."]<br>";
		
		switch($period_rep) {
			case "Baroque":
				insertType($baroque, $p_id, $cnxn);			
			break;
			
			case "Post 1815":
				insertType($post1815, $p_id, $cnxn);
			break;
			
			case "Medieval":
				insertType($noneArray, $p_id, $cnxn);
			break;
			
			case "Classical Revival":
				insertType($noneArray, $p_id, $cnxn);
			break;
			
			case "Renaissance":
				insertType($renaissance, $p_id, $cnxn);
			break;
			
			case "Roman":
				insertType($roman, $p_id, $cnxn);
			break;
			
			case "Hellenistic":
				insertType($hellenistic, $p_id, $cnxn);
			break;
			
			case "Greek":
				insertType($greek, $p_id, $cnxn);
			break;
			
			case "Minoan":
				insertType($minoan, $p_id, $cnxn);
			break;
			
			case "Chinese":
				insertType($chinese, $p_id, $cnxn);
			break;
			
			case "Indian":
				insertType($indian, $p_id, $cnxn);
			break;
			
			case "Japanese":
				insertType($japanese, $p_id, $cnxn);
			break;
			
			case "Other":
				insertType($noneArray, $p_id, $cnxn);
			break;	
			
			default:
				echo "No Period List yet<br>";
			break;
			
		}
	}
	
	$selectStmt->close();
	$cnxn->close();
	
	function insertType($typeArray, $p_id, &$cnxn) {
		
		$insert = "INSERT t_types SET p_id=?, t_type=?;";
		$insertStmt =& genericSQLPrep($cnxn, $insert); 
		foreach ($typeArray as $type) {
			echo "Period Id:[".$p_id."] ".$type."<br>";
			$insertStmt->bind_param('is', $p_id, $type)
				or die("PROBLEM BINDING!<BR>");
			$insertStmt->execute()
				or die ("Could not execute theatres INSERT for type:".$type."<br>");
			
		}
		$insertStmt->close();
		
	}

?>
