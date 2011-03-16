<div class="grid_12 featuredarea">
<!-- form open -->
<?php	echo form_open('user/create_member'); ?>
	<div class="grid_3 alpha sidebar">
		<div id="login_form">
			<h1 class="signup_header">Request an Account</h1>
		</div>
	</div>
	<div class="grid_9 omega maincontent">                     
		
			<!-- echo the form validation errors set up by config/form_validation library -->
		<?php echo validation_errors('<div class="warning">'); ?>	
			<div class="grid_4 suffix_1 alpha">		
			<fieldset class="signup">
				<legend>Personal Information</legend>
				<!-- first name input -->
				<?php $first_name = array(
              		'name' 	   => 'first_name',
              		'id'         => 'first_name',
			  		'class'	   => 'login_text',
              		'value'      => 'First Name',
              		'maxlength'  => '32',
            		);
		 			echo form_input($first_name); 
		 		?>
				<!-- last name input -->
				<?php $last_name = array(
              		'name' 	   => 'last_name',
              		'id'         => 'last_name',
			  		'class'	   => 'login_text',
              		'value'      => 'Last Name',
              		'maxlength'  => '32',
            		);
					echo form_input($last_name);
				?>
				<!-- email address -->
				<?php $email_address = array(
              		'name' 	   => 'email_address',
              		'id'         => 'email_address',
			  		'class'	   => 'login_text',
              		'value'      => 'Email Address',
              		'maxlength'  => '32',
		            );
					echo form_input($email_address);
				?>
				<!-- affiliation input -->
				<?php $affiliation = array(
              		'name' 	   => 'affiliation',
              		'id'         => 'affiliation',
			  		'class'	   => 'login_text',
              		'value'      => 'Affiliation (university, theatre, etc)',
              		'maxlength'  => '32',
            		);
					echo form_input($affiliation);
				?>
			</fieldset>	
		</div>
		<div class="grid_4 omega">
			<fieldset class="signup">
				<legend>Login Info</legend>
				<!-- user selected username -->
				<?php $username = array(
        	      'name' 	   => 'username',
            	  'id'         => 'username',
			  	  'class'	   => 'login_text',
              	  'value'      => 'Username',
              	  'maxlength'  => '32',
            		);
					echo form_input($username);
				?>
				<!-- password input -->
				<?php $password = array(
              		'name' 	   => 'password',
              		'id'         => 'password',
			  		'class'	   => 'login_text',
              		'value'      => 'Password',
              		'maxlength'  => '32',
            		);
					echo form_input($password);
				?>
				<!-- password confirmation input -->
				<?php $passconf = array(
              		'name' 	   => 'passconf',
              		'id'         => 'passconf',
			  		'class'	   => 'login_text',
              		'value'      => 'Password Confirm',
              		'maxlength'  => '32',
 		           );
				   echo form_input($passconf);
				?>
			</fieldset>	
		</div>
		

	<div class="grid_9 alpha omega">
		<h3>Application Statement and Qualifications</h3>
		<div id="noStyle"><?php echo $app_stmt;?> 
		<?php echo display_ckeditor($ckeditor_app_stmt);?>
		</div>
	</div>
	<div class="grid_9 alpha omega">
	<!-- submit button -->
	<p>
		<?php $request_submit = array(
          'name' 	   => 'request',
          'id'         => 'request',
          'value'      => 'Send Request',
          );
		  echo form_submit($request_submit);
		?>
	</p>
	</div>
		
	</form>
	
	</div>
</div>
