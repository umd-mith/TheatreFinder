<?php
	// make sure no one can open this from web...
  	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Filename: email.php 
 * When the CI email library is loaded from a controller method,
 * e.g., with $this->load->library('email') or via the autoload.php config,
 * The config/ dir is checked for this file. 
 * If it exists, the $config array for this CI app will be populated
 * with the mail values listed (protocol, host, etc).
 * The initial config for TheatreFinder is smtp, using gmail's configs.
 * This may change if we get the theatrefinder@umd.edu email account,
 * to a set-up under OIT's mail server configs.
 *  -- emb, 07/01/2010
 * 
 */
 /* Google mail server set up (if not pummeled by OIT security)
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_port'] =  465; // 587 should also work
$config['smtp_user'] = 'theatrefinder@gmail.com';
$config['smtp_pass'] = 'frankhildy';
*/
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'mx.glue.umd.edu';
$config['smtp_port'] = 25;