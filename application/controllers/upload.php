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
		$this->data['title'] = 'Theatre Finder | Upload Theatre Image';
		$this->data['id'] = $this -> uri -> segment(3);
		$this->data['type'] = $this->uri -> segment(4);
		$this->data['theatre'] = $this->Theatre_model->get_theatre($this->uri->segment(3));
		$this -> render();
	}

	function do_upload()
	{
		$CI =& get_instance();
		$site_root = $CI->config->slash_item('site_root');
		
		$t_id = $this -> uri -> segment(3);
		$this->data['id'] = $t_id;
		$i_type = $this->uri->segment(4);
		$this->data['type'] = $i_type;
		$this->data['theatre'] = $this->Theatre_model->get_theatre($t_id);
		
				
		$config['upload_path'] = $site_root."/images/theatres/$i_type/";
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{			
			$this->data['error'] = $this->upload->display_errors() . " (". $config['upload_path'] . ")";
			$this->render(FALSE, 'upload_form');
		}	
		else
		{
			$this->data['upload_data'] = $this->upload->data();
			# tie uploaded image to the id/type
			$this->Theatre_model->update_main_image($t_id, $i_type, $this->upload->file_name);
			if($this -> upload->image_width > $this->upload->image_height) {
				# do imagemagick command the constrains height to 92 and then crops
			}
			else {
				# do imagemagick command that constrains width to 130 and then crops
			}
			$this->render(FALSE, 'upload_success');
		}
	}	
}