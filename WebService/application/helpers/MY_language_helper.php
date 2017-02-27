<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function l()
{	
	$args = func_get_args();	
	if(is_array($args) && count($args))		
		$args = array_pop($args);	
	if ( is_array ($args ) )		
		$line = array_shift($args);	
	else		
		$line = $args;					
	$CI =& get_instance();		
	@list($file , $__line) = explode(".", $line, 2);		
	if ( empty ( $__line ) || empty($file) ) 
	// CI system translations	
	{		
		$__line = empty($__line) ? $file : $__line;		$file = "global";	
	}	
	if ( ! isset ( $CI->lang->language[$file][$__line] ) )	
	{		
		$CI->lang->custom = true;		
		$CI->lang->load($file);		
		$CI->lang->custom = false;	
	}	
	$line = $CI->lang->line($file, $__line);	
	if(empty($line))		
		$line = $__line;		
	if( is_array($args) && count($args) )		
		$line = vsprintf($line , $args);	
	return $line;
}/* End of file MY_language_helper.php *//* Location: ./application/helpers/MY_language_helper */