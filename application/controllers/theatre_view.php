<?php
	
class Theatre_view extends TheatreFinder_Controller {
	
	/* ***********************************************************
	 * Name:		__construct() theatre_view constructor
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
		
		// load library functions
		// now loaded in autoload.php (under config/ dir) 02/04/2010
		// **TESTING** removed from autoload.php 02/17/2010, calling here
		//$this->load->library('form_validation');
		$this->load->library('MY_Xmlrpc');
		
		//$this->load->library('MyMultiByte');
		// specific tf functions
		//$this->load->library('MY_TF_functions');
			
		// load helper functions
		// **Could be loaded in autoload.php (under config/ dir) 02/04/2010
		// removed from autoload.php 02/17/2010, calling here
		$this->load->helper(array('url','form'));
		$this->load->helper('international');
		$this->load->helper('ckeditor');
		
		// at start, ensure that all scripts+styles are added to 'views'
		$this->add_css('all_css');
		$this->add_scripts('all_scripts');
			
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
			$theatres[$i]['Details'] = anchor('theatre_view/entry_visitor_info/'.$theatres[$i]['idData'], 'View Details');
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
		$this->render('visitors_only_layout');
		
	}
	
	function search_theatres() {
		
		$this->data['title'] = 'Theatre Finder | Search Results List';
		$this->data['body_id'] = '<body id="list">';
		
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
					// here will use an or_like versus
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
						} else {
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
				$all = 1; // set $all for call to count
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
				// First try Exact Match Search for City
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
				// search for City/Country_name (exact match)
				$search_terms = array($field=>$text);
				$theatres = $this->Theatre_model->get_theatres_where($search_terms);
				$this->data['numTheatres']=$this->Theatre_model->get_theatre_count($search_terms);
				$search_phrase = $this->_prep_result_phrase($field, $text);
				$this->data['search_phrase'] = $search_phrase;
				
		}
		
		if ($this->data['numTheatres']==0) { // if we have no results
			// render some sort of nice error message			
			$this->render('visitors_only_layout','no_results');
			
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
				$theatres[$i]['Details'] = anchor('theatre_view/entry_visitor_info/'.$theatres[$i]['idData'], 'View Details');
				
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
		$this->render('visitors_only_layout');
		}
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
		$this->render('visitors_only_layout');

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
		
		$this->render('visitors_only_layout');

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
		
		$this->render('visitors_only_layout');

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
			
			default:
			break;
			
		}
		$phrase = $field.$search_opr."'".$search_text."'";
		return $phrase;
	}
}
?>