<?php

class FixDB extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();

        //=================================================================

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        //=================================================================

        $this->load->model("fixdb_model");
        $this->fixdb_model->initialize($this->dbConnection);

        //=================================================================

        $this->load->library('Encryption');
    }


    public function index(){    
            
        if (!$this->ion_auth->logged_in())
                redirect('auth', 'refresh');
        if( $this->session->userdata('user_id') == '1445' ){
            $this->fixdb_model->fix();        
        }else{
            echo "No tiene permiso para entrar aquí...";
        }
        
        
    }

}
?>
