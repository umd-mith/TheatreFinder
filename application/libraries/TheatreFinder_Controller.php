<?php
	// Creation of a base class from which all of our controllers 
	// will inherit
	 
class TheatreFinder_Controller extends Controller {
		
	// layout stuff
	protected $data = array();
	protected $controller_name;
	protected $method_name;
		
	// extend/instantiate the controller from CI main parent
	function __construct() {
			
		parent::Controller();
		
		// need to set the admin_link for all children Controllers here...
		$this->data['admin_link'] = '';
		$this->data['body_id'] = "<body>";
		// layout vars default load
		$this->load_defaults();
			
	}
	
	/* ***********************************************************
	 * Name:		_is_logged_in
	 * Input:	
	 * Output:	
	 * Dependency:  session library and indirectly, the login controller	
	 * Description:	Checks to see if the session data is logged in
	 * 				The template used is set based on whether the
	 * 				user is logged in or not
	 * 				
	 * *********************************************************** */
	function _is_logged_in($config) {

		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true) {
			
			if($this -> _get_logged_in_requires_auth($config, $this->method_name)) {
				echo 'You don\'t have permission to access this page. ' .anchor('/user', 'Login');	
				die();
			}
			
			$this->data['template'] = $this->_get_logged_in_config($config, $this->method_name, 'anonymous', 'template', 'visitors_only_layout');
			$this->data['view_controller'] = $this->_get_logged_in_config($config, $this->method_name, 'anonymous', 'view_controller', 'theatre_view');
			
		} else {
			
			$this->data['template'] = $this->_get_logged_in_config($config, $this->method_name, 'authenticated', 'template', 'main_layout');
			$this->data['view_controller'] = $this->_get_logged_in_config($config, $this->method_name, 'authenticated', 'view_controller', 'theatre_ctrl');
			
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
	
	protected function _get_logged_in_requires_auth($config, $method) {
		if(!array_key_exists($method, $config)) {
			if($method == '*') {
				return false;
			}
			return $this->_get_logged_in_requires_auth($config, '*');
		}
		if(!array_key_exists('requires_auth', $config[$method])) {
			return false;
		}
		return $config[$method]['requires_auth'];
	}
	
	protected function _get_logged_in_config($config, $method, $auth_type, $key, $default) {
		if(!array_key_exists($method, $config)) {
			if($method == '*') {
				return $default;
			}
			return $this->_get_logged_in_config($config, '*', $auth_type, $key, $default);
		}
		if(!array_key_exists($auth_type, $config[$method])) {
			if($method == '*') {
				return $default;
			}
			return $this->_get_logged_in_config($config, '*', $auth_type, $key, $default);
		}
		if(!array_key_exists($key, $config[$method][$auth_type])) {
			return $default;
		}
		return $config[$method][$auth_type][$key];
	}
	
	protected function load_defaults() {
		
		$this->controller_name = $this->router->fetch_directory() . $this->router->fetch_class();
		$this->method_name = $this->router->fetch_method();
		
		$this->data['css'] = '';
		$this->data['scripts'] = '';
		$this->data['title'] = 'Page Title';
		$this->data['header'] = '';
		$this->data['footer'] = '';
		
		// main content - this will be appended to by any data
		// that is output via the controller/method's "$this->data"
		$this->data['content'] = '';
		
		/* DeBug vars: e.g., what's the controller and action?
		 * What's my main uri, etc., etc
		 
		$this->data['debug_info'] = "<h3>CONTROLLER: ".$this->controller_name."<br>AND METHOD: ".$this->method_name
		."<h3> DIR: ".$this->router->fetch_directory()
		." ".$this->data['this_class'] = " and CLASS: ".$this->router->fetch_class()
		." ".$this->data['my_URI'] = " and URI [".$this->uri->uri_string()."]</h3>";
		 */
	}

	protected function render($template=FALSE, $alt=FALSE) {
		if(!$template) {
			$template = $this->data['template'];
		}
		$view_path = $this->controller_name . '/' . $this->method_name . '.php';
		
		if ($alt) { // if we don't follow the views/controller/method path,
					// use the method passed
				$alt_view = $this->controller_name.'/'.$alt; //.'.php';
				//$alt_view = '/'.$alt.'.php';		
				$this->data['content'] .= $this->load->view($alt_view, $this->data, true);
			
		} else {
			if (file_exists(APPPATH . 'views/' . $view_path)) {
				$this->data['content'] .= $this->load->view($view_path, $this->data, true);
			}
		}

		$this->load->view("layouts/$template.php", $this->data);
	}

	protected function add_css($filename) {
		//$this->data['css'] .= $this->load->view("partials/css.php", array('filename' => $filename), true);
		$this->data['css'] .= $this->load->view("partials/".$filename.".php", '', true);
	}
	
	protected function add_scripts($filename) {
		$this->data['scripts'] .= $this->load->view("partials/".$filename.".php", '', true);

	}
	
	/* *************************************************************************
	 * Name:		_convert_decimal_degrees_to_DMS()
	 * Input:		decimal degrees ($lat_dd, $long_dd)
	 * Output:		Lat/Long in Degrees/Mins/Seconds (DMS)
	 * 
	 * Used by:		Both the theatre_ctrl and theatre_view controllers
	 * 
	 * Description:	Converts decimal degrees to degrees/minutes/seconds 
	 * 				Modified code from Chad Cooper example (2004) www.super-cooper.com
	 * 
	 *				DD=decimal degrees 
	 *				DMS=degrees/minutes/seconds
	 * 				Example: 32.872352 lat and -91.632563 long
	 *				The whole units of the decimal will remain the same, so
	 * 1) multiply the decimal by 60 (0.872352 * 60 = 52.34112), the whole number becomes the minutes (52) 
	 * 2) take the remaining decimal (0.34112) and multiply by 60,
	 * 3) Result=seconds.  Next, truncate (not round) this to two decimal places 
	 * 		(0.34221 * 60 = 20.53)
	 * 4) string them all together 32 52 20.53
	 * * ***************************************************/
	 protected function _convert_decimal_degrees_to_DMS($lat_dd, $long_dd) {
    		
		// LATITUDE
		// DEGREES: get integer value of latitude, where $lat_dd is a floating point number like 32.872352
		$lat_dd_int = intval($lat_dd);
		//  MINUTES: get DD minutes (what's after the decimal point)
		$lat_dd_min = abs($lat_dd - $lat_dd_int);
		// get DMS minutes as floating point -> 60 * DD minutes floating
		$lat_dms_min_float = 60 * $lat_dd_min; 
		// get int value of lat DMS floating point
		$lat_dms_min_int = intval($lat_dms_min_float);
		// SECONDS: get DD seconds -> DMS minutes floating - DMS minutes integer
		$lat_dd_secs = $lat_dms_min_float - $lat_dms_min_int;
		// convert DD secs to DMS secs -> 60 * DD seconds
		$lat_dms_secs = 60 * $lat_dd_secs;
		// don't round anything to ensure most accurate position,
		// but need to truncate for display
		if ($lat_dms_secs != 0) {
			$lat_dms_secs = $this->_truncate_float($lat_dms_secs,2);
		}
		// LONGITUDE 
		// DEGREES: get integer value of longitude from db -> where $long_dd is a floating point number like -91.632563
		// this number remains negative
		$long_dd_int = intval($long_dd);
		//  MINUTES: get DD minutes -> Long DD floating point - long DD integer
		//  use abs to get rid of the negative sign
		$long_dd_min = abs($long_dd - $long_dd_int);
		// get DMS minutes as floating point -> 60 * DD minutes floating
		$long_dms_min_float = 60 * $long_dd_min; 
		// get int value of long DMS floating
		$long_dms_min_int = intval($long_dms_min_float);
		// SECONDS 
		// get DD seconds -> DMS minutes floating - DMS minutes integer
		$long_dd_secs = $long_dms_min_float - $long_dms_min_int;
		// convert DD secs to DMS secs -> 60 * DD seconds
		$long_dms_secs = 60 * $long_dd_secs;
		// don't round anything to ensure most accurate position,
		// but need to truncate for display
		if ($long_dms_secs!=0) {
			$long_dms_secs = $this->_truncate_float($long_dms_secs, 2);
		}		
		$lat_direction = ($lat_dd<0) ? "S" : "N";
		$lng_direction = ($long_dd<0) ? "W" : "E";
		
		$lat_dd_int = abs($lat_dd_int);
		$long_dd_int = abs($long_dd_int);
		
		$lat_DMS = $lat_dd_int."&deg; ".$lat_dms_min_int."&rsquo; ".$lat_dms_secs."&rdquo; ".$lat_direction;
		$lng_DMS = $long_dd_int."&deg; ".$long_dms_min_int."&rsquo; ".$long_dms_secs."&rdquo; ".$lng_direction;
			
		return(array($lat_DMS, $lng_DMS));
	}
	
	/* *********************************************************************
	 * Name:		_truncate_float
	 * Input:		 1) float (as str)
	 * 				 2) num decimal places
	 * 
	 * Output:		 Float num with decimal part to num places specified
	 * 				 Allows for displaying accurate seconds without truncating
	 * ******************************************************************* */
	function _truncate_float($float_str, $num_places) {
		$float_str = explode(".", $float_str);
		$trunc_point = substr($float_str[1], 0, $num_places);
		return $float_str[0].'.'.$trunc_point;
	}

	
	/* ***********************************************************************
	 * Name:		dms2dd
	 * Input:	    lat or lng in DMS 
	 * 				1) degrees
	 * 				2) minutes
	 * 				3) seconds
	 * 				4) if lat ==>"lat"; else, "lng"
	 * 				5) "N", "E", "S", "W"
	 * 
	 * Output:		lat in decimal degrees
	 * 
	 * Description:	
	 * ********************************************************************* */
	function _convert_dms2dd($degrees, $mins, $secs, $lat_or_lng, $hemisphere) {
			
		// determine the hemisphere (N,S,E,W)
		switch ($lat_or_lng) {
			case "lat":
				// if "S", lat is neg
				$sign = ($hemisphere==="S") ? '-' : '';
			break;
				
			case "lng":
				// if "W", lng is neg
				$sign = ($hemisphere==="W") ? '-' : '';
			break;
				
			default:
			//nada
			break;
		}
			
		$sec_dd = ($secs/3600);
		$min_dd = ($mins/60);
			
		// do arithmetic first
		$decimal_out = $degrees + $min_dd + $sec_dd;
		// then add sign
		$decimal_out = $sign.$decimal_out;
			
		return $decimal_out;
			
	}
	
	/* *************************************************************************
	 * Name:		_convert_decimal_degrees_to_DMS()
	 * Input:		1) decimal degrees of a lat or lng position
	 * 				2) lat or lng string (specifying "lat" or "lng")
	 * 
	 * Output:		Lat/Long in Degrees/Mins/Seconds (DMS)
	 * 
	 * Used by:		theatre_ctrl controller for editing entries (visitor_info.php)
	 * 
	 * Description:	Converts decimal degrees to degrees/minutes/seconds 
	 * 				Similar to _convert_decimal_degrees_to_DMS();
	 * 				BUT input/output formatting is different
	 * 				This takes one lat/long in decimal, with lat/lng specifier,
	 * 				and converts it into array of degrees, mins, secs + hemisphere
	 * *********************************************************************** */
	function _convert_dd2dms($decimal_lat_lng, $lat_or_lng) {
		
		// DEGREES: get integer value
		$dd_int = intval($decimal_lat_lng);
		//  MINUTES: get DD minutes (what's after the decimal point)
		$dd_min = abs($decimal_lat_lng - $dd_int);
		// DMS minutes as float -> 60 * DD minutes floating
		$dms_min_float = 60 * $dd_min; 
		// get int value of lat DMS floating point
		$dms_min_int = intval($dms_min_float);
		// SECONDS: DMS minutes float - DMS minutes integer
		$dd_secs = $dms_min_float - $dms_min_int;
		// convert DD secs to DMS secs -> 60 * DD seconds
		$dms_secs = 60 * $dd_secs;
		// do not round, just trunacte the remaining secs up
		if ($dms_secs!=0) {
			$dms_secs = $this->_truncate_float($dms_secs,2);
		}
			
		switch($lat_or_lng) {
			case "lat":
				$direction = ($decimal_lat_lng<0) ? "S" : "N";
			break;
			
			case "lng":
				$direction = ($decimal_lat_lng<0) ? "W" : "E";
			break;
			
			default:
			//nada
			break;
		}
		
		// now make the degrees + versus neg, for display
		$dd_int = abs($dd_int);
				 
		return (array("degrees"	=>$dd_int,
					 "mins"		=>$dms_min_int,
					 "secs"		=>$dms_secs,
					 "direction"=>$direction));
	}
}
?>