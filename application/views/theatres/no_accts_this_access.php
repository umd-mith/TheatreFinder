<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName">
		<h1 class="admin_header"><?php echo $heading;?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
		<div class="adminOpts">            
			<h4 class="active"><a href="<?php echo base_url();?>theatres/admin_dashboard">Manage Accounts</a></h4>
				<ul>
					<li><a href="<?php echo base_url();?>theatres/admin_dashboard">Pending Accounts (total: <?php echo $pend_count;?>)</a></li>
					<li><a href="<?php echo base_url();?>theatres/admin_acct_info">Existing Accounts</a>
						<ul>
							<li><a href="<?php echo base_url();?>theatres/admin_update_accts/author">Authors</a></li>
							<li><a href="<?php echo base_url();?>theatres/admin_update_accts/editor">Editors</a></li>
							<li><a href="<?php echo base_url();?>theatres/admin_update_accts/administrator">Administrators</a></li>
						</ul>
					</li>
					<li><a href="<?php echo base_url();?>theatres/admin_review_denied_accts">Previously Denied Accounts</a></li>
				</ul>
			<h4><a href="<?php echo base_url();?>theatres/edit_main_pages">Edit Site Content</a></h4>
		</div>
	</div>
<!-- Admin Info area -->
	<div class="grid_9 omega maincontent">            
		<h3><a name="overview">Review/Modify Access Levels for Existing Accounts</a></h3>  
		<div class="clear"></div> 
		<h3><?php echo $overview_heading;?>s </h3> 
		<p>(Total: <?php echo $count;?> as of <?php echo $now;?>)</p>
		<p> There are no accounts with access level: <strong><em><?php echo $overview_heading;?></em></strong>  to review at this time.</p>
	</div>
</div>