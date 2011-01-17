<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Forms - to handle utf-8 charsets
if ( ! function_exists('form_open')) {
	function form_open($action = '', $attributes = '', $hidden = array()) {
		$CI =& get_instance();

		if ($attributes == '') {
			$attributes = 'method="post"';
		}

		$action = ( strpos($action, '://') === FALSE) ? $CI->config->site_url($action) : $action;

		$form = '<form action="'.$action.'"';
	
		$form .= _attributes_to_string($attributes, TRUE);
	
		$form .= ' accept-charset="utf-8"';
	
		$form .= '>';

		if (is_array($hidden) AND count($hidden) > 0) {
			$form .= form_hidden($hidden);
		}

		return $form;
	}
}

// ------------------------------------------------------------------------

/**
 * Form Prep
 *
 * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_prep')) {
	function form_prep($str = '', $field_name = '') {
		static $prepped_fields = array();
		
		// if the field name is an array we do this recursively
		if (is_array($str)) {
			foreach ($str as $key => $val) {
				$str[$key] = form_prep($val);
			}

			return $str;
		}

		if ($str === '') {
			return '';
		}

		// we've already prepped a field with this name
		// @todo need to figure out a way to namespace this so
		// that we know the *exact* field and not just one with
		// the same name
		if (isset($prepped_fields[$field_name])) {
			return $str;
		}
		
		// $str = htmlspecialchars($str);

		$str = htmlspecialchars($str, ENT_COMPAT, 'UTF-8');

		// In case htmlspecialchars misses these.
		$str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

		if ($field_name != '') {
			$prepped_fields[$field_name] = $str;
		}
		
		return $str;
	}
}

// ------------------------------------------------------------------------
/* ********************************* */
if( ! function_exists('form_ajax')) {
	function form_ajax($form_id, $method = 'POST', $update = '', $create = true) {
		$automake_div = (!empty($update) && $create);
		$method = ($method === 'POST') ? 'POST' : 'GET';
		return
		($automake_div ? '<div id="'.$update.'"></div>' : '').'
		<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.7.0/build/yahoo-dom-event/yahoo-dom-event.js&2.7.0/build/connection/connection-min.js&2.7.0/build/selector/selector-min.js"></script> 
		<script type="text/javascript" charset="utf-8">
		var doNotSubmit = function(e) {
			YAHOO.util.Event.preventDefault(e);
			makeRequest();
		}
		YAHOO.util.Event.addListener(YAHOO.util.Selector.query("#'.$form_id.' input[type=submit]"), "click", doNotSubmit);

		'.($automake_div ? 'var div = document.getElementById("'.$update.'");' : '').'
		var callback = {
			success: function(o) {
				if(o.responseText !== undefined) {
					div.innerHTML = o.responseText;
				}
			}
		}
		var sUrl = document.getElementById("'.$form_id.'").action;
				
		function makeRequest() {
			YAHOO.util.Connect.setForm("'.$form_id.'");
			YAHOO.util.Connect.asyncRequest("'.$method.'", sUrl, callback);
		}
		</script>
		';
	}
}

/* End of MY_form_helper.php */
/* Location: ./system/application/helpers/MY_form_helper.php */