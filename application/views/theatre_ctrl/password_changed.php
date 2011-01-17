<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName">
		<h1 class="admin_header"><?php echo $heading;?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
		<div class="adminOpts">            
			<h4 class="active"><a href="<?php echo base_url();?>theatre_ctrl/admin_dashboard">Manage Accounts</a></h4>
				<ul>
					<li><a href="<?php echo base_url();?>theatre_ctrl/admin_dashboard">Pending Accounts (total: <?php echo $pend_count;?>)</a></li>
					<li><a href="<?php echo base_url();?>theatre_ctrl/admin_acct_info">Existing Accounts</a>
						<ul>
							<li><a href="<?php echo base_url();?>theatre_ctrl/admin_update_accts/author">Authors</a></li>
							<li><a href="<?php echo base_url();?>theatre_ctrl/admin_update_accts/editor">Editors</a></li>
							<li><a href="<?php echo base_url();?>theatre_ctrl/admin_update_accts/administrator">Administrators</a></li>
						</ul>
					</li>
					<li><a href="<?php echo base_url();?>theatre_ctrl/change_password_form">Change My Password</a></li>
				</ul>
			<h4><a href="<?php echo base_url();?>theatre_ctrl/edit_main_pages">Edit Site Content</a></h4>
		</div>
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