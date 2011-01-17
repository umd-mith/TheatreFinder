<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<script type="text/javascript" src="<?= base_url();?>/javascript/ckeditor/ckeditor.js"></script>
</head>
<body>
	<textarea name="content" id="content" ><p>Example data</p></textarea>
	<?php echo display_ckeditor($ckeditor); ?>
</body>
</html>