<?php

class Index extends TheatreFinder_Controller {

	function __construct() {
		parent::__construct();
		
		// load 'Theatres' table model
		$this->load->model('Theatre_model');
		
		// check to see if a user is logged in
		$this->_is_logged_in(array(
			'*' => array(
				'authenticated' => array(
					'template' => 'main_layout',
					'view_controller' => 'theatre_ctrl'
				),
				'anonymous' => array(
					'template' => 'visitors_only_layout',
					'view_controller' => 'theatre_ctrl'
				)
			)
		));
		
		//$this->load->helper(array('url','form'));
		$this->add_css('all_css');
		$this->add_scripts('all_scripts');
	
	}

	/* ***********************************************************
	 * Name:		index
	 * Input:		none	
	 * Output:		none
	 * 	
	 * Description:	main home page for the TheatreFinder site.
	 * 				Displays the 3 featured theatres, 9 most recently
	 * 				updated theatres and introductory text.
	 * 				
	 * *********************************************************** */
	function index() {
		$this->data['title'] = 'Theatre Finder | Home';
		$this->data['body_id'] = '<body id="home">';
		
		// ** Add the mainHome.js script for this form
		$this->add_scripts('link_mainHomejs');
		
		// Build featured theatres details (featured_theatres table)
		for($featured_index=1; $featured_index<4; $featured_index++) {
			
			$ft_index = 'featured_'.$featured_index;
			$this->data[$ft_index] = $this->Theatre_model->get_featured_theatre($featured_index);
			
			$this->data[$ft_index]['date_range'] = 
			$this->_format_date($this->data[$ft_index]['est_earliest'], 
								$this->data[$ft_index]['est_latest'], 
								$this->data[$ft_index]['earliestdate_bce_ce'], 
								$this->data[$ft_index]['latestdate_bce_ce']);
			$this->data[$ft_index]['featured_img'] = $this->data[$ft_index]['image_filepath'].$this->data[$ft_index]['image_filename'];
			
		}
		
		
		$recent_theatres = $this->Theatre_model->get_recentupdates();
		for ($i=0; $i<count($recent_theatres); $i++) { 
			unset($thumbnailFile);
			unset($thumbnailData);
			
			$recent_theatres[$i]['country_name'] = stripslashes($recent_theatres[$i]['country_name']);
			$recent_theatres[$i]['city'] = stripslashes($recent_theatres[$i]['city']);
			$recent_theatres[$i]['date_range'] = $this->_format_date($recent_theatres[$i]['est_earliest'], $recent_theatres[$i]['est_latest'], $recent_theatres[$i]['earliestdate_bce_ce'], $recent_theatres[$i]['latestdate_bce_ce']);
			
			$thumbnailData = $this->Theatre_model->getThumbNail($recent_theatres[$i]['id']);
			if (isset($thumbnailData)) {
				$thumbnailFile = $thumbnailData->file_path."/".$thumbnailData->image_file;
			}
			// If there isn't a thumbnail available for this theatre, use the 'imageNeeded placeholder
			$thumbnailFile = (isset($thumbnailFile)) ? $thumbnailFile : "images/130px/imageNeededThumbnail.gif";
			$recent_theatres[$i]['thumbnail'] = $thumbnailFile;
		}
		$this->data['recent_theatres'] = $recent_theatres;
		
		$this->render($this->data['template']);
	}

	function about() {
		$this->data['title'] = 'Theatre Finder | About';
		$this->data['body_id'] = '<body id="about">';
		$this->render($this->data['template']);
	}
	
	function search() {
		$this->data['title'] = 'Theatre Finder | Search';
		$this->data['body_id'] = '<body id="search">';
		$this->render($this->data['template']);
	}

	function join() {
		$this->add_scripts('period_slider_script');
		$this->data['title'] = 'Theatre Finder | Join';
		$this->data['body_id'] = '<body id="join">';
		$this->render($this->data['template']);
	}
	
	function resources() {
		$this->data['title'] = 'Theatre Finder | Resources';
		$this->data['body_id'] = '<body id="resources">';
		$this->render($this->data['template']);
	}
		
	function fhildy() {
		$this->data['title'] = 'Theatre Finder | Professor Franklin Hildy';
		$this->data['body_id'] = '<body id="admin">';
		$this->render($this->data['template']);
		
	}
	
	function contribute() {
		$this->add_scripts('period_slider_script');
		$this->data['title'] = 'Theatre Finder | Contribute';
		$this->data['body_id'] = '<body id="contribute">';
		$this->render($this->data['template']);
	}

	function featured_image() {
		// Get the id for the featured theatre div clicked
		$new_active = $_POST['new_active'];
			
		switch ($new_active) {
			case "featured_one":
				$t_id=8; // hard-coded for now, will add "Featured Theatre" table/plus UI
			break;
				
			case "featured_two":
				$t_id=345;
			break;
				
			case "featured_three":
				$t_id=98;
			break;
				
			default:
				$t_id=0;
			break;
		}
		
		$filename = $this->Theatre_model->get_featured_image($t_id);
		
		if ($filename!='imageNeededLarge.gif') {
			$filename = 'images/theatres/stage/'.$filename;
			if ($filename == 'imageNeededLarge.gif') {
				$filename = 'images/theatre/auditorium/'.$filename;
			}
		} else {
			$filename = 'images/theatres/'.$filename;
		}
		echo json_encode($filename);
	}

	function _format_date($est_earliest, $est_latest, $earliest_bce_ce, $latest_bce_ce) {
		$date = 'Not Yet Resolved';
			
		if ($est_earliest==$est_latest) {
			if ($earliest_bce_ce===$latest_bce_ce) { // same date & bce_ce type
				if ($est_earliest!=0) { // if the dates are equal and !=0
					if ($earliest_bce_ce==='CE') {
						$date = $est_earliest;
					} else {
						$date = $est_earliest." ".$earliest_bce_ce;
					}
				}
			} else { // dates are equal, but across bce->ce
				$date = $est_earliest." ".$earliest_bce_ce." - ".$est_latest." ".$latest_bce_ce;
			}
		} else { // they're not equal dates - give the range
			if (($earliest_bce_ce===$latest_bce_ce) && ($earliest_bce_ce==='CE')) {
				$date = $est_earliest." - ".$est_latest;
			} else {
				$date = $est_earliest." - ".$est_latest." ".$latest_bce_ce;
			}
		}
		
		return $date;
	}
	
		function _index_original() {
		$this->data['title'] = 'Theatre Finder | Home';
		$this->data['body_id'] = '<body id="home">';
		
		// ** Add the mainHome.js script for this form
		$this->add_scripts('link_mainHomejs');
		//Regex for long parenthetical names
		$parenRegex = '/\(|\)/';
		
		// Romanesque Theatre, Hellbrun
		$id1 = 8; // hard-coded for now, will add "Featured Theatre" table/plus UI 
		// CHANGE to new German theatre: id=61
		$id2 = 345;
		$id3 = 98;
		$this->data['featured_one']  = $this->Theatre_model->get_theatre($id1);
		if (preg_match($parenRegex,$this->data['featured_one']['theatre_name'])) {
			$long_name = preg_split($parenRegex, $this->data['featured_one']['theatre_name']);
			$this->data['featured_one']['theatre_name'] = $long_name[0];
				
		}
		$this->data['featured_one']['date_range'] = $this->_format_date($this->data['featured_one']['est_earliest'], $this->data['featured_one']['est_latest'], $this->data['featured_one']['earliestdate_bce_ce'], $this->data['featured_one']['latestdate_bce_ce']);
			
		$t_imgs = $this->Theatre_model->get_main_images($this->data['featured_one']['id']);
		//id, stage, exterior, auditorium, plan, section, top_file_path
		foreach ($t_imgs as $sub_path=>$filename) {
			if ($filename!='imageNeededLarge.gif') {
				$image_key = $sub_path.'_image';
				$this->data['featured_one'][$image_key] = $t_imgs['top_file_path'].$sub_path."/".$filename;
			} else {
				$image_key = $sub_path.'_image';
				$this->data['featured_one'][$image_key] = $t_imgs['top_file_path']."/".$filename;
			}
		}
		
		$this->data['featured_two'] = $this->Theatre_model->get_theatre($id2);
		$this->data['featured_two']['date_range'] = $this->_format_date($this->data['featured_two']['est_earliest'], $this->data['featured_two']['est_latest'], $this->data['featured_two']['earliestdate_bce_ce'], $this->data['featured_two']['latestdate_bce_ce']);
		
		$t_imgs = $this->Theatre_model->get_main_images($this->data['featured_two']['id']);
		//id, stage, exterior, auditorium, plan, section, top_file_path
		foreach ($t_imgs as $sub_path=>$filename) {
			if ($filename!='imageNeededLarge.gif') {
				$image_key = $sub_path.'_image';
				$this->data['featured_two'][$image_key] = $t_imgs['top_file_path'].$sub_path."/".$filename;
			} else {
				$image_key = $sub_path.'_image';
				$this->data['featured_two'][$image_key] = $t_imgs['top_file_path']."/".$filename;
			}
		}
		
		$this->data['featured_three'] = $this->Theatre_model->get_theatre($id3);
			
		$recent_theatres = $this->Theatre_model->get_recentupdates();
		for ($i=0; $i<count($recent_theatres); $i++) { 
			unset($thumbnailFile);
			unset($thumbnailData);
			
			$recent_theatres[$i]['country_name'] = stripslashes($recent_theatres[$i]['country_name']);
			$recent_theatres[$i]['city'] = stripslashes($recent_theatres[$i]['city']);
			$recent_theatres[$i]['date_range'] = $this->_format_date($recent_theatres[$i]['est_earliest'], $recent_theatres[$i]['est_latest'], $recent_theatres[$i]['earliestdate_bce_ce'], $recent_theatres[$i]['latestdate_bce_ce']);
			
			$thumbnailData = $this->Theatre_model->getThumbNail($recent_theatres[$i]['id']);
			if (isset($thumbnailData)) {
				$thumbnailFile = $thumbnailData->file_path."/".$thumbnailData->image_file;
			}
			$thumbnailFile = (isset($thumbnailFile)) ? $thumbnailFile : "images/130px/imageNeededThumbnail.gif";
			$recent_theatres[$i]['thumbnail'] = $thumbnailFile;
		}
		$this->data['recent_theatres'] = $recent_theatres;
		
		$this->render($this->data['template']);
	}
}
?>