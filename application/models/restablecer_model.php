<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Edwin P�rez
// Framework: Codeigniter
// Clase: restablecer_model.php

class Restablecer_model extends CI_Model
{

    public $connection;

    public function __construct()
    {

        parent::__construct();
    }

    //Initialize provee
    public function initialize($connection)
    {

        $this->connection = $connection;
    }

    public function existeTabla($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'restablecer'";
        $existe = $this->connection->query($sql)->result();
        if (empty($existe)) {
            $sql = "CREATE TABLE IF NOT EXISTS `restablecer`(
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `id_usuario` INT(11) NOT NULL,
                `json` TEXT(200) NOT NULL,
                `fecha` DATETIME,
                `activo` TINYINT(1) DEFAULT 1,
                 KEY(`id`)
                );";
            $this->connection->query($sql);
        }
    }

    //Backup tables
    private function backupTable($table)
    {

        // Select first row
        $queryResult = $this->connection->get($table, 1);

        // If not data in table, dont backup.
        if ($queryResult->num_rows() > 0) {

            // nameBackup = oldTable_yearMonthDay_hourMinuteSecond
            $name = $table . "_" . date("omd_His");
            $this->connection->query("CREATE TABLE $name LIKE $table;");
            $this->connection->query("INSERT $name SELECT * FROM $table;");

        }

    }

    public function deleteClientes()
    {

        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

        //$this->backupTable("venta");
        //$this->connection->query("TRUNCATE TABLE venta;");

        $sql = "SHOW TABLES LIKE 'clientes'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("clientes");
            $this->connection->query("TRUNCATE TABLE clientes;");
            // Re create default general client
            $query = "
            INSERT
            INTO clientes (id_cliente,pais,nombre_comercial,nif_cif,grupo_clientes_id)
            VALUES (-1,'Colombia','general','0',1)";

            $this->connection->query($query);
        }
        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
    }

    public function deletevendedores($almacenes = false)
    {

        if ($almacenes != false) {
            $this->backupTable("vendedor");
            $existeVendedor = "";
            foreach ($almacenes as $a) {
                //verifico cuales son los vendedores de ese almacen
                $sqlv1 = "SELECT * FROM vendedor WHERE almacen=$a";
                $sqlrv1 = $this->connection->query($sqlv1)->result();
                //verifico cuales tienen ventas asociadas
                foreach ($sqlrv1 as $value) {
                    $sql2 = "SELECT * FROM venta WHERE vendedor=$value->id LIMIT 1";
                    $existe = $this->connection->query($sql2)->result();
                    if (count($existe) == 0) {
                        //verifico vendedor_2
                        $sql3 = "SELECT * FROM venta WHERE vendedor_2=$value->id LIMIT 1";
                        $existe3 = $this->connection->query($sql3)->result();
                        if (count($existe3) == 0) {
                            //Se Elimina el registro
                            $sql4 = "DELETE FROM vendedor WHERE id=$value->id";
                            $this->connection->query($sql4);
                        } else {
                            $existeVendedor .= "," . $value->nombre;
                        }
                    } else {
                        $existeVendedor .= "," . $value->nombre;
                    }
                }
            }
        }
        $vendedores = trim($existeVendedor, ",");
        return $vendedores;
    }

    public function deletecategorias($almacenes = false)
    {

        if ($almacenes != false) {
            $this->backupTable("categoria");
            $sql = "SHOW TABLES LIKE 'categoria'";
            $existe = $this->connection->query($sql)->result();
            if (count($existe) != 0) {
                foreach ($almacenes as $a) {
                    $this->connection->query("delete from categoria where id= $a;");
                }
            }
        }

    }

    public function deleteProveedores()
    {

        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

        $sql = "SHOW TABLES LIKE 'proveedores'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("proveedores");
            $this->connection->query("TRUNCATE TABLE proveedores;");
        }

        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");

    }

    public function deleteProductos()
    {

        $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
        $sql = "SHOW TABLES LIKE 'stock_diario'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("stock_diario");
            $this->connection->query("TRUNCATE TABLE stock_diario;");
        }

        $sql = "SHOW TABLES LIKE 'stock_actual'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("stock_actual");
            $this->connection->query("TRUNCATE TABLE stock_actual;");
        }

        $sql = "SHOW TABLES LIKE 'stock_historial'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("stock_historial");
            $this->connection->query("TRUNCATE TABLE stock_historial;");
        }

        $sql = "SHOW TABLES LIKE 'producto_combos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto_combos");
            $this->connection->query("TRUNCATE TABLE producto_combos;");
        }

        $sql = "SHOW TABLES LIKE 'producto_ingredientes'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto_ingredientes");
            $this->connection->query("TRUNCATE TABLE producto_ingredientes;");
        }

        $sql = "SHOW TABLES LIKE 'producto_seriales'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto_seriales");
            $this->connection->query("TRUNCATE TABLE producto_seriales;");
        }

        $sql = "SHOW TABLES LIKE 'produccion_detalle'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("produccion_detalle");
            $this->connection->query("TRUNCATE TABLE produccion_detalle;");
        }

        $sql = "SHOW TABLES LIKE 'produccion'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("produccion");
            $this->connection->query("TRUNCATE TABLE produccion;");
        }

        $sql = "SHOW TABLES LIKE 'presupuestos_detalles'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("presupuestos_detalles");
            $this->connection->query("TRUNCATE TABLE presupuestos_detalles;");
        }

        $sql = "SHOW TABLES LIKE 'presupuestos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("presupuestos");
            $this->connection->query("TRUNCATE TABLE presupuestos;");
        }

        $sql = "SHOW TABLES LIKE 'pago'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("pago");
            $this->connection->query("TRUNCATE TABLE pago;");
        }

        $sql = "SHOW TABLES LIKE 'pagos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("pagos");
            $this->connection->query("TRUNCATE TABLE pagos;");
        }

        $sql = "SHOW TABLES LIKE 'detalle_orden_compra'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("detalle_orden_compra");
            $this->connection->query("TRUNCATE TABLE detalle_orden_compra;");
        }

        $sql = "SHOW TABLES LIKE 'pago_orden_compra'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("pago_orden_compra");
            $this->connection->query("TRUNCATE TABLE pago_orden_compra;");
        }

        $sql = "SHOW TABLES LIKE 'orden_compra'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("orden_compra");
            $this->connection->query("TRUNCATE TABLE orden_compra;");
        }

        $sql = "SHOW TABLES LIKE 'promocionesproductosdescuento'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("promocionesproductosdescuento");
            $this->connection->query("TRUNCATE TABLE promocionesproductosdescuento;");
        }

        $sql = "SHOW TABLES LIKE 'promociones_productos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("promociones_productos");
            $this->connection->query("TRUNCATE TABLE promociones_productos;");
        }

        $sql = "SHOW TABLES LIKE 'promociones_descripcion'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("promociones_descripcion");
            $this->connection->query("TRUNCATE TABLE promociones_descripcion;");
        }

        $sql = "SHOW TABLES LIKE 'promociones_almacenes'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("promociones_almacenes");
            $this->connection->query("TRUNCATE TABLE promociones_almacenes;");
        }

        $sql = "SHOW TABLES LIKE 'promociones'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("promociones");
            $this->connection->query("TRUNCATE TABLE promociones;");
        }

        $sql = "SHOW TABLES LIKE 'proformas'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("proformas");
            $this->connection->query("TRUNCATE TABLE proformas;");
        }

        $sql = "SHOW TABLES LIKE 'lista_detalle_precios'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("lista_detalle_precios");
            $this->connection->query("TRUNCATE TABLE lista_detalle_precios;");
        }

        $sql = "SHOW TABLES LIKE 'lista_precios'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("lista_precios");
            $this->connection->query("TRUNCATE TABLE lista_precios;");
        }

        $sql = "SHOW TABLES LIKE 'detalle_auditoria'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("detalle_auditoria");
            $this->connection->query("TRUNCATE TABLE detalle_auditoria;");
        }

        $sql = "SHOW TABLES LIKE 'auditoria_inventario'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("auditoria_inventario");
            $this->connection->query("TRUNCATE TABLE auditoria_inventario;");
        }

        $sql = "SHOW TABLES LIKE 'notacredito'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("notacredito");
            $this->connection->query("TRUNCATE TABLE notacredito;");
        }

        $sql = "SHOW TABLES LIKE 'devoluciones'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("devoluciones");
            $this->connection->query("TRUNCATE TABLE devoluciones;");
        }

        $sql = "SHOW TABLES LIKE 'cierres_caja'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("cierres_caja");
            $this->connection->query("TRUNCATE TABLE cierres_caja;");
        }

        $sql = "SHOW TABLES LIKE 'movimientos_cierre_caja'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("movimientos_cierre_caja");
            $this->connection->query("TRUNCATE TABLE movimientos_cierre_caja;");
        }

        $sql = "SHOW TABLES LIKE 'movimiento_detalle'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("movimiento_detalle");
            $this->connection->query("TRUNCATE TABLE movimiento_detalle;");
        }

        $sql = "SHOW TABLES LIKE 'movimiento_inventario'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("movimiento_inventario");
            $this->connection->query("TRUNCATE TABLE movimiento_inventario;");
        }

        $sql = "SHOW TABLES LIKE 'plan_separe_pagos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("plan_separe_pagos");
            $this->connection->query("TRUNCATE TABLE plan_separe_pagos;");
        }

        $sql = "SHOW TABLES LIKE 'plan_separe_detalle'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("plan_separe_detalle");
            $this->connection->query("TRUNCATE TABLE plan_separe_detalle;");
        }

        $sql = "SHOW TABLES LIKE 'plan_separe_factura'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("plan_separe_factura");
            $this->connection->query("TRUNCATE TABLE plan_separe_factura;");
        }

        $sql = "SHOW TABLES LIKE 'online_venta'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("online_venta");
            $this->connection->query("TRUNCATE TABLE online_venta;");
        }

        $sql = "SHOW TABLES LIKE 'factura_espera'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("factura_espera");
            $this->connection->query("TRUNCATE TABLE factura_espera;");
        }

        $sql = "SHOW TABLES LIKE 'facturas_detalles'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("facturas_detalles");
            $this->connection->query("TRUNCATE TABLE facturas_detalles;");
        }

        $sql = "SHOW TABLES LIKE 'puntos_acumulados'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("puntos_acumulados");
            $this->connection->query("TRUNCATE TABLE puntos_acumulados;");
        }

        $sql = "SHOW TABLES LIKE 'detalle_venta'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("detalle_venta");
            $this->connection->query("TRUNCATE TABLE detalle_venta;");
        }

        $sql = "SHOW TABLES LIKE 'ventas_pago'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("ventas_pago");
            $this->connection->query("TRUNCATE TABLE ventas_pago;");
        }

        $sql = "SHOW TABLES LIKE 'ventas_anuladas'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("ventas_anuladas");
            $this->connection->query("TRUNCATE TABLE ventas_anuladas;");
        }

        $sql = "SHOW TABLES LIKE 'ventas_pago_giftcard'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("ventas_pago_giftcard");
            $this->connection->query("TRUNCATE TABLE ventas_pago_giftcard;");
        }

        $sql = "SHOW TABLES LIKE 'venta'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("venta");
            $this->connection->query("TRUNCATE TABLE venta;");
        }

        $sql = "SHOW TABLES LIKE 'atributos_productos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("atributos_productos");
            $this->connection->query("TRUNCATE TABLE atributos_productos;");
        }

        $sql = "SHOW TABLES LIKE 'atributos_productos_almacenes'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("atributos_productos_almacenes");
            $this->connection->query("TRUNCATE TABLE atributos_productos_almacenes;");
        }

        $sql = "SHOW TABLES LIKE 'atributos_categorias'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("atributos_categorias");
            $this->connection->query("TRUNCATE TABLE atributos_categorias;");
        }

        $sql = "SHOW TABLES LIKE 'producto_adicional'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto_adicional");
            $this->connection->query("TRUNCATE TABLE producto_adicional;");
        }

        $sql = "SHOW TABLES LIKE 'producto_ingredientes'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto_ingredientes");
            $this->connection->query("TRUNCATE TABLE producto_ingredientes;");
        }

        $sql = "SHOW TABLES LIKE 'producto_modificacion'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto_modificacion");
            $this->connection->query("TRUNCATE TABLE producto_modificacion;");
        }

        $sql = "SHOW TABLES LIKE 'historico_orden_producto_restaurant'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("historico_orden_producto_restaurant");
            $this->connection->query("TRUNCATE TABLE historico_orden_producto_restaurant;");
        }

        $sql = "SHOW TABLES LIKE 'orden_producto_restaurant'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("orden_producto_restaurant");
            $this->connection->query("TRUNCATE TABLE orden_producto_restaurant;");
        }

        $sql = "SHOW TABLES LIKE 'impresion_rapida'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("impresion_rapida");
            $this->connection->query("TRUNCATE TABLE impresion_rapida;");
        }

        $sql = "SHOW TABLES LIKE 'producto'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("producto");
            $this->connection->query("TRUNCATE TABLE producto;");
        }

        $sql = "SHOW TABLES LIKE 'atributos_productos'";
        $existe = $this->connection->query($sql)->result();
        if (count($existe) != 0) {
            $this->backupTable("atributos_productos");
            $this->connection->query("TRUNCATE TABLE atributos_productos;");
        }

        $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");

    }

    public function deleteVentas($almacenes = false)
    {
        if ($almacenes != false) {
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

            $this->backupTable("venta");
            $this->backupTable("detalle_venta");
            $this->backupTable("ventas_pago");
            $this->backupTable("ventas_anuladas");
            $this->backupTable("stock_diario");
            $this->backupTable("movimientos_cierre_caja");
            $this->backupTable("pago");
            $this->backupTable("cierres_caja");
            $this->backupTable("plan_separe_factura");
            $this->backupTable("plan_separe_detalle");
            $this->backupTable("plan_separe_pagos");

            $this->backupTable("devoluciones");
            $this->backupTable("notacredito");
            $this->backupTable("factura_espera");
            $this->backupTable("detalle_factura_espera");

            foreach ($almacenes as $a) {
                $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");
                $sql = "SHOW TABLES LIKE 'online_venta'";
                $existe = $this->connection->query($sql)->result();

                if (count($existe) != 0) {
                    $query = "DELETE v,dv,vp,va,sd,mcc,p,psd, psf,psp,d,cre,fe,dfe, ov FROM venta AS v
                    LEFT JOIN detalle_venta AS dv ON dv.venta_id = v.id
                    LEFT JOIN ventas_pago AS vp ON vp.id_venta = v.id
                    LEFT JOIN ventas_anuladas AS va ON va.venta_id = v.id
                    LEFT JOIN stock_diario AS sd ON sd.cod_documento = v.factura
                    LEFT JOIN movimientos_cierre_caja AS mcc ON mcc.numero = v.factura
                    LEFT JOIN cierres_caja AS cc ON cc.id = mcc.Id_cierre
                    LEFT JOIN pago AS p ON p.id_factura = v.id
                    LEFT JOIN plan_separe_factura AS psf ON psf.factura =  v.factura
                    LEFT JOIN plan_separe_detalle AS psd ON psd.venta_id = psf.id
                    LEFT JOIN plan_separe_pagos AS psp ON psp.id_venta = psf.id
                    LEFT JOIN devoluciones AS d ON d.factura = v.factura
                    LEFT JOIN notacredito AS cre ON cre.devolucion_id = d.id
                    LEFT JOIN factura_espera AS fe ON fe.almacen_id = v.almacen_id
                    LEFT JOIN detalle_factura_espera AS dfe ON dfe.venta_id = fe.no_factura
                    LEFT JOIN online_venta AS ov ON v.id = ov.venta_id
                    WHERE v.almacen_id= $a";

                    $this->connection->query($query);
                } else {
                    $query = "DELETE v,dv,vp,va,sd,mcc,p,psd, psf,psp,d,cre,fe,dfe FROM venta AS v
                    LEFT JOIN detalle_venta AS dv ON dv.venta_id = v.id
                    LEFT JOIN ventas_pago AS vp ON vp.id_venta = v.id
                    LEFT JOIN ventas_anuladas AS va ON va.venta_id = v.id
                    LEFT JOIN stock_diario AS sd ON sd.cod_documento = v.factura
                    LEFT JOIN movimientos_cierre_caja AS mcc ON mcc.numero = v.factura
                    LEFT JOIN cierres_caja AS cc ON cc.id = mcc.Id_cierre
                    LEFT JOIN pago AS p ON p.id_factura = v.id
                    LEFT JOIN plan_separe_factura AS psf ON psf.factura =  v.factura
                    LEFT JOIN plan_separe_detalle AS psd ON psd.venta_id = psf.id
                    LEFT JOIN plan_separe_pagos AS psp ON psp.id_venta = psf.id
                    LEFT JOIN devoluciones AS d ON d.factura = v.factura
                    LEFT JOIN notacredito AS cre ON cre.devolucion_id = d.id
                    LEFT JOIN factura_espera AS fe ON fe.almacen_id = v.almacen_id
                    LEFT JOIN detalle_factura_espera AS dfe ON dfe.venta_id = fe.no_factura
                    WHERE v.almacen_id= $a";

                    $this->connection->query($query);
                }

                $query2 = "DELETE psf, psd,psp FROM plan_separe_factura AS psf
                        LEFT JOIN plan_separe_detalle AS psd ON psd.venta_id = psf.id
                        LEFT JOIN plan_separe_pagos AS psp ON psp.id_venta = psf.id
                        WHERE psf.almacen_id=$a";
                $this->connection->query($query2);

                $query3 = "DELETE cc, mcc FROM cierres_caja AS cc
                        LEFT JOIN movimientos_cierre_caja AS mcc ON cc.id = mcc.Id_cierre
                        WHERE cc.id_Almacen=$a";
                $this->connection->query($query3);
            }
            //Delete all from Online Ventas
            $this->deleteOnlineVentasTables();
            
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
        }

        return $query;
    }

    public function deleteOnlineVentasTables() {
        $delete_from_online_venta_schedule = "DELETE FROM online_venta_schedule";
        $this->connection->query($delete_from_online_venta_schedule);
        
        $delete_from_online_venta_prod_modification = "DELETE FROM online_venta_prod_modification";
        $this->connection->query($delete_from_online_venta_prod_modification);

        $delete_from_online_venta_prod_adition = "DELETE FROM online_venta_prod_adition";
        $this->connection->query($delete_from_online_venta_prod_adition);
        
        $delete_from_online_venta_prod = "DELETE FROM online_venta_prod";
        $this->connection->query($delete_from_online_venta_prod);
        
        $delete_from_online_venta = "DELETE FROM online_venta";
        $this->connection->query($delete_from_online_venta);
    }

    public function deleteInventarios($almacenes = false)
    {
        if ($almacenes != false) {
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

            $this->backupTable("stock_actual");
            $this->backupTable("stock_diario");
            $this->backupTable("movimiento_inventario");
            $this->backupTable("movimiento_detalle");
            $this->backupTable("auditoria_inventario");
            $this->backupTable("detalle_auditoria");

            $sql = "SHOW TABLES LIKE 'produccion'";
            $existeProduccion = $this->connection->query($sql)->result();
            if (count($existeProduccion) != 0) {
                $this->backupTable("produccion");
            }

            $sql = "SHOW TABLES LIKE 'produccion_detalle'";
            $existeProduccionDetalle = $this->connection->query($sql)->result();
            if (count($existeProduccionDetalle) != 0) {
                $this->backupTable("produccion_detalle");
            }

            
            

            foreach ($almacenes as $a) {
                $this->connection->query("UPDATE stock_actual SET stock_actual.unidades=0 where almacen_id = " . $a);
                $this->connection->query("DELETE FROM stock_diario where almacen_id = " . $a);
                $query = "DELETE i,d FROM movimiento_inventario AS i
                            LEFT JOIN movimiento_detalle AS d ON d.id_inventario = i.id
                            WHERE i.almacen_id = " . $a;
                $this->connection->query($query);
                //auditoria
                $query = "SELECT * FROM auditoria_inventario WHERE id_almacen = $a";
                $query = $this->connection->query($query)->result();
                foreach ($query as $value) {

                    $querydetalle = "DELETE FROM detalle_auditoria WHERE id_auditoria = $value->id";
                    $this->connection->query($querydetalle);
                }
                foreach ($query as $value) {
                    $queryau = "DELETE FROM auditoria_inventario WHERE id = $value->id";
                    $this->connection->query($queryau);
                }
                //produccion
                if(count($existeProduccion) != 0){
                    $query = "SELECT * FROM produccion WHERE almacen_id = $a";
                    $query = $this->connection->query($query)->result();
                    foreach ($query as $value) {

                        $querydetalle = "DELETE FROM produccion_detalle WHERE produccion_id = $value->id";
                        $this->connection->query($querydetalle);
                    }
                    foreach ($query as $value) {
                        $querypr = "DELETE FROM produccion WHERE id = $value->id";
                        $this->connection->query($querypr);
                    }
                }
                /*
                $this->connection->query("TRUNCATE TABLE movimiento_inventario;");
                $this->connection->query("TRUNCATE TABLE movimiento_detalle;");
                */
            }

            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
        }
    }

    public function deleteMovimientos($almacenes = false)
    {
        if ($almacenes != false) {
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

            $this->backupTable("movimiento_inventario");
            $this->backupTable("movimiento_detalle");
            $this->backupTable("auditoria_inventario");
            $this->backupTable("detalle_auditoria");

            $sql = "SHOW TABLES LIKE 'produccion'";
            $existeProduccion = $this->connection->query($sql)->result();
            if (count($existeProduccion) != 0) {
                $this->backupTable("produccion");
            }

            $sql = "SHOW TABLES LIKE 'produccion_detalle'";
            $existeProduccionDetalle = $this->connection->query($sql)->result();
            if (count($existeProduccionDetalle) != 0) {
                $this->backupTable("produccion_detalle");
            }

            foreach ($almacenes as $a) {
                $this->connection->query("DELETE i,d FROM movimiento_inventario AS i INNER JOIN movimiento_detalle AS d WHERE i.id=d.id_inventario AND i.almacen_id = " . $a);
                //auditoria
                $query = "SELECT * FROM auditoria_inventario WHERE id_almacen = $a";
                $query = $this->connection->query($query)->result();
                foreach ($query as $value) {
                    $querydetalle = "DELETE FROM detalle_auditoria WHERE id_auditoria = $value->id";
                    $this->connection->query($querydetalle);
                }
                foreach ($query as $value) {
                    $queryau = "DELETE FROM auditoria_inventario WHERE id = $value->id";
                    $this->connection->query($queryau);
                }

                //produccion
                if (count($existeProduccion) != 0) {
                    $query = "SELECT * FROM produccion WHERE almacen_id = $a";
                    $query = $this->connection->query($query)->result();
                    foreach ($query as $value) {

                        $querydetalle = "DELETE FROM produccion_detalle WHERE produccion_id = $value->id";
                        $this->connection->query($querydetalle);
                    }

                    foreach ($query as $value) {
                        $querypr = "DELETE FROM produccion WHERE id = $value->id";
                        $this->connection->query($querypr);
                    }
                }
            }

            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
        }
    }

    public function deleteOrdenes($almacenes = false)
    {
        if ($almacenes != false) {
            $this->connection->query("SET FOREIGN_KEY_CHECKS = 0;");

            $this->backupTable("orden_compra");
            $this->backupTable("pago_orden_compra");
            $this->backupTable("detalle_orden_compra");
            foreach ($almacenes as $a) {
                $query = "DELETE o,p,d FROM orden_compra AS o
                        LEFT JOIN pago_orden_compra AS p ON o.id=p.id_factura
                        LEFT JOIN detalle_orden_compra AS d ON o.id=d.venta_id
                        WHERE o.almacen_id = " . $a;
                $this->connection->query($query);

                $query = "DELETE mi,md FROM movimiento_inventario AS mi
                        LEFT JOIN movimiento_detalle AS md ON md.id_inventario = mi.id
                        WHERE mi.almacen_id = $a
                            AND mi.tipo_movimiento = 'entrada_compra'";
                $this->connection->query($query);
            }

            $this->connection->query("SET FOREIGN_KEY_CHECKS = 1;");
        }
    }

    public function add($data)
    {
        $this->connection->insert("restablecer", $data);
        return $this->connection->insert_id();
    }
}
