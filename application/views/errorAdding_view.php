<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $title?></title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1,h3 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 10px 0 2px 0;
 padding: 5px 0 6px 0;
}

span {
	margin: 0 10px 0 10px;
}
code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

</style>
</head>
<body>
<!--<h3><?php echo $heading?><span><input type="submit" value="Add Theatre" /></span></h3> -->
<h3><font color="#ff0000"><?php echo $errorMsg;?></h3>
<h3>Use the "Back" button in your browser to go back. Problems with your entry are listed below</font></h3>
<?php echo validation_errors(); ?>
<h5><span>Theatre Name <?php echo $nameInput;?>
</span>
</h5>
<h5><span>Country <?php echo $countryInput;?></span>
</h5>
<h5><span>City <?php echo $cityInput;?></span></h5>
<h5><span><span>Earliest Date? <?php echo $est_earliest;?></span> 
CE <?php echo $earliest_ce;?> BCE <?php echo $earliest_bce;?></span></h5>

<h5><span>
	<span> Latest Date? <?php echo $est_latest;?> 
	</span>
	CE <?php echo $latest_ce;?> BCE <?php echo $latest_bce;?>
	</span>
</h5>

</form>

</body>
</html>
