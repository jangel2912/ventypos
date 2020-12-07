var classVentaOffline = function () {
  // Base de datos

  var db;

  // Variable de Session

  var id_user = "";

  var is_admin = "";

  var username = "";

  var consecutivo = "";

  var base = "";

  var plantilla = "";

  var idVenta = "";

  var idVentaProd = "";

  var idCliente = "";

  var fechaVenta = "";

  //******************************************************************************************

  //

  //		 DB CONEXION

  //

  //******************************************************************************************

  //==========================================================================================

  // DB creacion y tablas

  //==========================================================================================

  function conectarDB(callBack) {
    // Seleccionamos Base de Datos

    db = openDatabase("vendty", "1.0", "vendty", 50 * 1024 * 1024);

    // Si la creacion o conexion fue exitosa

    if (db) {
      callBack();
    }

    // Si hay un error

    db.onError = function (tx, e) {
      errorOffline(
        "Ha habido un error en la creacion de la base de datos: " + e.message
      );
      return false;
    };
  }

  //==========================================================================================

  // TRUNCATE

  //==========================================================================================

  function truncateAll(data, callBack) {
    db.transaction(
      function (tx) {
        var tablas = Object.keys(data);

        for (var k = 0; k < tablas.length; k++) {
          tx.executeSql("DELETE FROM " + tablas[k]);
        }
      },

      function () {
        errorOffline("Error al borrar datos");
      },

      callBack
    );
  }

  function truncate(tabla) {
    db.transaction(function (tx) {
      tx.executeSql("DELETE FROM " + tabla);
    });
  }

  //================================================================================================================================

  //	>>>>>>>>   FIN CONEXION

  //================================================================================================================================

  //******************************************************************************************

  //

  //		 Aplicacion

  //

  //******************************************************************************************

  //-----------------------------------------------------------------------------

  //   Para borrar ventas una vez sincronizado

  //-----------------------------------------------------------------------------

  var cantidadProductos = 0;

  this.getTotalProductos = function (callback) {
    db.transaction(function (tx) {
      var sql = "select count(producto.id) as total from producto";

      tx.executeSql(
        sql,
        [],
        function (tx, results) {
          cantidadProductos = results.rows.item(0).total;

          callback();
        },
        null
      );
    });
  };

  this.totalProductos = function () {
    return cantidadProductos;
  };

  //-----------------------------------------------------------------------------

  //   Para borrar ventas una vez sincronizado

  //-----------------------------------------------------------------------------

  this.truncateVentas = function (callback, fallback) {
    var idUltimaVenta;

    db.transaction(
      function (tx) {
        var sql = " SELECT id FROM venta ORDER BY id DESC limit 1 ";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            idUltimaVenta = results.rows.item(0).id;
          },
          null
        );
      },

      function (tx, res) {
        errorOffline("Error al consultar la última ventas en la DB Local ");
        fallback();
      },

      function (tx, res) {
        db.transaction(
          function (tx) {
            tx.executeSql(
              "UPDATE _extraData SET ultima_venta = " + idUltimaVenta
            );

            tx.executeSql("DELETE FROM _offlineVentas");
          },

          function (tx, res) {
            errorOffline(
              "Error al actualizar el consecutivo ventas en la DB Local " + res
            );

            callback();
          },

          function (tx, res) {
            callback();
          }
        );
      }
    );
  };

  this.truncateClientes = function (callback) {
    db.transaction(
      function (tx) {
        tx.executeSql("DELETE FROM _offlineClientes");
      },

      function (tx, res) {
        errorOffline("Error al eliminar ventas de la DB Local " + res);
      },

      function (tx, res) {
        callback();
      }
    );
  };

  //-----------------------------------------------------------------------------

  //   Geters OBJs

  //-----------------------------------------------------------------------------

  // Objetos

  var objClientes = [];

  var objVendedores = [];

  var objProductosBuscador = [];

  var objProductosCodificalo = [];

  var objProductosNavegador = [];

  var objClientesOffline = [];

  var objVentas = [];

  var objVentasHis = [];

  var objFactura = [];

  this.getObjClientes = function () {
    return objClientes;
  };

  this.getObjVendedores = function () {
    return objVendedores;
  };

  this.getObjProductosBuscador = function () {
    return objProductosBuscador;
  };

  this.getObjProductosCodificalo = function () {
    return objProductosCodificalo;
  };

  this.getObjProductosNavegador = function () {
    return objProductosNavegador;
  };

  this.getObjClientesOffline = function () {
    return objClientesOffline;
  };

  this.getObjVentas = function () {
    return objVentas;
  };

  this.getObjVentasHis = function () {
    return objVentasHis;
  };

  this.getObjFactura = function () {
    return objFactura;
  };

  // Get ID de la ultima venta creada

  this.getIdVentaProd = function () {
    return idVentaProd;
  };

  this.getIdVenta = function () {
    return idVenta;
  };

  this.getIdCliente = function () {
    return idCliente;
  };

  this.getUsername = function () {
    return username;
  };

  this.getPlantilla = function () {
    return plantilla;
  };

  // Base Codeigniter

  this.getBase = function () {
    return base;
  };

  //-----------------------------------------------------------------------------

  //   Funciones  GET  en DB, guardan el resultado en los objetos anteriores

  //-----------------------------------------------------------------------------

  // Guarda en las variables iniciales la infromacion extra

  function getExtaData(callback) {
    db.transaction(
      function (tx) {
        var sql = "SELECT * FROM _extraData";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            id_user = results.rows.item(0).user_id;

            is_admin = results.rows.item(0).is_admin;

            username = results.rows.item(0).username;

            consecutivo = results.rows.item(0).consecutivo;

            base = results.rows.item(0).base;
          },
          null
        );

        var sql =
          " SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'plantilla_empresa' ";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            var factPlantilla = results.rows.item(0).valor_opcion;

            if (factPlantilla == "media_carta") {
              plantilla = "_imprimemediacarta";
            } else if (factPlantilla == "general") {
              plantilla = "_imprimemediacarta";
            } else if (factPlantilla == "media_carta_2") {
              plantilla = "_imprimemediacarta2";
            } else if (factPlantilla == "media_carta_3") {
              plantilla = "_imprimemediacarta3";
            } else if (factPlantilla == "moderna") {
              plantilla = "_imprimemediacarta4";
            } else if (factPlantilla == "moderna_logo_redondo") {
              plantilla = "_imprimemediacarta_logo_redondo";
            } else if (factPlantilla == "ticket_cafeterias") {
              plantilla = "_imprime_ticket_cafeterias";
            } else if (factPlantilla == "moderna_ingles") {
              plantilla = "_imprimemediacarta_ingles";
            } else if (factPlantilla == "moderna_completa_ingles") {
              plantilla = "_imprimemediacarta_ingles_completa";
            } else if (factPlantilla == "moderna_codibarras") {
              plantilla = "_imprimemediacarta_logo_izq";
            } else if (factPlantilla == "_imprimemediacarta_especial_1") {
              plantilla = "_imprimemediacarta_especial_1";
            } else if (factPlantilla == "_imprimemediacarta_especial_2") {
              plantilla = "_imprimemediacarta_especial_2";
            } else if (factPlantilla == "moderna_izq") {
              plantilla = "_imprimemediacarta_logo_izq";
            } else if (factPlantilla == "moderna_izq_discriminado_iva") {
              plantilla = "_imprimemediacarta_logo_izq";
            } else if (factPlantilla == "modelo_impresora_factura") {
              plantilla = "_imprimefacturamatricial";
            } else if (factPlantilla == "modelo_factura_clasica") {
              plantilla = "_imprime_clasica";
            } else if (factPlantilla == "modelo_ticket_58mm") {
              plantilla = "imprime_ticket_58mm";
            } else if (factPlantilla == "ticket_productos_atributos") {
              plantilla = "imprime_ticket_atributos";
            } else {
              plantilla = "imprime_ticket_atributos";
            }
          },
          null
        );
      },

      function (tx, res) {
        noLocalData();
      },

      function (tx, res) {
        callback();
      }
    );
  }

  // Retorna los productos modo NAVEGADOR

  this.queryProductosNavegador = function (callback) {
    var jsonObjProductosNavegador = [];

    db.transaction(
      function (tx) {
        var sql =
          'SELECT "vendtyShop.jpg" AS imagen, producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades AS stock_minimo, IFNULL(impuesto.porciento, 0) AS impuesto, "vendtyShop.jpg" AS imagen, producto.id FROM producto LEFT JOIN stock_actual ON producto.id = stock_actual.producto_id INNER JOIN usuario_almacen ON usuario_almacen.almacen_id = stock_actual.almacen_id LEFT JOIN impuesto ON impuesto.id_impuesto = producto.impuesto WHERE  producto.material=0 AND usuario_almacen.usuario_id = ' +
          id_user +
          " AND producto.activo = 1";
        // console.log(sql);
        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            var len = results.rows.length;

            for (var i = 0; i < len; i++) {
              jsonObjProductosNavegador.push(results.rows.item(i));
            }
          },
          null
        );
      },
      function (tx, res) {
        // console.log(jsonObjProductosNavegador);
        // console.log(tx + " ----- " + res);
        errorOffline(
          "Error al consultar productos desde navegador en la DB Local " + res
        );
      },

      function (tx, res) {
        objProductosNavegador = jsonObjProductosNavegador;

        callback();
      }
    );
  };

  // Retorna los productos modo BUSCADOR

  this.queryProductosBuscador = function (query, callback) {
    var jsonObjProductosBuscador = [];

    if (query == "") {
      objProductosBuscador = [];

      callback();
    } else {
      db.transaction(
        function (tx) {
          var sql =
            "SELECT producto.codigo_barra, producto.nombre, producto.codigo, producto.precio_compra, ubicacion AS ubic, producto.precio_venta, stock_actual.unidades AS stock_minimo, IFNULL(impuesto.porciento, 0) AS impuesto, 'vendtyShop.jpg' AS imagen, producto.id FROM producto INNER JOIN categoria ON categoria.id = producto.categoria_id LEFT JOIN stock_actual ON producto.id = stock_actual.producto_id INNER JOIN usuario_almacen ON usuario_almacen.almacen_id = stock_actual.almacen_id LEFT JOIN impuesto ON impuesto.id_impuesto = producto.impuesto WHERE  producto.material=0 AND (UPPER(producto.nombre) LIKE '%" +
            query +
            "%' OR UPPER(producto.codigo) LIKE '%" +
            query +
            "%' OR UPPER(categoria.nombre) LIKE '%" +
            query +
            "%') AND usuario_almacen.usuario_id = " +
            id_user +
            " AND producto.activo = 1 LIMIT 50";

          //console.log(sql);

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              var len = results.rows.length;

              for (var i = 0; i < len; i++) {
                jsonObjProductosBuscador.push(results.rows.item(i));
              }
            },
            null
          );
        },

        function (tx, res) {
          errorOffline(
            "Error al consultar productos desde buscador en la DB Local " + res
          );
        },

        function (tx, res) {
          objProductosBuscador = jsonObjProductosBuscador;

          callback();
        }
      );
    }
  };

  // Retorna los productos modo CODIFICALO

  this.queryProductosCodificalo = function (query, callback) {
    var jsonObjProductosCodificalo = [];

    if (query == "") {
      objProductosCodificalo = [];

      callback();
    } else {
      db.transaction(
        function (tx) {
          var sql =
            " SELECT 'vendtyShop.jpg' AS imagen, producto.nombre, producto.codigo, producto.precio_compra, ubicacion AS ubic, producto.precio_venta, stock_actual.unidades AS stock_minimo, IFNULL(impuesto.porciento, 0) AS impuesto, 'vendtyShop.jpg' AS imagen, producto.id FROM producto LEFT JOIN stock_actual ON producto.id = stock_actual.producto_id INNER JOIN usuario_almacen ON usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id LEFT JOIN impuesto ON impuesto.id_impuesto = producto.impuesto WHERE producto.material=0 AND producto.codigo = '" +
            query +
            "' AND usuario_almacen.usuario_id = '" +
            id_user +
            "' AND producto.activo = 1 ";

          //console.log(sql);

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              var len = results.rows.length;

              for (var i = 0; i < len; i++) {
                jsonObjProductosCodificalo.push(results.rows.item(i));
              }
            },
            null
          );
        },

        function (tx, res) {
          errorOffline(
            "Error al consultar productos desde codificalo en la DB Local " +
              res
          );
        },

        function (tx, res) {
          objProductosCodificalo = jsonObjProductosCodificalo[0];

          callback();
        }
      );
    }
  };

  // Retorna los productos modo CLIENTES

  this.queryClientes = function (query, callback) {
    var jsonObjClientes = [];

    if (query == "") {
      objClientes = [];

      callback();
    } else {
      db.transaction(
        function (tx) {
          var sql =
            " SELECT (SELECT id FROM lista_precios WHERE grupo_cliente_id = c.grupo_clientes_id LIMIT 1) AS lista, c.grupo_clientes_id, c.id_cliente AS id, c.nombre_comercial || ' (' || IFNULL(c.nif_cif, '') || ')' AS value, IFNULL(c.nif_cif, '') || ', ' || IFNULL(direccion, '') || ', ' || IFNULL(poblacion, '') || ', ' || IFNULL(pais, '') || ',' || IFNULL(provincia, '') || ', ' ||   IFNULL(cp, '') AS descripcion FROM clientes c WHERE  c.nombre_comercial || ' ' || IFNULL(c.nif_cif, '') || ' ' || IFNULL(c.poblacion, '')  LIKE '%" +
            query +
            "%' LIMIT 0, 30 ";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              var len = results.rows.length;

              for (var i = 0; i < len; i++) {
                jsonObjClientes.push(results.rows.item(i));
              }
            },
            null
          );
        },

        function (tx, res) {
          errorOffline("Error al consultar clientes en la DB Local " + res);
        },

        function (tx, res) {
          objClientes = jsonObjClientes;

          callback();
        }
      );
    }
  };

  // Retorna los productos modo VENDEDORES

  this.queryVendedores = function (query, callback) {
    var jsonObjVendedores = [];

    if (query == "") {
      objVendedores = [];

      callback();
    } else {
      db.transaction(
        function (tx) {
          var sql =
            " SELECT id,nombre as value FROM vendedor WHERE nombre LIKE '%" +
            query +
            "%' LIMIT 0, 30 ";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              var len = results.rows.length;

              for (var i = 0; i < len; i++) {
                jsonObjVendedores.push(results.rows.item(i));
              }
            },
            null
          );
        },

        function (tx, res) {
          errorOffline("Error al consultar Vendedores en la DB Local " + res);
        },

        function (tx, res) {
          objVendedores = jsonObjVendedores;

          callback();
        }
      );
    }
  };

  // Retorna las ventas de la tabla _offlineVentas y los clientes de _offlineClientes

  this.queryVentas = function (callback) {
    var jsonObjVentas = [];

    var jsonObjClientes = [];

    db.transaction(
      function (tx) {
        var sql = "SELECT obj from _offlineVentas";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            var len = results.rows.length;

            for (var i = 0; i < len; i++) {
              jsonObjVentas.push(results.rows.item(i));
            }
          },
          null
        );

        var sql = "SELECT obj from _offlineClientes";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            var len = results.rows.length;

            for (var i = 0; i < len; i++) {
              jsonObjClientes.push(results.rows.item(i));
            }
          },
          null
        );
      },

      function (tx, res) {
        errorOffline(
          "Error al consultar las ventas offline en la DB Local " + res
        );
      },

      function (tx, res) {
        objVentas = jsonObjVentas;

        objClientesOffline = jsonObjClientes;

        callback();
      }
    );
  };

  this.queryVentasHistorico = function (callback) {
    var jsonObjVentas = [];

    db.transaction(
      function (tx) {
        var sql = "SELECT ultima_venta from _extraData";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            idVentaProd = results.rows.item(0).ultima_venta;
          },
          null
        );

        var sql =
          " SELECT v.id, v.factura, v.fecha, v.total_venta as total, IFNULL(ve.nombre,'') as vendedor, IFNULL(c.nombre_comercial,'') as cliente from venta v left join clientes c ON v.cliente_id = c.id_cliente left join vendedor ve ON v.vendedor = ve.id ";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            var len = results.rows.length;

            for (var i = 0; i < len; i++) {
              jsonObjVentas.push(results.rows.item(i));
            }
          },
          null
        );
      },

      function (tx, res) {
        errorOffline(
          "Error al consultar el historico de ventas offline en la DB Local " +
            res
        );
      },

      function (tx, res) {
        objVentasHis = jsonObjVentas;

        callback();
      }
    );
  };

  // Retorna la la factura

  this.queryFactura = function (id, callback) {
    var data_empresa = {};

    var get_by_id = null;

    var get_detalles_ventas;

    var get_detalles_pago;

    var get_detalles_pago_result_pago;

    var get_detalles_pago_result_cambio;

    var venta_impuestos;

    //----------------------------------------------------------------------

    // DATA EMPRESA

    //----------------------------------------------------------------------

    var opciones = [
      "nombre_empresa",
      "resolucion_factura",
      "contacto_empresa",
      "email_empresa",
      "direccion_empresa",
      "telefono_empresa",
      "fax_empresa",
      "web_empresa",
      "moneda_empresa",
      "resolucion_factura_estado",
      "plantilla_empresa",
      "paypal_email",
      "cabecera_factura",
      "terminos_condiciones",
      "titulo_venta",
      "sistema",
      "nit",
      "plantilla_cotizacion",
      "numero",
      "sobrecosto",
      "multiples_formas_pago",
      "vendedor_impresion",
      "valor_caja",
      "filtro_ciudad",
      "tipo_factura",
      "comanda",
      "etienda",
      "logotipo_empresa",
    ];

    var dataEmpresaChild = {};

    //creando los objetos

    for (var i = 0; i < opciones.length; i++) {
      var opc;

      if (i == 1) {
        opc = opciones[i].split("_factura")[0];

        dataEmpresaChild[opc] = null;
      } else {
        opc = opciones[i].split("_empresa")[0];

        dataEmpresaChild[opc] = null;
      }
    }

    data_empresa.data = dataEmpresaChild;

    //----------------------------------------------------------------------

    // DATA EMPRESA

    //----------------------------------------------------------------------

    db.transaction(
      function (tx) {
        //----------------------------------------------------------------------

        // DATA EMPRESA

        //----------------------------------------------------------------------

        var dataEmpresaSql = function (sql, opc) {
          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              data_empresa.data[opc] = results.rows.length
                ? results.rows.item(0).valor_opcion
                : "";
            },
            null
          );
        };

        //Almacenando en objetos

        for (var i = 0; i < opciones.length; i++) {
          var name, sql;

          if (i == 1) {
            name = opciones[i].split("_factura")[0];

            sql =
              " SELECT valor_opcion FROM opciones WHERE nombre_opcion = '" +
              opciones[i] +
              "' ";
          } else {
            name = opciones[i].split("_empresa")[0];

            sql =
              " SELECT valor_opcion FROM opciones WHERE nombre_opcion = '" +
              opciones[i] +
              "' ";
          }

          dataEmpresaSql(sql, name);
        }

        //----------------------------------------------------------------------

        // GET BY ID

        //----------------------------------------------------------------------

        sql =
          "SELECT IFNULL(venta.id,'') as id_venta, venta.*, IFNULL(vendedor.nombre,'') as vendedor, IFNULL(clientes.nombre_comercial,'') AS nombre_comercial, IFNULL(clientes.email,'') AS email, IFNULL(clientes.nif_cif,'') AS nif_cif, IFNULL(clientes.telefono,'') as cliente_telefono, IFNULL(clientes.email,'') as cliente_email , IFNULL(clientes.grupo_clientes_id,'') AS grupo_clientes_id, IFNULL(clientes.direccion,'') as cliente_direccion , IFNULL(clientes.movil,'') as cliente_movil, IFNULL(clientes.tipo_identificacion,'') as tipo_identificacion, IFNULL(provincia,'') as cliente_provincia, almacen.* FROM  venta inner join almacen on almacen.id = venta.almacen_id left join clientes on id_cliente = venta.cliente_id left join vendedor on vendedor.id = venta.vendedor WHERE venta.id = '" +
          id +
          "'";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            get_by_id = results.rows.item(0);
          },
          null
        );

        //----------------------------------------------------------------------

        // get_detalles_ventas

        //----------------------------------------------------------------------

        sql =
          "SELECT IFNULL(detalle_venta.id,'') AS id,IFNULL(venta_id,'') AS venta_id, IFNULL(codigo_producto,'') AS codigo_producto, IFNULL(nombre_producto,'') AS nombre_producto, IFNULL( detalle_venta.descripcion_producto,'') as descripcion_producto, IFNULL(unidades,'') AS unidades, IFNULL(detalle_venta.precio_venta,'')AS precio_venta, IFNULL(descuento,'') as descuento, IFNULL(porcentaje_descuento,'') as porcentaje_descuento, IFNULL(detalle_venta.impuesto,'') AS impuesto, IFNULL(linea,'') AS linea, IFNULL(margen_utilidad,'') AS margen_utilidad, IFNULL(detalle_venta.activo,'') AS activo, IFNULL(detalle_venta.producto_id,'') AS producto_id, IFNULL( (select nombre_impuesto from impuesto where impuesto.porciento = detalle_venta.impuesto limit 1),'') as des_impuesto FROM detalle_venta where venta_id = " +
          id;

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            get_detalles_ventas = results.rows;
          },
          null
        );

        //----------------------------------------------------------------------

        // get_detalles_pago

        //----------------------------------------------------------------------

        sql = "SELECT * FROM ventas_pago WHERE id_venta = " + id;

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            get_detalles_pago = results.rows.item(0);
          },
          null
        );

        //----------------------------------------------------------------------

        // get_detalles_pago_result_pago & get_detalles_pago_result_cambio

        //----------------------------------------------------------------------

        // Pago

        sql = "SELECT * FROM ventas_pago WHERE id_venta = " + id;

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            get_detalles_pago_result_pago = results.rows;
          },
          null
        );

        // Cambio

        sql =
          "SELECT sum(cambio) as total_cambio FROM ventas_pago WHERE id_venta = " +
          id;

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            get_detalles_pago_result_cambio = results.rows;
          },
          null
        );

        //----------------------------------------------------------------------

        // venta_impuestos

        //----------------------------------------------------------------------

        sql =
          " SELECT (SELECT nombre_impuesto FROM impuesto where porciento = impuesto limit 1) as imp, SUM( (precio_venta - descuento) * impuesto / 100 *  unidades ) AS impuestos FROM  venta inner join detalle_venta on venta.id = detalle_venta.venta_id WHERE venta.id = " +
          id +
          " and impuesto > 0  group by impuesto ";

        tx.executeSql(
          sql,
          [],
          function (tx, results) {
            venta_impuestos = results.rows;
          },
          null
        );
      },

      function (tx, res) {
        errorOffline(
          "Error al consultar las ventas offline en la DB Local " + res
        );
      },

      function (tx, res) {
        //callback();

        //console.log(username);

        //console.log( JSON.stringify( data_empresa, null, "\t") );

        //console.log( JSON.stringify( get_by_id, null, "\t") );

        //console.log( JSON.stringify( get_detalles_ventas, null, "\t") );

        //console.log( JSON.stringify( get_detalles_pago, null, "\t") );

        //console.log( JSON.stringify( get_detalles_pago_result_pago, null, "\t") );

        //console.log( JSON.stringify( get_detalles_pago_result_cambio, null, "\t") );

        //console.log( JSON.stringify( venta_impuestos, null, "\t") );

        data = [];

        (data["venta"] = get_by_id),
          (data["detalle_venta"] = get_detalles_ventas),
          (data["detalle_pago"] = get_detalles_pago),
          (data["detalle_pago_multiples"] = get_detalles_pago_result_pago),
          (data[
            "detalle_pago_multiples_cambio"
          ] = get_detalles_pago_result_cambio),
          (data["venta_impuestos"] = venta_impuestos),
          (data["data_empresa"] = data_empresa),
          (data["username"] = username),
          (data["tipo_factura"] = data_empresa["data"]["tipo_factura"]);

        objFactura = data;

        callback();
      }
    );
  };

  //-----------------------------------------------------------------------------

  //   Funciones   SET  en DB

  //-----------------------------------------------------------------------------

  // Almacena las ventas en la tabla _offlineVentas

  this.guardarVenta2 = function (data, callback) {
    var venta = JSON.stringify(data);

    db.transaction(
      function (tx) {
        var sql = "INSERT INTO _offlineVentas (obj) VALUES ('" + venta + "') ";

        tx.executeSql(sql);
      },

      function (tx, res) {
        errorOffline("Error al guardar venta en DB Local " + res);
      },

      function (tx, res) {
        callback();
      }
    );
  };

  Number.prototype.padLeft = function (base, chr) {
    var len = String(base || 10).length - String(this).length + 1;

    return len > 0 ? new Array(len).join(chr || "0") + this : this;
  };

  function empty(mixed_var) {
    var key;

    if (
      mixed_var === "" ||
      mixed_var === 0 ||
      mixed_var === "0" ||
      mixed_var === null ||
      mixed_var === false ||
      mixed_var === undefined
    ) {
      return true;
    }

    if (typeof mixed_var == "object") {
      for (key in mixed_var) {
        return false;
      }

      return true;
    }

    return false;
  }

  function getActual() {
    var d = new Date();

    dformat =
      [
        d.getFullYear(),

        (d.getMonth() + 1).padLeft(),

        d.getDate().padLeft(),
      ].join("-") +
      " " +
      [
        d.getHours().padLeft(),

        d.getMinutes().padLeft(),

        d.getSeconds().padLeft(),
      ].join(":");

    return dformat;
  }

  function getFecha() {
    var d = new Date();

    dformat = [
      d.getFullYear(),

      (d.getMonth() + 1).padLeft(),

      d.getDate().padLeft(),
    ].join("-");

    return dformat;
  }

  function strInsert(nombre, obj) {
    var columnas = Object.keys(obj);

    var into = [];

    var values = [];

    for (var i = 0; i < columnas.length; i++) {
      into.push(columnas[i]);

      values.push("'" + obj[columnas[i]] + "'");
    }

    var sql =
      "INSERT INTO " +
      nombre +
      " (" +
      into.join(",") +
      ") VALUES (" +
      values.join(",") +
      ")";

    return sql;
  }

  //======================================

  // AÑADIR Cliente

  //======================================

  this.guardarCliente = function (cliente, callback) {
    var array_datos = {
      nombre_comercial: cliente.nombre_comercial,

      razon_social: cliente.nombre_comercial,

      tipo_identificacion: cliente.tipo_identificacion,

      nif_cif: cliente.nif_cif,

      email: cliente.email,

      telefono: cliente.telefono,

      direccion: cliente.direccion,

      pais: cliente.pais,

      provincia: cliente.provincia,

      movil: cliente.celular,

      grupo_clientes_id: 1,
    };

    db.transaction(
      function (tx) {
        var sql =
          "INSERT INTO _offlineClientes (obj) VALUES ('" +
          JSON.stringify(cliente) +
          "') ";

        tx.executeSql(sql);

        var sql = strInsert("clientes", array_datos);

        tx.executeSql(
          sql,
          [],

          function (itx, results) {
            var id = results.insertId;

            idCliente = id;
          },
          function (etx, err) {}
        );
      },

      function (tx, res) {
        errorOffline("Error al guardar cliente en DB Local " + res);
      },

      function (tx, res) {
        callback();
      }
    );
  };

  //======================================

  // AÑADIR VENTA

  //======================================

  this.guardarVenta = function (venta, callback, fallback) {
    var actual = getActual();

    fechaVenta = actual;

    var sobrecostos = "propina" in venta ? venta.propina : 0;

    var id_fact_espera = "id_fact_espera" in venta ? venta.id_fact_espera : "";

    //console.log("aqui ve");

    var data = {
      fecha: actual,

      fecha_vencimiento: actual,

      cliente: venta.cliente,

      vendedor: venta.vendedor,

      usuario: id_user,

      productos: venta.productos,

      // "total_venta": (venta.total_venta - ((venta.total_venta * venta.descuento_general) / 100)),
      total_venta: venta.total_venta,

      pago: venta.pago,

      pago_1: venta.pago_1,

      pago_2: venta.pago_2,

      pago_3: venta.pago_3,

      pago_4: venta.pago_4,

      pago_5: venta.pago_5,

      tipo_factura: "estandar",

      nota: venta.nota,

      descuento_general: venta.descuento_general,

      subtotal_input: venta.subtotal_input,

      subtotal_propina_input: venta.subtotal_propina_input,

      sobrecostos: sobrecostos,

      id_fact_espera: id_fact_espera,

      sistema: "Pos",
    };

    //================================================

    // MODELO ADD

    //================================================

    var id_cliente;

    if (data.cliente == "") id_cliente = -1;
    else id_cliente = data.cliente;

    if (data.productos.length > 0) {
      var array_cliente = [];
      var numero_factura = 0; // OK
      var prefijo_factura = 0; // OK
      var opcnum = 0; // OK
      var sobrecostosino = 0; // OK
      var nit = 0; //ok
      var multiformapago = 0; // OK
      var valor_caja = 0; // OK
      var no_factura_row = null;
      var factura_int = 0;
      var fact = 0;
      var productos = {};
      var almacenStock = {};

      db.transaction(
        function (tx) {
          var nit = 1;
          var sql = "SELECT valor_opcion FROM opciones  where id = 26";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              numero_factura = results.rows.item(0).valor_opcion;
            },
            null
          );

          var sql = "SELECT valor_opcion FROM opciones  where id = 27";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              prefijo_factura = results.rows.item(0).valor_opcion;
            },
            null
          );

          var sql = "SELECT valor_opcion FROM opciones  where id = 35";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              opcnum = results.rows.item(0).valor_opcion;
            },
            null
          );

          var sql =
            " SELECT valor_opcion FROM opciones  where nombre_opcion = 'sobrecosto' ";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              sobrecostosino = results.rows.item(0).valor_opcion;
            },
            null
          );

          var sql =
            " SELECT valor_opcion FROM opciones  where nombre_opcion = 'nit' ";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              nit = results.rows.item(0).valor_opcion;
            },
            null
          );

          var sql = " SELECT valor_opcion FROM opciones  where id = 37 ";

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              multiformapago =
                data["sistema"] == "Pos"
                  ? results.rows.item(0).valor_opcion
                  : "no";
            },
            null
          );

          var sql = "SELECT valor_opcion FROM opciones  where id = 39";
          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              valor_caja = results.rows.item(0).valor_opcion;
            },
            null
          );

          // ultimo consecutivo

          var sql =
            "select almacen.id, prefijo, consecutivo from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id = " +
            id_user;

          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              no_factura_row = results.rows.item(0);

              factura_int = results.rows.item(0).consecutivo + 1;
            },
            null
          );

          // fecha de la ultima venta

          var sql = " SELECT fecha FROM venta order by id desc limit 1 ";
          //  console.log(sql);
          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              // console.log(results);//
              // fact = results.rows.item(0).fecha;
            },
            null
          );

          // productos

          var getProductos = function (sql) {
            tx.executeSql(
              sql,
              [],
              function (tx, results) {
                productos[results.rows.item(0).id] = results.rows.item(0);
              },
              null
            );
          };

          for (var i = 0; i < data.productos.length; i++) {
            var value = data.productos[i];

            var sql =
              " SELECT * FROM producto where id = " + value.product_id + " ";

            getProductos(sql);
          }
          // almacen - stock actual

          var sql =
            "select almacen.id from almacen inner join usuario_almacen on almacen.id = almacen_id where usuario_id = " +
            id_user;
          // console.log(sql);
          tx.executeSql(
            sql,
            [],
            function (tx, results) {
              var almacenId = results.rows.item(0).id;
              // para cada producto

              for (var i = 0; i < data.productos.length; i++) {
                var value = data.productos[i];
                var sql =
                  " select * from stock_actual where almacen_id =" +
                  almacenId +
                  " and producto_id = " +
                  value["product_id"];

                tx.executeSql(
                  sql,
                  [],
                  function (tx, results) {
                    almacenStock[
                      results.rows.item(0).producto_id
                    ] = results.rows.item(0);
                  },
                  null
                );
              }
            },
            null
          );
        },

        function (tx, res) {
          errorOffline("Error al consultar datos en DB Local " + res);
          fallback();
        },

        function (tx, res) {
          db.transaction(
            function (tx2) {
              var num_factura = 0;

              // APLICATIVO INSERTAR EN DB

              if (data["fecha"] != fact) {
                if (opcnum == "no") {
                  num_factura = 0;
                  tx2.executeSql(
                    "UPDATE almacen SET consecutivo = " +
                      factura_int +
                      " WHERE id = '" +
                      no_factura_row.id +
                      "'"
                  );
                  num_factura = no_factura_row.prefijo + "" + factura_int;
                }

                if (opcnum == "si") {
                  num_factura = 0;
                  numero_factura = parseInt(numero_factura) + 1;
                  tx2.executeSql(
                    "UPDATE opciones SET valor_opcion = " +
                      numero_factura +
                      " WHERE id =  '26'"
                  );
                  num_factura = prefijo_factura + "" + numero_factura;
                }

                var array_datos = {
                  fecha: data["fecha"],

                  fecha_vencimiento: data["fecha_vencimiento"],

                  usuario_id: data["usuario"],

                  factura: num_factura,

                  almacen_id: no_factura_row.id,

                  total_venta: data["total_venta"],

                  cliente_id: id_cliente,

                  porcentaje_descuento_general: data["descuento_general"],

                  tipo_factura: data["tipo_factura"],
                };

                array_datos["activo"] = 1;

                array_datos["estado"] = 0;

                if (data["nota"] != "") {
                  array_datos["nota"] = data["nota"];
                }

                if (data["vendedor"] != "") {
                  array_datos["vendedor"] = data["vendedor"];
                }

                var sql = strInsert("venta", array_datos);

                // AÑADIMOS LA VENTA EN TABLA VENTA

                tx2.executeSql(
                  sql,
                  [],

                  function (itx, results) {
                    var id = results.insertId;

                    var data_detalles = [];

                    var query_stock = "";

                    var descuento_prod = "0";

                    // Ciclo segun los productos

                    for (var i = 0; i < data.productos.length; i++) {
                      var value = data.productos[i];

                      var unidades_compra = value["unidades"];

                      var product_id = value["product_id"];

                      // console.log(productos[product_id])

                      // console.log(productos)

                      var comp = productos[product_id].precio_compra
                        ? productos[product_id].precio_compra
                        : 0;

                      /*
                                            if (data['descuento_general'] > 0 && value['descuento'] == 0) {

                                                descuento_prod = ((value['precio_venta'] * data['descuento_general']) / 100);

                                            } else {

                                                descuento_prod = value['descuento'];

                                            }*/

                      descuento_prod = value["descuento"];
                      porcentaje_descuentop = 0;

                      if (!empty(value["porcentaje_descuentop"])) {
                        porcentaje_descuentop = value["porcentaje_descuentop"];
                      }

                      var compra_final_1 = value["precio_venta"] - comp;

                      var compra_final_2 = compra_final_1 * value["unidades"];

                      var compra_final_3 =
                        value["descuento"] * value["unidades"];

                      if (empty(value["descripcion"]))
                        value["descripcion"] = "";

                      data_detalles.push({
                        venta_id: id,

                        activo: 1,

                        codigo_producto: value["codigo"],

                        precio_venta: value["precio_venta"],

                        unidades: value["unidades"],

                        nombre_producto: value["nombre_producto"],

                        descripcion_producto: value["descripcion"],

                        impuesto: value["impuesto"],

                        descuento: descuento_prod,

                        porcentaje_descuento: porcentaje_descuentop,

                        producto_id: product_id,

                        margen_utilidad: compra_final_2 - compra_final_3,
                      });

                      var almacen = almacenStock[product_id];

                      var unidades_actual = almacen.unidades;

                      var unidades = unidades_actual - value["unidades"];

                      //ACTUALIZAMOS ESTOCK ACTUAL

                      var sql =
                        "UPDATE stock_actual SET unidades = " +
                        unidades +
                        " WHERE almacen_id =  '" +
                        no_factura_row.id +
                        "' AND producto_id =  '" +
                        value["product_id"] +
                        "'";

                      tx2.executeSql(sql, []);

                      // Insertamos Stock Diario

                      var array_datos = {
                        producto_id: value["product_id"],

                        almacen_id: no_factura_row.id,

                        fecha: getFecha(),

                        unidad: "-" + value["unidades"],

                        precio: value["precio_venta"],

                        cod_documento: num_factura,

                        usuario: data["usuario"],

                        razon: "S",
                      };

                      var sql = strInsert("stock_diario", array_datos);

                      tx2.executeSql(sql, []);

                      //=============================================

                      // NO SOPORTADO PARA INGREDIENTES Y COMBOS

                      //=============================================

                      /* Ingredientes =================================================== */

                      producto = productos[value["product_id"]];

                      if (producto.ingredientes == 1) {
                        // ultimo consecutivo

                        var sql =
                          "SELECT * FROM producto_ingredientes where id_producto = " +
                          value["product_id"];

                        tx2.executeSql(
                          sql,
                          [],
                          function (tx, results) {
                            var ingredientes_producto = results.rows;

                            for (
                              var k = 0;
                              k < ingredientes_producto.length;
                              k++
                            ) {
                              var value2 = ingredientes_producto[k];

                              var query_stock =
                                "select * from stock_actual where almacen_id = " +
                                no_factura_row.id +
                                " and producto_id = " +
                                value2.id_ingrediente;

                              var crudIngredientes = function (
                                query_stock,
                                value2
                              ) {
                                tx2.executeSql(
                                  query_stock,
                                  [],
                                  function (tx, results) {
                                    var almacen = results.rows.item(0);

                                    var unidades =
                                      unidades_actual -
                                      value2.cantidad * unidades_compra;

                                    //Actualizamos stock actual

                                    var sql =
                                      " UPDATE stock_actual SET  unidades = " +
                                      unidades +
                                      " WHERE almacen_id = " +
                                      no_factura_row.id +
                                      " AND producto_id = " +
                                      value2.id_ingrediente;

                                    tx2.executeSql(sql, []);

                                    // Insertamos stock diario

                                    var data_ingrediente = {
                                      producto_id: value2.id_ingrediente,

                                      almacen_id: no_factura_row.id,

                                      fecha: getFecha(),

                                      unidad:
                                        "-" + value2.cantidad * unidades_compra,

                                      precio: 0,

                                      cod_documento: num_factura,

                                      usuario: data["usuario"],

                                      razon: "S",
                                    };

                                    var sql = strInsert(
                                      "stock_diario",
                                      data_ingrediente
                                    );

                                    tx2.executeSql(sql, []);
                                  },
                                  null
                                );
                              };

                              crudIngredientes(query_stock, value2);
                            }
                          },
                          null
                        );
                      }

                      if (producto.combo == 1) {
                        //productos del combo

                        var query_productos_combo =
                          "SELECT * FROM producto_combos where id_combo = " +
                          value["product_id"];

                        tx2.executeSql(
                          query_productos_combo,
                          [],
                          function (tx, results) {
                            var productos_combo = results.rows;

                            for (var k = 0; k < productos_combo.length; k++) {
                              var value2 = productos_combo[k];

                              //Stock del ingrediente

                              var query_stock =
                                "select * from stock_actual where almacen_id =" +
                                no_factura_row.id +
                                " and producto_id=" +
                                value2.id_producto;

                              var crudComnbos = function (query_stock, value2) {
                                tx2.executeSql(
                                  query_stock,
                                  [],
                                  function (tx, results) {
                                    var almacen = results.rows.item(0);

                                    var unidades_actual = almacen.unidades;

                                    unidades =
                                      unidades_actual -
                                      value2.cantidad * unidades_compra;

                                    var sql =
                                      " UPDATE stock_actual SET  unidades = " +
                                      unidades +
                                      " WHERE almacen_id = " +
                                      no_factura_row.id +
                                      " AND producto_id = " +
                                      value2.id_producto;

                                    tx2.executeSql(sql, []);

                                    //Insertar stock diario

                                    var data_combos = {
                                      producto_id: value2.id_producto,

                                      almacen_id: no_factura_row.id,

                                      fecha: getFecha(),

                                      unidad:
                                        "-" + value2.cantidad * unidades_compra,

                                      precio: 0,

                                      cod_documento: num_factura,

                                      usuario: data["usuario"],

                                      razon: "S",
                                    };

                                    var sql = strInsert(
                                      "stock_diario",
                                      data_combos
                                    );

                                    tx2.executeSql(sql, []);
                                  },
                                  null
                                );
                              };

                              crudComnbos(query_stock, value2);
                            }
                          },
                          null
                        );
                      }

                      //======================================================================================

                      //	FIN Combos e Ingredientes

                      //======================================================================================
                    }

                    // añadimos el detalle venta
                    //console.log(data_detalles); return;

                    for (var i = 0; i < data_detalles.length; i++) {
                      var sql = strInsert("detalle_venta", data_detalles[i]);

                      //  tx2.executeSql(sql, []);
                      //   return;
                      tx2.executeSql(sql, []);
                    }

                    data["pago"]["id_venta"] = id;

                    //console.log(data);

                    //=============================================

                    // NO SOPORTADO PARA VENTA A CREDITO

                    //=============================================

                    // Si no hay cambio asignemole 0

                    data["pago"]["cambio"] == ""
                      ? (data["pago"]["cambio"] = 0)
                      : "";

                    // AGREGANDO EN TABLA ventas_pago

                    var sql = strInsert("ventas_pago", data["pago"]);

                    tx2.executeSql(sql, []);

                    // AGREGANDO EN TABLA ventas_pago los posibles multiples pagos

                    if (multiformapago == "si" && data["sistema"] == "Pos") {
                      if (data["pago_1"]["valor_entregado"] != "0") {
                        data["pago_1"]["id_venta"] = id;

                        var sql = strInsert("ventas_pago", data["pago_1"]);

                        tx2.executeSql(sql, []);
                      }

                      if (data["pago_2"]["valor_entregado"] != "0") {
                        data["pago_2"]["id_venta"] = id;

                        var sql = strInsert("ventas_pago", data["pago_2"]);

                        tx2.executeSql(sql, []);
                      }

                      if (data["pago_3"]["valor_entregado"] != "0") {
                        data["pago_3"]["id_venta"] = id;

                        var sql = strInsert("ventas_pago", data["pago_3"]);

                        tx2.executeSql(sql, []);
                      }

                      if (data["pago_4"]["valor_entregado"] != "0") {
                        data["pago_4"]["id_venta"] = id;

                        var sql = strInsert("ventas_pago", data["pago_4"]);

                        tx2.executeSql(sql, []);
                      }

                      if (data["pago_5"]["valor_entregado"] != "0") {
                        data["pago_5"]["id_venta"] = id;

                        var sql = strInsert("ventas_pago", data["pago_5"]);

                        tx2.executeSql(sql, []);
                      }
                    }

                    //--

                    if (data["sobrecostos"] != "" && sobrecostosino == "si") {
                      if (nit == "320001127839") {
                        var arraySobrecosto = [];

                        arraySobrecosto["venta_id"] = id;

                        arraySobrecosto["nombre_producto"] = "PROPINA";

                        arraySobrecosto["unidades"] = "1";

                        arraySobrecosto["descripcion_producto"] =
                          data["sobrecostos"];

                        arraySobrecosto["precio_venta"] =
                          (data["subtotal_input"] * data["sobrecostos"]) / 100;

                        arraySobrecosto["activo"] = 1;

                        var sql = strInsert("detalle_venta", arraySobrecosto);

                        tx2.executeSql(sql, []);
                      } else {
                        var arraySobrecosto = [];

                        valor_propina =
                          (data["subtotal_propina_input"] *
                            data["sobrecostos"]) /
                          100;

                        arraySobrecosto["venta_id"] = id;

                        arraySobrecosto["nombre_producto"] = "PROPINA";

                        arraySobrecosto["unidades"] = "1";

                        arraySobrecosto["descripcion_producto"] =
                          data["sobrecostos"];

                        arraySobrecosto["precio_venta"] =
                          valor_propina -
                          (data["descuento_general"] * valor_propina) / 100;

                        arraySobrecosto["activo"] = 1;

                        var sql = strInsert("detalle_venta", arraySobrecosto);

                        tx2.executeSql(sql, []);
                      }
                    }

                    // GUARDAMOS EL ID DE LA ULTIMA VENTA

                    idVenta = id;
                  },
                  function (etx, err) {}
                );
              }
            },

            function (tx, res) {
              errorOffline("Error al guardar venta en DB Local " + res);
              fallback();
            },

            function (tx, res) {
              db.transaction(
                function (tx) {
                  venta.fecha = fechaVenta;

                  var sql =
                    "INSERT INTO _offlineVentas (obj) VALUES ('" +
                    JSON.stringify(venta) +
                    "') ";

                  tx.executeSql(sql);
                },

                function (tx, res) {
                  errorOffline(
                    "Error al guardar OBJETO venta en DB Local " + res
                  );
                  fallback();
                },

                function (tx, res) {
                  callback();
                }
              );
            }
          );
        }
      );
    }

    //------------------------------------------------
  };

  //================================================================================================================================

  this.conectarDB = function (callback) {
    conectarDB(function () {
      //captura las informacion extra en DB y las guarda en las variables del principio

      getExtaData(callback);
    });
  };

  // Para visualizar las variables de la clase

  this.test = function () {
    console.log(id_user);

    console.log(is_admin);

    console.log(username);

    console.log(consecutivo);

    console.log(base);
  };
};
