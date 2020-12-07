<?php

class Comanda extends CI_Controller {

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

        $this->load->library('pagination');

        //=================================================================

        $this->load->model("comanda_model");
        $this->comanda_model->initialize($this->dbConnection);

        $this->load->model("miempresa_model");
        $this->miempresa_model->initialize($this->dbConnection);

        //=================================================================



        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $this->load->library('Encryption');
    }


    public function index(){    
            
        if (!$this->ion_auth->logged_in())
                redirect('auth', 'refresh');
        $this->comanda_model->crearTablasNotificaciones();
        $this->miempresa_model->crearOpcion("comanda_push",1);
        $comanda = $this->miempresa_model->obtenerOpcion("comanda");
        $comandaPush = $this->miempresa_model->obtenerOpcion("comanda_push");
        
        // Si la comanda no esta activa y las notificaciones tampoco, lo redirigimos
        if( $comanda != "si" || $comandaPush != "1")
            redirect('./', 'refresh');
        
        $id = $this->session->userdata('user_id');
        $this->conectarse($id);        
        
        
        $data = array(
            "id" => $id
        );
        
        $this->layout->template('member2')->show('comanda/index', array('data' => $data));        
        
    }

    public function sendPushToUsers(){
        $this->comanda_model->sendPushToUsers();
    }
    public function sendPushToServer(){                
        $this->comanda_model->sendPushToServer();
    }
        
    public function enviarComanda(){       
        
        $this->comanda_model->distribuirComanda();
    }
    
    public function conectarse($id){       
        
        $this->comanda_model->conectarse($id);
    }

    public function getData(){
        
        $result = $this->comanda_model->getData();
        
        $this->output->set_content_type('application/json')->set_output(json_encode( $result ));
        
    }
    
    public function getComandas($id){
               
        $result = $this->comanda_model->getComandas($id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode( $result ));
        
    }
    

    public function setEstado( $tipo, $estado ){       
        
        $this->comanda_model->setEstado( $tipo, $estado );       
        
    }    
    
    public function getNotificacionServer(){
                
        $result = $this->comanda_model->getNotificacionServer();
        
        if(empty($result)) {
            $result = array(
                array(
                    'notificacion' => ''
                )
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode( $result ));
        
    }
    
    public function getNotificacion($id){
                
        $result = $this->comanda_model->getNotificacion($id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode( $result ));
        
    }
    
    public function crearTablasNotificaciones(){
        
        if (!$this->ion_auth->logged_in())
                redirect('auth', 'refresh');
        
        $this->comanda_model->crearTablasNotificaciones();
    }

}
?>
