<?php

class Proformas_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function get_total() {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  proformas");

        return $query->row()->cantidad;
    }

    public function get_gastos_datos($id = 0) {

        $query = $this->connection->query("SELECT
		 id_proforma,descripcion,cantidad,valor,notas,fecha,id_impuesto,
		 nombre_comercial, nif_cif, nombre, forma_pago
		FROM  proformas  s
	    Inner Join proveedores c On s.id_proveedor = c.id_proveedor
		Inner Join almacen a On s.id_almacen = a.id	
		WHERE id_proforma= '" . $id . "'");

        return $query->row_array();
    }

    public function get_ajax_data($filtro=null) {
        //Se modificó la consulta para que pudiera cargar la tabla de gastos de manera progresiva.
        if(empty($filtro)){
            $filtro=" NOT ";
        }else{
             $filtro="";
        }
        //------------------------------------------------ almacen usuario  
        $user_id = $this->session->userdata('user_id');
        $id_user = '';
        $almacen = '';
        $nombre = '';
        $user = $this->db->query("SELECT id FROM users where id = '" . $user_id . "' limit 1")->result();
        foreach ($user as $dat) {
            $id_user = $dat->id;
        }

        $user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '" . $id_user . "' limit 1")->result();
        foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
            $nombre = $dat->nombre;
        }
        //---------------------------------------------	  
        $is_admin = $this->session->userdata('is_admin');

        $aColumns = array(            
            'g.id_proforma',
            'g.descripcion',
            'p.nombre_comercial',
            'g.fecha',
            'g.valor',
            'g.cantidad',
            'g.banco_asociado',
            'a.nombre'
        );
        //limit
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }
        //order
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i]) ] == "true") {
                    $sOrder.= $aColumns[intval($_GET['iSortCol_' . $i]) ] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        //Search
        $sWhere="";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere.= "AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere.= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere.= ')';
        }

        if ($is_admin == 's') {
            $sql = "SELECT SQL_CALC_FOUND_ROWS descripcion, p.nombre_comercial, fecha, valor, cantidad, banco_asociado, a.nombre AS nombre_almacen, id_proforma  
                FROM proformas AS g 
                INNER JOIN proveedores p ON g.id_proveedor = p.id_proveedor
                INNER JOIN almacen a ON g.id_almacen = a.id
		        where id_almacen = '" . $almacen . "'
                AND (notas $filtro LIKE '%eliminadoIn /%' or '%eliminadoOut /%')
                $sWhere 
                $sOrder
                $sLimit";
        } else {
            $sql = "SELECT SQL_CALC_FOUND_ROWS descripcion, p.nombre_comercial, fecha, valor, cantidad, banco_asociado, a.nombre AS nombre_almacen, id_proforma  
                    FROM proformas AS g 
                    INNER JOIN proveedores p ON g.id_proveedor = p.id_proveedor
                    INNER JOIN almacen a ON g.id_almacen = a.id
                    AND (notas $filtro LIKE '%eliminadoIn /%' or '%eliminadoOut /%')
                    $sWhere 
                    $sOrder
                    $sLimit";
        }      
        $rResult = $this->connection->query($sql);
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        //$sQuery = "SELECT COUNT(id_proforma) as cantidad FROM proformas where (notas $filtro LIKE '%eliminadoIn /%' or '%eliminadoOut /%')";
        $sQuery = "SELECT COUNT(id_proforma) as cantidad FROM proformas where (notas $filtro LIKE '%eliminadoIn /%' or '%eliminadoOut /%')";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad; 
        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            //"iTotalDisplayRecords" => 35,
            "aaData" => array()
        );

        $data = array();
        foreach ($rResult->result() as $value) {
            $banco_asociado = $this->get_banco_asociado($value->banco_asociado);
            if($banco_asociado != 'ninguno'){
                $banco_asociado = $banco_asociado->nombre_cuenta; 
            }

            $data[] = array(
                $value->id_proforma,
                $value->descripcion,
                $value->nombre_comercial,
                $value->fecha,
                $this->opciones_model->formatoMonedaMostrar($value->valor),
                $value->cantidad,
                $banco_asociado,
                $value->nombre_almacen,
                $value->id_proforma
            );
        }
        /*return array(
            'aaData' => $data
        );*/
        $output['aaData'] = $data;
        return $output;
    }

    public function get_banco_asociado($id_banco){
        $banco_asociado = 'ninguno';
        
        $this->connection->select("*");
        $this->connection->from("bancos");
        $this->connection->where("id",$id_banco);
        $this->connection->limit(1);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            $banco = $result->result()[0];
            $banco_asociado = $banco;
        }
        return $banco_asociado;
    }
    /* public function get_total_pendientes()

      {

      $query = $this->connection->query("SELECT count(*) as cantidad FROM proformas where estado = '0'");



      return $query->row()->cantidad;



      }



      public function get_total_pagadas()

      {

      $query = $this->connection->query("SELECT count(*) as cantidad FROM  proformas where estado = 1");

      return $query->row()->cantidad;

      } */



    /* public function get_max_cod()

      {

      $query = $this->connection->query("SELECT MAX(RIGHT(numero,6)) as cantidad FROM  proformas");

      return $query->row()->cantidad;

      } */

    public function get_all($offset) {



        $query = $this->connection->query("SELECT p.*, c.nombre_comercial, i.nombre_impuesto FROM proformas p Inner Join proveedores c 

												On p.id_proveedor = c.id_proveedor

                                                                                                Inner Join impuestos i On i.id_impuesto = p.id_impuesto

												ORDER BY p.id_proforma DESC LIMIT $offset, 8");

        return $query->result();
    }

    /* public function get_all_pendientes($offset)

      {



      $query = $this->connection->query("SELECT * FROM proformas p Inner Join clientes c

      On p.id_cliente = c.id_cliente

      WHERE estado = '0'

      ORDER BY p.id_proforma DESC LIMIT $offset, 8");



      return $query->result();

      } */

    public function get_sum_pendientes() {



        $query = $this->connection->query("SELECT sum(monto) as cantidad FROM proformas WHERE estado = '0'");

        return $query->row()->cantidad;
    }

    public function get_sum_pagadas() {



        $query = $this->connection->query("SELECT sum(monto) as cantidad FROM proformas WHERE estado = '1'");

        return $query->row()->cantidad;
    }

    public function get_by_id($id = 0) {

        $query = $this->connection->query("SELECT p.*, c.nombre_comercial, CONCAT(c.nif_cif, ', ', c.direccion, ', ', c.poblacion, ', ', c.provincia, ', ',c.cp) as otros_datos, a.nombre as almacen_nombre, a.id as id_almacen

						FROM proformas p, proveedores c, almacen a
                        
						WHERE p.id_proveedor = c.id_proveedor and p.id_almacen = a.id
						
						AND p.id_proforma = $id 

						ORDER BY p.id_proforma DESC ");


        
        $gastos = $query->row_array();

        if($gastos["movimiento_asociado"] != ""){
            $this->connection->select("*");
            $this->connection->from("movimientos_bancos mb");
            $this->connection->where("mb.id",$gastos["movimiento_asociado"]);
            $result = $this->connection->get();
            if($result->num_rows() > 0){
                $gastos["movimiento"] = $result->result()[0];
            }

            $this->connection->select("cg.id as id_categoria,cg.nombre as nombre_categoria,sg.id as id_subcategoria,sg.nombre as nombre_subcategoria");
            $this->connection->from("categorias_gastos cg");
            $this->connection->join("subcategorias_gastos sg","sg.id_categoria = cg.id");
            $this->connection->where("sg.id",$gastos["subcategoria_asociada"]);
            $result = $this->connection->get();
            if($result->num_rows() > 0){
                $gastos["detalle_categorias"] = $result->result()[0];
            }
        }

        return $gastos;
    }

    public function get_term() {

        $q = $this->input->post("q");

        $fi = $this->input->post("fi");

        $ff = $this->input->post("ff");

        $t = $this->input->post("t");



        if ($fi != '' && $ff != '')
            $wfecha = " AND p.fecha BETWEEN '" . $fi . "' AND '" . $ff . "'";

        if ($q != '')
            $wcl = " AND c.id_cliente = '" . $q . "'";



        $query = $this->connection->query("SELECT p.id_proforma id, p.numero, c.nombre_comercial, p.monto, 

												DATE_FORMAT(p.fecha , '%d/%m/%Y') fecha

												FROM proformas p, clientes c 

												WHERE p.id_cliente = c.id_cliente 

													" . $wfecha . "

													" . $wcl . "

													AND p.estado = '$t'

												ORDER BY p.id_proforma DESC");



        return $query->result();
    }

    /* public function get_detail($id = 0)

      {

      $query = $this->connection->query("SELECT * FROM proformas_detalles

      WHERE  id_proforma = '".$id."'  ORDER BY id_proforma_detalle ASC");



      return $query->result_array();

      } */

    public function add() {
        
      

        $array_datos = array(
            "id_proforma" => '',
            "descripcion" => $this->input->post('descripcion'),
            "id_proveedor" => $this->input->post('id_proveedor'),
            "valor" => $this->input->post('valor'),
            "cantidad" => $this->input->post('cantidad'),
            "notas" => $this->input->post('notas'),
            "fecha" => $this->input->post('fecha'),
            "id_almacen" => $this->input->post('almacen'),
            "forma_pago" => $this->input->post('forma_pago'),
            "id_cuenta_dinero" => $this->input->post('cuentas_dinero'),
            "fecha_crea_gasto" => date('Y-m-d H:i:s')
        );



        $id_impuesto = $this->input->post('id_impuesto');

        if (!empty($id_impuesto)) {

            $array_datos["id_impuesto"] = $id_impuesto;
        }

        if($this->input->post('cuentas_dinero') == 2 && $this->input->post('banco_asociado') != "" && $this->input->post('subcategoria_gasto_asociada') != "" ){
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

            $array_datos["banco_asociado"] = $this->input->post('banco_asociado');
            $array_datos["subcategoria_asociada"] = $this->input->post('subcategoria_gasto_asociada');
            $array_datos["movimiento_asociado"] = $id_movimiento;
        }

        
        $this->connection->insert("proformas", $array_datos);
        $id = $this->connection->insert_id();

        //guardar evento de primeros pasos gasto
        $estadoBD = $this->newAcountModel->getUsuarioEstado();                    
        if(($estadoBD["estado"]==2)&&(!empty($id))){
            $paso=15;
            $marcada=$this->primeros_pasos_model->verificar_tareas_realizadas(array('id_usuario' => $this->session->userdata('user_id'),'db_config' => $this->session->userdata('db_config_id'),'id_paso'=>$paso));
            if($marcada==0){
                    $datatarea=array(
                    'id_paso' => $paso,
                    'id_usuario' => $this->session->userdata('user_id'),
                    'db_config' => $this->session->userdata('db_config_id')
            );
            $this->primeros_pasos_model->insertar_tareas_realizadas($datatarea);
            }                               
        }

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

        $array_datos["id_proforma"] = $this->connection->insert_id();

        return $array_datos;
    }

    public function update() {

        $response = array();
        $array_datos = array(
            "id_proveedor" => $this->input->post('id_proveedor'),
            "descripcion" => $this->input->post('descripcion'),
            "valor" => $this->input->post('valor'),
            "cantidad" => $this->input->post('cantidad'),
            "notas" => $this->input->post('notas'),
            "fecha" => $this->input->post('fecha'),
            "id_impuesto" => $this->input->post('id_impuesto'),
            "id_almacen" => $this->input->post('almacen'),
            "forma_pago" => $this->input->post('forma_pago'),
            "id_cuenta_dinero" => $this->input->post('cuentas_dinero'),
        );

       
        if($this->input->post('cuentas_dinero') == 2 && $this->input->post('banco_asociado') != "" && $this->input->post('subcategoria_gasto_asociada') != "" ){
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

            $array_datos["banco_asociado"] = $this->input->post('banco_asociado');
            $array_datos["subcategoria_asociada"] = $this->input->post('subcategoria_gasto_asociada');
            $array_datos["movimiento_asociado"] = $id_movimiento;
        }

        /*if($this->input->post('banco_asociado') != "" && $this->input->post('subcategoria_gasto_asociada') != "" ){
            $this->connection->select("movimiento_asociado");
            $this->connection->from("proformas");
            $this->connection->where('id_proforma', $this->input->post('id_proforma'));
            $this->connection->limit(1);
            $result = $this->connection->get();

            $movimiento = $result->result()[0];

            if($movimiento->movimiento_asociado != NULL){
                $data = array(
                    'observacion' => $this->input->post('notas'),
                    'nota_impresion' => $this->input->post('notas'),
                    'valor' => $this->input->post('valor'),
                    'id_banco' => $this->input->post('banco_asociado'),
                    'referencia' => ''
                );
                
                $this->connection->where('id', $movimiento->movimiento_asociado);
                $this->connection->where('estado is NULL', null, false);
                $this->connection->update("movimientos_bancos",$data);
               
                if($this->connection->affected_rows() > 0){
                    $response = 'Se ha modificado correctamente el movimiento asociado al gasto';
                }else{
                    $response = 'Ocurrio un error al momento de actualizar el movimiento asociado al gasto, verifique que el movimiento no este conciliado';
                }
            }else{
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
    
                $array_datos["movimiento_asociado"] = $id_movimiento;
            }
            $array_datos["banco_asociado"] = $this->input->post('banco_asociado');
            $array_datos["subcategoria_asociada"] = $this->input->post('subcategoria_gasto_asociada');
        }else{
            $array_datos["banco_asociado"] = NULL;
            $array_datos["subcategoria_asociada"] = NULL;
            $array_datos["movimiento_asociado"] = NULL;
            $response = 'Se ha desvinculado el movimiento asociado al gasto, por favor eliminarlo desde movimientos';
        }*/

        $this->connection->where('id_proforma', $this->input->post('id_proforma'));
        $this->connection->update("proformas", $array_datos);

        return $response; 
    }

    public function delete($id) {
        $data = array();
        $response = 'success';
       
        $this->connection->select("mb.*,p.banco_asociado");
        $this->connection->from("proformas p");
        $this->connection->join("movimientos_bancos mb","mb.id = p.movimiento_asociado");
        $this->connection->where("id_proforma",$id);
        $this->connection->limit(1);

        $result = $this->connection->get();
        if($result->num_rows() > 0){
           
            $id_movimiento = $result->result()[0]->id;
            
            if($result->result()[0]->estado == 'conciliado' || $result->result()[0]->id_conciliacion != NULL){
                $response =  "movimiento_conciliado";
            }else{
                $preconciliado = false;
                /* Buscamos movimiento en conciliaciones pendientes*/
                $this->connection->select("*");
                $this->connection->from("conciliaciones_pendientes cp");
                $this->connection->where("id_banco",$result->result()[0]->banco_asociado);
                $this->connection->limit(1);
                $result_mov = $this->connection->get();
    
                if($result_mov->num_rows() > 0){
                    $movimientos = json_decode($result_mov->result()[0]->movimientos); 
                    for($i=0;$i<count($movimientos);$i++){
                        if($movimientos[$i] == $id_movimiento ){
                            $response = "movimiento_pre_conciliado";
                            $preconciliado = true;
                        }
                    }
                }

                if(!$preconciliado){
                    $this->connection->where('id',$id_movimiento);
                    $this->connection->delete('movimientos_bancos');
                    $response = "movimiento_eliminado";
                    $data["banco_asociado"] = NULL;
                    $data["subcategoria_asociada"] = NULL;
                    $data["movimiento_asociado"] = NULL; 
                }
            }
        }

        if($response == 'success' || $response == 'movimiento_eliminado') {
            $nota = "";
        
            if ($this->session->userdata('caja') != "") {
                $nota = "eliminadoIn / " . date("Y-m-d H:i:s");
            } else {
                $nota = "eliminadoOut / " . date("Y-m-d H:i:s");
            }
    
            $data['notas'] = $nota;
    
            $this->connection->where('id_proforma', $id);
            $this->connection->update("proformas", $data);
        }
        
        return $response;
    }

    public function excel() {

        $sql = " SELECT proformas.*, impuesto.*, proveedores.*, a.nombre as almacen  from proformas
		inner Join proveedores On proformas.id_proveedor = proveedores.id_proveedor
		inner Join impuesto On proformas.id_impuesto = impuesto.id_impuesto
                 inner join almacen a on a.id=proformas.id_almacen
        where proformas.notas NOT LIKE '%eliminado%'";
        //   die(var_dump($sql));
        //echo $sql;die;
        /*
          $this->connection->select("proformas.*, impuestos.*, proveedores.*");

          $this->connection->from("proformas");

          $this->connection->join("impuestos", "proformas.id_impuesto = impuestos.id_impuesto");

          $this->connection->join("proveedores", "proveedores.id_proveedor = proformas.id_proveedor");

          $query = $this->connection->get();
         */
        return $this->connection->query($sql)->result();
    }

    public function excel_exist($nombre_comercial, $email, $nif_cif) {



        $this->connection->where("nombre_comercial", $nombre_comercial);

        $this->connection->where("nif_cif", $nif_cif);

        $this->connection->where("email", $email);

        $this->connection->from("clientes");

        $this->connection->select("*");

        $flag = false;

        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            $flag = true;
        }

        $query->free_result();

        return $flag;
    }

    public function excel_add($array_datos) {

        $this->connection->insert("proformas", $array_datos);
    }

    public function actualizar_movimiento_cierre($data,$where){
        $this->connection->where($where);
        $this->connection->update('movimientos_cierre_caja',$data);
    }

    //ACTUALIZACION INCIDENCIA 813 FECHA Y HORA CREACION GASTO
    public function actualizar_proforma_gastos(){
        $sql = "SHOW COLUMNS FROM proformas LIKE 'fecha_crea_gasto'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            //creamos el campo
            $sql="ALTER TABLE proformas ADD COLUMN fecha_crea_gasto TIMESTAMP NULL DEFAULT  '0000-00-00 00:00:00' COMMENT 'Fecha de creacion del gasto almacena fecha y hora'";
            $this->connection->query($sql);   
        }
    }

    public function get_proformas_where($where, $almacen = null){
        $this->connection->where($where);
        if($almacen != null){
            if($almacen != 0 || $almacen != "0"){
                $this->connection->where('id_almacen', $almacen);
            }
        }
        $this->connection->not_like('notas','eliminadoIn');
        $query = $this->connection->get('proformas');
        return $query->result();
    }

    public function get_categorias(){
        $this->connection->select("*");
        $this->connection->from("categorias_gastos");
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return NULL;
        }
    }

    public function cargar_subcategorias($id_categoria){
        $this->connection->select("*");
        $this->connection->from("subcategorias_gastos sg");
        $this->connection->where("sg.id_categoria",$id_categoria);
        $this->connection->order_by("sg.nombre ASC");
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return NULL;
        }
    }

}

?>