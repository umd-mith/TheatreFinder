<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$title?></title>

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
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
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

<?=validation_errors(); ?>

<?=form_open('theatreCtrl/deleteTheatre/'); ?>
<?=form_hidden('idData',$this->uri->segment(3));?>
<div><span style="margin:5px"><input type="submit" value="Confirm Delete" /></span></div>
<h5><span style="margin:5px">Theatre Name: <font color="#0000ff">
	<?=$theatre->theatre_name;?>
	</font></span></h5>

<h5><span style="margin:5px">Country: 
	<font color="#0000ff"><?=$theatre->country_name;?></font>
	</span></h5>

<h5><span style="margin:5px">City: 
	<font color="#0000ff"><?=$theatre->city;?></font>
	</span></h5>

<h5><span style="margin:5px">Earliest Date: 
	<font color="#0000ff"><?=$theatre->est_earliest;?>
	<?=$theatre->earliestdate_bce_ce;?></font>
	</span></h5>

<h5><span style="margin:5px">Latest Date:
	<font color="#0000ff"><?=$theatre->est_latest;?>
	<?=$theatre->earliestdate_bce_ce;?></font>
	</span>
</form>

</body>
</html>
