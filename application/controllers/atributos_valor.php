<?php

class Atributos_valor extends CI_Controller {

    var $dbConnection;

    function __construct() {
        parent::__construct();

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("atributos_model", 'atributos');
        $this->atributos->initialize($this->dbConnection);

        $this->load->model("atributos_valor_model", 'atributos_valor');
        $this->atributos_valor->initialize($this->dbConnection);

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    public function index() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('atributos_valor/index');
        
    
    }

    public function get_ajax_data() {
        
        $this->output->set_content_type('application/json')->set_output(json_encode($this->atributos_valor->get_ajax_data()));
        
    }

    public function nuevo() {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($_POST) {

            $data = array(
                'atributo_id' => $this->input->post('atributo_id'),
                'valor' => $this->input->post('valor')
            );

            $this->atributos_valor->add($data);

            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Atributo creado correctamente'));
            redirect('atributos_valor/index');
        }

        $atributos = $this->atributos->get_combo_data();
        $this->layout->template('member')->show('atributos_valor/nuevo', array('atributos' => $atributos));
    }

    public function editar($id) {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('atributos_valor') == true) {
            $data = array(
                'id_atributo_valor' => $this->input->post('id')
                , 'valor' => $this->input->post('valor')
                , 'atributo_id' => $this->input->post('atributo_id')
            );
            $this->atributos_valor->update($data);

            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Atributo actualizado correctamente'));
            redirect('atributos_valor/index');
        }

        $data = array();
        $data['data'] = $this->atributos_valor->get_by_id($id);
        $data['atributos'] = $this->atributos->get_combo_data();
        $this->layout->template('member')->show('atributos_valor/editar', array('data' => $data));
    }

    public function eliminar($id) {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $this->atributos_valor->delete($id);
        $this->session->set_flashdata('message', custom_lang('sima_category_deleted_message', 'Se ha eliminado correctamente'));
        redirect("atributos_valor/index");
    }

}

?>
