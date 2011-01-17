<?php
	
class Theatre_ctrl extends MY_Controller {
	
	/* ***********************************************************
	 * Name:		__construct() theatre_ctrl constructor
	 * Input:	
	 * Output:	
	 * Dependency:	Theatre_model + config & libraries
	 * Description:	Loads the form that is part of the addTheatre_view
	 * 				Note that the libraries initially loaded from the
	 * 				controller are now part of the autoload.php
	 * 				script for the TheatreFinder MVC
	 * *********************************************************** */
	function __construct() {
		parent::__construct();
			
		// load 'Theatres' table model
		$this->load->model('Theatre_model');
		//mb_http_output( "UTF-8" );
		
		// *** MAKE SURE USER IS LOGGED IN
		// If they are logged in, several $this->['data'] params for the
		// theatre_ctrl controller object are initialized 
		// (e.g., $this->['username'], $this->['access_level'])
		$this->_is_logged_in();
		
		// Most library functions loaded in autoload.php (under config/ dir) 02/04/2010
			
		$this->load->library('MY_Xmlrpc');
		//$this->load->library('MyMultiByte');
			
		// load helper functions
		// **Could be loaded in autoload.php (under config/ dir) 02/04/2010
		// removed from autoload.php 02/17/2010, calling here
		// loading URL & Form helpers from autoload.php (06/18/2010)
		$this->load->helper('international');
		$this->load->helper('ckeditor');
		
		// at start, ensure that all scripts+styles are added to 'views'
		$this->add_css('all_css');
		$this->add_scripts('all_scripts');
		
	}
	
	/* ***********************************************************
	 * Name:		_is_logged_in
	 * Input:	
	 * Output:	
	 * Dependency:  session library and indirectly, the login controller	
	 * Description:	Checks to see if the session data is logged in;
	 * 				If not, it bounces back to some message (or view)
	 * 				If so, it allows access to views under control
	 * 				of the "theatre_ctrl" controller (e.g., editing,
	 * 				deleting, etc)
	 * *********************************************************** */
	function _is_logged_in() {
		
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true) {
			// need to load a view with error message
			echo 'You don\'t have permission to access this page. <a href="http://localhost:8888/TheatreFinder/login">Login</a>';	
			die();		
			//$this->load->view('login_form');
		} else {
			$this->data['username'] = $this->session->userdata('username');
			$this->data['access_level'] = $this->session->userdata('user_access_level');
			
			// check if the user is an administrator and set the admin_link appropriately
			if ($this->data['access_level'] == 'administrator') {
				$this->data['admin_link'] = anchor('theatre_ctrl/admin_dashboard', "Admin Options");
			} else {
				$this->data['admin_link'] = anchor('theatre_ctrl/password_form', "Change account password");
			}
		}	
		
	}

	/* ***********************************************************
	 * Name:		index() [Main driver for the theatre_ctrl 
	 * Input:	
	 * Output:	
	 * Dependency:	
	 * Description:	main index for editing/viewing theatres 
	 * 				- this function is called 
	 * 				when TheatreFinder/theatre_ctrl controller
	 * 				is loaded
	 * 				  
	 * *********************************************************** */
	function index() {
		
		$this->data['title'] = 'Theatre Finder | List of Theatres';
		$this->data['body_id'] = '<body id="list">';
		
		// get the big main query
		$theatres = $this->Theatre_model->getTheatres();
		
		$prev=0; // init prev (to aid Frank in navigating through the list_view)
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
			
			$theatres[$i]['theatre_name'] = stripslashes($theatres[$i]['theatre_name']);
		
			$theatres[$i]['country_name'] = stripslashes($theatres[$i]['country_name']);
			$theatres[$i]['country_digraph'] = $theatres[$i]['country_digraph'];
			$theatres[$i]['region'] = stripslashes($theatres[$i]['region']);
			
			$theatres[$i]['city'] = stripslashes($theatres[$i]['city']);
				
			$theatres[$i]['period_rep'] = stripslashes($theatres[$i]['period_rep']);
			$theatres[$i]['period_rep'] = (strcmp('Not Yet Specified', $theatres[$i]['period_rep'])) ? 
											"(".$theatres[$i]['period_rep']." period)" :
											"(".$theatres[$i]['period_rep'].")";
			$theatres[$i]['sub_type'] = stripslashes($theatres[$i]['sub_type']);
			
			$theatres[$i]['date_range'] = $this->_format_date($theatres[$i]['est_earliest'], $theatres[$i]['est_latest'], $theatres[$i]['earliestdate_bce_ce'], $theatres[$i]['latestdate_bce_ce']);
			
			// Build a uri for edit/delete that is comprised of the idData= entry id."_"previousRowNum ('prev');
			$theatres[$i]['Details'] = anchor('theatre_ctrl/entry_visitor_info/'.$theatres[$i]['idData'], 'View Details');
			$theatres[$i]['Edit'] = anchor('theatre_ctrl/edit_visitor_form/'.$theatres[$i]['idData'], 'Edit');
			$theatres[$i]['Delete'] = anchor('theatre_ctrl/delete_theatre_form/'.$theatres[$i]['idData'], 'Delete');
			$theatres[$i]['Add'] = anchor('theatre_ctrl/add_new_form/', 'Add new');
			// get the image information for this theatre
			$thumbnailData = $this->Theatre_model->getThumbNail($theatres[$i]['id']);
			if (isset($thumbnailData)) {
				$thumbnailFile = $thumbnailData->file_path."/".$thumbnailData->image_file;
			}
			$thumbnailFile = (isset($thumbnailFile)) ? $thumbnailFile : "images/130px/imageNeededThumbnail.gif";
			$theatres[$i]['thumbnail'] = $thumbnailFile;

		}
		
		$this->data['theatres'] = $theatres;
		$this->data['numTheatres']=$this->Theatre_model->get_totals_in_table('theatres');

		// load the data into the view (under theatre_control's views)
		switch ($this->data['access_level']) {
			
			case "author":
				$this->render('main_layout','theatre_authors');		
			break;
			
			case "editor":
				$this->render();
			break;
			
			case "administrator":
				$this->render();
				//redirect('theatre_ctrl/admin_dashboard');
			break;
			
			default:
				redirect('main'); // just take the user to main page 
								  // (maybe this should display an error msg or log?)
			break;
			
		}
		
	}
	
	/* ***********************************************************
	 * Name:		search_theatres
	 * Input:		form $_POST[] select option and text to search
	 * Output:		list of results or go to the "no results" page
	 * Dependency:	
	 * Description:	SIMPLE search based on LIKE and exact matches
	 * 				  
	 * *********************************************************** */
	function search_theatres() {
		
		$this->data['title'] = 'Theatre Finder | Search Results List';
		$this->data['body_id'] = '<body id="list">';
		
		// init numTheatres
		$this->data['numTheatres'] = 0;
		
		// Get the p_id for the period_rep posted
		$field = $_POST['search_menu'];
		$text = isset($_POST['search_text']) ? trim($_POST['search_text']) : '';
		
		switch ($field) {
			case 'all':
				if ($text==='') { // searching All theatres, w/no terms ==> gets entire list
					$theatres = $this->Theatre_model->getTheatres();
					$this->data['numTheatres']=$this->Theatre_model->get_totals_in_table('theatres');
					$this->data['search_phrase'] = "all theatres";
				
				} else { // search All fields for the input search_text
					$search_terms = array('period_rep'	=> $text,
									  'country_name' 	=> $text,
									  'city'			=> $text,
									  'theatre_name'	=> $text,
									  'sub_type'		=> $text
									   );
					// here will use an or_like array of field=>text matches
					// versus straight where(==exact match)
					$theatres = $this->Theatre_model->search_terms_like($search_terms);
					$all = 1; // set $all for call to count
					$this->data['numTheatres']=$this->Theatre_model->get_theatre_count($search_terms, $all);
					$search_phrase = $this->_prep_result_phrase($field, $text);
					$this->data['search_phrase'] = $search_phrase;
					
					// check for city_aliases LIKE the search text (not just alias=alias)
					if ($this->data['numTheatres']==0) {
						$theatres_city_alias = $this->Theatre_model->get_theatres_by_cityalias_fuzzymatch($text);
						if (count($theatres_city_alias)>0) {
							$theatres = $theatres_city_alias;
						} else { // no city aliases, try theatre_aliases
							$theatres_tname_alias = $this->Theatre_model->get_theatres_by_theatre_alias($text);
							if (count($theatres_tname_alias)>0) {
								$theatres = $theatres_tname_alias;
							}
						}
						// now get the count
						$this->data['numTheatres'] = count($theatres);
					
					} /*else { // search results > 0 without test of city_aliases
						$theatres_matching_city_alias = $this->Theatre_model->get_theatres_by_cityalias_fuzzymatch($text);
						if (count($theatres_matching_city_alias)>0) {
							// we have to merge the resulting list so that we get a unique list of both							
							$c_alias_plus_theatres = array_merge($theatres_matching_city_alias, $theatres);
							// just make the merged city_alias+theatres = final result set
							$theatres = $c_alias_plus_theatres;
							// now get the count
							$this->data['numTheatres'] = count($theatres);
						
						} else { // nothing for city_alias, merge anything from theatres for theatre_name_aliases
							$theatres_tname_alias = $this->Theatre_model->get_theatres_by_theatre_alias($text);
							
							if (count($theatres_tname_alias)>0) {
								// if we got anything, merge the theatre_names + existing theatres result set
								$final_theatres = array_merge($theatres, $theatres_tname_alias);
								$theatres = $final_theatres;
							} 							
							$this->data['numTheatres'] = count($theatres);
						} 
					} */
				}
			break;
			
			case 'theatre_name':
				$search_terms = array($field=>$text);
				// here will use an or_like on one field=>text pair
				$theatres = $this->Theatre_model->search_terms_like($search_terms);
				$all = 1; // set $all for call to count -- "like" versus exact matches
				$this->data['numTheatres']=$this->Theatre_model->get_theatre_count($search_terms, $all);
			
				if ($this->data['numTheatres']==0) { // If we don't get any match on the city, 
													 // we might on the city_aliases
					$theatres = $this->Theatre_model->get_theatres_by_theatre_alias($text);
					$this->data['numTheatres'] = count($theatres);
					$field="theatre_alias";
				} /*else {
					$theatres_matching_theatre_alias = $this->Theatre_model->get_theatres_by_theatre_alias($text);
					if (count($theatres_matching_theatre_alias)>0) {
						$final_theatres = array_merge($theatres, $theatres_matching_theatre_alias);
						$theatres = $final_theatres;
					}
				} */
				
				$this->data['numTheatres'] = count($theatres);
				$search_phrase = $this->_prep_result_phrase($field, $text);
				$this->data['search_phrase'] = $search_phrase;
			break;
			
			case 'period_rep':
				$search_terms = array('period_rep' => $text,
									  'sub_type'   => $text // added so that we can search both period/type
								);
				// here will use an or_like versus
				$theatres = $this->Theatre_model->search_terms_like($search_terms);
				$all = 1; // set $all for call to count -- "like" versus exact matches
				$this->data['numTheatres']=$this->Theatre_model->get_theatre_count($search_terms, $all);
				$search_phrase = $this->_prep_result_phrase($field, $text); 
				// search phrase still tells user about "Period" only
				$this->data['search_phrase'] = $search_phrase;
			break;
			
			case 'date_bce':
				// search text is a range
				$pattern = '/^(\d{1,4})(-{1})(\d{1,4})$/';
				if (preg_match($pattern, $text)) {
					list($date1, $date2) = explode("-", $text);
		
					$theatres = $this->Theatre_model->search_date_range('BCE', $date1, $date2);
					$this->data['numTheatres'] = count($theatres); // just get a count, don't run query
				
					// build info message for user for range_bce
					$this->data['search_phrase'] = $this->_prep_result_phrase('dates_range_bce', $text); 
				
				} else { // search text is a single date/numeric or an error
					$numeric_pat = '/^\d{1,4}$/';
					if (preg_match($numeric_pat, $text)) {
						
						$theatres = $this->Theatre_model->search_date('BCE', $text);
						$this->data['numTheatres'] = count($theatres); // just get a count, don't run query		
					
						// build info message for user for range_bce
						$this->data['search_phrase'] = $this->_prep_result_phrase('date_bce_exact', $text); 
				
					} else { // need to build error message - doesn't follow the date format
						$this->data['search_phrase'] = "Error in Date Format:<br>"
														."Please enter an exact date (e.g., 500, 1664) or" 
														."<br> Date Range (e.g., 1600-1700).";
						$this->data['numTheatres'] =0;
						
					}
				}
				
			break;
			
			case 'date_ce':
	
				// check if it's a range first
				$pattern = '/^(\d{1,4})(-{1})(\d{1,4})$/';
				if (preg_match($pattern, $text)) {
					list($date1, $date2) = explode("-", $text);
					$this->data['numTheatres'] = 0;
					$theatres = $this->Theatre_model->search_date_range('CE', $date1, $date2);
					$this->data['numTheatres'] = count($theatres); // just get a count, don't run query		
					
					// build info message for user for range_ce
					$this->data['search_phrase'] = $this->_prep_result_phrase('dates_range_ce', $text); 
				
				} else { // it's a single date/numeric or an error
					$numeric_pat = '/^\d{1,4}$/';
					if (preg_match($numeric_pat, $text)) {
						$theatres = $this->Theatre_model->search_date('CE', $text);
						$this->data['numTheatres'] = count($theatres); // just get a count, don't run query		
				
						// build info message for user for range_ce
						$this->data['search_phrase'] = $this->_prep_result_phrase('date_ce_exact', $text); 
				
					} else { // need to build error message - doesn't follow the date format
						$this->data['search_phrase'] = "Error in Date Format:<br>"
														."Please enter an exact date (e.g., 500, 1664) or" 
														."<br> Date Range (e.g., 1600-1700).";
						$this->data['numTheatres'] =0;
						
					}
				}
			break;
			
			case 'date_house':
				
				$pattern = '/^(\d{1,4})(-{1})(\d{1,4})$/';
				if (preg_match($pattern, $text)) {
					$search_phrase = $this->_prep_result_phrase('date_house_range', $text); 
					$this->data['search_phrase'] = $search_phrase;
					list($date1, $date2) = explode("-", $text);
					
					$theatres = $this->Theatre_model->search_housedate_range($date1, $date2);
					$this->data['numTheatres'] = count($theatres); // just get a count, don't run query		
					
				} else { // it's a single date/numeric or an error
					$numeric_pat = '/^\d{1,4}$/';
					if (preg_match($numeric_pat, $text/*, $matches*/)) {
						$search_phrase = $this->_prep_result_phrase('date_house_exact', $text); 
						$this->data['search_phrase'] = $search_phrase;
						// build search array (date column=>date to search)
						$search_terms = array('auditorium_date'=>$text);
						$theatres = $this->Theatre_model->get_theatres_where($search_terms);
						$this->data['numTheatres']=$this->Theatre_model->get_theatre_count($search_terms);
						
						//$this->data['matches'] = $matches; // $matches arg used for regex error-checking
						
					} else { // need to build error message - doesn't follow the date format
					 
						$this->data['search_phrase'] = "Error in Date Format:<br>"
														."Please enter an exact date (e.g., 500, 1664) or" 
														."<br> Date Range (e.g., 1600-1700).";
						$this->data['numTheatres'] =0;
					}
				}
			break;
			
			case 'city':
				// Exact Match Search for City
				$search_terms = array($field=>$text);
				$theatres = $this->Theatre_model->get_theatres_where($search_terms);
				
				// check if we get results with the exact city
				$this->data['numTheatres'] = count($theatres);
				if ($this->data['numTheatres']==0) { // If we don't get any match on the city, 
													 // we might on the city_aliases
					
					$theatres = $this->Theatre_model->get_theatres_by_city_alias($text);
					if (count($theatres)>0) {
						$this->data['numTheatres'] = count($theatres);
					}
				} 
				$search_phrase = $this->_prep_result_phrase($field, $text);
				$this->data['search_phrase'] = $search_phrase;
			break;
			
			default:
				// Exact Match Search for City/Country_name
				$search_terms = array($field=>$text);
				$theatres = $this->Theatre_model->get_theatres_where($search_terms);
				$this->data['numTheatres']=$this->Theatre_model->get_theatre_count($search_terms);
				$search_phrase = $this->_prep_result_phrase($field, $text);
				$this->data['search_phrase'] = $search_phrase;
			break;
				
		}
		
		if ($this->data['numTheatres']==0) { // if we have no results
			// render some sort of nice error message			
			$this->render('main_layout','no_results');
			
		} else { // else we got a list of theatre(s) to display
			$prev=0; // init prev (to aid Frank in navigating through the list_view)
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
				$theatres[$i]['theatre_name'] = stripslashes($theatres[$i]['theatre_name']);
				$theatres[$i]['country_name'] = stripslashes($theatres[$i]['country_name']);
				$theatres[$i]['region'] = stripslashes($theatres[$i]['region']);
				$theatres[$i]['city'] = stripslashes($theatres[$i]['city']);
				$theatres[$i]['period_rep'] = stripslashes($theatres[$i]['period_rep']);
				$theatres[$i]['period_rep'] = (strcmp('Not Yet Specified', $theatres[$i]['period_rep'])) ? 
											"(".$theatres[$i]['period_rep']." period)" :
											"(".$theatres[$i]['period_rep'].")";
				$theatres[$i]['sub_type'] = stripslashes($theatres[$i]['sub_type']);
			
				$theatres[$i]['date_range'] = $this->_format_date($theatres[$i]['est_earliest'], $theatres[$i]['est_latest'], $theatres[$i]['earliestdate_bce_ce'], $theatres[$i]['latestdate_bce_ce']);
			
				/*if (preg_match('/BCE/', $theatres[$i]['earliestdate_bce_ce'])) {
					$theatres[$i]['beginDate'] = $theatres[$i]['est_earliest'].' '.$theatres[$i]['earliestdate_bce_ce'];
				} else {
					$theatres[$i]['beginDate'] = $theatres[$i]['est_earliest'];
				}
				if (preg_match('/BCE/', $theatres[$i]['latestdate_bce_ce'])) {
					$theatres[$i]['endDate'] = $theatres[$i]['est_latest'].' '.$theatres[$i]['latestdate_bce_ce'];
				} else {
					$theatres[$i]['endDate'] = $theatres[$i]['est_latest'];
				}
				*/			
				// Build a uri for edit/delete that is comprised of the idData= entry id."_"previousRowNum ('prev');
				$theatres[$i]['Details'] = anchor('theatre_ctrl/entry_visitor_info/'.$theatres[$i]['idData'], 'View Details');
				$theatres[$i]['Edit'] = anchor('theatre_ctrl/edit_visitor_form/'.$theatres[$i]['idData'], 'Edit');
				$theatres[$i]['Delete'] = anchor('theatre_ctrl/delete_theatre_form/'.$theatres[$i]['idData'], 'Delete');
				$theatres[$i]['Add'] = anchor('theatre_ctrl/add_new_form/', 'Add new');
				// get the image information for this theatre
				$thumbnailData = $this->Theatre_model->getThumbNail($theatres[$i]['id']);
				if (isset($thumbnailData)) {
					$thumbnailFile = $thumbnailData->file_path."/".$thumbnailData->image_file;
				}
				$thumbnailFile = (isset($thumbnailFile)) ? $thumbnailFile : "images/130px/imageNeededThumbnail.gif";
				$theatres[$i]['thumbnail'] = $thumbnailFile;
			}
			
			$this->data['theatres'] = $theatres;
		
			// load the data into the view (under theatre_control's views)
			switch ($this->data['access_level']) {
			
				case "author":
					$this->render('main_layout','search_theatres_authors_view');		
				break;
			
				case "editor":
					$this->render();
				break;
			
				case "administrator":
					$this->render();

				break;
				
				default:
					redirect('main'); // just take the user to main page 
									  // (maybe this should display an error msg or log?)
				break;
			
			}
		} // end if no results or display results
	}
	
	/* ***********************************************************
	 * Name:		edit_main_pages()
	 * Input:	
	 * Output:	
	 * Dependency:	Admin Dashboard
	 * Description:	Placeholder for editing the 'main' site pages
	 * 				*TODO* enable a way to edit the featured theatres
	 * 				  
	 * *********************************************************** */
	function edit_main_pages() {
		$this->data['title'] = "Theatre finder: Administrator Options";
		$this->data['heading'] = "Administrator Options";
		$this->data['body_id'] = '<body id="admin">';
		$this->data['message'] = 'This section would enable editing of main content (e.g., "About TheatreFinder")';
		$this->render();
	}
	
	/* ***********************************************************
	 * Name:		add_new_form()
	 * Input:	
	 * Output:	
	 * Dependency:	Accessible from list_view (via the "Add New" link)
	 * Description:	Loads the form that is part of the addTheatre_view
	 * 				  
	 * *********************************************************** */
	function add_new_form() {

		// ensure that the internal encoding is UTF-8
		//mb_internal_encoding("UTF-8");
		// ensure that the http output is UTF-8
    	//mb_http_output( "UTF-8" );
		
		// Title + heading data for view
		$this->data['title'] = "Theatre finder: Add a new theatre";
		$this->data['heading'] = "Add a New Theatre";
		$this->data['body_id'] = '<body id="entry">';
		
		// ** Add the mainAdd.js script for this form
		$this->add_scripts('link_mainAddjs');
		
		// form submit url 
		// (control method that inserts the theatre if form input is valid)
		$this->data['form_open']=form_open('theatre_ctrl/insert_theatre/');
		
		// checkbox for TheatreName Aliases
		// Since this is an "ADD," the theatre alias will be blank initially
		// it will only have aliases if the theatrename the user enters has alias(es)
		$theatre_aliasCB = array(
			'name'        => 'theatre_aliasCB',
    		'id'          => 'theatre_aliasCB',
    		'value'       => 0,
    		'checked'     => FALSE,
    		'style'       => 'margin:3px',
			);
		$this->data['theatre_alias_chkBox'] = form_checkbox($theatre_aliasCB);
		
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
		
		// Build ckeditor text area wrappers
		// 1) set-up ckeditor_id=>form_element_id array
		$visit_ckeditors = array('ckeditor_notes' => 'running_notes',
								 'ckeditor_basic' => 'basic_description',
								 'ckeditor_visiting_info' => 'visiting_info',
								 'ckeditor_related_sites' => 'related_sites'
							);
		// 2) send in the ckeditor array to build it
		$this->_init_ckeditors($visit_ckeditors);
		
		// get the default form entry data for a new visitor form
		$selector_list = 'running_notes, basic_description, visiting_info, related_sites';
		$defaultform_guidelines = $this->Theatre_model->get_default_form_entries($selector_list);

		// The entry_first_lister is derived from whomever is logged in and adding/editing
		$entry_lister_data = $this->Theatre_model->get_account_by_username($this->data['username']);
		$entry_lister_input = array(
              'name' 	   => 'entry_first_lister',
              'id'         => 'entry_first_lister',
              'value'      => $entry_lister_data['first_name']." ".$entry_lister_data['last_name'],
              'maxlength'  => '64',
              'size'       => '20',
			  'style'	   => 'margin:3px'
            );
		$this->data['first_lister_input'] = form_input($entry_lister_input);
		
		// build the rest of the form inputs
		// "new" form is empty, except for textarea guidelines $defaultform_guidelines
		// send in an "new" form array into your formatting function
		$form_data = $this->_form_formats_visitor($defaultform_guidelines);
		foreach ($form_data as $form_element=>$form_val) {
			$this->data[$form_element] = $form_val;
		}
		
		// render the entry_overview form (Add new)
		$this->render();

	}

	/* ***********************************************************
	 * Name:		insert_theatre()
	 * Input:	
	 * Output:	
	 * Description:	If the add_new_form input is valid, 
	 * 				insert_theatre() inserts a new entry
	 * 				into the theatre database
	 * 				If the city entered has aliases, checks if that
	 * 				city already has those aliases or not.  If not,
	 * 				it adds the new aliases.  
	 * 				** TODO: Clean up the validation and double check
	 * 				** on the stripslashes/etc
	 * *********************************************************** */
	function insert_theatre() {
		
		$this->data['title'] = "Theatre finder: Add a new theatre";
		$this->data['heading'] = "Add a New Theatre Entry";
		
		// If the form's not valid, bounce it back
		if ($this->form_validation->run('theatres') == FALSE) {
			// form data
			$this->data['title'] = "Theatre finder: ERROR Adding new theatre";
			$this->data['heading'] = "Error Adding New Theatre to the database";
			$this->data['errorMsg'] = "Please try again, there was an error!";
			$this->data['form_open']=form_open('theatre_ctrl/insert_theatre/');
			
		// checkbox for TheatreName Aliases
		// Since this is an "ADD," the theatre alias will be blank initially
		// it will only have aliases if the theatrename the user enters has alias(es)
		$theatre_aliasCB = array(
			'name'        => 'theatre_aliasCB',
    		'id'          => 'theatre_aliasCB',
    		'value'       => 0,
    		'checked'     => FALSE,
    		'style'       => 'margin:3px',
			);
		$this->data['theatre_alias_chkBox'] = form_checkbox($theatre_aliasCB);
		
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
			
			// whatever is posted, fill it into the form elements
			// form elements returned into $form_data for view-use
			$form_data = $this->_form_formats_visitor($_POST);
			foreach ($form_data as $form_element=>$form_val) {
				$this->data[$form_element] = $form_val;
			}
			
			// **TODO UPDATE the errorAdding_view.php view
			$this->load->view('errorAdding_view', $this->data);
		
		} else {
			// If form is valid, insert to theatres, visitor_info 
			// & id into scholarly_details
			// redirect user to next step, scholarly details form
			
			$rating = $_POST['star1'];
			
			$theatre_name = $_POST['theatre_name'];
			$country_name = $_POST['country_name'];
			$region = $_POST['region'];
			$city = $_POST['city']; 
			// get the selected option from the periods pulldown
			$period_rep=$_POST['periods'];
			$sub_type=$_POST['sub_type'];
			$estEarliest = isset($_POST['est_earliest']) ? $_POST['est_earliest'] : 0; 
			$earlyBCorBCE = $_POST['earliestdate_bce_ce'];
			
			// Author/Editor/First lister details
			$entry_author = isset($_POST['entry_author']) ? $_POST['entry_author']: 'Needs Full Entry';
			$entry_editor = isset($_POST['entry_editor']) ? $_POST['entry_editor'] : 'Needs editing';
			$entry_first_lister = $_POST['entry_first_lister'];
			$entry_status = isset($_POST['entry_status']) ? $_POST['entry_status'] : 'awaiting edits';
			
			// if most recent (latest) date == 0, make it equal the *earliest* date, 
			// else keep the posted data
			$estLatest = (trim($_POST['est_latest']) == 0 ? $estEarliest : trim($_POST['est_latest'])); 
			// if more recent (latest) date == 0, make this bce/ce the same as earliestdate_bce_ce
			$lateBCorBCE = (trim($_POST['est_latest']) == 0 ? $earlyBCorBCE : trim($_POST['latestdate_bce_ce']));
			
			// auditorium date 
			$aud_date = isset($_POST['auditorium_date']) ? $_POST['auditorium_date'] : 0;
			
			// Now set up the time info for this new entry:
			$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
			$mysql_datetime = date("Y-m-d H:i:s", $timestamp_in_secs);
			
			// get the country_digraph for the $country_name
			$country_details = $this->Theatre_model->get_country_details($country_name);
			// get the country's lat/
			$lat_dd = $this->_convert_dms2dd($_POST['lat_degrees'], 
											 $_POST['lat_mins'], 
											 $_POST['lat_secs'], "lat", $_POST['lat_hemisphere']);
			
			$lng_dd = $this->_convert_dms2dd($_POST['lng_degrees'], 
											 $_POST['lng_mins'], 
											 $_POST['lng_secs'], "lng", $_POST['lng_hemisphere']);
			
			// If we get a 0 decimal degree, use the lat/lng for the country
			$lat_dd = ($lat_dd == 0) ? $country_details->lat : $lat_dd;
			$lng_dd = ($lng_dd == 0) ? $country_details->lng : $lng_dd;
			
			// should set up as $row_data array (originally set up as individual function params)
			$this->Theatre_model->insertTheatre($theatre_name, $country_name, $country_details->country_digraph, 
									$region, $city, $period_rep, $sub_type, $estEarliest,
									$earlyBCorBCE, $estLatest, $lateBCorBCE, $aud_date, $lat_dd, $lng_dd,
									$mysql_datetime, $entry_author, $entry_editor, $entry_first_lister, $rating);
			
			$new_theatre_id = $this->Theatre_model->getLastIdInserted();
			// **TODO: *Not* this for ultimate error-checking
			// **TODO: Set up a view that gives an error msg
			if (is_null($new_theatre_id)) {
				$new_theatre_id=1;
			}
			
			// Now add any aliases if they've been inserted to the form, using the theatre_id
			if (isset($_POST['theatre_aliasCB'])) {
				// If we have a checkbox, need to get the cAliases[] array
				foreach ($_POST['theatre_aliases'] as $key => $theatre_alias) {
					// check if the alias alread exists in db for this city
					$theatre_alias = trim($theatre_alias);
					// Since this is first time for this theatre, no need to check 
					// to see if the alias exists for the theatre yet, 
					// so just insert this alias into the theatre_aliases
						$this->Theatre_model->insert_theatre_alias($new_theatre_id, $theatre_alias);
				}
			}
			
			// Need to add this new entry into the other tables, too!
			// 1) First check that there is not an existing visitor_info entry 
			// 	  for this theatre_id
			$visitor_count = $this->Theatre_model->check_visitor_info($new_theatre_id);
			if ($visitor_count>0) {
				//*TODO REDIRECT to error message (shouldn't have visitor_info for new entry yet)
			} else {
				
				// now update the visitor_info...
				$rowData = array(
				'theatre_id' => $new_theatre_id,
				'running_notes' 	=> stripslashes(trim($_POST['running_notes'])),
				'basic_description' => stripslashes(trim($_POST['basic_description'])),
				'visiting_info'		=> stripslashes(trim($_POST['visiting_info'])),
				'related_sites'		=> stripslashes(trim($_POST['related_sites'])),
				'text_basic_desc_cs' 	=> '', 
				'text_basic_desc_ci' 	=> '', 
				'text_visiting_info_cs'	=> '', 
				'text_visiting_info_ci'	=> '', 
				'text_related_sites_cs'=> '',
				'text_related_sites_ci'=> ''
				);
			
				// 2) add the visting info texts to the visitor info
				$this->Theatre_model->insert_visitor_info($rowData);
			}
			
			// 1) First check that there is not an existing scholarly_details entry 
			// 	  for this theatre_id
			$scholar_count = $this->Theatre_model->check_scholar_details($new_theatre_id);
			if ($scholar_count>0) {
				//*TODO REDIRECT to error message
			} else {
				// 2) now add a new scholarly_details entry (no data yet, only theatre_id)
				
				// We want the new scholarly data to have the defaultform guidelines
				$selector_list = 'general_history, previous_theatres_onsite, alts_renovs_list, desc_current, measurements';
				$defaultform_guidelines = $this->Theatre_model->get_default_form_entries($selector_list);	
				// After we get the defaultform_guideline text, we insert them
				// into the new scholarly_details entry for this theatre
				$scholar_row_data = array(
					'theatre_id' 				=> $new_theatre_id,
					'general_history'			=> $defaultform_guidelines['general_history'],
					'previous_theatres_onsite'	=> $defaultform_guidelines['previous_theatres_onsite'],
					'alts_renovs_list'	 		=> $defaultform_guidelines['alts_renovs_list'], 
					'desc_current'		 		=> $defaultform_guidelines['desc_current'], 
					'measurements'				=> $defaultform_guidelines['measurements'],
					'text_general_history_cs'	=> '', 
					'text_general_history_ci'	=> '', 
					'text_previous_theatres_cs'	=> '',
					'text_previous_theatres_ci'	=> '',
					'text_alts_renovs_cs'		=> '',
					'text_alts_renovs_ci'		=> '',
					'text_desc_current_cs'		=> '',
					'text_desc_current_ci'		=> '',
					'text_measurements_cs'		=> '',
					'text_measurements_ci'		=> ''
				);
				$this->Theatre_model->insert_scholar_details($scholar_row_data);
				
				// * SAVE the scholarly_details id data to this controller method
				// * for use in editing
				// * Note: 06/18 - may not need this id for the redirect - Check later (*TODO*)
				$scholarly_id = $this->Theatre_model->getLastIdInserted();
			}
			// 1) First check that there is not an existing biblio entry 
			// 	  for this theatre_id
			$bib_count = $this->Theatre_model->check_biblio($new_theatre_id);
			if ($bib_count>0) {
				// REDIRECT to error message
			} else {
				// 2) now add a new scholarly_details entry (no data yet, only theatre_id)
				$this->Theatre_model->insert_new_biblio_ref($new_theatre_id);
			}
			$img_count = $this->Theatre_model->check_imgs($new_theatre_id);
			if ($img_count>0) {
				// REDIRECT to error message
			} else {
				$this->Theatre_model->insert_new_main_imgs($new_theatre_id);
			}
			// Make sure to see if the city already exists (OR NOT) in the cities db
			// first get the city_id
			$cityId = $this->Theatre_model->getCityId($city, $country_details->country_digraph);
			if ($cityId == 0) { // if the city's not in the cities db
				// add it
				$this->Theatre_model->insertCity($city, $country_details->country_digraph);
			}
			// City Aliases processing
			if (isset($_POST['cAliasCB'])) {
				// If we have a checkbox, need to get the cAliases[] array
				foreach ($_POST['cAliases'] as $key => $cAlias) {
					// check if the alias alread exists in db for this city
					$cAlias = trim($cAlias);
					$aliasCount = $this->Theatre_model->chkCityAliasCnt($cAlias, $city, $country_details->country_digraph);
					if ($aliasCount==0) {
						// if it doesn't exist,
						// First get the city_id to use for entering the alias, if new
						$cityId = $this->Theatre_model->getCityId($city, $country_name);
						// then insert this alias for that cityId
						$this->Theatre_model->insertCityAlias($cityId, $cAlias);
					}
				}
			}
			
			redirect('theatre_ctrl/add_scholarly_form/'.$new_theatre_id.'-'.$scholarly_id, $this->data);
		}
	}
	
	/* ***********************************************************
	 * Name:		add_scholarly_form()
	 * Input:	
	 * Output:	
	 * Description:	ADD (new entrys) form for Scholarly Details section
	 * 
	 * *********************************************************** */
	function add_scholarly_form() {
		
		// form submit url (control method)
		$this->data['form_open'] = form_open('theatre_ctrl/update_scholarly_details/');
		
		// get the theatre id in the uri sent from add_new_form/insert_theatre
		list($theatre_id, $scholar_id) = explode('-', $this->uri->segment(3));
		
		// need this for the nav link back to this visitor_info_form page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		$this->data['theatre_id'] = $theatre_id;
		
		// get the theatre data for scholary_details
		$theatre = $this->Theatre_model->get_theatre_scholarly_details($theatre_id);
		$theatre_name = $this->Theatre_model->get_theatre_name($theatre_id);
		$this->data['theatre'] = $theatre;
		// General View data (title, body, ckeditor vars, scripts, etc.)
		// Title + heading data for view
		$this->data['title'] = "Theatre Finder | Add Scholarly Details";
		$this->data['heading'] = "Add Scholarly Details: ".$theatre_name;
		$this->data['body_id'] = '<body id="entry">';
		// add extra scripts (e.g., mainEdit jQuery script)
		// for View
		$this->add_scripts('link_mainAddjs');
		
		// Build ckeditor textarea wrappers
		// 1) set-up ckeditor_id=>form_element_id array
		$scholar_editors = array('ckeditor_general_history' => 'general_history',
								 'ckeditor_prev_theatres' => 'previous_theatres_onsite',
								 'ckeditor_alts_renovs' => 'alts_renovs_list',
								 'ckeditor_desc_current' => 'desc_current',
								 'ckeditor_measurements' => 'measurements',
								 'ckeditor_biblio' => 'biblio' // biblio added 06/01
								 );
		// 2) send in the ckeditor array to build it
		$this->_init_ckeditors($scholar_editors);
		
		
		// get the theatre data for scholary_details 
		// NOTE: for new entries, the scholarly_details will have the text samples
		// from the defaultguidelines table
		$theatre = $this->Theatre_model->get_theatre_scholarly_details($theatre_id);
		// work-around for biblio a/o 06/01
		$bib_theatre = $this->Theatre_model->get_theatre_biblio($theatre_id);
		$theatre['biblio'] = $bib_theatre['biblio'];
		
		// send in the $theatre data array into your formatting function
		$form_data = $this->_form_formats_scholarly($theatre);
		foreach ($form_data as $form_element=>$form_val) {
			$this->data[$form_element] = $form_val;
		}
		
		$this->render();

	}
	
	// ****************
	function add_images() {
		
		// form submit url (control method)
		$this->data['form_open'] = form_open('theatre_ctrl/view_test_images/');
		
		// get the theatre id in the uri sent from add_new_form/insert_theatre
		list($theatre_id, $scholar_id) = explode('_', $this->uri->segment(3));
		
		// need this for the nav link back to this visitor_info_form page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		$this->data['theatre_id'] = $theatre_id;
		
		// get the theatre name for this theatre
		$theatre_name = $this->Theatre_model->get_theatre_name($theatre_id);
		
		// General View data (title, body, ckeditor vars, scripts, etc.)
		// Title + heading data for view
		$this->data['title'] = "Theatre Finder | Upload Images";
		$this->data['heading'] = "Upload images for: ".$theatre_name;
		$this->data['body_id'] = '<body id="entry">';
		
		$this->load->model('Gallery_model');
		
		$this->data['gal_path'] = $this->Gallery_model->gallery_path; 
		$this->data['gal_url'] = $this->Gallery_model->gallery_path_url;
		
		if ($this->input->post('upload')) {
			$this->Gallery_model->do_upload();
		}
		
		$this->load->library('template');
		$this->template->load('layouts/main_layout', 'theatre_ctrl/add_images', $this->data);
	}
	
	function view_test_images() {
		
		if (isset($_POST['idData'])) {
				$idData = $_POST['idData'];
				if (preg_match("/_/", $idData)) {
					list($theatre_id, $prev) = explode("_", $idData);
				} else if (preg_match("/-/", $idData)) {
					list($theatre_id, $scholarly_id) = explode("-", $idData);	
				}
										
		} else { // we have a new update, with id only
				$idData = $_POST['add_id_data'];
				list($theatre_id, $scholarly_id) = explode("-", $idData);
		}
		
		// need this for the nav link back to this visitor_info_form page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		$this->data['theatre_id'] = $theatre_id;
		// get the theatre name for this theatre
		$theatre_name = $this->Theatre_model->get_theatre_name($theatre_id);
		$this->load->model('Gallery_model');
		
		$this->data['gal_path'] = $this->Gallery_model->gallery_path; 
		$this->data['gal_url'] = $this->Gallery_model->gallery_path_url;
		
		// Title + heading data for view
		$this->data['title'] = "Theatre Finder | Upload Images";
		$this->data['heading'] = "Upload images for: ".$theatre_name;
		$this->data['body_id'] = '<body id="entry">';
		// form submit url (control method)
		$this->data['form_open'] = form_open('theatre_ctrl/view_test_images/');
		
		$this->data['images'] = $this->Gallery_model->get_images();
		
		$this->load->library('template');
		$this->template->load('layouts/main_layout', 'theatre_ctrl/add_images', $this->data);
		
	}
	
	/* ***********************************************************
	 * Name:		edit_visitor_form()
	 * Input:	
	 * Output:	
	 * Description:	Initializes the form page for the 
	 * 				"Visitor Info" link/page/form
	 * 				for a specific theatre entry
	 * 				Parses the uri segment identifying the 
	 * 				row (in previous list) & theatre id key 
	 * 				for a theatre, and populates the form
	 * 				with this data
	 * *********************************************************** */
	function edit_visitor_form() {
		
		// break down the uri segment to get the right theatre id
		if (preg_match("/_/", $this->uri->segment(3))) {
			list($theatreId, $prev) = explode('_',$this->uri->segment(3));
		} else if (preg_match("/-/", $this->uri->segment(3))) {
			list($theatreId, $scholar_id) = explode('-',$this->uri->segment(3));
		}
		// get the theatre to edit
		$theatre = $this->Theatre_model->get_theatre($theatreId);
		// to work with the form_formats_visitor, ensure your theatre's periods=period_rep
		$theatre['periods'] = $theatre['period_rep'];
		
		$this->data['theatre'] = $theatre;
		
		// General View data (title, body, ckeditor vars, scripts, etc.)
		// Title + heading data for view
		$this->data['title'] = "Theatre finder: Edit an Existing Theatre Entry";
		$this->data['heading'] = "Edit: ".$theatre['theatre_name'];
		$this->data['body_id'] = '<body id="entry">';
		// add extra scripts (e.g., mainEdit jQuery script)
		// for View
		$this->add_scripts('link_mainEditjs');
		
		// form submit url 
		// (control method that inserts the theatre if form input is valid)
		$this->data['form_open']=form_open('theatre_ctrl/update_visitor_info/');
		
		// Build ckeditor text area wrappers
		// 1) set-up ckeditor_id=>form_element_id array
		$visit_ckeditors = array('ckeditor_notes' => 'running_notes',
								 'ckeditor_basic' => 'basic_description',
								 'ckeditor_visiting_info' => 'visiting_info',
								 'ckeditor_related_sites' => 'related_sites'
							);
		// 2) send in the ckeditor array to build it
		$this->_init_ckeditors($visit_ckeditors);
		
		// Now get the city info for that theatre
		$city = trim(stripslashes($theatre['city']));
		$cityList = explode('\(', $city);
		$city = trim($cityList[0]);
		$cityId = $this->Theatre_model->getCityId($city, $theatre['country_digraph']);
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
		// also need city_id for this form, for city alias callbacks
		$this->data['city_id'] = $cityId;
		
		$theatre_alias_count = $this->Theatre_model->get_count(array('theatre_id'=>$theatre['id']), 'theatre_aliases');
		$theatre_alias_checked = ($theatre_alias_count>0) ? TRUE: FALSE;
		
		// checkbox for TheatreName Aliases
		// Since this is an "ADD," the theatre alias will be blank initially
		// it will only have aliases if the theatrename the user enters has alias(es)
		$theatre_aliasCB = array(
			'name'        => 'theatre_aliasCB',
    		'id'          => 'theatre_aliasCB',
    		'value'       => $theatre_alias_count,
    		'checked'     => $theatre_alias_checked,
    		'style'       => 'margin:3px',
			);
		$this->data['theatre_alias_chkBox'] = form_checkbox($theatre_aliasCB);

		
		// Set up the lat/lng dms format for the form
		// 1) Convert the lat/lng in decimal degrees (from $theatre)
		$lat_dms_array = $this->_convert_dd2dms($theatre['lat'], "lat");
		$lng_dms_array = $this->_convert_dd2dms($theatre['lng'], "lng");
		
		// 2) Translate return dms arrays into $theatre form vars
		$theatre['lat_degrees'] 	= $lat_dms_array['degrees'];
		$theatre['lat_mins'] 		= $lat_dms_array['mins'];
		$theatre['lat_secs'] 		= $lat_dms_array['secs'];
		$theatre['lat_hemisphere']	= $lat_dms_array['direction'];
		
		$theatre['lng_degrees'] 	= $lng_dms_array['degrees'];
		$theatre['lng_mins'] 		= $lng_dms_array['mins'];
		$theatre['lng_secs'] 		= $lng_dms_array['secs'];
		$theatre['lng_hemisphere']	= $lng_dms_array['direction'];
				
		// build the rest of the form inputs/etc
		// send in the $theatre array into your formatting function
		$form_data = $this->_form_formats_visitor($theatre);
		foreach ($form_data as $form_element=>$form_val) {
			$this->data[$form_element] = $form_val;
		}

		// id for the theatre will be part of a hidden elment (used in theatre_alias ajax/jQuery callbacks)
		$this->data['theatre_id'] = $theatre['id'];
		// entry_first_lister taken from the theatre data (not editable, since set in the "Add New" form)
		$this->data['entry_first_lister'] = $theatre['entry_first_lister'];
		
		// render the edit_visitor_form form
		$this->render();
		//$this->load->view('editTheatre_view.php', $this->data);
	}
	
	/* ***********************************************************
	 * Name:		update_visitor_info()
	 * Input:	
	 * Output:	
	 * Description:	If the edit_visitor_form input is valid, update_visitor_info()
	 * 				updates this entry for the theatre database
	 * 				If the city entered has aliases, checks if that
	 * 				city already has those aliases or not.  If not,
	 * 				it adds the new aliases.  
	 * 				** TODO: Clean up the validation and double check
	 * 				** on the stripslashes/etc
	 * *********************************************************** */
	function update_visitor_info() {
		
		// if the form data is NOT valid, refresh the form,
		// with (most) of the existing user data, if input
		if ($this->form_validation->run('theatres') == FALSE) {
		
			$theatre_name = isset($_POST['theatre_name']) ? trim($_POST['theatre_name']) : 'name Error';
			// form data
			// General View data (title, body, ckeditor vars, scripts, etc.)
			// Title + heading data for view
			$this->data['title'] = "Theatre finder: ERROR in EDITS";
			$this->data['heading'] = "Edit: ".$theatre_name;
			$this->data['body_id'] = '<body id="entry">';
		
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
			
			// checkbox for TheatreName Aliases
			$theatre_aliasCB = array(
				'name'        => 'theatre_aliasCB',
    			'id'          => 'theatre_aliasCB',
    			'value'       => 0, // like city alias, just 0 out if form is bad
    			'checked'     => FALSE,
    			'style'       => 'margin:3px',
				);
			$this->data['theatre_alias_chkBox'] = form_checkbox($theatre_aliasCB);
			
			// build the rest of the form inputs/etc
			// send in the $theatre array into your formatting function
			$form_data = $this->_form_formats_visitor($_POST);
			foreach ($form_data as $form_element=>$form_val) {
				$this->data[$form_element] = $form_val;
			}
			
			// **TODO: Figure out error page for editing (and Adding)
			//$this->edit_visitor_form();
			$this->load->view('editTheatre_view', $this->data);
			
		}  else { // form has valid data, update the theatre+visitor_info tables
			
			if (isset($_POST['idData'])) {
				$idData = $_POST['idData']; // need this for redirect
				list($id, $prev) = explode("_", $idData);
			}
			
			// Star rating:
			$rating = isset($_POST['star1']) ? $_POST['star1'] : 1;
			
			// get earliest dates info first
			$estEarliest = isset($_POST['est_earliest']) ? trim($_POST['est_earliest']) : 0;
			$earliestBCE_CE = trim($_POST['earliestdate_bce_ce']);
			// if most recent (latest) date == 0, make it equal earliest, else keep it
			$estLatest = (trim($_POST['est_latest']) == 0 ? $estEarliest : (trim($_POST['est_latest']))); 
			// if more recent (latest) date == 0, make this bce/ce the same as earliestdate_bce_ce
			$latestBCE_CE = (trim($_POST['est_latest']) == 0 ? $earliestBCE_CE : trim($_POST['latestdate_bce_ce']));
			
			// auditorium date 
			$aud_date = isset($_POST['auditorium_date']) ? $_POST['auditorium_date'] : 0;	
			
			// Author/Editor details 
			//(*Note* First lister is not part of the update, only with 'Add New' theatres)
			$entry_author = $_POST['entry_author'];
			$entry_editor = $_POST['entry_editor'];
			$entry_status = $_POST['entry_status'];
			
			// Now set up the last_updated timestamp for this edit
			$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
			$mysql_datetime = date("Y-m-d H:i:s", $timestamp_in_secs);
			
			// set up city/country_name for ease of use in cAlias processing
			$city = stripslashes(trim($_POST['city']));
			$country_name = trim($_POST['country_name']);
			$country_digraph = $this->Theatre_model->get_country_digraph($country_name);
			
			// get the country_digraph for the $country_name
			$country_details = $this->Theatre_model->get_country_details($country_name);
			// get the country's lat/
			$lat_dd = $this->_convert_dms2dd($_POST['lat_degrees'], 
											 $_POST['lat_mins'], 
											 $_POST['lat_secs'], "lat", $_POST['lat_hemisphere']);
			
			$lng_dd = $this->_convert_dms2dd($_POST['lng_degrees'], 
											 $_POST['lng_mins'], 
											 $_POST['lng_secs'], "lng", $_POST['lng_hemisphere']);
			
			// If we get a 0 decimal degree, use the lat/lng for the country
			$lat_dd = ($lat_dd == 0) ? $country_details->lat : $lat_dd;
			$lng_dd = ($lng_dd == 0) ? $country_details->lng : $lng_dd;
			
			$rowData = array(
				'theatre_name' => stripslashes(trim($_POST['theatre_name'])),
				'country_name' => $country_name,
				'country_digraph' => $country_digraph,
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
				'auditorium_date' => $aud_date,
				'lat'			 	=> $lat_dd,
				'lng'				=> $lng_dd,
				'last_updated' 		=> $mysql_datetime,
				'entry_author'		=> $entry_author,
				'entry_editor'		=> $entry_editor,
				'entry_status'		=> $entry_status,
				'rating'			=> $rating,
			);
			
			$this->Theatre_model->updateTheatreById($id, $rowData);
			
			// now update the visitor_info...
			$rowData = array(
			'theatre_id' => $id,
			'running_notes' 	=> stripslashes(trim($_POST['running_notes'])),
			'basic_description' => stripslashes(trim($_POST['basic_description'])),
			'visiting_info'		=> stripslashes(trim($_POST['visiting_info'])),
			'related_sites'		=> stripslashes(trim($_POST['related_sites'])),
			'text_basic_desc_cs' 	=> '', 
			'text_basic_desc_ci' 	=> '', 
			'text_visiting_info_cs'	=> '', 
			'text_visiting_info_ci'	=> '', 
			'text_related_sites_cs'=> '',
			'text_related_sites_ci'=> ''
		);
			$this->Theatre_model->update_visitor_info($id, $rowData);
		
			// Now add any aliases if they've been inserted to the form, using the theatre_id
			if (isset($_POST['theatre_aliasCB'])) {
				// If we have a checkbox, need to get the cAliases[] array
				foreach ($_POST['theatre_aliases'] as $key => $theatre_alias) {
					// check if the alias alread exists in db for this city
					$theatre_alias = trim($theatre_alias);
					// NEED to check if this theatre_alias already exists or not 
					$alias_count = $this->Theatre_model->chk_theatre_alias_count($id, $theatre_alias);
					if ($alias_count==0) { // only add it if it is a new alias for this theatre
						// then insert this alias into the theatre_aliases
						$this->Theatre_model->insert_theatre_alias($id, $theatre_alias);
					}
				}
			}	
			// Make sure to see if the city already exists (OR NOT) in the cities db
			// first get the city_id
			$cityId = $this->Theatre_model->getCityId($city, $country_digraph);
			if ($cityId == 0) { // if the city's not in the cities db
				// add it
				$this->Theatre_model->insertCity($city, $country_digraph);
			}
			// City Aliases processing -- INSERTIONS (* NO DELETIONS of City Aliases yet)
			if (isset($_POST['cAliasCB'])) {
				// If we have a checkbox, need to get the cAliases[] array
				foreach ($_POST['cAliases'] as $key => $cAlias) {
					// check if the alias already exists in db for this city
					$cAlias = trim($cAlias);
					$aliasCount = $this->Theatre_model->chkCityAliasCnt($cAlias, $city, $country_digraph);
					if ($aliasCount==0) {
						// if it doesn't exist,
						// First get the city_id to use for entering the alias, if new
						$cityId = $this->Theatre_model->getCityId($city, $country_digraph);
						// then insert this alias for that cityId
						$this->Theatre_model->insertCityAlias($cityId, $cAlias);
					}
				}
			}
			
			redirect('theatre_ctrl/edit_scholarly_form/'.$idData);
		}
	}
	
	
	/* ***********************************************************
	 * Name:		edit_scholarly_form()
	 * Input:	
	 * Output:	
	 * Description:	Edit form for Scholarly Details section
	 * 
	 * *********************************************************** */
	function edit_scholarly_form() {
		
		if (preg_match("/-/", $this->uri->segment(3))) { // we got a new entry
			// get the theatre id in the uri sent from add_new_form/insert_theatre
			list($theatre_id, $scholar_id) = explode("-", $this->uri->segment(3));
			
		} else if (preg_match('/_/', $this->uri->segment(3))) { 
			// request coming from list entries
			list($theatre_id, $prev) = explode('_',$this->uri->segment(3));
		}
			
		// need this for the nav link back to this visitor_info_form page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		
		// get the theatre data for scholary_details
		$theatre = $this->Theatre_model->get_theatre_scholarly_details($theatre_id);
		// work-around for biblio a/o 06/01
		$bib_theatre = $this->Theatre_model->get_theatre_biblio($theatre_id);
		$theatre['biblio'] = $bib_theatre['biblio'];
		$this->data['theatre'] = $theatre;
		
		// General View data (title, body, ckeditor vars, scripts, etc.)
		// Title + heading data for view
		$this->data['title'] = "Theatre Finder | Edit Scholarly Details";
		$this->data['heading'] = "Edit: ".$theatre['theatre_name'];
		$this->data['body_id'] = '<body id="entry">';
		// add extra scripts (e.g., mainEdit jQuery script)
		// for View
		$this->add_scripts('link_mainEditjs');
		
		// form submit url 
		// (control method that inserts the theatre if form input is valid)
		$this->data['form_open']=form_open('theatre_ctrl/update_scholarly_details/');
		
		// Build ckeditor textarea wrappers
		// 1) set-up ckeditor_id=>form_element_id array
		$scholar_editors = array('ckeditor_general_history' => 'general_history',
								 'ckeditor_prev_theatres' => 'previous_theatres_onsite',
								 'ckeditor_alts_renovs' => 'alts_renovs_list',
								 'ckeditor_desc_current' => 'desc_current',
								 'ckeditor_measurements' => 'measurements',
								 'ckeditor_biblio' => 'biblio' // biblio added 06/01
								 );
		// 2) send in the ckeditor array to build it
		$this->_init_ckeditors($scholar_editors);
		
		$form_data = $this->_form_formats_scholarly($theatre);
		foreach ($form_data as $form_element=>$form_val) {
			$this->data[$form_element] = $form_val;
		}
		
		$this->render();
	}
	
	/* ***********************************************************
	 * Name:		update_scholarly_details()
	 * Input:	
	 * Output:	
	 * Description:	If the edit_scholarly_form input is valid, update_visitor_info()
	 * 				updates this entry for the theatre database
	 * 				If the city entered has aliases, checks if that
	 * 				city already has those aliases or not.  If not,
	 * 				it adds the new aliases.  
	 * 				** TODO: Clean up the validation and double check
	 * 				** on the stripslashes/etc
	 * *********************************************************** */	
		function update_scholarly_details() {
		
		// if the form data is NOT valid, refresh the form,
		// with (most) of the existing user data, if input
		if ($this->form_validation->run('scholarly_details') == FALSE) {
		
		$theatre_name = isset($_POST['theatre_name']) ? trim($_POST['theatre_name']) : 'name Error';
		
		// form data
		// General View data (title, body, ckeditor vars, scripts, etc.)
		// Title + heading data for view
		$this->data['title'] = "Theatre finder: ERROR in EDITS";
		$this->data['heading'] = "Edit: ".$theatre_name;
		$this->data['body_id'] = '<body id="entry">';
		
			// build the rest of the form inputs/etc
			// send in the $theatre array into your formatting function
			$form_data = $this->_form_formats_scholarly($_POST);
			foreach ($form_data as $form_element=>$form_val) {
				$this->data[$form_element] = $form_val;
			}
			
			// **TODO: Figure out error page for editing (and Adding)
			//$this->edit_visitor_form();
			$this->load->view('editTheatre_view', $this->data);
		} else { // form data is valid; process the data
			
			if (isset($_POST['idData'])) {
				$idData = $_POST['idData'];
				if (preg_match("/_/", $idData)) {
					list($id, $prev) = explode("_", $idData);
				} else if (preg_match("/-/", $idData)) {
					list($id, $scholar_id) = explode("-", $idData);	
				}
										
			} else { // we have a new update, with id only
				$idData = $_POST['add_id_data'];
				list($id, $scholarly_id) = explode("-", $idData);
			}
			
			// now update the scholarlydata_info...
			$rowData = array(
			'theatre_id' 				=> $id,
			'general_history'			=> stripslashes(trim($_POST['general_history'])),
			'previous_theatres_onsite'	=> stripslashes(trim($_POST['previous_theatres_onsite'])),
			'alts_renovs_list'	 		=> stripslashes(trim($_POST['alts_renovs_list'])), 
			'desc_current'		 		=> stripslashes(trim($_POST['desc_current'])), 
			'measurements'				=> stripslashes(trim($_POST['measurements'])),
			'text_general_history_cs'	=> '', 
			'text_general_history_ci'	=> '', 
			'text_previous_theatres_cs'	=> '',
			'text_previous_theatres_ci'	=> '',
			'text_alts_renovs_cs'		=> '',
			'text_alts_renovs_ci'		=> '',
			'text_desc_current_cs'		=> '',
			'text_desc_current_ci'		=> '',
			'text_measurements_cs'		=> '',
			'text_measurements_ci'		=> ''
		);
		
			// Make sure we update the visitor_info table
			$this->Theatre_model->update_scholar_details($id, $rowData);
			
			// now update the biblio...
			$bibData = array('theatre_id' 	  => $id,
							 'biblio' 	  	  => stripslashes(trim($_POST['biblio'])),
							 'text_biblio_cs' => '',
							 'text_biblio_ci' => ''
							);
		
			// Make sure we update the visitor_info table
			$this->Theatre_model->update_biblio($id, $bibData);
			
			// Now set up the last_updated timestamp for this edit
			$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
			$mysql_datetime = date("Y-m-d H:i:s", $timestamp_in_secs);
			$this->Theatre_model->update_time_in_theatres($id, $mysql_datetime);
			
			// go back to the theatre list, user can view details from there
			redirect('theatre_ctrl'."#".$id);
		}
	}
	
	/* ***********************************************************
	 * Name:		edit_biblio_form()
	 * Input:	
	 * Output:	
	 * Description:	Edit form for Bibliography section
	 * 
	 * *********************************************************** */
	function edit_biblio_form() {
		
		if (preg_match("/-/", $this->uri->segment(3))) { // we got a new entry
			// get the theatre id in the uri sent from add_new_form/insert_theatre
			list($theatre_id, $scholar_id) = explode("-", $this->uri->segment(3));
			
		} else if (preg_match('/_/', $this->uri->segment(3))) { 
			// request coming from list entries 
			// (shouldn't happen with biblio, but leave in for testing)
			list($theatre_id, $prev) = explode('_',$this->uri->segment(3));
		} 
			
		// need this for the nav link back to this theatre's set of forms
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		
		// get the theatre data for scholary_details
		$theatre = $this->Theatre_model->get_theatre_biblio($theatre_id);
		$this->data['theatre'] = $theatre;
		
		// General View data (title, body, ckeditor vars, scripts, etc.)
		// Title + heading data for view
		$this->data['title'] = "Theatre Finder | Edit Bibliography";
		$this->data['heading'] = "Edit: ".$theatre['theatre_name'];
		$this->data['body_id'] = '<body id="entry">';
		// add extra scripts (e.g., mainEdit jQuery script)
		// for View
		$this->add_scripts('link_mainEditjs');
		
		// form submit url 
		// (control method that inserts the theatre if form input is valid)
		$this->data['form_open']=form_open('theatre_ctrl/update_biblio/');
		
		// Build ckeditor textarea wrappers
		// 1) set-up ckeditor_id=>form_element_id array
		$biblio_editor = array('ckeditor_biblio' => 'biblio');
								
		// 2) send in the ckeditor array to build it
		$this->_init_ckeditors($biblio_editor);
		
		$form_data = $this->_form_formats_biblio($theatre);
		foreach ($form_data as $form_element=>$form_val) {
			$this->data[$form_element] = $form_val;
		}
		
		$this->render();
	}	
	
	function update_biblio() {
		// if the form data is NOT valid, refresh the form,
		// with (most) of the existing user data, if input
		if ($this->form_validation->run('bibliography') == FALSE) {
		
			$theatre_name = isset($_POST['theatre_name']) ? trim($_POST['theatre_name']) : 'name Error';
		
			// form data
			// General View data (title, body, ckeditor vars, scripts, etc.)
			// Title + heading data for view
			$this->data['title'] = "Theatre finder: ERROR in EDITS";
			$this->data['heading'] = "Edit: ".$theatre_name;
			$this->data['body_id'] = '<body id="entry">';
		
			// build the rest of the form inputs/etc
			// send in the $theatre array into your formatting function
			$form_data = $this->_form_formats_scholarly($_POST);
			foreach ($form_data as $form_element=>$form_val) {
				$this->data[$form_element] = $form_val;
			}
			
			// **TODO: Figure out error page for editing (and Adding)
			//$this->edit_visitor_form();
			$this->load->view('editTheatre_view', $this->data);
		} else { // form data is valid; process the data
			
			if (isset($_POST['idData'])) {
				$idData = $_POST['idData'];
				if (preg_match("/_/", $idData)) {
					list($id, $prev) = explode("_", $idData);
				} else if (preg_match("/-/", $idData)) {
					list($id, $scholar_id) = explode("-", $idData);	
				}
										
			} else { // we have a new update, with id + scholar id
				$idData = $_POST['add_id_data'];
				list($id, $scholarly_id) = explode("-", $idData);
			}
			
			// now update the biblio...
			$rowData = array('theatre_id' 	  => $id,
							 'biblio' 	  	  => stripslashes(trim($_POST['biblio'])),
							 'text_biblio_cs' => '',
							 'text_biblio_ci' => ''
							);
		
			// Make sure we update the visitor_info table
			$this->Theatre_model->update_biblio($id, $rowData);
			
			// Now set up the last_updated timestamp for this edit
			$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
			$mysql_datetime = date("Y-m-d H:i:s", $timestamp_in_secs);
			$this->Theatre_model->update_time_in_theatres($id, $mysql_datetime);
			
			// go back to the theatre list, user can view details from there
			redirect('theatre_ctrl'."#".$id);
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
		
		$this->data['body_id'] = '<body id="entry">';
		$this->data['title'] = 'Theatre Finder | DELETE Entry';
		
		list($theatreId, $prev) = explode('_', $this->uri->segment(3));
		
		$theatre = $this->Theatre_model->get_theatre($theatreId);
		
		$theatre['theatre_name'] = stripslashes($theatre['theatre_name']);
		$theatre['country_name'] = stripslashes($theatre['country_name']);
		$theatre['city'] = stripslashes($theatre['city']);
		
		$this->data['theatre'] = $theatre;

		$this->data['heading'] = "Theatre finder: DELETE this entry?";
		
		$this->render();
		
	}
	
	/* ***********************************************************
	 * Name:		entry_visitor_info()
	 * Input:	
	 * Output:	
	 * Description:	Retrieves the data for this theatre and
	 * 				sends it to the view for the 'visitor_info' page
	 * 				(from "View Details" from the list)
	 * 				** TODO: Add city alias to the Overview 
	 * 					(either as popup or list under main name?)
	 * 				** TODO: Images, etc
	 * *********************************************************** */
	function entry_visitor_info() {
		
		$this->data['body_id'] = '<body id="entry">';
		$this->data['title'] = 'Theatre Finder | Theatre Entry General Visitor Information';
		
		// add the javascript to set the star rating according to the database 'rating'
		$this->add_scripts('link_set_star_rating_js');
		
		// explode the row/id details up first to get theatre id entry in database
		list($theatreId, $prev) = explode('_',$this->uri->segment(3));
		$theatre = $this->Theatre_model->get_theatre($theatreId);

		//$region=is_null($theatre->region) ? "Region" : $theatre->region;
		$theatre['region'] =
			((is_null($theatre['region'])) || trim($theatre['region'])==='') ? "Region" : $theatre['region'];
		
		$this->data['date_string'] = $this->_format_date($theatre['est_earliest'], $theatre['est_latest'], $theatre['earliestdate_bce_ce'], $theatre['latestdate_bce_ce']);
		$t_imgs = $this->Theatre_model->get_main_images($theatreId);
		//id, stage, exterior, auditorium, plan, section, video_link, top_file_path
		//$this->data['main_images'] = $t_imgs;
		foreach ($t_imgs as $sub_path=>$filename) {
			if ($filename!='imageNeededLarge.gif') {
				if ($sub_path === "video_link") { // if it's the video, take the original full column data
					$image_key = $sub_path;
					$theatre[$image_key] = $filename;
				} else {
					$image_key = $sub_path.'_image';
					$theatre[$image_key] = $t_imgs['top_file_path'].$sub_path."/".$filename;
				}
			} else {	
				$image_key = $sub_path.'_image';
				$theatre[$image_key] = $t_imgs['top_file_path']."/".$filename;
			}
		}
		
		// Decide the div class to use for the entry_status, depending on the status
		switch($theatre['entry_status']) {
			case "awaiting edits":
				$theatre["status_css_class"] = 'waitingedits';
			break;
			
			case "awaiting approval":
				$theatre["status_css_class"] = 'waitingapproval';
			break;
			
			case "approved":
				$theatre["status_css_class"] = 'approved';
			break;
			
			default:
				$theatre["status_css_class"] = '';
			break;
		}
		// Strip time (hh:mm:ss) from entry_date/last_updated
		$entry_date_list = explode(' ', $theatre['entry_date']);
		$theatre['entry_date'] = $entry_date_list[0]; // just get yyyy-mm-dd (not hh:mm:ss, too)
		
		$last_updated_list = explode(' ', $theatre['last_updated']);
		$theatre['last_updated'] = $last_updated_list[0];
		
		// get the decimal degrees (lat/lng) and convert to degrees/min/secs (DMS)
		list($theatre['lat_dms'], $theatre['lng_dms']) = $this->_convert_decimal_degrees_to_DMS($theatre['lat'], $theatre['lng']);
		
		$this->data['theatre'] = $theatre;
		// need this for the nav link back to this visitorinfo page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		$this->render();

	}
	
	/* ***********************************************************
	 * Name:		entry_scholarly_details()
	 * Input:	
	 * Output:	
	 * Description:	Retrieves the data for this theatre and
	 * 				sends it to the view for the 'scholarly_details' page
	 * 				(from "View Details" from the list)
	 * 				** TODO: Add city alias to the Overview 
	 * 					(either as popup or list under main name?)
	 * 				** TODO: Images, etc
	 * *********************************************************** */
	function entry_scholarly_details() {
		
		$this->data['body_id'] = '<body id="entry">';
		$this->data['title'] = 'Theatre Finder | Theatre Entry Scholarly Details';
		
		// explode the row/id details up first to get theatre id entry in database
		list($theatreId, $prev) = explode('_',$this->uri->segment(3));
		
		// need this for the nav link back to this visitorinfo page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		
		$theatre = $this->Theatre_model->get_theatre_scholarly_details($theatreId);
		
		// Biblio a/o Jun1, 2010 - Will have to modify model to get better
		// query, now that Frank wants biblio in scholarly details view
		$bib_theatre = $this->Theatre_model->get_theatre_biblio($theatreId);
		$this->data['biblio'] = $bib_theatre['biblio'];
		
		// Get any theatre_aliases for this theatre
		$theatre_aliases = $this->Theatre_model->get_theatre_aliases($theatreId);
		if ($theatre_aliases==0) {
			$theatre_aliases=array();
			$theatre_aliases[0] = array("theatre_alias" => 
									"This theatre, ".$theatre['theatre_name'].", is not currently listed under any alternative names (aliases).");
		}
		// Get any city_aliases for the city in/near which this theatre is located
		// get the city_id for this theatre's city/country pair
		$city_id = $this->Theatre_model->getCityId($theatre['city'], $theatre['country_digraph']);
		$city_alias_cnt = $this->Theatre_model->getAliasCnt4CityId($city_id);
		if ($city_alias_cnt>0) {
			$city_aliases = $this->Theatre_model->getCityAliases($city_id);
		} else {
			$city_aliases=array();
			$city_aliases[0] = array("city_alias" => "The city, ".$theatre['city']
							.", in/near which this theatre is located, is not currently listed with" 
							." any alternative names (aliases).");
		}
		
		$this->data['t_aliases'] = $theatre_aliases;
		$this->data['c_aliases'] = $city_aliases;
		
		// get images for the theatre
		$t_imgs = $this->Theatre_model->get_main_images($theatreId);
		//id, stage, exterior, auditorium, plan, section, top_file_path
		foreach ($t_imgs as $sub_path=>$filename) {
			if ($filename!='imageNeededLarge.gif') {
				if ($sub_path === "video_link") { // if it's the video, take the original full column data
					$image_key = $sub_path;
					$theatre[$image_key] = $filename;
				} else {
					$image_key = $sub_path.'_image';
					$theatre[$image_key] = $t_imgs['top_file_path'].$sub_path."/".$filename;
				}
			} else {
				$image_key = $sub_path.'_image';
				$theatre[$image_key] = $t_imgs['top_file_path']."/".$filename;
			}
		}
		
		$this->data['theatre'] = $theatre;
		
		$this->render();

	}
	
	function entry_biblio() {
		
		$this->data['body_id'] = '<body id="entry">';
		$this->data['title'] = 'Theatre Finder | Theatre Entry Scholarly Details';
		
		// explode the row/id details up first to get theatre id entry in database
		list($theatre_id, $prev) = explode('_',$this->uri->segment(3));
		
		// need this for the nav link back to this visitorinfo page
		$this->data['curr_theatre_ref'] = $this->uri->segment(3);
		
		$theatre = $this->Theatre_model->get_theatre_biblio($theatre_id);
		
		$t_imgs = $this->Theatre_model->get_main_images($theatre_id);
		//id, stage, exterior, auditorium, plan, section, top_file_path
		foreach ($t_imgs as $sub_path=>$filename) {
			if ($filename!='imageNeededLarge.gif') {
				if ($sub_path === "video_link") { // if it's the video, take the original full column data
					$image_key = $sub_path;
					$theatre[$image_key] = $filename;
				} else {
					$image_key = $sub_path.'_image';
					$theatre[$image_key] = $t_imgs['top_file_path'].$sub_path."/".$filename;
				}
			} else {
				$image_key = $sub_path.'_image';
				$theatre[$image_key] = $t_imgs['top_file_path']."/".$filename;
			}
		}
		
		$this->data['theatre'] = $theatre;
		
		$this->render();

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
		list($id, $prev) = explode('_', $idData);
		
		// do minor gymnastics to save a good row
		// when deleting entries at end of table
		$currentRow = substr($prev, 3);
		$total = $this->Theatre_model->get_totals_in_table('theatres');
		if ($currentRow>=($total-3)) {
			$prev = "row".($total-4);
		}
		$this->Theatre_model->delete_theatre($id);
		// now redirect back to the main page+entryRow
		redirect('theatre_ctrl'."#".$prev);
	}
	
	
	/* ***********************************************************
	 * Name:		getPeriods()
	 * Input:	
	 * Output:	
	 * Description:	very simply calls the theatre_model for this
	 * 				May need this for AJAX calls to theatre_ctrl 
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
      	/*  $countryList = $this->Theatre_model->getCountries();
		for ($i=0; $i<count($countryList); $i++) {
			$key = $countryList[$i]['country_digraph']; 
			// key=country_digraph; value=country_name
			$countries[$key] = $countryList[$i]['country_name']." (".$countryList[$i]['country_digraph'].")";
		}
	   */	
	 	$countries = $this->Theatre_model->getCountries();	
		
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
	
	/* **************************************************************************
	 * Name: 		getCityAliases()
	 * Input:		$_POST['cityId']
	 * Output:		xml-encoded list of city_aliases for the input 
	 * 				(posted) cityId from theatre entry's city
	 * 			
	 * Description: callback function for jQuery cityAlias CheckBox that returns
	 * 				all the alias names for a particular city (based on its id)
	 ************************************************************************** */
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
	
	/* **************************************************************************
	 * Name: changeTypeList()
	 * Input:	$_POST['periods']
	 * Output:	json_encoded list of sub_types, 
	 * 			based on the p_id of the period selected			
	 * 			e.g., Minoan p_id ==> Minoan sub_type list from t_types table
	 * 
	 * Description: Function to get list of sub_types from t_types table
	 * 				based on the period option selected.
	 * 				Returns a json-encoded list of theatre sub_types.
	 * 				Called via 'select' change (jQuery/ajax) 
	 * 				in the visitor form ui
	 ************************************************************************** */
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
	
	/* **************************************************************************
	 * Name: 		get_theatre_aliases()
	 * Input:		$_POST['theatre_id']
	 * Output:		xml-encoded list of city_aliases for the input 
	 * 				(posted) cityId from theatre entry's city
	 * 			
	 * Description: callback function for jQuery cityAlias CheckBox that returns
	 * 				all the alias names for a particular city (based on its id)
	 ************************************************************************** */
	function get_theatre_aliases() {
		$theatre_id = $_POST['theatre_id'];
		$alias_list = $this->Theatre_model->get_theatre_aliases($theatre_id);
		
		if (count($alias_list)>0) {
			echo json_encode($alias_list);
		}
		
	}
	
	/* ***********************************************************
	 * Name:		delete_alias()
	 * Input:		None
	 * Output:		None
	 * Dependency:  'user_access_level'	must be 'administrator' to see
	 * Description:	Loads the Administrator 'dashboard' of tasks/options
	 * 				*Note* The default page is just the pending list.
	 * 				  
	 * *********************************************************** */
	function delete_alias() {
		
		$alias2delete = $this->input->post('alias_text');
		$alias_class = $this->input->post('alias_type');
		
		if ($alias_class === 'cityAliases') {
			// run through deletion of city alias
			// need city_id data
			$city_id = $this->input->post('city_id');
			
			if ($city_id === 'new') {
				// nothing to delete, this is a new entry
			} else {
				$delete_action = "CITY";
				$this->Theatre_model->delete_city_alias($city_id, $alias2delete);	
			}
			//echo " Tried to delete: ".$alias2delete." For city id: ".$city_id." And action: ".$delete_action;
		
		} 
		if ($alias_class === 'theatre_aliases') {
			// run through deletion of theatre alias
			$theatre_id= $this->input->post('theatre_id');
			
			if ($theatre_id === 'new') {
				$alias_list['delete_me'] = "Do nothing, this is a new entry";
			} else {
				$delete_action = "Got it";
				$this->Theatre_model->delete_theatre_alias($theatre_id, $alias2delete);
			}
			//echo " Tried to delete: ".$alias2delete." For theatre: ".$theatre_id." And action: ".$delete_action;
		
		}
		
		//print_r($alias_list);
		echo " Tried to delete: ".$alias2delete." For theatre: ".$theatre_id." And action: ".$delete_action;
		//json_encode($alias_list);
		
	}
	
	/* ***********************************************************
	 * Name:		admin_dashboard()
	 * Input:		None
	 * Output:		None
	 * Dependency:  'user_access_level'	must be 'administrator' to see
	 * Description:	Loads the Administrator 'dashboard' of tasks/options
	 * 				*Note* The default page is just the pending list.
	 * 				  
	 * *********************************************************** */
	function admin_dashboard() {
		
		if ($this->data['access_level']!='administrator') { // user does not have access
			echo "Sorry, you do not have access to this section of Theatre-Finder";
			
		} else {
			// Title + heading data for view
			$this->data['title'] = "Theatre finder: Administrator Options";
			$this->data['heading'] = "Administrator Options";
			$this->data['body_id'] = '<body id="admin">';
		
			$this->add_scripts('link_account_approval_js');
		
			$account_types = array('pending' => 'pending', 
							   'administrator' => 'administrator', 
							   'editor' => 'editor', 
							   'author' => 'author');
		
			$pending_accounts	= $this->Theatre_model->get_accounts('pending');
		
			// need to build a form select for each pending account, with id tied to the account id (from database)
			for ($i=0; $i<count($pending_accounts); $i++) {
			
				// build a select name='' and id='' string
				$select_el_id = $pending_accounts[$i]['id'];
			
				// extra attributes for the select pull-down need to be added as a string: id and class
				$extra_attrs = 'class="account_types" id="select_list-'.$select_el_id.'"';
				$pending_accounts[$i]['select_menu'] = form_dropdown("user_access_level", $account_types, 'pending', $extra_attrs);
				$pending_accounts[$i]['undo_options'] = form_dropdown("pending_access_level", $account_types, 'pending', $extra_attrs);
		
				$approve = array(
					'name'        => "approve_deny",
					'id'		  => "aprove_deny",
    				'value'       => 'approve',
					'checked'	  => 'TRUE', // default is to have approved be checked
    				'style'       => 'margin:3px',
					);
				$deny = array(
					'name'        => "approve_deny",
					'id'		  => "aprove_deny",
    				'value'       => 'deny',
    				'style'       => 'margin:3px',
					);
			
				$pending_accounts[$i]['approve'] = form_radio($approve);
				$pending_accounts[$i]['deny'] = form_radio($deny);
			}
		
			$this->data['pending'] = $pending_accounts;
			$this->data['pend_count'] = $this->Theatre_model->get_num_accounts('pending');
			$this->render();
		}
	}
	
	/* ***********************************************************
	 * Name:		admin_acct_info()
	 * Input:		None
	 * Output:		None
	 * 
	 * Description:	Loads the totals for the various access levels
	 * 				(admin, editor, author)
	 *				Main heading section for updating user access levels
	 *				Sub-<li>s to this are found in admin_update_accts()
	 * *********************************************************** */
	function admin_acct_info() {
		
		if ($this->data['access_level']!='administrator') { // user does not have access
			echo "Sorry, you do not have access to this section of Theatre-Finder";
			
		} else {
			//$this->data['method'] = $this->router->fetch_method();
			// Title + heading data for view
			$this->data['title'] = "Theatre finder: Administrator Options";
			$this->data['heading'] = "Administrator Options";
			$this->data['body_id'] = '<body id="admin">';
		
			// used for nav-bar to show # pending accts
			$this->data['pend_count'] = $this->Theatre_model->get_num_accounts('pending');
		
			$this->data['total_author'] = $this->Theatre_model->get_num_accounts('author');
			$this->data['total_editor'] = $this->Theatre_model->get_num_accounts('editor');;
			$this->data['total_admins'] = $this->Theatre_model->get_num_accounts('administrator');;
		
			$this->data['total_existing'] = ($this->data['total_author']+$this->data['total_editor']+$this->data['total_admins']);
			$this->render();
		}
	}
	
	/* ***********************************************************
	 * Name:		admin_update_accts()
	 * Input:		None
	 * Output:		None
	 * Dependency: 
	 * Description:	Loads the Administrator 'dashboard' of tasks/options
	 * 				for existing users - Enables the modification
	 * 				of existing user levels, or the deletion
	 * 				of a user
	 * 				  
	 * *********************************************************** */
	function admin_update_accts() {
		if ($this->data['access_level']!='administrator') { // user does not have access
			echo "Sorry, you do not have access to this section of Theatre-Finder";
			
		} else {
		
			$access_to_get = $this->uri->segment(3);
			// check that the uri segment is a valid one
			switch($access_to_get) {
				case 'author':
				break;
				case 'editor':
				break;
				case 'administrator':
				break;
				case 'pending':
				break;
				default:
					redirect('theatre_ctrl/admin_acct_info'); 
					// redirect them back to the overall counts
					// if it's not an expected user_access_level
				break;
			}
		
			// Title + heading data for view
			$this->data['title'] = "Theatre finder: Administrator Options";
			$this->data['heading'] = "Administrator Options";
			$this->data['body_id'] = '<body id="admin">';
		
			// add the javascript (jQuery) for the ajax-based updates
			$this->add_scripts('link_account_approval_js');
			
			//access array (for pull-down)
			$access_types = array('author' => 'author',  
							   'editor' => 'editor', 
							   'administrator' => 'administrator');
							   
			// set up all the data for the view
			$this->data['overview_heading'] = ucfirst($access_to_get);
			// used for nav-bar to show # pending accts
			$this->data['pend_count'] = $this->Theatre_model->get_num_accounts('pending');
			// Time info
			$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
			$this->data['now'] = date("m-d-Y", $timestamp_in_secs);
			
			// Get the count for this access
			$this->data['count'] = $this->Theatre_model->get_num_accounts($access_to_get);
			
			if ($this->data['count']==0) { 
				// show the user the "no accounts under X access level" view
				$this->render('main_layout','no_accts_this_access');
			
			} else {
				// we have accounts to get and show			
				$accounts = $this->Theatre_model->get_accounts($access_to_get);
				for ($i=0; $i<count($accounts); $i++) {
					
					// build a select name='' and id='' string
					$select_el_id = $accounts[$i]['id'];
			
					// extra attributes for the select pull-down need to be added as a string: id and class
					$extra_attrs = 'class="account_types" id="select-'.$select_el_id.'"';
					$accounts[$i]['select_menu'] = form_dropdown("user_access_level", $access_types, $access_to_get, $extra_attrs);
					
					// check box for deletion - separate action from mod'ing existing users
					$delete = array(
						'name'        => "delete_option",
						'id'		  => "delete_option",
  		  				'value'       => 'delete',
    					'style'       => 'margin-right:10px',
						);
				
					$accounts[$i]['delete_option'] = form_checkbox($delete);
				}
			
				$this->data['accounts'] = $accounts;
				$this->render();
			}
		}	
	}
	
	/* **************************************************************************
	 * Name: 		approve_accounts()
	 * Input:		serialized form data (account info)
	 * Output:		Message to reviewing_admin + email to account requestor
	 * 			
	 * Description: Call-back function for jQuery click event that returns
	 * 				a text message to the reviewing_admin in the admin_dashboard
	 * 				based on whether the database update was successful and 
	 * 				if an email was successfully sent to the person requesting
	 * 				an account 
	 * 				See _$this->email_activate() and $this->_email_regrets() 
	 ************************************************************************** */
	function approve_accounts() {
				
		// init $result to false
		$result = FALSE; // or 0
		// init approved to FALSE
		$approved = 0;
		// get the serialized form data and parse it into database column=>value pairs
		$var_list = explode('&', $this->input->post('acct_data'));		
		for ($i=0; $i < count($var_list); $i++) {
    		$key_val_pair = split('=', $var_list[$i]);
			// $post_data['id'], $post_data['approve_deny'], $post_data['user_access_level']
    			$post_data[$key_val_pair[0]] = $key_val_pair[1];
		}
		
		// Now set up the datetime timestamp for this edit
		$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
		$row_data['reviewed_date'] = date("Y-m-d H:i:s", $timestamp_in_secs);
		$row_data['last_reviewed_date'] = $row_data['reviewed_date']; // first time approval, these dates are same
		
		// get the admin working this approval (i.e., whichever admin _is_logged_in())
		$row_data['reviewing_admin'] = $this->data['username'];
			
		// if the user has been approved, update their entry in the 'accounts' table 
		switch ($post_data['approve_deny']) {
			case 'approve':
				$row_data['user_access_level'] = $post_data['user_access_level'];							
				// update the account with approval info
				// row_data has reviewing_admin, reviewed_date, user_access_leve, for user id=post_data['id']
				$result = $this->Theatre_model->update_account_access($post_data['id'], $row_data);
				$approved = TRUE;
			break;
			
			case 'deny':  // mark the user for deletion
				//echo "Access DENIED for user id: [".$row_data['id']."] Level Req: ".$row_data['user_access_level'];
				$row_data['user_access_level'] = 'denied';
				$result = $this->Theatre_model->update_account_access($post_data['id'], $row_data);
	
			break;
			
			default: // nada *TODO* error message
			break;
		} 	
		
		if ($result) {
			
			$update = $this->Theatre_model->get_account_data_for($post_data['id']);
			$update['new_pend_count'] = $this->Theatre_model->get_num_accounts('pending');

			if ($approved) { // send the activation code to this newly approved user
				$return_string = $this->_email_activation_code($update);
			} else { // $approved still false, no joy for this user
				$this->_email_regrets($update);
				// ** now delete the user (after you get the update array vals set, you're free to delete)
				$this->Theatre_model->delete_user_account($post_data['id']);
			}
			// send the info to the UI for the reviewing_admin
			echo json_encode($update);
			
		} else {
			$update_fail = array("id" => $post_data['id'],
								 "error_message" => "Failed to Update database.");
			// send the error message to the UI for the reviewing_admin					 
			echo json_encode($update_fail);
		}
	}
	
	/* **************************************************************************
	 * Name: 		update_accounts()
	 * Input:		serialized form data (account info)
	 * Output:		Message to reviewing_admin about the update
	 * 			
	 * Description: Call-back function for jQuery click event that returns
	 * 				a text message to the reviewing_admin in the admin_dashboard
	 * 				based on whether the database update was successful 
	 * 				For the most part, almost equivalent to approve_accounts
	 * 				Probably could make it more modular, depending on the
	 * 				id or info about which page or button was referring
	 * 				(e.g., use approve/update/or/undo as a parameter instead
	 * 				 of 3 different functions...)
	 ************************************************************************** */
	function update_accounts() {
	
		if ($this->data['access_level']!='administrator') { // user does not have access
			echo "Sorry, you do not have access to this section of Theatre-Finder";
			
		} else {
			// init $result to false
			$result = FALSE; // or 0
			// get serialized form data, parse it
			$var_list = explode('&', $this->input->post('acct_data'));
			for ($i=0; $i < count($var_list); $i++) {
 	  	 		$key_val_pair = split('=', $var_list[$i]);
				// $post_data['id'], $post_data['approve_deny'], $post_data['user_access_level']
   		 		$post_data[$key_val_pair[0]] = $key_val_pair[1];
			}
		
			if (isset($post_data['delete_option'])) { // need to delete this user
				// extra check (prob not necessary, but anyway)
				if ($post_data['delete_option']==='delete') {
					// delete the user
					$row_data['user_access_level'] = 'DELETED';
					$result = $this->Theatre_model->update_account_access($post_data['id'], $row_data);
	
					// set up the return data to match the return data for a deleted user
				}
			
			} else { // update the user to their new access level
		
				// Now set up the datetime timestamp for this edit
				$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
				$row_data['last_reviewed_date'] = date("Y-m-d H:i:s", $timestamp_in_secs);
			
				// get the admin working this update (i.e., whichever admin _is_logged_in())
				$row_data['reviewing_admin'] = $this->data['username'];			
			
				// set up the new access_level for this user
				$row_data['user_access_level'] = $post_data['user_access_level'];	
					
				// update the account with approval info
				// row_data has reviewing_admin, reviewed_date, user_access_leve, for user id=post_data['id']
				$result = $this->Theatre_model->update_account_access($post_data['id'], $row_data);
			}
		
			// Build info on user-update transaction to reviewing admin (in admin_update_accts.php view)
			if ($result) {
				// get the user data for this update
				$update = $this->Theatre_model->get_account_data_for($post_data['id']);
				// get the number of accounts now left in the current access page (e.g., authors, editors)
				$update['new_count'] = $this->Theatre_model->get_num_accounts($post_data['current_access']);
			
				if ($update['user_access_level']==='DELETED') {
					// do some cleanup - if this user is being DELETED, delete its entry 
					//AFTER you get the user id data for the ajax return msg, you're free to delete
					$this->Theatre_model->delete_user_account($post_data['id']);
				}
				// Finally, send the data back
				echo json_encode($update);
			} else {
				$update_fail = array("id" => $post_data['id'],
								 "error_message" => "Failed to Update database.");
				// send the error message to the UI for the reviewing_admin					 
				echo json_encode($update_fail);
			}
		}
	}
	
	/* **************************************************************************
	 * Name: 		undo_update()
	 * Input:		serialized form data (account info)
	 * Output:		Message to reviewing_admin about the undo
	 * 			
	 * Description: Call-back function for jQuery click event that returns
	 * 				a text message to the reviewing_admin in the admin_dashboard
	 * 				based on whether the database update was successful 
	 * 				For the most part, almost equivalent to update_accounts
	 * 				Probably could make it more modular, depending on the
	 * 				id or info about which page or button was referring
	 * 				(e.g., use approve/update/or/undo as a parameter instead
	 * 				 of 3 different functions...)
	 ************************************************************************** */
	function undo_update() {
		
		$var_list = explode('&', $this->input->post('acct_data'));
		
		for ($i=0; $i < count($var_list); $i++) {
    		$key_val_pair = split('=', $var_list[$i]);
			// $post_data['id'], $post_data['approve_deny'], $post_data['user_access_level']
    		$post_data[$key_val_pair[0]] = $key_val_pair[1];
		}
		
	 	// undo the new access level, only pay attention to the 'current_access' posted input
		// Set up the datetime timestamp for this edit
		$timestamp_in_secs = $_SERVER['REQUEST_TIME'];
		$row_data['last_reviewed_date'] = date("Y-m-d H:i:s", $timestamp_in_secs);
			
		// get the admin working this update (i.e., whichever admin _is_logged_in())
		$row_data['reviewing_admin'] = $this->data['username'];			
			
		// reset the access level whatever *was* 'current' for this user
		$row_data['user_access_level'] = $post_data['current_access'];	
				
		// update the account with approval info
		// row_data has reviewing_admin, reviewed_date, user_access_leve, for user id=post_data['id']
		$result = $this->Theatre_model->update_account_access($post_data['id'], $row_data);
		
		// Build info on user-update transaction to reviewing admin (in admin_update_accts.php view)
		if ($result) {
			
			$update = $this->Theatre_model->get_account_data_for($post_data['id']);
			// get the number of accounts now left in the current access page (e.g., authors, editors)
			$update['new_count'] = $this->Theatre_model->get_num_accounts($post_data['current_access']);
			echo json_encode($update);
		} else {
			$update_fail = array("id" => $post_data['id'],
								 "error_message" => "Failed to Update database.");
			// send the error message to the UI for the reviewing_admin					 
			echo json_encode($update_fail);
		}
	}
	
	/* ***********************************************************
	 * Name:		change_password_form()
	 * Input:		None
	 * Output:		None
	 * 
	 * Description:	Form for updating/changing password (ADMIN option)
	 * 				For users with other access levels,
	 * 				we use a similar form (only heading is different)
	 * 				Note: could make a conditional to check user_access_level
	 * 				and change heading based on that versus having
	 * 				two of 'same' form info
	 * 				Compare with password_form() function 
	 * 				(for author/editor level users)
	 * *********************************************************** */
	function change_password_form() {
		
		$this->data['title'] = "Theatre finder: Update Password";
		$this->data['body_id'] = '<body id="admin">';
		
		// if the user is an admin, build the heading, else redirect to the right form
		if ($this->data['access_level']==='administrator') {
			$this->data['heading'] = "Administrator Options";
		} else {
			redirect('theatre_ctrl/password_form');
		}
		
		// get pending count for sidebar <li> info
		$this->data['pend_count'] = $this->Theatre_model->get_num_accounts('pending');		
		// get user data
		$this->data['user'] = $this->Theatre_model->get_account_by_username($this->data['username']); 
		
		$this->render();
	}
	
	/* **************************************************************************
	 * Name: 		change_admin_password()
	 * Input:		
	 * Output:		
	 * 			
	 * Description: Changes the password for users with administrator access.
	 * 				If the form has some validation errors (see config/form_validation.php),
	 * 				it outputs them and has user try again.
	 * 				If form input is successful, updates the account password.
	 * 				
	 * 				Could make it more modular by having the same function
	 * 				update either admin-level user passwords
	 * 				or users with any level of access.  But because the view
	 * 				for this function is tied to the administrator options 
	 * 				pages, it's simpler/easier to have one password-change function
	 * 				for admins and one for other levels of users.
	 ************************************************************************** */
	function change_admin_password() {
		
		// trying out a better template than what i'm using in libraries/MY_Controller.php
		$this->load->library('template');
		// init $result to false
		$result = FALSE;
		
		if ($this->form_validation->run()) { // form input is good
			
			$user_id = $this->input->post('user_id');
			$md5encoded_passwd = md5($this->input->post('password'));
			// update database
			$return = $this->Theatre_model->change_password($user_id, $md5encoded_passwd);
			
			if ($return) { // password successfully changed
				$this->data['message'] = "Your password has been changed.";
			} else {
				$this->data['message'] = "There's been a database error.  Please try to change your password "
										."again by following the 'Change Password' link on the left.</p>"
										."<p>If you still encounter problems, please contact Theatre-Finder. Thank you.";
			}	 
			
			$this->data['title'] = 'Theatre Finder | Password Update';
			$this->data['body_id'] = '<body id="admin">';
			$this->data['heading'] = "Administrator Options";
		
			$this->data['pend_count'] = $this->Theatre_model->get_num_accounts('pending');
			// theatre_ctrl username
			$this->data['user'] = $this->Theatre_model->get_account_by_username($this->data['username']); 
		
			$this->template->load('layouts/main_layout', 'theatre_ctrl/password_changed', $this->data);
			
		} else { // form input not valid -- 
				 // So, set up all the page data for the change_password form again
			$this->data['title'] = 'Theatre Finder | Change Password';
			$this->data['body_id'] = '<body id="admin">';
			$this->data['heading'] = "Administrator Options";
		
			$this->data['pend_count'] = $this->Theatre_model->get_num_accounts('pending');
		
			// theatre_ctrl username
			$this->data['user'] = $this->Theatre_model->get_account_by_username($this->data['username']); 
		
			// Slightly different template format than the $this->render() from MY_Controller
			// Using to ensure that the CI Form Validation Errors are working properly
			// This may work better for the entire app - will look at changing (emb/07/05/2010)
			$this->template->load('layouts/main_layout', 'theatre_ctrl/change_password_form', $this->data);
		}
	}
	
	/* ***********************************************************
	 * Name:		password_form()
	 * Input:		None
	 * Output:		None
	 * 
	 * Description:	Form for updating/changing password (NON-admin option)
	 * 				For users with other access levels,
	 * 				we use a similar form (only heading is different)
	 * 				Note: could make a conditional to check user_access_level
	 * 				and change heading based on that versus having
	 * 				two of 'same' form info.
	 * 				cf. change_password_form() (admin-level function)
	 * *********************************************************** */
	function password_form() {
		
		$this->data['title'] = "Theatre finder: Update Password";
		
		if ($this->data['access_level']==='administrator') { // in case the admin got here somehow
			redirect('theatre_ctrl/change_password_form');   // redirect them to the right place
			
		} else { // regular user
			$this->data['heading'] = "Update Password";
			$this->data['body_id'] = '<body id="admin">';
		
			// theatre_ctrl username
			$this->data['user'] = $this->Theatre_model->get_account_by_username($this->data['username']); 
		
			$this->render();
		}
	}
	
	/* **************************************************************************
	 * Name: 		change_password()
	 * Input:		
	 * Output:		
	 * 			
	 * Description: Changes the password for users with author/editor access.
	 * 				If the form has some validation errors (see config/form_validation.php),
	 * 				it outputs them and has user try again.
	 * 				If form input is successful, updates the account password.
	 * 				
	 * 				Could make it more modular by having the same function
	 * 				update either admin-level user passwords
	 * 				or users with any level of access.  But because the view
	 * 				for this function is tied to the administrator options 
	 * 				pages, it's simpler/easier to have one password-change function
	 * 				for admins and one for other levels of users.
	 ************************************************************************** */
	function change_password() {
		
		// trying out a better template than what i'm using in libraries/MY_Controller.php
		$this->load->library('template');
		
		if ($this->form_validation->run()) { // form input is valid
			
			$user_id = $this->input->post('user_id');
			$md5encoded_passwd = md5($this->input->post('password'));
			$return = $this->Theatre_model->change_password($user_id, $md5encoded_passwd);
			
			if ($return) { // password successfully changed
				$this->data['message'] = "Your password has been changed.";
			} else {
				$this->data['message'] = "There's been a database error.  Please try to change your password "
										."again by following the 'Change Password' link on the left.</p>"
										."<p>If you still encounter problems, please contact Theatre-Finder. Thank you.";
			}	 
			
			$this->data['title'] = 'Theatre Finder | Update Password';
			$this->data['heading'] = "Update Password";
			$this->data['body_id'] = '<body id="admin">';
			
			// get user data
			$this->data['user'] = $this->Theatre_model->get_account_by_username($this->data['username']); 

			$this->template->load('layouts/main_layout', 'theatre_ctrl/reg_password_changed', $this->data);
			
		} else { // form input not good -- 
				 // So, set up all the page data for the change_password form again
			$this->data['title'] = 'Theatre Finder | Update Password';
			
			$this->data['heading'] = "Update Password";
			$this->data['body_id'] = '<body id="admin">';
		
			// get user data
			$this->data['user'] = $this->Theatre_model->get_account_by_username($this->data['username']); 

			// Slightly different template format than the $this->render() from MY_Controller
			// Using to ensure that the CI Form Validation Errors are working properly
			$this->template->load('layouts/main_layout', 'theatre_ctrl/password_form', $this->data);
		}
	}
	
	
	
	/* **************************************************************************
	 * Name: _form_formats_visitor()
	 * Input:	array of data (e.g., $_POST, $theatre)
	 * Output:	array of form elements
	 * 
	 * Description: Sets up form vars for the visitor_info() form,
	 * 				whether it's an "empty"/add form or form
	 * 				filled with $_POST vars or $theatre attributes
	 ************************************************************************** */
	function _form_formats_visitor($data_array) {
		//init return form array
		$form_data = array();
		
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
		// If the data array's ['periods'] key is set, use it; else, option='Baroque'
		$optSelected = isset($data_array['periods']) ? $data_array['periods'] : $periodOpts['Baroque'];
		
		// periods pull-down
		$form_data['periodMenu'] = form_dropdown('periods', $periodOpts, $optSelected, 'id="periods"');
		
		// IF the period_rep is 'Other', we need a text input box
		// Else, a form drop_down
		// add the types fields
		if(strcmp($optSelected,'Other')==0) { // if they're equal, this will = 0}
			$typeSelected = isset($data_array['sub_type']) ? stripslashes($data_array['sub_type']) : ''; // this will be an 'Other' type
			$typeInput = array(
              'name' 	   => 'sub_type',
              'id'         => 'sub_type',
              'value'      => $typeSelected,
              'maxlength'  => '64',
              'size'       => '20',
			  'style'	   => 'margin:3px'
            );
			$form_data['sub_type'] = form_input($typeInput);
			
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
			// get theatre type that should be selected from this $data_array ($_POST['sub_type'] or $types[0])
			$typeSelected = isset($data_array['sub_type']) ? stripslashes($data_array['sub_type']) : $types[0];
			$form_data['sub_type'] = form_dropdown('sub_type', $typeOpts, $typeSelected, 'id="sub_type"');
		}
		
		// entry author/editor data 
		$author =	isset($data_array['entry_author']) ?
						stripslashes($data_array['entry_author']) : 'Needs Full Entry';
						
		$authorInput = array(
              'name' 	   => 'entry_author',
              'id'         => 'entry_author',
              'value'      => $author,
              'maxlength'  => '64',
              'size'       => '20',
			  'style'	   => 'margin:3px'
            );
		$form_data['authorInput'] = form_input($authorInput);
		
		$editor =	isset($data_array['entry_editor']) ?
						stripslashes($data_array['entry_editor']) : 'Needs editing';
												
		$editorInput = array(
              'name' 	   => 'entry_editor',
              'id'         => 'entry_editor',
              'value'      => $editor,
              'maxlength'  => '64',
              'size'       => '20',
			  'style'	   => 'margin:3px'
            );
		$form_data['editorInput'] = form_input($editorInput);
						
		// Set the entry_status array (3 options, *not* databased)
		$status = array('awaiting edits' 	=> 'awaiting edits',
						'awaiting approval' => 'awaiting approval',
						'approved' 			=> 'approved');
		// check what status is set in the database (or set the default, if new)
		$status_selected = isset($data_array['entry_status']) ? 
							$data_array['entry_status'] : $status['awaiting edits'];
		
		$form_data['status_menu'] = form_dropdown('entry_status', $status, $status_selected, 'id="entry_status"');
		
		// main theatre data
		$theatre_name =	isset($data_array['theatre_name']) ?
						stripslashes($data_array['theatre_name']) : '';

		$theatreInput = array(
              'name' 	   => 'theatre_name',
              'id'         => 'theatre_name',
              'value'      => $theatre_name,
              'maxlength'  => '128',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$form_data['nameInput'] = form_input($theatreInput);

		$country = isset($data_array['country_name']) ?
						stripslashes($data_array['country_name']) : '';
		
		$countryInput = array(
              'name' 	   => 'country_name',
              'id'         => 'country_name',
              'value'      => $country,
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$form_data['countryInput'] = form_input($countryInput);

		$region = isset($data_array['region']) ?
						stripslashes($data_array['region']) : '';
		$regionInput = array(
              'name' 	   => 'region',
              'id'         => 'region',
              'value'      => $region,
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$form_data['regionInput'] = form_input($regionInput);
		
		$city = isset($data_array['city']) ?
						stripslashes($data_array['city']) : '';
		$cityInput = array(
              'name' 	   => 'city',
              'id'         => 'city',
              'value'      => $city,
              'maxlength'  => '64',
              'size'       => '24',
			  'style'	   => 'margin:3px'
            );
		$form_data['cityInput'] = form_input($cityInput);
		
		// ** NOTE: moved from scholarly_details table to visitor_info
		// ** at Frank's request 07/13/2010 (emb)
		$check_notes = isset($data_array['running_notes']) ?
						stripslashes($data_array['running_notes']) : '';
		$running_notes = array(
			'name'		=> 'running_notes',
			'id'		=> 'running_notes',
			'value'		=> $check_notes,
			);
		$form_data['running_notes'] = form_textarea($running_notes);

		$check_basic = isset($data_array['basic_description']) ?
						stripslashes($data_array['basic_description']) : '';
		$basic_desc = array(
			'name'		=> 'basic_description',
			'id'		=> 'basic_description',
			'value'		=> $check_basic,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['basic_description'] = form_textarea($basic_desc);
		
		$check_visit = isset($data_array['visiting_info']) ?
						stripslashes($data_array['visiting_info']) : '';
		$visiting_info = array(
			'name'		=> 'visiting_info',
			'id'		=> 'visiting_info',
			'value'		=> $check_visit,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['visiting_info'] = form_textarea($visiting_info);
		
		$check_related = isset($data_array['related_sites']) ?
						stripslashes($data_array['related_sites']) : '';
		$related_sites = array(
			'name'		=> 'related_sites',
			'id'		=> 'related_sites',
			'value'		=> $check_related,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['related_sites'] = form_textarea($related_sites);
				
		$est_earliest = isset($data_array['est_earliest']) ? $data_array['est_earliest'] : 0;
		$est_earliestInput = array(
              'name' 	   => 'est_earliest',
              'id'         => 'est_earliest',
              'value'      => $est_earliest,
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:3px'
            );
		$form_data['est_earliest'] = form_input($est_earliestInput);
		
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
		// If the earliestdate_bce_ce radio button val is not set, default='CE'
		$earliestdate_bce_ce = isset($data_array['earliestdate_bce_ce']) ?
								$data_array['earliestdate_bce_ce'] : 'CE';
		// check on the bce/ce radio button check/not checked	
		if ($earliestdate_bce_ce == 'CE') {
			$earliest_ce['checked'] = TRUE;
		} else {
			$earliest_bce['checked'] = TRUE;
		}		
		$form_data['earliest_bce'] = form_radio($earliest_bce);
		$form_data['earliest_ce'] = form_radio($earliest_ce);
		
		$est_latest = isset($data_array['est_latest']) ? $data_array['est_latest'] : 0;
		$est_latestInput = array(
              'name' 	   => 'est_latest',
              'id'         => 'est_latest',
              'value'      => $est_latest,
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:10px'
            );
		$form_data['est_latest'] = form_input($est_latestInput);
		
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
		// If the earliestdate_bce_ce radio button val is not set, default='CE'	
		$latestdate_bce_ce = isset($data_array['latestdate_bce_ce']) ? 
						$data_array['latestdate_bce_ce'] : 'CE';
		// check on the bce/ce radio button check/not checked
		if ( $latestdate_bce_ce == 'CE') {
			$latest_ce['checked'] = TRUE;
		} else {
			$latest_bce['checked'] = TRUE;
		}		
		$form_data['latest_bce'] = form_radio($latest_bce);
		$form_data['latest_ce'] = form_radio($latest_ce);
		
		// Auditorium date
		$aud_date = isset($data_array['auditorium_date']) ? $data_array['auditorium_date'] : 0;
		$aud_date_input = array(
              'name' 	   => 'auditorium_date',
              'id'         => 'auditorium_date',
              'value'      => $aud_date,
              'maxlength'  => '4',
              'size'       => '4',
			  'style'	   => 'margin:10px'
            );
		$form_data['auditorium_date'] = form_input($aud_date_input);
		
		// build the lat/long form inputs = all blank for a new form
		$lat_degrees = isset($data_array['lat_degrees']) ? $data_array['lat_degrees'] : 0;
		$lat_degrees_input = array(
              'name' 	   => 'lat_degrees',
              'id'         => 'lat_degrees',
              'value'      => $lat_degrees,
              'maxlength'  => '3',
              'size'       => '3',
			  'style'	   => 'margin:1px'
            );
		$form_data['lat_degrees'] = form_input($lat_degrees_input);
		
		$lng_degrees = isset($data_array['lng_degrees']) ? $data_array['lng_degrees'] : 0;
		$lng_degrees_input = array(
              'name' 	   => 'lng_degrees',
              'id'         => 'lng_degrees',
              'value'      => $lng_degrees,
              'maxlength'  => '4',
              'size'       => '3',
			  'style'	   => 'margin:1px'
            );
		$form_data['lng_degrees'] = form_input($lng_degrees_input);
		
		$lat_mins = isset($data_array['lat_mins']) ? $data_array['lat_mins'] : 0;
		$lat_mins_input = array(
              'name' 	   => 'lat_mins',
              'id'         => 'lat_mins',
              'value'      => $lat_mins,
              'maxlength'  => '2',
              'size'       => '2',
			  'style'	   => 'margin:1px'
            );
		$form_data['lat_mins'] = form_input($lat_mins_input);
		
		$lng_mins = isset($data_array['lng_mins']) ? $data_array['lng_mins'] : 0;
		$lng_mins_input = array(
              'name' 	   => 'lng_mins',
              'id'         => 'lng_mins',
              'value'      => $lng_mins,
              'maxlength'  => '2',
              'size'       => '2',
			  'style'	   => 'margin:1px'
            );
		$form_data['lng_mins'] = form_input($lng_mins_input);
		
		$lat_secs = isset($data_array['lat_secs']) ? $data_array['lat_secs'] : 0.00;
		$lat_secs_input = array(
              'name' 	   => 'lat_secs',
              'id'         => 'lat_secs',
              'value'      => $lat_secs,
              'maxlength'  => '4',
              'size'       => '2',
			  'style'	   => 'margin:1px'
            );
		$form_data['lat_secs'] = form_input($lat_secs_input);
		
		$lng_secs = isset($data_array['lng_secs']) ? $data_array['lng_secs'] : 0.00;
		$lng_secs_input = array(
              'name' 	   => 'lng_secs',
              'id'         => 'lng_secs',
              'value'      => $lng_secs,
              'maxlength'  => '4',
              'size'       => '2',
			  'style'	   => 'margin:1px'
            );
		$form_data['lng_secs'] = form_input($lng_secs_input);
		
		// lat/lng hemisphere specs: N, S, E, W
		$north = array(
			'name'        => 'lat_hemisphere',
    		'id'          => 'lat_hemisphere',
    		'value'       => 'N',
    		'style'       => 'margin:3px',
			);
		$south = array(
			'name'        => 'lat_hemisphere',
    		'id'          => 'lat_hemisphere',
    		'value'       => 'S',
    		'style'       => 'margin:3px',
			);
		// If the lat_hemisphere radio button val is not set, default='N'	
		$lat_hemisphere = isset($data_array['lat_hemisphere']) ? 
						$data_array['lat_hemisphere'] : 'N';
		// check whether the lat_hem radio button is checked or not
		if ($lat_hemisphere == 'N') {
			$north['checked'] = TRUE;
		} else {
			$south['checked'] = TRUE;
		}	
		$form_data['north_radio'] = form_radio($north);
		$form_data['south_radio'] = form_radio($south);
		
		$east = array(
			'name'        => 'lng_hemisphere',
    		'id'          => 'lng_hemisphere',
    		'value'       => 'E',
    		'style'       => 'margin:3px',
			);
		$west = array(
			'name'        => 'lng_hemisphere',
    		'id'          => 'lng_hemisphere',
    		'value'       => 'W',
    		'style'       => 'margin:3px',
			);
		
		// If the lat_hemisphere radio button val is not set, default='E'	
		$lng_hemisphere = isset($data_array['lng_hemisphere']) ? 
						$data_array['lng_hemisphere'] : 'E';
		// check whether the lat_hem radio button is checked or not
		if ($lng_hemisphere == 'E') {
			$east['checked'] = TRUE;
		} else {
			$west['checked'] = TRUE;
		}	
		$form_data['east_radio'] = form_radio($east);
		$form_data['west_radio'] = form_radio($west);
		
		// send the form data back..
		return $form_data;
	}
	
	/* **************************************************************************
	 * Name: _form_formats_scholarly()
	 * Input:	array of data (e.g., $_POST, $theatre)
	 * Output:	array of form elements
	 * 
	 * Description: Sets up form vars for the scholarly_details form,
	 * 				whether it's an "empty"/add form or form
	 * 				filled with $_POST vars or $theatre attributes
	 ************************************************************************** */
	function _form_formats_scholarly($data_array) {
		//init return form array
		$form_data = array();
		
		// removed running_notes from scholarly_details table
		// at Frank's request, 7/13/2010 (emb) - moved to visitor_info
		/*$check_notes = isset($data_array['running_notes']) ?
						stripslashes($data_array['running_notes']) : '';
		$running_notes = array(
			'name'		=> 'running_notes',
			'id'		=> 'running_notes',
			'value'		=> $check_notes,
			);
		$form_data['running_notes'] = form_textarea($running_notes);
		*/
		$check_general = isset($data_array['general_history']) ?
						stripslashes($data_array['general_history']) : '';
		$general_hist = array(
			'name'		=> 'general_history',
			'id'		=> 'general_history',
			'value'		=> $check_general,
			);
		$form_data['general_history'] = form_textarea($general_hist);
		
		$check_prevlist = isset($data_array['previous_theatres_onsite']) ?
						stripslashes($data_array['previous_theatres_onsite']) : '';
		$prev_theatres = array(
			'name'		=> 'previous_theatres_onsite',
			'id'		=> 'previous_theatres_onsite',
			'value'		=> $check_prevlist,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['previous_theatres_onsite'] = form_textarea($prev_theatres);
		
		$check_alts_renovs = isset($data_array['alts_renovs_list']) ?
						stripslashes($data_array['alts_renovs_list']) : '';
		$desc_alts = array(
			'name'		=> 'alts_renovs_list',
			'id'		=> 'alts_renovs_list',
			'value'		=> $check_alts_renovs,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['alts_renovs_list'] = form_textarea($desc_alts);
		
		$check_desc_current = isset($data_array['desc_current']) ?
						stripslashes($data_array['desc_current']) : '';
		$desc_current = array(
			'name'		=> 'desc_current',
			'id'		=> 'desc_current',
			'value'		=> $check_desc_current,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['desc_current'] = form_textarea($desc_current);
		
		$check_measures = isset($data_array['measurements']) ?
						stripslashes($data_array['measurements']) : '';
		$desc_measures = array(
			'name'		=> 'measurements',
			'id'		=> 'measurements',
			'value'		=> $check_measures,
			'rows'		=> '10',
			'cols'		=> '80'
			);
		$form_data['measurements'] = form_textarea($desc_measures);
		
		$check_bib = isset($data_array['biblio']) ?
						stripslashes($data_array['biblio']) : '';
		$biblio = array(
			'name'		=> 'biblio',
			'id'		=> 'biblio',
			'value'		=> $check_bib,
			);
		$form_data['biblio'] = form_textarea($biblio);
		
		return $form_data;
	}
	
	function _form_formats_biblio($data_array) {
		//init return form array
		$form_data = array();
		
		$check_bib = isset($data_array['biblio']) ?
						stripslashes($data_array['biblio']) : '';
		$biblio = array(
			'name'		=> 'biblio',
			'id'		=> 'biblio',
			'value'		=> $check_bib,
			);
		$form_data['biblio'] = form_textarea($biblio);
		return $form_data;
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
			}	else { // dates are equal, but across bce->ce
				$date = $est_earliest." ".$earliest_bce_ce." - ".$est_latest." ".$latest_bce_ce;
			}
		} else { // they're not equal dates - give the range
			if (($earliest_bce_ce===$latest_bce_ce) && ($earliest_bce_ce==='CE')) {
				$date = $est_earliest." - ".$est_latest;
			} else {
				$date = $date = $est_earliest." - ".$est_latest." ".$latest_bce_ce;
			}
		}
		
		return $date;
	}
	/* **************************************************************************
	 * Name: 	_init_ckeditors()
	 * Input:	associative array of ckeditor var-names=> form element ids
	 * Output:	creates the calling function's ckeditor wrappers
	 * 			from the input array 
	 * 
	 * Description: Builds the ckeditor textarea wrappers for each
	 * 				textarea in a theatre entry form
	 * 				Note: *TODO: ultimately can build this as a CI plug-in for use 
	 * 				by all the controllers in this app.
	 ************************************************************************** */
	function _init_ckeditors($ckeditor_array) {
								 
		foreach($ckeditor_array as $ckeditor_id=>$form_el_id) {
			$this->data[$ckeditor_id] = array(		
			//ID of the textarea that will be replaced
			'id' 	=> 	$form_el_id,
			'path'	=>	'javascript/ckeditor',

			'customConfig' => 'customConfig/theatrefinder_ckeditor_config.js',
		);
			
		}
	}

	function _get_new_form_guidelines($which_selectors) {
		
		switch ($which_selectors) {
			case "new_visitor_info_form":
				
			break;
			
			case "new_scholarly_details_form":
				$selector_list = 'running_notes, general_history, previous_theatres_onsite, alts_renovs_list, desc_current, measurements';
				$defaultform_guidelines = $this->Theatre_model->get_default_form_entries($selector_list);
			break;
			
			default:
			//do nothing
			break;
		}
	}
	
	function _prep_result_phrase($field, $search_text) {
		$phrase = '';
		$search_opr = " = ";
		switch ($field) {
			case "period_rep":
				$field = "Period ";
			break;
			
			case "country_name":
				$field = "Country ";
			break;
			
			case "city":
				$field = "City";
			break;
			
			case 'all':
				$field = "Period, or Country, or City, or Theatre name ";
				$search_opr = " like ";
				$search_text = '%'.$search_text.'%';
			break;
			
			case 'theatre_name':
				$field = "Theatre name ";
				$search_opr = " like ";
				$search_text = '%'.$search_text.'%';
			break;
			
			case 'theatre_alias';
				$field = "Theatre name (alias name)";
				$search_opr = " like ";
				$search_text = 	'%'.$search_text.'%';			
			break;
			
			case 'dates_range_bce':
				$field = "Dates (BCE) ";
				$search_opr = " between ";
			break;
			
			case 'dates_range_ce':
				$field = "Dates (CE) ";
				$search_opr = " between ";
			break;
			
			case 'date_bce_exact':
				$field = "Date (BCE) ";
				$search_opr = " = ";
			break;
			
			case 'date_ce_exact':
				$field = "Date (CE) ";
				$search_opr = " = ";
			break;
			
			case 'date_house_exact':
				$field = "House Date";
				$search_opr = " = ";
			break;
			
			case 'date_house_range':
				$field = "House Date";
				$search_opr = " between ";
			break;
			
			default:
				// nada
			break;
			
		}
		$phrase = $field.$search_opr."'".$search_text."'";
		return $phrase;
	}
	
	/* *************************************************************************
	 * Name:		test_email()
	 * Input:		NONE
	 * Output:		email test message to emb
	 * Dependency:	1) email.php config file (in config dir)
	 * 				2) view file called test_email under views/theatre_ctrl
	 * 
	 * Description:	Sends test email to emb.  
	 * 				Testing the _email_activation_code() function.
	 ************************************************************************* */
	function test_email() {
		$this->data['title'] = 'Theatre Finder | EMAIL TEST';
		$this->data['body_id'] = '<body id="list">';
		
		$user = array("email_address" => "elizabeth.bonsignore@gmail.com",
					  "user_access_level" => "admin",
					  "activation_code" => "%%%%%activate%%%%%");
		$this->data['message'] = $this->_email_activation_code($user);
		$this->render();
	}
	
	function _email_activation_code($new_user) {
		
		$this->load->library('email');
		// necessary to have email start with newline (recommended...)
		$this->email->set_newline("\r\n");
		
		$this->email->from('theatre-finder@umd.edu', 'Theatre-Finder Editor-in-Chief');
		$this->email->to($new_user['email_address']);
		$this->email->subject('Theatre-Finder Registration Confirmation');
		$welcome_message = "\n\nYour request for a Theatre-Finder account has been approved. "
						   ." You have been approved as a Theatre-Finder ".$new_user['user_access_level']
						   .". \nPlease click this activation code link to confirm your registration and"
						   ." activate your account."
						   ."\nThank you for being a Theatre-Finder contributor!\n"
						   .anchor('login/confirm_registration/'.$new_user['activation_code'], 'Confirm Registration');
		$this->email->message($welcome_message);
		
		$this->email->send();
		/* if ($this->email->send()) {
			return "Success"; // "Good email! Hoooooray!: Active Code: ".$new_user['activation_code']." URL:".anchor("theatre_ctrl/activate_user", "Confirm");
			
		} else {
			return "ERROR! Check your email!!".show_error($this->email->print_debugger());
		} */
		
	}
	
	function _email_regrets($denied_user) {
		$this->load->library('email');
		// necessary to have email start with newline (recommended...)
		$this->email->set_newline("\r\n");
		
		$this->email->from('theatre-finder@umd.edu', 'Theatre-Finder Editor-in-Chief');
		$this->email->to($denied_user['email_address']);
		$this->email->subject('Theatre-Finder Registration Regrets');
		
		$regrets = "\n\nThank you for your interest in the Theatre-Finder project."
					."\nUnfortunately, your request for a Theatre-Finder account has NOT been approved. "
					."\nIf you feel that your registration should be re-considered, please re-submit with"
					." more details in your application CV/statement.  \nThank you again the Theatre-Finder Editorial Board!";
		$this->email->message($regrets);
		
		$this->email->send();
		
	}
	
		 
	
//////////////////// utility/testing functions + old parking lot ///////////////
	
	/* get char set of database */
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

	function ck_test() {
		$this->load->view('ckeditor', $this->data);
	}


}
