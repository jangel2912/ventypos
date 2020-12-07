<?php

class Ventas_online extends CI_Controller
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

        $this->load->model("tienda_model", 'tienda');
        $this->load->model("usuarios_model", 'user');
        $this->load->model("redes_model", 'redes');

        $this->load->model("ventas_online_model", 'ventas_online');
        $this->ventas_online->initialize($this->dbConnection);
        //creacion de tablas si no existen
        $this->ventas_online->existeTabla($base_dato);
        $this->ventas_online->actualizarVenta();

        $this->load->model("ventas_online_prod_model", 'ventas_online_prod');
        $this->ventas_online_prod->initialize($this->dbConnection);

        /* Begin Load Models for aditions, Modifications and Schedule*/
        $this->load->model("ventas_online_prod_adition_model", 'ventas_online_prod_adition');
        $this->ventas_online_prod_adition->initialize($this->dbConnection);
        $this->ventas_online_prod_adition->existeTabla($base_dato);

        $this->load->model("ventas_online_prod_modification_model", 'ventas_online_prod_modification');
        $this->ventas_online_prod_modification->initialize($this->dbConnection);
        $this->ventas_online_prod_modification->existeTabla($base_dato);
        
        $this->load->model("ventas_online_schedule_model", 'ventas_online_schedule_model');
        $this->ventas_online_schedule_model->initialize($this->dbConnection);
        /* End Load Models for aditions, Modifications and Schedule*/

        $this->load->model("stock_actual_model", 'stock_actual');
        $this->stock_actual->initialize($this->dbConnection);

        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');
        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("forma_pago_model", 'formaPago');
        $this->formaPago->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'mi_empresa');
        $this->mi_empresa->initialize($this->dbConnection);

    }

    public function get_ajax_data()
    {
        //echo "bn";die;
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas_online->get_ajax_data()));
    }

    public function get_ajax_data_orden()
    {
        //echo "bn";die;
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas_online->get_ajax_data_orden()));
    }

    public function get_ajax_data_anulada()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->ventas_online->get_ajax_data_anulada()));
    }

    public function ventas()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        //agregar tabla online_venta y online_venta_prod
        $this->ventas_online->existeTabla($this->session->userdata('base_dato'));
        //actualizar tabla online_venta si ya existia
        $this->ventas_online->actualizarVenta();

        $data['facturacion_electronica'] = $this->session->userdata('electronic_invoicing');

        $this->layout->template('member')->show('ventas_online/venta_virtual', array(
            'data' => $data,
        ));
    }

    public function ventas_orden()
    {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        //agregar tabla online_venta y online_venta_prod
        $this->ventas_online->existeTabla($this->session->userdata('base_dato'));
        //actualizar tabla online_venta si ya existia
        $this->ventas_online->actualizarVenta();

        $user_id = $this->session->userdata('user_id');

        $data = $this->tienda->get_by_id_user($user_id);

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['data_empresa'] = $data_empresa;
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $dataRed = $this->redes->getByUser($user_id);

        $dir_tienda = $this->config->item('url_shop');

        $this->layout->template('member')->show('ventas_online/venta_virtual_orden', array(
            'data' => $data,
            'dir_tienda' => $dir_tienda,
            'dataRed' => $dataRed,
        ));
    }

    public function ventas_anuladas()
    {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        //agregar tabla online_venta y online_venta_prod
        $this->ventas_online->existeTabla($this->session->userdata('base_dato'));
        //actualizar tabla online_venta si ya existia
        $this->ventas_online->actualizarVenta($this->session->userdata('base_dato'));

        $user_id = $this->session->userdata('user_id');

        $data = $this->tienda->get_by_id_user($user_id);

        $dataRed = $this->redes->getByUser($user_id);

        $dir_tienda = $this->config->item('url_shop');

        $data_empresa = $this->mi_empresa->get_data_empresa();
        $data['data_empresa'] = $data_empresa;
        $data["tipo_negocio"] = $data_empresa['data']['tipo_negocio'];

        $this->layout->template('member')->show('ventas_online/venta_virtual_anulada', array(
            'data' => $data,
            'dir_tienda' => $dir_tienda,
            'dataRed' => $dataRed,
        ));
    }

    public function atender_solicitud()
    {

        $user_id = $this->session->userdata('user_id');
        $data_tienda = $this->tienda->get_by_id_user($user_id);

        $id_venta_online = $this->input->post('conf_id');

        $venta = $this->ventas_online->get_by_id($id_venta_online);

        $prod_venta = $this->ventas_online_prod->get_by_id_venta($id_venta_online);

        $multiples_almacenes = false;
        if ($data_tienda['stock_almacen'] == 1) {
            $seleccion_origen_productos = $this->input->post('s_almacen_origen_confirmacion');
            $multiples_almacenes = true;
            $almacen_global = 0;
            if ($seleccion_origen_productos != 'individuales') {
                $almacen_global = $seleccion_origen_productos;
            }
        }

        $exito_procesar_producto = false;

        foreach ($prod_venta as $pv) {
            $id_almacen = $data_tienda['id_almacen'];
            if ($multiples_almacenes) {
                if ($almacen_global > 0) {
                    $obj = $this->stock_actual->get_by_prod_almac($almacen_global, $pv['id_producto']);
                    $id_almacen = $almacen_global;
                } else {
                    $almacen_producto = $this->input->post('s_almacen_independiente_' . $pv['id_producto']);
                    $obj = $this->stock_actual->get_by_prod_almac($almacen_producto, $pv['id_producto']);
                    $id_almacen = $almacen_producto;
                }
            } else {
                $obj = $this->stock_actual->get_by_prod_almac($data_tienda['id_almacen'], $pv['id_producto']);
            }

            $valor = $obj['unidades'] - $pv['cantidad'];

            if ($valor >= 0) {
                $this->stock_actual->update_by_prod_almac($id_almacen, $pv['id_producto'], $valor);
                $exito_procesar_producto = true;
            } else {
                $exito_procesar_producto = false;
                $datos_producto = $this->productos->get_by_id($pv['id_producto']);
                $this->session->set_flashdata('message', custom_lang('sima_bill_error_message', "Su solicitud no se puede atender, el producto " . $datos_producto['nombre'] . " el almacen tiene " . $obj['unidades'] . " y la venta tiene " . $pv['cantidad']));
                break;
            }
        }

        if ($exito_procesar_producto) {
            $this->ventas_online->update('estado', 11, $id_venta_online);
            $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Su solicitud ha sido atendida satisfactoriamente."));
        }

        redirect("ventas_online/ventas/");
    }

    public function get_detalle_confirmacion_view($id)
    {
        $data = $this->get_detalle_confirmacion($id, 'array');
        $this->layout->template('member')->show('ventas_online/confirmar_venta_online', array(
            'data' => (object) $data,
        ));

    }

    public function get_detalle_confirmacion($id, $forma_devolver = 'json')
    {

        $user_id = $this->session->userdata('user_id');
        $data_tienda = $this->tienda->get_by_id_user($user_id);
        $venta = $this->ventas_online->get_by_id($id);
        $prod_venta = $this->ventas_online_prod->get_by_id_venta($id);
        $productos = '';

        $disp = true;
        $color = '';
        $descuento = 0;
        $descuento_imp = 0;

        foreach ($prod_venta as $pr) {
            $color = '';
            $stock_todos_almacenes_string = '';
            $almacenes = '';
            //todos los almacenes se muestra el inventario de todos
            if ($data_tienda['stock_almacen'] == 0) {
                $stock_actual = $this->ventas_online_prod->get_stock_actual($pr['id_producto'], $data_tienda['id_almacen']);
                $datos_almacen = $this->almacenes->get_by_id($data_tienda['id_almacen']);
                $almacenes = $datos_almacen['nombre'];
                $st = $stock_actual['unidades'];
                $stock_todos_almacenes_string = '<td></td>';
            } else {
                if ($this->session->userdata('is_admin') == 't') {
                    $almacenes = $this->consultar_stock_todos_almacen($pr['id_producto']);
                } else {
                    $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
                    $datos_almacen = $this->almacenes->get_by_id($id_almacen);
                    $almacenes = $datos_almacen['nombre'];
                }
                $stock_almacenes = $this->consultar_stock_todos_almacen($pr['id_producto']);
                $stock_actual['unidades'] = 0;
                foreach ($stock_almacenes as $key => $value) {
                    $stock_actual['unidades'] += $value['stock_actual'];
                }
                $st = $stock_actual['unidades'];
                $stock_todos_almacenes_string = $this->consultar_stock_todos_almacen($pr['id_producto'], 'en_string');

            }

            if ($st < $pr['cantidad']) {

                $disp = false;
                $color = 'style = "border: 2px solid red !important"';
                $descuento += $pr['total'];
                $prod = $this->productos->get_by_id($pr['id_producto']);
                $imp = $this->impuestos->get_by_id($prod['impuesto']);
                $descuento_imp += $pr['total'] * $imp['porciento'] / 100;
            }

            $productos .= '<tr ' . $color . ' data-id="' . $pr['id_producto'] . '" >
                            <td class="tabp ">' . $pr['descripcion'] . '</td>
                            <td class="tabpr ">$ ' . $pr['precio'] . '</td>
                            <td class="tabcm ">' . $pr['cantidad'] . '</td>
                            <td class="tabcm " >' . $st . '</td>
                            <td class="tabt ">$ ' . $pr['total'] . '</td>';
            $productos .= $stock_todos_almacenes_string . '</tr>';
        }

        if ($disp) {
            $msg = 1;
        }
        //con disponibilidad
        else {
            $msg = 2;
        }
        // sin disp

        $puede = 0;
        if ($descuento == $venta['sub_total']) {
            $puede = 2; // 0-puede todo, 1- hay descuento , 2 no puede
            $msg = 3;
        } else if ($descuento > 0) {
            $puede = 1;
        }

        $data = array(
            'id' => $id,
            'fecha' => $venta['fecha'],
            'productos' => $productos,
            'subtotal' => $venta['sub_total'],
            'impuesto' => $venta['tasa_impuesto'],
            'total' => $venta['sub_total'] + $venta['tasa_impuesto'],
            'puede' => $puede,
            'descuento' => $descuento,
            'descuento_imp' => $descuento_imp,
            'final' => $venta['sub_total'] + $venta['tasa_impuesto'] - $descuento - $descuento_imp,
            'msg' => $msg,
            'almacenes' => $almacenes,
            'stock_almacen' => $data_tienda['stock_almacen'],
            'venta_id' => $venta['venta_id'],
        );
        if ($forma_devolver == 'json') {
            echo json_encode($data);
        } else {
            return $data;
        }

    }

    public function consultar_stock_todos_almacen($id_producto, $forma_devolver = 'array')
    {
        $almacenes = $this->almacenes->get_almacenes_activos();
        $stock_almacenes = array();

        foreach ($almacenes as $key => $value) {
            $stock = $this->ventas_online_prod->get_stock_actual($id_producto, $value->id);
            $stock_almacenes[$value->id] = array('id' => $value->id, 'nombre' => $value->nombre, 'almacen' => $value->nombre, 'stock_actual' => $stock['unidades']);
        }

        if ($forma_devolver == 'array') {
            return $stock_almacenes;
        } else {
            $string = '<td class="tabp select_existencias">';

            foreach ($stock_almacenes as $key => $value) {
                $string .= '<b>' . $value['almacen'] . ':</b><br> ' . $value['stock_actual'] . '<br>';
            }

            $string .= '</td>';

            return $string;
        }
    }

    public function get_detalle_solicitud($id)
    {
        $venta = $this->ventas_online->get_by_id($id);
        $prod_venta = $this->ventas_online_prod->get_by_id_venta($id);
        $schedule = $this->ventas_online_schedule_model->get_schedule_by_online_venta_id($id);

        $estado = '';
        $productos = '<tr>
            <td class="tabp tabhead">Producto</td>
            <td class="tabpr tabhead">Precio Unitario</td>
            <td class="tabc tabhead">Cantidad</td>
            <td class="tabt tabhead">Total</td>
        </tr>';

        foreach ($prod_venta as $pr) {
            //Get all aditions
            $prod_venta_adition = $this->ventas_online_prod_adition->get_aditions_by_online_venta_prod_id($pr['id']);
            $prod_venta_adition_result = "";
            if (count($prod_venta_adition) > 0) {
                $prod_venta_adition_result = "<p><strong>Adiciones</strong></p><ul>";
                foreach ($prod_venta_adition as $adition) {
                    $prod_venta_adition_result .= "<li>" . $adition['qty'] . " " . $adition['nombre'] . "</li>";
                }
                $prod_venta_adition_result .= "</ul>";
            }
            //Get all mdifications
            $prod_venta_modification = $this->ventas_online_prod_modification->get_modifications_by_online_venta_prod_id($pr['id']);
            $prod_venta_modification_result = "";
            if (count($prod_venta_modification) > 0) {
                $prod_venta_modification_result = "<p><strong>Modificaciones</strong></p><ul>";
                foreach ($prod_venta_modification as $modification) {
                    $prod_venta_modification_result .= "<li>" . $modification['nombre'] . "</li>";
                }
                $prod_venta_modification_result .= "</ul>";
            }

            $productos .= '<tr>
                <td class="tabp "><h4>' . $pr['descripcion']. "</h4>" . $prod_venta_adition_result . $prod_venta_modification_result . '</td>
                <td class="tabpr ">$ ' . $pr['precio'] . '</td>
                <td class="tabc ">' . $pr['cantidad'] . '</td>
                <td class="tabt ">$ ' . $pr['total'] . '</td>
            </tr>';
        }

        if ($venta['estado'] == 10) {
            $estado = 'INICIADA';
        } else if ($venta['estado'] == 11) {
            $estado = 'ATENDIDA';
        } else if ($venta['estado'] == 12) {
            $estado = 'ANULADA';
        } else if ($venta['estado'] == 13) {
            $estado = 'FACTURADA';
        }

        $data = array(
            'id' => $id,
            'nombre' => $venta['nombre'] . " " . $venta['nombre2'] . " " . $venta['apellidos'],
            'cedula' => $venta['dni'],
            'email' => $venta['email'],
            'telefono' => $venta['telefono'],
            'poblacion' => $venta['poblacion'],
            'notas' => $venta['notas'],
            'movil' => (!is_null($venta['movil'])) ? $venta['movil'] : $venta['telefono'],
            'schedule' => $schedule,
            'cpostal' => $venta['cpostal'],
            'direccion' => $venta['direccion'],
            'notas_adicionales' => $venta['notas_adicionales'],
            'fecha_estado' => $venta['fecha'],
            'estado' => $estado,
            'productos' => $productos,
            'subtotal' => $venta['sub_total'],
            'impuesto' => $venta['tasa_impuesto'],
            'total' => $venta['sub_total'] + $venta['tasa_impuesto'],
            'nombre_envio' => (isset($venta['nombre_envio'])) ? $venta['nombre_envio'] : $venta['nombre'],
            'nombre2_envio' => (isset($venta['nombre2_envio'])) ? $venta['nombre2_envio'] : $venta['nombre2'],
            'telefono_envio' => (isset($venta['telefono_envio'])) ? $venta['telefono_envio'] : $venta['telefono'],
            'correo_envio' => (isset($venta['correo_envio'])) ? $venta['correo_envio'] : $venta['email'],
            'direccion_envio' => (isset($venta['direccion_envio'])) ? $venta['direccion_envio'] : $venta['direccion'],
        );

        echo json_encode($data);
    }

    public function eliminar_solicitud_ventas($id)
    {
        $venta = $this->ventas_online->get_by_id($id);
        // if ($venta['estado'] == 2 or $venta['estado'] == 3 ) {
        //     $this->ventas_online->delete($id);
        //     //echo json_encode('Se ha eliminado correctamente la solicitud de venta online.');
        //     $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha rechazado correctamente la Solicitud de Venta Online (" . $id . ")."));
        // } else {
        //     $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "No se puede rechazar la compra en estado diferente a pendiente pago"));
        // }

        $this->ventas_online->delete($id);
        //echo json_encode('Se ha eliminado correctamente la solicitud de venta online.');
        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha rechazado correctamente la Solicitud de Venta Online (" . $id . ")."));

        redirect("ventas_online/ventas/");
    }

    public function eliminar_solicitud_ventas_orden($id)
    {
        $venta = $this->ventas_online->get_by_id($id);
        $this->ventas_online->delete($id);
        $this->session->set_flashdata('message', custom_lang('sima_bill_deleted_message', "Se ha rechazado correctamente la Solicitud de la Orden (" . $id . ")."));

        redirect("ventas_online/ventas_orden/");
    }

    public function facturarConfirmacion($id)
    {
        $db_config_id = $this->session->userdata('db_config_id');
        $data_tienda = $this->tienda->get_by_db_config($db_config_id);
        $venta = $this->ventas_online->get_by_id($id);
        $prod_venta = $this->ventas_online_prod->get_by_id_venta($id);
        $productos = '<tr>
            <td class="tabp tabhead">Producto</td>
            <td class="tabpr tabhead">Precio Unitario</td>
            <td class="tabcm tabhead">Cant.</td>
            <td class="tabcm tabhead">Disp.</td>
            <td class="tabt tabhead">Total</td>
        </tr>';

        $descuento = 0;
        $descuento_imp = 0;

        foreach ($prod_venta as $pr) {
            $stock_actual = $this->ventas_online_prod->get_stock_actual($pr['id_producto'], $data_tienda['id_almacen']);

            if (isset($stock_actual['unidades'])) {
                $st = $stock_actual['unidades'];
            } else {
                $st = 0;
            }

            //Get all aditions
            $prod_venta_adition = $this->ventas_online_prod_adition->get_aditions_by_online_venta_prod_id($pr['id']);
            $prod_venta_adition_result = "";
            if (count($prod_venta_adition) > 0) {
                $prod_venta_adition_result = "<p><strong>Adiciones</strong></p><ul>";
                foreach ($prod_venta_adition as $adition) {
                    $prod_venta_adition_result .= "<li>" . $adition['qty'] . " " . $adition['nombre'] . "</li>";
                }
                $prod_venta_adition_result .= "</ul>";
            }
            //Get all mdifications
            $prod_venta_modification = $this->ventas_online_prod_modification->get_modifications_by_online_venta_prod_id($pr['id']);
            $prod_venta_modification_result = "";
            if (count($prod_venta_modification) > 0) {
                $prod_venta_modification_result = "<p><strong>Modificaciones</strong></p><ul>";
                foreach ($prod_venta_modification as $modification) {
                    $prod_venta_modification_result .= "<li>" . $modification['nombre'] . "</li>";
                }
                $prod_venta_modification_result .= "</ul>";
            }

            $productos = $productos . '<tr>
                            <td class="tabp ">' . $pr['descripcion'] . $prod_venta_adition_result . $prod_venta_modification_result . '</td>
                            <td class="tabpr ">$ ' . $pr['precio'] . '</td>
                            <td class="tabcm ">' . $pr['cantidad'] . '</td>
                            <td class="tabcm " >' . $st . '</td>
                            <td class="tabt ">$ ' . $pr['total'] . '</td>
                        </tr>';
        }

        $formaPagoHtml = "";
        $formasPago = $this->formaPago->getActiva();
        foreach ($formasPago as $f) {
            $formaPagoHtml .= "<option value='$f->codigo'>$f->nombre</option>";
        }

        $almacenesHtml = "";
        $almacenes = $this->almacenes->get_almacenes_activos();
        foreach ($almacenes as $almacen) {
            $almacenesHtml .= "<option value='$almacen->id'>$almacen->nombre</option>";
        }

        $data = array(
            'id' => $id,
            'fecha' => $venta['fecha'],
            'productos' => $productos,
            'subtotal' => $venta['sub_total'],
            'impuesto' => $venta['tasa_impuesto'],
            'total' => $venta['sub_total'] + $venta['tasa_impuesto'],
            'descuento' => $descuento,
            'descuento_imp' => $descuento_imp,
            'final' => $venta['sub_total'] + $venta['tasa_impuesto'] - $descuento - $descuento_imp,
            'formasPago' => $formaPagoHtml,
            'almacenes' => $almacenesHtml,
            'estado' => ($venta['venta_id'] == "" || is_null($venta['venta_id']) == 1) ? "0" : $venta['venta_id'],
            'nombre' => $venta['nombre'] . " " . $venta['nombre2'] . " " . $venta['apellidos'],
            'cedula' => $venta['dni'],
            'email' => $venta['email'],
            'telefono' => $venta['telefono'],
            'poblacion' => $venta['poblacion'],
            'notas' => $venta['notas'],
            'movil' => (!is_null($venta['movil'])) ? $venta['movil'] : $venta['telefono'],
            'cpostal' => $venta['cpostal'],
            'direccion' => $venta['direccion'],
        );

        echo json_encode($data);
    }

    public function facturar()
    {
        $id = $_POST['id'];
        $user_id = $this->session->userdata('user_id');
        $data['venta'] = $this->ventas_online->get_by_id($id);
        $data['productos'] = $this->ventas_online->getDetalles($id);
        $data['adicionales'] = $this->ventas_online_prod_adition->getAdicionales($id);
        $data['nombre'] = $this->ventas_online->get_by_id($id);
        $data['cedula'] = $this->ventas_online->get_by_id($id);
        $data['email'] = $this->ventas_online->get_by_id($id);
        $data['telefono'] = $this->ventas_online->get_by_id($id);
        $data['poblacion'] = $this->ventas_online->get_by_id($id);
        $data['notas'] = $this->ventas_online->get_by_id($id);
        $data['movil'] = $this->ventas_online->get_by_id($id);
        $data['pago'] = $_POST['pago'];
        $factura = $this->ventas_online->facturar($data);
        echo json_encode(array("json" => 1, "factura" => $factura));
    }

    public function cambiarAlmacen()
    {
        $id = $_POST['id'];
        $almacen_id = $_POST['almacen_id'];
        $almacen = $this->ventas_online->cambiarAlmacen($id, $almacen_id);
        echo json_encode(array("json" => 1, "almacen" => $almacen));
    }

    public function comandarOrden()
    {
        $id = $_POST['id'];
        $comandarOrden = $this->ventas_online->comandarOrden($id);
        echo json_encode(array($comandarOrden));
    }
}
