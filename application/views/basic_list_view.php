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

.myMiniBar { 
	background-color: #D0D0D0; 
	clear: both; 
	padding: 0;
	height: 1px;
	line-height: 3px; 
	visibility: visible;
	}

table.sortable{border:0; padding:0; margin:0;}
table.sortable td{padding:4px; width:120px; border-bottom:solid 1px #DEDEDE;}
table.sortable th{padding:4px;}
table.sortable thead{background:#e3edef; color:#333333; text-align:left;}
table.sortable tfoot{font-weight:bold; }
table.sortable tfoot td{border:none;}

</style>
<script type="text/javascript" src="<?= base_url();?>javascript/sorttable.js">
</script>
</head>
<body>
<h3><?=$heading?></h3>

<div><span>
		<?php echo 'Total number of theatres :'.$numTheatres;?>
		&nbsp;&nbsp;&nbsp;
		<?=anchor('theatreCtrl/addTheatreForm/', 'Add a New Theatre');?>
	</span></div>
<div class="myMiniBar"></div>

<table border="1" cellpadding="0" cellspacing="0" class="sortable" style="overflow:auto;overflow-x:hidden;">
<tr> <th class="sorttable_nosort">&nbsp;</th>
	 <th> Theatre Name </th>
	 <th> Country </th>
	 <th> City </th>
	 <th> Earliest Date (BCE/CE)</th>
	 <th> Latest Renovation Date (BCE/CE)</th>
	 <th> Narrative Description</th>	 
</tr>
<?php foreach($theatres as $theatre):?>
<tr><td><a name="<?=$theatre['prev'];?>">
	<ul>
	<li><?=$theatre['Edit'];?></li><br>
	<li><?=anchor('theatreCtrl/addTheatreForm/', 'Add New');?></li><br>
	<li><?=$theatre['Delete'];?></li>
  </ul>
  </a>
  </td>
	<td><a name="<?=$theatre['id'];?>"><?=$theatre['theatre_name'];?></a></td>
	<td><?=$theatre['country_name'];?></td>
	<td><?=$theatre['city'];?></td>
	<td align="right"><?=$theatre['beginDate'];?></td>
	<td align="right"><?=$theatre['endDate'];?></td>
	<td><div style="height:100px;overflow-y:auto;overflow-x:hidden"><?=$theatre['narrative'];?></div></td>
</tr>
<?php endforeach;?>
</table>

</body>
</html>