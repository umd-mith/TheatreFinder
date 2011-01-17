<!--<?php header('Content-type: text/html; charset=UTF-8'); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Iñtërnâtiônàlizætiøn Page! Theatre Finder | List of Theatres</title>
<link rel="stylesheet" href="<?= base_url();?>css/reset.css" />
<link rel="stylesheet" href="<?= base_url();?>css/text.css" />
<link rel="stylesheet" href="<?= base_url();?>css/960.css" />
<link rel="stylesheet" href="<?= base_url();?>css/theatrefinder.css" />
<link rel="stylesheet" href="<?= base_url();?>javascript/jquery.rating.css" />
<link type="text/css" href="<?= base_url();?>css/tabs.css" rel="stylesheet" />
<!--[if IE 6]>
<link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection">
<![endif]--> 

<script src="<?= base_url();?>javascript/cufon.js" type="text/javascript"></script>
<script src="<?= base_url();?>javascript/steinem_400-steinem_700-steinem_italic_700-steinem_italic_700.font.js" type="text/javascript"></script>
<script type="text/javascript">
	Cufon.replace('h1');
	//Cufon.replace('h2');
	//Cufon.replace('h3');
</script>
<!-- start jquery scripts -->
<script type="text/javascript" src="<?= base_url();?>javascript/jquery-1.3.2.min.js"></script>
<!-- jquery script for hover on thumbnail images-->
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.easing.1.3.js"></script>
<script type="text/javascript">
			$(document).ready(function(){
			$('.boxgrid.captionfull').hover(function(){
					$(".cover", this).stop().animate({top:'0px'},{queue:false,duration:500});
				}, function() {
					$(".cover", this).stop().animate({top:'65px'},{queue:false,duration:500});
				});
			});
</script>
<!-- jquery script for star rating -->
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.rating.pack.js"></script>
</head>

<body id="list">
<!-- TEST Charsests -->
	   <div class="grid_12 clearfix alpha omega">
	   	<?php foreach($dbCharset as $charset):?>
		<?php foreach($charset as $key=>$value):?>
		<p><?=$key.": ".$value;?></p>
	   </div>
	   <?php endforeach;?>
	   <?php endforeach;?>
	  <div class="clear"></div>

<script type="text/javascript"> Cufon.now(); </script>

</body>
</html>
