<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
//si no existe la función invierte_date_time la creamos
if(!function_exists('dd')) {

	function dd($string)
	{
		$file = fopen("log.txt", "a");
		fwrite($file, $string);
		fclose($file);
	}
}