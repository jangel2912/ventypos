<?php

class Pagos_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function get_total($id_factura) {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM pago where id_factura = $id_factura");

        return $query->row()->cantidad;
    }

    public function get_total_orden_compra($id_factura) {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM pago_orden_compra where id_factura = $id_factura");

        return $query->row()->cantidad;
    }

    public function get_all($id_factura, $offset) {



        $query = $this->connection->query("SELECT * from pago where id_factura = $id_factura order by id_pago DESC");

        return $query->result();
    }

    public function get_all_orden_compra($id_factura, $offset) {
        $query = $this->connection->query("SELECT *,f.nombre AS tipo from pago_orden_compra AS p INNER JOIN forma_pago AS f ON f.codigo = p.tipo where id_factura = $id_factura order by id_pago DESC");

        return $query->result();
    }

    public function get_tipos_pago() {

        $this->db->select('valor_opcion, mostrar_opcion');

        $query = $this->db->get_where('opciones', array('nombre_opcion' => 'tipo_pago'));

        $result = array();

        foreach ($query->result() as $value) {

            $result[$value->valor_opcion] = $value->mostrar_opcion;
        }

        return $result;
    }

    public function get_tipos_pagoOffline() {
        //solo efectivo
        $result['efectivo'] = 'Efectivo';
        return $result;
    }

    public function add() {

        $notas = '';
      
        if($this->input->post('tipo') == 'nota_credito'){
            
            $this->load->model('ventas_model');
            $this->ventas_model->initialize($this->connection);
            $datos_venta = $this->ventas_model->get_by_id($this->input->post('id_factura'));
            
            $notas = 'Nota Credito #'.$this->input->post('valor_entregado_nota_credito');
            $this->load->model('nota_credito_model');
            $this->nota_credito_model->initialize($this->connection);
            $this->nota_credito_model->cancelarNotaCredito(array($this->input->post('valor_entregado_nota_credito')));
            
            $codNotaCredito = $this->input->post('valor_entregado_nota_credito');
            $notaCredito = $this->connection->get_where("notacredito",array("consecutivo"=>$codNotaCredito))->row();

            $this->connection
                  ->where("notaForeign_id",$notaCredito->id)
                  ->update("notacredito",array(
                  "factura_id" => $this->input->post('id_factura'),
                  "cliente_id" => $datos_venta['cliente_id']
                    ));
        }

        $array_datos = array(
            "fecha_pago" => $this->input->post('fecha_pago'),
            "notas" => $notas,
            "tipo" => $this->input->post('tipo'),
            "cantidad" => $this->input->post('cantidad'),
            "importe_retencion" => $this->input->post('importe_retencion'),
            "id_factura" => $this->input->post('id_factura'),
        );

        $this->connection->insert("pago", $array_datos);

        $id = $this->connection->insert_id();

        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
        $ocpresult = $this->connection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
            $valor_caja = $dat->valor_opcion;
        }
        
        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
            if(!empty($id)){    
                $username = $this->session->userdata('username');
                $db_config_id = $this->session->userdata('db_config_id');
                $id_user= $this->session->userdata('user_id');

                $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                    "hora_movimiento" => date('H:i:s'),
                    "id_usuario" => $id_user,
                    "tipo_movimiento" => 'entrada_venta',
                    "valor" => ($this->input->post('cantidad')),
                    "forma_pago" => $this->input->post('tipo'),
                    "numero" => '',
                    "id_mov_tip" => $id,
                    "tabla_mov" => "pago"
                );

                $this->connection->insert('movimientos_cierre_caja', $array_datos);
            }
        }
       
    }

    public function add_orden_pago() {

        $bancos = $this->connection->query("SELECT * FROM forma_pago where codigo = 'bancos'")->result();

        if(!$bancos){
            $banco = array(
                "codigo" => "bancos",
                "nombre" => "Bancos",
                "activo" => "1",
                "eliminar" => "1",
            );
            $this->connection->insert("forma_pago", $banco);
        }

        $array_datos = array(
            "fecha_pago" => $this->input->post('fecha_pago'),
            "notas" => '',
            "tipo" => $this->input->post('tipo'),
            "cantidad" => $this->input->post('cantidad'),
            "importe_retencion" => 0,
            "id_factura" => $this->input->post('id_factura'),
        );

        $this->connection->insert("pago_orden_compra", $array_datos);

        $id = $this->connection->insert_id();

        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
        $ocpresult = $this->connection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
            $valor_caja = $dat->valor_opcion;
        }

        if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
            if(!empty($id)){
                $username = $this->session->userdata('username');
                $db_config_id = $this->session->userdata('db_config_id');
                $id_user= $this->session->userdata('user_id');
                
                $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                    "hora_movimiento" => date('H:i:s'),
                    "id_usuario" => $id_user,
                    "tipo_movimiento" => 'salida_gastos',
                    "valor" => ($this->input->post('cantidad')),
                    "forma_pago" => $this->input->post('tipo'),
                    "numero" => '',
                    "id_mov_tip" => $id,
                    "tabla_mov" => "pago_orden_compra"
                );
                $this->connection->insert('movimientos_cierre_caja', $array_datos);
            }
        }        
    }

    public function update() {

        $array_datos = array(
            "fecha_pago" => $this->input->post('fecha_pago'),
            "notas" => $this->input->post('notas'),
            "tipo" => $this->input->post('tipo'),
            "cantidad" => $this->input->post('cantidad'),
            "importe_retencion" => $this->input->post('importe_retencion'),
            "id_factura" => $this->input->post('id_factura'),
        );



        $this->connection->where('id_pago', $_POST['id_pago']);

        $this->connection->update("pago", $array_datos);
    }

    public function delete($id) {

        $this->connection->where('id_pago', $id);

        $this->connection->delete("pago");


        $query = "DELETE FROM movimientos_cierre_caja WHERE id_mov_tip = '" . $id . "' and tabla_mov = 'pago'  ";
        $this->connection->query($query);
    }

    public function delete_orden($id) {

        $this->connection->where('id_pago', $id);

        $this->connection->delete("pago_orden_compra");


        $query = "DELETE FROM movimientos_cierre_caja WHERE id_mov_tip = '" . $id . "' and tabla_mov = 'pago_orden_compra'  ";
        $this->connection->query($query);
    }

    public function get_by_id($id = 0) {

        $query = $this->connection->query("SELECT * FROM  pago WHERE id_pago = '" . $id . "'");
        return $query->row_array();
    }

    public function get_pagos_compra($where){
        $this->connection->where($where);
        $query = $this->connection->get('pago_orden_compra');
        return $query->result();
    }

    public function get_ajax_data(){
        $id_db_config = $this->session->userdata('db_config_id');
        $sql_crm_licencias = "SELECT l.* FROM v_crm_licencias l WHERE id_db_config = $id_db_config";
        $data = array();

        foreach ($this->db->query($sql_crm_licencias)->result() as $value) {
            $sql = "SELECT id,nombre,direccion,telefono FROM almacen WHERE id= $value->id_almacen"; 
            $almacen = $this->connection->query($sql)->result();
            $hoy=date("Y-m-d");            
            $estado=$value->fecha_vencimiento>$hoy ? "Activa":"Inactiva";           
            
            $data[] = array(
                $almacen[0]->nombre,
                //$almacen[0]->direccion,
                $almacen[0]->telefono,
                $value->nombre_plan,
                $value->fecha_inicio_licencia,
                $value->fecha_vencimiento,
                //$value->descripcion,
                $estado,
                $value->valor_plan,
                $value->id_licencia
            );
        }
        return array(
            'aaData' => $data
        );
    }
    
    public function generarGasto(){

        //Se genera un array para la inserción gasto
       $array_datos = array(
           "id_proforma" => '',
           "descripcion" => $this->input->post('descripcion'),
           "id_proveedor" => $this->input->post('id_proveedor'),
           "valor" => $this->input->post('valor'),
           "cantidad" => $this->input->post('cantidad'),
           "notas" => $this->input->post('notas'),
           "fecha" => $this->input->post('fecha'),
           "id_almacen" => $this->input->post('id_almacen'),
           "forma_pago" => $this->input->post('tipo'),
           "id_cuenta_dinero" => $this->input->post('cuentas_dinero'),
           "fecha_crea_gasto" => date('Y-m-d H:i:s')
       );
        //Se valida si el gasto es por "Banco"
        if($this->input->post('cuentas_dinero') == 2 && $this->input->post('banco_asociado') != "" && $this->input->post('subcategoria_gasto') != "" ){
           //Si es por banco se genera el movimiento en los bancos
           $data = array(
               'observacion' => $this->input->post('notas'),
               'nota_impresion' => $this->input->post('notas'),
               'valor' => $this->input->post('valor'),
               'fecha_creacion' => date('Y-m-d'),
               'id_banco' => $this->input->post('banco_asociado'),
               'id_tipo' => 1,
               'id_usuario_creacion' => $this->session->userdata('user_id'),
               'referencia' => ''
           );
   
           $this->connection->insert("movimientos_bancos",$data);
           $id_movimiento = $this->connection->insert_id();
            //Se agrega los datos
           $array_datos["banco_asociado"] = $this->input->post('banco_asociado');
           $array_datos["subcategoria_asociada"] = $this->input->post('subcategoria_gasto');
           $array_datos["movimiento_asociado"] = $id_movimiento;
       }
        //Se guarda en proformas como si fuera un gasto
       /*$this->connection->insert("proformas", $array_datos);
       $id = $this->connection->insert_id();
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'valor_caja' ";
       $ocpresult = $this->connection->query($ocp)->result();
       foreach ($ocpresult as $dat) {
           $valor_caja = $dat->valor_opcion;
       }
        //Se genera el movimiento de cierre
       if ($valor_caja == 'si' && $this->session->userdata('caja') > '0') {
           if(!empty($id)){
               $id_user= $this->session->userdata('user_id');
           
               $forma_pago = utf8_decode($this->input->post('forma_pago'));
               if (utf8_decode($this->input->post('forma_pago')) == 'Efectivo') {
                   $forma_pago = 'Efectivo';
               }
               if (utf8_decode($this->input->post('forma_pago')) == 'Tarjeta de cr�dito') {
                   $forma_pago = 'tarjeta_credito';
               }
               if (utf8_decode($this->input->post('forma_pago')) == 'Tarjeta debito') {
                   $forma_pago = 'tarjeta_debito';
               }
               if (utf8_decode($this->input->post('forma_pago')) == 'Cr�dito') {
                   $forma_pago = 'Credito';
               }
                $array_datos = array("Id_cierre" => $this->session->userdata('caja'),
                   "hora_movimiento" => date('H:i:s'),
                   "id_usuario" => $id_user,
                   "tipo_movimiento" => 'salida_gastos',
                   "valor" => $this->input->post('valor'),
                   "forma_pago" => $forma_pago,
                   "id_mov_tip" => $id,
                   "tabla_mov" => "proformas"
               );
               $this->connection->insert('movimientos_cierre_caja', $array_datos);
           }
       }
        $array_datos["id_proforma"] = $this->connection->insert_id();*/
        return $array_datos;
   }

}

?>