<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Informes_model extends CI_Model
{
    public $connection;

    public function __construct()
    {
        parent::__construct();

        // Your own constructor code

        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("Caja_model", 'Caja');
        $this->Caja->initialize($this->dbConnection);
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

    // Solo categorias que tengan atributos relacionados
    public function queryPivote($atributos)
    {
        $lista = array();

        if ($atributos["marca"] > 0) {
            $val = $atributos["marca"];
            $lista[] = " AND pivot.id_marca = '$val'";
        }

        if ($atributos["color"] > 0) {
            $val = $atributos["color"];
            $lista[] = " AND pivot.id_color = '$val'";
        }

        if ($atributos["talla"] > 0) {
            $val = $atributos["talla"];
            $lista[] = " AND pivot.id_talla = '$val'";
        }

        if ($atributos["proveedor"] > 0) {
            $val = $atributos["proveedor"];
            $lista[] = " AND pivot.id_proveedor = '$val'";
        }

        if ($atributos["material"] > 0) {
            $val = $atributos["material"];
            $lista[] = " AND pivot.id_materiales = '$val'";
        }

        if ($atributos["linea"] > 0) {
            $val = $atributos["linea"];
            $lista[] = " AND pivot.id_lineas = '$val'";
        }

        if ($atributos["almacen"] > 0) {
            $val = $atributos["almacen"];
            $lista[] = " AND almacen_id = '$val'";
        }

        if ($atributos["categoria"] > 0) {
            $val = $atributos["categoria"];
            $lista[] = " AND categoria_id = '$val'";
        }

        if (count($lista) > 0) {
            $first = str_replace('AND ', '', $lista[0]);
            $lista[0] = $first;
        }

        $query = "
            SELECT pivot.*, sk.unidades ,alm.nombre AS nombre_almacen FROM(
                SELECT  pro.*, p.codigo_interno, p.nombre_producto, p.nombre_categoria,
                    MAX(IF( p.nombre_atributo = 'Marca', p.id_clasificacion, NULL)) AS id_marca,
                    MAX(IF( p.nombre_atributo = 'Marca', p.nombre_clasificacion, NULL)) AS nombre_marca,
                    MAX(IF( p.nombre_atributo = 'Color', p.id_clasificacion, NULL)) AS id_color,
                MAX(IF( p.nombre_atributo = 'Color', p.nombre_clasificacion, NULL)) AS nombre_color,
                    MAX(IF( p.nombre_atributo = 'Talla', p.id_clasificacion, NULL)) AS id_talla,
                MAX(IF( p.nombre_atributo = 'Talla', p.nombre_clasificacion, NULL)) AS nombre_talla,
                    MAX(IF( p.nombre_atributo = 'Proveedor', p.id_clasificacion, NULL)) AS id_proveedor,
                MAX(IF( p.nombre_atributo = 'Proveedor', p.nombre_clasificacion, NULL)) AS nombre_proveedor,
                    MAX(IF( p.nombre_atributo = 'Materiales', p.id_clasificacion, NULL)) AS id_materiales,
                    MAX(IF( p.nombre_atributo = 'Materiales', p.nombre_clasificacion, NULL)) AS nombre_materiales,
                    MAX(IF( p.nombre_atributo = 'Lineas', p.id_clasificacion, NULL)) AS id_lineas,
                MAX(IF( p.nombre_atributo = 'Lineas', p.nombre_clasificacion, NULL)) AS nombre_lineas
                FROM atributos_productos p
                INNER JOIN producto AS pro ON p.codigo_interno = pro.codigo_barra
                GROUP BY p.codigo_interno
            ) AS pivot
            INNER JOIN stock_actual AS sk ON pivot.id = sk.producto_id
            INNER JOIN almacen AS alm ON almacen_id = alm.id
        ";

        if (count($lista) > 0) {
            $query = $query . " WHERE ";

            for ($i = 0; $i < count($lista); $i++) {
                $query = $query . "" . $lista[$i];
            }
        }

        $query = $this->connection->query($query);
        return $query->result();
    }

    public function transacciones()
    {
        $sql = "
            SELECT s.fecha, s.cod_documento, p.codigo AS codigo, a.nombre AS almacen, p.nombre AS producto_nombre, p.descripcion as descripcion_producto, s.unidad AS cantidad, s.razon, u.username
            FROM stock_diario AS s
            INNER JOIN almacen a ON s.almacen_id = a.id
            INNER JOIN producto p ON s.producto_id = p.id
            INNER JOIN vendty2.users u ON s.usuario = u.id
            ORDER BY s.fecha desc limit 10000
		";

        $data = array();

        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->fecha,
                $value->cod_documento,
                $value->almacen,
                $value->codigo,
                $value->producto_nombre,
                $value->descripcion_producto,
                $value->cantidad,
                $value->razon,
                $value->username,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_existensias_inventario_excel($almacen = 0, $accion = false, $precio_almacen = false)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $order_by = " ORDER BY almacen, nom_cat";

        if ($is_admin == 't' || $is_admin == 'a') {
            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // Si tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";

            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } elseif ($almacen == -1) {
                $filtro .= " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select almacen.nombre as almacen, producto.nombre as producto, stock_actual.fecha_vencimiento as fvencimiento, producto.codigo, unidades.nombre as unidades_nombre, stock_actual.precio_compra,stock_actual.precio_venta" . $query . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto
                    inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $filtro $order_by";
            } else {
                // No tiene precios por almacen
                $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo, unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto
                    inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $filtro $order_by";
            }
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
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

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // No tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";

            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } else {
                $filtro .= " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select almacen.nombre as almacen, producto.nombre as producto, stock_actual.fecha_vencimiento as fvencimiento, producto.codigo,  unidades.nombre as unidades_nombre, stock_actual.precio_compra,stock_actual.precio_venta" . $query . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $filtro $order_by";
            } else {
                // No tiene precios por almacen
                $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo,  unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $filtro $order_by";
            }
        }

        $this->load->model('Opciones_model', 'opciones');
        $data = array();

        foreach ($this->connection->query($sql)->result() as $value) {
            $nombre_almacen = $value->almacen;

            if ($almacen == -1) {
                $nombre_almacen = 'consolidado';
            }

            $data[] = array(
                $nombre_almacen,
                $value->nom_cat,
                $value->producto,
                $value->codigo,
                $value->unidades_nombre,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_compra) : $value->precio_compra,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                $value->unidades,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->unidades * $value->precio_compra) : $value->unidades * $value->precio_compra,
                $value->ubicacion,
                $value->fvencimiento ? $value->fvencimiento : '',
                $value->descripcion,
                $value->nombre_comercial,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_existensias_inventario_excel_cron($almacen = 0, $accion = false, $precio_almacen = false)
    {
        if ($precio_almacen == 1) {
            // Si tiene precios por almacen
            $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
        } else {
            // Si tiene precios por almacen
            $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
        }

        $query = " ,stock_actual.unidades";

        if ($almacen > 0) {

            $filtro .= " And almacen.id = " . $almacen;
        } elseif ($almacen == -1) {
            $filtro .= " group by producto.id";
            $query = " ,SUM(stock_actual.unidades) as unidades";
        }

        if ($precio_almacen == 1) {

            // Si tiene precios por almacen
            $sql = "select almacen.nombre as almacen, producto.nombre as producto, stock_actual.fecha_vencimiento as fvencimiento, producto.codigo, unidades.nombre as unidades_nombre, stock_actual.precio_compra,stock_actual.precio_venta" . $query . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                inner join categoria on categoria.id = producto.categoria_id
                inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                $filtro LIMIT 1000";
        } else {
            // No tiene precios por almacen
            $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo, unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                inner join categoria on categoria.id = producto.categoria_id
                inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                $filtro LIMIT 1000";
        }

        $this->load->model('Opciones_model', 'opciones');
        $data = array();

        foreach ($this->connection->query($sql)->result() as $value) {
            $nombre_almacen = $value->almacen;

            if ($almacen == -1) {
                $nombre_almacen = 'consolidado';
            }

            $data[] = array(
                $nombre_almacen,
                $value->nom_cat,
                $value->producto,
                $value->codigo,
                $value->unidades_nombre,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_compra) : $value->precio_compra,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                $value->unidades,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->unidades * $value->precio_compra) : $value->unidades * $value->precio_compra,
                $value->ubicacion,
                $value->fvencimiento ? $value->fvencimiento : '',
                $value->descripcion,
                $value->nombre_comercial,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_existensias_inventario($almacen = 0, $accion = false, $precio_almacen = false, $start, $limit, $search = null)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $sort = $this->input->get('iSortCol_0');
        $order_by = '';

        if (isset($sort) && $sort != null && $sort != '') {
            $orden = $_GET['iSortCol_0'] + 1;
            $tipo = $_GET['sSortDir_0'];

            if ($orden == 0) {
                $order_by = "ORDER BY almacen $tipo";
            } else {
                $order_by = "ORDER BY $orden $tipo";

            }
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // Si tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";
            $query2 = ",producto.precio_compra *  stock_actual.unidades as inventario";

            if ($almacen > 0) {
                $filtro = " And almacen.id = " . $almacen;
            } elseif ($almacen == -1) {
                $filtro = " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($search != null) {
                $search = "and (producto.nombre like '%$search%' or producto.codigo like '%$search%' or (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) like'%$search%')";

                if ($almacen == -1) {
                    $query2 = ", producto.precio_compra *  SUM(stock_actual.unidades) as inventario";
                }
            } else {
                $search = '';
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select almacen.nombre as almacen,categoria.nombre as nom_cat, producto.nombre as producto, producto.codigo,unidades.nombre as unidades_nombre, stock_actual.precio_compra, stock_actual.precio_venta" . $query . $query2 . ",producto.ubicacion as ubicacion,stock_actual.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto
                    inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $search $filtro  $order_by LIMIT $start,$limit";
            } else {
                // No tiene precios por almacen
                $sql = "select almacen.nombre as almacen, categoria.nombre as nom_cat, producto.nombre as producto, producto.codigo, unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . $query2 . ",producto.ubicacion as ubicacion, producto.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto
                    inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $search $filtro $order_by LIMIT $start,$limit";
            }
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';

            if ($search != null) {
                $search = "and (producto.nombre like '%$search%' or producto.codigo like '%$search%' or (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) like'%$search%')";
            } else {
                $search = '';
            }

            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();

            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();

            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // No tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";
            $query2 = ",producto.precio_compra *  stock_actual.unidades as inventario";

            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } else {
                $filtro = " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select almacen.nombre as almacen, categoria.nombre as nom_cat, producto.nombre as producto,  producto.codigo,  unidades.nombre as unidades_nombre, stock_actual.precio_compra,stock_actual.precio_venta" . $query . $query2 . ", producto.ubicacion as ubicacion,stock_actual.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $search $filtro $order_by LIMIT $start,$limit";
            } else {
                // No tiene precios por almacen
                $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo,  unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . $query2 . ", categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    $filtro $search $order_by LIMIT $start,$limit";
            }
        }

        $this->load->model('Opciones_model', 'opciones');
        $data = array();
        $query = $this->connection->query($sql);

        foreach ($query->result() as $value) {
            $nombre_almacen = $value->almacen;

            if ($almacen == -1) {
                $nombre_almacen = 'consolidado';
            }

            $data[] = array(
                $nombre_almacen,
                $value->nom_cat,
                $value->producto,
                $value->codigo,
                $value->unidades_nombre,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_compra) : $value->precio_compra,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                $value->unidades,
                $value->inventario,
                $value->ubicacion,
                $value->fvencimiento ? $value->fvencimiento : '',
                $value->descripcion,
                $value->nombre_comercial,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_existencias_inventario_imei($almacen = 0, $accion = false, $precio_almacen = false, $start, $limit, $search = null)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $sort = $this->input->get('iSortCol_0');
        $order_by = '';

        if (isset($sort) && $sort != null && $sort != '') {
            $orden = $_GET['iSortCol_0'] + 1;
            $tipo = $_GET['sSortDir_0'];

            if ($orden == 0) {
                $order_by = "ORDER BY almacen $tipo";
            } else {
                $order_by = "ORDER BY $orden $tipo";
            }
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // Si tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";

            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } elseif ($almacen == -1) {
                $filtro .= " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($search != null) {
                $search = "and (producto.nombre like '%$search%' or producto.codigo like '%$search%' or ps.serial like '%$search%' or (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) like'%$search%')";
            } else {
                $search = '';
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen,categoria.nombre as nom_cat, producto.nombre as producto, producto.codigo,unidades.nombre as unidades_nombre, stock_actual.precio_compra, stock_actual.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario,producto.ubicacion as ubicacion,stock_actual.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto
                    inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    inner join producto_seriales ps ON producto.id = ps.id_producto
                    $filtro  $search group by serial $order_by LIMIT $start,$limit";
            } else {
                // No tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen, categoria.nombre as nom_cat, producto.nombre as producto, producto.codigo, unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario,producto.ubicacion as ubicacion, producto.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto
                    inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    inner join producto_seriales ps ON producto.id = ps.id_producto
                    $filtro $search group by serial $order_by LIMIT $start,$limit";
            }
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';

            if ($search != null) {
                $search = "and (producto.nombre like '%$search%' or producto.codigo like '%$search%' or  ps.serial like '%$search%' or (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) like'%$search%')";
            } else {
                $search = '';
            }

            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // No tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";
            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } else {
                $filtro .= " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen, categoria.nombre as nom_cat, producto.nombre as producto,  producto.codigo,  unidades.nombre as unidades_nombre, stock_actual.precio_compra,stock_actual.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario, producto.ubicacion as ubicacion,stock_actual.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto inner join stock_actual on producto.id = stock_actual.producto_id
				inner join categoria on categoria.id = producto.categoria_id
				inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                inner join producto_seriales ps ON producto.id = ps.id_producto
                    $filtro $search group by serial $order_by LIMIT $start,$limit";

            } else {
                // No tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo,  unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario, categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto inner join stock_actual on producto.id = stock_actual.producto_id
    inner join categoria on categoria.id = producto.categoria_id
    inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                inner join producto_seriales ps ON producto.id = ps.id_producto
                       $filtro $search group by serial $order_by LIMIT $start,$limit";
            }
        }

        $this->load->model('Opciones_model', 'opciones');
        $data = array();
        $query = $this->connection->query($sql);
        foreach ($query->result() as $value) {
            $nombre_almacen = $value->almacen;
            if ($almacen == -1) {
                $nombre_almacen = 'consolidado';
            }

            $data[] = array(
                $nombre_almacen,
                $value->nom_cat,
                $value->producto,
                $value->serial,
                $value->codigo,
                $value->unidades_nombre,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_compra) : $value->precio_compra,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                ($value->serial_vendido == 1) ? 'Vendido' : 'Sin vender',
                $value->unidades,
                $value->inventario,
                $value->ubicacion,
                $value->fvencimiento ? $value->fvencimiento : '',
                $value->descripcion,
                $value->nombre_comercial,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_existencias_inventario_imei_excel($almacen = 0, $accion = false, $precio_almacen = false, $search = null)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $sort = $this->input->get('iSortCol_0');
        $order_by = '';
        if (isset($sort) && $sort != null && $sort != '') {
            //$order = explode($_GET['iSortCol_0'])
            $orden = $_GET['iSortCol_0'] + 1;

            $tipo = $_GET['sSortDir_0'];
            //$this->connection->order_by($orden, $tipo);
            if ($orden == 0) {
                $order_by = "ORDER BY almacen $tipo";
            } else {
                $order_by = "ORDER BY $orden $tipo";

            }
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // Si tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";
            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } elseif ($almacen == -1) {
                $filtro .= " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }
            if ($search != null) {
                $search = "and (producto.nombre like '%$search%' or producto.codigo like '%$search%' or ps.serial like '%$search%' or (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) like'%$search%')";
            } else {
                $search = '';
            }

            if ($precio_almacen == 1) {

                // Si tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen,categoria.nombre as nom_cat, producto.nombre as producto, producto.codigo,unidades.nombre as unidades_nombre, stock_actual.precio_compra, stock_actual.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario,producto.ubicacion as ubicacion,stock_actual.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto
                inner join stock_actual on producto.id = stock_actual.producto_id
				inner join categoria on categoria.id = producto.categoria_id
				inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                inner join producto_seriales ps ON producto.id = ps.id_producto
                    $filtro  $search group by serial $order_by";

            } else {
                // No tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen, categoria.nombre as nom_cat, producto.nombre as producto, producto.codigo, unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario,producto.ubicacion as ubicacion, producto.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto
                inner join stock_actual on producto.id = stock_actual.producto_id
				inner join categoria on categoria.id = producto.categoria_id
				inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                inner join producto_seriales ps ON producto.id = ps.id_producto
                        $filtro $search group by serial $order_by";
            }
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $almacen = '';

            if ($search != null) {
                $search = "and (producto.nombre like '%$search%' or producto.codigo like '%$search%' or  ps.serial like '%$search%' or (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) like'%$search%')";
            } else {
                $search = '';
            }

            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $filtro = "Where stock_actual.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            } else {
                // No tiene precios por almacen
                $filtro = "Where producto.activo AND producto.ingredientes = 0 AND producto.combo = 0";
            }

            $query = " ,stock_actual.unidades";
            if ($almacen > 0) {
                $filtro .= " And almacen.id = " . $almacen;
            } else {
                $filtro .= " group by producto.id";
                $query = " ,SUM(stock_actual.unidades) as unidades";
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen, categoria.nombre as nom_cat, producto.nombre as producto,  producto.codigo,  unidades.nombre as unidades_nombre, stock_actual.precio_compra,stock_actual.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario, producto.ubicacion as ubicacion,stock_actual.fecha_vencimiento as fvencimiento,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                from producto inner join stock_actual on producto.id = stock_actual.producto_id
				inner join categoria on categoria.id = producto.categoria_id
				inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id
                inner join producto_seriales ps ON producto.id = ps.id_producto
                $filtro $search group by serial $order_by";

            } else {
                // No tiene precios por almacen
                $sql = "select ps.*, almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo,  unidades.nombre as unidades_nombre, producto.precio_compra,producto.precio_venta" . $query . ",producto.precio_compra *  stock_actual.unidades as inventario, categoria.nombre as nom_cat, producto.ubicacion as ubicacion,producto.descripcion, (SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor ) AS nombre_comercial
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                    inner join categoria on categoria.id = producto.categoria_id
                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id
                    inner join producto_seriales ps ON producto.id = ps.id_producto
                    $filtro $search group by serial $order_by";
            }
        }

        $this->load->model('Opciones_model', 'opciones');
        $data = array();
        $query = $this->connection->query($sql);
        foreach ($query->result() as $value) {
            $nombre_almacen = $value->almacen;
            if ($almacen == -1) {
                $nombre_almacen = 'consolidado';
            }

            $data[] = array(
                $nombre_almacen,
                $value->nom_cat,
                $value->producto,
                $value->serial,
                $value->codigo,
                $value->unidades_nombre,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_compra) : $value->precio_compra,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                ($value->serial_vendido == 1) ? 'Vendido' : 'Sin vender',
                $value->unidades,
                $value->inventario,
                $value->ubicacion,
                $value->fvencimiento ? $value->fvencimiento : '',
                $value->descripcion,
                $value->nombre_comercial,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_existensias_inventario_franquicia($almacen = 0, $id_franquicia)
    {
        $this->load->model('franquicias_model', 'franquicias');
        $franquicia = $this->franquicias->get_franquicia($id_franquicia);
        $usuariofranquicia = $this->franquicias->get_user_by_email($franquicia->email_franquicia);
        $user_db_connection = $this->franquicias->get_user_db_connection($usuariofranquicia->db_config_id);
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $filtro = "";

            if ($almacen != 0) {
                $filtro = " where almacen.id = " . $almacen;
            }

            $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo, unidades.nombre as unidades_nombre, precio_compra, stock_actual.unidades, categoria.nombre as nom_cat, producto.ubicacion as ubicacion
            from producto
            inner join stock_actual on producto.id = stock_actual.producto_id
            inner join categoria on categoria.id = producto.categoria_id
            inner join unidades on unidades.id = producto.unidad_id
            inner join almacen on almacen.id = stock_actual.almacen_id  $filtro";
        }

        if ($is_admin != 't' && $is_admin != 'a') {
            $db_config_id = $usuariofranquicia->db_config_id;
            $id_user = '';
            $almacen = '';
            $user = $this->db->query("SELECT id FROM users where db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $user_db_connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $almacen = $dat->almacen_id;
            }
            //---------------------------------------------
            $filtro = "";
            if ($almacen != 0) {
                $filtro = " where almacen.id = " . $almacen;
            }

            $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo,  unidades.nombre as unidades_nombre, precio_compra, stock_actual.unidades, categoria.nombre as nom_cat, producto.ubicacion as ubicacion
                    from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                    inner join categoria on categoria.id = producto.categoria_id
                                    inner join unidades on unidades.id = producto.unidad_id
                    inner join almacen on almacen.id = stock_actual.almacen_id $filtro";
        }

        $data = array();

        foreach ($user_db_connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->almacen,
                $value->nom_cat,
                $value->producto,
                $value->codigo,
                $value->unidades_nombre,
                $value->precio_compra,
                $value->unidades,
                $value->unidades * $value->precio_compra,
                $value->ubicacion,
                $value->fvencimiento ? $value->fvencimiento : '',
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_stock_minimo_maximo($almacen, $stock, $precio_almacen = false, $start, $limit)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $filtro = "";
            if ($almacen != 0) {
                $filtro = " and almacen.id = " . $almacen;
            }
            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                if ($stock == 'minimo') {
                    $sql = "select producto.id_proveedor, almacen.nombre as almacen, producto.nombre as producto, codigo, stock_actual.precio_compra, unidades, stock_actual.stock_minimo
                            from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                            inner join almacen on almacen.id = stock_actual.almacen_id where producto.id > 0 $filtro and (unidades <= producto.stock_minimo) LIMIT $start,$limit";
                }

                if ($stock == 'maximo') {
                    $sql = "select producto.id_proveedor, almacen.nombre as almacen, producto.nombre as producto, codigo, stock_actual.precio_compra, unidades, stock_actual.stock_minimo
                            from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                            inner join almacen on almacen.id = stock_actual.almacen_id where producto.id > 0 $filtro LIMIT $start,$limit";
                } else {

                    $sql = "select producto.id_proveedor, almacen.nombre as almacen, producto.nombre as producto, codigo, stock_actual.precio_compra, unidades, stock_actual.stock_minimo
                            from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                            inner join almacen on almacen.id = stock_actual.almacen_id where producto.id > 0 $filtro and (unidades <= producto.stock_minimo) LIMIT $start,$limit";
                }
            } else {

                // No tiene precios por almacen
                if ($stock == 'minimo') {
                    $sql = "select producto.id_proveedor, almacen.nombre as almacen, producto.nombre as producto, codigo, producto.precio_compra, unidades, producto.stock_minimo
                            from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                            inner join almacen on almacen.id = stock_actual.almacen_id where producto.id > 0 $filtro and (unidades <= producto.stock_minimo) LIMIT $start,$limit";
                }

                if ($stock == 'maximo') {
                    $sql = "select producto.id_proveedor, almacen.nombre as almacen, producto.nombre as producto, codigo, producto.precio_compra, unidades, producto.stock_minimo
                            from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                            inner join almacen on almacen.id = stock_actual.almacen_id where producto.id > 0 $filtro LIMIT $start,$limit";
                } else {
                    $sql = "select producto.id_proveedor, almacen.nombre as almacen, producto.nombre as producto, codigo, producto.precio_compra, unidades, producto.stock_minimo
                            from producto inner join stock_actual on producto.id = stock_actual.producto_id
                                            inner join almacen on almacen.id = stock_actual.almacen_id where producto.id > 0 $filtro and (unidades <= producto.stock_minimo) LIMIT $start,$limit";
                }
            }
        }
        //var_dump($sql); die();
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $proveedor = '';
            $sql_proveedor = "SELECT proveedores.nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = '$value->id_proveedor' LIMIT 1";
            $result = $this->connection->query($sql_proveedor);
            if ($result->num_rows() > 0) {
                $proveedor_result = $result->row();
                $proveedor = $proveedor_result->nombre_comercial;
            } else {
                $proveedor = "Sin proveedor";
            }

            $data[] = array(
                $value->almacen,
                $value->producto,
                $value->codigo,
                number_format($value->precio_compra, 2),
                $value->unidades,
                $proveedor,
                $value->stock_minimo,
                number_format($value->unidades * $value->precio_compra, 2),
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function consolidado_inventario($fecha_inicio = "", $fecha_fin = "", $accion = false, $precio_almacen = false)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) <= '$fecha_fin'";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $sql = "SELECT producto.nombre as producto, producto.codigo, (stock_actual.precio_venta * sum(unidades)) total_precio, sum(unidades) total_unidades, categoria.nombre as cate_nom
		from stock_actual
		inner join producto on producto.id = stock_actual.producto_id
		inner join categoria on categoria.id = producto.categoria_id
		group by stock_actual.producto_id ";
            } else {
                // No tiene precios por almacen
                $sql = "SELECT producto.nombre as producto, producto.codigo, (producto.precio_venta * sum(unidades)) total_precio, sum(unidades) total_unidades, categoria.nombre as cate_nom
		from stock_actual
		inner join producto on producto.id = stock_actual.producto_id
		inner join categoria on categoria.id = producto.categoria_id
		group by stock_actual.producto_id ";
            }
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->cate_nom,
                $value->producto,
                $value->codigo,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->total_precio) : $value->total_precio,
                $value->total_unidades,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function ventasxclients($fecha_inicio = "", $fecha_fin = "")
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " and date(venta.fecha) >= '$fecha_inicio' and date(venta.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " and date(venta.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " and date(venta.fecha) <= '$fecha_fin'";
        }

        //busco el tipo de negocio
        $ocp = "SELECT valor_opcion FROM opciones where nombre_opcion = 'tipo_negocio' ";
        $ocpresult = $this->connection->query($ocp)->row();
        $tipo_negocio = $ocpresult->valor_opcion;
        //$tipo_negocio = $dat->valor_opcion;

        if (($tipo_negocio != "Restaurante") && ($tipo_negocio != "restaurante")) {
            $filtro_comensales = "";
        } else {
            $filtro_comensales = " venta.comensales, ";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sql = "SELECT venta.fecha, venta.factura, venta.nota,
		(select nombre from almacen where almacen.id = venta.almacen_id) as nom_almacen,
		(select nombre_comercial from clientes where venta.cliente_id =  clientes.id_cliente) as nom_clientes,
		venta.total_venta, venta.factura AS total_precio_compra, ROUND(SUM( `unidades` * `descuento` )) AS total_descuento, $filtro_comensales sum(margen_utilidad) as margen_utilidad_final
                    FROM detalle_venta
                    INNER JOIN venta on detalle_venta.venta_id =  venta.id
                    WHERE venta.id $filtro_fecha
					AND venta.estado = 0

                        group by(detalle_venta.venta_id)
                        ORDER BY venta.fecha DESC";
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

            $sql = "SELECT venta.fecha, venta.factura, venta.nota,
		(select nombre from almacen where almacen.id = venta.almacen_id) as nom_almacen,
		(select nombre_comercial from clientes where venta.cliente_id =  clientes.id_cliente) as nom_clientes,
		venta.total_venta, venta.factura AS total_precio_compra, ROUND(SUM( `unidades` * `descuento` )) AS total_descuento, $filtro_comensales sum(margen_utilidad) as margen_utilidad_final
                    FROM detalle_venta
                    INNER JOIN venta on detalle_venta.venta_id =  venta.id
                    WHERE venta.id $filtro_fecha and  venta.almacen_id  = $almacen
                        AND venta.estado = 0

                        group by(detalle_venta.venta_id)
                        ORDER BY venta.fecha DESC";
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            if (empty($filtro_comensales)) {
                $data[] = array(
                    $value->nom_almacen,
                    $value->fecha,
                    $value->factura,
                    $value->nom_clientes,
                    $this->opciones_model->formatoMonedaMostrar($value->total_venta),
                    $this->opciones_model->formatoMonedaMostrar($value->margen_utilidad_final),
                    $value->nota,
                );
            } else {
                $data[] = array(
                    $value->nom_almacen,
                    $value->fecha,
                    $value->factura,
                    $value->nom_clientes,
                    $this->opciones_model->formatoMonedaMostrar($value->total_venta),
                    $this->opciones_model->formatoMonedaMostrar($value->margen_utilidad_final),
                    $value->comensales,
                    $value->nota,
                );
            }
        }

        return array(
            'aaData' => $data,
        );
    }

    public function ventasxclientsex($fecha_inicio = "", $fecha_fin = "")
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) <= '$fecha_fin'";
        }

        //busco el tipo de negocio
        $ocp = "SELECT valor_opcion FROM opciones where nombre_opcion = 'tipo_negocio' ";
        $ocpresult = $this->connection->query($ocp)->row();
        $tipo_negocio = $ocpresult->valor_opcion;
        //$tipo_negocio = $dat->valor_opcion;
        // echo"<br>".$tipo_negocio; die();
        if (($tipo_negocio != "Restaurante") && ($tipo_negocio != "restaurante")) {
            $filtro_comensales = "";
        } else {
            $filtro_comensales = " v.comensales, ";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sql = "SELECT a.nombre AS almacen, v.fecha, factura, c.nombre_comercial AS cliente, v.total_venta, $filtro_comensales sum(margen_utilidad) as margen_utilidad
                FROM venta AS v
                    INNER JOIN detalle_venta on venta_id = v.id
                    LEFT JOIN almacen AS a ON v.almacen_id = a.id
                    LEFT JOIN clientes AS c ON v.cliente_id =  c.id_cliente where v.id $filtro_fecha
					and v.estado = 0
                        group by(v.id)
                        ORDER BY v.fecha DESC";
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

            $sql = "SELECT a.nombre AS almacen, v.fecha, factura, c.nombre_comercial AS cliente, v.total_venta, $filtro_comensales sum(margen_utilidad) as margen_utilidad
                FROM venta AS v
                    INNER JOIN detalle_venta on venta_id = v.id
                    LEFT JOIN almacen AS a ON v.almacen_id = a.id
                    LEFT JOIN clientes AS c ON v.cliente_id =  c.id_cliente where v.id $filtro_fecha and v.almacen_id  = $almacen
					and v.estado = 0 and  v.almacen_id  = $almacen
                        group by(v.id)
                        ORDER BY v.fecha DESC";
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            if (empty($filtro_comensales)) {
                $data[] = array(
                    $value->almacen,
                    $value->fecha,
                    $value->factura,
                    $value->cliente,
                    $value->total_venta,
                    $value->margen_utilidad,
                );
            } else {
                $data[] = array(
                    $value->almacen,
                    $value->fecha,
                    $value->factura,
                    $value->cliente,
                    $value->total_venta,
                    $value->margen_utilidad,
                    $value->comensales,
                );
            }
        }

        return array(
            'aaData' => $data,
        );
    }

    public function descuentosotorgados($fecha_inicio = "", $fecha_fin = "")
    {
        $estado = array('ABIERTO', 'CERRADA', 'ANULADA');
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "
               select f.numero, f.fecha, f.estado, fd.descripcion, fd.precio, fd.cantidad, fd.descuento from facturas_detalles fd inner join facturas f on f.id_factura = fd.id_factura $filtro_fecha order by f.numero
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $descuento_unidad = $value->precio * $value->descuento / 100;
            $data[] = array(
                $value->numero,
                $value->fecha,
                $estado[$value->estado],
                $value->descripcion,
                $value->precio,
                $value->cantidad,
                $descuento_unidad,
                $descuento_unidad * $value->cantidad,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function margenutilidad()
    {
        $sql = "select * from productos";
        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $margen_utilidad = $value->precio - $value->precio_compra;
            $data[] = array(
                $value->codigo,
                $value->nombre,
                ceil($value->precio_compra),
                ceil($value->precio),
                ceil($margen_utilidad),
                ceil(($margen_utilidad - $value->precio_compra) * 100),
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function ventasxproductos($fecha_inicio, $fecha_fin)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "select descripcion, sum(cantidad) as cantidad, avg(precio) as precio, avg(descuento) as descuento from facturas_detalles fd inner join facturas f on fd.id_factura = f.id_factura
                    $filtro_fecha
                group by descripcion
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $descuento_total = $value->precio * $value->descuento / 100 * $value->cantidad;
            $data[] = array(
                $value->descripcion,
                $value->cantidad,
                $value->precio * $value->cantidad - $descuento_total,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function detallesgastos($fecha_inicio, $fecha_fin)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "select p.*, i.porciento from proformas p inner join impuestos i on p.id_impuesto = p.id_impuesto
                    $filtro_fecha
                group by descripcion
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $valor_total = $value->cantidad * $value->porciento;
            $data[] = array(
                $value->fecha,
                $value->descripcion,
                $valor_total + $value->cantidad,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function detallesimpuestos($fecha_inicio, $fecha_fin)
    {
        $estado = array('ABIERTO', 'CERRADA', 'ANULADA');
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
        }

        $sql = "select f.*, c.nombre_comercial from facturas f inner join clientes c on f.id_cliente = c.id_cliente
                    $filtro_fecha
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $valor_impuesto = $value->monto - $value->monto_siva;
            $data[] = array(
                $estado[$value->estado],
                $value->numero,
                $value->fecha,
                $value->nombre_comercial,
                $value->monto,
                $valor_impuesto,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function pagosrecibidos($fecha_inicio, $fecha_fin)
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where p.fecha_pago BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where p.fecha_pago > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where p.fecha_pago < '$fecha_fin'";
        }

        $sql = "select
            p.id_pago, p.fecha_pago, v.factura, c.nombre_comercial, p.tipo,  p.cantidad,  p.importe_retencion, v.total_venta
            from pago p
            inner join venta v on v.id = p.id_factura
            inner join clientes c on v.cliente_id = c.id_cliente
        " . $filtro_fecha;

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->id_pago,
                $value->fecha_pago,
                $value->factura,
                $value->nombre_comercial,
                $value->tipo,
                $value->cantidad,
                //$this->opciones_model->formatoMonedaMostrar($value->cantidad),
                $value->importe_retencion,
                //$this->opciones_model->formatoMonedaMostrar($value->importe_retencion),
                $value->total_venta,
                //$this->opciones_model->formatoMonedaMostrar($value->total_venta)
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function resumenimpuestos()
    {
        $sql = "SELECT i.nombre_impuesto, i.porciento, sum( precio ) AS monto, sum( precio * f.impuesto ) AS valor_impuesto
                    FROM facturas_detalles f
                        INNER JOIN impuestos i ON i.porciento = f.impuesto
                            GROUP BY i.porciento
        ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->nombre_impuesto,
                $value->porciento,
                $value->monto,
                $value->valor_impuesto,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    //Cuadre de caja
    public function utilidad_periodo($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $vr_valor = 0;
        $vr_costos = 0;
        $vr_gastos = 0;
        $vr_descuento = 0;
        $vr_valor_compra = 0;
        $unidad = 0;

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on venta.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $ventas = "SELECT
                                    p.id,
                                    p.codigo,
                                    p.nombre,
                                    dv.unidades,
                                    SUM( dv.unidades * dv.descuento ) AS total_descuento,
                                    SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                                    SUM( p.precio_compra * dv.unidades) AS total_precio_compra

                             FROM venta AS v
                             INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                             INNER JOIN producto AS p ON dv.producto_id = p.id
                             WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  $condition $condition1  AND  estado = 0
                           GROUP BY p.nombre";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            $ventas = "SELECT
                        p.id,
                        p.codigo,
                        p.nombre,
                        dv.unidades,
                        SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                        SUM( p.precio_compra * dv.unidades) AS total_precio_compra
                     FROM venta AS v
                     INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                     INNER JOIN producto AS p ON dv.producto_id = p.id
                     WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'   AND  estado = 0 AND almacen_id=$almacen
                     GROUP BY p.nombre";
        }

        $ventas_id = $this->connection->query($ventas)->result();

        foreach ($ventas_id as $value) {

            $vr_valor += $value->total_precio_venta;

            $vr_valor_compra += $value->total_precio_compra;

            $vr_descuento += $value->total_descuento;

            $unidad = $value->unidades;

            $detalle_inventario = "SELECT sum(precio_compra) as total_compra
                                    FROM producto
                                    WHERE id = '$value->id'";

            $detalle_inventario_id = $this->connection->query($detalle_inventario)->result();

            foreach ($detalle_inventario_id as $prod) {

                $vr_costos += $prod->total_compra * $unidad;
            }
        }

        $gastos = "SELECT sum(valor) as total FROM proformas where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' AND notas NOT LIKE '%eliminado%'";

        if ($almacen > 0) {
            $gastos .= " AND id_almacen = " . $almacen;
        }
        /* if($almacen != 0)
        {
        $gastos_orden = 'SELECT SUM(p.`cantidad`) as cantidad FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` AND o.`almacen_id` = '.$almacen.' WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        }else
        {
        $gastos_orden = 'SELECT SUM(p.`cantidad`) as cantidad FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        } */

        $gastos_id = $this->connection->query($gastos)->result();
        //$r_gastos_orden = $this->connection->query($gastos_orden)->result();
        //var_dump($gastos_orden);
        foreach ($gastos_id as $value) {
            $vr_gastos += $value->total;
        }

        return array(
            'total_venta' => $vr_valor,
            'total_descuento' => $vr_descuento,
            'total_costos' => $vr_valor_compra,
            'total_gastos' => $vr_gastos,
        );
    }

    public function total_utilidad_informe($fechainicial, $fechafinal, $almacen, $ciudad)
    {
        $vr_valor = 0;
        $vr_costos = 0;
        $vr_gastos = 0;
        $vr_descuento = 0;
        $vr_valor_compra = 0;
        $unidad = 0;

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on venta.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $ventas = "SELECT
                                    p.id,
                                    p.codigo,
                                    p.nombre,
                                    dv.unidades,
                                    SUM( dv.unidades * dv.descuento ) AS total_descuento,
                                    SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                                    SUM( p.precio_compra * dv.unidades) AS total_precio_compra

                             FROM venta AS v
                             INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                             INNER JOIN producto AS p ON dv.producto_id = p.id
                             WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  $condition $condition1  AND  estado = 0
                           GROUP BY p.nombre";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            $ventas = "SELECT
                        p.id,
                        p.codigo,
                        p.nombre,
                        dv.unidades,
                        SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                        SUM( p.precio_compra * dv.unidades) AS total_precio_compra
                     FROM venta AS v
                     INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                     INNER JOIN producto AS p ON dv.producto_id = p.id
                     WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'   AND  estado = 0 AND almacen_id=$almacen
                     GROUP BY p.nombre";
        }

        $ventas_id = $this->connection->query($ventas)->result();

        foreach ($ventas_id as $value) {

            $vr_valor += $value->total_precio_venta;

            $vr_valor_compra += $value->total_precio_compra;

            $vr_descuento += $value->total_descuento;

            $unidad = $value->unidades;

            $detalle_inventario = "SELECT sum(precio_compra) as total_compra
                                    FROM producto
                                    WHERE id = '$value->id'";

            $detalle_inventario_id = $this->connection->query($detalle_inventario)->result();

            foreach ($detalle_inventario_id as $prod) {

                $vr_costos += $prod->total_compra * $unidad;
            }
        }

        $gastos = "SELECT sum(valor) as total FROM proformas where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' AND notas NOT LIKE '%eliminado%'";

        if ($almacen > 0) {
            $gastos .= " AND id_almacen = " . $almacen;
        }
        /* if($almacen != 0)
        {
        $gastos_orden = 'SELECT SUM(p.`cantidad`) as cantidad FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` AND o.`almacen_id` = '.$almacen.' WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        }else
        {
        $gastos_orden = 'SELECT SUM(p.`cantidad`) as cantidad FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        } */

        $gastos_id = $this->connection->query($gastos)->result();
        //$r_gastos_orden = $this->connection->query($gastos_orden)->result();
        //var_dump($gastos_orden);
        foreach ($gastos_id as $value) {
            $vr_gastos += $value->total;
        }

        return ($vr_valor - $vr_valor_compra - $vr_descuento);
    }

    public function detalleUtilidad($fechainicial, $fechafinal, $almacen)
    {
        $vr_valor = 0;
        $vr_costos = 0;
        $vr_gastos = 0;
        $vr_descuento = 0;
        $vr_valor_compra = 0;
        $unidad = 0;
        $vr_impuestos = 0;

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            /* $ventas=" SELECT v.factura,
            v.fecha,
            a.`nombre`,
            v.`total_venta`
            FROM venta AS v
            INNER JOIN almacen AS a ON a.id = v.`almacen_id`
            WHERE DATE(v.fecha) BETWEEN '".$fechainicial."' AND '".$fechafinal."'".$condition; */

            $ventas = "SELECT
                        v.factura,
                        p.id,
                        p.codigo,
                        p.nombre,
                        dv.unidades,
                        SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                        SUM( p.precio_compra * dv.unidades) AS total_precio_compra
                    FROM venta AS v
                    INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                    INNER JOIN producto AS p ON dv.producto_id = p.id
                    WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  $condition   AND  estado = 0
                    GROUP BY v.id,p.nombre";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            /* $ventas="SELECT v.factura,
            v.fecha,
            a.`nombre`,
            v.`total_venta`
            FROM venta AS v
            INNER JOIN almacen AS a ON a.id = v.`almacen_id`
            WHERE DATE(v.fecha) BETWEEN '".$fechainicial."' AND '".$fechafinal."' AND a.id = '".$almacen."'"; */
            $ventas = "SELECT
                        v.factura,
                        p.id,
                        p.codigo,
                        p.nombre,
                        dv.unidades,
                        SUM( dv.unidades * dv.descuento ) AS total_descuento,
                        SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                        SUM( p.precio_compra * dv.unidades) AS total_precio_compra
                    FROM venta AS v
                    INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                    INNER JOIN producto AS p ON dv.producto_id = p.id
                    WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'   AND  estado = 0 AND almacen_id=$almacen
                    GROUP BY v.id,p.nombre";
        }

        $ventas = $this->connection->query($ventas)->result();
        foreach ($ventas as $value) {
            $vr_valor += $value->total_precio_venta;
            $vr_valor_compra += $value->total_precio_compra;
            $vr_descuento += $value->total_descuento;
            $unidad = $value->unidades;
            $vr_impuestos += $value->impuesto;
            $detalle_inventario = "SELECT sum(precio_compra) as total_compra
                                    FROM producto
                                    WHERE id = '$value->id'";

            $detalle_inventario_id = $this->connection->query($detalle_inventario)->result();

            foreach ($detalle_inventario_id as $prod) {
                $vr_costos += $prod->total_compra * $unidad;
            }
        }

        $gastos = "SELECT * FROM proformas as p INNER JOIN almacen AS a ON a.id = p.id_almacen where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' AND notas NOT LIKE '%eliminado%'";
        /* if($almacen != 0)
        {
        $gastos_orden = 'SELECT * FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` AND o.`almacen_id` = '.$almacen.' WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        }else
        {
        $gastos_orden = 'SELECT * FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        } */

        $gastosSuma = "SELECT sum(valor) as total FROM proformas where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' AND notas NOT LIKE '%eliminado%'";
        /* if($almacen != 0)
        {
        $gastos_ordenSuma = 'SELECT SUM(p.`cantidad`) as cantidad FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` AND o.`almacen_id` = '.$almacen.' WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        }else
        {
        $gastos_ordenSuma = 'SELECT SUM(p.`cantidad`) as cantidad FROM pago_orden_compra p JOIN orden_compra o ON p.`id_factura` = o.`id` WHERE p.`fecha_pago` BETWEEN "'.$fechainicial.'" AND "'.$fechafinal.'" AND estado = 0';
        } */

        $gastos = $this->connection->query($gastos)->result();
        //$gastos_orden = $this->connection->query($gastos_orden)->result();
        $gastosSuma = $this->connection->query($gastosSuma)->result();
        //$gastos_ordenSuma = $this->connection->query($gastos_ordenSuma)->result();
        $vr_gastos = 0;
        foreach ($gastosSuma as $value) {
            $vr_gastos += $value->total;
        }

        $vr_gastos_orden = 0;
        /* if(count($gastos_ordenSuma) > 0)
        {
        $vr_gastos_orden += $gastos_ordenSuma[0]->cantidad;
        } */
        return array(
            'ventas' => $ventas,
            'gastos' => $gastos,
            //'gastos_orden' => $gastos_orden,
            'calculos' => array("vr_valor" => $vr_valor, "vr_valor_compra" => $vr_valor_compra, "vr_descuento" => $vr_descuento, "vr_impuestos" => $vr_impuestos, "vr_costos" => $vr_costos, "gastos" => $vr_gastos, "gatos_orden" => $vr_gastos_orden),
        );
    }

    public function valor_inventario($precio_almacen = false)
    {

        $ventaid = 0;
        $rest = 0;
        $rest1 = 0;
        $detalleventaid = 0;
        $valor_total_detalle = 0;
        $valor_total_venta = 0;
        $total_existencias = 0;
        $total_unidades = 0;
        //------------------------------------------------ almacen usuario
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
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

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $almacen = "SELECT id, nombre FROM almacen";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $almacen = "SELECT id, nombre FROM almacen where id='$almacen'";
        }

        $almacen_result = $this->connection->query($almacen)->result();

        foreach ($almacen_result as $value) {

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $movimiento_detalle = "SELECT stock_actual.precio_compra * stock_actual.unidades as valor_total_detalle, stock_actual.unidades as total_unidades, stock_actual.precio_venta * stock_actual.unidades as valor_total_venta  FROM producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                where producto.activo AND producto.ingredientes = 0  AND producto.combo = 0 AND stock_actual.almacen_id  =  $value->id  ";
            } else {
                // No tiene precios por almacen
                $movimiento_detalle = "SELECT producto.precio_compra * stock_actual.unidades as valor_total_detalle, stock_actual.unidades as total_unidades, producto.precio_venta * stock_actual.unidades as valor_total_venta  FROM producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                where producto.activo AND producto.ingredientes = 0  AND producto.combo = 0 AND stock_actual.almacen_id  =  $value->id  ";
            }

            $movimiento_detalle_id = $this->connection->query($movimiento_detalle)->result();

            foreach ($movimiento_detalle_id as $det) {
                $valor_total_detalle += $det->valor_total_detalle;
                $total_unidades += $det->total_unidades;
                $valor_total_venta += $det->valor_total_venta;
            }

            /*if ($precio_almacen == 1) {
            // Si tiene precios por almacen
            $movimiento_detalle = "SELECT stock_actual.precio_venta * stock_actual.unidades as valor_total_venta FROM producto
            inner join stock_actual on producto.id = stock_actual.producto_id
            where stock_actual.almacen_id  =  $value->id   ";
            }else{
            // No tiene precios por almacen
            $movimiento_detalle = "SELECT producto.precio_venta * stock_actual.unidades as valor_total_venta FROM producto
            inner join stock_actual on producto.id = stock_actual.producto_id
            where stock_actual.almacen_id  =  $value->id   ";
            }

            $movimiento_detalle_id = $this->connection->query($movimiento_detalle)->result();

            foreach ($movimiento_detalle_id as $det) {
            $valor_total_venta += $det->valor_total_venta;
            }*/

            $inventario_almacen[] = array(
                'almacen_nombre' => $value->nombre
                , 'valor_inventario' => $valor_total_detalle
                , 'valor_venta' => $valor_total_venta
                , 'total_unidades' => $total_unidades,
            );

            $total_unidades = 0;
            $valor_total_detalle = 0;
            $valor_total_venta = 0;
        }

        return array(
            'almacenes' => $inventario_almacen,
        );
    }

    public function valor_inventario_cron($precio_almacen = false)
    {

        $ventaid = 0;
        $rest = 0;
        $rest1 = 0;
        $detalleventaid = 0;
        $valor_total_detalle = 0;
        $valor_total_venta = 0;
        $total_existencias = 0;
        $total_unidades = 0;

        $almacen = "SELECT id, nombre FROM almacen";
        $almacen_result = $this->connection->query($almacen)->result();

        foreach ($almacen_result as $value) {

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $movimiento_detalle = "SELECT stock_actual.precio_compra * stock_actual.unidades as valor_total_detalle, stock_actual.unidades as total_unidades  FROM producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                where producto.activo AND producto.ingredientes = 0  AND producto.combo = 0 AND stock_actual.almacen_id  =  $value->id  ";
            } else {
                // No tiene precios por almacen
                $movimiento_detalle = "SELECT producto.precio_compra * stock_actual.unidades as valor_total_detalle, stock_actual.unidades as total_unidades  FROM producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                where producto.activo AND producto.ingredientes = 0  AND producto.combo = 0 AND stock_actual.almacen_id  =  $value->id  ";
            }

            $movimiento_detalle_id = $this->connection->query($movimiento_detalle)->result();

            foreach ($movimiento_detalle_id as $det) {
                $valor_total_detalle += $det->valor_total_detalle;
                $total_unidades += $det->total_unidades;
            }

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $movimiento_detalle = "SELECT stock_actual.precio_venta * stock_actual.unidades as valor_total_venta FROM producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                where stock_actual.almacen_id  =  $value->id   ";
            } else {
                // No tiene precios por almacen
                $movimiento_detalle = "SELECT producto.precio_venta * stock_actual.unidades as valor_total_venta FROM producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                where stock_actual.almacen_id  =  $value->id   ";
            }

            $movimiento_detalle_id = $this->connection->query($movimiento_detalle)->result();

            foreach ($movimiento_detalle_id as $det) {
                $valor_total_venta += $det->valor_total_venta;
            }

            $inventario_almacen[] = array(
                'almacen_nombre' => $value->nombre
                , 'valor_inventario' => $valor_total_detalle
                , 'valor_venta' => $valor_total_venta
                , 'total_unidades' => $total_unidades,
            );

            $total_unidades = 0;
            $valor_total_detalle = 0;
            $valor_total_venta = 0;
        }

        return array(
            'almacenes' => $inventario_almacen,
        );
    }

    public function total_ventas_hora($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $ventas = array();

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $condition = '';
        $condition1 = '';
        $inner = "";

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
        }

        $alm = '';
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }
        }

        /*$total_ventas = "SELECT DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha, '%h %p') AS fecha
        ,SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades,
        SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento
        ,SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades,
        SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ))) AS impuesto
        ,SUM(ROUND(dv.precio_venta * IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades,
        1 ))) AS total_precio_venta
        FROM venta v
        INNER JOIN detalle_venta dv ON v.id=dv.venta_id
        LEFT  JOIN devoluciones d ON v.factura=d.factura
        LEFT  JOIN notacredito nc ON d.id=nc.devolucion_id
        WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'
        $condition $condition1 $alm AND v.estado = '0'
        GROUP BY DATE_FORMAT(v.fecha ,'%Y-%m-%d %H')";*/

        $total_ventas = "SELECT  DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%h %p') AS fecha
                            ,SUM((dv.unidades * dv.descuento)) AS total_descuento
                            #,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto
                            ,SUM((dv.precio_venta * dv.unidades)) AS total_precio_venta
                            ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) + SUM((dv.precio_venta * dv.unidades)) AS total
                            FROM venta v
                            INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                            $inner
                            WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'
                            $condition $condition1 $alm
                            AND v.estado = '0'
                            GROUP BY DATE_FORMAT(v.fecha ,'%Y-%m-%d %H')
                            ORDER BY v.fecha ";
        /* Devoluciones (NC)*/
        $subtotal_devoluciones = 0;
        $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
                      SUM((IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                      SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM((dv.precio_venta * dv.unidades)) AS total_precio_venta
                      FROM venta v
                      INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                      LEFT JOIN devoluciones d ON v.factura=d.factura
                      WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                      AND  DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  AND v.estado = '0'
                      $condition
                      GROUP BY DATE_FORMAT(v.fecha ,'%Y-%m-%d %H') ORDER BY v.fecha";

        $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
        if (count($total_devoluciones_result) > 0) {
            foreach ($total_devoluciones_result as $devolucion) {
                $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
            }
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $total_saldo_a_favor = 0;
        foreach ($total_ventas_result as $value) {

            $q_saldo_favor = "SELECT vp.valor_entregado AS valor_entregado
                                    FROM ventas_pago AS vp
                                    INNER JOIN venta AS v ON vp.id_venta = v.id
                                    INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                                    WHERE vp.forma_pago = 'Saldo_a_Favor' and DATE_FORMAT(v.fecha ,'%Y-%m-%d %h %p') = '" . $value->fecha_dia . " " . $value->fecha . "' AND estado = 0 group BY dv.venta_id ";

            $saldo_a_favor_result = $this->connection->query($q_saldo_favor)->result();

            $subtotal_saldo_a_favor = 0;
            if ($saldo_a_favor_result) {
                foreach ($saldo_a_favor_result as $value1) {
                    $total_saldo_a_favor += $value1->valor_entregado;
                    $subtotal_saldo_a_favor += $value1->valor_entregado;
                }
            }

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia,
                'fecha' => $value->fecha,
                'total_descuento' => $value->total_descuento,
                //'total_impuesto' => $value->impuesto,
                'saldo_a_favor' => $subtotal_saldo_a_favor,
                'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento),
                // 'total_precio_venta' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto
                'total_precio_venta' => ($value->total - $value->total_descuento),
            );
        }

        return array(
            'total_ventas' => $ventas,
            'devoluciones' => $subtotal_devoluciones,
        );
    }

/*/////////////////////////////////////////////////////////////////////////////////////////////*/
    public function total_ventas_hora1($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $ventas = array();

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $condition = '';
        $condition1 = '';
        $inner = "";

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
        }

        $alm = '';
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }
        }

        $total_ventas = "SELECT DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha, '%h %p') AS fecha,
                                    SUM(ROUND(dv.unidades * dv.descuento)) AS total_descuento,
                                    SUM(ROUND((dv.precio_venta - dv.descuento)) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                                    SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
                                    FROM venta AS v
                                    INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                                    $inner
                                    WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  $condition $condition1 $alm AND estado = 0
                        GROUP BY DATE_FORMAT(v.fecha ,'%Y-%m-%d %H')";

        $total_ventas_result = $this->connection->query($total_ventas)->result();

        $total_saldo_a_favor = 0;
        foreach ($total_ventas_result as $value) {
            $q_saldo_favor = "SELECT vp.valor_entregado AS valor_entregado
                            FROM ventas_pago AS vp
                            INNER JOIN venta AS v ON vp.id_venta = v.id
                            INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                            WHERE vp.forma_pago = 'Saldo_a_Favor' and DATE_FORMAT(v.fecha ,'%Y-%m-%d %h %p') = '" . $value->fecha_dia . " " . $value->fecha . "' AND estado = 0 group BY dv.venta_id ";

            $saldo_a_favor_result = $this->connection->query($q_saldo_favor)->result();
            $subtotal_saldo_a_favor = 0;

            if ($saldo_a_favor_result) {
                foreach ($saldo_a_favor_result as $value1) {
                    $total_saldo_a_favor += $value1->valor_entregado;
                    $subtotal_saldo_a_favor += $value1->valor_entregado;
                }
            }

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia,
                'fecha' => $value->fecha,
                'total_descuento' => $value->total_descuento,
                'total_impuesto' => $value->impuesto,
                'saldo_a_favor' => $subtotal_saldo_a_favor,
                'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento),
                'total_precio_venta' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function ventas_categoria($fechainicial, $fechafinal, $almacen)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $ventas_categorias = array();

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            $query = "
            SELECT DATE(v.fecha) AS fecha, c.nombre,
            SUM(dv.unidades * dv.descuento) AS total_descuento,
            SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades) AS impuesto,
            SUM(dv.precio_venta * dv.unidades) AS total_precio_venta
            FROM  detalle_venta as dv
            inner join venta as v on dv.venta_id = v.id
            inner join producto as p on dv.producto_id = p.id
            inner join categoria as c on p.categoria_id=c.id
            WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal' and estado = 0 $condition
            GROUP BY p.categoria_id
            ORDER BY v.fecha desc";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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
            $query = "
            SELECT DATE(v.fecha) AS fecha, c.nombre,
            SUM(dv.unidades * dv.descuento) AS total_descuento,
            SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades) AS impuesto,
            SUM(dv.precio_venta * dv.unidades) AS total_precio_venta

            FROM  detalle_venta as dv
            inner join venta as v on dv.venta_id = v.id
            inner join producto as p on dv.producto_id = p.id
            inner join categoria as c on p.categoria_id=c.id
            WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal' and v.almacen_id = $almacen
            GROUP BY p.categoria_id
            ORDER BY v.fecha desc";

            $condition = " and  v.almacen_id = '$almacen' ";
        }

        $model = $this->connection->query($query)->result();
        //echo $this->connection->last_query();

        foreach ($model as $value) {
            /* Devoluciones (NC)*/

            $subtotal_devoluciones = 0;
            $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
                SUM(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento) AS total_descuento ,
                SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades) AS impuesto ,SUM(dv.precio_venta * dv.unidades) AS total_precio_venta
                FROM venta v
                INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                LEFT JOIN devoluciones d ON v.factura=d.factura
                inner join producto as p on dv.producto_id = p.id
                inner join categoria as c on p.categoria_id=c.id
                WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                AND DATE(v.fecha)  BETWEEN '$fechainicial'  AND  '$fechafinal' AND v.estado = '0'
                AND c.nombre = '$value->nombre' $condition";

            //echo $total_devoluciones;

            $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();

            //print_r($total_devoluciones_result);
            if (count($total_devoluciones_result) > 0) {
                foreach ($total_devoluciones_result as $devolucion) {
                    $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                }
            }

            $sql_productos = "
            SELECT DATE(v.fecha) AS fecha, c.nombre,
            SUM(dv.unidades * dv.descuento) AS total_descuento,
            SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades) AS impuesto,
            SUM(dv.precio_venta * dv.unidades) AS total_precio_venta,
            SUM(dv.unidades) AS unidades,
            dv.nombre_producto
            FROM  detalle_venta as dv
            inner join venta as v on dv.venta_id = v.id
            inner join producto as p on dv.producto_id = p.id
            inner join categoria as c on p.categoria_id=c.id
            WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal' AND v.estado <> '-1' AND c.nombre = '$value->nombre' $condition
            GROUP BY dv.nombre_producto
            ORDER BY v.fecha desc";

            $result_productos = $this->connection->query($sql_productos)->result();

            /*foreach ($result_productos as $producto) {
            $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
            }*/

            $ventas_categorias[] = array(
                'fecha' => $value->fecha,
                'categoria' => $value->nombre,
                'descripcion_productos' => $result_productos,
                'devoluciones' => $subtotal_devoluciones,
                'subtotal' => ($value->total_precio_venta - $value->total_descuento) - $subtotal_devoluciones,
                'total' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto - $subtotal_devoluciones,
            );
        }
        /* echo $query;
        exit; */
        return array(
            'ventas_categorias' => $ventas_categorias,
        );
    }

    public function export_erp($fechainicial, $fechafinal, $almacen, $ciudad)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $ventas = array();

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on venta.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $total_ventas = "
   SELECT
   venta.fecha as fechaventa, factura, nombre_producto, detalle_venta.precio_venta as precioventa, detalle_venta.unidades as unidades, almacen.vendedor, alm_equivalencia, und_negocio, prod_equivalencia_1, prod_equivalencia_2, prod_equivalencia_3,  almacen.ciudad as provincia, cod_geo, cod_mun
   FROM `detalle_venta`
    inner join venta on venta.id = detalle_venta.venta_id
	inner join almacen on almacen.id = venta.almacen_id
	inner join producto on producto.nombre = detalle_venta.nombre_producto
	inner join clientes on clientes.id_cliente = venta.cliente_id
	 WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' $condition $condition1 and estado = '0'";
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
            $total_ventas = "
   SELECT
   venta.fecha as fechaventa, factura, nombre_producto, detalle_venta.precio_venta as precioventa, detalle_venta.unidades as unidades, almacen.vendedor, alm_equivalencia, und_negocio, prod_equivalencia_1, prod_equivalencia_2, prod_equivalencia_3, almacen.ciudad as provincia , cod_geo, cod_mun
   FROM `detalle_venta`
    inner join venta on venta.id = detalle_venta.venta_id
	inner join almacen on almacen.id = venta.almacen_id
	inner join producto on producto.nombre = detalle_venta.nombre_producto
	inner join clientes on clientes.id_cliente = venta.cliente_id
	 WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'";
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $date = 0;
        $fecha = 0;
        foreach ($total_ventas_result as $value) {

            $date = date_create($value->fechaventa);
            $fecha = date_format($date, 'dmY');

            $ventas[] = array(
                'fechaventa' => $fecha
                , 'und_negocio' => $value->und_negocio
                , 'vendedor' => $value->vendedor
                , 'cod_geo' => $value->cod_geo
                , 'cod_mun' => $value->cod_mun
                , 'factura' => $value->factura
                , 'nombre_producto' => $value->nombre_producto
                , 'precioventa' => $value->precioventa
                , 'unidades' => $value->unidades
                , 'alm_equivalencia' => $value->alm_equivalencia
                , 'prod_equivalencia_1' => $value->prod_equivalencia_1
                , 'prod_equivalencia_2' => $value->prod_equivalencia_2
                , 'prod_equivalencia_3' => $value->prod_equivalencia_3
                , 'provincia' => $value->provincia,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function export_office($fechainicial, $fechafinal, $almacen)
    {
        $empresa = '';
        $cedula = 0;
        $user = $this->connection->query("SELECT * FROM `opciones` where id = '1'")->result();

        foreach ($user as $dat) {
            $empresa = $dat->valor_opcion;
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $nombre = $this->session->userdata('first_name');
        $email = $this->session->userdata('email');

        $ventas = array();

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            $total_ventas = "SELECT venta_id,  codigo_producto,
   venta.factura as numerofac, venta.fecha as fechaventa, clientes.nif_cif as nit, ventas_pago.forma_pago as formapago,
   nombre_producto, unidades, descuento, detalle_venta.precio_venta, detalle_venta.impuesto, estado, descripcion_producto, vendedor
   FROM `detalle_venta`
    inner join venta on venta.id = detalle_venta.venta_id
	inner join almacen on almacen.id = venta.almacen_id
	inner join clientes on clientes.id_cliente = venta.cliente_id
	   inner join ventas_pago on detalle_venta.venta_id = ventas_pago.id_venta
	 WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' $condition and estado = '0'";
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
            $total_ventas = "SELECT venta_id,  codigo_producto,
            venta.factura as numerofac, venta.fecha as fechaventa, clientes.nif_cif as nit, ventas_pago.forma_pago as formapago,
            nombre_producto, unidades, descuento, detalle_venta.precio_venta, detalle_venta.impuesto, estado, descripcion_producto, vendedor
            FROM `detalle_venta`
            inner join venta on venta.id = detalle_venta.venta_id
            inner join almacen on almacen.id = venta.almacen_id
            inner join clientes on clientes.id_cliente = venta.cliente_id
            inner join ventas_pago on detalle_venta.venta_id = ventas_pago.id_venta
            WHERE DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0'";
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();

        //  $date =  0; $fecha = 0;
        $date = 0;
        $fecha = 0;
        foreach ($total_ventas_result as $value) {

            $date = date_create($value->fechaventa);
            $fecha = date_format($date, 'd/m/Y');

            $total_precio_venta = '';
            $total_ventas = "SELECT DATE(`fecha`) AS fecha_dia
        	  ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
        	  ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
        	  ,SUM( `unidades` * `descuento` ) AS total_descuento
        	  ,SUM( `descripcion_producto` ) AS sobrecosto
        	  FROM  `venta`
        	  inner join detalle_venta on venta.id = detalle_venta.venta_id
        	  WHERE venta.id = '$value->venta_id' and nombre_producto <> 'PROPINA'";

            $total_ventas_result = $this->connection->query($total_ventas)->result();
            foreach ($total_ventas_result as $dat1) {

                $total_precio_venta = ((($dat1->total_precio_venta - $dat1->total_descuento) * $value->descripcion_producto) / 100);
            }

            $vendedor = "SELECT cedula FROM vendedor WHERE id = '$value->vendedor'";

            $vende = $this->connection->query($vendedor)->result();

            foreach ($vende as $key) {
                $cedula = $key->cedula;
            }

            $ventas[] = array(
                'venta_id' => $value->venta_id
                , 'numerofac' => $value->numerofac
                , 'fechaventa' => $fecha
                , 'nit' => $value->nit
                , 'formapago' => $value->formapago
                , 'nombre_producto' => $value->nombre_producto
                , 'codigo_producto' => $value->codigo_producto
                , 'unidades' => $value->unidades
                , 'descuento' => $value->descuento
                , 'precio_venta' => $value->precio_venta
                , 'impuesto' => $value->impuesto
                , 'estado' => $value->estado
                , 'empresa' => $empresa
                , 'total_precio_venta' => $total_precio_venta
                , 'nit_vendedor' => $cedula
                , 'descripcion_producto' => $value->descripcion_producto,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function export_propina($fechainicial, $fechafinal, $almacen)
    {
        if ($this->session->userdata('base_dato') == 'vendty2_db_54cb1c75472a1') {
            $ventas = array();

            $total_precio_venta = '';
            $nombre_comercial = '';

            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');

            if ($is_admin == 't' || $is_admin == 'a') { //administrador
                if ($almacen == '0') {
                    $condition = '';
                } else {
                    $condition = " and  almacen_id = '$almacen' ";
                }

                $total_ventas = "SELECT
	DATE(`fecha`) AS fecha_dia, cliente_id, venta.id, factura
	 ,ROUND(SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` )) AS impuesto
	 ,ROUND(SUM( `precio_venta` * `unidades` ) - SUM( `unidades` * `descuento` )) AS total_precio_venta
	  ,ROUND(SUM( `unidades` * `descuento` )) AS total_descuento
	  ,ROUND(SUM( `descripcion_producto` )) AS sobrecosto
	 FROM  `venta`
      inner join detalle_venta on venta.id = detalle_venta.venta_id
	  WHERE nombre_producto <> 'PROPINA' AND  DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' $condition and estado = '0' group by venta_id";
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
                $total_ventas = "SELECT
	DATE(`fecha`) AS fecha_dia, cliente_id, venta.id, factura
	 ,ROUND(SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` )) AS impuesto
     ,ROUND(SUM( `precio_venta` * `unidades` ) - SUM( `unidades` * `descuento` )) AS total_precio_venta
	  ,ROUND(SUM( `unidades` * `descuento` )) AS total_descuento
	  ,ROUND(SUM( `descripcion_producto` )) AS sobrecosto
	 FROM  `venta`
      inner join detalle_venta on venta.id = detalle_venta.venta_id
	  WHERE nombre_producto <> 'PROPINA' AND  DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = '0' group by venta_id";
            }

            $total_ventas_result = $this->connection->query($total_ventas)->result();
            foreach ($total_ventas_result as $dat1) {
                $propina_final = 0;
                //$propina2 = ($dat1->total_precio_venta * 0.03) ;

                $total_ventas1 = "SELECT descripcion_producto  FROM  `detalle_venta` where venta_id = '$dat1->id' and nombre_producto = 'PROPINA' ";
                $total_ventas_result1 = $this->connection->query($total_ventas1)->result();
                foreach ($total_ventas_result1 as $dat2) {

                    $propina_final = (($dat1->total_precio_venta * $dat2->descripcion_producto) / 100);
                }

                $sql1 = "SELECT *  FROM clientes where id_cliente = '$dat1->cliente_id' ";
                foreach ($this->connection->query($sql1)->result() as $value1) {
                    $nombre_comercial = $value1->nombre_comercial;
                }

                $ventas[] = array(
                    'venta_id' => $dat1->id
                    , 'numerofac' => $dat1->factura
                    , 'fechaventa' => $dat1->fecha_dia
                    , 'nombre_comercia' => $nombre_comercial
                    , 'propina_final' => round($propina_final)
                    , 'impuesto' => round($dat1->impuesto)
                    , 'total_precio_venta' => round(((($dat1->total_precio_venta) + $dat1->impuesto) + $propina_final)),
                );
            }

            return array(
                'total_ventas' => $ventas,
            );
        } else {
            $ventas = array();

            $total_precio_venta = '';
            $nombre_comercial = '';

            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');

            if ($is_admin == 't' || $is_admin == 'a') { //administrador
                if ($almacen == '0') {
                    $condition = '';
                } else {
                    $condition = " and  almacen_id = '$almacen' ";
                }
            }
            if ($is_admin != 't' && $is_admin != 'a') { //usuario
                //------------------------------------------------ almacen usuario
                $db_config_id = $this->session->userdata('db_config_id');
                $id_user = '';
                $alm = '';
                $condition = '';
                $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
                foreach ($user as $dat) {
                    $id_user = $dat->id;
                }

                $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
                foreach ($user as $dat) {
                    $alm = ' AND almacen_id =  ' . $dat->almacen_id;
                }
            }

            //---------------------------------------------
            $total_ventas = "SELECT
	DATE(`fecha`) AS fecha_dia, cliente_id, venta.id, factura
	 ,ROUND(SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` )) AS impuesto
	 ,ROUND(SUM( `precio_venta` * `unidades` )) AS total_precio_venta
	  ,ROUND(SUM( `unidades` * `descuento` )) AS total_descuento
	  ,ROUND(SUM( `descripcion_producto` )) AS sobrecosto
	 FROM  `venta`
      inner join detalle_venta on venta.id = detalle_venta.venta_id
	  WHERE nombre_producto <> 'PROPINA' AND  DATE(venta.fecha) >= '$fechainicial'  AND  DATE(venta.fecha) <= '$fechafinal' $condition $alm and estado = '0' group by venta_id";

            $total_ventas_result = $this->connection->query($total_ventas)->result();
            foreach ($total_ventas_result as $dat1) {
                $propina_final = 0;
                //$propina2 = ($dat1->total_precio_venta * 0.03) ;

                $total_ventas1 = "SELECT precio_venta  FROM  `detalle_venta` where venta_id = '$dat1->id' and nombre_producto = 'PROPINA' ";
                $total_ventas_result1 = $this->connection->query($total_ventas1)->result();
                foreach ($total_ventas_result1 as $dat2) {

                    $propina_final = $dat2->precio_venta;
                }

                $sql1 = "SELECT *  FROM clientes where id_cliente = '$dat1->cliente_id' ";
                foreach ($this->connection->query($sql1)->result() as $value1) {
                    $nombre_comercial = $value1->nombre_comercial;
                }

                $ventas[] = array(
                    'venta_id' => $dat1->id
                    , 'numerofac' => $dat1->factura
                    , 'fechaventa' => $dat1->fecha_dia
                    , 'nombre_comercia' => $nombre_comercial
                    , 'propina_final' => round($propina_final)
                    , 'impuesto' => round($dat1->impuesto)
                    , 'total_precio_venta' => round(((($dat1->total_precio_venta - $dat1->total_descuento) + $dat1->impuesto) + $propina_final)),
                );
            }

            return array(
                'total_ventas' => $ventas,
            );
        }
    }

    public function detalleventa($venta, $almacen = null)
    {
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');

        if ($precio_almacen == 1) {

            $this->connection->select("dv.codigo_producto,dv.nombre_producto,dv.unidades,dv.descuento,
            dv.precio_venta AS precio_venta_producto,dv.precio_venta AS precio_venta_venta,st.precio_compra,dv.impuesto,dv.descripcion_producto,cat.nombre AS nombre_categoria,pro.nombre_comercial AS proveedor");
            $this->connection->from("detalle_venta dv");
            $this->connection->join("stock_actual st", "dv.producto_id = st.producto_id", "left");
            $this->connection->join("producto p", "dv.producto_id = p.id", "left");
            $this->connection->join("categoria cat", "cat.id = p.categoria_id", "left");
            $this->connection->join("proveedores pro", "pro.id_proveedor = p.id_proveedor", "left");
            $this->connection->where("dv.venta_id", $venta);
            $this->connection->where("st.almacen_id", $almacen);
            $result = $this->connection->get();
        } else {
            $this->connection->select("dv.codigo_producto,dv.nombre_producto,dv.unidades,dv.descuento,
            dv.precio_venta AS precio_venta_producto,dv.precio_venta AS precio_venta_venta,p.precio_compra,dv.impuesto,dv.descripcion_producto,cat.nombre AS nombre_categoria,pro.nombre_comercial AS proveedor");
            $this->connection->from("detalle_venta dv");
            $this->connection->join("producto p", "dv.producto_id = p.id", "left");
            $this->connection->join("categoria cat", "cat.id = p.categoria_id", "left");
            $this->connection->join("proveedores pro", "pro.id_proveedor = p.id_proveedor", "left");
            $this->connection->where("dv.venta_id", $venta);
            $result = $this->connection->get();
        }

        $detalle_venta = $result->result();
        return $detalle_venta;
    }

    public function devolucionesinforme($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $ventas = array();

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        //decimales? decimales_moneda
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM opciones where nombre_opcion = 'decimales_moneda' ";
        $ocpresult = $this->connection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
            $decimales_moneda = $dat->valor_opcion;
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else { //$inner = " inner join almacen on venta.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
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

            $condition1 = "";
            $condition = " and  almacen_id = '$almacen' ";
        }

        //echo 'Inicio '.date('h:i:s');

        /**********************************/
        $subtotal_devoluciones = 0;
        $totaldevoluciones = 0;
        ####todos
        $total_devoluciones = "SELECT v.id, v.factura, v.fecha as fecha_factura, d.fecha as fecha_devolucion, d.valor AS total_devolucion, v.total_venta, vendedor.nombre as nombre_vendedor, almacen.nombre as nombre_almacen, clientes.nombre_comercial, clientes.telefono, clientes.nif_cif, clientes.movil
        FROM devoluciones d
        INNER JOIN venta v ON d.factura=v.factura
        LEFT JOIN vendedor ON v.vendedor = vendedor.id
        INNER JOIN almacen on v.almacen_id = almacen.id
        LEFT JOIN clientes on v.cliente_id = clientes.id_cliente
        WHERE DATE(d.fecha) BETWEEN '$fechainicial' AND '$fechafinal'
        AND v.estado = '0' $condition
        order by v.fecha
        ";

        $total_devoluciones = $this->connection->query($total_devoluciones)->result();
        
        return $total_devoluciones;

    }

    public function transacionesinforme($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $ventas = array();

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        //decimales? decimales_moneda
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM opciones where nombre_opcion = 'decimales_moneda' ";
        $ocpresult = $this->connection->query($ocp)->result();
        foreach ($ocpresult as $dat) {
            $decimales_moneda = $dat->valor_opcion;
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else { //$inner = " inner join almacen on venta.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
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

            $condition1 = "";
            $condition = " and  almacen_id = '$almacen' ";
        }

        //echo 'Inicio '.date('h:i:s');

        /**********************************/
        $subtotal_devoluciones = 0;
        $totaldevoluciones = 0;
        ####todos
        $total_devoluciones = "SELECT v.id, v.factura, v.fecha, SUM(d.valor) AS total_devolucion
       FROM devoluciones d
       INNER JOIN venta v ON d.factura=v.factura
       WHERE DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal'
       AND v.estado = '0' $condition
       GROUP BY v.factura";

        $total_devoluciones = $this->connection->query($total_devoluciones)->result();
        $idFactura = "";
        foreach ($total_devoluciones as $key1 => $value1) {

            $totaldevoluciones += $value1->total_devolucion;
            $idFactura .= $value1->id . ",";
        }

        $idFactura = trim($idFactura, ",");

        if (!empty($idFactura)) {
            $subtotales = "SELECT SUM((IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
               SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * (dv.unidades-SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1)))) AS impuesto ,
               SUM((dv.precio_venta * (dv.unidades-SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1)))) AS total_precio_venta
               FROM detalle_venta dv
               WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
               AND dv.venta_id IN($idFactura)";

            $devolucion = $this->connection->query($subtotales)->row();

            $subtotal_devoluciones = $devolucion->impuesto;
        }
        /******************************************** */

        ini_set('memory_limit', '-1');

        $this->connection->select("*");
        $this->connection->from("almacen");
        $this->connection->where("id", $almacen);
        $result = $this->connection->get();
        $nombre_almacen = $result->result()[0]->nombre;

        $where = "DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal' $condition AND  v.estado = 0";
        $this->connection->select("v.id,v.factura AS numerofac,v.usuario_id AS usuario,v.fecha AS fechaventa,c.nif_cif AS nit,c.nombre_comercial AS nombre_cliente,
                        v.vendedor AS vendedor1,v.vendedor_2 AS vendedor2,v.usuario_id AS usuario_id,c.telefono AS telefono,
                        c.movil AS telmovil, c.provincia, v.estado,v.nota,promo.nombre AS nombre_promocion, v.almacen_id");
        $this->connection->from("venta v");
        $this->connection->join("clientes c", "c.id_cliente = v.cliente_id", "left");
        $this->connection->join("promociones promo", "promo.id = v.promocion", "left");
        $this->connection->where($where);
        $result = $this->connection->get();
        $ventas = $result->result();

        for ($i = 0; $i < count($ventas); $i++) {
            /* Formas de pago por venta*/
            $this->connection->select("forma_pago,transaccion");
            $this->connection->from("ventas_pago vp");
            $this->connection->where("vp.id_venta", $ventas[$i]->id);
            $result = $this->connection->get();
            $formas_pago = $result->result();

            $datos_pago = '';
            $datos_transaccion = '';
            foreach ($formas_pago as $fpago) {
                $datos_pago .= "," . $fpago->forma_pago;
                $datos_transaccion .= "," . $fpago->transaccion;
            }

            //$ventas[$i]->formas_pago = $formas_pago;
            $vendedor1 = "";
            $vendedor2 = "";
            /*Vendedores por venta*/
            if ($ventas[$i]->vendedor1 != "") {
                $this->connection->select("nombre");
                $this->connection->from("vendedor ve");
                $this->connection->where("ve.id", $ventas[$i]->vendedor1);
                $this->connection->limit("1");
                $result = $this->connection->get();
                $vendedor1 = $result->result();
                $vendedor1 = $vendedor1[0]->nombre;
                //$ventas[$i]->vendedor1 = $vendedor1[0]->nombre;
            }
            if ($ventas[$i]->vendedor2 != "") {
                $this->connection->select("nombre");
                $this->connection->from("vendedor ve");
                $this->connection->where("ve.id", $ventas[$i]->vendedor2);
                $this->connection->limit("1");
                $result = $this->connection->get();
                $vendedor2 = $result->result();
                $vendedor2 = $vendedor2[0]->nombre;
                //$ventas[$i]->vendedor2 = $vendedor2[0]->nombre;
            }

            /*Datos del usuario que realizo la venta*/
            $this->db->select("username");
            $this->db->from("users us");
            $this->db->where("us.id", $ventas[$i]->usuario);
            $this->db->limit("1");
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                $usuario = $result->result();
                $username = $usuario[0]->username;
            } else {
                $username = "";
            }
            //$ventas[$i]->usuario = $usuario[0]->username;

            /*Traemos la informacion del detalle de la venta*/
            $detalle_venta = $this->detalleventa($ventas[$i]->id, $ventas[$i]->almacen_id);

            foreach ($detalle_venta as $value) {
                $cantidad_devueltas = 0;
                //cantidades devueltas
                if (($value->descripcion_producto != '0') && (!empty($value->descripcion_producto))) {

                    $descripcion = explode('cantidadSindevolver":', $value->descripcion_producto);

                    if (!empty($descripcion[1])) {
                        $descripcion2 = explode(',', $descripcion[1]);

                        if ($descripcion2[0] == 0) { //se devolvieron todas
                            $cantidad_devueltas += floatval($value->unidades);
                            //$subtotal+=0;
                        } else { //se devolvieron algunas
                            $cantidad_devueltas += floatval($value->unidades) - floatval($descripcion2[0]);
                        }
                    } else {
                        $cantidad_devueltas += 0;
                    }

                }
                if ($decimales_moneda == 0) {
                    $impuesto = round($value->impuesto);
                    $descuento = round($value->descuento);
                    $precio_venta_producto = round($value->precio_venta_producto);
                    $precio_venta_venta = round($value->precio_venta_venta);
                    $precio_venta_producto = round($value->precio_venta_producto);
                    $precio_compra = round($value->precio_compra);
                    $total_impuesto = round(($value->precio_venta_venta - $value->descuento) * $value->impuesto / 100 * $value->unidades);
                    $total_descuento = round($value->unidades * $value->descuento);
                    $total_precio_venta = round($value->precio_venta_venta * $value->unidades);
                    $subtotal_devoluciones = round($subtotal_devoluciones);
                    $totaldevoluciones = round($totaldevoluciones);
                    $subto = round(($total_precio_venta - $total_descuento) + $total_impuesto);
                } else {
                    $impuesto = ($value->impuesto);
                    $descuento = ($value->descuento);
                    $precio_venta_producto = ($value->precio_venta_producto);
                    $precio_venta_venta = ($value->precio_venta_venta);
                    $precio_venta_producto = ($value->precio_venta_producto);
                    $precio_compra = ($value->precio_compra);
                    $total_impuesto = (($value->precio_venta_venta - $value->descuento) * $value->impuesto / 100 * $value->unidades);
                    $total_descuento = ($value->unidades * $value->descuento);
                    $total_precio_venta = ($value->precio_venta_venta * $value->unidades);
                    $subto = (($total_precio_venta - $total_descuento) + $total_impuesto);
                }

                $response[] = array(
                    'nombre_almacen' => $nombre_almacen,
                    'nombre_cliente' => $ventas[$i]->nombre_cliente,
                    'nit' => $ventas[$i]->nit,
                    'telefono' => $ventas[$i]->telefono,
                    'telmovil' => $ventas[$i]->telmovil,
                    'numerofac' => $ventas[$i]->numerofac,
                    'codigo_producto' => $value->codigo_producto,
                    'nombre_producto' => $value->nombre_producto,
                    'unidades' => $value->unidades,
                    'unidades_devueltas' => $cantidad_devueltas,
                    'precio_venta_producto' => $precio_venta_producto,
                    'precio_venta_venta' => $precio_venta_venta,
                    'precio_compra' => $precio_compra,
                    'fechaventa' => $ventas[$i]->fechaventa,
                    //'ciudad' =>  '',
                    'ciudad' => $ventas[$i]->provincia,
                    //'vendedor' => (isset($vendedor1[0]->nombre))? $vendedor1[0]->nombre : '',
                    //'vendedor2' => (isset($vendedor2[0]->nombre))? $vendedor2[0]->nombre : '',
                    'vendedor' => $vendedor1,
                    'vendedor2' => $vendedor2,
                    'usuario' => $username,
                    'impuesto' => $impuesto,
                    'descuento' => $descuento,
                    'formas_pago' => trim($datos_pago, ","),
                    'total_impuesto' => $total_impuesto,
                    'total_descuento' => $total_descuento,
                    'total_precio_venta' => $total_precio_venta,
                    'subtotal' => $subto,
                    'transaccion' => trim($datos_transaccion, ","),
                    'nombre_promocion' => $ventas[$i]->nombre_promocion,
                    'nota_transaccion' => $ventas[$i]->nota,
                    'categoria' => $value->nombre_categoria,
                    'proveedor' => $value->proveedor,
                );

            }

        }

        return array(
            'total_ventas' => $response,
            'subtotaldevoluciones' => $subtotal_devoluciones,
            'devoluciones' => $totaldevoluciones,
        );

    }

    public function validate_index_ventas_pago()
    {
        $sql = "SHOW INDEX FROM ventas_pago WHERE Key_name = 'indice_ventas'";
        $result = $this->connection->query($sql);

        if ($result->num_rows() == 0) {
            $sql = "CREATE INDEX indice_ventas ON ventas_pago(id_venta)";
            $this->connection->query($sql);
        }
    }

    public function total_utilidad($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $is_admin = $this->session->userdata('is_admin');

        $username = $this->session->userdata('username');

        $ventas = array();

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $total_ventas = "SELECT  SUM( dv.margen_utilidad) AS total_margen_utilidad,
                            SUM( dv.unidades * dv.descuento ) AS total_descuento,
                            SUM((((dv.precio_venta - dv.descuento) * dv.impuesto) / 100) *  dv.unidades) AS impuesto,
                            SUM(dv.precio_venta * dv.unidades) AS total_precio_venta

                             FROM venta AS v
                             INNER JOIN detalle_venta AS dv ON v.id = dv.venta_id $inner
     WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  $condition $condition1   AND estado = 0";
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

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $total_ventas = "SELECT   SUM(dv.margen_utilidad) AS total_margen_utilidad,
                            SUM( dv.unidades * dv.descuento ) AS total_descuento,
                            SUM((((dv.precio_venta - dv.descuento) * dv.impuesto) / 100) *  dv.unidades) AS impuesto,
                            SUM(dv.precio_venta * dv.unidades) AS total_precio_venta

                             FROM venta AS v
                             INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id $inner
     WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  AND almacen_id =  '$almacen' $condition1   AND estado = 0";
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();

        foreach ($total_ventas_result as $value) {

            $ventas[] = array(
                'total_utilidad' => $this->opciones_model->formatoMonedaMostrar($value->total_margen_utilidad)
                , 'total_precio_venta' => $this->opciones_model->formatoMonedaMostrar(($value->total_precio_venta) + $value->impuesto - $value->total_descuento),
            );
        }

        return array(
            'total_ventas' => $ventas,
            'total_utilidad_informe' => $this->total_utilidad_informe($fechainicial, $fechafinal, $almacen, $ciudad),
        );
    }

    public function total_formas_pago($fechainicial, $fechafinal, $almacen, $ciudad, $forma_pago)
    {

        $is_admin = $this->session->userdata('is_admin');

        $username = $this->session->userdata('username');

        $ventas = array();
        $pagos = array();

        if ($forma_pago == '0') {
            $forma_condition = '';
            $forma_conditionpssf = '';
            $forma_conditioncredito = '';
        } else {
            $forma_condition = " and  vp.forma_pago = '$forma_pago' ";
            $forma_conditionpssf = " and  psp.forma_pago = '$forma_pago' ";
            $forma_conditioncredito = " and  p.tipo = '$forma_pago' ";
        }

        if ($almacen == '0') {
            $condition = '';
            $conditionpssf = '';
        } else {
            $condition = " and  v.almacen_id = '$almacen' ";
            $conditionpssf = " and  psf.almacen_id = '$almacen' ";
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $total_ventas = "SELECT v.factura, v.fecha AS fecha_factura, (select nombre_comercial from clientes where clientes.id_cliente = v.cliente_id) AS nom_cli, UPPER(vp.forma_pago) AS forma_pago, IF( UPPER(vp.forma_pago) = 'NOTA_CREDITO', (vp.valor_entregado - vp.cambio) * -1 , vp.valor_entregado - vp.cambio )  AS valor_recibido, al.nombre AS almacen
                    FROM venta AS v
                    INNER JOIN ventas_pago AS vp ON v.id = vp.id_venta
                    INNER JOIN almacen AS al ON (v.almacen_id = al.id)
					WHERE DATE(v.fecha) >= '$fechainicial' and DATE(v.fecha)  <=  '$fechafinal'  $condition $condition1 $forma_condition AND estado = 0
                    ORDER BY v.fecha
					";

            $total_ventas_forma_pago = "select v.id as id_venta, sum(vp.valor_entregado) - sum(vp.cambio)  as total_venta, count(vp.forma_pago) as cantidad, vp.forma_pago
            from ventas_pago  AS vp
            inner join venta AS v on vp.id_venta = v.id  $inner
            where  DATE(v.fecha) >= '$fechainicial' and DATE(v.fecha)  <=  '$fechafinal'   and estado='0' $condition $condition1 $forma_condition group by forma_pago  ORDER BY        cantidad";

            ##total_ventas_plan_separe_abonos_por_fuera_del_cierre
            $abonos_por_fuera_cierre = "SELECT v.factura, v.fecha AS fecha_factura, psp.fecha AS fecha_abono,
                (SELECT nombre_comercial FROM clientes WHERE clientes.id_cliente = v.cliente_id) AS nom_cli,
                UPPER(psp.forma_pago) AS forma_pago, IF( UPPER(psp.forma_pago) = 'NOTA_CREDITO', (psp.valor_entregado - psp.cambio) * -1 , psp.valor_entregado) AS valor_recibido,
                al.nombre AS almacen
                FROM venta AS v
                INNER JOIN plan_separe_factura psf ON v.`id`=psf.`venta_id`
                INNER JOIN plan_separe_pagos psp ON psf.`id`=psp.`id_venta`
                INNER JOIN almacen AS al ON (v.almacen_id = al.id)
                WHERE DATE(v.fecha) >= '$fechainicial' and DATE(v.fecha)  <=  '$fechafinal'  $condition $forma_conditionpssf AND v.estado = 0	AND DATE(psp.fecha) < DATE(v.fecha)
                ORDER BY v.fecha";

            ##abonos de plan separe que no tienen facturas asociadas
            $sql_abonos_plan_separe_sin_Facturas = "SELECT
                psf.factura AS numero_fact,
                IF((psf.factura='-'),CONCAT('Abono Plan Separe: ', psf.id),CONCAT('Abono Plan Separe Fact.: ', psf.factura)) AS factura,
                psf.`fecha` AS fecha_factura,
                (SELECT nombre_comercial FROM clientes WHERE clientes.id_cliente = psf.cliente_id) AS nom_cli,
                UPPER(psp.forma_pago) AS forma_pago,
                IF( UPPER(psp.forma_pago) = 'NOTA_CREDITO', (psp.valor_entregado) * -1 , psp.valor_entregado) AS valor_recibido,
                al.nombre AS almacen
                FROM plan_separe_pagos psp
                INNER JOIN plan_separe_factura psf ON psp.`id_venta`=psf.`id`
                INNER JOIN almacen AS al ON (psf.almacen_id = al.id)
                WHERE DATE(psp.fecha) BETWEEN '$fechainicial' AND '$fechafinal' $conditionpssf $forma_conditionpssf
                #AND psf.factura='-'
                AND psf.estado <> 3
                ORDER BY psp.fecha";

            $sql_abonos_creditos = "SELECT
                v.`factura` AS factura,
                v.`fecha` AS fecha_factura,
                (SELECT nombre_comercial FROM clientes WHERE clientes.id_cliente = v.cliente_id) AS nom_cli,
                UPPER(p.tipo) AS forma_pago,
                IF( UPPER(p.tipo) = 'NOTA_CREDITO', (p.cantidad) * -1 , p.cantidad) AS valor_recibido,
                al.nombre AS almacen
                FROM pago p
                INNER JOIN venta v ON p.`id_factura`=v.`id`
                INNER JOIN almacen AS al ON (v.almacen_id = al.id)
                #WHERE DATE(v.fecha) >= '$fechainicial' AND DATE(v.fecha)  <=  '$fechafinal'
                WHERE DATE(p.fecha_pago) >= '$fechainicial' AND DATE(p.fecha_pago)  <=  '$fechafinal'
                $condition $forma_conditioncredito AND v.estado = 0";
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

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $total_ventas = "SELECT v.factura, v.fecha AS fecha_factura,(select nombre_comercial from clientes where clientes.id_cliente = v.cliente_id) AS nom_cli, UPPER(vp.forma_pago) AS forma_pago, IF( UPPER(vp.forma_pago) = 'NOTA_CREDITO', (vp.valor_entregado - vp.cambio) * -1 , vp.valor_entregado - vp.cambio )  AS valor_recibido, al.nombre AS almacen
                FROM venta AS v
                INNER JOIN ventas_pago AS vp ON v.id = vp.id_venta
                INNER JOIN almacen AS al ON (v.almacen_id = al.id)
                WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'  $condition $condition1  and almacen_id =  '$almacen' $condition1 $forma_condition   AND estado = 0
                ORDER BY v.fecha ";

            $total_ventas_forma_pago = "select v.id as id_venta, sum(vp.valor_entregado) - sum(vp.cambio)  as total_venta, count(vp.forma_pago) as cantidad, vp.forma_pago
		    from ventas_pago  AS vp
            inner join venta AS v on vp.id_venta = v.id  $inner
            where  DATE(v.fecha) >= '$fechainicial' and DATE(v.fecha)  <=  '$fechafinal'   and estado='0'  and almacen_id =  '$almacen'  $condition $condition1 $forma_condition group by forma_pago  ORDER BY        cantidad";

            ##total_ventas_plan_separe_abonos_por_fuera_del_cierre
            $abonos_por_fuera_cierre = "SELECT v.factura, v.fecha AS fecha_factura, psp.fecha AS fecha_abono,
                (SELECT nombre_comercial FROM clientes WHERE clientes.id_cliente = v.cliente_id) AS nom_cli,
                UPPER(psp.forma_pago) AS forma_pago, IF( UPPER(psp.forma_pago) = 'NOTA_CREDITO', (psp.valor_entregado - psp.cambio) * -1 , psp.valor_entregado) AS valor_recibido,
                al.nombre AS almacen
                FROM venta AS v
                INNER JOIN plan_separe_factura psf ON v.`id`=psf.`venta_id`
                INNER JOIN plan_separe_pagos psp ON psf.`id`=psp.`id_venta`
                INNER JOIN almacen AS al ON (v.almacen_id = al.id)
                WHERE DATE(v.fecha) >= '$fechainicial' and DATE(v.fecha)  <=  '$fechafinal'  $condition $forma_conditionpssf AND v.estado = 0	AND DATE(psp.fecha) < DATE(v.fecha)
                ORDER BY v.fecha";

            ##abonos de plan separe que no tienen facturas asociadas
            $sql_abonos_plan_separe_sin_Facturas = "SELECT
                psf.factura AS numero_fact,
                IF((psf.factura='-'),CONCAT('Abono Plan Separe: ', psf.id),CONCAT('Abono Plan Separe Fact.: ', psf.factura)) AS factura,
                psf.`fecha` AS fecha_factura,
                (SELECT nombre_comercial FROM clientes WHERE clientes.id_cliente = psf.cliente_id) AS nom_cli,
                UPPER(psp.forma_pago) AS forma_pago,
                IF( UPPER(psp.forma_pago) = 'NOTA_CREDITO', (psp.valor_entregado) * -1 , psp.valor_entregado) AS valor_recibido,
                al.nombre AS almacen
                FROM plan_separe_pagos psp
                INNER JOIN plan_separe_factura psf ON psp.`id_venta`=psf.`id`
                INNER JOIN almacen AS al ON (psf.almacen_id = al.id)
                WHERE DATE(psp.fecha) BETWEEN '$fechainicial' AND '$fechafinal' $conditionpssf $forma_conditionpssf
                #AND psf.factura='-'
                AND psf.estado <> 3
                ORDER BY psp.fecha";

            $sql_abonos_creditos = "SELECT
                v.`factura` AS factura,
                v.`fecha` AS fecha_factura,
                (SELECT nombre_comercial FROM clientes WHERE clientes.id_cliente = v.cliente_id) AS nom_cli,
                UPPER(p.tipo) AS forma_pago,
                IF( UPPER(p.tipo) = 'NOTA_CREDITO', (p.cantidad) * -1 , p.cantidad) AS valor_recibido,
                al.nombre AS almacen
                FROM pago p
                INNER JOIN venta v ON p.`id_factura`=v.`id`
                INNER JOIN almacen AS al ON (v.almacen_id = al.id)
                #WHERE DATE(v.fecha) >= '$fechainicial' AND DATE(v.fecha)  <=  '$fechafinal'
                WHERE DATE(p.fecha_pago) >= '$fechainicial' AND DATE(p.fecha_pago)  <=  '$fechafinal'
                $condition $forma_conditioncredito AND v.estado = 0";
        }

        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $total_ventas_forma_pago_result = $this->connection->query($total_ventas_forma_pago)->result();
        $abonos_por_fuera_cierre_result = $this->connection->query($abonos_por_fuera_cierre)->result();
        $sql_abonos_plan_separe_sin_Facturas_result = $this->connection->query($sql_abonos_plan_separe_sin_Facturas)->result_array();
        $sql_abonos_creditos_result = $this->connection->query($sql_abonos_creditos)->result_array();

        //se quitan todos los registros de abonos plan separe incluidos que no pertenecen a las fechas indicadas
        foreach ($total_ventas_result as $value) {
            $abonos_por_fuera_cierre_result = array_values($abonos_por_fuera_cierre_result);
            $band = 0;

            for ($i = 0; $i < count($abonos_por_fuera_cierre_result); $i++) {
                $valor_pago = $value->valor_recibido;
                if (($value->factura == $abonos_por_fuera_cierre_result[$i]->factura) && ($value->nom_cli == $abonos_por_fuera_cierre_result[$i]->nom_cli) && ($value->forma_pago == $abonos_por_fuera_cierre_result[$i]->forma_pago) && ($value->almacen == $abonos_por_fuera_cierre_result[$i]->almacen) && ($value->valor_recibido == $abonos_por_fuera_cierre_result[$i]->valor_recibido)) {
                    $valor_pago += isset($pagos[$value->forma_pago]['total_venta']) ? $pagos[$value->forma_pago]['total_venta'] : 0;

                    $pagos[strtoupper($value->forma_pago)] = array(
                        'total_venta' => $valor_pago,
                        'forma_pago' => strtoupper($value->forma_pago),
                    );
                    unset($abonos_por_fuera_cierre_result[$i]);
                    $band = 1;
                }
            }

            if ($band == 0) {
                $ventas[] = array(
                    'factura' => $value->factura,
                    'nom_cli' => $value->nom_cli,
                    'fecha_factura' => $value->fecha_factura,
                    'forma_pago' => $value->forma_pago,
                    'valor_recibido' => $value->valor_recibido,
                    'almacen' => $value->almacen,
                );
            }
        }

        $eliminar = array();
        $quitar = array();

        //busco las facturas que son a credito y verificar si en ventas no hay un pago de una credito diferente de la forma de pago credito
        for ($i = 0; $i < count($ventas); $i++) {
            if ($ventas[$i]['forma_pago'] == 'CREDITO') {
                $eliminar[$ventas[$i]['factura']] = array(
                    'factura' => $ventas[$i]['factura'],
                    'valor_recibido' => $ventas[$i]['valor_recibido'],
                    'forma_pago' => $ventas[$i]['forma_pago'],
                );
            }
        }

        //elimino los diferentes al metodo credito
        foreach ($eliminar as $value) {
            $cantventa = count($ventas);
            $ventas = array_values($ventas);

            for ($i = 0; $i < $cantventa; $i++) {
                if (($value['factura'] == $ventas[$i]['factura']) && ($ventas[$i]['forma_pago'] != 'CREDITO')) {
                    $quitar[$ventas[$i]['forma_pago']] = array(
                        'valor_recibido' => isset($quitar[$ventas[$i]['forma_pago']]['valor_recibido']) ? $quitar[$ventas[$i]['forma_pago']]['valor_recibido'] + $ventas[$i]['valor_recibido'] : 0 + $ventas[$i]['valor_recibido'],
                        'forma_pago' => $ventas[$i]['forma_pago'],
                    );

                    unset($ventas[$i]);
                }
            }
        }

        //ingresar los abonos creditos
        foreach ($sql_abonos_creditos_result as $value) {
            $x = $value;
            $x['factura'] = 'Abono Crédito: ' . $value['factura'];
            array_push($ventas, $x);
        }

        $ventas = array_values($ventas);

        //Ingresar los abonos de plan separe que no tienen factura asociada y que estan dentro de las fechas.
        //Me paseo por los registros que bienen con abonos en plan separe para sacarlos si se crearon las dfacturas entre las fechas y nos los incluya 2 veces
        $cantsinf = count($sql_abonos_plan_separe_sin_Facturas_result);

        for ($i = 0; $i < $cantsinf; $i++) {
            $band = 0;
            foreach ($ventas as $venta) {
                //A la validación le agregue nuevos parámetros, para que no solo comparara por la factura, sino también por el “valor_recibido” y “forma_pago”.
                //if($sql_abonos_plan_separe_sin_Facturas_result[$i]['numero_fact']==$venta['factura']){
                if ($sql_abonos_plan_separe_sin_Facturas_result[$i]['numero_fact'] == $venta['factura'] && $sql_abonos_plan_separe_sin_Facturas_result[$i]['valor_recibido'] == $venta['valor_recibido'] && $sql_abonos_plan_separe_sin_Facturas_result[$i]['forma_pago'] == $venta['forma_pago']) {
                    $band = 1;
                    unset($sql_abonos_plan_separe_sin_Facturas_result[$i]);
                    break;
                }
            }

            if ($band == 0) {
                array_push($ventas, $sql_abonos_plan_separe_sin_Facturas_result[$i]);
            }
        }

        $sql_abonos_plan_separe_sin_Facturas_result = array_values($sql_abonos_plan_separe_sin_Facturas_result);

        for ($i = 0; $i < count($total_ventas_forma_pago_result); $i++) {
            //se resta del los totales, el total de los abonos que estan por fuera
            foreach ($pagos as $pago) {
                if ((strtoupper($total_ventas_forma_pago_result[$i]->forma_pago)) == (strtoupper($pago['forma_pago']))) {
                    $total_ventas_forma_pago_result[$i]->total_venta = $total_ventas_forma_pago_result[$i]->total_venta - $pago['total_venta'];
                }
            }

            //SUMAR LOS ABONOS SIN FACTURAS AL RESULTADO FINAL
            foreach ($sql_abonos_plan_separe_sin_Facturas_result as $value) {
                if ((strtoupper($total_ventas_forma_pago_result[$i]->forma_pago)) == (strtoupper($value['forma_pago']))) {
                    $total_ventas_forma_pago_result[$i]->total_venta = $total_ventas_forma_pago_result[$i]->total_venta + $value['valor_recibido'];
                    $total_ventas_forma_pago_result[$i]->cantidad = $total_ventas_forma_pago_result[$i]->cantidad + 1;
                }
            }

            //restar LOS ABONOS de los creditos repetidos AL RESULTADO FINAL
            foreach ($quitar as $value) {
                if ((strtoupper($total_ventas_forma_pago_result[$i]->forma_pago)) == (strtoupper($value['forma_pago']))) {
                    $total_ventas_forma_pago_result[$i]->total_venta = $total_ventas_forma_pago_result[$i]->total_venta - $value['valor_recibido'];
                    $total_ventas_forma_pago_result[$i]->cantidad = $total_ventas_forma_pago_result[$i]->cantidad - 1;
                }
            }

            //sumar los abono válidos al resultado final
            foreach ($sql_abonos_creditos_result as $value) {
                if ((strtoupper($total_ventas_forma_pago_result[$i]->forma_pago)) == (strtoupper($value['forma_pago']))) {
                    $total_ventas_forma_pago_result[$i]->total_venta = $total_ventas_forma_pago_result[$i]->total_venta + $value['valor_recibido'];
                    $total_ventas_forma_pago_result[$i]->cantidad = $total_ventas_forma_pago_result[$i]->cantidad + 1;
                }
            }
        }

        return array(
            'total_ventas' => $ventas
            , 'total_ventas_forma_pago_result' => $total_ventas_forma_pago_result,
        );
    }

    public function total_ventas_atributos($fechainicial, $fechafinal, $almacen, $ciudad, $post, $accion = false)
    {
        $db = $this->connection;
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $atributos_where = '';
        $ventas = array();
        $clasificaciones = [];
        $condiciones = array('between' => "DATE(v.fecha) between '$fechainicial' AND '$fechafinal' ");

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " AND almacen_id = '$almacen'";
            $condiciones['almacen_id'] = $almacen;
        }

        if ($ciudad == '') {
            $condition1 = '';
            $inner = "";
        } else {
            $inner = " INNER JOIN almacen ON v.almacen_id =  almacen.id ";
            if ($condition != '') {
                $condition1 = " AND ciudad = '" . $ciudad . "'";
            } else {
                $condition1 = " AND ciudad = '" . $ciudad . "'";
            }
        }

        $condicion_categoria = '';

        if ($post['id_categoria'] > 0) {
            $condicion_categoria = " AND p.categoria_id='" . $post['id_categoria'] . "' ";
            $condiciones['categoria_id'] = $post['id_categoria'];
        }

        //si no es administrador solo el almacen que tiene el usuario
        if ($is_admin != 't' && $is_admin != 'a') {
            $id_almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
            $condition = " AND almacen_id='$almacen'";
            $condiciones['almacen_id'] = $almacen;
        }

        //si no viene categoria toca traer todo  de atributos
        if ($post['id_categoria'] == 0) {
            $total_ventas = $this->consultar_ventas_productos_atributos_detalle($condiciones);
        } else {
            $total_ventas = $this->get_inventario_filtro_atributos($condiciones, $post, false);
        }

        $ventas['columnas'] = [
            'Almacen',
            'Categoria',
            'Producto',
            'Codigo',
            'Referencia',
            'Cantidad',
            'Precio Compra',
            'Precio Unitario',
            'Precio Total',
        ];

        foreach ($total_ventas as $value) {
            $ventas[$value->pk] = array(
                $value->almacen,
                $value->categoria,
                $value->nombre_producto,
                $value->codigo,
                $value->referencia,
                $value->cantidad,
                $this->opciones_model->formatoMonedaMostrar($value->precio_compra),
                $this->opciones_model->formatoMonedaMostrar($value->precio_venta),
                $this->opciones_model->formatoMonedaMostrar(((int) $value->precio_venta * (int) $value->cantidad)),
            );
        }

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function total_ventas_atributos_franquicias($fechainicial, $fechafinal, $almacen, $ciudad, $post, $conection = null)
    {
        $db = $conection['db'] ? $conection['db'] : $this->connection;
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $atributos = '';
        $ventas = array();
        $clasificaciones = [];
        $tiene_atributos = $db->table_exists('atributos');
        $i = 1;
        $or = false;

        foreach ($post as $nombre_campo => $valor) {
            if ($i >= 5) {
                $atributo = explode('_', $nombre_campo);

                if (count($atributo) > 2) {
                    if ($valor != '0') {
                        $or = true;
                        //cambiar a AND si la busqueda es estricta por atributo
                        $atributos .= ' (id_atributo = "' . $atributo[1] . '" AND nombre_clasificacion = "' . $valor . '") OR ';
                    }
                    array_push($clasificaciones, $atributo[2]);
                } else {
                    if ($valor != '0') {
                        $atributos .= $nombre_campo . " = '" . $valor . "' AND ";
                    }

                }
            }

            $i++;
        }

        if ($post['id_categoria'] == '0' && $tiene_atributos) {
            $clasificaciones = [];
            $q_clasificaciones = $this->connection->query('SELECT nombre FROM atributos ORDER BY nombre');
            if ($q_clasificaciones->num_rows() > 0) {
                foreach ($q_clasificaciones->result_array() as $clasificacion) {
                    if ($clasificacion['nombre'] != "Proveedor") {
                        array_push($clasificaciones, $clasificacion['nombre']);
                    }

                }
            }
        }

        if (!$or) {
            $atributos = substr($atributos, 0, -4);
        } else {
            $atributos = substr($atributos, 0, -3);
        }

        if ($atributos != '') {
            $atributos = " WHERE  " . $atributos;
        }

        if ($tiene_atributos) {
            $sql = 'SELECT codigo_interno FROM atributos_productos ' . $atributos . ' GROUP BY codigo_interno';
        } else {
            $sql = "SELECT id as codigo_interno FROM producto";
        }

        $data = array();
        $detalleventaid = 0;

        foreach ($this->connection->query($sql)->result() as $value1) {
            $detalleventaid = $detalleventaid . ", '" . $value1->codigo_interno . "'";
        }

        $rest1 = substr($detalleventaid, 2);

        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

        if ($tiene_atributos) {
            $sql = "SELECT * FROM producto WHERE codigo_barra in (" . $rest1 . ") ";
        } else {
            $sql = "SELECT * FROM producto";
        }

        $data = array();
        $detalleventaid = 0;

        foreach ($this->connection->query($sql)->result() as $value1) {
            $detalleventaid = $detalleventaid . ",'" . $value1->id . "'";
        }

        $rest1 = substr($detalleventaid, 2);

        if ($rest1 == '') {
            $rest1 = ' AND producto_id in (0)';
        } else {
            $rest1 = ' AND producto_id in (' . substr($detalleventaid, 2) . ')';
        }

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " AND almacen_id = '$almacen' ";
        }

        if ($ciudad == '') {
            $condition1 = '';
            $inner = "";
        } else {
            $inner = " INNER JOIN almacen ON v.almacen_id =  almacen.id ";
            $condition1 = " AND ciudad = '" . $ciudad . "' ";
        }

        $total_ventas = "SELECT
                p.`id` as pk,
                a.`nombre` as almacen,
                c.`nombre` as categoria,
                dv.`nombre_producto` as nombre_producto,
                p.`codigo`,
                p.`codigo_barra`,
                FORMAT(p.`precio_venta`, 2) as precio_venta,
                " . ($tiene_atributos ? "(SELECT ap.`referencia` FROM atributos_productos ap WHERE ap.`codigo_interno` = p.`codigo_barra` LIMIT 0, 1) AS referencia," : "") . "
                sum(dv.`unidades`) as cantidad,
                v.`fecha`
            FROM venta AS v
            INNER JOIN almacen AS a ON a.id = v.almacen_id
            INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
            INNER JOIN producto AS p ON dv.producto_id = p.id AND p.`id_proveedor` = '" . $conection['id_proveedor'] . "' AND p.`id_proveedor` <> 0
            INNER JOIN categoria AS c ON p.categoria_id = c.id
            $inner
            WHERE DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal' $condition $condition1 AND estado = 0 GROUP BY p.`id` ORDER BY a.`id`, c.`nombre`, p.`nombre` ASC";
        //var_dump($total_ventas);die();
        $total_result = $db->query($total_ventas);

        $ventas['columnas'] = [
            'Fecha',
            'Almacen',
            'Categoria',
            'Producto',
            'Codigo',
            'Referencia',
            'Cantidad',
            'Precio de venta',
        ];

        foreach ($clasificaciones as $clasificacion) {
            array_push($ventas['columnas'], $clasificacion);
        }

        if ($total_result->num_rows() > 0) {
            $total_ventas_result = $db->query($total_ventas)->result();

            foreach ($total_ventas_result as $value) {
                $ventas[$value->pk] = array(
                    $value->fecha,
                    $value->almacen,
                    $value->categoria,
                    $value->nombre_producto,
                    $value->codigo,
                    $tiene_atributos ? $value->referencia : '',
                    $value->cantidad,
                    $value->precio_venta,
                );

                foreach ($clasificaciones as $clasificacion) {
                    $query = 'SELECT nombre_clasificacion FROM atributos_productos WHERE nombre_atributo = "' . $clasificacion . '" AND codigo_interno = "' . $value->codigo_barra . '"';
                    $nombre_atributo = '';
                    if ($value->codigo_barra != '') {
                        $attr = $this->connection->query($query);

                        if ($attr->num_rows() > 0) {
                            $resultado = $attr->result_array();
                            $nombre_atributo = $resultado[0]['nombre_clasificacion'];
                        }
                    }

                    array_push($ventas[$value->pk], $nombre_atributo);
                }
            }
        }

        return array(
            $conection['nombre'] => $ventas,
        );
    }

    public function total_ventas_franquicias($fechainicial, $fechafinal, $almacen, $conection = null, $accion = true)
    {
        $db = $conection['db'] ? $conection['db'] : $this->connection;
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $ventas = array();
        $this->load->model('Opciones_model', 'opciones');

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " AND almacen_id = '$almacen' ";
        }

        $total_ventas = "SELECT
                p.`id` as pk,
                a.`nombre` as almacen,
                c.`nombre` as categoria,
                dv.`nombre_producto` as nombre_producto,
                p.`codigo`,
                p.`codigo_barra`,
                (p.`precio_venta`) as precio_venta,
                sum(dv.`unidades`) as cantidad,
                v.`fecha`
            FROM venta AS v
            INNER JOIN almacen AS a ON a.id = v.almacen_id
            INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
            INNER JOIN producto AS p ON dv.producto_id = p.id
            INNER JOIN categoria AS c ON p.categoria_id = c.id
            WHERE DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal' $condition AND estado = 0 GROUP BY p.`id` ORDER BY a.`id`, c.`nombre`, p.`nombre` ASC";
        $total_result = $db->query($total_ventas);

        $ventas['columnas'] = [
            'Fecha',
            'Almacen',
            'Categoria',
            'Producto',
            'Codigo',
            'Cantidad',
            'Precio de venta',
        ];

        if ($total_result->num_rows() > 0) {
            $total_ventas_result = $db->query($total_ventas)->result();

            foreach ($total_ventas_result as $value) {
                $ventas[$value->pk] = array(
                    $value->fecha,
                    $value->almacen,
                    $value->categoria,
                    $value->nombre_producto,
                    $value->codigo,
                    $value->cantidad,
                    ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                );
            }
        }

        return array(
            $conection['nombre'] => $ventas,
        );
    }

    public function total_inventario_franquicias($almacen, $conection = null, $accion = true)
    {
        $db = $conection['db'] ? $conection['db'] : "";
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $filtro = "Where producto.activo";

            if ($almacen != 0) {
                $filtro = " And almacen.id = " . $almacen;
            }

            $sql = "select almacen.nombre as almacen, producto.nombre as producto, producto.fecha_vencimiento as fvencimiento, producto.codigo, unidades.nombre as unidades_nombre, producto.precio_compra, producto.precio_venta, stock_actual.unidades, categoria.nombre as nom_cat, producto.ubicacion as ubicacion
                from producto
                inner join stock_actual on producto.id = stock_actual.producto_id
                inner join categoria on categoria.id = producto.categoria_id
                inner join unidades on unidades.id = producto.unidad_id
                inner join almacen on almacen.id = stock_actual.almacen_id  $filtro";
        }

        $this->load->model('Opciones_model', 'opciones');
        $data = array();
        $data['columnas'] = [
            'Almacen',
            'Categoria',
            'Producto',
            'Codigo',
            'Precio Compra',
            'Unidades',
            'Precio Venta',
        ];

        foreach ($db->query($sql)->result() as $value) {
            $data[] = array(
                $value->almacen,
                $value->nom_cat,
                $value->producto,
                $value->codigo,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_compra) : $value->precio_compra,
                $value->unidades,
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
            );
        }

        return array(
            $conection['nombre'] => $data,
        );
    }

    public function consultar_inventario_atributos($condicion)
    {
        $sql = "SELECT DISTINCT p.id as pk,a.nombre as almacen,c.nombre as categoria,p.nombre,p.codigo,p.codigo_barra,ap.referencia,sa.unidades as cantidad ";
        $sql .= ' FROM producto p ';
        $sql .= 'JOIN atributos_productos ap on (ap.codigo_interno=p.id) ';
        $sql .= 'JOIN categoria c on (c.id = p.categoria_id) ';
        $sql .= 'JOIN stock_actual sa on (sa.producto_id = p.id) ';
        $sql .= 'JOIN almacen a on (a.id=sa.almacen_id) ';

        if (count($condicion) > 0) {
            $i = 0;
            $sql .= " WHERE ";

            foreach ($condicion as $key => $value) {
                if ($key != 'or_where') {
                    $sql .= $key . " = '" . $value . "' AND ";
                } else {
                    $sql .= $value . ' AND ';
                }

            }

            $sql = substr($sql, 0, -4);
        }

        $query = $this->connection->query($sql);
        return $query->result();
    }

    public function get_inventario_filtro_atributos($condicion, $post, $consulta_stock = true)
    {
        $sub_query_pivot = array();
        $i = 1;
        $or = false;

        foreach ($post as $nombre_campo => $valor) {
            if ($i >= 3) {
                $atributo = explode('_', $nombre_campo);

                if (count($atributo) > 2) {
                    $letra = chr($i);
                    if ($valor != '-1') {
                        $or = true;
                        $sub_query_pivot[] = "(SELECT * FROM  atributos_productos WHERE CONCAT(id_atributo,'_',nombre_clasificacion ) = '" . $atributo[1] . '_' . $valor . "')";
                    }
                }
            }

            $i++;
        }

        if (!empty($sub_query_pivot)) {
            $sql = "select * FROM ";
            $i_letra = 65;
            $arreglo_letras = array();

            foreach ($sub_query_pivot as $key => $value) {
                $letra = chr($i_letra);
                $arreglo_letras[$i_letra] = $letra;

                if ($key == 0) {
                    $sql .= $value . ' ' . $letra . ' INNER JOIN ';
                } else {
                    $posicion_anterior = $i_letra - 1;
                    $sql .= $value . ' ' . $letra . ' ON ' . $arreglo_letras[$posicion_anterior] . '.codigo_barras =' . $letra . '.codigo_barras INNER JOIN ';
                }

                $i_letra++;
            }

            $sql = substr($sql, 0, -11);
            $sql .= ' GROUP BY ' . $arreglo_letras[$i_letra - 1] . '.codigo_barras';
            $atributos_encontrados = $this->connection->query($sql);

            if ($atributos_encontrados->num_rows() > 0) {
                $str_or_where = '( ';

                foreach ($atributos_encontrados->result() as $key => $value) {
                    $str_or_where .= " p.codigo ='" . $value->codigo_barras . "' OR";
                }

                $str_or_where = substr($str_or_where, 0, -3);
                $str_or_where .= ')';
                $condicion['or_where'] = $str_or_where;
            }
        }

        //si es el estock o es ventas
        if ($consulta_stock) {
            return $this->consultar_inventario_atributos($condicion);
        } else {
            return $this->consultar_ventas_productos_atributos_detalle($condicion);
        }
    }

    public function consultar_ventas_productos_atributos_detalle($condicion)
    {
        $sql = "SELECT p.`id` as pk,a.`nombre` as almacen, c.`nombre` as categoria, dv.`nombre_producto`,";
        $sql .= "p.`codigo`,p.`codigo_barra`,p.`precio_venta` as precio_venta, p.`precio_compra` as precio_compra, ";
        $sql .= "(SELECT ap.`referencia` FROM atributos_productos ap WHERE ap.`codigo_interno` = p.`codigo_barra` LIMIT 0, 1) AS referencia,";
        $sql .= "sum(dv.`unidades`) as cantidad ";
        $sql .= "FROM venta AS v ";
        $sql .= "LEFT JOIN almacen AS a ON a.id = v.almacen_id ";
        $sql .= "INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id ";
        $sql .= "INNER JOIN producto AS p ON dv.producto_id = p.id ";
        $sql .= "LEFT JOIN categoria AS c ON p.categoria_id = c.id ";

        if (count($condicion) > 0) {
            $i = 0;
            $sql .= " WHERE ";

            foreach ($condicion as $key => $value) {
                if ($key != 'or_where' and $key != 'between') {
                    $sql .= $key . " = '" . $value . "' AND ";
                } else {
                    $sql .= $value . ' AND ';
                }
            }

            $sql = substr($sql, 0, -4);
        }

        $sql .= "AND NOT v.id IN (SELECT venta_id FROM ventas_anuladas)";
        $sql .= " GROUP BY p.`id` ORDER BY  a.`id`, c.`nombre`, p.`nombre` ASC";
        //var_dump($sql);die();
        $query = $this->connection->query($sql);
        return $query->result();
    }

    public function total_inventario_atributos($almacen, $ciudad, $post)
    {
        $db = $this->connection;
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $atributos_where = '';
        $ventas = array();
        $clasificaciones = [];
        $condiciones = array();
        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " AND almacen_id = '$almacen'";
            $condiciones['almacen_id'] = $almacen;
        }

        if ($ciudad == '') {
            $condition1 = '';
            $inner = "";
        } else {
            $inner = " INNER JOIN almacen ON v.almacen_id =  almacen.id ";
            if ($condition != '') {
                $condition1 = " AND ciudad = '" . $ciudad . "'";
            } else {
                $condition1 = " AND ciudad = '" . $ciudad . "'";
            }

        }

        $condicion_categoria = '';

        if ($post['id_categoria'] > 0) {
            $condicion_categoria = " AND p.categoria_id='" . $post['id_categoria'] . "' ";
            $condiciones['categoria_id'] = $post['id_categoria'];
        }

        //si no es administrador solo el almacen que tiene el usuario
        if ($is_admin != 't' && $is_admin != 'a') {
            //usuario
            //------------------------------------------------ almacen usuario
            $id_almacen = $this->almacen->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
            $condition = " AND almacen_id='$almacen'";
            $condiciones['almacen_id'] = $almacen;
        }

        //si no viene categoria toca traer todo el inventario de atributos
        if ($post['id_categoria'] == 0) {
            $total_ventas = $this->consultar_inventario_atributos($condiciones);
        } else {
            $total_ventas = $this->get_inventario_filtro_atributos($condiciones, $post);
        }

        $ventas['columnas'] = [
            'Almacen',
            'Categoria',
            'Producto',
            'Codigo',
            'Referencia',
            'Cantidad',
        ];

        foreach ($total_ventas as $value) {
            $ventas[$value->pk] = array(
                $value->almacen,
                $value->categoria,
                $value->nombre,
                $value->codigo,
                $value->referencia,
                $value->cantidad,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );

        //borrar
        /* $i = 1;
    $or = false;
    foreach ($post as $nombre_campo => $valor) {
    if ($i >= 3) {
    $atributo = explode('_', $nombre_campo);

    if (count($atributo) >= 2) {
    if ($valor != '-1') {
    $or = true;
    //cambiar a AND si la busqueda es estricta por atributo
    $atributos_where .= ' (id_atributo = "' . $atributo[1] . '" AND ( nombre_clasificacion = "' . $valor . '" OR nombre_atributo ="'.$valor.'" ) ) OR ';
    }
    array_push($clasificaciones, $atributo[2]);
    } else {
    if ($valor != '-1')
    $atributos_where .= $nombre_campo . " = '" . $valor . "' AND ";
    }
    }
    $i++;
    }
    //echo $atributos_where;die();
    if ($post['id_categoria'] == '0') {
    $clasificaciones = [];
    $q_clasificaciones = $db->query('SELECT nombre FROM atributos ORDER BY nombre');
    if ($q_clasificaciones->num_rows() > 0) {
    foreach ($q_clasificaciones->result_array() as $clasificacion) {
    array_push($clasificaciones, $clasificacion['nombre']);
    }
    }
    }

    if (!$or)
    $atributos_where = substr($atributos_where, 0, -4);
    else
    $atributos_where = substr($atributos_where, 0, -3);

    if ($atributos_where != '') {
    $atributos_where = " WHERE  " . $atributos_where;
    }

    $sql = 'SELECT codigo_barras FROM atributos_productos ' . $atributos_where . ' GROUP BY codigo_barras';
    //var_dump($sql);die();
    $data = array();
    $detalleventaid = 0;
    foreach ($db->query($sql)->result() as $value1) {
    $detalleventaid = $detalleventaid . ", '" . $value1->codigo_barras . "'";
    }

    $rest1 = substr($detalleventaid, 2);
    if ($rest1 == '') {
    $rest1 = 0;
    } else {
    $rest1 = substr($detalleventaid, 2);
    }

    $sql = "SELECT * FROM producto WHERE codigo in (" . $rest1 . ") ";
    //var_dump($sql);die();
    $data = array();
    $detalleventaid = 0;
    foreach ($db->query($sql)->result() as $value1) {
    $detalleventaid = $detalleventaid . ",'" . $value1->id . "'";
    }

    $rest1 = substr($detalleventaid, 2);
    if ($rest1 == '') {
    $rest1 = ' producto_id in (0)';
    } else {
    $rest1 = ' producto_id in (' . substr($detalleventaid, 2) . ')';
    }
    //var_dump($rest1);die();
    if ($is_admin == 't' || $is_admin == 'a') {
    if ($almacen == '0') {
    $condition = '';
    } else {
    $condition = " AND almacen_id = '$almacen'";
    }

    if ($ciudad == '') {
    $condition1 = '';
    $inner = "";
    } else {
    $inner = " INNER JOIN almacen ON v.almacen_id =  almacen.id ";
    if ($condition != '')
    $condition1 = " AND ciudad = '" . $ciudad . "'";
    else
    $condition1 = " AND ciudad = '" . $ciudad . "'";
    }
    $condicion_categoria = '';
    if ($post['id_categoria'] > 0) {
    $condicion_categoria =" AND p.categoria_id='".$post['id_categoria']."' ";
    }

    $total_ventas = "SELECT
    p.`id` as pk,
    a.`nombre` as almacen,
    c.`nombre` as categoria,
    p.`nombre`,
    p.`codigo`,
    p.`codigo_barra`,
    (SELECT ap.`referencia` FROM atributos_productos ap WHERE ap.`codigo_interno` = p.`codigo_barra` LIMIT 0, 1) AS referencia,
    sa.`unidades` as cantidad ..
    FROM producto as p
    LEFT JOIN atributos_productos as ap ON ap.codigo_interno = p.codigo_barra
    LEFT JOIN categoria AS c ON p.categoria_id = c.id
    LEFT JOIN stock_actual as sa ON p.id = sa.producto_id
    LEFT JOIN almacen AS a ON a.id = sa.almacen_id
    $inner
    WHERE $rest1 $condition $condition1 $condicion_categoria GROUP BY a.`id`, p.`id` ORDER BY p.`id`, c.`nombre`, p.`nombre` ASC";
    }

    if ($is_admin != 't' && $is_admin != 'a') {
    //usuario
    //------------------------------------------------ almacen usuario
    $db_config_id = $this->session->userdata('db_config_id');
    $id_user = '';
    $almacen = '';
    $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
    foreach ($user as $dat) {
    $id_user = $dat->id;
    }

    $user = $db->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
    foreach ($user as $dat) {
    $almacen = $dat->almacen_id;
    }

    if ($ciudad == '') {
    $condition1 = '';
    $inner = "";
    } else {
    $inner = " inner join almacen on v.almacen_id = almacen.id ";
    $condition1 = " AND ciudad = '" . $ciudad . "'";
    }

    $condicion_categoria = '';
    if ($post['id_categoria'] > 0) {
    $condicion_categoria =" AND p.categoria_id='".$post['id_categoria']."' ";
    }

    $total_ventas = "SELECT
    p.`id` as pk,
    a.`nombre` as almacen,
    c.`nombre` as categoria,
    p.`nombre`,
    p.`codigo`,
    p.`codigo_barra`,
    (SELECT ap.`referencia` FROM atributos_productos ap WHERE ap.`codigo_interno` = p.`codigo_barra` LIMIT 0, 1) AS referencia,
    sa.`unidades` as cantidad
    FROM producto as p
    LEFT JOIN atributos_productos as ap ON ap.codigo_interno = p.codigo_barra
    LEFT JOIN categoria AS c ON p.categoria_id = c.id
    LEFT JOIN stock_actual as sa ON p.id = sa.producto_id
    LEFT JOIN almacen AS a ON a.id = sa.almacen_id
    $inner
    WHERE $rest1 $condition1 $condicion_categoria AND almacen_id = '$almacen' GROUP BY a.`id`, p.`id` ORDER BY ORDER BY p.`id`, c.`nombre`, p.`nombre` ASC";
    }

    $total_ventas_result = $db->query($total_ventas)->result();

    $ventas['columnas'] = [
    'Almacen',
    'Categoria',
    'Producto',
    'Codigo',
    'Referencia',
    'Cantidad'
    ];

    foreach ($clasificaciones as $clasificacion) {
    array_push($ventas['columnas'], $clasificacion);
    }

    foreach ($total_ventas_result as $value) {
    $ventas[$value->pk] = array(
    $value->almacen,
    $value->categoria,
    $value->nombre,
    $value->codigo,
    $value->referencia,
    $value->cantidad
    );

    foreach ($clasificaciones as $clasificacion) {
    $query = 'SELECT nombre_clasificacion FROM atributos_productos WHERE nombre_atributo = "' . $clasificacion . '" AND codigo_interno = "' . $value->codigo_barra . '"';
    $nombre_atributo = '';
    if ($value->codigo_barra != '') {
    $attr = $db->query($query);

    if ($attr->num_rows() > 0) {
    $resultado = $attr->result_array();
    $nombre_atributo = $resultado[0]['nombre_clasificacion'];
    }
    }

    array_push($ventas[$value->pk], $nombre_atributo);
    }
    }

    return array(
    'total_ventas' => $ventas
    );*/

    }

    public function total_inventario_atributos_franquicias($almacen, $ciudad, $post, $conection = null)
    {
        $db = $conection['db'] ? $conection['db'] : $this->connection;

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $atributos = '';
        $ventas = array();
        $clasificaciones = [];
        $tiene_atributos = $db->table_exists('atributos');
        $i = 1;
        $or = false;

        foreach ($post as $nombre_campo => $valor) {
            if ($i >= 3) {
                $atributo = explode('_', $nombre_campo);

                if (count($atributo) > 2) {
                    if ($valor != '0') {
                        $or = true;
                        //cambiar a AND si la busqueda es estricta por atributo
                        $atributos .= ' (id_atributo = "' . $atributo[1] . '" AND nombre_clasificacion = "' . $valor . '") OR ';
                    }
                    array_push($clasificaciones, $atributo[2]);
                } else {
                    if ($valor != '0') {
                        $atributos .= $nombre_campo . " = '" . $valor . "' AND ";
                    }

                }
            }

            $i++;
        }

        if ($post['id_categoria'] == '0' && $tiene_atributos) {
            $clasificaciones = [];
            $q_clasificaciones = $db->query('SELECT nombre FROM atributos ORDER BY nombre');
            if ($q_clasificaciones->num_rows() > 0) {
                foreach ($q_clasificaciones->result_array() as $clasificacion) {
                    array_push($clasificaciones, $clasificacion['nombre']);
                }
            }
        }

        if (!$or) {
            $atributos = substr($atributos, 0, -4);
        } else {
            $atributos = substr($atributos, 0, -3);
        }

        if ($atributos != '') {
            $atributos = " WHERE  " . $atributos;
        }

        if ($tiene_atributos) {
            $sql = 'SELECT codigo_interno FROM atributos_productos ' . $atributos . ' GROUP BY codigo_interno';
        } else {
            $sql = "SELECT id as codigo_interno FROM producto";
        }

        $data = array();
        $detalleventaid = 0;
        foreach ($db->query($sql)->result() as $value1) {
            $detalleventaid = $detalleventaid . ", '" . $value1->codigo_interno . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

        if ($tiene_atributos) {
            $sql = "SELECT * FROM producto WHERE codigo_barra in (" . $rest1 . ") ";
        } else {
            $sql = "SELECT * FROM producto";
        }

        $data = array();
        $detalleventaid = 0;
        foreach ($db->query($sql)->result() as $value1) {
            $detalleventaid = $detalleventaid . ",'" . $value1->id . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = ' producto_id in (0)';
        } else {
            $rest1 = ' producto_id in (' . substr($detalleventaid, 2) . ')';
        }

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " AND almacen_id = '$almacen'";
        }

        if ($ciudad == '') {
            $condition1 = '';
            $inner = "";
        } else {
            $inner = " INNER JOIN almacen ON v.almacen_id =  almacen.id ";
            if ($condition != '') {
                $condition1 = " AND ciudad = '" . $ciudad . "'";
            } else {
                $condition1 = " AND ciudad = '" . $ciudad . "'";
            }

        }

        $total_ventas = "SELECT
                            p.`id` as pk,
                            a.`nombre` as almacen,
                            c.`nombre` as categoria,
                            p.`nombre`,
                            p.`codigo`,
                            p.`codigo_barra`,
                            " . ($tiene_atributos ? "(SELECT ap.`referencia` FROM atributos_productos ap WHERE ap.`codigo_interno` = p.`codigo_barra` LIMIT 0, 1) AS referencia," : "") . "
                            sa.`unidades` as cantidad
                        FROM producto as p
                        " . ($tiene_atributos ? "LEFT JOIN atributos_productos as ap ON ap.codigo_interno = p.codigo_barra" : "") . "
                        LEFT JOIN categoria AS c ON p.categoria_id = c.id
                        LEFT JOIN stock_actual as sa ON p.id = sa.producto_id
                        LEFT JOIN almacen AS a ON a.id = sa.almacen_id
                        $inner
                        WHERE $rest1 $condition $condition1 AND p.`id_proveedor` = '" . $conection['id_proveedor'] . "' AND p.`id_proveedor` <> 0 GROUP BY a.`id`, p.`id` ORDER BY p.`id`, c.`nombre`, p.`nombre` ASC";
        //var_dump($total_ventas);die();
        $total_result = $db->query($total_ventas);

        $ventas['columnas'] = [
            'Almacen',
            'Categoria',
            'Producto',
            'Codigo',
            'Referencia',
            'Cantidad',
        ];

        foreach ($clasificaciones as $clasificacion) {
            array_push($ventas['columnas'], $clasificacion);
        }

        if ($total_result->num_rows() > 0) {
            $total_ventas_result = $total_result->result();
            foreach ($total_ventas_result as $value) {
                $ventas[$value->pk] = array(
                    $value->almacen,
                    $value->categoria,
                    $value->nombre,
                    $value->codigo,
                    $tiene_atributos ? $value->referencia : '',
                    $value->cantidad,
                );

                foreach ($clasificaciones as $clasificacion) {
                    $query = 'SELECT nombre_clasificacion FROM atributos_productos WHERE nombre_atributo = "' . $clasificacion . '" AND codigo_interno = "' . $value->codigo_barra . '"';
                    $nombre_atributo = '';
                    if ($value->codigo_barra != '') {
                        $attr = $db->query($query);

                        if ($attr->num_rows() > 0) {
                            $resultado = $attr->result_array();
                            $nombre_atributo = $resultado[0]['nombre_clasificacion'];
                        }
                    }

                    array_push($ventas[$value->pk], $nombre_atributo);
                }
            }
        }

        return array(
            $conection['nombre'] => $ventas,
        );
    }

    public function total_ventas_dia($fechainicial, $almacen, $ciudad)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $ventas = array();
        $inner = "";
        $condition = '';
        $condition1 = '';

        if ($is_admin == 't' || $is_admin == 'a') {
            //administrador
            if (!empty($almacen)) {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if (!empty($ciudad)) {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
        }

        $alm = '';

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }
        }

        /* $total_ventas = "SELECT DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha,
        IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) AS cantidad,
        SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento,
        SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ))) AS impuesto,
        SUM(ROUND(dv.precio_venta * IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ))) AS total_precio_venta
        FROM venta AS v
        INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id $inner
        WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'   $condition $condition1 $alm   AND estado = 0
        AND (dv.descripcion_producto = '0' OR dv.descripcion_producto = '')
        GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY fecha_dia";*/
        $total_ventas = "SELECT  DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha
        ,SUM(ROUND(dv.unidades * dv.descuento)) AS total_descuento
        ,SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto
        ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
        ,SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) + SUM(ROUND(dv.precio_venta * dv.unidades)) AS total
         FROM venta v
        INNER JOIN detalle_venta dv ON v.id=dv.venta_id
        WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechainicial'
        $condition
        AND v.estado = '0'
        GROUP BY DATE_FORMAT(v.fecha ,'%d')
        ORDER BY v.fecha ";

        //$saldo_favor=0;
        $total_ventas_result = $this->connection->query($total_ventas)->result();

        /* Devoluciones (NC)*/
        $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
        SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
        SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
        FROM venta v
        INNER JOIN detalle_venta dv ON v.id=dv.venta_id
        LEFT JOIN devoluciones d ON v.factura=d.factura
        WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
        AND DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechainicial' AND v.estado = '0'
        $condition
        GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY v.fecha";

        $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
        $subtotal_devoluciones = 0;
        foreach ($total_devoluciones_result as $value) {
            $subtotal_devoluciones += ($value->total_precio_venta - $value->total_descuento) + $value->impuesto;
        }

        $total_saldo_a_favor = 0;
        foreach ($total_ventas_result as $value) {
            $q_saldo_favor = "SELECT vp.valor_entregado AS valor_entregado
                            FROM ventas_pago AS vp
                            INNER JOIN venta AS v ON vp.id_venta = v.id
                            INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                            WHERE vp.forma_pago = 'Saldo_a_Favor' and DATE_FORMAT(v.fecha ,'%Y-%m-%d') = '" . $value->fecha_dia . "' AND estado = 0 AND dv.descripcion_producto = 0 group BY dv.venta_id ";

            $saldo_a_favor_result = $this->connection->query($q_saldo_favor)->result();

            $subtotal_saldo_a_favor = 0;
            if ($saldo_a_favor_result) {
                foreach ($saldo_a_favor_result as $value1) {
                    $total_saldo_a_favor += $value1->valor_entregado;
                    $subtotal_saldo_a_favor += $value1->valor_entregado;
                }
            }

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia,
                'total_descuento' => $value->total_descuento,
                'total_impuesto' => $value->impuesto,
                'devoluciones' => $subtotal_devoluciones,
                'saldo_a_favor' => $subtotal_saldo_a_favor,
                'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento) - $subtotal_devoluciones,
                'total_precio_venta' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto - $subtotal_devoluciones,
            );
        }

        return array('total_ventas' => $ventas);
    }

    public function total_saldo_clientes($cliente)
    {

        $total_ventas = "SELECT  venta.id, SUM( dv.unidades * dv.descuento ) AS total_descuento,
                       SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                      (SELECT nombre_comercial FROM clientes WHERE id_cliente = venta.cliente_id) AS nombre_cliente
					 FROM venta
                     INNER JOIN detalle_venta AS dv ON venta.id=dv.venta_id
					 LEFT JOIN ventas_pago on ventas_pago.id_venta = venta.id
					 WHERE  cliente_id = '$cliente' and ventas_pago.forma_pago = 'Credito' AND
                    venta.id NOT IN (SELECT venta_id FROM ventas_anuladas) ";

        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $total_ventas_cliente = 0;
        foreach ($total_ventas_result as $value) {

            $nombre = $value->nombre_cliente;

            $total_ventas_cliente += (($value->total_precio_venta - $value->total_descuento) + $value->impuesto);
        }

        $sql = "SELECT id FROM ventas_pago INNER JOIN venta ON venta.id = ventas_pago.id_venta  where cliente_id = '$cliente' and  ventas_pago.forma_pago = 'Credito'  group by id_venta  ";
        $data = array();
        $detalleventaid = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

        $total_saldo = 0;
        $sql2 = "SELECT sum(cantidad) as saldo FROM pago where id_factura IN (" . $rest1 . ") ";
        foreach ($this->connection->query($sql2)->result() as $value2) {
            $total_saldo += $value2->saldo;
        }

        $ventas[] = array(
            'nombre_cliente' => $nombre
            , 'saldo_cliente' => $total_saldo
            , 'total_venta' => ($total_ventas_cliente),
        );

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function total_saldo_proveedor($proveedor)
    {

        $total_ventas_cliente = 0;
        $total_saldo = 0;
        $total_ventas = "SELECT  orden_compra.id, SUM( dv.unidades * dv.descuento ) AS total_descuento,
                       SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                        SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                      (SELECT nombre_comercial FROM  proveedores WHERE id_proveedor = orden_compra.cliente_id) AS nombre_proveedor
					 FROM orden_compra
                     INNER JOIN detalle_orden_compra AS dv ON orden_compra.id=dv.venta_id
					 WHERE  cliente_id = '$proveedor'  ";

        $total_ventas_result = $this->connection->query($total_ventas)->result();

        foreach ($total_ventas_result as $value) {

            $nombre = $value->nombre_proveedor;

            $total_ventas_cliente += (($value->total_precio_venta - $value->total_descuento) + $value->impuesto);
        }

        $sql = "SELECT id FROM orden_compra where cliente_id = '$proveedor'  ";
        $data = array();
        $detalleventaid = 0;
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);
        }

        $sql2 = "SELECT sum(cantidad) as saldo FROM pago_orden_compra where id_factura IN (" . $rest1 . ") ";
        foreach ($this->connection->query($sql2)->result() as $value2) {
            $total_saldo += $value2->saldo;
        }

        $ventas[] = array(
            'nombre_proveedor' => $nombre
            , 'saldo_proveedor' => $total_saldo
            , 'total_venta' => $total_ventas_cliente,
        );

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function total_ventas_totales($fechainicial, $fechafinal, $almacen, $ciudad, $filtro)
    {

        $ventas = array();
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $condition = '';
        $condition1 = '';
        $inner = "";
        $filtrosql = "";
        $fechasql = "";
        $fechasqldev = "";
        $fechasdevinicio = "";
        $fechasdevfin = "";

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
        }

        $alm = '';

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();

            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();

            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }

        }

        if (!empty($filtro)) {
            if ($filtro == 'horas') {
                $fechasql = " DATE_FORMAT(v.fecha ,'%h %p') AS fecha";
                $groupby = " GROUP BY DATE_FORMAT(v.fecha ,'%Y-%m-%d %H')";
                //$fechasqldev=" DATE_FORMAT(v.fecha ,'%d') AS fecha";
                $fechasqldev = " DATE_FORMAT(v.fecha ,'%h %p') AS fecha";
                $fechasdev = $fechainicial;
                $fechasdevinicio = $fechainicial;
                $fechasdevfin = $fechafinal;

            } else {
                if ($filtro == 'dias') {
                    $fechasql = " DATE_FORMAT(v.fecha ,'%d') AS fecha";
                    $groupby = " GROUP BY DATE_FORMAT(v.fecha ,'%Y-%m-%d')";
                    $fechasqldev = " DATE_FORMAT(v.fecha ,'%d') AS fecha";
                    $fechasdevinicio = $fechainicial;
                    $fechasdevfin = $fechafinal;
                } else {
                    if ($filtro == 'mes') {
                        $setmeses = "SET lc_time_names = 'es_MX';";
                        $this->connection->query($setmeses);
                        $fechasql = " DATE_FORMAT(v.fecha ,'%m') AS fecha, DATE_FORMAT(v.fecha ,'%Y-%m') AS fecha2";
                        $filtrosql = " ,MONTHNAME(v.fecha)AS mes";
                        $fechasqldev = " DATE_FORMAT(v.fecha ,'%m') AS fecha, DATE_FORMAT(v.fecha ,'%Y-%m') AS fecha2";
                        $groupby = " GROUP BY fecha2";
                        $fechasdevinicio = $fechainicial;
                        $fechasdevfin = $fechafinal;

                    }
                }
            }
        }

        $total_ventas = "SELECT  DATE(v.fecha) AS fecha_dia, $fechasql
        $filtrosql
        ,SUM((dv.unidades * dv.descuento)) AS total_descuento
        ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto
        ,SUM((dv.precio_venta * dv.unidades)) AS total_precio_venta
        ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) + SUM((dv.precio_venta * dv.unidades)) AS total
        ,SUM(IF(dv.nombre_producto='PROPINA',IF(dv.codigo_producto IS NULL,dv.precio_venta,0),0)) AS propina
        FROM venta v
        INNER JOIN detalle_venta dv ON v.id=dv.venta_id
        $inner
        WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'
        $condition $condition1 $alm
        AND v.estado = '0'
        $groupby
        ORDER BY v.fecha ";

        //die( $total_ventas);
        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $subtotal_enero = 0;
        $ventas_enero = 0;
        $subtotal_febrero = 0;
        $ventas_febrero = 0;
        $subtotal_marzo = 0;
        $ventas_marzo = 0;
        $subtotal_abril = 0;
        $ventas_abril = 0;
        $subtotal_mayo = 0;
        $ventas_mayo = 0;
        $subtotal_junio = 0;
        $ventas_junio = 0;
        $subtotal_julio = 0;
        $ventas_julio = 0;
        $subtotal_agosto = 0;
        $ventas_agosto = 0;
        $subtotal_septiembre = 0;
        $ventas_septiembre = 0;
        $subtotal_octubre = 0;
        $ventas_octubre = 0;
        $subtotal_noviembre = 0;
        $ventas_noviembre = 0;
        $subtotal_diciembre = 0;
        $ventas_diciembre = 0;
        $subtotal_mes = 0;
        $ventas_mes = 0;
        $ventas_por_mes = null;
        $total_saldo_a_favor = 0;
        $totaldevoluciones = 0;

        /* Devoluciones (NC)*/
        $subtotal_devoluciones = 0;
        ####todos
        $total_devoluciones = "SELECT v.id, v.factura, v.fecha, SUM(d.valor) AS total_devolucion
        FROM devoluciones d
        INNER JOIN venta v ON d.factura=v.factura
        WHERE DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal'
        AND v.estado = '0' $condition
        GROUP BY v.factura";

        $total_devoluciones = $this->connection->query($total_devoluciones)->result();
        $idFactura = "";
        foreach ($total_devoluciones as $key1 => $value1) {

            $totaldevoluciones += $value1->total_devolucion;
            $idFactura .= $value1->id . ",";
        }

        $idFactura = trim($idFactura, ",");

        if (!empty($idFactura)) {
            $subtotales = "SELECT SUM((IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * (dv.unidades-SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1)))) AS impuesto ,
                SUM((dv.precio_venta * (dv.unidades-SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1)))) AS total_precio_venta
                FROM detalle_venta dv
                WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                AND dv.venta_id IN($idFactura)";

            $devolucion = $this->connection->query($subtotales)->row();

            $subtotal_devoluciones = $devolucion->impuesto;
        }
        /*SALDO A FAVOR */
        ####TODOS
        $q_saldo_favor = "SELECT SUM(vp.valor_entregado) AS valor_entregado
                            FROM ventas_pago AS vp
                            INNER JOIN venta AS v ON vp.id_venta = v.id
                            WHERE vp.forma_pago = 'Saldo_a_Favor'
                            AND DATE(v.fecha) BETWEEN '$fechasdevinicio' AND '$fechasdevfin'
                            AND v.estado = '0'";

        $saldo_a_favor_result = $this->connection->query($q_saldo_favor)->row();
        $subtotal_saldo_a_favor = $saldo_a_favor_result->valor_entregado;
        $propinas = 0;
        foreach ($total_ventas_result as $value) {
            $propinas += $value->propina;
            if ($filtro == 'mes') {
                $fechasdevinicio = $value->fecha_dia;
                $fechasdevfin = $value->fecha_dia;
            }

            if ($filtro == 'horas') {
                $ventas[] = array(
                    'fecha_dia' => $value->fecha_dia,
                    'fecha' => $value->fecha,
                    'total_descuento' => $value->total_descuento,
                    //'total_impuesto' => $value->impuesto,
                    'saldo_a_favor' => $subtotal_saldo_a_favor,
                    'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento - $value->propina),
                    // 'total_precio_venta' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto
                    'total_precio_venta' => ($value->total - $value->total_descuento),
                    'propina_venta' => $value->propina,
                );
            } else {
                if ($filtro == 'dias') {
                    $ventas[] = array(
                        'fecha_dia' => $value->fecha_dia,
                        'total_descuento' => $value->total_descuento,
                        'total_impuesto' => $value->impuesto,
                        'devoluciones' => $subtotal_devoluciones,
                        'saldo_a_favor' => $subtotal_saldo_a_favor,
                        //'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento) - $subtotal_devoluciones,
                        'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento - $value->propina),
                        //'total_precio_venta' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto - $subtotal_devoluciones
                        'total_precio_venta' => ($value->total - $value->total_descuento),
                        'propina_venta' => $value->propina,
                    );

                } else {
                    if ($filtro == 'mes') {
                        $ventas[] = array(
                            'fecha_dia' => $value->fecha_dia,
                            'mes' => ucfirst($value->mes) . " del " . date("Y", strtotime($value->fecha_dia)),
                            'total_descuento' => $value->total_descuento,
                            'total_impuesto' => $value->impuesto,
                            'devoluciones' => $subtotal_devoluciones,
                            'propina_venta' => $value->propina,
                            'saldo_a_favor' => $subtotal_saldo_a_favor,
                            'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento - $value->propina),
                            'total_precio_venta' => ($value->total - $value->total_descuento),
                        );
                    }
                }
            }

        }
        if ($filtro == 'horas') {
            return array(
                'total_ventas' => $ventas,
                'devoluciones' => $totaldevoluciones,
                'subtotaldevoluciones' => $subtotal_devoluciones,
                'propina' => $propinas,
                //'devoluciones' => $subtotal_devoluciones
            );
        } else {
            if ($filtro == 'dias') {
                return array(
                    'total_ventas' => $ventas,
                    'devoluciones' => $totaldevoluciones,
                    'subtotaldevoluciones' => $subtotal_devoluciones,
                    'propina' => $propinas,
                );
            } else {
                if ($filtro == 'mes') {
                    return array(
                        'ventas_por_mes' => $ventas_por_mes,
                        'total_ventas' => $ventas,
                        'devoluciones' => $totaldevoluciones,
                        'subtotaldevoluciones' => $subtotal_devoluciones,
                        'propina' => $propinas,
                    );
                }
            }
        }

        //print_r($ventas_por_mes);
        /* return array(
    'ventas_por_mes' => $ventas_por_mes,
    'total_ventas' => $ventas
    );*/
    }

    public function total_ventas_mes($fechainicial, $fechafinal, $almacen, $ciudad)
    {
        $ventas = array();
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $condition = '';
        $condition1 = '';
        $inner = "";
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
        }

        $alm = '';

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();

            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();

            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }

        }

        $setmeses = "SET lc_time_names = 'es_MX';";
        $this->connection->query($setmeses);
        $total_ventas = "SELECT  DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%m') AS fecha
        ,MONTHNAME(v.fecha)AS mes
        ,SUM((dv.unidades * dv.descuento)) AS total_descuento
        ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto
        ,SUM((dv.precio_venta * dv.unidades)) AS total_precio_venta
        ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) + SUM((dv.precio_venta * dv.unidades)) AS total
         FROM venta v
        INNER JOIN detalle_venta dv ON v.id=dv.venta_id
        $inner
        WHERE DATE(v.fecha) BETWEEN '$fechainicial'  AND  '$fechafinal'
        $condition $condition1 $alm
        AND v.estado = '0'
        /*GROUP BY DATE_FORMAT(v.fecha ,'%Y %m %d')*/
        GROUP BY mes
        ORDER BY v.fecha ";

        $total_ventas_result = $this->connection->query($total_ventas)->result();
        $subtotal_enero = 0;
        $ventas_enero = 0;
        $subtotal_febrero = 0;
        $ventas_febrero = 0;
        $subtotal_marzo = 0;
        $ventas_marzo = 0;
        $subtotal_abril = 0;
        $ventas_abril = 0;
        $subtotal_mayo = 0;
        $ventas_mayo = 0;
        $subtotal_junio = 0;
        $ventas_junio = 0;
        $subtotal_julio = 0;
        $ventas_julio = 0;
        $subtotal_agosto = 0;
        $ventas_agosto = 0;
        $subtotal_septiembre = 0;
        $ventas_septiembre = 0;
        $subtotal_octubre = 0;
        $ventas_octubre = 0;
        $subtotal_noviembre = 0;
        $ventas_noviembre = 0;
        $subtotal_diciembre = 0;
        $ventas_diciembre = 0;
        $subtotal_mes = 0;
        $ventas_mes = 0;
        $ventas_por_mes = null;
        $total_saldo_a_favor = 0;
        foreach ($total_ventas_result as $value) {
            /* Devoluciones (NC)*/
            $subtotal_devoluciones = 0;
            $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%m') AS fecha,
                SUM((IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM((dv.precio_venta * dv.unidades)) AS total_precio_venta
                FROM venta v
                INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                LEFT JOIN devoluciones d ON v.factura=d.factura
                WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                AND DATE(v.fecha) BETWEEN '$value->fecha_dia' AND '$value->fecha_dia' AND v.estado = '0'
                $condition GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY v.fecha";

            //echo"<br><br><br>Aqui=".$total_devoluciones;
            $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
            if (count($total_devoluciones_result) > 0) {
                foreach ($total_devoluciones_result as $devolucion) {
                    $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                }
            }

            //echo $subtotal_devoluciones." - <br> \n";
            $q_saldo_favor = "SELECT vp.valor_entregado AS valor_entregado
                            FROM ventas_pago AS vp
                            INNER JOIN venta AS v ON vp.id_venta = v.id
                            INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                            WHERE vp.forma_pago = 'Saldo_a_Favor' and  DATE_FORMAT(v.fecha ,'%Y %m') = '" . $value->fecha . "'  AND estado = 0 group BY dv.venta_id ";

            $saldo_a_favor_result = $this->connection->query($q_saldo_favor)->result();

            $subtotal_saldo_a_favor = 0;
            if ($saldo_a_favor_result) {
                foreach ($saldo_a_favor_result as $value1) {
                    $total_saldo_a_favor += $value1->valor_entregado;
                    $subtotal_saldo_a_favor += $value1->valor_entregado;
                }
            }

            $subtotal_mes = ($value->total_precio_venta - $value->total_descuento) - $subtotal_devoluciones;
            $ventas_mes = ($value->total_precio_venta - $value->total_descuento) + $value->impuesto - $subtotal_devoluciones;
            $ventas_por_mes[] = array(
                'mes' => $value->mes,
                'venta_sin_impuesto' => $subtotal_mes,
                'total_venta' => $ventas_mes,
            );

            $ventas[] = array(
                'fecha_dia' => $value->fecha_dia,
                'total_descuento' => $value->total_descuento,
                'total_impuesto' => $value->impuesto,
                'devoluciones' => $subtotal_devoluciones,
                'saldo_a_favor' => $subtotal_saldo_a_favor,
                'subtotal_precio_venta' => ($value->total_precio_venta - $value->total_descuento) - $subtotal_devoluciones,
                'total_precio_venta' => ($value->total_precio_venta - $value->total_descuento) + $value->impuesto - $subtotal_devoluciones,
            );
        }

        return array(
            'ventas_por_mes' => $ventas_por_mes,
            'total_ventas' => $ventas,
        );
    }

    public function total_ventas_impuesto($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $ventas = array();
        $total_ventas = '';
        $total_ventas_sin_impuesto = '';
        $condition = '';
        $condition1 = '';
        $inner = "";

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on venta.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }

            $total_ventas = "SELECT  DATE(venta.fecha) AS fecha_dia, venta.id,
	               SUM((((detalle_venta.precio_venta - detalle_venta.descuento) * detalle_venta.impuesto ) / 100 ) * detalle_venta.unidades) AS impuesto, nombre_impuesto,
				   SUM((((detalle_venta.precio_venta - detalle_venta.descuento) ) ) * detalle_venta.unidades) AS subtotal,
				   (SUM((((detalle_venta.precio_venta - detalle_venta.descuento) ) ) * detalle_venta.unidades) + SUM((((detalle_venta.precio_venta - detalle_venta.descuento) * detalle_venta.impuesto ) / 100 ) * detalle_venta.unidades)) AS total
				   FROM venta
				   inner join detalle_venta on venta.id = detalle_venta.venta_id
				   inner join impuesto on detalle_venta.impuesto = impuesto.porciento
                        WHERE DATE(fecha) BETWEEN  '$fechainicial' AND '$fechafinal'
                        AND estado='0' and impuesto > 0 $condition  $condition1 group by nombre_impuesto ";

            $total_ventas_sin_impuesto = "SELECT DATE(venta.fecha) AS fecha_dia, venta.id,
	               SUM((((detalle_venta.precio_venta - detalle_venta.descuento) * detalle_venta.impuesto ) / 100 ) * detalle_venta.unidades) AS impuesto, nombre_impuesto,
				   SUM((((detalle_venta.precio_venta - detalle_venta.descuento) ) ) * detalle_venta.unidades) AS subtotal,
				   (SUM((((detalle_venta.precio_venta - detalle_venta.descuento) ) ) * detalle_venta.unidades) + SUM((((detalle_venta.precio_venta - detalle_venta.descuento) * detalle_venta.impuesto ) / 100 ) * detalle_venta.unidades)) AS total
				   FROM venta
				   inner join detalle_venta on venta.id = detalle_venta.venta_id
				   inner join impuesto on detalle_venta.impuesto = impuesto.porciento
                        WHERE DATE(fecha) BETWEEN  '$fechainicial' AND '$fechafinal'
                        AND estado='0' and impuesto = 0 $condition  $condition1 group by nombre_impuesto limit 1";
        }

        /* Devoluciones (NC)*/
        $subtotal_devoluciones = 0;
        $impuesto_devolucion = 0;
        $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
             SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
             SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
             FROM venta v
             INNER JOIN detalle_venta dv ON v.id=dv.venta_id
             LEFT JOIN devoluciones d ON v.factura=d.factura
             WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
             AND DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal' AND v.estado = '0'
             $condition
             GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY v.fecha";

        $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
        if (count($total_devoluciones_result) > 0) {
            foreach ($total_devoluciones_result as $devolucion) {
               // $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento);
                //$subtotal_devoluciones +=  $devolucion->valor_devolucion;
                $impuesto_devolucion += $devolucion->impuesto;

            }
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
        }

        //Cuando el impuesto es mayor a cero
        if ($total_ventas != '') {
            foreach ($this->connection->query($total_ventas)->result() as $value) {

                $ventas[] = array(
                    'nombre_impuesto' => $value->nombre_impuesto,
                    'impuesto' => $value->impuesto,
                    'subtotal' => $value->subtotal,
                    'total' => $value->total,
                );
            }
        }

        if ($total_ventas_sin_impuesto != '') {

            //Cuando el impuesto es 0
            foreach ($this->connection->query($total_ventas_sin_impuesto)->result() as $value) {

                $ventas[] = array(
                    'nombre_impuesto' => $value->nombre_impuesto,
                    'impuesto' => $value->impuesto,
                    'subtotal' => $value->subtotal,
                    'total' => $value->total,
                );
            }
        }

        return array(
            'total_ventas' => $ventas,
            'devoluciones' => $subtotal_devoluciones,
            'impuesto_devolucion' => $impuesto_devolucion
        );
    }

    public function habitos_consumo_hora($fechainicial, $fechafinal, $almacen)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " and  almacen_id = '$almacen' ";
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE(`fecha`)  AS fecha, DATE_FORMAT(`fecha`,'%h:00 %p') AS hora,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' $condition and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H')";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE(`fecha`)  AS fecha, DATE_FORMAT(`fecha`,'%h:00 %p') AS hora,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %H')";
        }

        $detalle_ventas_result = array();
        $consumo_productos = array();
        $ven_cod = '';
        $rest = '';
        $total_ventas = $this->connection->query($total_ventas)->result();
        foreach ($total_ventas as $value) {

            $detalle_ventas = "SELECT id FROM `venta`  where DATE(`fecha`) = '$value->fecha' $condition   and estado = 0 and DATE_FORMAT(`fecha`,'%h:00 %p') = '$value->hora' ";
            $detalle_ventas_result = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_result as $det) {

                if ($det->id > 0) {
                    $ven_cod = $ven_cod . "," . $det->id;
                }
            }

            if ($ven_cod) {
                $rest = substr($ven_cod, 1);
            } else {
                $rest = 0;
            }

            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_pdv2 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            $vr_unidades = 0;

            $detalle_ventas = "SELECT DATE(`fecha`) AS fecha_dia, DATE_FORMAT(`fecha`,'%h:00 %p') AS hora,  venta_id ,nombre_producto, sum(unidades) as unidades, precio_venta as total_detalleventa,
			    sum(descuento) as descuento, impuesto
			    ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	             ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	            ,SUM( `unidades` * `descuento` ) AS total_descuento,
                codigo_producto
                FROM detalle_venta  inner join venta on venta.id = detalle_venta.venta_id
				where venta_id  IN (" . $rest . ") group by nombre_producto order by unidades desc";

            $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_result_1 as $prod) {

                $consumo_productos[] = array(
                    'id' => $det->id
                    , 'fecha_dia' => $prod->fecha_dia
                    , 'unidades' => $prod->unidades
                    , 'hora' => $value->hora
                    , 'fecha' => $value->fecha
                    , 'nombre' => $prod->nombre_producto
                    , 'total_detalleventa' => ($prod->total_precio_venta - $prod->total_descuento) + $prod->impuesto,
                    'codigo_producto' => $prod->codigo_producto,

                );
            }

            $ven_cod = '';
        }

        if ($almacen == '0') {
            $total_ventas_4 = "SELECT sum(total_venta) as total_ventas FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal'  and estado = 0";
        } else {
            $total_ventas_4 = "SELECT sum(total_venta) as total_ventas FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' and  almacen_id = '$almacen' and estado = 0";
        }

        $detalle_ventas_result_4 = $this->connection->query($total_ventas_4)->result();

        return array(
            'total_ventas_1' => $total_ventas
            , 'total_ventas_2' => $detalle_ventas_result
            , 'total_ventas_3' => $consumo_productos
            , 'total_ventas_4' => $detalle_ventas_result_4,
        );
    }

    public function habitos_consumo_dia($fechainicial, $fechafinal, $almacen)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " and  almacen_id = '$almacen' ";
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE(`fecha`) AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' $condition and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %d')";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE(`fecha`) AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal'  and  almacen_id = '$almacen'  and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %d')";
        }

        $detalle_ventas_result = array();
        $consumo_productos = array();
        $ven_cod = '';
        $rest = '';
        $total_ventas = $this->connection->query($total_ventas)->result();

        $total_devolucion = 0;
        foreach ($total_ventas as $value) {

            $detalle_ventas = "SELECT id FROM `venta`  where DATE(`fecha`) = '$value->fecha' $condition   and estado = 0 ";
            $detalle_ventas_result = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_result as $det) {

                if ($det->id > 0) {
                    $ven_cod = $ven_cod . "," . $det->id;
                }
            }

            if ($ven_cod) {
                $rest = substr($ven_cod, 1);
            } else {
                $rest = 0;
            }

            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_pdv2 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            $vr_unidades = 0;

            $detalle_ventas = "SELECT DATE(`fecha`) AS fecha_dia, venta_id ,nombre_producto, sum(unidades) as unidades, precio_venta as total_detalleventa,
			    sum(descuento) as descuento, impuesto
			    ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	             ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	            ,SUM( `unidades` * `descuento` ) AS total_descuento,
                codigo_producto,
                producto_id
                FROM detalle_venta  inner join venta on venta.id = detalle_venta.venta_id
				where venta_id  IN (" . $rest . ") group by nombre_producto order by DATE(`fecha`), unidades desc";

            $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();

            foreach ($detalle_ventas_result_1 as $prod) {
                /* Devoluciones (NC)*/
                $subtotal_devoluciones = 0;
                if ($prod->producto_id != "") {

                    $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
                  SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                  SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
                  FROM venta v
                  INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                  LEFT JOIN devoluciones d ON v.factura=d.factura
                  WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                  AND DATE(v.fecha) BETWEEN '$prod->fecha_dia' AND '$prod->fecha_dia' AND dv.producto_id = $prod->producto_id AND v.estado = '0'
                  GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY v.fecha";

                    $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
                    if (count($total_devoluciones_result) > 0) {
                        foreach ($total_devoluciones_result as $devolucion) {
                            $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                        }
                    }
                }
                $total_devolucion += $subtotal_devoluciones;
                $consumo_productos[] = array(
                    'fecha_dia' => $prod->fecha_dia
                    , 'unidades' => $prod->unidades
                    , 'fecha' => $value->fecha
                    , 'nombre' => $prod->nombre_producto
                    , 'total_detalleventa' => ($prod->total_precio_venta - $prod->total_descuento) + $prod->impuesto - $subtotal_devoluciones,
                    'codigo_producto' => $prod->codigo_producto,
                );
            }

            $ven_cod = '';
        }

        return array(
            'total_ventas_1' => $total_ventas
            , 'total_ventas_2' => $detalle_ventas_result
            , 'total_ventas_3' => $consumo_productos
            , 'devoluciones' => $total_devolucion,
        );
    }

    public function historial_inventario($fecha_desde = null, $fecha_hasta = null, $almacen = null)
    {

        $where = '';

        if (isset($fecha_desde) && isset($fecha_hasta)) {
            $where = " WHERE sh.fecha BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
        }

        if ($almacen) {
            if ($where == '') {
                $where .= ' WHERE sh.almacen_id = ' . $almacen;
            } else {
                $where .= ' AND sh.almacen_id = ' . $almacen;
            }

        }

        $q_query = 'SELECT a.nombre AS Almacen, c.nombre AS Categoria, p.codigo AS Codigo, p.codigo_barra AS CodigoBarras, p.nombre AS Nombre, sh.unidades AS Unidades, sh.precio AS Precio, p.precio_compra as Precio_compra, sh.fecha as Fecha FROM stock_historial sh LEFT JOIN producto p ON sh.producto_id = p.id LEFT JOIN categoria c ON p.categoria_id = c.id LEFT JOIN almacen a ON sh.almacen_id = a.id ' . $where . ' ORDER BY a.nombre, p.nombre, p.codigo';

        $historial = $this->connection->query($q_query)->result();
        return $historial;
    }

    public function lista_precios($lista_precios = null, $almacen = null)
    {
        $where = '';

        if ($lista_precios) {
            $where = ' WHERE lp.id = ' . $lista_precios;
        }

        if ($almacen) {
            if ($where == '') {
                $where .= ' WHERE (a.id = ' . $almacen . ' or a.id = 0 )';
            } else {
                $where .= ' AND (a.id = ' . $almacen . ' or a.id = 0)';
            }

        }

        $q_query = 'SELECT a.`nombre` AS almacen, c.`nombre` AS categoria, p.`codigo`, p.`codigo_barra`, p.`nombre`, p.`precio_venta`, (SELECT nombre_impuesto FROM impuesto where id_impuesto = p.impuesto) as impuestoNombre, (SELECT porciento FROM impuesto where id_impuesto = p.impuesto) as impuestoValor, GROUP_CONCAT(ldp.`precio` ORDER BY ldp.`id_lista_precios`) AS precios_listas_precios, GROUP_CONCAT(ldp.`id_lista_precios` ORDER BY ldp.`id_lista_precios`) AS ids_listas_precios, GROUP_CONCAT(lp.`nombre` ORDER BY ldp.`id_lista_precios`) AS nombres_listas_precios FROM (((lista_detalle_precios ldp JOIN lista_precios lp ON ldp.`id_lista_precios` = lp.`id`) JOIN almacen a ON lp.`almacen_id` = a.`id`) JOIN producto p ON ldp.`id_producto` = p.`id` LEFT JOIN categoria c ON p.`categoria_id` = c.`id`) ' . $where . ' GROUP BY id_producto ORDER BY ldp.`id_lista_precios`';

        $lista_precios = $this->connection->query($q_query)->result();
        return $lista_precios;
    }

    public function inventario_en_minimos($database)
    {
        $q_query = 'SELECT a.`nombre`, sa.`unidades`, p.`stock_minimo`, c.`nombre`, p.`codigo`, p.`codigo_barra`, p.`nombre`, p.`precioventa` FROM ((' . $databases . '.producto p JOIN ' . $databases . '.stock_actual sa ON  p.`id` = sa.`producto_id` AND sa.`unidades` - 10 <= p.`stock_minimo` LEFT JOIN ' . $databases . '.almacen a ON a.`id` = sa.`almacen_id`) LEFT JOIN ' . $databases . '.categoria c ON p.`categoria_id` = c.`id`)';
        $inventario = $this->db->query($q_query)->result();

        return $inventario;
    }

    public function habitos_consumo_mes($fechainicial, $fechafinal, $almacen, $poblacion)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " and  almacen_id = '$almacen' ";
        }

        $ven_cod = '';
        $rest = '';
        $consumo_productos = array();

        if ($poblacion == '') {
            $condition1 = '';
            $inner = "";
            $campo = "";
        } else {
            $campo = " nombre_comercial, ";
            $inner = " inner join clientes on venta.cliente_id =  clientes.id_cliente ";
            $condition1 = " and  poblacion like '%" . $poblacion . "%' ";
        }

        $detalle_ventas = "SELECT id FROM `venta` $inner where DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' $condition $condition1   and estado = 0 ";
        $detalle_ventas_result = $this->connection->query($detalle_ventas)->result();
        foreach ($detalle_ventas_result as $det) {

            if ($det->id > 0) {
                $ven_cod = $ven_cod . "," . $det->id;
            }
        }

        if ($ven_cod) {
            $rest = substr($ven_cod, 1);
        } else {
            $rest = 0;
        }

        $vr_impuesto = 0;
        $vr_valor = 0;
        $vr_pdv = 0;
        $vr_pdv1 = 0;
        $vr_pdv2 = 0;
        $vr_column = 0;
        $vr_bruto = 0;
        $vr_unidades = 0;

        $detalle_ventas = "SELECT C.nombre_comercial, C.poblacion, DATE(V.fecha) AS fecha_dia, D.venta_id ,P.nombre AS nombre_producto,
                                SUM(D.unidades) AS unidades, D.precio_venta AS total_detalleventa, SUM(D.descuento) AS descuento, D.impuesto ,
                                SUM( (D.precio_venta - D.descuento) * D.impuesto / 100 * D.unidades ) AS impuesto ,SUM( D.precio_venta * D.unidades ) AS total_precio_venta ,
                                SUM( D.unidades * D.descuento ) AS total_descuento, P.codigo, D.producto_id
                                FROM detalle_venta D JOIN  venta V  ON V.id = D.venta_id
                                JOIN clientes C ON V.cliente_id = C.id_cliente
                                JOIN producto P ON P.id =  D.producto_id
				                    where D.venta_id IN (" . $rest . ") GROUP BY P.id ORDER BY unidades DESC ";

        $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();
        foreach ($detalle_ventas_result_1 as $prod) {
            /* Devoluciones (NC)*/
            $subtotal_devoluciones = 0;
            $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
                 SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                 SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
                 FROM venta v
                 INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                 LEFT JOIN devoluciones d ON v.factura=d.factura
                 WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                 AND DATE(v.fecha) BETWEEN '$prod->fecha_dia' AND '$prod->fecha_dia' AND dv.producto_id = $prod->producto_id AND v.estado = '0'
                 GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY v.fecha";

            $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
            if (count($total_devoluciones_result) > 0) {
                foreach ($total_devoluciones_result as $devolucion) {
                    $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                }
            }

            $consumo_productos[] = array(
                'poblacion' => $prod->poblacion
                , 'cliente' => $prod->nombre_comercial
                , 'fecha_dia' => $prod->fecha_dia
                , 'fecha_dia' => $prod->fecha_dia
                , 'unidades' => $prod->unidades
                , 'nombre' => $prod->nombre_producto
                , 'total_detalleventa' => ($prod->total_precio_venta - $prod->total_descuento) + $prod->impuesto - $subtotal_devoluciones,
                'codigo_barra' => $prod->codigo,
            );
        }

        return array(
            'total_ventas_3' => $consumo_productos,
        );
    }

    public function orden_compra_productos($proveedor, $producto, $fechainicial, $fechafinal, $almacen)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $condition = ($producto != '') ? " AND nombre_producto LIKE '%$producto%'" : '';
        $condition1 = ($proveedor != '') ? " AND cliente_id = '$proveedor'" : '';
        $condition2 = ($fechainicial != '' && $fechafinal != '') ? " AND DATE(orden_compra.fecha) >= '$fechainicial' AND  DATE(orden_compra.fecha) <= '$fechafinal'" : '';

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition3 = '';
            } else {
                $condition3 = " and  almacen_id = '$almacen' ";
            }

            $detalle_ventas = "SELECT cliente_id, DATE_FORMAT(`fecha`,'%Y-%m-%d') as fecha, nombre_producto, precio_venta, unidades
            FROM detalle_orden_compra  inner join orden_compra on orden_compra.id = detalle_orden_compra.venta_id
            where 1 $condition $condition1 $condition2 $condition3 and estado = 0";
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
            $detalle_ventas = "SELECT cliente_id, DATE_FORMAT(`fecha`,'%Y-%m-%d') as fecha, nombre_producto, precio_venta, unidades
            FROM detalle_orden_compra  inner join orden_compra on orden_compra.id = detalle_orden_compra.venta_id
            where $condition $condition1 $condition2 and  almacen_id = '$almacen' and estado = 0";
        }

        $nomprove = 0;
        $consumo_productos = array();
        $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();

        foreach ($detalle_ventas_result_1 as $prod) {

            $proveedor = "SELECT nombre_comercial FROM proveedores where id_proveedor = '$prod->cliente_id' ";

            $proveedor_result_1 = $this->connection->query($proveedor)->result();
            foreach ($proveedor_result_1 as $prove) {
                $nomprove = $prove->nombre_comercial;
            }

            $consumo_productos[] = array(
                'fecha' => $prod->fecha
                , 'nombre_producto' => $prod->nombre_producto
                //, 'precio_compra' => $this->opciones_model->formatoMonedaMostrar($prod->precio_venta)
                , 'precio_compra' => $prod->precio_venta
                , 'unidades' => $prod->unidades
                , 'nomprove' => $nomprove,
            );
        }

        return array(
            'total_ventas_3' => $consumo_productos,
        );
    }

    public function menos_rotacion($fechainicial, $fechafinal, $almacen, $precio_almacen = false)
    {

        $ventaid = 0;
        $rest = 0;
        $rest1 = 0;
        $detalleventaid = 0;
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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
        }
        // Validamos si seleccionaron todos (0) o si escogieron un almacen
        if (isset($almacen) && $almacen > 0) {
            $whereAlmacen = 'and stock_actual.almacen_id = ' . $almacen;
        } else {
            // Si es 0, la consulta va vacia.
            $whereAlmacen = '';
        }

        $ventas = "SELECT * FROM venta where date(fecha) >= '$fechainicial' and date(fecha) <= '$fechafinal' and estado = '0' ";
        $ventas_id = $this->connection->query($ventas)->result();
        foreach ($ventas_id as $value) {

            $ventaid = $ventaid . "," . $value->id;
        }

        $rest = substr($ventaid, 2);
        if ($rest == '') {
            $rest = 0;
        } else {
            $rest = substr($ventaid, 2);
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't') { //administrador
            $detalle_ventas = "SELECT * FROM detalle_venta where venta_id  IN (" . $rest . ")";
            $detalle_ventas_id = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_id as $det) {
                $detalleventaid = $detalleventaid . ",'" . $det->codigo_producto . "'";
            }

            $rest1 = substr($detalleventaid, 2);
            if ($rest1 == '') {
                $rest1 = 0;
            } else {
                $rest1 = substr($detalleventaid, 2);
            }

            $productos_ventas = "SELECT * FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ") $whereAlmacen ";
            $productos_ventas_id = $this->connection->query($productos_ventas)->result();

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $productos_ventas = "SELECT sum(stock_actual.precio_compra) as total_valor, sum(unidades) as total_unidades FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ")  $whereAlmacen ";
            } else {
                // No tiene precios por almacen
                $productos_ventas = "SELECT sum(producto.precio_compra) as total_valor, sum(unidades) as total_unidades FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ")  $whereAlmacen ";
            }

            $productos_ventas_totales = $this->connection->query($productos_ventas)->result();
        }

        if ($is_admin != 't') { //usuario
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
            // Validamos si seleccionaron todos (0) o si escogieron un almacen
            if (isset($almacen) && $almacen > 0) {
                $whereAlmacen = 'and stock_actual.almacen_id = ' . $almacen;
            } else {
                // Si es 0, la consulta va vacia.
                $whereAlmacen = '';
            }
            //---------------------------------------------
            $detalle_ventas = "SELECT * FROM detalle_venta where venta_id  IN (" . $rest . ")";
            $detalle_ventas_id = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_id as $det) {
                $detalleventaid = $detalleventaid . ",'" . $det->codigo_producto . "'";
            }

            $rest1 = substr($detalleventaid, 2);
            if ($rest1 == '') {
                $rest1 = 0;
            } else {
                $rest1 = substr($detalleventaid, 2);
            }

            $productos_ventas = "SELECT * FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ")  $whereAlmacen ";
            $productos_ventas_id = $this->connection->query($productos_ventas)->result();

            if ($precio_almacen == 1) {
                // Si tiene precios por almacen
                $productos_ventas = "SELECT sum(stock_actual.precio_compra) as total_valor, sum(unidades) as total_unidades FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ")  $whereAlmacen ";
            } else {
                // Si No tiene precios por almacen
                $productos_ventas = "SELECT sum(producto.precio_compra) as total_valor, sum(unidades) as total_unidades FROM producto inner join stock_actual on producto.id = stock_actual.producto_id where codigo NOT IN (" . $rest1 . ")  $whereAlmacen ";

            }

            $productos_ventas_totales = $this->connection->query($productos_ventas)->result();
        }

        return array(
            'productos' => $productos_ventas_id
            , 'totales' => $productos_ventas_totales,
        );
    }

    /* INVOCE2 ====================================== */

    //Cuadre de caja
    public function cuadre_caja($fecha, $tipo, $almacen)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $base_dato = $this->session->userdata('base_dato');
        $condition_1 = "";

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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
        }

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " and  venta.almacen_id = '$almacen' ";
            $condition_1 = " and  orden_compra.almacen_id = '$almacen' ";
        }

        $forma_pago = "select venta.id as id_venta, sum(valor_entregado) - sum(ventas_pago.cambio)  as total_venta, count(forma_pago) as cantidad, forma_pago from ventas_pago
		inner join venta on ventas_pago.id_venta = venta.id
		where date(fecha) = '$fecha' and estado='0' and venta.activo = '1'  $condition group by forma_pago";
        //echo $forma_pago;
        $forma_pago_result1 = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        //var_dump($forma_pago_result);
        foreach ($forma_pago_result as $value) {
            $forma_pago_result1[] = array('forma_pago' => $value->forma_pago,
                'cantidad' => $value->cantidad,
                'vr_valor' => $value->total_venta,
            );
        }

        $forma_pago = "select venta.id as id_venta, sum(valor_entregado) - sum(ventas_pago.cambio)   as total_venta, count(forma_pago) as cantidad, forma_pago from ventas_pago
		inner join venta on ventas_pago.id_venta = venta.id
		where date(fecha) = '$fecha' and estado='0' $condition and forma_pago <> 'Credito' and forma_pago <> 'Saldo_a_Favor' and forma_pago <> 'Gift_Card'  group by forma_pago";

        //die($forma_pago);
        $forma_pago_ventas = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;

        foreach ($forma_pago_result as $value) {
            $forma_pago_ventas[] = array(
                'forma_pago' => $value->forma_pago,
                'cantidad' => $value->cantidad,
                'vr_valor' => $value->total_venta,
            );
        }

        $forma_pago = "select venta.id as id_venta, sum(valor_entregado) - sum(ventas_pago.cambio)   as total_venta, count(forma_pago) as cantidad, forma_pago from ventas_pago
		inner join venta on ventas_pago.id_venta = venta.id
		where date(fecha) = '$fecha' and estado='0' $condition and forma_pago <> 'Credito' and forma_pago <> 'Saldo_a_Favor' and forma_pago <> 'Gift_Card'   group by forma_pago";

        $forma_pago_ventas = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;

        foreach ($forma_pago_result as $value) {
            $forma_pago_ventas[] = array(
                'forma_pago' => $value->forma_pago,
                'cantidad' => $value->cantidad,
                'vr_valor' => $value->total_venta,
            );
        }

        $total_pagos_plan_separe = 0;
        if ($this->session->userdata('base_dato') == 'vendty2_db_562a64c85a0a2' || $this->session->userdata('base_dato') == 'vendty2_db_1542_venta2015') {
            $forma_pago = "select sum(valor_entregado) as total_pagos_plan_separe from plan_separe_pagos
	                  inner join  plan_separe_factura as venta on plan_separe_pagos.id_venta = venta.id
					   where date(plan_separe_pagos.fecha) = '$fecha' $condition ";
            $forma_pago_credito = array();
            $forma_pago_result = $this->connection->query($forma_pago)->result();

            foreach ($forma_pago_result as $value) {

                $total_pagos_plan_separe = $value->total_pagos_plan_separe;
            }
        }

        //Abonos plan separe
        $abonos_plan_separe = 0;
        $forma_pago = "select sum(valor_entregado) as total_pagos_plan_separe from plan_separe_pagos
                    inner join  plan_separe_factura as venta on plan_separe_pagos.id_venta = venta.id
                    where date(plan_separe_pagos.fecha) = '$fecha' $condition ";
        $forma_pago_credito = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();

        foreach ($forma_pago_result as $value) {
            $abonos_plan_separe = $value->total_pagos_plan_separe;
            $abonos_plan_separe_array[] = array(
                'forma_pago' => '',
                'cantidad' => '',
                'valor' => $value->total_pagos_plan_separe,
            );
        }

        //Ventas anouladas
        $ventasAnuladas = $this->Caja->obtenerVentasAnuladas($fecha);

        //Ventas devueltas
        $ventasDevueltas = $this->Caja->obtenerDevoluciones($fecha, $almacen);

        $forma_pago = "select sum(cantidad) as total_credito from pago
	   inner join venta on pago.id_factura = venta.id
		where date(fecha_pago) = '$fecha' $condition ";
        $forma_pago_credito = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_credito[] = array('total_credito' => $value->total_credito + $total_pagos_plan_separe);
        }

        $forma_pago = "select sum(cantidad) as total_credito from pago_orden_compra
	   inner join orden_compra on pago_orden_compra.id_factura = orden_compra.id
		where date(fecha_pago) = '$fecha'  $condition_1  ";
        $forma_pago_proveedor = array();
        $forma_pago_result = $this->connection->query($forma_pago)->result();
        $vr_total = 0;
        foreach ($forma_pago_result as $value) {

            $forma_pago_proveedor[] = array('total_proveedor' => $value->total_credito,
            );
        }

        $impuesto = "select nombre_impuesto, impuesto.porciento, sum(unidades) as unidades, sum(precio_venta) as precio
		 from venta
		  inner join detalle_venta on detalle_venta.venta_id = venta.id
		  inner join impuesto on detalle_venta.impuesto = impuesto.porciento
		   where date(fecha) = '$fecha' and estado='0'  and venta.activo = '1'  $condition   group by impuesto.id_impuesto";

        $detalleventaid = 0;

        $sqlAlmacen = "";
        if ($almacen != 0) {
            $sqlAlmacen = " AND almacen_id = $almacen";
        }

        $sql = "SELECT id  FROM venta where date(fecha) = '$fecha' and estado='0' AND activo = 1  $sqlAlmacen";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value1) {

            $detalleventaid = $detalleventaid . ",'" . $value1->id . "'";
        }

        $rest1 = substr($detalleventaid, 2);
        if ($rest1 == '') {
            $rest1 = 0;
        } else {
            $rest1 = substr($detalleventaid, 2);

            $impuesto = "SELECT DATE(`fecha`) AS fecha_dia
	 ,  (SELECT nombre_impuesto FROM `impuesto` where porciento = impuesto limit 1) as imp
	 ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuestos
	 ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	  ,SUM( `unidades` * `descuento` ) AS total_descuento
	 FROM  `venta`
	 inner join detalle_venta on venta.id = detalle_venta.venta_id
	 WHERE venta.id  IN (" . $rest1 . ")  and venta.activo = '1'  group by impuesto";

            $where_gastos = "";
            if ($almacen != null && $almacen != 0) {
                $where_gastos = "and id_almacen = '$almacen' ";
            }

            $gastos = "SELECT sum(valor) as total FROM proformas where fecha = '$fecha' $where_gastos  group by fecha;";
            $factura = "SELECT
                venta.almacen_id, venta.id, venta.factura, venta.total_venta, codigo_producto,nombre_producto,sum(unidades) as unidades ,
                precio_venta as precio_unidad ,impuesto, ( (sum(precio_venta) * (impuesto/100) ) *unidades) as valor_impuesto,
                (precio_venta * sum(unidades)) as valor,
                sum((precio_venta+((precio_venta)*(impuesto/100)))*unidades) as total ,
                (sum(descuento)*unidades) as descuento,
                count(venta.id) as total_final
            FROM venta
            inner join detalle_venta on detalle_venta.venta_id = venta.id
            where date(fecha) = '$fecha' and estado='0'   $condition
            group by codigo_producto order by venta.id asc";
            $forma_pago_result = $this->connection->query($forma_pago)->result();
            $gastos_pago_result = $this->connection->query($gastos)->result();
            $impuesto_result = $this->connection->query($impuesto)->result();
        }

        if ($tipo == 'producto') {
            //ini_set('display_errors', 1);
            $factura1 = "SELECT venta.id as id_venta, factura, venta.activo AS venta_plan_activo, venta.vendedor, a.nombre
                       FROM venta
                       INNER JOIN almacen AS a ON a.id=venta.almacen_id
                       WHERE date(fecha) = '$fecha' and estado='0'  $condition   ";

            //var_dump($this->connection->query($factura1));
            $factura_result = $this->connection->query($factura1)->result();
            $factura_data = array();

            $vrUnidades = 0;
            $vrPrecioUnidad = 0;
            $vrValor = 0;
            $vrDescuento = 0;
            $vrValorImpuesto = 0;
            $vrTotal = 0;

            foreach ($factura_result as $value) {
                $value->id_venta . "<br>";
                $get_details = "select precio_venta, unidades, descuento, impuesto, venta_id, nombre_producto from detalle_venta where venta_id = $value->id_venta   ";
                $details_result = $this->connection->query($get_details)->result();
                $vr_impuesto = 0;
                $vr_valor = 0;
                $vr_pdv = 0;
                $vr_pdv1 = 0;
                $vr_column = 0;
                $vr_bruto = 0;
                $vr_unidades = 0;
                foreach ($details_result as $detail) {

                    $vr_pdv = $detail->precio_venta * $detail->unidades;
                    $vr_pdv1 = $detail->descuento * $detail->unidades;
                    $vr_pdv2 = $detail->precio_venta - $detail->descuento;
                    $vr_unidades = $detail->unidades;

                    $vr_bruto = $vr_pdv - $vr_pdv1;
                    $vr_impuesto = $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                    $vr_column = $vr_pdv - $vr_pdv1;
                    $vr_valor = $vr_pdv - $vr_pdv1;

                    $vendedor = '';
                    $total_vendedor = "SELECT nombre FROM  `vendedor` WHERE id = '" . $value->vendedor . "'";
                    $total_vendedor_result = $this->connection->query($total_vendedor)->result();
                    foreach ($total_vendedor_result as $dat1) {
                        $vendedor = $dat1->nombre;
                    }

                    $total_opciones = "SELECT valor_opcion FROM  `opciones` WHERE id = '1'";
                    $total_opciones_result = $this->connection->query($total_opciones)->result();
                    foreach ($total_opciones_result as $dat1) {
                        $empresa = $dat1->valor_opcion;
                    }

                    $factura_data[] = array('nombre_producto' => $detail->nombre_producto,
                        'vendedor' => $vendedor,
                        'empresa' => $empresa,
                        'venta_plan_activo' => $value->venta_plan_activo,
                        'unidades' => $vr_unidades,
                        'precio_unidad' => $detail->precio_venta,
                        'valor' => ($vr_bruto),
                        'valor_impuesto' => ($vr_impuesto),
                        'total' => ($detail->precio_venta),
                        'valorTotal' => ($vr_impuesto + $vr_bruto),
                        'descuento' => ($detail->descuento),
                        'factura' => $value->factura,
                        'almacen_id' => $value->nombre,
                    );
                    $vrUnidades += $vr_unidades;
                    $vrPrecioUnidad += $detail->precio_venta;
                    $vrValor += $vr_bruto;
                    $vrDescuento += $detail->descuento;
                    $vrValorImpuesto += $vr_impuesto;
                    $vrTotal += $vr_impuesto + $vr_bruto;
                }
            }

            return array(
                'forma_pago' => $forma_pago_result1
                , 'impuesto_result' => (isset($impuesto_result) && $impuesto_result != null) ? $impuesto_result : 0
                , 'factura_data' => $factura_data
                , 'gastos' => (isset($gastos_pago_result) && $gastos_pago_result != null) ? $gastos_pago_result : 0
                , 'forma_pago_credito' => $forma_pago_credito
                , 'abonos_plan_separe_array' => $abonos_plan_separe_array
                , 'forma_pago_proveedor' => $forma_pago_proveedor
                , 'forma_pago_ventas' => $forma_pago_ventas
                , 'vrUnidades' => ($vrUnidades)
                , 'vrPrecioUnidad' => ($vrPrecioUnidad)
                , 'vrValor' => ($vrValor)
                , 'vrDescuento' => ($vrDescuento)
                , 'vrValorImpuesto' => ($vrValorImpuesto)
                , 'vrTotal' => ($vrTotal)
                , 'ventas_anuladas' => $ventasAnuladas
                , 'ventas_devueltas' => $ventasDevueltas,
            );
        } else if ($tipo == 'factura') {
            $factura1 = "SELECT
                        almacen.nombre as almacen_id, venta.id, venta.activo AS venta_plan_activo,venta.factura, vendedor, venta.total_venta, codigo_producto,nombre_producto,sum(unidades) as unidades ,
                        precio_venta as precio_unidad ,impuesto, ( ((sum(precio_venta)*unidades - sum(descuento)) * (impuesto/100) ) ) as valor_impuesto,
                        (sum(unidades)) as valor_unidades,
						(sum(precio_venta)) as valor_ventas,
                       (precio_venta+((precio_venta)*(impuesto/100)))*unidades as total ,
                        (sum(descuento) ) as descuento
                    FROM venta
                    inner join detalle_venta on detalle_venta.venta_id = venta.id
                    inner join almacen on almacen.id=venta.almacen_id
                    where date(fecha) = '$fecha' and estado = '0'   $condition
                    group by venta.id order by venta.id asc";

            $factura_result = $this->connection->query($factura1)->result();

            $vrBrutoTotal = 0;
            $vrImpuestoTotal = 0;
            $vrDescuentoTotal = 0;
            $vrTotal = 0;
            $factura_data = array();
            foreach ($factura_result as $value) {
                $get_details = "select * from detalle_venta where venta_id = $value->id";
                $details_result = $this->connection->query($get_details)->result();
                $vr_impuesto = 0;
                $vr_valor = 0;
                $vr_pdv = 0;
                $vr_pdv1 = 0;
                $vr_column = 0;
                $vr_bruto = 0;
                foreach ($details_result as $detail) {

                    $vr_pdv = $detail->precio_venta * $detail->unidades;
                    $vr_pdv1 = $detail->descuento * $detail->unidades;
                    $vr_pdv2 = $detail->precio_venta - $detail->descuento;

                    $vr_bruto += $vr_pdv;
                    $vr_impuesto += $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                    $vr_column = $vr_pdv - $vr_pdv1;
                    $vr_valor += $vr_pdv - $vr_pdv1;
                }

                $vendedor = '';
                $total_vendedor = "SELECT nombre FROM  `vendedor` WHERE id = '" . $value->vendedor . "'";
                $total_vendedor_result = $this->connection->query($total_vendedor)->result();
                foreach ($total_vendedor_result as $dat1) {
                    $vendedor = $dat1->nombre;
                }

                $total_opciones = "SELECT valor_opcion FROM  `opciones` WHERE id = '1'";
                $total_opciones_result = $this->connection->query($total_opciones)->result();
                foreach ($total_opciones_result as $dat1) {
                    $empresa = $dat1->valor_opcion;
                }

                $factura_data[] = array('factura' => $value->factura,
                    'vendedor' => $vendedor,
                    'empresa' => $empresa,
                    'venta_plan_activo' => $value->venta_plan_activo,
                    'total_venta' => $value->total_venta,
                    'vr_impuesto' => ($vr_impuesto),
                    'vr_valor' => ($vr_valor),
                    'descuento' => ($value->descuento * $detail->unidades),
                    'vr_bruto' => ($vr_bruto),
                    'vr_neto' => ($vr_valor + $vr_impuesto),
                    'id_almacen' => $value->almacen_id,
                    'unidades' => $value->valor_unidades,
                    'precio_unidad' => $value->precio_unidad,
                );

                $vrBrutoTotal += $vr_bruto;
                $vrImpuestoTotal += $vr_impuesto;
                $vrDescuentoTotal += $value->descuento * $detail->unidades;
                $vrTotal += $vr_valor + $vr_impuesto;
            }

            //var_dump($impuesto_result);die();
            return array(
                'forma_pago' => $forma_pago_result1
                , 'impuesto_result' => (isset($impuesto_result) && $impuesto_result != null) ? $impuesto_result : 0
                , 'factura_data' => $factura_data
                , 'gastos' => (isset($gastos_pago_result) && $gastos_pago_result != null) ? $gastos_pago_result : 0
                , 'forma_pago_credito' => $forma_pago_credito
                , 'forma_pago_proveedor' => $forma_pago_proveedor
                , 'forma_pago_ventas' => $forma_pago_ventas
                , 'vrBrutoTotal' => ($vrBrutoTotal)
                , 'vrImpuestoTotal' => ($vrImpuestoTotal)
                , 'vrDescuentoTotal' => ($vrDescuentoTotal)
                , 'vrTotal' => ($vrTotal)
                , 'ventas_anuladas' => $ventasAnuladas
                , 'ventas_devueltas' => $ventasDevueltas,
            );
        }
    }

    //Ventas por clientes
    public function ventasgroupclientes_29_12_2016($fecha_inicio = "", $fecha_fin = "", $accion = true)
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where v.fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where v.fecha < '$fecha_fin'";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sql = "SELECT count(v.id) cantidad, sum(v.total_venta) total_venta, c.nombre_comercial AS cliente
                FROM venta AS v
                    inner JOIN clientes AS c ON v.cliente_id =  c.id_cliente $filtro_fecha
					and v.estado = 0
                        group by(c.id_cliente)
                        ORDER BY total_venta";
        }
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $sql = "SELECT count(v.id) cantidad, sum(v.total_venta) total_venta, c.nombre_comercial AS cliente
                FROM venta AS v
                    inner JOIN clientes AS c ON v.cliente_id =  c.id_cliente $filtro_fecha
					and v.estado = 0 and  v.almacen_id  = $almacen
                        group by(c.id_cliente)
                        ORDER BY total_venta";
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->cliente,
                $value->cantidad,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->total_venta) : $value->total_venta,
                //$value->total_venta
            );
        }

        return array(
            'aaData' => $data,
        );
    }
    // Nueva funcion ventas por clientes
    public function ventasgroupclientes($fecha_inicio = "", $fecha_fin = "", $accion = true)
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where v.fecha > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where v.fecha < '$fecha_fin'";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sql = "SELECT v.factura,v.fecha,v.vendedor, v.total_venta total_venta, c.nombre_comercial AS cliente,
                c.tipo_identificacion, c.nif_cif, c.telefono, c.email,v.id
                 FROM venta AS v
                    inner JOIN clientes AS c ON v.cliente_id =  c.id_cliente $filtro_fecha
					and v.estado = 0

                        ORDER BY total_venta";
        }
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $this->load->model("almacenes_model", 'almacenes');
            $this->almacenes->initialize($this->connection);
            $almacen = $this->almacenes->getIdAlmacenActualByUserId($this->ion_auth->get_user_id());
            $sql = "SELECT v.factura,v.fecha,v.vendedor, v.total_venta total_venta, c.nombre_comercial AS cliente,
                c.tipo_identificacion, c.nif_cif, c.telefono, c.email,v.id
                FROM venta AS v
                    inner JOIN clientes AS c ON v.cliente_id =  c.id_cliente $filtro_fecha
					and v.estado = 0 and  v.almacen_id  = $almacen
                        group by(c.id_cliente)
                        ORDER BY total_venta";
        }

        //var_dump($sql);die();
        $data = array();
        $this->load->model('vendedores_model', 'vendedores');
        $this->vendedores->initialize($this->dbConnection);
        foreach ($this->connection->query($sql)->result() as $value) {
            if (isset($value->vendedor)) {
                $info_vendedor = $this->vendedores->get_by_id($value->vendedor);
            }

            $cantidad_articulos = $this->cantidad_articulos_en_venta(array('venta_id' => $value->id));
            $data[] = array(
                $value->factura,
                $value->fecha,
                $value->cliente,
                $value->tipo_identificacion . ' ' . $value->nif_cif,
                $value->telefono,
                $value->email,
                (isset($value->vendedor)) ? $info_vendedor['nombre'] : '',
                $cantidad_articulos->unidades,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->total_venta) : $value->total_venta,
                //$value->total_venta
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    //suma de cantidad de productos comprados en una venta
    public function cantidad_articulos_en_venta($where)
    {
        $this->connection->where($where);
        $this->connection->select_sum('unidades');
        $query = $this->connection->get('detalle_venta');
        return $query->row();

    }

    //Informes de gastos
    public function informe_gastos($fecha_inicio, $fecha_fin, $opc, $almacen = 0)
    {
        $filtro_fecha = "";
        $filtro_fecha2 = "";

        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
            $filtro_fecha2 = " where fecha_pago BETWEEN '$fecha_inicio' and '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where fecha > '$fecha_inicio'";
            $filtro_fecha2 = " where fecha_pago > '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where fecha < '$fecha_fin'";
            $filtro_fecha2 = " where fecha_pago < '$fecha_fin'";
        }

        if ($almacen != 0) {
            if (empty($filtro_fecha)) {
                $filtro_fecha = "where a.id =$almacen";
            } else {
                $filtro_fecha .= " AND a.id=$almacen";
                $filtro_fecha2 .= " AND a.id=$almacen";
            }
        }
        if ($filtro_fecha == "") {
            $filtro_fecha .= " where notas NOT LIKE '%eliminado%'";
        } else {
            $filtro_fecha .= " AND notas NOT LIKE '%eliminado%'";
        }

        /*$sql = "SELECT p.*,i.*,proveedores.*,a.*,cd.nombre AS cuenta_dinero
        FROM proformas p
        LEFT JOIN impuesto i ON i.id_impuesto = p.id_impuesto
        INNER JOIN proveedores ON p.id_proveedor = proveedores.id_proveedor
        INNER JOIN almacen a ON p.`id_almacen` = a.`id`
        LEFT JOIN cuentas_dinero cd ON cd.id = p.id_cuenta_dinero $filtro_fecha
        ORDER BY fecha DESC
        ";*/

        //se adiciona un union para traer los pagos hechos a las ordenes de compra
        $sql = "(SELECT
            p.fecha AS fecha,
            p.valor AS valor,
            p.cantidad AS cantidad,
            p.id_proforma AS consecutivo,
            i.nombre_impuesto AS nombre_impuesto,
            i.porciento AS porciento,
            proveedores.nombre_comercial AS nombre_comercial,
            a.nombre AS nombre,
            p.descripcion AS descripcion,
            cd.nombre AS cuenta_dinero
            FROM proformas p
                LEFT JOIN impuesto i ON i.id_impuesto = p.id_impuesto
                INNER JOIN proveedores ON p.id_proveedor = proveedores.id_proveedor
                INNER JOIN almacen a ON p.`id_almacen` = a.`id`
                LEFT JOIN cuentas_dinero cd ON cd.id = p.id_cuenta_dinero $filtro_fecha
            )
            UNION ALL

            (SELECT
            poc.fecha_pago AS fecha,
            poc.cantidad AS valor,
            1 AS cantidad,
            poc.id_pago AS consecutivo,
            'Sin impuesto' AS nombre_impuesto,
            0 AS porciento,
            proveedores.nombre_comercial AS nombre_comercial,
            a.nombre AS nombre,
            CONCAT('Pago orden de compra ',oc.id) AS descripcion,
            'caja menor' AS cuenta_dinero
            FROM pago_orden_compra poc
            INNER JOIN orden_compra oc ON poc.`id_factura` = oc.`id`
            INNER JOIN proveedores ON oc.cliente_id = proveedores.id_proveedor
            INNER JOIN almacen a ON oc.`almacen_id` = a.`id`
            /*INNER JOIN clientes cl ON oc.`cliente_id` = cl.`id_cliente` */
            $filtro_fecha2)
            ORDER BY consecutivo DESC
        ";

        $data = array();
        if ($opc == 'excel') {
            foreach ($this->connection->query($sql)->result() as $value) {
                $valor_impuesto = (!empty($value->porciento) && $value->porciento != "") ? ($value->valor * $value->porciento / 100) : 0;
                $data[] = array(
                    $value->consecutivo,
                    $value->fecha,
                    $value->nombre_comercial,
                    $value->descripcion,
                    $value->valor,
                    $value->nombre_impuesto,
                    $value->cantidad,
                    $valor_impuesto + $value->valor,
                    $value->nombre,
                    $value->cuenta_dinero,
                );
            }
            return array(
                'aaData' => $data,
            );
        } else {
            foreach ($this->connection->query($sql)->result() as $value) {
                $valor_impuesto = $value->valor * $value->porciento / 100;
                $data[] = array(
                    $value->consecutivo,
                    $value->fecha,
                    $value->nombre_comercial,
                    $value->descripcion,
                    $this->opciones_model->formatoMonedaMostrar($value->valor),
                    $value->nombre_impuesto,
                    $value->cantidad,
                    $this->opciones_model->formatoMonedaMostrar($valor_impuesto + $value->valor),
                    $value->nombre,
                    $value->cuenta_dinero,
                );
            }
            return array(
                'aaData' => $data,
            );
        }
    }

    //Informes
    public function informe_impuesto($fecha_inicio, $fecha_fin)
    {
        //------------------------------------------------ almacen usuario
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
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

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) <= '$fecha_fin'";
        }
        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sql = "SELECT * FROM venta AS v
                    inner JOIN ventas_pago AS vp ON vp.id_venta =  v.id $filtro_fecha  and v.estado=0 ";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            $sql = "SELECT * FROM venta AS v
                    inner JOIN ventas_pago AS vp ON vp.id_venta =  v.id $filtro_fecha  and v.estado=0 and v.almacen_id = $almacen";
        }

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $get_details = "select * from detalle_venta where venta_id = $value->id";
            $details_result = $this->connection->query($get_details)->result();
            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            foreach ($details_result as $detail) {
                $vr_pdv += $detail->precio_venta * $detail->unidades;
                $vr_pdv1 += $detail->descuento * $detail->unidades;
                $vr_pdv2 = $detail->precio_venta - $detail->descuento;

                $vr_bruto += $vr_pdv - $vr_pdv1;
                $vr_impuesto += (($vr_pdv2 * $detail->impuesto) / 100) * $detail->unidades;
                $vr_column = $vr_pdv - $vr_pdv1;
                $vr_valor += $vr_pdv - $vr_pdv1;
            }

            if (isset($data[$value->factura])) {
                $data[$value->factura][6] = $data[$value->factura][6] . ',' . $value->forma_pago;
            } else {
                $data[$value->factura] = array(
                    $value->fecha,
                    $value->factura,
                    $this->opciones_model->formatoMonedaMostrar(($vr_impuesto)),
                    $this->opciones_model->formatoMonedaMostrar(($vr_pdv1)),
                    $this->opciones_model->formatoMonedaMostrar(($vr_pdv - $vr_pdv1)),
                    $this->opciones_model->formatoMonedaMostrar((($vr_pdv - $vr_pdv1) + $vr_impuesto)),
                    $value->forma_pago,
                );
            }

        }
        //para que el ajax ignore las key y muestre en el datatable
        $devolver = array();
        foreach ($data as $key => $value) {
            $devolver[] = $value;
        }

        return array('aaData' => $devolver);
    }

    //Informes
    public function informe_impuesto_excel($fecha_inicio, $fecha_fin)
    {
        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <=  '$fecha_fin'";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where date(v.fecha) >= '$fecha_inicio'";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where date(v.fecha) <= '$fecha_fin'";
        }

        $sql = "SELECT * FROM venta AS v
                    inner JOIN ventas_pago AS vp ON vp.id_venta =  v.id $filtro_fecha  and v.estado=0";
        $data = array();

        //devoluciones
        $subtotal_devoluciones = 0;
        $total_devoluciones = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%m') AS fecha ,
             /*SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
             SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta */
             SUM((dv.unidades - (SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))) * dv.descuento) AS total_descuento,
             SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * (dv.unidades - (SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))))) AS impuesto ,
             SUM((dv.precio_venta * (dv.unidades - (SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1))))) AS total_precio_venta
             FROM venta v
             INNER JOIN detalle_venta dv ON v.id=dv.venta_id
             LEFT JOIN devoluciones d ON v.factura=d.factura
             WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
             AND DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin' AND v.estado = '0'
             GROUP BY DATE_FORMAT(v.fecha ,'%m') ORDER BY v.fecha";

        $total_devoluciones_result = $this->connection->query($total_devoluciones)->result();
        if (count($total_devoluciones_result) > 0) {
            foreach ($total_devoluciones_result as $devolucion) {
                $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
            }
        }

        foreach ($this->connection->query($sql)->result() as $value) {
            $get_details = "select * from detalle_venta where venta_id = $value->id";
            $details_result = $this->connection->query($get_details)->result();
            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            foreach ($details_result as $detail) {
                $vr_pdv += $detail->precio_venta * $detail->unidades;
                $vr_pdv1 += $detail->descuento * $detail->unidades;
                $vr_pdv2 = $detail->precio_venta - $detail->descuento;

                $vr_bruto += $vr_pdv - $vr_pdv1;
                $vr_impuesto += $vr_pdv2 * $detail->impuesto / 100 * $detail->unidades;
                $vr_column = $vr_pdv - $vr_pdv1;
                $vr_valor += $vr_pdv - $vr_pdv1;
            }

            if (isset($data[$value->factura])) {
                $data[$value->factura][6] = $data[$value->factura][6] . ',' . $value->forma_pago;
            } else {
                $data[$value->factura] = array(
                    $value->fecha,
                    $value->factura,
                    $vr_pdv - $vr_pdv1, //number_format(),
                    $vr_impuesto,
                    $vr_pdv1,
                    $vr_pdv - $vr_pdv1 + $vr_impuesto,
                    $value->forma_pago,
                );
            }

        }

        return array(
            'aaData' => $data,
            //'devoluciones' => $this->opciones_model->formatoMonedaMostrar($subtotal_devoluciones)
            'devoluciones' => $subtotal_devoluciones,
        );
    }

    public function informe_vendedores($fecha_inicio, $fecha_fin, $vendedor)
    {

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = "  and date(v.fecha) >= '" . $fecha_inicio . "' and date(v.fecha) <= '" . $fecha_fin . "'  ";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = "  and date(v.fecha) >= '" . $fecha_inicio . "'  ";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = "  and date(v.fecha) <= '" . $fecha_fin . "'  ";
        }
        if ($vendedor != "") {
            $filtro_fecha .= " and d.id = $vendedor ";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $id_user = '';
        $alm = '';

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
            $db_config_id = $this->session->userdata('db_config_id');
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }
            //---------------------------------------------
        }

        $sql = "SELECT
                    v.*,
                    a.nombre AS nombre_almacen,
                    d.nombre AS nombre_vendedor,
                    IFNULL(d.comision, 0) AS comision,
                    SUM( dv.unidades * dv.descuento ) AS total_descuento,
                    SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades) AS impuesto,
                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                    (SELECT SUM(vp.valor_entregado) AS saldo_favor FROM ventas_pago AS vp WHERE forma_pago = 'Saldo_a_Favor' AND vp.id_venta = v.id ) AS saldo
                FROM vendedor d, venta v
                INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                INNER JOIN almacen a ON v.almacen_id = a.id
                WHERE (d.id = v.`vendedor` OR d.id = v.`vendedor_2`)
                AND v.`estado` = 0
                $filtro_fecha $alm
                GROUP BY d.id, v.id";

        //echo $sql;
        $data = array();

        $resultado = $this->connection->query($sql)->result_array();
        foreach ($resultado as $value) {
            $total_comision = ((($value['total_precio_venta'] - $value['total_descuento']) + $value['impuesto']) - $value['saldo']) * $value['comision'] / 100;
            $dividir_comision = $this->__count_array_key_helper($resultado, 'factura', $value['factura']);

            if ($dividir_comision > 1) {
                $total_comision = $total_comision / $dividir_comision;
            }

            $data[] = array(
                $value['nombre_vendedor'],
                $value['nombre_almacen'],
                $value['fecha'],
                $value['factura'],
                $this->opciones_model->formatoMonedaMostrar(((($value['total_precio_venta'] - $value['total_descuento']) + $value['impuesto']) - $value['saldo'])),
                $value['comision'],
                $this->opciones_model->formatoMonedaMostrar($total_comision),
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    private function __count_array_key_helper(array $_array, $_key, $_value)
    {
        $total = 0;
        foreach ($_array as $value) {
            if ($value[$_key] == $_value) {
                $total++;
            }
        }

        return $total;
    }

    //informe por utilidad por vendedores

    public function informe_vendedores_utilidad($fecha_inicio, $fecha_fin, $vendedor)
    {

        $utilidad = 0;
        $vr_costos = 0;
        //$vr_valor =0;

        $filtro_fecha = "";
        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = "  and date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <= '$fecha_fin'  ";
            if ($vendedor != "") {
                $filtro_fecha .= " and vd.id = $vendedor ";
            }
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = "  and date(v.fecha) >= '$fecha_inicio'  ";
            if ($vendedor != "") {
                $filtro_fecha .= " and vd.id = $vendedor ";
            }
        } elseif ($fecha_fin != "") {
            $filtro_fecha = "  and date(v.fecha) <= '$fecha_fin'  ";
            if ($vendedor != "") {
                $filtro_fecha .= " and vd.id = $vendedor ";
            }
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $sql = "SELECT v.*, a.nombre as nombre_almacen, vd.nombre as nombre_vendedor, IFNULL(vd.comision, 0) as comision FROM venta AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN vendedor AS vd ON vd.id =  v.vendedor where v.estado = 0 $filtro_fecha";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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
            $sql = "SELECT v.*, a.nombre as nombre_almacen, vd.nombre as nombre_vendedor, IFNULL(vd.comision, 0) as comision FROM venta AS v
                    inner join almacen a on v.almacen_id = a.id
                    inner JOIN vendedor AS vd ON vd.id =  v.vendedor where v.estado = 0  $filtro_fecha and v.almacen_id = $almacen";
        }

        $data = array();
        $rest1 = '';
        $detalleventaid = '';

        foreach ($this->connection->query($sql)->result() as $value) {

            $get_details = "SELECT codigo_producto,
				                         producto_id,
                                        nombre_producto,
										sum( `unidades` * `descuento` ) AS total_descuento,
										sum( `margen_utilidad` ) AS total_utilidad,
                                        sum(unidades) as unidades ,
                                        sum(producto.precio_compra * detalle_venta.unidades) as total_compra
                                        FROM detalle_venta
										inner JOIN producto ON detalle_venta.producto_id = producto.id
										WHERE venta_id = $value->id
                                        GROUP BY venta_id";

            $details_result = $this->connection->query($get_details)->result();

            foreach ($details_result as $detail) {
                $total_descuento = $detail->total_descuento;
                $margen_utilidad = $detail->total_utilidad;
                $vr_costos = $detail->total_compra;
            }
            /*
            $get_details = "SELECT producto_id FROM detalle_venta WHERE venta_id = 120";
            foreach ($details_result as $detail) {
            $detalleventaid = $detalleventaid.",'".$detail->producto_id."'";
            }

            $rest1 = substr($detalleventaid,2);
            if($rest1 == ''){  $rest1 = 0; }
            else{    $rest1 = "'".substr($detalleventaid,2);     }

            echo $detalle_inventario = "SELECT sum(precio_compra) as total_compra FROM producto where id in (".$rest1.") ";

            $detalle_inventario_id = $this->connection->query($detalle_inventario)->result();

            foreach ($detalle_inventario_id as $prod) {
            $vr_costos =  $prod->total_compra;
            }
             */

            $data[] = array(
                $value->nombre_vendedor,
                $value->nombre_almacen,
                $value->fecha,
                $value->factura,
                //$this->opciones_model->formatoMonedaMostrar($value->total_venta), //number_format($vr_valor),
                $value->total_venta, //number_format($vr_valor),
                //$this->opciones_model->formatoMonedaMostrar($vr_costos),
                //$this->opciones_model->formatoMonedaMostrar($margen_utilidad),
                $vr_costos,
                $margen_utilidad,
                $value->comision,
                //$this->opciones_model->formatoMonedaMostrar($margen_utilidad * $value->comision / 100)
                ($margen_utilidad * $value->comision / 100),
            );

            $rest1 = '';
            $detalleventaid = '';
        }

        return array(
            'aaData' => $data,
        );
    }

    public function total_vendedores($fecha_inicio, $fecha_fin, $almacen)
    {
        $filtro_fecha = "";
        $filtro_almacen = '';

        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " and date(v.fecha) >= '$fecha_inicio' and date(v.fecha) <= '$fecha_fin' and v.estado = 0 ";
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = "  and date(v.fecha) >= '$fecha_inicio' ";
        } elseif ($fecha_fin != "") {
            $filtro_fecha = "  and date(v.fecha) <= '$fecha_fin' ";
        }

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen != 0 && $almacen != '') {
                $filtro_almacen = ' and v.almacen_id = ' . $almacen;
            }
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $filtro_almacen = ' AND v.almacen_id =  ' . $dat->almacen_id;
            }
        }

        $sql = "SELECT
                    v.*,
                    a.nombre AS nombre_almacen,
                    d.id AS id_vendedor,
                    d.nombre AS nombre_vendedor,
                    IFNULL(d.comision, 0) AS comision,
                    SUM( dv.unidades * dv.descuento ) AS total_descuento,
                    SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades) AS impuesto,
                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta,
                    (SELECT SUM(vp.valor_entregado) AS saldo_favor FROM ventas_pago AS vp WHERE forma_pago = 'Saldo_a_Favor' AND vp.id_venta = v.id ) AS saldo
                FROM vendedor d, venta v
                INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id
                INNER JOIN almacen a ON v.almacen_id = a.id
                WHERE (d.id = v.`vendedor` OR d.id = v.`vendedor_2`)
                AND v.`estado` = 0
                $filtro_fecha $filtro_almacen
                GROUP BY d.id";

        $total = array();

        foreach ($this->connection->query($sql)->result() as $value) {
            /* Devoluciones (NC)*/
            $totaldevoluciones = 0;
            $subtotal_devoluciones = 0;
            ####todos
            $total_devoluciones = "SELECT v.id, v.factura, v.fecha, SUM(d.valor) AS total_devolucion
            FROM devoluciones d
            INNER JOIN venta v ON d.factura=v.factura
            INNER JOIN vendedor ven ON v.vendedor = ven.id
            WHERE DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
            AND v.estado = '0' AND ven.id = '$value->id_vendedor'
            GROUP BY v.factura";

            $total_devoluciones = $this->connection->query($total_devoluciones)->result();
            $idFactura = "";
            foreach ($total_devoluciones as $key1 => $value1) {
                $totaldevoluciones += $value1->total_devolucion;
            }

            $total[] = array('nombre_vendedor' => $value->nombre_vendedor,
                'nombre_almacen' => $value->nombre_almacen,
                'impuesto' => $this->opciones_model->formatoMonedaMostrar($value->impuesto),
                'subtotal' => $this->opciones_model->formatoMonedaMostrar((((($value->total_precio_venta - $value->total_descuento) + $value->impuesto) - $value->saldo) - $value->impuesto) - $totaldevoluciones),
                'total_venta' => $this->opciones_model->formatoMonedaMostrar(((($value->total_precio_venta - $value->total_descuento) + $value->impuesto) - $value->saldo) - $totaldevoluciones),
                'comision' => $value->comision,
                'total_comision' => $this->opciones_model->formatoMonedaMostrar((((($value->total_precio_venta - $value->total_descuento) + $value->impuesto) - $value->saldo) - $totaldevoluciones) * $value->comision / 100),
            );
        }

        return array(
            'total_vendedor' => $total,
        );
    }

    public function informe_movimientos($fecha_inicio, $fecha_fin, $almacen)
    {
        $filtro_fecha = "";
        $filtro_fecha_transacciones = "";
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $limit = "";

        if ($fecha_inicio == "" && $fecha_fin == "") {
            $limit = " LIMIT 1000;";
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($fecha_inicio != "" && $fecha_fin != "") {
                $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                $filtro_fecha_transacciones = " where s.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                    $filtro_fecha_transacciones .= " and s.almacen_id = $almacen";
                }
            } elseif ($fecha_inicio != "") {
                $filtro_fecha = " where v.fecha > '$fecha_inicio'";
                $filtro_fecha_transacciones = " where S.fecha > '$fecha_inicio'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                    $filtro_fecha_transacciones .= " and S.almacen_id = $almacen";
                }
            } elseif ($fecha_fin != "") {
                $filtro_fecha = " where v.fecha < '$fecha_fin'";
                $filtro_fecha_transacciones = " where S.fecha < '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                    $filtro_fecha_transacciones .= " and s.almacen_id = $almacen";
                }
            }

            if (($fecha_fin == "" && $fecha_inicio == "") && $almacen != "") {
                $filtro_fecha .= " where v.almacen_id = $almacen";
                $filtro_fecha_transacciones .= " where s.almacen_id = $almacen";
            }
        }

        $db_config_id = $this->session->userdata('db_config_id');
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            
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
            if ($fecha_inicio != "" && $fecha_fin != "") {
                $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                $filtro_fecha_transacciones = " where s.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                    $filtro_fecha_transacciones .= " and a.id = $almacen";
                }
            } elseif ($fecha_inicio != "") {
                $filtro_fecha = " where v.fecha > '$fecha_inicio'";
                $filtro_fecha_transacciones = " where s.fecha > '$fecha_inicio'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                    $filtro_fecha_transacciones .= " and s.almacen_id = $almacen";
                }
            } elseif ($fecha_fin != "") {
                $filtro_fecha = " where v.fecha < '$fecha_fin'";
                $filtro_fecha_transacciones = " where s.fecha < '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and v.almacen_id = $almacen";
                    $filtro_fecha_transacciones .= " and s.almacen_id = $almacen";
                }
            }

            if (($fecha_fin == "" && $fecha_inicio == "") && $almacen != "") {
                $filtro_fecha .= " where v.almacen_id = $almacen";
                $filtro_fecha_transacciones .= " where s.almacen_id = $almacen";
            }
        }

        $usuarios = $this->db->get_where('users', array('db_config_id' => $db_config_id))->result_array();

        $sql = "SELECT
                    v.fecha,
                    CONCAT(u.first_name, ' ', u.last_name) as usuario,
                    v.codigo_factura,
                    v.id AS consecutivo,
                    a.nombre,
                    ad.nombre as destino,
                    v.nota as nota,
                    pr.id as id_producto,
                    pr.nombre as producto_nombre,
                    pr.codigo as producto_codigo,
                    pr.descripcion as descripcion_producto,
                    pr.precio_compra AS costo,
                    uni.nombre AS unidad,
                    IF(v.tipo_movimiento ='devolucion_auditoria',CONCAT('-',md.cantidad),
                    IF(v.tipo_movimiento ='salida_auditoria',CONCAT('-',md.cantidad),
                    IF(v.tipo_movimiento ='devolucion_orden',CONCAT('-',md.cantidad), IF(v.tipo_movimiento ='salida_remision',CONCAT('-',md.cantidad),IF(v.tipo_movimiento ='salida_ajustes',CONCAT('-',md.cantidad),IF(v.tipo_movimiento ='salida_remision',CONCAT('-',md.cantidad),IF(v.tipo_movimiento ='salida_devolucion',CONCAT('-',md.cantidad),IF(v.tipo_movimiento ='salida_rotura',CONCAT('-',md.cantidad),md.cantidad)))))))) as cantidad,
                    v.tipo_movimiento
                    FROM movimiento_inventario AS v
                    INNER JOIN almacen a on v.almacen_id = a.id
                    LEFT JOIN almacen ad on v.almacen_traslado_id = ad.id
                    LEFT JOIN vendty2.users u on v.user_id = u.id
                    INNER JOIN movimiento_detalle AS md ON md.id_inventario =  v.id
                    LEFT JOIN producto as pr ON pr.id = md.producto_id
                    INNER JOIN unidades as uni ON pr.unidad_id = uni.id
                    $filtro_fecha
                    AND v.tipo_movimiento != 'traslado'
                    UNION ALL (SELECT
                        s.fecha,
                        s.usuario as usuario,
                        s.cod_documento as codigo_factura,
                        mv.id AS consecutivo,
                        am.nombre,
                        ad.nombre AS destino,
                        mv.nota as nota,
                        p.id AS id_producto,
                        p.nombre AS producto_nombre,
                        p.codigo AS producto_codigo,
                        p.descripcion as descripcion_producto,
                        p.precio_compra AS costo,
                        uni.nombre AS unidad,
                        s.unidad AS cantidad,
                        IF(s.razon = 'S','venta', IF(s.razon = 'ST','salida_traslado',IF(s.razon = 'ET','Entrada_traslado',IF(s.razon = 'EP','Entrada_Produccion',IF(s.razon = 'SP','Salida_Produccion','')))))
                        FROM stock_diario AS s
                        INNER JOIN almacen a ON s.almacen_id = a.id
                        INNER JOIN producto p ON s.producto_id = p.id
                        INNER JOIN unidades as uni ON p.unidad_id = uni.id
                        LEFT JOIN movimiento_inventario mv on mv.id = s.cod_documento
                        LEFT JOIN almacen ad on mv.almacen_traslado_id = ad.id
                        LEFT JOIN almacen am on mv.almacen_id = am.id
                        $filtro_fecha_transacciones
                        AND s.razon != 'E' AND s.razon != 'SM'
                    ORDER BY s.fecha desc) ORDER BY fecha DESC $limit";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            
            $usuario = $value->usuario;
            if(!empty($usuario) && is_numeric($usuario)){
                foreach($usuarios as $user_row) {
                    if($user_row['id'] == $usuario) {
                        $usuario = $user_row['first_name'] . " " . $user_row['last_name'];
                        break;
                    }
                }
            }

            $data[] = array(
                $value->fecha,
                $usuario,
                $value->codigo_factura,
                $value->consecutivo,
                $value->nota,
                $value->nombre,
                $value->destino,
                $value->id_producto,
                $value->producto_nombre,
                $value->producto_codigo,
                $value->descripcion_producto,
                $value->costo,
                $value->unidad,
                $value->cantidad,
                $value->tipo_movimiento,
            );
        }

        /*
        $sql_transacciones = "SELECT
        v.fecha,
        v.cod_documento,
        CONCAT(u.first_name, ' ', u.last_name) as usuario,
        p.id AS id,
        p.codigo AS codigo,
        a.nombre AS almacen,
        p.nombre AS producto_nombre,
        p.descripcion as descripcion_producto,
        v.unidad AS cantidad,
        v.razon AS tipo_movimiento,
        u.username
        FROM stock_diario AS v
        INNER JOIN almacen a ON v.almacen_id = a.id
        INNER JOIN producto p ON v.producto_id = p.id
        INNER JOIN vendty2.users u ON v.usuario = u.id
        $filtro_fecha
        ORDER BY v.fecha desc limit 1000
        ";

        foreach ($this->connection->query($sql_transacciones)->result() as $value) {
        $data[] = array(
        $value->fecha,
        $value->usuario,
        $value->cod_documento,
        '',
        $value->almacen,
        '',
        $value->id,
        $value->producto_nombre,
        $value->codigo,
        $value->descripcion_producto,
        $value->cantidad,
        $value->tipo_movimiento,
        );
        }*/

        return array(
            'aaData' => $data,
        );
    }

    public function stock_diario($fecha_inicio, $fecha_fin, $almacen)
    {

        $filtro_fecha = "";
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($fecha_inicio != "" && $fecha_fin != "") {
                $filtro_fecha = " where stock.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and stock.almacen_id = $almacen";
                }
            } elseif ($fecha_inicio != "") {
                $filtro_fecha = " where stock.fecha > '$fecha_inicio'";
                if ($almacen != "") {
                    $filtro_fecha .= " and stock.almacen_id = $almacen";
                }
            } elseif ($fecha_fin != "") {
                $filtro_fecha = " where stock.fecha < '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and stock.almacen_id = $almacen";
                }
            }

            if (($fecha_fin == "" && $fecha_inicio == "") && $almacen != "") {
                $filtro_fecha .= " where stock.almacen_id = $almacen";
            }
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
            if ($fecha_inicio != "" && $fecha_fin != "") {
                $filtro_fecha = " where stock.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and almacen.almacen_id = $almacen";
                }
            } elseif ($fecha_inicio != "") {
                $filtro_fecha = " where stock.fecha > '$fecha_inicio'";
                if ($almacen != "") {
                    $filtro_fecha .= " and almacen.almacen_id = $almacen";
                }
            } elseif ($fecha_fin != "") {
                $filtro_fecha = " where stock.fecha < '$fecha_fin'";
                if ($almacen != "") {
                    $filtro_fecha .= " and almacen.almacen_id = $almacen";
                }
            }

            if (($fecha_fin == "" && $fecha_inicio == "") && $almacen != "") {
                $filtro_fecha .= " where almacen.almacen_id = $almacen";
            }
        }

        /*  $sql = "SELECT stock.fecha as fecha, sum(stock.unidad) as codigo_factura, almacen.nombre as nombre,
        producto.nombre as producto_nombre, case stock.razon  when 'S' then 'Salida'  when 'E' then 'Entrada'  end  as cantidad, stock.id as tipo_movimiento
        FROM stock_diario AS stock
        INNER JOIN producto as producto ON stock.producto_id = producto.id
        INNER JOIN almacen AS almacen ON almacen.id = stock.almacen_id
        group by producto.nombre";*/

        $sql = "SELECT stock.fecha AS fecha, stock.unidad AS codigo_factura, almacen.nombre AS nombre,
                producto.nombre AS producto_nombre,
                CASE stock.razon  WHEN 'S' THEN 'Salida' WHEN 'ST' THEN 'Salida_Traslado' WHEN 'SP' THEN 'Salida_Produccion' WHEN 'E' THEN 'Entrada' WHEN 'ET' THEN 'Entrada_Traslado' WHEN 'EP' THEN 'Entrada_Produccion' END  AS cantidad,
                stock.id AS tipo_movimiento
                FROM stock_diario AS stock
                INNER JOIN producto AS producto ON stock.producto_id = producto.id
                INNER JOIN almacen AS almacen ON almacen.id = stock.almacen_id
                $filtro_fecha
                AND producto.material=1
                ORDER BY fecha DESC";
        //$sql = "SELECT id as tipo_movimiento, producto_id as codigo_factura, almacen_id as nombre, fecha, razon as producto_nombre, cod_documento as cantidad from stock_diario";
        //if( $is_admin == 't' || $is_admin == 'a'){ //administrador
        $data = array();
        //echo $sql;
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->fecha,
                $value->codigo_factura,
                $value->nombre,
                $value->producto_nombre,
                $value->cantidad,
                $value->tipo_movimiento,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function plan_separe_productos($almacen)
    {
        $filtro = "";
        if ($almacen != 0) {
            $filtro = " and pl.almacen_id = " . $almacen;
        }

        $q_simbolo = $this->connection->query('SELECT valor_opcion FROM opciones WHERE nombre_opcion = "simbolo"')->row();

        if ($q_simbolo) {
            $simbolo = $q_simbolo->valor_opcion == '' ? '$' : $q_simbolo->valor_opcion;
        }

        $sql = "SELECT (SELECT nombre_comercial FROM clientes AS c WHERE c.id_cliente = pl.cliente_id) AS cli_nom,
		               (SELECT nif_cif FROM clientes AS c WHERE c.id_cliente = pl.cliente_id) AS cli_nif_cif,
                       nombre_producto,
                       unidades,
                       precio_venta,
                       precio_venta + ((precio_venta * impuesto) / 100) as total,
                       estado,
					   (SELECT nombre FROM almacen AS a WHERE a.id = pl.almacen_id) AS al_nom
						 FROM plan_separe_detalle AS pldet INNER JOIN plan_separe_factura AS pl ON pl.id = pldet.venta_id WHERE pl.estado = '0' and estado <> '3' $filtro ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->al_nom,
                $value->cli_nom,
                $value->cli_nif_cif,
                $value->nombre_producto,
                $value->unidades,
                $simbolo . ' ' . number_format($value->precio_venta),
                $simbolo . ' ' . number_format($value->total),
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_bancos($almacen)
    {
        $data = array();
        $filtro = "";
        $this->connection->select("*");
        $this->connection->from("bancos b");
        $this->connection->join("almacen a", "a.id = b.id_almacen");
        if ($almacen != 0) {
            $this->connection->where('b.id_almacen', $almacen);
        }

        $this->connection->order_by("nombre_cuenta", "ASC");
        $result = $this->connection->get();
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $value) {
                $data[] = array(
                    $value->nombre,
                    $value->nombre_cuenta,
                    $value->numero_cuenta,
                    $value->descripcion,
                    $value->saldo_inicial,
                    $value->fecha_creacion,
                );
            }
        }

        return array(
            'aaData' => $data,
        );
    }

    public function producto_por_almacen($producto)
    {

        $sql = "SELECT p.codigo as codigo, p.nombre as nombre, a.nombre as almacen, st.unidades as unidades FROM producto as p INNER JOIN stock_actual as st ON p.id = st.producto_id INNER JOIN almacen as a ON a.id = st.almacen_id WHERE p.nombre = '$producto' OR p.codigo = '$producto' OR p.codigo_barra = '$producto' ORDER BY st.unidades DESC";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->codigo,
                $value->nombre,
                $value->almacen,
                $value->unidades,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function clientes_puntos_acumulados()
    {

        $query = "SELECT valor_opcion as total_puntos FROM opciones where nombre_opcion = 'punto_valor' ";
        $queryresult = $this->connection->query($query)->result();
        $total_puntos = '0';
        foreach ($queryresult as $dat) {
            $total_puntos = $dat->total_puntos;
        }

        $sql = "SELECT nombre_comercial, nif_cif, sum(puntos) as total_puntos FROM puntos_acumulados as pa inner join clientes on id_cliente = cliente group by id_cliente ";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->nombre_comercial,
                $value->nif_cif,
                number_format($value->total_puntos),
                $this->opciones_model->formatoMonedaMostrar($value->total_puntos * $total_puntos),
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function grafica_ventas_almacen($fechainicial, $fechafinal, $almacen, $ciudad)
    {

        $is_admin = $this->session->userdata('is_admin');

        $username = $this->session->userdata('username');

        $ventas = array();

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            if ($ciudad == '') {
                $condition1 = '';
                $inner = "";
            } else {
                $inner = " inner join almacen on v.almacen_id =  almacen.id ";
                $condition1 = " and  ciudad = '" . $ciudad . "' ";
            }
        }$alm = '';
        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $db_config_id = $this->session->userdata('db_config_id');
            $id_user = '';
            $user = $this->db->query("SELECT id FROM users where username = '" . $username . "' and db_config_id = '" . $db_config_id . "' limit 1")->result();
            foreach ($user as $dat) {
                $id_user = $dat->id;
            }

            $user = $this->connection->query("SELECT almacen_id FROM usuario_almacen where usuario_id = '" . $id_user . "' limit 1")->result();
            foreach ($user as $dat) {
                $alm = ' AND almacen_id =  ' . $dat->almacen_id;
            }
        }

        $total_ventas = "SELECT almacen_id,  (SELECT nombre FROM almacen WHERE almacen.id =  almacen_id ) AS alm_nom,
                                    SUM( dv.unidades * dv.descuento ) AS total_descuento,
                                    SUM((dv.precio_venta - dv.descuento) * dv.impuesto / 100 *  dv.unidades) AS impuesto,
                                    SUM( dv.precio_venta * dv.unidades) AS total_precio_venta
                             FROM venta AS v
                             INNER JOIN detalle_venta AS dv ON v.id=dv.venta_id $inner
     WHERE DATE(v.fecha) >= '$fechainicial'  AND  DATE(v.fecha) <=  '$fechafinal'    $alm   AND estado = 0
     GROUP BY almacen_id ";
        $saldo_favor = 0;
        $total_ventas_result = $this->connection->query($total_ventas)->result();

        foreach ($total_ventas_result as $value) {

            $ventas[] = array(
                'total_precio_venta' => (($value->total_precio_venta - $value->total_descuento) + ($value->impuesto))
                , 'alm_nom' => $value->alm_nom,
            );
        }

        return array(
            'total_ventas' => $ventas,
        );
    }

    public function export_siigo($fechainicial, $fechafinal, $almacen = '0')
    {
        $fechainicial = !empty($fechainicial) ? $fechainicial : '0000-01-01';
        $fechafinal = !empty($fechafinal) ? $fechafinal : '9999-12-31';

        $where = 'WHERE DATE(fecha) BETWEEN "' . $fechainicial . '" AND "' . $fechafinal . '"';
        if ($almacen != '0' || $almacen == '') {
            $where .= ' AND v.almacen_id = ' . $almacen;
        }

        $query = 'SELECT
            distinct v.id,
            v.`factura`,
            v.estado,
            v.total_venta,
            DATE(v.`fecha`) AS fecha,
            c.`nif_cif`,
            dv.`codigo_producto`,
            (dv.`precio_venta` * dv.`unidades`) + (((dv.`precio_venta` * dv.`impuesto`) / 100) * dv.`unidades`) AS valor_movimiento,
            (dv.`precio_venta` * dv.`unidades`) AS valor_movimiento_sin_impuesto,
            dv.`unidades`,
            dv.`descuento`,
            dv.`precio_venta`,
            ((dv.`precio_venta` * dv.`descuento`) /100) * dv.`unidades` AS total_descuento,
            dv.`impuesto`,
            ((dv.`precio_venta` * dv.`impuesto`) / 100) * dv.`unidades` AS total_impuesto
        FROM detalle_venta dv
        LEFT JOIN venta v ON dv.`venta_id` = v.`id`
        LEFT JOIN clientes c ON v.`cliente_id` = c.`id_cliente` ' . $where . ";";
        $data = $this->connection->query($query);

        if ($data->num_rows() > 0) {
            return $data->result_array();
        } else {
            return [];
        }
    }

    public function subtotalSiigo($id)
    {
        $sql = "select sum(dv.unidades*(dv.precio_venta-dv.descuento)) as subtotal from detalle_venta as dv where venta_id = $id group by venta_id";

        return $this->connection->query($sql)->row()->subtotal;
    }

    public function excentoSiigo($id)
    {
        $sql = "select sum(dv.unidades*dv.precio_venta) as subtotal from detalle_venta as dv where venta_id = $id and impuesto = 0 group by venta_id";
        $return = $this->connection->query($sql)->row();

        if (!empty($return)) {
            return $return->subtotal;
        } else {
            return 0;
        }
    }

    public function IvaSiigo($id)
    {
        //$this->connection->get_where("detalle_venta",array("venta_id"=>$id,""))
        $sql = "select sum(dv.unidades*(dv.precio_venta-dv.descuento)) as subtotal from detalle_venta as dv where venta_id = $id and impuesto <> 0 group by venta_id";
        $return = $this->connection->query($sql)->row();

        if (!empty($return)) {
            return (float) $return->subtotal * 16 / 100;
        } else {
            return 0;
        }
    }

    public function calcularDescuentoSiigo($id)
    {
        //$this->connection->get_where("detalle_venta",array("venta_id"=>$id,""))
        $sql = "select sum(dv.unidades*dv.descuento) as subtotal from detalle_venta as dv where venta_id = $id and impuesto <> 0 group by venta_id";
        $return = $this->connection->query($sql)->row();

        if (!empty($return)) {
            return (float) $return->subtotal * 16 / 100;
        } else {
            return 0;
        }
    }

    public function get_cuenta_siigo($val_impuesto = null, $id_cuenta = null)
    {

        $this->connection->select();
        if (isset($val_impuesto)) {
            $this->connection->where('valor', $val_impuesto);
        }
        if (isset($id_cuenta)) {
            $this->connection->where('id', $id_cuenta);
        }

        $this->connection->from('cuentas_siigo');

        $result = $this->connection->get()->row_array();

        return $result;
    }

    public function get_ajax_data_notas($almacen = 0, $accion = false)
    {

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        $filtro = "";
        if ($almacen != 0) {
            $filtro = "Where v.almacen_id = $almacen";
        }

        $sql = "SELECT n.consecutivo, n.devolucion_id, n.usuario_id, n.tipoNota, n.valor, n.fecha, v.factura, v.cliente_id, n.estado, n.notaforeign_id
            FROM notacredito AS n
            INNER JOIN venta AS v
            ON n.factura_id = v.`id`
            $filtro
        ";

        $this->load->model('Opciones_model', 'opciones');
        $data = array();

        foreach ($this->connection->query($sql)->result() as $value) {

            if ($value->tipoNota == "NC") {
                if ($value->estado == 0) {
                    $estado = "Redimida";
                } else {
                    $estado = "Sin Redimir";
                }
            } else {
                $estado = "";
            }
            if (is_null($value->notaforeign_id)) {
                $factura = "";
            } else {
                $f = $this->connection->get_where("notacredito", array("id" => $value->notaforeign_id))->row()->factura_id;

                if ($f != "-1") {
                    $factura = $this->connection->get_where("venta", array("id" => $f));
                    if ($factura->num_rows() > 0) {
                        $factura = $factura->row()->factura;
                    } else {
                        $factura = "";
                    }
                } else {
                    $factura = "";
                }
            }

            $comercial = $this->connection->get_where("clientes", array('id_cliente' => $value->cliente_id));
            if ($comercial->num_rows() > 0) {
                $comercial = $comercial->row()->nombre_comercial;
            } else {
                $comercial = "";
            }

            $data[] = array(
                $value->consecutivo,
                $value->devolucion_id,
                $this->db->get_where("users", array("id" => $value->usuario_id))->row()->username,
                ($value->tipoNota == "NC") ? "Nota Credito" : "Nota Debito",
                ($accion) ? $this->opciones->formatoMonedaMostrar($value->valor) : $value->valor,
                $value->fecha,
                $value->factura,
                $comercial,
                $estado,
                $factura,
            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function comprasCliente($fechainicial, $fechafinal, $almacen, $cliente, $producto, $accion = false)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $data = array();

        if ($fechainicial == "" || $fechafinal == "") {
            return array(
                'data' => $data,
            );
        }

        $where = "where v.fecha between '" . $fechainicial . "' and '" . $fechafinal . "' AND dv.nombre_producto <> 'PROPINA' ";
        if (!empty($almacen)) {
            $where .= " AND v.almacen_id = $almacen";
        }
        if ($cliente != "") {
            $where .= " AND v.cliente_id = $cliente";
        }
        if ($producto != "") {
            $where .= " AND dv.producto_id = $producto";
        }
//        $sql = "SELECT c.nombre_comercial, dv.nombre_producto, SUM(dv.unidades) as cantidad, dv.precio_venta +(dv.precio_venta * dv.impuesto/100) as precio_venta, sum(dv.precio_venta +(dv.precio_venta * dv.impuesto/100)) as total FROM venta as v INNER JOIN detalle_venta as dv ON v.id = dv.venta_id INNER JOIN clientes as c ON c.id_cliente = v.cliente_id $where group by dv.codigo_producto, v.cliente_id";
        // Se corrige consulta, se muestra correctamente el informe
        $sql = "SELECT c.nombre_comercial, dv.codigo_producto, dv.nombre_producto, SUM(dv.unidades) AS cantidad,
                dv.precio_venta +(dv.precio_venta * dv.impuesto/100) AS precio_venta, v.fecha as fecha_venta,
                SUM(dv.unidades)*(dv.precio_venta +(dv.precio_venta * dv.impuesto/100)) AS total,
                v.factura,v.id
                FROM venta as v INNER JOIN detalle_venta as dv ON v.id = dv.venta_id
                INNER JOIN clientes as c ON c.id_cliente = v.cliente_id
                $where group by dv.codigo_producto, v.cliente_id ORDER BY c.nombre_comercial ASC,dv.nombre_producto ASC";

        //echo $total_ventas."------------------";
        //var_dump($sql);die();
        $datos = $this->connection->query($sql)->result();

        $data['columnas'] = [
            'Cliente',
            'Código',
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'Precio Total',
            'Fecha Venta',
        ];

        foreach ($datos as $value) {

            $data[] = array(
                $value->nombre_comercial,
                $value->codigo_producto,
                $value->nombre_producto,
                $value->cantidad,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->total) : $value->total,
                //$value->precio_venta
                $value->fecha_venta,
            );
        }

        return array(
            'total_ventas' => $data,
        );
    }

    public function suma_valor_pagado_nota_credito($where)
    {
        $this->connection->where($where);

    }

    public function ventasVendedor($fechainicial, $fechafinal, $almacen, $vendedor, $producto, $accion = false)
    {
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');
        $data = array();

        if ($fechainicial == "" || $fechafinal == "") {
            return array(
                'data' => $data,
            );
        }

        $where = "where v.fecha between '" . $fechainicial . " 00:00:00' and '" . $fechafinal . " 23:59:59' AND dv.nombre_producto <> 'PROPINA' ";

        if (!empty($almacen)) {
            $where .= " AND v.almacen_id = $almacen";
        }

        if ($vendedor != "") {
            $where .= " AND v.vendedor = $vendedor";
        }

        if ($producto != "") {
            $where .= " AND dv.producto_id = $producto";
        }

        $sql = "SELECT ve.nombre, dv.nombre_producto, SUM(dv.unidades) as cantidad, dv.precio_venta + (dv.precio_venta * dv.impuesto/100) as precio_venta, SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) + SUM((dv.precio_venta * dv.unidades)) AS total, al.nombre as nombre_almacen FROM venta as v INNER JOIN detalle_venta as dv ON v.id = dv.venta_id INNER JOIN vendedor as ve ON ve.id = v.vendedor INNER JOIN almacen al ON al.id = v.almacen_id $where group by dv.codigo_producto, ve.id";
        
        $datos = $this->connection->query($sql)->result();
        $data['columnas'] = [
            'Vendedor',
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'Precio Total',
            'Almacen',
            //'# factura',
            //'Total devoluciones',
        ];

        foreach ($datos as $value) {
            $data[] = array(
                $value->nombre,
                $value->nombre_producto,
                $value->cantidad,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->precio_venta) : $value->precio_venta,
                ($accion) ? $this->opciones_model->formatoMonedaMostrar($value->total) : $value->total,
                $value->nombre_almacen
                //$value->factura
            );
        }

        return array(
            'total_ventas' => $data,
        );
    }

    public function ventas_domicilios($fecha_inicio, $fecha_fin, $almacen, $domiciliario)
    {

        $data = array();

        $where = "v.fecha between '" . $fecha_inicio . " 00:00:00' and '" . $fecha_fin . " 23:59:59' ";

        if (!empty($almacen)) {
            $where .= " AND v.almacen_id = $almacen";
        }

        if (!empty($domiciliario != "")) {
            $where .= " AND domi.id = $domiciliario";
        }

        $this->connection->select('d.*,v.`almacen_id`, a.`nombre` AS nombre_almacen, v.`factura` AS numero_factura,v.`total_venta`,domi.`descripcion`');
        $this->connection->from("domicilio d");
        $this->connection->join("venta v", "d.factura = v.id", "inner");
        $this->connection->join("domiciliarios domi", "d.domiciliario = domi.id", "inner");
        $this->connection->join("almacen a", "v.almacen_id = a.id", "inner");
        $this->connection->where($where);

        $result = $this->connection->get()->result_array();

        if (!empty($result)) {
            foreach ($result as $key => $venta) {
                $data[] = array(
                    $venta['id'],
                    $venta['hora_inicio'],
                    $venta['nombre_almacen'],
                    $venta['descripcion'],
                    $venta['nombre'],
                    $venta['telefono'],
                    $venta['direccion'],
                    $venta['numero_factura'],
                    $this->opciones->formatoMonedaMostrar($venta['total_venta']),
                );
            }
        }

        return array(
            'aaData' => $data,
        );
    }

    public function detalle_ventas_por_vendedor($fecha_inicio, $fecha_fin, $almacen, $vendedor)
    {
        ini_set('memory_limit', '-1');

        //buscamos si tengo o no decimales
        $decimales_moneda = get_option('decimales_moneda');

        $data = array();
        $upt = 0;
        $vpt = 0;
        $vpu = 0;
        $unidades_totales = 0;
        $unidades_totales_devueltas = 0;
        $total_venta_neta = 0;
        $total_impuestos = 0;
        $total_descuentos = 0;
        $total_transacciones = 0;
        $total_devolucion = 0;
        $total_ventas_todo = 0;

        //$where = "v.fecha between '" . $fecha_inicio . " 00:00:00' and '" . $fecha_fin . " 23:59:59' ";
        $where = "v.fecha between '" . $fecha_inicio . " 00:00:00' and '" . $fecha_fin . " 23:59:59' AND v.estado != -1 ";

        if (!empty($almacen)) {
            $where .= " AND v.almacen_id = $almacen";
        }
        if (!empty($vendedor != "")) {
            $where .= " AND v.vendedor = $vendedor";
        }

        $select = "v.id,v.fecha AS fecha_venta,a.nombre as almacen, v.factura, ve.nombre as vendedor, ve.cedula as cedula, v.total_venta";
        $this->connection->select($select);
        $this->connection->from("venta v");
        $this->connection->join("vendedor ve", "ve.id = v.vendedor", "left");
        $this->connection->join("almacen a", "a.id = v.almacen_id");
        if ($where != "") {
            $this->connection->where($where);
        }

        $result = $this->connection->get();

        if ($result->num_rows() > 0) {

            $total_unidades = 0;
            $total_venta_sin_iva = 0;
            $total_descuentos = 0;
            $total_devolucion = 0;
            $cantidad_devueltas = 0;

            $ventas = $result->result();
            foreach ($ventas as $venta):
                $unidades = 0;
                $descuento = 0;
                $impuesto = 0;
                $neto = 0;
                $cantidad_devueltas = 0;

                $this->connection->select('SUM(d.valor) AS total_devolucion');
                $this->connection->from("devoluciones d");
                $this->connection->join("venta v", "d.factura = v.factura", "inner");
                if ($where != "") {
                    $this->connection->where($where);
                }

                $this->connection->where('v.id', $venta->id);
                $this->connection->group_by('v.factura');
                $total_devoluciones = $this->connection->get()->result();

                foreach ($total_devoluciones as $key1 => $value1) {
                    $total_devolucion += $value1->total_devolucion;
                }

                $this->connection->select('*');
                $this->connection->from('detalle_venta dv');
                $this->connection->where("dv.venta_id", $venta->id);
                $result = $this->connection->get();
                $productos = $result->result();
                foreach ($productos as $producto):
                    $unidades += $producto->unidades;
                    //cantidades devueltas
                    if (($producto->descripcion_producto != '0') && (!empty($producto->descripcion_producto))) {

                        $descripcion = explode('cantidadSindevolver":', $producto->descripcion_producto);
                        $cantidad_devueltas = 0;
                        if(isset($descripcion[1])) {
                            $descripcion2 = explode(',', $descripcion[1]);
                            if(isset($descripcion2[0])){
                                if ($descripcion2[0] == 0) { //se devolvieron todas
                                    $cantidad_devueltas += floatval($producto->unidades);
                                    //$subtotal+=0;
                                } else { //se devolvieron algunas
                                    $cantidad_devueltas += floatval($producto->unidades) - floatval($descripcion2[0]);
                                }
                            }
                        }
                    }

                    if ($decimales_moneda == 0) {
                        $impuesto += round(($producto->precio_venta - $producto->descuento) * $producto->impuesto / 100 * $producto->unidades);
                        $descuento += round($producto->unidades * $producto->descuento);
                        $neto += ((round($producto->precio_venta * $producto->unidades) - round($producto->unidades * $producto->descuento)));
                    } else {
                        $impuesto += (($producto->precio_venta - $producto->descuento) * $producto->impuesto / 100 * $producto->unidades);
                        $descuento += ($producto->unidades * $producto->descuento);
                        $neto += ((($producto->precio_venta * $producto->unidades) - ($producto->unidades * $producto->descuento)) + (($producto->precio_venta - $producto->descuento) * $producto->impuesto / 100 * $producto->unidades));
                    }

                endforeach;

                $total_unidades += $unidades;
                $total_unidades += $cantidad_devueltas;
                $total_venta_sin_iva += $neto;

                //Estadisticas generales
                $unidades_totales += $unidades;
                $unidades_totales_devueltas += $cantidad_devueltas;
                $total_venta_neta += $neto;
                $total_impuestos += $impuesto;
                $total_descuentos += $descuento;
                $total_transacciones++;

                $data[] = array(
                    $venta->fecha_venta,
                    $venta->almacen,
                    $venta->factura,
                    $venta->vendedor,
                    $venta->cedula,
                    $unidades,
                    $this->opciones->formatoMonedaMostrar($descuento),
                    $this->opciones->formatoMonedaMostrar($impuesto),
                    $this->opciones->formatoMonedaMostrar($neto),
                    $this->opciones->formatoMonedaMostrar($venta->total_venta),
                );
            endforeach;

            $upt = $this->opciones->formatoMonedaMostrar($total_unidades / $total_transacciones);
            $vpt = $this->opciones->formatoMonedaMostrar($total_venta_sin_iva / $total_transacciones);
            $vpu = $this->opciones->formatoMonedaMostrar($total_venta_sin_iva / $total_unidades);
        }

        return array(
            'aaData' => $data,
            'total_venta_neta' => $this->opciones->formatoMonedaMostrar($total_venta_neta),
            'total_impuestos' => $this->opciones->formatoMonedaMostrar($total_impuestos),
            'total_descuentos' => $this->opciones->formatoMonedaMostrar($total_descuentos),
            'total_devoluciones' => $this->opciones->formatoMonedaMostrar($total_devolucion),
            'total_unidades' => $unidades_totales,
            'total_unidades_devueltas' => $unidades_totales_devueltas,
            'total_transacciones' => $total_transacciones,
            'UPT' => $upt,
            'VPT' => $vpt,
            'VPU' => $vpu,

        );
    }

    public function ventas_por_tomapedido($fecha_inicio, $fecha_fin, $almacen, $zona, $mesa)
    {

        $data = array();

        $where = "v.fecha between '" . $fecha_inicio . " 00:00:00' and '" . $fecha_fin . " 23:59:59' AND v.estado != -1";

        if (!empty($almacen)) {
            $where .= " AND v.almacen_id = $almacen";
        }

        if (!empty($zona)) {
            $where .= " AND h.zona = $zona";
        }

        if (!empty($mesa)) {
            $where .= " AND h.mesa_id = $mesa";
        }

        $select = "v.fecha,a.nombre AS almacen,v.factura,s.nombre_seccion AS zona,m.nombre_mesa AS mesa,p.nombre AS nombre_producto,h.cantidad,v.total_venta,h.order_adiciones AS adiciones, h.order_modificacion AS modificaciones";
        $this->connection->select($select);
        $this->connection->from("historico_orden_producto_restaurant h");
        $this->connection->join("venta v", "v.id = h.id_venta", "left");
        $this->connection->join("almacen a", "a.id = h.almacen", "left");
        $this->connection->join("secciones_almacen s", "s.id = h.zona", "left");
        $this->connection->join("mesas_secciones m", "m.id = h.mesa_id", "left");
        $this->connection->join("producto p", "p.id = h.order_producto", "left");
        if ($where != "") {
            $this->connection->where($where);
        }

        $result = $this->connection->get();
        if ($result->num_rows() > 0) {

            $ventas = $result->result();
            foreach ($ventas as $venta):
                /* Adiciones */
                $data_adiciones = array();
                $adiciones = json_decode($venta->adiciones);
                if (count($adiciones) > 0) {
                    foreach ($adiciones as $adicion):
                        $this->connection->select("nombre");
                        $this->connection->from("producto");
                        $this->connection->where("id", $adicion);
                        $this->connection->limit(1);
                        $result = $this->connection->get();

                        $data_adiciones[] = $result->result()[0]->nombre;
                    endforeach;
                } else {
                    $data_adiciones[] = "Sin adiciones";
                }
                /** End adiciones */

                $data[] = array(
                    $venta->fecha,
                    $venta->almacen,
                    $venta->factura,
                    $venta->zona,
                    $venta->mesa,
                    $venta->nombre_producto,
                    str_replace(array('[', ']'), array('(', ')'), json_encode($data_adiciones)),
                    ($venta->modificaciones == '') ? '("Sin modificaciones")' : str_replace(array('[', ']'), array('(', ')'), $venta->modificaciones),
                    $venta->cantidad,
                    $this->opciones->formatoMonedaMostrar($venta->total_venta),
                );
            endforeach;
        }

        //print_r($data);
        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_movimientos_bancarios($fecha_inicio, $fecha_fin, $banco, $tipo_movimiento)
    {
        $data = array();

        $where = "mb.fecha_creacion between '" . $fecha_inicio . "' and '" . $fecha_fin . "' ";

        if (!empty($banco)) {
            $where .= " AND mb.id_banco = $banco";
        }
        if (!empty($tipo_movimiento != "")) {
            $where .= " AND tmb.tipo = $tipo_movimiento";
        }

        $sql = "select mb.fecha_creacion,mb.referencia, tmb.nombre as nombre_movimiento,tmb.tipo as tipo_movimiento,  mb.valor, b.nombre_cuenta as nombre_banco, mb.estado, mb.id_usuario_creacion from movimientos_bancos AS mb inner join bancos AS b on mb.id_banco = b.id left join tipo_movimiento_banco tmb on tmb.id = mb.id_tipo WHERE $where";
        $result = $this->connection->query($sql);

        if ($result->num_rows() > 0) {
            $movimientos = $result->result();
            foreach ($movimientos as $movimiento) {
                $user = "";
                $sql_user = "select username from users as u where u.id = '" . $movimiento->id_usuario_creacion . "' LIMIT 1";
                $result_user = $this->db->query($sql_user);
                if ($result_user->num_rows() > 0) {
                    $user = $result_user->result()[0]->username;
                }

                $data[] = array(
                    $movimiento->fecha_creacion,
                    $movimiento->referencia,
                    $movimiento->nombre_movimiento,
                    ($movimiento->tipo_movimiento == 1) ? 'Entrada' : 'Salida',
                    $movimiento->valor,
                    $movimiento->nombre_banco,
                    ($movimiento->estado == 'conciliado') ? 'Conciliado' : 'Sin conciliar',
                    $user,
                );
            }
        }

        return array('aaData' => $data);
    }

    public function get_ajax_conciliaciones($fecha_inicio, $fecha_fin, $banco)
    {
        $data = array();

        $where = "c.fecha_creacion between '" . $fecha_inicio . "' and '" . $fecha_fin . "' ";

        if (!empty($banco)) {
            $where .= " AND c.id_banco = $banco";
        }

        $sql = "select c.*,b.nombre_cuenta from conciliaciones AS c inner join bancos AS b on c.id_banco = b.id WHERE $where";
        $result = $this->connection->query($sql);

        if ($result->num_rows() > 0) {
            $conciliaciones = $result->result();
            foreach ($conciliaciones as $conciliacion) {

                $data[] = array(
                    $conciliacion->fecha_creacion,
                    $conciliacion->transaccion,
                    $conciliacion->gastos_bancarios,
                    $conciliacion->impuestos_bancarios,
                    $conciliacion->entradas_bancarias,
                    $conciliacion->saldo_final,
                    $conciliacion->fecha_corte,
                    $conciliacion->nombre_cuenta,
                );
            }
        }

        return array('aaData' => $data);
    }

    public function habitos_consumo_dia_ajax($fechainicial, $fechafinal, $almacen, $page = false, $pageLength = false)
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return data, with data of sales by day
        Require date initial, date end, warehouse, page of search, and page length of search
         */

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        //Validation of page and length page
        $limit = "";
        if ($page && $pageLength) {
            $ini = ((int) $page * (int) $pageLength) - (int) $pageLength;
            $end = (int) $page * (int) $pageLength;
            $limit = "LIMIT " . $ini . ", " . $end;
        }

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " and  almacen_id = '$almacen' ";
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE(`fecha`) AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' $condition and estado = 0
              GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %d') $limit";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            //Query of sales,  needs to dates for range and possible limit
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha_dia, DATE(`fecha`) AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal'  and  almacen_id = '$almacen'  and estado = 0
                GROUP BY DATE_FORMAT(`fecha`,'%Y-%m-%d %d') $limit";
        }

        $sql = $total_ventas;

        $detalle_ventas_result = array();
        $consumo_productos = array();
        $ven_cod = '';
        $rest = '';
        $total_ventas = $this->connection->query($total_ventas)->result();

        //init devolution in zero for each sale
        $total_devolucion = 0;
        foreach ($total_ventas as $key => $value) {
            //Query for get id of sales by day
            $detalle_ventas = "SELECT id FROM `venta`  where DATE(`fecha`) = '$value->fecha' $condition   and estado = 0 ";
            $detalle_ventas_result = $this->connection->query($detalle_ventas)->result();
            foreach ($detalle_ventas_result as $det) {
                //Group sales id in string
                if ($det->id > 0) {
                    $ven_cod = $ven_cod . "," . $det->id;
                }
            }

            if ($ven_cod) {
                $rest = substr($ven_cod, 1);
            } else {
                $rest = 0;
            }

            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_pdv2 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            $vr_unidades = 0;

            //start devolution in zero, get all devolution in day
            $subtotal_devoluciones = 0;
            //Devoluciones
            $total_devoluciones_2 = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
                SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
                FROM venta v
                INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                LEFT JOIN devoluciones d ON v.factura=d.factura
                WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                AND DATE(v.fecha) BETWEEN '$value->fecha_dia' AND '$value->fecha_dia' AND v.estado = '0'
                GROUP BY DATE_FORMAT(v.fecha ,'%d') ORDER BY v.fecha";

            $total_devoluciones_result_ = $this->connection->query($total_devoluciones_2)->result();
            if (count($total_devoluciones_result_) > 0) {
                foreach ($total_devoluciones_result_ as $devolucion) {
                    $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                }
            }
            //Query of product sales on day, first 10 results
            $detalle_ventas = "SELECT DATE(`fecha`) AS fecha_dia, venta_id ,nombre_producto, sum(unidades) as unidades, precio_venta as total_detalleventa,
			    sum(descuento) as descuento, impuesto
			    ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	             ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	            ,SUM( `unidades` * `descuento` ) AS total_descuento,
                codigo_producto,
                producto_id
                FROM detalle_venta  inner join venta on venta.id = detalle_venta.venta_id
                where venta_id  IN (" . $rest . ") group by nombre_producto order by DATE(`fecha`), unidades desc limit 0, 10";

            $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();

            foreach ($detalle_ventas_result_1 as $prod) {
                $sale = array(
                    'fecha_dia' => $prod->fecha_dia,
                    'unidades' => $prod->unidades,
                    'fecha' => $value->fecha,
                    'nombre' => $prod->nombre_producto,
                    'total_detalleventa' => ($prod->total_precio_venta - $prod->total_descuento) + $prod->impuesto,
                    'codigo_producto' => $prod->codigo_producto,
                );

                $consumo_productos[] = $sale;
                $total_ventas[$key]->sales[] = $sale;
                $total_ventas[$key]->rest = $rest;
                $total_ventas[$key]->devolucion = $subtotal_devoluciones;
            }

            $ven_cod = '';

        }

        return array(
            'total_ventas_1' => $total_ventas,
            'total_ventas_2' => $detalle_ventas_result,
            'total_ventas_3' => $consumo_productos,
            'devoluciones' => $total_devolucion,
        );
    }

    public function habitos_consumo_mes_ajax($fechainicial, $fechafinal, $almacen, $page = false, $pageLength = false)
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return data, with data of sales by day
        Require date initial, date end, warehouse, page of search, and page length of search
         */

        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        //Validation of page and length page
        $limit = "";
        if ($page && $pageLength) {
            $ini = ((int) $page * (int) $pageLength) - (int) $pageLength;
            $end = (int) $page * (int) $pageLength;
            $limit = "LIMIT " . $ini . ", " . $end;
        }

        if ($almacen == '0') {
            $condition = '';
        } else {
            $condition = " and  almacen_id = '$almacen' ";
        }

        if ($is_admin == 't' || $is_admin == 'a') { //administrador
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' $condition and estado = 0 $limit";
        }

        if ($is_admin != 't' && $is_admin != 'a') { //usuario
            //------------------------------------------------ almacen usuario
            $is_admin = $this->session->userdata('is_admin');
            $username = $this->session->userdata('username');
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

            if ($almacen == '0') {
                $condition = '';
            } else {
                $condition = " and  almacen_id = '$almacen' ";
            }

            //Query of sales,  needs to dates for range and possible limit
            $total_ventas = "SELECT  DATE(`fecha`) AS fecha,  sum(total_venta) as total_venta
                FROM `venta`
				WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal'  $condition  and estado = 0 $limit";
        }

        $sql = $total_ventas;

        $detalle_ventas_result = array();
        $consumo_productos = array();
        $ven_cod = '';
        $rest = '';

        $total_ventas = $this->connection->query($total_ventas)->result();

        //init devolution in zero for each sale
        $total_devolucion = 0;
        foreach ($total_ventas as $key => $value) {
            //Query for get id of sales by day
            $detalle_ventas = "SELECT id FROM `venta`  WHERE DATE(fecha) >= '$fechainicial'  AND  DATE(fecha) <= '$fechafinal' $condition and estado = 0 ";
            $detalle_ventas_result = $this->connection->query($detalle_ventas)->result();

            foreach ($detalle_ventas_result as $det) {
                //Group sales id in string
                if ($det->id > 0) {
                    $ven_cod = $ven_cod . "," . $det->id;
                }
            }

            if ($ven_cod) {
                $rest = substr($ven_cod, 1);
            } else {
                $rest = 0;
            }

            $vr_impuesto = 0;
            $vr_valor = 0;
            $vr_pdv = 0;
            $vr_pdv1 = 0;
            $vr_pdv2 = 0;
            $vr_column = 0;
            $vr_bruto = 0;
            $vr_unidades = 0;

            //start devolution in zero, get all devolution in day
            /*$subtotal_devoluciones = 0;
            //Devoluciones
            $total_devoluciones_2 = "SELECT SUM(d.valor) AS valor_devolucion, DATE(v.fecha) AS fecha_dia, DATE_FORMAT(v.fecha ,'%d') AS fecha ,
                SUM(ROUND(IF((dv.descripcion_producto = '0' OR dv.descripcion_producto = ''), dv.unidades, SUBSTRING_INDEX(SUBSTRING_INDEX(dv.descripcion_producto,'\"cantidadSindevolver\":',-1),',',1) ) * dv.descuento)) AS total_descuento ,
                SUM(ROUND((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto ,SUM(ROUND(dv.precio_venta * dv.unidades)) AS total_precio_venta
                FROM venta v
                INNER JOIN detalle_venta dv ON v.id=dv.venta_id
                LEFT JOIN devoluciones d ON v.factura=d.factura
                WHERE (dv.descripcion_producto != '0' AND dv.descripcion_producto != '')
                AND DATE(v.fecha) BETWEEN '$fechainicial' AND '$fechafinal' AND v.estado = '0' ORDER BY v.fecha";

            $total_devoluciones_result_ = $this->connection->query($total_devoluciones_2)->result();
            if (count($total_devoluciones_result_) > 0) {
                foreach ($total_devoluciones_result_ as $devolucion) {
                    $subtotal_devoluciones += ($devolucion->total_precio_venta - $devolucion->total_descuento) + $devolucion->impuesto;
                }
            }*/

            //Query of product sales on day, first 10 results
            $detalle_ventas = "SELECT venta_id ,nombre_producto, sum(unidades) as unidades, precio_venta as total_detalleventa,
			    sum(descuento) as descuento, impuesto
			    ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
	             ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
	            ,SUM( `unidades` * `descuento` ) AS total_descuento,
                codigo_producto,
                producto_id
                FROM detalle_venta  inner join venta on venta.id = detalle_venta.venta_id
                where venta_id  IN (" . $rest . ") group by nombre_producto order by unidades desc, DATE(`fecha`) desc limit 0, 40";

            $detalle_ventas_result_1 = $this->connection->query($detalle_ventas)->result();

            foreach ($detalle_ventas_result_1 as $prod) {

                $get_producto_devolucion = "SELECT detalle_venta.* FROM detalle_venta inner join venta on venta_id = venta.id where venta.fecha BETWEEN '{$fechainicial}' AND '$fechafinal' and venta.estado = 0 AND producto_id = {$prod->producto_id} and descripcion_producto <> '0'";
                $get_producto_devolucion_result = $this->connection->query($get_producto_devolucion)->result();                
                $subtotal_devoluciones = 0;
                foreach($get_producto_devolucion_result as $producto_devolucion) {
                    if (($producto_devolucion->descripcion_producto != '0') && (!empty($producto_devolucion->descripcion_producto))) {
                        $descripcion = explode('cantidadSindevolver":', $producto_devolucion->descripcion_producto);
                        $cantidad_devueltas = 0;
                        if (!empty($descripcion[1])) {
                            $descripcion2 = explode(',', $descripcion[1]);
    
                            if ($descripcion2[0] == 0) { //se devolvieron todas
                                $subtotal_devoluciones += $producto_devolucion->precio_venta;
                            } else { //se devolvieron algunas
                                $cantidad_devueltas = floatval($producto_devolucion->unidades) - floatval($descripcion2[0]);
                                $subtotal_devoluciones +=  $producto_devolucion->precio_venta * $cantidad_devueltas;
                            }
                        }
                    }
                }

                $sale = array(
                    //'fecha_dia' => $prod->fecha_dia,
                    'unidades' => $prod->unidades,
                    'fecha' => $value->fecha,
                    'nombre' => $prod->nombre_producto,
                    'total_detalleventa' => ($prod->total_precio_venta - $prod->total_descuento) + $prod->impuesto - $subtotal_devoluciones,
                    'codigo_producto' => $prod->codigo_producto,
                    'total_devoluciones' => $subtotal_devoluciones
                );

                $consumo_productos[] = $sale;
                $total_ventas[$key]->sales[] = $sale;
                $total_ventas[$key]->rest = $rest;
                $total_ventas[$key]->devolucion = $subtotal_devoluciones;
            }

            $ven_cod = '';

        }

        return array(
            'total_ventas_1' => $total_ventas,
            'total_ventas_2' => $detalle_ventas_result,
            'total_ventas_3' => $consumo_productos,
            'devoluciones' => $total_devolucion,
        );
    }

    public function getSalesByDay($sales = 0, $start = 0, $end = 10)
    {

        /*
        Jeisson Rodriguez Dev
        03-09-2019

        This function return data, data of products salesed by day
        Require id sales, page of search and page length of search
         */

        //Query of products by day, in range of dates
        $query = "SELECT DATE(`fecha`) AS fecha_dia, venta_id ,nombre_producto, sum(unidades) as unidades,
        sum(descuento) as descuento, impuesto
        ,SUM( (`precio_venta` - `descuento`) * impuesto / 100 *  `unidades` ) AS impuesto
        ,SUM( (`precio_venta` - `descuento`) * ((impuesto / 100) + 1) *  `unidades` ) AS total_detalleventa
         ,SUM( `precio_venta` * `unidades` ) AS total_precio_venta
        ,SUM( `unidades` * `descuento` ) AS total_descuento,
        codigo_producto,
        producto_id
        FROM detalle_venta  inner join venta on venta.id = detalle_venta.venta_id
        where venta_id  IN (" . $sales . ") group by nombre_producto order by DATE(`fecha`), unidades desc limit $start, $end";
        return $this->connection->query($query)->result();
    }

}
