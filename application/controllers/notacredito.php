<?php

class Notacredito extends CI_Controller {
    function __construct() {

        parent::__construct();

        $this->user = $this->session->userdata('user_id');
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("nota_credito_model", 'nota_credito');
        $this->nota_credito->initialize($this->dbConnection);
        
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
        
        //Creacion de tabla en caso de no existir
        $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
    }
    
    public function cancelarNotaCredito(){
        // creamos la tabla de pagos con nota credito si no existe
        // Esta tabla es para relacionar una venta con el codigo de la nota credito
        $this->nota_credito->existeNotaCredito($this->session->userdata('base_dato'));
        
        $listaNotaCredito = $this->input->post('notas');
        $this->nota_credito->cancelarNotaCredito( $listaNotaCredito );
    }
    
    public function estadoNotaCredito(){
        $codigo = $this->input->post('codigo');
        $data = $this->nota_credito->estadoNotaCredito( $codigo );
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}