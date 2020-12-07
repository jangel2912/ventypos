<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Factura_electronica extends CI_Controller {


    var $dbConnection;
    

    public function __construct() {
        parent::__construct();
        $this->user = $this->session->userdata('user_id');
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("miempresa_model", 'miempresa');
        $this->miempresa->initialize($this->dbConnection);

        $this->load->model('ventas_model','ventas');
        $this->ventas->initialize($this->dbConnection);

    }


    public function index(){
        $this->miempresa->crearOpcion("archivo_certificado_sellos","");
        $this->miempresa->crearOpcion("archivo_clave_certificado","");
        $this->miempresa->crearOpcion("clave_archivo_key","");
        $this->layout->template('member')->show('factura_electronica/index');
    }

    public function guardar_configuracion(){
       // $this->form_validation->set_rules('fi_certificado_sello', 'Certificado de sellos', 'required');
       // $this->form_validation->set_rules('fi_clave_privada_certificado', 'Clave privada de certificado', 'required');
        $this->form_validation->set_rules('t_clave_archivo_cifrado', 'Contraseña para clave privada', 'required');
        $carpeta = 'uploads1/certificados_factura_electronica/'.$base_dato = $this->session->userdata('base_dato');
            
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }   

        $config['upload_path'] = $carpeta;
        $config['allowed_types']        = 'cer|key';
        $config['max_size']             = 100;
        $soporte_name = $carpeta.'/';
        $this->load->library('upload', $config);
        $carga_archivos = false;
        $error_archivos='';
        if (!$this->upload->do_upload('fi_certificado_sello')) {
            $error_archivos.=  $this->upload->display_errors();            
        } else {
            $upload_data_certificado = $this->upload->data();
            $carga_archivos = true;
        }

        if(!$this->upload->do_upload('fi_clave_privada_certificado')){
           $error_archivos.= $this->upload->display_errors();
           $carga_archivos = false;
          
        }else{
            $upload_data_firma = $this->upload->data();
            $carga_archivos = true;
        }   
        
        if ( ($this->form_validation->run() == FALSE) OR ($carga_archivos == FALSE)) {
            $error_view = array('error'=> validation_errors(),'mensaje_archivos'=>$error_archivos);
            $this->layout->template('member')->show('factura_electronica/index',$error_view);        
        }else{
                $this->dbConnection->where('nombre_opcion','archivo_certificado_sellos');
                $this->dbConnection->update('opciones',array('valor_opcion' => $soporte_name .$upload_data_certificado['file_name']));
                $this->dbConnection->where('nombre_opcion','archivo_clave_certificado');
                $this->dbConnection->update('opciones',array('valor_opcion' => $soporte_name .$upload_data_firma['file_name']));
                $this->dbConnection->where('nombre_opcion','clave_archivo_key');
                $this->dbConnection->update('opciones',array('valor_opcion' => $this->input->post('t_clave_archivo_cifrado')));
                redirect('frontend/configuracion','refresh');
        }
    }

    public function generar_timbrado(){
        $this->ventas->agregar_columna_timbrado_venta();
        $id = $this->input->post('factura');
        $where = array('id'=>$id);
        $datos_venta = $this->ventas->get_datos_una_venta_where($where);
        if($datos_venta->factura_timbrada == 1){
            echo json_encode(array('respuesta'=>'Esta factura ya se timbro, si desea puede descargar el xml en la opcion descargar'));
            die();
        }

        $update = array('factura_timbrada'=>1,'ruta_xml_timbrado'=>'certificados_factura_electronica/13f5e7ff-e9cc-46d3-a4eb-9c435231ec49.xml');
        $this->ventas->actualizar_venta_con_parametros($where,$update);

        echo json_encode(array('respuesta'=>'Se ha realizado el timbrado de la factura correctamente'));
    }

    public function descargar_xml(){
        $id = $this->input->post('factura');
        $where = array('id'=>$id);
        $datos_venta = $this->ventas->get_datos_una_venta_where($where);
        echo json_encode(array('url_archivo'=> base_url().'uploads1/'.$datos_venta->ruta_xml_timbrado));
    }
}
?>