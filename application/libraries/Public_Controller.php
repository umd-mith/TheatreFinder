<?php
    class Public_Controller extends TheatreFnder_Controller {
    	
    	function __construct() {
			parent::__construct();
        	if($this->config->item('site_open') === FALSE) {
	            show_error('Sorry the site is shut for now.');
        	}

	        // If the user is using a mobile, use a mobile theme
			// This from Phil Sturgeon templates - comment out for now (4/26/2010)
    	    /* $this->load->library('user_agent');
        	if( $this->agent->is_mobile() ) {
            
            / *
             * Use my template library to set a theme for your staff
             *     http://philsturgeon.co.uk/code/codeigniter-template
             * /
            	$this->template->set_theme('mobile');
        	}
			
			*/
    	}
}
?>
