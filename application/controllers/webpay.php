<?php

Class Webpay extends CI_Controller{
    
    var $dbConnection;
    var $user;

    function __construct() {
        parent::__construct();
    }
    
    public function nuevo()
    {
        $id = base64_decode($_POST['id']);
        $data = json_decode($_POST['json']);
        /*
        estado 
        0=anulado,
        1=completado,
        2=pediente
        */
        $array = array(
            "transaction_id"=>$data->transaction->transaction_id,
            "transaction_datetime"=>$data->transaction->transaction_datetime,
            "valor"=>$data->transaction->amount,
            "json"=>$_POST['json'],
            "aleatorio"=>$_POST['a'],
            "estado"=>"2"
        );
        $this->load->model('webpay_model','webpay');
        $conexion = $this->webpay->datosConexion($id);
        
        $dns = "mysql://$conexion->usuario:$conexion->clave@$conexion->servidor/$conexion->base_dato";
        $this->dbConnection = $this->load->database($dns, true);
        $this->webpay->initialize($this->dbConnection);
        $this->webpay->existeWebpay($conexion->base_dato);
        $this->webpay->nuevoTransaccion($array);
    }
    
    public function closeIframe()
    {
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);
        $this->load->model('webpay_model','webpay');
        $this->webpay->initialize($this->dbConnection);
        $getPendiente = $this->webpay->getPendiente($_POST['aleatorio']);
        if($getPendiente != false)
        {
            $this->webpay->updateEstado($getPendiente,1);
            echo json_encode(array('close'=>true));
        } else {
            echo json_encode(array('close'=>false));
        }
        
        
    }
    
    
}