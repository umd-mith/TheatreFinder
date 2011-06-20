<div style="margin-left: 20%; margin-right: 20%;">
<?php echo form_open_multipart('upload/do_upload/' . $id . '/' . $type);?>

<p>
	Please select an image to upload showing the <?php echo $type ?> for &ldquo;<?php echo $theatre['theatre_name'] ?>.&rdquo;  Images need to be less than 100k in size and be formatted as .gif, .jpg, or .png.  They also need to be no wider than 1024 pixels and no higher than 768 pixels.
</p>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="Upload Image" />

</form>
<p>
	<a href="<?php echo base_url().'theatres/entry_visitor_info/'.$theatre['id'].'_top' ?>">Return to
		&ldquo;<?php echo $theatre['theatre_name'] ?>&rdquo;</a>
</p>
</div>