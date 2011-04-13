<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName">
		<h1 class="admin_header"><?php echo $heading;?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
	</div>
<!-- Admin Info area -->
	<div class="grid_9 omega maincontent">            
		<h3><a name="overview">Password Changed Successfully</a></h3>
		<div class="clear"></div> 
		<div class="grid_9 headerRow alpha omega">
			<p><?php echo $user['first_name']." ".$user['last_name']." (".$username.")";?></p>
		</div>
		<div class="grid_9 alpha omega">
			<p><?php echo $message;?></p>
		</div>	
	</div>
</div>