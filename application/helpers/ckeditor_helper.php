<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * CKEditor helper for CodeIgniter
 * 
 * @Notes: embonsignore, April 2010
 * I used this as a basic example to learn how to add a CI helper to the application
 * using the new CKeditor (update from FCKeditor) then modified it a lot.
 * The major details for the formatting and editor prefs are in the dir:
 * application/javascript/ckeditor/customConfigtheatrefinder_ckeditor_config.js
 * and we don't have custom TF styles, we don't really have to use most of this.
 * Ultimately, we could make this a private function or plugin in our CodeIgniter app
 * The CKeditor documentation is very good.
 * 
 * @author Samuel Sanchez <samuel.sanchez.work@gmail.com> - http://www.kromack.com/
 * @package CodeIgniter
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 * @tutorial http://www.kromack.com/codeigniter/ckeditor-helper-for-codeigniter
 * @see http://codeigniter.com/forums/viewthread/127374/
 * 
 */
function display_ckeditor($data) {
	//  only need the <script /> tag here if you don't have the ckeditor.js set up in your view header..
  	//  $return = '<script type="text/javascript" src="'.base_url(). $data['path'] . '/ckeditor.js"></script>';
	$return = '';

	/* **Do Not Need Styles section ('my_styles') currently.  	
    //Adding styles values
    if(isset($data['styles'])) {
    	
    	$return .= "<script type=\"text/javascript\">CKEDITOR.addStylesSet( 'my_styles', [";
   
    	
	    foreach($data['styles'] as $k=>$v) {
	    	
	    	$return .= "{ name : '" . $k . "', element : '" . $v['element'] . "', styles : { ";

	    	if(isset($v['styles'])) {
	    		foreach($v['styles'] as $k2=>$v2) {
	    			
	    			$return .= "'" . $k2 . "' : '" . $v2 . "'";
	    			
					if($k2 !== end(array_keys($v['styles']))) {
						 $return .= ",";
					}
	    		} 
    		} 
	    
	    	$return .= '} }';
	    	
	    	if($k !== end(array_keys($data['styles']))) {
				$return .= ',';
			}	    	

	    } 
	    
	    $return .= ']);</script>';
    }   
    */
	
    //Building Ckeditor script
    
    $return .= "<script type=\"text/javascript\">
     	CKEDITOR_BASEPATH = '" . base_url() . $data['path'] . "/';
     	CKEDITOR.replace('" . $data['id'] . "', {";

			// Adding config values 
			// Added customConfig processing (emb 2010)
			if (isset($data['customConfig'])) {
				
				$return .= "customConfig : '". base_url().$data['path']."/".$data['customConfig'] ."'";
				
			} else if(isset($data['config'])) { // this is the new 
	    		foreach($data['config'] as $k=>$v) {
	    			
	    			$return .= $k . " : '" . $v . "'";
	    			
	    			if($k !== end(array_keys($data['config']))) {
						$return .= ",";
					}		    			
	    		} 
    		}   			
    				
    $return .= '});';
    
    //$return .= "CKEDITOR.config.stylesCombo_stylesSet = 'my_styles';
    $return .= "</script>";

    return $return;
}