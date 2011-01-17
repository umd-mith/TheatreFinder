<?php
    class Admin_Controller extends MY_Controller {
    	function __construct() {
	        parent::__construct();
        
        	if($this->data['user']['group'] !== 'admin') {
	            show_error('You have insufficient privileges: this area is for admins only.');
        	}
    }
}
?>
