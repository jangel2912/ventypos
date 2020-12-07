<?php

class Licencias extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $this->load->model("roles_model", 'roles');
        $this->roles->initialize($this->dbConnection);
        $this->load->model("Caja_model", 'Caja');
        $this->Caja->initialize($this->dbConnection);
        $this->load->model("usuario_almacen_model", 'usuario_almacen');
        $this->usuario_almacen->initialize($this->dbConnection);
        $this->load->model('Usuarios_model', 'usuarios');
        $this->usuarios->initialize($this->dbConnection);
        $this->load->model('Licencias_model', 'licencias');
        $this->licencias->initialize($this->dbConnection);
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    public function get_ajax_data() {
        die("Asdad");
        $this->output->set_content_type('application/json')->set_output(json_encode($this->licencias->get_ajax_data()));
    }
}