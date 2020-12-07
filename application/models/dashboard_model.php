<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends CI_Model
{

    public $connection;
    public $connectionUser;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function get_terms_conditions()
    {
        $data = array(
            'term_acept' => 'Si',
        );
        $this->db->select("term_acept");
        $this->db->from("users");
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->limit(1);

        $result = $this->db->get();

        return $result->result()[0]->term_acept;
    }

    public function accept_terms_conditions()
    {
        $data = array(
            'term_acept' => 'Si',
        );
        $this->db->where('id', $this->session->userdata('user_id'));
        $this->db->update('users', $data);
        if ($this->db->affected_rows() > 0):
            return true;
        else:
            return false;
        endif;
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

    //==================================================================================
    //==================================================================================
    //==================================================================================

    //----------------------------------------------------------------------------------
    //  OFFLINE
    //----------------------------------------------------------------------------------

    public function queryOfflineAjax($mail = null)
    {
        $queryUser;

        if ($mail == null) {

            //Consula sql
            $sql = "SELECT * FROM opciones WHERE nombre_opcion = 'offline' ";
            // capturamos el iddb de un email
            $queryUser = $this->connection->query($sql)->row();

        } else {

            //Consula sql
            $sql = "SELECT db.servidor,db.base_dato, db.clave, db.usuario FROM users u INNER JOIN db_config db ON u.db_config_id = db.id WHERE u.email LIKE '%$mail%'";
            $queryDBObj = $this->db->query($sql);

            if ($queryDBObj->num_rows() == 0) {
                return "empty";
            }

            $queryDB = $queryDBObj->row();

            $usuario = $queryDB->usuario;
            $clave = $queryDB->clave;
            $servidor = $queryDB->servidor;
            $base_dato = $queryDB->base_dato;

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->connectionUser = $this->load->database($dns, true);

            $this->connectionUser->_error_message();
            $this->connectionUser->_error_number();

            $sql = "SELECT * FROM opciones WHERE nombre_opcion = 'offline' ";
            $queryResult = $this->connectionUser->query($sql);

            //Si no hay un obj como respuesta es que no hay conexion
            if (!$queryResult) {
                return "empty";
            } else {
                $queryUser = $queryResult->row();
            }

        }

        // SI NO HAY RESPUESTA ES POR QUE NO HAY CREADO EL ATRIBUTO OFFLINE, ENTOCES LA CREAMOS
        if (count($queryUser)) {

            return $queryUser->valor_opcion;

        } else {

            $sql = "INSERT INTO opciones (nombre_opcion, valor_opcion) VALUES  ('offline','false') ";

            if ($mail == null) {
                $this->connection->query($sql);
            } else {
                $this->connectionUser->query($sql);
            }

            return "false";

        }

    }

    public function setOffline($mail = null, $tipo)
    {

        if ($mail == "") {

            $sql = " UPDATE opciones SET valor_opcion = '$tipo' WHERE nombre_opcion = 'offline' ";
            $this->connection->query($sql);

        } else {

            //Consula sql
            $sql = "SELECT db.servidor,db.base_dato, db.clave, db.usuario FROM users u INNER JOIN db_config db ON u.db_config_id = db.id WHERE u.email LIKE '%$mail%'";
            $queryDB = $this->db->query($sql)->row();

            $usuario = $queryDB->usuario;
            $clave = $queryDB->clave;
            $servidor = $queryDB->servidor;
            $base_dato = $queryDB->base_dato;

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->connectionUser = $this->load->database($dns, true);

            //Consula sql
            $sql = " UPDATE opciones SET valor_opcion = '$tipo' WHERE nombre_opcion = 'offline' ";
            // capturamos el iddb de un email
            $this->connectionUser->query($sql);

        }

    }

    public function getOffline()
    {
        // set_time_limit(0);
        ini_set("memory_limit", "-1");

        $tablas = $this->dbConnection->list_tables();
        $this->load->model('almacenes_model', 'almacenes');
        $this->almacenes->initialize($this->dbConnection);

        // Eliminamos tablas que son muy pesadas pero no tiene relación con una venta, y eliminamos las tablas backup
        foreach ($tablas as $i => $tabla) {

            if (strpos($tabla, '2015')) {
                unset($tablas[$i]);
            }

            if (strpos($tabla, '2016')) {
                unset($tablas[$i]);
            }

            if (strpos($tabla, '2017')) {
                unset($tablas[$i]);
            }

            if (strpos($tabla, '09')) {
                unset($tablas[$i]);
            }

            if (strpos($tabla, '_copy')) {
                unset($tablas[$i]);
            }

            if ($tabla == 'stock_historial') {
                unset($tablas[$i]);
            }

            if ($tabla == 'promociones_productos') {
                unset($tablas[$i]);
            }

        }
        //pr( $tablas );

        $tablesSQL = array();

        foreach ($tablas as $table) {

            $fields = $this->connection->field_data($table);

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

            $tablesSQL[] = $sql;

        }

        // Poner tablas en la lista para hacer el backup respectivo
        // IMPORTANTE!! La tabla debe estar creada en el script public/v2/appOffline.js

        $master = array();

        $data = array();

        //-------------------
        //  vendty2
        //-------------------

        // Colocar en esta lista las tablas que se desea copiar desde vendty2
        $tablas2 = ['provincia'];

        foreach ($tablas2 as $tabla) {
            if ($this->db->table_exists($tabla)) {
                $data[$tabla] = $this->db->query("SELECT * FROM $tabla")->result();

            }
        }

        //-------------------
        //  DB USER
        //-------------------
        //el almacen para traer solo las ventas del almacen del usuario
        $id_almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
        foreach ($tablas as $tabla) {

            // tablas al las que no copiaremos la informacion
            if (
                //$tabla == 'venta' ||
                $tabla == 'ventas_anuladas' ||
                //$tabla == 'stock_diario' ||
                $tabla == 'pagos' ||
                $tabla == 'pago' ||
                $tabla == 'movimientos_cierre_caja' ||
                $tabla == 'movimiento_inventario' ||
                $tabla == 'movimiento_detalle' ||
                //$tabla == 'facturas_detalles' ||
                $tabla == 'facturas' ||
                //$tabla == 'detalle_venta' ||
                $tabla == 'cierres_caja' ||
                $tabla == 'restablecer' ||
                strpos($tabla, 'clientes_') !== false ||
                strpos($tabla, 'hist') !== false ||
                strpos($tabla, 'promociones') !== false ||
                //strpos( $tabla, 'ventas_pago' ) !== false ||
                //strpos( $tabla, 'detalle' ) !== false ||
                strpos($tabla, 'atributos' !== false)
            ) {
                $data[$tabla] = $this->connection->query("SELECT * FROM $tabla LIMIT 1")->result();
                //echo "1.".$tabla;var_dump($this->connection->query("SELECT * FROM $tabla  LIMIT 1")->result());
            } else {
                if ($tabla == 'venta') {
                    $where = array('almacen_id' => $id_almacen, 'date(fecha)' => date("Y-m-d", strtotime('-2 days')));
                    $data[$tabla] = $this->connection->select('*')->where($where)->get($tabla)->result();

                } elseif ($tabla == 'detalle_venta') {
                    $where = array('b.almacen_id' => $id_almacen, 'date(fecha)' => date("Y-m-d", strtotime('-2 days')));
                    $this->connection->select('a.*');
                    $this->connection->from($tabla . ' a');
                    $this->connection->join('venta b', 'a.venta_id =b.id');
                    $this->connection->where($where);
                    $data[$tabla] = $this->connection->get()->result();
                } else {
                    $data[$tabla] = $this->connection->query("SELECT * FROM $tabla ")->result();
                    //echo "2.".$tabla;var_dump($this->connection->query("SELECT * FROM $tabla")->result());
                }

            }
        }

        //-------------------
        //  Convertimos mapa a array, eliminamos indices
        //-------------------
        $tablasLista = array();
        foreach ($tablas as $tabla) {
            $tablasLista[] = $tabla;
        }

        //añadimos tabla provincias al array de nombres de tablas
        array_push($tablasLista, "provincia");

        $master["script_tablas"] = $tablesSQL;
        $master["tablas"] = $tablasLista;
        $master["data"] = $data;

        return $master;

    }

    public function getOfflineExtraData()
    {
        $data = array();

        $consecutivoQ = $this->connection->query("SELECT factura FROM venta ORDER BY id DESC LIMIT 1");
        $ultima_ventaQ = $this->connection->query(" SELECT id FROM venta ORDER BY id DESC LIMIT 1 ");
        $ultimo_cliente = $this->connection->query(" SELECT id_cliente FROM clientes ORDER BY id_cliente DESC LIMIT 1 ");

        $data["consecutivo"] = $consecutivoQ->num_rows > 0 ? $consecutivoQ->row()->factura : "";
        $data["ultima_venta"] = $ultima_ventaQ->num_rows > 0 ? $ultima_ventaQ->row()->id : "0";
        $data["ultimo_cliente"] = $ultimo_cliente->num_rows > 0 ? $ultimo_cliente->row()->id_cliente : "0";

        $data["id_user"] = $this->session->userdata('user_id');
        $data["username"] = $this->session->userdata('username');
        $data["is_admin"] = $this->session->userdata('is_admin');
        $data["base"] = base_url();

        return $data;
    }

    //----------------------------------------------------------------------------------
    //----------------------------------------------------------------------------------

    public function getLogo()
    {
        return $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'logotipo_empresa' ")->row()->valor_opcion;
    }

    public function setUi($version)
    {
        $this->connection->query(" UPDATE opciones SET valor_opcion = '$version' WHERE nombre_opcion = 'ui_version' ");
    }

    public function getAllAlmacenes()
    {
        $isAdmin = $this->session->userdata('is_admin');

        if ($isAdmin == 't' || $isAdmin == 'a') {
            $query = $this->connection->query("SELECT * FROM almacen");
        } else {

            $almActual = $this->getAlmacenActual();
            $query = $this->connection->query("SELECT * FROM almacen where id = $almActual");
        }
        return $query->result();
    }

    public function getPermisos()
    {
        $idUser = $this->session->userdata('user_id');
        $idRol = $this->db->query("SELECT rol_id FROM users WHERE id =  $idUser")->row()->rol_id;
        $permisosTmp = $this->connection->query("SELECT id_permiso FROM permiso_rol WHERE id_rol = $idRol")->result();
        $permisos = array();

        foreach ($permisosTmp as $value) {
            $permisos[] = $value->id_permiso;
        }

        return $permisos;
    }

    public function getZoho($id)
    {
        $sql = "SELECT first_name, last_name, email, phone AS telefono, first_name AS nombre FROM users WHERE id = $id";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getEmpresaData()
    {
        $query = $this->connection->query("SELECT * FROM opciones");
        return $query->result();
    }

    public function getAlmacenActuallicencias()
    {
        $id = $this->session->userdata('user_id');

        $query = $this->connection->query("SELECT almacen_id FROM usuario_almacen WHERE usuario_id = $id");
        if ($query->num_rows() == 0) {
            return 0;
        } else {
            return $query->row()->almacen_id;
        }
    }

    public function getAlmacenActual()
    {
        $permisos = getPermisos();

        if ($permisos["admin"] == "a") {
            return 0;
        } else {
            $id = $this->session->userdata('user_id');
            $query = $this->connection->query("SELECT almacen_id FROM usuario_almacen WHERE usuario_id = $id");

            if ($query->num_rows() == 0) {
                return 0;
            } else {
                return $query->row()->almacen_id;
            }
        }
    }

    public function getProductosRelevantesHoy($almacen = 0)
    {
        $filtroFecha = "";
        $conditionAlmacen = "";
        $hoy = date('Y-m-d');
        $desde = $hoy;
        $hasta = $hoy;
        $hasta = strtotime('+1 day', strtotime($hasta));
        $hasta = date('Y-m-d', $hasta);
        $filtroFecha = " and venta.fecha >= '" . $desde . "'";
        $filtroFecha .= " and venta.fecha <= '" . $hasta . "'";

        if ($almacen != 0) {
            $filtroFecha .= "  and venta.almacen_id = " . $almacen;
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = " AND almacen_id = '$almacen'";
        }

        $query_relevantes = "
            SELECT producto.imagen, producto.nombre, SUM(unidades) AS count_productos, SUM(margen_utilidad) AS utilidad, detalle_venta.precio_venta
            FROM detalle_venta
            INNER JOIN venta ON detalle_venta.venta_id = venta.id
            INNER JOIN producto ON detalle_venta.producto_id = producto.id
            WHERE venta.estado='0'
            $conditionAlmacen
            $filtroFecha
            GROUP BY nombre_producto
            ORDER BY count_productos
            DESC LIMIT 3
        ";

        return $this->connection->query($query_relevantes)->result_array();
    }

    public function getVentas($almacen = 0, $desde = 0, $group = 0)
    {
        $filtroFecha = "";
        $conditionAlmacen = "";
        $hoy = date('Y-m-d');
        $desde = strtotime("-$desde day", strtotime($hoy));
        $desde = date('Y-m-d', $desde);
        $hasta = strtotime('+1 day', strtotime($hoy));
        $hasta = date('Y-m-d', $hasta);
        $filtroFecha = " and venta.fecha >= '" . $desde . "' ";
        $filtroFecha .= " and venta.fecha <= '" . $hasta . "' ";
        $dia = date('d');

        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = " AND almacen_id = '$almacen'";
        }

        if ($group == '0') {
            $group = "";
        } else {
            $group = " GROUP BY DATE_FORMAT(fecha,'%y-%m-%d')";
        }

        $queryVentas = "
            select count(venta.id) as cantidad, sum(total_venta) as total_venta, DATE_FORMAT(fecha,'%d') as dia
            FROM venta
            inner join almacen ON venta.almacen_id = almacen.id
            WHERE estado='0'
            $conditionAlmacen
            $filtroFecha
            $group
        ";

        if ($this->connection->query($queryVentas)->num_rows() == 0) {
            $result = array();
            $result[] = array(
                "total_venta" => "0",
                "dia" => $dia,
                'cantidad' => 0,
            );

            return $result;
        } else {
            return $this->connection->query($queryVentas)->result_array();
        }
    }

    public function getUtilidad($almacen = 0, $desde = 0)
    {
        $filtroFecha = "";
        $conditionAlmacen = "";
        $hoy = date('Y-m-d');
        $desde = strtotime("-$desde day", strtotime($hoy));
        $desde = date('Y-m-d', $desde);
        $hasta = strtotime('+1 day', strtotime($hoy));
        $hasta = date('Y-m-d', $hasta);
        $filtroFecha = " and venta.fecha >= '" . $desde . "' ";
        $filtroFecha .= " and venta.fecha <= '" . $hasta . "' ";
        $dia = date('d');

        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = " AND  almacen_id = '$almacen'";
        }

        $queryVentas = "
            SELECT ( SUM( margen_utilidad ) ) AS total_utilidad, DATE_FORMAT(venta.fecha, '%d') AS dia
            FROM detalle_venta
            INNER JOIN venta ON detalle_venta.venta_id = venta.id
            WHERE estado='0'
            $conditionAlmacen
            $filtroFecha
        ";

        if ($this->connection->query($queryVentas)->num_rows() == 0) {
            $result = array();
            $result[] = array(
                "total_utilidad" => "0",
                "dia" => $dia,
            );

            return $result;
        } else {
            return $this->connection->query($queryVentas)->result_array();
        }
    }

    public function ventasPorAlmacen($almacen = 0, $desde = 0)
    {
        $filtroFecha = "";
        $conditionAlmacen = "";

        //Hoy
        $hoy = date('Y-m-d');

        $desde = strtotime("-$desde day", strtotime($hoy));
        $desde = date('Y-m-d', $desde);
        $hasta = strtotime('+1 day', strtotime($hoy));
        $hasta = date('Y-m-d', $hasta);

        $filtroFecha = " and venta.fecha >= '" . $desde . "' ";
        $filtroFecha .= " and venta.fecha <= '" . $hasta . "' ";

        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = " AND  almacen_id = '$almacen'";
        }

        $queryVentas = "

            SELECT almacen.nombre,
            ROUND ( SUM( dv.precio_venta * dv.unidades ) - SUM( dv.unidades * dv.descuento ) + SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) )  AS total_venta
            FROM venta
            INNER JOIN detalle_venta AS dv ON venta.id=dv.venta_id
            INNER JOIN almacen ON venta.almacen_id = almacen.id
            WHERE estado='0'
            $filtroFecha
            GROUP BY almacen_id
            ASC LIMIT 5

        ";

        if ($this->connection->query($queryVentas)->num_rows() == 0) {
            $result = array();
            $result[] = array(
                "nombre" => "Sin ventas",
                "total_venta" => "0",
            );

            return $result;
        } else {
            return $this->connection->query($queryVentas)->result_array();
        }
    }

    public function productosMasPopulares($almacen = 0, $desde = 0)
    {
        $filtroFecha = "";
        $conditionAlmacen = "";
        $hoy = date('Y-m-d');
        $desde = strtotime("-$desde day", strtotime($hoy));
        $desde = date('Y-m-d', $desde);
        $hasta = strtotime('+1 day', strtotime($hoy));
        $hasta = date('Y-m-d', $hasta);
        $filtroFecha = " and venta.fecha >= '" . $desde . "' ";
        $filtroFecha .= " and venta.fecha <= '" . $hasta . "' ";

        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = " and  almacen_id = '$almacen'";
        }

        $queryVentas = "
            SELECT producto.nombre, SUM(unidades) AS count_productos
            FROM detalle_venta
            INNER JOIN venta ON detalle_venta.venta_id = venta.id
            INNER JOIN producto ON detalle_venta.producto_id = producto.id
            WHERE venta.estado='0'
            $conditionAlmacen
            $filtroFecha
            GROUP BY nombre_producto
            ORDER BY count_productos
            DESC LIMIT 5

        ";

        if ($this->connection->query($queryVentas)->num_rows() == 0) {
            $result = array();
            $result[] = array(
                "nombre" => "Sin productos vendidos",
                "count_productos" => "0",
            );

            return $result;
        } else {
            return $this->connection->query($queryVentas)->result_array();
        }
    }

    public function categoriasMasVendidas($almacen = 0, $desde = 0)
    {
        $filtroFecha = "";
        $conditionAlmacen = "";
        $hoy = date('Y-m-d');
        $desde = strtotime("-$desde day", strtotime($hoy));
        $desde = date('Y-m-d', $desde);
        $hasta = strtotime('+1 day', strtotime($hoy));
        $hasta = date('Y-m-d', $hasta);
        $filtroFecha = " and v.fecha >= '" . $desde . "' ";
        $filtroFecha .= " and v.fecha <= '" . $hasta . "' ";

        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = " AND  almacen_id = '$almacen'";
        }

        $queryVentas = "
			SELECT c.nombre, ROUND( SUM( dv.precio_venta * dv.unidades) ) AS total
			FROM  detalle_venta AS dv
			INNER JOIN venta AS v ON dv.venta_id = v.id
			INNER JOIN producto AS p ON dv.producto_id = p.id
			INNER JOIN categoria AS c ON p.categoria_id=c.id
			WHERE v.estado='0'
            $conditionAlmacen
            $filtroFecha
			GROUP BY p.categoria_id
			ORDER BY total
			DESC LIMIT 5
        ";

        $queryResult = $this->connection->query($queryVentas);

        if ($queryResult->num_rows() == 0) {
            $result = array();
            $result[] = array(
                "nombre" => "Sin ventas",
                "total" => "0",
            );

            return $result;
        } else {
            return $queryResult->result_array();
        }
    }

    public function stockMinimo($almacen = 0)
    {
        if ($almacen == '0') {
            $conditionAlmacen = "";
        } else {
            $conditionAlmacen = "WHERE almacen_id = '$almacen'";
        }

        $queryVentas = "
            SELECT producto.nombre,ROUND( SUM( unidades ) ) AS unidades
            FROM stock_actual
            INNER JOIN producto ON stock_actual.producto_id = producto.id
            $conditionAlmacen
            GROUP BY producto_id
            ORDER BY unidades
            ASC LIMIT 5
        ";

        return $this->connection->query($queryVentas)->result_array();
    }

    //==================================================================================
    //==================================================================================
    //==================================================================================

    public function get_meta_diaria($almacen = 0, $desde = 0)
    {
        $hoy = date('Y-m-d');
        $desde = strtotime("-$desde day", strtotime($hoy));
        $desde = date('Y-m-d', $desde);
        $hasta = strtotime('+1 day', strtotime($hoy));
        $hasta = date('Y-m-d', $hasta);
        $filtroFecha = " and venta.fecha >= '" . $desde . "' ";
        $filtroFecha .= " and venta.fecha <= '" . $hasta . "' ";

        $filtro = "";
        $nom_alm = "";
        $filtro_margen = "";

        if ($almacen == '0') {
            $condition = '';
            $filtro = "";
        } else {
            $condition = " and venta.almacen_id = $almacen ";
            $filtro = " where id= $almacen ";
        }

        $get_sum_almacenes_meta = "select IFNULL(sum(almacen.meta_diaria), 0) as meta_almacen from almacen  $filtro";
        $total_ventas = "SELECT IFNULL(sum(total_venta),0) as total_venta from venta  where venta.id>0 and estado = '0' " . $filtroFecha . $condition;
        $result = array(
            'meta_almacen' => $this->connection->query($get_sum_almacenes_meta)->row()->meta_almacen,
            'total_ventas' => $this->connection->query($total_ventas)->row()->total_venta,
            'total_devolucion' => 0,
        );

        return $result;
    }

    public function get_productos_relevantes($almacen = 0, $desde = 0, $hasta = 0)
    {
        $filtro = "";

        if ($desde != 0) {
            $nuevahasta = strtotime('+1 day', strtotime($hasta));
            $nuevahasta = date('Y-m-j', $nuevahasta);
            $filtro = " and venta.fecha >= '" . $desde . "'";
            $filtro .= " and venta.fecha <= '" . $nuevahasta . "'";
            if ($almacen != 0) {
                $filtro .= "  and venta.almacen_id = " . $almacen;
            }
        }
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }
            $query_relevantes = "SELECT count(`nombre_producto`) as count_productos, nombre_producto FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id  where venta.estado='0' $condition  $filtro group by `nombre_producto` ORDER BY `count_productos`  DESC limit 10";
        }
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $query_relevantes = "SELECT count(`nombre_producto`) as count_productos, nombre_producto FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id  where venta.estado='0' and venta.almacen_id = $almacen $filtro group by `nombre_producto` ORDER BY `count_productos`  DESC limit 10";
        }

        return $this->connection->query($query_relevantes)->result_array();
    }

    public function excel_productos_relevantes($desde = 0, $hasta = 0, $almacen = 0)
    {
        $filtro = "";

        if ($desde != 0) {
            $nuevahasta = strtotime('+1 day', strtotime($hasta));
            $nuevahasta = date('Y-m-j', $nuevahasta);
            $filtro = " and venta.fecha >= '" . $desde . "'";
            $filtro .= " and venta.fecha <= '" . $nuevahasta . "'";
            if ($almacen != 0) {
                $filtro .= "  and venta.almacen_id = " . $almacen;
            }
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }
            $query_relevantes = "SELECT count(`nombre_producto`) as count_productos,
	  nombre_producto , nombre
		FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id
		inner join almacen on almacen.id = venta.almacen_id
		where venta.estado='0' $condition  $filtro  group by `nombre_producto` ORDER BY `count_productos` desc ";
        }
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $query_relevantes = "SELECT count(`nombre_producto`) as count_productos,
	  nombre_producto , nombre
		FROM `detalle_venta` inner join venta on detalle_venta.venta_id = venta.id
		inner join almacen on almacen.id = venta.almacen_id
		where venta.estado='0' and venta.almacen_id = $almacen  $filtro  group by `nombre_producto` ORDER BY `count_productos` desc ";
        }

        $productos_populares = array();
        $productos_populares = $this->connection->query($query_relevantes)->result();

        return array(
            'productos' => $productos_populares,
        );
    }

    public function get_utilidad_almacen($almacen = 0)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  venta.almacen_id = '$almacen' ";
            }
            $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id where venta.estado='0' $condition  group by almacen.id ";
        }
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id where venta.estado='0' and   venta.almacen_id = $almacen group by almacen.id  ";
        }

        return $this->connection->query($get_utilidad)->result_array();
    }

    public function get_utilidad_general($desde = 0, $hasta = 0)
    {
        $filtro = "";

        $filtro_margen = "";

        if ($hasta != 0) {

            $nuevahasta = strtotime('+1 day', strtotime($hasta));
            $nuevahasta = date('Y-m-j', $nuevahasta);
            $filtro = " and venta.fecha >= '" . $desde . "'";

            $filtro .= " and venta.fecha <= '" . $nuevahasta . "'";

            //  $filtro_margen = " where venta.almacen_id = $almacen";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre as almacen_nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id  where venta.estado='0' $filtro group by almacen.id  ";
        }
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $get_utilidad = "SELECT  sum(detalle_venta.`margen_utilidad`) as margen_utilidad, almacen.nombre as almacen_nombre  FROM `detalle_venta` Inner Join venta on venta.id = detalle_venta.venta_id inner join almacen on almacen.id = venta.almacen_id  where venta.estado='0' and venta.almacen_id = $almacen $filtro  group by almacen.id  ";
        }

        return $this->connection->query($get_utilidad)->result_array();
    }

    public function get_data_empresa()
    {
        $data = array();

        $this->connection->select('valor_opcion');
        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'nombre_empresa'));
        $data['data']['nombre'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $this->connection->select('valor_opcion');
        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'resolucion_factura'));
        $data['data']['resolucion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'contacto_empresa'));
        $data['data']['contacto'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'email_empresa'));
        $data['data']['email'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'direccion_empresa'));
        $data['data']['direccion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'telefono_empresa'));
        $data['data']['telefono'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'fax_empresa'));
        $data['data']['fax'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'web_empresa'));
        $data['data']['web'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'moneda_empresa'));
        $data['data']['moneda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'resolucion_factura_estado'));
        $data['data']['resolucion_factura_estado'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'plantilla_empresa'));
        $data['data']['plantilla'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'paypal_email'));
        $data['data']['paypal_email'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'cabecera_factura'));
        $data['data']['cabecera_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'terminos_condiciones'));
        $data['data']['terminos_condiciones'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'titulo_venta'));
        $data['data']['titulo_venta'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'sistema'));
        $data['data']['sistema'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'nit'));
        $data['data']['nit'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'plantilla_cotizacion'));
        $data['data']['plantilla_cotizacion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'numero'));
        $data['data']['numero'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'sobrecosto'));
        $data['data']['sobrecosto'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'multiples_formas_pago'));
        $data['data']['multiples_formas_pago'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'vendedor_impresion'));
        $data['data']['vendedor_impresion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'valor_caja'));
        $data['data']['valor_caja'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'filtro_ciudad'));
        $data['data']['filtro_ciudad'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'tipo_factura'));
        $data['data']['tipo_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'comanda'));
        $data['data']['comanda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        //tienda
        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'etienda'));
        $data['data']['etienda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'logotipo_empresa'));
        $data['data']['logotipo'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        $query = $this->connection->get_where('opciones', array('nombre_opcion' => 'puntos_leal'));
        $data['data']['puntos_leal'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        //Nombre almacen
        $idUser = $this->session->userdata('user_id');
        $sql = "
                SELECT al.nombre FROM usuario_almacen AS ua
                INNER JOIN almacen AS al ON ua.almacen_id = al.id
                WHERE usuario_id = $idUser
         ";
        $query = $this->connection->query($sql);
        $data['data']['nombre_almacen'] = $query->num_rows > 0 ? $query->row()->nombre : "";

        return $data;
    }

    public function get_ventas_hora($fecha, $almacen = null)
    {
        $where = '';
        if ($almacen != null && $almacen != 0) {
            $where = "and almacen_id = $almacen";
        }

        $devolver = array('0' => '0', '1' => '0', '2' => '0', '3' => '0', '4' => '0', '5' => '0', '6' => '0', '7' => '0', '8' => '0', '9' => '0', '10' => '0', '11' => '0', '12' => '0', '13' => '0', '14' => '0', '15' => '0', '16' => '0', '17' => '0', '18' => '0', '19' => '0', '20' => '0', '21' => '0', '22' => '0', '23' => '0', '24' => '0');
        $sql = "SELECT sum(total_venta) as cantidad, HOUR(fecha) as hora FROM venta WHERE DATE(fecha) = '" . $fecha . "' $where GROUP BY HOUR(fecha) ORDER BY fecha";

        $query = $this->connection->query($sql);
        foreach ($query->result() as $key => $value) {
            $devolver[$value->hora] = $value->cantidad;
        }

        return $devolver;
    }

    public function load_type_businness($tipo)
    {

        switch ($tipo) {
            case 'restaurante':

                $sql = "insert  into `categoria`(`id`,`codigo`,`nombre`,`imagen`,`padre`,`activo`,`tienda`,`es_menu_principal_tienda`) values (2,0,'General','',NULL,1,1,0),(3,0,'Entradas','',NULL,1,1,0),(4,0,'Ensaladas','',NULL,1,1,0),(5,0,'Batidos','',NULL,1,1,0),(6,0,'Bebidas','',NULL,1,1,0),(7,0,'GiftCard','giftCard.png',NULL,1,0,0)";
                $this->connection->query($sql);

                $sql = "insert  into `comanda_notificacion_cliente`(`id`,`usuario`,`nombre`,`notificacion`) values (2,11416,'administracion@hotelparquedelrio.com','20172211195707393000')";
                $this->connection->query($sql);

                $sql = "insert  into `comanda_notificacion_servidor`(`notificacion`) values ('20171110214116979500')";
                $this->connection->query($sql);

                $sql = "insert  into `cuentas_dinero`(`id`,`nombre`,`tipo_cuenta`,`numero`,`banco`,`tipo_bancaria`,`id_almacen`) values (1,'caja menor','Caja Menor','','','Ahorro',1),(2,'Caja Bancos','Banco','','','',1)";
                $this->connection->query($sql);

                $sql = "insert  into `detalle_orden_compra`(`id`,`venta_id`,`codigo_producto`,`nombre_producto`,`descripcion_producto`,`unidades`,`precio_venta`,`descuento`,`impuesto`,`impuesto_id`,`linea`,`margen_utilidad`,`activo`,`id_unidad`,`producto_id`,`precio_venta_p`,`precio_venta_actual`) values (16,1,'016','Jugo de Mora','',1,0,0,0,NULL,NULL,0,1,1,16,5000,NULL),(17,2,'018','Jugo de Guanabana','',1,1000,0,0,NULL,NULL,0,1,1,18,5000,NULL)";
                $this->connection->query($sql);

                $sql = "insert  into `secciones_almacen`(`id`,`fecha_creacion`,`creado_por`,`fecha_modificacion`,`modificado_por`,`activo`,`id_almacen`,`codigo_seccion`,`nombre_seccion`,`descripcion_seccion`) values (1,'2019-10-25 02:14:08',21105,NULL,NULL,1,1,'1','Principal',''),(2,'2019-10-25 02:14:34',21105,NULL,NULL,1,1,'2','Terraza','')";
                $this->connection->query($sql);

                $sql = "insert  into `mesas_secciones`(`id`,`fecha_creacion`,`creado_por`,`fecha_modificacion`,`modificado_por`,`activo`,`id_seccion`,`codigo_mesa`,`nombre_mesa`,`nota_comanda`,`vendedor_estacion`,`consecutivo_orden_restaurante`,`comensales`) values (1567092897,NULL,21105,'2019-08-29 10:34:57',NULL,1,-1,'-1','quick service',NULL,NULL,1,1),
            (1579116896,NULL,21105,'2020-01-15 14:34:56',NULL,1,-1,'-1','quick service',NULL,NULL,3,1),
            (1579116915,NULL,21105,'2020-01-15 14:35:15',NULL,1,-1,'-1','quick service',NULL,NULL,3,1),
            (1579116925,NULL,21105,'2020-01-15 14:35:25',NULL,1,-1,'-1','quick service',NULL,NULL,3,1),
            (1579116954,NULL,21105,'2020-01-15 14:35:54',NULL,1,-1,'-1','quick service',NULL,NULL,3,1),
            (1579122657,NULL,21105,'2020-01-15 16:10:57',NULL,1,-1,'-1','quick service',NULL,NULL,3,1),
            (1579122740,NULL,21105,'2020-01-15 16:12:20',NULL,1,-1,'-1','quick service',NULL,NULL,4,1),
            (1579127763,NULL,21105,'2020-01-15 17:36:03',NULL,1,-1,'-1','quick service',NULL,NULL,5,1),
            (1579127784,NULL,21105,'2020-01-15 17:36:24',NULL,1,-1,'-1','quick service',NULL,NULL,6,1)";
                $this->connection->query($sql);

                $sql = "insert  into `movimiento_detalle`(`id_detalle`,`id_inventario`,`codigo_barra`,`cantidad`,`precio_compra`,`existencias`,`nombre`,`total_inventario`,`producto_id`,`precio_venta_p`,`precio_venta_actual`) values (1,1,'019',100,0,-2,'Jugo de Mango',0,19,NULL,NULL),(2,1,'016',100,0,-2,'Jugo de Mora',0,16,NULL,NULL)";
                $this->connection->query($sql);

                set_option('tipo_negocio', 'restaurante');
                $sql = "insert  into `orden_compra`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`,`nota`,`motivo`,`id_user_anulacion`,`fecha_anulacion`) values (1,1,NULL,'','2019-08-29 12:05:45',21105,-1,NULL,NULL,1,0,0,'Orden de Compra','2019-08-29','',NULL,NULL,NULL),(2,1,NULL,'','2019-08-29 12:06:09',21105,-1,NULL,NULL,1,1000,0,'Orden de Compra','2019-08-29','',NULL,NULL,NULL)";
                $this->connection->query($sql);

                $sql = "insert  into `producto`(`id`,`categoria_id`,`codigo`,`nombre`,`codigo_barra`,`precio_compra`,`precio_venta`,`stock_minimo`,`descripcion`,`activo`,`impuesto`,`fecha`,`imagen`,`thumbnail`,`material`,`ingredientes`,`combo`,`unidad_id`,`imagen1`,`imagen2`,`imagen3`,`imagen4`,`imagen5`,`id_proveedor`,`stock_maximo`,`fecha_vencimiento`,`ubicacion`,`ganancia`,`tienda`,`muestraexist`,`vendernegativo`,`woocommerce_id`,`id_tipo_producto`,`codigo_puntos_leal`) values
            (1,3,'001','Hamburguesa de Garbanzo',NULL,0,15000,0,'',1,1,NULL,'comidas1.jpg','thumbnail-comidas1.jpg',0,1,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (2,3,'002','Estofado de Lentejas',NULL,100,12000,0,'',1,1,NULL,'comidas2.jpg','thumbnail-comidas2.jpg',0,1,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (3,3,'003','Canelone de puerro',NULL,0,13000,0,'',1,1,NULL,'comidas3.jpg','thumbnail-comidas3.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (4,3,'004','Pollo a la Plancha',NULL,0,15000,0,'',1,1,NULL,'comidas4.jpg','thumbnail-comidas4.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (5,3,'005','Salmon a la Plancha',NULL,0,19000,0,'',1,1,NULL,'comidas5.jpg','thumbnail-comidas5.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (6,4,'006','Ensalada de Pollo',NULL,0,12000,0,'',1,1,NULL,'comidas6.jpg','thumbnail-comidas6.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (7,4,'007','Ensalada Verdura',NULL,0,15000,0,'',1,1,NULL,'comidas7.jpg','thumbnail-comidas7.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (8,4,'008','Ensalada de Aguacate',NULL,0,13000,0,'',1,1,NULL,'comidas8.jpg','thumbnail-comidas8.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (9,4,'009','Ensalada Verde',NULL,0,12000,0,'',1,1,NULL,'comidas9.jpg','thumbnail-comidas9.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (10,4,'010','Ensalada de Colores',NULL,0,12000,0,'',1,1,NULL,'comidas10.jpg','thumbnail-comidas10.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (13,5,'013','Batido Citrico',NULL,0,8000,0,'',1,1,NULL,'comidas15.jpg','thumbnail-comidas15.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (14,5,'014','Batido Naranja',NULL,0,8000,0,'',1,1,NULL,'comidas14.jpg','thumbnail-comidas14.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (15,5,'015','Batido Verde',NULL,0,8000,0,'',1,1,NULL,'comidas13.jpg','thumbnail-comidas13.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (16,6,'016','Jugo de Mora',NULL,0,5000,0,'',1,1,NULL,'comidas19.jpg','thumbnail-comidas19.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL),
            (17,6,'017','Jugo de Maracuya',NULL,0,5000,0,'',1,1,NULL,'comidas20.jpg','thumbnail-comidas20.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,1,NULL,NULL,NULL)";
                $this->connection->query($sql);
                $sql = "insert  into `producto_adicional`(`id`,`id_producto`,`id_adicional`,`cantidad`,`precio`) values (5,1,24,1,2000),(6,1,23,1,2000)";
                $this->connection->query($sql);
                $sql = "insert  into `producto_ingredientes`(`id`,`id_producto`,`id_ingrediente`,`cantidad`) values (1,2,21,20),(5,1,22,0)";
                $this->connection->query($sql);
                $sql = "insert  into `producto_modificacion`(`id`,`id_producto`,`nombre`) values (1,1,'Sin salsa'),(2,1,'Mas vegetales'),(3,1,'Sin aderezo')";
                $this->connection->query($sql);

                $sql = "insert  into `proformas`(`id_proforma`,`id_proveedor`,`descripcion`,`cantidad`,`valor`,`notas`,`fecha`,`id_impuesto`,`id_almacen`,`forma_pago`,`id_cuenta_dinero`,`fecha_crea_gasto`,`banco_asociado`,`subcategoria_asociada`,`movimiento_asociado`) values (1,1,'compras',1,45500,'','2019-09-06',0,1,'Efectivo',1,'2019-09-06 11:51:07',NULL,NULL,NULL)";
                $this->connection->query($sql);
                $sql = "insert  into `proveedores`(`id_proveedor`,`pais`,`provincia`,`nombre_comercial`,`razon_social`,`nif_cif`,`contacto`,`pagina_web`,`email`,`poblacion`,`direccion`,`cp`,`telefono`,`movil`,`fax`,`tipo_empresa`,`entidad_bancaria`,`numero_cuenta`,`observaciones`) values (1,'',NULL,'surti fruver','surty fruver','25625781',NULL,NULL,'webinar@vendty.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)";
                $this->connection->query($sql);

                $sql = "insert  into `stock_actual`(`id`,`almacen_id`,`producto_id`,`unidades`) values (1,1,1,0),(2,1,2,98),(3,1,3,-26),(4,1,4,-2),(5,1,5,91),(6,1,6,-21),(7,1,7,-42),(8,1,8,-21),(9,1,9,-20),(10,1,10,-13),(13,1,13,-10),(14,1,14,-7),(15,1,15,-7),(16,1,16,96),(17,1,17,-2)";
                $this->connection->query($sql);

                $data = array(
                    'wizard_tiponegocio' => 1,
                );
                $this->connection->update('usuario_almacen', $data);

                //Copiar las imágenes
                $email = $this->session->userdata('email');
                $sql = "SELECT db.base_dato FROM users u INNER JOIN db_config db ON u.db_config_id = db.id WHERE u.email LIKE '%$email%'";
                $queryDB = $this->db->query($sql)->row();

                $base_dato = $queryDB->base_dato;

                if (!(file_exists('./uploads/' . $base_dato . '/imagenes_productos'))) {
                    mkdir('./uploads/' . $base_dato . '/imagenes_productos');
                }

                $rutaorigen = './uploads/vendty2_db_21105_comi2019/imagenes_productos';
                $rutadestino = './uploads/' . $base_dato . '/imagenes_productos';
                $imagenes_productos = array(
                    'comidas1.jpg', 'thumbnail-comidas1.jpg',
                    'comidas2.jpg', 'thumbnail-comidas2.jpg',
                    'comidas3.jpg', 'thumbnail-comidas3.jpg',
                    'comidas4.jpg', 'thumbnail-comidas4.jpg',
                    'comidas5.jpg', 'thumbnail-comidas5.jpg',
                    'comidas6.jpg', 'thumbnail-comidas6.jpg',
                    'comidas7.jpg', 'thumbnail-comidas7.jpg',
                    'comidas8.jpg', 'thumbnail-comidas8.jpg',
                    'comidas9.jpg', 'thumbnail-comidas9.jpg',
                    'comidas10.jpg', 'thumbnail-comidas10.jpg',
                    'comidas15.jpg', 'thumbnail-comidas15.jpg',
                    'comidas14.jpg', 'thumbnail-comidas14.jpg',
                    'comidas13.jpg', 'thumbnail-comidas13.jpg',
                    'comidas19.jpg', 'thumbnail-comidas19.jpg',
                    'comidas20.jpg', 'thumbnail-comidas20.jpg',
                );

                foreach ($imagenes_productos as $imagen) {
                    $ruta_origen = $rutaorigen . "/" . $imagen;
                    $ruta_destino = $rutadestino . "/" . $imagen;
                    if (!copy($ruta_origen, $ruta_destino)) {
                        echo "Error al copiar $ruta_origen...\n";
                    } else {
                        echo "<br> supuestamente si copio";
                    }
                }

                $this->mi_empresa->update_data_empresa(array(
                    'quick_service' => 'si',
                ));

                //-------------------------------------------------------------------------------
                // ABRIR CAJA AUTOMATICAMENTE CON $100.000

                $sql = "insert  into `cierres_caja`(`id`,`fecha`,`hora_apertura`,`hora_cierre`,`id_Usuario`,`id_Caja`,`id_Almacen`,`total_egresos`,`total_ingresos`,`total_cierre`,`arqueo`,`fecha_fin_cierre`,`fecha_cierre`,`consecutivo`) values (1,CURRENT_TIMESTAMP,'09:35:02','00:00:00'," . $this->session->userdata('user_id') . ",1,1,'','','',NULL,NULL,NULL,NULL);";
                $this->connection->query($sql);

                $sql = "insert into `movimientos_cierre_caja`(`id`,`Id_cierre`,`hora_movimiento`,`id_usuario`,`tipo_movimiento`,`valor`,`forma_pago`,`numero`,`id_mov_tip`,`tabla_mov`) values (1,1,'09:35:02'," . $this->session->userdata('user_id') . ",'entrada_apertura','100000','efectivo','',0,'apertura');";
                $this->connection->query($sql);

                //-------------------------------------------------------------------------------

                return true;
                break;
            case 'retail':

                $sql = "insert  into categoria(id,codigo,nombre,imagen,padre,activo) values (1,0,'COMPUTADORES','vendty2_db_11423_gener2017/categorias_productos/coding.jpg',NULL,1),(2,0,'SMARTPHONES Y TABLETS','vendty2_db_11423_gener2017/categorias_productos/smartphone.jpg',NULL,1),(3,0,'CÁMARAS FOTOGRÁFICAS','vendty2_db_11423_gener2017/categorias_productos/photo-camera.jpg',NULL,1),(4,0,'TV','vendty2_db_11423_gener2017/categorias_productos/television.jpg',NULL,1)";
                $this->connection->query($sql);

                $sql = "insert into cuentas_dinero(id,nombre,tipo_cuenta,numero,banco,tipo_bancaria,id_almacen) values (1,'caja menor','Caja Menor','','','Ahorro',1),(2,'Caja Bancos','Banco','','','',1)";
                $this->connection->query($sql);

                $sql = "insert  into cierres_caja(`id`,`fecha`,`hora_apertura`,`hora_cierre`,`id_Usuario`,`id_Caja`,`id_Almacen`,`total_egresos`,`total_ingresos`,`total_cierre`) values (1,'2017-11-08','11:01:02','00:00:00',144,1,1,'','',''),(2,'2017-11-09','09:17:42','00:00:00',144,1,1,'','','');
             ";
                $this->connection->query($sql);
                $sql = "delete from factura_espera";
                $this->connection->query($sql);
                $sql = "insert  into factura_espera(id,almacen_id,forma_pago_id,factura,no_factura,fecha,usuario_id,cliente_id,vendedor,cambio,activo,total_venta,estado,tipo_factura,fecha_vencimiento,nota,sobrecosto) values (-1,0,NULL,'Venta No ',NULL,'2015-06-24 00:32:06',0,-1,NULL,NULL,1,56010,0,'estandar','2015-06-24 00:32:06','',0)";
                $this->connection->query($sql);

                $sql = "insert  into orden_compra(id,almacen_id,forma_pago_id,factura,fecha,usuario_id,cliente_id,vendedor,cambio,activo,total_venta,estado,tipo_factura,fecha_vencimiento,nota) values (1,1,NULL,'','2017-10-24 12:11:07',1129,-1,NULL,NULL,1,10000000,0,'Orden de Compra','2017-10-24','')";
                $this->connection->query($sql);
                $sql = "insert  into detalle_orden_compra(id,venta_id,codigo_producto,nombre_producto,descripcion_producto,unidades,precio_venta,descuento,impuesto,impuesto_id,linea,margen_utilidad,activo,id_unidad,producto_id) values (1,1,'1','Carne de res','',100000,100,0,0,NULL,NULL,0,1,1,35)";
                $this->connection->query($sql);
                //$sql = "insert  into permiso_rol(id_permiso_rol,id_permiso,id_rol) values (7,3,2),(8,11,2),(9,11,3),(10,12,3),(11,27,3),(12,29,3),(13,2,4),(14,3,4),(15,4,4),(16,13,4),(17,16,4),(18,3,10),(19,10,10),(20,32,10),(21,89,10),(22,90,10),(23,1005,10),(24,1010,10),(28,1,11),(29,3,11),(30,4,11),(31,10,11),(32,12,11),(33,14,11),(34,1,12),(35,3,12),(36,4,12),(37,10,12),(38,12,12),(39,14,12),(129,1,13),(130,2,13),(131,3,13),(132,4,13),(133,5,13),(134,10,13),(135,11,13),(136,12,13),(137,13,13),(138,14,13),(139,15,13),(140,16,13),(141,17,13),(142,27,13),(143,28,13),(144,29,13),(145,30,13),(146,32,13),(147,33,13),(148,34,13),(149,35,13),(150,45,13),(151,57,13),(152,58,13),(153,59,13),(154,60,13),(155,61,13),(156,62,13),(157,63,13),(158,64,13),(159,65,13),(160,66,13),(161,67,13),(162,68,13),(163,69,13),(164,70,13),(165,71,13),(166,73,13),(167,74,13),(168,75,13),(169,76,13),(170,77,13),(171,78,13),(172,79,13),(173,80,13),(174,81,13),(175,82,13),(176,84,13),(177,85,13),(178,86,13),(179,87,13),(180,88,13),(181,89,13),(182,90,13),(183,91,13),(184,92,13),(185,93,13),(186,94,13),(187,1000,13),(188,1001,13),(189,1002,13),(190,1009,13),(191,1010,13),(192,1011,13),(193,10,14),(194,11,14),(195,32,14),(196,34,14),(197,57,14),(198,1009,14),(199,1010,14),(200,2,1),(201,11,1),(202,27,1),(203,28,1),(204,32,1),(205,57,1),(206,58,1),(207,59,1),(208,60,1),(209,62,1),(210,67,1),(211,70,1),(212,71,1),(213,85,1),(214,86,1),(215,87,1),(216,89,1),(217,90,1),(218,91,1),(219,92,1),(220,1009,1),(221,1010,1),(222,3,15)";
                //$this->connection->query($sql);

                /*$sql = "delete from opciones";
                $this->connection->query($sql);
                $sql = "insert  into `opciones`(`id`,`nombre_opcion`,`valor_opcion`) values (1,'nombre_empresa',''),(2,'resolucion_factura',''),(3,'logotipo_empresa',''),(4,'contacto_empresa',''),(5,'email_empresa',''),(6,'direccion_empresa',''),(7,'telefono_empresa',''),(8,'fax_empresa',''),(9,'web_empresa',''),(17,'moneda_empresa','USD'),(20,'plantilla_empresa','default'),(21,'paypal_email',''),(22,'cabecera_factura',''),(23,'terminos_condiciones',''),(24,'prefijo_presupuesto','P'),(25,'numero_presupuesto','1'),(26,'numero_factura','1'),(27,'prefijo_factura','F'),(28,'last_numero_factura','1'),(29,'last_numero_presupuesto','1'),(30,'nit',''),(31,'titulo_venta',''),(32,'sistema','Pos'),(74,'costo_promedio','1'),(75,'plantilla_cotizacion','Estandar'),(76,'tipo_factura','estandar'),(35,'numero','no'),(36,'sobrecosto','no'),(37,'multiples_formas_pago','si'),(38,'vendedor_impresion','1'),(39,'valor_caja','si'),(40,'documento','NIT'),(41,'filtro_ciudad','no'),(42,'comanda','no'),(43,'etienda','si'),(48,'offline','backup'),(63,'orden_compra_precio','0'),(74,'resolucion_factura_estado','si'),(55,'cierre_automatico','1'),(62,'plantilla_general','tirilla'),(77,'punto_valor','0'),(78,'por_compras_puntos_acumulados','0'),(81,'numero_devolucion','1'),(82,'prefijo_devolucion','NC'),(68, 'precio_almacen','0')";
                $this->connection->query($sql);
                 */

                $sql = "insert  into `movimientos_cierre_caja`(`id`,`Id_cierre`,`hora_movimiento`,`id_usuario`,`tipo_movimiento`,`valor`,`forma_pago`,`numero`,`id_mov_tip`,`tabla_mov`) values (1,1,'11:01:02',144,'entrada_apertura','1','efectivo','',0,''),(2,1,'15:24:07',144,'entrada_venta','210000','efectivo','No2',20,'venta'),(3,1,'17:43:46',144,'entrada_venta','1603449','efectivo','No3',21,'venta')";
                $this->connection->query($sql);
                $sql = "insert into `producto`(`id`,`categoria_id`,`codigo`,`nombre`,`codigo_barra`,`precio_compra`,`precio_venta`,`vendernegativo`,`stock_minimo`,`descripcion`,`activo`,`impuesto`,`fecha`,`imagen`,`material`,`ingredientes`,`combo`,`unidad_id`,`imagen1`,`imagen2`,`imagen3`,`imagen4`,`imagen5`,`id_proveedor`,`stock_maximo`,`fecha_vencimiento`,`ubicacion`,`ganancia`,`tienda`,`muestraexist`,`id_tipo_producto`) values
                (1,2,'1545','Portátil Dell Inspiron',NULL,850000,1200000,1,0,'',1,1,NULL,'vendty2_db_11423_gener2017/imagenes_productos/81LcrgMpIcL._SL1500__4.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,NULL),
                (2,2,'86945','Samsung Galaxy Tab 2',NULL,500000,800000,1,0,'',1,1,NULL,'vendty2_db_11423_gener2017/imagenes_productos/81SXbcDcuxL._SL1500__2.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,NULL),
                (3,2,'66894','Cámara Sony semiprofesional',NULL,420000,630000,1,0,'',1,1,NULL,'vendty2_db_11423_gener2017/imagenes_productos/3901866_11.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,NULL)";
                $this->connection->query($sql);
                $sql = "insert  into `stock_actual`(`id`,`almacen_id`,`producto_id`,`unidades`) values (69,1,1,-47),(70,1,2,20),(71,1,3,1)";
                $this->connection->query($sql);
                $sql = "insert  into `stock_diario`(`id`,`producto_id`,`almacen_id`,`fecha`,`razon`,`cod_documento`,`unidad`,`precio`,`usuario`) values (328,1,1,'2017-11-09','E',NULL,20,1200000,144),(329,2,1,'2017-11-09','E',NULL,20,800000,144),(330,3,1,'2017-11-09','E',NULL,2,630000,144),(331,1,1,'2017-11-09','S','No5',-2,0,144),(332,3,1,'2017-11-09','S','No6',-1,0,144),(333,1,1,'2017-11-09','S','No7',-19,0,144),(334,1,1,'2017-11-09','S','No8',-46,0,144)";
                $this->connection->query($sql);
                $sql = "insert  into `venta`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`vendedor_2`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`,`nota`,`promocion`,`porcentaje_descuento_general`) values
                (23,1,NULL,'No5','" . date('Y-m-d H:i:s') . "',144,-1,NULL,NULL,NULL,1,2400000,0,'estandar','" . date('Y-m-d H:i:s') . "','','',0),
                (24,1,NULL,'No6','" . date('Y-m-d H:i:s') . "',144,-1,NULL,NULL,NULL,1,630000,0,'estandar','" . date('Y-m-d H:i:s') . "','','',0),
                (25,1,NULL,'No7','" . date('Y-m-d H:i:s') . "',144,-1,NULL,NULL,NULL,1,22800000,0,'estandar','" . date('Y-m-d H:i:s') . "','','',0),
                (26,1,NULL,'No8','" . date('Y-m-d H:i:s') . "',144,-1,NULL,NULL,NULL,1,55200000,0,'estandar','" . date('Y-m-d H:i:s') . "','','',0)";
                $this->connection->query($sql);
                $sql = "insert  into `detalle_venta`(`id`,`venta_id`,`codigo_producto`,`nombre_producto`,`descripcion_producto`,`unidades`,`precio_venta`,`descuento`,`impuesto`,`linea`,`margen_utilidad`,`activo`,`producto_id`,`status`,`porcentaje_descuento`) values
                (91,23,'1545','Portátil Dell Inspiron','0',2,1200000,0,0,NULL,-14600000,1,1,NULL,0),
                (92,24,'66894','Cámara Sony semiprofesional','0',1,630000,0,0,NULL,210000,1,3,NULL,0),
                (93,25,'1545','Portátil Dell Inspiron','0',19,1200000,0,0,NULL,6650000,1,1,NULL,0),
                (94,26,'1545','Portátil Dell Inspiron','0',46,1200000,0,0,NULL,16100000,1,1,NULL,0)";
                $this->connection->query($sql);

                $sql = "insert  into `ventas_pago`(`id_pago`,`id_venta`,`forma_pago`,`valor_entregado`,`cambio`) values
                 (23,23,'efectivo',2400000,0),
                 (24,24,'efectivo',630000,0),
                 (25,25,'efectivo',22800000,0),
                 (26,26,'efectivo',55200000,0)";
                $this->connection->query($sql);

                $data = array(
                    'wizard_tiponegocio' => 1,
                );
                $this->connection->update('usuario_almacen', $data);
                return true;
                break;
            case 'moda':
                /*$sql = "insert  into almacen(id,resolucion_factura,nit,nombre,direccion,meta_diaria,prefijo,consecutivo,activo,telefono,ciudad,razon_social) values (1,NULL,NULL,'General',NULL,NULL,'G',2,1,'','',NULL)";
                $this->connection->query($sql);
                $sql = "insert  into atributos(id,nombre) values (3,'Color'),(4,'Talla')";
                $this->connection->query($sql);*/
                $sql = "insert  into atributos_detalle(id,valor,descripcion,atributo_id) values (1,'S',NULL,4),(2,'M',NULL,4),(3,'L',NULL,4),(4,'XL',NULL,4),(5,'8',NULL,4),(6,'10',NULL,4),(7,'12',NULL,4),(8,'14',NULL,4),(9,'16',NULL,4),(10,'18',NULL,4),(11,'28',NULL,4),(12,'30',NULL,4),(13,'32',NULL,4),(14,'34',NULL,4),(15,'36',NULL,4),(16,'38',NULL,4),(17,'40',NULL,4),(18,'AZUL',NULL,3),(19,'BLANCO',NULL,3),(20,'ROSADO',NULL,3),(21,'NEGRO',NULL,3),(22,'GRIS',NULL,3),(23,'ROJO',NULL,3),(24,'BEIGE',NULL,3)";
                $this->connection->query($sql);
                $sql = "insert  into atributos_posee_categorias(categoria_id,atributo_id) values (3,3),(3,4),(4,3),(4,4)";
                $this->connection->query($sql);
                $sql = "insert  into atributos_productos(id,referencia,codigo_interno,nombre_producto,codigo_barras,id_categoria,nombre_categoria,id_atributo,nombre_atributo,id_clasificacion,nombre_clasificacion) values (1,'BL205',1,'BLUSA','7707250',3,'Femenino',3,'Color',18,'AZUL'),(2,'BL205',1,'BLUSA','7707250',3,'Femenino',4,'Talla',1,'S'),(3,'BL205',2,'BLUSA','7707251',3,'Femenino',3,'Color',18,'AZUL'),(4,'BL205',2,'BLUSA','7707251',3,'Femenino',4,'Talla',2,'M'),(5,'BL205',3,'BLUSA','7707252',3,'Femenino',3,'Color',18,'AZUL'),(6,'BL205',3,'BLUSA','7707252',3,'Femenino',4,'Talla',3,'L'),(7,'BL205',4,'BLUSA','7707253',3,'Femenino',3,'Color',18,'AZUL'),(8,'BL205',4,'BLUSA','7707253',3,'Femenino',4,'Talla',4,'XL'),(9,'BL206',5,'BLUSA','7707254',3,'Femenino',3,'Color',19,'BLANCO'),(10,'BL206',5,'BLUSA','7707254',3,'Femenino',4,'Talla',1,'S'),(11,'BL206',6,'BLUSA','7707255',3,'Femenino',3,'Color',19,'BLANCO'),(12,'BL206',6,'BLUSA','7707255',3,'Femenino',4,'Talla',2,'M'),(13,'BL206',7,'BLUSA','7707256',3,'Femenino',3,'Color',19,'BLANCO'),(14,'BL206',7,'BLUSA','7707256',3,'Femenino',4,'Talla',3,'L'),(15,'BL207',8,'BLUSA','7707258',3,'Femenino',3,'Color',20,'ROSADO'),(16,'BL207',8,'BLUSA','7707258',3,'Femenino',4,'Talla',1,'S'),(17,'BL207',9,'BLUSA','7707259',3,'Femenino',3,'Color',20,'ROSADO'),(18,'BL207',9,'BLUSA','7707259',3,'Femenino',4,'Talla',2,'M'),(19,'BL207',10,'BLUSA','7707260',3,'Femenino',3,'Color',20,'ROSADO'),(20,'BL207',10,'BLUSA','7707260',3,'Femenino',4,'Talla',3,'L'),(21,'BL207',11,'BLUSA','7707261',3,'Femenino',3,'Color',20,'ROSADO'),(22,'BL207',11,'BLUSA','7707261',3,'Femenino',4,'Talla',4,'XL'),(23,'PL360',12,'PANTALON','7707262',3,'Femenino',3,'Color',18,'AZUL'),(24,'PL360',12,'PANTALON','7707262',3,'Femenino',4,'Talla',5,'8'),(25,'PL360',13,'PANTALON','7707263',3,'Femenino',3,'Color',18,'AZUL'),(26,'PL360',13,'PANTALON','7707263',3,'Femenino',4,'Talla',6,'10'),(27,'PL360',14,'PANTALON','7707264',3,'Femenino',3,'Color',18,'AZUL'),(28,'PL360',14,'PANTALON','7707264',3,'Femenino',4,'Talla',7,'12'),(29,'PL360',15,'PANTALON','7707265',3,'Femenino',3,'Color',18,'AZUL'),(30,'PL360',15,'PANTALON','7707265',3,'Femenino',4,'Talla',8,'14'),(31,'PL360',16,'PANTALON','7707266',3,'Femenino',3,'Color',18,'AZUL'),(32,'PL360',16,'PANTALON','7707266',3,'Femenino',4,'Talla',9,'16'),(33,'PL360',17,'PANTALON','7707267',3,'Femenino',3,'Color',18,'AZUL'),(34,'PL360',17,'PANTALON','7707267',3,'Femenino',4,'Talla',10,'18'),(35,'PL361',18,'PANTALON','7707268',3,'Femenino',3,'Color',23,'ROJO'),(36,'PL361',18,'PANTALON','7707268',3,'Femenino',4,'Talla',5,'8'),(37,'PL361',19,'PANTALON','7707269',3,'Femenino',3,'Color',23,'ROJO'),(38,'PL361',19,'PANTALON','7707269',3,'Femenino',4,'Talla',6,'10'),(39,'PL361',20,'PANTALON','7707270',3,'Femenino',3,'Color',23,'ROJO'),(40,'PL361',20,'PANTALON','7707270',3,'Femenino',4,'Talla',7,'12'),(41,'PL361',21,'PANTALON','7707271',3,'Femenino',3,'Color',23,'ROJO'),(42,'PL361',21,'PANTALON','7707271',3,'Femenino',4,'Talla',8,'14'),(43,'PL361',22,'PANTALON','7707272',3,'Femenino',3,'Color',23,'ROJO'),(44,'PL361',22,'PANTALON','7707272',3,'Femenino',4,'Talla',9,'16'),(45,'PL361',23,'PANTALON','7707273',3,'Femenino',3,'Color',23,'ROJO'),(46,'PL361',23,'PANTALON','7707273',3,'Femenino',4,'Talla',10,'18'),(47,'CA300',24,'CAMISA','7707274',4,'Masculino',3,'Color',18,'AZUL'),(48,'CA300',24,'CAMISA','7707274',4,'Masculino',4,'Talla',1,'S'),(49,'CA300',25,'CAMISA','7707275',4,'Masculino',3,'Color',18,'AZUL'),(50,'CA300',25,'CAMISA','7707275',4,'Masculino',4,'Talla',2,'M'),(51,'CA300',26,'CAMISA','7707276',4,'Masculino',3,'Color',18,'AZUL'),(52,'CA300',26,'CAMISA','7707276',4,'Masculino',4,'Talla',3,'L'),(53,'CA300',27,'CAMISA','7707277',4,'Masculino',3,'Color',18,'AZUL'),(54,'CA300',27,'CAMISA','7707277',4,'Masculino',4,'Talla',4,'XL'),(55,'CA301',28,'CAMISA','7707278',4,'Masculino',3,'Color',22,'GRIS'),(56,'CA301',28,'CAMISA','7707278',4,'Masculino',4,'Talla',1,'S'),(57,'CA301',29,'CAMISA','7707279',4,'Masculino',3,'Color',22,'GRIS'),(58,'CA301',29,'CAMISA','7707279',4,'Masculino',4,'Talla',2,'M'),(59,'CA301',30,'CAMISA','7707280',4,'Masculino',3,'Color',22,'GRIS'),(60,'CA301',30,'CAMISA','7707280',4,'Masculino',4,'Talla',3,'L'),(61,'CA301',31,'CAMISA','7707281',4,'Masculino',3,'Color',22,'GRIS'),(62,'CA301',31,'CAMISA','7707281',4,'Masculino',4,'Talla',4,'XL'),(63,'PT200',32,'PANTALON','7707282',4,'Masculino',3,'Color',21,'NEGRO'),(64,'PT200',32,'PANTALON','7707282',4,'Masculino',4,'Talla',11,'28'),(65,'PT200',33,'PANTALON','7707283',4,'Masculino',3,'Color',21,'NEGRO'),(66,'PT200',33,'PANTALON','7707283',4,'Masculino',4,'Talla',12,'30'),(67,'PT200',34,'PANTALON','7707284',4,'Masculino',3,'Color',21,'NEGRO'),(68,'PT200',34,'PANTALON','7707284',4,'Masculino',4,'Talla',13,'32'),(69,'PT200',35,'PANTALON','7707285',4,'Masculino',3,'Color',21,'NEGRO'),(70,'PT200',35,'PANTALON','7707285',4,'Masculino',4,'Talla',14,'34'),(71,'PT200',36,'PANTALON','7707286',4,'Masculino',3,'Color',21,'NEGRO'),(72,'PT200',36,'PANTALON','7707286',4,'Masculino',4,'Talla',15,'36'),(73,'PT200',37,'PANTALON','7707287',4,'Masculino',3,'Color',21,'NEGRO'),(74,'PT200',37,'PANTALON','7707287',4,'Masculino',4,'Talla',16,'38'),(75,'PT200',38,'PANTALON','7707288',4,'Masculino',3,'Color',21,'NEGRO'),(76,'PT200',38,'PANTALON','7707288',4,'Masculino',4,'Talla',17,'40')";
                $this->connection->query($sql);
                $sql = "insert  into categoria(id,codigo,nombre,imagen,padre,activo,es_menu_principal_tienda,tienda) values
                (3,1,'Femenino','vendty2_db_11091_modav2017/categorias_productos/20090831175816012118-armand-basi-vestidos.jpg',NULL,1,0,1),
                (4,2,'Masculino','vendty2_db_11091_modav2017/categorias_productos/0_66eb0_7ecaaec9_xxl.jpg',NULL,1,0,1),
                (5,3,'Accesorios','vendty2_db_11091_modav2017/categorias_productos/Screen-Shot-2013-05-20-at-10_28_31-AM.jpg',NULL,1,0,1),
                (6,0,'GiftCard','giftCard.png',NULL,1,0,0)";
                $this->connection->query($sql);

                $sql = "insert into cuentas_dinero(id,nombre,tipo_cuenta,numero,banco,tipo_bancaria,id_almacen) values (1,'caja menor','Caja Menor','','','Ahorro',1),(2,'Caja Bancos','Banco','','','',1)";
                $this->connection->query($sql);
                /*$sql = "insert  into cierres_caja(id,fecha,hora_apertura,hora_cierre,id_Usuario,id_Caja,id_Almacen,total_egresos,total_ingresos,total_cierre) values (1,'2017-10-23','11:40:15','00:00:00',10719,0,1,'','','')";
                $this->connection->query($sql);
                $sql = "insert  into clientes(id_cliente,pais,provincia,nombre_comercial,razon_social,tipo_identificacion,nif_cif,contacto,pagina_web,email,poblacion,direccion,cp,telefono,movil,fax,tipo_empresa,entidad_bancaria,numero_cuenta,observaciones,grupo_clientes_id) values (-1,'Colombia',NULL,'general',NULL,'CC','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1)";
                $this->connection->query($sql);
                $sql = "insert  into comanda_notificacion_servidor(notificacion) values ('')";
                $this->connection->query($sql);*/
                $sql = "insert  into detalle_venta(id,venta_id,codigo_producto,nombre_producto,descripcion_producto,unidades,precio_venta,descuento,impuesto,linea,margen_utilidad,activo,producto_id,status,porcentaje_descuento) values (1,1,'7707253','BLUSA/AZUL/XL/','0',1,35000,0,0,NULL,20000,1,4,NULL,0),(2,1,'7707252','BLUSA/AZUL/L/','0',1,35000,0,0,NULL,20000,1,3,NULL,0),(3,1,'7707251','BLUSA/AZUL/M/','0',1,35000,0,0,NULL,20000,1,2,NULL,0)";
                $this->connection->query($sql);
                $sql = "delete from factura_espera";
                $this->connection->query($sql);
                $sql = "insert  into factura_espera(id,almacen_id,forma_pago_id,factura,no_factura,fecha,usuario_id,cliente_id,vendedor,cambio,activo,total_venta,estado,tipo_factura,fecha_vencimiento,nota,sobrecosto) values (-1,0,NULL,'Venta No ',NULL,'2015-06-24 00:32:06',0,-1,NULL,NULL,1,56010,0,'estandar','2015-06-24 00:32:06','',0)";
                $this->connection->query($sql);

                /*$sql = "insert  into forma_pago(id,codigo,nombre,activo,eliminar,tipo) values (1,'efectivo','Efectivo',1,0,''),(2,'tarjeta_credito','Tarjeta de crédito',1,0,'Datafono'),(3,'tarjeta_debito','Tarjeta debito',1,0,'Datafono'),(4,'Credito','Crédito',1,0,''),(5,'Saldo_a_Favor','Saldo a Favor',1,0,''),(6,'Visa_debito','Visa débito',1,0,''),(7,'Visa_credito','Visa crédito',1,0,''),(8,'MasterCard_debito','MasterCard débito',1,0,''),(9,'American_Express','American Express',1,0,'Datafono'),(10,'MasterCard Credito','MasterCard Crédito',1,0,''),(11,'Gift_Card','Gift Card',1,0,''),(12,'MercadoPago','MercadoPago',1,0,''),(13,'Linio','Linio',1,0,''),(14,'Bancolombia','Bancolombia',1,0,''),(15,'Efecty','Efecty',1,0,''),(16,'Interrapidisimo','Interrapidisimo',1,0,''),(17,'Baloto','Baloto',1,0,''),(18,'Sodexo','Sodexo',1,0,''),(19,'Puntos','Puntos',1,0,''),(20,'Maestro_Debito','Maestro Débito',1,0,'Datafono'),(21,'Tarjeta_Codensa','Tarjeta Codensa',1,0,'Datafono'),(22,'Diners_Club','Diners Club',1,0,'Datafono'),(23,'PayU','PayU',1,0,''),(24,'nota_credito','Nota Credito',1,0,'')";
                $this->connection->query($sql);
                $sql = "insert  into grupo_clientes(id,nombre) values (1,'sin grupo')";
                $this->connection->query($sql);
                $sql = "insert  into impuesto(id_impuesto,nombre_impuesto,porciento) values (1,'Sin Impuesto',0)";
                $this->connection->query($sql);
                $sql = "insert  into movimientos_cierre_caja(id,Id_cierre,hora_movimiento,id_usuario,tipo_movimiento,valor,forma_pago,numero,id_mov_tip,tabla_mov) values (1,1,'11:40:15',10719,'entrada_apertura','3000','efectivo','',0,''),(2,1,'11:40:42',10719,'entrada_venta','105000','efectivo','G2',1,'venta')";
                $this->connection->query($sql);*/
                /*$sql = "delete from opciones";
                $this->connection->query($sql);

                $sql = "insert  into opciones(id,nombre_opcion,valor_opcion) values (1,'nombre_empresa','Boutique Fashion'),(2,'resolucion_factura',''),(3,'logotipo_empresa','IMG_6735.jpg'),(4,'contacto_empresa','Juan Garzon'),(5,'email_empresa','modavendty@vendty.com'),(6,'direccion_empresa','cra53a#25-25'),(7,'telefono_empresa','3115478966'),(8,'fax_empresa',''),(9,'web_empresa',''),(17,'moneda_empresa','COP'),(20,'plantilla_empresa','ticket_atributos_nuevo'),(21,'paypal_email','0'),(22,'cabecera_factura',''),(23,'terminos_condiciones',''),(24,'prefijo_presupuesto','P'),(25,'numero_presupuesto','1'),(26,'numero_factura','1'),(27,'prefijo_factura','F'),(28,'last_numero_factura','1'),(29,'last_numero_presupuesto','1'),(30,'nit','9008756321-8'),(31,'titulo_venta','Factura de venta'),(32,'sistema','Pos'),(33,'plantilla_cotizacion','Estandar'),(34,'tipo_factura','estandar'),(35,'numero','no'),(36,'sobrecosto','no'),(37,'multiples_formas_pago','si'),(38,'vendedor_impresion','1'),(39,'valor_caja','si'),(40,'documento','NIT'),(41,'filtro_ciudad','no'),(42,'comanda','no'),(43,'etienda','no'),(44,'punto_valor','0'),(45,'por_compras_puntos_acumulados','0'),(46,'ui_version','v2'),(47,'offline','backup'),(48,'plantilla_orden_compra','Estandar'),(49,'auto_factura','estandar'),(50,'auto_pago','estandar'),(51,'clientes_cartera','0'),(52,'redondear_precios','0'),(53,'sobrecosto_todos','0'),(54,'precio_almacen','0'),(55,'cierre_automatico','1'),(56,'plantilla_general','tirilla'),(57,'enviar_factura','no'),(58,'facturar_mesas','no'),(59,'num_exterior',''),(60,'num_interior',''),(61,'colonia',''),(62,'localidad',''),(63,'estado',''),(64,'municipio',''),(65,'codigo_postal',''),(66,'decimales_moneda','0'),(67,'tipo_separador_decimales',','),(68,'tipo_separador_miles','.'),(69,'orden_compra_precio','0'),(70,'costo_promedio','1'),(71,'pais','1'),(72,'zona_horaria','America/Bogota'),(73,'simbolo',''),(74,'resolucion_factura_estado','si'),(75,'multiples_vendedores','0'),(76,'tipo_moneda','COP'),(77,'comanda_push','1'),(78,'tipo_negocio','moda'),(79,'publicidad_vendty','1'),(80,'plan_separe','no'),(81,'numero_factura_fin',''),(82,'numero_devolucion','1'),(83,'prefijo_devolucion','NC'),(84,'atributo','si'),(85,'orden_compra_precio','0'),(147,'impresion_rapida','no'),(159,'puntos_correo_bienvenida',0)";
                $this->connection->query($sql);
                 */
                set_option('tipo_negocio', 'moda');
                $sql = "insert  into producto(id,categoria_id,codigo,nombre,codigo_barra,precio_compra,precio_venta,stock_minimo,descripcion,activo,impuesto,fecha,imagen,material,ingredientes,combo,unidad_id,imagen1,imagen2,imagen3,imagen4,imagen5,id_proveedor,stock_maximo,fecha_vencimiento,ubicacion,ganancia,tienda,muestraexist,id_tipo_producto) values
                (1,3,'7707250','BLUSA/AZUL/S/','1',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (2,3,'7707251','BLUSA/AZUL/M/','2',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusazul1.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (3,3,'7707252','BLUSA/AZUL/L/','3',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusazul2.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (4,3,'7707253','BLUSA/AZUL/XL/','4',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusazul3.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (5,3,'7707254','BLUSA/BLANCO/S/','5',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusablanco1.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (6,3,'7707255','BLUSA/BLANCO/M/','6',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusablanco.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (7,3,'7707256','BLUSA/BLANCO/L/','7',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusablanco2.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (8,3,'7707258','BLUSA/ROSADO/S/','8',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusarosa.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (9,3,'7707259','BLUSA/ROSADO/M/','9',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusarosa1.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (10,3,'7707260','BLUSA/ROSADO/L/','10',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusarosa2.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (11,3,'7707261','BLUSA/ROSADO/XL/','11',15000,35000,0,'BLUSA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/blusarosa3.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (12,3,'7707262','PANTALON/AZUL/8/','12',25000,50000,0,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (13,3,'7707263','PANTALON/AZUL/10/','13',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (14,3,'7707264','PANTALON/AZUL/12/','14',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (15,3,'7707265','PANTALON/AZUL/14/','15',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (16,3,'7707266','PANTALON/AZUL/16/','16',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (17,3,'7707267','PANTALON/AZUL/18/','17',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (18,3,'7707268','PANTALON/ROJO/8/','18',25000,50000,0,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantarojo.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (19,3,'7707269','PANTALON/ROJO/10/','19',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantarojo.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (20,3,'7707270','PANTALON/ROJO/12/','20',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantarojo.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (21,3,'7707271','PANTALON/ROJO/14/','21',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantarojo.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (22,3,'7707272','PANTALON/ROJO/16/','22',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantarojo.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (23,3,'7707273','PANTALON/ROJO/18/','23',25000,50000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantarojo.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (24,4,'7707274','CAMISA/AZUL/S/','24',35000,65000,0,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisaazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (25,4,'7707275','CAMISA/AZUL/M/','25',35000,65000,NULL,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisaazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (26,4,'7707276','CAMISA/AZUL/L/','26',35000,65000,NULL,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisaazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (27,4,'7707277','CAMISA/AZUL/XL/','27',35000,65000,NULL,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisaazul.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (28,4,'7707278','CAMISA/GRIS/S/','28',35000,65000,0,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisagris.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (29,4,'7707279','CAMISA/GRIS/M/','29',35000,65000,0,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisagris1.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (30,4,'7707280','CAMISA/GRIS/L/','30',35000,65000,NULL,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisagris.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (31,4,'7707281','CAMISA/GRIS/XL/','31',35000,65000,NULL,'CAMISA IMPORTADA',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/camisagris.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (32,4,'7707282','PANTALON/NEGRO/28/','32',40000,80000,0,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (33,4,'7707283','PANTALON/NEGRO/30/','33',40000,80000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (34,4,'7707284','PANTALON/NEGRO/32/','34',40000,80000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (35,4,'7707285','PANTALON/NEGRO/34/','35',40000,80000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (36,4,'7707286','PANTALON/NEGRO/36/','36',40000,80000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (37,4,'7707287','PANTALON/NEGRO/38/','37',40000,80000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (38,4,'7707288','PANTALON/NEGRO/40/','38',40000,80000,NULL,'PANTALON IMPORTADO',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/pantalonnegro.jpg',0,0,0,1,'dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg','dragDrop.jpg',0,0,'','',0,0,0,NULL),
                (39,5,'7707400','Reloj Mujer',NULL,15000,35000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/reloj_mujer.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (40,5,'7707401','Reloj Hombre',NULL,20000,40000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/reloj_hombre.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (41,5,'7707402','Collar ger',NULL,45000,65000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/collar1.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (42,5,'7707403','Collar ger II',NULL,45000,65000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/collar2.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (43,5,'7707404','Collar ger III',NULL,45000,65000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/collar3.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (44,5,'7707405','Collar ger IV',NULL,45000,65000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/collar4.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (45,5,'7707406','Collar ger V',NULL,45000,65000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/collar5.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (46,5,'7707407','Billetera cuero',NULL,25000,50000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/billetera.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL),
                (47,5,'7707408','Billetera sintetica',NULL,15000,30000,0,'',1,1,NULL,'vendty2_db_11091_modav2017/imagenes_productos/billetera2.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','',0,1,0,NULL)";
                $this->connection->query($sql);
                /*$sql = "insert  into producto_tipo(id,nombre) values (1,'unico'),(2,'compuesto'),(3,'combo')";
                $this->connection->query($sql);*/
                /*$sql = "insert  into unidades(id,nombre) values (1,'unidad'),(2,'gramo'),(3,'kilogramo'),(4,'libra'),(5,'litro'),(6,'mililitro'),(7,'onza')";
                $this->connection->query($sql);*/
                $sql = "insert  into stock_actual(id,almacen_id,producto_id,unidades) values (1,1,1,0),(2,1,2,-1),(3,1,3,-1),(4,1,4,-1),(5,1,5,0),(6,1,6,0),(7,1,7,0),(8,1,8,0),(9,1,9,0),(10,1,10,0),(11,1,11,0),(12,1,12,0),(13,1,13,0),(14,1,14,0),(15,1,15,0),(16,1,16,0),(17,1,17,0),(18,1,18,0),(19,1,19,0),(20,1,20,0),(21,1,21,0),(22,1,22,0),(23,1,23,0),(24,1,24,0),(25,1,25,0),(26,1,26,0),(27,1,27,0),(28,1,28,0),(29,1,29,0),(30,1,30,0),(31,1,31,0),(32,1,32,0),(33,1,33,0),(34,1,34,0),(35,1,35,0),(36,1,36,0),(37,1,37,0),(38,1,38,0),(39,1,39,0),(40,1,40,0),(41,1,41,0),(42,1,42,0),(43,1,43,0),(44,1,44,0),(45,1,45,0),(46,1,46,0),(47,1,47,0)";
                $this->connection->query($sql);
                /*$sql = "insert  into usuario_almacen(id,usuario_id,almacen_id,id_Caja) values (1,1150,1,1)";
                $this->connection->query($sql);*/

                $sql = "insert  into vendedor(id,nombre,cedula,email,telefono,comision,almacen) values (1,'Zuleima Beltran','12919191','zule.bel@hotmail.com','122818181',0,0),(2,'Bello','1234','','',0,0)";
                $this->connection->query($sql);

                $sql = "insert  into venta(id,almacen_id,forma_pago_id,factura,fecha,usuario_id,cliente_id,vendedor,vendedor_2,cambio,activo,total_venta,estado,tipo_factura,fecha_vencimiento,nota,promocion,porcentaje_descuento_general) values
                (1,1,NULL,'F101','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,18000,0,'estandar','2017-10-05 11:33:36','','',0),
                (2,1,NULL,'F102','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,18000,0,'estandar','2017-10-05 12:36:44','','',0),
                (3,1,NULL,'F103','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,80000,0,'estandar','2017-10-06 12:11:33','','',0),
                (4,1,NULL,'F104','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,19500,0,'estandar','2017-10-09 15:56:43','','',0),
                (5,1,NULL,'F105','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,36000,0,'estandar','2017-10-09 17:00:32','','',0),
                (6,1,NULL,'F106','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,31500,0,'estandar','2017-10-11 16:29:30','','',0),
                (7,1,NULL,'F107','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,243157,0,'estandar','2017-10-12 00:29:12','','',0),
                (8,1,NULL,'F108','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,15500,0,'estandar','2017-10-17 10:21:11','','',0),
                (9,1,NULL,'F109','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,13500,0,'estandar','2017-10-24 08:58:39','','',0),
                (10,1,NULL,'F110','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,17539,0,'estandar','2017-10-24 09:57:14','','',0),
                (11,1,NULL,'F111','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,10500,0,'estandar','2017-10-24 12:02:47','','',0),
                (12,1,NULL,'F112','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,81078,0,'estandar','2017-10-24 14:33:20','','',0),
                (13,1,NULL,'F113','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,208500,0,'estandar','2017-11-01 17:26:33','','',0),
                (14,1,NULL,'F114','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,40000,0,'estandar','2017-11-03 09:29:49','','',0),
                (15,1,NULL,'F115','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,43339,0,'estandar','2017-11-03 09:36:08','','',0),
                (16,1,NULL,'F116','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,20000,0,'estandar','2017-11-03 18:20:24','','',0),
                (17,1,NULL,'F117','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,55539,0,'estandar','2017-11-03 18:26:31','','',0),
                (18,1,NULL,'F118','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,55539,0,'estandar','2017-11-03 18:27:06','','',0),
                (19,1,NULL,'F119','" . date('Y-m-d H:i:s') . "',1129,-1,NULL,NULL,NULL,1,38500,0,'estandar','2017-11-03 18:28:07','','',0)";
                $this->connection->query($sql);

                $sql = "insert  into ventas_pago(id_pago,id_venta,forma_pago,valor_entregado,cambio) values
                (1,1,'efectivo',18000,0),
                (2,2,'efectivo',18000,0),
                (3,3,'efectivo',80000,0),
                (4,4,'efectivo',19500,0),
                (5,5,'efectivo',36000,0),
                (6,6,'efectivo',40000,8500),
                (7,7,'efectivo',243157,0),
                (8,8,'efectivo',15500,0),
                (9,9,'efectivo',20000,6500),
                (10,10,'efectivo',20000,2461),
                (11,11,'efectivo',10500,0),
                (12,12,'efectivo',81078,0),
                (13,13,'efectivo',208500,0),
                (14,14,'efectivo',40000,0),
                (15,15,'efectivo',43339,0),
                (16,16,'efectivo',20000,0),
                (17,17,'efectivo',55539,0),
                (18,18,'efectivo',55539,0),
                (19,19,'efectivo',38500,0)";
                $this->connection->query($sql);

                $data = array(
                    'wizard_tiponegocio' => 1,
                );
                $this->connection->update('usuario_almacen', $data);

                return true;
                break;
        }
    }

    public function load_state_wizard()
    {
        $this->connection->select("*");
        $this->connection->from("usuario_almacen");
        $this->connection->limit("1");
        $result = $this->connection->get();
        if (isset($result->result_array()[0]['wizard_tiponegocio'])) {
            return $result->result_array()[0]['wizard_tiponegocio'];
        } else {
            return null;
        }
    }

    public function get_type_licence()
    {
        $db = $this->session->userdata("db_config_id");
        $this->db->select("planes_id");
        $this->db->from("crm_licencias_empresa l");
        $this->db->join("crm_planes p", "l.planes_id = p.id");
        $this->db->where("id_db_config", $db);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() > 0):
            return $result->result()[0]->planes_id;
        else:
            return null;
        endif;
    }

    public function get_stores_avaibles()
    {
        $db = $this->session->userdata("db_config_id");
        $this->db->select("shopname");
        $this->db->from("tienda t");
        $result = $this->db->get();
        $stores = json_encode($result->result());
        return $stores;
        //print_r($shops ); die();
    }

    public function load_settings($data)
    {
        $business = array(
            'type_business' => $data['type_business'],
            'subcategory_business' => $data['subcategory_business'],
        );

        if (isset($data["propine"])) {
            $propine = $data['propine'];
        } else {
            $propine = "";
        }

        $invoice = array(
            'title' => $data['title'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'footer' => $data['footer'],
            'propine' => $propine,
        );
        $shop = array(
            'shop_name' => $data['shop_name'],
            'local_domain' => $data['local_domain'],
            'domain' => $data['domain'],
            'country' => $data['country'],
            'currency' => $data['currency'],
            'template' => $data['template'],
            'store_title' => $data['store_title'],
            'store_description' => $data['store_description'],
            'keywords' => $data['keywords'],
        );

        $error_business = $this->load_settings_business($business);
        $error_invoice = $this->load_settings_invoice($invoice);
        $error_shop = $this->load_settings_shop($shop);

        $response = array(
            'message' => 'success',
            'error_business' => $error_business,
            'error_invoice' => $error_invoice,
            'error_shop' => $error_shop,
        );

        $data = array(
            'wizard_tiponegocio' => 1,
        );
        $this->connection->update('usuario_almacen', $data);

        $this->save_step(4, $data['type_business']);
        return $response;
    }

    public function save_step($step, $type_business)
    {
        $sql = "SHOW COLUMNS FROM vendty2.primeros_pasos_usuarios LIKE 'step'";
        $exist = $this->db->query($sql)->result();
        if (count($exist) == 0) {
            $sql = "ALTER TABLE primeros_pasos_usuarios
            ADD COLUMN `step` int(11) NOT NULL DEFAULT 1 COMMENT 'paso maximo alcanzado al crear la cuenta' ";
            $this->db->query($sql);
        }

        $this->db->select("*");
        $this->db->from("primeros_pasos_usuarios");
        $this->db->where("id_usuario", $this->session->userdata('user_id'));
        $result = $this->db->get();

        if ($result->num_rows() > 0):
            $data_step = array(
                'step' => $step,
                'type_business' => $type_business,
            );
            $this->db->where('id_usuario', $this->session->userdata('user_id'));
            $this->db->update('primeros_pasos_usuarios', $data_step);
        else:
            $data_step = array(
                'id_paso' => '',
                'id_usuario' => $this->session->userdata('user_id'),
                'db_config' => $this->session->userdata('db_config_id'),
                'fecha_creacion' => date('y-m-d m:i:s'),
                'step' => $step,
                'type_business' => $type_business,
            );
            $this->db->insert('primeros_pasos_usuarios', $data_step);
        endif;

    }

    public function load_settings_business($business)
    {
        $errors = "";
        $this->empty_all();
        switch ($business["type_business"]) {
            case 'restaurant':
                $this->load_type_businness('restaurante');
                break;

            case 'retail':
                switch ($business["subcategory_business"]) {
                    case 'stationery':
                        $dump = $this->dump_stationery();
                        break;
                    case 'hardware_store':
                        $dump = $this->dump_hardware_store();
                        break;
                    case 'micro_market':
                        $dump = $this->dump_hardware_store();
                        break;
                    default:
                        $this->load_type_businness('retail');
                }
                if (isset($dump)):
                    $sql_array = explode(';', $dump);
                    $this->connection->trans_start();
                    foreach ($sql_array as $query) {
                        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0");
                        $this->connection->query($query);
                        if ($this->connection->affected_rows() < 0):
                            $errors .= "error - " . $query . "\n";
                        endif;
                        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1");
                    }
                    $this->connection->trans_complete();
                endif;
                break;

            case 'fashion':
                $this->load_type_businness('moda');
                break;

            default:
                $this->load_type_businness('retail');
        }
        return $errors;
    }
    public function load_settings_invoice($invoice)
    {
        $errors = "";

        set_option('nombre_empresa', $invoice['title']);
        set_option('direccion_empresa', $invoice['address']);
        set_option('telefono_empresa', $invoice['phone']);
        set_option('terminos_condiciones', $invoice['footer']);
        set_option('sobrecosto', $invoice['propine']);

        /*$data = array(
        array(
        'valor_opcion' => $invoice['title'],
        'nombre_opcion' => 'nombre_empresa'
        ),
        array(
        'valor_opcion' => $invoice['address'],
        'nombre_opcion' => 'direccion_empresa'
        ),
        array(
        'valor_opcion' => $invoice['phone'],
        'nombre_opcion' => 'telefono_empresa'
        ),
        array(
        'valor_opcion' => $invoice['footer'],
        'nombre_opcion' => 'terminos_condiciones'
        )
        );*/

        /*$this->connection->update_batch('opciones',$data, "nombre_opcion");
        if($this->connection->affected_rows() < 0):
        $errors .= "error - ".$this->connection->last_query()."\n";
        endif;*/

        /*
        foreach($data as $string_val){
        $this->connection->update('opciones',$string_val, "nombre_opcion");
        if($this->connection->affected_rows() < 0):
        $errors .= "error - ".$this->connection->last_query()."\n";
        endif;
        }
         */

        return $errors;
    }

    public function load_settings_shop($shop)
    {
        $errors = "";

        if ($shop['shop_name'] != ''):
            $this->db->select("*");
            $this->db->from("tienda");
            $this->db->where("id_user", $this->session->userdata('user_id'));
            $this->db->limit(1);
            $result = $this->db->get();
            if ($result->num_rows() > 0):
                $data = array(
                    'shopname' => $shop['shop_name'],
                    'dominio' => $shop['domain'],
                    'layout' => $shop['template'],
                    'description' => 'tienda',
                    'activo' => 1,
                    'wizard' => 0,
                );
                $this->db->where('id_user', $this->session->userdata('user_id'));
                $this->db->update('tienda', $data);
                if ($this->db->affected_rows() < 0):
                    $errors .= "error - " . $this->db->last_query() . "\n";
                endif;
            else:
                $data = array(
                    'id_user' => $this->session->userdata('user_id'),
                    'id_db' => $this->session->userdata('db_config_id'),
                    'id_almacen' => 1,
                    'shopname' => $shop['shop_name'],
                    'layout' => $shop['template'],
                    'description' => 'tienda',
                    'activo' => 1,
                    'dominio' => $shop['domain'],
                    'wizard' => 0,
                );

                $this->db->insert('tienda', $data);
                if ($this->db->affected_rows() < 0):
                    $errors .= "error - " . $this->db->last_query() . "\n";
                endif;
            endif;

            $data = array(
                'valor_opcion' => $shop['currency'],
                'nombre_opcion' => 'moneda_empresa',
            );
            $this->connection->update('opciones', $data, 'nombre_opcion');
            if ($this->connection->affected_rows() < 0):
                $errors .= "error - " . $this->connection->last_query() . "\n";
            endif;
        endif;
        return $errors;
    }

    public function empty_all()
    {
        $query = $this->connection->query("SHOW TABLES");
        $name = $this->connection->database;
        $this->connection->trans_start();
        foreach ($query->result_array() as $row) {
            $table = $row['Tables_in_' . $name];
            if ($table != "almacen" && $table != "usuario_almacen" && $table != "cajas" && $table != "clientes" && $table != "impuesto" && $table != "forma_pago" && $table != "producto_tipo" && $table != "unidades" && $table != "rol" && $table != "permiso_rol" && $table != "opciones"):
                $this->connection->query("SET FOREIGN_KEY_CHECKS = 0");
                $this->connection->query("TRUNCATE " . $table);
                if ($table !== 'domicilio' && $table !== 'historico_orden_producto_restaurant' && $table !== 'proformas') {
                    $this->connection->query("ALTER TABLE " . $table . " AUTO_INCREMENT = 1");
                }
                $this->connection->query("SET FOREIGN_KEY_CHECKS = 1");
            endif;
        }
        $this->connection->trans_complete();
        return true;
        //$this->output->set_output("Database emptyed");
    }

    public function dump_hardware_store()
    {
        $data = "/*

        /*Data for the table `atributos` */

        insert  into `atributos`(`id`,`nombre`) values (1,'Marca'),(2,'Proveedor'),(3,'Color'),(4,'Talla'),(5,'Lineas'),(6,'Materiales'),(7,'Tipos');

        /*Data for the table `atributos_categorias` */

        /*Data for the table `atributos_detalle` */

        /*Data for the table `atributos_posee_categorias` */

        /*Data for the table `atributos_productos` */

        /*Data for the table `atributos_productos_almacenes` */

        /*Data for the table `auditoria_inventario` */

        /*Data for the table `carrito_compras` */

        /*Data for the table `categoria` */

        INSERT  INTO `categoria`(`id`,`codigo`,`nombre`,`imagen`,`padre`,`activo`,`es_menu_principal_tienda`,`tienda`)
        VALUES (2,0,'General','',NULL,1,0,1),
        (7,1,'CASA','vendty2_db_16399_ferel2019/categorias_productos/CASA.jpg',NULL,1,1,1),
        (8,2,'CARRO','vendty2_db_16399_ferel2019/categorias_productos/CARRO.jpg',NULL,1,0,1),
        (9,0,'EMPRESA','vendty2_db_16399_ferel2019/categorias_productos/JARDIN.jpg',NULL,1,1,1),
        (10,4,'CONSTRUCCIÓN ','vendty2_db_16399_ferel2019/categorias_productos/CONSTRUCION.jpg',NULL,1,0,1),
        (11,0,'GiftCard','giftCard.png',NULL,1,0,0);

        /*Data for the table `cierres_caja` */

        insert  into `cierres_caja`(`id`,`fecha`,`fecha_fin_cierre`,`hora_apertura`,`hora_cierre`,`id_Usuario`,`id_Caja`,`id_Almacen`,`total_egresos`,`total_ingresos`,`total_cierre`,`arqueo`) values (1,'2019-02-08',NULL,'11:01:09','00:00:00',16399,1,1,'','','',NULL);

        /*Data for the table `cliente_plan_punto` */

        /*Data for the table `comanda_notificacion_cliente` */

        insert  into `comanda_notificacion_cliente`(`id`,`usuario`,`nombre`,`notificacion`) values (2,11416,'administracion@hotelparquedelrio.com','20172211195707393000');

        /*Data for the table `comanda_notificacion_detalle` */

        /*Data for the table `comanda_notificacion_servidor` */

        insert  into `comanda_notificacion_servidor`(`notificacion`) values ('20171110214116979500');

        /*Data for the table `cuentas_dinero` */

        insert  into `cuentas_dinero`(`id`,`nombre`,`tipo_cuenta`,`numero`,`banco`,`tipo_bancaria`,`id_almacen`) values (1,'caja menor','Caja Menor','','','Ahorro',1),(2,'Caja Bancos','Banco','','','',1);

        /*Data for the table `detalle_auditoria` */

        /*Data for the table `detalle_factura_espera` */

        /*Data for the table `detalle_orden_compra` */

        /*Data for the table `detalle_venta` */

        insert  into `detalle_venta`(`id`,`venta_id`,`codigo_producto`,`nombre_producto`,`descripcion_producto`,`unidades`,`precio_venta`,`descuento`,`impuesto`,`linea`,`margen_utilidad`,`activo`,`producto_id`,`status`,`porcentaje_descuento`) values (1,1,'137782','Nissi todo terreno','0',2,4500,0,19,NULL,-1791000,1,2,NULL,0),(2,1,'137783','Benotto todo terreno','0',2,360000,0,19,NULL,600000,1,3,NULL,0),(3,2,'9999','ALICATE','0',1,1000,50,5,NULL,450,1,21,NULL,0);

        /*Data for the table `devoluciones` */

        /*Data for the table `domiciliarios` */

        insert  into `domiciliarios`(`id`,`tipo`,`descripcion`,`telefono`,`direccion`,`logo`,`comision`,`activo`) values (1,1,'Rappi',NULL,NULL,'rappi_logo.png',0,1),(2,1,'Domicilios.com',NULL,NULL,'domicilios_logo.png',0,1),(3,1,'Uber Eats',NULL,NULL,'uber_eats_logo.png',0,1);

        /*Data for the table `domicilio` */

        /*Data for the table `epayco_token_customers` */

        /*Data for the table `factura_espera` */

        /*Data for the table `facturas` */

        /*Data for the table `facturas_detalles` */

        /*Data for the table `favoritos` */

        /*Data for the table `grupo_clientes` */

        insert  into `grupo_clientes`(`id`,`nombre`) values (1,'sin grupo');

        /*Data for the table `impresora_rest_categoria_almacen` */

        /*Data for the table `impresoras_restaurante` */

        /*Data for the table `impuesto` */

        insert  into `impuesto`(`id_impuesto`,`nombre_impuesto`,`porciento`,`predeterminado`) values (2,'IVA 19%',19,1),(3,'IVA 5%',5,0);

        /*Data for the table `lista_detalle_precios` */

        /*Data for the table `lista_precios` */

        /*Data for the table `logs_login` */

        /*Data for the table `mesas_secciones` */

        /*Data for the table `meta_ventas` */

        /*Data for the table `movimiento_detalle` */

        insert  into `movimiento_detalle`(`id_detalle`,`id_inventario`,`codigo_barra`,`cantidad`,`precio_compra`,`existencias`,`nombre`,`total_inventario`,`producto_id`) values (1,1,'9999',5000,500,0,'ALICATE',2500000,21);

        /*Data for the table `movimiento_inventario` */

        insert  into `movimiento_inventario`(`id`,`fecha`,`almacen_id`,`almacen_traslado_id`,`tipo_movimiento`,`codigo_factura`,`user_id`,`total_inventario`,`nota`,`proveedor_id`) values (1,'2019-02-08 13:08:25',1,NULL,'entrada_producto',NULL,16399,2500000,NULL,0);

        /*Data for the table `movimientos_cierre_caja` */

        insert  into `movimientos_cierre_caja`(`id`,`Id_cierre`,`hora_movimiento`,`id_usuario`,`tipo_movimiento`,`valor`,`forma_pago`,`numero`,`id_mov_tip`,`tabla_mov`) values (1,1,'11:01:09',16399,'entrada_apertura','1','efectivo','',0,''),(2,1,'13:05:07',16399,'entrada_venta','867510','efectivo','FER-2',1,'venta'),(3,1,'13:12:54',16399,'entrada_venta','997.5','efectivo','FER-3',2,'venta');

        /*Data for the table `notacredito` */

        /*Data for the table `online_venta` */

        /*Data for the table `online_venta_prod` */

        /*Data for the table `opciones` */

        /*Data for the table `orden_compra` */

        /*Data for the table `orden_producto_restaurant` */

        /*Data for the table `pago` */

        /*Data for the table `pago_orden_compra` */

        /*Data for the table `pagos` */

        /*Data for the table `plan_puntos` */

        /*Data for the table `plan_separe_detalle` */

        /*Data for the table `plan_separe_factura` */

        /*Data for the table `plan_separe_pagos` */

        /*Data for the table `presupuestos` */

        /*Data for the table `presupuestos_detalles` */

        /*Data for the table `producto` */

        INSERT  INTO `producto`(`id`,`categoria_id`,`codigo`,`nombre`,`codigo_barra`,`precio_compra`,`precio_venta`,`vendernegativo`,`stock_minimo`,`descripcion`,`activo`,`impuesto`,`fecha`,`imagen`,`material`,`ingredientes`,`combo`,`unidad_id`,`imagen1`,`imagen2`,`imagen3`,`imagen4`,`imagen5`,`id_proveedor`,`stock_maximo`,`fecha_vencimiento`,`ubicacion`,`ganancia`,`tienda`,`muestraexist`,`id_tipo_producto`)
        VALUES (1,7,'1','MARTILLO',NULL,8000,9000,1,1000,'123465',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/MARTILLO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),(
        2,7,'2','LAVADERO',NULL,900000,1000000,1,1000,'123466',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/LAVADERO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (3,7,'3','DESTORNILLADOR',NULL,60000,80000,1,1000,'123467',1,3,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/DESTORNILLADOR.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (4,7,'4','LINTERNA',NULL,80000,100000,1,1000,'123468',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/LINTERNA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (5,7,'5','TALADRO',NULL,500000,600000,1,1000,'123469',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/TALADRO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (6,8,'6','LLAVE DE TUERCAS',NULL,934500,1500000,1,1000,'123470',1,3,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/LLAVE_DE_TUERCAS.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (7,8,'7','ACEITE',NULL,65000,80000,1,1000,'123471',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/ACEITE.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (8,8,'8','GATO HIDRAULICO',NULL,300000,600000,1,1000,'123472',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/GATO_HIDRAULICO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (9,8,'9','LIMPIA VIDRIO',NULL,50000,90000,1,1000,'123473',1,3,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/LIMPIA_VIDRIO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (10,8,'10','PULIDORA',NULL,200000,300000,1,1000,'123474',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/PULIDORA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (11,9,'11','METRO',NULL,10000,35000,1,1000,'123475',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/METRO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (12,9,'12','GUANTES',NULL,15000,30000,1,1000,'123476',1,3,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/GUANTES.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (13,9,'13','BOTAS',NULL,250000,300000,1,1000,'123477',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/BOTAS.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (14,9,'14','PORTA HERRAMIENTA',NULL,120000,150000,1,1000,'123478',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/PORTA_HERRAMIENTA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (15,9,'15','ESCALERA',NULL,300000,400000,1,1000,'123479',1,3,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/ESCALERA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (16,10,'16','LADRILLO',NULL,500,15000,1,1000,'123480',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/LADRILLO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (17,10,'17','CEMENTO',NULL,60000,90000,1,1000,'123481',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/CEMENTO.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (18,10,'18','ARENA DE RIO',NULL,90000,200000,1,1000,'123482',1,3,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/ARENA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (19,10,'19','VENTANA',NULL,350000,421000,1,1000,'123483',1,1,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/VENTANA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (20,10,'20','PUERTA',NULL,450000,750000,1,1000,'123484',1,2,NULL,'vendty2_db_16399_ferel2019/imagenes_productos/PUERTA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (21,7,'9999','ALICATE',NULL,500,1000,1,0,'',1,3,NULL,NULL,0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,0,'','',0,0,0,NULL);
        /*Data for the table `producto_adicional` */

        /*Data for the table `producto_combos` */

        /*Data for the table `producto_ingredientes` */

        /*Data for the table `producto_modificacion` */

        /*Data for the table `producto_seriales` */


        /*Data for the table `productosf` */

        /*Data for the table `proformas` */

        /*Data for the table `promociones` */

        insert  into `promociones`(`id`,`nombre`,`tipo`,`fecha_inicial`,`fecha_final`,`hora_inicio`,`hora_fin`,`dias`,`activo`) values (1,'PRUEBAS','cantidad','2019-02-01','2019-02-28','00:00:00','23:00:00','5',1);

        /*Data for the table `promociones_almacenes` */

        insert  into `promociones_almacenes`(`id`,`id_promocion`,`id_almacen`) values (1,1,1);

        /*Data for the table `promociones_descripcion` */

        insert  into `promociones_descripcion`(`id`,`id_promocion`,`producto_pos`,`cantidad`,`descuento`,`tipo`) values (2,1,0,1,5,'mayor_costo');

        /*Data for the table `promociones_productos` */

        insert  into `promociones_productos`(`id`,`id_promocion`,`id_producto`) values (1,1,21);

        /*Data for the table `proveedores` */

        /*Data for the table `puntos_acumulados` */

        /*Data for the table `scriptchat` */

        /*Data for the table `secciones_almacen` */

        /*Data for the table `stock_actual` */

        insert  into `stock_actual`(`id`,`almacen_id`,`producto_id`,`unidades`) values (1,1,1,8000),(2,1,2,8998),(3,1,3,6998),(4,1,4,5000),(5,1,5,10000),(6,1,6,5000),(7,1,7,3000),(8,1,8,7000),(9,1,9,9000),(10,1,10,20000),(11,1,11,8000),(12,1,12,9000),(13,1,13,4000),(14,1,14,3000),(15,1,15,6000),(16,1,16,9500),(17,1,17,4500),(18,1,18,6500),(19,1,19,3000),(20,1,20,7000),(21,1,21,4999);

        /*Data for the table `stock_diario` */

        insert  into `stock_diario`(`id`,`producto_id`,`almacen_id`,`fecha`,`razon`,`cod_documento`,`unidad`,`precio`,`usuario`) values (1,1,1,'2019-02-08','E',NULL,8000,9000,16399),(2,2,1,'2019-02-08','E',NULL,9000,1000000,16399),(3,3,1,'2019-02-08','E',NULL,7000,80000,16399),(4,4,1,'2019-02-08','E',NULL,5000,100000,16399),(5,5,1,'2019-02-08','E',NULL,10000,600000,16399),(6,6,1,'2019-02-08','E',NULL,5000,1500000,16399),(7,7,1,'2019-02-08','E',NULL,3000,80000,16399),(8,8,1,'2019-02-08','E',NULL,7000,600000,16399),(9,9,1,'2019-02-08','E',NULL,9000,90000,16399),(10,10,1,'2019-02-08','E',NULL,20000,300000,16399),(11,11,1,'2019-02-08','E',NULL,8000,35000,16399),(12,12,1,'2019-02-08','E',NULL,9000,30000,16399),(13,13,1,'2019-02-08','E',NULL,4000,300000,16399),(14,14,1,'2019-02-08','E',NULL,3000,150000,16399),(15,15,1,'2019-02-08','E',NULL,6000,400000,16399),(16,16,1,'2019-02-08','E',NULL,9500,15000,16399),(17,17,1,'2019-02-08','E',NULL,4500,90000,16399),(18,18,1,'2019-02-08','E',NULL,6500,200000,16399),(19,19,1,'2019-02-08','E',NULL,3000,421000,16399),(20,20,1,'2019-02-08','E',NULL,7000,750000,16399),(21,2,1,'2019-02-08','S','FER-2',-2,0,16399),(22,3,1,'2019-02-08','S','FER-2',-2,0,16399),(23,21,1,'2019-02-08','E',NULL,5000,1000,16399),(24,21,1,'2019-02-08','S','FER-3',-1,0,16399);

        /*Data for the table `stock_historial` */

        /*Data for the table `tipo_domiciliarios` */

        insert  into `tipo_domiciliarios`(`id`,`descripcion`) values (1,'Empresa'),(2,'Persona')";

        return $data;
    }

    public function dump_stationery()
    {
        $dump = "/*Data for the table `atributos` */

        insert  into `atributos`(`id`,`nombre`) values (1,'Marca'),(2,'Proveedor'),(3,'Color'),(4,'Talla'),(5,'Lineas'),(6,'Materiales'),(7,'Tipos');

        /*Data for the table `atributos_categorias` */

        /*Data for the table `atributos_detalle` */

        /*Data for the table `atributos_posee_categorias` */

        /*Data for the table `atributos_productos` */

        /*Data for the table `atributos_productos_almacenes` */

        /*Data for the table `auditoria_inventario` */

        /*Data for the table `carrito_compras` */

        /*Data for the table `categoria` */

         INSERT  INTO `categoria`(`id`,`codigo`,`nombre`,`imagen`,`padre`,`activo`,`es_menu_principal_tienda`,`tienda`)
        VALUES (2,0,'General','',NULL,1,0,1),
        (7,1,'CUADERNOS','vendty2_db_16407_papel2019/categorias_productos/cuadernos.jpg',NULL,1,0,1),
        (8,2,'UTILES ','vendty2_db_16407_papel2019/categorias_productos/utiles.jpg',NULL,0,1,1),
        (9,3,'MORRALES','vendty2_db_16407_papel2019/categorias_productos/morrales.jpg',NULL,1,1,1),
        (10,4,'LIBROS','vendty2_db_16407_papel2019/categorias_productos/libros.jpg',NULL,1,1,1);

        /*Data for the table `cierres_caja` */

        insert  into `cierres_caja`(`id`,`fecha`,`fecha_fin_cierre`,`hora_apertura`,`hora_cierre`,`id_Usuario`,`id_Caja`,`id_Almacen`,`total_egresos`,`total_ingresos`,`total_cierre`,`arqueo`) values (3,'2019-02-08',NULL,'14:17:58','00:00:00',16407,1,1,'','','',NULL),(4,'2019-02-11',NULL,'13:00:22','00:00:00',16407,1,1,'','','',NULL);

        /*Data for the table `cliente_plan_punto` */

        /*Data for the table `comanda_notificacion_cliente` */

        insert  into `comanda_notificacion_cliente`(`id`,`usuario`,`nombre`,`notificacion`) values (2,11416,'administracion@hotelparquedelrio.com','20172211195707393000');

        /*Data for the table `comanda_notificacion_detalle` */

        /*Data for the table `comanda_notificacion_servidor` */

        insert  into `comanda_notificacion_servidor`(`notificacion`) values ('20171110214116979500');

        /*Data for the table `cuentas_dinero` */

        insert  into `cuentas_dinero`(`id`,`nombre`,`tipo_cuenta`,`numero`,`banco`,`tipo_bancaria`,`id_almacen`) values (1,'caja menor','Caja Menor','','','Ahorro',1),(2,'Caja Bancos','Banco','','','',1);

        /*Data for the table `detalle_auditoria` */

        /*Data for the table `detalle_factura_espera` */

        /*Data for the table `detalle_orden_compra` */

        insert  into `detalle_orden_compra`(`id`,`venta_id`,`codigo_producto`,`nombre_producto`,`descripcion_producto`,`unidades`,`precio_venta`,`descuento`,`impuesto`,`impuesto_id`,`linea`,`margen_utilidad`,`activo`,`id_unidad`,`producto_id`) values (1,1,'1','Carne de res','',100000,100,0,0,NULL,NULL,0,1,1,35);

        /*Data for the table `detalle_venta` */

        /*Data for the table `devoluciones` */

        /*Data for the table `domiciliarios` */

        insert  into `domiciliarios`(`id`,`tipo`,`descripcion`,`telefono`,`direccion`,`logo`,`comision`,`activo`) values (1,1,'Rappi',NULL,NULL,'rappi_logo.png',0,1),(2,1,'Domicilios.com',NULL,NULL,'domicilios_logo.png',0,1),(3,1,'Uber Eats',NULL,NULL,'uber_eats_logo.png',0,1);

        /*Data for the table `domicilio` */

        /*Data for the table `envio_tienda` */

        /*Data for the table `epayco_token_customers` */

        /*Data for the table `factura_espera` */

        insert  into `factura_espera`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`no_factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`,`nota`,`sobrecosto`,`id_mesa`) values (-1,0,NULL,'Venta No ',NULL,'2015-06-24 00:32:06',0,-1,NULL,NULL,1,56010,0,'estandar','2015-06-24 00:32:06','',0,NULL);

        /*Data for the table `facturas` */

        /*Data for the table `facturas_detalles` */

        /*Data for the table `favoritos` */

        /*Data for the table `grupo_clientes` */

        insert  into `grupo_clientes`(`id`,`nombre`) values (1,'sin grupo');

        /*Data for the table `impresora_rest_categoria_almacen` */

        /*Data for the table `impresoras_restaurante` */

        /*Data for the table `impuesto` */

        insert  into `impuesto`(`id_impuesto`,`nombre_impuesto`,`porciento`,`predeterminado`) values (2,'IVA 19%',19,0),(3,'IVA 5%',5,0);

        /*Data for the table `lista_detalle_precios` */

        /*Data for the table `lista_precios` */

        /*Data for the table `logs_login` */

        /*Data for the table `mesas_secciones` */

        /*Data for the table `meta_ventas` */

        /*Data for the table `movimiento_detalle` */

        /*Data for the table `movimiento_inventario` */

        /*Data for the table `movimientos_cierre_caja` */

        insert  into `movimientos_cierre_caja`(`id`,`Id_cierre`,`hora_movimiento`,`id_usuario`,`tipo_movimiento`,`valor`,`forma_pago`,`numero`,`id_mov_tip`,`tabla_mov`) values (10,3,'14:17:58',16407,'entrada_apertura','1','efectivo','',0,''),(11,4,'13:00:22',16407,'entrada_apertura','1','efectivo','',0,'');

        /*Data for the table `notacredito` */

        /*Data for the table `online_venta` */

        /*Data for the table `online_venta_prod` */

        /*Data for the table `opciones` */


        /*Data for the table `orden_compra` */

        insert  into `orden_compra`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`) values (1,1,NULL,'','2017-10-24 12:11:07',1129,-1,NULL,NULL,1,10000000,0,'Orden de Compra','2017-10-24');

        /*Data for the table `orden_producto_restaurant` */

        /*Data for the table `pago` */

        /*Data for the table `pago_orden_compra` */

        /*Data for the table `pagos` */

        /*Data for the table `plan_puntos` */

        /*Data for the table `plan_separe_detalle` */

        /*Data for the table `plan_separe_factura` */

        /*Data for the table `plan_separe_pagos` */

        /*Data for the table `presupuestos` */

        /*Data for the table `presupuestos_detalles` */

        /*Data for the table `producto` */

        INSERT  INTO `producto`(`id`,`categoria_id`,`codigo`,`nombre`,`codigo_barra`,`precio_compra`,`precio_venta`,`vendernegativo`,`stock_minimo`,`descripcion`,`activo`,`impuesto`,`fecha`,`imagen`,`material`,`ingredientes`,`combo`,`unidad_id`,`imagen1`,`imagen2`,`imagen3`,`imagen4`,`imagen5`,`id_proveedor`,`stock_maximo`,`fecha_vencimiento`,`ubicacion`,`ganancia`,`tienda`,`muestraexist`,`id_tipo_producto`)
        VALUES (4,7,'1','CUADERNO 150',NULL,8000,9000,1,1000,'123465',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/cuaderno_150.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (5,7,'2','CUADERNO 250 HOJAS',NULL,900000,1000000,1,1000,'123466',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/cuaderno_250.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (6,7,'3','CUADERNO 100 HOJAS',NULL,60000,80000,1,1000,'123467',1,5,NULL,'vendty2_db_16407_papel2019/imagenes_productos/cuaderno_100.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (7,7,'4','BIOLOGIA',NULL,80000,100000,1,1000,'123468',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/biologia.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (8,7,'5','CUADERNO 50 HOJAS',NULL,500000,600000,1,1000,'123469',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/cuaderno_50_hojas.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (9,8,'6','BORRADOR',NULL,934500,1500000,1,1000,'123470',1,5,NULL,'vendty2_db_16407_papel2019/imagenes_productos/BORRADOR.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (10,8,'7','TEMPERA',NULL,65000,80000,1,1000,'123471',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/tempera_11.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (11,8,'8','COMPÁS ',NULL,300000,600000,1,1000,'123472',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/compas.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (12,8,'9','TEMPERA',NULL,50000,90000,1,1000,'123473',1,5,NULL,'vendty2_db_16407_papel2019/imagenes_productos/tempera.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (13,8,'10','REGLA',NULL,200000,300000,1,1000,'123474',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/regla.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (14,9,'11','MORRAL SX',NULL,10000,35000,1,1000,'123475',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/morral_xl.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (15,9,'12','MORRAL XL',NULL,15000,30000,1,1000,'123476',1,5,NULL,'vendty2_db_16407_papel2019/imagenes_productos/morral_xl1.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (16,9,'13','MALETA M',NULL,250000,300000,1,1000,'123477',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/maleta_m.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (17,9,'14','MORRAL SSS',NULL,120000,150000,1,1000,'123478',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/morral_sss.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (18,9,'15','MORRAL S',NULL,300000,400000,1,1000,'123479',1,5,NULL,'vendty2_db_16407_papel2019/imagenes_productos/maleta_ss.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (19,10,'16','FISICA',NULL,500,15000,1,1000,'123480',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/FISICA.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (20,10,'17','GEOMETRIA',NULL,60000,90000,1,1000,'123481',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/geometria.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (21,10,'18','SOCIALES ',NULL,90000,200000,1,1000,'123482',1,5,NULL,'vendty2_db_16407_papel2019/imagenes_productos/sociales.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (22,10,'19','MATEMATICAS',NULL,350000,421000,1,1000,'123483',1,1,NULL,'vendty2_db_16407_papel2019/imagenes_productos/MATEMATICAS.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL),
        (23,10,'20','RELIGION',NULL,450000,750000,1,1000,'123484',1,4,NULL,'vendty2_db_16407_papel2019/imagenes_productos/religion.jpg',0,0,0,1,NULL,NULL,NULL,NULL,NULL,0,1,'','TIENDA',0,1,1,NULL);

        /*Data for the table `producto_adicional` */

        /*Data for the table `producto_combos` */

        /*Data for the table `producto_ingredientes` */

        /*Data for the table `producto_modificacion` */

        /*Data for the table `producto_seriales` */

        /*Data for the table `productosf` */

        /*Data for the table `proformas` */

        /*Data for the table `promociones` */

        /*Data for the table `promociones_almacenes` */

        /*Data for the table `promociones_descripcion` */

        /*Data for the table `promociones_productos` */

        /*Data for the table `proveedores` */

        /*Data for the table `puntos_acumulados` */

        /*Data for the table `scriptchat` */

        /*Data for the table `secciones_almacen` */

        /*Data for the table `stock_actual` */

        insert  into `stock_actual`(`id`,`almacen_id`,`producto_id`,`unidades`) values (72,1,4,8000),(73,1,5,9000),(74,1,6,7000),(75,1,7,5000),(76,1,8,10000),(77,1,9,5000),(78,1,10,3000),(79,1,11,7000),(80,1,12,9000),(81,1,13,20000),(82,1,14,8000),(83,1,15,9000),(84,1,16,4000),(85,1,17,3000),(86,1,18,6000),(87,1,19,9500),(88,1,20,4500),(89,1,21,6500),(90,1,22,3000),(91,1,23,7000);

        /*Data for the table `stock_diario` */

        insert  into `stock_diario`(`id`,`producto_id`,`almacen_id`,`fecha`,`razon`,`cod_documento`,`unidad`,`precio`,`usuario`) values (335,4,1,'2019-02-08','E',NULL,8000,9000,16407),(336,5,1,'2019-02-08','E',NULL,9000,1000000,16407),(337,6,1,'2019-02-08','E',NULL,7000,80000,16407),(338,7,1,'2019-02-08','E',NULL,5000,100000,16407),(339,8,1,'2019-02-08','E',NULL,10000,600000,16407),(340,9,1,'2019-02-08','E',NULL,5000,1500000,16407),(341,10,1,'2019-02-08','E',NULL,3000,80000,16407),(342,11,1,'2019-02-08','E',NULL,7000,600000,16407),(343,12,1,'2019-02-08','E',NULL,9000,90000,16407),(344,13,1,'2019-02-08','E',NULL,20000,300000,16407),(345,14,1,'2019-02-08','E',NULL,8000,35000,16407),(346,15,1,'2019-02-08','E',NULL,9000,30000,16407),(347,16,1,'2019-02-08','E',NULL,4000,300000,16407),(348,17,1,'2019-02-08','E',NULL,3000,150000,16407),(349,18,1,'2019-02-08','E',NULL,6000,400000,16407),(350,19,1,'2019-02-08','E',NULL,9500,15000,16407),(351,20,1,'2019-02-08','E',NULL,4500,90000,16407),(352,21,1,'2019-02-08','E',NULL,6500,200000,16407),(353,22,1,'2019-02-08','E',NULL,3000,421000,16407),(354,23,1,'2019-02-08','E',NULL,7000,750000,16407);

        /*Data for the table `stock_historial` */

        /*Data for the table `tipo_domiciliarios` */

        insert  into `tipo_domiciliarios`(`id`,`descripcion`) values (1,'Empresa'),(2,'Persona')
        ";

        return $dump;
    }

    public function init_configuration()
    {
        $this->connection->where('usuario_id', $this->session->userdata('user_id'));
        $this->connection->update('usuario_almacen', array('wizard_tiponegocio' => 0));
    }

    public function skip_configuration()
    {
        $this->connection->where('usuario_id', $this->session->userdata('user_id'));
        $this->connection->update('usuario_almacen', array('wizard_tiponegocio' => 1));
    }

    public function get_complete_steps()
    {
        $response = 'finalizado';
        $sql = "SHOW COLUMNS FROM usuario_almacen LIKE 'wizard_tiponegocio'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            $sql = "ALTER TABLE `usuario_almacen` ADD COLUMN `wizard_tiponegocio` INT NULL DEFAULT 0 AFTER `id_Caja`;";
            $this->connection->query($sql);
        }

        $this->connection->select("wizard_tiponegocio");
        $this->connection->from("usuario_almacen");
        $this->connection->where("usuario_id", $this->session->userdata('user_id'));
        $this->connection->limit(1);
        $result = $this->connection->get();
        $wizard = $result->result()[0]->wizard_tiponegocio;
        if ($wizard):
            $this->db->select("step");
            $this->db->from("primeros_pasos_usuarios");
            $this->db->where("id_usuario", $this->session->userdata('user_id'));
            $this->db->limit(1);
            $result = $this->db->get();
            if ($result->num_rows() > 0):
                //Wizar incomplete
                $step = $result->result()[0]->step;
                switch ($step) {
                    case '1':
                        $response = 'Estas a solo tres pasos para terminar la configuración de tu negocio.';
                        break;

                    case '2':
                        $response = 'Estas a solo dos pasos para terminar la configuración de tu negocio.';
                        break;

                    case '3':
                        $response = 'Estas a solo un paso para terminar la configuración de tu negocio.';
                        break;
                }
            endif;
        endif;

        return $response;
    }

}
