<?php

class Crm_oportunidades extends CI_Controller {

    var $dbConnection;

    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->load->model(array('crm_model', 'crm_oportunidades_model'));
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    public function index() {
        $this->layout->template('member')->show('crm/index');
    }

    public function oportunidad_modal() {
        $id_oportunidad = $this->input->post('id_oportunidad');
        $data = array();
        if ($id_oportunidad != false) {
            $data['oportunidad'] = $this->crm_oportunidades_model->get_data(null, $id_oportunidad);
        }
        $data['id_crm'] = $this->input->post('id_crm');
        $data['select_probabilidad'] = array(
            0 => 0,
            10 => 10,
            20 => 20,
            30 => 30,
            40 => 40,
            50 => 50,
            60 => 60,
            70 => 70,
            80 => 80,
            90 => 90,
            100 => 100,
        );
        $user_log = $this->crm_model->get_usuario_email($this->session->userdata('email'));
        $data['user_session_id'] = $user_log['id'];
        $data['user_session'] = $user_log;
        $data['select_estados'] = $this->crm_model->select_estados();
        $data['select_justificacion'] = $this->crm_model->select_justificacion();
        $data['select_plan'] = $this->crm_model->select_plan();
        $data['select_crm_usuarios'] = $this->crm_model->select_crm_usuarios();

        $view = $this->load->view('crm/oportunidad_modal', $data, true);

        return $this->output
                        ->set_header("HTTP/1.0 200 OK")
                        ->set_content_type('application/json')
                        ->set_output(json_encode($view));
    }

    public function save_oportunidad() {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->form_validation->set_rules('id_crm', 'Usuario', 'required|xss_clean');
            $this->form_validation->set_rules('nombre', 'Nombre', 'required|xss_clean');
            $this->form_validation->set_rules('monto', 'Monto', 'required|numeric|xss_clean');
            $this->form_validation->set_rules('punto_venta', 'Puntos de venta', 'required|numeric|xss_clean');
            $this->form_validation->set_rules('id_usuario', 'Asignado', 'required|xss_clean');
            $this->form_validation->set_rules('id_plan', 'Plan', 'required|xss_clean');
            $this->form_validation->set_rules('id_estado', 'Estado', 'required|xss_clean');
            $this->form_validation->set_rules('descripcion', 'Descripción', 'required|xss_clean');
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'error' => validation_errors(),
                    'res' => 'error'
                );
            } else {
                $data_array = array(
                    'id_crm' => $this->input->post('id_crm'),
                    'nombre' => $this->input->post('nombre'),
                    'monto' => $this->input->post('monto'),
                    'punto_venta' => $this->input->post('punto_venta'),
                    'fecha_cierre' => $this->input->post('fecha_cierre'),
                    'id_usuario' => $this->input->post('id_usuario'),
                    'id_plan' => $this->input->post('id_plan'),
                    'id_estado' => $this->input->post('id_estado'),
                    'descripcion' => $this->input->post('descripcion')
                );
                if ($this->input->post('id_oportunidad')) {
                    // Update
                    $this->db->where('id', $this->input->post('id_oportunidad'));
                    $this->db->update('crm_oportunidades', $data_array);

                    $estado_anterior = $this->db->select('id_estado')
                                    ->from('crm_oportunidades')
                                    ->where('id', $this->input->post('id_oportunidad'))
                                    ->get()->row_array();

                    // Validamos si se cambia de estado
                    if ($estado_anterior['id_estado'] != $this->input->post('id_estado')) {
                        $data_estados = array(
                            'id_oportunidad' => $this->input->post('id_oportunidad'),
                            'id_estado' => $this->input->post('id_estado'),
                            'fecha' => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('crm_fecha_estados', $data_estados);
                    }
                } else {
                    // Insert
                    $this->db->insert('crm_oportunidades', $data_array);
                    $id_oportunidad = $this->db->insert_id();

                    $data_estados = array(
                        'id_oportunidad' => $id_oportunidad,
                        'id_estado' => $this->input->post('id_estado'),
                        'fecha' => date('Y-m-d H:i:s'),
                    );
                    $this->db->insert('crm_fecha_estados', $data_estados);
                }
                $data = array(
                    'success' => "Se ha guardado con éxito!",
                    'res' => 'success'
                );
            }
            echo json_encode($data);
        }
    }

    public function change_estado() {
        if ($this->input->is_ajax_request()) {
            
            $data_array = array(
                'id_estado' => $this->input->post('id_estado'),
            );
            $this->db->where('id', $this->input->post('id_oportunidad'));
            $this->db->update('crm_oportunidades', $data_array);

            // Validamos si se cambia de estado
            $data_estados = array(
                'id_oportunidad' => $this->input->post('id_oportunidad'),
                'id_estado' => $this->input->post('id_estado'),
                'fecha' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('crm_fecha_estados', $data_estados);

            $data = array(
                'success' => "Se ha guardado con éxito!",
                'res' => 'success'
            );

            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
            
        }
    }

}
