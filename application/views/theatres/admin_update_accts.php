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
					<li><a href="<?php echo base_url();?>theatres/change_password_form">Change My Password</a></li>
				</ul>
			<h4><a href="<?php echo base_url();?>theatres/edit_main_pages">Edit Site Content</a></h4>
		</div>
	</div>
<!-- Admin Info area -->
	<div class="grid_9 omega maincontent">            
		<h3><a name="overview">Review/Modify Access Levels for Existing Accounts</a></h3>  
		<div class="clear"></div> 
		<h3><?php echo $overview_heading;?>s </h3> 
		<p>(Total: <span id="access_count"><?php echo $count;?></span> as of <span id="new_time"><?php echo $now;?></span>)</p>
		<?php foreach ($accounts as $account):?>
		<form class="acct_form" id='acct_form-<?php echo $account['id'];?>'>
			<input type="hidden" name="id" value='<?php echo $account['id'];?>' />
			<input type="hidden" name="current_access" value='<?php echo $this->uri->segment(3);?>' />
			<div class="grid_9 headerRow alpha omega">
				<a name='<?php echo "row-".$account['id'];?>'>
				<h4 id='<?php echo "headerRow-".$account['id'];?>'><?php echo $account['first_name']." ".$account['last_name'];?></h4>
			</div></a>
			<div id='<?php echo "wrapper_div-".$account['id'];?>'>
			<div class="grid_4 alpha" id='<?php echo "name_info-".$account['id'];?>'>
				<p><strong>Name:</strong> <?php echo $account['first_name']." ".$account['last_name'];?></p>
				<p><strong>Email:</strong> <?php echo $account['email_address'];?></p>                        
				<p><strong>Affiliation:</strong> <?php echo $account['affiliation'];?></p>
				<p><strong>Username:</strong> <?php echo $account['username'];?></p>
				<p><strong>Date:</strong> <?php echo $account['request_date']?></p>
				<p><strong>Last Reviewed:</strong> <?php echo $account['last_reviewed_date']?></p>
			</div>     
	        <div class="grid_5 omega" id='<?php echo "app_stmt-".$account[$i]['id'];?>'>
	        	<h4><a name="basic_desc">Applicant Statement/CV</a></h4>
				<div id="noStyle"><?php echo $account['vita_statement'];?></div>
			</div>                  
			<div class="grid_9 editRow alpha omega" id='<?php echo "editRow-".$account['id'];?>'>
				<p>Delete?<a class="tipsy" original-title="Deletions cannot be undone (after update)" href='#<?php echo "row-".$account['id'];?>'><?php echo $account['delete_option'];?></a> 
					<!--<strong><font color="#94bec4">||</font></strong>-->
				    or Modify Existing Level: <?php echo $account['select_menu'];?>
				<input type="button" value="Update" class="updatebutton" id='<?php echo $account['id'];?>' name='<?php echo $account['id'];?>' /></p>
			</div>
			</form>
			</div>
			<div class="grid_9 undoRow alpha omega" id='<?php echo "undoRow-".$account['id'];?>'>
			<p id='<?php echo "message-".$account['id'];?>'></p>
			   <input type="button" value="Undo" class="undobutton" id='<?php echo "undo-".$account['id'];?>' name='<?php echo $account['id'];?>' />
		 </div>
		<?php endforeach;?>	
	</div>
</div>