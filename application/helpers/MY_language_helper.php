<?php



if ( ! function_exists('custom_lang'))

{

	function custom_lang($line, $alternative)

	{

		$CI =& get_instance();

		if($CI->session->userdata('pais_idioma') == 103 ){
			$CI->lang->load('sima','es_mx');
		}

		$line = $CI->lang->line($line);

		if(!$line){
            return $alternative;
        }
		return $line;
	}

}

?>