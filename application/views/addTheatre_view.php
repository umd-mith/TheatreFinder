<?php header('Content-type: text/html; charset=UTF-8');?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
<title><?=$title?></title>
<link rel="stylesheet" href="<?= base_url();?>css/reset.css" />
<link rel="stylesheet" href="<?= base_url();?>css/text.css" />
<link rel="stylesheet" href="<?= base_url();?>css/960.css" />
<link rel="stylesheet" href="<?= base_url();?>css/theatrefinder.css" />
<script src="<?= base_url();?>javascript/cufon.js" type="text/javascript"></script>
<script src="<?= base_url();?>javascript/steinem_400-steinem_700-steinem_italic_700-steinem_italic_700.font.js" type="text/javascript"></script>
<script type="text/javascript">
	Cufon.replace('h1');
	//Cufon.replace('h2');
	//Cufon.replace('h3');
</script>
<!-- Autocomplete css selector file -->
<link rel="stylesheet" type="text/css" href="<?= base_url();?>css/jquery.autocomplete.css" />

<script type="text/javascript" src="<?= base_url();?>javascript/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?= base_url();?>javascript/jquery.url.packed.js"></script>
<script type="text/javascript" src="<?= base_url();?>javascript/aliasBox.js"></script>
<script type="text/javascript" src="<?= base_url();?>javascript/mainAdd.js"></script>
<script type="text/javascript" src="<?= base_url();?>/javascript/ckeditor/ckeditor.js"></script>

</head>

<body>
<?=$formOpen;?>
<?=validation_errors(); ?>
<div class="container_12 clearfix">
	<br>
	<div class="grid_4 alpha"><h3><?=$heading?></h3></div>
	<div class="grid_8 omega">
		<p><input type="submit" value="Add Theatre" /></p>
	</div>
	<hr>
	<div class="grid_6 suffix_6 alpha">
	<strong>Theatre Name</strong>
	<?=$nameInput;?>
	</div>
<div class="clear"></div>
<br>
	<div class="grid_4 alpha">
	<strong>Country</strong>
	<?=$countryInput;?>
	</div>
	<div class="grid_8 omega">
	<strong>Region</strong>
	<?=$regionInput;?>
	</div>
<div class="clear"></div>
<br>
	<div class="grid_6 suffix_6 alpha">
	<strong>City</strong>
	<?=$cityInput;?>
	</div>
<div class="clear"></div>
<br>
<div class="grid_6 suffix_6 alpha omega">
<!-- check box for city aliases -->
	<?=$cAliasChkBox;?>
	<label for="cAliasCB"><strong>Add City Alias</strong></label>
	</input>
</div>
<div class="clear"></div>
<div id="cityAliasDiv_1" style="margin-bottom:4px" class="cityAliases grid_4 suffix_8 omega">
		<label for="cityAliasName_1">Alias 1:</label> 
		<input type="text" name="cAliases[]" id="cityAliasName_1" size="16" maxlength="64"/>
	<img id="add_btn" class="icon" alt="Add button" title="add an alias" src="<?= base_url();?>/images/icon_add.png">
		<img id="del_btn" class="icon" alt="Delete button" title="Remove this alias" src="<?= base_url();?>/images/icon_delete.png">
	</div>
	<div class="clear" id="last"></div>
<hr>
<div class="grid_3 alpha">
	<strong>Period: </strong> 
	<?=$periodMenu;?>
</div>
<div class="grid_9 omega" id="type_wrapper">
	<strong>Type: </strong>
	<?=$sub_type;?>
</div>
<div class="clear"></div>
<br>
<div class="grid_4 alpha">
	<strong>Range (start)</strong> 
	<?=$est_earliest;?>
	CE <?=$earliest_ce;?> BCE <?=$earliest_bce;?>
</div>
<div class="grid_6 suffix_6 alpha">
	<strong>Range (end)</strong> 
	<?=$est_latest;?>
	CE <?=$latest_ce;?> BCE <?=$latest_bce;?>
</div>
<br>
<div class="grid_6 suffix_6 alpha">
	<strong>Website</strong> 
	<?=$websiteInput;?>
</div>
<div class="clear"></div>
<br>
<div class="grid_3 suffix_9 alpha"><h3>Notes</h3></div>
<div class="grid_10 suffix_2 alpha"><?=$notes;?>
<?=display_ckeditor($ckeditor_notes); ?>
</div>
<div class="clear"></div>
<br>
<div class="grid_3 suffix_9 alpha"><h3>Brief Description</h3></div>
<div class="grid_10 suffix_2 alpha"><?=$brief_desc;?>
<?=display_ckeditor($ckeditor_brief); ?>
</div>
<div class="clear"></div>
<br>
<div class="grid_3 suffix_9 alpha"><h3>Scholarly Description</h3></div>
<div class="grid_10 suffix_2 alpha"><?=$scholar;?>
<?=display_ckeditor($ckeditor_scholar); ?>
</div>
</div>
</form>

</body>
</html>