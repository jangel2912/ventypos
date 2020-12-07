
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

class Sub_menu extends CI_Controller {

    //put your code here

    

    public function __construct()

    {

        parent::__construct();

         $this->load->library('pagination');

         $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        

        $this->load->model('backend/menu_model', 'menu');

        $this->load->model('backend/sub_menu_model', 'sub_menu');

    }

    

    public function index($offset = 0){

         if (!$this->ion_auth->is_admin()){

                $this->session->set_flashdata('message', 'Acceso limitado');

                redirect('frontend/acceso_limitado');

        }

        

        $data['data'] = $this->sub_menu->get_menus($offset);

        $data['total'] = $this->sub_menu->get_total();

        $this->layout->show('backend/sub_menu/index.php', array('data' => $data));

    }

    

     public function get_ajax_data(){

        $this->output->set_content_type('application/json')->set_output(json_encode($this->sub_menu->get_ajax_data()));

    }

    

    public function nuevo(){

         if (!$this->ion_auth->is_admin()){

                $this->session->set_flashdata('message', 'Acceso limitado');

                redirect('frontend/acceso_limitado');

        }

        

        if ($this->form_validation->run('sub_menu') == true)

        {

            $this->sub_menu->add();

            $this->session->set_flashdata('message', 'Submenu creado correctamente');

            redirect('backend/sub_menu/index');

        }            

        $data = array();

        $menues = array();

        foreach ($this->menu->get_menus() as $value) {

            $menues[$value->id_menu] = $value->nombre_link;

        }

        $data['menu'] = $menues;

        $this->layout->show('backend/sub_menu/nuevo', array('data' => $data));

    }

    

    public function editar($id){

        if (!$this->ion_auth->is_admin()){

                $this->session->set_flashdata('message', 'Acceso limitado');

                redirect('frontend/acceso_limitado');

        }

        if ($this->form_validation->run('sub_menu') == true)

            {

                $this->sub_menu->update();	

                $this->session->set_flashdata('message', 'Submenu actualizado correctamente');

                redirect("backend/sub_menu/index");

            }



        $data = array(); 

        $menues = array();

        foreach ($this->menu->get_menus() as $value) {

            $menues[$value->id_menu] = $value->nombre_link;

        }

        $data['menu'] = $menues;

        $data['data']  = $this->sub_menu->get_by_id($id);

        $this->layout->show('backend/sub_menu/editar', array('data' => $data));

    }

    

    public function eliminar($id)

    {	

            if (!$this->ion_auth->is_admin()){

                $this->session->set_flashdata('message', 'Acceso limitado');

                redirect('frontend/acceso_limitado');

            }

            $this->sub_menu->delete($id);

            $this->session->set_flashdata('message', 'Se ha eliminado correctamente');

            redirect("backend/sub_menu/index");

    }

}

?>