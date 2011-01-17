<?php

function utf8_to_uri($utf8_string) {
	$CI =& get_instance();
	$CI->load->config('international');
	$utf8_dict = $CI->config->item('utf8_dict');
	
	return strtr($utf8_string, $utf8_dict);
}

function utf8_ucfirst($utf8_string) {
	$e ='utf-8';
	
	if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($utf8_string)) {
		$utf8_string = mb_strtolower($utf8_string, $e);
		$upper = mb_strtoupper($utf8_string, $e);
		preg_match('#(.)#us', $upper, $matches);
		$utf8_string = $matches[1] . mb_substr($utf8_string, 1, mb_strlen($utf8_string, $e), $e);
	
	} else {
		$utf8_string = ucfirst($utf8_string);
	}
	
	return $utf8_string;
}

function is_valid_utf8($utf8_string) {
	$utf8_re = "/^([\\x00-\\x7f]|"
	  . "[\\xc2-\\xdf][\\x80-\\xbf]|"
	  . "\\xe0[\\xa0-\\xbf][\\x80-\\xbf]|"
	  . "[\\xe1-\\xec][\\x80-\\xbf]{2}|"
	  . "\\xed[\\x80-\\x9f][\\x80-\\xbf]|"
	  . "\\xef[\\x80-\\xbf][\\x80-\\xbc]|"
	  . "\\xee[\\x80-\\xbf]{2}|"
	  . "\\xf0[\\x90-\\xbf][\\x80-\\xbf]{2}|"
	  . "[\\xf1-\\xf3][\\x80-\\xbf]{3}|"
	  . "\\xf4[\\x80-\\x8f][\\x80-\\xbf]{2})*$/";
	
	$valid = FALSE;
	
	if ( preg_match($utf8_re, $utf8_string) > 0 ) {
		$valid = TRUE;
	}
	
	return $valid;
}


function compare_uft8($s1, $s2) {
	$CI =& get_instance();
	$CI->load->config('international');
	$utf8_dict = $CI->config->item('utf8_dict');
	
	return strcasecmp(strtr($s1, $utf8_dict), strtr($s2, $utf8_dict));
}

function sort_uft8($utf8_array) {
	usort($utf8_array, 'compare_utf8');
	
	return $utf8_array;
}

?>