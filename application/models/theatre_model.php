<?php
   	
	class Theatre_model extends Model {
	/*	
	 * Theatre Model - of the theatrefinder database
	 * Originally created by emb ~late October 2009,
	 * parsing from a *.csv file that Frank's students
	 * had created.  The original *.csv file
	 * was not normalized or well-formed, 
	 * so the original table was
	 * developed based on parsing and discussions with Frank.
	 * 
	 * The database was created using mysql commands,
	 * MySQL Query Browser (also SQuirreL and phpMyAdmin
	 * where convenient).
	 * It was originally created as part of a ZEND MVC
	 * php framework, but was migrated to CodeIgniter due
	 * to problems with migrating the database to peach
	 * (database dlls for ZEND did not seem to work, even
	 *  when coordinating efforts with gplord & dreside)
	 *  
	 *  Note that the model (and controller) functions
	 *  don't really follow a standard convention 
	 *  of camel case versus underscores(_)
	 *  The original names were mostly camel-case.
	 *  However, upon learning that most CI best-practice
	 *  guides recommend the use of underscore, changed
	 *  over gradually since ~feb/march2010
	 *  
	 * The database table, "theatres," 
	 * from the DB, "theatrefinder" (~Nov2009 - ORIGINAL)
	 		'theatre_name'		 => $theatre_name,
			'country_name' 		 => $country_name,
			'city'				 => $city,
			'period_rep'		 => $period_rep,
			'est_earliest'		 => $estEarliest,
			'earliestdate_bce_ce' => $earlyADBC,
			'est_latest'		 => $estLatest,
			'latestdate_bce_ce'	 => $lateADBC,	
	*/
	
	function Theatre_model() {
		
		// call the Model constructor
		parent::Model();	
		
		// We can specify the 'theatrefinder' group
		// for loading as an argument to this load->database() call:
		// $this->load->database('theatrefinder'); 
		// Or, we can also specify the database
		// by making it the 'active_group' in the database.php  config file.
		// Then the load->database() call does not need an argument (below).
		$this->load->database();		
	}
	
	/* ***********************************************************
	 * Name:		getTheatres()
	 * Input:		none
	 * 				
	 * Output:		All theatres in DB at time of function call
	 * 				
	 * Description:	returns the list of theatres for the main
	 * 				'theatre_ctrl' page where the entire database
	 * 				of theatres is returned. Ordered by Country, City
	 * *********************************************************** */
	function getTheatres(){
		// Whenever we initially get the Theatre listing,
		// we want to order by the last entry inserted, 
		// then by city (asc), country_name (asc)
		// Get type id from theatre_type table
		
		$select = "SELECT id, theatre_name, country_name, country_digraph, city, region, "
				  ."period_rep, sub_type, est_earliest, earliestdate_bce_ce, "
				  ."est_latest, latestdate_bce_ce "
				  ."FROM theatres "
				  ."ORDER BY country_name ASC, city ASC;";
				  
		$query=$this->db->query($select);

		if($query->num_rows()>0){ 
			// return result set as an associative array
			return $query->result_array();
		}
	}

	/* ***********************************************************
	 * Name:		get_theatres_where()
	 * Input:		$match_array == array of 1+ column=>value pairs
	 * 				to match (SELECT) against
	 * 	
	 * Output:		theatres that match the $match_array criteria
	 * 				
	 * Description:	Returns an assoc. array of theatres that match
	 * 				the $match_array criteria.  This is an *EXACT* match
	 * 				for each of the columns to run against the "WHERE" clause.
	 * 				Outputs the list order that is accepted for all
	 * 				other lists (ORDER BY Country, City)
	 * *********************************************************** */
	function get_theatres_where($match_array){

		$this->db->where($match_array);
		// Here, where() assumes an array of column=>value pairs:
		// 'city'=>'Vienna' or 'country_name'=>'Albania'
		// get from 'table'='theatres
		$this->db->order_by("country_name, city, theatre_name"); // order by country, city
		
		$query=$this->db->get('theatres');
		if($query->num_rows()>0){ 
			// return result set as an associative array
			return $query->result_array();
		}	
	}
	
	/* ***********************************************************
	 * Name:		search_terms_like()
	 * Input:		$match_array == array of 1+ column=>value pairs
	 * 				to SELECT against
	 * 
	 * Output:		multiple row data (assoc array) of theatres 
	 * 				that match the criteria specified by $match_array
	 * 				
	 * Description:	returns a list of theatres that match
	 * 				the $match_array criteria.
	 * 				* Unlike the get_theatres_where() function,
	 * 				* this one uses "LIKE" versus exact= match
	 * 				* Wraps the terms to search on with %-signs
	 * 				* i.e., SELECT x WHERE column LIKE %<search_term>%
	 * *********************************************************** */
	function search_terms_like($match_array) {
		
		$this->db->or_like($match_array);
		$this->db->order_by("country_name, city, theatre_name");
		$query=$this->db->get('theatres');
		
		if($query->num_rows()>0){ 
			// return result set as an associative array
			return $query->result_array();
		}
		
	}
	
	/* ***********************************************************
	 * Name:		get_theatres_by_city_alias()
	 * Input:		$city_alias name 
	 * 				
	 * Output:		Row data (assoc array) SELECTed by match on $city_alias
	 * 				
	 * Description:	returns the theatre data for all Visitor_Info
	 * 				page detail for specific theatre (by $id).
	 * 				Requires INNER JOIN 
	 * 				on the theatres.id=visitor_info.theatre_id
	 * *********************************************************** */
	function get_theatres_by_city_alias($city_alias) {
		
		$select = "SELECT c.city_id, a.city_id, c.city, a.city_alias, c.country_digraph, t.* "
				."FROM cities c, city_aliases a, theatres t "
				."WHERE (a.city_alias=? AND c.city_id=a.city_id AND c.city=t.city) "
				."ORDER BY t.country_name, t.city, t.theatre_name;";
		
		$result = $this->db->query($select, $city_alias);
		if ($result->num_rows()>0) {
			return $result->result_array();
		}
		
	}
	
	function get_theatres_by_cityalias_fuzzymatch($city_alias) {
		// '%' (LIKE wildcards added around the $city_alias)
		$city_alias = '%'.$city_alias.'%';
		
		$select = "SELECT c.city_id, a.city_id, c.city, a.city_alias, c.country_digraph, t.* "
				."FROM cities c, city_aliases a, theatres t "
				."WHERE (a.city_alias LIKE ? AND c.city_id=a.city_id AND c.city=t.city) "
				."ORDER BY t.country_name, t.city, t.theatre_name;";
		
		$result = $this->db->query($select, $city_alias);
		if ($result->num_rows()>0) {
			return $result->result_array();
		}
	}
	
	function get_theatres_by_theatre_alias($theatre_alias) {
		
		$theatre_alias = '%'.$theatre_alias.'%';
		
		$select = "SELECT t.* "
				  ."FROM theatres t, theatre_aliases ta "
				  ."WHERE (t.id=ta.theatre_id AND ta.theatre_alias LIKE ?) "
				  ."ORDER BY t.country_name, t.city, t.theatre_name;";
		
		$result = $this->db->query($select, $theatre_alias);
		if ($result->num_rows()>0) {
			return $result->result_array();
		}		  
		
	}
	
	/* ***********************************************************
	 * Name:		get_theatre()
	 * Input:		$id Key for specific theatre
	 * 				
	 * Output:		Row data (assoc array) SELECTed by $id
	 * 				
	 * Description:	returns the theatre data for all Visitor_Info
	 * 				page detail for specific theatre (by $id).
	 * 				Requires INNER JOIN 
	 * 				on the theatres.id=visitor_info.theatre_id
	 * *********************************************************** */
	function get_theatre($id){
		
		$select = "SELECT theatres.id, theatres.theatre_name, theatres.country_name, "
				  ."theatres.country_digraph, theatres.city, theatres.region, theatres.period_rep, "
				  ."theatres.sub_type, theatres.est_earliest, theatres.earliestdate_bce_ce, "
				  ."theatres.est_latest, theatres.latestdate_bce_ce, theatres.auditorium_date, "
				  ."theatres.entry_first_lister, theatres.entry_author, theatres.entry_editor, "
				  ."theatres.entry_date, theatres.entry_status, theatres.last_updated, theatres.rating, "
				  ."theatres.lat, theatres.lng, "
				  ."visitor_info.basic_description, "
				  ."visitor_info.visiting_info, "
				  ."visitor_info.related_sites, "
				  ."visitor_info.running_notes "
				  ."FROM theatres, visitor_info "
				  ."WHERE theatres.id=".$id
				  ." AND theatres.id=visitor_info.theatre_id;";
		$query = $this->db->query($select);

		if ($query->num_rows() > 0) {
			// return the result row  (as array)
			return $query->row_array();
			// return result as obj
			//return $query->row();
		}
	}
	
	/* ***********************************************************
	 * Name:		get_recentupdates()
	 * Input:		none
	 * 				
	 * Output:		9 rows of theatres table data
	 * 				
	 * Description:	Selects 9 theatres by the date that	
	 * 				they were recently updated/edited
	 * 				by contributors/editorial board
	 * 				(called in 'main' controller)
	 * ********************************************************** */
	function get_recentupdates() {
		$select = "SELECT id, theatre_name, country_name, "
				  ."city, est_earliest, earliestdate_bce_ce, "
				  ."est_latest, latestdate_bce_ce "
				  ."FROM theatres "
				  ."ORDER BY last_updated DESC LIMIT 9;";
				  
		$query = $this->db->query($select);

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				
				// return result set as an associative array
				return $query->result_array();
			}
		} 
		
	}
	
	/* ***********************************************************
	 * Name:		get_theatre_name()
	 * Input:		$id Key for specific theatre
	 * 				
	 * Output:		specific column (theatre_name) SELECTed by $id
	 * 				
	 * Description:	returns the theatre_name 
	 * 				for a specific theatre (by $id).
	 * 				
	 * ********************************************************** */
	function get_theatre_name($id) {
		
		$select = "SELECT theatres.theatre_name FROM theatres "
				  ."WHERE id=".$id.";";
				  
		$query = $this->db->query($select);
		if ($query->num_rows()>0) {
			$row = $query->row();
			return $row->theatre_name;
		}		
	}
	/* ***********************************************************
	 * Name:		get_theatre_scholarly_details()
	 * Input:		1) $id (id key for theatre)
	 * 				
	 * Output:		assoc array of row data for specific theatre
	 * 				
	 * Description:	Get the scholarly details entry for theatre[$id]
	 * 				(Goal: fulltext search)
	 * *********************************************************** */
	function get_theatre_scholarly_details($id) {
		
		$select = "SELECT sd.id, sd.theatre_id, sd.general_history, "
				 ."sd.previous_theatres_onsite, sd.alts_renovs_list, sd.desc_current, "
				 ."sd.measurements, t.theatre_name, "
				 ."t.city, t.country_digraph "
				 ."FROM scholarly_details sd, theatres t "
				 ."WHERE sd.theatre_id=".$id
				 ." AND sd.theatre_id=t.id;";
				 
		$query = $this->db->query($select);
		
		if ($query->num_rows() > 0) {
			// return the result row  (as array)
			return $query->row_array();
		}
	}
	
	/* ***********************************************************
	 * Name:		get_theatre_biblio()
	 * Input:		1) $id (id key for theatre)
	 * 				
	 * Output:		assoc array of row data for specific theatre
	 * 				
	 * Description:	** originally separate from the scholarly_details
	 * 				** page -- Added into the scholarly_details
	 * 				** based on Frank's request to change it
	 * 				** (May2010)
	 * *********************************************************** */
	function get_theatre_biblio($id) {
		
		$select = "SELECT b.id, b.theatre_id, biblio, t.theatre_name "
				  ."FROM biblios b, theatres t "
				  ."WHERE b.theatre_id=".$id
				  ." AND b.theatre_id=t.id;";
		
		$query = $this->db->query($select);
		
		if ($query->num_rows() > 0) {
			// return the result row  (as array)
			return $query->row_array();
		}
	}
	
	function get_featured_theatre($featured_num) {

		$select = "SELECT t.id, t.theatre_name, t.est_earliest, t.earliestdate_bce_ce, "
				 ."t.est_latest, t.latestdate_bce_ce, t.period_rep, "
				 ."t.country_name, t.country_digraph, t.city, "
				 ."ft.featured_num, ft.featured_text, ft.image_filepath, ft.image_filename "
				 ."FROM featured_theatres ft, theatres t "
				 ."WHERE (ft.featured_num=? AND ft.theatre_id=t.id);";

		$result = $this->db->query($select, $featured_num);
		if ($result->num_rows() > 0) {
			// username already exists
			return $result->row_array();
		}		
	}
	
	/* ***********************************************************
	 * Name:		get_country_details()
	 * Input:		1) $country_name
	 * 				
	 * Output:		country object, with attributes:
	 * 				- 2-letter DIGraph for specific country
	 * 				- lat (~center of country)
	 * 				- lng (~center of country)
	 * 				
	 * Description:	
	 * *********************************************************** */
	function get_country_details($country_name) {
		
		// first get the right digraph for this city
		$select_country_digraph = "SELECT country_digraph, lat, lng "
								  ."FROM country_codes "
								  ."WHERE country_name='".$country_name."';"; 
	
		$query = $this->db->query($select_country_digraph);
		if ($query->num_rows()>0) {
			return $query->row();

		}	
	}
	
	/* ***********************************************************
	 * Name:		get_country_digraph()
	 * Input:		1) $country_name
	 * 				
	 * Output:		2-letter DIGraph for specific country
	 * 				
	 * 				
	 * Description:	Using ISO-3166 as the standard
	 * 				country<->country_digraph list,
	 * 				this model query returns the 2-letter
	 * 				digraph based on the country_name
	 * *********************************************************** */
	function get_country_digraph($country_name) {
		
		// first get the right digraph for this city
		$select_country_digraph = "SELECT country_digraph "
								  ."FROM country_codes "
								  ."WHERE country_name='".$country_name."';"; 
	
		$query = $this->db->query($select_country_digraph);
		if ($query->num_rows()>0) {
			$row =  $query->row();
			return $row->country_digraph;

		}	
	}
	/* ***********************************************************
	 * Name:		insertTheatre()
	 * Input:		1) $country_name
	 * 				
	 * Output:		no return vals (but a DB-insert)
	 * 				
	 * Description:	Inserts a new theatre based on all
	 * 				the information input via the 
	 * 				"add_new_theatre" method (theatre_ctrl)
	 * 				Could have used a $rowdata array built
	 * 				in the method, but used separate column entries
	 * 				here since the beginning (~Nov2009)
	 * 				and haven't updated
	 * *********************************************************** */
	function insertTheatre($theatre_name, $country_name, $country_digraph, 
							$region, $city, $period_rep, $sub_type, $estEarliest, 
							$earlyADBC, $estLatest, $lateADBC, $aud_date, $lat_dd, $lng_dd,
							$datetime, $entry_author, $entry_editor, $entry_first_lister, $rating) {
		
		$data = array(

			'theatre_name'		  => $theatre_name,
			'country_name' 		  => $country_name,
			'country_digraph'	  => $country_digraph,
			'region' 		  	  => $region,
			'city'				  => $city,
			// city=orig_city after march 17, 2010 //
			'orig_city'			  => $city,
			'period_rep'		  => $period_rep,
			'sub_type'			  => $sub_type,
			'est_earliest'		  => $estEarliest,
			'earliestdate_bce_ce' => $earlyADBC,
			'est_latest'		  => $estLatest,
			'latestdate_bce_ce'	  => $lateADBC,
			'auditorium_date'     => $aud_date,
			'lat'				  => $lat_dd,
			'lng'				  => $lng_dd,
			'entry_date'		  => $datetime,
			'last_updated'		  => $datetime, // for insertions, entry_date=last_updated
			'entry_author'		  => $entry_author,
			'entry_editor'		  => $entry_editor,
			'entry_first_lister'  => $entry_first_lister,
			'rating'			  => $rating, 
			);
			
		
		$this->db->insert('theatres', $data);
		
	}
	
	/* ***********************************************************
	 * Name:		insert_visitor_info()
	 * Input:		1) $rowData, an associative array
	 * 				   of table column-names=>insert_values
	 * Output:		none
	 * 				
	 * Description:	Inserts a row of theatre data  
	 * 				into visitor_info table
	 * 				Need to strip formatting from 
	 * 				the basic_description, visiting_info,
	 * 				related_sites columns for the text
	 * 				versions - both case-sensitive (cs)
	 * 				case-insensitve (ci)
	 * 				Allows for full-text search
	 * *********************************************************** */
	function insert_visitor_info($rowData) {
		
		// init temp vars for stripping/unformatting texts
		// that we need for full-text search
		$unformat_basic = $unformat_visiting = $unformat_related_sites = '';
		
		// strip tags of basic_description into plain utf8
		$unformat_basic = strip_tags($rowData['basic_description']);
		$rowData['text_basic_desc_cs'] = html_entity_decode($unformat_basic,ENT_QUOTES,"UTF-8");
		$rowData['text_basic_desc_ci'] = $rowData['text_basic_desc_cs']; 
		// cs & ci are the same text, just collated/compared/searched
		// as case-sens. (cs) or case-INsens. (ci)
		
		// strip tags of visiting_info into plain utf8
		$unformat_visiting = strip_tags($rowData['visiting_info']);
		$rowData['text_visiting_info_cs'] = html_entity_decode($unformat_visiting,ENT_QUOTES,"UTF-8");
		$rowData['text_visiting_info_ci'] = $rowData['text_visiting_info_cs'];
		
		// strip tags of related_sites desc into plain utf8
		$unformat_related_sites = strip_tags($rowData['related_sites']);
		$rowData['text_related_sites_cs'] = html_entity_decode($unformat_related_sites,ENT_QUOTES,"UTF-8");
		$rowData['text_related_sites_ci'] = $rowData['text_related_sites_cs'];
		
		// enter the data in - using the theatres[id]==>visitor_info[theatre_id]
		$this->db->insert('visitor_info', $rowData);
		
	}
	
	/* ***********************************************************
	 * Name:		insert_new_scholarly()
	 * Input:		1) $id (id key for theatre)
	 * Output:		none
	 * 				
	 * Description:	Inserts theatre_id into scholarly_details
	 * 				so that the form has an id  
	 * 				to which to attach its data
	 * 				**TODO: Could make this more flexible by
	 * 				** simply passing in the theatre_id and the table_name
	 * 				** string (note that insert_new_scholarly, insert_new_biblio_ref,
	 * 				** & insert_new_main_imgs follow same form)
	 * *********************************************************** */
	function insert_new_scholarly($theatre_id) {
		// set name=>value pair for insert
		$this->db->set('theatre_id', $theatre_id);
		// active transaction == 
		// "INSERT into scholarly_details (theatre_id) VALUES (){$theatre_id}');" 
		$this->db->insert('scholarly_details');
	}
	
	function insert_new_biblio_ref($theatre_id){
		// set name=>value pair for insert
		$this->db->set('theatre_id', $theatre_id);
		$this->db->insert('biblios');
	}
	
	function insert_new_main_imgs($theatre_id){
		// set name=>value pair for insert
		$this->db->set('t_id', $theatre_id);
		$this->db->insert('main_images');
	}
	
	function updateTheatreById($id, $rowData) {
		// update the theatre <WHERE> theatre->id == $id 
		// (3rd param to the CI Active Record db->update function/method)
		$this->db->update('theatres', $rowData, array('id' => $id));
	}
	
	function update_time_in_theatres($id, $updateTime) {
			// set up the where clause (updating where id=$id)
			$this->db->where('id', $id);	
			$date_data = array('last_updated'=>$updateTime); 
			// array needed for Codeigniter Active Record (safer query set-up)
			$this->db->update('theatres', $date_data); 
	}
	
	/* ***********************************************************
	 * Name:		update_visitor_info()
	 * Input:		1) $id (id key for theatre)
	 * 				2) $rowData: array of column values 
	 * 							 for the theatre entry (row)
	 * Output:		none
	 * 				
	 * Description:	Updates visitor_info entry for specific theatre; and
	 * 				Strips tags for text-based entries 
	 * 				(Goal: fulltext search)
	 * *********************************************************** */
	function update_visitor_info($id, $rowData) {
		
		// init temp vars for stripping/unformatting texts
		// that we need for full-text search
		$unformat_basic = $unformat_visiting = $unformat_related_sites = '';
		
		// strip tags of basic_description into plain utf8
		$unformat_basic = strip_tags($rowData['basic_description']);
		$rowData['text_basic_desc_cs'] = html_entity_decode($unformat_basic,ENT_QUOTES,"UTF-8");
		$rowData['text_basic_desc_ci'] = $rowData['text_basic_desc_cs']; 
		// cs & ci are the same text, just collated/compared/searched
		// as case-sens. (cs) or case-INsens. (ci)
		
		// strip tags of visiting_info into plain utf8
		$unformat_visiting = strip_tags($rowData['visiting_info']);
		$rowData['text_visiting_info_cs'] = html_entity_decode($unformat_visiting,ENT_QUOTES,"UTF-8");
		$rowData['text_visiting_info_ci'] = $rowData['text_visiting_info_cs'];
		
		// strip tags of related_sites desc into plain utf8
		$unformat_related_sites = strip_tags($rowData['related_sites']);
		$rowData['text_related_sites_cs'] = html_entity_decode($unformat_related_sites,ENT_QUOTES,"UTF-8");
		$rowData['text_related_sites_ci'] = $rowData['text_related_sites_cs'];
		
		// update the theatre <WHERE> theatre->id == $id 
		// (3rd param to the CI Active Record db->update function/method)
		$this->db->update('visitor_info', $rowData, array('theatre_id' => $id));
	}
	
	function update_biblio($theatre_id, $rowData) {
		
		$unformat_biblio = '';
		$unformat_biblio = strip_tags($rowData['biblio']);
		$rowData['text_biblio_cs'] = html_entity_decode($unformat_biblio,ENT_QUOTES,"UTF-8");
		$rowData['text_biblio_ci'] = $rowData['text_biblio_cs']; 
		// cs & ci are the same text, just collated/compared/searched
		// as case-sens. (cs) or case-INsens. (ci)
		$this->db->update('biblios', $rowData, array('theatre_id'=> $theatre_id));
		
	}

	
	/* ***********************************************************
	 * Name:		insert_scholar_details()
	 * Input:		1) $id (id key for theatre)
	 * 				2) $rowData: array of column values 
	 * 							 for the theatre entry (row)
	 * Output:		none
	 * 				
	 * Description:	Inserts scholarly_details entry for specific theatre
	 * 				Strips tags for text-based entries 
	 * 				(Goal: fulltext search)
	 * *********************************************************** */
	function insert_scholar_details($rowData) {
		
		// init temp vars for stripping/unformatting texts
		// that we need for full-text search
		$unformat_general = $unformat_prevlist = $unformat_alts_renovs = '';
		$unformat_current = $unformat_measurements = '';
		
		// running notes column doesn't need to be unformatted, 
		//so it's updated automatically (sent in from $rowData)
		
		// strip tags of general history into plain utf8
		$unformatted_general = strip_tags($rowData['general_history']);
		$rowData['text_general_history_cs'] = html_entity_decode($unformatted_general,ENT_QUOTES,"UTF-8");
		$rowData['text_general_history_ci'] = $rowData['text_general_history_cs']; 
		// cs & ci are the same text, just collated/compared/searched
		// as case-sens. (cs) or case-INsens. (ci)
		
		// strip tags of previous list of theatres into plain utf8
		$unformat_prevlist = strip_tags($rowData['previous_theatres_onsite']);
		$rowData['text_previous_theatres_cs'] = html_entity_decode($unformat_prevlist,ENT_QUOTES,"UTF-8");
		$rowData['text_previous_theatres_ci'] = $rowData['text_previous_theatres_cs'];
		
		// strip tags of current theatre desc into plain utf8
		$unformat_current = strip_tags($rowData['desc_current']);
		$rowData['text_desc_current_cs'] = html_entity_decode($unformat_current,ENT_QUOTES,"UTF-8");
		$rowData['text_desc_current_ci'] = $rowData['text_desc_current_cs'];
		
		// strip tags of alterations/renovations info into plain utf8
		$unformat_alts_renovs = strip_tags($rowData['alts_renovs_list']);
		$rowData['text_alts_renovs_cs'] = html_entity_decode($unformat_alts_renovs,ENT_QUOTES,"UTF-8");
		$rowData['text_alts_renovs_ci'] = $rowData['text_alts_renovs_cs'];
		
		// strip tags of alterations/renovations info into plain utf8
		$unformat_measurements = strip_tags($rowData['measurements']);
		$rowData['text_measurements_cs'] = html_entity_decode($unformat_measurements,ENT_QUOTES,"UTF-8");
		$rowData['text_measurements_ci'] = $rowData['text_measurements_cs'];
		
		$this->db->insert('scholarly_details', $rowData);
	}
	
	/* ***********************************************************
	 * Name:		update_scholar_details()
	 * Input:		1) $id (id key for theatre)
	 * 				2) $rowData: array of column values 
	 * 							 for the theatre entry (row)
	 * Output:		none
	 * 				
	 * Description:	Updates scholarly_details entry for specific theatre
	 * 				Strips tags for text-based entries 
	 * 				(Goal: fulltext search)
	 * *********************************************************** */
	function update_scholar_details($id, $rowData) {
		
		// init temp vars for stripping/unformatting texts
		// that we need for full-text search
		$unformat_general = $unformat_prevlist = $unformat_alts_renovs = '';
		$unformat_current = $unformat_measurements = '';
		
		// strip tags of general history into plain utf8
		$unformatted_general = strip_tags($rowData['general_history']);
		$rowData['text_general_history_cs'] = html_entity_decode($unformatted_general,ENT_QUOTES,"UTF-8");
		$rowData['text_general_history_ci'] = $rowData['text_general_history_cs']; 
		// cs & ci are the same text, just collated/compared/searched
		// as case-sens. (cs) or case-INsens. (ci)
		
		// strip tags of previous list of theatres into plain utf8
		$unformat_prevlist = strip_tags($rowData['previous_theatres_onsite']);
		$rowData['text_previous_theatres_cs'] = html_entity_decode($unformat_prevlist,ENT_QUOTES,"UTF-8");
		$rowData['text_previous_theatres_ci'] = $rowData['text_previous_theatres_cs'];
		
		// strip tags of current theatre desc into plain utf8
		$unformat_current = strip_tags($rowData['desc_current']);
		$rowData['text_desc_current_cs'] = html_entity_decode($unformat_current,ENT_QUOTES,"UTF-8");
		$rowData['text_desc_current_ci'] = $rowData['text_desc_current_cs'];
		
		// strip tags of alterations/renovations info into plain utf8
		$unformat_alts_renovs = strip_tags($rowData['alts_renovs_list']);
		$rowData['text_alts_renovs_cs'] = html_entity_decode($unformat_alts_renovs,ENT_QUOTES,"UTF-8");
		$rowData['text_alts_renovs_ci'] = $rowData['text_alts_renovs_cs'];
		
		// strip tags of alterations/renovations info into plain utf8
		$unformat_measurements = strip_tags($rowData['measurements']);
		$rowData['text_measurements_cs'] = html_entity_decode($unformat_measurements,ENT_QUOTES,"UTF-8");
		$rowData['text_measurements_ci'] = $rowData['text_measurements_cs'];
		
		// update the theatre <WHERE> theatre->id == $id 
		// (3rd param to the CI Active Record db->update function/method)
		$this->db->update('scholarly_details', $rowData, array('theatre_id' => $id));
	}
	
	/* ***********************************************************
	 * Name:		delete_theatre()
	 * Input:		1) $id (id key for theatre)
	 * Output:		none
	 * 				
	 * Description:	Deletes all entries in all tables associated
	 * 				with a particular theatre
	 * 				1) scholarly_details table entry
	 * 				2) visitor_info table entry
	 * 				3) main 'theatres' entry
	 *  			** TODO: Should look into checking the cities
	 *  			** associated with this theatre.  If the theatre
	 *  			** is the only one in a particular theatre,
	 *  			** delete the city info, too.
	 * *********************************************************** */
	function delete_theatre($id) {
		
		// delete the theatre information from the main_images table
		$this->db->delete('main_images', array('t_id' => $id));
		// delete the theatre information from the biblios table
		$this->db->delete('biblios', array('theatre_id' => $id));
		// delete the theatre information from the scholarly_details table
		$this->db->delete('scholarly_details', array('theatre_id' => $id));
		// delete the theatre information from the visitor_info table
		$this->db->delete('visitor_info', array('theatre_id' => $id));
		// Finally, delete the theatre from main 'theatres' table, where 'id'=$id
		$this->db->delete('theatres', array('id' => $id));
	}
	
	/* ***********************************************************
	 * Name:		get_totals_in_table()
	 * Input:		$table == table name
	 * Output:		Number/Count of rows in $table table
	 * *********************************************************** */
	function get_totals_in_table($table){

		return $this->db->count_all($table);

	}
	
	/* ***********************************************************
	 * Name:		get_theatre_count()
	 * Input:		where array ($field=>$value)
	 * Output:		Number/Count of theatres in 'theatres' table
	 * *********************************************************** */
	function get_theatre_count($match_array, $all=0) {
		if ($all) {
			$this->db->or_like($match_array);	
		} else {
			$this->db->where($match_array);
			// use direct where match (City='Vienna' not City like '%Vienn%')
		}
		$this->db->from('theatres');
		
		return $this->db->count_all_results();
	}
	
	// get the last auto-increment index inserted
	// ** This MUST occur directly after the query of interest
	function getLastIdInserted() {
		
		return $this->db->insert_id(); 
	}
	
	// *** NOTE - Should make these a GENERIC Table select?
	// *** PASSING in the table name and building SELECT qry from that
	function getPeriods(){
		// Whenever we initially get the Theatre listing,
		// we want to order by the last entry inserted, 
		// then by city (asc), country_name (asc)
		$select = "SELECT p_id, period_rep from period;";
				  
		$query=$this->db->query($select);

		if($query->num_rows()>0){ 
			foreach ($query->result() as $row) {
				
				// return result set as an associative array
				return $query->result_array();
			}
		}
			
	}
	
	function getCountries(){
		
		$select = "SELECT country_digraph, country_name from country_codes;";
				  
		$query=$this->db->query($select);
		$result = array();
		if($query->num_rows()>0){ 
			foreach ($query->result() as $row) {
				$result[$row->country_digraph] = $row->country_name;
				
				// return result set as an associative array
				//return $query->result_array();
				
			} 
			return $result;
		}
			
	}
	
	/* ***********************************************************
	 * Name:		getAliases4CityId()
	 * Input:		1) city (id)
	 * Output:		Count of whether this city has ANY
	 * 				alias names in the city_aliases table or not
	 * 				If not, returns 0; if so, returns #
	 * 
	 * Description:	Model database select statement used
	 * 				by the controller to see if a particular
	 * 				city has any aliases at all
	 * 				Note: Closely related to chkCityAliasCnt()
	 * 				but this checks for the number of aliases
	 * 				for a city, not whether an alias exists or not
	 * *********************************************************** */
	function getAliasCnt4CityId($cityId) {
		
		$select = "SELECT count(city_aliases.city_alias) as aliasCount"
				." FROM city_aliases, cities" 
				." WHERE cities.city_id=?"
				." AND city_aliases.city_id=cities.city_id;";
				
		$query=$this->db->query($select, $cityId);

		if($query->num_rows()>0){ 
			// should only have one row for this query
			$row = $query->row();
			return $row->aliasCount;
		}	
		
	}
	
	/* ***********************************************************
	 * Name:		chkCityAliasCnt()
	 * Input:		1) city_alias (string)
	 * 				2) city (name/string)
	 * 				3) country_digraph (name/string)
	 * Output:		Count of whether the alias exists in the
	 * 				the database for this city, country pair.  
	 *				Note: the result
	 * 				should only be 0 or 1
	 * 
	 * Description:	Model database select statement used
	 * 				by the controller to see if an alias
	 * 				already exists in the city_aliases
	 * 				database for this city
	 * 				Note: Closely related to getAliases4City()
	 * 				but this checks for specific aliases, not
	 * 				just a count
	 * *********************************************************** */
	function chkCityAliasCnt($alias, $city, $country_digraph) {
		
		$select = "SELECT count(city_alias) as aliasCount FROM city_aliases, cities"
				." WHERE (city_alias=? AND city=? AND country_digraph=?"
				." AND city_aliases.city_id=cities.city_id);";
							
		$query=$this->db->query($select, array($alias, $city, $country_digraph));
		
		if($query->num_rows()>0){ 
			// should only have one row for this query
			$row = $query->row();
			return $row->aliasCount;
		}
		
	}
	
	function check_visitor_info($theatre_id) {
		$select = "SELECT count(theatre_id) as visitor_count "
				  ."FROM visitor_info "
				  ."WHERE theatre_id=?;";
		$query = $this->db->query($select, $theatre_id);
		
		if($query->num_rows()>0){ 
			// get the count
			$row = $query->row();
			return $row->visitor_count;
		}
	}
	
	function check_scholar_details($theatre_id) {
		$select = "SELECT count(theatre_id) as scholar_count "
				  ."FROM scholarly_details "
				  ."WHERE theatre_id=?;";
		$query = $this->db->query($select, $theatre_id);
		
		if($query->num_rows()>0){ 
			// get the count
			$row = $query->row();
			return $row->scholar_count;
		}
	}
	
	function check_biblio($theatre_id) {
		$select = "SELECT count(theatre_id) as bib_count "
				  ."FROM biblios "
				  ."WHERE theatre_id=?;";
		$query = $this->db->query($select, $theatre_id);
		
		if($query->num_rows()>0){ 
			// get the count
			$row = $query->row();
			return $row->bib_count;
		}
		
	}
	
	function check_imgs($theatre_id) {
		$select = "SELECT count(t_id) as img_count "
				  ."FROM main_images "
				  ."WHERE t_id=?;";
		$query = $this->db->query($select, $theatre_id);
		
		if($query->num_rows()>0){ 
			// get the count
			$row = $query->row();
			return $row->img_count;
		}
	}
	
	function getCityId($city, $country_digraph) {
		
		$select = "SELECT city_id FROM cities WHERE "
				."(city like \"".$this->db->escape_like_str($city)."%\""
				." AND country_digraph='".$country_digraph."');";
				
		$query=$this->db->query($select);
		
		if($query->num_rows()>0){ 
		// should only have one row for this query
			$row = $query->row();
			// just return the city_id
			return $row->city_id;
		} else { // city doesn't exist in DB yet, send back a 0 
			return 0;
		}
	}
	
	// ** TO DO: DELETE The orig_city column once this is tested.
	function insertCity($newCity, $country_digraph) {
		$insertData = array(
               'city' => $newCity,
			   'orig_city' => $newCity, // old carry-over
			   'country_digraph' => $country_digraph
           );
		$this->db->insert('cities', $insertData);
	}
	
	function insertCityAlias($cityId, $cityAlias) {
		// only insert if the cityAlias is NOT a blank string
		if (strlen($cityAlias) > 0) {
			$insertData = array(
				'city_id' 	 => $cityId,
				'city_alias' => $cityAlias
			);
		
		$this->db->insert('city_aliases', $insertData);
		}
	}
	
	function insert_theatre_alias($theatre_id, $alias) {
		// only insert if the cityAlias is NOT a blank string
		if (strlen($alias) > 0) {
			$insertData = array(
				'theatre_id' 	 => $theatre_id,
				'theatre_alias' => $alias
			);
		
		$this->db->insert('theatre_aliases', $insertData);
		}
	}
	/* ***********************************************************
	 * Name:		getCityAlias()
	 * Input:		1) cityId
	 * Output:		Return the city Alias names (and alias_ids)
	 * 				
	 * 
	 * Description:	Model database select statement used
	 * 				by the controller to get all the alias
	 * 				names for a particular city
	 * *********************************************************** */
	function getCityAliases($cityId) {
		$select = "SELECT alias_id, city_alias "
				  ."FROM city_aliases "
				  ."WHERE city_id='".$cityId."' "
				  ."ORDER BY city_alias;";
				  
		$query=$this->db->query($select);

		if($query->num_rows()>0){ 
			foreach ($query->result() as $row) {
				
				// return result set as an associative array
				return $query->result_array();
			}
		}
		
	}
	
	function delete_city_alias($city_id, $city_alias) {
		
		$this->db->where('city_id', $city_id);
		$this->db->where('city_alias', $city_alias);
		$this->db->delete('city_aliases');
		
	}
	
	/* ***********************************************************
	 * Name:		get_theatre_aliases()
	 * Input:		1) theatre_id
	 * Output:		Return the theatre alias names (and alias_ids)
	 * 				
	 * 
	 * Description:	Model database select statement used
	 * 				by the controller to get all the alias
	 * 				names for a particular theatre
	 * *********************************************************** */
	function get_theatre_aliases($theatre_id) {
		$select = "SELECT theatre_alias "
				  ."FROM theatre_aliases "
				  ."WHERE theatre_id=?;";
		$result=$this->db->query($select, $theatre_id);
		if($result->num_rows()>0){ 
			foreach ($result->result() as $row) {
				// return result set as an associative array
				return $result->result_array();
			}
			
		} else {
			return 0;
		}
	}
	
	function chk_theatre_alias_count($theatre_id, $alias) {
		
		$select = "SELECT count(theatre_alias) as aliasCount FROM theatre_aliases"
				." WHERE (theatre_id=? AND theatre_alias=?);";
							
		$query=$this->db->query($select, array($theatre_id, $alias));
		
		if($query->num_rows()>0){ 
			// should only have one row for this query
			$row = $query->row();
			return $row->aliasCount;
		}
	}
	
	/* ***********************************************************
	 * Name:		delete_theatre_alias()
	 * Input:		1) theatre_id
	 * 				2) theatre_alias (to delete)
	 * Output:		Return TRUE if deleted w/no error
	 * 				Else, return FALSE
	 * 				
	 * 
	 * Description:	Model database select statement used
	 * 				by the controller to delete a specific alias
	 * 				for a particular theatre
	 * *********************************************************** */
	function delete_theatre_alias($theatre_id, $alias) {
		
		$this->db->where('theatre_id', $theatre_id);
		$this->db->where('theatre_alias', $alias);
		$this->db->delete('theatre_aliases');
	}
	
	/* ***********************************************************
	 * Name:		get_count()
	 * Input:		selection_criteria array (field/column=>value)
	 * 				e.g., theatre_id=23
	 * 				
	 * Output:		Number/Count of rows returned in $table_name table
	 * Description: More generic than other Theatre_model 'count' queries --
	 * 				but works similar to get_theatre_count()
	 * *********************************************************** */
	function get_count($select_criteria, $table_name) {
		
		$this->db->where($select_criteria);
			// $select_criteria is an array of column->value pairs
		$this->db->from($table_name);
		
		return $this->db->count_all_results();
	}
	
	function getThumbNail($theatreId) {
		
		$select = "SELECT t_id, image_file, file_path "
				  ." FROM thumbnails "
				  ." WHERE t_id='".$theatreId."'";
				  
		$query = $this->db->query($select);
		
		if($query->num_rows()>0){
		// return the result row
			return $query->row();
		}
	}
	
	function getTypes($p_id){
		
		$select = "SELECT t_type from t_types where p_id=".$p_id.";";
				  
		$query=$this->db->query($select);

		if($query->num_rows()>0){ 
			foreach ($query->result() as $row) {
				
				// return result set as an associative array
				return $query->result_array();
			}
		}
			
	}
	
	function getPeriodId($periodRep) {
		$select = "SELECT p_id from period where period_rep='".$periodRep."';";
				  
		$query=$this->db->query($select);

		if($query->num_rows()>0){
			// get the result object
			$row = $query->row();
			// return the p_id of the query result obj.
			return $row->p_id;
		}
		
	}
	
	// Get Main images for a specific theatre
	function get_main_images($t_id) {
		
		$select = "SELECT id, stage, exterior, auditorium, plan, section, video_link, top_file_path "
				  ."FROM main_images "
				  ."WHERE t_id=".$t_id.";";
		$query=$this->db->query($select);

		if($query->num_rows()>0){
			$row = $query->row_array();
			return $row;
		}		  
	}
	
	// 
	function get_featured_image($t_id) {
		
		$select = "SELECT stage FROM main_images WHERE t_id=".$t_id.";";
		$query=$this->db->query($select);

		if($query->num_rows()>0){
			// get the result object
			$row = $query->row();
			// return the p_id of the query result obj.
			return $row->stage;
		}
		
	}
	
	/* **************************************************************
	 * Name:			search_date_range()
	 *
	 * Description:		Runs prepared mysqli-based query
	 * 					that binds the '?' values in the query
	 * 					to the array passed as 2nd arg to $this->db->query()
	 * 
	 * 
	 * ************************************************************** */
	function search_date_range($ce_or_bce, $date1, $date2) {
			
		if ($ce_or_bce === 'BCE') {
			$sort_order = 'DESC'; // if BCE, larger dates are older, smaller are more recent if BCE)
			if ($date1<$date2) { // we need to swap the order if BCE and date1<date2
				$date1 = $date2;
				$date2 = $date1;
			}
		} else {
			$sort_order = 'ASC'; // normal sort-order
		}
		
		$select = "SELECT * FROM theatres "
				  ."WHERE ((earliestdate_bce_ce=? OR latestdate_bce_ce=?) " 
				  // at least one has to match $ce_or_bce choice
				  ."AND (est_earliest>=? AND est_latest<=?) "
				  ."AND (est_earliest!=0 OR est_latest!=0)) "
				  // and they must be in range, earliest to latest - **no check on whether date1<date2 
				  ."ORDER BY earliestdate_bce_ce, est_earliest ".$sort_order.";";
				  // want to order by bce first, if that's what comes up, then by earliest date,
				  // if BCE -- want to sort DESC
				   
		$result = $this->db->query($select, array($ce_or_bce, $ce_or_bce, $date1, $date2));
		// $ce_or_bce is listed twice because of the OR for 'CE'/'BCE' in early or late column
		
		if ($result->num_rows() > 0) {
			// return the result list as an array (of arrays if >1 result row)
			return $result->result_array();
		}	
	}
	
	/* **************************************************************
	 * Name:			search_date()
	 *
	 * Input:			$ce_or_bce ('CE', 'BCE')
	 * 					$date (EXACT match)
	 * 
	 * Description:		** BCE/CE dates (not house)
	 * 					Needs both eary/late BCE/CE to MATCH
	 * 					Needs either early OR late date to MATCH
	 * 					Runs prepared mysqli-based query
	 * 					that binds the '?' values in the query
	 * 					to the array passed as 2nd arg to $this->db->query()
	 * 
	 * 
	 * ************************************************************** */
	function search_date($ce_or_bce, $date_to_match) {
	//SELECT * FROM theatres where ((earliestdate_bce_ce='CE' AND latestdate_bce_ce='CE') 
	// AND (est_earliest=? or est_latest=?))
	
	$select = "SELECT * from theatres "
			  ."WHERE ((earliestdate_bce_ce=? AND latestdate_bce_ce=?) "
			  ."AND (est_earliest=? or est_latest=?) "
			  ."AND (est_earliest!=0 OR est_latest!=0));";
			  
	$result = $this->db->query($select, array($ce_or_bce, $ce_or_bce, $date_to_match, $date_to_match));
		
		if ($result->num_rows() > 0) {
			// return the result list as an array (of arrays if >1 result row)
			return $result->result_array();
		}	
	}
	
	/* **************************************************************
	 * Name:			search_housedate_range()
	 *
	 * Input:			$date1 and $date2 ** no bce/ce?
	 * Output:
	 * 
	 * Description:		Runs prepared mysqli-based query
	 * 					that binds the '?' values in the query
	 * 					to the array passed as 2nd arg to $this->db->query()
	 * 
	 * 
	 * ************************************************************** */
	 function search_housedate_range($date1, $date2) {
	 	
		$select = "SELECT * FROM theatres "
				  ."WHERE ((auditorium_date BETWEEN ? and ?) " // inclusive
				  ."AND (auditorium_date!=0));"; 
				  
		$result = $this->db->query($select, array($date1, $date2));
		
		if ($result->num_rows() > 0) {
			// return the result list as an array (of arrays if >1 result row)
			return $result->result_array();
		}	
	 }
	
	/* **************************************************************
	 * Name:			get_default_form_entries()
	 *
	 * Description: 	For the add_form/edit_forms -- when adding a new entry, 
	 * 					this pulls from a database of default guidelines for entry
	 * 					e.g., <Please enter details on the auditorium measurements, 
	 * 							if available.>
	 * ************************************************************** */
	function get_default_form_entries($selectors_list) {
		
		// $selectors_list is actually a comma-delimited string 
		// of the columns to select from the entry_guidelines table
		$this->db->select($selectors_list);
		$this->db->where('id', 1); // only one row in this table
		$query = $this->db->get('entry_guidelines'); 

		if ($query->num_rows()>0) {
			$row = $query->row_array(); 
			// return result set as an associative array
				return $row;
		}
		
	}
	
	// ***************************************************************  //
	// ********* ACCOUNTS' MEMBERSHIP TABLES INTERACTION *************  //
	
	/* ***********************************************************
	 * Name:		validate_user()
	 * Input:		Submitted <username> and <password> ($_POST[] vars)
	 * Output:		Account info for that username/password, if it exists
	 * 
	 * Description: DB select using "where()" from CI's active query format
	 * 				Chains the WHERE clause (two calls to where)
	 * 				This is same as boolean AND
	 * *********************************************************** */
	function validate_user($username, $password) {
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$result = $this->db->get('accounts');
		
		if ($result->num_rows == 1) {
			return $result->row();
		}
	}
	
	/* ***********************************************************
	 * Name:		create_pending_account()
	 * Input:		array of insert data (e.g., first name, last name,
	 * 				username, email address, etc)
	 * Output:		none -but inserts data into database
	 * Description: Model db insert statement to register/insert user
	 * 				into the database
	 * *********************************************************** */
	function create_pending_account($pending_acct_insert_data) {
		
		// SET up all the author full-text indexed statement INFO
		// for future use in Searching author expertise/background
		// strip tags of general history into plain utf8
		$unformatted_app_stmt = strip_tags($pending_acct_insert_data['vita_statement']);
		$pending_acct_insert_data['vita_statement_cs'] = html_entity_decode($unformatted_app_stmt,ENT_QUOTES,"UTF-8");
		$pending_acct_insert_data['vita_statement_ci'] = $pending_acct_insert_data['vita_statement_cs']; 
		// cs & ci are the same text, just collated/compared/searched
		// as case-sens. (cs) or case-INsens. (ci)
		
		$insert = $this->db->insert('accounts', $pending_acct_insert_data);
		return $insert; // returns 1 if success; 0 if not
	}
	
	/* ***********************************************************
	 * Name:		get_accounts()
	 * Input:		type ('pending' 'author', 'editor', 'admin')
	 * Output:		
	 * Description: Model db select statement used to get lists
	 * 				of accounts
	 * *********************************************************** */
	function get_accounts($type) {
		// Build "SELECT * from accounts where user_access_level=$type;"
		$this->db->where('user_access_level', $type);
		$result = $this->db->get('accounts');
		
		if ($result->num_rows() > 0) {
			// return the result list as an array (of arrays if >1 result row)
			return $result->result_array();
		}
		
	}
	
	function get_accounts_where($id){
		$select = "SELECT id, user_access_level, username, request_date FROM accounts WHERE id=?;";
		
		$result=$this->db->query($select, $id);
		if($result->num_rows()>0){ 
			// should only be one row
			return $result->result_array();
		}	
	}
	
	function update_account_access($id, $row_data) {
		
		// update the theatre <WHERE> accounts->id == $id 
		// (3rd param to the CI Active Record db->update function/method)
		if ($this->db->update('accounts', $row_data, array('id' => $id))) {
			return TRUE;
		} else { // it failed to update
			return FALSE;
		}
	}
	
	function delete_user_account($id) {
		
		// delete the pending acct from the 
		$this->db->delete('accounts', array('id' => $id));
	}

	/* ***********************************************************
	 * Name:		get_num_accounts()
	 * Input:		where user_access_level = $access_level
	 * Output:		Number/Count of theatres in 'theatres' table
	 * *********************************************************** */
	function get_num_accounts($access_level) {
		
		$this->db->where('user_access_level', $access_level);

		$this->db->from('accounts');
		
		return $this->db->count_all_results();
	}
	
	function get_account_data_for($id) {
		
		$select = "SELECT username, first_name, last_name, user_access_level, "
					."email_address, reviewing_admin, last_reviewed_date, activation_code " 
					."FROM accounts "
				 	."WHERE id=?";
		$result = $this->db->query($select, $id);
		
		if($result->num_rows()>0){
			// get the result array as one row only (row_array() vs. result_array())
			return $result->row_array();			
		}
	}	
	
	/* ***********************************************************
	 * Name:		get_account_by_username()
	 * Input:		1) $username
	 * 				
	 * Output:		some of account data for the user with $username
	 * 				
	 * Description:	Called in theatre_ctrl/change_password_form.
	 * 				 
	 * 				Probably could have minimized number of
	 * 				different model queries to get a single
	 * 				account, since most get by $id, but this
	 * 				does the trick as needed.
	 * *********************************************************** */
	function get_account_by_username($username) {
		
		$select = "SELECT id, username, first_name, last_name, user_access_level "
					."FROM accounts "
				 	."WHERE username=?";
		
		$result = $this->db->query($select, $username);
		
		if($result->num_rows()>0){
			// get the result array as one row only (row_array() vs. result_array())
			return $result->row_array();			
		}
	}
	
	// Update activation code in accounts to 1 (activated)
	function activate_user($activation_code) {
		// get the id for this activation code
		$select = "SELECT id from accounts where activation_code=?";
		$result = $this->db->query($select, $activation_code);
		
		if ($result->num_rows() == 1) { // activate the acct
			$update = "UPDATE accounts set activated=1 where activation_code=?";
			$this->db->query($update, $activation_code);
			return TRUE;	
			
		} else { // got a problem with this activation code or the query
			return FALSE;
		}
	}
	
	
	function change_password($id, $md5encoded_password) {
		
		$row_data = array('password'=>$md5encoded_password);
		
		if ($this->db->update('accounts', $row_data, array('id' => $id))) {
			return TRUE;
		} else { // it failed to update
			return FALSE;
		}
	}
	
	
	/* ***********************************************************
	 * Name:		username_exists()
	 * Input:		username var
	 * Output:		TRUE if username already exists in accounts table;
	 * 				FALSE if NOT
	 * Description: Model db select statement used to check if 
	 * 				a username already exists in the database
	 * *********************************************************** */
	function username_exists($username) {
		$select = "SELECT username FROM accounts where username=?";
		$result = $this->db->query($select, $username);
		if ($result->num_rows() > 0) {
			// username already exists
			return TRUE;
		} else { // username doesn't exist (yet)
			return FALSE;
		}
	}
	
	/* ************************************************************
	 * TEMPORARY db update for the application instructions 
     * use if you want to change the entry_guidelines table instructions 
	 * more easily.
	 * ************************************************************ */
	function update_instruction_tbl($row_data) {
		$this->db->update('entry_guidelines', $row_data, array('id'=> 1));
	}
	
	/* ***********************************************************
	 * Name:		getCharsets()
	 * Input:		none
	 * Output:		Returns the mysql database variable character
	 * 				sets, like the client, system, server ones
	 * 				This was set up to check that the CI database config
	 * 				was in fact working, and that the utf8 charset
	 * 				was being set as desired (during a troubleshooting fiasco in Feb2010)
	 * 
	 * Description:	Model database select statement used
	 * 				by the controller to get all the alias
	 * 				names for a particular city
	 * *********************************************************** */
	function getCharsets() {
		$setCharset = "SHOW VARIABLES LIKE 'character_set%';";
		$query=$this->db->query($setCharset);
		if($query->num_rows()>0){ 
			foreach ($query->result() as $row) {
				
				// return result set as an associative array
				return $query->result_array();
			}
		}
		
	}


}	
?>
