<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin.php
 *
 * @author Locho
 */
class Dashboard extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
    
    public function index(){
        if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        else{
            $this->load->model('backend/usuarios_model', 'usuarios');
            $this->load->model('backend/menu_model', 'menus');
            $this->load->model('backend/sub_menu_model', 'submenus');
            $data = array();
            $data['total_users'] = $this->usuarios->get_total();
            $data['total_active'] = $this->usuarios->get_total_active();
            $data['total_deactive'] = $this->usuarios->get_total_deactive();
            $data['total_menu'] = $this->menus->get_total();
            $data['total_submenu'] = $this->submenus->get_total();
            $this->layout->show('backend/dashboard/index.php', array('data' => $data));
        }
    }
}
?>