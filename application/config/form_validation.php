<?php
// File to set up all the form validation checks for any forms

 $config = array(
                 // Researchers/Accounts (login) form & table validation
				 // NOTE: with config.php global: $config['global_xss_filtering'] = TRUE, 
				//       "xss_clean" is not really needed here, but extra	
                 'login/create_member' => array(
				 					array(
                                            'field' => 'first_name',
                                            'label' => 'First Name',
                                            'rules' => 'trim|required|alpha|xss_clean'
                                         ),
									array(
                                            'field' => 'last_name',
                                            'label' => 'Last Name',
                                            'rules' => 'trim|required|alpha|xss_clean'
                                         ),
                                    array(
                                            'field' => 'username',
                                            'label' => 'Username',
                                            'rules' => 'trim|required|min_length[4]|xss_clean|callback_username_unique'
                                         ),
                                    array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|min_length[4]|max_length[32]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'PasswordConfirmation',
                                            'rules' => 'trim|required|matches[password]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'affiliation',
                                            'label' => 'Affiliation',
                                            'rules' => 'trim|xss_clean'
                                         ),
                                    array(
                                            'field' => 'email_address',
                                            'label' => 'Email Address',
                                            'rules' => 'trim|required|valid_email|xss_clean'
                                         ),
									array(
                                            'field' => 'app_stmt',
                                            'label' => 'Application Statement',
											'rules' => 'xss_clean'
                                         ),
                                    ),
				// note the same validation rules for change_admin_password/change_password
				'theatre_ctrl/change_admin_password' => array(
									array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|min_length[4]|max_length[32]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'PasswordConfirmation',
                                            'rules' => 'trim|required|matches[password]|xss_clean'
                                         ),
									),
				'theatre_ctrl/change_password' => array(
									array(
                                            'field' => 'password',
                                            'label' => 'Password',
                                            'rules' => 'trim|required|min_length[4]|max_length[32]|xss_clean'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'PasswordConfirmation',
                                            'rules' => 'trim|required|matches[password]|xss_clean'
                                         ),
									),
				// theatres table validation. 
				// NOTE: with config.php global: $config['global_xss_filtering'] = TRUE, 
				//       "xss_clean" is not really needed here, but extra				
                 'theatres' => array(
                                    array(
                                            'field' => 'theatre_name',
                                            'label' => 'Theatre Name',
                                            //'rules' => 'required|max_length[128]|xss_clean|mysql_real_escape_string|trim'
											'rules' => 'required|max_length[128]|xss_clean|trim'
                                         ),
                                    
                                    array(
                                            'field' => 'country_name',
                                            'label' => 'Country',
                                            //'rules' => 'required|max_length[64]|xss_clean|mysql_real_escape_string|trim'
											'rules' => 'required|max_length[128]|xss_clean|trim'
                                         ),
									array(
                                            'field' => 'city',
                                            'label' => '(Modern) City',
                                            //'rules' => 'required|max_length[64]|xss_clean|mysql_real_escape_string|trim'
											'rules' => 'required|max_length[128]|xss_clean|trim'
                                         ),
									array(
											'field' => 'auditorium_date',
											'label' => 'Auditorium Date',
											'rules' => 'xss_clean|trim|numeric' // numbers only for dates
										 ),
									array(
											'field' => 'est_earliest',
											'label' => 'Earliest Known Date',
											'rules' => 'xss_clean|trim|numeric'
										 ),
									array(
											'field' => 'est_latest',
											'label' => 'Latest Date',
											'rules' => 'xss_clean|trim|numeric'
										 ),
									array(
											'field' => 'lat_degrees',
											'label' => 'Degrees Latitude',
											'rules' => 'xss_clean|trim|numeric'
										 ),
									array(
											'field' => 'lng_degrees',
											'label' => 'Degrees Longitude',
											'rules' => 'xss_clean|trim|numeric'
										 ),
									array(
											'field' => 'lng_mins',
											'label' => 'Minutes Longitude',
											'rules' => 'xss_clean|trim|numeric'
										 ),
									array(
											'field' => 'lat_secs',
											'label' => 'Seconds Latitude',
											'rules' => 'xss_clean|trim|numeric'
										 ),
									array(
											'field' => 'lng_secs',
											'label' => 'Seconds Longitude',
											'rules' => 'xss_clean|trim|numeric'
										 ),	
                                    ),
				// scholarly_details valid
				'scholarly_details' => array(
                                    array(
                                            'field' => 'running_notes',
                                            'label' => 'Running Notes',
											'rules' => 'xss_clean'
                                         ),
										 array(
                                            'field' => 'general_history',
                                            'label' => 'General History',
											'rules' => 'xss_clean'
                                         ),
										 array(
                                            'field' => 'previous_theatres_onsite',
                                            'label' => 'Info on Previous Theatres',
											'rules' => 'xss_clean'
                                         ),
										 array(
                                            'field' => 'alts_renovs_list',
                                            'label' => 'Alterations, Renovations, etc.',
											'rules' => 'xss_clean'
                                         ),
										 array(
                                            'field' => 'desc_current',
                                            'label' => 'Current Theatre Description',
											'rules' => 'xss_clean'
                                         ),
										 array(
                                            'field' => 'measurements',
                                            'label' => 'Measurements and Technical details',
											'rules' => 'xss_clean'
                                         ),
										 array(
                                            'field' => 'biblio',
                                            'label' => 'Bibliography',
											'rules' => 'xss_clean'
                                         )
									),              
               );
?>