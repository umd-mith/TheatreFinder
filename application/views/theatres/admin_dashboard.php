<!-- Featured theatres -->
<div class="grid_12 featuredarea">   
	<div class="grid_12 alpha omega theatreName">
		<h1 class="admin_header"><?php echo $heading;?></h1>
	</div>
	<div class="grid_3 alpha sidebar">
		<div class="adminOpts">            
			<h4 class="active"><a href="<?php echo base_url();?>theatres/admin_dashboard">Manage Accounts</a></h4>
				<ul>
					<li><a href="#">Pending Accounts (total: <span class="pend"><?php echo $pend_count;?></span>)</a></li>
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
		<h3><a name="overview">Review Accounts Awaiting Approval (Total: <span class="pend"><?php echo $pend_count;?></span>)</h3>  
		<div class="clear"></div>        
		<?php 
		if(isset($pending)):
		foreach($pending as $individual_request):?>
		<form class="acct_form" id='acct_form-<?php echo $individual_request['id'];?>'>
			<input type="hidden" name="id" value='<?php echo $individual_request['id'];?>' />
			<div class="grid_9 headerRow alpha omega" id='<?php echo "headerRow-".$individual_request['id'];?>'>
				<h4><?php echo $individual_request['first_name']." ".$individual_request['last_name'];?></h4>
			</div>
			<div id='<?php echo "wrapper_div-".$individual_request['id'];?>'>
			<div class="grid_4 alpha" id='<?php echo "name_info-".$individual_request['id'];?>'>
				<p><strong>Name:</strong> <?php echo $individual_request['first_name']." ".$individual_request['last_name'];?></p>
				<p><strong>Email:</strong> <?php echo $individual_request['email_address'];?></p>                        
				<p><strong>Affiliation:</strong> <?php echo $individual_request['affiliation'];?></p>
				<p><strong>Username:</strong> <?php echo $individual_request['username'];?></p>
				<p><strong>Date:</strong> <?php echo $individual_request['request_date']?></p>
			</div>     
	        <div class="grid_5 omega" id='<?php echo "app_stmt-".$individual_request['id'];?>'>
	        	<h4><a name="basic_desc">Applicant Statement/CV</a></h4>
				<div id="noStyle"><?php echo $individual_request['vita_statement'];?></div>
			</div>                  
			<div class="grid_9 editRow alpha omega" id='<?php echo "editRow-".$individual_request['id'];?>'>
				<p>Approve? <?php echo $individual_request['approve'];?> 
				   Deny? <?php echo $individual_request['deny'];?> <strong><font color="#94bec4">||</font></strong>
				   Level: <?php echo $individual_request['select_menu'];?>
				 <input type="button" value="Submit" class="acctbutton" id='<?php echo $individual_request['id'];?>' name='<?php echo $individual_request['id'];?>' /></p>
		   </div>
		   </form>
		   </div>
		   <div class="grid_9 undoRow alpha omega" id='<?php echo "undoRow-".$individual_request['id'];?>'>
			<p id='<?php echo "message-".$individual_request['id'];?>'>
			   <!--<input type="button" value="Undo" class="undobutton" id='<?php echo "undo-".$individual_request['id'];?>' name='<?php echo $individual_request['id'];?>' /> --> 
			</p>
		 </div>
					
		<?php endforeach;
			endif;
		?>
	</div>
</div>