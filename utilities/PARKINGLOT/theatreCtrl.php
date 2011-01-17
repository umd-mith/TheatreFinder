<?php

// *** TODO: Set up header/footer using Elisabeth's UI
	
//class TheatreCtrl extends Controller {
class TheatreCtrl extends Controller {
	
	/* ***********************************************************
	 * Name:		TheatreCtrl()
	 * Input:	
	 * Output:	
	 * Dependency:	Theatre_model + config & libraries
	 * Description:	Loads the form that is part of the addTheatre_view
	 * 				Note that the libraries initially loaded from the
	 * 				controller are now part of the autoload.php
	 * 				script for the TheatreFinder MVC
	 * *********************************************************** */
	function TheatreCtrl (){
		// load controller parent
		parent::Controller();

		// load 'Theatres' table model
		$this->load->model('Theatre_model');
		
		// load library functions
		// now loaded in autoload.php (under config/ dir) 02/04/2010
		// **TESTING** removed from autoload.php 02/17/2010, calling here
		$this->load->library('form_validation');
		$this->load->library('MY_Xmlrpc');
		$this->load->library('MY_Email');
		//$this->load->library('MyMultiByte');
		// specific tf functions
		//$this->load->library('MY_TF_functions');
			
		// load helper functions
		// **Could be loaded in autoload.php (under config/ dir) 02/04/2010
		// removed from autoload.php 02/17/2010, calling here
		$this->load->helper(array('url','form'));
		$this->load->helper('international');
		$this->load->helper('ckeditor');
		
		// ckeditor
		//Ckeditor's configuration
		$this->data['ckeditor_notes'] = array(		
			//ID of the textarea that will be replaced
			'id' 	=> 	'notes',
			'path'	=>	'javascript/ckeditor',

			'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',

			//Optionnal values
			/* 'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"550px",	//Setting a custom width
				'height' 	=> 	'150px',	//Setting a custom height
			),
			*/
			
			//Replacing styles from the "Styles tool"
			'styles' => array(
			
				//Creating a new style named "style 1"
				'style 1' => array (
					'name' 		=> 	'Blue Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Blue',
						'font-weight' 		=> 	'bold'
					)
				),
				
				//Creating a new style named "style 2"
				'style 2' => array (
					'name' 		=> 	'Red Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Red',
						'font-weight' 		=> 	'bold',
						'text-decoration'	=> 	'underline'
					)
				)				
			)
		);
		
		$this->data['ckeditor_brief'] = array(		
			//ID of the textarea that will be replaced
			'id' 	=> 	'brief_desc',
			'path'	=>	'javascript/ckeditor',

			'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',

			//Optionnal values
			/* 'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"550px",	//Setting a custom width
				'height' 	=> 	'150px',	//Setting a custom height
			),
			*/
			
			//Replacing styles from the "Styles tool"
			'styles' => array(
			
				//Creating a new style named "style 1"
				'style 1' => array (
					'name' 		=> 	'Blue Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Blue',
						'font-weight' 		=> 	'bold'
					)
				),
				
				//Creating a new style named "style 2"
				'style 2' => array (
					'name' 		=> 	'Red Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Red',
						'font-weight' 		=> 	'bold',
						'text-decoration'	=> 	'underline'
					)
				)				
			)
		);
		
		$this->data['ckeditor_scholar'] = array(		
			//ID of the textarea that will be replaced
			'id' 	=> 	'scholar',
			'path'	=>	'javascript/ckeditor',

			'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',

			//Optionnal values
			/* 'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"550px",	//Setting a custom width
				'height' 	=> 	'150px',	//Setting a custom height
			),
			*/
			
			//Replacing styles from the "Styles tool"
			'styles' => array(
			
				//Creating a new style named "style 1"
				'style 1' => array (
					'name' 		=> 	'Blue Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Blue',
						'font-weight' 		=> 	'bold'
					)
				),
				
				//Creating a new style named "style 2"
				'style 2' => array (
					'name' 		=> 	'Red Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 			=> 	'Red',
						'font-weight' 		=> 	'bold',
						'text-decoration'	=> 	'underline'
					)
				)				
			)
		);
			
	}
	
	function ck_test() {
		$this->load->view('ckeditor', $this->data);
	}
/*	
	function _remap($method) {
    	if ($method === 'main') {
    	    $this->main();
    	} else {
	        $this->index();
    	}
	}
*/	
	function main() {
		$this->load->view('main', $this->data);
		
	}
	/* ***********************************************************
	 * Name:		index() [Main driver for the CI app]
	 * Input:	
	 * Output:	
	 * Dependency:	
	 * Description:	Main driver for the CI app - this function is
	 * 				called when TheatreFinder/theatreCtrl controller
	 * 				is loaded
	 * 				  
	 * *********************************************************** */
	function index() {
		
		$prev=0; // init prev (to aid Frank in navigating through the list_view)
		
		// get the big main query
		$theatres = $this->Theatre_model->getTheatres();
		
		$lastRow = count($theatres)-1;
		// Iterate through the associative array to set up the list_view output
		for ($i=0; $i<count($theatres); $i++) {
			
			// First: need to unset the thumbnail vars to ensure we don't double-up
			// the image files...isn't there a better way to do this? **TODO: Find better way**
			unset($thumbnailFile);
			unset($thumbnailData);
			
			switch ($i) {
				case 0:
				$theatres[$i]['prev'] = "top";
				break;
				
				case $lastRow:
				$theatres[$i]['prev'] = "last";
				break;
				
				default:
				$theatres[$i]['prev'] = "row".($i-1);
				break;
			}
			$theatres[$i]['idData'] = $theatres[$i]['id']."_".$theatres[$i]['prev'];
			
			
			/*$theatres[$i]['theatre_name'] = 
				mb_detect_encoding((stripslashes($theatres[$i]['theatre_name'])))=="UTF-8" ?
				utf8_decode(stripslashes($theatres[$i]['theatre_name'])) : iconv("UTF-8", "ISO-8859-1", stripslashes($theatres[$i]['theatre_name']));
			*/
			$theatres[$i]['theatre_name'] = stripslashes($theatres[$i]['theatre_name']);
		
			/*$theatres[$i]['country_name'] = 
				mb_detect_encoding(stripslashes($theatres[$i]['country_name'])) ?
				utf8_decode(stripslashes($theatres[$i]['country_name'])) : iconv("UTF-8", "ISO-8859-1", stripslashes($theatres[$i]['theatre_name']));
			*/
			$theatres[$i]['country_name'] = stripslashes($theatres[$i]['country_name']);
			
			//$theatres[$i]['region'] = utf8_decode(stripslashes($theatres[$i]['region']));
			$theatres[$i]['region'] = stripslashes($theatres[$i]['region']);
			
			/*$theatres[$i]['city'] = $theatres[$i]['city'] = 
				mb_detect_encoding(stripslashes(($theatres[$i]['city']))) ? 
				utf8_decode(stripslashes($theatres[$i]['city'])) : iconv("UTF-8", "ISO-8859-1", stripslashes($theatres[$i]['city'])); 
			*/$theatres[$i]['city'] = stripslashes($theatres[$i]['city']);
				
			$theatres[$i]['period_rep'] = stripslashes($theatres[$i]['period_rep']);
			$theatres[$i]['period_rep'] = (strcmp('Not Yet Specified', $theatres[$i]['period_rep'])) ? 
											"(".$theatres[$i]['period_rep']." period)" :
											"(".$theatres[$i]['period_rep'].")";
			$theatres[$i]['sub_type'] = stripslashes($theatres[$i]['sub_type']);
			
			if (preg_match('/BCE/', $theatres[$i]['earliestdate_bce_ce'])) {
				$theatres[$i]['beginDate'] = $theatres[$i]['est_earliest'].' '.$theatres[$i]['earliestdate_bce_ce'];
			} else {
				$theatres[$i]['beginDate'] = $theatres[$i]['est_earliest'];
			}
			if (preg_match('/BCE/', $theatres[$i]['latestdate_bce_ce'])) {
				$theatres[$i]['endDate'] = $theatres[$i]['est_latest'].' '.$theatres[$i]['latestdate_bce_ce'];
			} else {
				$theatres[$i]['endDate'] = $theatres[$i]['est_latest'];
			}
			
			// Build a uri for edit/delete that is comprised of the idData= entry id."_"previousRowNum ('prev');
			$theatres[$i]['Details'] = anchor('theatreCtrl/entry_view/'.$theatres[$i]['idData'], 'View Details');
			$theatres[$i]['Edit'] = anchor('theatreCtrl/editTheatreForm/'.$theatres[$i]['idData'], 'Edit');
			$theatres[$i]['Delete'] = anchor('theatreCtrl/delete_theatre_form/'.$theatres[$i]['idData'], 'Delete');
			
			// get the image information for this theatre
			$thumbnailData = $this->Theatre_model->getThumbNail($theatres[$i]['id']);
			if (isset($thumbnailData)) {
				$thumbnailFile = $thumbnailData->file_path."/".$thumbnailData->image_file;
			}
			$thumbnailFile = (isset($thumbnailFile)) ? $thumbnailFile : "images/130px/imageNeededThumbnail.gif";
			$theatres[$i]['thumbnail'] = $thumbnailFile;

		}
		
		$this->data['theatres'] = $theatres;
		$this->data['numTheatres']=$this->Theatre_model->getTotalTheatres();
		
		// load the data into the view
		//$this->load->view('basic_list_view', $this->data);
		$this->load->view('list_view', $this->data);
	}
	
	/* ***********************************************************
	 * Name:		addTheatreForm()
	 * Input:	
	 * Output:	
	 * Dependency:	Accessible from list_view (via the "Add New" link)
	 * Description:	Loads the form that is part of the addTheatre_view
	 * 				  
	 * *********************************************************** */
	function addTheatreForm() {
		
		// ensure that the internal encoding is UTF-8
		//mb_internal_encoding("UTF-8");
		// ensure that the http output is UTF-8
    	//mb_http_output( "UTF-8" );
		
		// Title + heading data for view
		$this->data['title'] = "theatre finder: Add a new theatre";
		$this->data['heading'] = "Add a New Theatre Entry";
		
		// form submit url (control method)
		$this->data['formOpen']=form_open('theatreCtrl/insertTheatre/');
		
		// get some data on the Period list from the db model
		$periods = $this->Theatre_model->getPeriods();
		// rearrange period array to get right values/period names
		// ** TO DO ** change model/query to return this array without rearrangement
		$periodOpts = array();
		$periodsById = array();
		for ($i=0; $i<count($periods); $i++) {
			// key & value=period_rep string
			$key = $periods[$i]['period_rep'];
			$periodOpts[$key] = $periods[$i]['period_rep'];
			// make another array w/key=p_id
			$idKey = $periods[$i]['p_id'];
			$periodsById[$idKey] = $periodOpts[$key];
		}
		$optSelected = $periodOpts['Baroque'];		
		// periods pull-down
		$this->data['periodMenu'] = form_dropdown('periods', $periodOpts, $optSelected, 'id="periods"');
		
		// get the p_id for the period $optSelected (default is p_id for 'Baroque')
		// get it from the key of the $periods array
		$p_id = array_search($optSelected, $periodsById);
		// add the types fields
		$types = $this->Theatre_model->getTypes($p_id);
		// have to get a typeOpts array to avoid making an <optGroup> in the form/select
		$typeOpts = array();
		for ($i=0; $i<count($types); $i++) {
			// key & value=period_rep string
			$key = $types[$i]['t_type'];
			$typeOpts[$key] = $types[$i]['t_type'];
		}
		// doesn't matter which theatre_type is selected
		$this->data['sub_type'] = form_dropdown('sub_type', $typeOpts, $types[0], 'id="sub_type"');

		// checkbox for City Aliases
		// Since this is an "ADD," the city will be blank initially
		// it will only have aliases if the city the user enters has alias(es)
		$cAliasCB = array(
			'name'        => 'cAliasCB',
    		'id'          => 'cAliasCB',
    		'value'       => 0,
    		'checked'     => FALSE,
    		'style'       => 'margin:3px',
			);
		$this->data['cAliasChkBox'] = form_checkbox($cAliasCB);
		
		// original input data
		$theatreInput = array(
              'name' 	   => 'theatre_name',
              'id'         => 'theatre_name',
              'value'      => '',
              'maxlength'  => '128',
              'size'       => '40',
			  'style'	   => 'margin:3px'
            );
		$this->data['nameInput'] = form_input($theatreInput);

		$countryInput = array(
              'name' 	   => 'country_name',
              'id'         => 'country_name',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$this->data['countryInput'] = form_input($countryInput);
		
		$regionInput = array(
              'name' 	   => 'region',
              'id'         => 'region',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$this->data['regionInput'] = form_input($regionInput);
		
		$cityInput = array(
              'name' 	   => 'city',
              'id'         => 'city',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$this->data['cityInput'] = form_input($cityInput);
		
		$websiteInput = array(
              'name' 	   => 'website',
              'id'         => 'website',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '32',
			  'style'	   => 'margin:3px'
            );
		$this->data['websiteInput'] = form_input($websiteInput);
		
		$notes = array(
			'name'		=> 'notes',
			'id'		=> 'notes',
			'value'		=> '',
			//'rows'		=> '10',
			//'cols'		=> '80'
			);
		$this->data['notes'] = form_textarea($notes);
		
		$brief_desc = array(
			'name'		=> 'brief_desc',
			'id'		=> 'brief_desc',
			'value'		=> '',
			//'rows'		=> '10',
			//'cols'		=> '80'
			);
		$this->data['brief_desc'] = form_textarea($brief_desc);
		
		$scholar = array(
			'name'		=> 'scholar',
			'id'		=> 'scholar',
			'value'		=> '',
			//'rows'		=> '10',
			//'cols'		=> '80'
			);
		$this->data['scholar'] = form_textarea($scholar);
		
		$est_earliestInput = array(
              'name' 	   => 'est_earliest',
              'id'         => 'est_earliest',
              'value'      => '0',
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:3px'
            );
		$this->data['est_earliest'] = form_input($est_earliestInput);
		
		$earliest_ce = array(
			'name'        => 'earliestdate_bce_ce',
    		'id'          => 'earliestdate_bce_ce',
    		'value'       => 'CE',
    		'checked'     => TRUE,
    		'style'       => 'margin:3px',
			);
			
		$this->data['earliest_ce'] = form_radio($earliest_ce);
		
		$earliest_bce = array(
			'name'        => 'earliestdate_bce_ce',
    		'id'          => 'earliestdate_bce_ce',
    		'value'       => 'BCE',
    		'checked'     => FALSE,
    		'style'       => 'margin:3px',
			);
		$this->data['earliest_bce'] = form_radio($earliest_bce);
		
		$est_latestInput = array(
              'name' 	   => 'est_latest',
              'id'         => 'est_latest',
              'value'      => '0',
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:3px'
            );
		$this->data['est_latest'] = form_input($est_latestInput);
		
		$latest_ce = array(
			'name'        => 'latestdate_bce_ce',
    		'id'          => 'latestdate_bce_ce',
    		'value'       => 'CE',
    		'checked'     => TRUE,
    		'style'       => 'margin:3px',
			);
		$this->data['latest_ce'] = form_radio($latest_ce);
		
		$latest_bce = array(
			'name'        => 'latestdate_bce_ce',
    		'id'          => 'latestdate_bce_ce',
    		'value'       => 'BCE',
    		'checked'     => FALSE,
    		'style'       => 'margin:3px',
			);
		$this->data['latest_bce'] = form_radio($latest_bce);
		
		$this->load->view('addTheatre_view', $this->data);
	}

	/* ***********************************************************
	 * Name:		insertTheatre()
	 * Input:	
	 * Output:	
	 * Description:	If the addTheatreForm input is valid, insertTheatre()
	 * 				inserts a new entry into the theatre database
	 * 				If the city entered has aliases, checks if that
	 * 				city already has those aliases or not.  If not,
	 * 				it adds the new aliases.  
	 * 				** TODO: Clean up the validation and double check
	 * 				** on the stripslashes/etc
	 * *********************************************************** */
	function insertTheatre() {
		
		$this->data['title'] = "theatre finder: Add a new theatre";
		$this->data['heading'] = "Add a New Theatre Entry";
		
		// If the form's not valid, bounce it back
		if ($this->form_validation->run('theatres') == FALSE) {
			// form data
			$this->data['title'] = "theatre finder: Add a new theatre";
			$this->data['heading'] = "Add a New Theatre to the database";
			$this->data['errorMsg'] = "Please try again, there was an error!";
			$this->data['formOpen']=form_open('theatreCtrl/insertTheatre/');
			
			$periods = $this->Theatre_model->getPeriods();
			// get some period data from model (periods table)
			$periodsById = array();
			for ($i=0; $i<count($periods); $i++) {
				// key & value=period_rep string
				$key = $periods[$i]['period_rep'];
				$periodOpts[$key] = $periods[$i]['period_rep'];
				// make another array w/key=p_id
				$idKey = $periods[$i]['p_id'];
				$periodsById[$idKey] = $periodOpts[$key];
			}
			$optSelected = $periodOpts['Baroque'];		
			// periods pull-down
			$this->data['periodMenu'] = form_dropdown('periods', $periodOpts, $optSelected, 'id="periods"');
		
			// get the p_id for the period $optSelected (default is p_id for 'Baroque')
			// get it from the key of the $periods array
			$p_id = array_search($optSelected, $periodsById);
			// add the types fields
			$types = $this->Theatre_model->getTypes($p_id);
			// have to get a typeOpts array to avoid making an <optGroup> in the form/select
			$typeOpts = array();
			for ($i=0; $i<count($types); $i++) {
				// key & value=period_rep string
				$key = $types[$i]['t_type'];
				$typeOpts[$key] = $types[$i]['t_type'];
			}
			// doesn't matter which theatre_type is selected
			$this->data['sub_type'] = form_dropdown('sub_type', $typeOpts, $types[0], 'id="sub_type"');
			
			// checkbox for City Aliases
			$cAliasCB = array(
				'name'        => 'cAliasCB',
    			'id'          => 'cAliasCB',
    			'value'       => 0, // just assume 0'd out if the form validation is bad
    								// ** TODO: get the real data that was already input, if any
    			'checked'     => FALSE,
    			'style'       => 'margin:3px',
			);
			
			$this->data['cAliasChkBox'] = form_checkbox($cAliasCB);
			
			$theatreInput = array(
              'name' 	   => 'theatre_name',
              'id'         => 'theatre_name',
              'value'      => $_POST['theatre_name'],
              'maxlength'  => '128',
              'size'       => '40',
			  'style'	   => 'margin:3px'
            );
			$this->data['nameInput'] = form_input($theatreInput);
			
			$countryInput = array(
              'name' 	   => 'country_name',
              'id'         => 'country_name',
              'value'      => $_POST['country_name'],
              'maxlength'  => '64',
              'size'       => '32',
			  'style'	   => 'margin:3px'
            );
			$this->data['countryInput'] = form_input($countryInput);
		
			$regionInput = array(
              'name' 	   => 'region',
              'id'         => 'region',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
			$this->data['regionInput'] = form_input($regionInput);
				
			$cityInput = array(
              'name' 	   => 'city',
              'id'         => 'city',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '32',
			  'style'	   => 'margin:3px'
            );
			$this->data['cityInput'] = form_input($cityInput);
		
			$websiteInput = array(
              'name' 	   => 'website',
              'id'         => 'website',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '32',
			  'style'	   => 'margin:3px'
            );
			$this->data['websiteInput'] = form_input($websiteInput);
		
			$notes = array(
			  'name'		=> 'notes',
			  'id'			=> 'notes',
			  'value'		=> '',
			  'rows'		=> '10',
			  'cols'		=> '80'
			);
			$this->data['notes'] = form_textarea($notes);
		
			$brief_desc = array(
			  'name'		=> 'brief_desc',
			  'id'			=> 'brief_desc',
			  'value'		=> '',
			  'rows'		=> '10',
			  'cols'		=> '80'
			);
			$this->data['brief_desc'] = form_textarea($brief_desc);
			
			$scholar = array(
			  'name'		=> 'scholar',
			  'id'			=> 'scholar',
			  'value'		=> '',
			  'rows'		=> '10',
			  'cols'		=> '80'
			);
			$this->data['scholar'] = form_textarea($scholar);
				
			$est_earliestInput = array(
              'name' 	   => 'est_earliest',
              'id'         => 'est_earliest',
              'value'      => '0',
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:3px'
            );
			$this->data['est_earliest'] = form_input($est_earliestInput);
		
			$earliest_ce = array(
			  'name'        => 'earliestdate_bce_ce',
    		  'id'          => 'earliestdate_bce_ce',
    		  'value'       => 'CE',
    		  'checked'     => TRUE,
    		  'style'       => 'margin:3px',
			 );
			
		    $this->data['earliest_ce'] = form_radio($earliest_ce);
		
			$earliest_bce = array(
			  'name'        => 'earliestdate_bce_ce',
    		  'id'          => 'earliestdate_bce_ce',
    		  'value'       => 'BCE',
    		  'checked'     => FALSE,
    		  'style'       => 'margin:3px',
			);
			$this->data['earliest_bce'] = form_radio($earliest_bce);
		
			$est_latestInput = array(
              'name' 	   => 'est_latest',
              'id'         => 'est_latest',
              'value'      => '0',
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:10px'
            );
			$this->data['est_latest'] = form_input($est_latestInput);
		
			$latest_ce = array(
			  'name'        => 'latestdate_bce_ce',
    		  'id'          => 'latestdate_bce_ce',
    		  'value'       => 'CE',
    		  'checked'     => TRUE,
    		  'style'       => 'margin:3px',
			);
			$this->data['latest_ce'] = form_radio($latest_ce);
		
		    $latest_bce = array(
			  'name'        => 'latestdate_bce_ce',
    		  'id'          => 'latestdate_bce_ce',
    		  'value'       => 'BCE',
    		  'checked'     => FALSE,
    		  'style'       => 'margin:3px',
			);
			$this->data['latest_bce'] = form_radio($latest_bce);	
			
			// **TODO UPDATE the errorAdding_view.php view
			$this->load->view('errorAdding_view', $this->data);
		
		} else {
			// If successfully inserted,
			// redirect back to the main page
			
			$theatre_name = $_POST['theatre_name'];
			$country_name = $_POST['country_name'];
			$region = $_POST['region'];
			$city = $_POST['city']; 
			// get the selected option from the periods pulldown
			$period_rep=$_POST['periods'];
			$sub_type=$_POST['sub_type'];
			$estEarliest = $_POST['est_earliest']; 
			$earlyBCorBCE = $_POST['earliestdate_bce_ce'];
			
			// if most recent (latest) date == 0, make it equal earliest, else keep the posted data
			$estLatest = (trim($_POST['est_latest']) == 0 ? $estEarliest : trim($_POST['est_latest'])); 
			// if more recent (latest) date == 0, make this bce/ce the same as earliestdate_bce_ce
			$lateBCorBCE = (trim($_POST['est_latest']) == 0 ? $earlyBCorBCE : trim($_POST['latestdate_bce_ce']));

			$website = $_POST['website']; 
			
			$this->Theatre_model->insertTheatre($theatre_name, $country_name, $region, $city, $period_rep, $sub_type, $estEarliest, $earlyBCorBCE, $estLatest, $lateBCorBCE, $website);
			
			// $id = $this->db->insert_id();
			$new_id = $this->Theatre_model->getLastIdInserted();
			if (is_null($new_id)) {
				$new_id=1;
			}
			// Now update the narratives table
			// First set up $rowData array
			$rowData = array(
			'theatre_id' => $new_id, // map to the new theatre id
			'formatted_notes' 		 => $_POST['notes'],
			'brief_description'		 => $_POST['brief_desc'],
			'scholarly_description'	 => $_POST['scholar'],
			'text_notes_cs' 		 => '', // all text fmts are 
			'text_notes_ci' 		 => '', // initially empty strings ''
			'text_brief_desc_cs'	 => '', // & reformatted b4
			'text_brief_desc_ci'	 => '', // insertion/update in model
			'text_scholarly_desc_cs'=> '', // only for insert (add new)
			'text_scholarly_desc_ci'=> ''
		);
			$this->Theatre_model->insertNarrative($rowData);
			
			// Make sure to see if the city already exists (OR NOT) in the cities db
			// first get the city_id
			$cityId = $this->Theatre_model->getCityId($city);
			if ($cityId == 0) { // if the city's not in the cities db
				// add it
				$this->Theatre_model->insertCity($city);
			}
			// City Aliases processing
			if (isset($_POST['cAliasCB'])) {
				// If we have a checkbox, need to get the cAliases[] array
				foreach ($_POST['cAliases'] as $key => $cAlias) {
					// check if the alias alread exists in db for this city
					$cAlias = trim($cAlias);
					$aliasCount = $this->Theatre_model->chkCityAliasCnt($cAlias, $city);
					if ($aliasCount==0) {
						// if it doesn't exist,
						// First get the city_id to use for entering the alias, if new
						$cityId = $this->Theatre_model->getCityId($city);
						// then insert this alias for that cityId
						$this->Theatre_model->insertCityAlias($cityId, $cAlias);
					}
				}
			}
			
			redirect('theatreCtrl'."#".$new_id, $this->data);
			//redirect('theatreCtrl', $this->data);
		}
	}
	
	function editTheatreForm() {
		
		// Set up the form heading/title data
		$this->data['title'] = "theatre finder: Edit an Existing Theatre Entry";
		$this->data['heading'] = "Edit an Existing Theatre Entry";
		
		// break down the uri segment to get the right theatre id
		list($theatreId, $prev) = split('_',$this->uri->segment(3));
		// get the theatre to edit
		//$theatre = $this->Theatre_model->getTheatreById($this->uri->segment(3));
		$theatre = $this->Theatre_model->getTheatreById($theatreId);
		
		// Now get the city info for that theatre
		$city = trim(stripslashes($theatre->city));
		$cityList = split('\(', $city);
		$city = trim($cityList[0]);
		$cityId = $this->Theatre_model->getCityId($city);
		// Check if this cityId has any aliases at all. If so, return the count
		$aliasCount = $this->Theatre_model->getAliasCnt4CityId($cityId);
		
		// set up the values for the cAliasCB checkbox (in the view)
		$cAliasValue = ($aliasCount>0) ? $aliasCount."_".$cityId : 0;
		$cAliasChecked = ($aliasCount>0) ? TRUE : FALSE;
		
		// checkbox for City Aliases
		$cAliasCB = array(
			'name'        => 'cAliasCB',
    		'id'          => 'cAliasCB',
    		'value'       => $cAliasValue, //'['.$city.']'.' ['.$cityId.']', // == number of aliases for this theatre's city 
    		'checked'     => $cAliasChecked, // if #aliases>0, TRUE
    		'style'       => 'margin:3px',
			);
		$this->data['cAliasChkBox'] = form_checkbox($cAliasCB);		
		
		// get some data on the Period list from the db model
		$periods = $this->Theatre_model->getPeriods();
		// rearrange period array to get right values/period names
		// ** TO DO ** change model/query to return this array without rearrangement
		$periodOpts = array();
		for ($i=0; $i<count($periods); $i++) {
			// key & value=period_rep string
			$key = $periods[$i]['period_rep'];
			$periodOpts[$key] = $periods[$i]['period_rep'];
		}
		// get the existing option/theatre period from the database
		$optSelected = trim(stripslashes($theatre->period_rep));		
		// periods pull-down
		$this->data['periodMenu'] = form_dropdown('periods', $periodOpts, $optSelected, 'id="periods"');
		
		// IF the period_rep is 'Other', we need a text input box
		// Else, a form drop_down
		// add the types fields
		if(strcmp($optSelected,'Other')==0) { // if they're equal, this will = 0}
			$typeSelected = stripslashes($theatre->sub_type); // this will be an 'Other' type
			$typeInput = array(
              'name' 	   => 'sub_type',
              'id'         => 'sub_type',
              'value'      => $typeSelected,
              'maxlength'  => '64',
              'size'       => '20',
			  'style'	   => 'margin:3px'
            );
			$this->data['sub_type'] = form_input($typeInput);
			
		} else { // We need to make a select dropdown
			// get the p_id for the period $optSelected 
			// from the database
			$p_id = $this->Theatre_model->getPeriodId($optSelected);

			$types = $this->Theatre_model->getTypes($p_id);
			// have to get a typeOpts array to avoid making an <optGroup> in the form/select
			$typeOpts = array();
			for ($i=0; $i<count($types); $i++) {
				// key & value=period_rep string
				$key = $types[$i]['t_type'];
				$typeOpts[$key] = $types[$i]['t_type'];
			}
			// get theatre type that should be selected from this entry
			$typeSelected = stripslashes($theatre->sub_type);
			$this->data['sub_type'] = form_dropdown('sub_type', $typeOpts, $typeSelected, 'id="sub_type"');
		}
		
		// main theatre data
		$theatre_name =	stripslashes($theatre->theatre_name);

		$theatreInput = array(
              'name' 	   => 'theatre_name',
              'id'         => 'theatre_name',
              'value'      => $theatre_name,
              'maxlength'  => '128',
              'size'       => '40',
			  'style'	   => 'margin:3px'
            );
		$this->data['nameInput'] = form_input($theatreInput);

		$country = stripslashes($theatre->country_name);
		
		$countryInput = array(
              'name' 	   => 'country_name',
              'id'         => 'country_name',
              'value'      => $country,
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$this->data['countryInput'] = form_input($countryInput);

		$region = stripslashes($theatre->region);
		$regionInput = array(
              'name' 	   => 'region',
              'id'         => 'region',
              'value'      => $region,
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$this->data['regionInput'] = form_input($regionInput);
		
		$city = stripslashes($theatre->city);
		$cityInput = array(
              'name' 	   => 'city',
              'id'         => 'city',
              'value'      => $city,
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$this->data['cityInput'] = form_input($cityInput);

		$websiteInput = array(
              'name' 	   => 'website',
              'id'         => 'website',
              'value'      => $theatre->website,
              'maxlength'  => '64',
              'size'       => '32',
			  'style'	   => 'margin:3px'
            );
		$this->data['websiteInput'] = form_input($websiteInput);

		$notes = array(
			'name'		=> 'notes',
			'id'		=> 'notes',
			'value'		=> stripslashes($theatre->formatted_notes),
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$this->data['notes'] = form_textarea($notes);
		
		$brief_desc = array(
			'name'		=> 'brief_desc',
			'id'		=> 'brief_desc',
			'value'		=> stripslashes($theatre->brief_description),
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$this->data['brief_desc'] = form_textarea($brief_desc);
		
		$scholar = array(
			'name'		=> 'scholar',
			'id'		=> 'scholar',
			'value'		=> stripslashes($theatre->scholarly_description),
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$this->data['scholar'] = form_textarea($scholar);
				
		$est_earliestInput = array(
              'name' 	   => 'est_earliest',
              'id'         => 'est_earliest',
              'value'      => $theatre->est_earliest,
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:3px'
            );
		$this->data['est_earliest'] = form_input($est_earliestInput);
		
		$earliest_ce = array(
			'name'        => 'earliestdate_bce_ce',
    		'id'          => 'earliestdate_bce_ce',
    		'value'       => 'CE',
    		'style'       => 'margin:3px',
			);
		$earliest_bce = array(
			'name'        => 'earliestdate_bce_ce',
    		'id'          => 'earliestdate_bce_ce',
    		'value'       => 'BCE',
    		'style'       => 'margin:3px',
			);
		// check on the bce/ce radio button check/not checked	
		if ($theatre->earliestdate_bce_ce == 'CE') {
			$earliest_ce['checked'] = TRUE;
		} else {
			$earliest_bce['checked'] = TRUE;
		}		
		$this->data['earliest_bce'] = form_radio($earliest_bce);
		$this->data['earliest_ce'] = form_radio($earliest_ce);
		
		$est_latestInput = array(
              'name' 	   => 'est_latest',
              'id'         => 'est_latest',
              'value'      => $theatre->est_latest,
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:10px'
            );
		$this->data['est_latest'] = form_input($est_latestInput);
		
		$latest_ce = array(
			'name'        => 'latestdate_bce_ce',
    		'id'          => 'latestdate_bce_ce',
    		'value'       => 'CE',
    		'style'       => 'margin:3px',
			);
		
		$latest_bce = array(
			'name'        => 'latestdate_bce_ce',
    		'id'          => 'latestdate_bce_ce',
    		'value'       => 'BCE',
    		'checked'     => FALSE,
    		'style'       => 'margin:3px',
			);
		// check on the bce/ce radio button check/not checked	
		if ($theatre->latestdate_bce_ce == 'CE') {
			$latest_ce['checked'] = TRUE;
		} else {
			$latest_bce['checked'] = TRUE;
		}		
		$this->data['latest_bce'] = form_radio($latest_bce);
		$this->data['latest_ce'] = form_radio($latest_ce);
		
		$this->load->view('editTheatre_view.php', $this->data);
	}
	
	/* ***********************************************************
	 * Name:		editTheatre()
	 * Input:	
	 * Output:	
	 * Description:	If the editTheatreForm input is valid, editTheatre()
	 * 				updates this entry for the theatre database
	 * 				If the city entered has aliases, checks if that
	 * 				city already has those aliases or not.  If not,
	 * 				it adds the new aliases.  
	 * 				** TODO: Clean up the validation and double check
	 * 				** on the stripslashes/etc
	 * *********************************************************** */
	function editTheatre() {
		
		// if the form data is NOT valid, refresh the form,
		// with (most) of the existing user data, if input
		if ($this->form_validation->run('theatres') == FALSE) {
		
			// get some period data (periods)
			$periodOpts = array();
			$periods = $this->Theatre_model->getPeriods();
			for ($i=0; $i<count($periods); $i++) {
				$key = $periods[$i]['p_id'];
				$periodOpts[$key] = $periods[$i]['period_rep'];
			}
			// note: $periodOpts['Baroque'] should be Baroque (p_id=1, period_rep='Baroque'in "period" table)
			//$periodName = (isset($_POST['period_rep'])) ? $_POST['period_rep'] : $periodOpts[1];
			// $optSelected = array_search($periodName, $periodOpts);
			$optSelected = isset($_POST['periods']) ? $POST['periods'] : $periodOpts['Baroque'];
			
			// periods pull-down
			$this->data['periodMenu'] = form_dropdown('periods', $periodOpts, $optSelected);
			
			// form data
			$this->data['title'] = "theatre finder: Edit an Existing Theatre Entry";
			$this->data['heading'] = "Edit an Existing Theatre Entry";
		
			// sub_type field
			$initType = array(
			'name' 	   		=> 'sub_type',
              'id'			=> 'sub_type',
              'value'		=> trim(stripslashes($theatre->sub_type)),
              'maxlength'	=> '64',
              'size'		=> '24',
			  'style'		=> 'margin:3px'
			);
			$this->data['sub_type'] = form_input($initType);
			
			// checkbox for City Aliases
			$cAliasCB = array(
				'name'        => 'cAliasCB',
    			'id'          => 'cAliasCB',
    			'value'       => 0, // just assume 0'd out if the form is bad
    								// Note: should react better than this, in case
									// city entered has multiple alias data ...
    			'checked'     => FALSE,
    			'style'       => 'margin:3px',
			);
			
			$this->data['cAliasChkBox'] = form_checkbox($cAliasCB);
		
			$theatreInput = array(
              'name' 	   => 'theatre_name',
              'id'         => 'theatre_name',
              'value'      => stripslashes($_POST['theatre_name']),
              'maxlength'  => '128',
              'size'       => '40',
			  'style'	   => 'margin:3px'
            );
			$this->data['nameInput'] = form_input($theatreInput);
			
			$countryInput = array(
              'name' 	   => 'country_name',
              'id'         => 'country_name',
              'value'      => stripslashes($_POST['country_name']),
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
			$this->data['countryInput'] = form_input($countryInput);
		
			$regionInput = array(
              'name' 	   => 'region',
              'id'         => 'region',
              'value'      => stripslashes($_POST['region']),
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
			$this->data['regionInput'] = form_input($regionInput);
			
			$cityInput = array(
              'name' 	   => 'city',
              'id'         => 'city',
              'value'      => stripslashes($_POST['city']),
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
			$this->data['cityInput'] = form_input($cityInput);
		
			$websiteInput = array(
              'name' 	   => 'website',
              'id'         => 'website',
              'value'      => $_POST['website'],
              'maxlength'  => '64',
              'size'       => '32',
			  'style'	   => 'margin:3px'
            );
			$this->data['websiteInput'] = form_input($websiteInput);
		
			$notes = array(
			'name'		=> 'notes',
			'id'		=> 'notes',
			'value'		=> stripslashes($_POST['notes']),
			);
			$this->data['notes'] = form_textarea($notes);
		
			$brief_desc = array(
			'name'		=> 'brief_desc',
			'id'		=> 'brief_desc',
			'value'		=> stripslashes($_POST['brief_desc']),
			);
			$this->data['brief_desc'] = form_textarea($brief_desc);
		
			$scholar = array(
			'name'		=> 'scholar',
			'id'		=> 'scholar',
			'value'		=> stripslashes($_POST['scholar']),
			);
			$this->data['scholar'] = form_textarea($scholar);
		
			$est_earliestInput = array(
              'name' 	   => 'est_earliest',
              'id'         => 'est_earliest',
              'value'      => $_POST['est_earliest'],
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:3px'
            );
			$this->data['est_earliest'] = form_input($est_earliestInput);
		
			$earliest_ce = array(
			  'name'        => 'earliestdate_bce_ce',
    		  'id'          => 'earliestdate_bce_ce',
    		  'value'       => 'CE',
    		  'checked'     => TRUE,
    		  'style'       => 'margin:3px',
			 );
			
		    $this->data['earliest_ce'] = form_radio($earliest_ce);
		
			$earliest_bce = array(
			  'name'        => 'earliestdate_bce_ce',
    		  'id'          => 'earliestdate_bce_ce',
    		  'value'       => 'BCE',
    		  'checked'     => FALSE,
    		  'style'       => 'margin:3px',
			);
			$this->data['earliest_bce'] = form_radio($earliest_bce);
		
			$est_latestInput = array(
              'name' 	   => 'est_latest',
              'id'         => 'est_latest',
              'value'      => '0',
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:10px'
            );
			$this->data['est_latest'] = form_input($est_latestInput);
		
			$latest_ce = array(
			  'name'        => 'latestdate_bce_ce',
    		  'id'          => 'latestdate_bce_ce',
    		  'value'       => 'CE',
    		  'checked'     => TRUE,
    		  'style'       => 'margin:3px',
			);
			$this->data['latest_ce'] = form_radio($latest_ce);
		
		    $latest_bce = array(
			  'name'        => 'latestdate_bce_ce',
    		  'id'          => 'latestdate_bce_ce',
    		  'value'       => 'BCE',
    		  'checked'     => FALSE,
    		  'style'       => 'margin:3px',
			);
			$this->data['latest_bce'] = form_radio($latest_bce);	
			
			$this->load->view('editTheatre_view', $this->data);
		}
		else {
			// If successfully inserted,
			// redirect back to the main page+entry id
			
			$idData = $_POST['idData'];
			list($id, $prev) = split("_", $idData);
			
			// get earliest dates info first
			$estEarliest = trim($_POST['est_earliest']);
			$earliestBCE_CE = trim($_POST['earliestdate_bce_ce']);
			// if most recent (latest) date == 0, make it equal earliest, else keep it
			$estLatest = (trim($_POST['est_latest']) == 0 ? $estEarliest : (trim($_POST['est_latest']))); 
			// if more recent (latest) date == 0, make this bce/ce the same as earliestdate_bce_ce
			$latestBCE_CE = (trim($_POST['est_latest']) == 0 ? $earliestBCE_CE : trim($_POST['latestdate_bce_ce']));
			
			// set up city for ease of use in cAlias processing
			$city = stripslashes(trim($_POST['city']));
			$rowData = array(
				'theatre_name' => stripslashes(trim($_POST['theatre_name'])),
				'country_name' => stripslashes(trim($_POST['country_name'])),
				'region' => stripslashes(trim($_POST['region'])),
				'city' => $city, // primary city name here
				//'period_rep' => stripslashes(trim($_POST['period_rep'])),
				// get the selected option from the periods pulldown
				'period_rep' => $_POST['periods'],
				'sub_type' => stripslashes(trim($_POST['sub_type'])),
				'est_earliest' => $estEarliest,
				'earliestdate_bce_ce' => $earliestBCE_CE,				
				'est_latest' => $estLatest,
				'latestdate_bce_ce' => $latestBCE_CE,
				'website' => trim($_POST['website']),
			);
			
			$this->Theatre_model->updateTheatreById($id, $rowData);
			
			// now update the narrative...
			$rowData = array(
			'theatre_id' => $id,
			'formatted_notes' 	=> stripslashes(trim($_POST['notes'])),
			'brief_description'		=> stripslashes(trim($_POST['brief_desc'])),
			'scholarly_description'	=> stripslashes(trim($_POST['scholar'])),
			'text_notes_cs' 	=> '', 
			'text_notes_ci' 	=> '', 
			'text_brief_desc_cs'	=> '', 
			'text_brief_desc_ci'	=> '', 
			'text_scholarly_desc_cs'=> '',
			'text_scholarly_desc_ci'=> ''
		);
			$this->Theatre_model->updateNarrativeById($id, $rowData);
			
			// Make sure to see if the city already exists (OR NOT) in the cities db
			// first get the city_id
			$cityId = $this->Theatre_model->getCityId($city);
			if ($cityId == 0) { // if the city's not in the cities db
				// add it
				$this->Theatre_model->insertCity($city);
			}
			// City Aliases processing -- INSERTIONS (* NO DELETIONS of City Aliases yet)
			if (isset($_POST['cAliasCB'])) {
				// If we have a checkbox, need to get the cAliases[] array
				foreach ($_POST['cAliases'] as $key => $cAlias) {
					// check if the alias alread exists in db for this city
					$cAlias = trim($cAlias);
					$aliasCount = $this->Theatre_model->chkCityAliasCnt($cAlias, $city);
					if ($aliasCount==0) {
						// if it doesn't exist,
						// First get the city_id to use for entering the alias, if new
						$cityId = $this->Theatre_model->getCityId($city);
						// then insert this alias for that cityId
						$this->Theatre_model->insertCityAlias($cityId, $cAlias);
					}
				}
			}
			
			redirect('theatreCtrl'."#".$id);
		}
	}
	
	/* ***********************************************************
	 * Name:		delete_theatre_form()
	 * Input:	
	 * Output:	
	 * Dependency:	Called from main list_view.php 
	 * Description:	MVC View to confirm whether this entry
	 * 				should be deleted or not
	 * 				** TODO: Show more of the specific theatre
	 * 					data in this view
	 * *********************************************************** */
	function delete_theatre_form() {
		
		list($theatreId, $prev) = split('_', $this->uri->segment(3));
		//$theatre = $this->Theatre_model->getTheatreById($this->uri->segment(3));
		
		$theatre = $this->Theatre_model->getTheatreById($theatreId);
		
		$theatre->theatre_name = stripslashes($theatre->theatre_name);
		$theatre->country_name = stripslashes($theatre->country_name);
		$theatre->city = stripslashes($theatre->city);
		
		$this->data['theatre'] = $theatre;
		
		$this->data['title'] = "theatre finder: Delete a theatre entry";
		$this->data['heading'] = "theatre finder: Delete this entry?";
		
		$this->load->view('delete_theatre_view.php', $this->data);
		
	}
	
	/* ***********************************************************
	 * Name:		entry_view()
	 * Input:	
	 * Output:	
	 * Description:	Retrieves the data for this theatre and
	 * 				sends it to the view.
	 * 				** TODO: Add city alias, theatre alias data
	 * 				** Images, etc
	 * *********************************************************** */
	function entry_view() {
		// split the row/id details up first to get theatre id entry in database
		list($theatreId, $prev) = split('_',$this->uri->segment(3));
		$theatre = $this->Theatre_model->getTheatreById($theatreId);
		//$region=is_null($theatre->region) ? "Region" : $theatre->region;
		$theatre->region=
			((is_null($theatre->region)) || trim($theatre->region)==='') ? "Region" : $theatre->region;
		
		$this->data['theatre'] = $theatre;
		//$this->data['region'] = $region;
	
		$this->load->view('entry_view', $this->data);
	}
	
	/* ***********************************************************
	 * Name:		delete_theatre()
	 * Input:	
	 * Output:	
	 * Dependency:	Called from delete_theatre_form()
	 * Description:	Upon confirmation, deletes this entry from database
	 * 
	 * 				** TODO: Will need to delete stuff other than
	 * 				just the specific theatres table data
	 * 				(e.g., if this theatre's city has no other
	 * 				 dependencies, etc.)
	 * *********************************************************** */
	function delete_theatre() {
		
		$idData = $_POST['idData'];
		list($id, $prev) = split('_', $idData);
		
		// minor gymnastics to save a good row
		// when deleting entries at end of table
		$currentRow = substr($prev, 3);
		$total = $this->Theatre_model->getTotalTheatres();
		if ($currentRow>=($total-3)) {
			$prev = "row".($total-4);
		}
		$this->Theatre_model->delete_theatre($id);
		// now redirect back to the main page+entryRow
		redirect('theatreCtrl'."#".$prev);
	}
	
	/* ***********************************************************
	 * Name:		getPeriods()
	 * Input:	
	 * Output:	
	 * Description:	very simply calls the theatre_model for this
	 * 				May need this for AJAX calls to theatreCtrl 
	 * *********************************************************** */
	function getPeriods() {
		
		$periods = $this->Theatre_model->getPeriods();
	}
	
	/* ***********************************************************
	 * Name:		getCountries()
	 * Input:	
	 * Output:		a mapped/JSON array of ISO 2-letter country ids
	 * 				and country_names for use by the jQuery autocomplete
	 * 				plugin
	 * Dependency:	Output is formatted especially for autocomplete plugin
	 * 				Country list is based on ISO data 
	 * 				(that is, an xml file of 246 countries/digraphs)
	 * Description:	very simply calls the theatre_model for this
	 * 				Used for AJAX calls to theatreCtrl 
	 * *********************************************************** */
	function getCountries() {
        $q = strtolower($_POST["q"]);
        if (!$q) return;
		
		$countries = array();
		
        // get country list
        $countryList = $this->Theatre_model->getCountries();
		for ($i=0; $i<count($countryList); $i++) {
			$key = $countryList[$i]['country_digraph']; 
			// key=country_digraph; value=country_name
			$countries[$key] = $countryList[$i]['country_name'];
		}
		
        // Format as JSON array for jQuery.autocomplete
		echo "[";
		foreach ($countries as $id=>$country) {
			//if (strpos(strtolower($country), $q) != false) {
				//echo $id."|".$country."\n";
			echo "{ id: \"$id\", name: \"$country\" }, ";
			//echo "<br>";
			//}
		}
		echo "]";

    }
	
	function getCityAliases() {
		$cityId = $_POST['cityId'];
		$cityAliasList = $this->Theatre_model->getCityAliases($cityId);
		
		echo "<?xml version='1.0' encoding='UTF-8'?>";
		echo "<response>\n";
		for($i=0; $i<count($cityAliasList); $i++) {
			$cityAlias = $cityAliasList[$i]['city_alias'];
			
			echo "\n<aliasData>";
			echo "\n<cityAlias>".$cityAlias."</cityAlias>";
			echo "\n</aliasData>";
		}
		echo "</response>";
		
	}
	
	function changeTypeList() {
			// Get the p_id for the period_rep posted
			$period = $_POST['periods'];
			
			//$period='Roman';
			$p_id = $this->Theatre_model->getPeriodId($period);

			// Get the types array for the p_id
			$types = $this->Theatre_model->getTypes($p_id);
			//print_r($types);
			// return the json of the types array 
			// For example: [{"t_type":"Period Subtype1"} {"t_type":"Period Subtype2"}]
			echo json_encode($types);
			
	}
	
	// Not needed now, keeping the function as example
	// of AJAX call/in CodeIgniter
	function testAjax() {
		
		if($_POST) print_r($_POST);
        else
        {
            $this->load->helper('form');
            echo form_open('test', array('id'=>'form'));
            echo form_input('title');
            echo form_submit(array('name'=>'Submit', 'id'=>'Submit', 'value'=>'Submit this'));
            echo form_close();
            echo form_ajax('form', 'POST', 'updateDiv', true);
        }
	}

	
	// Function addCityAlias 
	// Not needed now, keeping the function as example
	// of AJAX call/in CodeIgniter
	function addCityAlias() {
		if (IS_AJAX) {
			$cityInput = array(
              'name' 	   => 'city_alias',
              'id'         => 'city_alias',
              'value'      => '',
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
			
			echo form_label('Alias', 'city_alias');
			echo form_input($cityInput);
			echo "<div class=\"grid_1\"><span id=\"dropCityAlias\"><strong>".anchor('../dropCityAlias', 'Remove');
			echo "</strong></span></div>";

		} else {
			echo "Direct access to controller is Not allowed!";
		}
		
	}
	
	function showCharsets() {
		
		$charsets = $this->Theatre_model->getCharsets();
		$this->data['dbCharset'] = $charsets;
		$this->load->view('db_params', $this->data);

	}
	
	function check_utf8($str) {
    $len = strlen($str);
    for($i = 0; $i < $len; $i++){
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
} // end of check_utf8 
	
}