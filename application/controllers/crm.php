<?php
class Crm extends CI_Controller {
    var $dbConnection;

    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->load->model(array('crm_model','crm_oportunidades_model'));
//        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
        $user_log = $this->crm_model->get_usuario_email($this->session->userdata('email'));
        if(count($user_log)<1){
            redirect('frontend', 'refresh');
        }
    }

    public function index() {
        $this->layout->template('member')->show('crm/index');
    }
    
    public function dashboard(){
        $data['dashboard'] = $this->crm_oportunidades_model->get_dashboard_data($this->session->userdata('email'));
        $data['alertas'] = $this->crm_model->get_alertas($this->session->userdata('email'));
        $this->layout->template('member')->show('crm/dashboard',$data);
    }
    
    public function contactos(){
        $data['seguimientos'] = $this->crm_model->get_total_data();
        $this->layout->template('member')->show('crm/contactos',$data);
    }
    
    public function view($user_id = null){
        
        if(isset($user_id)){
            $data['seguimiento'] = $this->crm_model->get_total_data($user_id);
            $data['actividades']  = $this->crm_model->get_data_actividad($user_id);
            $data['alertas']  = $this->crm_model->get_data_alerta($user_id);
            $data['oportunidades']  = $this->crm_oportunidades_model->get_data($user_id);
        }
        
        $data['select_tipo_negocio'] = $this->crm_model->select_tipo_negocio();
        $data['select_estados'] = $this->crm_model->select_estados();
        
        
        $this->layout->template('member')->show('crm/view',$data);
    }   
    
    public function actividad_modal() {
        $actividad_id = $this->input->post('actividad_id');
        $data = array();
        if ($actividad_id != false) {
            $data['actividad'] = $this->crm_model->get_data_actividad(null,$actividad_id);
        }
        $data['id_crm'] = $this->input->post('id_crm');
        $data['select_tipo_actividad'] = $this->crm_model->select_tipo_actividad();
        $data['select_crm_usuarios'] = $this->crm_model->select_crm_usuarios();
       
        $view = $this->load->view('crm/actividad_modal', $data,true);
        
        return $this->output
                ->set_header("HTTP/1.0 200 OK")
                ->set_content_type('application/json')
                ->set_output(json_encode($view));
        
    }
    public function alerta_modal() {
        $alerta_id = $this->input->post('alerta_id');
        $data = array();
        if ($alerta_id != false) {
            $data['alerta'] = $this->crm_model->get_data_alerta(null,$alerta_id);
        }
        $data['id_crm'] = $this->input->post('id_crm');
        $data['select_tipo_actividad'] = $this->crm_model->select_tipo_actividad();
        $data['select_crm_usuarios'] = $this->crm_model->select_crm_usuarios();
        for($i = 0;$i<24;$i++){
            $data['select_hora'][$i.':00'] = $i.':00';
        }
        
       
        $view = $this->load->view('crm/alerta_modal', $data,true);
        
        return $this->output
                ->set_header("HTTP/1.0 200 OK")
                ->set_content_type('application/json')
                ->set_output(json_encode($view));
    }
    
    public function save_customer(){
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean');
            $this->form_validation->set_rules('mail', 'Email', 'required|valid_email|xss_clean');
            $this->form_validation->set_rules('tipo_negocio', 'Tipo de Negocio', 'required|xss_clean');
            
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                
                
                $data_array = array(
                    'nombre' => $this->input->post('nombre'),
                    'identificacion' => $this->input->post('identificacion'),
                    'empresa' => $this->input->post('empresa'),
                    'mail' => $this->input->post('mail'),
                    'telefono' => $this->input->post('telefono'),
                    'celular' => $this->input->post('celular'),
                    'pais' => $this->input->post('pais'),
                    'ciudad' => $this->input->post('ciudad'),
                    'direccion' => $this->input->post('direccion'),
                    'tipo_negocio' => $this->input->post('tipo_negocio'),
                );
                
                if($this->input->post('id_crm')){
                    $this->db->where('id', $this->input->post('id_crm'));
                    $this->db->update('crm', $data_array);
                    $id_crm = $this->input->post('id_crm');
                    
                }else{
                    $data_array['fecha_creacion'] = date('Y-m-d H:i:s');
                    $user_log = $this->crm_model->get_usuario_email($this->session->userdata('email'));
                    $data_array['usuario'] = $user_log['id'];
                    $this->db->insert('crm', $data_array);
                    $id_crm = $this->db->insert_id();
                }
                
                $data = array(
                    'success' => "Se ha guardado con éxito!",
                    'res' => 'success',
                    'id_crm' => $id_crm
                );
            }
            // Respuesta en Json
            //echo json_encode($data);
            // Respuesta en Json
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
        }
    }
    
    public function save_actividad(){
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('tipo_actividad', 'Tipo Actividad', 'required|xss_clean');
            $this->form_validation->set_rules('nota', 'Nota', 'required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                $user_log = $this->crm_model->get_usuario_email($this->session->userdata('email'));
                $data_array = array(
                    'id_crm' => $this->input->post('id_crm'),
                    'fecha' => date('Y-m-d H:i:s'),
                    'usuario' => $user_log['id'],
                    'tipo_actividad' => $this->input->post('tipo_actividad'),
                    'nota' => $this->input->post('nota')
                );
                if($this->input->post('id_crm_actividades')){
                    // Update
                    unset($data_array['fecha']);
                   $this->db->where('id', $this->input->post('id_crm_actividades'));
                    $this->db->update('crm_actividades', $data_array);  
                }else{
                    // Insert
                    $this->db->insert('crm_actividades', $data_array); 
                }
                $data = array(
                    'success' => "Se ha guardado con éxito!",
                    'res' => 'success'
                );
            }
            echo json_encode($data);
        }
    }
    
    public function save_alerta(){
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('fecha_programada', 'Fecha', 'required|xss_clean');
            $this->form_validation->set_rules('tipo_actividad', 'Tipo Actividad', 'required|xss_clean');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                $user_log = $this->crm_model->get_usuario_email($this->session->userdata('email'));
                $data_array = array(
                    'id_crm' => $this->input->post('id_crm'),
                    'fecha_programada' => $this->input->post('fecha_programada'),
                    'hora' => $this->input->post('hora'),
                    'fecha_cierre' => $this->input->post('fecha_cierre'),
                    'usuario' => $user_log['id'],
                    'tipo_actividad' => $this->input->post('tipo_actividad'),
                    'descripcion' => $this->input->post('descripcion'),
                    'activo' => $this->input->post('activo')
                );
                if($this->input->post('id_crm_alerta')){
                    // Update
                   $this->db->where('id', $this->input->post('id_crm_alerta'));
                    $this->db->update('crm_alertas', $data_array);  
                }else{
                    // Insert
                    $this->db->insert('crm_alertas', $data_array); 
                }
                $data = array(
                    'success' => "Se ha guardado con éxito!",
                    'res' => 'success'
                );
            }
            echo json_encode($data);
        }
    }

}
