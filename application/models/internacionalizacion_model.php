<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//2015/12/19

class Internacionalizacion_model extends CI_Model {

	public function __construct(){
		parent::__construct();

        if($this->session->userdata('usuario') !== FALSE)
        {
        	$group_licencias=array(3,4);
        	//var_dump($this->ion_auth->in_group($group_licencias));die();
            if(!$this->ion_auth->in_group($group_licencias)){
            	$usuario = $this->session->userdata('usuario');
		    	$clave = $this->session->userdata('clave');
		    	$servidor = $this->session->userdata('servidor');
		    	$base_dato = $this->session->userdata('base_dato');
            	$dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            	$this->dbconnection = $this->load->database($dns, true);
            	$this->init_time_zone();	
             }
        	
        } else {
            $this->dbconnection = $this->load->database();
        }
	}

	private function init_time_zone()
	{
		$query = $this->dbconnection->get_where('opciones' ,array('nombre_opcion' => 'zona_horaria'));
		$zona_horaria = $query->num_rows > 0 ? $query->row()->valor_opcion : '';

		if($zona_horaria !== '')
		{
			date_default_timezone_set($zona_horaria);
			ini_set("date.timezone", $zona_horaria);
		}
		else
		{
			date_default_timezone_set('America/Bogota');
		}
	}

}