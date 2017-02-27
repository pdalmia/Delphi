<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI =& get_instance();
$CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
function load_db()
{
	$CI =& get_instance();
	if ( isset($CI -> databaseIsLoaded) && $CI -> databaseIsLoaded )
		return;
	$CI->load->database();
	$CI->databaseIsLoaded = true;
}

?>
