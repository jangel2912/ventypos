<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of multi_tenant
 *
 * @author Locho
 */
class Menu extends CI_Controller {
    //put your code here
    
    public function __construct()
    {
        parent::__construct();
         $this->load->library('pagination');
         $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        
        $this->load->model('backend/menu_model', 'menu');
    }
    
    public function index($offset = 0){
         if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        
        $data['data'] = $this->menu->get_menus($offset);
        $data['total'] = $this->menu->get_total();
        $this->layout->show('backend/menu/index.php', array('data' => $data));
    }
    
     public function get_ajax_data(){
        $this->output->set_content_type('application/json')->set_output(json_encode($this->menu->get_ajax_data()));
    }
    
    public function nuevo(){
         if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        if ($this->form_validation->run('menu') == true)
        {
            $this->menu->add();
            $this->session->set_flashdata('message', 'Menu creado correctamente');
            redirect('backend/menu/index');
        }            
        $data = array();
        $this->layout->show('backend/menu/nuevo', array('data' => $data));
    }
    
    public function editar($id){
        if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        if ($this->form_validation->run('menu') == true)
            {
                $this->menu->update();	
                $this->session->set_flashdata('message', 'Menu actualizado correctamente');
                redirect("backend/menu/index");
            }

        $data = array();   
        $data['data']  = $this->menu->get_by_id($id);
        $this->layout->show('backend/menu/editar', array('data' => $data));
    }
    
    public function eliminar($id)
    {	
            if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
            }
            $this->menu->delete($id);
            $this->session->set_flashdata('message', 'Se ha eliminado correctamente');
            redirect("backend/menu/index");
    }
}
?>