<div class="grid_12 featuredarea">
	<div class="grid_3 alpha sidebar">
		<div id="login_form">
			<h1 class="login_header">TheatreFinder Login</h1>
		    <h3>Login to TheatreFinder</h3>
			<?php echo form_open('user/validate_user_credentials'); ?>
	
			<div class="login_details">
				<?php echo $username_login;?>
				<?php echo $login_password; ?>
				<?php echo $login_submit; ?>
				<a href="<?php echo base_url();?>user/signup" class="login_links">Request Account</a>
			<div class="clear"></div>
			</div>
			<?php echo validation_errors(); ?>
			<?php echo form_close();?>
		</div>
	</div><!-- end login_form-->

	<div class="grid_8 maincontent suffix_1 omega">
		<!-- Validation error_messages will be shown here -->		
		<?php echo validation_errors(); ?>
		<h3>Instructions for users</h3>
		<div class="grid_4 alpha">
		<p>Instructions for new users.</p>
		</div>

		<div class="grid_4 omega">
		<p>Instructions for registered users.</p>
		</div>
	</div>
</div>