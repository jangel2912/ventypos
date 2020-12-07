<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of opciones
 *
 * @author Locho
 */
class Opciones extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('backend/opciones_model', 'opciones');
    }
    
    public function index(){
       
         if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        else{
             $this->layout->show('backend/opciones/index.php', array('message' => ''));
         }
     }
     
     public function get_ajax_data(){
        $this->output->set_content_type('application/json')->set_output(json_encode($this->opciones->get_ajax_data()));
    }

    public function activation(){
        if(!$this->ion_auth->is_admin())
        {
            redirect('frontend/acceso_limitado');
        }
        $data['email_activation'] = $this->opciones->get_activation();
        $this->layout->show('backend/opciones/activation.php', array('data' => $data));
    }
    
    public function save_activation(){
         if(!$this->ion_auth->is_admin())
        {
            redirect('frontend/acceso_limitado');
        }
        $this->opciones->save_activation();
        redirect('backend/opciones/activation');
    }
    
    public function nuevo(){
         if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        
        if ($this->form_validation->run('opciones') == true)
        {
            $this->opciones->add();
            $this->session->set_flashdata('message', 'Opcion creada correctamente');
            redirect('backend/opciones/index');
        }
        $this->layout->show('backend/opciones/nuevo');
    }
    
    public function editar($id){
        if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        if ($this->form_validation->run('opciones') == true)
            {
                $this->opciones->update();	
                $this->session->set_flashdata('message', 'Opcion actualizada correctamente');
                redirect("backend/opciones/index");
            }
        $data['data']  = $this->opciones->get_by_id($id);
        $this->layout->show('backend/opciones/editar', array('data' => $data));
    }
    
    public function eliminar($id)
    {	
            if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
            }
            $this->opciones->delete($id);
            $this->session->set_flashdata('message', 'Se ha eliminado correctamente');
            redirect("backend/opciones/index");
    }
}
?>