<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OpcionesController
 *
 * @author usuario
 */
class OpcionesController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);
        $this->load->model("opciones_model","opciones");
    }

    public function index() {
        $data = $this->opciones->getDataMoneda();
        $datos['data']=$data;
        $this->load->view('opciones/index.php',$datos);
    }
    
      public function consultaDecimal() {
        $data['decimales'] = $this->opciones->getDataMoneda();
       $this->load->view('opciones/consultaDecimales', $data);
    }
    
    public function verFormato(){
       echo  $this->opciones_model->formatoMonedaMostrar(2972244.59);
    }

}
