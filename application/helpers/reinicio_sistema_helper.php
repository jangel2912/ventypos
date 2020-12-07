<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('costo_promedio'))
{
    function reinicio_sistema($modulo,$almacen = null) {
        $ci =& get_instance();
        $this->ci->load->database();
        $id_usuario = $ci->session->userdata('user_id');
        
        
        return true;
    }
}