<?php

class Ventas extends CI_Controller
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

        $this->load->model("ventas_model", 'ventas');

        $this->ventas->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("vendedores_model", 'vendedores');

        $this->vendedores->initialize($this->dbConnection);

        $this->load->model("pagos_model", 'pagos');

        $this->pagos->initialize($this->dbConnection);

        /*$this->load->model("clientes_model",'clientes');

        $this->clientes->initialize($this->dbConnection);*/

        $this->load->model("clientes_model", 'clientes');

        $this->clientes->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');

        $this->productos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');

        $this->categorias->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("pais_provincia_model", 'pais_provincia');

        $this->load->model("facturas_model", 'facturas');

        $this->facturas->initialize($this->dbConnection);

        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);

    }

    public function nuevo()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        if (isset($_REQUEST['var'])) {
            $_REQUEST['var'];
        } else {
            $_REQUEST['var'] = 'buscalo';
        }

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array();

        $data['pais'] = $this->pais_provincia->get_pais();

        /* if($this->form_validation->run('facturas') == true){...}*/
        if (isset($_POST['vendedor'])) {

            //Identifica si una venta fue por POS o por Servicios
            if ($data_empresa['data']['tipo_factura'] != 'clasico') {
                $pago = $_POST['pago'];

                $pago_1 = $_POST['pago_1'];
                $pago_2 = $_POST['pago_2'];
                $pago_3 = $_POST['pago_3'];
                $pago_4 = $_POST['pago_4'];
                $pago_5 = $_POST['pago_5'];

                $tipo_factura = 'estandar';
                $fecha = date('Y-m-d H:i:s');
                $fecha_vencimiento = date('Y-m-d H:i:s');

            } else {

                $pago = array(
                    'valor_entregado' => $_POST['total_venta'],
                    'cambio' => 0,
                    'forma_pago' => 'Credito',
                );

                $tipo_factura = 'clasico';
                $fecha = $_POST['fecha'] . " " . date('H:i:s');
                $fecha_vencimiento = $_POST['fecha_v'];

            }

            $data = array(

                'fecha' => $fecha,

                'fecha_vencimiento' => $fecha_vencimiento,

                'cliente' => $_POST['cliente'],

                'vendedor' => $_POST['vendedor'],

                'usuario' => $this->session->userdata('user_id'),

                'productos' => $_POST['productos'],

                'total_venta' => $_POST['total_venta'],

                'pago' => $pago,

                'pago_1' => $pago_1,

                'pago_2' => $pago_2,

                'pago_3' => $pago_3,

                'pago_4' => $pago_4,

                'pago_5' => $pago_5,

                'tipo_factura' => $tipo_factura,

                'nota' => $_POST['nota'],

                'sobrecostos' => $_POST['sobrecostos'],

            );

            /*Registrar venta*/
            $id = $this->ventas->add($data);

            $data = array(

                'venta' => $this->ventas->get_by_id($id)

                , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)

                , 'detalle_pago' => $this->ventas->get_detalles_pago($id)

                , 'data_empresa' => $data_empresa,

            );

            /*Email*/
            $this->load->library('email');

            $this->email->clear();

            $this->email->from($data_empresa["data"]["email"], $data_empresa["data"]["nombre"]);

            $this->email->to('info@vendty.com');

            $this->email->subject("Su recibo de compra");

            if ($data_empresa['data']['plantilla'] == 'media_carta') {
                $message = $this->load->view('ventas/_imprimemediacarta', array('data' => $data), true);
            } else {
                $message = $this->load->view('ventas/imprime', array('data' => $data), true);
            }

            $message = $message . "<br/>Enviado por www.vendty.com";

            $this->email->message($message);

            $this->email->send();

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente"));

            $this->output->set_content_type('application/json')->set_output(json_encode(array('success' => true, 'id' => $id)));

        } else {

            $data["grupo_clientes"] = $this->clientes->get_group_all(0);

            $data["clientes"] = $this->clientes->get_all(0);

            $data['vendedores'] = $this->vendedores->get_combo_data();

            //Vitrina categorias----------------------------------------------------------- //
            $data['categorias'] = $this->categorias->get_limit(0);
            //...............................................................................

            //$data["productos"] = $this->productos->get_term('', $this->session->userdata('user_id'));

            $data['forma_pago'] = $this->pagos->get_tipos_pago();

            $data['sobrecosto'] = $data_empresa['data']['sobrecosto'];

            $data['multiples_formas_pago'] = $data_empresa['data']['multiples_formas_pago'];
            //Factura estandar --------------------------------------------------------------------------------------
            if ($data_empresa['data']['tipo_factura'] == 'estandar') {
                /*var_dump($data_empresa['data']['tipo_factura'] );*/

                $this->layout->template('ventas')

                    ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css"), base_url('public/css/multiselect/multiselect.css')))

                    ->js(array(base_url("/public/js/ventas.js?con=7"), base_url("/public/fancybox/jquery.fancybox.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))

                    ->show('ventas/nuevo', array('data' => $data));

            } else {

                // $data['cod'] = $this->_codigo();

                //Factura clasica -------------------------------------------------------------------------------------------------------
                $data['impuestos'] = $this->impuestos->get_combo_data_factura();

                $this->layout->template('member')->show('ventas/nclasico', array('data' => $data));

            }

        }

    }

    public function actualizar($id = null)
    {

        /*var_dump($this->db->get('venta'));  */

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array(

            'venta' => $this->ventas->get_by_id($id)

            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)

            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)

            , 'data_empresa' => $data_empresa

            , 'tipo_factura' => $data_empresa['data']['tipo_factura'],
        );

        if (isset($_POST['id_producto'])) {

            $id_compra = $_POST['id'];
            $id_producto = $_POST['id_producto'];
            $codigo = $_POST['codigo'];
            $product_service = $_POST['product-service'];
            $cantidad = $_POST['cantidad'];
            $precio = $_POST['precio'];
            $descuento = $_POST['descuento'];
            $n = count($id_producto);
            $i = 0;

            $data = array(
                'user_id' => $this->session->userdata('user_id')
                , 'fecha' => date('Y-m-d')
                , 'almacen_id' => $_POST['almacen']
                , 'tipo_movimiento' => 'entrada_compra'
                , 'total_inventario' => $_POST['input_total_siva']
                , 'proveedor_id' => $_POST['proveedor']
                , 'codigo_factura' => $_POST['id'],
            );

            while ($i < $n) {

                $data = array(

                    'id_compra' => $id_compra,

                    'id' => $id_producto[$i],

                    'unidades' => $cantidad[$i],

                    'nombre_producto' => $product_service[$i],

                    'precio_venta' => $precio[$i]

                    , 'almacen_id' => $_POST['almacen'],

                );
                $this->ventas->actualizar_venta($data, $id);

                $i++;
            }

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));

            redirect("ventas/index/");
        }

        //agregar producto
        if (isset($_POST['id_producto_ac'])) {

            $id = $_POST['id'];
            $id_producto = $_POST['id_producto_ac'];
            $codigo = $_POST['codigo_ac'];
            $product_service = utf8_decode($_POST['product-service_ac']);
            $cantidad = $_POST['cantidad_ac'];
            $precio = $_POST['precio_ac'];
            $descuento = $_POST['descuento_ac'];
            $impuesto = $_POST['id_impuesto'];
            $n = count($id_producto);
            $i = 0;

            $data = array(

                'id_compra' => $id,

                'codigo' => $codigo,

                'descuento' => $descuento,

                'impuesto' => $impuesto,

                'product_id' => $id_producto,

                'unidades' => $cantidad,

                'nombre_producto' => $product_service,

                'precio_venta' => $precio

                , 'almacen_id' => $_POST['almacen'],

            );

            $this->ventas->agregar_actualizar_venta($data, $id);

            $this->session->set_flashdata('message', custom_lang('sima_bill_created_message', "Se ha guardado correctamente en el inventario"));

            redirect("ventas/actualizar/" . $id);
        }

        //   $data['cod'] = $this->_codigo();

        //Factura clasica -------------------------------------------------------------------------------------------------------
        $data['impuestos'] = $this->impuestos->get_combo_data_factura();
        $this->layout->template('member')
            ->css(array(base_url("/public/css/stylesheets.css"), base_url('public/css/multiselect/multiselect.css')))
            ->show('ventas/actualizar', array('data' => $data, 'id' => $id));

    }

    public function eliminar_producto($venta, $id, $prod, $cant, $alm)
    {

        $this->ventas->eliminar_producto_actualizar($id, $prod, $cant, $alm);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha quitado correctamente el producto"));

        redirect("ventas/actualizar/" . $venta);

    }

    public function _codigo()
    {

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $last_numero_factura = $this->miempresa->last_numero_factura();

        $numero_factura = $this->miempresa->get_numero_factura();

        $prefijo_factura = $this->miempresa->get_prefijo_factura();

        $cod = $this->facturas->get_max_cod();

        $new_cod = "";

        if ($cod == '') {

            if ($numero_factura != $last_numero_factura) {

                $this->miempresa->update_last_numero_factura($numero_factura);

            }

            $dig = ((int) $numero_factura);

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;

            return $prefijo_factura . $new_cod;

        } else {

            if ($numero_factura != $last_numero_factura) {

                $this->miempresa->update_last_numero_factura($numero_factura);

                $cod = $numero_factura;

            } else {

                $cod = (int) $cod + 1;

            }

            $dig = ((int) $cod);

            $ceros = (6 - strlen($dig));

            $new_cod = str_repeat("0", $ceros) . $dig;

            return $prefijo_factura . $new_cod;

        }

    }

    public function pendiente()
    {

        if ($_POST) {

            $data = array(

                'cliente' => $_POST['cliente']['identificacion']

                , 'vendedor' => $_POST['vendedor']

                , 'usuario' => $this->session->userdata('user_id')

                , 'productos' => $_POST['productos']

                , 'total_venta' => $_POST['total_venta']

                , 'pago' => $_POST['pago'],

            );

            /*Registrar venta*/
            $id = $this->ventas->pendiente($data);
            echo "pendiente success = " . $id;

        }

    }

    public function get_ajax_data()
    {

        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data()));

    }

    public function editar($id)
    {

        /*var_dump($this->db->get('venta'));  */

        $this->ventas->edit($id);

        $data["grupo_clientes"] = $this->clientes->get_group_all(0);
        $data["clientes"] = $this->clientes->get_all(0);
        $data['vendedores'] = $this->vendedores->get_combo_data();
        $data['forma_pago'] = $this->pagos->get_tipos_pago();

        $this->layout->template('ventas')

            ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css"), base_url('public/css/multiselect/multiselect.css')))

            ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js"), base_url('public/js/plugins/multiselect/jquery.multi-select.js')))

            ->show('ventas/nuevo', array('data' => $data));

    }

    public function anular()
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $data = array(

            'id' => $_POST['venta_id']

            , 'usuario' => $this->session->userdata('user_id')

            , 'motivo' => $_POST['motivo'],

        );

        $this->ventas->anular($data);

        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha anulado correctamente"));

        redirect("ventas/index");

    }

    public function index($estado = 0)
    {

        $action = "ventas/index";

        if ($estado == -1) {

            $action = "ventas/ventas_anuladas";

        }

        $this->layout->template('member')

            ->css(array(base_url("/public/css/ventas.css"), base_url("/public/fancybox/jquery.fancybox.css")))

            ->js(array(base_url("/public/js/ventas.js"), base_url("/public/fancybox/jquery.fancybox.js")))

            ->show($action);

    }

    public function ventas_anuladas()
    {
        $this->index(-1);
    }

    public function get_ajax_data_anuladas()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas->get_ajax_data_anuladas(-1)));
    }

    public function imprimir($id)
    {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $get_by_id = $this->ventas->get_by_id($id);

        $username = '';
        $user = $this->db->query("SELECT username FROM users where id = '" . $get_by_id["usuario_id"] . "'")->result();
        foreach ($user as $dat) {
            $username = $dat->username;
        }

        $data = array(

            'venta' => $this->ventas->get_by_id($id)

            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)

            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)

            , 'detalle_pago_multiples' => $this->ventas->get_detalles_pago_result($id)

            , 'data_empresa' => $data_empresa

            , 'username' => $username

            , 'tipo_factura' => $data_empresa['data']['tipo_factura'],

        );
        if ($data_empresa['data']['plantilla'] == 'media_carta') {

            $this->layout->template('ajax')->show('ventas/_imprimemediacarta', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'general') {

            $this->layout->template('ajax')->show('ventas/_imprimemediacarta', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'media_carta_2') {

            $this->layout->template('ajax')->show('ventas/_imprimemediacarta2', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'media_carta_3') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta3', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta4', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna_logo_redondo') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_redondo', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'ticket_cafeterias') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_cafeterias', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'ticket_cafeterias_decimales') {
            $this->layout->template('ajax')->show('ventas/_imprime_ticket_cafeterias_decimales', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna_ingles') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_ingles', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna_completa_ingles') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_ingles_completa', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna_codibarras') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_codigo_barras_media', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == '_imprimemediacarta_especial_1') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_especial_1', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == '_imprimemediacarta_especial_2') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_especial_2', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna_izq') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_izq', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'moderna_izq_discriminado_iva') {
            $this->layout->template('ajax')->show('ventas/_imprimemediacarta_logo_izq_discriminado_iva', array('data' => $data));

        } else if ($data_empresa['data']['plantilla'] == 'modelo_factura_clasica') {
            $this->layout->template('ajax')->show('ventas/_imprime_clasica', array('data' => $data));

        } else {

            $this->layout->template('ajax')->show('ventas/imprime', array('data' => $data));

        }
    }

    public function guia_despacho($id)
    {

        $this->load->model("miempresa_model", 'mi_empresa');

        $this->mi_empresa->initialize($this->dbConnection);

        $data_empresa = $this->mi_empresa->get_data_empresa();

        $data = array(

            'venta' => $this->ventas->get_by_id($id)

            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)

            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)

            , 'data_empresa' => $data_empresa

            , 'tipo_factura' => $data_empresa['data']['tipo_factura'],

        );

        $this->layout->template('ajax')->show('ventas/_imprimemediacartaguiadespacho', array('data' => $data));

    }

    public function enviar_email($id)
    {

        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $empresa = $this->miempresa->get_data_empresa();

        $data = array(

            'venta' => $this->ventas->get_by_id($id)

            , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)

            , 'detalle_pago' => $this->ventas->get_detalles_pago($id)

            , 'data_empresa' => $empresa,

        );

        $this->email->clear();

        $this->email->from($empresa["data"]["email"], $empresa["data"]["nombre"]);

        $this->email->to($data['venta']['cliente_email']);

        $this->email->subject("Factura " . $data['venta']["numero"]);

        $this->email->message("Para ver su factura por favor verifique su adjunto.");

        /*die(); */
        require_once APPPATH . 'libraries/tcpdf/tcpdf_import.php';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, "LETTER", true, 'UTF-8', false);

        $pdf->setPrintHeader(false);

        $pdf->setPrintFooter(false);

        $pdf->AddPage('P', "LETTER");

        $dire = $empresa["data"]['direccion'];
        $telef = $empresa["data"]['telefono'];
        $email = $empresa["data"]['email'];

        $cafac = $empresa["data"]['cabecera_factura'];
        $nit = $empresa["data"]['nit'];
        $resol = $empresa["data"]['resolucion'];
        $tele = $empresa["data"]['telefono'];
        $dire = $empresa["data"]['direccion'];
        $web = $empresa["data"]['web'];

        $img = base_url("uploads/{$empresa['data']['logotipo']}");

        $fech = date("d/m/Y", strtotime($data['venta']['fecha']));
        $numero = $data['venta']['factura'];

        $nif_cif = $data['venta']['nif_cif'];
        $nomcomercial_cli = $data['venta']['nombre_comercial'];
        $direccion_cli = $data['venta']['cliente_direccion'];
        $telefono_cli = $data['venta']['cliente_telefono'];

        $html = '
                     <table width="650px" style="border: 1px solid #000000; border-bottom: 1px solid #000000; font-size: 9px">
                        <tr>
                            <td width="33%"  align="center" style=" font-size: 11px">

                ' . $empresa['data']['documento'] . ': ' . $nit . ' <br>
                ' . $resol . ' <br>
                ' . $tele . ' <br>
                ' . $dire . ' <br>
                ' . $web . '
                            </td>

                            <td width="33%"  align="center">
                             ' . $cafac . '
                            </td>

                            <td width="20%" align="right">
';
        if (!empty($empresa["data"]['logotipo'])) {
            $pdf->Image($img, 55, 13, 43, 15, 'JPG', '', '', true, 150, 'R', false, false, 0, false, false, false);
        }
        $html .= '
                           </td>
						</tr>
				</table>
';

        $html .= '
        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; font-size: 9px">
                      <tr>
                        <td>
                      <b>Fecha de la cotización</b>  ' . $fech . '
                        </td>
                         <td align="right">
                          <b>No. de cotización</b> ' . $numero . '
                         </td>
                      </tr>
                     <tr>
                        <td style="border-top: 1px solid #000000;">
					   <b>Cliente: </b> ' . $nomcomercial_cli . '
                         </td>
						 <td style="border-top: 1px solid #000000;">
						<b>Dirección: </b>
						 ' . $direccion_cli . '
						  </td>
					 </tr>
                     <tr>
                        <td style="border-top: 1px solid #000000;">
					   <B>C.C/NIT:</B> ' . $nif_cif . '
                         </td>
						 <td style="border-top: 1px solid #000000;">
						<b>Telefono: </b> ' . $telefono_cli . '
						  </td>
					 </tr>
                   </table>
';

        $html .= '
        <table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; font-size: 9px">
                      <tr>
                        <th  style="border: inset 1px #000000; " align="left">Ref</th>
                        <th  style="border: inset 1px #000000; " align="left">Descripción</th>
                        <th  style="border: inset 1px #000000; " align="left">Cantidad</th>
                        <th  style="border: inset 1px #000000; " align="left">Precio</th>
                        <th  style="border: inset 1px #000000; " align="left">Desc</th>
                        <th  style="border: inset 1px #000000; " align="left">Total</th>
                      </tr>
';

        $total = 0;

        $timp = 0;

        $subtotal = 0;

        $total_items = 0;

        $group_by_impuesto = array();
        $counter = null;
        $hasta = null;
        foreach ($data["detalle_venta"] as $p) {
            $counter++;
            if ($empresa['data']['tipo_factura'] == 'clasico') {
                /* SERVICIOS */
                $pv = $p['precio_venta'];

                $desc = $p['descuento'];

                $pvd = $pv - ($pv * ($desc / 100));

                $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

                $total_column = $pvd * $p['unidades'];

                $total_items += $total_column;

                $valor_total = $pvd * $p['unidades'] + $imp;

                $total += $total + $valor_total;

                $timp += $imp;
            } else {
                /* POS */
                $pv = $p['precio_venta'];
                $desc = $p['descuento'];
                $pvd = $pv - $desc;
                $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
                $total_column = $pvd * $p['unidades'];
                $total_items += $total_column;
                $valor_total = $pvd * $p['unidades'] + $imp;
                $total += $total + $valor_total;
                $timp += $imp;
            }

            $precio_venta_final = number_format($p['precio_venta']);
            $precio_column_final = number_format($total_column);
            $html .= '
                      <tr>
                            <td  style="font-size: 9px" align="left"> ' . $p["codigo"] . '</td>
                            <td  style="font-size: 9px" align="left"> ' . $p["nombre_producto"] . ' </td>
                            <td  style="font-size: 9px" align="right"> ' . $p["unidades"] . '</td>
                            <td  style="font-size: 9px" align="right">$  ' . $precio_venta_final . '</td>
                            <td style="font-size: 9px"  align="right"> ' . $p["descuento"] . '</td>
                            <td style="font-size: 9px"  align="right">$ ' . $precio_column_final . '</td>
						 </tr>
';
        }
        $hasta = 10 - $counter;
        for ($i = 1; $i <= $hasta; $i++) {
            $html .= '
                      <tr>
                       <td  style="font-size: 10px" align="left">   </td>
                       <td  style="font-size: 10px" align="left">   </td>
                       <td  style="font-size: 10px" align="left">  </td>
                       <td  style="font-size: 10px" align="right">   </td>
                       <td  style="font-size: 10px" align="right">  </td>
                      </tr>
';
        }
        $html .= '
              </table>
';

        $total = $total_items + $timp;
        $formpago = str_replace("_", " ", $data['detalle_pago']['forma_pago']);
        $total_items = number_format($total_items);
        $timp = number_format($timp);
        $formpago = ucfirst($formpago);

        $valor_entregado = number_format($data['detalle_pago']['valor_entregado']);
        $cambio = number_format($data['detalle_pago']['cambio']);
        $total = number_format($total);

        $terminos_condiciones = str_replace('size=', "", $empresa['data']['terminos_condiciones']);

        if ($data['detalle_pago']['forma_pago'] != 'efectivo') {
            $efectivo_final_sin = "<b>Forma de pago: </b> <br> ";
        }

        if ($data['detalle_pago']['forma_pago'] == 'efectivo') {
            $efectivo_final = "
		 <b> Efectivo: </b> <br>
		 <b> Cambio: </b>  <br>
		 ";
        }

        if ($data['detalle_pago']['forma_pago'] != 'efectivo') {
            $efectivo_final_1 = '
			  ' . $formpago . '<br>  ';
        }
        if ($data['detalle_pago']['forma_pago'] == 'efectivo') {
            $efectivo_final_2 = '
		    $ ' . $valor_entregado . ' <br>
	        $ ' . $cambio . ' <br> ';
        }

        $html .= '
<table width="559px" style="border-left: 1px solid #000000; border-right: 1px solid  #000000; border-top: 1px solid  #000000; border-bottom: 1px solid #000000;">
               <tr>
               <td style="border-right: inset 1px #000000; width: 20%; font-size: 8px;" align="center"  ><br><br><br><br><br>______________________<br><B>FIRMA DEL CLIENTE</B></td>
	 <td style="border-right: solid 1px #000000; font-size: 8px; width: 260px" align="left">' . $terminos_condiciones . '</td>
               <td style="border-right: solid 1px #000000; font-size: 10px; width: 90px; " align="left">
			   <b>Valor items: </b><br>
			   <b>T.Impuestos: </b><br>
			   ' . $efectivo_final_sin . '
			   ' . $efectivo_final . '
			   <b>Total a Pagar:</b>
			    </td>
';
        $html .= '
			   <td style="border-right: solid 1px #000000; font-size: 9px; width: 97px; " align="right">
			  $ ' . $total_items . '<br>
			  $ ' . $timp . '<br>
			   ' . $efectivo_final_1 . '
			   ' . $efectivo_final_2 . '
			  $ ' . $total . '

			    </td>
 ';

        $html .= '
               </tr>
			 </table>
 ';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf_name = "Factura-" . $data['venta']['id_venta'] . "-" . $data['venta']['factura'] . ".pdf";

        $pdf_name = 'Factura_' . $data_factura['numero'] . '.pdf';

        $pdf->Output("uploads/$pdf_name", 'F');

        $this->email->attach("uploads/$pdf_name");

        $this->email->send();

        unlink("uploads/$pdf_name");

        $this->session->set_flashdata('message', custom_lang('sima_bill_send_message', "Se ha enviado la factura correctamente"));

        redirect("ventas/index");

    }

    public function pagos_servicio($id)
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $venta = $this->ventas->get_by_id($id);
        $pagos = false;

        if ($venta['tipo_factura'] != 'estandar') {
            $pagos = true;
        } else {
            $detalles_pago = $this->ventas->get_detalles_pago($id);

            if ($detalles_pago['forma_pago'] == 'Credito') {
                $pagos = true;
            }
        }

        if ($pagos) {
            $data_empresa = $this->miempresa->get_data_empresa();
            $data = array();
            $data['venta_credito'] = array(
                'venta' => $venta
                , 'detalle_venta' => $this->ventas->get_detalles_ventas($id)
                , 'detalle_pago' => $this->ventas->get_detalles_pago($id)
                , 'data_empresa' => $data_empresa,
            );
            $data['tipo'] = $this->pagos->get_tipos_pago();
            $data["total"] = $this->pagos->get_total($id);
            $data["data"] = $this->pagos->get_all($id, 0);
            $numero = $this->ventas->get_by_id($id);
            $data['numero'] = $numero["factura"];
            $data["id_factura"] = $id;

            $this->layout->template('member')->show('pagos/ver_pago', array('data' => $data));
        }
    }
}
