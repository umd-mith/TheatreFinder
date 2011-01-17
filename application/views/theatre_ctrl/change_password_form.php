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
					<li><a href="#">Change My Password</a></li>
				</ul>
			<h4><a href="<?php echo base_url();?>theatre_ctrl/edit_main_pages">Edit Site Content</a></h4>
		</div>
	</div>
<!-- Admin Info area -->
	<div class="grid_9 omega maincontent">            
		<h3><a name="overview">Change Password</a></h3>
		<form class="acct_form" id='acct_form-<?php echo $user['id'];?>' 
		 method='post' action='<?php echo base_url();?>theatre_ctrl/change_admin_password'>
		 <?php echo form_hidden('user_id',$user['id']);?>
		<div class="clear"></div> 
		<div class="grid_9 headerRow alpha omega">
			<div class="grid_9 alpha omega">
			<p><?php echo $user['first_name']." ".$user['last_name']." (".$username.")";?></p>
			</div>
		</div>
		<div class="grid_9 alpha omega">
		<?php echo validation_errors('<div class="warning">'); ?>
		<div class="grid_5 alpha">
		<!-- password input -->
		<?php $password = array(
        	'name' 	   => 'password',
        	'id'       => 'password',
			'class'	   => 'password_input',
 			'value'    => 'NEW Password',
			//'placeholder' => 'New Password', // someday - an HTML5 input attrib to replace label or default values
            'maxlength'  => '32',
           );
			echo form_input($password);
		?>
		<!-- password confirmation input -->
		<?php $passconf = array(
			'name' 	   => 'passconf',
			'id'       => 'passconf',
			'class'	   => 'password_input',
			'value'    => 'Confirm New Password',
			//'placeholder' => 'Confirm New Password', // someday - an HTML5 input attrib to replace label or default values
			'maxlength'  => '32',
 		 	);
			echo form_input($passconf);
		?>
		</div>
		<div class="grid_4 omega">
		<input type="submit" value="Change Password" id="changepasswd_btn" name='<?php echo "changebtn-".$user['id'];?>' /></p>
		
		</div>
		</div>
		</form>
		<div class="clear"></div>
		<div class="grid_9 headerRow alpha omega"></div>
</div>
</div>