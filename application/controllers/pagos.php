<?php

use GuzzleHttp\json_encode;

class Pagos extends CI_Controller
{

    var $dbConnection;

    function __construct()
    {

        parent::__construct();


        //opciones
        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);


        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        $this->load->model("pagos_model", 'pagos');

        $this->pagos->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        $this->load->model("facturas_model", 'facturas');

        $this->facturas->initialize($this->dbConnection);


        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);


        $this->load->model("ventas_model", 'ventas');

        $this->ventas->initialize($this->dbConnection);



        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $this->load->model("Caja_model", 'caja');
        $this->caja->initialize($this->dbConnection);

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }



    public function ver_pago($id, $offset = 0)
    {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();
        $data['venta_credito'] = array(

            'venta' => $this->ventas->get_by_id($id), 'detalle_venta' => $this->ventas->get_detalles_ventas($id), 'detalle_pago' =>  $this->ventas->get_detalles_pago($id), 'data_empresa' =>  $data_empresa

        );

        $data['tipo'] = $this->pagos->get_tipos_pago();
        $data["total"] = $this->pagos->get_total($id);
        $data["data"] = $this->pagos->get_all($id, $offset);
        $data["forma_pago"] = $this->forma_pago->getAvaible();
        $numero = $this->ventas->get_by_id($id);
        $data['numero'] = $numero["factura"];
        $data["id_factura"] = $id;
        $data["estado_caja"] = "cerrada";

        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = $this->session->userdata('user_id');

        //verifico si la caja esta abierta
        if ($this->session->userdata('caja') != "") {
            $data["estado_caja"] = "abierta";
        } else {

            if ($data_empresa['data']['valor_caja'] == 'si') {
                // Si el cierre de caja es automatico           
                if ($data_empresa['data']['cierre_automatico'] == '1') {
                    $hoy = date("Y-m-d");
                    $where = array('id_Usuario' => $this->session->userdata('user_id'), 'fecha' => $hoy);
                } else {
                    $where = array('id_Usuario' => $this->session->userdata('user_id'));
                }

                $orderby_cierre = "fecha desc, hora_apertura desc";
                $limit_cierre = "1";
                $cierre_caja = $this->caja->get_id_caja_en_cierre_caja($where, $orderby_cierre, $limit_cierre);

                if ((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))) {
                    $this->session->set_userdata('caja', $cierre_caja->id);
                    $data["estado_caja"] = "abierta";
                }
            } else {
                $data["estado_caja"] = "abierta";
            }
        }

        $this->layout->template('member')->show('pagos/ver_pago', array('data' => $data));
    }

    public function ver_pago_ajax($id, $offset = 0)
    {
        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $pagos = $this->pagos->get_tipos_pago();
        $this->forma_pago->actualizarTabla($pagos);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();
        $data['venta_credito'] = array(

            'venta' => $this->ventas->get_by_id($id), 'detalle_venta' => $this->ventas->get_detalles_ventas($id), 'detalle_pago' =>  $this->ventas->get_detalles_pago($id), 'data_empresa' =>  $data_empresa

        );

        $data['tipo'] = $this->pagos->get_tipos_pago();
        $data["total"] = $this->pagos->get_total($id);
        $data["data"] = $this->pagos->get_all($id, $offset);
        $data["forma_pago"] = $this->forma_pago->getAvaible();
        $numero = $this->ventas->get_by_id($id);
        $data['numero'] = $numero["factura"];
        $data["id_factura"] = $id;
        $data["estado_caja"] = "cerrada";

        $username = $this->session->userdata('username');
        $db_config_id = $this->session->userdata('db_config_id');
        $id_user = $this->session->userdata('user_id');

        //verifico si la caja esta abierta
        if ($this->session->userdata('caja') != "") {
            $data["estado_caja"] = "abierta";
        } else {

            if ($data_empresa['data']['valor_caja'] == 'si') {
                // Si el cierre de caja es automatico           
                if ($data_empresa['data']['cierre_automatico'] == '1') {
                    $hoy = date("Y-m-d");
                    $where = array('id_Usuario' => $this->session->userdata('user_id'), 'fecha' => $hoy);
                } else {
                    $where = array('id_Usuario' => $this->session->userdata('user_id'));
                }

                $orderby_cierre = "fecha desc, hora_apertura desc";
                $limit_cierre = "1";
                $cierre_caja = $this->caja->get_id_caja_en_cierre_caja($where, $orderby_cierre, $limit_cierre);

                if ((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))) {
                    $this->session->set_userdata('caja', $cierre_caja->id);
                    $data["estado_caja"] = "abierta";
                }
            } else {
                $data["estado_caja"] = "abierta";
            }
        }

        echo json_encode($data);
    }



    public function cantidad($str)

    {

        $cantidad = $this->dbConnection->from('pagos')

            ->where('id_factura', $this->input->post('id_factura'))

            ->select('sum(cantidad) as cantidad')

            ->get()

            ->row()->cantidad;

        $monto = $this->dbConnection->from('facturas')

            ->where('id_factura', $this->input->post('id_factura'))

            ->select('monto')

            ->get()

            ->row()->monto;

        if (($cantidad + $this->input->post('cantidad')) <= $monto) {

            return TRUE;
        } else {

            $this->form_validation->set_message('cantidad', '%s excedida id factura ' . $this->input->post('id_factura'));

            return FALSE;
        }
    }

    function caja_abierta()
    {
        $band = 0;
        $data_empresa = $this->mi_empresa->get_data_empresa();

        //verifico si la caja esta abierta
        //verifico si hay caja abierta y no la tengo en session 
        //verifico si hay una caja abierta para el usuario
        //verifico si hay cierre automatico
        if ($data_empresa['data']['valor_caja'] == 'si') {
            // Si el cierre de caja es automatico           
            if ($data_empresa['data']['cierre_automatico'] == '1') {
                $hoy = date("Y-m-d");
                $where = array('id_Usuario' => $this->session->userdata('user_id'), 'fecha' => $hoy);
            } else {
                $where = array('id_Usuario' => $this->session->userdata('user_id'));
            }

            $orderby_cierre = "fecha desc, hora_apertura desc";
            $limit_cierre = "1";
            $cierre_caja = $this->caja->get_id_caja_en_cierre_caja($where, $orderby_cierre, $limit_cierre);

            if ((isset($cierre_caja->id) && ($cierre_caja->total_cierre == ""))) {
                $this->session->set_userdata('caja', $cierre_caja->id);
                $band = 1;
            } else {
                $this->session->unset_userdata('caja');
                $band = 0;
            }
        } else {
            $band = 1;
        }
        return $band;
    }

    public function nuevo($id)
    {


        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }


        $band = $this->caja_abierta();

        if (($this->input->post('cantidad') > 0) && ($band == 1)) {
            $this->pagos->add();
            $this->session->set_flashdata('message', custom_lang('sima_payment_created_message', 'Pago creado correctamente'));
        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar un pago'));
            } else {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'La cantidad debe ser mayor a 0'));
            }
        }

        redirect('pagos/ver_pago/' . $id);
    }

    public function nuevo_ajax($id)
    {


        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $band = $this->caja_abierta();
        $message ="";

        if (($this->input->post('cantidad') > 0) && ($band == 1)) {
            $this->pagos->add();
            $this->session->set_flashdata('message', custom_lang('sima_payment_created_message', 'Pago creado correctamente'));
            $message = 'Pago creado correctamente';
        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar un pago'));
                $message = 'Debe tener caja abierta para realizar un pago';
            } else {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'La cantidad debe ser mayor a 0'));
                $message = 'La cantidad debe ser mayor a 0';
            }
        }
        $response = array("message" => $message);
        echo json_encode($response);
    }


    public function editar($id)
    {



        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        if ($this->form_validation->run('pagos') == true) {

            $this->pagos->update();

            $this->session->set_flashdata('message', custom_lang('sima_payment_updated_message', 'Pago actualizado correctamente'));

            redirect('pagos/ver_pago/' . $id);
        }



        $data = array();

        $numero = $this->facturas->get_by_id($id);

        $data['numero'] = $numero["numero"];

        $data['tipo'] = $this->pagos->get_tipos_pago();

        $data['data']  = $this->pagos->get_by_id($id);

        //$data["id_factura"] = $id_factura;

        $this->layout->template('member')->show('pagos/editar', array('data' => $data));
    }



    public function eliminar($id)

    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $band = $this->caja_abierta();

        if ($band == 1) {
            $this->pagos->delete($id);
            $this->session->set_flashdata('message', custom_lang('sima_services_created_message', 'Se ha eliminado correctamente'));
            redirect("pagos/ver_pago/" . $_GET['factura']);
        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar este proceso'));
                redirect("pagos/ver_pago/" . $_GET['factura']);
            }
        }
    }

    public function eliminar_ajax($id)

    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $band = $this->caja_abierta();
        $message = "";

        if ($band == 1) {
            $this->pagos->delete($id);
            $this->session->set_flashdata('message', custom_lang('sima_services_created_message', 'Se ha eliminado correctamente'));

            $message = "Se ha eliminado correctamente";

            //redirect("pagos/ver_pago/" . $_GET['factura']);
        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar este proceso'));
                //redirect("pagos/ver_pago/" . $_GET['factura']);
                $message = "Debe tener caja abierta para realizar este proceso";

            }
        }
        $response = array("message" => $message);
        echo json_encode($response);
    }

    public function eliminar_orden_compra($id)

    {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $band = $this->caja_abierta();

        if ($band == 1) {

            $this->pagos->delete_orden($id);

            $this->session->set_flashdata('message', custom_lang('sima_services_created_message', 'Se ha eliminado correctamente'));

            redirect("orden_compra/pagos_servicio/" . $_GET['factura']);
        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar este proceso'));
                redirect("pagos/ver_pago/" . $_GET['factura']);
            }
        }
    }

    public function nuevo_orden_compra($id)
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $band = $this->caja_abierta();

        if (($this->input->post('cantidad') > 0) && ($band == 1)) {

            if($_POST['banco_asociado']){
                $_POST['tipo'] = 'bancos';
            }
            
            $this->pagos->add_orden_pago();
            //Se genera el nuevo pago de orden como un nuevo gasto
            $this->pagos->generarGasto();
            $this->session->set_flashdata('message', custom_lang('sima_payment_created_message', 'Pago creado correctamente'));
        } else {
            if ($band == 0) {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'Debe tener caja abierta para realizar un pago'));
            } else {
                $this->session->set_flashdata('message1', custom_lang('sima_payment_created_message', 'La cantidad debe ser mayor a 0'));
            }
        }

        redirect('orden_compra/pagos_servicio/' . $id);
    }

    function get_ajax_data()
    {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->pagos->get_ajax_data()));
    }
}
