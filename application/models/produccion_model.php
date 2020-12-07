<?php

class Produccion_model extends CI_Model {

    var $connection;

    // Constructor
    public function __construct() {
        parent::__construct();
        //$this->load->model('opciones_model','opciones');	
        $this->load->model('productos_model','productos');

    }

    public function initialize($connection) {
        $this->connection = $connection;
        $this->transaction_table();
    }

    public function get_total() {
        $query = $this->connection->query("SELECT count(*) as cantidad FROM  presupuestos");
        return $query->row()->cantidad;
    }
    
    public function get_data( $produccion_id ){
        $query  = $this->connection->query("SELECT id,
                                                   consecutivo,
                                                   fecha,
                                                   usuario_id,
                                                   almacen_id,
                                                   IF(estado = 0, 'Eliminado', IF(estado = 1, 'Creado', IF(estado = 2, 'Confirmado', 'Trasladado')) ) AS estado,
                                                   fecha_creacion
                                              FROM produccion WHERE id = '{$produccion_id}' ");
        return (array)$query->row();
    }
    
    public function get_data_detail( $produccion_id ){
        $data = array();
        $query = $this->connection->query("SELECT b.nombre AS producto,
                                                  c.nombre AS producto_final,
                                                  a.cantidad,
                                                  a.id AS produccion_detalle_id
                                             FROM produccion_detalle a INNER JOIN 
                                                  producto b ON a.producto_id = b.id INNER JOIN
                                                  producto c ON a.producto_final_id = c.id
                                            WHERE produccion_id = '{$produccion_id}' ");
        $result = $query->result();
        foreach ( $result as $row ):
            $data[] = (array)$row;
        endforeach;
        return $data;
    }
    
    public function get_consecutivo(){
        $query = $this->connection->query("SELECT MAX( consecutivo ) AS consecutivo FROM produccion");
        return (int)$query->row()->consecutivo + 1;
    }

    public function get_ajax_data( $data_array ) {
        
        $filtro_fecha = "";
        if ( !empty($data_array['fecha_inicio']) && !empty($data_array['fecha_fin']) ) {
            $filtro_fecha = " and date(a.fecha) >= '{$data_array['fecha_inicio']}' and date(a.fecha) <=  '{$data_array['fecha_fin']}'";
        } elseif(!empty($data_array['fecha_inicio']) && empty($data_array['fecha_fin'])){
            $filtro_fecha = " and date(a.fecha) >= '{$data_array['fecha_inicio']}'";
        } elseif(!empty($data_array['fecha_fin'])  && empty($data_array['fecha_inicio'])){
            $filtro_fecha = " and date(a.fecha) <= '{$data_array['fecha_fin']}'";
        }
        
        
        $aColumns = array('a.consecutivo', 'a.usuario_id', 'c.nombre', 'a.fecha', 'a.estado',  'a.id');
        $sIndexColumn = "a.id";
        $sTable = "produccion";
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }
        
        if(!empty($filtro_fecha)):
            if(preg_match('/^WHERE/', $sWhere)):
                $sWhere .= $filtro_fecha;
            else:
                $sWhere .= " WHERE 1=1 " . $filtro_fecha;
            endif;
        endif;

        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
        FROM   $sTable AS a                     
        INNER JOIN almacen AS c ON  c.id = a.almacen_id
		$sWhere  
		$sOrder
		$sLimit
            ";

        $db=$this->session->userdata('db_config_id');
        $users="SELECT id, username FROM users WHERE db_config_id=$db";
        $users = $this->db->query($users)->result_array();        
        $rResult = $this->connection->query($sQuery);        
        /* Data set length after filtering */
        $sQuery = "
                    SELECT FOUND_ROWS() as cantidad
            ";
        $rResultFilterTotal = $this->connection->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ") as  cantidad
		FROM   $sTable AS a ";
        
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        $this->load->model("dashboard_model", 'dashboardModel');
        $this->dashboardModel->initialize($this->dbConnection);
        $this->load->model("almacenes_model", 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        $almacenActual = $this->dashboardModel->getAlmacenActuallicencias();
        if(empty($almacenActual)){
            $almacenActual = $this->dashboardModel->getAlmacenActual();
        }
        $puedofacturar=0;
        $puedofacturar = $this->almacenes->get_Bodega($almacenActual);
        if (($puedofacturar == 1) && ($this->session->userdata('db_config_id') != 2547)) {
           $puedofacturar=1;
        }

        $aColumns = array('consecutivo', 'username', 'nombre', 'fecha', 'estado',  'id','bodega');
             
        foreach ($rResult->result_array() as $row) {            
            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == 'username') {
                    foreach ($users as $value) {                        
                        if($row['usuario_id']==$value['id']){
                            $data[]= $value['username'];
                        }                            
                    }
                        
                }else{
                    if ($aColumns[$i] == 'bodega') {
                        $data[] = $puedofacturar;
                    }else{
                        if ($aColumns[$i] == 'estado') {
                            switch ((int)$row[$aColumns[$i]]){
                                case 0:
                                    $data[] = 'Eliminado';
                                    break;
                                case 1:
                                    $data[] = 'Creado';
                                    break;
                                case 2:
                                    $data[] = 'Confirmado';
                                    break;
                                case 3:
                                    $data[] = 'Trasladado';
                                    break;
                            }
                        } else {
                            $data[] = $row[$aColumns[$i]];
                        }
                    }
                }
            }
            $output['aaData'][] = $data;
        }
      
        return $output;
    }
    
    
    public function confirm_produccion($connection, $data_array){
        $connection->db_debug = TRUE;
        $i=0;
        $produccion_id = $data_array['produccion_id'];
        $almacen_id =  $data_array['almacen_id'];
        $produccion_detalle_id = $data_array['produccion_detalle_id'];
        $cantidad = $data_array['cantidad'];
        while (!empty($produccion_detalle_id[$i])):
            $connection->where('produccion_id', $produccion_id);
            $connection->where('id', $produccion_detalle_id[$i]);
            $connection->update('produccion_detalle', array('cantidad'=>$cantidad[$i]));
            
            $query  = $connection->query("SELECT * FROM produccion_detalle WHERE id = '{$produccion_detalle_id[$i]}'");
            $result = (array)$query->row();
            
            $cantidad1    = (double)$result['cantidad'];
            $producto_id = $result['producto_id'];
            
            $connection->where('id', $producto_id);
            $connection->where('ingredientes', 1);
            $connection->update('producto', array('activo' => '0'));
            
            //$query  = $connection->query("SELECT * FROM producto_ingredientes WHERE id_producto = '{$producto_id}'");
            /*
            foreach ( $query->result() as $row):
                
                $cantidad_ingrediente = $cantidad1 * (double)$row->cantidad;
                $connection->query("UPDATE stock_actual SET unidades = (unidades - {$cantidad_ingrediente}) WHERE almacen_id = '{$almacen_id}' AND producto_id = '{$row->id_ingrediente}' ");
            
            endforeach;*/
            $i++;
            
        endwhile;
        
        $connection->where('id', $produccion_id);
        $connection->update('produccion', array('estado'=>2)); #Confirmaciòn de Producciòn
        
        return array('cod_status' => $connection->_error_number(), 'omsg_status' => $connection->_error_message());
    }
    
    public function update_stock( $connection, $data_array ){
        $connection->db_debug = TRUE;
        
        $i=0;
        $produccion_id = $data_array['produccion_id'];
        $almacen_id =  $data_array['almacen_id'];
        $produccion_detalle_id = $data_array['produccion_detalle_id'];
        $produccion_detalle_cantidades = $data_array['cantidad'];
        $almacen_traslado_id = $data_array['almacen_traslado_id'];
        //actualizar  las cantidades a trasladar
        foreach ($produccion_detalle_id as $key => $value) {
            $query  = $connection->query("SELECT * FROM produccion_detalle WHERE id = $value");
            $data_produccion_cantidad = $query->row();
            $cantidad_producir=$produccion_detalle_cantidades[$key];
            $datos=array(
                'cantidad' =>$cantidad_producir
            );
            $this->connection->where(array('id'=>$value));
            $this->connection->update('produccion_detalle',$datos);
        }

        $id_movimiento=$this->insertar_movimiento($produccion_detalle_id, $almacen_id, $almacen_traslado_id);

        foreach ($produccion_detalle_id as $key => $value) {
            $query  = $connection->query("SELECT * FROM produccion_detalle WHERE id = $value");
            $data_produccion = $query->row();
            $this->actualizar_stock_producto($data_produccion,$almacen_traslado_id);  //actualiza el stock_actual producto final     
            $this->actualizar_stock_diario_producto($data_produccion,$almacen_id,$almacen_traslado_id, $produccion_id, $id_movimiento);
            $this->actualizar_precio_producto_produccion($data_produccion,$almacen_traslado_id);           
        }
        
        
        $connection->where('id', $produccion_id);
        $connection->update('produccion', array('estado'=>3)); #Confirmaciòn de Producciòn
        
        
        return array('cod_status' => $connection->_error_number(), 'omsg_status' => $connection->_error_message());
    }

    public function actualizar_precio_producto_produccion($data_produccion,$almacen_traslado_id){
        $producccion_id = $data_produccion->producto_id;
        $this->load->model('productos_model','productos');
        $this->productos->initialize($this->connection);
        $datos_producto_produccion = $this->productos->get_by_id($producccion_id);
        $precio_compra_produccion = $datos_producto_produccion['precio_compra'];
        $this->actualizar_precio_compra_producto($data_produccion->producto_final_id,$precio_compra_produccion,$almacen_traslado_id);
    }

    public function actualizar_precio_compra_producto($id_producto,$precio_compra,$almacen=-1){
        $this->load->model('opciones_model','opciones');
        $this->opciones->initialize($this->connection);
        $data_update = array('precio_compra'=>$precio_compra);

        if( $this->opciones->getOpcion('precio_almacen') == 1 ){
            if($almacen = -1){
                $where = array('producto_id'=>$id_producto);
            }else{
                $where = array('producto_id'=>$id_producto,'almacen_id'=>$almacen);    
            }
            
            $this->connection->where($where);
            $this->connection->update('stock_actual',$data_update);
        }else{
            $where = array('id'=>$id_producto);
            $this->connection->where($where);
            $this->connection->update('producto',$data_update);
        }

    }

    public function actualizar_stock_producto($data_produccion,$almacen_traslado_id){
        
        //var_dump($data_produccion);
        $producto_almacen = $this->connection->query("SELECT * FROM stock_actual where almacen_id ='{$almacen_traslado_id}' AND producto_id = '{$data_produccion->producto_final_id}' ")->row();
        if($producto_almacen){
            $nuevo_stock = $producto_almacen->unidades + $data_produccion->cantidad;
            $this->connection->where(array('almacen_id'=>$almacen_traslado_id,'producto_id'=>$data_produccion->producto_final_id));
            $this->connection->update('stock_actual',array('unidades'=>$nuevo_stock));
            
        }else{
            $data_insert = array('unidades'=>$data_produccion->cantidad,
                                 'almacen_id'=>$almacen_traslado_id,
                                 'producto_id'=>$data_produccion->producto_final_id
                                );          
            $this->connection->insert('stock_actual',$data);
        }
    }

    public function actualizar_stock_diario_producto($data_produccion,$almacen_id,$almacen_traslado_id, $produccion_id, $id_movimiento){        
        $producccion_id = $data_produccion->producto_id;
        $this->load->model('productos_model','productos');
        $this->productos->initialize($this->connection);
        $datos_producto_produccion = $this->productos->get_by_id($producccion_id);
        $precio_compra_produccion = $datos_producto_produccion['precio_compra'];
        $user_id = $this->session->userdata('user_id');
        $this->connection->insert('stock_diario', array('producto_id' => $data_produccion->producto_final_id, 'almacen_id' =>$almacen_traslado_id, 'fecha' => date('Y-m-d'), 'unidad' => $data_produccion->cantidad, 'precio' => $precio_compra_produccion, 'cod_documento' => $id_movimiento, 'usuario' => $user_id, 'razon' => 'EP'));
       //restar los ingredientes 
       //busco los ingredientes del compuesto y descuento en stock_diario y en stock_actual
        $producto_ingredientes  = $this->connection->query("SELECT * FROM producto_ingredientes WHERE id_producto = $data_produccion->producto_id")->result();
        
        foreach ($producto_ingredientes as $key => $value) {    
            $datos_producto_ingrediente = $this->productos->get_by_id($value->id_ingrediente);
            $precio_compra_ingrediente = $datos_producto_ingrediente['precio_compra'];
            $unidades_completas=$value->cantidad * $data_produccion->cantidad;
            $this->connection->insert('stock_diario', array('producto_id' => $value->id_ingrediente, 'almacen_id' =>$almacen_id, 'fecha' => date('Y-m-d'), 'unidad' => '-'.$unidades_completas, 'precio' => $precio_compra_ingrediente, 'cod_documento' => $id_movimiento, 'usuario' => $user_id, 'razon' => 'SP'));           
            $producto_almacen2 = $this->connection->query("SELECT * FROM stock_actual where almacen_id =$almacen_id AND producto_id =$value->id_ingrediente")->row();
            if($producto_almacen2){
                $quedan=$producto_almacen2->unidades - $unidades_completas;
                $this->connection->where(array('almacen_id'=>$almacen_id,'producto_id'=>$value->id_ingrediente));
                $this->connection->update('stock_actual',array('unidades'=>$quedan));
            }
        }
    }

    public function insertar_movimiento($produccion_detalle_id,$almacen_id,$almacen_traslado_id){
        $this->chequeo_columna_id_producto();
        $this->productos->initialize($this->connection);
        #-------------------------------------------------------------------
            #Registro de movimiento de inventario.
            #-------------------------------------------------------------------
            $this->connection->insert('movimiento_inventario', array(
                'fecha'               => date('Y-m-d H:i:s'),
                'almacen_id'          => $almacen_id,
                'almacen_traslado_id' => $almacen_traslado_id,
                'tipo_movimiento'     => 'traslado_produccion',
                'codigo_factura'      => NULL,
                'user_id'             => $this->session->userdata('user_id'),
               // 'total_inventario'    => $data_produccion->cantidad,
                'proveedor_id'        => NULL,
                'nota'                => NULL,
                //'producto_id'         => $data_produccion->producto_final_id
            ));
        $id_movimiento = $this->connection->insert_id();
        $total_inventario = 0;
        foreach ($produccion_detalle_id as $key => $value) {
            $query  = $this->connection->query("SELECT * FROM produccion_detalle WHERE id = $value");
            $data_produccion = $query->row();
            ///var_dump($data_produccion);
            $data_producto = $this->productos->get_by_id($data_produccion->producto_final_id);
            $data_producto_producccion = $this->productos->get_by_id($data_produccion->producto_id);
            $total_inventario_item = ( $data_produccion->cantidad * $data_producto_producccion['precio_compra']);
            $total_inventario+=$total_inventario_item;
            $data_insertar = array( 'id_inventario'=>$id_movimiento,
                                    'codigo_barra' => empty($data_producto['codigo_barra']) ? $data_producto['codigo'] :$data_producto['codigo_barra'] ,
                                    'cantidad' => $data_produccion->cantidad,
                                    'precio_compra' => $data_producto_producccion['precio_compra'],
                                    'existencias' => $data_produccion->cantidad, 
                                    'nombre'      => $data_producto['nombre'],
                                    'total_inventario'=> $total_inventario_item,
                                    'producto_id'  => $data_produccion->producto_final_id
                 );
             $this->connection->insert('movimiento_detalle',$data_insertar);   
            } 
        $this->connection->where('id',$id_movimiento);
        $this->connection->update('movimiento_inventario',array('total_inventario'=>$total_inventario));
        return $id_movimiento;      
    }

    public function transaction_table(){

        $this->connection->query("CREATE TABLE IF NOT EXISTS produccion (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  consecutivo int(11) DEFAULT NULL,
                                  fecha date DEFAULT NULL,
                                  usuario_id int(11) DEFAULT NULL,
                                  almacen_id int(11) DEFAULT NULL,
                                  estado int(11) DEFAULT NULL COMMENT '0=eliminado, 1=creado,2=confirmado,3=transladado',
                                  fecha_creacion datetime DEFAULT NULL,
                                  PRIMARY KEY (id)
                                ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;");

        $this->connection->query("CREATE TABLE IF NOT EXISTS produccion_detalle (
                                  id int(11) NOT NULL AUTO_INCREMENT,
                                  produccion_id int(11) DEFAULT NULL,
                                  producto_id int(11) DEFAULT NULL COMMENT 'Producto compuesto en produccion',
                                  producto_final_id int(11) DEFAULT NULL COMMENT 'Producto final',
                                  cantidad int(11) DEFAULT NULL,
                                  PRIMARY KEY (id)
                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

    }

    public function chequeo_columna_id_producto(){
        $sql = "SHOW COLUMNS FROM movimiento_inventario LIKE 'producto_id'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {// Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `movimiento_inventario` 
                ADD COLUMN `producto_id` int NULL  AFTER `nota`;
            ";
            $this->connection->query($sql);
        }
    }

    public function findIdProd( $id )
    {
        /** obtenemos los datos del almacen, la fecha de la creacion y su consecutivo */
        $this->connection->select('almacen.nombre, produccion.fecha, produccion.consecutivo')
                        ->join('almacen','produccion.almacen_id = almacen.id')
                        ->where('produccion.id',$id);
        $almacen = $this->connection->get('produccion');
        $almacen = $almacen->row();
        
        $lstProductos = $this->connection->select('producto_id, producto_final_id, cantidad')
                        ->where('produccion_id',$id)
                        ->get('produccion_detalle');
        $almacen->producto = $lstProductos->result();
        
        /** Recorremos y obtenemos los nombre de los productos solicitados a crear */
        foreach ($almacen->producto as $value) {

            $proProducion=$this->connection->select('nombre')->where('id',$value->producto_id)->get('producto');
            
            $proProducion = $proProducion->row();
            $value->produccion=$proProducion;

            $proFinal = $this->connection->select('nombre')->where('id',$value->producto_final_id)->get('producto');
            $proFinal = $proFinal->row();
            $value->final=$proFinal;
        }

        return $almacen;
        
    }

    public function delete($id){
        $data['message'] = '';
        $this->connection->where('produccion_id',$id);
        $this->connection->delete('produccion_detalle');

        $this->connection->where('id',$id);
        $this->connection->delete('produccion');

        if($this->connection->affected_rows() > 0):
            $data['message'] = 'success';
        else: 
            $data['message'] = 'error';
        endif;

        return $data;
    }

    public function removeProduct($id){
        $this->connection->where('id',$id);
        $this->connection->delete('produccion_detalle');

        if($this->connection->affected_rows() > 0):
            $data['message'] = 'success';
        else: 
            $data['message'] = 'error';
        endif;

        return $data;
    }
}

?>