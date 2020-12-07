<?php

class MY_Controller extends CI_Controller {
	
	var $db_connection;

	function __construct()
    {
        parent::__construct();
        $usuario = $this->session->userdata('usuario');
		$clave = $this->session->userdata('clave');
		$servidor = $this->session->userdata('servidor');
		$base_dato = $this->session->userdata('base_dato');
		$dns = "mysql://$usuario:$clave@$servidor/$base_dato";
		$this->db_connection = $this->load->database($dns, true);
    }

    protected function checkLogin()
    {
    	if (!$this->ion_auth->logged_in())
			redirect('auth', 'refresh');
    }

}