<?php

class Upload extends TheatreFinder_Controller {
	
	function __construct() {
		parent::__construct();
		
		// load 'Theatres' table model
		$this->load->model('Theatre_model');
		
		// check to see if a user is logged in
		$this->_is_logged_in(array(
			'*' => array(
				'requires_auth' => TRUE
			)
		));
		
		//$this->load->helper(array('url','form'));
		$this->add_css('all_css');
		$this->add_scripts('all_scripts');
	
	}
	
	function index()
	{	
		//$this->load->view('upload_form', array('error' => ' ' ));
		$this -> render();
	}

	function do_upload()
	{
		$config['upload_path'] = '/Users/jgsmith/Sites/TheatreFinder/upload/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{			
			$this->data['error'] = $this->upload->display_errors();
			$this->render(FALSE, 'upload_form');
		}	
		else
		{
			$this->data['upload_data'] = $this->upload->data();
			
			$this->render(FALSE, 'upload_success');
		}
	}	
}