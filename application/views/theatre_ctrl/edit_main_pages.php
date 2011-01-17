<!-- Featured theatres -->
<div class="grid_12 featuredarea">
    <div class="grid_12 alpha omega theatreName">
		<h1 class="admin_header"><?php echo $heading;?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
		<div class="adminOpts">
			<h4><a href="<?php echo base_url();?>theatre_ctrl/admin_dashboard">Manage Accounts</a></h4>
				<ul>
					<li>Pending Accounts</li>
					<li>Existing Accounts</li>
					<li>Change My Password</li>
				
				</ul>
			<h4 class="active"><a href="<?php echo base_url();?>theatre_ctrl/edit_main_pages">Edit Site Content</a></h4>
				<ul>
					<li>Update Featured Theatres</li>
				</ul
		</div>
    </div>
    <div class="grid_8 omega maincontent suffix_1">
        <!-- Content area -->
		<p class="warning">This section is not yet operational.</p>
		<?php echo $message;?>
    </div>
</div>
<!-- end featuredarea -->