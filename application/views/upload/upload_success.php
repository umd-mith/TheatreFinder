<div style="margin-left: 20%; margin-right: 20%;">


<h3>Your file was successfully uploaded!</h3>

<ul>
<?php foreach ($upload_data as $item => $value):?>
<li><?php echo $item;?>: <?php echo $value;?></li>
<?php endforeach; ?>
</ul>

<p>
	<a href="<?php echo base_url().'theatres/entry_visitor_info/'.$theatre['id'].'_top' ?>">Return to
		&ldquo;<?php echo $theatre['theatre_name'] ?>&rdquo;</a>
</p>
</div>