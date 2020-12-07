<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/facebook/facebook.php';
require_once APPPATH . 'libraries/google/Google_Client.php';
require_once APPPATH . 'libraries/google/contrib/Google_Oauth2Service.php';

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('backend/db_config/db_config_model', "dbconfig");
        $this->load->model('ion_auth_model');
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $usuario = 'vendtyMaster';
        $clave = 'ro_ar_8027*_na';
        $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
        
        $base_dato = 'vendty2';
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        $this->dbConnectionv2 = $this->load->database($dns, true);

        $this->load->model('pais_model', 'pais');
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnectionv2);

        $this->load->model("crm_facturas_model", 'facturas');
        $this->facturas->initialize($this->dbConnection);

        $this->load->model("crm_model", 'crm_model');
        $this->load->model("crm_empresas_clientes_model", 'crm_empresas_clientes');
        $this->load->model("crm_licencia_model", 'crm_licencia_model');
        $this->load->model("crm_licencias_empresa_model", 'crm_licencias_empresa');
        $this->load->model("licencias_model", 'licenciasModel');
        $this->load->model("licencia_model", 'licencias');

        $this->load->model("new_count_model", 'newAcountModel');
        $this->newAcountModel->initialize($this->dbConnectionv2);

        $this->load->model('crm_empresas_clientes_model');
        $this->load->model('primeros_pasos_model');

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnectionv2);

        $this->load->model("licencia_model", 'licencias');
        $this->licencias->initialize($this->dbConnectionv2);
    }

    /*** Pagos */
    public function responseRecurrentPayment()
    {
        try {
            $usuario = 'vendtyMaster';
            $clave = 'ro_ar_8027*_na';
            $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
            $base_dato = 'vendty2';
            $conn = @mysql_connect($servidor, $usuario, $clave);

            mysql_select_db($base_dato, $conn);
            $response = json_encode($_POST);
            mysql_query("INSERT INTO `response` (`response`)VALUES ('$response');", $conn);
            $id_suscripcion = explode('-', $_POST['x_id_factura'])[0];
            $sql = "SELECT * FROM epayco_suscripcion WHERE suscripcion_id = '$id_suscripcion' LIMIT 1;";
            $result = mysql_query($sql, $conn);

            while ($row = mysql_fetch_array($result)) {
                $licenciaGuardada = $row["licencia"];
            }

            $plan = "SELECT id FROM crm_planes WHERE nombre_plan = '" . $_POST['x_description'] . "' LIMIT 1";
            $result = mysql_query($plan, $conn);

            while ($row = mysql_fetch_array($result)) {
                $idplan = $row["id"];
            }

            mysql_close($conn);

            var_dump($_POST);
            echo '<br>';

            var_dump($licenciaGuardada);
            echo '<br>';

            $observacion = $_POST['x_response_reason_text'];
            $estado = $_POST['x_cod_response'];
            $referencia_aux = explode('Licencia Vendty', $licenciaGuardada); // 8701_35_90000-8177_35_90000
            $licencia = explode('-', $referencia_aux[1]); // [8701_35_90000, 8177_35_90000]
            $tipo_documento_user = $_POST['x_customer_doctype'];
            $numero_documento_user = $_POST['x_customer_document'];
            $nombre_user = $_POST['x_customer_name'];
            $apellido_user = $_POST['x_customer_lastname'];
            $email_user = $_POST['x_customer_email'];
            $telefono_user = $_POST['x_customer_phone'];
            $direccion_user = $_POST['x_customer_address'];
            $transaction_id = $_POST['x_transaction_id'];
            $ref_payco = $_POST['x_ref_payco'];
            $info_adicional = "response pagoLicencia";
            $extra1 = $_POST['x_extra1'];
            $currency = $_POST['x_currency_code'];
            $total_pais = $_POST['x_amount_country'];
            $metodopago = $_POST['x_franchise'];
            $pago_por = $_POST['x_bank_name'];

            echo 'Datos';
            echo '<br>';

            //licencia=1386_2 - 4024_2
            //extra1=1386-5000 _ 4024-5000
            if (count($licencia) != 1) {
                echo '+ licencia';
                echo '<br>';
                $id_licencia = array();
                $valor = array();
                $valor_dolares = array();
                $sw = 1;
                $extra1 = explode('_', $_POST['x_extra1']);

                for ($x = 0; $x < count($licencia); $x++) {
                    $v = explode('_', $licencia[$x]);
                    $ex = explode('-', $extra1[$x]);
                    if ($currency == "USD") {
                        array_push($valor, $ex[1]);
                    } else {
                        array_push($valor, $v[2]);
                    }
                    array_push($id_licencia, $v[0]);
                    array_push($valor_dolares, $v[1]);
                }
            } else {
                echo '1 licencia';
                echo '<br>';
                $sw = 0;
                $valor = $_POST['x_amount'];
                $valor_dolares = $_POST['x_amount'];

                if ($currency == "USD") {
                    $valor = $extra1;
                }
                //$valor=5000;
                $v = explode('_', $referencia_aux[1]);
                $id_licencia = (count($v) != 1) ? $v[0] : $referencia_aux[1];
            }

            echo 'licencia ';
            var_dump($licencia);
            echo '<br>';

            $estado = ($estado == 1) ? 1 : 3;
            $forma_pago = 3;

            if ($estado == 1) {
                echo 'Estado ';
                var_dump($estado);
                echo '<br>';

                //verifico si es un array o no la licencia
                $id_licencia_array = is_array($id_licencia) ? $id_licencia[0] : $id_licencia;

                //verificar si llego el pago
                $hoy = date('Y-m-d');
                $existe_pago = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia_array, 'estado_pago' => 1));
                echo 'Existe pago ';
                var_dump($existe_pago);
                echo '<br>';
                if ($existe_pago == 0) {
                    echo 'No existe pago ';
                    echo '<br>';
                    $pago = $this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, $sw, $transaction_id, $ref_payco, $forma_pago, $valor_dolares, $total_pais, $metodopago, $pago_por, $currency);
                    echo 'id  pago ';
                    var_dump($pago);
                    echo '<br>';
                    if ($pago != 0) {

                        echo 'pago diferente de 0';
                        echo '<br>';

                        $bduser = $this->licencias->buscarBD($id_licencia_array);
                        echo 'Buscar bd';
                        echo '<br>';

                        $idbd = $bduser[0]['id'];
                        echo 'iddb ';
                        var_dump($idbd);
                        echo '<br>';

                        $this->licencias->updateEstadoBD2($idbd);
                        echo 'update EstadoBD2';
                        echo '<br>';

                        //**verifico si tiene informacion en crm_info_facturacion sino tomo los valores de epayco y los guardo */
                        $datos_licencia = $this->crm_licencia_model->get_licencias(array('idlicencias_empresa' => $id_licencia_array));
                        $empresa = $datos_licencia[0]->idempresas_clientes;
                        $datos_empresas = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $empresa));

                        echo 'Datos empresa';
                        echo '<br>';

                        if (empty($datos_empresas)) {
                            echo 'No hay datos empresa';
                            echo '<br>';

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
                        //Cambiar las fechas de bodegas si las hubiera
                        $sqlbodegas = "SELECT * FROM " . $bduser[0]['base_dato'] . ".almacen WHERE bodega=1";
                        $bodegas = $this->db->query($sqlbodegas)->result_array();

                        echo 'Bodegas ';
                        var_dump($bodegas);
                        echo '<br>';

                        if ($sw != 0) {
                            echo 'Sw ';
                            var_dump($sw);
                            echo '<br>';

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
                                //$email->emailConfirmarPago($id);
                                //verificar nuevamente que no haya factura asociada
                                $existe_pago2 = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id, 'estado_pago' => 1, 'id_factura_licencia !=' => ''));
                                if ($existe_pago2 == 0) {
                                    //generando la factura
                                    require_once 'administracion_vendty/facturas_licencia.php';
                                    $factura = new Facturas_licencia();
                                    $facturag = $factura->generar_factura_de_licencia($id, $pago);

                                    if (!empty($facturag)) {
                                        //$email->emailFacturaPago($facturag);
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
                                            $this->crm_licencias_empresa->update($datosli);
                                        }
                                    }
                                    $i++;
                                }
                            }
                        } else {
                            echo 'Sw ';
                            var_dump($sw);
                            echo '<br>';

                            require_once 'job.php';
                            $email = new Job();
                            //$email->emailConfirmarPago($id_licencia);
                            echo 'Email confirmacion ';
                            echo '<br>';
                            //generando la factura
                            require_once 'administracion_vendty/facturas_licencia.php';
                            $existe_pago2 = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia_array, 'estado_pago' => 1, 'id_factura_licencia !=' => ''));

                            echo 'Existe pago 2 ';
                            var_dump($existe_pago2);
                            echo '<br>';

                            if ($existe_pago2 == 0) {

                                echo 'dentro if Existe pago 2 ';
                                echo '<br>';

                                $factura = new Facturas_licencia();
                                $facturag = $factura->generar_factura_de_licencia($id_licencia, $pago);

                                echo 'facturag 2 ';
                                var_dump($facturag);
                                echo '<br>';

                                if (!empty($facturag)) {
                                    //$email->emailFacturaPago($facturag);
                                    echo 'Email facturag ';
                                    echo '<br>';

                                    echo 'Empty bodegas ';
                                    var_dump($bodegas);
                                    echo '<br>';
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
                                                    echo 'Datosli ';
                                                    var_dump($datosli);
                                                    echo '<br>';

                                                    $this->crm_licencias_empresa->update($datosli);
                                                }
                                            }
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($id_licencia == 11152) {
                            print_r($_POST);die();
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $usuario = 'vendtyMaster';
            $clave = 'ro_ar_8027*_na';
            $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
            $base_dato = 'vendty2';
            $conn = @mysql_connect($servidor, $usuario, $clave);
            mysql_select_db($base_dato, $conn);
            mysql_query("INSERT INTO `response` (`response`)VALUES ('$e->getMessage()');", $conn);
            mysql_close($conn);
        }
    }

    public function responseRecurrentPayment2()
    {
        try {
            $usuario = 'vendtyMaster';
            $clave = 'ro_ar_8027*_na';
            $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
            $base_dato = 'vendty2';
            $conn = @mysql_connect($servidor, $usuario, $clave);
            mysql_select_db($base_dato, $conn);
            $response = json_encode($_POST);
            mysql_query("INSERT INTO `response` (`response`)VALUES ('$response');", $conn);
            $id_suscripcion = explode('-', $_POST['x_id_factura'])[0];
            $sql = "SELECT * FROM epayco_suscripcion WHERE suscripcion_id = '$id_suscripcion' LIMIT 1;";
            $result = mysql_query($sql, $conn);

            while ($row = mysql_fetch_array($result)) {
                $licenciaGuardada = $row["licencia"];
            }

            $plan = "SELECT id FROM crm_planes WHERE nombre_plan = '" . $_POST['x_description'] . "' LIMIT 1";
            $result = mysql_query($plan, $conn);

            while ($row = mysql_fetch_array($result)) {
                $idplan = $row["id"];
            }

            mysql_close($conn);

            var_dump($_POST);
            echo '<br>';

            var_dump($licenciaGuardada);
            echo '<br>';

            $observacion = $_POST['x_response_reason_text'];
            $estado = $_POST['x_cod_response'];
            $valor = $_POST['x_amount'];
            $referencia_aux = explode('Licencia Vendty', $licenciaGuardada);
            $referencia_aux = explode('-', $referencia_aux[1]);
            $id_licencia = explode('_', $referencia_aux[0])[0];
            $id_plan = $idplan;
            $tipo_documento_user = $_POST['x_customer_doctype'];
            $numero_documento_user = $_POST['x_customer_document'];
            $nombre_user = $_POST['x_customer_name'];
            $apellido_user = $_POST['x_customer_lastname'];
            $email_user = $_POST['x_customer_email'];
            $telefono_user = $_POST['x_customer_phone'];
            $direccion_user = $_POST['x_customer_address'];
            $transaction_id = $_POST['x_transaction_id'];
            $ref_payco = $_POST['x_ref_payco'];
            $info_adicional = "response2 pagoLicencia";
            $extra1 = $_POST['x_extra1'];
            $currency = $_POST['x_currency_code'];
            $total_pais = $_POST['x_amount_country'];
            $metodopago = $_POST['x_franchise'];
            $pago_por = $_POST['x_bank_name'];
            $valor_dolares = $valor;
            $estado = ($estado == 1) ? 1 : 3;
            $forma_pago = 3;
            $idbd = "";

            echo 'Datos';
            echo '<br>';

            if ($currency == "USD") {
                $valor = $extra1;
            }

            echo 'Estado ';
            var_dump($estado);
            echo '<br>';

            if ($estado == 1) {
                echo 'Estado ';
                var_dump($estado);
                echo '<br>';
                //verificar si llego el pago
                $hoy = date('Y-m-d');
                $existe_pago = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia, 'estado_pago' => 1));
                echo 'Existe pago ';
                var_dump($existe_pago);
                echo '<br>';
                if ($existe_pago == 0) {
                    echo 'No existe pago ';
                    echo '<br>';
                    //email
                    require_once 'job.php';
                    $email = new Job();

                    echo 'id licencia ';
                    var_dump($id_licencia);
                    echo '<br>';
                    $planActual = $this->licencias->getPlanActual($id_licencia);
                    $pago = $this->licencias->insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, 0, $transaction_id, $ref_payco, $forma_pago, $valor_dolares, $total_pais, $metodopago, $pago_por, $currency);
                    echo 'id  pago ';
                    var_dump($pago);
                    echo '<br>';
                    //PASAR A PRODUCCIÓN
                    $migrar = is_array($id_licencia) ? 1 : 0;
                    echo 'Migrar ';
                    var_dump($migrar);
                    echo 'plan actual ';
                    var_dump($planActual);
                    echo '<br>';
                    if (($migrar == 0) && ($planActual == 1)) {
                        echo 'pago diferente de 0';
                        echo '<br>';
                        $bduser = $this->licencias->buscarBD($id_licencia);
                        echo 'Buscar bd';
                        echo '<br>';
                        $nombrebd = $bduser[0]['base_dato'];
                        $idbd = $bduser[0]['id'];
                        echo 'iddb ';
                        var_dump($idbd);
                        echo '<br>';
                        $email1 = explode("vendty2_db_", $nombrebd);

                        //$this->licencias->produccion($nombrebd);
                        $data = array(
                            'origen' => 2,
                            'destino' => 1,
                            'dbname' => $email1[1],
                        );

                        //$migrada=post_curl('migraciondb',json_encode($data),$this->session->userdata('token_api'));
                        //if ($migrada->status){
                        //if ($migrada->description=='ok'){
                        $this->licencias->updateEstadoBD($idbd);
                        echo 'update EstadoBD';
                        echo '<br>';
                        //modifico las fechas licencia
                        $plan = $this->crm_model->get_planes(array('id' => $id_plan));
                        $tiempo = $plan[0]->dias_vigencia;
                        $this->licencias->updateLicencianuevo($id_licencia, $id_plan, $tiempo);
                        echo 'update EstadoBD2';
                        echo '<br>';
                        //email de bienvenida
                        //$email->BienvenidoaVendty($idbd);
                        echo 'Bienvenida';
                        echo '<br>';
                        //}
                        //}

                    }

                    //**tomo los valores de epayco y los guardo */
                    //if ($migrada->status){
                    //if ($migrada->description=='ok'){
                    echo 'datos empresa';
                    echo '<br>';
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
                    echo 'email pago';
                    echo '<br>';
                    //$email->emailConfirmarPago($id_licencia);
                    //generando la factura
                    $existe_pago2 = $this->crm_model->existe_pago(array('transaction_id' => $transaction_id, 'ref_payco' => $ref_payco, 'id_licencia' => $id_licencia, 'estado_pago' => 1, 'id_factura_licencia !=' => ''));

                    if ($existe_pago2 == 0) {
                        require_once 'administracion_vendty/facturas_licencia.php';
                        $factura = new Facturas_licencia();
                        $facturag = $factura->generar_factura_de_licencia($id_licencia, $pago);

                        if (!empty($facturag)) {
                            //$email->emailFacturaPago($facturag);
                        }
                    }

                    //}
                    //}
                }
            }
        } catch (Exception $ex) {
            $usuario = 'vendtyMaster';
            $clave = 'ro_ar_8027*_na';
            $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
            $base_dato = 'vendty2';
            $conn = @mysql_connect($servidor, $usuario, $clave);
            mysql_select_db($base_dato, $conn);
            mysql_query("INSERT INTO `response` (`response`)VALUES ('$e->getMessage()');", $conn);
            mysql_close($conn);
        }
    }

    /*** Pagos */
    /****      Registro nuevo     *****/
    public function Registro()
    {
        //GUARDANDO EN SESSION PARA DIRECCIONAR PRIMERO A GRACIAS
        if ($this->input->get('Email') != "") {
            $_POST["Last_Name"] = $this->input->get('Last_Name');
            $_POST["Mobile"] = $this->input->get('Mobile');
            $_POST["Email"] = $this->input->get('Email');
            $_POST["Fuente"] = $this->input->get('Fuente'); // == "" ? "Adwords" : $this->input->get('Fuente');
            $_POST["Estado"] = $this->input->get('Estado'); // == "" ? "registro" : $this->input->get('Estado');
        }

        if ($this->input->post('Email') != "") {
            $_POST["Fuente"] = $this->input->post('Fuente') == "" ? "Adwords" : $this->input->post('Fuente');
            $_POST["Estado"] = $this->input->post('Estado') == "" ? "registro" : $this->input->post('Estado');

            if (!is_numeric($this->input->post('Mobile'))) {
                echo "Por favor ingrese en el campo del telefono un valor numerico<br><br>";
                exit();
            }
        }

        $this->form_validation->set_rules('Email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|email_check|callback_email_check');

        if ($this->form_validation->run() == true) {
            $email_validation = post_curl('email-validation', json_encode([
                'email' => $this->input->post('Email'),
            ]));

            if ($email_validation->result === 'invalid') {
                redirect('http://vendty.com/registro_nuevo.html');
            } else {
                $year = date('Y');
                $salt = substr(md5(uniqid(rand(), true)), 0, 10);
                $password = substr($this->input->post('Email'), 0, 4) . "$year";
                $password_send = $password;
                $conf_code = $salt . substr(sha1($salt . $password), 0, -10);
                $email = $this->input->post('Email');
                $username = explode('@', $email);
                $username = $username[0];
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $mobile = $this->input->post('Mobile');
                $nombreUsuario = $this->input->post('Last_Name');

                $query = "INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `db_config_id`, `idioma`, `pais`, `rol_id`, `is_admin`,`term_acept`) VALUES (NULL, '" . $ip_address . "', '" . $username . "', '" . $this->ion_auth->hash_password($password) . "', '" . $salt . "', '" . $email . "', '" . $conf_code . "', NULL, NULL, NULL, '" . time() . "', '" . time() . "', '0', '$nombreUsuario', NULL, NULL, '" . $mobile . "', '', 'spanish', '', '', 't','Si');";
                $this->dbConnectionv2->query($query);
                $id = $this->dbConnectionv2->insert_id();

                $query = "INSERT INTO `registros` (`correo`, `nombre`, `fuente`, `user_id`, `created_at`, `updated_at`) VALUES ('" . $email . "', '" . $_POST["Last_Name"] . "', '" . $_POST["Fuente"] . "', '" . $id . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "');";
                $this->dbConnectionv2->query($query);

                $data = array(
                    "user" => $nombreUsuario,
                    "email" => $email,
                    "password" => $password,
                );

                $html = $this->load->view('email/new_account', $data, true);

                $this->load->library('email');
                $this->email->initialize();
                $this->email->from('no-responder@vendty.net', 'Vendty Sistema Pos Cloud');
                $this->email->to($email);
                $this->email->bcc('roxanna@vendty.com, info@vendty.com');
                $this->email->subject("¡Bienvenido a Vendty!");
                $this->email->message($html);
                $this->email->send();
                $this->sendMailRegisterWebinar($nombreUsuario, $email);

                $phone = explode(" ", $_POST["form_fields"]["835ab33"])[1] . $mobile;

                //$this->sendTextMessage($phone);
                //$this->sendWhatsappMessage($this->input->post('Last_Name'), '', $phone);

                $password = $id . '_' . $password;
                $this->activate_count2($id, $password, $conf_code); //

                $data['identity'] = array(
                    'name' => 'identity',
                    'id' => 'identity',
                    'type' => 'text',
                    'value' => "$email",
                    'placeholder' => "login",
                );
                $data['password'] = array(
                    'name' => 'password',
                    'id' => 'password',
                    'type' => 'password',
                    'value' => "$password_send",
                    'placeholder' => "password",
                );
                $data['Nombre'] = $this->input->post('Last_Name');
                $data["Mobile"] = $this->input->post('Mobile');
                $data["Email"] = $this->input->post('Email');
                $data["Fuente"] = $this->input->post('Fuente');
                $data["Estado"] = $this->input->post('Estado');

                $this->loginRegistro($this->input->post('Email'), $password_send);
            }
        } else {
            if (!isset($_POST['Email'])) {
                redirect('http://vendty.com/registro_nuevo.html');
            } else {
                $data['email'] = $this->input->post('Email');
                $data['message'] = "El email que intentas ingresar ya se encuentra registrado o no esta permitido.<br/> A continuación podras recuperar tu contraseña";
                $this->layout->template("login")->show('auth/forgot_password', array('data' => $data));
            }
        }
    }

    public function cancelSubscription()
    {
        $correo = $this->input->get('email');

        if ($correo) {
            $query = "UPDATE registros SET suscripcion = 0 WHERE correo = '{$correo}'";
            $usuario = 'vendtyMaster';
            $clave = 'ro_ar_8027*_na';
            $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
            $base_dato = 'vendty2';
            $conn = @mysql_connect($servidor, $usuario, $clave);
            mysql_select_db($base_dato, $conn);
            mysql_query($query);
            mysql_close($conn);
        }

        echo 'Suscripción Cancelada';
    }

    public function sendMailRegistro()
    {
        $data = array(
            "user" => $_POST['user'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
        );
        $html = $this->load->view('email/new_account', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty Sistema Pos Cloud');
        $this->email->to($_POST['email']);
        $this->email->bcc('roxanna@vendty.com, info@vendty.com');
        $this->email->subject("¡Bienvenido a Vendty!");
        $this->email->message($html);
        $this->email->send();
        $this->sendMailRegisterWebinarApi($_POST['user'], $_POST['email']);
    }

    public function sendMailRegisterStore()
    {
        $data = array(
            "user" => $_POST['user'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "store" => $_POST['store'],
        );
        $html = $this->load->view('email/new_store', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($_POST['email']);
        $this->email->bcc('asesor@vendty.com, arnulfo@vendty.com, info@vendty.com');
        $this->email->subject("¡Bienvenido a Vendty!");
        $this->email->message($html);
        $this->email->send();
        $this->sendMailRegisterWebinarApi($_POST['user'], $_POST['email']);
    }

    public function sendMailRegisterWompi()
    {
        $data = array(
            "user" => $_POST['user'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "store" => $_POST['store'],
        );
        $html = $this->load->view('email/new_store_wompi', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($_POST['email']);
        $this->email->bcc('asesor@vendty.com, roxanna@vendty.com, info@vendty.com');
        $this->email->subject("¡Bienvenido a tu tienda virtual!");
        $this->email->message($html);
        $this->email->send();
    }

    public function sendMailRegisterWebinar($user, $email)
    {
        $data = array(
            "user" => $user,
        );
        $html = $this->load->view('email/register_webinar', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($email);
        $this->email->bcc('asesor@vendty.com, roxanna@vendty.com, info@vendty.com');
        $this->email->subject("¡UPS, se nos olvidó invitarte, pero nos dimos cuenta a tiempo!");
        $this->email->message($html);
        $this->email->send();
    }

    public function sendMailRegisterWebinarApi($user, $email)
    {
        $data = array(
            "user" => $user,
        );
        $html = $this->load->view('email/register_webinar_api', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($email);
        $this->email->bcc('asesor@vendty.com, roxanna@vendty.com, info@vendty.com');
        $this->email->subject("¡UPS, se nos olvidó invitarte, pero nos dimos cuenta a tiempo!");
        $this->email->message($html);
        $this->email->send();
    }

    public function sendMailRegisterLoreal()
    {
        $data = array(
            "user" => $_POST['user'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "store" => $_POST['store'],
        );
        $html = $this->load->view('email/new_store_loreal', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($_POST['email']);
        $this->email->bcc('roxanna@vendty.com, info@vendty.com');
        $this->email->subject("¡Bienvenido a tu tienda virtual!");
        $this->email->message($html);
        $this->email->send();
    }

    private function sendWhatsappMessage($first_name, $last_name, $phone)
    {
        $phone = strpos($phone, "+") !== false ? $phone : "+57" . $phone;
        $dataWhatsapp = array(
            "to" => "whatsapp:" . $phone,
            "name" => $first_name == $last_name ? strtoupper($first_name) : strtoupper($first_name . " " . $last_name),
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://35.164.59.216:8080/interactions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataWhatsapp);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function sendTextMessage($phone)
    {
        if (strpos($phone, "+") === false || (strpos($phone, "+") !== false && strpos($phone, "+57") !== false)) {
            $phone = strpos($phone, "+57") !== false ? substr($phone, 3) : $phone;
            $user = rawurlencode('vendty');
            $password = rawurlencode('YjxmMUeRVXT@E9U');
            $destination = !empty($phone) ? rawurlencode($phone) : rawurlencode('3012832146');
            $message = rawurlencode('[welcome]');
            $globalParams = rawurlencode('{"welcome":"Bienvenido(a) a Vendty, enviamos las credenciales a tu correo, revisa tu bandeja de entrada o en SPAM,  para asesoría WhatsApp http://bit.ly/2RSQeAg"}');
            $userParams = rawurlencode('{}');

            //Agrupacion de los parametros para enviar la peticion GET
            $url = 'https://contactalos.com/services/rs/sendsms.php?user=' . $user . '&password=' . $password . '&destination=' . $destination . '&message=' . $message . '&globalParams=' . $globalParams . '&userParams=' . $userParams;

            // append the header putting the secret key and hash

            $request_headers = array();
            $request_headers[] = 'Authorization: Bearer ';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);
            curl_close($ch);

            return json_decode($data, true);
        }
    }

    public function activate_count2($id, $email, $code = false)
    {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } elseif ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id, $code);
        }

        if ($activation) {
            try {
                $this->_create_db_nuevo($id, $email);
                $this->_do_login($id);
                $this->session->set_flashdata('message', "Su cuenta ha sido creada. Por favor verifique su email");
            } catch (Exception $e) {
                redirect("auth", 'refresh');
            }
        } else {

            //redirect them to the forgot password page

            $this->session->set_flashdata('message', $this->ion_auth->errors());

            redirect("auth/forgot_password", 'refresh');
        }
    }

    public function _create_db_nuevo($id, $nom_bd)
    {
        $username_multi = $this->config->item('multi_tenant_user');
        $clave_multi = $this->config->item('multi_tenant_pass');
        $servidor_multi = $this->config->item('multi_tenant_host');
        $conn = @mysql_connect($servidor_multi, $username_multi, $clave_multi);

        if (!$conn) {
            $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");
        } else {
            $uid = uniqid();
            $valores_remplazar = array('.', '-', '/');
            $nom_bd = str_replace($valores_remplazar, '_', $nom_bd);
            $database_name = "vendty2_db_$nom_bd";
            $sql = "CREATE DATABASE $database_name";

            $_SESSION['db_name'] = $database_name;

            if (mysql_query($sql, $conn)) {
                mysql_select_db($database_name, $conn);

                $sql_almacen = "CREATE TABLE IF NOT EXISTS `almacen` (

                           `id` int(11) NOT NULL AUTO_INCREMENT,

                           `resolucion_factura` varchar(254) DEFAULT NULL,

                           `nit` varchar(254) DEFAULT NULL,

                           `nombre` varchar(254) DEFAULT NULL,

                           `direccion` text,

                           `meta_diaria` float DEFAULT NULL,

                           `prefijo` varchar(254) DEFAULT NULL,

                           `consecutivo` int(10) unsigned DEFAULT NULL,

                           `activo` tinyint(1) DEFAULT '1',

                           `telefono` varchar(20) NOT NULL,

                           `tipo_almacen` varchar(20) NULL,
                           `pais` varchar(50) DEFAULT NULL,
                           `consecutivo_orden_restaurante` int(11) NOT NULL DEFAULT '1' COMMENT 'Campo para saber el numero de orden para las mesas',
                           `reiniciar_consecutivo_orden_restaurante` int(11) DEFAULT '100' COMMENT 'Numero para reiniciar el consecutivo de la orden de restaurante',
                           PRIMARY KEY (`id`)

                         ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_categoria = "CREATE TABLE IF NOT EXISTS `categoria` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `codigo` int(11) DEFAULT NULL,
                `nombre` varchar(254) DEFAULT NULL,
                `imagen` varchar(254) DEFAULT NULL,
                `padre` varchar(254) DEFAULT NULL,
                `activo` tinyint(1) DEFAULT '1',
                `tienda` int(11) DEFAULT '1',
                `es_menu_principal_tienda` tinyint(1) DEFAULT '0',
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

                $categoria_query = "INSERT INTO `categoria` (`id`, `codigo`, `nombre`, `imagen`, `padre`, `activo`) VALUES

                           (2, 0000000, 'General', '', NULL, 1);";

                $sql_clientes = "CREATE TABLE IF NOT EXISTS `clientes` (

                           `id_cliente` int(11) NOT NULL AUTO_INCREMENT,

                           `pais` varchar(254) NOT NULL,

                           `provincia` varchar(254) DEFAULT NULL,

                           `nombre_comercial` varchar(100) DEFAULT NULL,

                           `razon_social` varchar(100) DEFAULT NULL,

                           `nif_cif` varchar(15) DEFAULT NULL,

                           `contacto` varchar(100) DEFAULT NULL,

                           `pagina_web` varchar(150) DEFAULT NULL,

                           `email` varchar(80) DEFAULT NULL,

                           `poblacion` varchar(80) DEFAULT NULL,

                           `direccion` text,

                           `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,

                           `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                           `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                           `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                           `tipo_empresa` varchar(80) DEFAULT NULL,

                           `entidad_bancaria` varchar(100) DEFAULT NULL,

                           `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,

                           `observaciones` text,
                           `grupo_clientes_id` int(11) NOT NULL DEFAULT '1',
                           `onlineTienda` tinyint(1) DEFAULT '0',
                           `password` varchar(100) DEFAULT NULL,
                           `fecha_nacimiento` varchar(100) DEFAULT NULL,
                           `genero` varchar(100) DEFAULT NULL,
                           PRIMARY KEY (`id_cliente`)

                         ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

                               ";

                /* $sql_servicios = "CREATE TABLE IF NOT EXISTS `servicios` (

                `id_servicio` int(11) NOT NULL AUTO_INCREMENT,

                `nombre` varchar(254) NOT NULL,

                `codigo` varchar(254) DEFAULT NULL,

                `descripcion` text NOT NULL,

                `precio` float(10,2) NOT NULL,

                `id_impuesto` int(11) NOT NULL,

                PRIMARY KEY (`id_servicio`),

                KEY `servicios_FK1` (`id_impuesto`)

                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";*/

                $sql_detalle_venta = "CREATE TABLE IF NOT EXISTS `detalle_venta` (

                           `id` int(11) NOT NULL AUTO_INCREMENT,

                           `venta_id` int(11) NOT NULL,

                           `codigo_producto` varchar(15) DEFAULT NULL,

                           `nombre_producto` varchar(254) DEFAULT NULL,

                           `unidades` double DEFAULT NULL,

                           `precio_venta` double DEFAULT NULL,

                           `descuento` double DEFAULT NULL,

                           `impuesto` double DEFAULT NULL,

                           `linea` varchar(254) DEFAULT NULL,

                           `margen_utilidad` double DEFAULT NULL,

                           `activo` tinyint(1) DEFAULT '1',

                           `producto_id` int(11) DEFAULT NULL,
                           `status` int(1) DEFAULT NULL,
                           `porcentaje_descuento` double DEFAULT NULL,

                           PRIMARY KEY (`id`),

                           KEY `detalle_venta_FKIndex1` (`venta_id`)

                         ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_productos = "CREATE TABLE IF NOT EXISTS `producto` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `categoria_id` int(11) NOT NULL,
                `codigo` varchar(50) DEFAULT NULL,
                `nombre` varchar(254) DEFAULT NULL,
                `codigo_barra` varchar(254) DEFAULT NULL,
                `precio_compra` double DEFAULT NULL,
                `precio_venta` double DEFAULT NULL,
                `stock_minimo` double DEFAULT NULL,
                `descripcion` text,
                `descripcion_larga` text,
                `activo` tinyint(1) DEFAULT '1',
                `impuesto` double DEFAULT NULL,
                `fecha` date DEFAULT NULL,
                `imagen` varchar(254) DEFAULT NULL,
                `thumbnail` varchar(255) DEFAULT NULL,
                `material` tinyint(1) DEFAULT '0',
                `ingredientes` tinyint(1) DEFAULT '0',
                `combo` int(11) DEFAULT '0',
                `unidad_id` int(11) DEFAULT '1',
                `imagen1` varchar(254) DEFAULT NULL,
                `imagen2` varchar(254) DEFAULT NULL,
                `imagen3` varchar(254) DEFAULT NULL,
                `imagen4` varchar(254) DEFAULT NULL,
                `imagen5` varchar(254) DEFAULT NULL,
                `id_proveedor` bigint(20) DEFAULT '0',
                `stock_maximo` double NOT NULL,
                `fecha_vencimiento` varchar(100) NOT NULL,
                `ubicacion` varchar(150) NOT NULL,
                `ganancia` double NOT NULL,
                `tienda` tinyint(1) DEFAULT '0',
                `muestraexist` tinyint(1) DEFAULT '0',
                `vendernegativo` tinyint(1) DEFAULT '0',
                `mostrar_stock` tinyint(1) NOT NULL DEFAULT '0',
                `woocommerce_id` int(10) unsigned DEFAULT NULL,
                `id_tipo_producto` int(11) DEFAULT NULL COMMENT 'referencia la tabla tipo de producto, esta columna se agrega para los productos de restaurante',
                `destacado_tienda` tinyint(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                KEY `producto_FKIndex1` (`categoria_id`),
                CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

                $sql_impuestos = "
                CREATE TABLE IF NOT EXISTS `impuesto` (
                    `id_impuesto` int(11) NOT NULL AUTO_INCREMENT,
                    `nombre_impuesto` varchar(254) DEFAULT NULL,
                    `porciento` double DEFAULT NULL,
                    `predeterminado` tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id_impuesto`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $insert_impusto = "INSERT INTO `impuesto` (`id_impuesto`, `nombre_impuesto`, `porciento`) VALUES (NULL, 'Sin Impuesto', '0');";

                $sql_opciones = "CREATE TABLE IF NOT EXISTS `opciones` (

                           `id` int(11) NOT NULL AUTO_INCREMENT,

                           `nombre_opcion` varchar(254) NOT NULL DEFAULT '',

                           `valor_opcion` text NOT NULL,

                           PRIMARY KEY (`id`,`nombre_opcion`)

                         ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_nothing_tax = "INSERT INTO `impuestos` (`id_impuesto` ,`nombre_impuesto` ,`porciento`) VALUES (NULL , 'Ninguno', '0');";

                $sql_pagos = "CREATE TABLE IF NOT EXISTS `pagos` (

                               `id_pago` int(11) NOT NULL AUTO_INCREMENT,

                               `id_factura` int(11) NOT NULL,

                               `fecha_pago` date NOT NULL,

                               `cantidad` double NOT NULL,

                               `tipo` varchar(254) NOT NULL,

                               `notas` text NOT NULL,

                               `importe_retencion` double DEFAULT NULL,

                               PRIMARY KEY (`id_pago`),

                               KEY `pagos_FK1` (`id_factura`)

                               ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_proveedores = "CREATE TABLE IF NOT EXISTS `proveedores` (

                               `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,

                               `pais` varchar(254) NOT NULL,

                               `provincia` varchar(254) DEFAULT NULL,

                               `nombre_comercial` varchar(100) DEFAULT NULL,

                               `razon_social` varchar(100) DEFAULT NULL,

                               `nif_cif` varchar(15) DEFAULT NULL,

                               `contacto` varchar(100) DEFAULT NULL,

                               `pagina_web` varchar(150) DEFAULT NULL,

                               `email` varchar(80) DEFAULT NULL,

                               `poblacion` varchar(80) DEFAULT NULL,

                               `direccion` text,

                               `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,

                               `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                               `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                               `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                               `tipo_empresa` varchar(80) DEFAULT NULL,

                               `entidad_bancaria` varchar(100) DEFAULT NULL,

                               `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,

                               `observaciones` text,

                               PRIMARY KEY (`id_proveedor`)

                               ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $sql_proformas = "CREATE TABLE IF NOT EXISTS `proformas` (
                `id_proforma` int(11) NOT NULL AUTO_INCREMENT,
                `id_proveedor` int(11) NOT NULL,
                `descripcion` varchar(254) NOT NULL,
                `cantidad` double NOT NULL,
                `valor` double NOT NULL,
                `notas` text NOT NULL,
                `fecha` date NOT NULL,
                `id_impuesto` int(1) NOT NULL,
                `id_almacen` int(30) NOT NULL,
                `forma_pago` varchar(150) NOT NULL,
                `id_cuenta_dinero` int(100) NOT NULL,
                `fecha_crea_gasto` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha de creacion del gasto almacena fecha y hora',
                `banco_asociado` int(11) DEFAULT NULL COMMENT 'Campo para verificar si el gasto fue asociado a un banco',
                `subcategoria_asociada` int(11) DEFAULT NULL COMMENT 'Campo para verficar la categoria asociada al banco',
                `movimiento_asociado` int(11) DEFAULT NULL COMMENT 'Campo para verificar si tiene movimiento activo',
                PRIMARY KEY (`id_proforma`),
                KEY `proformas_FK1` (`id_impuesto`),
                KEY `proformas_FK2` (`id_proveedor`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

                $sql_presupuestos = "CREATE TABLE IF NOT EXISTS `presupuestos` (

                             `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT,

                             `id_cliente` int(11) NOT NULL,

                             `numero` varchar(10) CHARACTER SET latin1 NOT NULL,

                             `monto` double NOT NULL,

                             `monto_siva` double NOT NULL,

                             `monto_iva` double NOT NULL,

                             `fecha` date NOT NULL,

                             PRIMARY KEY (`id_presupuesto`),

                             KEY `presupuestos_FK1` (`id_cliente`)

                           ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                $sql_presupuestos_detalles = "CREATE TABLE IF NOT EXISTS `presupuestos_detalles` (

                                                       `id_presupuesto_detalle` int(11) NOT NULL AUTO_INCREMENT,

                                                       `id_presupuesto` int(11) NOT NULL,

                                                       `precio` double NOT NULL,

                                                       `cantidad` double NOT NULL,

                                                       `impuesto` double NOT NULL,

                                                       `fk_id_producto` int(11) NOT NULL,

                                                       `descuento` double NOT NULL,

                                                       `descripcion_d` text NOT NULL,

                                                       PRIMARY KEY (`id_presupuesto_detalle`),

                                                       KEY `presupuestos_detalles_FK1` (`id_presupuesto`)

                                                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $sql_facturas = "

                               CREATE TABLE IF NOT EXISTS `facturas` (

                                 `id_factura` int(11) NOT NULL AUTO_INCREMENT,

                                 `id_cliente` int(11) NOT NULL,

                                 `numero` varchar(10) CHARACTER SET latin1 NOT NULL,

                                 `monto` double NOT NULL,

                                 `monto_siva` double NOT NULL,

                                 `monto_iva` double NOT NULL,

                                 `fecha` date NOT NULL,

                                 `fecha_v` date DEFAULT NULL,

                                 `estado` int(1) NOT NULL,

                                 PRIMARY KEY (`id_factura`),

                                 KEY `facturas_FK1` (`id_cliente`)

                               ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                $sql_facturas_detalles = "CREATE TABLE IF NOT EXISTS `facturas_detalles` (

                                   `id_factura_detalle` int(11) NOT NULL AUTO_INCREMENT,

                                   `id_factura` int(11) NOT NULL,

                                   `precio` double NOT NULL,

                                   `cantidad` double NOT NULL,

                                   `impuesto` double NOT NULL,

                                   `descuento` double NOT NULL,

                                   `fk_id_producto` int(11) NOT NULL,

                                   `descripcion_d` text NOT NULL,

                                   PRIMARY KEY (`id_factura_detalle`),

                                   KEY `facturas_detalles_FK1` (`id_factura`)

                                 ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $stock_actual = "CREATE TABLE IF NOT EXISTS `stock_actual` (

                               `id` int(11) NOT NULL AUTO_INCREMENT,

                               `almacen_id` int(11) DEFAULT NULL,

                               `producto_id` int(11) DEFAULT NULL,

                               `unidades` DOUBLE,

                               PRIMARY KEY (`id`),

                               KEY `stok_actual_FKIndex1` (`almacen_id`),

                               KEY `producto_stock_actual_fk_idx` (`producto_id`)

                             ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $stock_diario = "CREATE TABLE IF NOT EXISTS `stock_diario` (

                               `id` int(11) NOT NULL AUTO_INCREMENT,

                               `producto_id` int(11) NOT NULL,

                               `almacen_id` int(11) NOT NULL,

                               `fecha` date DEFAULT NULL,

                               `razon` varchar(254) DEFAULT NULL,

                               `cod_documento` varchar(254) DEFAULT NULL,

                               `unidad` DOUBLE,

                               `precio` double DEFAULT NULL,

                               `usuario` int(11) DEFAULT NULL,

                               PRIMARY KEY (`id`),

                               KEY `stock_diario_FKIndex1` (`almacen_id`),

                               KEY `stock_diario_FKIndex2` (`producto_id`)

                             ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $stock_historial = "CREATE TABLE IF NOT EXISTS `stock_historial`(
                               `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                               `fecha` DATE NOT NULL,
                               `almacen_id` INT(11),
                               `producto_id` INT(11),
                               `unidades` double,
                               `precio` double,
                               PRIMARY KEY (`id`),
                               INDEX `stock_historial_almacen_id_index` (`almacen_id`),
                               INDEX `stock_historial_producto_id_index` (`producto_id`),
                               CONSTRAINT `stock_historial_almacen_id_foreign` FOREIGN KEY (`almacen_id`) REFERENCES `almacen`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
                               CONSTRAINT `stock_historial_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
                           ) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

                $usuario_almacen = "CREATE TABLE IF NOT EXISTS `usuario_almacen` (

                               `id` int(11) NOT NULL AUTO_INCREMENT,

                               `usuario_id` int(11) NOT NULL,

                               `almacen_id` int(11) NOT NULL,

                               PRIMARY KEY (`id`)

                             ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

                $vendedor = "CREATE TABLE IF NOT EXISTS `vendedor` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(254) NOT NULL,
                `cedula` varchar(15) NOT NULL,
                `email` varchar(254) NOT NULL,
                `telefono` varchar(20) NOT NULL,
                `comision` double DEFAULT '0',
                `almacen` int(100) NOT NULL,
                `estacion` tinyint(1) DEFAULT '0' COMMENT 'Para saber si el vendedor pertenece a estación de pedido',
                `sesion_estacion` tinyint(1) DEFAULT '0' COMMENT 'Para controlar la cantidad de veces que esta dentro del sistema',
                `codigo` varchar(4) DEFAULT NULL COMMENT 'codigo para ingresar a la estacion de Pedidos',
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";

                $forma_pago = "CREATE TABLE IF NOT EXISTS `forma_pago` (

                           `id` int(11) NOT NULL AUTO_INCREMENT,

                           `codigo` varchar(15) DEFAULT NULL,

                           `nombre` varchar(254) DEFAULT NULL,

                           `activo` tinyint(1) DEFAULT '1',
                           `eliminar` tinyint(1) DEFAULT '1',
                           `tipo` varchar(254) DEFAULT NULL,

                           PRIMARY KEY (`id`)

                         ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $venta = "CREATE TABLE IF NOT EXISTS `venta` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `almacen_id` int(11) DEFAULT NULL,
                `forma_pago_id` int(11) DEFAULT NULL,
                `factura` varchar(254) DEFAULT NULL,
                `resolution_history_id` int(10) unsigned DEFAULT NULL,
                `fecha` datetime DEFAULT NULL,
                `usuario_id` int(11) DEFAULT NULL,
                `cliente_id` int(11) DEFAULT NULL,
                `vendedor` int(11) DEFAULT NULL,
                `vendedor_2` int(11) DEFAULT NULL,
                `cambio` varchar(254) DEFAULT NULL,
                `activo` tinyint(1) DEFAULT '1',
                `total_venta` double NOT NULL,
                `estado` int(11) DEFAULT '0',
                `tipo_factura` varchar(10) DEFAULT 'estandar',
                `fecha_vencimiento` datetime DEFAULT NULL,
                `nota` text NOT NULL,
                `promocion` text,
                `ruta_xml_timbrado` longtext COMMENT 'ruta donde esta el archivo xml resultado de timbrar',
                `factura_timbrada` int(11) DEFAULT NULL COMMENT 'para saber si se ha timbrado o no una factura',
                `porcentaje_descuento_general` double DEFAULT NULL,
                `consecutivo_orden` int(11) DEFAULT NULL,
                `comensales` int(10) unsigned DEFAULT '1',
                `cufe` varchar(100) DEFAULT NULL,
                `factura_electronica` tinyint(1) NOT NULL DEFAULT '0',
                `id_transaccion` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `venta_FKIndex1` (`forma_pago_id`),
                KEY `venta_FKIndex2` (`almacen_id`),
                KEY `venta_cliente_id` (`cliente_id`),
                KEY `venta_vendedor_fk_idx` (`vendedor`),
                CONSTRAINT `venta_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `venta_cliente_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `venta_forma_pago` FOREIGN KEY (`forma_pago_id`) REFERENCES `forma_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `venta_vendedor_fk` FOREIGN KEY (`vendedor`) REFERENCES `vendedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

                $productosf = "CREATE TABLE `productosf` (

                           `id_producto` int(11) NOT NULL AUTO_INCREMENT,

                           `codigo` varchar(254) NOT NULL,

                           `descripcion` text NOT NULL,

                           `nombre` varchar(254) NOT NULL,

                           `id_impuesto` int(11) NOT NULL,

                           `precio` double DEFAULT NULL,

                           `precio_compra` double DEFAULT NULL,

                           PRIMARY KEY (`id_producto`)

                         ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $ventas_pagos = "CREATE TABLE `ventas_pago` (
                    `id_pago` int(11) NOT NULL AUTO_INCREMENT,
                    `id_venta` int(11) NOT NULL,
                    `forma_pago` varchar(254) NOT NULL,
                    `valor_entregado` double NOT NULL,
                    `cambio` double NOT NULL,
                    `transaccion` varchar(25) DEFAULT NULL,
                    PRIMARY KEY (`id_pago`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $rol = "CREATE TABLE `rol` (

                       `id_rol` int(11) NOT NULL AUTO_INCREMENT,

                       `nombre_rol` varchar(254) NOT NULL,

                       `descripcion` text NOT NULL,

                       PRIMARY KEY (`id_rol`)

                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $rol_permisos = "CREATE TABLE `permiso_rol` (

                       `id_permiso_rol` int(11) NOT NULL AUTO_INCREMENT,

                       `id_permiso` int(11) NOT NULL,

                       `id_rol` int(11) NOT NULL,

                       PRIMARY KEY (`id_permiso_rol`)

                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_venta_online = "CREATE TABLE `online_venta` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                       `nombre` varchar(100) NOT NULL,
                       `nombre2` varchar(100) DEFAULT NULL,
                       `apellidos` varchar(100) DEFAULT NULL,
                       `dni` varchar(15) NOT NULL,
                       `telefono` varchar(30) DEFAULT NULL,
                       `movil` varchar(30) DEFAULT NULL,
                       `fax` varchar(100) DEFAULT NULL,
                       `email` varchar(100) NOT NULL,
                       `cpostal` varchar(10) DEFAULT NULL,
                       `direccion` text,
                       `notas` text,
                       `fecha` date NOT NULL,
                       `sub_total` int(20) NOT NULL,
                       `tasa_impuesto` int(20) DEFAULT NULL,
                       `estado` int(11) NOT NULL,
                       `id_transac` varchar(100) DEFAULT NULL,
                       `metodo_pago` varchar(10) DEFAULT NULL,
                       `venta_id` int(11) DEFAULT NULL,
                       `cobro_envio` int(11) DEFAULT NULL,
                       `nombre_envio` varchar(100) DEFAULT NULL,
                       `nombre2_envio` varchar(100) DEFAULT NULL,
                       `correo_envio` varchar(100) DEFAULT NULL,
                       `telefono_envio` varchar(15) DEFAULT NULL,
                       `direccion_envio` varchar(100) DEFAULT NULL,
                       PRIMARY KEY (`id`)
                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

                $sql_prod_venta_online = "CREATE TABLE `online_venta_prod` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                       `id_venta` int(11) NOT NULL,
                       `id_producto` int(11) NOT NULL,
                       `descripcion` text NOT NULL,
                       `precio` double DEFAULT NULL,
                       `cantidad` int(20) DEFAULT NULL,
                       `total` double DEFAULT NULL,
                       `precio_sin_impuesto` double DEFAULT NULL,
                       `impuesto` int(11) DEFAULT NULL,
                       PRIMARY KEY (`id`)
                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

                $sql_auditoria_inventario = "CREATE TABLE `auditoria_inventario` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `fecha_creacion` datetime DEFAULT NULL,
                         `creado_por` int(11) DEFAULT NULL,
                         `fecha_modificacion` datetime DEFAULT NULL,
                         `modificado_por` int(11) DEFAULT NULL,
                         `nombre_auditoria` varchar(100) DEFAULT NULL COMMENT 'el nombre es como un titulo, para que el usuario sepa',
                         `descripcion_auditoria` varchar(300) DEFAULT NULL COMMENT 'una descripcion adicional',
                         `estado_auditoria` varchar(15) DEFAULT NULL COMMENT 'borrador cuando se crea, cerrado cuando se confirma y ya no permite modificar',
                         `id_almacen` int(11) NOT NULL COMMENT 'referencia la tabla almacen, el almacen al que pertence esta auditoria',
                         `archivo_fisico` varchar(1000) DEFAULT NULL COMMENT 'si existe un archivo de soporte se puede cargar y la ruta se guarda en este campo',
                         PRIMARY KEY (`id`),
                         KEY `fk_auditoria_inventario_almacen_idx` (`id_almacen`),
                         CONSTRAINT `fk_auditoria_inventario_almacen` FOREIGN KEY (`id_almacen`) REFERENCES `almacen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                       ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

                $sql_detalle_auditoria = "CREATE TABLE `detalle_auditoria` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `fecha_creacion` datetime DEFAULT NULL,
                         `creado_por` int(11) DEFAULT NULL,
                         `fecha_modificacion` datetime DEFAULT NULL,
                         `modificado_por` int(11) DEFAULT NULL,
                         `id_auditoria` int(11) NOT NULL,
                         `producto_id` int(11) NOT NULL,
                         `cantidad_contada` double DEFAULT NULL COMMENT 'la cantidad del producto que se conto en la auditoria, el fisico',
                         `cantidad_sistema` double DEFAULT NULL COMMENT 'la cantidad que tiene el sistema en el momento de iniciar el arqueo o de contar el primer producto',
                         `observacion_adicional` varchar(1000) DEFAULT NULL COMMENT 'alguna ainformacion que puede digitar el usuario sobre el inventario de este producto',
                         PRIMARY KEY (`id`),
                         KEY `fk_detalle_auditoria_auditoria_inventario1_idx` (`id_auditoria`),
                         KEY `fk_detalle_auditoria_producto1_idx` (`producto_id`),
                         CONSTRAINT `fk_detalle_auditoria_auditoria_inventario1` FOREIGN KEY (`id_auditoria`) REFERENCES `auditoria_inventario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                         CONSTRAINT `fk_detalle_auditoria_producto1` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                       ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

                $filtros_facturas = "ALTER TABLE `facturas`

                               ADD CONSTRAINT `facturas_FK1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_facturas_detalles = "ALTER TABLE `facturas_detalles`

                               ADD CONSTRAINT `facturas_detalles_FK1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_pagos = "ALTER TABLE `pagos`

                               ADD CONSTRAINT `pagos_FK1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_presupuestos = "ALTER TABLE `presupuestos`

                               ADD CONSTRAINT `presupuestos_FK1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_detalles = "ALTER TABLE `presupuestos_detalles`

                               ADD CONSTRAINT `presupuestos_detalles_FK1` FOREIGN KEY (`id_presupuesto`) REFERENCES `presupuestos` (`id_presupuesto`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_productos = "ALTER TABLE `producto`
                       ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_proformas = "ALTER TABLE `proformas`

                               ADD CONSTRAINT `proformas_FK2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,

                               ADD CONSTRAINT `proformas_FK1` FOREIGN KEY (`id_impuesto`) REFERENCES `impuestos` (`id_impuesto`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_stock_actual = "ALTER TABLE `stock_actual`

                   ADD CONSTRAINT `producto_stock_actual_fk` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

                   ADD CONSTRAINT `almacen_id_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_stock_diario = "ALTER TABLE `stock_diario`

                   ADD CONSTRAINT `almacen_fk_stock_actual` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

                   ADD CONSTRAINT `stock_diario_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_ventas = "ALTER TABLE `venta`

                   ADD CONSTRAINT `venta_vendedor_fk` FOREIGN KEY (`vendedor`) REFERENCES `vendedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,

                   ADD CONSTRAINT `venta_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

                   ADD CONSTRAINT `venta_cliente_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,

                   ADD CONSTRAINT `venta_forma_pago` FOREIGN KEY (`forma_pago_id`) REFERENCES `forma_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_usuario_almacen = "ALTER TABLE `usuario_almacen`

                   ADD CONSTRAINT `usuario_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;";

                $filtro_insert_almacen = "INSERT INTO `almacen` (`id`, `nombre`, `direccion`, `prefijo`, `consecutivo`, `activo`, `telefono`, `meta_diaria`) VALUES ('1', 'General', NULL, 'No', '1', '1', '', NULL);";
                $insert_opciones = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES

                                               (1, 'nombre_empresa', ''),

                                               (2, 'resolucion_factura', ''),

                                               (3, 'logotipo_empresa', ''),

                                               (4, 'contacto_empresa', ''),

                                               (5, 'email_empresa', ''),

                                               (6, 'direccion_empresa', ''),

                                               (7, 'telefono_empresa', ''),

                                               (8, 'fax_empresa', ''),

                                               (9, 'web_empresa', ''),

                                               (17, 'moneda_empresa', 'COP'),

                                               (20, 'plantilla_empresa', 'default'),

                                               (21, 'paypal_email', ''),

                                               (22, 'cabecera_factura', ''),

                                               (23, 'terminos_condiciones', ''),

                                               (24, 'prefijo_presupuesto', 'P'),

                                               (25, 'numero_presupuesto', '1'),

                                               (26, 'numero_factura', '1'),

                                               (27, 'prefijo_factura', 'F'),

                                               (28, 'last_numero_factura', '1'),

                                               (29, 'last_numero_presupuesto', '1'),

                                               (30, 'nit', ''),
                                               (31, 'titulo_venta', ''),
                                               (32, 'sistema', 'Pos'),
                                               (44, 'resolucion_factura_estado', 'si'),
                                               (46,'ui_version','v2'),
                                               (50,'simbolo','$'),
                                               (55,'redondear_precios','no'),
                                               (57,'auto_factura','estandar'),
                                               (58,'auto_pago','estandar'),
                                               (59,'clientes_cartera',0),
                                               (60,'sobrecosto_todos',0),
                                               (61,'cierre_automatico',1),
                                               (65,'zona_horaria','America/Bogota'),
                                               (66,'multiples_vendedores',0),
                                               (68, 'precio_almacen','0'),
                                               (70,'enviar_factura','no'),
                                               (71,'facturar_mesas','no'),
                                               (74, 'costo_promedio', '1'),
                                               (77,'municipio',''),
                                               (78,'codigo_postal',''),
                                               (62, 'plantilla_general','tirilla'),
                                               (81,'numero_devolucion',1),
                                               (82,'prefijo_devolucion','NC'),
                                               (82, 'offline', 'backup'),
                                               (83,'plan_separe','no'),
                                               (90,'eliminar_producto_comanda','no'),
                                               (146,'permitir_formas_pago_pendiente','no'),
                                               (147,'impresion_rapida','no'),
                                               (148,'domicilios','no'),
                                               (157,'quick_service','no'),
                                               (158,'quick_service_command','no'),
                                               (159,'puntos_correo_bienvenida',0),
                                               (161,'table_selected',4),
                                               (162,'publicidad_venty',1),
                                               (174,'tipo_separador_miles',','),
                                               (175,'tipo_separador_decimales','.');";

                $sql_formas_pago_pendiente = "CREATE TABLE IF NOT EXISTS `ventas_forma_pago_pendiente` (
                    `id_venta` int(11) NOT NULL,
                    `id_almacen` int(11) NOT NULL,
                    `id_user` int(11) NOT NULL,
                    PRIMARY KEY (`id_venta`,`id_almacen`,`id_user`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

                mysql_query($sql_almacen, $conn);

                mysql_query($sql_categoria, $conn);

                mysql_query($categoria_query, $conn);

                mysql_query($sql_clientes, $conn);

                mysql_query($sql_detalle_venta, $conn);

                mysql_query($sql_productos, $conn);

                mysql_query($productosf, $conn);

                mysql_query($sql_impuestos, $conn);

                mysql_query($insert_impusto, $conn);

                mysql_query($sql_nothing_tax, $conn);

                mysql_query($sql_opciones, $conn);

                mysql_query($sql_pagos, $conn);

                mysql_query($sql_proveedores, $conn);

                mysql_query($sql_proformas, $conn);

                mysql_query($sql_presupuestos, $conn);

                mysql_query($sql_presupuestos_detalles, $conn);

                mysql_query($sql_facturas, $conn);

                mysql_query($sql_facturas_detalles, $conn);

                mysql_query($stock_actual, $conn);

                mysql_query($stock_diario, $conn);

                mysql_query($stock_historial, $conn);

                mysql_query($usuario_almacen, $conn);

                mysql_query($vendedor, $conn);

                mysql_query($forma_pago, $conn);

                mysql_query($venta, $conn);

                mysql_query($ventas_pagos, $conn);

                mysql_query($rol, $conn);

                mysql_query($rol_permisos, $conn);

                mysql_query($sql_venta_online, $conn);

                mysql_query($sql_prod_venta_online, $conn);

                mysql_query($sql_auditoria_inventario, $conn);

                mysql_query($sql_detalle_auditoria, $conn);

                mysql_query($sql_formas_pago_pendiente, $conn);

                //Creamos los roles
                $q_rol = "INSERT INTO `rol` (`id_rol`, `nombre_rol`, `descripcion`) VALUES (1, 'Administrador','Administrador de la tienda'),(2, 'Vendedor','Vendedor de la tienda'),(3, 'Inventario','Inventario') ,(4, 'Informes','Informes')";
                mysql_query($q_rol, $conn);

                //Asignamos los permisos correspondientes a los roles
                $q_rol_permisos = "insert  into `permiso_rol`(`id_permiso_rol`,`id_permiso`,`id_rol`) values(1,10,2),(2,11,2),(3,12,2),(4,13,2),(5,23,2),(6,24,2),(7,25,2),(8,27,2),(9,28,2),(10,29,2),(11,32,2),(12,33,2),(13,34,2),(14,54,2),(15,57,2),(16,66,2),(17,1009,2),(18,1010,2),(19,1023,2),(20,1035,2),(21,1036,2),(22,1037,2),(23,1038,2),(24,49,3),(25,50,3),(26,51,3),(27,52,3),(28,62,3),(29,63,3),(30,64,3),(31,67,3),(32,68,3),(33,70,3),(34,71,3),(35,1007,3),(36,1019,3),(37,1020,3),(38,1021,3),(39,1,4),(40,73,4),(41,74,4),(42,75,4),(43,76,4),(44,77,4),(45,78,4),(46,79,4),(47,80,4),(48,81,4),(49,82,4),(50,84,4),(51,85,4),(52,86,4),(53,87,4),(54,88,4),(55,89,4),(56,90,4),(57,91,4),(58,92,4),(59,93,4),(60,94,4),(61,95,4),(62,1001,4),(63,1002,4),(64,1011,4),(65,1012,4),(66,1013,4),(67,1014,4),(68,1015,4),(69,1016,4),(70,1017,4),(71,1018,4),(72,1034,4)";
                mysql_query($q_rol_permisos, $conn);

                $q_rol = "INSERT INTO `rol` (`id_rol`, `nombre_rol`, `descripcion`) VALUES (1, 'Administrador','Administrador de la tienda'),(2, 'Vendedor','Vendedor de la tienda'),(3, 'Inventario','Inventario') ,(4, 'Informes','Informes')";
                mysql_query($q_rol, $conn);

                //Creamos las formas de pago
                $q_formapago = "INSERT INTO `forma_pago` (`id`, `codigo`, `nombre`,`activo`) VALUES (1, 'efectivo','Efectivo',1),(2, 'Credito','Credito',1),(3, 'Gift_Card','GiftCard',1),(4, 'nota_credito','Nota Credito',1),(5, 'Puntos','Puntos',1),(6, 'tarjeta_credito','Tarjeta de credito',1),(7, 'tarjeta_debito','Tarjeta debito',1)";
                mysql_query($q_formapago, $conn);

                mysql_query($filtros_facturas, $conn);

                mysql_query($filtros_facturas_detalles, $conn);

                mysql_query($filtros_pagos, $conn);

                mysql_query($filtros_presupuestos, $conn);

                mysql_query($filtros_detalles, $conn);

                mysql_query($filtros_productos, $conn);

                mysql_query($filtros_proformas, $conn);

                mysql_query($filtros_stock_actual, $conn);

                mysql_query($filtros_stock_diario, $conn);

                mysql_query($filtros_ventas, $conn);

                mysql_query($filtros_usuario_almacen, $conn);

                mysql_query($filtro_insert_almacen, $conn);
                $id_almacen_creado = mysql_insert_id($conn);

                //mysql_query($filtro_insert_usuario_almacen, $conn);

                mysql_query($insert_opciones, $conn);

                /*Creamos las tablas para el restaurante*/
                $sql = "CREATE TABLE IF NOT EXISTS `producto_adicional` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_producto` int(11) DEFAULT NULL,
                `id_adicional` int(11) DEFAULT NULL,
                `cantidad` double DEFAULT NULL,
                `precio` double DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS producto_ingredientes (
                                   id int(11) NOT NULL AUTO_INCREMENT,
                                   id_producto int(11) DEFAULT NULL,
                                   id_ingrediente int(11) DEFAULT NULL,
                                   cantidad double DEFAULT NULL,
                                   PRIMARY KEY (id)
                                 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS `secciones_almacen` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'incremental de la tabla y clave primaria',
                `fecha_creacion` datetime DEFAULT NULL COMMENT 'fecha en que se crea el registro',
                `creado_por` int(11) DEFAULT NULL COMMENT 'el usuario que creo que el registro',
                `fecha_modificacion` datetime DEFAULT NULL COMMENT 'fecha de ultima actualizacion del registro',
                `modificado_por` int(11) DEFAULT NULL COMMENT 'el usuario que realizo la ultima modificacion',
                `activo` tinyint(4) DEFAULT '1' COMMENT 'activo 1 desactivo 0',
                `id_almacen` int(11) DEFAULT NULL COMMENT 'referencia la tabla almacenes, el almacen al que pertenece esta seccion',
                `codigo_seccion` varchar(10) DEFAULT NULL COMMENT 'codigo identificador para informes',
                `nombre_seccion` varchar(50) DEFAULT NULL COMMENT 'nombre de la seccion o piso',
                `descripcion_seccion` varchar(500) DEFAULT NULL COMMENT 'descripcion de la seccion de mesas',
                PRIMARY KEY (`id`),
                KEY `fk_almacen_seccion` (`id_almacen`),
                CONSTRAINT `fk_almacen_seccion` FOREIGN KEY (`id_almacen`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='para las mesas contiene las secciones o pisos donde hay mesa';";

                mysql_query($sql, $conn);
                $sql = "CREATE TABLE IF NOT EXISTS producto_modificacion (
                                   id int(11) NOT NULL AUTO_INCREMENT,
                                   id_producto int(11) DEFAULT NULL,
                                   nombre varchar(100) DEFAULT NULL,
                                   PRIMARY KEY (id)
                                 ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS impresoras_restaurante (
                                            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                            nombre VARCHAR(20) DEFAULT NULL,
                                            codigo VARCHAR(15) DEFAULT NULL,
                                            PRIMARY KEY (id)
                                            ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS impresora_rest_categoria_almacen (
                                       id_impresora INT(11) NOT NULL,
                                       id_categoria INT(11) NOT NULL,
                                       id_almacen INT(11) NOT NULL,
                                       PRIMARY KEY (id_impresora,id_categoria,id_almacen)
                                       ) ENGINE=INNODB DEFAULT CHARSET=latin1;";
                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS `mesas_secciones` (
                `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de la tabla autoincremental',
                `fecha_creacion` datetime DEFAULT NULL COMMENT 'fecha en que se crea el registro',
                `creado_por` int(11) DEFAULT NULL COMMENT 'usuario que creo el registro',
                `fecha_modificacion` datetime DEFAULT NULL COMMENT 'ultima fecha en que se modifico el registro',
                `modificado_por` int(11) DEFAULT NULL COMMENT 'identificador del usuario que realizo la ultima modificacion',
                `activo` tinyint(4) DEFAULT '1' COMMENT 'activo =1 desactivado =0',
                `id_seccion` int(11) DEFAULT NULL COMMENT 'referencia la tabla secciones_almacen a que seccion pertenece esta mesa',
                `codigo_mesa` varchar(10) DEFAULT NULL COMMENT 'codigo de la mesa',
                `nombre_mesa` varchar(100) DEFAULT NULL COMMENT 'nombre de la mesa, el que vera el usuario',
                `nota_comanda` text,
                `vendedor_estacion` int(11) DEFAULT NULL COMMENT 'para saber que vendedor la tiene ocupada mientras esta en la estacion pedido',
                `consecutivo_orden_restaurante` int(11) NOT NULL DEFAULT '0' COMMENT 'Campo para saber el numero de orden de pedidos para las mesas',
                `comensales` int(10) unsigned DEFAULT '1',
                PRIMARY KEY (`id`),
                KEY `fk_seccion_mesa` (`id_seccion`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='listado de mesas que se tiene en las diferentes secciones';";

                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS `orden_producto_restaurant` (
                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                 `order_producto` varchar(250) DEFAULT NULL,
                                 `order_modificacion` varchar(250) DEFAULT NULL,
                                 `order_adiciones` varchar(250) DEFAULT NULL,
                                 `zona` int(11) DEFAULT NULL,
                                 `mesa_id` int(11) DEFAULT NULL,
                                 `estado` int(11) DEFAULT NULL,
                                 `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                                 `update_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
                                 `cantidad` double DEFAULT NULL,
                                 PRIMARY KEY (`id`)
                               ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

                mysql_query($sql, $conn);

                $sql = "CREATE TABLE IF NOT EXISTS `ventas_forma_pago_pendiente` (
                `id_venta` int(11) NOT NULL,
                `id_almacen` int(11) NOT NULL,
                `id_user` int(11) NOT NULL,
                PRIMARY KEY (`id_venta`,`id_almacen`,`id_user`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

                mysql_query($sql, $conn);

                /*----------------------------------------------------------------/*
                | Julian 30/07/2014                                               |
                /*-----------------------------------------------------------------

                /*Nueva campo en tabla clientes*/
                $clientes_grupo_id = "ALTER TABLE `clientes` ADD COLUMN `grupo_clientes_id` integer not null default 1";
                mysql_query($clientes_grupo_id, $conn);

                /*Cliente general*/
                $insert_cliente_general = "INSERT INTO `clientes` (`id_cliente`,`pais`, `nombre_comercial`, `nif_cif`, `grupo_clientes_id`) VALUES ('-1','Colombia', 'general', '0', '1')";
                mysql_query($insert_cliente_general, $conn);

                /*Nueva tabla grupo de clientes*/
                $grupo_clientes = "CREATE TABLE `grupo_clientes` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `nombre` VARCHAR(15) NOT NULL DEFAULT 'Unknown',
                     PRIMARY KEY (`id`)
                     /*foreign key (id) references producto(id)*/
                   ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
                mysql_query($grupo_clientes, $conn);

                /*Sin grupo*/
                $sin_grupo = "INSERT INTO `grupo_clientes` VALUES (1,'sin grupo')";
                mysql_query($sin_grupo, $conn);

                /*Estado de venta Anulada - activa*/
                $venta_estado = "ALTER TABLE `venta` ADD COLUMN `estado` INT NULL DEFAULT 0 AFTER `total_venta`";
                mysql_query($venta_estado, $conn);

                /*Lista precios*/
                $lista_precios = "CREATE TABLE `lista_precios` (
                       `id` INT NOT NULL AUTO_INCREMENT,
                       `nombre` VARCHAR(45) NULL,
                       `grupo_cliente_id` INT NULL,
                       `almacen_id` INT NULL,
                       `start` DATE NULL,
                       `end` DATE NULL,
                       PRIMARY KEY (`id`),
                       foreign key (grupo_cliente_id) references grupo_clientes(id),
                       foreign key (almacen_id) references almacen(id)
                   );";
                mysql_query($lista_precios, $conn);

                /*Lista detalle precios*/
                $lista_detalle_precios = "CREATE TABLE `lista_detalle_precios`(
                       `id` INT NOT NULL AUTO_INCREMENT,
                       `id_producto` INT NULL,
                       `id_impuesto` INT NULL,
                       `id_lista_precios` INT NULL,
                       `precio` double DEFAULT NULL,
                       PRIMARY KEY (`id`)
                   );";
                mysql_query($lista_detalle_precios, $conn);

                /*Tabla anuladas*/
                $ventas_anuladas = "CREATE TABLE `ventas_anuladas` (
                     `id_venta_anulada` int(11) NOT NULL AUTO_INCREMENT,
                     `usuario_id` int(11) NOT NULL,
                     `fecha` datetime NOT NULL,
                     `motivo` text NOT NULL,
                     `venta_id` int(11) NOT NULL,
                     PRIMARY KEY (`id_venta_anulada`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
                mysql_query($ventas_anuladas, $conn);

                /*Comision en vendedor*/
                $comision_vendedor = "ALTER TABLE vendedor ADD `comision` double NOT NULL DEFAULT '0'";
                mysql_query($comision_vendedor, $conn);

                /*Movimiento detalle*/
                $movimiento_detalle = "CREATE TABLE `movimiento_detalle` (
                `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                `id_inventario` int(11) NOT NULL,
                `codigo_barra` varchar(254) NOT NULL,
                `cantidad` double NOT NULL,
                `precio_compra` double NOT NULL,
                `existencias` double NOT NULL,
                `nombre` varchar(254) NOT NULL,
                `total_inventario` double NOT NULL,
                `producto_id` int(11) DEFAULT NULL,
                `precio_venta_p` double DEFAULT NULL COMMENT 'precio de venta sin impuesto en orden de compra para cambiar',
                `precio_venta_actual` double DEFAULT NULL COMMENT 'precio de venta sin impuesto del producto actual',
                PRIMARY KEY (`id_detalle`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
                mysql_query($movimiento_detalle, $conn);

                /*Movimiento inventario*/
                $movimiento_inventario = "CREATE TABLE `movimiento_inventario` (
                       `id` int(11) NOT NULL AUTO_INCREMENT,
                       `fecha` datetime NOT NULL,
                       `almacen_id` int(11) NOT NULL,
                       `almacen_traslado_id` int(11) DEFAULT NULL,
                       `tipo_movimiento` varchar(254) NOT NULL,
                       `codigo_factura` varchar(254) DEFAULT NULL,
                       `user_id` int(11) NOT NULL,
                       `total_inventario` double NOT NULL,
                       `nota` TEXT,
                       `proveedor_id` int(11) DEFAULT NULL,
                       PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
                mysql_query($movimiento_inventario, $conn);

                $plantilla_cotizacion = "INSERT INTO `opciones` (`nombre_opcion`, `valor_opcion`) VALUES ('plantilla_cotizacion', 'Estandar');";
                mysql_query($plantilla_cotizacion, $conn);

                /*INGREDIENTES =====================================================================================================*/

                $producto = "ALTER TABLE `producto`
                   ADD COLUMN `material` TINYINT(1) NULL DEFAULT '0' AFTER `imagen`,
                   ADD COLUMN `ingredientes` TINYINT(1) NULL DEFAULT '0' AFTER `material`,
                   ADD COLUMN `unidad_id` INT NULL DEFAULT '1' AFTER `ingredientes`;";
                mysql_query($producto, $conn);

                $producto_ingredientes = "CREATE TABLE `producto_ingredientes` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `id_producto` int(11) DEFAULT NULL,
                     `id_ingrediente` int(11) DEFAULT NULL,
                     `cantidad` double,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
                mysql_query($producto_ingredientes, $conn);

                $unidades = "CREATE TABLE `unidades` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `nombre` varchar(45) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
                mysql_query($unidades, $conn);

                $unidades_default = "INSERT INTO `unidades` VALUES (1,'unidad'),(2,'gramo'),(3,'kilogramo'),(4,'libra'),(5,'litro'),(6,'mililitro'),(7,'onza');";
                mysql_query($unidades_default, $conn);
                /*....................................................................................................................*/

                /*Factura estandar y clasica */

                $tipo_factura = "INSERT INTO `opciones` (`nombre_opcion`, `valor_opcion`) VALUES ('tipo_factura', 'estandar');";
                mysql_query($tipo_factura, $conn);

                $tipo_factura_venta = "ALTER TABLE `venta` ADD COLUMN `tipo_factura` VARCHAR(10) NULL DEFAULT 'estandar' AFTER `estado`;";
                mysql_query($tipo_factura_venta, $conn);

                $venta_fecha_vencimiento = "ALTER TABLE `venta` ADD COLUMN `fecha_vencimiento` DATETIME NULL AFTER `tipo_factura`;";
                mysql_query($venta_fecha_vencimiento, $conn);

                /*Tipo producto*/

                $producto_tipo = "CREATE TABLE `producto_tipo` (
                     `id` INT NOT NULL ,
                     `nombre` VARCHAR(45) NULL,
                     PRIMARY KEY (`id`)
                   );";
                mysql_query($producto_tipo, $conn);

                $producto_tipo_AI = "ALTER TABLE `producto_tipo` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;";
                mysql_query($producto_tipo_AI, $conn);

                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('unico');";
                mysql_query($insert_producto_tipo, $conn);
                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('compuesto');";
                mysql_query($insert_producto_tipo, $conn);
                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('combo');";
                mysql_query($insert_producto_tipo, $conn);

                /*COMBO*/
                $alter_producto = "ALTER TABLE `producto` ADD COLUMN `combo` INT(11) NULL DEFAULT '0' AFTER `ingredientes`;";
                mysql_query($alter_producto, $conn);

                $producto_combos = "CREATE TABLE `producto_combos` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_combo` int(11) DEFAULT NULL,
                `id_producto` int(11) DEFAULT NULL,
                `cantidad` double DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysql_query($producto_combos, $conn);

                $alter_producto = "ALTER TABLE `producto`
                     ADD COLUMN `combo` TINYINT(1) NULL DEFAULT '0' AFTER `material`;
                   ";
                mysql_query($alter_producto, $conn);

                //Pago servicios
                $pago = "CREATE TABLE `pago` (
                     `id_pago` int(11) NOT NULL AUTO_INCREMENT,
                     `id_factura` int(11) DEFAULT NULL,
                     `fecha_pago` date NOT NULL,
                     `cantidad` double NOT NULL,
                     `tipo` varchar(254) NOT NULL,
                     `notas` text NOT NULL,
                     `importe_retencion` double DEFAULT NULL,
                     PRIMARY KEY (`id_pago`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
                mysql_query($pago, $conn);

                $alter_venta = "ALTER TABLE `venta`
                   ADD COLUMN `tipo_factura` VARCHAR(45) NULL DEFAULT 'estandar' AFTER `estado`,
                   ADD COLUMN `fecha_vencimiento` VARCHAR(45) NULL DEFAULT NULL AFTER `tipo_factura`;";

                mysql_query($alter_venta, $conn);

                $comision = "ALTER TABLE `vendedor`
                   CHANGE COLUMN `comision` `comision` double NULL DEFAULT 0 ";

                mysql_query($comision, $conn);

                $comision = "ALTER TABLE `detalle_venta`
                   ADD COLUMN `descripcion_producto` TEXT NULL AFTER `nombre_producto`;";

                mysql_query($comision, $conn);

                $detalle_orden_compra = "
               CREATE TABLE `detalle_orden_compra` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `venta_id` int(11) NOT NULL,
                `codigo_producto` varchar(15) DEFAULT NULL,
                `nombre_producto` varchar(254) DEFAULT NULL,
                `descripcion_producto` text,
                `unidades` double DEFAULT NULL,
                `precio_venta` double DEFAULT NULL,
                `descuento` double DEFAULT NULL,
                `impuesto` double DEFAULT NULL,
                `impuesto_id` int(11) DEFAULT NULL,
                `linea` varchar(254) DEFAULT NULL,
                `margen_utilidad` double DEFAULT NULL,
                `activo` tinyint(1) DEFAULT '1',
                `id_unidad` int(11) DEFAULT NULL,
                `producto_id` int(11) DEFAULT NULL,
                `precio_venta_p` double DEFAULT NULL COMMENT 'precio de venta sin impuesto en orden de compra para cambiar',
                `precio_venta_actual` double DEFAULT NULL COMMENT 'precio de venta sin impuesto del producto actual',
                PRIMARY KEY (`id`),
                KEY `detalle_venta_FKIndex1` (`venta_id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
                     ";

                mysql_query($detalle_orden_compra, $conn);

                $orden_compra = "
               CREATE TABLE `orden_compra` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `almacen_id` int(11) DEFAULT NULL,
                `forma_pago_id` int(11) DEFAULT NULL,
                `factura` varchar(254) DEFAULT NULL,
                `fecha` datetime DEFAULT NULL,
                `usuario_id` int(11) DEFAULT NULL,
                `cliente_id` int(11) DEFAULT NULL,
                `vendedor` int(11) DEFAULT NULL,
                `cambio` varchar(254) DEFAULT NULL,
                `activo` tinyint(1) DEFAULT '1',
                `total_venta` double NOT NULL,
                `estado` int(11) DEFAULT '0',
                `tipo_factura` varchar(30) DEFAULT 'estandar',
                `fecha_vencimiento` date DEFAULT NULL,
                `nota` text NOT NULL,
                `motivo` text,
                `id_user_anulacion` int(11) DEFAULT NULL,
                `fecha_anulacion` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `venta_FKIndex1` (`forma_pago_id`),
                KEY `venta_FKIndex2` (`almacen_id`),
                KEY `venta_cliente_id` (`cliente_id`),
                KEY `venta_vendedor_fk_idx` (`vendedor`)
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
                     ";

                mysql_query($orden_compra, $conn);

                $pago_orden_compra = "
CREATE TABLE `pago_orden_compra` (
 `id_pago` int(11) NOT NULL AUTO_INCREMENT,
 `id_factura` int(11) DEFAULT NULL,
 `fecha_pago` date NOT NULL,
 `cantidad` double NOT NULL,
 `tipo` varchar(254) NOT NULL,
 `notas` text NOT NULL,
 `importe_retencion` double DEFAULT NULL,
 PRIMARY KEY (`id_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                     ";

                mysql_query($pago_orden_compra, $conn);

                $cambios1 = "ALTER TABLE `venta` ADD `nota` TEXT NOT NULL;";
                mysql_query($cambios1, $conn);

                $cambios_promociones_venta = "ALTER TABLE `venta` ADD COLUMN `promocion` TEXT NULL AFTER `nota`;";
                mysql_query($cambios_promociones_venta, $conn);

                $cambios2 = "ALTER TABLE `proformas` ADD `id_almacen` INT(30) NOT NULL; ";
                mysql_query($cambios2, $conn);

                $cambios3 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (35, 'numero', 'no');";
                mysql_query($cambios3, $conn);

                $cambios4 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (36, 'sobrecosto', 'no');";
                mysql_query($cambios4, $conn);

                $cambios5 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (37, 'multiples_formas_pago', 'si'); ";
                mysql_query($cambios5, $conn);

                $cambios6 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (38, 'vendedor_impresion', '1'); ";
                mysql_query($cambios6, $conn);

                $cambios7 = "ALTER TABLE `proformas` ADD `forma_pago` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios7, $conn);

                $cambios8 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (39, 'valor_caja', 'si'); ";
                mysql_query($cambios8, $conn);

                if ($this->input->post('TipoDocumento') != null && $this->input->post('TipoDocumento') != '') {
                    $TipoDocumento = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (40, 'documento', '" . $this->input->post('TipoDocumento') . "'); ";
                    mysql_query($TipoDocumento, $conn);
                } else {
                    $cambiosj1 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (40, 'documento', 'NIT'); ";
                    mysql_query($cambiosj1, $conn);
                }

                if ($this->input->post('NumeroDocumento') != null && $this->input->post('NumeroDocumento') != '') {
                    $NumeroDocumento = "UPDATE `opciones` SET valor_opcion = '" . $this->input->post('NumeroDocumento') . "' WHERE id = 30";
                    mysql_query($NumeroDocumento, $conn);
                }

                if ($this->input->post('Pais') != null && $this->input->post('Pais') != '') {
                    $Pais = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (64, 'pais', '" . $this->input->post('Pais') . "'); ";
                    mysql_query($Pais, $conn);
                }

                if ($this->input->post('TipoNegocio') != null && $this->input->post('TipoNegocio') != '') {
                    $TipoNegocio = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (84, 'tipo_negocio', '" . $this->input->post('TipoNegocio') . "'); ";
                    mysql_query($TipoNegocio, $conn);
                }

                $cambios9 = "ALTER TABLE `almacen` ADD `ciudad` VARCHAR(150) NOT NULL, ADD `bodega` tinyint(1) unsigned DEFAULT 0;";
                mysql_query($cambios9, $conn);

                $cambios10 = "
CREATE TABLE IF NOT EXISTS `cajas` (
 `id` int(100) NOT NULL AUTO_INCREMENT,
 `nombre` varchar(100) NOT NULL,
 `id_Almacen` int(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios10, $conn);

                /*Creacion Caja*/
                $q_caja = "INSERT INTO `cajas` (`id`, `nombre`, `id_Almacen`) VALUES (1, 'caja1', " . $id_almacen_creado . ")";
                mysql_query($q_caja, $conn);
                $id_caja_creada = mysql_insert_id($conn);

                $cambios11 = "
CREATE TABLE IF NOT EXISTS `cierres_caja` (
 `id` int(200) NOT NULL AUTO_INCREMENT,
 `fecha` date NOT NULL,
 `hora_apertura` time NOT NULL,
 `hora_cierre` time NOT NULL,
 `id_Usuario` int(100) NOT NULL,
 `id_Caja` int(200) NOT NULL,
 `id_Almacen` int(50) NOT NULL,
 `total_egresos` varchar(200) NOT NULL,
 `total_ingresos` varchar(200) NOT NULL,
 `total_cierre` varchar(200) NOT NULL,
 `arqueo` double DEFAULT NULL,
 `fecha_fin_cierre` timestamp NULL DEFAULT NULL,
 `fecha_cierre` date DEFAULT NULL,
 `consecutivo` int(11) DEFAULT NULL,

 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios11, $conn);

                $cambios12 = "
               CREATE TABLE IF NOT EXISTS `cuentas_dinero` (
                `id` int(100) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(200) NOT NULL,
                `tipo_cuenta` varchar(100) NOT NULL,
                `numero` varchar(50) NOT NULL,
                `banco` varchar(100) NOT NULL,
                `tipo_bancaria` varchar(100) NOT NULL,
                `id_almacen` int(50) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1; ";
                mysql_query($cambios12, $conn);

                $cambios13 = "
CREATE TABLE IF NOT EXISTS `movimientos_cierre_caja` (
 `id` int(255) NOT NULL AUTO_INCREMENT,
 `Id_cierre` int(200) NOT NULL,
 `hora_movimiento` time NOT NULL,
 `id_usuario` int(100) NOT NULL,
 `tipo_movimiento` varchar(100) NOT NULL,
 `valor` varchar(200) NOT NULL,
 `forma_pago` varchar(200) NOT NULL,
 `numero` varchar(100) NOT NULL,
 `id_mov_tip` int(150) NOT NULL,
 `tabla_mov` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;   ";
                mysql_query($cambios13, $conn);

                $cambios14 = "ALTER TABLE `usuario_almacen` ADD `id_Caja` INT(100) NOT NULL ; ";
                mysql_query($cambios14, $conn);

                $wizard_type_businness = "ALTER TABLE `usuario_almacen` ADD COLUMN `wizard_tiponegocio` INT NULL DEFAULT 0 AFTER `id_Caja`";
                mysql_query($wizard_type_businness, $conn);

                //Creamos las dos cuentas asociadas a la caja
                $q_cuentas = "INSERT INTO `cuentas_dinero` (`id`, `nombre`, `tipo_cuenta`,`numero`,`banco`,`tipo_bancaria`,`id_almacen`) VALUES (1, 'caja menor','Caja Menor',NULL,NULL,'Ahorro'," . $id_almacen_creado . "),(2, 'Caja Bancos','Banco',NULL,NULL,NULL," . $id_almacen_creado . ")";
                mysql_query($q_cuentas, $conn);

                $q_usuario_almacen = "INSERT INTO `usuario_almacen` (`id`, `usuario_id`, `almacen_id`,`id_Caja`) VALUES (1, " . $id . ", 1,1)";
                mysql_query($q_usuario_almacen, $conn);

                $cambios15 = "ALTER TABLE `proformas` ADD `id_cuenta_dinero` INT(100) NOT NULL ; ";
                mysql_query($cambios15, $conn);

                $cambios16 = "ALTER TABLE `producto` ADD `stock_maximo` double NOT NULL ; ";
                mysql_query($cambios16, $conn);

                $cambios17 = "ALTER TABLE `producto` ADD `fecha_vencimiento` VARCHAR(100) NOT NULL ; ";
                mysql_query($cambios17, $conn);

                $cambios18 = "ALTER TABLE `producto` ADD `ubicacion` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios18, $conn);

                $cambios19 = "ALTER TABLE `producto` ADD `ganancia` double NOT NULL ; ";
                mysql_query($cambios19, $conn);

                $cambios20 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (41, 'filtro_ciudad', 'no');";
                mysql_query($cambios20, $conn);

                $cambios21 = "CREATE TABLE `factura_espera` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `almacen_id` int(11) DEFAULT NULL,
 `forma_pago_id` int(11) DEFAULT NULL,
 `factura` varchar(254) DEFAULT NULL,
 `no_factura` int(50) DEFAULT NULL,
 `fecha` datetime DEFAULT NULL,
 `usuario_id` int(11) DEFAULT NULL,
 `cliente_id` int(11) DEFAULT NULL,
 `vendedor` int(11) DEFAULT NULL,
 `cambio` varchar(254) DEFAULT NULL,
 `activo` tinyint(1) DEFAULT '1',
 `total_venta` double NOT NULL,
 `estado` int(11) DEFAULT '0',
 `tipo_factura` varchar(10) DEFAULT 'estandar',
 `fecha_vencimiento` datetime DEFAULT NULL,
 `nota` text NOT NULL,
 `sobrecosto` double NOT NULL,
 `id_mesa` int(11) DEFAULT NULL COMMENT 'referencia la tabla mesas_secciones cual es la mesa a la que pertenece esta venta en espera',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysql_query($cambios21, $conn);
                $cambios22 = "insert  into `factura_espera`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`no_factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`,`nota`,`sobrecosto`) values (-1,0,NULL,'Venta No ',NULL,'2015-06-24 00:32:06',0,-1,NULL,NULL,1,56010,0,'estandar','2015-06-24 00:32:06','',0); ";
                mysql_query($cambios22, $conn);

                $cambios23 = "CREATE TABLE `detalle_factura_espera` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `venta_id` int(11) NOT NULL,
 `codigo_producto` varchar(15) DEFAULT NULL,
 `nombre_producto` varchar(254) DEFAULT NULL,
 `descripcion_producto` text,
 `unidades` varchar(150) DEFAULT NULL,
 `precio_venta` double DEFAULT NULL,
 `descuento` double DEFAULT NULL,
 `impuesto` double DEFAULT NULL,
 `impuesto_id` int(11) DEFAULT NULL,
 `linea` varchar(254) DEFAULT NULL,
 `margen_utilidad` double DEFAULT NULL,
 `activo` tinyint(1) DEFAULT '1',
 `id_producto` int(100) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `venta_id` (`venta_id`),
 CONSTRAINT `detalle_factura_espera_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `factura_espera` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1; ";
                mysql_query($cambios23, $conn);

                // AGREGAR NUEVAS OPCIONES con ETIENDA
                $cambios24 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`)
                    VALUES (42, 'comanda', 'no'),
                           (43, 'etienda', 'si');";

                mysql_query($cambios24, $conn);

                $cambios26 = "ALTER TABLE `clientes` ADD `tipo_identificacion` VARCHAR(70) NOT NULL DEFAULT 'CC' AFTER `razon_social`;";

                mysql_query($cambios26, $conn);

                mysql_query("ALTER TABLE producto DROP COLUMN tienda;");
                mysql_query("ALTER TABLE producto ADD tienda TINYINT(1) NULL DEFAULT '0';");
                mysql_query("ALTER TABLE producto ADD muestraexist TINYINT(1) NULL DEFAULT '0';");
                mysql_query("ALTER TABLE producto ADD vendernegativo TINYINT(1) NULL DEFAULT '0';");

                mysql_query("ALTER TABLE almacen ADD nit VARCHAR(20), ADD resolucion_factura VARCHAR(20);");

                mysql_query("ALTER TABLE detalle_orden_compra ADD producto_id INT;");
                mysql_query("ALTER TABLE detalle_venta ADD producto_id INT;");
                mysql_query("ALTER TABLE movimiento_detalle ADD producto_id INT;");

                mysql_query("UPDATE detalle_venta, producto SET detalle_venta.producto_id = producto.id WHERE detalle_venta.codigo_producto = producto.codigo;");
                mysql_query("UPDATE detalle_venta, producto SET detalle_venta.producto_id = producto.id WHERE detalle_venta.nombre_producto = producto.nombre;");
                mysql_query("UPDATE detalle_orden_compra, producto SET detalle_orden_compra.producto_id = producto.id WHERE detalle_orden_compra.codigo_producto = producto.codigo;");
                mysql_query("UPDATE detalle_orden_compra, producto SET detalle_orden_compra.producto_id = producto.id WHERE detalle_orden_compra.nombre_producto = producto.nombre;");
                mysql_query("UPDATE detalle_orden_compra, producto SET detalle_orden_compra.producto_id = producto.id WHERE detalle_orden_compra.nombre_producto = producto.nombre;");

                //----------------------------------------------------------------------------------------
                mysql_query("INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (NULL, 'punto_valor', '0');");

                mysql_query("CREATE TABLE IF NOT EXISTS `cliente_plan_punto` (  `id` int(100) NOT NULL AUTO_INCREMENT,  `id_cliente` int(100) NOT NULL,  `plan_id` int(100) NOT NULL,  `codinterna` varchar(100) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

                mysql_query("CREATE TABLE IF NOT EXISTS `plan_puntos` (  `id_puntos` int(50) NOT NULL AUTO_INCREMENT,  `nombre` varchar(100) NOT NULL,  `puntos` double NOT NULL,  `valor` double NOT NULL,`iva` varchar(10) NOT NULL,  PRIMARY KEY (`id_puntos`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

                mysql_query("CREATE TABLE IF NOT EXISTS `puntos_acumulados` (  `id` int(100) NOT NULL AUTO_INCREMENT,  `fecha` date NOT NULL,  `factura` int(100) NOT NULL,  `total_factura` double NOT NULL, `puntos`  double NOT NULL,  `cliente` int(100) NOT NULL,  `tipo` varchar(50) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

                mysql_query("CREATE TABLE IF NOT EXISTS `plan_separe_detalle` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `venta_id` int(11) NOT NULL,
 `codigo_producto` varchar(15) DEFAULT NULL,
 `nombre_producto` varchar(254) DEFAULT NULL,
 `descripcion_producto` text,
 `unidades` double DEFAULT NULL,
 `precio_venta` double DEFAULT NULL,
 `descuento` double DEFAULT NULL,
 `impuesto` double DEFAULT NULL,
 `linea` varchar(254) DEFAULT NULL,
 `margen_utilidad` double DEFAULT NULL,
 `activo` tinyint(1) DEFAULT '1',
 `producto_id` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `detalle_venta_FKIndex1` (`venta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

                mysql_query("CREATE TABLE IF NOT EXISTS `plan_separe_factura` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `almacen_id` int(11) DEFAULT NULL,
 `forma_pago_id` int(11) DEFAULT NULL,
 `factura` varchar(254) DEFAULT NULL,
 `fecha` datetime DEFAULT NULL,
 `usuario_id` int(11) DEFAULT NULL,
 `cliente_id` int(11) DEFAULT NULL,
 `vendedor` int(11) DEFAULT NULL,
 `cambio` varchar(254) DEFAULT NULL,
 `activo` tinyint(1) DEFAULT '1',
 `total_venta` double NOT NULL,
 `estado` int(11) DEFAULT '0',
 `tipo_factura` varchar(10) DEFAULT 'estandar',
 `fecha_vencimiento` datetime DEFAULT NULL,
 `nota` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

                mysql_query("CREATE TABLE IF NOT EXISTS `plan_separe_pagos` (
 `id_pago` int(11) NOT NULL AUTO_INCREMENT,
 `id_venta` int(11) NOT NULL,
 `forma_pago` varchar(254) NOT NULL,
 `valor_entregado` double NOT NULL,
 `cambio` double NOT NULL,
 `fecha` datetime DEFAULT NULL,
 PRIMARY KEY (`id_pago`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

                /*PROMOCIONES*/
                mysql_query("CREATE TABLE IF NOT EXISTS `promociones` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `nombre` varchar(50) NOT NULL,
                `tipo` enum('progresivo','individual','cantidad') NOT NULL,
                `fecha_inicial` date NOT NULL,
                `fecha_final` date NOT NULL,
                `hora_inicio` time NOT NULL,
                `hora_fin` time NOT NULL,
                `dias` varchar(16) NOT NULL,
                `activo` tinyint(1) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `promociones_almacenes` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `id_promocion` bigint(20) unsigned NOT NULL,
                `id_almacen` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `promociones_almacenes_id_almacen_foreign` (`id_almacen`),
                KEY `promociones_almacenes_id_promocion_foreign` (`id_promocion`),
                CONSTRAINT `promociones_almacenes_id_almacen_foreign` FOREIGN KEY (`id_almacen`) REFERENCES `almacen` (`id`) ON DELETE CASCADE,
                CONSTRAINT `promociones_almacenes_id_promocion_foreign` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id`) ON DELETE CASCADE
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("ALTER TABLE `promociones_almacenes`
                     ADD CONSTRAINT `promociones_almacenes_id_almacen_foreign` FOREIGN KEY (`id_almacen`) REFERENCES `almacen`(`id`) ON DELETE CASCADE;", $conn);

                mysql_query("ALTER TABLE `promociones_almacenes`
                     ADD CONSTRAINT `promociones_almacenes_id_promocion_foreign` FOREIGN KEY (`id_promocion`) REFERENCES `promociones`(`id`) ON DELETE CASCADE;", $conn);

                mysql_query("CREATE TABLE IF NOT EXISTS `promociones_productos`(
                     `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                     `id_promocion` BIGINT UNSIGNED NOT NULL,
                     `id_producto` INT NOT NULL,
                     PRIMARY KEY (`id`)
                   );", $conn);

                mysql_query("ALTER TABLE `promociones_productos`
                     ADD CONSTRAINT `promociones_productos_id_promocion_foreign` FOREIGN KEY (`id_promocion`) REFERENCES `promociones`(`id`) ON DELETE CASCADE;", $conn);

                mysql_query("ALTER TABLE `promociones_productos`
                     ADD CONSTRAINT `promociones_productos_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto`(`id`) ON DELETE CASCADE;", $conn);

                mysql_query("CREATE TABLE `promociones_descripcion` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `id_promocion` bigint(20) unsigned NOT NULL,
                `producto_pos` double unsigned NOT NULL,
                `cantidad` double unsigned DEFAULT NULL,
                `descuento` double unsigned DEFAULT NULL,
                `tipo` enum('mayor_costo','menor_costo') DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `promociones_descripcion_id_promocion_foreig` (`id_promocion`),
                CONSTRAINT `promociones_descripcion_id_promocion_foreig` FOREIGN KEY (`id_promocion`) REFERENCES `promociones` (`id`) ON DELETE CASCADE
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("ALTER TABLE `promociones_descripcion`
                     ADD CONSTRAINT `promociones_descripcion_id_promocion_foreig` FOREIGN KEY (`id_promocion`) REFERENCES `promociones`(`id`) ON DELETE CASCADE;", $conn);

                /*ATRIBUTOS*/
                mysql_query("CREATE TABLE `atributos` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `nombre` varchar(45) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("insert  into `atributos`(`id`,`nombre`) values (1,'Marca'),(2,'Proveedor'),(3,'Color'),(4,'Talla'),(5,'Lineas'),(6,'Materiales'),(7,'Tipos');", $conn);

                mysql_query("CREATE TABLE `atributos_categorias` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `nombre` varchar(45) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `atributos_detalle` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `valor` varchar(45) DEFAULT NULL,
                     `descripcion` varchar(45) DEFAULT NULL,
                     `atributo_id` int(11) NOT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `atributos_posee_categorias` (
                     `categoria_id` int(11) DEFAULT NULL,
                     `atributo_id` int(11) DEFAULT NULL
                   ) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `atributos_productos` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `referencia` varchar(50) DEFAULT NULL,
                     `codigo_interno` int(11) DEFAULT NULL,
                     `nombre_producto` varchar(50) DEFAULT NULL,
                     `codigo_barras` varchar(20) DEFAULT NULL,
                     `id_categoria` int(11) DEFAULT NULL,
                     `nombre_categoria` varchar(50) DEFAULT NULL,
                     `id_atributo` int(11) DEFAULT NULL,
                     `nombre_atributo` varchar(30) DEFAULT NULL,
                     `id_clasificacion` int(11) DEFAULT NULL,
                     `nombre_clasificacion` varchar(30) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `atributos_productos_almacenes` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `atributos_productos_id` int(11) NOT NULL,
                     `almacen_id` int(11) NOT NULL,
                     `cantidad` int(20) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("ALTER TABLE `vendedor` ADD `almacen` INT(100) NOT NULL ;");

                mysql_query("INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (NULL, 'por_compras_puntos_acumulados', '0');");

                mysql_query("CREATE TABLE `comanda_notificacion_cliente` (
                `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
                `usuario` int(12) DEFAULT NULL,
                `nombre` varchar(150) DEFAULT NULL,
                `notificacion` varchar(20) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;", $conn);

                mysql_query("insert  into `comanda_notificacion_cliente`(`id`,`usuario`,`nombre`,`notificacion`) values (2,11416,'administracion@hotelparquedelrio.com','20172211195707393000')", $conn);

                mysql_query("CREATE TABLE `comanda_notificacion_detalle` (
                `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
                `id_usuario` int(12) DEFAULT NULL,
                `id_factura_espera` int(10) DEFAULT NULL,
                `estado` int(2) DEFAULT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;", $conn);

                mysql_query("CREATE TABLE `comanda_notificacion_servidor` (
                `notificacion` varchar(30) DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;", $conn);

                mysql_query("insert  into `comanda_notificacion_servidor`(`notificacion`) values ('20171110214116979500')", $conn);

                mysql_query("CREATE TABLE `devoluciones` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `fecha` date DEFAULT NULL,
                     `movimiento_id` int(11) DEFAULT NULL,
                     `factura` varchar(50) DEFAULT NULL,
                     `valor` double DEFAULT NULL,
                     `cliente_cedula` int(11) DEFAULT NULL,
                     `cliente_id` int(11) DEFAULT NULL,
                     `usuario_id` int(11) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `notacredito` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     `consecutivo` varchar(20) DEFAULT NULL,
                     `usuario_id` int(11) DEFAULT NULL,
                     `tipoNota` varchar(2) DEFAULT NULL,
                     `valor` double DEFAULT NULL,
                     `fecha` datetime DEFAULT NULL,
                     `devolucion_id` int(11) DEFAULT NULL,
                     `factura_id` int(11) DEFAULT NULL,
                     `notaForeign_id` int(11) DEFAULT NULL,
                     `movimiento_id` int(11) DEFAULT NULL,
                     `cliente_id` int(11) DEFAULT NULL,
                     `estado` int(1) DEFAULT NULL,
                     PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `favoritos` (
                     `id` INT NOT NULL AUTO_INCREMENT,
                     `id_cliente` INT NOT NULL,
                     `id_producto` INT NOT NULL,
                     `created_at` TIMESTAMP NULL,
                     `updated_at` TIMESTAMP NULL,
                     PRIMARY KEY (`id`),
                     INDEX `fk_id_producto_idx` (`id_producto` ASC),
                     INDEX `fk_id_cliente_idx` (`id_cliente` ASC),
                     CONSTRAINT `fk_id_cliente`
                       FOREIGN KEY (`id_cliente`)
                       REFERENCES `clientes` (`id_cliente`)
                       ON DELETE NO ACTION
                       ON UPDATE NO ACTION,
                     CONSTRAINT `fk_id_producto`
                       FOREIGN KEY (`id_producto`)
                       REFERENCES `producto` (`id`)
                       ON DELETE NO ACTION
                       ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `carrito_compras` (
                     `id` INT NOT NULL AUTO_INCREMENT,
                     `id_producto` INT NOT NULL,
                     `id_cliente` INT NOT NULL,
                     `created_at` TIMESTAMP NULL,
                     `updated_at` TIMESTAMP NULL,
                     PRIMARY KEY (`id`),
                     INDEX `fk_id_producto_idx` (`id_producto` ASC),
                     INDEX `id_cliente_idx` (`id_cliente` ASC),
                     CONSTRAINT `fk_id_producto_sc`
                       FOREIGN KEY (`id_producto`)
                       REFERENCES `producto` (`id`)
                       ON DELETE NO ACTION
                       ON UPDATE NO ACTION,
                     CONSTRAINT `id_cliente_sc`
                       FOREIGN KEY (`id_cliente`)
                       REFERENCES `clientes` (`id_cliente`)
                       ON DELETE NO ACTION
                       ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("ALTER TABLE `carrito_compras`
                   ADD COLUMN `cantidad` INT NOT NULL DEFAULT 1 AFTER `id_producto`", $conn);

                mysql_query("ALTER TABLE `clientes` ADD `remember_token` VARCHAR(255) NULL AFTER `genero`", $conn);

                mysql_query("CREATE TABLE `epayco_token_customers` (
                     `id` INT NOT NULL AUTO_INCREMENT,
                     `token_id` VARCHAR(45) NOT NULL,
                     `customer_id` VARCHAR(45) NOT NULL,
                     `cliente_id` INT NOT NULL,
                     PRIMARY KEY (`id`),
                     INDEX `fk_cliente_id_idx` (`cliente_id` ASC),
                     CONSTRAINT `fk_cliente_id`
                       FOREIGN KEY (`cliente_id`)
                       REFERENCES `clientes` (`id_cliente`)
                       ON DELETE NO ACTION
                       ON UPDATE NO ACTION) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `scriptchat` (
                     `id` INT NOT NULL AUTO_INCREMENT,
                     `html` TEXT NULL,
                     `javascript` TEXT NULL,
                     PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("ALTER TABLE `online_venta_prod`
                   ADD COLUMN `created_at` TIMESTAMP NULL AFTER `total`,
                   ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`", $conn);

                mysql_query("CREATE TABLE `logs_login` (
                     `id` INT NOT NULL,
                     `ip` VARCHAR(45) NULL,
                     `browser` VARCHAR(255) NULL,
                     `created_at` TIMESTAMP NULL,
                     `updated_at` VARCHAR(45) NULL,
                     PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);

                mysql_query("CREATE TABLE `meta_ventas` (
                     `id` INT NOT NULL AUTO_INCREMENT,
                     `monto` DOUBLE(10,2) NOT NULL,
                     `created_at` TIMESTAMP NULL,
                     `updated_at` TIMESTAMP NULL,
                     PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;", $conn);
                //----------------------------------------------------------------------------------------

                @mysql_close($conn);

                unset($conn);

                //$usuario = $this->db->username;

                //$clave = $this->db->password;

                //$servidor = $this->db->hostname;

                //$base_dato = $this->db->database;

                //------------------------------------------------------------------------------
                $usuario = 'vendtyMaster';
                $clave = 'ro_ar_8027*_na';
                $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
                /*$usuario = 'root';
                $clave = '';
                $servidor = 'localhost';*/
                $base_dato = 'vendty2';

                //$conn1 = @mysql_connect($servidor, $usuario, $clave);

                $conn1 = @mysql_connect($servidor, $usuario, $clave);

                if (!$conn1) {
                    $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");
                } else {
                    mysql_select_db($base_dato, $conn1);
                    $api_key = md5(uniqid($this->input->post('User_id'), true));
                    mysql_query("INSERT INTO `db_config` (`id` ,`servidor` ,`base_dato` ,`usuario` ,`clave` ,`estado`,`fecha`,`api_key`)VALUES (NULL , '$servidor_multi', '$database_name', '$username_multi', '$clave_multi','2', '" . date('Y-m-d') . "', '$api_key');", $conn1);

                    $id_database = mysql_insert_id($conn1);

                    mysql_query("UPDATE `users` SET `db_config_id` = '$id_database' , is_admin = 't' WHERE `users`.`id` = $id;", $conn1);

                    if ($this->input->post('User_id') != null && $this->input->post('User_id') != "") {
                        $user_creacion = $this->input->post('User_id');
                    } else {
                        $user_creacion = $id;
                    }

                    if ($this->input->post('Distribuidor_id') != null && $this->input->post('Distribuidor_id') != "") {
                        $distribuidor_licencia = $this->input->post('Distribuidor_id');
                    } else {
                        $distribuidor_licencia = '2';
                    }

                    if ($this->input->post('Pais') != null && $this->input->post('Pais') != "") {
                        $pais = $this->input->post('Pais');
                    } else {
                        $pais = 'Colombia';
                    }

                    if ($this->input->post('Ciudad') != null && $this->input->post('Ciudad') != "") {
                        $ciudad = $this->input->post('Ciudad');
                    } else {
                        $ciudad = 'Bogotá D.C';
                    }

                    if ($this->input->post('TipoNegocio') != null && $this->input->post('TipoNegocio') != "") {
                        $tiponegocio = $this->input->post('TipoNegocio');
                    } else {
                        $tiponegocio = 'retail';
                    }

                    //$datos_usuario = $this->db->query("SELECT * FROM users WHERE id=$id")->row();
                    $usuario = 'vendtyMaster';
                    $clave = 'ro_ar_8027*_na';
                    $servidor = 'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com';
                    /*$usuario = 'root';
                    $clave = '';
                    $servidor = 'localhost';*/
                    $base_dato = 'vendty2';
                    $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
                    $this->dbConnectionv3 = $this->load->database($dns, true);
                    $datos_usuario = $this->dbConnectionv3->query("SELECT * FROM users WHERE id=$id")->row();

                    /*REgistros en produccion */
                    $sql_empresas_clientes = "INSERT INTO `crm_empresas_clientes` (
                       `nombre_empresa`,
                       `telefono_contacto`,
                       `idusuario_creacion`,
                       `id_db_config`,
                       `id_distribuidores_licencia`,
                       `id_user_distribuidor`,
                       `ciudad_empresa`,
                       `departamento_empresa`,
                       `pais`,`tipo_negocio` ) values('" . $datos_usuario->username . "','" . $datos_usuario->phone . "','" . $user_creacion . "','" . $id_database . "','" . $distribuidor_licencia . "','22965','" . $ciudad . "','Cundinamarca','" . $pais . "','" . $tiponegocio . "')";
                    mysql_query($sql_empresas_clientes, $conn1);
                    $id_empresas_cliente = mysql_insert_id($conn1);

                    $sql_licencia_empresa = "INSERT INTO `crm_licencias_empresa` (
                       `idempresas_clientes`,
                       `planes_id`,
                       `fecha_creacion`,
                       `creado_por`,
                       `fecha_modificacion`,
                       `fecha_inicio_licencia`,
                       `fecha_vencimiento`,
                       `id_db_config`,
                       `id_almacen`,
                       `estado_licencia`) values('" . $id_empresas_cliente . "','1','" . date("Y-m-d h:i:s") . "','" . $user_creacion . "','" . date("Y-m-d h:i:s") . "','" . date("Y-m-d h:i:s") . "','" . date("Y-m-d", strtotime("+7 days")) . "','" . $id_database . "','" . $id_almacen_creado . "','1')";
                    mysql_query($sql_licencia_empresa, $conn1);

                    if (($this->input->post('Distribuidor_id') == null || $this->input->post('Distribuidor_id') == "") && ($this->input->post('User_id') == null || $this->input->post('User_id') == "")) {

                        //creamos el registro en empresa
                        /*$data_empresa = array('nombre_empresa'=>$datos_usuario->username,
                        'telefono_contacto' => $datos_usuario->phone,
                        'idusuario_creacion' => $user_creacion,
                        'id_db_config' => $id_database,
                        'id_distribuidores_licencia'=>$distribuidor_licencia,
                        'id_user_distribuidor'=>$user_creacion,
                        'ciudad_empresa'=>$ciudad,
                        'departamento_empresa'=>'Cundinamarca',
                        'pais'=>$pais,
                        'tipo_negocio' => $tiponegocio );

                        $this->db->insert('crm_empresas_clientes', $data_empresa);
                        $id_empresa = $this->db->insert_id();*/

                        $data_contacto = array('tipo_contacto' => 'creacion_cuenta',
                            'nombre_contacto' => $datos_usuario->username,
                            'telefono_contacto' => $datos_usuario->phone,
                            'email_contacto' => $datos_usuario->email,
                            'idempresas_clientes' => $id_empresas_cliente,
                        );
                        $this->db->insert('crm_contactos_empresa', $data_contacto);

                        /*$data_licencia = array('idempresas_clientes'=>$id_empresa,
                    'planes_id'=>1,
                    'fecha_vencimiento'=>date("Y-m-d", strtotime("+7 days")),
                    'fecha_creacion' => date("Y-m-d h:i:s"),
                    'creado_por' => $user_creacion,
                    'id_db_config' => $id_database,
                    'id_almacen' => $id_almacen_creado,
                    'estado_licencia'=>1,
                    'fecha_inicio_licencia' => date("Y-m-d")
                    );
                    $this->db->insert('crm_licencias_empresa', $data_licencia);*/
                    }

                    @mysql_close($conn1);
                    unset($conn1);

                    $this->session->set_flashdata('message', "Su cuenta ha sido activada");
                }
            } else {
                $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");
            }
        }
    }

    /****      Registro nuevo     *****/

    public function nuevoPago()
    {
        return $this->load->view("licenciasvencidasPayU.php");
    }

    //============================================================================================================
    //  EDWIN
    //============================================================================================================

    public function login2()
    {

        $this->load->library('session');
        $this->session->unset_userdata('nuevoCliente');
        $nuevoUsuario = array();
        $nuevoUsuario['formEmail'] = $this->input->post('Email');
        $nuevoUsuario['formNombre'] = $this->input->post('Last_Name');
        $nuevoUsuario['formTelefono'] = $this->input->post('Mobile');
        $nuevoUsuario['formFuente'] = $this->input->post('Fuente') == "" ? "Adwords" : $this->input->post('Fuente');
        $nuevoUsuario['formEstado'] = $this->input->post('Estado') == "" ? "registro" : $this->input->post('Estado');
        $this->session->set_userdata('nuevoCliente', $nuevoUsuario);

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {

                //if the login is successful
                //redirect them back to the home page

                $this->session->set_flashdata('message', $this->ion_auth->messages());

                if ($this->ion_auth->is_admin()) {

                    redirect('backend/dashboard');
                } else {

                    $username = $this->input->post('identity');

                    $term = '';
                    $admin = '';
                    $user = $this->db->query("SELECT term_acept, is_admin FROM users where email = '" . $username . "' ")->result();
                    foreach ($user as $dat) {
                        $term = $dat->term_acept;
                        $admin = $dat->is_admin;
                    }

                    if ($term == '' && $admin == 't') {
                        redirect("frontend/index/new");
                    }

                    if ($term == 'Si') {

                        redirect("frontend/index/new");
                    }
                    if ($admin == 'f' || $admin == 'a') {

                        redirect("frontend/index/new");
                    }
                }
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());

                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            $data['identity'] = array(
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => "$user",
                'placeholder' => "login",
            );

            $data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => "$pass",
                'placeholder' => "password",
            );

            $this->layout->template('login')->show('auth/login2', array('data' => $data));
        }
    }

    //============================================================================================================
    //  EDWIN FIN
    //============================================================================================================

    public function check_tester()
    {
        $query = "SELECT realname FROM users WHERE id = " . $this->session->userdata('user_id');

        if ($this->db->query($query)->num_rows() > 0) {
            $result = $this->db->query($query)->result();

            if ($result[0]->realname != '') {
                exit($result[0]->realname);
            }
        }

        echo 0;
    }

    public function email_check($str)
    {
        $query = "select * from users where email = '" . $str . "'";
        //if ($this->db->query($query)->num_rows() > 0)
        if ($this->dbConnectionv2->query($query)->num_rows() > 0) {
            $this->form_validation->set_message('email_check', 'El %s existe, por favor recupere su clave');
            return false;
        } else {
            $array_validar = array('yopmail.com', 'sharklasers.com', 'malinimator.com', 'guerrillamail.com', 'misena.edu.co', 'aver.com', 'naver.com', 'utoo.email', 'mail.ru', 'bizml.ru', 'yandex.ru', 'rambler.ru', 'jkdihanie.ru', 'ya.ru', 'inbox.ru', '.ru');
            $array_email = explode('@', $str);

            if (in_array($array_email[1], $array_validar)) {
                $this->form_validation->set_message('email_check', 'El %s debe ser de un dominio valido');
                return false;
            }
            return true;
        }
    }

    public function nueva_cuenta()
    {

        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|callback_email_check');

        if ($this->form_validation->run() == true) {
            $salt = substr(md5(uniqid(rand(), true)), 0, 10);

            $password = uniqid();

            $password_send = $password;

            $conf_code = $salt . substr(sha1($salt . $password), 0, -10);

            $email = $this->input->post('email');

            $username = explode('@', $email);

            $ip_address = $_SERVER['REMOTE_ADDR'];

            $query = "INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `db_config_id`, `idioma`, `pais`, `rol_id`, `is_admin`) VALUES (NULL, '" . $ip_address . "', '" . $username[0] . "', '" . $this->ion_auth->hash_password($password) . "', '" . $salt . "', '" . $email . "', '" . $conf_code . "', NULL, NULL, NULL, '" . time() . "', '" . time() . "', '0', NULL, NULL, NULL, NULL, '', 'spanish', '', '', 't');";

            $this->db->query($query);

            $id = $this->db->insert_id();

            $this->load->library('email');
            $this->email->clear();
            $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
            $this->email->to($email);
            $this->email->bcc('arnulfo@vendty.com, desarrollo@vendty.com, comercial@vendty.com, roxanna@vendty.com');
            $this->email->subject("Bienvenido a VendTy Tu Punto de Venta en la nube");
            $this->email->message('

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">

             <tbody><tr>

                 <td align="center" valign="top" style="border-collapse: collapse;">

                        <!-- // Begin Template Preheader \\ -->

                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="templatePreheader" style="background-color: #FAFAFA;">

                            <tbody><tr>

                                <td valign="top" class="preheaderContent" style="border-collapse: collapse;">



                                 <!-- // Begin Module: Standard Preheader \ -->

                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                     <tbody><tr>

                                       <td valign="top" bgcolor="#009900" style="border-collapse: collapse;"><span class="Estilo2" style="color: #FFFFFF;font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 24px;">Bienvenido a VendTy</span></td>

                                            <!--  -->

                                      </tr>

                                    </tbody></table>

                                 <!-- // End Module: Standard Preheader \ -->



                                </td>

                            </tr>

                        </tbody></table>

                        <!-- // End Template Preheader \\ -->

                     <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer" style="border: 1px solid #DDDDDD;background-color: #FFFFFF;">

                         <tbody><tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Header \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader" style="background-color: #FFFFFF;border-bottom: 0;">

                                        <tbody><tr>

                                            <td class="headerContent" style="border-collapse: collapse;color: #202020;font-family: Arial;font-size: 16px;line-height: 100%;padding: 0;text-align: center;vertical-align: middle;">



                                              <p>

                                               <!-- // Begin Module: Standard Header Image \\ -->

                                             Comience de inmediato! Ingrese a su cuenta de VendTy desde esta <b>direcci&oacute;n</b>:</p>

                                              <b><a href="www.vendty.com/invoice" style="color: #336699;font-weight: normal;text-decoration: underline;">http://vendty.com/invoice</a></b><br>

                                              <p>Su nombre de <b>usuario</b> administrador es: <b>

                                              <a style="color: #336699;font-weight: normal;text-decoration: underline;"> ' . $email . ' </a></b>

                                                <!-- // End Module: Standard Header Image \\ -->

                                               </p>

                                              <p>Su <b>contrase&ntilde;a</b> actual es: <b>' . $password . '</b></p></td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Header \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Body \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">

                                     <tbody><tr>

                                         <td valign="top" width="400" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                 <tbody><tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                             <tbody><tr>

                                                                 <td valign="top" class="bodyContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Standard Content \\ -->

                                                                        <!-- // End Module: Standard Content \\ --></td>

                                               </tr>

                                                            </tbody></table>

                                                 </td>

                                                    </tr>

                                                    <tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                                            <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                                <tbody><tr>

                                                                    <td valign="top" width="180" class="leftColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_1" mc:repeatindex="0" mc:hideable="hideable_repeat_1_1" mchideable="hideable_repeat_1_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Comience con el pie derecho</h4>

<span style="font-size:14px">Con nuestra &uacute;til <a href="http://www.vendty.com/manual.pdf" target="_blank" style="color: #336699;font-weight: normal;text-decoration: underline;">Guia de introduccion</a>: comenzar&#65533; usted con el pie derecho, con una breve descripcion de como configurar y comenzar a usar su cuenta de VendTy.</span></div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                    <td valign="top" width="180" class="rightColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_2" mc:repeatindex="0" mc:hideable="hideable_repeat_2_1" mchideable="hideable_repeat_2_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Hable con el equipo</h4>

Llame ya al (1) 301 6991 o al 300 412 8887 y haga una pregunta a nuestro equipo de atenci&#65533;n al cliente, siga <a href="http://www.youtube.com/channel/UCjjkzv4FmwcBen2TCVUg4gQ" style="color: #336699;font-weight: normal;text-decoration: underline;">nuestros videos</a>, o aprenda y comparta consejos &uacute;tiles y noticias en Twitter y Facebook.</div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                </tr>

                                                            </tbody></table>

                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // Begin Sidebar \\ -->

                                         <td valign="top" width="200" id="templateSidebar" style="border-collapse: collapse;background-color: #FFFFFF;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="200">

                                                 <tbody><tr>

                                                     <td valign="top" class="sidebarContent" style="border-collapse: collapse;">



                                                            <!-- // Begin Module: Social Block with Icons \\ -->

                                                            <!-- // End Module: Social Block with Icons \\ -->

                                                            <!-- // Begin Module: Top Image with Content \\ -->

<table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                <tbody><tr mc:repeatable="repeat_3" mc:repeatindex="0" mc:hideable="hideable_repeat_3_1" mchideable="hideable_repeat_3_1">

                                                                    <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 12px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Sistema integral</h4>

<span style="font-size:14px">VendTy ofrece una soluci&oacute;n integral de punto de venta. Incluye un sistema de seguridad de usuarios y roles, personalizaci&oacute;n del recibo de pago, stock de productos, informes contables, integraci&oacute;n y soporte telef&oacute;nico.</span></div>

                                                                  </td>

                                                                </tr>

                                                       </tbody></table>

                                                            <!-- // End Module: Top Image with Content \\ -->



                                                   </td>

                                               </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // End Sidebar \\ -->

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Body \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Footer \\ -->

                                 <table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter" style="background-color: #FFFFFF;border-top: 0;">

                                     <tbody><tr>

                                         <td valign="top" class="footerContent" style="border-collapse: collapse;">



                                                <!-- // Begin Module: Standard Footer \\ -->

                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                                    <tbody><tr>

                                                        <td valign="middle" id="social" style="border-collapse: collapse;background-color: #FAFAFA;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;"><img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_twitter.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;">&nbsp;<a href="www.twitter.com/vendtyapps" style="color: #336699;font-weight: normal;text-decoration: underline;">Siguenos en Twitter</a> | <img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_facebook.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;"> <a href="www.facebook.com/vendtycom" style="color: #336699;font-weight: normal;text-decoration: underline;">Encuentranos en Facebook</a> | <a href="www.vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">www.vendTy.com</a>&nbsp;</div>                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="top" align="center" style="border-collapse: collapse;">



                                                              <div align="center" style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: left;">Sistematizamos SAS, Calle 145 #46-13, Bogot&#65533; - Colombia <br>

                                                                <strong>Escribanos a nuestra direcci&#65533;n de email:</strong>

                                                                <br>

info@vendty.com</div>

                                                                                                                </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="middle" id="utility" style="border-collapse: collapse;background-color: #FFFFFF;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;">

                                                                &nbsp;Este e-mail fue enviado a usted por info@vendty.com.

Si usted ya no desea recibir mas mensajes de correo electr&#65533;nico desde info@vendty.com, <a href="mailto:info@vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">desuscribase de esta lista.</a>&nbsp;                                                            </div>                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                                <!-- // End Module: Standard Footer \\ -->



                                       </td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Footer \\ -->

                                </td>

                            </tr>

                        </tbody></table>

                        <br>

                    </td>

                </tr>

            </tbody></table>

</body>



                ');

            $this->email->attach("uploads1/Manual_Vendty.pdf");

            if ($this->email->send() == true) {
                $this->activate_count($id, $conf_code);

                redirect("auth/login", 'refresh');
            }

        } else {
            if (!isset($_POST['email'])) {
                $this->layout->template("login")->show('auth/new_count');
            } else {
                $data = array();

                $data['email'] = array(

                    'name' => 'email',

                    'id' => 'email',

                    'type' => 'text',

                    'value' => $this->form_validation->set_value('email'),

                    'placeholder' => 'Correo electr&oacute;nico',

                );

                $data['message'] = "Usted tiene cuenta.<br/> Por favor envie para recuperar su clave";

                $this->layout->template("login")->show('auth/forgot_password', array('data' => $data));
            }

        }
    }

    //redirect if needed, otherwise display the user list

    public function index()
    {

        if (!$this->ion_auth->logged_in()) {

            //redirect them to the login page

            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {

            //redirect them to the home page because they must be an administrator to view this

            redirect('frontend/index', 'refresh');
        } else {
            redirect('backend/dashboard/index', 'refresh');
        }
    }

    public function change_languaje()
    {

        $languaje = $this->input->post('languaje');

        $this->session->set_userdata('idioma', $languaje);

        $this->db->where('id', $this->session->userdata('user_id'))->update('users', array('idioma' => $languaje));

    }

    public function googlelogin()
    {

        $gClient = new Google_Client();

        $gClient->setApplicationName($this->config->item('site_title'));

        $gClient->setClientId($this->config->item('google_client_id'));

        $gClient->setClientSecret($this->config->item('google_client_secret'));

        $gClient->setRedirectUri(site_url("auth/googlelogin"));

        $gClient->setDeveloperKey($this->config->item('google_developer_key'));

        $google_oauthV2 = new Google_Oauth2Service($gClient);

        //Redirect user to google authentication page for code, if code is empty.

        //Code is required to aquire Access Token from google

        //Once we have access token, assign token to session variable

        //and we can redirect user back to page and login.

        if (isset($_GET['code'])) {
            $gClient->authenticate($_GET['code']);

            $this->session->set_userdata('token', $gClient->getAccessToken());

            redirect(site_url("auth/googlelogin"));
        }

        $token = $this->session->userdata('token');

        if (!empty($token)) {
            $gClient->setAccessToken($this->session->userdata('token'));
        }

        if ($gClient->getAccessToken()) {

            //Get user details if user is logged in

            $user = $google_oauthV2->userinfo->get();

            $user_id = $user['id'];

            $user_name = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);

            $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);

            //$profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);

            //$profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);

            //$personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";

            //$_SESSION['token']    = ;

            $this->session->set_userdata('token', $gClient->getAccessToken());
        } else {
            //get google login url

            $authUrl = $gClient->createAuthUrl();
        }

        //user is not logged in, show login button
        if (isset($authUrl)) {
            redirect($authUrl);
        } else {
            if ($this->ion_auth_model->email_check($email)) {
                $query = $this->db->from('users')->where("email", $email)

                    ->select('id')

                    ->limit(1)

                    ->get();

                $this->_do_login($query->row()->id);

                $this->_do_login($query->row()->id);
            } else {
                $additional_data = array(

                    'first_name' => $user_name,

                    'last_name' => $user_name,

                    //'company'    => $this->input->post('company'),

                    //'phone'      => $this->input->post('phone1'),

                    //'db_config_id' => 0

                );

                $id = $this->ion_auth_model->register($user_name, $this->ion_auth_model->salt(), $email, $additional_data);

                $this->ion_auth_model->activate($id);

                $this->_create_db($id);

                $this->_do_login($id);
            }

            redirect("frontend/index");
        }
    }

    public function fblogin()
    {

        //get the Facebook appId and app secret from facebook.php which located in config directory for the creating the object for Facebook class

        $facebook = new Facebook(array(
            'appId' => $this->config->item('appID'),
            'secret' => $this->config->item('appSecret'),

        ));

        $user = $facebook->getUser(); // Get the facebook user id

        if ($user) {
            try {
                $user_profile = $facebook->api('/me'); //Get the facebook user profile data

                $this->session->set_userdata('logout', $facebook->getLogoutUrl(array('next' => site_url('auth/logout'))));

                $user_id = $user_profile['id'];

                $user_first_name = $user_profile['first_name'];

                $user_last_name = $user_profile['last_name'];

                $username = strtolower($user_profile['name']);

                $email = strtolower($user_profile['email']);

                if ($this->ion_auth_model->email_check($email)) {
                    $query = $this->db->from('users')->where("email", $email)

                        ->select('id')

                        ->limit(1)

                        ->get();

                    $this->_do_login($query->row()->id);

                } else {
                    $additional_data = array(

                        'first_name' => $user_first_name,

                        'last_name' => $user_last_name,

                        //'company'    => $this->input->post('company'),

                        //'phone'      => $this->input->post('phone1'),

                        //'db_config_id' => 0

                    );

                    $id = $this->ion_auth_model->register($username, $this->ion_auth_model->salt(), $email, $additional_data);

                    $this->ion_auth_model->activate($id);

                    $this->_create_db($id);

                    $this->_do_login($id);

                }

                redirect("frontend/index");
            } catch (FacebookApiException $e) {
                error_log($e);

                $user = null;
            }

        } else {
            $loginUrl = $facebook->getLoginUrl(array('redirect_uri' => site_url('auth/fblogin'), 'scope' => "email"));

            redirect($loginUrl);
        }
    }

    public function _do_login($id)
    {

        $query = $this->db->select('users.id, username, rol_id, is_admin, es_estacion_pedido, estado, email, users.id, password, active, last_login, usuario, clave, servidor, base_dato, db_config_id, idioma,db_config.estado')

            ->where("users.id", $id)

            ->join('db_config', 'db_config.id = users.db_config_id', 'left')

            ->limit(1)

            ->get('users');

        $user = $query->row();

        $this->ion_auth_model->set_session($user);

        $this->ion_auth_model->update_last_login($user->id);
    }

    public function terminos_condiciones()
    {
        $array_datos = array(
            "term_acept" => 'Si',
            "term_fecha" => date("Y/m/d"),
        );

        $this->db->where('username', $this->session->userdata('username'));

        $this->db->update("users", $array_datos);

        redirect("frontend/index");
    }
    //log the user in

    public function loginRegistro($email, $password)
    {
        if ($this->ion_auth->login($email, $password, true)) {
            $this->ion_auth->save_log();

            $valores_remplazar = array('.', '-', '/');
            $nom_bd = str_replace($valores_remplazar, '_', $email);
            $database_name = $_SESSION['db_name'];

            $usuario = "vendtyMaster";
            $clave = "ro_ar_8027*_na";
            $servidor = "ec2-35-163-242-38.us-west-2.compute.amazonaws.com";
            /*$usuario = 'root';
            $clave = '';
            $servidor = 'localhost';*/
            $base_dato = $database_name;
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->userConnection = $this->load->database($dns, true);
            $this->mi_empresa->initialize($this->userConnection);

            $this->load->model("puntos_model", 'puntos');
            $this->puntos->initialize($this->userConnection);

            $this->load->model("productos_model", 'productos');
            $this->productos->initialize($this->userConnection);

            $this->load->model("categorias_model", 'categorias');
            $this->categorias->initialize($this->userConnection);

            $this->load->model("ventas_model", 'ventas');
            $this->ventas->initialize($this->userConnection);

            $this->load->model("proformas_model", 'proformas');
            $this->proformas->initialize($this->userConnection);

            $this->load->model("almacenes_model", 'almacenes');
            $this->almacenes->initialize($this->userConnection);

            $this->load->model("dashboard_model", 'dashboardModel');
            $this->dashboardModel->initialize($this->userConnection);

            $arraydata = array(
                'soy_nuevo' => 0,
            );
            $this->session->set_userdata($arraydata);

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            //cambio para el panel administrador de licencias
            $group_licencias = array(3, 4, 5);

            $username = $email;

            //  redirect("frontend/indexh");

            $term = '';
            $admin = '';
            $user = $this->db->query("SELECT term_acept, is_admin FROM users where email = '" . $username . "' ")->result();
            foreach ($user as $dat) {
                $term = $dat->term_acept;
                $admin = $dat->is_admin;
            }

            if ($term == '' && $admin == 't') {
                $this->layout->template('login')
                    ->css(array(base_url('public/css/stylesheets.css')))
                    ->show('frontend/condiciones.php');
            }

            $data = array(
                "email" => $email,
                "password" => $password,
                "app" => "web",
            );

            $response = post_curl('login', $data);

            session_start();
            $_SESSION['api_auth'] = json_encode($response);

            $this->session->set_userdata('terms_condition', $term);

            /** Validacione de campos */

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

            /** Validaciones de campos */

            /** Load index view */
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

                    if ($fecha_nueva4 >= '2019-05-02') {
                        $fecha_nueva4 = '2019-05-01';
                    }
                    $hoy4 = date("Y-m-d");
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
                $fecha_nueva3 = $milicencia['fecha_vencimiento'];
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
            $datos_ventas = $this->dashboardModel->getVentas($almacen, 0, 0);
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
                        $datos_plan = $this->crm_model->get_planes(array('id' => $datos_vencimiento[0]->id_plan));
                        $recordarplan = $datos_plan[0]->comienzo_dias_recordacion;
                        $data['nombre_plan'] = $datos_plan[0]->nombre_plan;
                        $data['fecha_extendida'] = date("Y-m-d", strtotime($datos_vencimiento[0]->fecha_vencimiento . "+ 7 days"));
                        if ($data['fecha_extendida'] >= '2019-05-02') {
                            $data['fecha_extendida'] = '2019-05-01';
                        }
                        $hoy = date_create(date("y-m-d"));
                        $fecha_vencimiento = date_create($datos_vencimiento[0]->fecha_vencimiento);
                        $dias = date_diff($hoy, $fecha_vencimiento);
                        //verificar si aun estoy dentro de los 7 dias adicionales
                        $fecha_nueva = $datos_vencimiento[0]->fecha_vencimiento;
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
                        }
                        $data['dias_licencia'] = $dias->format("%a");
                        $data['fecha_vencimiento'] = $datos_vencimiento[0]->fecha_vencimiento;
                        $data['valor_renovacion'] = $datos_vencimiento[0]->valor_plan;
                    } else {
                        $data['dias_licencia'] = null;
                        $data['fecha_vencimiento'] = null;
                        $data['valor_renovacion'] = null;
                    }
                    $recordarplan = $recordarplan == "" ? 7 : $recordarplan;
                    if ($recordarplan < $data['dias_licencia']) {
                        $data['dias_licencia'] = null;
                    }

                    $data_empresa = $this->mi_empresa->get_data_empresa();
                    $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

                    $data["inicio_home"] = $this->primeros_pasos_model->link_primeros_pasos(array('link' => 0), array($data["tipo_negocio"], '0'));
                    $data["inicio_home_tablero"] = $this->primeros_pasos_model->link_primeros_pasos(array('link' => 1), array($data["tipo_negocio"], '0'));
                    $data["tareas_realizadas"] = $this->primeros_pasos_model->tareas_realizadas_tablero(array('id_usuario' => $this->session->userdata('user_id'), 'db_config' => $this->session->userdata('db_config_id')));
                    $data["datos_empresa"] = $this->crm_model->get_empresas(array('id_db_config' => $this->session->userdata('db_config_id')));

                    $data_empresa = $this->mi_empresa->get_data_empresa();
                    $data['permitir_formas_pago_pendiente'] = $data_empresa['data']['permitir_formas_pago_pendiente'];
                    $data["datos_empresa_ap"] = $data_empresa;
                    $data["wizard_tiponegocio"] = $this->dashboardModel->load_state_wizard();
                    $data["tiponegocio_infoactualizar"] = $this->crm_model->load_state_actualizar_info(array('id_db_config' => $this->session->userdata('db_config_id')));
                    $data['paises'] = $this->pais->getAll();
                    $data['identificacion_tributaria'] = $this->crm_model->crm_opciones(array('nombre_opcion' => 'identificacion_tributaria'));
                    $data['tipo_negocio_especializado'] = $this->crm_model->select_tipo_negocio(false, array('mostrar' => 0));

                    //formas de pagos
                    $data['type_licence'] = $this->dashboardModel->get_type_licence();
                    $data['stores_avaibles'] = $this->dashboardModel->get_stores_avaibles();
                    $data['steps_complete'] = $this->dashboardModel->get_complete_steps();
                    if (array_key_exists('api_auth', $_SESSION) && isset($_SESSION['api_auth']) && $_SESSION['api_auth'] != '') {
                        $data['token'] = $_SESSION['api_auth'];
                    } else {
                        $data['token'] = false;
                    }
                    $data['email'] = $email;
                    $data['new'] = true;
                    $this->layout->template('dashboard')->show('frontend/dash.php', array('data' => $data));
                }

            }
            /** Load index view */

        } else {
            //if the login was un-successful

            //redirect them back to the login page

            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries

        }
    }

    public function login()
    {

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $this->form_validation->set_rules('identity', 'Identity', 'required');

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                $this->ion_auth->save_log();

                $arraydata = array(
                    'soy_nuevo' => 0,
                );
                $this->session->set_userdata($arraydata);

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //cambio para el panel administrador de licencias
                $group_licencias = array(3, 4, 5);

                if ($this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))) {
                    //var_dump('es del grupo de licencias');die();
                    redirect('administracion_vendty/empresas/index');
                }
                if ($this->ion_auth->is_admin()) {

                    redirect('backend/dashboard');

                } else {

                    $username = $this->input->post('identity');

                    //  redirect("frontend/indexh");

                    $term = '';
                    $admin = '';
                    $user = $this->db->query("SELECT term_acept, is_admin FROM users where email = '" . $username . "' ")->result();
                    foreach ($user as $dat) {

                        $term = $dat->term_acept;
                        $admin = $dat->is_admin;
                    }
                    //var_dump($user);die();
                    if ($term == '' && $admin == 't') {

                        $this->layout->template('login')
                            ->css(array(base_url('public/css/stylesheets.css')))
                            ->show('frontend/condiciones.php');

                    }

                    //$data_user = $this->db->query("SELECT u.email,u.password FROM users u where u.email = '" . $username . "' ")->result()[0];

                    $data = array(
                        "email" => trim($this->input->post('identity')),
                        "password" => trim($this->input->post('password')),
                        "app" => "web",
                    );
                    //dd($this->input->post());
                    $response = post_curl('login', $data);
                    // dd(json_encode($response));
                    // $this->session->set_userdata('api_auth', json_encode($response));
                    session_start();
                    $_SESSION['api_auth'] = json_encode($response);
                    //dd($data);
                    //dd($response);
                    $token = $response->token;
                    $electronicInvoicing = json_encode($response->warehouse->electronic_invoicing);
                    $this->session->set_userdata('token_api', $token);
                    $this->session->set_userdata('terms_condition', $term);
                    $this->session->set_userdata('electronic_invoicing', $electronicInvoicing);
                    if ($term == 'Si') {

                        //print_r($this->session->userdata);
                        //die();
                        redirect("frontend/index");
                        // Envio variable NEW para identificar que viene desde el login
                        // Con esto valido si se va para el form wizard o no

                    }
                    if ($admin == 'f' || $admin == 'a' || $admin == 's') {

                        redirect("frontend/index");

                    }

                }

            } else {
                //if the login was un-successful

                //redirect them back to the login page

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries

            }

        } else {
            $data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'placeholder' => "Email",
                'class' => "",
            );

            $data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => "**********",
                'class' => "",
            );

            $random_step = rand(0, 4);

            $steps = array(
                array(
                    'title' => 'Descarga la Nueva App Punto de Venta',
                    'image' => base_url('public/img/imagen-app-punto-de-venta.png'),
                    'link' => 'https://play.google.com/store/apps/details?id=com.vendtycaja&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1',

                    'description' => 'Convierte tu tablet Android en un potente  punto de venta para tu negocio,es compatible con cualquier impresora y lector código de barra, además trabaja sin internet.',
                    'button' => false,
                    'button_image' => base_url('public/img/disponible_google_play.png'),
                ),
                array(
                    'title' => 'Nueva App Dashboard, Tu Empresa en tu Celular',
                    'image' => base_url('public/img/imagen-app-dashboard.png'),
                    'link' => 'https://play.google.com/store/apps/details?id=com.vendty.dashboard&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1',
                    'description' => 'Descarga ya la nueva App Dashboard y lleva todo el control de tu empresa en tu celular, podrás revisar las métricas, gestionar licencias y usuarios.',
                    'button' => false,
                    'button_image' => base_url('public/img/disponible_google_play.png'),
                ),
                array(
                    'title' => 'App de Toma Pedido para agilizar las ventas',
                    'image' => base_url('public/img/imagen-toma-pedido.png'),
                    'link' => 'https://play.google.com/store/apps/details?id=com.vendty.take_order',
                    'description' => 'Desde una tablet Android los meseros podrán tomar las órdenes de tus mesas y enviarlas automáticamente a tu cocina, aumentando tus tiempos de atención.',
                    'button' => false,
                    'button_image' => base_url('public/img/disponible_google_play.png'),
                ),
                array(
                    'title' => 'App de Comanda Virtual, Lleva tu restaurante a otro Nivel',
                    'image' => base_url('public/img/imagen-app-tablet.png'),
                    'link' => 'https://play.google.com/store/apps/details?id=com.vendty.command&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1',
                    'description' => 'Desde tu tablet Andoid podrás visualizar y llevar el control de las órdenes que ponen los meseros, además podrás cambiar los estados para agilizar tus pedidos.',
                    'button' => false,
                    'button_image' => base_url('public/img/disponible_google_play.png'),
                ),
                array(
                    'title' => 'Facturación Electrónica para tu punto de Venta o Restaurante',
                    'image' => base_url('public/img/imagen-facturacion-electronica.png'),
                    'link' => 'https://vendty.com/facturacion-electronica/',
                    'description' => 'Ya estamos listos para que comiences a facturar electrónicamente con validación previa, de acuerdo a la última reglamentación de la DIAN',
                    'button' => 'DESCÚBRELA YA',
                ),
            );

            $data["step_selected"] = $steps[$random_step];

            $this->layout->template('login')->show('auth/login', array('data' => $data));
        }
    }

    public function login3()
    {
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $this->form_validation->set_rules('identity', 'Identity', 'required');

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                $arraydata = array(
                    'soy_nuevo' => 1,
                );
                $this->session->set_userdata($arraydata);

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //cambio para el panel administrador de licencias
                $group_licencias = array(3, 4, 5);

                if ($this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))) {
                    //var_dump('es del grupo de licencias');die();
                    redirect('administracion_vendty/empresas/index');
                }
                if ($this->ion_auth->is_admin()) {

                    redirect('backend/dashboard');

                } else {

                    $username = $this->input->post('identity');
                    $term = '';
                    $admin = '';
                    $user = $this->db->query("SELECT term_acept, is_admin FROM users where email = '" . $username . "' ")->result();
                    foreach ($user as $dat) {

                        $term = $dat->term_acept;
                        $admin = $dat->is_admin;
                    }
                    if ($term == '' && $admin == 't') {

                        $this->layout->template('login')
                            ->css(array(base_url('public/css/stylesheets.css')))
                            ->show('frontend/condiciones.php');

                    }

                    $data = array(
                        "email" => $this->input->post('identity'),
                        "password" => $this->input->post('password'),
                        "app" => "web",
                    );

                    $response = post_curl('login', $data);
                    // dd(json_encode($response));
                    // $this->session->set_userdata('api_auth', json_encode($response));
                    session_start();
                    $_SESSION['api_auth'] = json_encode($response);
                    $token = $response->token;

                    $this->session->set_userdata('token_api', $token);
                    $this->session->set_userdata('terms_condition', $term);

                    if ($term == 'Si') {

                        redirect("frontend/index");
                        // Envio variable NEW para identificar que viene desde el login
                        // Con esto valido si se va para el form wizard o no

                    }
                    if ($admin == 'f' || $admin == 'a' || $admin == 's') {

                        redirect("frontend/index");

                    }

                }

            } else {
                //if the login was un-successful

                //redirect them back to the login page

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries

            }

        } else {
            //die("si");
            //the user is not logging in so display the login page

            //set the flash data error message if there is one

            //$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'placeholder' => "Email",
                'class' => "form-control",
            );

            $data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => "Password",
                'class' => "form-control",
            );

            $this->layout->template('login')->show('auth/login', array('data' => $data));
        }
    }

    public function login_tienda()
    {
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $this->form_validation->set_rules('identity', 'Identity', 'required');

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                $arraydata = array(
                    'soy_nuevo' => 1,
                );
                $this->session->set_userdata($arraydata);

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //cambio para el panel administrador de licencias
                $group_licencias = array(3, 4, 5);

                if ($this->ion_auth->in_group($this->config->item('grupo_usuarios_distribuidores'))) {
                    //var_dump('es del grupo de licencias');die();
                    redirect('administracion_vendty/empresas/index');
                }
                if ($this->ion_auth->is_admin()) {

                    redirect('backend/dashboard');

                } else {

                    $username = $this->input->post('identity');
                    $term = '';
                    $admin = '';
                    $user = $this->db->query("SELECT term_acept, is_admin FROM users where email = '" . $username . "' ")->result();
                    foreach ($user as $dat) {

                        $term = $dat->term_acept;
                        $admin = $dat->is_admin;
                    }
                    if ($term == '' && $admin == 't') {

                        $this->layout->template('login')
                            ->css(array(base_url('public/css/stylesheets.css')))
                            ->show('frontend/condiciones.php');

                    }

                    $data = array(
                        "email" => $this->input->post('identity'),
                        "password" => $this->input->post('password'),
                        "app" => "web",
                    );

                    $response = post_curl('login', $data);
                    // dd(json_encode($response));
                    // $this->session->set_userdata('api_auth', json_encode($response));
                    session_start();
                    $_SESSION['api_auth'] = json_encode($response);
                    $token = $response->token;

                    $this->session->set_userdata('token_api', $token);
                    $this->session->set_userdata('terms_condition', $term);

                    if ($term == 'Si') {

                        redirect("frontend/loginTienda");
                        // Envio variable NEW para identificar que viene desde el login
                        // Con esto valido si se va para el form wizard o no

                    }
                    if ($admin == 'f' || $admin == 'a' || $admin == 's') {

                        redirect("frontend/loginTienda");

                    }

                }

            } else {
                //if the login was un-successful

                //redirect them back to the login page

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries

            }

        } else {
            //the user is not logging in so display the login page
            $data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
                'placeholder' => "Email",
                'class' => "form-control",
            );

            $data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'placeholder' => "Password",
                'class' => "form-control",
            );

            $this->layout->template('login')->show('auth/login', array('data' => $data));
        }
    }

    public function calzado_vendty()
    {

        $remember = '';
        $this->ion_auth->login('calzado@vendty.com', '12345678', $remember);
        redirect("frontend/index");
    }

    public function drogeria_vendty()
    {

        $remember = '';
        $this->ion_auth->login('drogeria@vendty.com', '12345678', $remember);
        redirect("frontend/index");
    }

    public function ropa_vendty()
    {

        $remember = '';
        $this->ion_auth->login('ropa@vendty.com', '12345678', $remember);
        redirect("frontend/index");
    }

    public function tecnologia_vendty()
    {

        $remember = '';
        $this->ion_auth->login('tecnologia@vendty.com', '12345678', $remember);
        redirect("frontend/index");
    }

    public function micromercado_vendty()
    {

        $remember = '';
        $this->ion_auth->login('micromercado@vendty.com', '12345678', $remember);
        redirect("frontend/index");
    }

    public function restaurante_vendty()
    {

        $remember = '';
        $this->ion_auth->login('restaurante@vendty.com', '12345678', $remember);
        redirect("frontend/index");
    }

    //log the user out

    public function logout()
    {
        $group_licencias = array(3, 4);

        if (!$this->ion_auth->in_group($group_licencias)) {
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $base_dato = $this->session->userdata('base_dato');
            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->dbConnection = $this->load->database($dns, true);
            $usuario = '';
            $usuario = $this->session->userdata('user_id');
            $this->dbConnection->query("delete from  factura_espera where usuario_id = '" . $usuario . "' and id>0 ");
        }

        $this->data['title'] = "Logout";
        $token = $this->session->userdata('token');

        if (!empty($token)) {
            $gClient = new Google_Client();
            $gClient->setApplicationName($this->config->item('site_title'));
            $gClient->setClientId($this->config->item('google_client_id'));
            $gClient->setClientSecret($this->config->item('google_client_secret'));
            $gClient->setRedirectUri(site_url("auth/googlelogin"));
            $gClient->setDeveloperKey($this->config->item('google_developer_key'));
            $google_oauthV2 = new Google_Oauth2Service($gClient);
            $this->session->unset_userdata('token');
            $gClient->revokeToken();
        }

        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('auth/login', 'refresh');
    }

    //change password
    public function change_password()
    {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->_render_page('auth/change_password', $this->data);
        } else {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));
            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //forgot password
    public function forgot_password()
    {
        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');

        if ($this->form_validation->run() == false) {
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'placeholder' => 'Introduzca su correo electronico',
                'class' => 'form-control',
            );

            if ($this->config->item('identity', 'ion_auth') == 'username') {
                $this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
            } else {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->layout->template('forgot')->show('auth/forgot_password', array('data' => $this->data));
        } else {
            $salt = substr(md5(uniqid(rand(), true)), 0, 10);
            $randon = substr(str_shuffle("0123456789"), 0, 5);
            $password = substr(str_shuffle($this->input->post('email')), 0, 5) . $randon;
            $password_send = $password;
            $conf_code = $salt . substr(sha1($salt . $password), 0, -10);

            // get identity for that email
            $config_tables = $this->config->item('tables', 'ion_auth');
            $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();

            if (count($identity) != 0) {
                $email = $this->input->post('email');
                $query = "UPDATE users SET password ='" . $this->ion_auth->hash_password($password) . "' WHERE email='" . $this->input->post('email') . "'";

                if ($this->db->query($query)) {
                    $this->load->library('email');
                    $this->email->initialize();
                    $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                    $this->email->to($email);
                    $this->email->subject("Recuperacion de contraseña Vendty");
                    $mensaje = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>Alerta</title>
                        <style type="text/css">
                        img {
                        max-width: 100%;
                        }
                        body {
                        -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
                        }
                        body {
                        background-color: #f6f6f6;
                        }
                        @media only screen and (max-width: 640px) {
                          body {
                            padding: 0 !important;
                          }
                          h1 {
                            font-weight: 800 !important; margin: 20px 0 5px !important;
                          }
                          h2 {
                            font-weight: 800 !important; margin: 20px 0 5px !important;
                          }
                          h3 {
                            font-weight: 800 !important; margin: 20px 0 5px !important;
                          }
                          h4 {
                            font-weight: 800 !important; margin: 20px 0 5px !important;
                          }
                          h1 {
                            font-size: 22px !important;
                          }
                          h2 {
                            font-size: 18px !important;
                          }
                          h3 {
                            font-size: 16px !important;
                          }
                          .container {
                            padding: 0 !important; width: 100% !important;
                          }
                          .content {
                            padding: 0 !important;
                          }
                          .content-wrap {
                            padding: 10px !important;
                          }
                          .invoice {
                            width: 100% !important;
                          }
                        }
                        </style>
                        </head>
                        <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                        <table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                                <td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                                    <div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="alert alert-warning" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #62CB31; margin: 0; padding: 20px;" align="center" bgcolor="#62CB31" valign="top">
                                                    <img src="http://www.vendty.com/invoice/public/v2/img/logo_2.png" alt="">
                                                </td>
                                            </tr><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                <strong>Notificación</strong><br>Su contraseña se ha restablecido.
                                                            </td>
                                                        </tr><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                                                    <table class="invoice" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 100%; margin: 20px auto;">
                                                                        <tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                            <td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;" valign="top">
                                                                                ¡Comience de inmediato! ingrese a su cuenta de VendTy desde esta <b>dirección</b>:
                                                                                <br>
                                                                                <b>
                                                                                    <a href="http://www.vendty.com/invoice" target="_blank" style="color: #336699;font-weight: normal;text-decoration: underline;">
                                                                                        http://vendty.com/invoice
                                                                                    </a>
                                                                                </b>
                                                                                <br>
                                                                                <br>
                                                                                Su nombre de <b>usuario</b> es:
                                                                                <a style="color: #336699;font-weight: normal;text-decoration: underline;"> ' . $email . ' </a></b>
                                                                                <br>
                                                                                Su <b>contraseña</b> actual es: <b style="color: #ff0000">' . $password . '</b>
                                                                            </td>
                                                                        </tr>
                                                                </table>
                                                            </td>
                                                        </tr><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                Gracias por elegirnos, equipo Vendty.
                                                            </td>
                                                        </tr></table></td>
                                            </tr></table><div class="footer" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                                            <table width="100%" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="aligncenter content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"></td>
                                                </tr></table></div></div>
                                </td>
                                <td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                            </tr></table></body>
                        </html>';

                    $data_email = array(
                        'user' => $email,
                        'password' => $password,
                    );
                    $mensaje = $this->load->view('email/forgot_password', $data_email, true);

                    $this->email->message($mensaje);

                    if ($this->email->send() == true) {
                        $this->session->set_flashdata('message2', "Su cuenta ha sido reiniciada. Por favor verifique su email");
                        redirect("auth/login", 'refresh');
                    }

                }
            }
        }
    }

    //reset password - final step for forgotten password

    public function reset_password($code = null)
    {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');

            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {

                //display the form

                //set the flash data error message if there is one

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

                $this->data['new_password'] = array(

                    'name' => 'new',

                    'id' => 'new',

                    'type' => 'password',

                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',

                );

                $this->data['new_password_confirm'] = array(

                    'name' => 'new_confirm',

                    'id' => 'new_confirm',

                    'type' => 'password',

                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',

                );

                $this->data['user_id'] = array(

                    'name' => 'user_id',

                    'id' => 'user_id',

                    'type' => 'hidden',

                    'value' => $user->id,

                );

                $this->data['csrf'] = $this->_get_csrf_nonce();

                $this->data['code'] = $code;

                //render

                $this->_render_page('auth/reset_password', $this->data);
            } else {

                // do we have a valid request?

                if ($this->_valid_csrf_nonce() === false || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up

                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));

                } else {

                    // finally change the password

                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());

                        $this->logout();
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());

                        redirect('auth/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());

            redirect("auth/forgot_password", 'refresh');
        }
    }

    public function _create_db($id)
    {

        $username_multi = $this->config->item('multi_tenant_user');

        $clave_multi = $this->config->item('multi_tenant_pass');

        $servidor_multi = $this->config->item('multi_tenant_host');

        $conn = @mysql_connect($servidor_multi, $username_multi, $clave_multi);

        if (!$conn) {
            $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");
        } else {
            $uid = uniqid();

            $database_name = "vendty2_db_$uid";

            $sql = "CREATE DATABASE $database_name";

            if (mysql_query($sql, $conn)) {

                mysql_select_db($database_name, $conn);

                $sql_almacen = "CREATE TABLE IF NOT EXISTS `almacen` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre` varchar(254) DEFAULT NULL,

                            `direccion` text,

                            `meta_diaria` float DEFAULT NULL,

                            `prefijo` varchar(254) DEFAULT NULL,

                            `consecutivo` int(10) unsigned DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            `telefono` varchar(20) NOT NULL,

                            'resolucion_factura' varchar(20)

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_categoria = "CREATE TABLE IF NOT EXISTS `categoria` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `codigo` int(11) DEFAULT NULL,

                            `nombre` varchar(254) DEFAULT NULL,

                            `imagen` varchar(254) DEFAULT NULL,

                            `padre` varchar(254) DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $categoria_query = "INSERT INTO `categoria` (`id`, `codigo`, `nombre`, `imagen`, `padre`, `activo`) VALUES

                            (2, 0000000, 'General', '', NULL, 1);";

                $sql_clientes = "CREATE TABLE IF NOT EXISTS `clientes` (

                            `id_cliente` int(11) NOT NULL AUTO_INCREMENT,

                            `pais` varchar(254) NOT NULL,

                            `provincia` varchar(254) DEFAULT NULL,

                            `nombre_comercial` varchar(100) DEFAULT NULL,

                            `razon_social` varchar(100) DEFAULT NULL,

                            `nif_cif` varchar(15) DEFAULT NULL,

                            `contacto` varchar(100) DEFAULT NULL,

                            `pagina_web` varchar(150) DEFAULT NULL,

                            `email` varchar(80) DEFAULT NULL,

                            `poblacion` varchar(80) DEFAULT NULL,

                            `direccion` text,

                            `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,

                            `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `tipo_empresa` varchar(80) DEFAULT NULL,

                            `entidad_bancaria` varchar(100) DEFAULT NULL,

                            `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,

                            `observaciones` text,

                            PRIMARY KEY (`id_cliente`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

                                ";

                /* $sql_servicios = "CREATE TABLE IF NOT EXISTS `servicios` (

                `id_servicio` int(11) NOT NULL AUTO_INCREMENT,

                `nombre` varchar(254) NOT NULL,

                `codigo` varchar(254) DEFAULT NULL,

                `descripcion` text NOT NULL,

                `precio` float(10,2) NOT NULL,

                `id_impuesto` int(11) NOT NULL,

                PRIMARY KEY (`id_servicio`),

                KEY `servicios_FK1` (`id_impuesto`)

                 */

                $sql_detalle_venta = "CREATE TABLE IF NOT EXISTS `detalle_venta` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `venta_id` int(11) NOT NULL,

                            `producto_id` int DEFAULT NULL,

                            `codigo_producto` varchar(15) DEFAULT NULL,

                            `nombre_producto` varchar(254) DEFAULT NULL,

                            `unidades` int(11) DEFAULT NULL,

                            `precio_compra` float DEFAULT NULL,

                            `precio_venta` float DEFAULT NULL,

                            `descuento` float DEFAULT NULL,

                            `impuesto` float DEFAULT NULL,

                            `linea` varchar(254) DEFAULT NULL,

                            `margen_utilidad` float DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            PRIMARY KEY (`id`),

                            KEY `detalle_venta_FKIndex1` (`venta_id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_productos = "CREATE TABLE IF NOT EXISTS `producto` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `categoria_id` int(11) NOT NULL,
                    `codigo` varchar(50) DEFAULT NULL,
                    `nombre` varchar(254) DEFAULT NULL,
                    `codigo_barra` varchar(254) DEFAULT NULL,
                    `precio_compra` double DEFAULT NULL,
                    `precio_venta` double DEFAULT NULL,
                    `stock_minimo` double DEFAULT NULL,
                    `descripcion` text,
                    `descripcion_larga` text,
                    `activo` tinyint(1) DEFAULT '1',
                    `impuesto` double DEFAULT NULL,
                    `fecha` date DEFAULT NULL,
                    `imagen` varchar(254) DEFAULT NULL,
                    `thumbnail` varchar(255) DEFAULT NULL,
                    `material` tinyint(1) DEFAULT '0',
                    `ingredientes` tinyint(1) DEFAULT '0',
                    `combo` int(11) DEFAULT '0',
                    `unidad_id` int(11) DEFAULT '1',
                    `imagen1` varchar(254) DEFAULT NULL,
                    `imagen2` varchar(254) DEFAULT NULL,
                    `imagen3` varchar(254) DEFAULT NULL,
                    `imagen4` varchar(254) DEFAULT NULL,
                    `imagen5` varchar(254) DEFAULT NULL,
                    `id_proveedor` bigint(20) DEFAULT '0',
                    `stock_maximo` double NOT NULL,
                    `fecha_vencimiento` varchar(100) NOT NULL,
                    `ubicacion` varchar(150) NOT NULL,
                    `ganancia` double NOT NULL,
                    `tienda` tinyint(1) DEFAULT '0',
                    `muestraexist` tinyint(1) DEFAULT '0',
                    `vendernegativo` tinyint(1) DEFAULT '0',
                    `mostrar_stock` tinyint(1) NOT NULL DEFAULT '0',
                    `woocommerce_id` int(10) unsigned DEFAULT NULL,
                    `id_tipo_producto` int(11) DEFAULT NULL COMMENT 'referencia la tabla tipo de producto, esta columna se agrega para los productos de restaurante',
                    `destacado_tienda` tinyint(1) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`id`),
                    KEY `producto_FKIndex1` (`categoria_id`),
                    CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                  ) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;";

                $sql_impuestos = "

                            CREATE TABLE IF NOT EXISTS `impuesto` (

                                `id_impuesto` int(11) NOT NULL AUTO_INCREMENT,

                                `nombre_impuesto` varchar(254) DEFAULT NULL,

                                `porciento` int(11) DEFAULT NULL,

                                PRIMARY KEY (`id_impuesto`)

                              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $insert_impusto = "INSERT INTO `impuesto` (`id_impuesto`, `nombre_impuesto`, `porciento`) VALUES (NULL, 'Sin Impuesto', '0');";

                $sql_opciones = "CREATE TABLE IF NOT EXISTS `opciones` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre_opcion` varchar(254) NOT NULL DEFAULT '',

                            `valor_opcion` text NOT NULL,

                            PRIMARY KEY (`id`,`nombre_opcion`)

                          ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;";

                $sql_nothing_tax = "INSERT INTO `impuestos` (`id_impuesto` ,`nombre_impuesto` ,`porciento`) VALUES (NULL , 'Ninguno', '0');";

                $sql_pagos = "CREATE TABLE IF NOT EXISTS `pagos` (

                                `id_pago` int(11) NOT NULL AUTO_INCREMENT,

                                `id_factura` int(11) NOT NULL,

                                `fecha_pago` date NOT NULL,

                                `cantidad` float(10,2) NOT NULL,

                                `tipo` varchar(254) NOT NULL,

                                `notas` text NOT NULL,

                                `importe_retencion` float(10,2) DEFAULT NULL,

                                PRIMARY KEY (`id_pago`),

                                KEY `pagos_FK1` (`id_factura`)

                                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_proveedores = "CREATE TABLE IF NOT EXISTS `proveedores` (
                    `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,
                    `pais` varchar(254) NOT NULL,
                    `provincia` varchar(254) DEFAULT NULL,
                    `nombre_comercial` varchar(100) DEFAULT NULL,
                    `razon_social` varchar(100) DEFAULT NULL,
                    `nif_cif` varchar(15) DEFAULT NULL,
                    `contacto` varchar(100) DEFAULT NULL,
                    `pagina_web` varchar(150) DEFAULT NULL,
                    `email` varchar(80) DEFAULT NULL,
                    `poblacion` varchar(80) DEFAULT NULL,
                    `direccion` text,
                    `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,
                    `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
                    `tipo_empresa` varchar(80) DEFAULT NULL,
                    `entidad_bancaria` varchar(100) DEFAULT NULL,
                    `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
                    `observaciones` text,
                    PRIMARY KEY (`id_proveedor`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;";

                $sql_proformas = "CREATE TABLE IF NOT EXISTS `proformas` (
                    `id_proforma` int(11) NOT NULL AUTO_INCREMENT,
                    `id_proveedor` int(11) NOT NULL,
                    `descripcion` varchar(254) NOT NULL,
                    `cantidad` double NOT NULL,
                    `valor` double NOT NULL,
                    `notas` text NOT NULL,
                    `fecha` date NOT NULL,
                    `id_impuesto` int(1) NOT NULL,
                    `id_almacen` int(30) NOT NULL,
                    `forma_pago` varchar(150) NOT NULL,
                    `id_cuenta_dinero` int(100) NOT NULL,
                    `fecha_crea_gasto` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Fecha de creacion del gasto almacena fecha y hora',
                    `banco_asociado` int(11) DEFAULT NULL COMMENT 'Campo para verificar si el gasto fue asociado a un banco',
                    `subcategoria_asociada` int(11) DEFAULT NULL COMMENT 'Campo para verficar la categoria asociada al banco',
                    `movimiento_asociado` int(11) DEFAULT NULL COMMENT 'Campo para verificar si tiene movimiento activo',
                    PRIMARY KEY (`id_proforma`),
                    KEY `proformas_FK1` (`id_impuesto`),
                    KEY `proformas_FK2` (`id_proveedor`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;";

                $sql_presupuestos = "CREATE TABLE IF NOT EXISTS `presupuestos` (

                              `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT,

                              `id_cliente` int(11) NOT NULL,

                              `numero` varchar(10) CHARACTER SET latin1 NOT NULL,

                              `monto` float(10,2) NOT NULL,

                              `monto_siva` float(10,2) NOT NULL,

                              `monto_iva` float(10,2) NOT NULL,

                              `fecha` date NOT NULL,

                              PRIMARY KEY (`id_presupuesto`),

                              KEY `presupuestos_FK1` (`id_cliente`)

                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                $sql_presupuestos_detalles = "CREATE TABLE IF NOT EXISTS `presupuestos_detalles` (

                                                        `id_presupuesto_detalle` int(11) NOT NULL AUTO_INCREMENT,

                                                        `id_presupuesto` int(11) NOT NULL,

                                                        `precio` float(10,2) NOT NULL,

                                                        `cantidad` int(11) NOT NULL,

                                                        `impuesto` float(10,2) NOT NULL,

                                                        `fk_id_producto` int(11) NOT NULL,

                                                        `descuento` float NOT NULL,

                                                        `descripcion_d` text NOT NULL,

                                                        PRIMARY KEY (`id_presupuesto_detalle`),

                                                        KEY `presupuestos_detalles_FK1` (`id_presupuesto`)

                                                      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $sql_facturas = "

                                CREATE TABLE IF NOT EXISTS `facturas` (

                                  `id_factura` int(11) NOT NULL AUTO_INCREMENT,

                                  `id_cliente` int(11) NOT NULL,

                                  `numero` varchar(10) CHARACTER SET latin1 NOT NULL,

                                  `monto` float(10,2) NOT NULL,

                                  `monto_siva` float(10,2) NOT NULL,

                                  `monto_iva` float(10,2) NOT NULL,

                                  `fecha` date NOT NULL,

                                  `fecha_v` date DEFAULT NULL,

                                  `estado` int(1) NOT NULL,

                                  PRIMARY KEY (`id_factura`),

                                  KEY `facturas_FK1` (`id_cliente`)

                                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                $sql_facturas_detalles = "CREATE TABLE IF NOT EXISTS `facturas_detalles` (

                                    `id_factura_detalle` int(11) NOT NULL AUTO_INCREMENT,

                                    `id_factura` int(11) NOT NULL,

                                    `precio` float(10,2) NOT NULL,

                                    `cantidad` int(11) NOT NULL,

                                    `impuesto` float(10,2) NOT NULL,

                                    `descuento` float NOT NULL,

                                    `fk_id_producto` int(11) NOT NULL,

                                    `descripcion_d` text NOT NULL,

                                    PRIMARY KEY (`id_factura_detalle`),

                                    KEY `facturas_detalles_FK1` (`id_factura`)

                                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $stock_actual = "CREATE TABLE IF NOT EXISTS `stock_actual` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `almacen_id` int(11) DEFAULT NULL,
                    `producto_id` int(11) DEFAULT NULL,
                    `unidades` double DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `stok_actual_FKIndex1` (`almacen_id`),
                    KEY `producto_stock_actual_fk_idx` (`producto_id`),
                    CONSTRAINT `almacen_id_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT `producto_stock_actual_fk` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
                  ) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=latin1;";

                $stock_diario = "CREATE TABLE IF NOT EXISTS `stock_diario` (

                                `id` int(11) NOT NULL AUTO_INCREMENT,

                                `producto_id` int(11) NOT NULL,

                                `almacen_id` int(11) NOT NULL,

                                `fecha` date DEFAULT NULL,

                                `razon` varchar(254) DEFAULT NULL,

                                `cod_documento` varchar(254) DEFAULT NULL,

                                `unidad` int(11) DEFAULT NULL,

                                `precio` float DEFAULT NULL,

                                `usuario` int(11) DEFAULT NULL,

                                PRIMARY KEY (`id`),

                                KEY `stock_diario_FKIndex1` (`almacen_id`),

                                KEY `stock_diario_FKIndex2` (`producto_id`)

                              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $stock_historial = "CREATE TABLE IF NOT EXISTS `stock_historial`(
                                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `fecha` DATE NOT NULL,
                                `almacen_id` INT(11),
                                `producto_id` INT(11),
                                `unidades` INT(11),
                                `precio` INT(11),
                                PRIMARY KEY (`id`),
                                INDEX `stock_historial_almacen_id_index` (`almacen_id`),
                                INDEX `stock_historial_producto_id_index` (`producto_id`),
                                CONSTRAINT `stock_historial_almacen_id_foreign` FOREIGN KEY (`almacen_id`) REFERENCES `almacen`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
                                CONSTRAINT `stock_historial_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
                            ) ENGINE=INNODB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

                $usuario_almacen = "CREATE TABLE IF NOT EXISTS `usuario_almacen` (

                                `id` int(11) NOT NULL AUTO_INCREMENT,

                                `usuario_id` int(11) NOT NULL,

                                `almacen_id` int(11) NOT NULL,

                                PRIMARY KEY (`id`)

                              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

                $vendedor = "CREATE TABLE IF NOT EXISTS `vendedor` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `nombre` varchar(254) NOT NULL,
                    `cedula` varchar(15) NOT NULL,
                    `email` varchar(254) NOT NULL,
                    `telefono` varchar(20) NOT NULL,
                    `comision` double DEFAULT '0',
                    `almacen` int(100) NOT NULL,
                    `estacion` tinyint(1) DEFAULT '0' COMMENT 'Para saber si el vendedor pertenece a estación de pedido',
                    `sesion_estacion` tinyint(1) DEFAULT '0' COMMENT 'Para controlar la cantidad de veces que esta dentro del sistema',
                    `codigo` varchar(4) DEFAULT NULL COMMENT 'codigo para ingresar a la estacion de Pedidos',
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";

                $forma_pago = "CREATE TABLE IF NOT EXISTS `forma_pago` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `codigo` varchar(15) DEFAULT NULL,

                            `nombre` varchar(254) DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $venta = "CREATE TABLE IF NOT EXISTS `venta` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `almacen_id` int(11) DEFAULT NULL,

                            `forma_pago_id` int(11) DEFAULT NULL,

                            `factura` varchar(254) DEFAULT NULL,

                            `fecha` datetime DEFAULT NULL,

                            `usuario_id` int(11) DEFAULT NULL,

                            `cliente_id` int(11) DEFAULT NULL,

                            `vendedor` int(11) DEFAULT NULL,

                            `cambio` varchar(254) DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            `total_venta` float NOT NULL,

                            PRIMARY KEY (`id`),

                            KEY `venta_FKIndex1` (`forma_pago_id`),

                            KEY `venta_FKIndex2` (`almacen_id`),

                            KEY `venta_cliente_id` (`cliente_id`),

                            KEY `venta_vendedor_fk_idx` (`vendedor`)

                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $productosf = "CREATE TABLE `productosf` (

                            `id_producto` int(11) NOT NULL AUTO_INCREMENT,

                            `codigo` varchar(254) NOT NULL,

                            `descripcion` text NOT NULL,

                            `nombre` varchar(254) NOT NULL,

                            `id_impuesto` int(11) NOT NULL,

                            `precio` float DEFAULT NULL,

                            `precio_compra` float DEFAULT NULL,

                            PRIMARY KEY (`id_producto`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $ventas_pagos = "CREATE TABLE `ventas_pago` (
                    `id_pago` int(11) NOT NULL AUTO_INCREMENT,
                    `id_venta` int(11) NOT NULL,
                    `forma_pago` varchar(254) NOT NULL,
                    `valor_entregado` float NOT NULL,
                    `cambio` float NOT NULL,
                    `transaccion` varchar(25) DEFAULT NULL,
                    PRIMARY KEY (`id_pago`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $rol = "CREATE TABLE `rol` (

                        `id_rol` int(11) NOT NULL AUTO_INCREMENT,

                        `nombre_rol` varchar(254) NOT NULL,

                        `descripcion` text NOT NULL,

                        PRIMARY KEY (`id_rol`)

                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $rol_permisos = "CREATE TABLE `permiso_rol` (

                        `id_permiso_rol` int(11) NOT NULL AUTO_INCREMENT,

                        `id_permiso` int(11) NOT NULL,

                        `id_rol` int(11) NOT NULL,

                        PRIMARY KEY (`id_permiso_rol`)

                      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                /*

                CREATE TABLE `permisos` (

                `id_permiso` int(11) NOT NULL AUTO_INCREMENT,

                `nombre_permiso` varchar(254) NOT NULL,

                `url` varchar(254) NOT NULL,

                PRIMARY KEY (`id_permiso`)

                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

                -- --------------------------------------------------------

                --

                -- Estructura de tabla para la tabla `permiso_rol`

                --

                CREATE TABLE `permiso_rol` (

                `id_permiso_rol` int(11) NOT NULL AUTO_INCREMENT,

                `id_permiso` int(11) NOT NULL,

                `id_rol` int(11) NOT NULL,

                PRIMARY KEY (`id_permiso_rol`)

                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

                -- --------------------------------------------------------

                --

                -- Estructura de tabla para la tabla `rol`

                --

                CREATE TABLE `rol` (

                `id_rol` int(11) NOT NULL AUTO_INCREMENT,

                `nombre_rol` varchar(254) NOT NULL,

                `descripcion` text NOT NULL,

                PRIMARY KEY (`id_rol`)

                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

                 */

                $filtros_facturas = "ALTER TABLE `facturas`

                                ADD CONSTRAINT `facturas_FK1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_facturas_detalles = "ALTER TABLE `facturas_detalles`

                                ADD CONSTRAINT `facturas_detalles_FK1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_pagos = "ALTER TABLE `pagos`

                                ADD CONSTRAINT `pagos_FK1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_presupuestos = "ALTER TABLE `presupuestos`

                                ADD CONSTRAINT `presupuestos_FK1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_detalles = "ALTER TABLE `presupuestos_detalles`

                                ADD CONSTRAINT `presupuestos_detalles_FK1` FOREIGN KEY (`id_presupuesto`) REFERENCES `presupuestos` (`id_presupuesto`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_productos = "ALTER TABLE `producto`

  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_proformas = "ALTER TABLE `proformas`

                                ADD CONSTRAINT `proformas_FK2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,

                                ADD CONSTRAINT `proformas_FK1` FOREIGN KEY (`id_impuesto`) REFERENCES `impuestos` (`id_impuesto`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_stock_actual = "ALTER TABLE `stock_actual`

  ADD CONSTRAINT `producto_stock_actual_fk` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

  ADD CONSTRAINT `almacen_id_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_stock_diario = "ALTER TABLE `stock_diario`

  ADD CONSTRAINT `almacen_fk_stock_actual` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

  ADD CONSTRAINT `stock_diario_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_ventas = "ALTER TABLE `venta`

  ADD CONSTRAINT `venta_vendedor_fk` FOREIGN KEY (`vendedor`) REFERENCES `vendedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,

  ADD CONSTRAINT `venta_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

  ADD CONSTRAINT `venta_cliente_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,

  ADD CONSTRAINT `venta_forma_pago` FOREIGN KEY (`forma_pago_id`) REFERENCES `forma_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_usuario_almacen = "ALTER TABLE `usuario_almacen`

  ADD CONSTRAINT `usuario_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;";

                $filtro_insert_almacen = "INSERT INTO `almacen` (`id`, `nombre`, `direccion`, `prefijo`, `consecutivo`, `activo`, `telefono`, `meta_diaria`) VALUES (NULL, 'General', NULL, 'G', '1', '1', '', NULL);";

                $filtro_insert_usuario_almacen = "INSERT INTO `usuario_almacen` (`id`, `usuario_id`, `almacen_id`) VALUES (NULL, '$id', '1');";

                $nuevas_imagenes_en_producto = "ALTER TABLE producto ADD imagen1 VARCHAR(254); ALTER TABLE producto ADD imagen2 VARCHAR(254); ALTER TABLE producto ADD imagen3 VARCHAR(254); ALTER TABLE producto ADD imagen4 VARCHAR(254); ALTER TABLE producto ADD imagen5 VARCHAR(254);";

                $insert_opciones = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES

                                                (1, 'nombre_empresa', ''),

                                                (2, 'resolucion_factura', ''),

                                                (3, 'logotipo_empresa', ''),

                                                (4, 'contacto_empresa', ''),

                                                (5, 'email_empresa', ''),

                                                (6, 'direccion_empresa', ''),

                                                (7, 'telefono_empresa', ''),

                                                (8, 'fax_empresa', ''),

                                                (9, 'web_empresa', ''),

                                                (17, 'moneda_empresa', 'USD'),

                                                (20, 'plantilla_empresa', 'default'),

                                                (21, 'paypal_email', ''),

                                                (22, 'cabecera_factura', ''),

                                                (23, 'terminos_condiciones', ''),

                                                (24, 'prefijo_presupuesto', 'P'),

                                                (25, 'numero_presupuesto', '1'),

                                                (26, 'numero_factura', '1'),

                                                (27, 'prefijo_factura', 'F'),

                                                (28, 'last_numero_factura', '1'),

                                                (29, 'last_numero_presupuesto', '1'),

                                                (30, 'nit', ''),

                                                (31, 'titulo_venta', ''),

                                                (32, 'sistema', 'Pos'),

                                                (32, 'resolucion_factura_estado', 'no');

";

                mysql_query($sql_almacen, $conn);

                mysql_query($sql_categoria, $conn);

                mysql_query($categoria_query, $conn);

                mysql_query($sql_clientes, $conn);

                mysql_query($sql_detalle_venta, $conn);

                mysql_query($sql_productos, $conn);

                mysql_query($productosf, $conn);

                mysql_query($sql_impuestos, $conn);

                mysql_query($insert_impusto, $conn);

                mysql_query($sql_nothing_tax, $conn);

                mysql_query($sql_opciones, $conn);

                mysql_query($sql_pagos, $conn);

                mysql_query($sql_proveedores, $conn);

                mysql_query($sql_proformas, $conn);

                mysql_query($sql_presupuestos, $conn);

                mysql_query($sql_presupuestos_detalles, $conn);

                mysql_query($sql_facturas, $conn);

                mysql_query($sql_facturas_detalles, $conn);

                mysql_query($stock_actual, $conn);

                mysql_query($stock_diario, $conn);

                mysql_query($stock_historial, $conn);

                mysql_query($usuario_almacen, $conn);

                mysql_query($vendedor, $conn);

                mysql_query($forma_pago, $conn);

                mysql_query($venta, $conn);

                mysql_query($ventas_pagos, $conn);

                mysql_query($rol, $conn);

                mysql_query($rol_permisos, $conn);

                mysql_query($filtros_facturas, $conn);

                mysql_query($filtros_facturas_detalles, $conn);

                mysql_query($filtros_pagos, $conn);

                mysql_query($filtros_presupuestos, $conn);

                mysql_query($filtros_detalles, $conn);

                mysql_query($filtros_productos, $conn);

                mysql_query($filtros_proformas, $conn);

                mysql_query($filtros_stock_actual, $conn);

                mysql_query($filtros_stock_diario, $conn);

                mysql_query($filtros_ventas, $conn);

                mysql_query($filtros_usuario_almacen, $conn);

                mysql_query($filtro_insert_almacen, $conn);

                mysql_query($filtro_insert_usuario_almacen, $conn);

                mysql_query($nuevas_imagenes_en_producto, $conn);

                mysql_query($insert_opciones, $conn);

                /*----------------------------------------------------------------/*
                | Julian 30/07/2014                                               |
                /*-----------------------------------------------------------------

                 */
                $clientes_grupo_id = "ALTER TABLE `clientes` ADD COLUMN `grupo_clientes_id` integer not null default 1";
                mysql_query($clientes_grupo_id, $conn);

                /*Cliente general*/
                $insert_cliente_general = "INSERT INTO `clientes` (`id_cliente`,`pais`, `nombre_comercial`, `nif_cif`, `grupo_clientes_id`) VALUES ('-1','Colombia', 'general', '0', '1')";
                mysql_query($insert_cliente_general, $conn);

                /*Nueva tabla grupo de clientes*/
                $grupo_clientes = "CREATE TABLE `grupo_clientes` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `nombre` VARCHAR(15) NOT NULL DEFAULT 'Unknown',
                      PRIMARY KEY (`id`)
                      /*foreign key (id) references producto(id)*/
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
                mysql_query($grupo_clientes, $conn);

                /*Sin grupo*/
                $sin_grupo = "INSERT INTO `grupo_clientes` VALUES (1,'sin grupo')";
                mysql_query($sin_grupo, $conn);

                /*Estado de venta Anulada - activa*/
                $venta_estado = "ALTER TABLE `venta` ADD COLUMN `estado` INT NULL DEFAULT 0 AFTER `total_venta`";
                mysql_query($venta_estado, $conn);

                /*Lista precios*/
                $lista_precios = "CREATE TABLE `lista_precios` (
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `nombre` VARCHAR(45) NULL,
                        `grupo_cliente_id` INT NULL,
                        `almacen_id` INT NULL,
                        `start` DATE NULL,
                        `end` DATE NULL,
                        PRIMARY KEY (`id`),
                        foreign key (grupo_cliente_id) references grupo_clientes(id),
                        foreign key (almacen_id) references almacen(id)
                    );";
                mysql_query($lista_precios, $conn);

                /*Lista detalle precios*/
                $lista_detalle_precios = "CREATE TABLE `lista_detalle_precios`(
                        `id` INT NOT NULL AUTO_INCREMENT,
                        `id_producto` INT NULL,
                        `id_impuesto` INT NULL,
                        `id_lista_precios` INT NULL,
                        `precio` float DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    );";
                mysql_query($lista_detalle_precios, $conn);

                /*Tabla anuladas*/
                $ventas_anuladas = "CREATE TABLE `ventas_anuladas` (
                      `id_venta_anulada` int(11) NOT NULL AUTO_INCREMENT,
                      `usuario_id` int(11) NOT NULL,
                      `fecha` datetime NOT NULL,
                      `motivo` text NOT NULL,
                      `venta_id` int(11) NOT NULL,
                      PRIMARY KEY (`id_venta_anulada`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8";
                mysql_query($ventas_anuladas, $conn);

                /*Comision en vendedor*/
                $comision_vendedor = "ALTER TABLE vendedor ADD `comision` int(11) NOT NULL DEFAULT '0'";
                mysql_query($comision_vendedor, $conn);

                /*Movimiento detalle*/
                $movimiento_detalle = "CREATE TABLE `movimiento_detalle` (
                        `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
                        `id_inventario` int(11) NOT NULL,
                        `codigo_barra` varchar(254) NOT NULL,
                        `cantidad` int(11) NOT NULL,
                        `precio_compra` int(11) NOT NULL,
                        `existencias` int(11) NOT NULL,
                        `nombre` varchar(254) NOT NULL,
                        `total_inventario` int(11) NOT NULL,
                        PRIMARY KEY (`id_detalle`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;";
                mysql_query($movimiento_detalle, $conn);

                /*Movimiento inventario*/
                $movimiento_inventario = "CREATE TABLE `movimiento_inventario` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `fecha` datetime NOT NULL,
                        `almacen_id` int(11) NOT NULL,
                        `almacen_traslado_id` int(11) DEFAULT NULL,
                        `tipo_movimiento` varchar(254) NOT NULL,
                        `codigo_factura` varchar(254) DEFAULT NULL,
                        `user_id` int(11) NOT NULL,
                        `total_inventario` int(11) NOT NULL,
                        `proveedor_id` int(11) DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1";
                mysql_query($movimiento_inventario, $conn);

                $plantilla_cotizacion = "INSERT INTO `opciones` (`nombre_opcion`, `valor_opcion`) VALUES ('plantilla_cotizacion', 'Estandar');";
                mysql_query($plantilla_cotizacion, $conn);

                /*INGREDIENTES =====================================================================================================*/

                $producto = "ALTER TABLE `producto`
                    ADD COLUMN `material` TINYINT(1) NULL DEFAULT '0' AFTER `imagen`,
                    ADD COLUMN `ingredientes` TINYINT(1) NULL DEFAULT '0' AFTER `material`,
                    ADD COLUMN `unidad_id` INT NULL DEFAULT '1' AFTER `ingredientes`;";
                mysql_query($producto, $conn);

                $producto_ingredientes = "CREATE TABLE `producto_ingredientes` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `id_producto` int(11) DEFAULT NULL,
                    `id_ingrediente` int(11) DEFAULT NULL,
                    `cantidad` double DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=latin1;";
                mysql_query($producto_ingredientes, $conn);

                $unidades = "CREATE TABLE `producto_modificacion` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `id_producto` int(11) DEFAULT NULL,
                    `nombre` varchar(100) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=latin1;";
                mysql_query($unidades, $conn);

                $unidades_default = "INSERT INTO `unidades` VALUES (1,'unidad'),(2,'gramo'),(3,'kilogramo'),(4,'libra'),(5,'litro'),(6,'mililitro'),(7,'onza');";
                mysql_query($unidades_default, $conn);
                /*....................................................................................................................*/

                /*Factura estandar y clasica */

                $tipo_factura = "INSERT INTO `opciones` (`nombre_opcion`, `valor_opcion`) VALUES ('tipo_factura', 'estandar');";
                mysql_query($tipo_factura, $conn);

                $tipo_factura_venta = "ALTER TABLE `venta` ADD COLUMN `tipo_factura` VARCHAR(10) NULL DEFAULT 'estandar' AFTER `estado`;";
                mysql_query($tipo_factura_venta, $conn);

                $venta_fecha_vencimiento = "ALTER TABLE `venta` ADD COLUMN `fecha_vencimiento` DATETIME NULL AFTER `tipo_factura`;";
                mysql_query($venta_fecha_vencimiento, $conn);

                /*Tipo producto*/

                $producto_tipo = "CREATE TABLE `producto_tipo` (
                      `id` INT NOT NULL ,
                      `nombre` VARCHAR(45) NULL,
                      PRIMARY KEY (`id`)
                    );";
                mysql_query($producto_tipo, $conn);

                $producto_tipo_AI = "ALTER TABLE `producto_tipo` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;";
                mysql_query($producto_tipo_AI, $conn);

                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('unico');";
                mysql_query($insert_producto_tipo, $conn);
                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('compuesto');";
                mysql_query($insert_producto_tipo, $conn);
                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('combo');";
                mysql_query($insert_producto_tipo, $conn);

                /*COMBO*/
                $alter_producto = "ALTER TABLE `producto` ADD COLUMN `combo` INT(11) NULL DEFAULT '0' AFTER `ingredientes`;";
                mysql_query($alter_producto, $conn);

                $producto_combos = "CREATE TABLE `producto_combos` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `id_combo` INT(11) NULL DEFAULT NULL,
                    `id_producto` INT(11) NULL DEFAULT NULL,
                    `cantidad` DOUBLE NULL DEFAULT NULL,
                    PRIMARY KEY (`id`));";
                mysql_query($producto_combos, $conn);

                $alter_producto = "ALTER TABLE `producto`
                      ADD COLUMN `combo` TINYINT(1) NULL DEFAULT '0' AFTER `material`;
                    ";
                mysql_query($alter_producto, $conn);

                //Pago servicios
                $pago = "CREATE TABLE `pago` (
                      `id_pago` int(11) NOT NULL AUTO_INCREMENT,
                      `id_factura` int(11) DEFAULT NULL,
                      `fecha_pago` date NOT NULL,
                      `cantidad` float(10,2) NOT NULL,
                      `tipo` varchar(254) NOT NULL,
                      `notas` text NOT NULL,
                      `importe_retencion` float(10,2) DEFAULT NULL,
                      PRIMARY KEY (`id_pago`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;";
                mysql_query($pago, $conn);

                $alter_venta = "ALTER TABLE `venta`
                    ADD COLUMN `tipo_factura` VARCHAR(45) NULL DEFAULT 'estandar' AFTER `estado`,
                    ADD COLUMN `fecha_vencimiento` VARCHAR(45) NULL DEFAULT NULL AFTER `tipo_factura`;";

                mysql_query($alter_venta, $conn);

                $comision = "ALTER TABLE `vendedor`
                    CHANGE COLUMN `comision` `comision` FLOAT NULL DEFAULT 0 ";

                mysql_query($comision, $conn);

                $comision = "ALTER TABLE `detalle_venta`
                    ADD COLUMN `descripcion_producto` TEXT NULL AFTER `nombre_producto`;";

                mysql_query($comision, $conn);

                $detalle_orden_compra = "
                CREATE TABLE `detalle_orden_compra` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `venta_id` int(11) NOT NULL,
                    `codigo_producto` varchar(15) DEFAULT NULL,
                    `nombre_producto` varchar(254) DEFAULT NULL,
                    `descripcion_producto` text,
                    `unidades` double DEFAULT NULL,
                    `precio_venta` double DEFAULT NULL,
                    `descuento` double DEFAULT NULL,
                    `impuesto` double DEFAULT NULL,
                    `impuesto_id` int(11) DEFAULT NULL,
                    `linea` varchar(254) DEFAULT NULL,
                    `margen_utilidad` double DEFAULT NULL,
                    `activo` tinyint(1) DEFAULT '1',
                    `id_unidad` int(11) DEFAULT NULL,
                    `producto_id` int(11) DEFAULT NULL,
                    `precio_venta_p` double DEFAULT NULL COMMENT 'precio de venta sin impuesto en orden de compra para cambiar',
                    `precio_venta_actual` double DEFAULT NULL COMMENT 'precio de venta sin impuesto del producto actual',
                    PRIMARY KEY (`id`),
                    KEY `detalle_venta_FKIndex1` (`venta_id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
                      ";

                mysql_query($detalle_orden_compra, $conn);

                $orden_compra = "
CREATE TABLE `orden_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `almacen_id` int(11) DEFAULT NULL,
  `forma_pago_id` int(11) DEFAULT NULL,
  `factura` varchar(254) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vendedor` int(11) DEFAULT NULL,
  `cambio` varchar(254) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `total_venta` double NOT NULL,
  `estado` int(11) DEFAULT '0',
  `tipo_factura` varchar(30) DEFAULT 'estandar',
  `fecha_vencimiento` date DEFAULT NULL,
  `nota` text NOT NULL,
  `motivo` text,
  `id_user_anulacion` int(11) DEFAULT NULL,
  `fecha_anulacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_FKIndex1` (`forma_pago_id`),
  KEY `venta_FKIndex2` (`almacen_id`),
  KEY `venta_cliente_id` (`cliente_id`),
  KEY `venta_vendedor_fk_idx` (`vendedor`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
                      ";

                mysql_query($orden_compra, $conn);

                $pago_orden_compra = "
CREATE TABLE `pago_orden_compra` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `id_factura` int(11) DEFAULT NULL,
  `fecha_pago` date NOT NULL,
  `cantidad` int(30) NOT NULL,
  `tipo` varchar(254) NOT NULL,
  `notas` text NOT NULL,
  `importe_retencion` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                      ";

                mysql_query($pago_orden_compra, $conn);

                $cambios1 = "ALTER TABLE `venta` ADD `nota` TEXT NOT NULL;";
                mysql_query($cambios1, $conn);

                $cambios2 = "ALTER TABLE `proformas` ADD `id_almacen` INT(30) NOT NULL; ";
                mysql_query($cambios2, $conn);

                $cambios3 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (35, 'numero', 'no');";
                mysql_query($cambios3, $conn);

                $cambios4 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (36, 'sobrecosto', 'no');";
                mysql_query($cambios4, $conn);

                $cambios5 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (37, 'multiples_formas_pago', 'no'); ";
                mysql_query($cambios5, $conn);

                $cambios6 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (38, 'vendedor_impresion', '1'); ";
                mysql_query($cambios6, $conn);

                $cambios7 = "ALTER TABLE `proformas` ADD `forma_pago` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios7, $conn);

                $cambios8 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (39, 'valor_caja', 'no'); ";
                mysql_query($cambios8, $conn);

                $cambios9 = "ALTER TABLE `almacen` ADD `ciudad` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios9, $conn);

                $cambios10 = "
CREATE TABLE IF NOT EXISTS `cajas` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_Almacen` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios10, $conn);

                $cambios11 = "
CREATE TABLE IF NOT EXISTS `cierres_caja` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  `id_Usuario` int(100) NOT NULL,
  `id_Caja` int(200) NOT NULL,
  `id_Almacen` int(50) NOT NULL,
  `total_egresos` varchar(200) NOT NULL,
  `total_ingresos` varchar(200) NOT NULL,
  `total_cierre` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios11, $conn);

                $cambios12 = "
CREATE TABLE IF NOT EXISTS `cuentas_dinero` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `tipo_cuenta` varchar(100) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `banco` varchar(100) NOT NULL,
  `tipo_bancaria` varchar(100) NOT NULL,
  `id_almacen` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios12, $conn);

                $cambios13 = "
CREATE TABLE IF NOT EXISTS `movimientos_cierre_caja` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Id_cierre` int(200) NOT NULL,
  `hora_movimiento` time NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `tipo_movimiento` varchar(100) NOT NULL,
  `valor` varchar(200) NOT NULL,
  `forma_pago` varchar(200) NOT NULL,
  `numero` varchar(100) NOT NULL,
  `id_mov_tip` int(150) NOT NULL,
  `tabla_mov` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;   ";
                mysql_query($cambios13, $conn);

                $cambios14 = "ALTER TABLE `usuario_almacen` ADD `id_Caja` INT(100) NOT NULL ; ";
                mysql_query($cambios14, $conn);

                $cambios15 = "ALTER TABLE `proformas` ADD `id_cuenta_dinero` INT(100) NOT NULL ; ";
                mysql_query($cambios15, $conn);

                $cambios16 = "ALTER TABLE `producto` ADD `stock_maximo` INT(100) NOT NULL ; ";
                mysql_query($cambios16, $conn);

                $cambios17 = "ALTER TABLE `producto` ADD `fecha_vencimiento` VARCHAR(100) NOT NULL ; ";
                mysql_query($cambios17, $conn);

                $cambios18 = "ALTER TABLE `producto` ADD `ubicacion` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios18, $conn);

                $cambios19 = "ALTER TABLE `producto` ADD `ganancia` INT(50) NOT NULL ; ";
                mysql_query($cambios19, $conn);

                $cambios20 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (41, 'filtro_ciudad', 'no');";
                mysql_query($cambios20, $conn);

                $cambios21 = "CREATE TABLE `factura_espera` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `almacen_id` int(11) DEFAULT NULL,
  `forma_pago_id` int(11) DEFAULT NULL,
  `factura` varchar(254) DEFAULT NULL,
  `no_factura` int(50) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vendedor` int(11) DEFAULT NULL,
  `cambio` varchar(254) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `total_venta` float NOT NULL,
  `estado` int(11) DEFAULT '0',
  `tipo_factura` varchar(10) DEFAULT 'estandar',
  `fecha_vencimiento` datetime DEFAULT NULL,
  `nota` text NOT NULL,
  `sobrecosto` int(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysql_query($cambios21, $conn);

                $cambios22 = "insert  into `factura_espera`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`no_factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`,`nota`,`sobrecosto`) values (-1,0,NULL,'Venta No ',NULL,'2015-06-24 00:32:06',0,-1,NULL,NULL,1,56010,0,'estandar','2015-06-24 00:32:06','',0); ";
                mysql_query($cambios22, $conn);

                $cambios23 = "CREATE TABLE `detalle_factura_espera` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `codigo_producto` varchar(15) DEFAULT NULL,
  `nombre_producto` varchar(254) DEFAULT NULL,
  `descripcion_producto` text,
  `unidades` varchar(150) DEFAULT NULL,
  `precio_venta` float DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `impuesto` float DEFAULT NULL,
  `impuesto_id` int(11) DEFAULT NULL,
  `linea` varchar(254) DEFAULT NULL,
  `margen_utilidad` float DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `id_producto` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  CONSTRAINT `detalle_factura_espera_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `factura_espera` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1; ";
                mysql_query($cambios23, $conn);

                @mysql_close($conn);

                unset($conn);

                $usuario = $this->db->username;

                $clave = $this->db->password;

                $servidor = $this->db->hostname;

                $base_dato = $this->db->database;

                $conn1 = @mysql_connect($servidor, $usuario, $clave);

                if (!$conn1) {

                    $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");

                } else {

                    mysql_select_db($base_dato, $conn1);

                    mysql_query("INSERT INTO `db_config` (`id` ,`servidor` ,`base_dato` ,`usuario` ,`clave` ,`fecha`)VALUES (NULL , '$servidor_multi', '$database_name', '$username_multi', '$clave_multi', '" . date('Y-m-d') . "');", $conn1);

                    $id_database = mysql_insert_id($conn1);

                    mysql_query("UPDATE `users` SET `db_config_id` = '$id_database' , is_admin = 't' WHERE `users`.`id` = $id;", $conn1);

                    @mysql_close($conn1);

                    unset($conn1);

                    $this->session->set_flashdata('message', "Su cuenta ha sido activada");
                }
            } else {
                $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");
            }

        }
    }

    //activate the user
    public function activate_count($id, $code = false)
    {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            try {
                $this->_create_db($id);
                $this->session->set_flashdata('message', "Su cuenta ha sido creada. Por favor verifique su email");

                redirect("auth", 'refresh');
            } catch (Exception $e) {
                redirect("auth", 'refresh');
            }
        } else {

            //redirect them to the forgot password page

            $this->session->set_flashdata('message', $this->ion_auth->errors());

            redirect("auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user

    public function deactivate($id = null)
    {

        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

        $this->load->library('form_validation');

        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');

        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == false) {

            // insert csrf check

            $this->data['csrf'] = $this->_get_csrf_nonce();

            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('auth/deactivate_user', $this->data);
        } else {

            // do we really want to deactivate?

            if ($this->input->post('confirm') == 'yes') {

                // do we have a valid request?

                if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {

                    show_error($this->lang->line('error_csrf'));

                }

                // do we have the right userlevel?

                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

                    $this->ion_auth->deactivate($id);

                }

            }

            //redirect them back to the auth page

            redirect('auth', 'refresh');
        }
    }

    //create a new user
    public function create_user()
    {
        $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() /*|| !$this->ion_auth->is_admin()*/) {
            redirect('auth', 'refresh');
        }

        //validate form input

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

        $this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'required|xss_clean|min_length[3]|max_length[3]');

        $this->form_validation->set_rules('phone2', $this->lang->line('create_user_validation_phone2_label'), 'required|xss_clean|min_length[3]|max_length[3]');

        $this->form_validation->set_rules('phone3', $this->lang->line('create_user_validation_phone3_label'), 'required|xss_clean|min_length[4]|max_length[4]');

        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');

        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));

            $email = $this->input->post('email');

            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
            );
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("auth", 'refresh');
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );

            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );

            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );

            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );

            $this->data['phone1'] = array(
                'name' => 'phone1',
                'id' => 'phone1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone1'),
            );

            $this->data['phone2'] = array(
                'name' => 'phone2',
                'id' => 'phone2',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone2'),
            );

            $this->data['phone3'] = array(
                'name' => 'phone3',
                'id' => 'phone3',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone3'),
            );

            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );

            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->_render_page('auth/create_user', $this->data);
        }
    }

    //edit a user

    public function edit_user($id)
    {

        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();

        $groups = $this->ion_auth->groups()->result_array();

        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //process the phone number

        if (isset($user->phone) && !empty($user->phone)) {
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

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
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

            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === true) {
                $this->ion_auth->update($user->id, $data);

                //check to see if we are creating the user

                //redirect them back to the admin page

                $this->session->set_flashdata('message', "User Saved");

                redirect("auth", 'refresh');
            }

        }

        //display the edit user form

        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view

        $this->data['user'] = $user;

        $this->data['groups'] = $groups;

        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),

        );

        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),

        );

        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),

        );

        $this->data['phone1'] = array(
            'name' => 'phone1',
            'id' => 'phone1',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone1', $user->phone[0]),

        );

        $this->data['phone2'] = array(
            'name' => 'phone2',
            'id' => 'phone2',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone2', $user->phone[1]),

        );

        $this->data['phone3'] = array(
            'name' => 'phone3',
            'id' => 'phone3',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone3', $user->phone[2]),

        );

        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',

        );

        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password',
        );

        $this->_render_page('auth/edit_user', $this->data);
    }

    // create a new group
    public function create_group()
    {
        $this->data['title'] = $this->lang->line('create_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));

            if ($new_group_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());

                redirect("auth", 'refresh');
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            );

            $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
            );

            $this->_render_page('auth/create_group', $this->data);
        }
    }

    //edit a group
    public function edit_group($id)
    {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $this->data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
        $this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === true) {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if ($group_update) {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }

                redirect("auth", 'refresh');
            }
        }

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data['group'] = $group;

        $this->data['group_name'] = array(
            'name' => 'group_name',
            'id' => 'group_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_name', $group->name),
        );

        $this->data['group_description'] = array(
            'name' => 'group_description',
            'id' => 'group_description',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        );

        $this->_render_page('auth/edit_group', $this->data);
    }

    public function _get_csrf_nonce()
    {
        $this->load->helper('string');

        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);

        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    public function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== false &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {

            return true;
        } else {
            return false;
        }
    }

    public function _render_page($view, $data = null, $render = false)
    {
        $this->viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $this->viewdata, $render);

        if (!$render) {
            return $view_html;
        }
    }

    public function restarFechas($start, $end)
    {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;

        return round($diff / 86400);
    }

    public function sendMailStore()
    {
        $data = array(
            "user" => $_POST['nombreUsuario'],
            "email" => $_POST['email'],
            "password" => $_POST['password'],
            "store" => $_POST['store'],
        );
        $mobile = $_POST['mobile'];
        $name = $_POST['name'];
        $html = $this->load->view('email/new_store', $data, true);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to($_POST['email']);
        $this->email->bcc('roxanna@vendty.com, info@vendty.com');
        $this->email->subject("¡Bienvenido a Vendty!");
        $this->email->message($html);
        $this->email->send();
    }

    public function sendMailTest()
    {
        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty Sistema Pos Cloud');
        $this->email->to('desarrollomovil@vendty.com');
        $this->email->subject("¡Bienvenido a Vendty!");
        $this->email->message('Prueba 1');
        $this->email->send();

        echo 'ok 1';
    }
}
