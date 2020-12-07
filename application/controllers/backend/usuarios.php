<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuarios
 *
 * @author Locho
 */
class Usuarios extends CI_Controller {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->helper('language');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $this->load->model('backend/db_config/db_config_model', 'dbconfig');
    }
    
    public function index(){
         if (!$this->ion_auth->is_admin()){
                $this->session->set_flashdata('message', 'Acceso limitado');
                redirect('frontend/acceso_limitado');
        }
        else{
            
           /* $usuarios['users'] = $this->ion_auth->users()->result();
            
			foreach ($usuarios['users'] as $k => $user)
			{
				$usuarios['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}*/
            
            $this->layout->show('backend/usuarios/index.php', array(/*'users' => $usuarios['users'], */'message' => ''));
        }
    }
    
     public function get_ajax_data(){
        $this->load->model('backend/usuarios_model', 'usuarios');
        $usuarios = $this->usuarios->get_ajax_data();
        foreach ($usuarios['aaData'] as $k => $user) {
             $usuarios['aaData'][$k][4] = $this->ion_auth->get_users_groups($usuarios['aaData'][$k][5])->result();
        
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($usuarios));
    }
    
    //create a new user
	function create_user()
	{
                $data = array("message" => "");
		$data['title'] = "Create User";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                else if(!$this->ion_auth->is_admin())
                    {
                        redirect('frontend/acceso_limitado');
                    }

		//validate form input
                    
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
                $this->form_validation->set_rules('db_config_id', "Base de datos", 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		$this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'required|xss_clean|max_length[10]');
		$this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone1'),
                                //'db_config_id' => $this->input->post('db_config_id')
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
		{
			//check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("backend/usuarios/index", 'refresh');
		}
		else
		{
			$data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
                                'placeholder' => 'Nombre'
			);
			$data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
                                 'placeholder' => 'Apellido'
			);
			$data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
                                 'placeholder' => 'Correo electr&oacute;nico'
			);
			$data['company'] = array(
				'name'  => 'company',
				'id'    => 'company',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('company'),
                                'placeholder' => 'Compa&ntilde;ia'
			);
			$data['phone1'] = array(
				'name'  => 'phone1',
				'id'    => 'phone1',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone1'),
                                'placeholder' => 'Tel&eacute;fono'
			);
			$data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
                                 'placeholder' => 'Clave'
			);
			$data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
                                'placeholder' => 'Repita la claves'
			);
                        $data['dbconfig'] = $this->dbconfig->get_combo_array();
                        $this->layout->show('backend/usuarios/create_user.php', array('data' => $data));
		}
	}
        
        //edit a user
	function edit_user($id)
	{
           
		$data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                else if(!$this->ion_auth->is_admin()){
                    redirect('frontend/acceso_limitado');
                }

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		//process the phone number
		if (isset($user->phone) && !empty($user->phone))
		{
			$user->phone = explode('-', $user->phone);
		}

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('phone1', $this->lang->line('edit_user_validation_phone1_label'), 'required|xss_clean|min_length[3]|max_length[3]');
		$this->form_validation->set_rules('phone2', $this->lang->line('edit_user_validation_phone2_label'), 'required|xss_clean|min_length[3]|max_length[3]');
		$this->form_validation->set_rules('phone3', $this->lang->line('edit_user_validation_phone3_label'), 'required|xss_clean|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');
		$this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if (/*$this->_valid_csrf_nonce() === FALSE ||*/ $id != $this->input->post('id'))
			{
				show_error("Ha ocurrido un error");
			}
                       
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => $this->input->post('company'),
				'phone'      => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
			);

			//Update the groups user belongs to
			$groupData = $this->input->post('groups');

			if (isset($groupData) && !empty($groupData)) {

				$this->ion_auth->remove_from_group('', $id);

				foreach ($groupData as $grp) {
					$this->ion_auth->add_to_group($grp, $id);
				}

			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

				$data['password'] = $this->input->post('password');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$this->ion_auth->update($user->id, $data);

				//check to see if we are creating the user
				//redirect them back to the admin page
				$this->session->set_flashdata('message', "User Saved");
				redirect("backend/usuarios/index", 'refresh');
			}
		}
               
		//display the edit user form
		$data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$data['user'] = $user;
		$data['groups'] = $groups;
		$data['currentGroups'] = $currentGroups;

		$data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$data['phone1'] = array(
			'name'  => 'phone1',
			'id'    => 'phone1',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
		);
		$data['phone2'] = array(
			'name'  => 'phone2',
			'id'    => 'phone2',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone2', isset($user->phone[1]) ? $user->phone[1] : ""),
		);
		$data['phone3'] = array(
			'name'  => 'phone3',
			'id'    => 'phone3',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone3', isset($user->phone[2]) ? $user->phone[2] : ""),
		);
		$data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);
               
                $this->layout->show('backend/usuarios/edit_user.php', array('data' => $data));
	}
        
        // create a new group
	function create_group()
	{
		$data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                else if(!$this->ion_auth->is_admin()){
                    redirect('frontend/acceso_limitado');
                }

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("backend/usuarios/index", 'refresh');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);
                        $this->layout->show('backend/usuarios/create_group', array('data' => $data));    
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('backend/usuario/index', 'refresh');
		}

		$data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}
                else if(!$this->ion_auth->is_admin()){
                     redirect('frontend/acceso_limitado');
                }

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("backend/usuarios/index", 'refresh');
			}
		}

		//set the flash data error message if there is one
		$data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$data['group'] = $group;

		$data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);
                $this->layout->show('backend/usuarios/edit_group', array('data' => $data));           
		
	}
        
        //activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("backend/usuarios/index", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		//$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$data['csrf'] = $this->_get_csrf_nonce();
			$data['user'] = $this->ion_auth->user($id)->row();

			//$this->_render_page('auth/deactivate_user', $data);
                        $this->layout->show('backend/usuarios/deactivate_user.php', array('data' => $data));
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if (/*$this->_valid_csrf_nonce() === FALSE ||*/ $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('backend/usuarios/index', 'refresh');
		}
	}
        
        function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
              
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
        
        public function delete($id){
             $this->load->model('backend/usuarios_model', 'usuarios');
             $this->usuarios->eliminar($id);
             $this->session->set_flashdata('message',  'Se ha eliminado correctamente');
             redirect('backend/usuarios');
        }
}
?>