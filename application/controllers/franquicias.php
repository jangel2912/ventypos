<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Controlador para las franquicias

 * 
 *  */

class Franquicias extends CI_Controller {
    
    /*
     * Constructor
     */
    public function __construct(){
        
        parent::__construct();

        // Carga de idioma.
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
        
        // Carga del modelo de franquicias.
        $this->load->model( 'franquicias_model' , 'franquicias' );
    }
    
    /*
     * Listado de franquicias y acciones sobre estas.
     */
    public function index(){
        
        // Validación de logueo.
        if (!$this->ion_auth->logged_in()){ redirect('auth', 'refresh'); }
  
        // Envio datos de las franquicias a la vista franquicias/index.
        $this->layout->template('member')->show( 'franquicias/index' );
    }
    
    /*
     * Carga via ajax de las franquisias.
     */
    public function get_ajax_data(){

        // Envio en formato json las franquicias obtenidas en el modelo
        $this->output->set_content_type('application/json')->set_output(json_encode($this->franquicias->get_ajax_data()));
    }
    
    /*
     * Eliminar franquicia
     * $id : Id de la franquicia a eliminar.
     */
    public function eliminar($id){
        
        // Validación de logueo.
        if (!$this->ion_auth->logged_in()){ redirect('auth', 'refresh'); }
        
        // Elimino franquicia.
        $this->franquicias->eliminar($id);
        $this->session->set_flashdata('message', custom_lang('sima_franquicias_deleted_message', 'Se ha eliminado correctamente'));
        
        // Redirecciono hacia franquicias/index.
        redirect("franquicias/index");
    }
}