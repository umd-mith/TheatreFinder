<html>
<head>
<title>Upload Form</title>
</head>
<body>

<?php echo $error;?>

<pre>[<?php echo $id;		
?>]</pre>

<?php echo form_open_multipart('upload/do_upload/' . $id . '/' . $type);?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>

</body>
</html>