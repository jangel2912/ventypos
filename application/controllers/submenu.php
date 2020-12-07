<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Submenu extends CI_Controller {

    var $dbConnection;

    public function __construct() {
        
        parent::__construct();
        
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model("pais_provincia_model", 'pais_provincia');

        $this->load->model("almacenes_model", 'almacen');
        $this->almacen->initialize($this->dbConnection);

        $this->load->model("graficas_model", 'graficas');
        $this->graficas->initialize($this->dbConnection);



        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    // connfiguracion
    public function configuracion() {
        
        
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data['valor_caja'] = $data_empresa['data']['valor_caja'];
        $data['etienda'] = $data_empresa['data']['etienda'];

        $this->layout->template('member')->show('submenu/configuracion.php', array('data' => $data));
    }

    public function contactos() {
                
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('submenu/contactos.php');
    }    
    
    public function compras() {
                
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('submenu/compras.php');
    }   
    
    public function cotizacion() {
                
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('submenu/cotizacion.php');
    }
    
    public function productos() {
                
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('submenu/productos.php');
    }  
    
    public function ventas() {
                
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('submenu/ventas.php');
    }      
    
    public function fidelizacion() {
                
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('submenu/fidelizacion.php');
    }  
    
    
}

?>