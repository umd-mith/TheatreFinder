<div class="grid_12 breadcrumbs">
	<p>
		<a href="<?php echo  base_url();?>theatre_ctrl#<?php echo $theatre['id'];?>">&lt;&lt; &nbsp;back to list</a>
	</p>
</div>
<!-- Featured theatres -->
<div class="grid_12">   

<?php echo validation_errors(); ?>
<?php echo form_open('theatre_ctrl/delete_theatre/'); ?>
<?php echo form_hidden('idData',$this->uri->segment(3));?>
<div><span style="margin:5px"><input type="submit" value="Confirm Delete" /></span>
</div>
<h5><span style="margin:5px">Theatre Name: <font color="#0000ff">
	<?php echo $theatre['theatre_name'];?>
	</font></span></h5>

<span style="margin:5px">Country: 
	<font color="#0000ff"><?php echo $theatre['country_name'];?></font>
	</span>

<span style="margin:5px">City: 
	<font color="#0000ff"><?php echo $theatre['city'];?></font>
	</span>

<span style="margin:5px">Earliest Date: 
	<font color="#0000ff"><?php echo $theatre['est_earliest'];?>
	<?php echo $theatre['earliestdate_bce_ce'];?></font>
	</span>

<span style="margin:5px">Latest Date:
	<font color="#0000ff"><?php echo $theatre['est_latest'];?>
	<?php echo $theatre['earliestdate_bce_ce'];?></font>
	</span>
</form>
</div>
