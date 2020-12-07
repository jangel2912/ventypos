<?php

class Almacenes_model extends CI_Model {

    var $connection;

    public function __construct() {

        parent::__construct();

    }

    public function initialize($connection) {

        $this->connection = $connection;

    }

    public function get_almacenes($where) {
      /*  $this->connection->select('*');
        $this->connection->from('almacen');
        $this->connection->where($where);  
        $query = $this->connection->get('almacen');
*/
        $this->connection->where($where);
		$query = $this->connection->get('almacen')->result();
        
        return $query;
    }

    public function get_total() {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");

        return $query->row()->cantidad;

    }

    public function validateInvoiceNumber($consecutivo, $prefijo) {
        $query = $this->connection->query("SELECT factura FROM `venta` where factura LIKE '$prefijo%' order by fecha DESC limit 1");
        if($query->num_rows() > 0) {
            $factura = $query->row()->factura;
            $number = str_replace($prefijo, '', $factura);

            if(is_numeric($number) && $consecutivo < $number) {
                return false;
            }
        }

        return true;
    }

    public function getAll(){
        $query = $this->connection->query("SELECT * FROM almacen ORDER BY id ASC");
        return $query->result();
    }
    public function getAllBodega(){        
        $query = $this->connection->query("SELECT * FROM almacen where bodega=TRUE ORDER BY id ASC");
        return $query->result();
    }

    public function get_Bodega($almacen=0){             
        $query = $this->connection->query("SELECT bodega FROM almacen WHERE id=$almacen");
        return $query->row()->bodega;
    }

    public function cantBodega(){        
        $query = $this->connection->query("SELECT count(*) as cantidad FROM almacen where bodega=TRUE");
        return $query->row()->cantidad;		
    }

    public function get_combo_data($todos = null, $bodega=null) {

        if($bodega){
            $bodega=" and bodega is false";
        }
        else{
            $bodega="";
        }
        
        $data = array();

        //$query = $this->connection->query("SELECT * FROM almacen ORDER BY id ASC");
        $query = $this->connection->query("SELECT * FROM almacen  where activo=1 $bodega ORDER BY id ASC");
        
        if(isset($todos))
            $data[0] = "Todos";

        foreach ($query->result() as $value) {

            $data[$value->id] = $value->nombre;

        }

        return $data;

    }

    public function get_combo_data1() {
        
        $data = array();
        //$query = $this->connection->query("SELECT * FROM almacen ORDER BY id ASC");
        $query = $this->connection->query("SELECT * FROM almacen  ORDER BY id ASC");
        
        if(isset($todos))
            $data[0] = "Todos";

        foreach ($query->result() as $value) {

            $data[$value->id] = $value->nombre;

        }

        return $data;

    }

    public function get_combo_data_stock_actual($id) {

        $query = $this->connection->query("SELECT * FROM almacen left join stock_actual on almacen.id = stock_actual.almacen_id where stock_actual.producto_id = $id ORDER BY almacen_id DESC");

        return $query->result();

    }

    public function get_all($offset,$bodega=null) {

        if($bodega){
            $bodega=" and bodega is false";
        }
        else{
            $bodega="";
        }
                        
        $query = $this->connection->query("SELECT * FROM almacen where activo=1 $bodega ");

        return $query->result();

    }

    public function get_ajax_data_bodegas() {

        $aColumns = array('nombre', 'direccion', 'activo', 'ciudad','id');
        //$aColumns = array('nombre', 'direccion', 'prefijo', 'consecutivo', 'activo', 'telefono', 'meta_diaria', 'id');

        $sIndexColumn = "id";

        $sTable = "almacen";

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

                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .

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

                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";

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

                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";

            }

        }
        if(empty($sWhere)){
            $sWhere = "WHERE bodega is true";
        }else{
           
            $sWhere .= " AND bodega is true";
        }

        $sQuery = "

        SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`

        FROM   $sTable s

        $sWhere

        $sOrder

        $sLimit

            ";
        
        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "

                    SELECT FOUND_ROWS() as cantidad

            ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "

        SELECT COUNT(`" . $sIndexColumn . "`) as cantidad

        FROM   $sTable

            ";

        $rResultTotal = $this->connection->query($sQuery);

        $iTotal = $rResultTotal->row()->cantidad;

        $output = array(

            "sEcho" => intval($_GET['sEcho']),

            "iTotalRecords" => $iTotal,

            "iTotalDisplayRecords" => $iFilteredTotal,

            "aaData" => array(),

        );

        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {

                $data[] = $row[$aColumns[$i]];

            }

            $output['aaData'][] = $data;

        }

        return $output;

    }


    public function get_ajax_data($licence_status = []) {

        $aColumns = array('nombre', 'direccion', 'prefijo', 'consecutivo', 'activo', 'telefono', 'meta_diaria', 'id');

        $sIndexColumn = "id";

        $sTable = "almacen";

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

                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .

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

                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";

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

                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";

            }

        }

        if(empty($sWhere)){
            $sWhere = "WHERE bodega is false";
        }else{            
            $sWhere .= " AND bodega is false";
        }        

        $sQuery = "

        SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`

        FROM   $sTable s

        $sWhere

        $sOrder

        $sLimit

            ";

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "

                    SELECT FOUND_ROWS() as cantidad

            ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "

        SELECT COUNT(`" . $sIndexColumn . "`) as cantidad

        FROM   $sTable

            ";

        $rResultTotal = $this->connection->query($sQuery);

        $iTotal = $rResultTotal->row()->cantidad;

        $output = array(

            "sEcho" => intval($_GET['sEcho']),

            "iTotalRecords" => $iTotal,

            "iTotalDisplayRecords" => $iFilteredTotal,

            "aaData" => array(),

        );

        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {
                if($aColumns[$i] == 'id') {
                    $is_active = 1;
                    foreach($licence_status as $licence) {
                        if($licence['id_almacen'] == $row[$aColumns[$i]]) {
                            $is_active = 0;
                            break;
                        }
                    }
                    $data[] = $is_active;
                    $data[] = $row[$aColumns[$i]];
                }
                else {
                    $data[] = $row[$aColumns[$i]];
                }
            }

            $output['aaData'][] = $data;

        }

        return $output;

    }

    public function get_ajax_data_admin_usuarios() {
        $sql = "select company, username, email, phone, FROM_UNIXTIME(created_on) as creacion, FROM_UNIXTIME(last_login) as ultimo, active, id FROM users WHERE is_admin = 't' GROUP BY db_config_id";

        $data = array();
        foreach ($this->db->query($sql)->result() as $value) {
            $data[] = array(
                $value->company,
                $value->username,
                $value->email,
                $value->phone,
                $value->creacion,
                $value->ultimo,
                $value->active,
                $value->id,
            );
        }
        return array(
            'aaData' => $data,
        );
        //$aColumns = array('company', 'username','email', 'phone', 'FROM_UNIXTIME(created_on)', 'FROM_UNIXTIME(last_login)', 'active', 'id');

        //$sIndexColumn = "id";

        // $sTable = "users";
    }

    public function get_by_name($name) {

        $query = $this->connection->query("select id from almacen where nombre = '$name'");

        if ($query->num_rows() > 0) {

            return $query->row()->id;

        }

        return "";

    }

    public function get_term($q = '') {

        $query = $this->connection->query("SELECT id_producto as id, nombre, precio, i.nombre_impuesto, i.porciento, descripcion FROM productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto WHERE nombre LIKE '%$q%' LIMIT 0,30");

        return $query->result_array();

    }

    public function get_by_id($id = 0) {
        $query = $this->connection->query("SELECT * FROM  almacen WHERE id = '" . $id . "'");
        $baseData = $query->row_array();
        if(array_key_exists('facturacion_electronica', $baseData) && $baseData['facturacion_electronica'] === '1') {
            $facturacion_electronica_campos = get_curl('electronic-invoicing/'.$id, $this->session->userdata('token_api'));
            $baseData['facturacion_electronica_campos'] = is_null($facturacion_electronica_campos) ? json_decode('{}') : $facturacion_electronica_campos;
        } else {
            $baseData['facturacion_electronica_campos'] = json_decode('{}');
        }
        return $baseData;
    }
    
    
    public function getIdAlmacenActualByUserId($id = 0) {
        $query = $this->connection->query("SELECT almacen_id FROM usuario_almacen WHERE usuario_id = '" . $id . "'");
        $idAlmacen = $query->num_rows > 0 ? $query->row()->almacen_id : 0;               
        return $idAlmacen;
    }
    

    public function get_users_by_id($id = 0) {
        $total_ventas = 0;
        $total_productos = 0;
        $tienda = '';

        $active_user = $this->db->query("SELECT db_config_id, active FROM users where id = " . $id . " limit 1")->result();
        foreach ($active_user as $act) {
            $database = $act->db_config_id;
            $estado = $act->active;
        }

        $db_config = $this->db->query("SELECT base_dato, almacen, fecha, estado FROM db_config  where id = " . $database . " limit 1")->first_row();
        $r_tienda = $this->db->query('SELECT * FROM tienda WHERE id_user = ' . $id)->result();
        $alertas_inventario = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 1 AND cliente_id = ' . $database)->result();
        $plan_separe = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 2 AND cliente_id = ' . $database)->result();
        $atributos = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 3 AND cliente_id = ' . $database)->result();
        $puntos = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 4 AND cliente_id = ' . $database)->result();

        $opcion_offline = $this->db->query("SELECT valor_opcion FROM `" . $db_config->base_dato . "`.`opciones` WHERE nombre_opcion='offline' limit 1")->result();

        $db = mysql_select_db($db_config->base_dato);
        if (($err = mysql_errno()) == 1049) {
            echo "";
        } else {

            $otro = $this->dbConnection->query("SELECT count(*) as total_ventas FROM `" . $db_config->base_dato . "`.`venta` ")->result();
            foreach ($otro as $dat_2) {
                $total_ventas = $dat_2->total_ventas;
            }
            $otro = $this->dbConnection->query("SELECT count(*) as total_productos FROM `" . $db_config->base_dato . "`.`producto` ")->result();
            foreach ($otro as $dat_2) {
                $total_productos = $dat_2->total_productos;
            }
            $otro = $this->dbConnection->query("SELECT valor_opcion FROM `" . $db_config->base_dato . "`.`opciones` WHERE nombre_opcion='etienda' limit 1")->result();
            if (empty($otro)) {
                $tienda = 'no';
            } else {
                foreach ($otro as $dat_2) {
                    $tienda = $dat_2->valor_opcion;
                }
            }
        }

        $fecha_inicio = strtotime($db_config->fecha);
        $fecha_actual = strtotime(date('Y-m-d'));
        $fecha_limite = strtotime('+15 day', $fecha_inicio);

        $dias_disponibles = $fecha_limite - $fecha_actual;
        $dias_disponibles = floor($dias_disponibles / (60 * 60 * 24));
        $total_ampliables = 15 - $dias_disponibles;

        if (count($opcion_offline) > 0) {
            $offline = $opcion_offline[0]->valor_opcion == 'active' || $opcion_offline[0]->valor_opcion == 'backup' ? 1 : 0;
        } else {
            $offline = 0;
        }

        $user_result = array(
            'id' => $id,
            'db_config' => $db_config->base_dato,
            'almacenes' => $db_config->almacen,
            'total_ventas' => $total_ventas,
            'total_productos' => $total_productos,
            'tienda' => count($r_tienda) > 0 ? '1' : '0',
            'activo' => $estado,
            'dias_restantes' => $total_ampliables,
            'estado_cliente' => $db_config->estado,
            'alertas_inventario' => count($alertas_inventario) > 0 ? '1' : '0',
            'plan_separe' => count($plan_separe) > 0 ? '1' : '0',
            'atributos' => count($atributos) > 0 ? '1' : '0',
            'puntos' => count($puntos) > 0 ? '1' : '0',
            'offline' => $offline,
        );
        return $user_result;
    }

    public function add($data) {

        $this->connection->insert("almacen", $data);

        $id = $this->connection->insert_id();

        $productos = $this->connection->get('producto');

        $data_stock_actual = array();

        foreach ($productos->result() as $producto) {

            $data_stock_actual[] = array(

                'almacen_id' => $id,

                'producto_id' => $producto->id,

                'unidades' => 0,

            );

        }

        $this->connection->insert_batch('stock_actual', $data_stock_actual);

        return $id;
    }

    public function update($data) {

        /*$array_datos = array(

                                    "nombre"        => $this->input->post('nombre'),

                                                "codigo"        => $this->input->post('codigo'),

                                    "descripcion"   => $this->input->post('descripcion'),

                                    "precio"    => $this->input->post('precio'),

                                                "precio_compra" => $this->input->post('precio_compra'),

                                                "id_impuesto"   => $this->input->post('id_impuesto')

        */

        $this->connection->where('id', $data['id']);

        $this->connection->update("almacen", $data);

    }

    public function update_user($data) {
        $active_user = $this->db->query("SELECT db_config_id, active FROM users where id = " . $data['id'] . " limit 1")->result();

        foreach ($active_user as $act) {
            $database = $act->db_config_id;
        }

        /*Validación módulos*/
        $db_config = $this->db->query("SELECT * FROM db_config WHERE id = " . $database . " limit 1")->first_row();
        $tienda = $this->db->query('SELECT * FROM tienda WHERE id_user = ' . $data['id'])->result();
        $alertas_inventario = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 1 AND cliente_id = ' . $database)->result();
        $plan_separe = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 2 AND cliente_id = ' . $database)->result();
        $atributos = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 3 AND cliente_id = ' . $database)->result();
        $puntos = $this->db->query('SELECT * FROM view_clientes_modulos WHERE id_modulo = 4 AND cliente_id = ' . $database)->result();

        /*Actualizar almacenes*/
        $this->db->where('id', $database);
        $this->db->set('almacen', $data['almacen']);
        $this->db->update("db_config");

        /*Actualizar estado usuarios*/
        $this->db->where('id', $data['id']);
        $this->db->set('active', $data['estado']);
        $this->db->update("users");

        /*Actualizar tienda*/
        $query_tienda = '';
        if ($data['tienda'] && count($tienda) == 0) {
            $nombre = hash_hmac('joaat', $database . '~' . date('YmdHis'), 'vendty');
            $query_tienda = 'INSERT INTO tienda (id_user, id_almacen, shopname, description, layout, activo) VALUES (' . $data['id'] . ', 1, "Tienda_' . $nombre . '", "", "ly_eshop", 0)';
        } else if (!$data['tienda']) {
            $query_tienda = 'DELETE FROM tienda WHERE id_user = ' . $data['id'];
        }

        /*Alertas inventarios*/
        $query_alertas = '';
        if ($data['alertas_inventario'] && count($alertas_inventario) == 0) {
            $query_alertas = 'INSERT INTO modulos_clientes (db_config_id, modulo_id, estado) VALUES (' . $database . ', 1, 0)';
        } else if (!$data['alertas_inventario']) {
            $query_alertas = 'DELETE FROM modulos_clientes WHERE db_config_id = ' . $database . ' AND modulo_id = 1';
        }

        /*plan_separe*/
        $query_plan_separe = '';
        if($data['plan_separe'] && count($plan_separe) == 0)
        {
            $query_plan_separe = 'INSERT INTO modulos_clientes (db_config_id, modulo_id, estado) VALUES ('.$database.', 2, 1)';
        } else if(!$data['plan_separe']) {
            $query_plan_separe = 'DELETE FROM modulos_clientes WHERE db_config_id = '.$database.' AND modulo_id = 2';
        }

        /*atributos*/
        $query_atributos = '';
        if($data['atributos'] && count($atributos) == 0)
        {
            $query_atributos = 'INSERT INTO modulos_clientes (db_config_id, modulo_id, estado) VALUES ('.$database.', 3, 1)';
            $usuario = $this->session->userdata('usuario');
            $clave = $this->session->userdata('clave');
            $servidor = $this->session->userdata('servidor');
            $conn = @mysql_connect($servidor, $usuario, $clave);
           
            if(!$this->connection->table_exists($db_config->base_dato.'.atributos'))
            {
                $db = mysql_select_db($db_config->base_dato);

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
            }

        } else if(!$data['atributos']) {
            $query_atributos = 'DELETE FROM modulos_clientes WHERE db_config_id = '.$database.' AND modulo_id = 3';
        }

        /*puntos*/
        $query_puntos = '';
        if($data['puntos'] && count($puntos) == 0)
        {
            $query_puntos = 'INSERT INTO modulos_clientes (db_config_id, modulo_id, estado) VALUES ('.$database.', 4, 1)';
        } else if(!$data['puntos']) {
            $query_puntos = 'DELETE FROM modulos_clientes WHERE db_config_id = '.$database.' AND modulo_id = 4';
        }



        /*Ampliar periodo de pruebas*/
        $fecha_inicio = strtotime($db_config->fecha);
        $fecha_actual = strtotime(date('Y-m-d'));
        $fecha_limite = strtotime('+15 day', $fecha_inicio);

        $dias_disponibles = $fecha_limite - $fecha_actual;
        $dias_disponibles = floor($dias_disponibles / (60 * 60 * 24));
        $total_ampliables = 15 - $dias_disponibles;

        if ($data['dias_restantes'] <= $total_ampliables && $data['dias_restantes'] > 0) {
            $fecha_inicio = strtotime('+' . $data['dias_restantes'] . ' day', $fecha_inicio);
            $r_dias_restantes = $this->db->query('UPDATE db_config SET fecha = "' . date('Y-m-d', $fecha_inicio) . '"  WHERE id = "' . $database . '"');
        }

        $r_update = $this->db->query('UPDATE db_config SET estado = "' . $data['estado_cliente'] . '" WHERE id = ' . $database);

        if ($query_tienda != '')
            $r_tienda = $this->db->query($query_tienda);
        
        if ($query_alertas != '')
            $r_alertas = $this->db->query($query_alertas);
        
        if ($query_plan_separe != '')
            $r_plan_separe = $this->db->query($query_plan_separe);
        
        if ($query_atributos != '')
            $r_atributos = $this->db->query($query_atributos);
        
        if ($query_puntos != '')
            $r_puntos = $this->db->query($query_puntos);

        $db = mysql_select_db($db_config->base_dato);
        if (($err = mysql_errno()) == 1049) {
            echo "error";
        } else {

            if ($data['tienda']) {
                $tienda = 'si';
            } else if (!$data['tienda']) {
                $tienda = 'no';
            }

            if ($data['offline']) {
                $offline = 'active';
            } else if (!$data['offline']) {
                $offline = 'false';
            }

            $opcion_tienda = $this->dbConnection->query("SELECT valor_opcion FROM `" . $db_config->base_dato . "`.`opciones` WHERE nombre_opcion='etienda' limit 1")->result();
            if (empty($opcion_tienda)) {
                $this->dbConnection->set('nombre_opcion', 'etienda');
                $this->dbConnection->set('valor_opcion', $tienda);
                $this->dbConnection->insert($db_config->base_dato . ".opciones");

                $this->db->set('id_user', $data['id']);
                $this->db->insert("redes");
            } else {
                $this->dbConnection->where('nombre_opcion', 'etienda');
                $this->dbConnection->set('valor_opcion', $tienda);
                $this->dbConnection->update($db_config->base_dato . ".opciones");
            }

            $opcion_offline = $this->dbConnection->query("SELECT valor_opcion FROM `" . $db_config->base_dato . "`.`opciones` WHERE nombre_opcion='offline' limit 1")->result();
            if (empty($opcion_offline)) {
                $this->dbConnection->set('nombre_opcion', 'offline');
                $this->dbConnection->set('valor_opcion', $offline);
                $this->dbConnection->insert($db_config->base_dato . ".opciones");

                $this->db->set('id_user', $data['id']);
                $this->db->insert("redes");
            } else {
                $this->dbConnection->where('nombre_opcion', 'offline');
                $this->dbConnection->set('valor_opcion', $offline);
                $this->dbConnection->update($db_config->base_dato . ".opciones");
            }
        }
    }

    public function verificar_modulo_habilitado($id, $modulo_id)
    {
        $active_user = $this->db->query("SELECT db_config_id, active FROM users where id = ".$id." ORDER BY db_config_id ASC LIMIT 1")->result();

        $query = 'SELECT * FROM view_clientes_modulos WHERE cliente_id = '.$active_user[0]->db_config_id.' AND id_modulo = '.$modulo_id.' AND estado = 1';
        $modulo = $this->db->query($query);
        $num_results = $this->db->count_all_results();
        if ($num_results > 0)
            return true;
        else 
            return false;
    }

    public function delete($id) {
        $usuarios = $this->connection->get_where('usuario_almacen',array('almacen_id'=>$id))->result();
        
        if(count($usuarios) == 0)
        {
            //eliminar la informacion de crm_db_activa_almacenes
            $this->db->where('id_almacen', $id);
            $this->db->where('id_db_config', $this->session->userdata('db_config_id'));
            $this->db->delete("crm_db_activa_almacenes");

            $this->connection->where('id', $id);
            $this->connection->delete("almacen");
        
            return true;
        }else
        {
            $users = "";
            foreach($usuarios as $u)
            {
                $usuario = $this->db->get_where('users',array('id'=>$u->usuario_id))->row();
                $users.= $usuario->username." ( ".$usuario->email." )<br>";
            }
            return $users;
        }        
    }

    public function excel() {
        $this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");
        $this->connection->from("productos");
        $this->connection->join('impuestos', 'impuestos.id_impuesto = productos.id_impuesto');
        $query = $this->connection->get();
        return $query->result();
    }

    public function excel_exist($nombre, $precio) {

        $this->connection->where("nombre", $nombre);

        $this->connection->where("precio", $precio);

        $this->connection->from("productos");

        $this->connection->select("*");

        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            return true;

        } else {

            return false;

        }

    }

    public function excel_add($array_datos) {

        $query = "INSERT INTO `productos` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('" . $array_datos['nombre'] . "', '" . $array_datos['descripcion'] . "', " . $array_datos['precio'] . ", " . $array_datos['id_impuesto'] . ");";

        $this->connection->query($query);

    }

    public function get_almacen_usuario($id) {

        $this->connection->where("usuario_id", $id);

        $result = $this->connection->get('usuario_almacen')->row();

        return $result;

    }

    public function obtenerModulo($nombre) {
        $database = $this->session->userdata('base_dato');
        $q_query = 'SELECT * FROM view_clientes_modulos WHERE nombre = "' . $nombre . '" AND name = "' . $database . '"';
        $modulo = $this->db->query($q_query);

        return $modulo->result();
    }

    public function establecerModulo($nombre, $estado) {
        $database = $this->session->userdata('base_dato');
        $modulo = $this->obtenerModulo($nombre);

        if (count($modulo) > 0) {
            $q_update = 'UPDATE modulos_clientes SET estado = ' . $estado . ' WHERE db_config_id = ' . $modulo[0]->cliente_id . ' AND modulo_id = ' . $modulo[0]->id_modulo;
            $update = $this->db->query($q_update);
            return $update;
        }

        return false;
    }
    
    public function actualizarTabla()
    {
        $sql = "SHOW COLUMNS FROM almacen LIKE 'fecha_vencimiento'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `almacen`   
                ADD COLUMN `numero_fin` INT(10) UNSIGNED NULL AFTER `nit`,
                ADD COLUMN `fecha_vencimiento` DATE NULL AFTER `numero_fin`,
                ADD COLUMN `numero_alerta` INT(10) UNSIGNED NULL AFTER `fecha_vencimiento`,
                ADD COLUMN `fecha_alerta` INT(2) UNSIGNED NULL AFTER `numero_alerta`;
            ";

            $this->connection->query($sql);
        }

        $sql = "SHOW COLUMNS FROM almacen LIKE 'pais'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `almacen`   
                ADD COLUMN `pais` VARCHAR(50) NULL AFTER `ciudad`;
            ";

            $this->connection->query($sql);
        }
    }
    public function actualizarTablaAlmacen(){
        $sql = "SHOW COLUMNS FROM almacen LIKE 'razon_social'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            //creamos el campo
            $sql="ALTER TABLE almacen ADD COLUMN razon_social VARCHAR(250) COMMENT 'Campo razon social para el caso de franquicias'";
            $this->connection->query($sql);   

        }
    }
    public function get_almacenes_inactivos($activo = false){
        $data = array();
        $query = $this->connection->query("SELECT * FROM almacen  where activo=0  ORDER BY id ASC");
        
        foreach ($query->result() as $value) {
            $data[$value->id] = $value->nombre;
        }
        return $data;
    }

    public function get_almacenes_activos($activo = true){
        if($activo){
            $this->connection->where('activo',1);
        }
        $this->connection->select('*');
        $query = $this->connection->get('almacen');
        return $query->result();

    }

    public function update_almacen_activo($data){
        $this->connection->where('id', $data['id']);
        $this->connection->set('activo', $data['estado']);
        $this->connection->update("almacen");
    }

    public function actualizarTablaAlmacenBodega(){
        $sql = "SHOW COLUMNS FROM almacen LIKE 'bodega'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            //creamos el campo
            $sql="ALTER TABLE almacen ADD COLUMN bodega BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Campo para saber si es almacen=false o bodega=true'";
            $this->connection->query($sql);              
        }
    }

    public function actualizarTablaAlmacenordenRestaurant(){
        $sql = "SHOW COLUMNS FROM almacen LIKE 'consecutivo_orden_restaurante'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            //creamos el campo
            $sql="ALTER TABLE almacen ADD COLUMN consecutivo_orden_restaurante INT NOT NULL DEFAULT 1 COMMENT 'Campo para saber el numero de orden para las mesas',
                  ADD COLUMN reiniciar_consecutivo_orden_restaurante INT NULL DEFAULT 100 COMMENT 'Numero para reiniciar el consecutivo de la orden de restaurante';
            ";
            $this->connection->query($sql);              
        }
    }

    /**
     * @method  actualizar_tabla_almacen_cierre_caja()
     *  Actualiza la tabla almacen con los campos activar_consecutivo_cierre_caja y consecutivo_cierre_caja
     * @author [Dairinet Avila]    
    */

    public function actualizar_tabla_almacen_cierre_caja(){
        $sql = "SHOW COLUMNS FROM almacen LIKE 'consecutivo_cierre_caja'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            //creamos el campo
            $sql="ALTER TABLE almacen ADD COLUMN activar_consecutivo_cierre_caja varchar(2) NOT NULL DEFAULT 'no' COMMENT 'Campo para saber si está activo el consecutivo de cierre de caja para el almacen',
            ADD COLUMN consecutivo_cierre_caja INT NOT NULL DEFAULT 0 COMMENT 'Campo para saber el consecutivo del cierre de caja del almacen';";
            $this->connection->query($sql);              
        }
    }

    /**
     * @method  buscar_Consecutivo_cierre_caja($where)
     *  busca el consecutivo del cierre de caja del almacen
     * @author [Dairinet Avila]
     * @return numerosiguiente
    */
    public function buscar_consecutivo_cierre_caja($where){
        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('almacen')->row();        
        $numero=intval($query->consecutivo_cierre_caja)+1;
        //le sumo uno al consecutivo
        $this->connection->where($where);
        $this->connection->set('consecutivo_cierre_caja',$numero);
        $this->connection->update("almacen");
        
        return $numero;
    }

    public function buscar_Consecutivo_orden_restaurante($where){
        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('almacen')->row();
        
        $numero=intval($query->consecutivo_orden_restaurante);
        $reiniciarnumero=intval($query->reiniciar_consecutivo_orden_restaurante);
        
        if($numero > $reiniciarnumero){
            $numerosiguiente = 1;
            $numero = 1;
        } else if($numero == $reiniciarnumero){
            $numerosiguiente = $numero;
            $numero = 0;
        } else{
            $numerosiguiente = $numero;
        }
        //le sumo uno al consecutivo
        $this->connection->where($where);
        $this->connection->set('consecutivo_orden_restaurante', $numero+1);
        $this->connection->update("almacen");
        
        return $numerosiguiente;
    }

    public function newColumnBusinessName(){
        $query = $this->connection->get('almacen')->row(1);
        if(!isset($query->razon_social) && !property_exists($query, "razon_social"))
        {
            $this->connection->query("ALTER TABLE almacen ADD razon_social varchar(250) DEFAULT '0';");            
        }
        return $query;
    }

    public function newColumnIva(){
        $query = $this->connection->get('almacen')->row(1);
        if(!isset($query->responsable_iva) && !property_exists($query, "responsable_iva"))
        {
            $this->connection->query("ALTER TABLE almacen ADD responsable_iva ENUM('0','1') DEFAULT '1';");            
        }
        return $query;
    }

}

?>