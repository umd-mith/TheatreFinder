<?php

class Upload extends TheatreFinder_Controller {
	
	function Upload()
	{
		parent::Controller();
		$this->_is_logged_in(array(
			'*' => array(
				'requires_auth' => true
			)
		));
		$this->load->helper(array('form', 'url'));
	}
	
	function index()
	{	
		$this->load->view('upload_form', array('error' => ' ' ));
	}

	function do_upload()
	{
		$config['upload_path'] = '/Users/davelester/Sites/CodeIgniter/upload/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			
			$this->load->view('upload_form', $error);
		}	
		else
		{
			$data = array('upload_data' => $this->upload->data());
			
			$this->load->view('upload_success', $data);
		}
	}	
}