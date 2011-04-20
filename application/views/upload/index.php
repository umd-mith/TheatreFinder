<pre>[<?php print_r( $id );		
?>]</pre>

<?php echo form_open_multipart('upload/do_upload/' . $id . '/' . $type);?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="upload" />

</form>
