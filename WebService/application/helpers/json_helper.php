<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function jsonp($data, $end = true)
{
	$CI =& get_instance();
	$CI->load->library('form_validation');
	if (! isset ( $_GET['callback'] ) || empty($_GET['callback']) || ! CI_Form_validation::alpha_dash( $_GET['callback'] ) )
		show_error("Invalid JSONP callback");
	Header("Content-Type: application/json");
	echo $_GET['callback'] . "(" . json_encode($data) . ")";
	if($end)
		die();
		
}

?>