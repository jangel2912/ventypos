<?php

class Forma_Pago extends CI_Controller
{
    public $dbConnection;

    public function __construct()
    {

        parent::__construct();

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("ventas_pago_model", 'ventas_pago');
        $this->ventas_pago->initialize($this->dbConnection);

        $this->load->model("pagos_model", 'pagos');
        $this->pagos->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        if ($this->session->userdata('is_admin') == "t") {
            $this->layout->template('member')->show('forma_pago/index', array("data" => $data));
        } else {
            redirect(site_url('frontend/index'));
        }
    }

    public function get_ajax_data()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->forma_pago->get_ajax_data()));
    }

    public function nuevo()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run("forma_pago") == true) {

            $array = array(
                'nombre' => $this->input->post('nombre'),
                'tipo' => (!isset($_POST['tipo'])) ? "" : $_POST['tipo'],
                'activo' => (!isset($_POST['activo'])) ? "0" : $_POST['activo'],
                'tipo' => (!isset($_POST['tipo'])) ? "" : $_POST['tipo'],
                'codigo' => str_replace(" ", "_", $this->input->post('nombre')),
            );
            $this->forma_pago->insertar($array);

            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Forma de pago creada correctamente'));
            redirect(site_url("forma_pago/index"));
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('forma_pago/nuevo', array("data" => $data));
    }

    public function editar($id)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        $data['forma'] = $this->forma_pago->get($id);
        if ($this->form_validation->run("forma_pago") == true) {
            /*if($data['forma']->eliminar == 0)
            {
            $array = array(
            'activo' => $this->input->post('activo')
            );
            $this->forma_pago->modificar($array,$id);
            }else{*/
            $array = array(
                'nombre' => $this->input->post('nombre'),
                'tipo' => (!isset($_POST['tipo'])) ? "" : $_POST['tipo'],
                'activo' => (!isset($_POST['activo'])) ? "0" : $_POST['activo'],
                'tipo' => (!isset($_POST['tipo'])) ? "" : $_POST['tipo'],
                'codigo' => str_replace(" ", "_", $this->input->post('nombre')),
            );
            $this->forma_pago->modificar($array, $id);
            //}

            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Forma de pago modificada correctamente'));
            redirect(site_url("forma_pago/index"));
        }

        $this->layout->template('member')->show('forma_pago/editar', $data);
    }

    public function eliminar($id)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data['forma'] = $this->forma_pago->get($id);
        var_dump($data['forma']->eliminar);
        if ($data['forma']->eliminar == 1) {
            $ventas = $this->ventas_pago->consultarFormaPago($data['forma']->codigo);

            if ($ventas == true) {
                $this->forma_pago->eliminar($id);
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La forma de pago fue eliminada correctamente'));
                redirect(site_url("forma_pago/index"));
            } else {
                $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La forma de pago hay ventas pagadas con esa forma de pago por lo que no puede ser eliminada'));
                redirect(site_url("forma_pago/index"));
            }
        } else {
            $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'La forma de pago viene por defecto y no puede ser eliminada'));
            redirect(site_url("forma_pago/index"));
        }
    }

    public function activo()
    {
        if (isset($_POST)) {
            $this->forma_pago->modificar(array("activo" => $_POST['activo']), $_POST['id']);
            echo json_encode(array(
                "resp" => 1,
                "texto" => ($_POST['activo'] == 1) ? "Si" : "No",
            ));
            die;
        }
        echo json_encode(array("resp" => 0));
    }
}
