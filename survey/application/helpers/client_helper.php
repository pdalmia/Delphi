<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function isLogged()
{
	$CI = &get_instance();
	if ( !isset ($CI->session ))
		$CI->load->library('session');
	if($CI->session->userdata('logged_in'))
		return $CI->session->userdata('logged_in');
	return false;
}

?>