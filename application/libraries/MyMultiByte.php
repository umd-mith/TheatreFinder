<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyMultiByte {
	
	// May be used in form_validation -
	// html_encode converts html entities into characters utf-8
	function html_encode($var) {
	return htmlentities($var, ENT_QUOTES, 'UTF-8') ;
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
    return 1;
	} // end of check_utf8
}
?>
