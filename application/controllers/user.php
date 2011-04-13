<?php

class User extends TheatreFinder_Controller {

	function __construct() {
		parent::__construct();
		
		$this->load->helper(array('url','form'));
		$this->load->library('form_validation');
		
		// load 'Theatres' table model
		$this->load->model('Theatre_model');
		
		$this->load->helper('ckeditor');
		$this->add_css('all_css');
		$this->add_scripts('all_scripts');
	
	}

	/* ***********************************************************
	 * Name:		_is_logged_in
	 * Input:	
	 * Output:	
	 * Dependency:  session library and indirectly, the login controller	
	 * Description:	Checks to see if the session data is logged in
	 * 				The template used is set based on whether the
	 * 				user is logged in or not
	 * 				
	 * *********************************************************** */
	function _is_logged_in() {
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true) {
			$this->data['template'] = 'visitors_only_layout';

		} else {
			$this->data['template'] = 'main_layout';
			$this->data['username'] = $this->session->userdata('username');
			$this->data['view_controller'] = 'theatres';
			$this->data['access_level'] = $this->session->userdata('user_access_level');
			
			// check if the user is an administrator and set the admin_link appropriately
			if ($this->data['access_level'] == 'administrator') {
				$this->data['admin_link'] = anchor('theatres/admin_dashboard', "Admin Options");
			}
		}
	}

	function index() {
		// check to see if user's already logged in
		$is_logged_in = $this->session->userdata('is_logged_in');
		
		if (!isset($is_logged_in) || $is_logged_in != true) {
			// User has not yet logged in, so show the normal login page/form
			
			$this->data['title'] = 'Theatre Finder | Login';
			$this->data['body_id'] = '<body id="login">';
		
			$login_input = array(
              'name' 	   => 'username',
              'id'         => 'username',
			  'class'	   => 'login_text',
              'value'      => '',
              'maxlength'  => '32',
               );
			$this->data['username_login'] = form_input($login_input);
		
			$login_password = array(
              'name' 	   => 'password',
              'id'         => 'password',
			  'class'	   => 'login_text',
              'value'      => '',
               );
			$this->data['login_password'] = form_password($login_password);
		
			$login_submit = array(
              'name' 	   => 'submit',
              'id'         => 'submit',
              'value'      => 'Login',
             );
			// If the user logs in and hits the submit button,
			// the validate_user_credentials() function will be called
			$this->data['login_submit'] = form_submit($login_submit);
		
			// login will only ever have the visitors_only_layout
			$this->render('visitors_only_layout');	
			
		} else {  
		
			// Inform the user to log out before they login again or as someone else
			// Keep the layout as 'main' layout versus visitors layout
			redirect('user/already_logged_in');
			//$this->render('main_layout', 'already_logged_in');
		
		}
	}
	
	function validate_user_credentials() {
		$valid_user = $this->Theatre_model->validate_user($this->input->post('username'), $this->input->post('password'));
		
		if(isset($valid_user)) { // if the user's credentials validated...
			
			// If the user is still pending, direct them back to the view (and tell them why)
			if ($valid_user->user_access_level === 'pending') {
				echo "Your Request to Register is being processed. Please wait for your approval email, thanks!";
			
			} else { // they're valid/approved, but need to check if activated (via activation code)
				
				if ($valid_user->activated == 0) { // they have been approved, but *not* activated
					$this->data['error_message'] =
						"Your account has been approved but has not been activated."
						."<br>Please check your email and follow the instructions to activate your account."
						."<br>Thank you!";
						
					$this->data['title'] = 'Theatre Finder | ERROR with Login';
					$this->data['body_id'] = '<body id="login">';
					// build the form, and make $this->data array from the key=>val pairs
					$login_form_data = $this->_format_login_data($_POST);
					foreach ($login_form_data as $form_element=>$form_val) {
						$this->data[$form_element] = $form_val;
					}
					$this->render('visitors_only_layout');
						
				} else  { // they're valid AND activated, so turn over control to theatres
					
					$userdata = array(
						'username' => $this->input->post('username'),
						'user_access_level' => $valid_user->user_access_level,
						'is_logged_in' => true
					);
			
					$this->session->set_userdata($userdata);			
					redirect('index');
				}	
			}
			 
		} else { // !isset($valid_user) ==> incorrect username or password
			
			$this->data['title'] = 'Theatre Finder | ERROR with Login';
			$this->data['body_id'] = '<body id="login">';

			$this->data['error_message'] = "Please check your username and password again."
										   ."<br>They do not match any in our accounts' records."
										   ."<br> If you have not yet registered, please registor for a Theatre-finder account.";
			// build the form, and make $this->data array from the key=>val pairs
			$login_form_data = $this->_format_login_data($_POST);
			foreach ($login_form_data as $form_element=>$form_val) {
				$this->data[$form_element] = $form_val;
			}
			$this->render('visitors_only_layout'); 
		}
	}

	function signup() {
		$this->data['title'] = 'Theatre Finder | Request Account';
		$this->data['body_id'] = '<body id="signup">';
		
		$defaultform_instruction = $this->Theatre_model->get_default_form_entries('application_instructions');

		// all the form view data is in views/login/signup.php
		// except for the CKeditor rich-textarea
		$this->data['ckeditor_app_stmt'] = array(		
			//ID of the textarea that will be replaced
			'id' 	=> 	'app_stmt',
			'path'	=>	'javascript/ckeditor',
			'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',
			);
		$app_stmt = array(
			'name'		=> 'app_stmt',
			'id'		=> 'app_stmt',
			'value'		=> $defaultform_instruction['application_instructions'],
			);
		$this->data['app_stmt'] = form_textarea($app_stmt);
		
		$this->render('visitors_only_layout');

	}
	
	// If the username already exists, warn user that it is NOT unique
	// This is a call-back for the form_validation for signing up 
	// or registering new users
	function username_unique($username) {
		
		$this->form_validation->set_message('username_unique', 'That %s already exists.  Please try a different username and try again.');
		
		if ($this->Theatre_model->username_exists($username)) {
			return FALSE; // i.e., it exists, it's NOT unique, set form validation false
		} else {
			return TRUE;
		}
	}


	function create_member() {
		
		// trying out a better template than what i'm using in libraries/MY_Controller.php
		$this->load->library('template');
		
		if ($this->form_validation->run()) { // Registration form input is good 
										
			// generate a random activation code that will be sent later to the user
			$activation_code = $this->_gen_rand();
			
			// Now set up the time info for this new user's registration:
			$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
			$mysql_datetime = date("Y-m-d H:i:s", $timestamp_in_secs);
			
			$affiliation = isset($_POST['affiliation']) ? $_POST['affiliation'] : '';
			
			$vita_statement = isset($_POST['app_stmt']) ? $_POST['app_stmt'] : '';

			$new_account_insert_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_address' => $this->input->post('email_address'),			
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'affiliation' => $affiliation,
				'activation_code' => $activation_code,
				'request_date' => 	$mysql_datetime,
				'vita_statement' => $vita_statement,			
				);
			if($query = $this->Theatre_model->create_pending_account($new_account_insert_data)) {
				// redirect them to the successful signup area
				redirect('user/signup_success');
				
			} else { // some error in input to database
				$affiliation = isset($_POST['affiliation']) ? $_POST['affiliation'] : '';
				$vita_statement = isset($_POST['app_stmt']) ? $_POST['app_stmt'] : '';

				$malformed_input = array(
					'first_name_input' => (isset($_POST['first_name']) ? $_POST['first_name'] : ''),
					'last_name_input' => (isset($_POST['last_name']) ? $_POST['last_name'] : ''),
					'email_input' => (isset($_POST['email_address']) ? $_POST['email_address'] : ''),			
					'username_input' => (isset($_POST['username']) ? $_POST['username'] : ''),
					'password_input' => (isset($_POST['password']) ? $_POST['password'] : ''),
					'password_confirm' => (isset($_POST['password']) ? $_POST['passconf'] : ''),
					'affiliation_input' => (isset($_POST['affiliation']) ? $_POST['affiliation'] : ''),
					'vita_statement' => $vita_statement,
				);
				// set up the application format
				$defaultform_instruction = $this->Theatre_model->get_default_form_entries('application_instructions');
				// all the form view data variables are in views/login/signup.php
				// except for the CKeditor rich-textarea
				$this->data['ckeditor_app_stmt'] = array(		
					//ID of the textarea that will be replaced (by CKeditor block)
					'id' 	=> 	'app_stmt',
					'path'	=>	'javascript/ckeditor',
					'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',
				);
				$app_stmt = array(
					'name'		=> 'app_stmt',
					'id'		=> 'app_stmt',
					'value'		=> $defaultform_instruction['application_instructions'],
				);
				$this->data['app_stmt'] = form_textarea($app_stmt);
			
				// Slightly different template format than the $this->render() from MY_Controller
				// Using to ensure that the CI Form Validation Errors are working properly
				$this->template->load('visitors_only_layout','user/signup', $this->data);
			}
			
			
		} else {			
			// error in form validation (config/form_validation) 
			// Need to redirect to the sign up again 
			$this->data['title'] = 'Theatre Finder | ERRORS: Request Account';
			$this->data['body_id'] = '<body id="signup">';
			
			$malformed_input = array(
				'first_name_input' => (isset($_POST['first_name']) ? $_POST['first_name'] : ''),
				'last_name_input' => (isset($_POST['last_name']) ? $_POST['last_name'] : ''),
				'email_input' => (isset($_POST['email_address']) ? $_POST['email_address'] : ''),			
				'username_input' => (isset($_POST['username']) ? $_POST['username'] : ''),
				'password_input' => (isset($_POST['password']) ? $_POST['password'] : ''),
				'password_confirm' => (isset($_POST['password']) ? $_POST['passconf'] : ''),
				'affiliation_input' => (isset($_POST['affiliation']) ? $_POST['affiliation'] : ''),
				'vita_statement' => (isset($_POST['app_stmt']) ? $_POST['app_stmt'] : '')
			);
			foreach($malformed_input as $index=>$val) {
				$this->data[$index] = $val;
			}
			// set up the application format
			$defaultform_instruction = $this->Theatre_model->get_default_form_entries('application_instructions');
			// all the form view data variables are in views/login/signup.php
			// except for the CKeditor rich-textarea
			$this->data['ckeditor_app_stmt'] = array(		
				//ID of the textarea that will be replaced (by CKeditor block))
				'id' 	=> 	'app_stmt',
				'path'	=>	'javascript/ckeditor',
				'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',
				);
			$app_stmt = array(
				'name'		=> 'app_stmt',
				'id'		=> 'app_stmt',
				'value'		=> $defaultform_instruction['application_instructions'],
				);
			$this->data['app_stmt'] = form_textarea($app_stmt);
			
			// Slightly different template format than the $this->render() from MY_Controller
			// Using to ensure that the CI Form Validation Errors are working properly
			// This may work better for the entire app - will look at changing (emb/07/05/2010)
			$this->template->load('layouts/visitors_only_layout', 'user/signup', $this->data);
			
		}
	}
	







	function signup_success() {
		
		$this->data['title'] = 'Theatre Finder | Successfully Registered!';
		$this->data['body_id'] = '<body id="home">';
		

		$this->render('visitors_only_layout');
	}
	
	function already_logged_in() {
		$this->data['title'] = 'Theatre Finder | Already Logged in';
		$this->data['body_id'] = '<body id="login">';
		
		// Since we know the user stuff is set (logged in), get it
		$this->data['username'] = $this->session->userdata('username');
		$this->data['access_level'] = $this->session->userdata('user_access_level');
			
		// check if the user is an administrator and set the admin_link appropriately
		if ($this->data['access_level'] == 'administrator') {
			$this->data['admin_link'] = anchor('theatres/admin_dashboard', "Admin Options");
		}
		
		$this->data['user_message'] = "You're already logged in...Please logout first.";
		
		$this->render('main_layout');
		
	}
	function logout() {
		$this->session->sess_destroy();
		$this->data['body_id'] = '<body id="login">';
		
		redirect('index');
	}
	
	/* **************************************************************************
	 * Name: 		confirm_registration()
	 * Input:		
	 * Output:		
	 * 			
	 * Description: This is the page that is shown to an approved user
	 * 				who has received their activation message, and 
	 * 				clicked the activation link (created by their randomly generated
	 * 				activation code) 
	 ************************************************************************** */
	function confirm_registration() {
		// the uri segment after this method contains the randomly gen'd activation code 
		// for the user who is confirming their registration from the approval/reg email
		$activation_code = $this->uri->segment(3);
		
		if ($activation_code == '') {
			// problem with the uri sent...
			echo "ERROR: Sorry, there is no activation code listed.";
			exit();
		} else {
			$registration_confirmed = $this->Theatre_model->activate_user($activation_code);
			
			if ($registration_confirmed) {
				echo "<br><br>Thank you! Your Theatre-Finder account has been registered."
					."<br>Please follow this link to login now.<br>"
					.anchor('login', 'Go to Login');
			} else {
				echo "We're sorry; there were problems with your activation code."
					."<br>No record found with that activation code."
					."<br>Please notify the Theatre-Finder editorial team at: "
					."theatre-finder@umd.edu";
			}
		}
		
		
	}

	function  _gen_rand() {
    	$length = 24;
    	$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    	$string = ''; 
			
		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, (strlen($characters)-1))];
   		}

		return $string;
	}
	
	function _format_login_data($input) {
			
		$login_form_data = array();
			
		$login_input = array(
        	'name' 	   => 'username',
      		'id'         => 'username',
		  	'class'	   => 'login_text',
       		'value'      => isset($input['username']) ? $input['username'] : 'Username',
        	'maxlength'  => '32',
        );
		$login_form_data['username_login'] = form_input($login_input);
		
		$login_password = array(
        	'name' 	   => 'password',
        	'id'         => 'password',
			'class'	   => 'login_text',
        	'value'      => 'Password', // just set the password to default, 
           							  // regardless of input
        );
		$login_form_data['login_password'] = form_password($login_password);
		
		$login_submit = array(
       		'name' 	   => 'submit',
        	'id'         => 'submit',
        	'value'      => 'Login',
        );
		
		$login_form_data['login_submit'] = form_submit($login_submit);
		
		// return the form of info
		return $login_form_data;	
	}
	
}
?>