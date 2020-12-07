<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'libraries/epayco/index.php';

class Frontend extends CI_Controller
{

    const ATRIBUTOS = 3;

    public $dbConnection;
    public $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->session->userdata('user_id');
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->load->model('pais_model', 'pais');
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("pais_provincia_model", 'pais_provincia');

        $this->load->model("almacenes_model", 'almacen');
        $this->almacen->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');
        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("Caja_model", 'Caja');
        $this->Caja->initialize($this->dbConnection);

        $this->load->model("roles_model", 'roles');
        $this->roles->initialize($this->dbConnection);

        $this->load->model("franquicias_model", 'franquicias');

        //=========================================================================

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);

        $this->load->model("licencias_model", 'licenciasModel');

        //=========================================================================

        $this->load->model("graficas_model", 'graficas');
        $this->graficas->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');
        $this->categorias->initialize($this->dbConnection);

        $this->load->model("ventas_model", 'ventas');
        $this->ventas->initialize($this->dbConnection);

        $this->load->model("proformas_model", 'proformas');
        $this->proformas->initialize($this->dbConnection);

        $this->load->model('pagos_model', 'pagos');
        $this->pagos->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->load->model("secciones_almacen_model", 'secciones_almacen');
        $this->secciones_almacen->initialize($this->dbConnection);

        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);

        $this->load->model('crm_model');

        $this->load->model('crm_licencias_empresa_model');
        $this->load->model('crm_empresas_clientes_model');
        $this->load->model("clientes_model", 'clientes');
        $this->clientes->initialize($this->dbConnection);

        $this->load->model("usuarios_model", 'usuarios');
        $this->usuarios->initialize($this->dbConnection);

        $this->load->model('permisos_model', 'permisos');

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("licencia_model", 'licencias');
        $this->licencias->initialize($this->dbConnection);

        $this->load->model("ordenes_model", 'ordenes');
        $this->ordenes->initialize($this->dbConnection);

        $this->load->model("vendedores_model", 'vendedores');
        $this->vendedores->initialize($this->dbConnection);

        $this->load->model("mesas_secciones_model", 'mesas_secciones');
        $this->mesas_secciones->initialize($this->dbConnection);

        $this->load->model("impresoras_restaurante_model", 'impresoras');
        $this->impresoras->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'forma_pago');
        $this->forma_pago->initialize($this->dbConnection);

        $this->load->model("puntos_model", 'puntos');
        $this->puntos->initialize($this->dbConnection);

        $this->load->model('primeros_pasos_model');
        $this->load->model("crm_licencia_model", 'crm_licencia_model');

        $this->load->model("orden_compra_model", 'orden_compra');
        $this->orden_compra->initialize($this->dbConnection);

        //actualizacion de almacen consecutivo_cieree_caja
        $this->almacenes->actualizar_tabla_almacen_cierre_caja();

        $this->almacenes->newColumnIva();
        //agrega los precio de ventas del producto en el detalle_orden_compra y en movimiento_detalle
        $this->orden_compra->campos_precios_venta_orden();

        //Creacion tablas producto_adicional, producto_ingredientes, producto_modificacion y secciones_almacen
        $this->productos->creartablas_producto_modificacion_producto_adicional_producto_ingredientes_secciones_almacen();
        $this->ordenes->creartable_orden_producto_restaurant();
        //ACTUALIZAR TABLAS PARA ESTACION PEDIDO // Comensales
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        //si no tengo seleccionado tipo de negocio se colocara retail
        if (empty($data["tipo_negocio"])) {
            //actualizar a retail
            $this->mi_empresa->update_data_empresa(array('tipo_negocio' => 'retail', 'plan_separe' => 'no'));
        }

        //Actualizar valor_caja a si, para que siempre pida abrir la caja
        // $this->mi_empresa->update_data_empresa(array('valor_caja'=>'si'));

        if ((!empty($data_empresa['data']['tipo_negocio'])) && ($data_empresa['data']['tipo_negocio'] == "restaurante")) {
            $this->vendedores->actualizarTablaparaEstacion();
            $this->almacenes->actualizarTablaAlmacenordenRestaurant();
            $this->mesas_secciones->agregarnota(0, 0);
            // CREAR CAMPO COMENSALES
            $this->load->model("ventas_model", 'ventas');
            $this->ventas->initialize($this->dbConnection);
            $this->ventas->add_campo_comensales($this->dbConnection);

            $this->load->model("mesas_secciones_model", 'mesas_secciones');
            $this->mesas_secciones->initialize($this->dbConnection);
            $this->mesas_secciones->add_campo_comensales($this->dbConnection);
            // FIN CREAR CAMPO COMENSALES
            //Insertar campo para permitir formas de pagos pendiente
            $this->mi_empresa->crearOpcion('permitir_formas_pago_pendiente', 'no');
            //crear tabla para formas de pago pendiente
            $this->ventas->crear_ventas_forma_pago_pendiente();

        }
        //crear campo fecha_fin_cierre en el cierre de caja
        $this->Caja->add_campo_fecha_fin_cierre();

        //domicilios
        $this->load->model("domiciliarios_model", 'domiciliarios');
        $this->domiciliarios->initialize($this->dbConnection);
        $this->mi_empresa->crearOpcion('domicilios', 'no');
        //crear tabla de domiciliarios
        $this->domiciliarios->crear_domiciliarios();

        //enviar correo valor del inventario
        $this->mi_empresa->crearOpcion('enviar_valor_inventario', 'no');
        //enviar correo existencias del inventario (stock_historial)
        $this->mi_empresa->crearOpcion('stock_historico', 'no');
        $this->mi_empresa->crearOpcion('puntos_leal', 'no');
        //buscar el usuario administrador
        $userp = $this->usuarios->get_id_user_admin(10, array('db_config_id' => $this->session->userdata('db_config_id'), 'is_admin' => 't'));

        if (!empty($userp)) {
            $this->mi_empresa->crearOpcion('correo_valor_inventario', $userp[0]['email']);
            $this->mi_empresa->crearOpcion('correo_stock_historico', $userp[0]['email']);
        } else {
            $this->mi_empresa->crearOpcion('correo_valor_inventario', '');
            $this->mi_empresa->crearOpcion('correo_stock_historico', '');
        }

        $this->mi_empresa->crearOpcion('usuario_puntos_leal', '');
        $this->mi_empresa->crearOpcion('contraseña_puntos_leal', '');

        //crear opciones de usuario si no estan creadas
        $this->mi_empresa->crearOpcion('precio_almacen', '0');
        $this->mi_empresa->crearOpcion('auto_factura', 'estandar');
        $this->mi_empresa->crearOpcion('auto_pago', 'estandar');
        $this->mi_empresa->crearOpcion('valor_caja', 'si');
        $this->mi_empresa->crearOpcion('cierre_automatico', '0');
        $this->mi_empresa->crearOpcion('enviar_factura', 'no');
        $this->mi_empresa->crearOpcion('numero', 'no');
        $this->mi_empresa->crearOpcion('numero_devolucion', '1');
        $this->mi_empresa->crearOpcion('numero_presupuesto', '1');
        $this->mi_empresa->crearOpcion('prefijo_devolucion', 'NC');
        $this->mi_empresa->crearOpcion('prefijo_presupuesto', 'P');
        $this->mi_empresa->crearOpcion('publicidad_vendty', '1');
        $this->mi_empresa->crearOpcion('resolucion_factura_estado', 'si');
        $this->mi_empresa->crearOpcion('sobrecosto', 'no');
        $this->mi_empresa->crearOpcion('sobrecosto_todos', '1');
        $this->mi_empresa->crearOpcion('zona_horaria', 'America/Bogota');
        $this->mi_empresa->crearOpcion('pais', '1');

        /*arreglar los campos en ventas*/
        // $this->ventas->camposVentasDobles();

        ///////////Combrobar que esta activa la licencia usuarios//////////////

        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $almacen = $almacenActual;

        /************Comprobar que esta activa la licencia usuarios*****************/
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        if ($almacen == 0) {
            $almacen = $this->dashboardModel->getAlmacenActuallicencias();
        }

        $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
        $almacentodos = $this->almacenes->getAll();

        $hoy = date('Y-m-d');

        if (isset($licencia['estado_licencia'])) {
            if ((($licencia['fecha_vencimiento'] < $hoy) || ($licencia['estado_licencia'] == 15)) && ($licencia['id_almacen'] == $almacen) && (($administrador == 's') || ($administrador == 'a') || ($administrador == 'f') || ($administrador != 't'))) {
                //verificar si aun estoy dentro de los 7 dias adicionales
                $fecha_nueva = $licencia['fecha_vencimiento'];
                /*$datetime1 = new DateTime($fecha_nueva);
                $datetime2 = new DateTime('2019-05-01');
                $interval = $datetime1->diff($datetime2);
                $diasn= $interval->format('%a');  */
                $fecha_nueva = date("Y-m-d", strtotime($fecha_nueva . "+ 7 days"));

                if ($fecha_nueva >= '2019-05-02') {
                    $fecha_nueva = '2019-05-01';
                }

                if ($fecha_nueva < $hoy) {
                    //redirigir a login
                    $data['title'] = "SUSCRIPCIÓN VENCIDA";
                    $data['message'] = "Su suscripción está vencida. Si gusta seguir disfutando de Vendty, por favor comuníquese con el administrador del Sistema.";
                    $data['message1'] = "";
                    $data['message2'] = "";
                    $data['message3'] = '3';
                    $data['mostrar_salir'] = true;
                    $data['logout'] = false;
                    $data['config'] = false; //boton de pagar
                    $data['btnconfig'] = false; //muestra boton de configuraciones =true
                    $data['licencias'] = $licencia;
                    $data['almacentodos'] = $almacentodos;
                    $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $data['info_factura_pais'] = $this->clientes->get_pais();
                    $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                    $this->ion_auth->logout();
                    $this->layout->template('login')->show('licenciasvencidas');
                    $html = $this->load->view('licenciasvencidas', $data, true);
                    echo $html;
                    exit;
                }
            }
        }

        $image_crop = false;
    }

    //************************************************************************
    //  EDWIN
    //************************************************************************
    //----------------------------------------------------------------------------------
    //  OFFLINE
    //----------------------------------------------------------------------------------
    public function ttest()
    {

        $tables = $this->dbConnection->list_tables();

        $tablesArray = array();

        foreach ($tables as $table) {

            $fields = $this->dbConnection->field_data($table);

            $sql = "CREATE TABLE IF NOT EXISTS " . $table . " ( ";

            foreach ($fields as $field) {

                $sql = $sql . $field->name . ' ';

                if ($field->type == "varchar" || $field->type == "text") {
                    $sql = $sql . 'TEXT DEFAULT "", ';
                } else if ($field->type == "int" || $field->type == "tinyint" || $field->type == "bigint") {
                    if ($field->primary_key == 1) {
                        $sql = $sql . "INTEGER PRIMARY KEY AUTOINCREMENT, ";
                    } else {
                        $sql = $sql . "INT, ";
                    }

                } else if ($field->type == "float") {
                    $sql = $sql . 'REAL, ';
                } else if ($field->type == "datetime" || $field->type == "time") {
                    $sql = $sql . 'DATETIME, ';
                } else if ($field->type == "date") {
                    $sql = $sql . 'DATE, ';
                } else {
                    $sql = $sql . $field->type . ", ";
                }
            }

            $sql = rtrim($sql, ", ") . " )";

            $tablesArray[] = $sql;
        }

        echo "<pre>";
        print_r($tablesArray);
    }

    public function image() {
        
        //var_dump($this->s3->getBucket('vendty-img'));
        $base_dato = $this->session->userdata('base_dato');
        var_dump($this->s3->getObject('vendty-img','vendty2_db_21105_comi2019/1605369446.jpg'));

    }

    public function getOffline()
    {
        $data = $this->dashboardModel->getOffline();
        // $this->output->set_content_type('application/json')->set_output(json_encode($data));
        echo json_encode($data);
    }

    public function getOfflineExtraData()
    {
        $data = $this->dashboardModel->getOfflineExtraData();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function recuperarManifest()
    {
        $idUsuario = $this->session->userdata('user_id');

        if (file_exists("./uploads/offline/vendty" . $idUsuario . "Tmp.appcache")) {
            rename("./uploads/offline/vendty" . $idUsuario . "Tmp.appcache", "./uploads/offline/vendty" . $idUsuario . ".appcache");
        } else {
            //rename("./uploads/offline/vendtyTmp.appcache", "./uploads/offline/vendty.appcache");
        }
        echo "ok";
    }

    public function borrarOffline()
    {

        $idUsuario = $this->session->userdata('user_id');
        if (file_exists("./uploads/offline/vendty" . $idUsuario . ".appcache")) {
            rename("./uploads/offline/vendty" . $idUsuario . ".appcache", "./uploads/offline/vendty" . $idUsuario . "Tmp.appcache");
        } else {
            //rename("./uploads/offline/vendty.appcache", "./uploads/offline/vendtyTmp.appcache");
        }

        $this->layout->template('ajax')->show('frontend/borrarOffline.php');
    }

    public function updateManifest()
    {

        $idUsuario = $this->session->userdata('user_id');
        $logo = $this->dashboardModel->getLogo();

        //-----------------------------------------
        //  Copia del manifesto y el loaderOffline
        //-----------------------------------------

        $baseManifest = './uploads/offline/vendtyBase.appcache';
        $newManifest = "./uploads/offline/vendty$idUsuario.appcache";

        $baseLoader = './uploads/offline/offlineLoader.html';
        $newLoader = "./uploads/offline/offlineLoaderV2_$idUsuario.html";

        // sudo cp LocalSettings.php /var/www
        if (!copy($baseManifest, $newManifest)) {
            echo "failed to copy $baseManifest...\n";
            exit();
        }

        if (!copy($baseLoader, $newLoader)) {
            echo "failed to copy $baseLoader...\n";
            exit();
        }

        //------------------------------------------
        //  Manifesto
        //------------------------------------------

        $existeLogo = " ./uploads/$logo";

        $version = "# version: " . date("o/m/d - H:i:s");
        $strLogo = " ../uploads/$logo";

        $file = $newManifest;

        if (file_exists($existeLogo)) {
            $new_lines = array(2 => $version, 5 => $strLogo);
        } else {
            $new_lines = array(2 => $version);
        }

        $source_file = null;

        $response = 0;
        //characters
        $tab = chr(9);
        $lbreak = chr(13) . chr(10);
        //get lines into an array
        if ($source_file) {
            $lines = file($source_file);
        } else {
            $lines = file($file);
        }
        //change the lines (array starts from 0 - so minus 1 to get correct line)
        foreach ($new_lines as $key => $value) {
            $lines[--$key] = $value . $lbreak;
        }
        //implode the array into one string and write into that file
        $new_content = implode('', $lines);

        if ($h = fopen($file, 'w')) {
            if (fwrite($h, $new_content)) {
                $response = 1;
            }
            fclose($h);
        }

        //-------------------------------------
        // Fin Manifesto
        //-------------------------------------
        //------------------------------------------
        //  offlineLoader
        //------------------------------------------

        $manifestName = '<html  manifest="vendty' . $idUsuario . '.appcache" type="text/cache-manifest">';

        $file = $newLoader;
        $new_lines = array(2 => $manifestName);
        $source_file = null;

        $response = 0;
        //characters
        $tab = chr(9);
        $lbreak = chr(13) . chr(10);
        //get lines into an array
        if ($source_file) {
            $lines = file($source_file);
        } else {
            $lines = file($file);
        }
        //change the lines (array starts from 0 - so minus 1 to get correct line)
        foreach ($new_lines as $key => $value) {
            $lines[--$key] = $value . $lbreak;
        }
        //implode the array into one string and write into that file
        $new_content = implode('', $lines);

        if ($h = fopen($file, 'w')) {
            if (fwrite($h, $new_content)) {
                $response = 1;
            }
            fclose($h);
        }

        //-------------------------------------
        // offlineLoader
        //-------------------------------------

        echo "Manifest Uploaded...";
    }

    public function accept_terms_conditions()
    {
        echo $this->dashboardModel->accept_terms_conditions();
    }

    //----------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------

    public function index($estado = "")
    {

        $term = $this->dashboardModel->get_terms_conditions();
        $this->session->set_userdata('terms_condition', $term);
        valide_option('table_selected', '4');
        $this->puntos->update_table_puntos();
        $this->productos->check_ventas_negativo();
        $this->categorias->updateColumnCategoria();
        $this->ventas->addColumn_porcentaje_descuento();
        $this->categorias->updateColumnMenuTienda();
        $this->productos->columna_tipo_producto();
        //Actualizacion segun incidencia #813
        $this->proformas->actualizar_proforma_gastos();
        $this->almacenes->actualizarTablaAlmacen();
        $this->almacenes->actualizarTablaAlmacenBodega();

        $this->productos->check_tabla_seriales();
        $this->productos->validate_tipo_producto_imei();

        if (getUiVersion() == "v2") {
            $this->indexV2($estado = "");
        } else {
            $this->indexV1($estado = "");
        }

    }

    public function loginTienda($estado = "")
    {

        $term = $this->dashboardModel->get_terms_conditions();
        $this->session->set_userdata('terms_condition', $term);
        valide_option('table_selected', '4');
        $this->puntos->update_table_puntos();
        $this->productos->check_ventas_negativo();
        $this->categorias->updateColumnCategoria();
        $this->ventas->addColumn_porcentaje_descuento();
        $this->categorias->updateColumnMenuTienda();
        $this->productos->columna_tipo_producto();
        //Actualizacion segun incidencia #813
        $this->proformas->actualizar_proforma_gastos();
        $this->almacenes->actualizarTablaAlmacen();
        $this->almacenes->actualizarTablaAlmacenBodega();

        $this->productos->check_tabla_seriales();
        $this->productos->validate_tipo_producto_imei();

        $this->load->view('frontend/loginTienda.php');

    }

    public function cargar_tipo_negocio($estado = "")
    {
        $tipo = $this->input->post("type");
        /**crear carpeta para imagenes**/
        $base_dato = $this->session->userdata('base_dato');
        //basededatos
        $carpeta = './uploads/' . $base_dato;
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        //categorias_productos
        $carpeta = './uploads/' . $base_dato . '/categorias_productos';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        //imagenes_productos
        $carpeta = './uploads/' . $base_dato . '/imagenes_productos';

        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        echo $this->dashboardModel->load_type_businness($tipo);
    }

    public function indexb($estado = "")
    {
        if (getUiVersion() == "v2") {
            $this->indexV2($estado = "");
        } else {
            $this->indexV1($estado = "");
        }

    }

    public function setUi($version)
    {
        $this->dashboardModel->setUi($version);
    }

    // restarFechas("fecha vieja", "fecha nueva");
    // "año-mes-dia"
    // restarFechas("2006-04-05", "2006-04-01");
    public function restarFechas($start, $end)
    {

        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        return round($diff / 86400);
    }

    public function pagarPrueba()
    {
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        if (($administrador) || ($administrador == 'a')) {
            $fecha = date('Y-m-d');
            $from = " v_crm_licencias V JOIN crm_licencias_empresa L ON L.idlicencias_empresa=V.id_licencia ";
            $where = " V.id_db_config=$db_config_id_user AND (V.fecha_vencimiento >= '$fecha') AND L.planes_id=1 ";
            $licenciast = $this->licenciasModel->licenciaPrueba($from, $where);

        }

        $data['sw_prueba'] = 1;
        $data['mostrar_salir'] = false;
        $data['title'] = "SUSCRIPCI&Oacute;N GRATUITA PR&Oacute;XIMA A VENCER";
        //$data['message'] = "Te recuerdo que nuestra promoción del 50% del plan Pyme finalizará el día 13 de Abril del 2019.";
        $data['message'] = "";
        //$data['message1'] = " Si deseas adquirir la promoción entonces comunícate con nosotros a los teléfono +(57)318 8018675 - +(57)317 5108254 o";
        $data['message1'] = "";
        $data['message2'] = 'En la parte inferior se encuentran los planes disponibles.';
        //$data['message2'] = 'escríbenos un correo a <a href="mailto:asesor@vendty.com">asesor@vendty.com</a> <br>¡Quedan pocos días de Promoción!';
        $data['message3'] = '1';
        $data['logout'] = true;
        $data['licencias'] = $licenciast;
        $data['almacentodos'] = $this->almacenes->getAll();
        $data['planes'] = $this->licencias->get_planes();
        $planesid = " where id_plan in(";
        $id = "";

        foreach ($data['planes'] as $plan) {
            $id .= "," . $plan["id"];
        }

        $planesid .= trim($id, ",") . ")";
        $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

        foreach ($data['planes'] as $key => $plan) {
            foreach ($data['detalles_planes'] as $detalle) {

                if (($detalle->id_plan == $plan["id"])) {
                    $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
                }

            }
        }

        $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura_pais'] = $this->clientes->get_pais();
        $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        $data['config'] = true;
        //$this->ion_auth->logout();
        $this->layout->template('login')->show('licenciasvencidas');
        $html = $this->load->view('licenciasvencidas', $data, true);
        echo $html;
        exit;
    }

    public function pagarPrueba2()
    {
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        if (($administrador) || ($administrador == 'a')) {
            $fecha = date('Y-m-d');
            $from = " v_crm_licencias V JOIN crm_licencias_empresa L ON L.idlicencias_empresa=V.id_licencia ";
            $where = " V.id_db_config=$db_config_id_user AND (V.fecha_vencimiento >= '$fecha') AND L.planes_id=1 ";
            $licenciast = $this->licenciasModel->licenciaPrueba($from, $where);

        }

        $data['sw_prueba'] = 1;
        $data['mostrar_salir'] = false;
        $data['title'] = "SUSCRIPCI&Oacute;N GRATUITA PR&Oacute;XIMA A VENCER PayU";
        //$data['message'] = "Te recuerdo que nuestra promoción del 50% del plan Pyme finalizará el día 13 de Abril del 2019.";
        $data['message'] = "";
        //$data['message1'] = " Si deseas adquirir la promoción entonces comunícate con nosotros a los teléfono +(57)318 8018675 - +(57)317 5108254 o";
        $data['message1'] = "";
        $data['message2'] = 'En la parte inferior se encuentran los planes disponibles.';
        //$data['message2'] = 'escríbenos un correo a <a href="mailto:asesor@vendty.com">asesor@vendty.com</a> <br>¡Quedan pocos días de Promoción!';
        $data['message3'] = '1';
        $data['logout'] = true;
        $data['licencias'] = $licenciast;
        $data['almacentodos'] = $this->almacenes->getAll();
        $data['planes'] = $this->licencias->get_planes();
        $planesid = " where id_plan in(";
        $id = "";

        foreach ($data['planes'] as $plan) {
            $id .= "," . $plan["id"];
        }

        $planesid .= trim($id, ",") . ")";
        $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

        foreach ($data['planes'] as $key => $plan) {
            foreach ($data['detalles_planes'] as $detalle) {

                if (($detalle->id_plan == $plan["id"])) {
                    $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
                }

            }
        }

        $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura_pais'] = $this->clientes->get_pais();
        $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        $data['config'] = true;

        $this->layout->template('login')->show('frontend/nuevoPagar');
        $html = $this->load->view('frontend/nuevoPagar', $data, true);

        echo $html;
        exit;
    }

    public function pagarVencida()
    {
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        /*
        if(($administrador=='t')){
        $fecha=date('Y-m-d');
        $almacenActual = $this->dashboardModel->getAlmacenActual();

        if($almacenActual==0){
        $almacenActual=$this->dashboardModel->getAlmacenActuallicencias();
        }
        //tengo mas de una licencia?
        //busco todas las licencias que tengo
        $licenciastu = $this->licenciasModel->by_id_config_estado_licencias( "where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) ",false);

        //busco todas mis licencias asociadas vencidas que no esten desactivadas
        $licencias = $this->licenciasModel->by_id_config_estado_licencias( "where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) AND (estado_licencia=15 OR fecha_vencimiento < '$fecha') and desactivada !=1  ",false);

        $licenciasdesactivadas = $this->licenciasModel->by_id_config_estado_licencias( "where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) AND (estado_licencia=15 OR fecha_vencimiento < '$fecha') and desactivada=1",false);

        $cantlicenciastodas=count($licenciastu);
        $cantlicenciasdesactivadas=count($licenciasdesactivadas);
        $cantlicenciasvencidasnodesactivadas=count($licencias);

        }   */

        if (($administrador == 't')) {
            $fecha = date('Y-m-d');
            $diacuentasnuevas = $this->crm_model->getdiascuentaprueba();
            $diacuentasnuevas = $diacuentasnuevas[0]['dias'];
            $nuevafecha = strtotime('+' . $diacuentasnuevas . ' day', strtotime($fecha));
            $nuevafecha = date('Y-m-j', $nuevafecha);
            $from = " v_crm_licencias V JOIN crm_licencias_empresa L ON L.idlicencias_empresa=V.id_licencia ";
            $where = " V.id_db_config=$db_config_id_user AND (V.fecha_vencimiento <= '$nuevafecha') AND L.planes_id not in (1,15,16,17) ";
            $licenciast = $this->licenciasModel->licenciaPrueba($from, $where);

        }

        $data['title'] = "SUSCRIPCIONES PRÓXIMA A VENCER";
        $data['message'] = "Su suscripción vencerá en los próximos días, si desea evitar el corte genere su pago.";
        $data['message1'] = " ";
        $data['message2'] = '';
        $data['message3'] = '1';
        $data['logout'] = true;
        $data['mostrar_salir'] = false;
        $data['licencias'] = $licenciast;
        $data['almacentodos'] = $this->almacenes->getAll();
        $data['config'] = true;
        $data['btnconfig'] = true;
        $data['todas_vencidas'] = false;
        $data['planes'] = $this->licencias->get_planes();
        //print_r($data['planes']); die();
        $planesid = " where id_plan in(";
        $id = "";

        foreach ($data['planes'] as $plan) {
            $id .= "," . $plan["id"];
        }

        $planesid .= trim($id, ",") . ")";
        $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

        foreach ($data['planes'] as $key => $plan) {
            foreach ($data['detalles_planes'] as $detalle) {

                if (($detalle->id_plan == $plan["id"])) {
                    $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
                }

            }
        }

        $almacenes_vencidos = array();

        foreach ($licenciast as $licencia_vencida) {
            array_push($almacenes_vencidos, $licencia_vencida["id_almacen"]);
        }

        $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura_pais'] = $this->clientes->get_pais();
        $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        //$this->ion_auth->logout();
        $this->layout->template('login')->show('licenciasvencidas');
        $html = $this->load->view('licenciasvencidas', $data, true);
        echo $html;
        exit;
    }

    public function inicio()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $getAlm = $this->input->get('alm');
        $getDia = $this->input->get('dia');

        $almacenActual = $this->dashboardModel->getAlmacenActual();

        $almacen = $getAlm == "" ? $almacenActual : $getAlm;
        $dias = $getDia == "" ? 7 : $getDia;

        /************Combrobar que esta activa la licencia usuarios admin*****************/
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        if (($administrador) || ($administrador == 'a')) {
            $fecha = date('Y-m-d');
            //$licencia = $this->licenciasModel->by_id_config_estado_licen($db_config_id_user,true);
            //$licenciast = $this->licenciasModel->by_id_config_estado_licen($db_config_id_user,false);
            $licencia = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND (estado_licencia=15 OR fecha_vencimiento< '$fecha')", true);
            $licenciast = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND (estado_licencia=15 OR fecha_vencimiento< '$fecha')", false);
            $licenciastu = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user", false);

        }
        if (isset($licencia['estado_licencia'])) {
            if ((($licencia['fecha_vencimiento'] < $fecha) || ($licencia['estado_licencia'] == 15)) && (($administrador == 't') || ($administrador == 'a'))) {
                //redirigir a la vista de licencias para pagar
                $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
                $estado = $cuentaEstado["estado"];
                if ($estado == 2) {
                    $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
                    $data['title'] = "¡TU PRUEBA HA FINALIZADO!";
                    $data['message'] = "Su licencia está vencida. Esperamos que haya sido de su agrado la prueba. Si gusta seguir disfutando de VendTy, debe ";
                    $data['message1'] = "actualizar ";
                    $data['message2'] = 'a un plan de pago.';
                    $data['message3'] = '2';
                    $data['logout'] = true;
                    $data['licencias'] = $licencia;
                    $data['almacentodos'] = $this->almacenes->getAll();
                    $data['planes'] = $this->licencias->get_planes();
                    $data['config'] = true;
                } else {
                    $data['title'] = "SUSCRIPCIÓN VENCIDA!";
                    $data['message'] = "Su suscripción está vencida. Si gusta seguir disfutando de VendTy, debe ";
                    $data['message1'] = "realizar su pago. ";
                    $data['message2'] = ' ';
                    $data['message3'] = '3';
                    $data['logout'] = true;
                    $data['licencias'] = $licenciast;
                    $data['almacentodos'] = $this->almacenes->getAll();
                    $data['planes'] = $this->licencias->get_planes();
                    $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $data['info_factura_pais'] = $this->clientes->get_pais();
                    $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                    // $data['config'] = false;
                    //  print_r($licenciastu);
                    if (count($licenciastu) >= 1) {
                        $data['config'] = true;
                    }
                }
                $this->layout->template('login')->show('licenciasvencidas');
                $html = $this->load->view('licenciasvencidas', $data, true);
                echo $html;
                exit;
                /*$this->layout->template('login')->show('licenciasvencidas');
            $html = $this->load->view('licenciasvencidas',$data,true);
            echo $html;
            exit;*/
            }
        }

        //------------------

        $data = array();
        $data['dias_licencia'] = null;
        $data['fecha_vencimiento'] = null;
        $data['valor_renovacion'] = null;
        $data['almacen'] = $almacen;
        $data['dias'] = $dias;
        $recordarplan = 7;

        $data['usuario'] = $this->session->userdata('username');
        $data['almacenes'] = $this->dashboardModel->getAllAlmacenes();
        $data['empresa'] = $this->dashboardModel->get_data_empresa();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['data_empresa'] = $data_empresa;
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $data['meta_diaria'] = $this->dashboardModel->get_meta_diaria($almacen, 0);
        $datos_ventas = $this->dashboardModel->getVentas($almacen, 0, 0);
        $data['meta_diaria']["total_ventas"] = $datos_ventas[0]["total_venta"];
        $data['meta_diaria']['total_facturas'] = $datos_ventas[0]["cantidad"];
        $data['productos_relevantes_hoy'] = $this->dashboardModel->getProductosRelevantesHoy($almacen);
        $data['ventas'] = $ventas = $this->dashboardModel->getVentas($almacen, $dias, 1);
        $data['utilidad'] = $utilidad = $this->dashboardModel->getUtilidad($almacen, $dias);

        $data['ventas_almacen'] = $this->dashboardModel->ventasPorAlmacen($almacen, $dias);
        $data['categorias_vendidas'] = $this->dashboardModel->categoriasMasVendidas($almacen, $dias);
        // var_dump($data['categorias_vendidas']);die();
        $total_gastos = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha' => date("Y-m-d")), $almacen);

        foreach ($proformas as $key => $value) {
            $total_gastos += $value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago' => date("Y-m-d")));

        foreach ($pagos_compras as $key => $value) {
            $total_gastos += $value->cantidad;
        }

        $data['total_gastos'] = $total_gastos;
        $total_gastos_ayer = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha' => date("Y-m-d", strtotime("-1 day"))), $almacen);

        foreach ($proformas as $key => $value) {
            $total_gastos_ayer += $value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago' => date("Y-m-d", strtotime("-1 day"))));

        foreach ($pagos_compras as $key => $value) {
            $total_gastos_ayer += $value->cantidad;
        }

        $data['total_gastos_ayer'] = $total_gastos_ayer;
        $data['ventas_por_hora'] = $this->dashboardModel->get_ventas_hora(date("Y-m-d"));
        $prod_populares = $this->dashboardModel->productosMasPopulares($almacen, $dias);
        $stock_minimo = $this->dashboardModel->stockMinimo($almacen);

        //-----------------------------------------------------
        //   Acortando nombre con atributos muy largos
        //-----------------------------------------------------
        // PRODUCTOS POUPULARES
        foreach ($prod_populares as $key => $val) {
            $tmpArray = explode("/", $val["nombre"]);
            if (count($tmpArray) > 2) {
                $prod_populares[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
            }
        }

        // STOCK_MINIMO
        foreach ($stock_minimo as $key => $val) {
            $tmpArray = explode("/", $val["nombre"]);
            if (count($tmpArray) > 2) {
                $stock_minimo[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
            }
        }

        $data['prod_populares'] = $prod_populares;
        $data['stock_minimo'] = $stock_minimo;

        $idUsuario = $this->session->userdata('user_id');
        $data['zoho'] = $this->dashboardModel->getZoho($idUsuario);
        $data['estadoZoho'] = "no";

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();

        $estado = $cuentaEstado["estado"];
        $fecha = $cuentaEstado["fecha"];

        //SI ES TOTALMENTE NUEVO EL USUARIO, SE REDIRECCIONAREMOS A ZOHO Y A GRACIAS
        if ($estado == "4") {

            $zohoData = $this->session->userdata('nuevoCliente');
            $this->layout->template('ajax')->show('frontend/zoho.php', array('data' => $zohoData));

            // Si esta en estado de configuracion
            $this->wizard();

        } else {

            $data['estado'] = $estado;

            //RESTA DE FECHAS PARA VALIDAR QUE YA SE LE ACABO EL TIEMPO AL USUARIO
            date_default_timezone_set("America/Lima");
            $data['diasCuenta'] = $this->restarFechas($fecha, date('Y-m-d'));
            $data['diasCuentaDisponibles'] = 7 - $data['diasCuenta'];

            if ($data['diasCuentaDisponibles'] <= 0 && $estado == "2" || $data['diasCuentaDisponibles'] <= 0 && $estado == "3") {
                // show_error('404 Page Not Found','Fin periodo prueba','mensaje_cuenta_desactivada',404);
                // $this->layout->template('login')->show('mensaje_cuenta_desactivada');
                // $this->ion_auth->logout();
                // $html = $this->load->view('mensaje_cuenta_desactivada',null,true);
                //  echo $html;
                //  exit;
                $db_config_id_user = $this->session->userdata('db_config_id');
                //echo"<br><br>".print_r($this->session->userdata); die();
                if ($almacen == 0) {
                    $almacen = $this->dashboardModel->getAlmacenActuallicencias();
                }

                $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
                $data['title'] = "¡TU PRUEBA HA FINALIZADO!";
                $data['message'] = "Su suscripción está vencida. Esperamos que haya sido de su agrado la prueba. Si gusta seguir disfutando de VendTy, debe ";
                $data['message1'] = "actualizar ";
                $data['message2'] = 'a un plan de pago.';
                $data['message3'] = '3';
                $data['logout'] = true;
                $data['licencias'] = $licencia;
                $data['almacentodos'] = $this->almacenes->getAll();
                $data['planes'] = $this->licencias->get_planes();
                $data['config'] = true;
                $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                $data['info_factura_pais'] = $this->clientes->get_pais();
                $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                $this->layout->template('login')->show('licenciasvencidas');
                $html = $this->load->view('licenciasvencidas', $data, true);
                echo $html;
                exit;
            }

            $data['offline'] = $this->input->get("offline");

            // Si no ha completado la configuracion se envia a Wizard
            if ($estado == 3) {
                $this->wizard();
            } else {
                // Carga normal de la pagina
                //consultamos si esta en la lista de por vencerse
                $administrador = $this->session->userdata('is_admin');

                if (($administrador == 't') || ($administrador == 'a')) {
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_plan !=' => '1'));
                } else {
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_almacen' => $almacen));
                }
                //  $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('email'=>$this->session->userdata('email')));
                if (count($datos_vencimiento) > 0) {
                    $datos_plan = $this->crm_model->get_planes(array('id' => $datos_vencimiento[0]->id_plan));
                    $recordarplan = $datos_plan[0]->comienzo_dias_recordacion;
                    $data['nombre_plan'] = $datos_plan[0]->nombre_plan;
                    $data['fecha_extendida'] = $datos_plan[0]->nombre_plan;
                    $hoy = date_create(date("y-m-d"));
                    $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                    $dias = date_diff($hoy, $fecha_vencimiento);

                    //$data['dias_licencia'] = $dias->format("%R%a");
                    $data['dias_licencia'] = $dias->format("%a");
                    $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                    $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                } else {
                    $data['dias_licencia'] = null;
                    $data['fecha_vencimiento'] = null;
                    $data['valor_renovacion'] = null;
                }
                //var_dump($datos_vencimiento);die();
                if ($datos_vencimiento) {
                    $hoy = date_create(date("y-m-d"));
                    $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                    $dias = date_diff($hoy, $fecha_vencimiento);
                    if ($dias->format("%a") <= 30) {
                        //$data['dias_licencia'] = $dias->format("%R%a");
                        $data['dias_licencia'] = $dias->format("%a");
                        $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                        $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                    }
                } else {
                    $data['dias_licencia'] = null;
                    $data['fecha_vencimiento'] = null;
                    $data['valor_renovacion'] = null;
                }

                ////verifico los dias para mostrar alerta de vencimiento
                //if($data['dias_licencia']>$data['dias']){

                $recordarplan = $recordarplan == "" ? 7 : $recordarplan;
                if ($recordarplan < $data['dias_licencia']) {
                    $data['dias_licencia'] = null;
                }

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
                if ($data["tipo_negocio"] == "restaurante") {
                    $almacenActual = $this->dashboardModel->getAlmacenActual();
                    $data["zonas"] = $this->secciones_almacen->get_secciones_almacen('b.nombre != "" AND id_almacen=' . $almacenActual);
                    $data["mesas_secciones"] = $this->secciones_almacen->get_mesas_secciones();

                    //$data["orden_barra"] = $this->ordenes->getLatestOrdenByBarra();
                    $pedidos = 0;
                    foreach ($data["mesas_secciones"] as $key) {
                        $pedidos = $this->ordenes->verificaEstado($key->id_seccion, $key->id);
                        $key->pedidos = $pedidos;
                    }

                }

                $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();
                /*$convertloop = new \ConvertLoop\ConvertLoop("73a39be3", "pkUU21crGXeEfVpDKZsTkVGJ", "v1");
                $person = array(
                "email" => $this->session->userdata('email'),
                "first_name" => $this->session->userdata('username')
                );
                $convertloop->people()->createOrUpdate($person);
                $event = array(
                "name" => 'iniciorapido',
                "person" => $person,
                "ocurred_at" => time()
                );
                $convertloop->eventLogs()->send($event);*/
                $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));
                $this->layout->template('dashboard')->show('frontend/inicio.php', array('data' => $data));
            }
        }

    }

    public function recomendar()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();
        /*$convertloop = new \ConvertLoop\ConvertLoop("73a39be3", "pkUU21crGXeEfVpDKZsTkVGJ", "v1");
        $person = array(
        "email" => $this->session->userdata('email'),
        "first_name" => $this->session->userdata('username')
        );
        $convertloop->people()->createOrUpdate($person);
        $event = array(
        "name" => 'recomendar',
        "person" => $person,
        "ocurred_at" => time()
        );
        $convertloop->eventLogs()->send($event);*/

        $getAlm = $this->input->get('alm');
        $getDia = $this->input->get('dia');

        $almacenActual = $this->dashboardModel->getAlmacenActual();

        $almacen = $getAlm == "" ? $almacenActual : $getAlm;
        $dias = $getDia == "" ? 7 : $getDia;

        /************Combrobar que esta activa la licencia usuarios admin*****************/
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        if (($administrador) || ($administrador == 'a')) {
            $fecha = date('Y-m-d');
            //$licencia = $this->licenciasModel->by_id_config_estado_licen($db_config_id_user,true);
            //$licenciast = $this->licenciasModel->by_id_config_estado_licen($db_config_id_user,false);
            $licencia = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND (estado_licencia=15 OR fecha_vencimiento< '$fecha')", true);
            $licenciast = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND (estado_licencia=15 OR fecha_vencimiento< '$fecha')", false);
            $licenciastu = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user", false);

        }
        if (isset($licencia['estado_licencia'])) {
            if ((($licencia['fecha_vencimiento'] < $fecha) || ($licencia['estado_licencia'] == 15)) && (($administrador == 't') || ($administrador == 'a'))) {
                //redirigir a la vista de licencias para pagar
                $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
                $estado = $cuentaEstado["estado"];
                if ($estado == 2) {
                    $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
                    $data['title'] = "¡TU PRUEBA HA FINALIZADO!";
                    $data['message'] = "Su suscripción está vencida. Esperamos que haya sido de su agrado la prueba. Si gusta seguir disfutando de VendTy, debe ";
                    $data['message1'] = "actualizar ";
                    $data['message2'] = 'a un plan de pago.';
                    $data['message3'] = '2';
                    $data['logout'] = true;
                    $data['licencias'] = $licencia;
                    $data['almacentodos'] = $this->almacenes->getAll();
                    $data['planes'] = $this->licencias->get_planes();
                    $data['config'] = true;
                } else {
                    $data['title'] = "SUSCRIPCIÓN VENCIDA";
                    $data['message'] = "Su suscripción está vencida. Si gusta seguir disfutando de VendTy, debe ";
                    $data['message1'] = "realizar su pago. ";
                    $data['message2'] = ' ';
                    $data['message3'] = '3';
                    $data['logout'] = true;
                    $data['licencias'] = $licenciast;
                    $data['almacentodos'] = $this->almacenes->getAll();
                    $data['planes'] = $this->licencias->get_planes();
                    $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $data['info_factura_pais'] = $this->clientes->get_pais();
                    $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

                    if (count($licenciastu) >= 1) {
                        $data['config'] = true;
                    }
                }

                $this->layout->template('login')->show('licenciasvencidas');
                $html = $this->load->view('licenciasvencidas', $data, true);
                echo $html;
                exit;
            }
        }

        //------------------
        $data = array();
        $data['dias_licencia'] = null;
        $data['fecha_vencimiento'] = null;
        $data['valor_renovacion'] = null;
        $data['almacen'] = $almacen;
        $data['dias'] = $dias;
        $recordarplan = 7;
        $data['usuario'] = $this->session->userdata('username');
        $data['almacenes'] = $this->dashboardModel->getAllAlmacenes();
        $data['empresa'] = $this->dashboardModel->get_data_empresa();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['data_empresa'] = $data_empresa;
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        $data['meta_diaria'] = $this->dashboardModel->get_meta_diaria($almacen, 0);
        $datos_ventas = $this->dashboardModel->getVentas($almacen, 0, 0);
        $data['meta_diaria']["total_ventas"] = $datos_ventas[0]["total_venta"];
        $data['meta_diaria']['total_facturas'] = $datos_ventas[0]["cantidad"];
        $data['productos_relevantes_hoy'] = $this->dashboardModel->getProductosRelevantesHoy($almacen);
        $data['ventas'] = $ventas = $this->dashboardModel->getVentas($almacen, $dias, 1);
        $data['utilidad'] = $utilidad = $this->dashboardModel->getUtilidad($almacen, $dias);

        $data['ventas_almacen'] = $this->dashboardModel->ventasPorAlmacen($almacen, $dias);
        $data['categorias_vendidas'] = $this->dashboardModel->categoriasMasVendidas($almacen, $dias);
        // var_dump($data['categorias_vendidas']);die();
        $total_gastos = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha' => date("Y-m-d")), $almacen);

        foreach ($proformas as $key => $value) {
            $total_gastos += $value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago' => date("Y-m-d")));

        foreach ($pagos_compras as $key => $value) {
            $total_gastos += $value->cantidad;
        }

        $data['total_gastos'] = $total_gastos;
        $total_gastos_ayer = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha' => date("Y-m-d", strtotime("-1 day"))), $almacen);

        foreach ($proformas as $key => $value) {
            $total_gastos_ayer += $value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago' => date("Y-m-d", strtotime("-1 day"))));

        foreach ($pagos_compras as $key => $value) {
            $total_gastos_ayer += $value->cantidad;
        }

        $data['total_gastos_ayer'] = $total_gastos_ayer;
        $data['ventas_por_hora'] = $this->dashboardModel->get_ventas_hora(date("Y-m-d"));
        $prod_populares = $this->dashboardModel->productosMasPopulares($almacen, $dias);
        $stock_minimo = $this->dashboardModel->stockMinimo($almacen);

        //-----------------------------------------------------
        //   Acortando nombre con atributos muy largos
        //-----------------------------------------------------
        // PRODUCTOS POUPULARES
        foreach ($prod_populares as $key => $val) {
            $tmpArray = explode("/", $val["nombre"]);
            if (count($tmpArray) > 2) {
                $prod_populares[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
            }
        }

        // STOCK_MINIMO
        foreach ($stock_minimo as $key => $val) {
            $tmpArray = explode("/", $val["nombre"]);
            if (count($tmpArray) > 2) {
                $stock_minimo[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
            }
        }

        $data['prod_populares'] = $prod_populares;
        $data['stock_minimo'] = $stock_minimo;
        $idUsuario = $this->session->userdata('user_id');
        $data['zoho'] = $this->dashboardModel->getZoho($idUsuario);
        $data['estadoZoho'] = "no";
        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $estado = $cuentaEstado["estado"];
        $fecha = $cuentaEstado["fecha"];

        //SI ES TOTALMENTE NUEVO EL USUARIO, SE REDIRECCIONAREMOS A ZOHO Y A GRACIAS
        if ($estado == "4") {
            $zohoData = $this->session->userdata('nuevoCliente');
            $this->layout->template('ajax')->show('frontend/zoho.php', array('data' => $zohoData));

            // Si esta en estado de configuracion
            $this->wizard();
        } else {
            $data['estado'] = $estado;

            //RESTA DE FECHAS PARA VALIDAR QUE YA SE LE ACABO EL TIEMPO AL USUARIO
            date_default_timezone_set("America/Lima");
            $data['diasCuenta'] = $this->restarFechas($fecha, date('Y-m-d'));
            $data['diasCuentaDisponibles'] = 7 - $data['diasCuenta'];

            if ($data['diasCuentaDisponibles'] <= 0 && $estado == "2" || $data['diasCuentaDisponibles'] <= 0 && $estado == "3") {
                $db_config_id_user = $this->session->userdata('db_config_id');

                if ($almacen == 0) {
                    $almacen = $this->dashboardModel->getAlmacenActuallicencias();
                }

                $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
                $data['title'] = "¡TU PRUEBA HA FINALIZADO!";
                $data['message'] = "Su suscripción está vencida. Esperamos que haya sido de su agrado la prueba. Si gusta seguir disfutando de VendTy, debe ";
                $data['message1'] = "actualizar ";
                $data['message2'] = 'a un plan de pago.';
                $data['message3'] = '3';
                $data['logout'] = true;
                $data['licencias'] = $licencia;
                $data['almacentodos'] = $this->almacenes->getAll();
                $data['planes'] = $this->licencias->get_planes();
                $data['config'] = true;
                $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                $data['info_factura_pais'] = $this->clientes->get_pais();
                $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                $this->layout->template('login')->show('licenciasvencidas');
                $html = $this->load->view('licenciasvencidas', $data, true);
                echo $html;
                exit;
            }

            $data['offline'] = $this->input->get("offline");

            // Si no ha completado la configuracion se envia a Wizard
            if ($estado == 3) {
                $this->wizard();
            } else {
                // Carga normal de la pagina
                //consultamos si esta en la lista de por vencerse
                $administrador = $this->session->userdata('is_admin');

                if (($administrador == 't') || ($administrador == 'a')) {
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_plan !=' => '1'));
                } else {
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_almacen' => $almacen));
                }

                if (count($datos_vencimiento) > 0) {
                    $datos_plan = $this->crm_model->get_planes(array('id' => $datos_vencimiento[0]->id_plan));
                    $recordarplan = $datos_plan[0]->comienzo_dias_recordacion;
                    $data['nombre_plan'] = $datos_plan[0]->nombre_plan;
                    $hoy = date_create(date("y-m-d"));
                    $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                    $dias = date_diff($hoy, $fecha_vencimiento);
                    $data['dias_licencia'] = $dias->format("%a");
                    $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                    $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                } else {
                    $data['dias_licencia'] = null;
                    $data['fecha_vencimiento'] = null;
                    $data['valor_renovacion'] = null;
                }

                if ($datos_vencimiento) {
                    $hoy = date_create(date("y-m-d"));
                    $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                    $dias = date_diff($hoy, $fecha_vencimiento);
                    if ($dias->format("%a") <= 30) {
                        //$data['dias_licencia'] = $dias->format("%R%a");
                        $data['dias_licencia'] = $dias->format("%a");
                        $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                        $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                    }
                } else {
                    $data['dias_licencia'] = null;
                    $data['fecha_vencimiento'] = null;
                    $data['valor_renovacion'] = null;
                }

                ////verifico los dias para mostrar alerta de vencimiento
                $recordarplan = $recordarplan == "" ? 7 : $recordarplan;

                if ($recordarplan < $data['dias_licencia']) {
                    $data['dias_licencia'] = null;
                }

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

                if ($data["tipo_negocio"] == "restaurante") {
                    $almacenActual = $this->dashboardModel->getAlmacenActual();
                    $data["zonas"] = $this->secciones_almacen->get_secciones_almacen('b.nombre != "" AND id_almacen=' . $almacenActual);
                    $data["mesas_secciones"] = $this->secciones_almacen->get_mesas_secciones();

                    //$data["orden_barra"] = $this->ordenes->getLatestOrdenByBarra();
                    $pedidos = 0;
                    foreach ($data["mesas_secciones"] as $key) {
                        $pedidos = $this->ordenes->verificaEstado($key->id_seccion, $key->id);
                        $key->pedidos = $pedidos;
                    }
                }

                $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();

                $this->layout->template('dashboard')->show('frontend/recomendar.php', array('data' => $data));
            }
        }

    }

    public function enviar_recomendacion()
    {
        $email = $this->input->post('correo_recomendacion');
        $html = $this->load->view("frontend/recomendacionEmail", array(
            "email" => $email,
            "asunto" => $this->input->post('asunto_recomendacion'),
            "mensaje" => $this->input->post('mensaje_recomendacion'),
        ), true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($email);
        $this->email->bcc('roxanna@vendty.com');
        $this->email->subject("VendTy Tu Punto de Venta en la nube");
        $this->email->message($html);
        $this->email->send();

        redirect(site_url());
    }

    public function indexV2($estado = "")
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $getAlm = $this->input->get('alm');
        $getDia = $this->input->get('dia');

        $almacenActual = $this->dashboardModel->getAlmacenActual();

        $almacen = $getAlm == "" ? $almacenActual : $getAlm;
        $diacuentasnuevas = $this->crm_model->getdiascuentaprueba();
        $diacuentasnuevas = $diacuentasnuevas[0]['dias'];
        $dias = $getDia == "" ? $diacuentasnuevas : $getDia;

        /************Comprobar que esta activa la licencia usuarios admin*****************/
        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');
        $cantlicenciastodas = 0;
        $cantlicenciasdesactivadas = 0;
        $cantlicenciasvencidasnodesactivadas = 0;
        $licencias = array();

        if (($administrador == 't')) {
            $fecha = date('Y-m-d');
            $almacenActual = $this->dashboardModel->getAlmacenActual();

            if ($almacenActual == 0) {
                $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
            }
            $milicencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacenActual);
            //tengo mas de una licencia?
            //busco todas las licencias que tengo
            $licenciastu = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) ", false);

            //busco todas mis licencias asociadas vencidas que no esten desactivadas
            $licencias = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) AND (estado_licencia=15 OR fecha_vencimiento < '$fecha') and desactivada !=1  ", false);

            $licenciasdesactivadas = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) AND (estado_licencia=15 OR fecha_vencimiento < '$fecha') and desactivada=1", false);

            $cantlicenciastodas = count($licenciastu);
            $cantlicenciasdesactivadas = count($licenciasdesactivadas);
            $cantlicenciasvencidasnodesactivadas = count($licencias);
            $todasvencidasdespues = $cantlicenciastodas;

            foreach ($licenciastu as $value) {
                $fecha_nueva4 = $value['fecha_vencimiento'];
                $fecha_nueva4 = date("Y-m-d", strtotime($fecha_nueva4 . "+ 7 days"));
                $hoy4 = date("Y-m-d");

                if ($fecha_nueva4 >= '2019-05-02') {
                    $fecha_nueva4 = '2019-05-01';
                }

                if ($fecha_nueva4 >= $hoy4) {
                    $todasvencidasdespues--;
                }
            }
        }

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $estado = $cuentaEstado["estado"]; //estado bd

        //verificar si es de prueba
        if (!empty($licencias) && $estado == 2) {

            if ((($licencias[0]['fecha_vencimiento'] < $fecha) || ($licencias[0]['estado_licencia'] == 15)) && (($administrador == 't'))) {

                $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
                //$data['title'] = "TU PRUEBA HA FINALIZADO";
                $data['title'] = "TU SUSCRIPCIÓN GRATUITA A EXPIRADO";
                $data['message'] = "Selecciona el plan que más se adapte a tu negocio";
                $data['message1'] = " ";
                $data['message2'] = '';
                $data['message3'] = '2';
                $data['mostrar_salir'] = true;
                $data['logout'] = true;
                $data['licencias'] = $licencia;
                $data['almacentodos'] = $this->almacenes->getAll();
                $data['planes'] = $this->licencias->get_planes();
                $planesid = " where id_plan in(";
                $id = "";
                foreach ($data['planes'] as $plan) {
                    $id .= "," . $plan["id"];
                }
                $planesid .= trim($id, ",") . ")";
                $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);
                foreach ($data['planes'] as $key => $plan) {
                    foreach ($data['detalles_planes'] as $detalle) {

                        if (($detalle->id_plan == $plan["id"])) {
                            $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
                        }

                    }
                }
                $data['config'] = true;
                $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                $data['info_factura_pais'] = $this->clientes->get_pais();
                $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
                $this->layout->template('login')->show('licenciasvencidas');
                $html = $this->load->view('licenciasvencidas', $data, true);
                echo $html;
                exit;
            }
        }

        //si no soy prueba y soy admin y tengo por lo menos una licencia asociada que esta venciada y
        if ($administrador == 't') {
            //verificar si aun estoy dentro de los 7 dias adicionales

            $fecha_nueva3 = isset($milicencia['fecha_vencimiento']) ? $milicencia['fecha_vencimiento'] : date('Y-m-d');
            /*$datetime1 = new DateTime($fecha_nueva3);
            $datetime2 = new DateTime('2019-05-01');
            $interval = $datetime1->diff($datetime2);
            $diasn= $interval->format('%a');  */
            //$fecha_nueva3= date("Y-m-d",strtotime($fecha_nueva3."+ ".$diasn." days"));
            $fecha_nueva3 = date("Y-m-d", strtotime($fecha_nueva3 . "+ 7 days"));
            $hoy3 = date('Y-m-d');
            if ($fecha_nueva3 >= '2019-05-02') {
                $fecha_nueva3 = '2019-05-01';
            }
            $mensaje = "";
            $mensaje2 = "";
            $vencidadespues = false;
            if ($fecha_nueva3 < $hoy3) {
                $vencidadespues = true;

            } else {

                $date1 = new DateTime($fecha_nueva3);
                $date2 = new DateTime($hoy3);
                $diff1 = $date1->diff($date2);
                $dias2 = date_diff($date1, $date2);
                //$mensaje="<br>Vendty te regala 7 días despues del vencimiento de tu suscripción. Te quedan:<b>".$dias2->format("%a")." días</b>";
                //$mensaje2="<br>Te quedan:<b>".$dias2->format("%a")." días</b>";
            }

            if ((((!empty($licencias)) || ($cantlicenciastodas == ($cantlicenciasvencidasnodesactivadas + $cantlicenciasdesactivadas))))) {

                $data['title'] = "SUSCRIPCIÓN VENCIDA";
                $data['message'] = "Su suscripción está vencida. Si gusta seguir disfutando de Vendty, debe realizar su pago ";
                $data['message1'] = "";
                $data['message2'] = ' ';
                $data['message3'] = '';
                $data['logout'] = true; //para mostrar el boton de inicio =true
                $data['licencias'] = $licencias;
                $data['mostrar_salir'] = true;
                $data['almacentodos'] = $this->almacenes->getAll();
                $data['planes'] = $this->licencias->get_planes();
                $planesid = " where id_plan in(";
                $id = "";
                foreach ($data['planes'] as $plan) {
                    $id .= "," . $plan["id"];
                }
                $planesid .= trim($id, ",") . ")";
                $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

                foreach ($data['planes'] as $key => $plan) {
                    foreach ($data['detalles_planes'] as $detalle) {

                        if (($detalle->id_plan == $plan["id"])) {
                            $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
                        }

                    }
                }
                $data['config'] = true; //para que salga el boton pagar =true
                if ($vencidadespues) {
                    $data['btnconfig'] = false; //muestra boton de configuraciones =true
                } else {
                    $data['btnconfig'] = true; //muestra boton de configuraciones =true
                }

                $data['admin_licencia_vencida'] = true;
                $data['todas_vencidas'] = false;
                //tengo todas las licencia asociadas vencida
                //saber si tengo más de una licencia y no estan todas vencidas

                if (($cantlicenciastodas > 1) && (($cantlicenciastodas) != ($cantlicenciasvencidasnodesactivadas + $cantlicenciasdesactivadas))) {
                    $data['btnconfig'] = true; //muestra boton de configuraciones =true
                    $data['mostrar_salir'] = false;
                    $data['message'] = "Tiene suscripciones vencidas. <br>Si gusta seguir disfutando de Vendty, debe realizar el pago";
                } else {
                    if (count($licenciastu) > 1) {
                        //$data['message'] = "Tiene suscripciones vencidas. Si gusta seguir disfutando de Vendty, debe ";
                        $data['message'] = "Tiene suscripciones vencidas." . $mensaje;
                    }
                }

                $almacenes_vencidos = array();

                foreach ($licencias as $licencia_vencida) {
                    array_push($almacenes_vencidos, $licencia_vencida["id_almacen"]);
                }

                $this->session->set_userdata('licencia_admin_vencida', $almacenes_vencidos);

                $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
                $data['info_factura_pais'] = $this->clientes->get_pais();
                $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

                if ($cantlicenciastodas == $todasvencidasdespues) {
                    $this->layout->template('login')->show('licenciasvencidas');
                    $html = $this->load->view('licenciasvencidas', $data, true);
                    echo $html;
                    exit;
                }

            }
        }
        //------------------

        $data = array();
        $data['dias_licencia'] = null;
        $data['fecha_vencimiento'] = null;
        $data['valor_renovacion'] = null;
        $data['almacen'] = $almacen;
        $data['dias'] = $dias;
        $recordarplan = 7;

        $data['usuario'] = $this->session->userdata('username');
        $data['almacenes'] = $this->dashboardModel->getAllAlmacenes();
        $data['empresa'] = $this->dashboardModel->get_data_empresa();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['data_empresa'] = $data_empresa;
        $data['datos_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        // $data['meta_diaria'] = $this->dashboardModel->get_meta_diaria($almacen,0);
        $datos_ventas = $this->dashboardModel->getVentas($almacen, 0, 0);
        // $data['meta_diaria']["total_ventas"] = $datos_ventas[0]["total_venta"];
        // $data['meta_diaria']['total_facturas'] = $datos_ventas[0]["cantidad"];
        // $data['productos_relevantes_hoy'] = $this->dashboardModel->getProductosRelevantesHoy($almacen);
        // $data['ventas'] = $ventas = $this->dashboardModel->getVentas($almacen, $dias,1);
        // $data['utilidad'] = $utilidad = $this->dashboardModel->getUtilidad($almacen, $dias);

        // $data['ventas_almacen'] = $this->dashboardModel->ventasPorAlmacen($almacen, $dias);
        // $data['categorias_vendidas'] = $this->dashboardModel->categoriasMasVendidas($almacen, $dias);
        // var_dump($data['categorias_vendidas']);die();

        /*
        $total_gastos = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha'=>date("Y-m-d")), $almacen);

        foreach ($proformas as $key => $value) {
        $total_gastos+=$value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago'=>date("Y-m-d")));

        foreach ($pagos_compras as $key => $value) {
        $total_gastos+=$value->cantidad;
        }

        $data['total_gastos'] = $total_gastos;
        $total_gastos_ayer =0;
        $proformas = $this->proformas->get_proformas_where(array('fecha'=>date("Y-m-d",strtotime("-1 day"))), $almacen);

        foreach ($proformas as $key => $value) {
        $total_gastos_ayer+=$value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago'=>date("Y-m-d",strtotime("-1 day") )));

        foreach ($pagos_compras as $key => $value) {
        $total_gastos_ayer+=$value->cantidad;
        }

        $data['total_gastos_ayer']=$total_gastos_ayer;
        $data['ventas_por_hora'] = $this->dashboardModel->get_ventas_hora(date("Y-m-d"));
        $prod_populares = $this->dashboardModel->productosMasPopulares($almacen, $dias);
        $stock_minimo = $this->dashboardModel->stockMinimo($almacen);
         */

        //-----------------------------------------------------
        //   Acortando nombre con atributos muy largos
        //-----------------------------------------------------
        // PRODUCTOS POUPULARES
        /*
        foreach ($prod_populares as $key => $val) {
        $tmpArray = explode("/", $val["nombre"]);
        if (count($tmpArray) > 2)
        $prod_populares[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
        }

        // STOCK_MINIMO
        foreach ($stock_minimo as $key => $val) {
        $tmpArray = explode("/", $val["nombre"]);
        if (count($tmpArray) > 2)
        $stock_minimo[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
        }

        $data['prod_populares'] = $prod_populares;
        $data['stock_minimo'] = $stock_minimo;
         */

        $idUsuario = $this->session->userdata('user_id');
        $data['zoho'] = $this->dashboardModel->getZoho($idUsuario);
        $data['estadoZoho'] = "no";

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();

        $estado = $cuentaEstado["estado"];
        $fecha = $cuentaEstado["fecha"];

        //SI ES TOTALMENTE NUEVO EL USUARIO, SE REDIRECCIONAREMOS A ZOHO Y A GRACIAS
        if ($estado == "4") {

            $zohoData = $this->session->userdata('nuevoCliente');
            $this->layout->template('ajax')->show('frontend/zoho.php', array('data' => $zohoData));

            // Si esta en estado de configuracion
            $this->wizard();

        } else {

            $data['estado'] = $estado;

            //RESTA DE FECHAS PARA VALIDAR QUE YA SE LE ACABO EL TIEMPO AL USUARIO
            date_default_timezone_set("America/Lima");
            $data['diasCuenta'] = $this->restarFechas($fecha, date('Y-m-d'));
            //$data['diasCuentaDisponibles'] = 14 - $data['diasCuenta'];
            $diacuentasnuevas = $this->crm_model->getdiascuentaprueba();
            $diacuentasnuevas = $diacuentasnuevas[0]['dias'];
            $data['diasCuentaDisponibles'] = $diacuentasnuevas - $data['diasCuenta'];
            $data['offline'] = $this->input->get("offline");

            // Si no ha completado la configuracion se envia a Wizard
            if ($estado == 3) {
                $this->wizard();
            } else {
                // Carga normal de la pagina
                //consultamos si esta en la lista de por vencerse
                $administrador = $this->session->userdata('is_admin');
                $data['dias_adicionales'] = 0;
                if (($administrador == 't') || ($administrador == 'a')) {
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id')), array('1', '15', '16', '17'));
                } else {
                    if ($almacen == 0) {
                        $al = $this->almacenes->getAll();
                        $almacen = ($this->dashboardModel->getAlmacenActuallicencias() == 0) ? 1 : $al[0]->id;
                    }
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_almacen' => $almacen));
                }
                if (count($datos_vencimiento) > 0) {
                    /*$datetime1 = new DateTime($datos_vencimiento[0]->fecha_vencimiento);
                    $datetime2 = new DateTime('2019-05-01');
                    $interval = $datetime1->diff($datetime2);
                    $diasn= $interval->format('%a');*/

                    $datos_plan = $this->crm_model->get_planes(array('id' => $datos_vencimiento[0]->id_plan));
                    $recordarplan = $datos_plan[0]->comienzo_dias_recordacion;
                    $data['nombre_plan'] = $datos_plan[0]->nombre_plan;
                    $data['datos_plan'] = $datos_plan[0];
                    $data['datos_vencimiento'] = $datos_vencimiento[0];
                    $data['fecha_extendida'] = date("Y-m-d", strtotime($datos_vencimiento[0]->fecha_vencimiento . "+ 7 days"));
                    //$data['fecha_extendida']= date("Y-m-d",strtotime($datos_vencimiento[0]->fecha_vencimiento."+ ".$diasn." days"));
                    if ($data['fecha_extendida'] >= '2019-05-02') {
                        $data['fecha_extendida'] = '2019-05-01';
                    }
                    $hoy = date_create(date("y-m-d"));
                    $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                    $dias = date_diff($hoy, $fecha_vencimiento);
                    //verificar si aun estoy dentro de los 7 dias adicionales
                    $fecha_nueva = $datos_vencimiento[0]->fecha_vencimiento;
                    /*$datetime1 = new DateTime($fecha_nueva);
                    $datetime2 = new DateTime('2019-05-01');
                    $interval = $datetime1->diff($datetime2);
                    $diasn= $interval->format('%a');
                    $fecha_nueva= date("Y-m-d",strtotime($fecha_nueva."+ ".$diasn." days")); */
                    $fecha_nueva = date("Y-m-d", strtotime($fecha_nueva . "+ 7 days"));
                    $hoy2 = date("Y-m-d");
                    if ($fecha_nueva >= '2019-05-02') {
                        $fecha_nueva = '2019-05-01';
                    }
                    if ($datos_vencimiento[0]->fecha_vencimiento < $hoy2) {
                        $data['dias_adicionales'] = 1;
                        $date1 = new DateTime($fecha_nueva);
                        $date2 = new DateTime($hoy2);
                        $diff1 = $date1->diff($date2);
                        $dias = date_diff($date1, $date2);
                        // will output 2 days
                        //echo $dias->format("%a");
                    }

                    //$data['dias_licencia'] = $dias->format("%R%a");
                    $data['dias_licencia'] = $dias->format("%a");
                    $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                    $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                } else {
                    $data['dias_licencia'] = null;
                    $data['fecha_vencimiento'] = null;
                    $data['valor_renovacion'] = null;
                }
                //var_dump($datos_vencimiento);die();

                ////verifico los dias para mostrar alerta de vencimiento
                //if($data['dias_licencia']>$data['dias']){

                $recordarplan = $recordarplan == "" ? 7 : $recordarplan;

                if ($recordarplan < $data['dias_licencia']) {
                    $data['dias_licencia'] = null;
                }

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
                /*
                if($data["tipo_negocio"] == "restaurante"){

                $almacenActual = $this->dashboardModel->getAlmacenActual();
                $data["zonas"] = $this->secciones_almacen->get_secciones_almacen('b.nombre != "" and a.activo=1 AND id_almacen='.$almacenActual.' AND a.id != -1');
                $data["mesas_secciones"] = $this->secciones_almacen->get_mesas_secciones();

                //$data["orden_barra"] = $this->ordenes->getLatestOrdenByBarra();
                $pedidos = 0;
                foreach($data["mesas_secciones"] as $key){
                $pedidos = $this->ordenes->verificaEstado($key->id_seccion,$key->id);
                $key->pedidos = $pedidos;

                $fechaCreacion = $this->ordenes->getFechaOrdenMesa($key->id_seccion,$key->id);

                $key->fecha_creacion = !empty($fechaCreacion['created_at']) ? $fechaCreacion['created_at'] : '';
                }

                }
                 */

                $data["inicio_home"] = $this->primeros_pasos_model->link_primeros_pasos(array('link' => 0), array($data["tipo_negocio"], '0'));
                $data["inicio_home_tablero"] = $this->primeros_pasos_model->link_primeros_pasos(array('link' => 1), array($data["tipo_negocio"], '0'));
                $data["tareas_realizadas"] = $this->primeros_pasos_model->tareas_realizadas_tablero(array('id_usuario' => $this->session->userdata('user_id'), 'db_config' => $this->session->userdata('db_config_id')));
                $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));

                $data_empresa = $this->mi_empresa->get_data_empresa();
                //print_r($data_empresa); die();
                $data['permitir_formas_pago_pendiente'] = $data_empresa['data']['permitir_formas_pago_pendiente'];
                $data["datos_empresa_ap"] = $data_empresa;
                $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();
                $data["tiponegocio_infoactualizar"] = $this->crm_model->load_state_actualizar_info(array('id_db_config' => $this->session->userdata('db_config_id')));
                $data['paises'] = $this->pais->getAll();
                $data['identificacion_tributaria'] = $this->crm_model->crm_opciones(array('nombre_opcion' => 'identificacion_tributaria'));
                $data['tipo_negocio_especializado'] = $this->crm_model->select_tipo_negocio(false, array('mostrar' => 0));

                //formas de pagos
                // $data['forma_pago'] = $this->forma_pago->getActiva();
                // $data['impuesto'] = $this->impuestos->getFisrt();
                // $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];
                // $data['simbolo'] = $data_empresa['data']['simbolo'];
                // $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];
                $data['type_licence'] = $this->dashboardModel->get_type_licence();
                $data['stores_avaibles'] = $this->dashboardModel->get_stores_avaibles();
                $data['steps_complete'] = $this->dashboardModel->get_complete_steps();
                session_start();
                if (array_key_exists('api_auth', $_SESSION) && isset($_SESSION['api_auth']) && $_SESSION['api_auth'] != '') {
                    $data['token'] = $_SESSION['api_auth'];
                } else {
                    $data['token'] = false;
                }
                $this->layout->template('dashboard')->show('frontend/dash.php', array('data' => $data));
            }
        }

    }

    public function getAjaxDashboard()
    {

        $data = array();
        $dias = $this->input->post("dias_seleccionados");
        $dia1 = $this->input->post("dias_seleccionados");
        if ($dia1 == 7) {
            $dia1 = 0;
        }

        $almacen = $this->input->post("almacen");
        $data['meta_diaria'] = $this->dashboardModel->get_meta_diaria($almacen, $dia1);
        $data['productos_relevantes_hoy'] = $this->dashboardModel->getProductosRelevantesHoy($almacen);
        $data['ventas'] = $ventas = $this->dashboardModel->getVentas($almacen, $dias, 1);
        $data['utilidad'] = $utilidad = $this->dashboardModel->getUtilidad($almacen, $dias);
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['simbolo'] = $data_empresa['data']['simbolo'];
        $data['ventas_almacen'] = $ventasAlmacen = $this->dashboardModel->ventasPorAlmacen($almacen, $dias);
        $data['prod_populares'] = $populares = $this->dashboardModel->productosMasPopulares($almacen, $dias);
        $data['stock_minimo'] = $stockMinimo = $this->dashboardModel->stockMinimo($almacen);
        $data['categorias_vendidas'] = $categoriasVendidas = $this->dashboardModel->categoriasMasVendidas($almacen, $dias);
        $data['ventas_por_hora'] = $this->dashboardModel->get_ventas_hora(date("Y-m-d"), $almacen);
        // $datos_ventas = $this->dashboardModel->getVentas($almacen, 0);
        $datos_ventas = $this->dashboardModel->getVentas($almacen, $dia1, 0);
        $data['meta_diaria']['total_facturas'] = $datos_ventas[0]["cantidad"];
        //print_r($data);

        //jsson-gastos
        $total_gastos = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha' => date("Y-m-d")), $almacen);

        foreach ($proformas as $key => $value) {
            $total_gastos += $value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago' => date("Y-m-d")));

        foreach ($pagos_compras as $key => $value) {
            $total_gastos += $value->cantidad;
        }

        $data['total_gastos'] = $total_gastos;
        $total_gastos_ayer = 0;
        $proformas = $this->proformas->get_proformas_where(array('fecha' => date("Y-m-d", strtotime("-1 day"))), $almacen);

        foreach ($proformas as $key => $value) {
            $total_gastos_ayer += $value->valor;
        }

        $pagos_compras = $this->pagos->get_pagos_compra(array('fecha_pago' => date("Y-m-d", strtotime("-1 day"))));

        foreach ($pagos_compras as $key => $value) {
            $total_gastos_ayer += $value->cantidad;
        }

        $data['total_gastos_ayer'] = $total_gastos_ayer;

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function setNewUserData()
    {

        //=========================================================================

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);

        //=========================================================================

        $data = array();

        $data["nombre"] = $this->input->post("nombre");
        $data["nit"] = $this->input->post("nit");
        $data["factura"] = $this->input->post("factura");

        //Configuration Upload and Image
        $image_name = "";
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
        $config['max_size'] = '2024';
        $config['max_width'] = '200000';
        $config['max_height'] = '2000000';

        $this->load->library('upload', $config);

        // -----------------------------------
        //    IMAGEN
        //

        //Si hay una imagen
        if (!empty($_FILES['logo']['name'])) {

            // Si se subio correctamente
            if (!$this->upload->do_upload('logo')) {
                $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
            } else {

                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];
            }
        } else {

            $image_name = "dragDrop.jpg";
        }

        $data["logo"] = $image_name;

        //
        //   >>>   FIN IMAGEN
        // -----------------------------------

        $this->newAcountModel->setNewUserData($data);

        echo "ok";
    }

    public function save_config_first_user()
    {
        $this->load->library('form_validation');
        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnection);

        $data = array();

        $this->form_validation->set_rules('nombre', 'Nombre de la Empresa', 'required');
        $this->form_validation->set_rules('nit', 'Nit de la Empresa', 'required');
        $this->form_validation->set_rules('base_datos', 'Base de Datos de prueba', 'required');

        if ($this->form_validation->run() == false) {
            $errors_msj = str_replace('<p>', ' ', validation_errors());
            $errors_msj = str_replace('</p>', ' ', $errors_msj);
            $data = array(
                'error' => $errors_msj,
                'res' => 'error',
            );
        } else {
            switch ($this->input->post("base_datos")) {
                case 1:$db_name_wizard = 'Moda';
                    break;
                case 2:$db_name_wizard = 'Comidas';
                    break;
                case 3:$db_name_wizard = 'Minimercados';
                    break;
                case 4:$db_name_wizard = 'Droguerias';
                    break;
                case 5:$db_name_wizard = 'Retail General';
                    break;
                case 6:$db_name_wizard = 'Otros';
                    break;
            }

            //$convertloop = new \ConvertLoop\ConvertLoop("f4c03103", "pkUU21crGXeEfVpDKZsTkVGJ", "v1");
            $convertloop = new \ConvertLoop\ConvertLoop("73a39be3", "pkUU21crGXeEfVpDKZsTkVGJ", "v1");
            $person = array(
                "email" => $this->session->userdata('email'),
                "first_name" => $this->session->userdata('username'),
                "Empresa" => $this->input->post("nombre"),
                "Base_de_datos" => $db_name_wizard,
            );
            $convertloop->people()->createOrUpdate($person);
            $event = array(
                "name" => 'Wizard',
                "person" => $person,
                "ocurred_at" => time(),
            );
            $convertloop->eventLogs()->send($event);

            $data["nombre"] = $this->input->post("nombre");
            $data["base_datos"] = $this->input->post("base_datos");
            $data["nit"] = $this->input->post("nit");
            $data["factura"] = $this->input->post("factura");

            //Configuration Upload and Image
            $image_name = "";
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG||png';
            $config['max_size'] = '2024';
            $config['max_width'] = '200000';
            $config['max_height'] = '2000000';

            $this->load->library('upload', $config);

            //Si hay una imagen
            if (!empty($_FILES['logo']['name'])) {
                // Si se subio correctamente
                if (!$this->upload->do_upload('logo')) {
                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                } else {
                    $upload_data = $this->upload->data();
                    $image_name = $upload_data['file_name'];
                }

            } else {
                $image_name = "dragDrop.jpg";
            }
            $data["logo"] = $image_name;
            // Fin Logo

            $this->newAcountModel->setNewUserData($data);
            $this->newAcountModel->setDataBase($data["base_datos"]);

            $data = array(
                'res' => 'success',
                'success' => 'Se ha actualizado con éxito!',
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function test()
    {

        //=========================================================================

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);

        //=========================================================================

        $almacen = 0;
        $dias = 7;

        $metaDiaria = $this->dashboardModel->get_meta_diaria($almacen);
        $relevantesHoy = $this->dashboardModel->getProductosRelevantesHoy($almacen);

        $ventas = $this->dashboardModel->getVentas($almacen, $dias);
        $utilidad = $this->dashboardModel->getUtilidad($almacen, $dias);
        $ventasAlmacen = $this->dashboardModel->ventasPorAlmacen($almacen, $dias);
        $populares = $this->dashboardModel->productosMasPopulares($almacen, $dias);
        $stockMinimo = $this->dashboardModel->stockMinimo($almacen);
        $categoriasVendidas = $this->dashboardModel->categoriasMasVendidas($almacen, $dias);

        echo "metadiaria: ";
        print_r($metaDiaria);
        echo "<br><br>";
        echo "relevantesHoy: ";
        print_r($relevantesHoy);
        echo "<br><br>";
        echo "ventas: ";
        print_r($ventas);
        echo "<br><br>";
        echo "utilidad: ";
        print_r($utilidad);
        echo "<br><br>";
        echo "ventasAlmacen: ";
        print_r($ventasAlmacen);
        echo "<br><br>";
        echo "populares: ";
        print_r($populares);
        echo "<br><br>";
        echo "stockmin: ";
        print_r($stockMinimo);
        echo "<br><br>";
        echo "categorias mas: ";
        print_r($categoriasVendidas);
    }

    //************************************************************************
    // FIN EDWIN
    //************************************************************************

    public function acceso_limitado()
    {
        $this->layout->template('login')->show('frontend/acceso_limitado.php', array('message' => $this->session->flashdata('message')));
    }

    public function indexV1()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data = array();
        $data['almacen'] = $this->almacen->get_combo_data();

        $data['meta_diaria'] = $this->graficas->get_meta_diaria();
        $data['productos_relevantes'] = $this->graficas->get_productos_relevantes();
        $data['margen_utilidad'] = $this->graficas->get_utilidad_almacen();
        $data['margen_utilidad_general'] = $this->graficas->get_utilidad_general();
        $data['almacen'] = $this->almacen->get_all('0');

        /*  $data['clientes'] = $this->clientes->get_total();
        $data['proveedores'] = $this->proveedores->get_total();
        $data['productos'] = $this->productos->get_total();
        //$data['servicios'] = $this->servicios->get_total();
        $data['facturas']['total'] = $this->facturas->get_total();
        $data['facturas']['pendientes'] = $this->facturas->get_total_pendientes();
        $data['facturas']['pagadas'] = $this->facturas->get_total_pagadas();
        //$data['proformas']['total'] = $this->proformas->get_total();
        $data['presupuestos'] = $this->presupuestos->get_total();
        $db_config_id = $this->session->userdata('db_config_id');
        $data['usuarios'] = $this->usuarios->get_total_tenant($db_config_id);

        $this->load->model("miempresa_model",'miempresa');
        $this->miempresa->initialize($this->dbConnection); */
        $this->layout->template('member')->js(array(
            base_url('public/js/jqplot/jquery.jqplot.min.js')
            , base_url('public/js/jqplot/jqplot.meterGaugeRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.barRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.categoryAxisRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.pieRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.donutRenderer.min.js')
            , base_url('public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js')
            , 'http://www.chartjs.org/assets/Chart.min.js',
        ))->css(array(base_url('public/css/jquery.jqplot.min.css')
            , base_url('public/css/jquery/ui.css')))
            ->show('frontend/graficas.php', array('data' => $data, 'data1' => $data));
    }

    public function get_ajax_meta_diaria()
    {
        $almacen = $this->input->get('almacen', true);
        $this->output->set_content_type('application/json')->set_output(json_encode($this->graficas->get_meta_diaria($almacen)));
    }

    public function get_ajax_productos_relevantes()
    {

        $almacen = $this->input->post('almacen', true);
        $fecha_desde = $this->input->post('fecha_desde', true);
        $fecha_hasta = $this->input->post('fecha_hasta', true);

        $data = array();
        $data['almacen'] = $this->almacen->get_combo_data();

        $data['meta_diaria'] = $this->graficas->get_meta_diaria();
        $data['margen_utilidad'] = $this->graficas->get_utilidad_almacen();
        $data['margen_utilidad_general'] = $this->graficas->get_utilidad_general();

        $data['productos_relevantes'] = $this->graficas->get_productos_relevantes($almacen, $fecha_desde, $fecha_hasta);

        $this->layout->template('ajax')->js(array(
            base_url('public/js/jqplot/jquery.jqplot.min.js')
            , base_url('public/js/jqplot/jqplot.meterGaugeRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.barRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.categoryAxisRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.pieRenderer.min.js')
            , base_url('public/js/jqplot/jqplot.donutRenderer.min.js')
            , base_url('public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js')
            , 'http://www.chartjs.org/assets/Chart.min.js',
        ))->css(array(base_url('public/css/jquery.jqplot.min.css')
            , base_url('public/css/jquery/ui.css')))
            ->show('frontend/graficas-productos-populares', array('data' => $data));
    }

    public function excel_productos_populares()
    {
        $almacen = $this->input->post('almacen', true);
        $fechainicial = $this->input->post('fecha_desde', true);
        $fechafinal = $this->input->post('fecha_hasta', true);
        $this->layout->template('ajax')->show('frontend/excel-productos-populares', array('data' => $this->graficas->excel_productos_relevantes($fechainicial, $fechafinal, $almacen), 'fechainicial' => $fechainicial, 'fechafinal' => $fechafinal, 'almacen' => $almacen));
    }

    public function get_ajax_utilidad_general()
    {
        $fecha_desde = $this->input->get('fecha_desde', true);
        $fecha_hasta = $this->input->get('fecha_hasta', true);
        $this->output->set_content_type('application/json')->set_output(json_encode($this->graficas->get_utilidad_general($fecha_desde, $fecha_hasta)));
    }

    public function filtro_prod_serv()
    {

        $result = array();
        $this->load->model('productos_model', 'productos');
        $this->productos->initialize($this->dbConnection);
        $result = $this->productos->get_term($this->input->get('term', true));
        /* else if($this->input->get('type', TRUE) == "servicio"){

        $this->load->model("servicios_model",'servicios');
        $this->servicios->initialize($this->dbConnection);

        $result = $this->servicios->get_term($this->input->get('term', TRUE));
        } */

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function load_provincias_from_pais()
    {
        $pais = $this->input->get('pais', true);
        $result = $this->pais_provincia->get_provincia_from_pais($pais);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function supcripcion()
    {
        $this->layout->template('member')->css(array(base_url('public/css/stylesheet.css')))->show('frontend/supcripcion.php');
    }

    // connfiguracion
    public function configuracion()
    {
        /*
        if(isset($_FILES['croppedImage'])){
        echo "croppedImage <br>";
        var_dump($_FILES['croppedImage']);
        $image_crop = $_FILES['croppedImage'];
        die();
        }

        if(!empty($_FILES['logotipo']['name'])){
        var_dump($_FILES['logotipo']);
        die();
        }*/
        $message = "";
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $this->opciones->update("etienda", "si");
        $this->session->unset_userdata('estado');
        $this->session->unset_userdata('upload_status');

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

        $this->almacenes->actualizarTablaAlmacenBodega();
        $this->ventas->verificarImpresionRapida();
        //$data['plantilla'] = $this->mi_empresa->get_plantillas();

        //recibimos formularios
        $seleccionado = $this->input->get('seleccionado');
        if (!empty($_POST)) {
            $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();
            $epayco = (!empty($_POST['epayco'])) ? $this->input->post('epayco') : 0;
            switch ($_POST['form']) {
                case 'facturacion':
                    if ($this->form_validation->run('mi_empresa_factura') == true) {
                        $this->crm_empresas_clientes_model->update_info_factura_cliente(
                            array(
                                'nombre_empresa' => $this->input->post('nombre_empresa'),
                                'tipo_identificacion' => $this->input->post('tipo_identificacion'),
                                'numero_identificacion' => $this->input->post('numero_identificacion'),
                                'direccion' => $this->input->post('direccion_factura'),
                                'telefono' => $this->input->post('telefono_factura'),
                                'pais' => $this->input->post('pais_factura'),
                                'correo' => $this->input->post('correo_factura'),
                                'ciudad' => $this->input->post('ciudad_factura'),
                                'contacto' => $this->input->post('contacto_factura'),
                            ),
                            array('id_db_config' => $this->session->userdata('db_config_id'))
                        );

                        if ($epayco == 1) {
                            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
                            return;
                        } else {
                            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha actualizado el registro correctamente. $message"));
                            redirect('frontend/configuracion?seleccionado=1');
                        }

                    } else {
                        if ($epayco == 1) {
                            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
                            return;
                        } else {
                            $seleccionado = 1;
                            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Error: No es posible crear el registro datos incompletos"));
                            redirect('frontend/configuracion?seleccionado=1');
                        }

                    }

                    break;
                case 'empresa':
                    $error_upload = "";
                    $config['upload_path'] = 'uploads/';
                    $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';
                    $config['max_size'] = '50';
                    $config['max_width'] = '250';
                    $config['max_height'] = '250';
                    $this->load->library('upload', $config);
                    //Verficamos si recibimos imagen
                    if (!empty($_FILES['logotipo']['name'])) {
                        if (!$this->upload->do_upload('logotipo')) {
                            $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                        } else {
                            /*$upload_data = $this->upload->data();
                            $image_name = $upload_data['file_name'];
                            $logotipo_empresa = $this->mi_empresa->get_logotipo_empresa();
                            if (!empty($logotipo_empresa) && is_file("uploads/$logotipo_empresa"))
                            {
                            unlink("uploads/$logotipo_empresa");
                            }*/

                            //guardar evento de primeros pasos logo
                            $estadoBD = $this->newAcountModel->getUsuarioEstado();
                            if ($estadoBD["estado"] == 2) {
                                $paso = 10;
                                $marcada = $this->primeros_pasos_model->verificar_tareas_realizadas(array('id_usuario' => $this->session->userdata('user_id'), 'db_config' => $this->session->userdata('db_config_id'), 'id_paso' => $paso));
                                if ($marcada == 0) {
                                    $datatarea = array(
                                        'id_paso' => $paso,
                                        'id_usuario' => $this->session->userdata('user_id'),
                                        'db_config' => $this->session->userdata('db_config_id'),
                                    );
                                    $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
                                }
                            }

                        }
                        if (!empty($error_upload)) {
                            $error_upload .= '<p class="text-error"> Tenga en cuenta: tamaño máximo de archivo ' . $config['max_size'] . ' kb, alto y ancho de imagen: 250px.</p>';
                        }
                    }

                    if ($this->form_validation->run('mi_empresa_config') == true && $error_upload == "") {

                        if ($this->input->post('eliminar_logo') == 1) {
                            $logotipo = $this->mi_empresa->obtenerOpcion('logotipo_empresa');
                            $image_name = "bo";
                            if (!empty($logotipo) && is_file("uploads/$logotipo")) {
                                unlink("uploads/$logotipo");
                            }
                        }

                        $this->mi_empresa->update_data_empresa(array(
                            'nombre_empresa' => $this->input->post('nombre'),
                            'contacto' => $this->input->post('contacto'),
                            'documento' => $this->input->post('documento'),
                            'direccion' => $this->input->post('direccion'),
                            'nit' => $this->input->post('nit'),
                            'telefono' => $this->input->post('telefono'),
                            'pais' => $this->input->post('pais'),
                            'zona_horaria' => $this->input->post('zona_horaria'),
                            'moneda' => $this->input->post('moneda'),
                            'decimales_moneda' => $this->input->post('decimales'),
                            'logotipo_empresa' => isset($image_name) ? $image_name : '',
                            'tipo_negocio' => $this->input->post('tipo_negocio'),
                            //'propina' => $this->input->post('propina'),
                            //'cierre_caja_mesas_abiertas' => $this->input->post('cierre_caja_mesas_abiertas'),
                            'plan_separe' => $this->input->post('plan_separe'),
                            //'eliminar_producto_comanda' => (!empty($this->input->post('eliminar_producto_comanda'))?'si':'no'),
                            //'permitir_formas_pago_pendiente' => (!empty($this->input->post('permitir_formas_pago_pendiente'))?'si':'no'),
                            //'domicilios' => (!empty($this->input->post('domicilios'))?'si':'no'),
                            //'quick_service' => (!empty($this->input->post('quick_service'))?'si':'no'),
                            //'quick_service_command' => (!empty($this->input->post('quick_service_command'))?'si':'no'),
                            'nueva_impresion_rapida' => (!empty($this->input->post('nueva_impresion_rapida')) ? 'si' : 'no'),
                            'impresion_rapida' => (!empty($this->input->post('impresion_rapida')) ? 'si' : 'no'),
                            'enviar_valor_inventario' => (!empty($this->input->post('enviar_valor_inventario')) ? 'si' : 'no'),
                            'stock_historico' => (!empty($this->input->post('stock_historico')) ? 'si' : 'no'),
                            'correo_valor_inventario' => $this->input->post('correo_valor_inventario'),
                            'correo_stock_historico' => $this->input->post('correo_stock_historico'),
                        ));
                        //actualizo en crm_db_activa la informacion de la empre en configuraciones
                        $this->crm_model->update_data_empresa_config(array(
                            'nombre_empresa_config' => $this->input->post('nombre'),
                            'contacto_empresa_config' => $this->input->post('contacto'),
                            'tipo_documento_config' => $this->input->post('documento'),
                            'direccion_empresa_config' => $this->input->post('direccion'),
                            'numero_documento_config' => $this->input->post('nit'),
                            'telefono_empresa_config' => $this->input->post('telefono'),
                            'nombre_pais_config' => $this->input->post('pais'),
                            'tipo_negocio' => $this->input->post('tipo_negocio'),
                        ));

                        $message = "";

                        $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha actualizado el registro correctamente. $message"));
                        redirect('frontend/configuracion/');
                    } else {
                        $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Error: No es posible crear el registro datos incompletos"));
                        redirect('frontend/configuracion/');
                    }

                    break;
                case 'config_restaurante':
                    $this->mi_empresa->update_data_empresa(array(
                        'tipo_negocio' => $this->input->post('tipo_negocio2'),
                        'propina' => $this->input->post('propina'),
                        'cierre_caja_mesas_abiertas' => $this->input->post('cierre_caja_mesas_abiertas'),
                        'eliminar_producto_comanda' => (!empty($this->input->post('eliminar_producto_comanda')) ? 'si' : 'no'),
                        'permitir_formas_pago_pendiente' => (!empty($this->input->post('permitir_formas_pago_pendiente')) ? 'si' : 'no'),
                        'domicilios' => (!empty($this->input->post('domicilios')) ? 'si' : 'no'),
                        'comanda_virtual' => (!empty($this->input->post('comanda_virtual')) ? 'si' : 'no'),
                        'quick_service' => (!empty($this->input->post('quick_service')) ? 'si' : 'no'),
                        'quick_service_command' => (!empty($this->input->post('quick_service_command')) ? 'si' : 'no'),
                    ));

                    $message = "";

                    $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha actualizado el registro correctamente. $message"));
                    redirect('frontend/configuracion/');

                    break;
                case 'impresion':

                    //validmos formulario de impresion
                    $plantilla = $this->input->post('plantilla_pos');
                    // if ($this->form_validation->run('header_temrs_config') == true)
                    //{
                    $this->mi_empresa->update_data_empresa(array(
                        'plantilla' => $plantilla,
                        'titulo_venta' => $_POST['titulo_venta'],
                        'plantilla_general' => $_POST['plantilla_general'],
                    ));

                    //}
                    $this->mi_empresa->update_data_header_terms(array(
                        'terminos' => $this->input->post('terms'),
                        'cabecera' => $this->input->post('header'),
                    ));
                    break;
                case 'numeros':
                    if ($this->form_validation->run('numero_prefijo_config') == true) {
                        $this->mi_empresa->update_data_numeros(array(
                            'prefijo_presupuesto' => $this->input->post('prefijo_presupuesto'),
                            'numero_presupuesto' => $this->input->post('numero_presupuesto'),
                            'prefijo_devolucion' => $this->input->post('prefijo_devolucion'),
                            'numero_devolucion' => $this->input->post('numero_devolucion'),
                        ));
                    }
                    break;
                case 'impuestos':

                    if ($this->form_validation->run('impuestos_config') == true) {

                        if ($this->impuestos->add()) {
                            $this->session->set_flashdata('message', custom_lang('sima_tax_created_message', 'Impuesto creado correctamente'));
                        } else {
                            $this->session->set_flashdata('error', custom_lang('sima_tax_created_message', 'Error, ya existe otro impuesto con el mismo porcentaje, por favor verifica e intenta nuevamente'));
                        }

                        redirect('frontend/configuracion/#tab-4');
                    }
                    break;
                case 'usuarios':
                    $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
                    $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
                    $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
                    $this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'required|xss_clean|max_length[10]');
                    $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');
                    $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
                    $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
                    $this->form_validation->set_rules('rol_id', 'Rol', 'required');
                    $this->form_validation->set_rules('almacen', 'Almacen', 'required');
                    $this->form_validation->set_rules('caja', 'Caja', 'required');
                    if ($this->form_validation->run('impuestos_config') == true) {
                        $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
                        $email = $this->input->post('email');
                        $password = $this->input->post('password');
                        $is_admin = 'a';
                        if ($this->input->post('almacen') != '-1') {
                            if ($_POST['is_admin'] == 't') {
                                $is_admin = isset($_POST['is_admin']) ? 't' : 'f';
                            } else {
                                $is_admin = isset($_POST['is_admin']) ? 's' : 'f';
                            }

                        }

                        $additional_data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'company' => $this->input->post('company'),
                            'phone' => $this->input->post('phone1'),
                            'db_config_id' => $this->session->userdata('db_config_id'),
                            'rol_id' => $this->input->post('rol_id'),
                            'is_admin' => $is_admin,
                        );
                        $this->load->model('ion_auth_model');
                        $id = $this->ion_auth_model->register($username, $password, $email, $additional_data);
                        $this->ion_auth_model->activate($id);
                        if ($id != false) {

                            $array_datos = array(
                                "db_config_id" => $this->session->userdata('db_config_id'),
                            );

                            $this->db->where('id', $id);
                            $this->db->update("users", $array_datos);

                            $ids = $this->dbConnection->insert('usuario_almacen', array('usuario_id' => $id, 'almacen_id' => $this->input->post('almacen'), 'id_Caja' => $this->input->post('caja')));
                            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

                            redirect("frontend/configuracion#tab-5", 'refresh');
                        }
                        redirect("frontend/configuracion#tab-5", 'refresh');
                    }
                    break;
                case 'almacenes':
                    if (!$this->ion_auth->logged_in()) {redirect('auth', 'refresh');}

                    $this->almacenes->actualizarTabla();

                    $db_config_id = $this->session->userdata('db_config_id');
                    $is_admin = $this->session->userdata('is_admin');
                    $username = $this->session->userdata('username');

                    $user = $this->db->query("SELECT almacen FROM db_config where id = '" . $db_config_id . "' limit 1")->result();
                    foreach ($user as $dat) {
                        $almacen_1 = $dat->almacen;
                    }
                    $user = $this->dbConnection->query("SELECT count(*) as total_almacen FROM almacen ORDER BY id ASC")->result();
                    foreach ($user as $dat) {
                        $almacen_2 = $dat->total_almacen;
                    }

                    if ($almacen_2 >= $almacen_1) {echo "<script> alert('Si desea craear mas almacenes comuniquese con el area comercial de vendty');
                    window.location='index'; </script>";
                    }
                    if ($this->form_validation->run('almacenes') == true) {

                        $active = isset($_POST['activo']) ? 1 : 0;
                        $activar_consecutivo_cierre_caja = isset($_POST['activar_consecutivo_cierre_caja']) ? 'si' : 'no';

                        $data = array(
                            'direccion' => $this->input->post('direccion')
                            , 'resolucion_factura' => $this->input->post('resolucion_factura')
                            , 'nit' => $this->input->post('nit')
                            , 'nombre' => $this->input->post('nombre')
                            , 'telefono' => $this->input->post('telefono')
                            , 'prefijo' => $this->input->post('prefijo')
                            , 'consecutivo' => $this->input->post('consecutivo')
                            , 'meta_diaria' => $this->input->post('meta_diaria')
                            , 'ciudad' => $this->input->post('provincia')
                            , 'activo' => $active
                            , 'numero_fin' => $this->input->post('numero_fin')
                            , 'fecha_vencimiento' => $this->input->post('fecha_vencimiento')
                            , 'numero_alerta' => $this->input->post('numero_alerta')
                            , 'fecha_alerta' => $this->input->post('fecha_alerta')
                            , 'consecutivo_cierre_caja' => $this->input->post('consecutivo_cierre_caja')
                            , 'activar_consecutivo_cierre_caja' => $activar_consecutivo_cierre_caja,

                        );

                        $this->almacenes->add($data);

                        $this->session->set_flashdata('message', custom_lang('sima_category_created_message', 'Almacen creado correctamente'));

                        redirect('frontend/configuracion');

                    }
                    break;
                case 'caja':
                    if ($this->input->post('nombre')) {
                        $data = array(

                            'nombre' => $this->input->post('nombre')

                            , 'id_Almacen' => $this->input->post('almacen'),

                        );

                        $this->Caja->add($data);
                        $this->session->set_flashdata('estado', 'ok');
                        $this->session->set_flashdata('upload_status', 'Caja creada correctamente');

                    }

                    if (($this->input->post('valor_caja')) && ($this->input->post('cierre_automatico'))) {
                        $valor_caja = $this->input->post('valor_caja');
                        $cierre = ($this->input->post('cierre_automatico') == 2) ? 0 : 1;
                        $this->mi_empresa->update_data_empresa(array('valor_caja' => $valor_caja, 'cierre_automatico' => $cierre));
                        $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha actualizado el registro correctamente."));
                        redirect('frontend/configuracion/');
                    }

                    break;
                case 'rol':
                    if ($this->form_validation->run('roles') == true) {
                        $data = array(
                            'rol' => array(
                                'nombre_rol' => $this->input->post('nombre_rol'),
                                'descripcion' => $this->input->post('descripcion'),
                            ),
                            'permisos' => $_POST['permisos'],
                        );

                        $this->roles->add($data);
                        $this->session->set_flashdata('estado', 'ok');
                        $this->session->set_flashdata('upload_status', custom_lang('sima_rol_created_message', 'Rol creado correctamente'));
                    }
                    break;
                case 'puntos_leal':
                    $this->mi_empresa->update_data_empresa(array(
                        'puntos_leal' => (!empty($this->input->post('puntos_leal')) ? 'si' : 'no'),
                        'usuario_puntos_leal' => $this->input->post('usuario_puntos_leal'),
                        'contraseña_puntos_leal' => $this->input->post('contraseña_puntos_leal'),
                    ));

                    $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha actualizado el registro correctamente. $message"));
                    redirect('frontend/configuracion/');
                    break;
            }
        }

        $timezones = array(
            'Pacific/Midway' => "(GMT-11:00) Midway Island",
            'US/Samoa' => "(GMT-11:00) Samoa",
            'US/Hawaii' => "(GMT-10:00) Hawaii",
            'US/Alaska' => "(GMT-09:00) Alaska",
            'US/Pacific' => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana' => "(GMT-08:00) Tijuana",
            'US/Arizona' => "(GMT-07:00) Arizona",
            'US/Mountain' => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua' => "(GMT-07:00) Chihuahua",
            'America/Mazatlan' => "(GMT-07:00) Mazatlan",
            'America/Mexico_City' => "(GMT-06:00) Mexico City",
            'America/Monterrey' => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan' => "(GMT-06:00) Saskatchewan",
            'US/Central' => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern' => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana' => "(GMT-05:00) Indiana (East)",
            'America/Bogota' => "(GMT-05:00) Bogota",
            'America/Lima' => "(GMT-05:00) Lima",
            'America/Caracas' => "(GMT-04:30) Caracas",
            'Canada/Atlantic' => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz' => "(GMT-04:00) La Paz",
            'America/Santiago' => "(GMT-04:00) Santiago",
            'Canada/Newfoundland' => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            //'Greenland' => "(GMT-03:00) Greenland",
            'Atlantic/Stanley' => "(GMT-02:00) Stanley",
            'Atlantic/Azores' => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde' => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca' => "(GMT) Casablanca",
            'Europe/Dublin' => "(GMT) Dublin",
            'Europe/Lisbon' => "(GMT) Lisbon",
            'Europe/London' => "(GMT) London",
            'Africa/Monrovia' => "(GMT) Monrovia",
            'Europe/Amsterdam' => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade' => "(GMT+01:00) Belgrade",
            'Europe/Berlin' => "(GMT+01:00) Berlin",
            'Europe/Bratislava' => "(GMT+01:00) Bratislava",
            'Europe/Brussels' => "(GMT+01:00) Brussels",
            'Europe/Budapest' => "(GMT+01:00) Budapest",
            'Europe/Copenhagen' => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana' => "(GMT+01:00) Ljubljana",
            'Europe/Madrid' => "(GMT+01:00) Madrid",
            'Europe/Paris' => "(GMT+01:00) Paris",
            'Europe/Prague' => "(GMT+01:00) Prague",
            'Europe/Rome' => "(GMT+01:00) Rome",
            'Europe/Sarajevo' => "(GMT+01:00) Sarajevo",
            'Europe/Skopje' => "(GMT+01:00) Skopje",
            'Europe/Stockholm' => "(GMT+01:00) Stockholm",
            'Europe/Vienna' => "(GMT+01:00) Vienna",
            'Europe/Warsaw' => "(GMT+01:00) Warsaw",
            'Europe/Zagreb' => "(GMT+01:00) Zagreb",
            'Europe/Athens' => "(GMT+02:00) Athens",
            'Europe/Bucharest' => "(GMT+02:00) Bucharest",
            'Africa/Cairo' => "(GMT+02:00) Cairo",
            'Africa/Harare' => "(GMT+02:00) Harare",
            'Europe/Helsinki' => "(GMT+02:00) Helsinki",
            'Europe/Istanbul' => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem' => "(GMT+02:00) Jerusalem",
            'Europe/Kiev' => "(GMT+02:00) Kyiv",
            'Europe/Minsk' => "(GMT+02:00) Minsk",
            'Europe/Riga' => "(GMT+02:00) Riga",
            'Europe/Sofia' => "(GMT+02:00) Sofia",
            'Europe/Tallinn' => "(GMT+02:00) Tallinn",
            'Europe/Vilnius' => "(GMT+02:00) Vilnius",
            'Asia/Baghdad' => "(GMT+03:00) Baghdad",
            'Asia/Kuwait' => "(GMT+03:00) Kuwait",
            'Africa/Nairobi' => "(GMT+03:00) Nairobi",
            'Asia/Riyadh' => "(GMT+03:00) Riyadh",
            'Europe/Moscow' => "(GMT+03:00) Moscow",
            'Asia/Tehran' => "(GMT+03:30) Tehran",
            'Asia/Baku' => "(GMT+04:00) Baku",
            'Europe/Volgograd' => "(GMT+04:00) Volgograd",
            'Asia/Muscat' => "(GMT+04:00) Muscat",
            'Asia/Tbilisi' => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan' => "(GMT+04:00) Yerevan",
            'Asia/Kabul' => "(GMT+04:30) Kabul",
            'Asia/Karachi' => "(GMT+05:00) Karachi",
            'Asia/Tashkent' => "(GMT+05:00) Tashkent",
            'Asia/Kolkata' => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu' => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg' => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty' => "(GMT+06:00) Almaty",
            'Asia/Dhaka' => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk' => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok' => "(GMT+07:00) Bangkok",
            'Asia/Jakarta' => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk' => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing' => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong' => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur' => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth' => "(GMT+08:00) Perth",
            'Asia/Singapore' => "(GMT+08:00) Singapore",
            'Asia/Taipei' => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar' => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi' => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk' => "(GMT+09:00) Irkutsk",
            'Asia/Seoul' => "(GMT+09:00) Seoul",
            'Asia/Tokyo' => "(GMT+09:00) Tokyo",
            'Australia/Adelaide' => "(GMT+09:30) Adelaide",
            'Australia/Darwin' => "(GMT+09:30) Darwin",
            'Asia/Yakutsk' => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane' => "(GMT+10:00) Brisbane",
            'Australia/Canberra' => "(GMT+10:00) Canberra",
            'Pacific/Guam' => "(GMT+10:00) Guam",
            'Australia/Hobart' => "(GMT+10:00) Hobart",
            'Australia/Melbourne' => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney' => "(GMT+10:00) Sydney",
            'Asia/Vladivostok' => "(GMT+11:00) Vladivostok",
            'Asia/Magadan' => "(GMT+12:00) Magadan",
            'Pacific/Auckland' => "(GMT+12:00) Auckland",
            'Pacific/Fiji' => "(GMT+12:00) Fiji",
        );

        $tz_stamp = time();
        foreach($timezones as $key => $timezone){
            date_default_timezone_set($key); 
            $timezones[$key] = "(GTM".date('P', $tz_stamp).") ".$key;
        }

        //Obenemos los datos basicos de mi empresa
        //$data_empresa = $this->mi_empresa->get_data_empresa();
        //Para saber si tiene licencias venciadas
        //$this->uri->segment('4');

        if (empty($seleccionado)) {
            $seleccionado = 0;
        }
        valide_option('nueva_impresion_rapida', 'no');
        $data['selecionado'] = $seleccionado;
        $data['apikey'] = $this->impresoras->getApiKey();
        if ($data['apikey'] == '') {
            $data['apikey'] = $this->impresoras->generateApiKey();
        }

        $data['empresa'] = $this->mi_empresa->get_data_empresa();
        $data['paises'] = $this->pais->getAll();
        $data['moneda'] = $this->mi_empresa->get_nomen('moneda');
        $data['pais'] = $this->opciones->getOpcion('pais');
        $data['plan_separe'] = $this->opciones->getOpcion('plan_separe');
        $data['quick_service'] = $this->opciones->getOpcion('quick_service');
        $data['quick_service_command'] = $this->opciones->getOpcion('quick_service_command');
        $data['comanda_virtual'] = $this->opciones->getOpcion('comanda_virtual');
        $data['impresion_rapida'] = $this->opciones->getOpcion('impresion_rapida');
        $data['nueva_impresion_rapida'] = $this->opciones->getOpcion('nueva_impresion_rapida');
        $data['puntos_leal'] = $this->opciones->getOpcion('puntos_leal');
        $data['decimales'] = $this->opciones->getOpcion('decimales_moneda');
        $data["tienda"] = $this->opciones->getOpcion('etienda');
        $data['timezones'] = $timezones;
        $data['plantilla_general'] = $this->opciones->getOpcion('plantilla_general');
        $data['cabecera'] = $this->mi_empresa->get_cabecera_factura();
        $data['terminos'] = $this->mi_empresa->get_terminos_condiciones();
        $data['numero_presupuesto'] = $this->mi_empresa->get_numero_presupuesto();
        $data['numero_factura_fin'] = $this->opciones->getOpcion("numero_factura_fin");
        $data['prefijo_presupuesto'] = $this->mi_empresa->get_prefijo_presupuesto();
        $data['numero_devolucion'] = $this->opciones->getOpcion("numero_devolucion");
        $data['prefijo_devolucion'] = $this->opciones->getOpcion("prefijo_devolucion");
        $data['almacen'] = $this->almacenes->get_combo_data(null, false);
        $data['caja'] = $this->Caja->get_all('0');
        $data['permisos'] = $this->permisos->get_combo_data($this->mi_empresa->get_sistema_empresa());
        $data['opciones'] = $this->mi_empresa->get_permisos_description();
        $data['email'] = $this->session->userdata('email');
        $data['valor_caja'] = $data['empresa']['data']['valor_caja'];
        //$data['tipo_identificacion'] = array('NIT'=>'NIT','RUT'=>'RUT','CC'=>'CC','CE'=>'CE');
        $data['tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

        $data['roles'] = $this->roles->get_combo_data();
        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $data['data']['upload_error'] = isset($error_upload) ? $error_upload : '';
        //$data['valor_caja'] = $data_empresa['data']['valor_caja'];
        //$data['etienda'] = $data_empresa['data']['etienda'];
        $data['atributos'] = $this->almacen->verificar_modulo_habilitado($this->user, self::ATRIBUTOS);
        $data['franquicias'] = (empty($this->franquicias->get_franquicias())) ? false : true;
        $data['mexico'] = ($this->session->userdata('pais_idioma') == 103) ? true : false;
        $almacenActual = $this->dashboardModel->getAlmacenActual();
        $licencia = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_almacen' => $almacenActual));
        $data['factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura_pais'] = $this->clientes->get_pais();
        $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        if (count($licencia) > 0) {
            $licencia = 1;
        } else {
            $licencia = 0;
        }

        $data['tengo_licencia'] = $licencia;
        $key = "537208";
        $time = time();
        $hash = hash_hmac('sha256', $time, $key);
        $data['hash'] = $hash;
        $data['time'] = $time;
        $db_config_id = $this->session->userdata('db_config_id');
        $data['plan_cliente'] = $this->licencias->getPlanesCliente($db_config_id);

        //conseguir las licencias activas
        $hoy = date('Y-m-d');
        $planesactivos = "where id_plan in (";
        //$licenciatotalbd = $this->crm_licencias_empresa_model->get_all_id(array('id_db_config'=>$db_config_id,'fecha_vencimiento >='=>$hoy,'estado_licencia !='=>'15'));
        $licenciatotalbd = $this->crm_licencias_empresa_model->get_all_id(array('id_db_config' => $db_config_id));

        $tiene = 0;

        foreach ($licenciatotalbd as $idplan) {
            $planesactivos .= "'" . $idplan->id_plan . "',";
            $tiene = 1;
        }

        $planesactivos = trim($planesactivos, ',');
        $planesactivos .= ")";
        if ($tiene == 1) {
            $planesdetalleactivos = $this->crm_model->get_detalle_plan($planesactivos);
        } else {
            $planesactivos = "where id_plan in (6)";
            $planesdetalleactivos = $this->crm_model->get_detalle_plan($planesactivos);
        }

        $al_campo = array();
        $deficrear = array();
        $cantbodegasplanes = 0;

        foreach ($licenciatotalbd as $key => $value) {
            $al_campo[$value->id_almacen]['almacen'] = $value->id_almacen;
            foreach ($planesdetalleactivos as $key1 => $value1) {
                if ($value->id_plan == $value1->id_plan) {
                    if ($value->fecha_vencimiento < $hoy) {
                        $al_campo[$value->id_almacen][$value1->nombre_campo] = 0;
                    } else {
                        $al_campo[$value->id_almacen][$value1->nombre_campo] = $value1->valor;
                        if ($value1->nombre_campo == 'bodegas') {
                            if (!is_null($value1->valor)) {
                                $cantbodegasplanes += $value1->valor;
                            } else {
                                $cantbodegasplanes += 100000;
                            }
                            $al_campo[$value->id_almacen][$value1->nombre_campo] = $cantbodegasplanes;
                        }
                    }
                }
            }
        }

        /** Datos planes */

        $administrador = $this->session->userdata('is_admin');
        $db_config_id_user = $this->session->userdata('db_config_id');

        if (($administrador) || ($administrador == 'a')) {
            $fecha = date('Y-m-d');
            $from = " v_crm_licencias V JOIN crm_licencias_empresa L ON L.idlicencias_empresa=V.id_licencia ";
            $where = " V.id_db_config=$db_config_id_user AND (V.fecha_vencimiento >= '$fecha') AND L.planes_id=1 ";
            $licenciast = $this->licenciasModel->licenciaPrueba($from, $where);

        }

        $data['planes']['sw_prueba'] = 1;
        $data['planes']['mostrar_salir'] = false;
        $data['planes']['title'] = "SUSCRIPCI&Oacute;N GRATUITA PR&Oacute;XIMA A VENCER";
        $data['planes']['message'] = "";
        $data['planes']['message1'] = "";
        $data['planes']['message2'] = 'En la parte inferior se encuentran los planes disponibles.';
        $data['planes']['message3'] = '1';
        $data['planes']['logout'] = true;
        $data['planes']['licencias'] = $licenciast;
        $data['planes']['almacentodos'] = $this->almacenes->getAll();
        $data['planes']['planes'] = $this->licencias->get_planes();
        $planesid = " where id_plan in(";
        $id = "";

        foreach ($data['planes']['planes'] as $plan) {
            $id .= "," . $plan["id"];
        }

        $planesid .= trim($id, ",") . ")";
        $data['planes']['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

        foreach ($data['planes']['planes'] as $key => $plan) {
            foreach ($data['planes']['detalles_planes'] as $detalle) {

                if (($detalle->id_plan == $plan["id"])) {
                    $data['planes']['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
                }

            }
        }

        $data['planes']['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['planes']['info_factura_pais'] = $this->clientes->get_pais();
        $data['planes']['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        $data['planes']['config'] = true;

        /** Fin Datos planes */



        // $al_campo=Array ( [1] => Array ( [almacen] => 1 [bodegas] => 100000 [usuarios] => [cajas] => ) [3] => Array ( [almacen] => 3 [bodegas] => 100002 [usuarios] => 5 [cajas] => 2 ) );

        foreach ($al_campo as $key => $value) {
            //buscar cuantos tengo para desactivar el almacen
            //usuario
            $cantuser = $this->usuarios->get_users_almacen($value['almacen']);
            // print_r($cantuser);
            if (isset($value['usuarios'])) {
                if ($cantuser < $value['usuarios']) {
                    $deficrear[$key]['usuarios'] = "1"; //1 permitir -  0 no permitir
                } else {
                    $deficrear[$key]['usuarios'] = "0"; // 0 no permitir
                }
            } else {
                $deficrear[$key]['usuarios'] = "0"; // 0 no permitir
            }
            //bodega
            $cantbodegacreadas = $this->almacen->cantBodega();
            if ($cantbodegacreadas < $cantbodegasplanes) {
                $deficrear[$key]['bodegas'] = "1"; //1 permitir -  0 no permitir
            } else {
                $deficrear[$key]['bodegas'] = "0"; // 0 no permitir
            }
            //cajas
            $cantcajas = $this->Caja->cant_almacen_caja($value['almacen']);
            if (isset($value['cajas'])) {
                if ($cantcajas < $value['cajas']) {
                    $deficrear[$key]['cajas'] = "1"; //1 permitir
                } else {
                    $deficrear[$key]['cajas'] = "0"; // 0 no permitir
                }
            } else {
                $deficrear[$key]['cajas'] = "0"; // 0 no permitir
            }
        }

        $data['definecrear'] = $deficrear;


        /*$db_id=$this->session->userdata['db_config_id'];
        $licenciavencidas = $this->licenciasModel->by_id_config_estado_licencias( "where id_db_config=$db_id and estado_licencia=15", false);
        $data['totallicenciavencidas']=$licenciavencidas;*/

        if ($this->session->userdata('is_admin') == "t") {
            $this->layout->js(base_url("/public/js/plugins/cleditor/jquery.cleditor.js"))
                ->js(base_url('public/js/plugins/multiselect/jquery.multi-select.min.js'))
                ->template('member')->show('frontend/config.php', array('data' => $data));
        } else {
            redirect(site_url('frontend/index'));
        }

    }

    public function update_table_selected()
    {
        $value = $this->input->post("table_selected");
        set_option('table_selected', $value);
    }

    public function creditCardPayment()
    {
        $usuario = 'vendtyMaster';
        $clave = 'ro_ar_8027*_na';
        $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
        $base_dato = 'vendty2';

        $conn = mysqli_connect($servidor, $usuario, $clave);
        mysqli_select_db($conn, $base_dato);

        $epayco = new Epayco\Epayco(array(
            "apiKey" => "2815e60ed8e00cbfc47180d0d37a7a6c",
            "privateKey" => "865298319cc8174ace617e0751f82c7a",
            "lenguage" => "ES",
            "test" => false,
        ));

        /** Data Tarjeta */
        $dataTarjeta = array(
            "card[number]" => trim(strval($_POST['creditCard']['card_number'])),
            "card[exp_year]" => trim(strval($_POST['creditCard']['card_exp_year'])),
            "card[exp_month]" => trim(strval($_POST['creditCard']['card_exp_month'])),
            "card[cvc]" => trim(strval($_POST['creditCard']['card_cvc'])),
        );

        $token = $epayco->token->create($dataTarjeta);

        if ($token->status != 1) {
            $sql = "INSERT INTO  `response` (`response`) value ('" . json_encode($token) . "')";
            $result = mysqli_query($conn, $sql);
            echo json_encode(array(
                "success" => false,
                "data" => $token,
                "log" => $result,
                "lugar" => "registro tarjeta",
            ));
            return 0;
        };
        /** Data Tarjeta */

        /** Data Customer */
        $customer = $epayco->customer->create(array(
            "token_card" => $token->id,
            "name" => trim(strval($_POST['client']['name'])),
            "email" => trim(strval($_POST['creditCard']['card_email'])),
            "default" => true,
            //Optional parameters: These parameters are important when validating the credit card transaction
            //"city" => $_POST['card_cvc'],
            //"address" => $_POST['card_cvc'],
            "phone" => $_POST['client']['phone'],
            "cell_phone" => $_POST['client']['phone'],
        ));

        if ($customer->status != 1) {
            $sql = "INSERT INTO  `response` (`response`) value ('" . json_encode($customer) . "')";
            $result = mysqli_query($conn, $sql);
            echo json_encode(array(
                "success" => false,
                "data" => $customer,
                "log" => $result,
                "lugar" => "registro usuario",
            ));
            return 0;
        };

        $customerId = $customer->data->customerId;
        mysqli_query($conn, "INSERT INTO `epayco_token_customers` (`id` ,`user_id` ,`token_id` ,`id_cliente`)VALUES (NULL , '" . $_POST['user_id'] . "', '$token->id', '$customerId');");
        /** Data Customer */

        /** Data Plan */
        $mensual = strpos($_POST['plan']['nombre_plan'], "MENSUAL");
        $periodo = $mensual !== false ? 'month' : 'year';

        $complemento = str_replace("-", " ", $_POST['licencia']);
        $complemento = str_replace("_", " ", $complemento);

        $nombrePlan = $_POST['plan']['nombre_plan'] . ' ' . $complemento;

        $idPlan = str_replace(" ", "_", $nombrePlan);

        $nuevoPlan = array(
            "id_plan" => trim($idPlan),
            "name" => $nombrePlan,
            "description" => $_POST['licencia'],
            "amount" => $_POST['plan']['valor_final'],
            "currency" => 'COP',
            "interval" => $periodo,
            "interval_count" => 1,
            "trial_days" => 0,
        );

        $plan = $epayco->plan->create($nuevoPlan);

        if ($plan->status != 1) {
            $sql = "INSERT INTO  `response` (`response`) value ('" . json_encode($plan) . "')";
            mysqli_query($conn, $sql);
            echo json_encode(array(
                "success" => false,
                "data" => $plan,
                "lugar" => "registro plan",
            ));
            return 0;
        };
        /** Data Plan */

        $dataSub = array(
            "id_plan" => $plan->data->id_plan,
            "customer" => $customer->data->customerId,
            "token_card" => $token->id,
            "doc_type" => trim(strval($_POST['client']['doc_type'])),
            "doc_number" => trim(strval($_POST['client']['doc_number'])),
        );

        $sub = $epayco->subscriptions->create($dataSub);

        if ($sub->status != 1) {
            $sql = "INSERT INTO  `response` (`response`) value ('" . json_encode($sub) . "')";
            mysqli_query($conn, $sql);
            echo json_encode(array(
                "success" => false,
                "data" => $sub,
                "lugar" => "registro sub",
            ));
            return 0;
        };

        $licencia = $_POST['licencia'];

        $sql = "INSERT INTO `epayco_suscripcion` (`id`,`suscripcion_id` ,`licencia`, `estado`, `pagada` )VALUES (NULL , '$sub->id', '$licencia', '$sub->status', false);";
        mysqli_query($conn, $sql);

        $dataPaySub = array(
            "id_plan" => $plan->data->id_plan,
            "customer" => $customer->data->customerId,
            "token_card" => $token->id,
            "doc_type" => trim(strval($_POST['client']['doc_type'])),
            "doc_number" => trim(strval($_POST['client']['doc_number'])),
            "address" => "cll 96 #13-31",
            "phone" => trim(strval($_POST['client']['phone'])),
            "cell_phone" => trim(strval($_POST['client']['phone'])),
            "url_confirmation" => $_POST['confirmation'],
            "url_response" => $_POST['confirmation'],
        );

        $paySub = $epayco->subscriptions->charge($dataPaySub);

        if (isset($paySub->status)) {
            $sql = "INSERT INTO  `response` (`response`) value ('" . json_encode($paySub) . "')";
            mysqli_query($conn, $sql);
            echo json_encode(array(
                "success" => false,
                "data" => $paySub,
                "lugar" => "registro pago",
            ));
            return 0;
        };

        $sqlUpdate = "UPDATE `epayco_suscripcion` SET `pagada` = $paySub->success WHERE `suscripcion_id` = '$sub->id';";

        mysqli_query($conn, $sqlUpdate);

        mysqli_close($conn);

        echo json_encode(array(
            "success" => true,
            "data" => $paySub,
            "confirmation" => $_POST['confirmation'],
        ));
        return 0;
    }

    public function accountPayment()
    {
        $numeroDeLicencias = $_POST['numeroDeLicencias'];

        $usuario = 'vendtyMaster';
        $clave = 'ro_ar_8027*_na';
        $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
        /*$usuario = 'root';
        $clave = '';
        $servidor = 'localhost';*/
        $base_dato = 'vendty2';

        $nombre = $_POST['client']['name'];
        $tipo_documento = $_POST['client']['doc_type'];
        $numero_documento = $_POST['client']['doc_number'];
        $email = $_POST['client']['email'];
        $tipo_cuenta = $_POST['bankAccount']['account_type'];
        $numero_cuenta = $_POST['bankAccount']['account_number'];
        $cod_entidad_bancaria = $_POST['bankAccount']['bank_entity'];
        $tipo_persona = $_POST['bankAccount']['person_type'];

        $confirmation = $_POST['confirmation'];

        $licencia = $_POST['licencia'];
        $plan = $_POST['plan'];
        $test = $_POST['test'];
        $user_id = $_POST['user_id'];

        $conn = @mysql_connect($servidor, $usuario, $clave);
        mysql_select_db($base_dato, $conn);

        $result = mysqli_query("INSERT INTO `cuentas_bancarias` (
            `id`,
            `user_id`,
            `nombre`,
            `tipo_documento`,
            `numero_documento`,
            `tipo_cuenta`,
            `numero_cuenta`,
            `cod_entidad_bancaria`
            )VALUES (
                NULL ,
                '" . $user_id . "',
                '" . $nombre . "',
                '" . $tipo_documento . "',
                '" . $numero_documento . "',
                '" . $tipo_cuenta . "',
                '" . $numero_cuenta . "',
                '" . $cod_entidad_bancaria . "'
                );", $conn);

        $epayco = new Epayco\Epayco(array(
            "apiKey" => "2815e60ed8e00cbfc47180d0d37a7a6c",
            "privateKey" => "865298319cc8174ace617e0751f82c7a",
            "lenguage" => "ES",
            "test" => false,
        ));

        $dataPago = array(
            "bank" => $cod_entidad_bancaria, //Banco exclusivo para pruebas "Banco Union Colombiano"
            "invoice" => time(),
            "description" => $licencia,
            "value" => $plan['valor_final'],
            "tax" => "0",
            "tax_base" => "0",
            "currency" => "COP",
            "type_person" => $tipo_persona,
            "doc_type" => $tipo_documento,
            "doc_number" => $numero_documento,
            "name" => explode(" ", $nombre)[0],
            "last_name" => explode(" ", $nombre)[1],
            "email" => $email,
            "country" => "CO",
            "cell_phone" => "3010000001",
            "url_response" => $confirmation,
            "url_confirmation" => $confirmation,
            "method_confirmation" => "POST",
        );

        $pse = $epayco->bank->create($dataPago);

        print_r($dataPago);
        print_r($pse);
        die();

        $sql = "INSERT INTO `epayco_suscripcion` (`id`,`suscripcion_id` ,`licencia`, `estado`, `pagada` )VALUES (NULL , '$sub->id', '$licencia', '$sub->status', false);";

        echo '<pre>';
        echo ('---------------------');
        echo '</pre>';
    }

    public function response()
    {
        $observacion = $_POST['x_response_reason_text'];
        $estado = $_POST['x_cod_response'];
        $referencia_aux = explode('Licencia Vendty', $_POST['x_description']);
        $licencia = explode('-', $referencia_aux[1]);

        if (count($licencia) != 1) {
            $id_licencia = array();
            $valor = array();
            $sw = 1;
            for ($x = 0; $x < count($licencia); $x++) {
                $v = explode('_', $licencia[$x]);
                array_push($id_licencia, $v[0]);
                array_push($valor, $v[1]);
            }
        } else {
            $sw = 0;
            $valor = $_POST['x_amount'];
            $v = explode('_', $referencia_aux[1]);
            $id_licencia = (count($v) != 1) ? $v[0] : $referencia_aux[1];
        }

        $estado = ($estado == 1) ? 1 : 3;
        $info_adicional = "response frontend";

        $this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, $sw);

        $bduser = $this->licencias->buscarBD($id_licencia);
        $idbd = $bduser[0]['id'];
        $this->licencias->updateEstadoBD2($idbd);

        if ($estado == 1) {
            require_once 'job.php';
            $email = new Job();
            $email->emailConfirmarPago($id_licencia);
        }

        redirect("frontend/index");
    }

    public function responseDos()
    {
        $observacion = $_POST['x_response_reason_text'];
        $estado = $_POST['x_cod_response'];
        $valor = $_POST['x_amount'];
        $referencia_aux = explode('Licencia Vendty', $_POST['x_description']);
        $referencia_aux = explode('-', $referencia_aux[1]);
        $id_licencia = $referencia_aux[0];
        $id_plan = $referencia_aux[1];

        $estado = ($estado == 1) ? 1 : 3;
        $info_adicional = "response2 frontend";

        if ($estado == 1) {

            $planActual = $this->licencias->getPlanActual($id_licencia);
            $this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, 0);
            $this->licencias->updateLicencia($id_licencia, $id_plan);

            //PASAR A PRODUCCIÓN
            $migrar = is_array($id_licencia) ? 1 : 0;

            if (($migrar == 0) && ($planActual == 1)) {

                $bduser = $this->licencias->buscarBD($id_licencia);
                $nombrebd = $bduser[0]['base_dato'];
                $idbd = $bduser[0]['id'];
                $email1 = explode("vendty2_db_", $nombrebd);
                //$this->licencias->produccion($nombrebd);
                $data = array(
                    'origen' => 2,
                    'destino' => 8,
                    'dbname' => $email1[1],
                );

                $migrada = post_curl('migraciondb', json_encode($data), $this->session->userdata('token_api'));

                if (isset($migrada->status) && isset($migrada->description)) {
                    if (!$migrada->status && $migrada->description == "Verifica los datos enviados") {
                        $migrada = post_curl('migraciondb', $data, $this->session->userdata('token_api'));
                    }
                } else {
                    $migrada = post_curl('migraciondb', $data, $this->session->userdata('token_api'));
                }

                if ($migrada->status) {
                    if ($migrada->description == 'ok') {
                        $this->licencias->updateEstadoBD($idbd);
                    }
                }
            }
            //email
            require_once 'job.php';
            $email = new Job();
            $email->emailConfirmarPago($id_licencia);
            $this->ion_auth->logout();
            redirect("auth/login");
        }

        redirect("frontend/index");
    }

    public function generaToken()
    {

        $monto = $this->input->post('monto');

        $key = "4Vj8eK4rloUd272L48hsrarnUA";
        $merchandid = "508029";
        $time = time();
        $hash = md5($key, $merchandid, $time, $monto, 'COP');

        $data['time'] = $time;
        $data['hash'] = $hash;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function wizard()
    {

        $convertloop = new \ConvertLoop\ConvertLoop("f5089529", "pkUU21crGXeEfVpDKZsTkVGJ", "v1");
        $person = array(
            "email" => $this->session->userdata('email'),
            "first_name" => $this->session->userdata('username'),
        );

        $convertloop->people()->createOrUpdate($person);
        $event = array(
            "name" => 'Prueba7Dias',
            "person" => $person,
            "ocurred_at" => time(),
        );

        $convertloop->eventLogs()->send($event);

        $getAlm = $this->input->get('alm');
        $getDia = $this->input->get('dia');

        $almacenActual = $this->dashboardModel->getAlmacenActual();

        $almacen = $getAlm == "" ? $almacenActual : $getAlm;
        $dias = $getDia == "" ? 7 : $getDia;

        //------------------

        $data = array();

        $data['almacen'] = $almacen;
        $data['dias'] = $dias;

        $data['usuario'] = $this->session->userdata('username');
        $data['almacenes'] = $this->dashboardModel->getAllAlmacenes();
        $data['empresa'] = $this->dashboardModel->get_data_empresa();

        $data['meta_diaria'] = $this->dashboardModel->get_meta_diaria($almacen);
        $data['meta_diaria']["total_ventas"] = $this->dashboardModel->getVentas($almacen, 0)[0]["total_venta"];
        $data['productos_relevantes_hoy'] = $this->dashboardModel->getProductosRelevantesHoy($almacen);
        $data['ventas'] = $ventas = $this->dashboardModel->getVentas($almacen, $dias);
        $data['utilidad'] = $utilidad = $this->dashboardModel->getUtilidad($almacen, $dias);
        $data['ventas_almacen'] = $this->dashboardModel->ventasPorAlmacen($almacen, $dias);
        $data['categorias_vendidas'] = $this->dashboardModel->categoriasMasVendidas($almacen, $dias);

        $prod_populares = $this->dashboardModel->productosMasPopulares($almacen, $dias);
        $stock_minimo = $this->dashboardModel->stockMinimo($almacen);

        //-----------------------------------------------------
        //   Acortando nombre con atributos muy largos
        //-----------------------------------------------------
        // PRODUCTOS POUPULARES
        foreach ($prod_populares as $key => $val) {
            $tmpArray = explode("/", $val["nombre"]);
            if (count($tmpArray) > 2) {
                $prod_populares[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
            }
        }

        // STOCK_MINIMO
        foreach ($stock_minimo as $key => $val) {
            $tmpArray = explode("/", $val["nombre"]);
            if (count($tmpArray) > 2) {
                $stock_minimo[$key]["nombre"] = $tmpArray[0] . "/" . $tmpArray[1] . "/" . $tmpArray[2];
            }
        }

        $data['prod_populares'] = $prod_populares;
        $data['stock_minimo'] = $stock_minimo;

        $idUsuario = $this->session->userdata('user_id');
        $data['zoho'] = $this->dashboardModel->getZoho($idUsuario);
        $data['estadoZoho'] = "no";

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();

        $estado = $cuentaEstado["estado"];
        $fecha = $cuentaEstado["fecha"];
        $data['estado'] = $estado;

        //RESTA DE FECHAS PARA VALIDAR QUE YA SE LE ACABO EL TIEMPO AL USUARIO
        date_default_timezone_set("America/Lima");
        $data['diasCuenta'] = $this->restarFechas($fecha, date('Y-m-d'));
        $data['diasCuentaDisponibles'] = 7 - $data['diasCuenta'];

        $data['offline'] = $this->input->get("offline");
        $this->layout->template('dashboard')->show('frontend/wizard.php', array('data' => $data));
    }

    public function info_negocio()
    {

        $tipo_negocio_especializado = $this->input->post('tipo_negocio_especializado');
        $identificacion_tributaria = $this->input->post('identificacion_tributaria');
        $pais_negocio = $this->input->post('pais_negocio');
        $provincia = $this->input->post('provincia');
        if (!empty($tipo_negocio_especializado) && (!empty($identificacion_tributaria)) && (!empty($pais_negocio)) && (!empty($provincia))) {
            $data = array(
                'id_db_config' => $this->session->userdata('db_config_id'),
                'tipo_negocio_especializado' => $tipo_negocio_especializado,
                'identificacion_tributaria' => $identificacion_tributaria,
                'pais_negocio' => $pais_negocio,
                'ciudad_negocio' => $provincia,
            );
            //insertar info
            $id = $this->crm_model->insert_actualizar_info_negocio($data);

            if (!empty($id)) {
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
            } else {
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
            }
        }
    }

    public function load_settings()
    {

        $data = json_decode(file_get_contents("php://input"), true);
        $response = $this->dashboardModel->load_settings($data);

        //send email congratulations
        $email = $data['email'];
        $dates = array(
            "email" => $email,
        );
        $html = $this->load->view("email/wizard_complete", $dates, true);
        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($email);
        $this->email->bcc('roxanna@vendty.com');
        $this->email->subject("VendTy Tu Punto de Venta en la nube");
        $this->email->message($html);
        $this->email->send();

        echo json_encode($response);
    }

    public function save_step()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->dashboardModel->save_step($data['step'], $data['type_business']);
    }

    public function skip_configuration()
    {
        $this->dashboardModel->skip_configuration();
        redirect('frontend');
    }

    public function init_configuration()
    {
        $this->dashboardModel->init_configuration();
        redirect('frontend');
    }

    public function responsepaypal()
    {

        $data = $_POST['data'];
        $referencia_aux = explode('Licencia Vendty', $_POST['referencia']);
        $licencia = explode('-', $referencia_aux[1]);
        $valor = $_POST['total'];
        $transaction_id = $data['id'];
        $ref_payco = $data['cart'];
        $status = $data['state'];
        $payer = $data['payer'];
        $payerinfo = $data['payer']['payer_info'];
        //$transactions=$data['transactions'][0]['amount'];
        $observacion = $status;
        $info_adicional = "Paypal - responsepaypal frontend";

        $nombre_user = $payerinfo['first_name'];
        $apellido_user = $payerinfo['last_name'];
        $tipo_documento_user = $payerinfo['payer_id'];
        $numero_documento_user = $payerinfo['payer_id'];
        $direccion_user = $payerinfo['shipping_address']['line1'] . " " . $payerinfo['shipping_address']['city'] . " " . $payerinfo['shipping_address']['state'];
        $telefono_user = "";
        $email_user = $payerinfo['email'];
        $total_pais = 0;
        $metodopago = "paypal";
        $pago_por = "paypal";
        $currency = "USD";

        if (count($licencia) != 1) {
            $id_licencia = array();
            $valor = array();
            $valor_dolares = array();
            $sw = 1;
            for ($x = 0; $x < count($licencia); $x++) {
                $v = explode('_', $licencia[$x]);
                array_push($id_licencia, $v[0]);
                //buscar el valor en pesos de la licencia
                /* $bduser=$this->licencias->buscarBD($v[0]);
                $idbd=$bduser[0]['id'];
                $almacen=$bduser[0]['id_almacen'];

                $licenciavp = $this->licenciasModel->by_id_config_almacen($idbd,$almacen);
                $va=$licenciavp['valor_plan'];
                $vd=$licenciavd['valor_plan_dolares'];*/

                array_push($valor_dolares, $v[2]);
                array_push($valor, $v[1]);
            }
        } else {
            $sw = 0;
            $valor_dolares = $_POST['total'];
            $v = explode('_', $referencia_aux[1]);
            $id_licencia = $v[0];
            $valor = $v[1];
            //buscar el valor en pesos de la licencia
            /*$bduser=$this->licencias->buscarBD($id_licencia);
        $idbd=$bduser[0]['id'];
        $almacen=$bduser[0]['id_almacen'];
        $licenciavp = $this->licenciasModel->by_id_config_almacen($idbd,$almacen);
        $valor=$licenciavp['valor_plan'];*/

        }

        if ($status == 'approved') {
            $estado = 1;
            $forma_pago = 6;
            //verifico si es un array o no la licencia
            $id_licencia_array = is_array($id_licencia) ? $id_licencia[0] : $id_licencia;

            //verificar si llego el pago
            $hoy = date('Y-m-d');
            $existe_pago = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia_array, 'estado_pago' => 1));

            if ($existe_pago == 0) {
                $pago = $this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, $sw, $transaction_id, $ref_payco, $forma_pago, $valor_dolares, $total_pais, $metodopago, $pago_por, $currency);

                $bduser = $this->licencias->buscarBD($id_licencia_array);
                $idbd = $bduser[0]['id'];
                $this->licencias->updateEstadoBD2($idbd);

                //**verifico si tiene informacion en crm_info_facturacion sino tomo los valores de epayco y los guardo */
                $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia_array));
                $empresa = $datos_licencia[0]->idempresas_clientes;
                $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));

                if (empty($datos_empresas)) {
                    //guardar la informacion del cliente que viene de epayco
                    $this->crm_empresas_clientes_model->update_info_factura_cliente(
                        array(
                            'nombre_empresa' => $nombre_user . " " . $apellido_user,
                            'tipo_identificacion' => $tipo_documento_user,
                            'numero_identificacion' => $numero_documento_user,
                            'direccion' => $direccion_user,
                            'telefono' => $telefono_user,
                            'correo' => $email_user,
                            'contacto' => $nombre_user . " " . $apellido_user,
                        ),
                        array('id_db_config' => $idbd)
                    );
                }
                //Cambiar las fechas de bodegas si las hubiera
                $sqlbodegas = "SELECT * FROM " . $bduser[0]['base_dato'] . ".almacen WHERE bodega=1";
                $bodegas = $this->db->query($sqlbodegas)->result_array();

                if ($sw != 0) {
                    $pagos = explode(",", $pago);
                    $cantidadPlanBodega = 0;
                    for ($x = 0; $x < count($id_licencia); $x++) {
                        $id = $id_licencia[$x];
                        $pago = $pagos[$x];

                        //cantidad de bodegas
                        $datos_licencia_b = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id));
                        $detalle_plan = $this->crm_model->get_detalle_plan("where id_plan=" . $datos_licencia_b[0]->planes_id . " and nombre_campo='bodegas'");
                        $cantidadPlanBodega += (!empty($detalle_plan[0]->valor)) ? $detalle_plan[0]->valor : 0;

                        require_once 'job.php';
                        $email = new Job();
                        $email->emailConfirmarPago($id); //envio pago

                        //verificar nuevamente que no haya factura asociada
                        $existe_pago2 = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id, 'estado_pago' => 1, 'id_factura_licencia !=' => ''));
                        if ($existe_pago2 == 0) {
                            //generando la factura
                            require_once 'administracion_vendty/facturas_licencia.php';
                            $factura = new Facturas_licencia();
                            /*if($idbd == '18318') {
                            $facturag = $factura->generar_factura_electronica($id,$pago);
                            } else {
                            $facturag=$factura->generar_factura_de_licencia($id,$pago);
                            }*/
                            $facturag = $factura->generar_factura_electronica($id, $pago);

                            if (!empty($facturag)) {

                                $email->emailFacturaPago($facturag);
                            }
                        }
                    }
                    //actualizar fechas bodegas
                    if (!empty($bodegas)) {
                        $i = 0;
                        //busco licencia asociada a ese almacen
                        foreach ($bodegas as $key => $value) {
                            if ($i < $cantidadPlanBodega) {
                                //licencias asociadas
                                $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config' => $idbd, 'id_almacen' => $value['id']));

                                if (empty($datos_licencia_bodegas)) { //se crea la licencia asociada
                                    $planB = 17;
                                    switch ($datos_licencia[0]->dias_vigencia) {
                                        case 30:
                                            $planB = 17;
                                            break;
                                        case 90:
                                            $planB = 16;
                                            break;
                                        case 365:
                                            $planB = 15;
                                            break;
                                        default:
                                            $planB = 16;
                                            break;
                                    }

                                    $datosli = array(
                                        'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                        , 'planes_id' => $planB
                                        , 'fecha_creacion' => date('Y-m-d H:i:s')
                                        , 'creado_por' => $datos_licencia[0]->creado_por
                                        , 'fecha_inicio_licencia' => $datos_licencia[0]->fecha_inicio_licencia
                                        , 'fecha_vencimiento' => $datos_licencia[0]->fecha_vencimiento
                                        , 'id_db_config' => $idbd
                                        , 'id_almacen' => $value['id']
                                        , 'estado_licencia' => 1,
                                    );
                                    $this->crm_licencia_model->agregar_licencia($datosli);
                                } else {
                                    //cambiar las fechas
                                    $datosli = array(
                                        'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                        , 'fecha_inicio_licencia' => $datos_licencia[0]->fecha_inicio_licencia
                                        , 'fecha_vencimiento' => $datos_licencia[0]->fecha_vencimiento
                                        , 'fecha_modificacion' => date('Y-m-d H:i:s')
                                        , 'estado_licencia' => 1,
                                    );
                                    $this->crm_licencias_empresa_model->update($datosli);
                                }
                            }
                            $i++;
                        }
                    }

                } else {

                    require_once 'job.php';
                    $email = new Job();
                    $email->emailConfirmarPago($id_licencia);

                    //generando la factura
                    require_once 'administracion_vendty/facturas_licencia.php';
                    $existe_pago2 = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia_array, 'estado_pago' => 1, 'id_factura_licencia !=' => ''));

                    if ($existe_pago2 == 0) {
                        $factura = new Facturas_licencia();
                        /*if($idbd == '18318') {
                        $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                        } else {
                        $facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                        }*/
                        $facturag = $factura->generar_factura_electronica($id_licencia, $pago);

                        if (!empty($facturag)) {
                            $email->emailFacturaPago($facturag);

                            //cambiar las fechas de licencias de bodegas
                            if (!empty($bodegas)) {
                                //busco detalle del plan
                                $detalle_plan = $this->crm_model->get_detalle_plan("where id_plan=" . $datos_licencia[0]->planes_id . " and nombre_campo='bodegas'");
                                $i = 0;
                                $cantidadPlanBodega = !empty($detalle_plan[0]->valor) ? $detalle_plan[0]->valor : 0;
                                //busco licencia asociada a ese almacen
                                foreach ($bodegas as $key => $value) {

                                    if ($i < $cantidadPlanBodega) {
                                        //licencias asociadas
                                        $datos_licencia_bodegas = $this->crm_licencia_model->get_licencias(array('id_db_config' => $idbd, 'id_almacen' => $value['id']));

                                        if (empty($datos_licencia_bodegas)) { //se crea la licencia asociada
                                            $planB = 17;
                                            switch ($datos_licencia[0]->dias_vigencia) {
                                                case 30:
                                                    $planB = 17;
                                                    break;
                                                case 90:
                                                    $planB = 16;
                                                    break;
                                                case 365:
                                                    $planB = 15;
                                                    break;
                                                default:
                                                    $planB = 16;
                                                    break;
                                            }

                                            $datosli = array(
                                                'idempresas_clientes' => $datos_licencia[0]->idempresas_clientes
                                                , 'planes_id' => $planB
                                                , 'fecha_creacion' => date('Y-m-d H:i:s')
                                                , 'creado_por' => $datos_licencia[0]->creado_por
                                                , 'fecha_inicio_licencia' => $datos_licencia[0]->fecha_inicio_licencia
                                                , 'fecha_vencimiento' => $datos_licencia[0]->fecha_vencimiento
                                                , 'id_db_config' => $idbd
                                                , 'id_almacen' => $value['id']
                                                , 'estado_licencia' => 1,
                                            );
                                            $this->crm_licencia_model->agregar_licencia($datosli);
                                        } else {
                                            //cambiar las fechas
                                            $datosli = array(
                                                'idlicencias_empresa' => $datos_licencia_bodegas[0]->idlicencias_empresa
                                                , 'fecha_inicio_licencia' => $datos_licencia[0]->fecha_inicio_licencia
                                                , 'fecha_vencimiento' => $datos_licencia[0]->fecha_vencimiento
                                                , 'fecha_modificacion' => date('Y-m-d H:i:s')
                                                , 'estado_licencia' => 1,
                                            );

                                            $this->crm_licencias_empresa_model->update($datosli);
                                        }
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
        }
    }

    public function responseDospaypal()
    {

        $referencia_aux = explode('Licencia Vendty', $_POST['referencia']);
        $referencia_aux = explode('-', $referencia_aux[1]);
        $id_licencia = $referencia_aux[0];
        $id_plan = $referencia_aux[1];
        $valor = $referencia_aux[2];
        $data = $_POST['data'];
        $transaction_id = $data['id'];
        $ref_payco = $data['cart'];
        $status = $data['state'];
        $observacion = $status;
        $info_adicional = "Paypal - responseDospaypal frontend";
        $payerinfo = $data['payer']['payer_info'];
        $nombre_user = $payerinfo['first_name'];
        $apellido_user = $payerinfo['last_name'];
        $tipo_documento_user = $payerinfo['payer_id'];
        $numero_documento_user = $payerinfo['payer_id'];
        $direccion_user = $payerinfo['shipping_address']['line1'] . " " . $payerinfo['shipping_address']['city'] . " " . $payerinfo['shipping_address']['state'];
        $telefono_user = "";
        $email_user = $payerinfo['email'];
        $valor_dolares = $_POST['total'];
        $forma_pago = 6;
        $payer = $data['payer'];
        $total_pais = 0;
        $metodopago = "paypal";
        $pago_por = "paypal";
        $currency = "USD";

        //$transactions=$data['transactions'][0]['amount'];

        if ($status == 'approved') {
            $estado = 1;
            //veri
            $hoy = date('Y-m-d');
            $existe_pago = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia, 'estado_pago' => 1));

            if ($existe_pago == 0) {
                //email
                require_once 'job.php';
                $email = new Job();

                $planActual = $this->licencias->getPlanActual($id_licencia);
                $pago = $this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, 0, $transaction_id, $ref_payco, $forma_pago, $valor_dolares, $total_pais, $metodopago, $pago_por, $currency);

                //PASAR A PRODUCCIÓN
                $migrar = is_array($id_licencia) ? 1 : 0;

                if (($migrar == 0) && ($planActual == 1)) {

                    $bduser = $this->licencias->buscarBD($id_licencia);
                    $nombrebd = $bduser[0]['base_dato'];
                    $idbd = $bduser[0]['id'];
                    $email1 = explode("vendty2_db_", $nombrebd);
                    //$this->licencias->produccion($nombrebd);
                    $data = array(
                        'origen' => 2,
                        'destino' => 8,
                        'dbname' => $email1[1],
                    );

                    $migrada = post_curl('migraciondb', json_encode($data), $this->session->userdata('token_api'));

                    if (isset($migrada->status) && isset($migrada->description)) {
                        if (!$migrada->status && $migrada->description == "Verifica los datos enviados") {
                            $migrada = post_curl('migraciondb', $data, $this->session->userdata('token_api'));
                        }
                    } else {
                        $migrada = post_curl('migraciondb', $data, $this->session->userdata('token_api'));
                    }

                    if ($migrada->status) {
                        if ($migrada->description == 'ok') {
                            $this->licencias->updateEstadoBD($idbd);
                            //modifico las fechas licencia
                            $plan = $this->crm_model->get_planes(array('id' => $id_plan));
                            $tiempo = $plan[0]->dias_vigencia;
                            $this->licencias->updateLicencianuevo($id_licencia, $id_plan, $tiempo);

                            //email de bienvenida
                            $email->BienvenidoaVendty($idbd);
                        }
                    }
                }

                //**tomo los valores de epayco y los guardo */
                $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia));
                $empresa = $datos_licencia[0]->idempresas_clientes;
                $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));
                //buscar tipo negocio
                //$sqlinsertdb="SELECT valor_opcion FROM ".$nombrebd.".opciones WHERE nombre_opcion='tipo_negocio'";
                //$sqlinsertdb=$this->db->query($sqlinsertdb)->result_array();
                //$tipo_negocio=$sqlinsertdb[0]['valor_opcion'];
                //$datos_bd_acti=array();
                if (empty($datos_empresas)) {
                    //guardar la informacion del cliente que viene de epayco
                    $this->crm_empresas_clientes->update_info_factura_cliente(
                        array(
                            'nombre_empresa' => $nombre_user . " " . $apellido_user,
                            'tipo_identificacion' => $tipo_documento_user,
                            'numero_identificacion' => $numero_documento_user,
                            'direccion' => $direccion_user,
                            'telefono' => $telefono_user,
                            'correo' => $email_user,
                            'contacto' => $nombre_user . " " . $apellido_user,
                        ),
                        array('id_db_config' => $idbd)
                    );
                }
                // insertar datos en bd_activa
                // $this->crm_model->insert_db_activa_info($datos_bd_acti);

                //email pago
                $email->emailConfirmarPago($id_licencia);
                //generando la factura
                $existe_pago2 = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia, 'estado_pago' => 1, 'id_factura_licencia !=' => ''));

                if ($existe_pago2 == 0) {
                    require_once 'administracion_vendty/facturas_licencia.php';
                    $factura = new Facturas_licencia();
                    /*if($idbd == '18318') {
                    $facturag = $factura->generar_factura_electronica($id_licencia,$pago);
                    } else {
                    $facturag=$factura->generar_factura_de_licencia($id_licencia,$pago);
                    }*/
                    $facturag = $factura->generar_factura_electronica($id_licencia, $pago);
                    if (!empty($facturag)) {
                        $email->emailFacturaPago($facturag);
                    }
                }

                $this->ion_auth->logout();
                $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 1)));
                //redirect("auth/login");
            }
        } else {
            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => 0)));
        }
    }

    public function reiniciar()
    {
        echo $this->ventas->reiniciar();
    }

    /**
     *
     */
    public function dash()
    {
        session_start();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if (isset($_SESSION['api_auth'])) {
            $apiAuth = json_decode($_SESSION['api_auth'], true);
        }

        if (isset($apiAuth['error'])) {
            if ($apiAuth['code'] == 'SUBSCRIPTION-EXPIRED' || $apiAuth['code'] == 'SUBSCRIPTION-FREE-EXPIRED') {
                $this->layout->template('login')->show('licenciasvencidas');
                $html = $this->load->view('licenciasvencidas', $apiAuth['data'], true);
                echo $html;
                exit;
            }
        }

        $fecha = date('Y-m-d');

        $getAlm = $this->input->get('alm');
        $getDia = $this->input->get('dia');

        $almacenActual = $this->dashboardModel->getAlmacenActual();

        $diacuentasnuevas = $this->crm_model->getdiascuentaprueba();
        $diacuentasnuevas = $diacuentasnuevas[0]['dias'];
        $almacen = $getAlm == "" ? $almacenActual : $getAlm;
        //$dias = $getDia == "" ? 14 : $getDia;
        $dias = $getDia == "" ? $diacuentasnuevas : $getDia;

        /************Comprobar que esta activa la licencia usuarios admin*****************/
        $administrador = $this->session->userdata('is_admin');
        // $db_config_id_user = $this->session->userdata('db_config_id');
        // $cantlicenciastodas = 0;
        // $cantlicenciasdesactivadas = 0;
        // $cantlicenciasvencidasnodesactivadas = 0;
        // $licencias = array();

        if (($administrador == 't')) {
            $fecha = date('Y-m-d');
            // $almacenActual = $this->dashboardModel->getAlmacenActual();

            /*
            if ($almacenActual == 0) {
            $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
            }
             */
            // $milicencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacenActual);
            //tengo mas de una licencia?
            //busco todas las licencias que tengo
            // $licenciastu = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) ", false);

            //busco todas mis licencias asociadas vencidas que no esten desactivadas
            // $licencias = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) AND (estado_licencia=15 OR fecha_vencimiento < '$fecha') and desactivada !=1  ", false);

            // $licenciasdesactivadas = $this->licenciasModel->by_id_config_estado_licencias("where id_db_config=$db_config_id_user AND id_plan not in(15,16,17) AND (estado_licencia=15 OR fecha_vencimiento < '$fecha') and desactivada=1", false);

            // $cantlicenciastodas = count($licenciastu);
            // $cantlicenciasdesactivadas = count($licenciasdesactivadas);
            // $cantlicenciasvencidasnodesactivadas = count($licencias);

            /*
        $todasvencidasdespues = $cantlicenciastodas;

        foreach ($licenciastu as $value) {

        $fecha_nueva4 = $value['fecha_vencimiento'];

        $fecha_nueva4 = date("Y-m-d", strtotime($fecha_nueva4 . "+ 14 days"));

        if ($fecha_nueva4 >= '2019-05-02') {
        $fecha_nueva4 = '2019-05-01';
        }

        $hoy4 = date("Y-m-d");
        if ($fecha_nueva4 >= $hoy4) {
        $todasvencidasdespues--;
        }
        }
         */

        }

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $estado = $cuentaEstado["estado"]; //estado bd

        //verificar si es de prueba
        /*
        if (!empty($licencias) && $estado == 2) {

        if ((($licencias[0]['fecha_vencimiento'] < $fecha) || ($licencias[0]['estado_licencia'] == 15)) && (($administrador == 't'))) {

        $licencia = $this->licenciasModel->by_id_config_almacen($db_config_id_user, $almacen);
        $data['title'] = "TU SUSCRIPCIÓN GRATUITA A EXPIRADO";
        $data['message'] = "Selecciona el plan que más se adapte a tu negocio";
        $data['message1'] = " ";
        $data['message2'] = '';
        $data['message3'] = '2';
        $data['mostrar_salir'] = true;
        $data['logout'] = true;
        $data['licencias'] = $licencia;
        $data['almacentodos'] = $this->almacenes->getAll();
        $data['planes'] = $this->licencias->get_planes();
        $planesid = " where id_plan in(";
        $id = "";

        foreach ($data['planes'] as $plan) {
        $id .= "," . $plan["id"];
        }

        $planesid .= trim($id, ",") . ")";
        $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

        foreach ($data['planes'] as $key => $plan) {
        foreach ($data['detalles_planes'] as $detalle) {

        if (($detalle->id_plan == $plan["id"])) {
        $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
        }
        }
        }

        $data['config'] = true;
        $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura_pais'] = $this->clientes->get_pais();
        $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();
        $this->layout->template('login')->show('licenciasvencidas');
        $html = $this->load->view('licenciasvencidas', $data, true);
        echo $html;
        exit;
        }
        }
         */

        //si no soy prueba y soy admin y tengo por lo menos una licencia asociada que esta venciada y
        /*
        if ($administrador == 't') {
        //verificar si aun estoy dentro de los 7 dias adicionales

        $fecha_nueva3 = $milicencia['fecha_vencimiento'];
        $fecha_nueva3 = date("Y-m-d", strtotime($fecha_nueva3 . "+ 14 days"));
        $hoy3 = date('Y-m-d');
        if ($fecha_nueva3 >= '2019-05-02') {
        $fecha_nueva3 = '2019-05-01';
        }

        $mensaje = "";
        // $mensaje2 = "";
        $vencidadespues = false;
        if ($fecha_nueva3 < $hoy3) {
        $vencidadespues = true;

        } else {

        // $date1 = new DateTime($fecha_nueva3);
        // $date2 = new DateTime($hoy3);
        // $diff1 = $date1->diff($date2);
        // $dias2 = date_diff($date1, $date2);
        }

        if ((((!empty($licencias)) || ($cantlicenciastodas == ($cantlicenciasvencidasnodesactivadas + $cantlicenciasdesactivadas))))) {

        $data['title'] = "SUSCRIPCIÓN VENCIDA";
        $data['message'] = "Su suscripción está vencida. Si gusta seguir disfutando de Vendty, debe realizar su pago ";
        $data['message1'] = "";
        $data['message2'] = ' ';
        $data['message3'] = '';
        $data['logout'] = true;    //para mostrar el boton de inicio =true
        $data['licencias'] = $licencias;
        $data['mostrar_salir'] = true;
        $data['almacentodos'] = $this->almacenes->getAll();
        $data['planes'] = $this->licencias->get_planes();
        $planesid = " where id_plan in(";
        $id = "";

        foreach ($data['planes'] as $plan) {
        $id .= "," . $plan["id"];
        }

        $planesid .= trim($id, ",") . ")";
        $data['detalles_planes'] = $this->crm_model->get_detalle_plan($planesid);

        foreach ($data['planes'] as $key => $plan) {
        foreach ($data['detalles_planes'] as $detalle) {

        if (($detalle->id_plan == $plan["id"])) {
        $data['planes'][$key][$detalle->nombre_campo] = $detalle->valor;
        }
        }
        }

        $data['config'] = true; //para que salga el boton pagar =true
        if ($vencidadespues) {
        $data['btnconfig'] = false; //muestra boton de configuraciones =true
        } else {
        $data['btnconfig'] = true; //muestra boton de configuraciones =true
        }

        $data['admin_licencia_vencida'] = true;
        $data['todas_vencidas'] = false;
        //tengo todas las licencia asociadas vencida
        //saber si tengo más de una licencia y no estan todas vencidas

        if (($cantlicenciastodas > 1) && (($cantlicenciastodas) != ($cantlicenciasvencidasnodesactivadas + $cantlicenciasdesactivadas))) {
        $data['btnconfig'] = true; //muestra boton de configuraciones =true
        $data['mostrar_salir'] = false;
        $data['message'] = "Tiene suscripciones vencidas. <br>Si gusta seguir disfutando de Vendty, debe realizar el pago";
        } else {
        if (count($licenciastu) > 1) {
        $data['message'] = "Tiene suscripciones vencidas." . $mensaje;
        }
        }

        $almacenes_vencidos = array();

        foreach ($licencias as $licencia_vencida) {
        array_push($almacenes_vencidos, $licencia_vencida["id_almacen"]);
        }

        $this->session->set_userdata('licencia_admin_vencida', $almacenes_vencidos);

        $data['info_factura'] = $this->crm_empresas_clientes_model->get_by_id_cliente_config(array('id_db_config' => $this->session->userdata('db_config_id')));
        $data['info_factura_pais'] = $this->clientes->get_pais();
        $data['info_factura_tipo_identificacion'] = $this->clientes->get_tipo_identificacion();

        if ($cantlicenciastodas == $todasvencidasdespues) {
        $this->layout->template('login')->show('licenciasvencidas');
        $html = $this->load->view('licenciasvencidas', $data, true);
        echo $html;
        exit;
        }
        }
        }
         */
        //------------------

        $data = array();
        $data['dias_licencia'] = null;
        // $data['fecha_vencimiento'] = null;
        // $data['valor_renovacion'] = null;
        // $data['almacen'] = $almacen;
        $data['dias'] = $dias;
        // $recordarplan = 7;

        // $data['usuario'] = $this->session->userdata('username');
        // $data_empresa = $this->mi_empresa->get_data_empresa();
        // $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
        // $datos_ventas = $this->dashboardModel->getVentas($almacen, 0, 0);

        // $idUsuario = $this->session->userdata('user_id');
        // $data['zoho'] = $this->dashboardModel->getZoho($idUsuario);
        // $data['estadoZoho'] = "no";

        // $cuentaEstado = $this->newAcountModel->getUsuarioEstado();

        // $estado = $cuentaEstado["estado"];
        // $fecha = $cuentaEstado["fecha"];

        //SI ES TOTALMENTE NUEVO EL USUARIO, SE REDIRECCIONAREMOS A ZOHO Y A GRACIAS
        if ($estado == "4") {

            // $zohoData = $this->session->userdata('nuevoCliente');
            // $this->layout->template('ajax')->show('frontend/zoho.php', array('data' => $zohoData));

            // Si esta en estado de configuracion
            // $this->wizard();

        } else {

            $data['estado'] = $estado;

            //RESTA DE FECHAS PARA VALIDAR QUE YA SE LE ACABO EL TIEMPO AL USUARIO
            date_default_timezone_set("America/Lima");
            $data['diasCuenta'] = $this->restarFechas($fecha, date('Y-m-d'));
            $data['diasCuentaDisponibles'] = 7 - $data['diasCuenta'];
            $data['offline'] = $this->input->get("offline");

            // Si no ha completado la configuracion se envia a Wizard
            if ($estado == 3) {
                $this->wizard();
            } else {

                $data['dias_adicionales'] = 0;

                if (($administrador == 't') || ($administrador == 'a')) {
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id')), array('1', '15', '16', '17'));
                } else {
                    if ($almacen == 0) {
                        $al = $this->almacenes->getAll();
                        $almacen = ($this->dashboardModel->getAlmacenActuallicencias() == 0) ? 1 : $al[0]->id;
                    }
                    $datos_vencimiento = $this->crm_model->get_usuario_renovacion(array('id_db_config' => $this->session->userdata('db_config_id'), 'id_almacen' => $almacen));
                }
                if (count($datos_vencimiento) > 0) {
                    /*$datetime1 = new DateTime($datos_vencimiento[0]->fecha_vencimiento);
                    $datetime2 = new DateTime('2019-05-01');
                    $interval = $datetime1->diff($datetime2);
                    $diasn= $interval->format('%a');*/

                    $diacuentasnuevas = $this->crm_model->getdiascuentaprueba();
                    $diacuentasnuevas = $diacuentasnuevas[0]['dias'];
                    $datos_plan = $this->crm_model->get_planes(array('id' => $datos_vencimiento[0]->id_plan));
                    $recordarplan = $datos_plan[0]->comienzo_dias_recordacion;
                    $data['nombre_plan'] = $datos_plan[0]->nombre_plan;
                    //$data['fecha_extendida']= date("Y-m-d",strtotime($datos_vencimiento[0]->fecha_vencimiento."+ 14 days"));
                    $data['fecha_extendida'] = date("Y-m-d", strtotime($datos_vencimiento[0]->fecha_vencimiento . "+ " . $diacuentasnuevas . " days"));
                    if ($data['fecha_extendida'] >= '2019-05-02') {
                        $data['fecha_extendida'] = '2019-05-01';
                    }
                    $hoy = date_create(date("y-m-d"));
                    $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                    $dias = date_diff($hoy, $fecha_vencimiento);
                    //verificar si aun estoy dentro de los 7 dias adicionales
                    $fecha_nueva = $datos_vencimiento[0]->fecha_vencimiento;
                    /*$datetime1 = new DateTime($fecha_nueva);
                    $datetime2 = new DateTime('2019-05-01');
                    $interval = $datetime1->diff($datetime2);
                    $diasn= $interval->format('%a');
                    $fecha_nueva= date("Y-m-d",strtotime($fecha_nueva."+ ".$diasn." days")); */
                    $fecha_nueva = date("Y-m-d", strtotime($fecha_nueva . "+ " . $diacuentasnuevas . " days"));
                    $hoy2 = date("Y-m-d");
                    if ($fecha_nueva >= '2019-05-02') {
                        $fecha_nueva = '2019-05-01';
                    }
                    if ($datos_vencimiento[0]->fecha_vencimiento < $hoy2) {
                        $data['dias_adicionales'] = 1;
                        $date1 = new DateTime($fecha_nueva);
                        $date2 = new DateTime($hoy2);
                        $diff1 = $date1->diff($date2);
                        $dias = date_diff($date1, $date2);
                        // will output 2 days
                        //echo $dias->format("%a");
                    }

                    //$data['dias_licencia'] = $dias->format("%R%a");
                    $data['dias_licencia'] = $dias->format("%a");
                    $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                    $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                }

                ////verifico los dias para mostrar alerta de vencimiento

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
                if ($data["tipo_negocio"] == "restaurante") {
                    $almacenActual = $this->dashboardModel->getAlmacenActual();
                    $data["zonas"] = $this->secciones_almacen->get_secciones_almacen('b.nombre != "" and a.activo=1 AND id_almacen=' . $almacenActual . ' AND a.id != -1');
                    $data["mesas_secciones"] = $this->secciones_almacen->get_mesas_secciones();

                    $pedidos = 0;
                    foreach ($data["mesas_secciones"] as $key) {
                        $pedidos = $this->ordenes->verificaEstado($key->id_seccion, $key->id);
                        $key->pedidos = $pedidos;

                        $fechaCreacion = $this->ordenes->getFechaOrdenMesa($key->id_seccion, $key->id);

                        $key->fecha_creacion = !empty($fechaCreacion['created_at']) ? $fechaCreacion['created_at'] : '';
                    }

                }

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data['permitir_formas_pago_pendiente'] = $data_empresa['data']['permitir_formas_pago_pendiente'];

                //formas de pagos
                // $data['forma_pago'] = $this->forma_pago->getActiva();

                $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();
                $data['stores_avaibles'] = $this->dashboardModel->get_stores_avaibles();
                $data['type_licence'] = $this->dashboardModel->get_type_licence();

                $this->layout->template('dashboard')->show('frontend/index.php', array('data' => $data));
            }
        }
    }

    public function zones()
    {
        /************Comprobar que esta activa la licencia usuarios admin*****************/
        $administrador = $this->session->userdata('is_admin');

        if (($administrador == 't')) {
            $fecha = date('Y-m-d');
        }

        $cuentaEstado = $this->newAcountModel->getUsuarioEstado();
        $estado = $cuentaEstado["estado"]; //estado bd

        $data = array();
        $data['dias_licencia'] = null;

        //SI ES TOTALMENTE NUEVO EL USUARIO, SE REDIRECCIONAREMOS A ZOHO Y A GRACIAS
        if ($estado == "4") {
            //
        } else {

            $data['estado'] = $estado;

            //RESTA DE FECHAS PARA VALIDAR QUE YA SE LE ACABO EL TIEMPO AL USUARIO
            date_default_timezone_set("America/Lima");
            $fecha = date('Y-m-d');
            $data['diasCuenta'] = $this->restarFechas($fecha, date('Y-m-d'));
            $data['diasCuentaDisponibles'] = 7 - $data['diasCuenta'];
            $data['offline'] = $this->input->get("offline");

            // Si no ha completado la configuracion se envia a Wizard
            if ($estado == 3) {
                $this->wizard();
            } else {

                ////verifico los dias para mostrar alerta de vencimiento

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];
                if ($data["tipo_negocio"] == "restaurante") {
                    $almacenActual = $this->dashboardModel->getAlmacenActual();
                    $data["zonas"] = $this->secciones_almacen->get_secciones_almacen('b.nombre != "" and a.activo=1 AND id_almacen=' . $almacenActual . ' AND a.id != -1');
                    $data["mesas_secciones"] = $this->secciones_almacen->get_mesas_secciones();

                    $pedidos = 0;
                    foreach ($data["mesas_secciones"] as $key) {
                        $pedidos = $this->ordenes->verificaEstado($key->id_seccion, $key->id);
                        $key->pedidos = $pedidos;

                        $fechaCreacion = $this->ordenes->getFechaOrdenMesa($key->id_seccion, $key->id);

                        $key->fecha_creacion = !empty($fechaCreacion['created_at']) ? $fechaCreacion['created_at'] : '';
                    }

                }

                $data_empresa = $this->mi_empresa->get_data_empresa();
                $data['permitir_formas_pago_pendiente'] = $data_empresa['data']['permitir_formas_pago_pendiente'];

                //formas de pagos
                $data['forma_pago'] = $this->forma_pago->getActiva();
                $data['impuesto'] = $this->impuestos->getFisrt();
                $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];
                $data['simbolo'] = $data_empresa['data']['simbolo'];
                $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];
                $data['type_licence'] = $this->dashboardModel->get_type_licence();
                $data['stores_avaibles'] = $this->dashboardModel->get_stores_avaibles();
                $data['steps_complete'] = $this->dashboardModel->get_complete_steps();
                //print_r($data['tipo_negocio_especializado']); die();

                $this->layout->template('dashboard')->show('frontend/dashboard/zones.php', array('data' => $data));
            }
        }
    }

    /**
     *
     */
    public function iframe()
    {
        $this->load->view('frontend/dashboard/index');
    }

    /**
     * Devolvemos todo el objeto del login del API
     *
     * @author Rafael Moreno
     */
    public function get_api_auth()
    {
        session_start();
        echo $_SESSION['api_auth'];
    }
    public function change_logo()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }
        if (isset($_FILES['croppedImage'])) {
            //var_dump($_FILES['croppedImage']);

            // MARK : Almacenamiento de nuevo logo 
            $base_dato = $this->session->userdata('base_dato');

            $input = $this->s3->inputFile($_FILES['croppedImage']['tmp_name'], true);
            
            
            $image_name = date('U') . ".jpg";
            $carpeta = $base_dato.'/'.$image_name;
            $fn = $_FILES['croppedImage']['tmp_name']; 
            
            $object = $this->s3->putObject($input, 'vendty-img', $carpeta, 'private', []);
            

            move_uploaded_file($_FILES['croppedImage']["tmp_name"], "uploads/" . $image_name);

        }

        $this->mi_empresa->update_data_empresa(array(
            'logotipo_empresa' => isset($image_name) ? $image_name : '',
        ));
        $data = array('status' => true);
        echo json_encode($data);
    }
}
