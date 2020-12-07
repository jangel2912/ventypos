<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

//2015/12/18
class Informes extends CI_Controller
{

    //put your code here

    const PLAN_SEPARE = 2;
    const ATRIBUTOS = 3;
    const PUNTOS = 4;

    public $user;

    public function __construct()
    {

        parent::__construct();

        $this->user = $this->session->userdata('user_id');

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        if ($this->session->userdata('usuario') !== false) {
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
        } else {
            $this->dbConnection = $this->load->database();
        }

        $this->load->library('Encryption');
        $this->load->model("informes_model", 'informes');

        $this->informes->initialize($this->dbConnection);

        $this->load->model("pagos_model", 'pagos');
        $this->pagos->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');
        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacen');
        $this->almacen->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categoria');
        $this->categoria->initialize($this->dbConnection);

        $this->load->model("lista_precios_model", 'lista_precios');
        $this->lista_precios->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("atributos_model", 'atributos');
        $this->atributos->initialize($this->dbConnection);

        $this->load->model("cuentas_siigo_model", 'cuentasSiigo');
        $this->cuentasSiigo->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        $this->load->model("opciones_model", "opciones");
        $this->opciones->initialize($this->dbConnection);

        // Carga de modelo de franquicias.
        $this->load->model('franquicias_model', 'franquicias');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

        $this->load->model("vendedores_model", 'vendedores');
        $this->vendedores->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("mesas_secciones_model", 'mesas_secciones');
        $this->mesas_secciones->initialize($this->dbConnection);

        $this->load->model("ventas_model", 'ventas');
        $this->ventas->initialize($this->dbConnection);

        $this->load->model("bancos_model", 'bancos');
        $this->bancos->initialize($this->dbConnection);

        $this->load->model("domiciliarios_model", 'domiciliarios');
        $this->domiciliarios->initialize($this->dbConnection);

        $this->load->model("secciones_almacen_model", 'secciones_almacen');
        $this->secciones_almacen->initialize($this->dbConnection);

        $this->load->model("ordenes_model", 'ordenes');
        $this->ordenes->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        //creo la opcion para restaurante
        $this->mi_empresa->crearOpcion('domicilios', 'no');
        //crear tabla de domiciliarios
        $this->domiciliarios->crear_domiciliarios();

        if ((!empty($data_empresa['data']['tipo_negocio'])) && ($data_empresa['data']['tipo_negocio'] == "restaurante")) {
            $this->vendedores->actualizarTablaparaEstacion();
            $this->almacenes->actualizarTablaAlmacenordenRestaurant();
            $this->mesas_secciones->agregarnota(0, 0);
            // CREAR CAMPO COMENSALES
            $this->ventas->add_campo_comensales($this->dbConnection);
            $this->mesas_secciones->add_campo_comensales($this->dbConnection);
            // FIN CREAR CAMPO COMENSALES

        }

    }

    public function total_ventas_atributos()
    {
        acceso_informe('Informe de Total de Ventas por Atributos');
        $data['almacen'] = $this->almacen->get_all('0');
        $data['categoria'] = $this->categoria->get_combo_data('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas_atributos', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function total_inventario_atributos()
    {
        acceso_informe('Informe de Total de Inventario por Atributos');
        $data['almacen'] = $this->almacen->get_all('0');
        $data['categoria'] = $this->categoria->get_combo_data('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_inventario_atributos', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function total_ventas_atributos_franquicia()
    {

        $data['franquicias'] = $this->franquicias->get_franquicias();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $this->layout->template('member')->show('informes/total_ventas_atributos_franquicia', ['data1' => $data]);
    }

    public function total_ventas_franquicia()
    {

        $data['franquicias'] = $this->franquicias->get_franquicias();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $this->layout->template('member')->show('informes/total_ventas_franquicia', ['data1' => $data]);
    }

    public function total_inventario_atributos_franquicia()
    {

        $data['franquicias'] = $this->franquicias->get_franquicias();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $this->layout->template('member')->show('informes/total_inventario_atributos_franquicia', ['data1' => $data]);
    }

    public function total_inventario_franquicia()
    {

        $data['franquicias'] = $this->franquicias->get_franquicias();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $this->layout->template('member')->show('informes/total_inventario_franquicia', ['data1' => $data]);
    }

    public function total_inventario_ajax_data_franquicia()
    {

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
    
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }
            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);
            //var_dump($f->id_proveedor);die;
            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f->id_proveedor,
                'activo' => $f->activo,
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            if ($conection['activo'] == '1') {
                array_push($resultados, $this->informes->total_inventario_franquicias($almacen, $conection));
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($resultados));
    }

    public function almacenes_franquicias()
    {
        if ($_POST['id_franquicia'] !== '0') {
            $user_db_connection = $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']);
            $this->almacen->initialize($user_db_connection);
            $data['almacen'] = $this->almacen->get_all('0');
            $this->output->set_content_type('application/json')->set_output(json_encode($data['almacen']));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode([]));
        }
    }

    public function filtro_atributos_categoria_franquicia()
    {
        $user_db_connection = $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']);
        $resultado = [];
        $categoria = 0;
        $categoria = $this->input->post('id', true);
        $resultado = $user_db_connection->query("SELECT (select nombre from atributos where atributos_posee_categorias.atributo_id = atributos.id) as nombre_atributo ,atributo_id FROM atributos_posee_categorias where categoria_id = '" . $categoria . "' ")->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }

    public function filtro_atributos_categoria()
    {
        $resultado = [];
        $categoria = 0;
        $categoria = $this->input->post('id', true);
        $resultado = $this->dbConnection->query("SELECT (select nombre from atributos where atributos_posee_categorias.atributo_id = atributos.id) as nombre_atributo ,atributo_id FROM atributos_posee_categorias where categoria_id = '" . $categoria . "' ")->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }

    public function filtro_atributos_detalle()
    {
        $resultado = [];
        $atributo = 0;
        $atributo = $this->input->post('id', true);
        $resultado = $this->dbConnection->query("SELECT id, valor FROM atributos_detalle where atributo_id = '" . $atributo . "' ORDER BY valor  ")->result();

        $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }

    public function filtro_atributos_detalle_franquicia()
    {
        $user_db_connection = $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']);
        $resultado = [];
        $atributo = 0;
        $atributo = $this->input->post('id', true);
        $resultado = $user_db_connection->query("SELECT id, valor FROM atributos_detalle where atributo_id = '" . $atributo . "' ")->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($resultado));
    }

    public function total_ventas_atributos_data()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $data['categoria'] = $this->categoria->get_combo_data('0');
        $resultados = $this->informes->total_ventas_atributos($fechainicial, $fechafinal, $almacen, $ciudad, $_POST, false);

        $this->__create_report_file_from_array($resultados['total_ventas'], 'Ventas productos con atributos', 'Ventas');
    }

    public function total_ventas_atributos_ajax_data()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $data['categoria'] = $this->categoria->get_combo_data('0');

        if (!isset($_POST['id_categoria'])) {
            $_POST['id_categoria'] = 0;
        }

        $resultados = $this->informes->total_ventas_atributos($fechainicial, $fechafinal, $almacen, $ciudad, $_POST, true);

        $this->output->set_content_type('application/json')->set_output(json_encode($resultados));
    }

    public function total_inventario_atributos_data()
    {
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $data['categoria'] = $this->categoria->get_combo_data('0');
        $resultados = $this->informes->total_inventario_atributos($almacen, $ciudad, $_POST);

        $this->__create_report_file_from_array($resultados['total_ventas'], 'Inventario productos con atributos', 'Ventas');
    }

    public function total_inventario_atributos_ajax_data()
    {
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $data['categoria'] = $this->categoria->get_combo_data('0');
        $resultados = $this->informes->total_inventario_atributos($almacen, $ciudad, $_POST);

        $this->output->set_content_type('application/json')->set_output(json_encode($resultados));
    }

    public function total_inventario_atributos_ajax_data_franquicia()
    {

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f['id_proveedor'],
                'activo' => $f['activo'],
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            if ($conection['activo'] == '1') {
                array_push($resultados, $this->informes->total_inventario_atributos_franquicias($almacen, '', $_POST, $conection));
            }

        }

        $this->output->set_content_type('application/json')->set_output(json_encode($resultados));
    }

    public function total_inventario_data_franquicia()
    {

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f->id_proveedor,
                'activo' => $f->activo,
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            if ($conection['activo'] == '1') {
                array_push($resultados, $this->informes->total_inventario_franquicias($almacen, $conection, $accion = false));
            }

        }

        $this->__create_report_file_from_multidimensional_array($resultados, 'Inventario productos franquicias', 'Ventas');
    }

    public function total_inventario_atributos_data_franquicia()
    {

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f['id_proveedor'],
                'activo' => $f['activo'],
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            if ($conection['activo'] == '1') {
                array_push($resultados, $this->informes->total_inventario_atributos_franquicias($almacen, '', $_POST, $conection));
            }

        }

        $this->__create_report_file_from_multidimensional_array($resultados, 'Inventario productos con atributos franquicias', 'Ventas');
    }

    public function total_ventas_atributos_ajax_data_franquicia()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f['id_proveedor'],
                'activo' => $f['activo'],
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            array_push($resultados, $this->informes->total_ventas_atributos_franquicias($fechainicial, $fechafinal, $almacen, '', $_POST, $conection));
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($resultados));
    }

    public function total_ventas_ajax_data_franquicia()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f->id_proveedor,
                'activo' => $f->activo,
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            array_push($resultados, $this->informes->total_ventas_franquicias($fechainicial, $fechafinal, $almacen, $conection));
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($resultados));
    }

    public function total_ventas_data_franquicia()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f->id_proveedor,
                'activo' => $f->activo,
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            array_push($resultados, $this->informes->total_ventas_franquicias($fechainicial, $fechafinal, $almacen, $conection, false));
        }

        $this->__create_report_file_from_multidimensional_array($resultados, 'Ventas productos franquicias', 'Ventas');
    }

    public function total_ventas_atributos_data_franquicia($id_franquicia)
    {

        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');

        $franquicias = $this->franquicias->get_franquicias();
        $almacen = $this->input->post('almacen');
        $connections = [];
        if ($_POST['id_franquicia'] == 0) {
            foreach ($franquicias as $franquicia) {
                array_push($connections, [
                    'id_franquicia' => $franquicia['id'],
                    'id_proveedor' => $franquicia['id_proveedor'],
                    'activo' => $franquicia['activo'],
                    'nombre' => $franquicia['nombre_empresa'],
                    'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($franquicia['id']),
                ]);
            }
        } else {
            $nombre_empresa = '';
            foreach ($franquicias as $franquicia) {
                if ($_POST['id_franquicia'] == $franquicia['id']) {
                    $nombre_empresa = $franquicia['nombre_empresa'];
                }

            }

            $f = $this->franquicias->get_franquicia($_POST['id_franquicia']);

            array_push($connections, [
                'id_franquicia' => $_POST['id_franquicia'],
                'id_proveedor' => $f['id_proveedor'],
                'activo' => $f['activo'],
                'nombre' => $nombre_empresa,
                'db' => $this->franquicias->get_user_db_connection_by_id_franquicia($_POST['id_franquicia']),
            ]);
        }

        $resultados = [];

        foreach ($connections as $conection) {
            array_push($resultados, $this->informes->total_ventas_atributos_franquicias($fechainicial, $fechafinal, $almacen, '', $_POST, $conection));
        }

        $this->__create_report_file_from_multidimensional_array($resultados, 'Ventas productos con atributos franquicias', 'Ventas');
    }

    private function __create_report_file_from_array(array $data, $file, $title)
    {
        $reporte = $this->load->library('phpexcel');
        $reporte = new PHPExcel();
        $reporte->setActiveSheetIndex(0);
        //die(var_dump($data));
        $reporte->getActiveSheet()->fromArray($data, null, 'A1');

        foreach ($reporte->getWorksheetIterator() as $worksheet) {
            $reporte->setActiveSheetIndex($reporte->getIndex($worksheet));

            $sheet = $reporte->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $reporte->getActiveSheet()->setTitle($title);
        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($reporte, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    private function __create_report_file_from_multidimensional_array(array $data, $file, $title)
    {
        $reporte = $this->load->library('phpexcel');
        $reporte = new PHPExcel();
        $index = 0;

        foreach ($data as $key => $franquicia) {
            $reporte->setActiveSheetIndex($index);
            $nombre = '';

            foreach ($franquicia as $key2 => $value2) {
                $nombre = $key2;
                $reporte->getActiveSheet()->setTitle($nombre);
                $reporte->getActiveSheet()->fromArray($value2, null, 'A1');
                foreach ($reporte->getWorksheetIterator() as $worksheet) {
                    $reporte->setActiveSheetIndex($reporte->getIndex($worksheet));

                    $sheet = $reporte->getActiveSheet();
                    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(true);
                    foreach ($cellIterator as $cell) {
                        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
                    }
                }
            }

            $index++;
            $reporte->createSheet($index);
        }

        // Rename worksheet
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($reporte, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function existensias_inventario()
    {
        acceso_informe('Existencias de inventario');
        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

        $data['almacenes'] = $this->almacenes->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/existensias_inventario', ['data' => $data]);
    }

    public function existensias_inventario_franquicia($id_franquicia)
    {

        $this->load->model('franquicias_model', 'franquicias');
        $user_db_connection = $this->franquicias->get_user_db_connection_by_id_franquicia($id_franquicia);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($user_db_connection);
        $data['almacenes'] = $this->almacenes->get_combo_data();

        $this->layout->template('member')->show('informes/existensias_inventario_franquicia', ['data' => $data, 'id_franquicia' => $id_franquicia]);
    }

    public function get_ajax_data_existencias_inventario_imei()
    {
        $start = $this->input->get('iDisplayStart');
        $limit = $this->input->get('iDisplayLength');
        $search = $this->input->get('sSearch');

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $almacen = $this->input->get('almacen');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->get_ajax_data_existencias_inventario_imei($almacen, true, $precio_almacen, $start, $limit, $search)));

    }

    public function exexistensiasimei()
    {
        // ini_set("memory_limit","1048M");

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Categoria');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Producto');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Serial');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Codigo');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Unidad');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Precio Compra');

        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Precio Venta');

        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Unidades');

        $almacen = $this->input->get('almacen');
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $query = $this->informes->get_ajax_data_existencias_inventario_imei_excel($almacen, false, $precio_almacen);
        //print_r($query);
        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);

            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:I' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Inventario por imei');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="inventario por imei.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;
    }

//************************************************************************
    //  EDWIN
    //************************************************************************
    //Vista del panel de productos
    public function productos()
    {

        $data = [];

        $data['marcas'] = $this->atributos->ajaxClasificacion(1);
        $data['proveedores'] = $this->atributos->ajaxClasificacion(2);
        $data['colores'] = $this->atributos->ajaxClasificacion(3);
        $data['tallas'] = $this->atributos->ajaxClasificacion(4);
        $data['lineas'] = $this->atributos->ajaxClasificacion(5);
        $data['materiales'] = $this->atributos->ajaxClasificacion(6);
        $data['tipos'] = $this->atributos->ajaxClasificacion(7);

        $data['categorias'] = $this->atributos->ajaxCategorias();
        $data['almacenes'] = $this->atributos->ajaxAlmacenes();

        $this->layout->template('member')->show('atributos/atributosInforme', ['data' => $data]);
    }

    public function qPivote()
    {

        $idAtributos = $this->input->post("str");

        $arrayData = explode(",", $idAtributos);

        $data = [];
        $data['marca'] = $arrayData[0];
        $data['color'] = $arrayData[1];
        $data['talla'] = $arrayData[2];
        $data['proveedor'] = $arrayData[3];
        $data['material'] = $arrayData[4];
        $data['linea'] = $arrayData[5];
        $data['almacen'] = $arrayData[6];
        $data['categoria'] = $arrayData[7];

        $result = $this->informes->queryPivote($data);

        // Luego de obtener los resultados

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function index()
    {

        if (getUiVersion() === "v2") {
            $this->indexV2();
        } else {
            $this->indexV1();
        }
    }

    public function indexV2()
    {

        // Carga del modelo de franquicias.
        $this->load->model('franquicias_model', 'franquicias');

        $sistema = $this->miempresa->get_sistema_empresa();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $data['atributos'] = $this->almacen->verificar_modulo_habilitado($this->user, self::ATRIBUTOS);
        $data['plan_separe'] = $this->almacen->verificar_modulo_habilitado($this->user, self::PLAN_SEPARE);
        $data['puntos'] = $this->almacen->verificar_modulo_habilitado($this->user, self::PUNTOS);
        $this->layout->template('member')->show('informes/indexV3', [
            'sistema' => $sistema,
            'data' => $data,
            'franquicias' => $this->franquicias->get_franquicias(),
            'siigo' => $this->cuentasSiigo->existeCS($this->session->userdata('base_dato')),
        ]);
    }

    public function indexV1()
    {

        $sistema = $this->miempresa->get_sistema_empresa();

        $this->layout->template('member')->show('informes/index', ['sistema' => $sistema]);
    }

    public function ventasxclientes()
    {
        acceso_informe('Ventas por Utilidad');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/ventasxclientes', array('data' => $data));
    }

    public function exventasxclientes()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-d');
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Factura');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Cliente');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Total venta');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Margen de utilidad');

        if (($data["tipo_negocio"] == "restaurante") || ($data["tipo_negocio"] == "Restaurante")) {
            $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Comensales');
        }

        $query = $this->informes->ventasxclientsex($fecha_inicio, $fecha_fin);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            if (($data["tipo_negocio"] == "restaurante") || ($data["tipo_negocio"] == "Restaurante")) {
                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            }

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        if (($data["tipo_negocio"] == "restaurante") || ($data["tipo_negocio"] == "Restaurante")) {
            $valor = "G";
            $valor1 = "G1";
        } else {
            $valor = "F";
            $valor1 = "F1";
        }
        $this->phpexcel->getActiveSheet()->getStyle("A1:$valor" . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle("A1:$valor1")->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('ventas por fechas');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="ventas por fechas.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;
    }

    public function consolidado_inventario()
    {
        acceso_informe('Informe de consolidado');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/consolidado_inventario', array('data' => $data));
    }

    public function get_ajax_data_consolidado_inventario()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->consolidado_inventario($fecha_inicio, $fecha_fin, true, $precio_almacen)));
    }

    public function consolidado_inventario_ex()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Categoria');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Producto');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Codigo');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Total de precio venta');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Total de unidades');

        $query = $this->informes->consolidado_inventario($fecha_inicio, $fecha_fin, false, $precio_almacen);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Consolidado de inventario');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="Consolidado de inventario.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;
    }

    public function cuadre_caja_excel($tipo = null, $fecha_cierre = null, $almacen = null)
    {

        $this->load->library('phpexcel');

        $empresa = $this->miempresa->get_data_empresa();

        $caja = $this->informes->cuadre_caja($fecha_cierre, $tipo, $almacen);

        $nombre_empresa = $empresa["data"]['nombre'];
        $dire = $empresa["data"]['direccion'];
        $telef = $empresa["data"]['telefono'];
        $email = $empresa["data"]['email'];

        $cafac = $empresa["data"]['cabecera_factura'];
        $nit = $empresa["data"]['nit'];
        $resol = $empresa["data"]['resolucion'];
        $tele = $empresa["data"]['telefono'];
        $dire = $empresa["data"]['direccion'];
        $web = $empresa["data"]['web'];

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', '#');

        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Forma de pago');
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(65);

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor');
        $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $row = 2;
        $cantidad = 0;
        $total = 0;

        foreach ($caja['forma_pago'] as $value) {

            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ]]);

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['cantidad']);
            $cantidad += $value['cantidad'];

            $formpago = str_replace("_", " ", $value['forma_pago']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $formpago);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value['vr_valor']);
            $total += $value['vr_valor'];

            $row++;
        }

        $total_ventas_cierre = 0;

        foreach ($caja['forma_pago_ventas'] as $val) {

            $total_ventas_cierre += $val['vr_valor'];
        }

        $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ]]);

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $cantidad);

        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Total Ventas");

        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($total));

        $row++;

        $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ]]);

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, '');

        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Total Gastos");

        $gastos = 0;
        //var_dump($caja);die;
        if (isset($caja['gastos']) && is_array($caja['gastos'])) {
            foreach ($caja['gastos'] as $value) {
                $gastos = $value->total;
            }
        }

        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($gastos));

        $row++;

        foreach ($caja['forma_pago_credito'] as $value) {

            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ]]);

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, '');

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Total de pagos a creditos");

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($total_creditos = $value['total_credito']));
        };

        $row++;

        $total_proveedor = 0;

        foreach ($caja['forma_pago_proveedor'] as $value):

            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ]]);

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, '');

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Total de pagos a proveedores");

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($total_proveedor = $value['total_proveedor']));
        endforeach;

        $row++;

        $total_abonos_plan_separe = 0;
        foreach ($caja['abonos_plan_separe_array'] as $value):

            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ]]);

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, '');

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Total de abonos plan separe");

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($total_abonos_plan_separe = $value['valor']));
        endforeach;

        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, '');

        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Total cierre");

        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($total - $gastos + $total_creditos - $total_proveedor + $total_abonos_plan_separe));
        $row++;

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Impuesto por Ventas");

        $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $row++;

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Nombre');
        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, 'Valor');

        $row++;

        $impuesto = 0;
        $total_imp = 0;

        foreach ($caja['impuesto_result'] as $value):

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->imp);

            $total_imp += ($value->total_precio_venta - $value->total_descuento) + $value->impuestos;

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format(($value->total_precio_venta - $value->total_descuento) + $value->impuestos));

        endforeach;

        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Total de Impuesto");

        $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':F' . $row)->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($total_imp));

        $row++;

        if ($tipo === "producto") {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Productos");
            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );

            $row++;

            foreach ($caja['factura_data'] as $value):

                $empresa = $value['empresa'];

            endforeach;

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Factura");

            if ($empresa === 'TCC S.A.') {

                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Vendedor");

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, "Almacen");
            }

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Descripción");

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, "Cantidad");

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, "V.Unidad");

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "Valor");

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, "Descuento");

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, "V.Impuesto");

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, "Total");

            $row++;

            $vr_unidades = 0;
            $vr_precio_unidad = 0;
            $vr_valor = 0;
            $vr_descuento = 0;
            $vr_valor_impuesto = 0;
            $vr_total = 0;

            foreach ($caja['factura_data'] as $value):

                $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['factura']);

                if ($empresa === 'TCC S.A.') {

                    $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['vendedor']);

                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['almacen_id']);
                }

                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['nombre_producto']);

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['unidades']);

                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, number_format($value['precio_unidad']));

                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, number_format($value['valor']));

                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($value['descuento']));

                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, number_format($value['valor_impuesto']));

                $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, number_format($value['valor'] + $value['valor_impuesto']));

                if ($value['venta_plan_activo'] === '1') {
                    $vr_unidades += $value['unidades'];
                    $vr_precio_unidad += $value['precio_unidad'];
                    $vr_valor += $value['valor'];
                    $vr_descuento += $value['descuento'];
                    $vr_valor_impuesto += $value['valor_impuesto'];
                    $vr_total += $value['valor'] + $value['valor_impuesto'];
                }

                $row++;

            endforeach;

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Totales");

            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'H' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );

            if ($empresa === 'TCC S.A.') {
                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, '');

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, '');
            }

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, number_format($vr_unidades));

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, number_format($vr_precio_unidad));

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, number_format($vr_valor));

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, number_format($vr_descuento));

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, number_format($vr_valor_impuesto));

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, number_format($vr_total));

            $row++;

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Total con Descuento");
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, number_format($vr_total));

            $row++;

            $styleThinBlackBorderOutline = [
                'borders' => [
                    'outline' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            //   $this->phpexcel->getActiveSheet()->getStyle('A1:F5')->applyFromArray($styleThinBlackBorderOutline);

            $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );
        }

        if ($tipo === "factura") {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Factura");
            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'F' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );

            $row++;

            foreach ($caja['factura_data'] as $value):

                $empresa = $value['empresa'];

            endforeach;

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Factura");

            if ($empresa === 'TCC S.A.') {

                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "Vendedor");

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, "Almacen");

                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, "VR Bruto");

                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "VR Iva");

                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, "Descuento");

                $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, "VR Neto");
            } else {

                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, "VR Bruto");

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, "VR Iva");

                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, "Descuento");

                $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "VR Neto");
            }

            $row++;

            $vr_unidades = 0;
            $vr_precio_unidad = 0;
            $vr_valor = 0;
            $vr_descuento = 0;
            $vr_valor_impuesto = 0;
            $vr_total = 0;

            $vr_bruto = 0;
            $vr_impuesto = 0;
            $vr_total = 0;
            $vr_descuento = 0;

            foreach ($caja['factura_data'] as $value):

                $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['factura']);

                if ($empresa === 'TCC S.A.') {

                    $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['vendedor']);

                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['almacen_id']);
                }

                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, number_format($value['vr_bruto']));

                $vr_bruto += $value['vr_valor'];

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, number_format($value['vr_impuesto']));

                $this->phpexcel->getActiveSheet()->getStyle('C' . $row)->applyFromArray(
                    [
                        'alignment' => [
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                $vr_impuesto += $value['vr_impuesto'];

                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, number_format($value['descuento']));

                $this->phpexcel->getActiveSheet()->getStyle('D' . $row)->applyFromArray(
                    [
                        'alignment' => [
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                $vr_descuento += $value['descuento'];

                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, number_format(($value['vr_valor'] + $value['vr_impuesto'])));

                $this->phpexcel->getActiveSheet()->getStyle('E' . $row)->applyFromArray(
                    [
                        'alignment' => [
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                if ($value['venta_plan_activo'] === '1') {
                    $vr_total += $value['vr_valor'] + $value['vr_impuesto'];

                    // $this->phpexcel->getActiveSheet()->setCellValue('F'.$row, number_format($value['valor'] + $value['valor_impuesto']));
                    //var_dump($value['unidades']);die;
                    $vr_unidades += $value['unidades'];
                    $vr_precio_unidad += $value['precio_unidad'];
                    $vr_valor += $value['vr_valor'];
                    $vr_descuento += $value['descuento'];
                    $vr_valor_impuesto += $value['vr_impuesto'];
                    $vr_total += $value['vr_valor'] + $value['vr_impuesto'];
                }

                $row++;

            endforeach;

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Totales");

            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );

            if ($empresa === 'TCC S.A.') {
                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, '');

                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, '');
            }

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, number_format($vr_bruto));

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, number_format($vr_impuesto));

            $this->phpexcel->getActiveSheet()->getStyle('C' . $row)->applyFromArray(
                [
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, number_format($vr_descuento));

            $this->phpexcel->getActiveSheet()->getStyle('D' . $row)->applyFromArray(
                [
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, number_format($vr_total));

            $this->phpexcel->getActiveSheet()->getStyle('E' . $row)->applyFromArray(
                [
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

            $row++;

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, "Total con Descuento");
            $this->phpexcel->getActiveSheet()->getStyle('A' . $row . ':' . 'B' . $row)->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, number_format($vr_total));

            $row++;

            $styleThinBlackBorderOutline = [
                'borders' => [
                    'outline' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            //   $this->phpexcel->getActiveSheet()->getStyle('A1:F5')->applyFromArray($styleThinBlackBorderOutline);

            $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
                [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                        ],
                        // 'bottom'     => array( 'style' => PHPExcel_Style_Border::BORDER_THIN
                    ],
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startcolor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endcolor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ]
            );
        }

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Informe de Cuadre de Caja');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="cuadre_caja"' . $tipo . '"' . date("Y:m:d") . '".xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;
    }

    public function get_ajax_data_ventasxclientes()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-d');
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->ventasxclients($fecha_inicio, $fecha_fin)));
    }

    public function get_ajax_data_existensias_inventario()
    {

        $start = $this->input->get('iDisplayStart');
        $limit = $this->input->get('iDisplayLength');
        $search = $this->input->get('sSearch');

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $almacen = $this->input->get('almacen');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->get_ajax_data_existensias_inventario($almacen, true, $precio_almacen, $start, $limit, $search)));
        //die();
    }

    public function get_ajax_data_existensias_inventario_franquicia($id_franquicia)
    {
        $almacen = $this->input->get('almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->get_ajax_data_existensias_inventario_franquicia($almacen, $id_franquicia)));
    }

    public function exexistensiasinventario()
    {

        // ini_set("memory_limit","1048M");

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Categoria');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Producto');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Codigo');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Unidad');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Precio Compra');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Precio Venta');

        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Unidades');

        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Valor inventario');

        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Ubicación');

        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Fecha Vencimiento');
        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Descripcion');
        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Proveedor');

        $almacen = $this->input->get('almacen');
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $query = $this->informes->get_ajax_data_existensias_inventario_excel($almacen, false, $precio_almacen);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);

            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);

            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[9]);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value[10]);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value[11]);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, $value[12]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:M' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Existencia de inventario');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="existencia de inventario.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;
    }

    public function descuentosotorgados()
    {

        $this->layout->template('member')->show('informes/descuentosotorgados');
    }

    public function get_ajax_data_descuentosotorgados()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->descuentosotorgados($fecha_inicio, $fecha_fin)));
    }

    public function exdescuentosotorgados()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Numero de factura');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Estado');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Producto');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Precio');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Cantidad');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Descuento unidad');

        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Descuento total');

        $query = $this->informes->descuentosotorgados($fecha_inicio, $fecha_fin);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:H' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Descuentos otorgados');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="descuentos otorgados.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    //informe de movimiento

    public function transacciones()
    {
        acceso_informe('Informe de transacciones de inventario');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_transacciones', array('data' => $data));
    }

    public function json_transacciones()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->transacciones()));
    }

    public function excel_transacciones()
    {
        $transacciones = $this->informes->transacciones();
        $reporte = $this->load->library('phpexcel');

        $reporte = new PHPExcel();
        $reporte->setActiveSheetIndex(0);
        $reporte->getActiveSheet()->setCellValue('A1', 'Fecha');
        $reporte->getActiveSheet()->setCellValue('B1', 'Cod documento');
        $reporte->getActiveSheet()->setCellValue('C1', 'Codigo');
        $reporte->getActiveSheet()->setCellValue('D1', 'Almacen');
        $reporte->getActiveSheet()->setCellValue('E1', 'Producto nombre');
        $reporte->getActiveSheet()->setCellValue('F1', 'Descripcion producto');
        $reporte->getActiveSheet()->setCellValue('G1', 'Cantidad');
        $reporte->getActiveSheet()->setCellValue('H1', 'Razon');
        $reporte->getActiveSheet()->setCellValue('I1', 'Username');
        $row = 2;

        foreach ($transacciones['aaData'] as $value) {
            $reporte->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $reporte->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $reporte->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $reporte->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $reporte->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $reporte->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $reporte->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $reporte->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $reporte->getActiveSheet()->setCellValue('I' . $row, $value[8]);

            $row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Informe transacciones.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($reporte, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function exinforme_movimiento()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');

        if (!empty($fecha_inicio)) {
            $fecha_inicio = $fecha_inicio . ' 00:00:00';
        }
        if (!empty($fecha_fin)) {
            $fecha_fin = $fecha_fin . ' 23:59:59';
        }

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        /*$this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Usuario');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Compra');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Almacen destino');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Nota');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Descripcion producto');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Tipo Movimiento');*/
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Usuario');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Documento/Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Consecutivo');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Nota');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Almacen destino');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Id Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Codigo Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Descripcion producto');
        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Costo producto');
        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Unidad producto');
        $this->phpexcel->getActiveSheet()->setCellValue('N1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('O1', 'Tipo Movimiento');

        //$query = $this->informes->informe_impuesto_excel($fecha_inicio, $fecha_fin);
        $query = $this->informes->informe_movimientos($fecha_inicio, $fecha_fin, $almacen);
        $row = 2;

        foreach ($query['aaData'] as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[9]);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value[10]);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value[11]);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, $value[12]);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, $value[13]);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, $value[14]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:O' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],

                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe de movimiento' . date("Y-m-d"));

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de movimiento"' . date("Y-m-d") . '".xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function margenutilidad()
    {

        $this->layout->template('member')->show('informes/margenutilidad');
    }

    public function get_ajax_data_margenutilidad()
    {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->margenutilidad()));
    }

    public function exmargenutilidad()
    {

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Código');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Nombre del producto');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Precio de compra');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Precio de venta');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Margen de utilidad');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Porcentaje');

        $query = $this->informes->margenutilidad();

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Margen de utilidad');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="margen de utilidad.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function ventasxproductos()
    {

        $this->layout->template('member')->show('informes/ventasxproductos');
    }

    public function get_ajax_data_ventasxproductos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->ventasxproductos($fecha_inicio, $fecha_fin)));
    }

    public function exventasxproductos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Nombre del producto');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cantidad vendida');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Precio promedio');

        $query = $this->informes->ventasxproductos($fecha_inicio, $fecha_fin);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:C' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Ventas por productos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="ventas por productos.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function detallesgastos()
    {

        $this->layout->template('member')->show('informes/detallesgastos');
    }

    public function get_ajax_data_detallesgastos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->detallesgastos($fecha_inicio, $fecha_fin)));
    }

    public function exdetallesgastos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha del gasto');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Descripcion');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Valor del gasto');

        $query = $this->informes->detallesgastos($fecha_inicio, $fecha_fin);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:C' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('detalles del gasto');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="detalles del gasto.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function detallesimpuestos()
    {

        $this->layout->template('member')->show('informes/detallesimpuestos');
    }

    public function get_ajax_data_detallesimpuestos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->detallesimpuestos($fecha_inicio, $fecha_fin)));
    }

    public function exdetallesimpuestos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Estado');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Numero');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Nombre del cliente');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor factura');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor impuesto');

        $query = $this->informes->detallesimpuestos($fecha_inicio, $fecha_fin);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('detalles de impuestos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="detalles de impuestos.xlsx"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function pagosrecibidos()
    {
        acceso_informe('Pagos recibidos');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/pagosrecibidos', array('data' => $data));
    }

    public function get_ajax_data_pagosrecibidos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->pagosrecibidos($fecha_inicio, $fecha_fin)));
    }

    public function expagosrecibidos()
    {

        $fecha_inicio = $this->input->get('fecha_inicio');

        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Numero de pago');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha de pago');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Numero de factura');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Cliente');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Metodo de pago');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor del pago');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Retención');

        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Valor de la factura');

        $query = $this->informes->pagosrecibidos($fecha_inicio, $fecha_fin);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:H' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $this->phpexcel->getActiveSheet()->setTitle('pagos recibidos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="pagos recibidos.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;

        /* header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=archivo.xlsx");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "<table border=1> ";

    echo "
    <thead>
    <th>Numero de pago</th>

    <th>Fecha de pago</th>

    <th>Numero de factura</th>

    <th>Cliente</th>

    <th>Metodo de pago</th>

    <th>Valor del pago</th>

    <th>Valor de la factura</th>
    <thead> ";

    foreach ($query['aaData'] as $value) {

    echo '<tbody><tr>';
    echo '<td>' . $value[0] . '</td>';

    echo '<td>' . $value[1] . '</td>';

    echo '<td>' . $value[2] . '</td>';

    echo '<td>' . $value[3] . '</td>';

    echo '<td>' . $value[4] . '</td>';

    echo '<td>' . ceil($value[5]) . '</td>';

    echo '<td>' . $value[6] . '</td>';
    echo '</tbody></tr>';

    $row++;

    }

    echo "</table> "; */
    }

    public function resumenimpuestos()
    {

        $this->layout->template('member')->show('informes/resumenimpuestos');
    }

    public function get_ajax_data_resumenimpuestos()
    {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->resumenimpuestos()));
    }

    public function exresumenimpuestos()
    {

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Nombre del impuesto');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Porcentaje del impuesto (%)');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Valor sin impuesto');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Valor de impuesto');

        $query = $this->informes->resumenimpuestos();

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:D' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('resumen de impuestos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="resumen de impuestos.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function valor_inventario()
    {
        acceso_informe('Valor de Inventario');
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');

        $this->layout->template('member')->show('informes/valor_inventario', ['data' => $this->informes->valor_inventario($precio_almacen)]);
    }

    //Utilidad de Operación del Periodo
    public function utilidad_periodo()
    {
        acceso_informe('Utilidad de Operación del Periodo');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/utlidad_operacion', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function utilidad_periodo_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');

        $this->layout->template('member')->show('informes/utlidad_operacion', ['data' => $this->informes->utilidad_periodo($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function detalle_utilidad()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get("almacen");
        $result = $this->informes->detalleUtilidad($fecha_inicio, $fecha_fin, $almacen);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Ventas');

        $this->phpexcel->getActiveSheet()->setCellValue('A2', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('B2', 'Nombre');
        $this->phpexcel->getActiveSheet()->setCellValue('C2', 'Unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('D2', 'Total Descuento');
        $this->phpexcel->getActiveSheet()->setCellValue('E2', 'Total Precio Venta');
        $this->phpexcel->getActiveSheet()->setCellValue('F2', 'Total Precio Compra');
        $this->phpexcel->getActiveSheet()->setCellValue('G2', 'Impuesto');
        //$this->phpexcel->getActiveSheet()->setCellValue('F2', 'Impuesto');
        $row = 3;
        foreach ($result['ventas'] as $v) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $v->factura);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $v->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $v->unidades);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $v->total_descuento);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $v->total_precio_venta);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $v->total_precio_compra);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $v->impuesto);
            //echo $v->factura.$v->fecha.$v->nombre.$v->total_venta."n/";
            $row++;
        }
        $row++;
        //echo $result['calculos']['vr_descuento'].$result['calculos']['vr_descuento'].$result['calculos']['vr_descuento'];
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $result['calculos']['vr_descuento']);
        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $result['calculos']['vr_valor']);
        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $result['calculos']['vr_valor_compra']);
        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $result['calculos']['vr_impuestos']);
        $row++;

        $rowVentas = $row;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Ventas = ');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 'Total Precio Ventas - Total Descuento');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, ($result['calculos']['vr_valor'] - $result['calculos']['vr_descuento']));
        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Utilidad Bruta = ');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 'Ventas - Costos de Ventas');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $result['calculos']['vr_valor'] - $result['calculos']['vr_descuento'] - $result['calculos']['vr_valor_compra']);
        $row++;
        $row++;

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Gastos');
        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Descripcion');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 'Valor');
        $row++;
        foreach ($result['gastos'] as $g) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $g->descripcion);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $g->fecha);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $g->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $g->valor);
            //echo $g->descripcion.$g->fecha.$g->nombre.$g->valor."n/";
            $row++;
        }
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $result['calculos']['gastos']);
        $row++;
        $row++;

        /* $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, 'Gastos - Ordenes de Compra');
        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, 'Descripcion');
        $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, 'Valor');
        $row++;
        foreach($result['gastos_orden'] as $g)
        {
        $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, $g->id_factura);
        $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, $g->fecha_pago);
        $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, "");
        $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $g->cantidad);
        $row++;
        }
        $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $result['calculos']['gatos_orden']);
        $row++;$row++; */
        $rowFinal = $row;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Gastos Operacion = ');
        //$this->phpexcel->getActiveSheet()->setCellValue('B'.$row, 'Gastos + Gastos ordenes compra');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $result['calculos']['gastos']);
        $row++;

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Utilidad de operacion = ');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 'Utilidad Bruta - Gastos Operacion');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $result['calculos']['vr_valor'] - $result['calculos']['vr_descuento'] - $result['calculos']['vr_valor_compra'] - ($result['calculos']['gastos'] + $result['calculos']['gatos_orden']));

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:G' . $row)->applyFromArray($styleThinBlackBorderOutline);
        $style = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => '136191108'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:G2')->applyFromArray($style);
        $this->phpexcel->getActiveSheet()->getStyle('A' . $rowVentas . ':G' . ($rowVentas + 1))->applyFromArray($style);
        $this->phpexcel->getActiveSheet()->getStyle('A' . ($rowVentas + 3) . ':G' . ($rowVentas + 4))->applyFromArray($style);
        //$this->phpexcel->getActiveSheet()->getStyle('A'.($rowGastosOrden).':G'.($rowGastosOrden+1))->applyFromArray($style);
        $this->phpexcel->getActiveSheet()->getStyle('A' . ($rowFinal) . ':G' . ($rowFinal + 1))->applyFromArray($style);

        $this->phpexcel->getActiveSheet()->setTitle("Informedetalleutilidad");

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        $filename = "ExcelInformedetalleutilidad.xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    //Utilidad de Operación del Periodo
    public function menos_rotacion()
    {
        acceso_informe('Inventario con Menos Rotación');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/rotacion_menos', ['data1' => $data, 'data' => $data]);
    }

    public function menos_rotacion_data()
    {
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/rotacion_menos', ['data' => $this->informes->menos_rotacion($fechainicial, $fechafinal, $almacen, $precio_almacen), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data]);
    }

    public function total_ventas_hora()
    {
        acceso_informe('Total de Ventas por Hora');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0', false);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas_hora', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function total_ventas_hora_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        //$this->layout->template('member')->show('informes/total_ventas_hora', ['data' => $this->informes->total_ventas_hora($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
        $this->layout->template('member')->show('informes/total_ventas_hora', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_utilidad()
    {
        acceso_informe('Total de Utilidad');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_utilidad', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function total_utilidad_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/total_utilidad', ['data' => $this->informes->total_utilidad($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_formas_pago()
    {
        acceso_informe('Resumen por medios de pago');
        $data['almacen'] = $this->almacen->get_all('0');
        //$data['forma_pago'] = $this->pagos->get_tipos_pago();
        $data["forma_pago"] = $this->forma_pago->getActiva();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/tota_formaspago', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa, 'data' => $data]);
    }

    public function total_formas_pago_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $forma_pago = $this->input->post('forma_pago');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        //$data['forma_pago'] = $this->pagos->get_tipos_pago();

        if (!isset($_POST['almacen'])) {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data["forma_pago"] = $this->forma_pago->getActiva();
        $this->layout->template('member')->show('informes/tota_formaspago', ['data' => $this->informes->total_formas_pago($fechainicial, $fechafinal, $almacen, $ciudad, $forma_pago), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'forma_pago' => $forma_pago, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_formas_pago_excel()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $forma_pago = $this->input->post('forma_pago');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Forma de pago');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Almacen');

        $query = $this->informes->total_formas_pago($fechainicial, $fechafinal, $almacen, $ciudad, $forma_pago);

        $row = 2;

        foreach ($query['total_ventas'] as $value) {

            $formpago = str_replace("_", " ", $value['forma_pago']);
            $formpago = strtolower($formpago);

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['fecha_factura']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['nom_cli']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['factura']);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, ucfirst($formpago));
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value['valor_recibido']);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value['almacen']);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Formas de pago');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="Formas de pago.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        ob_clean();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function total_ventas_dia()
    {
        acceso_informe('Total de Ventas por Dia');
        $data['almacen'] = $this->almacen->get_all('0', false);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas_dia', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function imprimir_total_ventas_dia($fechainicial = 0, $fechafinal = 0, $almacen = 0, $ciudad = 0)
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = [
            'data_empresa' => $data_empresa,
        ];

        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $this->layout->template('ajax')->show('informes/imprimir_ticket_total_ventas_dias', ['data1' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, 'dias'), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_ventas_dia_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        //$fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $this->layout->template('member')->show('informes/total_ventas_dia', ['data' => $this->informes->total_ventas_dia($fechainicial, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_ventas_mes()
    {
        acceso_informe('Total de Ventas por Mes');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas_mes', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function total_ventas_mes_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $filtro = $this->input->post('filtro');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        //$this->layout->template('member')->show('informes/total_ventas_mes', ['data' => $this->informes->total_ventas_mes($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
        $this->layout->template('member')->show('informes/total_ventas_mes', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function habitos_consumo_mes_data_ajax()
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return data in format JSON, with data of sales by day
        Require date initial, date end, warehouse, page of search, and page length of search
         */

        //Data of inputs
        $fechainicial = $_POST['fechainicial'];
        $fechafinal = $_POST['fechafinal'];
        $almacen = $_POST['almacen'];
        $page = $_POST['page'];
        $pageLength = $_POST['pageLength'];

        //Data company
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['almacen'] = $this->almacen->get_all('0');

        //Return in format JSON, information of sales
        echo json_encode($this->informes->habitos_consumo_mes_ajax($fechainicial, $fechafinal, $almacen, $page, $pageLength));
    }

    public function total_ventas()
    {
        acceso_informe('Total de Ventas por Hora');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0', false);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
        //$this->layout->template('member')->show('informes/total_ventas', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad,$filtro), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_ventas_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $filtro = $this->input->post('filtro');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        if ($filtro == 'horas') {
            $this->layout->template('member')->show('informes/total_ventas_hora', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
        } else {
            if ($filtro == 'dias') {
                $this->layout->template('member')->show('informes/total_ventas_dia', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro), 'fechainicial' => $fechainicial, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
            } else {
                if ($filtro == 'mes') {
                    $this->layout->template('member')->show('informes/total_ventas_mes', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
                }
            }

        }
        //$this->layout->template('member')->show('informes/total_ventas_mes', ['data' => $this->informes->total_ventas_mes($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
        // $this->layout->template('member')->show('informes/total_ventas_mes', ['data' => $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad,$filtro), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function total_ventas_impuesto()
    {
        acceso_informe('Total de Ventas por Impuestos');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas_impuesto', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function total_ventas_impuesto_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $this->layout->template('member')->show('informes/total_ventas_impuesto', ['data' => $this->informes->total_ventas_impuesto($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data_empresa' => $data_empresa]);
    }

    public function ventas_categoria()
    {
        acceso_informe('Ventas por Categoria');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/ventas_categoria', ['data1' => $data, 'data' => $data]);
    }

    public function ventas_categoria_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/ventas_categoria', ['data' => $this->informes->ventas_categoria($fechainicial, $fechafinal, $almacen), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data, 'data_empresa' => $data_empresa]);
    }

    public function ex_ventas_categoria_data($almacen, $fechainicial, $fechafinal)
    {

        $result = $this->informes->ventas_categoria($fechainicial, $fechafinal, $almacen);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Categoria');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Productos');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Subtotal');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Total');
        $row = 2;
        foreach ($result['ventas_categorias'] as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['fecha']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['categoria']);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value['subtotal']);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value['total']);
            if (count($value['descripcion_productos']) > 1):
                foreach ($value['descripcion_productos'] as $product):
                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $product->nombre_producto);
                    $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $product->unidades);
                    $row++;
                endforeach;
            else:
                $product = $value['descripcion_productos'][0];
                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $product->nombre_producto);
                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $product->unidades);
                $row++;
            endif;
        }
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        foreach (range('A', 'F') as $columnID) {
            $this->phpexcel->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . ($row - 1))->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Ventas por categoria');

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ventas por categoria.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function transacionesinforme()
    {
        acceso_informe('Informe de Transacciones');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        //$data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/transacionesinforme', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function devolucionesinforme()
    {
        acceso_informe('Devoluciones');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        //$data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/devolucionesinformes', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function devolucionesinforme_data()
    {

        $this->informes->validate_index_ventas_pago();

        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacén');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Identificación cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Telefono cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Celular cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', '# factura');       
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Fecha factura');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Fecha de la devolución');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Vendedor');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Total Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Total de la devolución');

        $this->load->model("informes_model", 'informes');
        $this->informes->initialize($this->dbConnection);
        $query = $this->informes->devolucionesinforme($fechainicial, $fechafinal, $almacen, $ciudad);

        $row = 2;
        $total_ventas = 0;
        $total_devoluciones = 0;

        foreach ($query as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value->nombre_almacen);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value->nombre_comercial);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value->nif_cif);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value->telefono);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value->movil);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value->factura);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value->fecha_factura);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value->fecha_devolucion);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value->nombre_vendedor);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value->total_venta);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value->total_devolucion);
            $total_ventas += $value->total_venta;
            $total_devoluciones += $value->total_devolucion;
            $row++;

        }

        $style_totales = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'bottom' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'left' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'right' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endcolor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, 'Totales: ');
        $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $total_ventas);
        $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $total_devoluciones);
        
        $this->phpexcel->getActiveSheet()->getStyle('I' . $row)->applyFromArray($style_totales);
        $this->phpexcel->getActiveSheet()->getStyle('J' . $row)->applyFromArray($style_totales);
        $this->phpexcel->getActiveSheet()->getStyle('K' . $row)->applyFromArray($style_totales);

        $this->phpexcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle("Devoluciones ");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        $filename = "Excel Informe de Devoluciones " . date('Y-m-d') . ".xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        ob_clean();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;
    }

    public function transacionesinforme_data()
    {

        $this->informes->validate_index_ventas_pago();

        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');

// $this->layout->template('excel')->show('informes/transacionesinformeexcel',
        // array('data' => $this->informes->transacionesinforme($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial,'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data));
        //ini_set("memory_limit","1048M");

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacén');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Identificación cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Telefono cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Celular cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', '# factura');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Codigo del producto');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Detalle producto');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Cantidad producto');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Cantidad Devueltas');
        $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Fecha factura');
        $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Precio venta x producto');
        $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Precio compra x producto');
        $this->phpexcel->getActiveSheet()->setCellValue('N1', 'Descuento');
        $this->phpexcel->getActiveSheet()->setCellValue('O1', 'Descuento Total');
        $this->phpexcel->getActiveSheet()->setCellValue('P1', 'Venta - descuento');
        $this->phpexcel->getActiveSheet()->setCellValue('Q1', 'impuesto Total');
        $this->phpexcel->getActiveSheet()->setCellValue('R1', 'Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('S1', 'Subtotal');
        $this->phpexcel->getActiveSheet()->setCellValue('T1', 'Total venta');
        $this->phpexcel->getActiveSheet()->setCellValue('U1', 'Ciudad');
        $this->phpexcel->getActiveSheet()->setCellValue('V1', 'Forma pago');
        $this->phpexcel->getActiveSheet()->setCellValue('W1', 'Vendedor');
        $this->phpexcel->getActiveSheet()->setCellValue('X1', 'Vendedor2');
        $this->phpexcel->getActiveSheet()->setCellValue('Y1', 'Usuario');
        $this->phpexcel->getActiveSheet()->setCellValue('Z1', 'No. Transacción');
        $this->phpexcel->getActiveSheet()->setCellValue('AA1', 'Promoción');
        $this->phpexcel->getActiveSheet()->setCellValue('AB1', 'Nota');
        $this->phpexcel->getActiveSheet()->setCellValue('AC1', 'Categoria');
        $this->phpexcel->getActiveSheet()->setCellValue('AD1', 'Proveedor');
        $this->phpexcel->getActiveSheet()->setCellValue('AE1', 'Nota Crédito');

        // $this->phpexcel->getActiveSheet()->setCellValue('Y1', 'Nota Credito');
        $this->load->model("informes_model", 'informes');
        $this->informes->initialize($this->dbConnection);
        $query = $this->informes->transacionesinforme($fechainicial, $fechafinal, $almacen, $ciudad);

        $row = 2;
        $total_ventas = 0;
        // print_r($query); die();
        foreach ($query['total_ventas'] as $value) {
            $total_ventas += $value['subtotal'];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['nombre_almacen']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['nombre_cliente']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['nit']);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value['telefono']);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value['telmovil']);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value['numerofac']);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value['codigo_producto']);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value['nombre_producto']);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value['unidades']);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value['unidades_devueltas']);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value['fechaventa']);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value['precio_venta_venta']);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, $value['precio_compra']);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, $value['descuento']);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, $value['total_descuento']);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, $value['precio_venta_venta'] - $value['descuento']);
            //$this->phpexcel->getActiveSheet()->setCellValue('O' . $row, (((($value['precio_venta_venta'] - $value['descuento']) * $value['impuesto']) / 100) * $value['unidades']));
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, $value['total_impuesto']);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, $value['impuesto']);
            //$this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, ( ($value['precio_venta_venta'] - $value['descuento']) * $value['unidades'] ));
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, $value['subtotal'] - $value['total_impuesto']);
            //$value['total_precio_venta']
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, $value['subtotal']);

            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, $value['ciudad']);
            //$this->phpexcel->getActiveSheet()->setCellValue('S' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, strtolower(str_replace('_', ' ', $value['formas_pago'])));
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, $value['vendedor']);
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, $value['vendedor2']);
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, $value['usuario']);
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, $value['transaccion']);
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, $value['nombre_promocion']);
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, @$value['nota_transaccion']);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, $value['categoria']);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, $value['proveedor']);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, $value['unidades_devueltas'] > 0 ? "Si" : "No");

            // $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, $value['nota_devolucion']);
            $row++;

        }
        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 'Devoluciones:');
        $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, $query['devoluciones']);
        $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, 'Ventas:');
        $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, $total_ventas);
        $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, 'Total:');
        $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, ($total_ventas - $query['devoluciones']));

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $style_totales = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'bottom' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'left' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'right' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endcolor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('X' . $row)->applyFromArray($style_totales);
        $this->phpexcel->getActiveSheet()->getStyle('V' . $row)->applyFromArray($style_totales);
        $this->phpexcel->getActiveSheet()->getStyle('Z' . $row)->applyFromArray($style_totales);

        $this->phpexcel->getActiveSheet()->getStyle('A1:AE' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:AE1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle("Transacciones ");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        $filename = "Excel Informe de Transacciones " . date('Y-m-d') . ".xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        ob_clean();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit;

        /**
         * if($this->input->post('excel') == 'EXCEL'){
         * $this->layout->template('excel')->show('informes/transacionesinformeexcel',
         * array('data' => $this->informes->transacionesinforme($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial,'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data));
         * }
         * if($this->input->post('pdf') == 'PDF'){
         * $this->layout->template('excel')->show('informes/transacionesinformepdf',
         * array('data' => $this->informes->transacionesinforme($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial,'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data));
         */
    }

    public function ex_ventas_dia()
    {
        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = [
            'data_empresa' => $data_empresa,
        ];

        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $ventas = $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, 'dias');

        $this->load->library('phpexcel');
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        /**
         * Muestra el Excel
         */
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Subtotal');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Total de ventas');

        $row = 2;
        $value = $ventas;

        foreach ($ventas['total_ventas'] as $value) {
            $total = $total + $value['total_precio_venta'];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['fecha_dia']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['subtotal_precio_venta']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['total_precio_venta']);

            $row = $row + 1;
        }
        /**
         * Renderiza las devoluciones y el calculo total depues de las devoluciones
         */
        $row = $row + 1;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Devoluciones');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $ventas['devoluciones']);
        $row = $row + 1;

        if ((!empty($data_empresa['data']['tipo_negocio'])) && (strtolower($data_empresa['data']['tipo_negocio']) == 'restaurante')) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Propinas');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $ventas['propina']);
            $row = $row + 1;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Totales - Devoluciones - Propinas');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, ($total - $ventas['devoluciones'] - $ventas['propina']));
        } else {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Totales - Devoluciones');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, ($total - $ventas['devoluciones']));
        }

        $this->phpexcel->getActiveSheet()->getStyle('A1:C1' . $row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $this->phpexcel->getActiveSheet()->setTitle("Informe de Ventas por dia ");
        header('Content-Type: application/vnd.ms-excel');
        $filename = "Excel Informe de Ventas por dia " . date('Y-m-d') . ".xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function ex_ventas_mes()
    {

        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('ex_provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro = 'mes';
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        //$ventas = $this->informes->total_ventas_mes($fechainicial, $fechafinal, $almacen, $ciudad);

        $ventas = $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro);

        $this->load->library('phpexcel');
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Mes');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Total Descuento');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Total Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Saldo a Favor');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Devoluciones');
        if ((!empty($data_empresa['data']['tipo_negocio'])) && (strtolower($data_empresa['data']['tipo_negocio']) == 'restaurante')) {
            $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Propinas');
            $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Subtotal');
            $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Total');
        } else {
            $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Subtotal');
            $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Total');
        }

        $row = 2;
        $total_ventas = 0;
        foreach ($ventas["total_ventas"] as $value) {
            $total_ventas += $value["total_precio_venta"];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['mes']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $this->opciones->formatoMonedaMostrar($value['total_descuento']));
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $this->opciones->formatoMonedaMostrar($value['total_impuesto']));
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $this->opciones->formatoMonedaMostrar($value['saldo_a_favor']));
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $this->opciones->formatoMonedaMostrar($value['devoluciones']));
            if ((!empty($data_empresa['data']['tipo_negocio'])) && (strtolower($data_empresa['data']['tipo_negocio']) == 'restaurante')) {
                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $this->opciones->formatoMonedaMostrar($value['propina']));
                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $this->opciones->formatoMonedaMostrar($value['subtotal_precio_venta'] - $value['devoluciones'] - $value['propina']));
                $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $this->opciones->formatoMonedaMostrar($value['total_precio_venta'] - $value['devoluciones'] - $value['propina']));
            } else {
                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $this->opciones->formatoMonedaMostrar($value['subtotal_precio_venta'] - $value['devoluciones']));
                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $this->opciones->formatoMonedaMostrar($value['total_precio_venta'] - $value['devoluciones']));
            }
            $row++;
        }

        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $this->phpexcel->getActiveSheet()->setTitle("Informe de Ventas por dia ");
        header('Content-Type: application/vnd.ms-excel');
        $filename = "Excel Informe de Ventas por mes " . date('Y-m-d') . ".xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function ex_ventas_horas()
    {

        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $filtro = 'horas';
        $data_empresa = $this->mi_empresa->get_data_empresa();

        $ventas = $this->informes->total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro);
        //echo json_encode( $horas );
        //die();
        $this->load->library('phpexcel');
        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        /**
         * Muestra el Excel
         */
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Subtotal');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Total de ventas');

        $row = 2;
        $value = $ventas;

        foreach ($ventas['total_ventas'] as $value) {
            $total = $total + $value['total_precio_venta'];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['fecha_dia'] . ' - ' . $value['fecha']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['subtotal_precio_venta']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['total_precio_venta']);

            $row = $row + 1;
        }
        /**
         * Renderiza las devoluciones y el calculo total depues de las devoluciones
         */
        $row = $row + 1;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Devoluciones');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $ventas['devoluciones']);
        $row = $row + 1;

        if ((!empty($data_empresa['data']['tipo_negocio'])) && (strtolower($data_empresa['data']['tipo_negocio']) == 'restaurante')) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Propinas');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $ventas['propina']);
            $row = $row + 1;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Totales - Devoluciones - Propinas');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, ($total - $ventas['devoluciones'] - $ventas['propina']));
        } else {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'Totales - Devoluciones');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, ($total - $ventas['devoluciones']));
        }

        $this->phpexcel->getActiveSheet()->getStyle('A1:C1' . $row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        $this->phpexcel->getActiveSheet()->setTitle("Informe de Ventas por dia ");
        header('Content-Type: application/vnd.ms-excel');
        $filename = "Excel Informe de Ventas por dia " . date('Y-m-d') . ".xls";
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function export_erp()
    {
        acceso_informe('Exportación PeopleSoft');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/export_erp', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function export_erp_data()
    {
        $data['almacen'] = $this->almacen->get_all('0');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data = $this->informes->export_erp($fechainicial, $fechafinal, $almacen, $ciudad);

        echo $name = $_SERVER['DOCUMENT_ROOT'] . "/prueba.txt";

        $fp = fopen($name, "w");

        ini_set("memory_limit", "1048M");

        function limpiarCaracteresEspeciales($string)
        {
            $string = htmlentities($string);
            $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
            return $string;
        }

        foreach ($data['total_ventas'] as $value) {

            $tam = strlen($value['fechaventa']);

            if ($tam === 8) {
                $fecha_nueva = "" . $value['fechaventa'];
                //  echo "holaaa";
            }

            if ($tam === 9) {
                $fecha_nueva = $value['fechaventa'];
            }

// $nuevo;
            if (trim($value['prod_equivalencia_1']) === 'RECARGAS') {
                $nuevo = trim($value['und_negocio']) . ';'
                . '999999999;'
                . trim($fecha_nueva) . ';'
                . trim($value['factura']) . ';'
                . trim($value['factura']) . ';'
                . trim($value['prod_equivalencia_1']) . ";"
                . round($value['precioventa'] * $value['unidades']) . ';'
                . '999999999' . ';'
                . '999999999' . ';'
                . trim($value['vendedor']) . ';'
                . trim($value['alm_equivalencia']) . ";"
                . trim($value['prod_equivalencia_2']) . ";"
                . trim($value['prod_equivalencia_3']) . ";"
                . strtoupper(limpiarCaracteresEspeciales($value['provincia'])) . ';'
                . 'FT' . ';'
                . trim($value['factura']) . ';'
                . ";COL;"
                . trim($value['cod_geo']) . ';'
                . trim($value['cod_mun']) . ';'
                    . "\r\n";
            }

            if (trim($value['prod_equivalencia_1']) !== 'RECARGAS') {
                $nuevo = trim($value['und_negocio']) . ';'
                . '999999999;'
                . trim($fecha_nueva) . ';'
                . trim($value['factura']) . ';'
                . trim($value['factura']) . ';'
                . trim($value['prod_equivalencia_1']) . ";"
                . round($value['precioventa'] * $value['unidades']) . ';'
                . '999999999' . ';'
                . '999999999' . ';'
                . trim($value['vendedor']) . ';'
                . trim($value['alm_equivalencia']) . ";"
                . trim($value['prod_equivalencia_2']) . ";"
                . trim($value['prod_equivalencia_3']) . ";"
                . strtoupper(limpiarCaracteresEspeciales($value['provincia'])) . ';'
                . 'FT' . ';'
                . trim($value['factura']) . ';'
                . ";COL;"
                . trim($value['cod_geo']) . ';'
                . trim($value['cod_mun']) . ';'
                    . "\r\n";
            }

            /**
             * $this->phpexcel->getActiveSheet()->setCellValue('A'.$row, 'P9001');
             * $this->phpexcel->getActiveSheet()->setCellValue('B'.$row, '999999999');
             * $this->phpexcel->getActiveSheet()->setCellValue('C'.$row, $value['fechaventa']);
             * $this->phpexcel->getActiveSheet()->setCellValue('D'.$row, $value['factura']);
             * $this->phpexcel->getActiveSheet()->setCellValue('E'.$row, $value['factura']);
             * $this->phpexcel->getActiveSheet()->setCellValue('F'.$row, $value['prod_equivalencia_1']);
             * $this->phpexcel->getActiveSheet()->setCellValue('G'.$row, round($value['precioventa']));
             * $this->phpexcel->getActiveSheet()->setCellValue('H'.$row, '999999999');
             * $this->phpexcel->getActiveSheet()->setCellValue('I'.$row, '999999999');
             * $this->phpexcel->getActiveSheet()->setCellValue('J'.$row, '101');
             * $this->phpexcel->getActiveSheet()->setCellValue('K'.$row, $value['alm_equivalencia']);
             * $this->phpexcel->getActiveSheet()->setCellValue('L'.$row, $value['prod_equivalencia_2']);
             * $this->phpexcel->getActiveSheet()->setCellValue('M'.$row, $value['prod_equivalencia_3']);
             * $this->phpexcel->getActiveSheet()->setCellValue('N'.$row, strtoupper(limpiarCaracteresEspeciales($value['provincia'])));
             * $this->phpexcel->getActiveSheet()->setCellValue('O'.$row, 'FT');
             * $this->phpexcel->getActiveSheet()->setCellValue('P'.$row, $value['factura']);
             * $this->phpexcel->getActiveSheet()->setCellValue('Q'.$row, 'COL');
             */
            fputs($fp, $nuevo);
        }

        fclose($fp);

        if (file_exists($name)) {

            header("Content-Description: File Transfer");

            header("Content-Type: text/csv");

            header("Content-Disposition: attachment; filename=VTAS_CONT_" . date('Ymd') . ".csv");

            header("Content-Transfer-Encoding: Binary");

            header("Expires:0");

            header("Cache-control:must-revalidate");

            header("Pragma:public");

            header("Content-Length" . filesize($name));

            ob_clean();

            flush();

            readfile($name);

            exit;
        }
    }

    public function export_office()
    {
        acceso_informe('Exportación WorldOffice');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/export_office', ['data1' => $data, 'data' => $data]);
    }

    public function export_office_data()
    {
        $data['almacen'] = $this->almacen->get_all('0');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');

        ini_set('memory_limit', '128M');

        $this->layout->template('excel')->show('informes/export_office_excel', [
            'data' => $this->informes->export_office($fechainicial, $fechafinal, $almacen),
            'fechainicial' => $fechainicial,
            'fechafinal' => $fechafinal,
            'almacen' => $almacen]
        );
    }

    public function export_siigo()
    {
        acceso_informe('Exportación Siigo');
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/export_siigo', ['data1' => $data]);
    }

    public function export_siigo_data()
    { //aqui
        $data['almacen'] = $this->almacen->get_all('0');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');

        if (isset($_POST['almacen'])) {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = "0";
        }

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $query = $this->informes->export_siigo($fechainicial, $fechafinal, $almacen);

        $this->phpexcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode('0000000000');
        $this->phpexcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode('000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode('00000');
        $this->phpexcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode('000');

        $this->phpexcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode('000');
        //$this->phpexcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode('00000000000');
        //$this->phpexcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('W')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('X')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('Y')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('Z')->getNumberFormat()->setFormatCode('0000');
        //$this->phpexcel->getActiveSheet()->getStyle('AA')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AB')->getNumberFormat()->setFormatCode('00000');
        $this->phpexcel->getActiveSheet()->getStyle('AC')->getNumberFormat()->setFormatCode('000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AD')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AE')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AF')->getNumberFormat()->setFormatCode('000000');
        $this->phpexcel->getActiveSheet()->getStyle('AG')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AH')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AI')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AJ')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AK')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AL')->getNumberFormat()->setFormatCode('0');
        $this->phpexcel->getActiveSheet()->getStyle('AM')->getNumberFormat()->setFormatCode('00000');
        $this->phpexcel->getActiveSheet()->getStyle('AN')->getNumberFormat()->setFormatCode('00000000000');
        //$this->phpexcel->getActiveSheet()->getStyle('AO')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('AP')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AQ')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('AR')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('AS')->getNumberFormat()->setFormatCode('0');
        $this->phpexcel->getActiveSheet()->getStyle('AT')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AU')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AV')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AW')->getNumberFormat()->setFormatCode('0');
        $this->phpexcel->getActiveSheet()->getStyle('AX')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AY')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AZ')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('BA')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('BB')->getNumberFormat()->setFormatCode('00');

        $row = 1;

        $cuentas_siigo = $this->informes->get_cuenta_siigo(null, 1); // Venta normal
        $tipo_cuenta = $cuentas_siigo['letra'];
        $numero_cuenta = $cuentas_siigo['codigo1'] . $cuentas_siigo['codigo2'] . $cuentas_siigo['codigo3'] . $cuentas_siigo['codigo4'] . $cuentas_siigo['codigo5'] . $cuentas_siigo['codigo6'];

        $impuestos = array();
        $iteracion = 1;

        $res = array_count_values(array_map(function ($ingre) {
            return $ingre['factura'];
        }, $query));

        foreach ($query as $keyQuery => $value) { // Ventas detalle

            $nFactura = $value['factura'];
            $fecha = explode('-', $value['fecha']);

            if ($value['impuesto'] > 0) {
                if (isset($impuestos[$value['impuesto']])) {
                    $impuestos[$value['impuesto']] = $impuestos[$value['impuesto']] + $value['total_impuesto'];
                } else {
                    $impuestos[$value['impuesto']] = $value['total_impuesto'];
                }
            }

            //primer fila+---------------------------------------------------
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['id']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $numero_cuenta); //serial
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $tipo_cuenta);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($value['valor_movimiento_sin_impuesto']));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, $value['impuesto']);

            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, $value['codigo_producto']);
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, $value['unidades']);
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, $value['descuento']);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, $value['precio_venta']);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, $value['total_descuento']);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, $value['impuesto']);
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, $value['total_impuesto']);
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);

            $row++;

            if ($iteracion == $res[$value['factura']]) {

                foreach ($impuestos as $key => $value1) {
                    $impuestos_siigo = $this->informes->get_cuenta_siigo($key, null);

                    if (isset($impuestos_siigo['letra'])) {
                        $tipo_cuenta_imp = $impuestos_siigo['letra'];
                        $numero_cuenta_imp = $impuestos_siigo['codigo1'] . $impuestos_siigo['codigo2'] . $impuestos_siigo['codigo3'] . $impuestos_siigo['codigo4'] . $impuestos_siigo['codigo5'] . $impuestos_siigo['codigo6'];
                        $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
                        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['id']);
                        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
                        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $numero_cuenta_imp); //serial
                        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $tipo_cuenta_imp);
                        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($value1));
                        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
                        $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
                        $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
                        $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
                        $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
                        $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 1);
                        $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
                        $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
                        $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

                        $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
                        $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
                        $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
                        $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(16, 3));

                        $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, "Fac " . $value['factura'] . " internet");
                        $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
                        $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
                        $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

                        $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

                        $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
                        $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);

                        $row++;
                    }
                }

                $impuestos_siigo = $this->informes->get_cuenta_siigo(null, 4); // Forma de pago
                $tipo_cuenta_imp = $impuestos_siigo['letra'];
                $numero_cuenta_imp = $impuestos_siigo['codigo1'] . $impuestos_siigo['codigo2'] . $impuestos_siigo['codigo3'] . $impuestos_siigo['codigo4'] . $impuestos_siigo['codigo5'] . $impuestos_siigo['codigo6'];

                $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['id']);
                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $numero_cuenta_imp); //serial
                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $tipo_cuenta_imp);
                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($value['total_venta']));
                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
                $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
                $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
                $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
                $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
                $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 1);
                $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
                $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
                $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

                $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
                $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
                $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
                $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(16, 3));

                $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, "Fac " . $value['factura'] . " internet");
                $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
                $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
                $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

                $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

                $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
                $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);
                $row++;
                $iteracion = 1;
                unset($impuestos);
            } else {
                $iteracion++;
            }

        }
        $this->phpexcel->getActiveSheet()->setTitle('Movimiento contable');

        $filename = "Excel Ventas Siigo " . date('Y-m-d') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename);
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        ob_clean();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
//
        $objWriter->save('php://output');

        exit();
    }
    public function export_siigo_data_10022017()
    { //aqui
        $data['almacen'] = $this->almacen->get_all('0');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');

        if (isset($_POST['almacen'])) {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = "0";
        }

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $query = $this->informes->export_siigo($fechainicial, $fechafinal, $almacen);
        $row = 1;

        //die("bn");

        $this->phpexcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('D')->getNumberFormat()->setFormatCode('0000000000');
        $this->phpexcel->getActiveSheet()->getStyle('F')->getNumberFormat()->setFormatCode('000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('G')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('H')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('I')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('J')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('K')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode('00000');
        $this->phpexcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode('000');

        $this->phpexcel->getActiveSheet()->getStyle('P')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('Q')->getNumberFormat()->setFormatCode('000');
        //$this->phpexcel->getActiveSheet()->getStyle('R')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('S')->getNumberFormat()->setFormatCode('00000000000');
        //$this->phpexcel->getActiveSheet()->getStyle('T')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('U')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('W')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('X')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('Y')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('Z')->getNumberFormat()->setFormatCode('0000');
        //$this->phpexcel->getActiveSheet()->getStyle('AA')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AB')->getNumberFormat()->setFormatCode('00000');
        $this->phpexcel->getActiveSheet()->getStyle('AC')->getNumberFormat()->setFormatCode('000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AD')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AE')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AF')->getNumberFormat()->setFormatCode('000000');
        $this->phpexcel->getActiveSheet()->getStyle('AG')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AH')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AI')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AJ')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AK')->getNumberFormat()->setFormatCode('0000000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AL')->getNumberFormat()->setFormatCode('0');
        $this->phpexcel->getActiveSheet()->getStyle('AM')->getNumberFormat()->setFormatCode('00000');
        $this->phpexcel->getActiveSheet()->getStyle('AN')->getNumberFormat()->setFormatCode('00000000000');
        //$this->phpexcel->getActiveSheet()->getStyle('AO')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('AP')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('AQ')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('AR')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('AS')->getNumberFormat()->setFormatCode('0');
        $this->phpexcel->getActiveSheet()->getStyle('AT')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AU')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AV')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AW')->getNumberFormat()->setFormatCode('0');
        $this->phpexcel->getActiveSheet()->getStyle('AX')->getNumberFormat()->setFormatCode('00000000000');
        $this->phpexcel->getActiveSheet()->getStyle('AY')->getNumberFormat()->setFormatCode('000');
        $this->phpexcel->getActiveSheet()->getStyle('AZ')->getNumberFormat()->setFormatCode('0000');
        $this->phpexcel->getActiveSheet()->getStyle('BA')->getNumberFormat()->setFormatCode('00');
        $this->phpexcel->getActiveSheet()->getStyle('BB')->getNumberFormat()->setFormatCode('00');

        $index = 1; //print_r($query); die();
        $anterior = "";
        //var_dump($query);die;
        foreach ($query as $keyQuery => $value) {
            if ($keyQuery == 0) {
                $anterior = $value['factura'];
            }
            if ($value['factura'] == $anterior && $keyQuery != 0) {
                continue;
            }
            $anterior = $value['factura'];
            $nFactura = explode("C", $value['factura'])[1];
            $fecha = explode('-', $value['fecha']);
            //primer fila+---------------------------------------------------
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 41355601); //serial
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "C");
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($this->informes->subtotalSiigo($value['id']), 5));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(16, 3));
            //
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, $this->informes->subtotalSiigo($value['id']));
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, $this->informes->subtotalSiigo($value['id']));
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, "S");
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);

            $row++;
            //segunda Fila---------------------------------------------------
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 422530); //serial
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "C");
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($this->informes->excentoSiigo($value['id']), 5));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 2);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(0, 2));
            //
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, "N");
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);
            $row++;

            //tercer fila+---------------------------------------------------
            $valor = (($value['total_venta']) - $this->informes->calcularDescuentoSiigo($value['id'])) * 16 / 100;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 24080501); //serial
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "C");
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($this->informes->IvaSiigo($value['id']), 5));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 3);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(16, 3));

            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, round($this->informes->IvaSiigo($value['id']), 5));
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, round($this->informes->IvaSiigo($value['id']), 5));
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);

            $row++;

            //cuarta fila+---------------------------------------------------
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 110505); //serial
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "D");
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, round($value['total_venta']), 5);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 4);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(16, 3));
            //
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);

            $row++;
            //quinta fila+---------------------------------------------------
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, 'F');
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $nFactura);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 530535); //serial
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, "D");
            //$this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $this->informes->descuentosSiigo($value['id']));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $this->informes->calcularDescuentoSiigo($value['id']));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $fecha[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $fecha[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $fecha[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, 0001);
            $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, 0110);
            $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, 5);
            $this->phpexcel->getActiveSheet()->setCellValue('N' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('O' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('P' . $row, (int) $value['nif_cif']);
            $this->phpexcel->getActiveSheet()->setCellValue('Q' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('R' . $row, "Fac " . $value['factura'] . " internet");
            $this->phpexcel->getActiveSheet()->setCellValue('S' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('T' . $row, ($value['estado'] == 0) ? "N" : "S"); //anulada
            $this->phpexcel->getActiveSheet()->setCellValue('U' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('V' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('W' . $row, round(16, 3));
            //
            $this->phpexcel->getActiveSheet()->setCellValue('X' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('Y' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('Z' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('AA' . $row, "");
            $this->phpexcel->getActiveSheet()->setCellValue('AB' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AC' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AD' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AE' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AF' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AG' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AH' . $row, 0); //cantidades
            $this->phpexcel->getActiveSheet()->setCellValue('AI' . $row, 1);
            $this->phpexcel->getActiveSheet()->setCellValue('AJ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AK' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AL' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AM' . $row, 0);

            $this->phpexcel->getActiveSheet()->setCellValue('AN' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AO' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AP' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AQ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AR' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AS' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AT' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AU' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AV' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AW' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AX' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AY' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('AZ' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BA' . $row, 0);
            $this->phpexcel->getActiveSheet()->setCellValue('BB' . $row, 0);

            $row++;
            //$index++;
            //        }
            // }
        }
        //die;
        $this->phpexcel->getActiveSheet()->setTitle('Movimiento contable');

        $filename = "Excel Ventas Siigo " . date('Y-m-d') . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename);
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        ob_clean();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        $objWriter->save('php://output');

        exit();

        /**
         * ini_set('memory_limit', '128M');
         * $this->layout->template('excel')->show('informes/export_siigo_excel',
         * array(
         * 'data' => $this->informes->export_office($fechainicial, $fechafinal, $almacen)
         * 'fechainicial' => $fechainicial,
         * 'fechafinal' => $fechafinal,
         * 'almacen' => $almacen)
         * );
         */
    }

    public function export_propina()
    {
        acceso_informe('Exportación Ventas con Propina');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/export_propina', ['data1' => $data, 'data' => $data]);
    }

    public function export_propina_data()
    {
        $data['almacen'] = $this->almacen->get_all('0');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $this->layout->template('excel')->show('informes/export_propina_excel', ['data' => $this->informes->export_propina($fechainicial, $fechafinal, $almacen), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen]);
    }

    public function habitos_consumo_hora()
    {
        acceso_informe('Hábitos de Consumo por Hora');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/habitos_consumo_hora', ['data1' => $data, 'data' => $data]);
    }

    public function habitos_consumo_hora_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $data_empresa = $this->mi_empresa->get_data_empresa();

        if ($this->session->userdata('is_admin') == 't' || $this->session->userdata('is_admin') == 'a') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/habitos_consumo_hora', ['data' => $this->informes->habitos_consumo_hora($fechainicial, $fechafinal, $almacen), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data, 'data_empresa' => $data_empresa]);
    }

    public function fechaespanol($fecha)
    { //yyyy-mm-dd
        $diafecespanol = date("d", strtotime($fecha));
        $diaespanol = date("N", strtotime($fecha));
        $mesespanol = date("m", strtotime($fecha));
        $anoespanol = date("Y", strtotime($fecha));
        //Asignamos el nombre en espa�ol

        // dia
        if ($diaespanol == "1") {$diaespan = "Lunes";}
        if ($diaespanol == "2") {$diaespan = "Martes";}
        if ($diaespanol == "3") {$diaespan = "Miercoles";}
        if ($diaespanol == "4") {$diaespan = "Jueves";}
        if ($diaespanol == "5") {$diaespan = "Viernes";}
        if ($diaespanol == "6") {$diaespan = "Sabado";}
        if ($diaespanol == "7") {$diaespan = "Domingo";}

        //mes
        if ($mesespanol == "1") {$mesespan = "Enero";}
        if ($mesespanol == "2") {$mesespan = "Febrero";}
        if ($mesespanol == "3") {$mesespan = "Marzo";}
        if ($mesespanol == "4") {$mesespan = "Abril";}
        if ($mesespanol == "5") {$mesespan = "Mayo";}
        if ($mesespanol == "6") {$mesespan = "Junio";}
        if ($mesespanol == "7") {$mesespan = "Julio";}
        if ($mesespanol == "8") {$mesespan = "Agosto";}
        if ($mesespanol == "9") {$mesespan = "Septiembre";}
        if ($mesespanol == "10") {$mesespan = "Octubre";}
        if ($mesespanol == "11") {$mesespan = "Noviembre";}
        if ($mesespanol == "12") {$mesespan = "Diciembre";}

        //ano
        $anoespanol = $anoespanol;

        //Fecha
        $fecha = $diaespan . " " . $diafecespanol . " de " . $mesespan . " del " . $anoespanol;

        return $fecha;
    }

    public function habitos_consumo_hora_excel()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $datos = $this->informes->habitos_consumo_hora($fechainicial, $fechafinal, $almacen);
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);

        $total = 0;
        $index = 1;

        foreach ($datos['total_ventas_1'] as $key => $value) {
            $index++;
            $fecha_string = $this->fechaespanol($value->fecha_dia);
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $fecha_string . ' - ' . $value->hora);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, 'Cantidad vendida');
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $value->total_venta);

            foreach ($datos['total_ventas_3'] as $key => $prod) {
                if ($prod['hora'] == $value->hora && $prod['fecha_dia'] == $value->fecha_dia) {
                    $total += $prod['total_detalleventa'];
                    $index++;
                    $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $prod['codigo_producto']);
                    $this->phpexcel->getActiveSheet()->setCellValue('B' . $index, $prod['nombre']);
                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, $prod['unidades']);
                    $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $prod['total_detalleventa']);

                }
            }
        }

        foreach ($datos['total_ventas_4'] as $key => $value) {
            $index++;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, 'Total');
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $value->total_ventas);
        }

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Habitos consumo hora ' . $fechainicial);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Habitos consumo hora ' . $fechainicial . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function habitos_consumo_dia()
    {
        acceso_informe('Hábitos de Consumo por día');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/habitos_consumo_dia', ['data1' => $data, 'data' => $data]);
    }

    //actualizacion realizada segun incidencia #175
    public function habitos_consumo_dia_excel()
    {
        echo $fechainicial = $this->input->post('dateinicial');
        echo $fechafinal = $this->input->post('datefinal');

        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $datos = $this->informes->habitos_consumo_dia($fechainicial, $fechafinal, $almacen);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);

        $total = 0;
        $index = 1;

        foreach ($datos['total_ventas_1'] as $key => $value) {
            $index++;
            $fecha_string = $this->fechaespanol($value->fecha_dia);
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $fecha_string . ' - ' . $value->fecha);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, 'Cantidad vendida');
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $value->total_venta);

            foreach ($datos['total_ventas_3'] as $key => $prod) {
                if ($prod['fecha'] == $value->fecha) {
                    $total += $prod['total_detalleventa'];
                    $index++;
                    $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $prod['codigo_producto']);
                    $this->phpexcel->getActiveSheet()->setCellValue('B' . $index, $prod['nombre']);
                    $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, $prod['unidades']);
                    $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $prod['total_detalleventa']);

                }

            }

        }
        $index++;

        $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, 'Total');
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $total);

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Habitos consumo dia ' . $fechainicial);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Habitos consumo dia ' . $fechainicial . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function habitos_consumo_dia_data()
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return view consumption habits with data currency,
        dates of inputs, data of company and data user
         */

        //Data currency
        $datacurrency = (object) array(
            'symbol' => $this->opciones->getDataMoneda()->simbolo,
            'decimals' => $this->opciones->getDataMoneda()->decimales,
            'thousands_sep' => $this->opciones->getDataMoneda()->tipo_separador_miles,
            'decimals_sep' => $this->opciones->getDataMoneda()->tipo_separador_decimales,
        );

        //Dates of inputs
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');

        //Data of company
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')

            ->show(
                'informes_vue/habitos_consumo_dia',
                /*
            There are two routes,:
            1.informes_vue/habitos_consumo_dia with vue.js
            2.informes/habitos_consumo_dia without vue.js (only PHP, HTML, JS...) this is classic view
             */
                [
                    'data' => '', //No send more data because the rest data is consulted in the view
                    'fechainicial' => $fechainicial,
                    'fechafinal' => $fechafinal,
                    'almacen' => $almacen,
                    'data1' => $data,
                    'data_empresa' => $data_empresa,
                    'datacurrency' => $datacurrency,
                ]
            );
    }

    // public function historial_inventario() {
    //     acceso_informe('Historial de inventario');
    //     $data['almacen'] = $this->almacen->get_all('0');

    //     $this->layout->template('member')->show('informes/historial_inventario', ['data1' => $data, 'data' => null]);
    // }

    // public function historial_inventario_data() {

    //     $fecha_desde = $this->input->post('fecha_desde');
    //     $fecha_hasta = $this->input->post('fecha_hasta');
    //     $almacen = $this->input->post('almacen') !== '' ? $this->input->post('almacen') : null;
    //     $data['almacen'] = $this->almacen->get_all('0');

    //     $data['historial'] = $this->informes->historial_inventario($fecha_desde,$fecha_hasta, $almacen);

    //     $this->layout->template('member')->show('informes/historial_inventario', ['data' => $data['historial'], 'data1' => $data, 'fecha_desde' => $fecha_desde, 'almacen' => $almacen]);
    // }

    // public function historial_inventario_excel($fecha_desde = null,$fecha_hasta = null, $_almacen = null) {
    //     $this->load->library('phpexcel');
    //     $this->phpexcel->setActiveSheetIndex(0);
    //     $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');
    //     $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Categoría');
    //     $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Código');
    //     $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Código de barras');
    //     $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Producto');
    //     $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Cantidad');
    //     $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Precio compra');
    //     $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Precio');

    //     //$fecha = $fecha_desde;
    //     $almacen = $_almacen;
    //     $lista = $this->informes->historial_inventario($fecha_desde,$fecha_hasta, $almacen);

    //     $index = 1;

    //     foreach ($lista as $producto) {
    //         $index++;
    //         $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $producto->Almacen);
    //         $this->phpexcel->getActiveSheet()->setCellValue('B' . $index, $producto->Categoria);
    //         $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, ' ' . $producto->Codigo);
    //         $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, ' ' . $producto->CodigoBarras);
    //         $this->phpexcel->getActiveSheet()->setCellValue('E' . $index, $producto->Nombre);
    //         $this->phpexcel->getActiveSheet()->setCellValue('F' . $index, $producto->Unidades);
    //         $this->phpexcel->getActiveSheet()->setCellValue('G' . $index, $this->opciones_model->formatoMonedaMostrar($producto->Precio_compra));
    //         $this->phpexcel->getActiveSheet()->setCellValue('H' . $index, $this->opciones_model->formatoMonedaMostrar($producto->Precio));
    //     }

    //     $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(70);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    //     $this->phpexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

    //     $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
    //             [
    //                 'font' => [
    //                     'bold' => true,
    //                 ],
    //                 'alignment' => [
    //                     'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
    //                 ],
    //                 'borders' => [
    //                     'top' => [
    //                         'style' => PHPExcel_Style_Border::BORDER_THIN,
    //                     ],
    //                     'bottom' => [
    //                         'style' => PHPExcel_Style_Border::BORDER_THIN,
    //                     ],
    //                 ],
    //                 'fill' => [
    //                     'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
    //                     'rotation' => 90,
    //                     'startcolor' => [
    //                         'argb' => 'FFDCDCDC',
    //                     ],
    //                     'endcolor' => [
    //                         'argb' => 'FFDCDCDC',
    //                     ],
    //                 ],
    //             ]
    //     );

    //     // Rename worksheet
    //     $this->phpexcel->getActiveSheet()->setTitle('Historial inventario ' . $fecha);
    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment;filename="Historial inventario ' . $fecha . '.xlsx"');
    //     header('Cache-Control: max-age=0');
    //     header('Cache-Control: max-age=1');
    //     header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    //     header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    //     header('Cache-Control: cache, must-revalidate');
    //     header('Pragma: public');

    //     $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
    //     ob_clean();

    //     $objWriter->save('php://output');
    // }

    public function informe_lista_de_precios()
    {
        acceso_informe('Informe lista de precios');
        $data['almacen'] = $this->almacen->get_all('0');
        $data['lista_precios'] = $this->lista_precios->leer();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/lista_de_precios', ['data1' => $data, 'data' => $data]);
    }

    public function informe_lista_precios_excel()
    {
        $lista_precios = $this->input->post('lista_precios') !== '' ? $this->input->post('lista_precios') : null;
        $almacen = $this->input->post('almacen') !== '' ? $this->input->post('almacen') : null;
        $registros = $this->informes->lista_precios($lista_precios, $almacen);
        //var_dump($registros);

        if ($lista_precios) {
            $listas = $this->lista_precios->get($lista_precios);
        } else {
            $listas = $this->lista_precios->leer();
        }

        $this->load->library('phpexcel');
        $indice = 0;
        $this->phpexcel->setActiveSheetIndex($indice);

        $columnas = ['Categoría', 'Código', 'Producto', 'Precio', "Nombre Impuesto", "Valor Impuesto"];
        $indice_columnas = count($columnas);
        $total_antes_de_dinamicas = $indice_columnas;
        $columnas_dinamicas = [];

        foreach ($listas as $lista) {
            $columnas[$indice_columnas] = $lista->nombre;
            $columnas_dinamicas[$indice_columnas] = $lista->id;
            $indice_columnas++;
        }
        $preparado = [];
        array_push($preparado, $columnas);
        //precision del redondeo
        $precision = $this->opciones_model->getDataMoneda();
        foreach ($registros as $registro) {
            $datos = [];
            $datos['categoria'] = $registro->categoria;
            $datos['codigo'] = $registro->codigo;
            $datos['nombre'] = $registro->nombre;
            $datos['precio_venta'] = round($registro->precio_venta, $precision->decimales);
            $datos['impuesto_nombre'] = $registro->impuestoNombre;
            $datos['impuesto_valor'] = $registro->impuestoValor;

            $precios_listas = explode(',', $registro->precios_listas_precios);
            $ids_listas = explode(',', $registro->ids_listas_precios);
            $nombres_listas = explode(',', $registro->nombres_listas_precios);

            for ($i = $total_antes_de_dinamicas; $i < count($columnas); $i++) {
                $key = array_search($columnas[$i], $nombres_listas);

                if (is_numeric($key)) {
                    $datos[$columnas[$i]] = round($precios_listas[$key], $precision->decimales);
                } else {
                    $datos[$columnas[$i]] = '';
                }
            }

            array_push($preparado, $datos);
        }

        //var_dump($preparado);

        $row = 1;

        for ($k = 0; $k < count($preparado); $k++) {
            $col = 0;

            foreach ($preparado[$k] as $key => $value) {
                $this->phpexcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }

            $row++;
        }

        // Rename worksheet
        $fecha = date('Y-m-d');
        $this->phpexcel->getActiveSheet()->setTitle('Libro precios ' . $fecha);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Libro precios ' . $fecha . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function inventarios_minimos_excel($q_encode)
    {
        $url_encode_data = $this->encryption->decode($q_encode);
        $database = explode('~', $url_encode_data);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Categoría');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Código');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Código de barras');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Cantidad actual');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Cantidad mínima');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Precio');

        $q_inventario = $this->db->query('SELECT a.`nombre` as almacen, c.`nombre` as categoria, p.`codigo`, p.`codigo_barra`, p.`nombre`, sa.`unidades`, p.`stock_minimo`, p.`precio_venta` FROM ((' . $database[0] . '.producto p JOIN ' . $database[0] . '.stock_actual sa ON  p.`id` = sa.`producto_id` AND (sa.`unidades` <= p.`stock_minimo` OR sa.unidades <= 0) LEFT JOIN ' . $database[0] . '.almacen a ON a.`id` = sa.`almacen_id`) LEFT JOIN ' . $database[0] . '.categoria c ON p.`categoria_id` = c.`id`) ORDER BY CAST(sa.`unidades` AS SIGNED) ASC');
        $inventario = $q_inventario->result();

        $index = 1;

        foreach ($inventario as $producto) {
            $index++;
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $producto->almacen);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $index, $producto->categoria);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, ' ' . $producto->codigo);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, ' ' . $producto->codigo_barra);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $index, $producto->nombre);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $index, $producto->unidades);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $index, $producto->stock_minimo);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $index, $producto->precio_venta);
        }

        $this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(70);
        $this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $this->phpexcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFDCDCDC',
                    ],
                    'endcolor' => [
                        'argb' => 'FFDCDCDC',
                    ],
                ],
            ]
        );

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Alerta inventario mínimo');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Alerta inventario mínimo.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');
    }

    public function habitos_consumo_mes()
    {
        acceso_informe('Hábitos de Consumo por Mes');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/habitos_consumo_mes2', ['data1' => $data, 'data' => $data]);
    }

    public function habitos_consumo_mes_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $poblacion = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/habitos_consumo_mes', ['data' => $this->informes->habitos_consumo_mes($fechainicial, $fechafinal, $almacen, $poblacion), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'poblacion' => $poblacion, 'data1' => $data, 'data_empresa' => $data_empresa]);
    }

    public function habitos_consumo_mes_data2()
    {
        $datacurrency = (object) array(
            'symbol' => $this->opciones->getDataMoneda()->simbolo,
            'decimals' => $this->opciones->getDataMoneda()->decimales,
            'thousands_sep' => $this->opciones->getDataMoneda()->tipo_separador_miles,
            'decimals_sep' => $this->opciones->getDataMoneda()->tipo_separador_decimales,
        );

        //Dates of inputs
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');

        //Data of company
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')

            ->show(
                'informes_vue/habitos_consumo_mes',
                /*
            There are two routes,:
            1.informes_vue/habitos_consumo_dia with vue.js
            2.informes/habitos_consumo_dia without vue.js (only PHP, HTML, JS...) this is classic view
             */
                [
                    'data' => '', //No send more data because the rest data is consulted in the view
                    'fechainicial' => $fechainicial,
                    'fechafinal' => $fechafinal,
                    'almacen' => $almacen,
                    'data1' => $data,
                    'data_empresa' => $data_empresa,
                    'datacurrency' => $datacurrency,
                ]
            );
    }

    public function habitos_consumo_mes_excel()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $poblacion = $this->input->post('provincia');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $datos = $this->informes->habitos_consumo_mes($fechainicial, $fechafinal, $almacen, $poblacion);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $index = 1;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, 'Codigó');
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $index, 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, 'Cantidad Vendida');
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, 'Total de ventas');

        $total = 0;
        foreach ($datos['total_ventas_3'] as $prod) {
            $index++;
            $total += $prod['total_detalleventa'];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, $prod['codigo_barra']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $index, $prod['nombre']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $index, ' ' . $prod['unidades']);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, ' ' . $prod['total_detalleventa']);
        }
        $index++;
        $this->phpexcel->getActiveSheet()->setCellValue('A' . $index, 'Total');
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $index, $total);

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Habitos consumo mes');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Habitos consumo mes.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
        ob_clean();

        $objWriter->save('php://output');

    }

    public function orden_compra_productos()
    {
        acceso_informe('Informe de los productos comprados');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/orden_compra_productos', ['data1' => $data, 'data' => $data]);
    }

    public function orden_compra_productos_data()
    {
        $producto = $this->input->post('producto');
        $proveedor = $this->input->post('proveedor');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $almacen = $this->input->post('almacen');
        $data['almacen'] = $this->almacen->get_all('0');

        $this->layout->template('member')->show('informes/orden_compra_productos', ['data' => $this->informes->orden_compra_productos($proveedor, $producto, $fechainicial, $fechafinal, $almacen), 'proveedor' => $proveedor, 'producto' => $producto, 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data, 'data_empresa' => $data_empresa]);
    }

    public function ex_orden_compra_productos_data()
    {
        $producto = $this->input->post('producto');
        $proveedor = $this->input->post('proveedor');
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $almacen = $this->input->post('almacen');
        $data['almacen'] = $this->almacen->get_all('0');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'producto');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'precio de compra');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'proveedor');

        $query = $this->informes->orden_compra_productos($proveedor, $producto, $fechainicial, $fechafinal, $almacen);

        $row = 2;

        foreach ($query['total_ventas_3'] as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value['fecha']);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value['nombre_producto']);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value['precio_compra']);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value['unidades']);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value['nomprove']);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:E' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Productos comprados');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="productos comprados.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;

    }

    public function total_saldo_clientes()
    {
        acceso_informe('Saldo total por clientes');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_saldo_clientes', ['data1' => $data, 'data' => $data]);
    }

    public function total_saldo_clientes_data()
    {
        $cliente = $this->input->post('cliente');

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $this->layout->template('member')->show('informes/total_saldo_clientes', ['data' => $this->informes->total_saldo_clientes($cliente), 'cliente' => $cliente, 'data_empresa' => $data_empresa]);
    }

    public function total_saldo_proveedor()
    {
        acceso_informe('Saldo total por proveedores');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_saldo_proveedor', ['data1' => $data, 'data' => $data]);
    }

    public function total_saldo_proveedor_data()
    {
        $proveedor = $this->input->post('proveedor');

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $this->layout->template('member')->show('informes/total_saldo_proveedor', ['data' => $this->informes->total_saldo_proveedor($proveedor), 'proveedor' => $proveedor, 'data_empresa' => $data_empresa]);
    }

    public function stock_minimo_maximo()
    {
        acceso_informe('Stock Actual');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $data['almacenes'] = $this->almacenes->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/stock_minimo_maximo', ['data' => $data]);
    }

    public function informe_producto_por_almacen()
    {
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_producto_por_almacen', array('data' => $data));
    }

    public function get_ajax_data_producto_por_almacen()
    {
        $producto = $this->input->post('producto');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->producto_por_almacen($producto)));
    }

    public function get_ajax_data_stock_minimo_maximo()
    {
        $start = $this->input->get('iDisplayStart');
        $limit = $this->input->get('iDisplayLength');

        $almacen = $this->input->get('almacen');
        $stock = $this->input->get('stock');
        if (empty($stock) or $stock == 'undefined') {
            $stock = 'maximo';
        }
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->get_ajax_data_stock_minimo_maximo($almacen, $stock, $precio_almacen, $start, $limit)));
    }

    /* INVOCE2 =============================================== */

    //Cuadre de caja
    public function cuadre_caja()
    {
        acceso_informe('Cuadre de caja');
        $fecha = $this->input->post('date');
        $tipo = $this->input->post('tipo');
        $almacen = $this->input->post('almacen');
        $data['almacen'] = $this->almacen->get_all('0');

        $this->layout->template('member')->show('informes/cuadre_caja', ['data' => $this->informes->cuadre_caja($fecha, $tipo, $almacen), 'tipo' => $tipo, 'fecha' => $fecha, 'almacen' => $almacen, 'data1' => $data]);
    }

    public function cuadre_caja_data()
    {
        $fecha = $this->input->post('date');
        $tipo = $this->input->post('tipo');
        $almacen = $this->input->post('almacen');
        $data['almacen'] = $this->almacen->get_all('0');
        //var_dump($this->informes->cuadre_caja($fecha, $tipo, $almacen));
        //$this->informes->cuadre_caja($fecha, $tipo, $almacen);
        $this->layout->template('member')->show('informes/cuadre_caja', ['data' => $this->informes->cuadre_caja($fecha, $tipo, $almacen), 'tipo' => $tipo, 'fecha' => $fecha, 'almacen' => $almacen, 'data1' => $data]);
    }

    public function imprimir_cuadre_caja($tipo = null, $fecha_cierre = null, $almacen = null)
    {

        $empresa = $this->miempresa->get_data_empresa();

        $caja = $this->informes->cuadre_caja($fecha_cierre, $tipo, $almacen);

        //Ventas anouladas
        $ventasAnuladas = $this->Caja->obtenerVentasAnuladas($fecha_cierre);

        //Ventas devueltas
        $ventasDevueltas = $this->Caja->obtenerDevoluciones($fecha_cierre, $almacen);

        $nombre_empresa = $empresa["data"]['nombre'];
        $dire = $empresa["data"]['direccion'];
        $telef = $empresa["data"]['telefono'];
        $email = $empresa["data"]['email'];

        $cafac = $empresa["data"]['cabecera_factura'];
        $nit = $empresa["data"]['nit'];
        $resol = $empresa["data"]['resolucion'];
        $tele = $empresa["data"]['telefono'];
        $dire = $empresa["data"]['direccion'];
        $web = $empresa["data"]['web'];

        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

        $pdf->setPrintHeader(false);

        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', "LETTER");

        if ($tipo === 'producto') {
            $html = '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
    <tr>
        <th align="center"><b>' . $nombre_empresa . '</b></th>
    </tr>
    <tr>
        <td align="center"><b>Cierre de Caja:</b></td>
    </tr>
    <tr>
        <td align="center">Fecha: <b>' . $fecha_cierre . '</b></td>
    </tr>
    <tr>
        <td align="center">Tipo: <b>Producto</b></td>
    </tr>
</table>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
                            <th><b>#</b></th>
                            <th><b>Forma de pago</b></th>
                            <th align="right"><b>Valor</b></th>
                        </tr>
';

            $cantidad = 0;
            $total = 0;

            foreach ($caja['forma_pago'] as $value) {

                $cantidad += $value['cantidad'];
                $total += $value['vr_valor'];

                $formpago = str_replace("_", " ", $value['forma_pago']);
                $formpago = ucfirst($formpago);

                $html .= '
                        <tr>
                            <td align="left">
                                ' . $value['cantidad'] . '

                            </td>
                            <td align="left">
                                ' . $formpago . '
                            </td>
                            <td align="right">
                               ' . number_format($value['vr_valor']) . '

                            </td>
                        </tr>
';
            }

            $html .= '  </table>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3"  style=" font-size:9px" >
                        <tr>
                            <td>
                                ' . $cantidad . '
                            </td>
                            <td>
                                <strong>Total ventas</strong>
                            </td>
                            <td  align="right">
                                 ' . number_format($total + $ventasAnuladas['total']) . '
                            </td>
                        </tr>

                        <tr>
                            <td>
                                ' . count($ventasAnuladas['data']) . '
                            </td>
                            <td>
                                <strong>Total ventas anuladas</strong>
                            </td>
                            <td  align="right">
                                 ' . number_format($ventasAnuladas['total']) . '
                            </td>
                        </tr>

                        <tr>
                            <td>
                                ' . count($ventasDevueltas['data']) . '
                            </td>
                            <td>
                                <strong>Ventas devueltas</strong>
                            </td>
                            <td  align="right">
                                ' . number_format($ventasDevueltas['total']) . '
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <strong>Total gastos</strong>
                            </td>
                            <td  align="right">
';
            $gastos = 0;

            foreach ($caja['gastos'] as $key => $value) {
                $gastos = $value->total;
            }

            $html .= '
                                  ' . number_format($gastos) . '
                            </td>
                        </tr>
';
            $total_creditos = 0;

            foreach ($caja['forma_pago_credito'] as $value):
                $html .= '
	                                                                                            <tr>
	                                                                                                <td>

	                                                                                                </td>
	                                                                                                <td>
	                                                                                                    <strong>Total de pagos a creditos</strong>
	                                                                                                </td>
	                                                                                                <td   align="right">
	                                                                                                      ' . number_format($total_creditos = $value['total_credito']) . '
	                                                                                                </td>
	                                                                                            </tr>
	                                                                    ';
            endforeach;

            $total_proveedor = 0;

            foreach ($caja['forma_pago_proveedor'] as $value):

                $html .= '
	                                                                                            <tr>
	                                                                                                <td>
	                                                                                                </td>
	                                                                                                <td >
	                                                                                                    <strong>Total de pagos a proveedores</strong>
	                                                                                                </td>
	                                                                                                <td   align="right">
	                                                                                                    ' . number_format($total_proveedor = $value['total_proveedor']) . '
	                                                                                                </td>
	                                                                                            </tr>
	                                                                    ';
            endforeach;

            //Abonos plan separe
            $total_abonos_plan_separe = 0;

            foreach ($caja['abonos_plan_separe_array'] as $value):

                $html .= '
	                        <tr>
	                            <td>
	                            </td>
	                            <td >
	                                <strong>Total de abonos plan separe</strong>
	                            </td>
	                            <td   align="right">
	                                ' . number_format($total_abonos_plan_separe = $value['valor']) . '
	                            </td>
	                        </tr>
	                        ';
            endforeach;

            foreach ($caja['forma_pago_ventas'] as $value):
                $total_ventas_cierre += $value['vr_valor'];
            endforeach;

            $html .= '
                        <tr>
                            <td>

                            </td>
                            <td>
                                <strong>Total cierre</strong>
                            </td>
                            <td  align="right">
                                ' . number_format($total - $gastos + $total_creditos - $total_proveedor + $total_abonos_plan_separe) . '
                            </td>
                        </tr>
';
            $html .= '  </table><BR><BR><b  style=" font-size:9px" >Resumen de Productos Vendidos</b><BR>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3"  style=" font-size:9px" >
                        <tr>
                            <th width="290"><b>Descripción</b></th>
                            <th width="80"><b>Cantidad</b></th>
                            <th width="80"><b>Descuento</b></th>
                            <th width="90" align="right"><b>Valor a Pagar</b></th>
                        </tr>
';

            $vr_valor = 0;
            $vr_descuento = 0;
            $impuesto = 0;

            foreach ($caja['factura_data'] as $value) {

                if ($value['venta_plan_activo'] === '1') {
                    $vr_valor += $value['valor'] + $value['valor_impuesto'];
                    $vr_descuento += $value['descuento'];
                    $impuesto += $value['valor_impuesto'];
                }

                $html .= '
                        <tr style="border-top: 1px solid #000000;">

                            <td align="left" style="border-top: 1px solid #000000;">
                                ' . $value['nombre_producto'] . '

                            </td>
                            <td align="left" style="border-top: 1px solid #000000;">
                                ' . $value['unidades'] . '
                            </td>
                            <td align="left" style="border-top: 1px solid #000000;">
                                ' . number_format($value['descuento']) . '
                            </td>
                            <td align="right" style="border-top: 1px solid #000000;">
                               ' . number_format($value['valor'] + $value['valor_impuesto']) . '

                            </td>
                        </tr>
';
            }

            $html .= '  </table>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3"  style=" font-size:9px" >
                        <tr>
                            <th><b></b></th>
                            <th><b></b></th>
                            <th><b>Total</b></th>
                            <th align="right"><b>' . number_format($vr_valor) . '</b></th>
                        </tr>
</table><BR><BR><b  style=" font-size:9px" >Impuestos por Ventas</b><BR>
';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3"  style=" font-size:9px" >
                        <tr>
                            <th><b>Nombre</b></th>
                            <th  align="right"><b>Valor</b></th>
                        </tr>
';

            foreach ($caja['impuesto_result'] as $value) {

                $html .= '
                        <tr>
                            <td align="left">
                                ' . $value->imp . '
                             </td>
                            <td align="right">
                                ' . number_format(($value->total_precio_venta - $value->total_descuento) + $value->impuestos) . '
                            </td>
                        </tr>
';
            }

            $html .= '  </table><BR><BR>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3"  style=" font-size:9px" >
    <tr>
        <th align="center"><b>FIRMA:__________________________________________________________________________</b></th>
    </tr>
    <tr>
        <th align="center"><b></b></th>
    </tr>
    <tr>
        <th align="center"><b>CEDULA:_________________________________________________________________________</b></th>
    </tr>
</table>';
        }

        if ($tipo === 'factura') {
            $html = '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
    <tr>
        <th align="center"><b>' . $nombre_empresa . '</b></th>
    </tr>
    <tr>
        <td align="center"><b>Cierre de Caja:</b></td>
    </tr>
    <tr>
        <td align="center">Fecha: <b>' . $fecha_cierre . '</b></td>
    </tr>
    <tr>
        <td align="center">Tipo: <b>Facturas</b></td>
    </tr>
</table>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
                            <th><b>#</b></th>
                            <th><b>Forma de pago</b></th>
                            <th align="right"><b>Valor</b></th>
                        </tr>
';

            $cantidad = 0;
            $total = 0;

            foreach ($caja['forma_pago'] as $value) {

                $cantidad += $value['cantidad'];
                $total += $value['vr_valor'];

                $formpago = str_replace("_", " ", $value['forma_pago']);
                $formpago = ucfirst($formpago);

                $html .= '
                        <tr>
                            <td align="left">
                                ' . $value['cantidad'] . '

                            </td>
                            <td align="left">
                                ' . $formpago . '
                            </td>
                            <td align="right">
                               ' . number_format($value['vr_valor']) . '

                            </td>
                        </tr>
';
            }

            $html .= '  </table>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
                            <td>
                                ' . $cantidad . '
                            </td>
                            <td>
                                <strong>Total ventas</strong>
                            </td>
                            <td  align="right">
                                 ' . number_format($total) . '
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <strong>Total gastos</strong>
                            </td>
                            <td  align="right">
';
            $gastos = 0;

            foreach ($caja['gastos'] as $key => $value) {
                $gastos = $value->total;
            }

            $html .= '
                                  ' . number_format($gastos) . '
                            </td>
                        </tr>
';
            $total_creditos = 0;

            foreach ($caja['forma_pago_credito'] as $value):
                $html .= '
	                                                                                            <tr>
	                                                                                                <td>

	                                                                                                </td>
	                                                                                                <td>
	                                                                                                    <strong>Total de pagos a creditos</strong>
	                                                                                                </td>
	                                                                                                <td   align="right">
	                                                                                                      ' . number_format($total_creditos = $value['total_credito']) . '
	                                                                                                </td>
	                                                                                            </tr>
	                                                                    ';
            endforeach;

            $total_proveedor = 0;

            foreach ($caja['forma_pago_proveedor'] as $value):

                $html .= '
	                                                                                            <tr>
	                                                                                                <td>
	                                                                                                </td>
	                                                                                                <td >
	                                                                                                    <strong>Total de pagos a proveedores</strong>
	                                                                                                </td>
	                                                                                                <td   align="right">
	                                                                                                    ' . number_format($total_proveedor = $value['total_proveedor']) . '
	                                                                                                </td>
	                                                                                            </tr>
	                                                                    ';
            endforeach;

            foreach ($caja['forma_pago_ventas'] as $value):
                $total_ventas_cierre += $value['vr_valor'];
            endforeach;

            $html .= '
                        <tr>
                            <td>
                            </td>
                            <td>
                                <strong>Total cierre</strong>
                            </td>
                            <td  align="right">
                                ' . number_format($total_ventas_cierre - $gastos + $total_creditos - $total_proveedor) . '
                            </td>
                        </tr>
';
            $html .= '  </table><BR><BR><b style=" font-size:9px" >Facturas</b><BR>';
            $html .= '<hr>';

            foreach ($caja['factura_data'] as $value):
                $empresa = $value['empresa'];
            endforeach;

            $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
                            <th><b>Factura</b></th> ';

            if ($empresa === 'TCC S.A.') {
                $html .= '
                            <th ><b>Vendedor</b></th>
';
            }

            $html .= '
                            <th align="right"><b>VR Bruto</b></th>
                            <th align="right"><b>VR Iva</b></th>
                            <th align="right"><b>Descuento</b></th>
                            <th align="right"><b>VR Neto</b></th>
                        </tr>
';

            $vr_bruto = 0;
            $vr_impuesto = 0;
            $vr_total = 0;
            $vr_descuento = 0;

            foreach ($caja['factura_data'] as $value) {

                if ($value['venta_plan_activo'] === '1') {
                    $vr_bruto += $value['vr_bruto'];
                    $vr_impuesto += $value['vr_impuesto'];
                    $vr_total += $value['vr_valor'] + $value['vr_impuesto'];
                    $vr_descuento += $value['descuento'];
                    $impuesto += $value['vr_impuesto'];
                }

                $html .= '
                        <tr>
                            <td align="left">
                                ' . $value["factura"] . '

                            </td>
';

                if ($empresa === 'TCC S.A.') {
                    $html .= '
                            <td align="left">
                                ' . $value["vendedor"] . '
                             </td>
';
                }

                $html .= '
                            <td align="right">
                                ' . number_format($value['vr_bruto']) . '
                            </td>
                            <td align="right">
                                ' . number_format($value['vr_impuesto']) . '
                            </td>
                            <td align="right">
                                ' . number_format($value['descuento']) . '
                            </td>
                            <td align="right">
                               ' . number_format($value['vr_valor'] + $value['vr_impuesto']) . '

                            </td>
                        </tr>
';
            }

            $html .= '                      <tr >
                            <td style="border-top: 1px solid #000000;" >
                                <strong>Totales</strong>
                            </td>
';

            if ($empresa === 'TCC S.A.') {
                $html .= '
                            <td  style="border-top: 1px solid #000000;">
                             </td>
';
            }

            $html .= '
                            <td  align="right" style="border-top: 1px solid #000000;">
                                 ' . number_format($vr_bruto) . '
                            </td>
                             <td  align="right" style="border-top: 1px solid #000000;">
                                 ' . number_format($vr_impuesto) . '
                            </td>
                             <td  align="right" style="border-top: 1px solid #000000;">
                                ' . number_format($vr_descuento) . '
                            </td>
                              <td  align="right" style="border-top: 1px solid #000000;">
                                 ' . number_format($vr_total) . '
                            </td>
                        </tr>
    ';
            $html .= '  </table>';
            $html .= '<hr>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
                        <tr>
                            <th><b>Nombre</b></th>
                            <th  align="right"><b>Valor</b></th>
                        </tr>
';

            foreach ($caja['impuesto_result'] as $value) {

                $html .= '
                        <tr>
                            <td align="left">
                                ' . $value->imp . '
                             </td>
                            <td align="right">
                                ' . number_format(($value->total_precio_venta - $value->total_descuento) + $value->impuestos) . '
                            </td>
                        </tr>
';
            }

            $html .= '  </table><BR><BR>';
            $html .= '
<table border="0" cellspacing="1" cellpadding="3" style=" font-size:9px" >
    <tr>
        <th align="center"><b>FIRMA:__________________________________________________________________________</b></th>
    </tr>
    <tr>
        <th align="center"><b></b></th>
    </tr>
    <tr>
        <th align="center"><b>CEDULA:_________________________________________________________________________</b></th>
    </tr>
</table>';
        }

        ob_clean();
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output('cuadre de caja ' . $fecha_cierre . '.pdf', 'I');
    }

//Cuadre de caja---------------------------------------------
    //Ventas por clientes
    public function ventasgroupclientes()
    {
        acceso_informe('Ventas por clientes');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/ventasgroupclientes', array('data' => $data));
    }

    public function get_ajax_data_ventasgroupclientes()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->ventasgroupclientes($fecha_inicio, $fecha_fin)));
    }

    public function exventasgroupclientes()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Nombre Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Identificación');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Teléfono');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Email');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Vendedores');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Total venta');

        $query = $this->informes->ventasgroupclientes($fecha_inicio, $fecha_fin, false);
        $row = 2;

        foreach ($query['aaData'] as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[8]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:H' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Ventas por clientes');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ventas por clientes.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    //Gastos

    public function informe_gastos()
    {
        acceso_informe('Informe de gastos');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_gastos', ['data1' => $data, 'data' => $data]);
    }

    public function get_ajax_data_informe_gastos()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->informe_gastos($fecha_inicio, $fecha_fin, '', $almacen)));
    }

    public function exinformegastos()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Consecutivo');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Proveedor');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Descripción');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Valor con Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Cuenta Dinero');

        $query = $this->informes->informe_gastos($fecha_inicio, $fecha_fin, 'excel', $almacen);
        $row = 2;
//die(var_dump($query['aaData']));
        foreach ($query['aaData'] as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]); //echo $value[6];
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[9]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:J' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Gastos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Gastos.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    //Impuestos
    public function informe_impuestos()
    {
        acceso_informe('Informe de Impuestos');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_impuestos', array('data' => $data));
    }

    public function get_ajax_data_informe_impuesto()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->informe_impuesto($fecha_inicio, $fecha_fin)));
    }

    public function total_inventario_imeis()
    {
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_inventario_imeis', array('data' => $data));
    }

    public function exinforme_impuesto()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Numero');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Valor');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Descuento');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Valor Total');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Forma de pago');

        $query = $this->informes->informe_impuesto_excel($fecha_inicio, $fecha_fin);
        $row = 2;
        $total_ventas = 0;
        foreach ($query['aaData'] as $value) {
            $total_ventas += $value[5];
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $row++;
        }

        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 'Devoluciones:');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $query['devoluciones']);
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 'Ventas:');
        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $total_ventas);
        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, 'Total:');
        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, ($total_ventas - $query['devoluciones']));

        $style_totales = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'bottom' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'left' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
                'right' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startcolor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endcolor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('B' . $row)->applyFromArray($style_totales);
        $this->phpexcel->getActiveSheet()->getStyle('D' . $row)->applyFromArray($style_totales);
        $this->phpexcel->getActiveSheet()->getStyle('F' . $row)->applyFromArray($style_totales);

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:G' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe de impuesto');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de impuesto.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    //
    public function informe_comisiones()
    {
        acceso_informe('Informe de comisiones por vendedor');
        $this->load->model("vendedores_model", 'vendedores');
        $this->vendedores->initialize($this->dbConnection);
        $options = "<option value=''>Seleccione Vendedor</option>";

        foreach ($this->vendedores->get_all_invoce2() as $value) {
            $options .= "<option value='{$value->id}'>{$value->nombre}</option>";
        }
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_comision', ['options' => $options, 'data' => $data]);
    }

    public function informe_vendedor_utilidad()
    {
        acceso_informe('Total de comisiones de vendedor por utilidad');
        $this->load->model("vendedores_model", 'vendedores');
        $this->vendedores->initialize($this->dbConnection);
        $options = "<option value=''>Seleccione Vendedor</option>";

        foreach ($this->vendedores->get_all_invoce2() as $value) {
            $options .= "<option value='{$value->id}'>{$value->nombre}</option>";
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_utilidad_vendedor', ['options' => $options, 'data' => $data]);
    }

    public function total_ventas_vendedor()
    {
        acceso_informe('Total de comisiones por vendedor');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/total_ventas_vendedor', ['data1' => $data, 'data' => $data]);
    }

    public function total_ventas_data_vendedor()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $data['almacen'] = $this->almacen->get_all('0');
        $this->layout->template('member')->show('informes/total_ventas_vendedor', ['data' => $this->informes->total_vendedores($fechainicial, $fechafinal, $almacen), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'data1' => $data]);
    }

    public function get_ajax_data_informe_comision()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $vendedor = $this->input->get('vendedor');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->informe_vendedores($fecha_inicio, $fecha_fin, $vendedor)));
    }

    //informe de utilidad
    public function get_ajax_data_informe_utilidad()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $vendedor = $this->input->get('vendedor');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->informe_vendedores_utilidad($fecha_inicio, $fecha_fin, $vendedor)));
    }

    public function ex_informe_comision()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $vendedor = $this->input->get('vendedor');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Nombre Vendedor');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Total');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Porciento');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Valor Comision');

        $query = $this->informes->informe_vendedores($fecha_inicio, $fecha_fin, $vendedor);
        $row = 2;

        foreach ($query['aaData'] as $value) {
            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, ($value[6]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe de comisiones');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de comisiones.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    // informe utilidad vendedor
    public function ex_informe_utilidad()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $vendedor = $this->input->get('vendedor');

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Nombre Vendedor');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Total');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Costo');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Utilidad');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Porciento');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Valor Comision');

        $query = $this->informes->informe_vendedores_utilidad($fecha_inicio, $fecha_fin, $vendedor);
        //print_r($query);
        $row = 2;

        //print_r($query['aaData']);
        //die();
        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:I' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        //$this->phpexcel->getActiveSheet()->getStyle("E1:E35000")->getNumberFormat()->setFormatCode('#,##');
        //$this->phpexcel->getActiveSheet()->getStyle("F1:F35000")->getNumberFormat()->setFormatCode('#,##');
        //$this->phpexcel->getActiveSheet()->getStyle("G1:G35000")->getNumberFormat()->setFormatCode('#,##');
        // $this->phpexcel->getActiveSheet()->getStyle("H1:H35000")->getNumberFormat()->setFormatCode('#,##0.');
        //$this->phpexcel->getActiveSheet()->getStyle("I1:I35000")->getNumberFormat()->setFormatCode('#,##');

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe de comisiones');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de comision utilidad.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function informe_movimientos()
    {
        acceso_informe('Informe de movimientos de inventario');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $options = "<option value=''>Seleccione Almacen</option>";
        $offset = "";

        foreach ($this->almacenes->get_all($offset) as $value) {
            $options .= str_replace('"', "'", "<option value='{$value->id}'>{$value->nombre}</option>");
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_movimientos', ['options' => $options, 'data' => $data]);
    }

    public function ventas_domicilios()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $domiciliario = $this->input->get('domiciliario');

        $options_domiciliarios = "<option value=''>Seleccione Domiciliario</option>";
        $domiciliarios = $this->domiciliarios->get_domiciliarios();
        $options = "<option value=''>Seleccione Almacén</option>";
        $offset = "";

        foreach ($this->almacenes->get_all($offset) as $value) {
            $options .= "<option value='{$value->id}'>{$value->nombre}</option>";
        }

        if (!empty($domiciliarios)) {
            foreach ($domiciliarios as $key => $domi) {
                $options_domiciliarios .= "<option value='" . $domi['id'] . "'>" . $domi['descripcion'] . "</option>";
            }
        }

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-01');
        }

        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $this->layout->template('member')->show('informes/ventas_domicilios', ['options' => $options, 'options_domiciliarios' => $options_domiciliarios, 'data' => $data]);
    }

    public function get_ajax_ventas_domicilios()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $domiciliario = $this->input->get('domiciliario');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-01');
        }

        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        echo json_encode($this->informes->ventas_domicilios($fecha_inicio, $fecha_fin, $almacen, $domiciliario));

    }

    public function ex_ventas_domicilios()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $domiciliario = $this->input->get('domiciliario');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-01');
        }

        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        $this->load->library('phpexcel');

        $datos = $this->informes->ventas_domicilios($fecha_inicio, $fecha_fin, $almacen, $domiciliario);

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Id');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Almacén');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Domiciliario');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Teléfono');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Dirección');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'N° Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Total venta');
        $row = 2;

        foreach ($datos['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $row++;
        }

        $this->phpexcel->getActiveSheet()->setTitle('ventas por domicilios');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ventas_domicilios.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        if (ob_get_contents()) {
            ob_clean();
        }
        $objWriter->save('php://output');
    }

    public function detalle_ventas_por_vendedor()
    {
        acceso_informe('Detalle de ventas por vendedor');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $options = "<option value=''>Todos los almacenes</option>";
        $offset = "";

        foreach ($this->almacenes->get_all($offset) as $value) {
            $options .= "<option value='{$value->id}'>{$value->nombre}</option>";
        }

        $options_vendedor = "<option value=''>Seleccione vendedor</option>";
        $vendedores = $this->vendedores->get();
        if ($vendedores != null) {
            foreach ($vendedores as $vendedor) {
                $options_vendedor .= "<option value='{$vendedor->id}'>{$vendedor->nombre}</option>";
            }
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $this->layout->template('member')->show('informes/detalle_ventas_por_vendedor', ['options' => $options, 'options_vendedor' => $options_vendedor, 'data' => $data]);
    }

    public function ventas_por_tomapedido()
    {

        $this->ordenes->create_table_historico_ordenes();

        acceso_informe('Detalle de ventas por toma pedido');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $offset = "";

        /** options almacen */
        $options_almacen = "<option value=''>Almacenes</option>";
        foreach ($this->almacenes->get_all($offset) as $value) {
            $options_almacen .= "<option value='{$value->id}'>{$value->nombre}</option>";
        }

        /** options zonas */
        $options_zonas = "<option value=''>Zonas</option>";
        $zonas = $this->secciones_almacen->get_secciones_almacen("a.id > 0");
        foreach ($zonas as $value) {
            $options_zonas .= "<option value='{$value->id}'>{$value->nombre_seccion}</option>";
        }

        /** options zonas */
        $options_mesas = "<option value=''>Mesas</option>";
        $mesas = $this->mesas_secciones->get_mesa_secciones("mesas_secciones.id > 0");
        foreach ($mesas as $value) {
            $options_mesas .= "<option value='{$value->id}'>{$value->nombre_mesa}</option>";
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $this->layout->template('member')->show('informes/ventas_por_tomapedido', ['options_almacen' => $options_almacen, 'options_zonas' => $options_zonas, 'options_mesas' => $options_mesas, 'data' => $data]);
    }

    public function get_ajax_data_detalle_ventas_por_vendedor()
    {
        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $vendedor = $this->input->get('vendedor');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        echo json_encode($this->informes->detalle_ventas_por_vendedor($fecha_inicio, $fecha_fin, $almacen, $vendedor));
    }

    public function get_ajax_data_ventas_por_tomapedido()
    {
        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $zona = $this->input->get('zona');
        $mesa = $this->input->get('mesa');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        echo json_encode($this->informes->ventas_por_tomapedido($fecha_inicio, $fecha_fin, $almacen, $zona, $mesa));
    }

    public function ex_detalle_ventas_por_vendedor()
    {
        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');
        $vendedor = $this->input->get('vendedor');
        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        $this->load->library('phpexcel');

        $datos = $this->informes->detalle_ventas_por_vendedor($fecha_inicio, $fecha_fin, $almacen, $vendedor);

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'No Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Vendedor');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Cedula Vendedor');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Descuentos');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Neto sin impuesto');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Total venta');
        $row = 2;

        foreach ($datos['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[8]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $row++;
        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, 'Venta neta');
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, 'Impuestos');
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, 'Descuentos');
        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, 'Unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, 'Transacciones');
        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, 'UPT');
        $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, 'VPT');
        $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, 'VPU');
        $row++;

        $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $datos['total_venta_neta']);
        $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $datos['total_impuestos']);
        $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $datos['total_descuentos']);
        $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $datos['total_unidades']);
        $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $datos['total_transacciones']);
        $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $datos['UPT']);
        $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $datos['VPT']);
        $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $datos['VPU']);

        $row++;
        $this->phpexcel->getActiveSheet()->getStyle('A1:I' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],

                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('ventas por vendedor');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        $file_name = "ventas por vendedor $fecha_inicio $fecha_fin.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $file_name .'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');

    }

    public function get_ajax_data_informe_movimientos()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');

        if (!empty($fecha_inicio)) {
            $fecha_inicio = $fecha_inicio . ' 00:00:00';
        }
        if (!empty($fecha_fin)) {
            $fecha_fin = $fecha_fin . ' 23:59:59';
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->informe_movimientos($fecha_inicio, $fecha_fin, $almacen)));
    }

    public function stock_diario()
    {
        acceso_informe('Informe movimiento material');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $options = "<option value=''>Seleccione Almacen</option>";
        $offset = "";

        foreach ($this->almacenes->get_all($offset) as $value) {
            $options .= "<option value='{$value->id}'>{$value->nombre}</option>";
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/stock_diario', ['options' => $options, 'data' => $data]);
    }

    public function get_ajax_data_stock_diario()
    {
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-d');
        }

        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->stock_diario($fecha_inicio, $fecha_fin, $almacen)));
    }

    public function excel_stock_diario()
    {
        $this->load->library('phpexcel');

        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $almacen = $this->input->get('almacen');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date('Y-m-d');
        }

        if (empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
        }

        //$datos = $this->informes->stock_diario('', '', '');
        $datos = $this->informes->stock_diario($fecha_inicio, $fecha_fin, $almacen);

        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Tipo movimiento');
        $row = 2;

        foreach ($datos['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:E' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],

                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe movimiento material');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe movimiento material.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');

    }

    public function plan_separe_productos()
    {
        acceso_informe('Informe de plan separe');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $data['almacenes'] = $this->almacenes->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/informe_plan_separe', ['data' => $data]);
    }

    public function get_ajax_data_plan_separe_productos()
    {
        $almacen = $this->input->get('almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->plan_separe_productos($almacen)));
    }

    public function clientes_puntos_acumulados()
    {
        acceso_informe('Puntos Acumulados por Cliente');
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $data['almacenes'] = $this->almacenes->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/clientes_puntos_acumulados', ['data' => $data]);
    }

    public function get_ajax_data_clientes_puntos_acumulados()
    {
        $almacen = $this->input->get('almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->clientes_puntos_acumulados()));
    }

    public function expuntos_acumulados()
    {
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Nombre');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'No Identificación');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Total Puntos');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Valor');
        $query = $this->informes->clientes_puntos_acumulados();
        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:D' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Informe de puntos');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de puntos.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        ob_clean();
        $objWriter->save('php://output');
        exit;
    }

    public function ex_plan_separe($almacen = null)
    {
        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Cédula');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Nombre del producto');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Precio');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Precio + Iva');
        $query = $this->informes->plan_separe_productos($almacen);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:G' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Informe de plan separe');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de plan separe.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        ob_clean();
        $objWriter->save('php://output');
        exit;
    }

    public function exstockminimomaximo()
    {

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Codigo');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Precio');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Unidades');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Proveedor');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Stock Minimo');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Valor del inventario');

        $almacen = $this->input->get('almacen');
        $stock = $this->input->get('stock');
        if (empty($stock) or $stock == 'undefined') {
            $stock = 'maximo';
        }
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $query = $this->informes->get_ajax_data_stock_minimo_maximo($almacen, $stock, $precio_almacen, 1, 1000);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:H' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('Informe de Stock minimo');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de stock.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
        ob_clean();
        $objWriter->save('php://output');
        exit;
    }

    public function grafica_ventas_almacen()
    {
        acceso_informe('Grafica de Ventas por Almacén');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('ventas')
        //->js(array(base_url("/public/js/amchart/amcharts.js"), base_url("/public/js/amchart/serial.js")  ))
            ->show('informes/grafica_ventas_almacen', ['data1' => $data, 'filtro_ciudad' => $filtro_ciudad, 'data' => $data]);
    }

    public function grafica_ventas_almacen_data()
    {
        $fechainicial = $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $ciudad = $this->input->post('provincia');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $filtro_ciudad = $data_empresa['data']['filtro_ciudad'];
        $this->layout->template('ventas')->show('informes/grafica_ventas_almacen', ['data' => $this->informes->grafica_ventas_almacen($fechainicial, $fechafinal, $almacen, $ciudad), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen, 'ciudad' => $ciudad, 'data1' => $data, 'filtro_ciudad' => $filtro_ciudad]);
    }

    public function notas()
    {
        acceso_informe('Informe Notas Credito y Debito');
        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

        $data['almacenes'] = $this->almacenes->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/notas', ['data' => $data]);
    }

    public function get_ajax_data_notas()
    {
        $almacen = $this->input->get('almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->get_ajax_data_notas($almacen, true)));
    }

    public function exnotas()
    {
        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Consecutivo');

        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Devolución');

        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Usuario');

        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Tipo');

        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor');

        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Fecha');

        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Factura');

        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Cliente');

        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Estado');

        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Factura Asociada');

        $almacen = $this->input->get('almacen');

        $query = $this->informes->get_ajax_data_notas($almacen);

        $row = 2;

        foreach ($query['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);

            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);

            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);

            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);

            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);

            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);

            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);

            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);

            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);

            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[9]);

            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:J' . --$row)->applyFromArray($styleThinBlackBorderOutline);

        $this->phpexcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );

        // Rename worksheet

        $this->phpexcel->getActiveSheet()->setTitle('Notas Credito y Debito');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="notas credito y debito.xls"');

        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed

        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1

        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        ob_clean();

        $objWriter->save('php://output');

        exit;
    }

    public function comprasCliente()
    {
        acceso_informe('Ventas detallas por clientes');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/comprasCliente', ['data1' => $data, 'data' => $data]);
    }

    public function get_ajax_data_comprasCliente()
    {

        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $cliente = $this->input->post('id_cliente') != "" && $this->input->post('datos_cliente') != "" ? $this->input->post('id_cliente') : "";
        $producto = $this->input->post('id_producto') != "" && $this->input->post('nombre-producto') != "" ? $this->input->post('id_producto') : "";
        echo json_encode($this->informes->comprasCliente($fechainicial, $fechafinal, $almacen, $cliente, $producto, true));
    }

    public function ex_comprasCliente()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $cliente = $this->input->post('id_cliente') != "" && $this->input->post('datos_cliente') != "" ? $this->input->post('id_cliente') : "";
        $producto = $this->input->post('id_producto') != "" && $this->input->post('nombre-producto') != "" ? $this->input->post('id_producto') : "";
        $resultados = $this->informes->comprasCliente($fechainicial, $fechafinal, $almacen, $cliente, $producto);

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        /* $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Valor Unitario');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor Total'); */

        $row = 1;

        foreach ($resultados['total_ventas'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, ($value[5]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe ventas por clientes');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe ventas detalladas por clientes.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function ventasVendedor()
    {
        acceso_informe('Ventas detallas por vendedor');
        $data['almacen'] = $this->almacen->get_all('0');
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/ventasVendedor', ['data1' => $data, 'data' => $data]);
    }

    public function get_ajax_data_ventasVendedor()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $vendedor = $this->input->post('vendedor') != "" && $this->input->post('datos_vendedor') != "" ? $this->input->post('vendedor') : "";
        $producto = $this->input->post('id_producto') != "" && $this->input->post('nombre-producto') != "" ? $this->input->post('id_producto') : "";
        echo json_encode($this->informes->ventasVendedor($fechainicial, $fechafinal, $almacen, $vendedor, $producto, true));
    }

    public function ex_ventasVendedor()
    {
        $fechainicial = $this->input->post('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->post('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->post('almacen');
        $vendedor = $this->input->post('vendedor') != "" && $this->input->post('datos_vendedor') != "" ? $this->input->post('vendedor') : "";
        $producto = $this->input->post('id_producto') != "" && $this->input->post('nombre-producto') != "" ? $this->input->post('id_producto') : "";
        $resultados = $this->informes->ventasVendedor($fechainicial, $fechafinal, $almacen, $vendedor, $producto);

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);
        /* $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Cliente');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Valor Unitario');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor Total'); */

        $row = 1;

        foreach ($resultados['total_ventas'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, ($value[5]));
            //$this->phpexcel->getActiveSheet()->setCellValue('G' . $row, ($value[6]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe ventas por vendedor');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        $file_name = "informe ventas vendedor $fechainicial-$fechafinal.xls";
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function ex_ventas_por_tomapedido()
    {
        $fechainicial = $this->input->get('dateinicial') == '' ? date("Y-m-d") : $this->input->post('dateinicial');
        $fechafinal = $this->input->get('datefinal') == '' ? date("Y-m-d") : $this->input->post('datefinal');
        $almacen = $this->input->get('almacen');
        $zona = $this->input->get('zona');
        $mesa = $this->input->get('mesa');
        $resultados = $this->informes->ventas_por_tomapedido($fechainicial, $fechafinal, $almacen, $zona, $mesa);

        $this->load->library('phpexcel');

        $this->phpexcel->setActiveSheetIndex(0);

        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'No. Factura');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Zona');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Mesa');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Producto');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Adiciones');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Modificaciones');
        $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Cantidad');
        $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Total venta');
        $row = 2;

        foreach ($resultados['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, ($value[5]));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, ($value[6]));
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, ($value[7]));
            $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, ($value[8]));
            $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, ($value[9]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:J' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe ventas por toma pedido');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe ventas tomapedido.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    // Actualizacion realizada segun incidencia #175
    public function informe_devolucion_view()
    {
        $this->layout->template('member')->show('informes/devoluciones');
    }

    public function informe_stock_diario_view()
    {
        //die();
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $options = "<option value=''>Seleccione Almacen</option>";
        $offset = "";

        $data['almacenes'] = $this->almacenes->get_combo_data();

        if ($this->input->post('producto') != '' && $this->input->post('dateinicial') != '' && $this->input->post('datefinal') != '') {
            $fechainicial = $this->input->post('dateinicial');
            $fechafinal = $this->input->post('datefinal');
            $producto = $this->input->post('producto');
            $almacen = $this->input->post('almacen');

            $this->load->model("stock_diario_model", 'stock_diario');
            $this->stock_diario->initialize($this->dbConnection);
            $lista = $this->stock_diario->get_by_prod_almac($almacen, $producto, $fechainicial, $fechafinal);
            print_r($lista);
        }

        $this->layout->template('member')->show('informes/stock_diario_view', ['data' => $data]);
    }

    /********************************* */
    /*************BANCOS*************** */
    /********************************* */
    public function bancos()
    {
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $data['almacenes'] = $this->almacenes->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/bancos', ['data' => $data]);
    }

    public function get_ajax_data_bancos()
    {
        $almacen = $this->input->get('almacen');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->informes->get_ajax_data_bancos($almacen)));
    }

    public function ex_bancos()
    {
        $almacen = $this->input->post('almacen');
        $resultados = $this->informes->get_ajax_data_bancos($almacen);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacen');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Banco');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Número cuenta');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Descripción');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Saldo_inicial');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Fecha Creación');
        $row = 2;

        foreach ($resultados['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, ($value[5]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:F' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('informe de bancos');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="informe de bancos.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function movimientos_bancarios()
    {
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $bancos = "<option value=''>Seleccione Banco</option>";
        foreach ($this->bancos->get_bancos() as $value) {
            $bancos .= "<option value='{$value->id}'>{$value->nombre_cuenta}</option>";
        }

        $tipo_movimientos = "<option value=''>Seleccione Tipo</option>";
        $tipo_movimientos .= "<option value='1'>Entrada</option>";
        $tipo_movimientos .= "<option value='2'>Salida</option>";
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/movimientos_bancarios', ['data' => $data, 'bancos' => $bancos, 'tipo_movimientos' => $tipo_movimientos]);
    }

    public function get_ajax_movimientos_bancarios()
    {
        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $banco = $this->input->get('banco');
        $tipo_movimiento = $this->input->get('tipo_movimiento');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        echo json_encode($this->informes->get_ajax_movimientos_bancarios($fecha_inicio, $fecha_fin, $banco, $tipo_movimiento));
    }

    public function ex_movimientos_bancarios()
    {

        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $banco = $this->input->get('banco');
        $tipo_movimiento = $this->input->get('tipo_movimiento');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        $resultados = $this->informes->get_ajax_movimientos_bancarios($fecha_inicio, $fecha_fin, $banco, $tipo_movimiento);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha creac
        ión');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Referencia');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Nombre');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Tipo');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Valor');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Banco');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Estado');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Usuario');
        $row = 2;

        foreach ($resultados['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, ($value[5]));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, ($value[6]));
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, ($value[7]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:H' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('movimientos bancarios');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="movimientos bancarios.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function conciliaciones()
    {
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);
        $bancos = "<option value=''>Seleccione Banco</option>";
        foreach ($this->bancos->get_bancos() as $value) {
            $bancos .= "<option value='{$value->id}'>{$value->nombre_cuenta}</option>";
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $this->layout->template('member')->show('informes/conciliaciones', ['data' => $data, 'bancos' => $bancos]);
    }

    public function get_ajax_conciliaciones()
    {
        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $banco = $this->input->get('banco');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        echo json_encode($this->informes->get_ajax_conciliaciones($fecha_inicio, $fecha_fin, $banco));
    }

    public function ex_conciliaciones()
    {

        $fecha_actual = date('Y-m-d');
        $fecha_inicio = $this->input->get('fecha_inicio');
        $fecha_fin = $this->input->get('fecha_fin');
        $banco = $this->input->get('banco');

        if (empty($fecha_inicio)) {
            $fecha_inicio = date("y-m-d", strtotime($fecha_actual . "- 10 days"));
        }
        if (empty($fecha_fin)) {
            $fecha_fin = date('y-m-d');
        }

        $resultados = $this->informes->get_ajax_conciliaciones($fecha_inicio, $fecha_fin, $banco);

        $this->load->library('phpexcel');
        $this->phpexcel->setActiveSheetIndex(0);
        $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Fecha creación');
        $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Transacción');
        $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Gastos bancarios');
        $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Impuestos bancarios');
        $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Entradas bancarias');
        $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Saldo final');
        $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Fecha corte');
        $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Banco');
        $row = 2;

        foreach ($resultados['aaData'] as $value) {

            $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
            $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
            $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
            $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
            $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, ($value[4]));
            $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, ($value[5]));
            $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, ($value[6]));
            $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, ($value[7]));
            $row++;
        }

        $styleThinBlackBorderOutline = [
            'borders' => [
                'outline' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $this->phpexcel->getActiveSheet()->getStyle('A1:H' . --$row)->applyFromArray($styleThinBlackBorderOutline);
        $this->phpexcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                ],
                'borders' => [
                    'top' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endcolor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ]
        );
        // Rename worksheet
        $this->phpexcel->getActiveSheet()->setTitle('conciliaciones');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        //$this->phpexcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="conciliaciones.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');

        if (ob_get_contents()) {
            ob_clean();
        }

        $objWriter->save('php://output');
        exit;
    }

    public function habitos_consumo_dia_data_ajax()
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return data in format JSON, with data of sales by day
        Require date initial, date end, warehouse, page of search, and page length of search
         */

        //Data of inputs
        $fechainicial = $_POST['fechainicial'];
        $fechafinal = $_POST['fechafinal'];
        $almacen = $_POST['almacen'];
        $page = $_POST['page'];
        $pageLength = $_POST['pageLength'];

        //Data company
        if ($this->session->userdata('is_admin') == 't') {
            $almacen = $this->input->post('almacen');
        } else {
            $almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        }

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['almacen'] = $this->almacen->get_all('0');

        //Return in format JSON, information of sales
        echo json_encode($this->informes->habitos_consumo_dia_ajax($fechainicial, $fechafinal, $almacen, $page, $pageLength));
    }
    public function getSalesByDay()
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return in format JSON, data of products salesed by day
        Require id sales, page of search and page length of search
         */

        //data unputs
        $sales = $_POST['sales'];
        $page = $_POST['page'];
        $pageLength = $_POST['pageLength'];

        //Return in format JSON, information of products by day in a range of data
        echo json_encode($this->informes->getSalesByDay($sales, $page, $pageLength));
    }

}
