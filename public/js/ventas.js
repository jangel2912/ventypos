//2015-12-30
function handleBaseURL() {
  var getUrl = window.location,
    baseUrl =
      getUrl.protocol +
      "//" +
      getUrl.host +
      "/" +
      getUrl.pathname.split("/")[1];
  return baseUrl;
}
function imprimirOrden() {
  if (confirm("Desea imprimir la orden de compra")) imprimirOrden();
}

var imprimirOrden = function() {
  var getUrl = window.location;
  var zona = getUrl.pathname.split("/")[4];
  var mesa = getUrl.pathname.split("/")[5];
  console.log(zona);
  console.log(mesa);
  $.fancybox.open({
    width: "85%",

    height: "85%",

    autoScale: false,

    transitionIn: "none",

    transitionOut: "none",

    href: handleBaseURL() + "/ventas/imprime_orden/" + zona + "/" + mesa,

    type: "iframe",

    afterClose: function() {
      // location.href =  $reloadThis+"?var="+busquedas;
      //window.location = $reloadThis+"?var="+tipo_busqueda;
    }

    //padding : 5
  });
};

tipo_busqueda = $navegador; /*Tipo de busqueda default = 'buscalo' */

_enviarFactura = false;

/*--------------------------------------------------

| Controlador tipo busqueda                         |

---------------------------------------------------*/

switch (tipo_busqueda) {
  case "buscalo":
    tipo_busqueda = "buscalo";

    $("#search").attr("placeholder", "Digite producto a buscar...");

    $("#search").focus();

    $("#codificalo").removeClass("active");

    $("#navegador").removeClass("active");

    $("#buscalo-controles").css("display", "block");

    $(this).addClass("active");

    break;

  case "codificalo":
    tipo_busqueda = "codificalo";

    $("#search").attr("placeholder", "Código de barra");

    $("#search").focus();

    $("#buscalo").removeClass("active");

    $("#navegador").removeClass("active");

    $("#buscalo-controles").css("display", "none");

    $(this).addClass("active");

    break;

  case "navegador":
    //alert("fvf");
    tipo_busqueda = "navegador";

    filtrarCategoria(
      {
        categoria: "categoria",
        id: 0
      },
      0
    );

    $("#categorias").css("display", "block");

    $("#vitrina").css("display", "block");

    $("#search-container").css("display", "none");

    $("#buscalo-controles").css("display", "none");

    $("#buscalo").removeClass("active");

    $("#codificalo").removeClass("active");

    $(this).addClass("active");

    break;
}

var descuentoPorcentajePromocion = 0;

function openConnection() {
  // uses global 'conn' object
  if (conn.readyState === undefined || conn.readyState > 1) {
    conn = new WebSocket("ws://127.0.1.1:12500");
    conn.onopen = function() {
      conn.send("Connection Established Confirmation");
    };
    conn.onmessage = function(event) {
      //document.getElementById("content").innerHTML = event.data;
    };
    conn.onerror = function(event) {
      console.log("Web Socket Error");
    };

    conn.onclose = function(event) {
      console.log("Web Socket Closed");
    };
  }
}

$(document).ready(function() {
  (conn = {}), (window.WebSocket = window.WebSocket || window.MozWebSocket);
  openConnection();

  tipo_busqueda = "buscalo";

  $("#search").attr("placeholder", "Búsqueda por nombre de producto...");

  $("#search").focus();

  $("#buscalo-controles").css("display", "block");

  $(this).addClass("active");

  filtrarCategoria(
    {
      categoria: "categoria",
      id: 0
    },
    0
  );

  $("#categorias").css("display", "block");

  $("#vitrina").css("display", "block");
});

$("#tipo-busqueda li").click(function() {
  $("#search-container").css("display", "block");

  $("#categorias").css("display", "none");

  $("#vitrina").css("display", "none");

  $("#cod-container").css("display", "none");

  $("#buscalo-controles").css("display", "none");

  $("#search").val("");

  //$('#vitrina').html('');

  $("#facturasTable tbody").html("");

  busquedas = $(this).attr("id");

  switch ($(this).attr("id")) {
    case "buscalo":
      tipo_busqueda = "buscalo";

      $("#search").attr("placeholder", "Digite producto a buscar...");

      $("#search").focus();

      $("#codificalo").removeClass("active");

      $("#navegador").removeClass("active");

      $("#buscalo-controles").css("display", "block");

      $(this).addClass("active");

      break;

    case "codificalo":
      tipo_busqueda = "codificalo";

      $("#search").attr("placeholder", "Código de barra");

      $("#search").focus();

      $("#buscalo").removeClass("active");

      $("#navegador").removeClass("active");

      $(this).addClass("active");

      break;

    case "navegador":
      tipo_busqueda = "navegador";

      filtrarCategoria(
        {
          categoria: "categoria",
          id: 0
        },
        0
      );

      $("#categorias").css("display", "block");

      $("#vitrina").css("display", "block");

      $("#search-container").css("display", "none");

      $("#buscalo").removeClass("active");

      $("#codificalo").removeClass("active");

      $(this).addClass("active");

      break;
  }
});

var venta = {};

var url_promos = $("#promociones").data("fetch");

var promocionTipo = false;

function obtenerPromocion(id_promocion) {
  if (this.memo === undefined) this.memo = [];

  if (this.memo["promocion_" + id_promocion] !== undefined)
    return this.memo["promocion_" + id_promocion];

  var promocion;

  $.ajax({
    method: "post",

    url: url_promos + "/obtener",

    data: {
      id_promocion: id_promocion
    },

    async: false,

    dataType: "json"
  }).done(function(data) {
    promocion = data;

    if (promocion["tipo"] == "cantidad") {
      promocionTipo = 1;
    } else if (promocion["tipo"] == "") {
      promocionTipo = 0;
    }
  });

  this.memo["promocion_" + id_promocion] = promocion;

  return promocion;
}

function obtenerDetallePromocion(id_promocion) {
  if (this.memo === undefined) this.memo = [];

  if (this.memo["promocion_detalle_" + id_promocion] !== undefined)
    return this.memo["promocion_detalle_" + id_promocion];

  var detalle;

  $.ajax({
    method: "post",

    url: url_promos + "/obtenerDetallePromocion",

    data: {
      id_promocion: id_promocion
    },

    async: false,

    dataType: "json"
  }).done(function(data) {
    detalle = data;
  });

  this.memo["promocion_detalle_" + id_promocion] = detalle;

  //console.log(detalle);

  return detalle;
}

function validarProductoPromocion(id_promocion, id_producto) {
  // alert("validarProductoPromocion");
  if (this.memo === undefined) this.memo = [];

  if (
    this.memo["promocion_producto_" + id_promocion + "_" + id_producto] !==
    undefined
  )
    return this.memo["promocion_producto_" + id_promocion + "_" + id_producto];

  $.ajax({
    method: "post",

    url: url_promos + "/validarProducto",

    data: {
      id_promocion: id_promocion,

      id_producto: id_producto
    },

    async: false,

    dataType: "json"
  }).done(function(data) {
    valido = data.valido;
  });

  return valido;
}

function validarProductoPromocionD(id_promocion, id_producto) {
  //alert("validarProductoPromocionD");
  if (this.memo === undefined) this.memo = [];

  if (
    this.memo["promocion_producto_" + id_promocion + "_" + id_producto] !==
    undefined
  )
    return this.memo["promocion_producto_" + id_promocion + "_" + id_producto];

  $.ajax({
    method: "post",

    url: url_promos + "/validarProductoD",

    data: {
      id_promocion: id_promocion,

      id_producto: id_producto
    },

    async: false,

    dataType: "json"
  }).done(function(data) {
    valido = data.valido;
  });

  return valido;
}

function procesarDescuento(producto, descuento) {
  //alert("procesarDescuento");
  var table = $('.product_id[value="' + producto.product_id + '"]').closest(
    "tr"
  );

  var precio = table.find(".precio-prod-real-no-cambio").val();

  var total_descuento = (parseFloat(precio) * descuento) / 100;

  var total = precio - total_descuento;

  table.find(".precio-prod-real").val(total);

  table.find(".precio-prod-real").attr("data-promocion", 1);

  table.find(".precio-prod").text(total);
  // alert("aquive procesarDescuento");
}

function removerDescuento(producto) {
  // alert("aquive removerDescuento");
  var table = $('.product_id[value="' + producto.product_id + '"]').closest(
    "tr"
  );

  var attr = table.find(".precio-prod-real").attr("data-promocion");

  if (typeof attr !== typeof undefined && attr !== false) {
    var precio = table.find(".precio-prod-real-no-cambio").val();

    precio = parseFloat(precio);

    table.find(".precio-prod-real").val(precio);

    table.find(".precio-prod-real").removeAttr("data-promocion");

    table.find(".precio-prod").text(precio);
  }
}

function facturasTable() {
  // Recorrer productos de la venta

  productos_list = new Array();

  productos_promo = new Array();

  if ($("#promociones").is(":visible") && $("#promocion").val() > 0) {
    var id_promocion = $("#promocion").val();

    // alert("promociones");
    $(".title-detalle").each(function(x) {
      var descuento = 0;
      var porcentaje_descuentop = 0;

      if (
        $(".precio-prod-descuento")
          .eq(x)
          .val() > 0
      ) {
        //calculando porcentaje general
        if (descuentogeneral != 0) {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-descuento")
              .eq(x)
              .val();
        } else {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
        }

        //calculando porcentaje interno
        if (
          $(".precio-prod-real")
            .eq(x)
            .val() != 0
        ) {
          descuentopi =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
          porcentaje_descuentop =
            parseFloat(descuentopi * 100) /
            parseFloat(
              $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            );
        } else {
          porcentaje_descuentop = 0;
        }
      }

      //descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

      if (
        parseInt(
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val()
        ) <
        parseInt(
          $(".precio-prod-real")
            .eq(x)
            .val()
        )
      )
        descuento = 0;

      productos_list[x] = {
        codigo: $(".codigo-final")
          .eq(x)
          .val(),

        precio_venta:
          descuento != 0
            ? $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            : $(".precio-prod-real")
                .eq(x)
                .val(),

        unidades: parseFloat(
          $(".cantidad")
            .eq(x)
            .text()
        ),

        impuesto: $(".impuesto-final")
          .eq(x)
          .val(),

        nombre_producto: $(".title-detalle")
          .eq(x)
          .text(),

        product_id: $(".product_id")
          .eq(x)
          .val(),

        descuento: descuento,

        porcentaje_descuentop: porcentaje_descuentop,

        margen_utilidad:
          ($(".precio-prod-real")
            .eq(x)
            .val() -
            $(".precio-compra-real-selected")
              .eq(x)
              .val()) *
          parseInt(
            $(".cantidad")
              .eq(x)
              .text()
          )
      };
    });

    /*$.each(productos_list, function(i, e)

        {

            if(validarProductoPromocion(id_promocion, e.product_id))

            {

                productos_promo.push(e);

            }

        });*/

    detalle = obtenerDetallePromocion(id_promocion);

    promocion = obtenerPromocion(id_promocion);

    /*productos_promo.sort(

            function(a, b){

                if (a.precio_venta < b.precio_venta)

                    return 1;

                else if (a.precio_venta > b.precio_venta)

                    return -1;

                else 

                    return 0;

            }

        );*/

    //validar y aplicar promoción

    switch (promocion.tipo) {
      case "progresivo":
        break;

      case "individual":
        break;

      case "cantidad":
        //  alert("en cantidad");
        var descuento_general = 0;

        var unidades = 0;

        $.each(productos_promo, function(i, e) {
          unidades += e.unidades;
        });

        var cantidad_para_descuento = 0;

        $.each(detalle, function(i, e) {
          cantidad_para_descuento = e.cantidad;

          //console.log(unidades, cantidad_para_descuento, unidades >= cantidad_para_descuento);

          if (unidades >= cantidad_para_descuento) {
            descuento_general = e.descuento;
          }

          //console.log(descuento_general+"descuento");
        });

        $.each(productos_promo, function(i, e) {
          procesarDescuento(e, descuento_general);
        });

        break;
    }
  } else {
    //alert("enelese");
    $(".title-detalle").each(function(x) {
      var descuento = 0;
      var porcentaje_descuentop = 0;

      if (
        $(".precio-prod-descuento")
          .eq(x)
          .val() > 0
      ) {
        //calculando porcentaje general
        if (descuentogeneral != 0) {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-descuento")
              .eq(x)
              .val();
        } else {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
        }

        //calculando porcentaje interno
        if (
          $(".precio-prod-real")
            .eq(x)
            .val() != 0
        ) {
          descuentopi =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
          porcentaje_descuentop =
            parseFloat(descuentopi * 100) /
            parseFloat(
              $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            );
        } else {
          porcentaje_descuentop = 0;
        }
      }

      //descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();
      if (
        parseInt(
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val()
        ) <
        parseInt(
          $(".precio-prod-real")
            .eq(x)
            .val()
        )
      )
        descuento = 0;

      productos_list[x] = {
        codigo: $(".codigo-final")
          .eq(x)
          .val(),

        precio_venta:
          descuento != 0
            ? $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            : $(".precio-prod-real")
                .eq(x)
                .val(),

        unidades: parseFloat(
          $(".cantidad")
            .eq(x)
            .text()
        ),

        impuesto: $(".impuesto-final")
          .eq(x)
          .val(),

        nombre_producto: $(".title-detalle")
          .eq(x)
          .text(),

        product_id: $(".product_id")
          .eq(x)
          .val(),

        descuento: descuento,

        porcentaje_descuentop: porcentaje_descuentop,

        margen_utilidad:
          ($(".precio-prod-real")
            .eq(x)
            .val() -
            $(".precio-compra-real-selected")
              .eq(x)
              .val()) *
          parseInt(
            $(".cantidad")
              .eq(x)
              .text()
          )
      };
    });

    $.each(productos_list, function(i, e) {
      removerDescuento(e);
    });
  }
}

var cantidadDescuento = 0;

function pasarPromocion() {
  // Recorrer productos de la venta

  productos_list = new Array();

  productos_promo = new Array();

  if ($("#promociones").is(":visible") && $("#promocion").val() > 0) {
    var id_promocion = $("#promocion").val();

    $(".title-detalle").each(function(x) {
      var descuento = 0;
      var porcentaje_descuentop = 0;

      if (
        $(".precio-prod-descuento")
          .eq(x)
          .val() > 0
      ) {
        //calculando porcentaje general
        if (descuentogeneral != 0) {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-descuento")
              .eq(x)
              .val();
        } else {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
        }

        //calculando porcentaje interno
        if (
          $(".precio-prod-real")
            .eq(x)
            .val() != 0
        ) {
          descuentopi =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
          porcentaje_descuentop =
            parseFloat(descuentopi * 100) /
            parseFloat(
              $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            );
        } else {
          porcentaje_descuentop = 0;
        }
      }

      //descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

      if (
        parseInt(
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val()
        ) <
        parseInt(
          $(".precio-prod-real")
            .eq(x)
            .val()
        )
      )
        descuento = 0;

      productos_list[x] = {
        codigo: $(".codigo-final")
          .eq(x)
          .val(),

        precio_venta:
          descuento != 0
            ? $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            : $(".precio-prod-real")
                .eq(x)
                .val(),

        unidades: parseFloat(
          $(".cantidad")
            .eq(x)
            .text()
        ),

        impuesto: $(".impuesto-final")
          .eq(x)
          .val(),

        nombre_producto: $(".title-detalle")
          .eq(x)
          .text(),

        product_id: $(".product_id")
          .eq(x)
          .val(),

        descuento: descuento,

        porcentaje_descuentop: porcentaje_descuentop,

        margen_utilidad:
          ($(".precio-prod-real")
            .eq(x)
            .val() -
            $(".precio-compra-real-selected")
              .eq(x)
              .val()) *
          parseInt(
            $(".cantidad")
              .eq(x)
              .text()
          )
      };
    });

    $.each(productos_list, function(i, e) {
      if (validarProductoPromocion(id_promocion, e.product_id)) {
        productos_promo.push(e);
      }
    });

    detalle = obtenerDetallePromocion(id_promocion);

    // console.log("------");

    // console.log(detalle);

    // console.log("------");

    //console.log("pasarPromocion"+promocion);
    switch (promocion.tipo) {
      case "progresivo":
      case "":
        if (detalle[0]["descuento"] == 1) {
          var cantidadPDescuento = parseInt(detalle[0]["cantidad"]), //cantidad para el obsequio, debo comprar
            cantidadDDescuento = parseInt(detalle[0]["producto_pos"]); //cantidad de obsequio

          $("#productos-detail")
            .find("tr")
            .each(function(i, e) {
              if (
                validarProductoPromocion(
                  id_promocion,
                  $(e)
                    .find(".product_id")
                    .val()
                )
              ) {
                $("input.precio-prod-real")
                  .eq(i)
                  .attr("data-promocion", "1");

                cantidad = $(e)
                  .find("span.cantidad")
                  .html();

                cantidadPagar = 0;

                function recorrerCantidad(cantidadFaltante) {
                  var cantidadContador = cantidadFaltante;

                  for (i = 1; i <= cantidadFaltante; i++) {
                    if (i <= cantidadPDescuento) {
                      cantidadPagar++;

                      cantidadContador--;
                    } else if (i <= cantidadPDescuento + cantidadDDescuento) {
                      cantidadContador--;
                    } else if (i >= cantidadPDescuento + cantidadDDescuento) {
                      recorrerCantidad(cantidadContador);

                      break;
                    }
                  }
                }

                recorrerCantidad(cantidad);

                numeroDescuento =
                  parseInt(cantidad / cantidadPDescuento) * cantidadDDescuento;

                impuesto = $(e)
                  .find("input.impuesto-final")
                  .val();

                precio = $(e)
                  .find("input.precio-prod-real-no-cambio")
                  .val();

                precioTotal = cantidadPagar * precio;

                ivaCantidad = ((precio * impuesto) / 100) * cantidadPagar;

                $(e)
                  .find("input.promocionPrecio")
                  .val(precioTotal);

                $(e)
                  .find(".promocionIva")
                  .val(ivaCantidad);
              }
            });
        } else if (detalle[0]["descuento"] == 0) {
          var cantidadPDescuento = parseInt(detalle[0]["cantidad"]), //cantidad para obsequio
            cantidadDDescuento = parseInt(detalle[0]["producto_pos"]); //cantidad de obsequio
          cantidadPPP = 0;
          colaProductos = [];
          colaProductosobsequios = [];

          $("#productos-detail")
            .find("tr")
            .each(function(i, e) {
              //$(e).find('span.cantidad').attr("data-cantidad",cant);  *
              tipo = 0;
              //producto compra
              var productoValidado = validarProductoPromocion(
                id_promocion,
                $(e)
                  .find(".product_id")
                  .val()
              );
              //producto obsequio
              var productoValidadoD = validarProductoPromocionD(
                id_promocion,
                $(e)
                  .find(".product_id")
                  .val()
              );

              if (productoValidado == true && productoValidadoD == false) {
                //producto que es solo de compra
                $("input.precio-prod-real")
                  .eq(i)
                  .attr("data-promocion", "1");
                tipo = 1;
              } else if (
                productoValidado == false &&
                productoValidadoD == true
              ) {
                //producto que es solo de obsequio
                $("input.precio-prod-real")
                  .eq(i)
                  .attr("data-promocion", "2");
                tipo = 2;
              } else if (
                productoValidado == true &&
                productoValidadoD == true
              ) {
                //producto de compra y obsequio
                tipo = 3;
                $("input.precio-prod-real")
                  .eq(i)
                  .attr("data-promocion", "3");
              }
              cant = parseInt(
                $(e)
                  .find("span.cantidad")
                  .html()
              );

              for (i = 0; i < cant; i++) {
                impuesto = $(e)
                  .find("input.impuesto-final")
                  .val();
                precio = $(e)
                  .find("input.precio-prod-real-no-cambio")
                  .val();

                temporal = [];
                temporal["id"] = $(e)
                  .find(".product_id")
                  .val();
                temporal["tipo"] = tipo;
                temporal["cant"] = 1;
                temporal["cantoriginal"] = cant;
                temporal["precio"] = precio;
                temporal["impuesto"] = impuesto;
                temporal["precioTotal"] = precio;
                temporal["ivaCantidad"] = ((precio * impuesto) / 100) * cant;
                colaProductos.push(temporal);
              }
            });

          colaProductos.sort(function(a, b) {
            return a.tipo > b.tipo ? 1 : b.tipo > a.tipo ? -1 : 0;
          });
          regalosPendientes = 0;
          contadorProductosParticipantes = 0;

          for (i = 0; i < colaProductos.length; i++) {
            switch (colaProductos[i].tipo) {
              case 1:
                contadorProductosParticipantes++;
                if (contadorProductosParticipantes == cantidadPDescuento) {
                  regalosPendientes += cantidadDDescuento;
                  contadorProductosParticipantes = 0;
                }
                break;

              case 2:
                if (regalosPendientes > 0) {
                  colaProductos[i].cant = 0;
                  regalosPendientes--;
                } else {
                  colaProductosobsequios.push(colaProductos[i].id);
                }
                break;
              case 3:
                if (regalosPendientes > 0) {
                  if (colaProductosobsequios.length > 0) {
                    colaProductosobsequios.forEach(function(
                      elemento,
                      indice,
                      array
                    ) {
                      for (k = 0; k < colaProductos.length; k++) {
                        if (
                          elemento == colaProductos[k].id &&
                          regalosPendientes > 0 &&
                          colaProductos[k].cant != 0
                        ) {
                          colaProductos[k].cant = 0;
                          regalosPendientes--;
                          colaProductosobsequios.pop(indice);
                          break;
                        }
                      }
                    });
                  } else {
                    colaProductos[i].cant = 0;
                    regalosPendientes--;
                  }
                } else {
                  contadorProductosParticipantes++;
                  if (contadorProductosParticipantes == cantidadPDescuento) {
                    regalosPendientes += cantidadDDescuento;
                    contadorProductosParticipantes = 0;
                  }
                  if (regalosPendientes > 0) {
                    if (colaProductosobsequios.length > 0) {
                      colaProductosobsequios.forEach(function(
                        elemento,
                        indice,
                        array
                      ) {
                        for (k = 0; k < colaProductos.length; k++) {
                          if (
                            elemento == colaProductos[k].id &&
                            regalosPendientes > 0 &&
                            colaProductos[k].cant != 0
                          ) {
                            colaProductos[k].cant = 0;
                            regalosPendientes--;
                            colaProductosobsequios.pop(indice);
                            break;
                          }
                        }
                      });
                    }
                  }
                }
                break;
            }
          }
          colaProductosFinales = [];

          // alert("aqui uno solo");
          for (i = 0; i < colaProductos.length; i++) {
            band = true;
            if (colaProductosFinales.length > 0) {
              for (k = 0; k < colaProductosFinales.length; k++) {
                //alert("colaProductosFinales[k].id="+colaProductosFinales[k].id+" colaProductos[i].id="+colaProductos[i].id);
                if (colaProductosFinales[k].id == colaProductos[i].id) {
                  //  alert("asigno cant="+colaProductosFinales[k].cant+" + colaProductos[i].cant="+colaProductos[i].cant);
                  colaProductosFinales[k].cant += colaProductos[i].cant;
                  //  alert("asigne y quedo en="+colaProductosFinales[k].cant);
                  band = false;
                  break;
                }
              }
              if (band) {
                temporal = [];
                temporal["id"] = colaProductos[i].id;
                temporal["tipo"] = colaProductos[i].tipo;
                temporal["cant"] = colaProductos[i].cant;
                temporal["cantoriginal"] = colaProductos[i].cantoriginal;
                temporal["precio"] = colaProductos[i].precio;
                temporal["impuesto"] = colaProductos[i].impuesto;
                temporal["precioTotal"] = colaProductos[i].precioTotal;
                temporal["ivaCantidad"] = colaProductos[i].ivaCantidad;
                colaProductosFinales.push(temporal);
                band = false;
              }
            } else {
              temporal = [];
              temporal["id"] = colaProductos[i].id;
              temporal["tipo"] = colaProductos[i].tipo;
              temporal["cant"] = colaProductos[i].cant;
              temporal["cantoriginal"] = colaProductos[i].cantoriginal;
              temporal["precio"] = colaProductos[i].precio;
              temporal["impuesto"] = colaProductos[i].impuesto;
              temporal["precioTotal"] = colaProductos[i].precioTotal;
              temporal["ivaCantidad"] = colaProductos[i].ivaCantidad;
              colaProductosFinales.push(temporal);
            }
          }

          /*cantidadTotalPromocion=0;
                    cantidadPagar = 0;
                    obsequio1=[];
                    cantidadProductotipo1=0;
                    cantidadProductotipo2=0;
                    cantidadPDescuentoantes=0;*/

          if (colaProductosFinales.length > 0) {
            for (i = 0; i < colaProductosFinales.length; i++) {
              $("#productos-detail tr#" + colaProductosFinales[i].id).each(
                function() {
                  cantidad = colaProductosFinales[i].cant;
                  impuesto = colaProductosFinales[i].impuesto;
                  precio = colaProductosFinales[i].precio;
                  precioTotal = cantidad * precio;
                  ivaCantidad = ((precio * impuesto) / 100) * cantidad;
                  $(this)
                    .find("input.promocionPrecio")
                    .val(precioTotal);
                  $(this)
                    .find(".promocionIva")
                    .val(ivaCantidad);

                  switch (colaProductosFinales[i].tipo) {
                    case 2:
                      $(this)
                        .find("input[data-promocion=2]")
                        .attr("data-cantidad", cantidad);
                      break;
                    case 3:
                      $(this)
                        .find("input[data-promocion=3]")
                        .attr("data-cantidad", cantidad);
                      break;
                  }
                }
              );
            }
          }

          //***************************** */
          /*

                    for (i = 0; i < obsequio1.length; i++) {                             
                        $('#productos-detail tr#'+obsequio1[i].id).each(function()
                        {
                            cantidad = obsequio1[i].cantidad;                                
                            //cantidadPPP += cantidad;   
                            impuesto = obsequio1[i].impuesto;        
                            precio = obsequio1[i].precio;       
                            cantidadPagar =cantidad * precio;  
                            precioTotal = cantidad * precio;        
                            ivaCantidad = ((precio * impuesto) /100)*(cantidad);        
                            $(this).find('input.promocionPrecio').val(precioTotal);        
                            $(this).find('.promocionIva').val(ivaCantidad);   

                            if($(this).find('input[data-promocion]').data('promocion')==3){
                                $(this).find('input[data-promocion=3]').attr('data-cantidad',cantidad);
                            }else{
                                $(this).find('input[data-promocion=2]').attr('data-cantidad',cantidad);
                            }  

                            
                              
                        });     
                    }*/

          /********************************/

          /*
                    $('#productos-detail tr').each(function(i,e)
                    {          
                                      
                        cantidad = parseInt($(e).find('span.cantidad').attr("data-cantidad"));                   
                        //cantidadProductotipo1+=parseInt(cantidad); 
                        //cantidadPPP += parseInt(cantidad);
                        //cantidadPagar = parseInt(cantidadPagar) + parseInt(cantidad);
                        impuesto = $(e).find('input.impuesto-final').val();
                        precio = $(e).find('input.precio-prod-real-no-cambio').val();
                        precioTotal = (cantidad) * precio;
                        ivaCantidad = (precio * impuesto /100)*(cantidad);
                      
                        $(e).find('input.promocionPrecio').val(precioTotal);
                        $(e).find('.promocionIva').val(ivaCantidad);
                        //cantidadTotalPromocion+=parseInt(cantidad);
                    });
                   */

          /*$('#productos-detail').find('input[data-promocion=2],input[data-promocion=3]').parents('tr').each(function(i,e)
                    {
                        cantidad = parseInt($(e).find('span.cantidad').html());    
                        temporal=[];
                        temporal['id']=$(e).find('.product_id').val();
                        temporal['cantidad']=cantidad;
                        temporal['impuesto']=$(e).find('input.impuesto-final').val();
                        temporal['precio']=$(e).find('input.precio-prod-real-no-cambio').val();                                         
                                           
                        if($(e).find('input[data-promocion]').data('promocion')==3){
                            temporal['tipoproductopromocion']=3;                            
                            cantidadTotalPromocion+=cantidad;
                        }else{
                            temporal['tipoproductopromocion']=2;
                            cantidadProductotipo2+=parseInt(cantidad);
                        }                        
                        obsequio1.push(temporal);    
                      
                    });       */
          /*
                    if(cantidadPDescuento==1){                    
                        cantidadPDescuentoantes=1;
                      //  cantidadPDescuento=2;                        
                   }

                    cantidadProductotipo3 = cantidadTotalPromocion - cantidadProductotipo1;                    
                    regaloparte1=Math.trunc(cantidadProductotipo1/cantidadPDescuento);
                    restanteproductostipo1 = (cantidadProductotipo1%cantidadPDescuento);
                    if(cantidadTotalPromocion>=cantidadPDescuento){                        
                    }else{
                        if((cantidadTotalPromocion<cantidadPDescuento)&&(cantidadPDescuentoantes==1)){
                            cantidadProductotipo2=1;
                        }else{
                            cantidadProductotipo2=0;
                        }
                        
                    }*/
          /*restanteproductostipo3 =cantidadProductotipo3- regaloparte1+cantidadProductotipo2;
                    regaloparte2=Math.trunc((restanteproductostipo1+restanteproductostipo3)/cantidadPDescuento);
                    console.log("restanteproductostipo1="+restanteproductostipo1+"restanteproductostipo3="+restanteproductostipo3);
                    if((cantidadTotalPromocion!=0)){
                        car=regaloparte1+regaloparte2;
                    }else{
                        car=0;
                    }
                   
                    //console.log("car="+car);
                    //car=Math.trunc((cantidadTotalPromocion/cantidadPDescuento)*cantidadDDescuento);                      
                    //reordenamos el arreglo de productos para asegurar que se descuenten primero los de tipo 2 (solo obsequio)
                    obsequio1.sort(function(a,b) {return (a.tipoproductopromocion > b.tipoproductopromocion) ? 1 : ((b.tipoproductopromocion > a.tipoproductopromocion) ? -1 : 0);} );

                    if(obsequio1.length>0){ 
                        if(car>0){
                            cantidadproductosregalados=0;                           
                            for (i = 0; i < obsequio1.length; i++) {  
                                if((obsequio1[i].tipoproductopromocion==2)&&(cantidadproductosregalados!=car)){
                                   
                                    if(obsequio1[i].cantidad >= (car - cantidadproductosregalados)){  
                                        //los regalé todos  
                                        //console.log("todos2");                                     
                                        obsequio1[i].cantidad = obsequio1[i].cantidad - (car - cantidadproductosregalados);
                                        break;                            
                                    }
                                    else{   
                                       // console.log("uno2");            
                                        cantidadproductosregalados += obsequio1[i].cantidad; 
                                        cantidadTotalPromocion -= cantidadPDescuento*obsequio1[i].cantidad;     
                                        obsequio1[i].cantidad = 0;
                                    }
                                }
                                
                                if((obsequio1[i].tipoproductopromocion==3)&&(cantidadproductosregalados!=car)){                                                               
                                    if(((cantidadTotalPromocion-cantidadProductotipo3)>=cantidadPDescuento)||((cantidadProductotipo3/(car - cantidadproductosregalados))>cantidadPDescuento || ((cantidadProductotipo3 > cantidadPDescuento) && ((cantidadProductotipo3 % cantidadPDescuento) == 0 )))||((cantidadProductotipo3+cantidadProductotipo1)>(cantidadPDescuento)) ||(cantidadPDescuentoantes==1)){
                                        if(obsequio1[i].cantidad >= (car - cantidadproductosregalados)){                                            
                                            if((cantidadProductotipo3 > cantidadPDescuento) && ((cantidadProductotipo3 % cantidadPDescuento) == 0 )){ 
                                                if((cantidadPDescuentoantes==1)){
                                                //    console.log("todos1");                                               
                                                    obsequio1[i].cantidad = obsequio1[i].cantidad - (car - cantidadproductosregalados);
                                                    break;  
                                                }else{
                                                    obsequio1[i].cantidad -= (obsequio1[i].cantidad / cantidadPDescuento) - 1 ;
                                                  //  console.log("uno"); 
                                                }                              
                                            }else{                                              
                                                     //los regalé todos   
                                                   //  console.log("todos");                                               
                                                    obsequio1[i].cantidad = obsequio1[i].cantidad - (car - cantidadproductosregalados);
                                                    break;                                                                                             
                                            }                              
                                        }
                                        else{
                                          //  console.log("else"); 
                                            cantidadproductosregalados+=obsequio1[i].cantidad;  
                                            cantidadTotalPromocion-= cantidadPDescuento*obsequio1[i].cantidad;        
                                            obsequio1[i].cantidad = 0;                                            
                                        }  
                                        cantidadTotalPromocion-= cantidadPDescuento;
                                    }                              
                                }
                            } 
                        }     
                        
                        for (i = 0; i < obsequio1.length; i++) {                             
                            $('#productos-detail tr#'+obsequio1[i].id).each(function()
                            {
                                cantidad = obsequio1[i].cantidad;                                
                                //cantidadPPP += cantidad;   
                                impuesto = obsequio1[i].impuesto;        
                                precio = obsequio1[i].precio;       
                                cantidadPagar =cantidad * precio;  
                                precioTotal = cantidad * precio;        
                                ivaCantidad = ((precio * impuesto) /100)*(cantidad);        
                                $(this).find('input.promocionPrecio').val(precioTotal);        
                                $(this).find('.promocionIva').val(ivaCantidad);   

                                if($(this).find('input[data-promocion]').data('promocion')==3){
                                    $(this).find('input[data-promocion=3]').attr('data-cantidad',cantidad);
                                }else{
                                    $(this).find('input[data-promocion=2]').attr('data-cantidad',cantidad);
                                }  

                                
                                  
                            });     
                        }              
                    } */
          /*
                    cantidadPagar = 0;

                    var cantidad_total = 0;
                    var cantidad_obsequio = 0;

                    $('#productos-detail').find('input[data-promocion=1]').parents('tr').each(function(i,e)

                    {

                        cantidad = $(e).find('span.cantidad').html();
                        cantidad_total += parseInt(cantidad);
                        cantidadPPP += parseInt(cantidad);

                        cantidadPagar = parseInt(cantidadPagar) + parseInt(cantidad);

                        impuesto = $(e).find('input.impuesto-final').val();

                        precio = $(e).find('input.precio-prod-real-no-cambio').val();

                        precioTotal = (cantidad) * precio;

                        ivaCantidad = (precio * impuesto /100)*(cantidad);

                        $(e).find('input.promocionPrecio').val(precioTotal);

                        $(e).find('.promocionIva').val(ivaCantidad);
                      

                    });

                    

                    cantidadPPP = parseInt(cantidadPPP/cantidadPDescuento) * cantidadDDescuento;

                     console.log("La cantidad inicial de PPP es : "+cantidadPPP);                      

                    $('#productos-detail').find('input[data-promocion=2]').parents('tr').each(function(i,e)

                    {
                        cantidad = $(e).find('span.cantidad').html();
                        impuesto = $(e).find('input.impuesto-final').val();
                        precio = $(e).find('input.precio-prod-real-no-cambio').val();
                        cantidad_total += parseInt(cantidad); 

                        if(cantidadPPP > 0)
                        {   
                            cantidadDescuentos = parseInt(cantidadPagar / cantidadPDescuento)* cantidadDDescuento;
                          // alert(cantidadPPP + " - "+ cantidad);
                           if(cantidadPPP >= cantidad)
                            {
                                precioTotal = 0;
                                ivaCantidad = 0;
                                $(e).find('input.promocionPrecio').val(precioTotal);
                                $(e).find('.promocionIva').val(ivaCantidad);
                                cantidadPagar = 0;    
                            }else
                            {
                                precioTotal = (cantidad-cantidadPPP) * precio;
                                ivaCantidad = (precio * impuesto /100)*(cantidad-cantidadPPP);
                                cantidadPagar = cantidad- cantidadPPP;
                                //alert("cantidad a pagar "+cantidadPagar);
                                if(cantidadPagar < 0)
                                {
                                    cantidadPagar = cantidadPPP - cantidad;
                                }
                            } 
                           
                        }else
                        {

                            precioTotal = (cantidad) * precio;
                            ivaCantidad = (precio * impuesto /100)*(cantidad);
                            cantidadPagar = cantidad;
                        }

                        
                        
                        $(e).find('input.promocionPrecio').val(precioTotal);

                        $(e).find('.promocionIva').val(ivaCantidad);

                        $(e).find('input[data-promocion=2]').attr('data-cantidad',cantidadPagar);
                        console.log("Promocion 2");
                        console.log("Cantidad : "+cantidad);
                        console.log("CantidadPPP : "+cantidadPPP);
                        console.log("Cantidad a pagar"+cantidadPagar);
                        console.log("Precio total"+precioTotal);

                        cantidadPagar = cantidad- cantidadPPP;
                        cantidadPPP  -= cantidad;

                        console.log("Cantidad : "+cantidad);
                        console.log("CantidadPPP : "+cantidadPPP);
                        console.log("Cantidad a pagar"+cantidadPagar);
                        console.log("Precio total"+precioTotal);
                        
                        

                    });

                    

                    //contar lo productos

                   $('#productos-detail').find('input[data-promocion=3]').parents('tr').each(function(i,e)

                    {

                        //parte 1
                        

                        cantidad = $(e).find('span.cantidad').html();
                        cantidad_total+=parseInt(cantidad);
                       // cantidadPPP += parseInt(cantidad);
                      
                        console.log("Promocion 3");
                        console.log("Cantidad : "+cantidad);
                        console.log("CantidadPPP : "+cantidadPPP);
                        console.log("Cantidad a pagar"+cantidadPagar);
                        console.log("Precio total"+precioTotal);
                    });

                                       

                    cantidadPagar = 0;

                    function recorrerCantidad(cantidadFaltante)

                    {

                        var cantidadContador = cantidadFaltante;
                        for(i=1;i<=cantidadFaltante;i++)

                        {   

                            if(i<=cantidadPDescuento)

                            {

                               cantidadPagar++; 

                               cantidadContador--;

                            }else if(i<=cantidadPDescuento+cantidadDDescuento)

                            {

                                cantidadContador--;

                            }else if(i >= cantidadPDescuento+cantidadDDescuento)

                            {

                                recorrerCantidad(cantidadContador);

                                break;

                            }

                        }

                    }

                    

                    recorrerCantidad(cantidadPPP);

                    cantidadPPP = cantidadPPP-cantidadPagar;
                    console.log("Cantidad PPP"+cantidadPPP);
                    console.log("Cantidad Pagar"+cantidadPagar);
                                        

                    //aplicar promocion
                    var cantidadTotal = parseInt($("#cantidad-total").html());
                    $('#productos-detail').find('input[data-promocion=3]').parents('tr').each(function(i,e)
                    {
                        
                        ///parte 2

                        cantidad = parseInt($(e).find('span.cantidad').html());
                        impuesto = $(e).find('input.impuesto-final').val();
                        precio = $(e).find('input.precio-prod-real-no-cambio').val();
                        console.log("CantidadPP de llegada :" + cantidadPPP);
                        
                        

                        if((cantidad_total) > cantidadPDescuento){
                            cantidadPPP = 1;                            
                        }
                        console.log("Cantidad :" + (cantidadTotal) + " Cantidad a vender : "+cantidadPDescuento + "Promocion aplicada" + cantidadPPP);
                        
                        if(cantidadPPP > 0)// && cantidadPPP <= parseInt(cantidad / cantidadPDescuento))
                        {   
                            if(cantidad == 1){
                                precioTotal = 0;
                                ivaCantidad = 0;
                                $(e).find('input.promocionPrecio').val(precioTotal);
                                $(e).find('.promocionIva').val(ivaCantidad);
                                cantidadPagar = 0;  
                            }

                            //if(cantidadPPP >= cantidad )
                         //   {
                         //       precioTotal = 0;
                         //       ivaCantidad = 0;
                         //       $(e).find('input.promocionPrecio').val(precioTotal);
                         //       $(e).find('.promocionIva').val(ivaCantidad);
                         //       cantidadPagar = 0;    
                          //  }
                            else
                            {
                                precioTotal = (cantidad-cantidadPPP) * precio;
                                ivaCantidad = (precio * impuesto /100)*(cantidad-cantidadPPP);
                                console.log(precioTotal+"precioTotal",ivaCantidad+"ivaCantidad");
                                cantidadPagar = cantidad- cantidadPPP;
                                if(cantidadPagar < 0)
                                {
                                    cantidadPagar = cantidadPPP - cantidad;
                                }
                            }

                        }else

                        {

                            precioTotal = (cantidad) * precio;

                            ivaCantidad = (precio * impuesto /100)*(cantidad);

                            cantidadPagar = cantidad;

                        }

                        $(e).find('input.promocionPrecio').val(precioTotal);

                        $(e).find('.promocionIva').val(ivaCantidad);

                        $(e).find('input[data-promocion=3]').attr('data-cantidad',cantidadPagar);

                        console.log("------cantidadPPP"+cantidadPPP);

                        cantidadPPP -= cantidad;

                        

                    });*/
        }

        break;

      case "individual":
        break;

      //---//////promocion cantidad--

      case "cantidad":
        var descuento_general = 0;

        var unidades = 0;
        // alert("cantidad33");

        $.each(productos_promo, function(i, e) {
          unidades += e.unidades;
        });

        var cantidad_para_descuento = 0;

        $.each(detalle, function(i, e) {
          cantidad_para_descuento = e.cantidad * 1;

          if (unidades >= cantidad_para_descuento) {
            descuento_general = e.descuento;
          }
        });

        descuentoPorcentajePromocion = descuento_general;

        var precioTotal = 0,
          ivaCantidad = 0,
          idAnterior = 0;

        $.each(productos_promo, function(i, e) {
          if (idAnterior != e.product_id) {
            precioTotal = 0;

            ivaCantidad = 0;
          }

          var table = $('.product_id[value="' + e.product_id + '"]').closest(
            "tr"
          );

          var precio = table.find(".precio-prod-real-no-cambio").val();

          var cantidad = limpiarCampo(table.find(".cantidad").text());

          var total_descuento = (precio / 100) * descuento_general * cantidad;

          var total = precio * cantidad - total_descuento,
            impuesto = table.find(".impuesto-final").val();

          //console.log(total_descuento+"TD-P"+precio+"--DG"+descuento_general+"--C"+cantidad+"--T"+total);

          precioTotal = total + precioTotal;

          //console.log("PT"+precioTotal+"--");

          ivaCantidad = (precioTotal / 100) * impuesto;

          table.find(".precio-prod-real").attr("data-promocion", 1);

          table.find("input.promocionPrecio").val(precioTotal);

          table.find(".promocionIva").val(ivaCantidad);

          //procesarDescuento(e, descuento_general);

          idAnterior = e.product_id;
          preciodescuentocant = total_descuento / cantidad;
          table.find(".precio-prod-real").val(precioTotal / cantidad);
          table.find(".precio-prod-descuento").val(precioTotal / cantidad);
        });

        break;
    }

    promocion = obtenerPromocion(id_promocion);

    productos_promo.sort(function(a, b) {
      if (a.precio_venta < b.precio_venta) return 1;
      else if (a.precio_venta > b.precio_venta) return -1;
      else return 0;
    });
  } else {
    $(".title-detalle").each(function(x) {
      var descuento = 0;
      var porcentaje_descuentop = 0;

      if (
        $(".precio-prod-descuento")
          .eq(x)
          .val() > 0
      ) {
        //calculando porcentaje general
        if (descuentogeneral != 0) {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-descuento")
              .eq(x)
              .val();
        } else {
          descuento =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
        }

        //calculando porcentaje interno
        if (
          $(".precio-prod-real")
            .eq(x)
            .val() != 0
        ) {
          descuentopi =
            $(".precio-prod-real-no-cambio")
              .eq(x)
              .val() -
            $(".precio-prod-real")
              .eq(x)
              .val();
          porcentaje_descuentop =
            parseFloat(descuentopi * 100) /
            parseFloat(
              $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            );
        } else {
          porcentaje_descuentop = 0;
        }
      }

      // descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

      if (
        parseInt(
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val()
        ) <
        parseInt(
          $(".precio-prod-real")
            .eq(x)
            .val()
        )
      )
        descuento = 0;

      productos_list[x] = {
        codigo: $(".codigo-final")
          .eq(x)
          .val(),

        precio_venta:
          descuento != 0
            ? $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            : $(".precio-prod-real")
                .eq(x)
                .val(),

        unidades: parseFloat(
          $(".cantidad")
            .eq(x)
            .text()
        ),

        impuesto: $(".impuesto-final")
          .eq(x)
          .val(),

        nombre_producto: $(".title-detalle")
          .eq(x)
          .text(),

        product_id: $(".product_id")
          .eq(x)
          .val(),

        descuento: descuento,

        porcentaje_descuentop: porcentaje_descuentop,

        margen_utilidad:
          ($(".precio-prod-real")
            .eq(x)
            .val() -
            $(".precio-compra-real-selected")
              .eq(x)
              .val()) *
          parseInt(
            $(".cantidad")
              .eq(x)
              .text()
          )
      };
    });

    $.each(productos_list, function(i, e) {
      removerDescuento(e);
    });
  }
}

$(".cantidad").live("click", function() {
  //console.log($(this));
  var id_producto = $(this)[0].dataset.id;
  var stock_selected = $(this)[0].dataset.stock;
  var negativo_selected = $(this)[0].dataset.vendernegativo;
  var tipo_producto_selected = $(this)[0].dataset.tipo_producto;
  var imei_selected = $(this)[0].dataset.imei;
  //alert("imei_selected="+imei_selected)

  if (imei_selected == "" || typeof imei_selected === "undefined") {
    cantidadField = $(this);
    propoverContent =
      "<div class='row'>" +
      "<div class='col-md-8'>" +
      "<input type='text' data-id='" +
      id_producto +
      "' data-stock='" +
      stock_selected +
      "' data-vendernegativo='" +
      negativo_selected +
      "' data-tipo_producto='" +
      tipo_producto_selected +
      "' class='spinner' name='cantidad_input' value='" +
      cantidadField.text() +
      "'/>" +
      "</div>" +
      "<div class='col-md-4'>" +
      "<button data-id='" +
      id_producto +
      "' data-stock='" +
      stock_selected +
      "' data-vendernegativo='" +
      negativo_selected +
      "' data-tipo_producto='" +
      tipo_producto_selected +
      "' type='button' id='btn-accept-cantidad' class='btn btn-success text-right' style='float:right;'><span class='icon-ok icon-white'></span></button>" +
      "</div>" +
      "</div>";

    cantidadField
      .popover({
        placement: "bottom",
        title: "Cantidad",
        html: true,
        content: propoverContent,
        trigger: "manual"
      })
      .popover("show");
    $(".spinner").spinner({
      min: 0
    });

    $("#btn-accept-cantidad").click(function(e) {
      //console.log($(this));

      var value_cant = parseFloat($(".spinner").val());
      //var stock_selected = $('.stock_selected').val();
      //var vendernegativo_selected = $('.vendernegativo_selected').val();
      var stock_selected = $(this)[0].dataset.stock;
      var vendernegativo_selected = $(this)[0].dataset.vendernegativo;
      var tipo_producto = $(this)[0].dataset.tipo_producto;
      var bandera = 1;
      //alert(vendernegativo_selected + " - " + value_cant + " - " + stock_selected);
      /*alert("tipo4");
            alert("vendernegativo_selected=" + vendernegativo_selected);
            alert("value_cant=" + value_cant);
            alert("stock_selected=" + stock_selected);*/
      if (vendernegativo_selected == 0 && value_cant > stock_selected) {
        if (tipo_producto == 3) {
          //verifico mis ingredientes si tengo disponible
          $.ajax({
            async: false, //mostrar variables fuera de el function
            url: $buscarstockespeciales,
            type: "POST",
            dataType: "json",
            data: {
              id: id_producto,
              tipo: tipo_producto,
              unidades: value_cant
            },
            success: function(data) {
              // console.log(data);
              if (data.success == 0) {
                /*swal(
                                    'Alerta',
                                    data.msj,
                                    'warning'
                                )*/
                bandera = 0;
                Swal({
                  title: "Alerta",
                  html: data.msj,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#5ca745",
                  cancelButtonColor: "#e62626",
                  confirmButtonText: "¿Deseas realizar la venta?",
                  cancelButtonText: "No vender"
                }).then(result => {
                  if (result.value) {
                    //si acepta vender
                    bandera = 1;

                    cantidadField.html($(".spinner").val());
                    cantidadField.popover("destroy");
                    pasarPromocion();
                    calculate();
                    actualizarEspera();
                  }
                });
              }
            }
          });
        } else {
          swal(
            "Alerta",
            "No posees stock para la venta de este producto",
            "warning"
          );
          bandera = 0;
        }
      }
      if (bandera == 1) {
        cantidadField.html($(".spinner").val());
        cantidadField.popover("destroy");
        pasarPromocion();
        calculate();
        actualizarEspera();
      }
    });

    $("input[name='cantidad_input']").keyup(function(e) {
      if (e.keyCode == 13) {
        var value_cant = parseFloat($(".spinner").val());
        //var stock_selected = $('.stock_selected').val();
        //var vendernegativo_selected = $('.vendernegativo_selected').val();

        var stock_selected = $(this)[0].dataset.stock;
        var vendernegativo_selected = $(this)[0].dataset.vendernegativo;
        var tipo_producto = $(this)[0].dataset.tipo_producto;
        bandera = 1;
        //alert(vendernegativo_selected + " - " + value_cant + " - " + stock_selected);
        //alert("tipo5");
        if (vendernegativo_selected == 0 && value_cant > stock_selected) {
          if (tipo_producto == 3) {
            //verifico mis ingredientes si tengo disponible
            $.ajax({
              async: false, //mostrar variables fuera de el function
              url: $buscarstockespeciales,
              type: "POST",
              dataType: "json",
              data: {
                id: id_producto,
                tipo: tipo_producto,
                unidades: value_cant
              },
              success: function(data) {
                // console.log(data);
                if (data.success == 0) {
                  /*swal(
                                        'Alerta',
                                        data.msj,
                                        'warning'
                                    )*/
                  bandera = 0;
                  Swal({
                    title: "Alerta",
                    html: data.msj,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#5ca745",
                    cancelButtonColor: "#e62626",
                    confirmButtonText: "¿Deseas realizar la venta?",
                    cancelButtonText: "No vender"
                  }).then(result => {
                    if (result.value) {
                      //si acepta vender
                      bandera = 1;
                      cantidadField.html($(".spinner").val());
                      cantidadField.popover("destroy");
                      pasarPromocion();
                      calculate();
                      actualizarEspera();
                    }
                  });
                }
              }
            });
          } else {
            swal(
              "Alerta",
              "No posees stock para la venta de este producto",
              "warning"
            );
            bandera = 0;
          }
        }
        if (bandera == 1) {
          cantidadField.html($(".spinner").val());

          cantidadField.popover("destroy");

          pasarPromocion();

          calculate();
          actualizarEspera();
        }
      }
    });
  } else {
    swal("Alerta", "Cantidad maxima seleccionada.", "warning");
  }
});

function calculadora_descuento(valor_precio_venta) {
  $(".precio-prod").live("click", function() {
    precioField = $(this);

    precioFieldReal = $(".precio-prod-real").eq(
      $(".precio-prod").index($(this))
    );

    precioProd = precioFieldReal.val();

    //console.log(precioProd);

    impuesto = $(".impuesto-final")
      .eq($(".precio-prod").index($(this)))
      .val();

    precioContent =
      "<div style='position: inherit; z-index: 99999999999999999'><form id='Calc'>" +
      "<div class='row'>" +
      "<div class='col-md-12' style='padding: 0px'>" +
      "<input type='text' value='' name='Input' class='form-control Input'/>" +
      "<input type='hidden' value='' name='Input1' class='Input1'/>" +
      "</div>" +
      "</div>" +
      "<div class='row'>" +
      "<div class='col-md-3 col-sn-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn one' value='1' name='one'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn two' value='2' name='two'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn three' value='3' name='three'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input class='btn sum' type='button' value='+' name='sum'/>" +
      "</div>" +
      "</div>" +
      "<div class='row'>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn four' value='4' name='four'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn five' value='5' name='five'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn six' value='6' name='six'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input class='btn rest' type='button' value='-' name='rest'/>" +
      "</div>" +
      "</div>" +
      "<div class='row'>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn seven' value='7' name='seven'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn eith' value='8' name='eith'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn nine' value='9' name='nine'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input class='btn porcen' type='button'  value='%' name='divi'/>" +
      "</div>" +
      "</div>" +
      "<div class='row'>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input class='btn cero' type='button'  value='0' name='cero'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input type='button' class='btn clear' value='C' name='clear'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3' style='padding: 2px !important;'>" +
      "<input class='btn doIt' type='button'  value='=' name='doIt'/>" +
      "</div>" +
      "<div class='col-md-3 col-sm-3 col-md-offset-3' style='padding: 2px !important;'>" +
      "</div>" +
      "</div>" +
      "<div class='row'>" +
      "<div class='col-md-8 col-md-offset-2' style='padding: 0px !important;'>" +
      "<button type='button' id='btn-accept-precio' style='width:100%' class='btn btn-success'>Enter</button>" +
      "</div>" +
      "</div>" +
      "</form></div>";

    precioField
      .popover({
        placement: "bottom",

        title: "Precio",

        html: true,

        content: precioContent,

        trigger: "manual"
      })
      .popover("show");

    $("#btn-accept-precio").click(function() {
      if ($(".Input").val() != "") {
        var valor = $(".Input").val();
        var val1 = valor.replace("%", "");
        var val2 = val1.replace(" ", "");

        if (__decimales__ == 0) {
          var precio = Math.round(valor_precio_venta);
        } else {
          var precio = parseFloat(valor_precio_venta);
        }

        var primer = $(".Input").val();
        var res1 = primer.replace(/[1234567890]/gi, "");

        if (res1 == " % " || $(".Input1").val() == "porcentaje") {
          if (__decimales__ == 0) {
            var resultado_porcen1 = Math.round(
              (parseFloat(precio) * val2) / 100
            );
          } else {
            var resultado_porcen1 = parseFloat(
              (parseFloat(precio) * val2) / 100
            );
          }

          var resultado_porcen2 = precio - resultado_porcen1;

          //if (resultado_porcen2>0){
          $(".Input").val(resultado_porcen2);
          $(".Input1").val("");
          //}
        } else {
          $(".Input").val(eval($(".Input").val()));
          resultado_porcen2 = $(".Input").val();
        }

        //if(impuesto != 0)
        if (impuesto != 0 && resultado_porcen2 >= 0) {
          precioProd = $(".Input").val() / parseFloat((impuesto / 100) + 1);
        } else {
          if (resultado_porcen2 >= 0) {
            precioProd = $(".Input").val();
          }
        }
      } else if ($(".Input").val() == "") {
        var precio = limpiarCampo(precioField.text());
        resultado_porcen2 = precio;
        $(".Input").val(precio);
      }

      if (isNaN($(".Input").val())) {
        $(".Input").val("0");
      }
      if (resultado_porcen2 >= 0) {
        precioField.html(formatDollar($(".Input").val()));
        precioFieldReal.val(precioProd);
        precioField.popover("destroy");
        calculate();
        actualizarEspera();
      } else {
        swal({
          position: "center",
          type: "error",
          title: "El precio del producto no puede ser negativo",
          showConfirmButton: false,
          timer: 1500
        });
        precioField.popover("destroy");
      }
    });

    $("#Calc").submit(function(e) {
      e.preventDefault();
      if ($(".Input").val() != "") {
        var valor = $(".Input").val();
        var val1 = valor.replace("%", "");
        var val2 = val1.replace(" ", "");
        var precio = Math.round(valor_precio_venta);
        var primer = $(".Input").val();
        var res1 = primer.replace(/[1234567890]/gi, "");
        if (res1 == " % " || $(".Input1").val() == "porcentaje") {
          var resultado_porcen1 = Math.round((parseFloat(precio) * val2) / 100);
          var resultado_porcen2 = precio - resultado_porcen1;
          $(".Input").val(resultado_porcen2);
          $(".Input1").val("");
        } else {
          $(".Input").val(eval($(".Input").val()));
        }

        if (impuesto != 0) {
          if (impuesto.length == 1) {
            precioProd = $(".Input").val() / parseFloat("1.0" + impuesto);
          } else if (impuesto.length == 2) {
            precioProd = $(".Input").val() / parseFloat("1." + impuesto);
          }
        } else {
          precioProd = $(".Input").val();
        }
      } else if ($(".Input").val() == "") {
        var precio = limpiarCampo(precioField.text());
        $(".Input").val(precio);
      }
      if (isNaN($(".Input").val())) {
        $(".Input").val("0");
      }
      precioField.html(formatDollar($(".Input").val()));
      precioFieldReal.val(precioProd);
      precioField.popover("destroy");
      calculate();
      actualizarEspera();
    });
  });
}

$(
  ".one, .two, .three, .sum, .four, .five, .six, .seven, .eith, .nine, .cero, .rest, .mult, .porcen, .doIt, .clear"
).live("click", function() {
  data = $(this).attr("class");

  switch (data.split(" ")[1]) {
    case "one":
      $(".Input").val($(".Input").val() + 1);

      break;

    case "two":
      $(".Input").val($(".Input").val() + 2);

      break;

    case "three":
      $(".Input").val($(".Input").val() + 3);

      break;

    case "four":
      $(".Input").val($(".Input").val() + 4);

      break;

    case "five":
      $(".Input").val($(".Input").val() + 5);

      break;

    case "six":
      $(".Input").val($(".Input").val() + 6);

      break;

    case "seven":
      $(".Input").val($(".Input").val() + 7);

      break;

    case "eith":
      $(".Input").val($(".Input").val() + 8);

      break;

    case "nine":
      $(".Input").val($(".Input").val() + 9);

      break;

    case "cero":
      $(".Input").val($(".Input").val() + 0);

      break;

    case "sum":
      $(".Input").val($(".Input").val() + " + ");

      break;

    case "rest":
      $(".Input").val($(".Input").val() + " - ");

      break;

    case "porcen":
      $(".Input").val($(".Input").val() + " % ");

      $(".Input1").val($(".Input1").val() + "porcentaje");

      break;

    case "doIt":
      var valor = $(".Input").val();

      var val1 = valor.replace("%", "");

      var val2 = val1.replace(" ", "");

      var precio = precioField.text().replace(",", "");

      var primer = $(".Input").val();

      var res1 = primer.replace(/[1234567890]/gi, "");

      if (res1 == " % " || $(".Input1").val() == "porcentaje") {
        var resultado_porcen1 = (parseInt(precio) * val2) / 100;

        var resultado_porcen2 = precio - resultado_porcen1;

        $(".Input").val(resultado_porcen2);

        $(".Input1").val("");
      } else {
        $(".Input").val(eval($(".Input").val()));
      }

      break;

    case "clear":
      $(".Input").val("");

      $(".Input1").val("");

      break;
  }

  $(".Input").focus();
});

$(".precio-porcentaje").live("click", function() {
  precioField1 = $(this);

  //alert($(".precio-prod").index($(this)));

  precioFieldReal1 = $(".precio-prod-precio-porcentaje").eq(
    $(".precio-porcentaje").index($(this))
  );

  precioContent =
    "<form id='Calc'><table  width='100%'><tr colspan='4'><td><input type='text' value=' '  style='width: 200px; height: 31px;' name='Input' class='Input'/></td></tr>";

  precioContent += "<tr>";

  precioContent += "<td>";

  precioContent +=
    "&nbsp;<input type='button' class='btn clear' value='           C           ' name='clear'/>";

  precioContent +=
    "&nbsp;&nbsp;<button type='button' id='btn-accept-precio' class='btn btn-primary'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Enter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></button>";

  precioContent += "</td>";

  precioContent += "</tr>";

  precioContent += "</table></form>";

  precioField1
    .popover({
      placement: "bottom",

      title:
        "Ingrese el numero de porcentaje de descuento y dar click en enter",

      html: true,

      content: precioContent,

      trigger: "manual"
    })
    .popover("show");

  $("#btn-accept-precio").click(function() {
    precioField1.html(formatDollar($(".Input").val()));

    precioFieldReal1.val($(".Input").val());

    precioField1.popover("destroy");

    calculate();
    actualizarEspera();
  });

  $("#Calc").submit(function() {
    precioField1.html(formatDollar($(".Input").val()));

    precioFieldReal1.val($(".Input").val());

    precioField1.popover("destroy");

    calculate();
    actualizarEspera();

    return false;
  });
});

$(
  ".uno, .dos, .tres, .sumas, .cuatro, .cinco, .seis, .siete, .ocho, .nueve, .cero, .rest, .mult, .divi, .doIt, .clear"
).live("click", function() {
  data = $(this).attr("class");

  switch (data.split(" ")[1]) {
    case "clear":
      $(".Input").val(" ");

      break;
  }

  $(".Input").focus();
});

var objData = null;

var limit = 8;

var offset = 0;

var pages = 0;

var tieneCredito = false;

$(document).ready(function() {
  //retorna true si esta vacio

  function isEmptyJSON(obj) {
    for (var i in obj) {
      return false;
    }

    return true;
  }

  $(document).on("click", ".grabarVenta", function() {
    console.log("grabar 4");
    $("#grabar").prop("disabled", true);
    grabar();
  });

  $(document).on("click", "#EnviarFacturaBoton", function() {
    $("#EnviarFacturaBoton").prop("disabled", true);
    $("#EnviarFacturaBotonNo").prop("disabled", true);
    var id = $id_user_mix;
    var email = $email_mix;
    var nombre_empresa = $empresa_mix;

    mixpanel.identify(id);

    mixpanel.track("Enviar Factura por correo", {
      $email: email,
      $empresa: nombre_empresa
    });
    enviarFacturaEmail();
  });

  function enviarFacturaEmail() {
    $("#grabar").prop("disabled", true);
    _enviarFactura = $("#enviarFacturaModal")
      .find("input#emailCliente")
      .val();

    //console.log("enviarFacturaEmail---"+_enviarFactura+"----"+$('#enviarFacturaModal').find('input#emailCliente').val());

    grabar();
  }

  function enviarFacturaForm() {
    id_cliente = $("#id_cliente").val();

    $.post(
      $buscarEmail,
      {
        id: id_cliente
      },
      function(data) {
        correo = "";

        if (data.resp == 1) {
          correo = data.cliente["email"];
        }

        $("#enviarFacturaModal")
          .find("input#emailCliente")
          .val(correo);

        //console.log("enviarFacturaForm--"+correo+"----"+$('#enviarFacturaModal').find('input#emailCliente').val());

        //grabar();
      },
      "json"
    );
  }

  //============================================================================

  // Forma de pago GiftCard

  //============================================================================

  // para validar pago final

  window.formaGiftObj = {
    "0": null,

    "1": null,

    "2": null,

    "3": null,

    "4": null,

    "5": null
  };

  function mostrarDiscriminacionImpuesto(e) {
    //debugger;

    var opcion = $(e).find("option:selected");

    if ($(opcion).attr("data-tipo") == "Datafono") {
      calcularImpuestoDicriminado($(e));

      $(e)
        .parents("div.row")
        .parent()
        .find("div.datafono")
        .show();
    } else {
      $(e)
        .parents("div.row")
        .parent()
        .find("div.datafono")
        .hide();
    }
  }

  function calcularImpuestoDicriminado(e) {
    var row = e.parents("div.row").parent(),
      valorEntregado = $(row)
        .find("input[name=valor_entregado]")
        .val(),
      impuesto = limpiarCampo(
        $(row)
          .find("#impuestoDatafono")
          .val()
      );

    if (String(impuesto).length == 2) {
      subtotal = valorEntregado / parseFloat("1." + impuesto);
    } else if (String(impuesto).length == 1) {
      subtotal = valorEntregado / parseFloat("1.0" + impuesto);
    }

    iva = valorEntregado - subtotal;

    //console.log("-------------------------------");

    //console.log(impuesto,valorEntregado,subtotal,iva,parseFloat("1.0"+impuesto));

    //console.log("-------------------------------");

    $(row)
      .find("input.subtotal")
      .val(limpiarCampo(subtotal));

    $(row)
      .find("input.impuesto2")
      .val(limpiarCampo(iva));
  }

  $("input[name=impuestoDatafono]").on("keyup", function() {
    calcularImpuestoDicriminado($(this));
  });

  $("#forma_pago").on("change", function() {
    eliminar_efectivo();
    //debugger;

    if ($(this).val() == "Gift_Card") {
      setDomGift("", 1);
    } else if ($(this).val() == "nota_credito") {
      setDomGift("", 2);
    } else if ($(this).val() == "datafono_vendty") {
      setDomGift("", 3);
    } else {
      setDomGift("", 0);
    }

    mostrarDiscriminacionImpuesto($(this));
  });

  $("#forma_pago1").on("change", function() {
    eliminar_efectivo();
    if ($(this).val() == "Gift_Card") {
      setDomGift(1, 1);
    } else if ($(this).val() == "nota_credito") {
      setDomGift(1, 2);
    } else {
      setDomGift(1, 0);
    }

    mostrarDiscriminacionImpuesto($(this));
  });

  $("#forma_pago2").on("change", function() {
    eliminar_efectivo();
    if ($(this).val() == "Gift_Card") {
      setDomGift(2, 1);
    } else if ($(this).val() == "nota_credito") {
      setDomGift(2, 2);
    } else {
      setDomGift(2, 0);
    }

    mostrarDiscriminacionImpuesto($(this));
  });

  $("#forma_pago3").on("change", function() {
    eliminar_efectivo();
    if ($(this).val() == "Gift_Card") {
      setDomGift(3, 1);
    } else if ($(this).val() == "nota_credito") {
      setDomGift(3, 2);
    } else {
      setDomGift(3, 0);
    }

    mostrarDiscriminacionImpuesto($(this));
  });

  $("#forma_pago4").on("change", function() {
    eliminar_efectivo();
    if ($(this).val() == "Gift_Card") {
      setDomGift(4, 1);
    } else if ($(this).val() == "nota_credito") {
      setDomGift(4, 2);
    } else {
      setDomGift(4, 0);
    }

    mostrarDiscriminacionImpuesto($(this));
  });

  $("#forma_pago5").on("change", function() {
    eliminar_efectivo();
    if ($(this).val() == "Gift_Card") {
      setDomGift(5, 1);
    } else if ($(this).val() == "nota_credito") {
      setDomGift(5, 2);
    } else {
      setDomGift(5, 0);
    }

    mostrarDiscriminacionImpuesto($(this));
  });

  $(".codigoGift").keyup(function(event) {
    if (event.which == 13) {
      var index = $(this).attr("index");

      var codigo = $(this).val();

      getGiftEstado(codigo, index);

      event.preventDefault();
    }
  });

  $(".btnBuscarGift2").click(function() {
    var index = $(this).attr("index");

    var codigo = $("#valor_entregado_gift" + index).val();

    getGiftEstado(codigo, index);
  });

  // para validar pago final

  function setGiftObj(index, valor) {
    if (index == "") i = 0;
    else i = index;

    formaGiftObj[i] = valor;
  }

  window.eliminarGiftcard = function(index) {
    setDomGift(index, 0);

    $("#forma_pago" + index)
      .val("efectivo")
      .trigger("change");
  };

  function setDomGift(index, opc) {
    setGiftObj(index, null);

    // si opc = 1 se muestra la opcion giftcard, de lo contrario s eoculta

    if (opc == 1) {
      $("#valor_entregado" + index).hide();

      $("#valor_entregado" + index).val(0);

      $("#valor_entregado_gift" + index).attr(
        "style",
        "display: block !important"
      );

      $("#valor_entregado_giftb" + index).attr(
        "style",
        "display: block !important"
      );

      $("#valor_entregado_nota_credito" + index).prop("disabled", false);

      $("#valor_entregado_nota_credito" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_nota_creditob" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_nota_credito" + index).val("");

      $("#valor_datafono_vendty" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_datafono_vendtyb" + index).attr(
        "style",
        "display: none !important"
      );
    } else if (opc == 2) {
      $("#valor_entregado" + index).hide();

      $("#valor_entregado" + index).val(0);

      $("#valor_entregado_nota_credito" + index).attr(
        "style",
        "display: block !important"
      );

      $("#valor_entregado_nota_creditob" + index).attr(
        "style",
        "display: block !important"
      );

      $("#valor_entregado_gift" + index).prop("disabled", false);

      $("#valor_entregado_gift" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_giftb" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_gift" + index).val("");

      $("#valor_datafono_vendty" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_datafono_vendtyb" + index).attr(
        "style",
        "display: none !important"
      );
    } else if (opc == 3) {
      // Datafono Vendty

      if ($("#valor_entregado" + index).val() > 0) {
        $("#valor_datafono_vendty" + index).val(
          $("#valor_entregado" + index).val()
        );
      }

      $("#valor_entregado" + index).hide();

      $("#valor_entregado" + index).val(0);

      $("#valor_datafono_vendty" + index).attr(
        "style",
        "display: block !important"
      );

      $("#valor_datafono_vendtyb" + index).attr(
        "style",
        "display: block !important"
      );

      $("#valor_entregado_nota_credito" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_nota_creditob" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_gift" + index).prop("disabled", false);

      $("#valor_entregado_gift" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_giftb" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_gift" + index).val("");
    } else {
      $("#valor_entregado" + index).show();

      //$("#valor_entregado"+index).val( 0 );

      $("#valor_entregado" + index).prop("disabled", false);

      $("#valor_entregado" + index).css("cursor", "default");

      $("#valor_entregado_gift" + index).prop("disabled", false);

      $("#valor_entregado_gift" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_giftb" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_gift" + index).val("");

      $("#valor_entregado_nota_credito" + index).prop("disabled", false);

      $("#valor_entregado_nota_credito" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_nota_creditob" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_entregado_nota_credito" + index).val("");

      $("#valor_datafono_vendty" + index).attr(
        "style",
        "display: none !important"
      );

      $("#valor_datafono_vendtyb" + index).attr(
        "style",
        "display: none !important"
      );
    }

    validarMediosDePago(0);
  }

  function getGiftEstado(codigo, index) {
    $.ajax({
      url: $estadoGiftCard,

      dataType: "json",

      type: "POST",

      data: { codigo: codigo },

      error: function(jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },

      success: function(data) {
        setEstadoGiftCard(data, index);
      }
    });
  }

  function setEstadoGiftCard(datos, index) {
    var estado = datos.estado;

    var nombre = datos.nombre;

    var valor = datos.valor;

    //valor_entregado_gift -> codigo

    //valor_entregado -> valor

    //forma_pago -> select

    $("#valor_entregado" + index).val(0);

    $("#valor_entregado" + index).prop("disabled", false);

    $("#valor_entregado" + index).css("cursor", "default");

    $("#valor_entregado" + index).hide();

    $("#valor_entregado_gift" + index).prop("disabled", false);

    $("#valor_entregado_gift" + index).css("cursor", "default");

    $("#valor_entregado_giftb" + index).attr(
      "style",
      "display: block !important"
    );

    setGiftObj(index, null);

    if (estado == "empty") {
      //alert("La giftCard no existe");
      $("#backPopUp").css("display", "none");
      swal({
        position: "center",
        type: "error",
        title: "La giftCard no existe",
        showConfirmButton: false,
        timer: 1500
      });
      setTimeout(function() {
        $("#backPopUp").css("display", "block");
      }, 1600);
    }

    if (estado == "cancelado") {
      //alert("La "+nombre+" ya ha sido canjeada");
      $("#backPopUp").css("display", "none");
      swal({
        position: "center",
        type: "error",
        title: "La " + nombre + " ya ha sido canjeada",
        showConfirmButton: false,
        timer: 1500
      });
      setTimeout(function() {
        $("#backPopUp").css("display", "block");
      }, 1600);
    }

    if (estado == "activo") {
      //alert("La "+nombre+" no ha sido pagada");
      $("#backPopUp").css("display", "none");
      swal({
        position: "center",
        type: "error",
        title: "La " + nombre + " no ha sido pagada",
        showConfirmButton: false,
        timer: 1500
      });
      setTimeout(function() {
        $("#backPopUp").css("display", "block");
      }, 1600);
    }

    if (estado == "pagado") {
      $("#valor_entregado" + index).val(valor);

      $("#valor_entregado" + index).prop("disabled", true);

      $("#valor_entregado" + index).css("cursor", "not-allowed");

      $("#valor_entregado" + index).show();

      $("#valor_entregado_gift" + index).prop("disabled", true);

      $("#valor_entregado_gift" + index).css("cursor", "not-allowed");

      $("#valor_entregado_giftb" + index).attr(
        "style",
        "display: none !important"
      );

      setGiftObj(index, "pagada");
    }

    validarMediosDePago(0);
  }

  //============================================================================

  // FIN Forma de pago GiftCard

  //============================================================================

  //=============================================================================

  // Inicio Nota credito

  //=============================================================================

  // para validar pago final

  window.formaNotaCreditoObj = {
    "0": null,

    "1": null,

    "2": null,

    "3": null,

    "4": null,

    "5": null
  };

  // para validar pago final Nota credito

  function setNotaCreditoObj(index, valor) {
    if (index == "") i = 0;
    else i = index;

    formaNotaCreditoObj[i] = valor;
  }

  $(".codigoNotaCredito").keyup(function(event) {
    if (event.which == 13) {
      var index = $(this).attr("index");

      var codigo = $(this).val();

      getNotaCreditoEstado(codigo, index);

      event.preventDefault();
    }
  });

  $(".btnBuscarNotaCredito2").click(function() {
    var index = $(this).attr("index");

    var codigo = $("#valor_entregado_nota_credito" + index).val();

    getNotaCreditoEstado(codigo, index);
  });

  function getNotaCreditoEstado(codigo, index) {
    $.ajax({
      url: $estadoNotaCredito,

      dataType: "json",

      type: "POST",

      data: { codigo: codigo },

      error: function(jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },

      success: function(data) {
        setEstadoNotaCredito(data, index);
      }
    });
  }

  function setEstadoNotaCredito(datos, index) {
    var estado = datos.estado;

    var nombre = datos.nombre;

    var valor = datos.valor;

    //valor_entregado_gift -> codigo

    //valor_entregado -> valor

    //forma_pago -> select

    $("#valor_entregado" + index).val(0);

    $("#valor_entregado" + index).prop("disabled", false);

    $("#valor_entregado" + index).css("cursor", "default");

    $("#valor_entregado" + index).hide();

    $("#valor_entregado_gift" + index).attr("disabled");

    $("#valor_entregado_nota_credito" + index).css("cursor", "default");

    $("#valor_entregado_nota_credito" + index).attr(
      "style",
      "display: block !important"
    );

    setNotaCreditoObj(index, null);

    if (estado == "empty") {
      //alert("La nota credito no existe");
      $("#backPopUp").css("display", "none");
      swal({
        position: "center",
        type: "error",
        title: "La nota credito no existe",
        showConfirmButton: false,
        timer: 1500
      });
      setTimeout(function() {
        $("#backPopUp").css("display", "block");
      }, 1600);

      $("#valor_entregado_nota_credito" + index).val("");
    }

    if (estado == "cancelado") {
      //alert("La "+nombre+" ya ha sido canjeada");
      $("#backPopUp").css("display", "none");
      swal({
        position: "center",
        type: "error",
        title: "La " + nombre + " ya ha sido canjeada",
        showConfirmButton: false,
        timer: 1500
      });
      setTimeout(function() {
        $("#backPopUp").css("display", "block");
      }, 1600);
      $("#valor_entregado_nota_credito" + index).val("");
    }

    if (estado == "activo") {
      //alert("La "+nombre+" no ha sido pagada");
      $("#backPopUp").css("display", "none");
      swal({
        position: "center",
        type: "error",
        title: "La " + nombre + " no ha sido pagada",
        showConfirmButton: false,
        timer: 1500
      });
      setTimeout(function() {
        $("#backPopUp").css("display", "block");
      }, 1600);
    }

    //console.log(valor);

    if (estado == "pagado") {
      $("#valor_entregado" + index).val(valor);

      $("#valor_entregado" + index).prop("disabled", true);

      $("#valor_entregado" + index).css("cursor", "not-allowed");

      $("#valor_entregado" + index).show();

      $("#valor_entregado_nota_credito" + index).prop("disabled", true);

      $("#valor_entregado_nota_credito" + index).css("cursor", "not-allowed");

      $("#valor_entregado_nota_creditob" + index).attr(
        "style",
        "display: none !important"
      );

      setNotaCreditoObj(index, "pagada");
    }

    validarMediosDePago(0);
  }

  //=============================================================================

  // Fin Nota credito

  //=============================================================================

  $(
    "#valor_entregado, #valor_entregado1, #valor_entregado2, #valor_entregado3, #valor_entregado4, #valor_entregado5"
  ).keyup(function(e) {
    validarMediosDePago(e);

    calcularImpuestoDicriminado($(this));
  });

  //obtener promociones

  $.post(
    url_promos + "/obtenerHabilitados",

    {},

    function(data) {
      console.log(data.length);
      if (data.length > 0) {
        $("#promocion").append(
          '<option value="0" selected >Seleccionar Promoción</option>'
        );

        $.each(data, function(i, e) {
          $("#promocion").append(
            '<option value="' + e.id_promocion + '">' + e.nombre + "</option>"
          );
        });

        //mostrar selector de promociones solo cuando existan

        $("#promociones").show();
      }
    },

    "json"
  );

  $("#buscar-cliente").keyup(function(e) {
    if ($("#buscar-cliente").val() != "")
      $("#contenedor-lista-clientes").fadeIn("fast");
    else $("#contenedor-lista-clientes").fadeOut("fast");
  });

  $(".first, .previous, .next, .last, .clean").live("click", function() {
    if (!$(this).hasClass("paginate_button_disabled")) {
      classValue = $(this).attr("class");

      switch (classValue.split(" ")[0]) {
        case "next":
          pages++;

          offset += limit;

          break;

        case "last":
          offset = (getCountPages() - 1) * limit;

          pages = getCountPages();

          break;

        case "first":
          offset = 0;

          pages = 0;

          break;

        case "previous":
          offset -= limit;

          pages--;

          break;

        case "clean":
          $("#search").val("");

          $("#searchPaginator").css("display", "none");

          $("#productsSearchInput").css("display", "none");

          $("#slickCategories").css("display", "flex");

          $("#vitrinaProductos").css("display", "flex");

          break;
      }

      paintRows();
    }
  });

  /* Imei - Codigo de barra */
  $("#codigo_barra_imei").on("keyup", function(e) {
    var code = e.keyCode || e.which;
    if (!isEmptyJSON(cliente_selecionado)) {
      var cliente = cliente_selecionado;
      var grupo = cliente_grupo;
    } else {
      var cliente = "";
      var grupo = 0;
    }

    if (code == 13 || code == 9) {
      var imei = $("#codigo_barra_imei").val();
      imeiAddValue(cliente, grupo);
      e.preventDefault();
    }
  });

  $("#search").on("keyup", function(e) {
    var booleanV;
    var code = e.keyCode || e.which;

    /// verifica r si es numero o texto
    if ($("#forBarCode").hasClass("activeB")) {
      booleanV = true;
    }

    if ($("#forNameProduct").hasClass("activeB")) {
      booleanV = false;
    }

    //console.log(booleanV);

    // console.log(isEmptyJSON(cliente_selecionado),"ass");

    if (!isEmptyJSON(cliente_selecionado)) {
      var cliente = cliente_selecionado;

      var grupo = cliente_grupo;
    } else {
      var cliente = "";

      var grupo = 0;
    }

    //console.log(cliente,grupo,4);

    if (!booleanV) {
      // console.log('Normal');

      //alert(cliente_grupo);
      if ($(this).val() != "") {
        $("#cod-container").css("display", "none");
        $("#slickCategories").css("display", "none");
        $("#vitrinaProductos").css("display", "none");
        $("#searchPaginator").css("display", "flex");
        $("#productsSearchInput").css("display", "flex");
      } else {
        $("#search").val("");
        $("#searchPaginator").css("display", "none");
        $("#productsSearchInput").css("display", "none");
        $("#slickCategories").css("display", "flex");
        $("#vitrinaProductos").css("display", "flex");
      }

      $.ajax({
        url: $url,

        dataType: "json",

        type: "post",

        data: {
          filter: $(this).val(),
          type: "buscalo",
          cliente: cliente,
          grupo: grupo
        },

        success: function(data) {
          objData = data;

          limit = 8;

          offset = 0;

          pages = 0;

          paintRows();
        }
      });
    } else {
      if (code == 13 || code == 9) {
        codificaloAddValue(cliente, grupo);

        e.preventDefault();
      }
    }
  });

  function paintRows() {
    formatTable();

    paintInfo();

    paintPagin();

    terminar = getCount();

    for (i = offset; i < terminar; i++) {
      if (
        $("#facturasTable tbody tr")
          .eq(0)
          .hasClass("nothing")
      ) {
        $("#facturasTable tbody").html("");

        var strRow = formatRows(objData[i]).replace(
          "<img",
          '<img onerror="ImgError(this)" '
        );

        //console.log(offset,getCount(),i,"si");

        var $row = $(strRow).appendTo("#facturasTable tbody");

        //Agregamos input hidden con el valor del giftcard

        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="giftcard" value="' +
              objData[i]["gc"] +
              '">'
          );

        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="val_stock" value="' +
              objData[i]["stock_minimo"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="vendernegativo" value="' +
              objData[i]["vendernegativo"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="tipo_producto" value="' +
              objData[i]["tipo_producto"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="imei" value="' +
              objData[i]["imei"] +
              '">'
          );
      } else {
        //console.log(offset,getCount(),i,"no");

        var strRow = formatRows(objData[i]).replace(
          "<img",
          '<img onerror="ImgError(this)" '
        );

        var $row = $(strRow).appendTo("#facturasTable tbody");

        //Agregamos input hidden con el valor del giftcard
        console.log(objData[i]);
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="giftcard" value="' +
              objData[i]["gc"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="val_stock" value="' +
              objData[i]["stock_minimo"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="vendernegativo" value="' +
              objData[i]["vendernegativo"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="tipo_producto" value="' +
              objData[i]["tipo_producto"] +
              '">'
          );
        $($row[0])
          .find(".codigo")
          .after(
            '<input type="hidden" class="imei" value="' +
              objData[i]["imei"] +
              '">'
          );
      }
    }
  }

  /*Count elements on table*/

  function getCount() {
    count = limit + offset;

    //console.log("datos"+objData+"asasas");

    dataLength = objDataLength(objData);

    if (count > dataLength) {
      count = dataLength;
    }

    return count;
  }

  function getCountPages() {
    count = Math.ceil(objDataLength(objData) / limit);

    return count;
  }

  function objDataLength(objData) {
    if (objData == null) {
      return 0;
    } else {
      return objData.length;
    }
  }

  /*function formatTable(){

        $("#facturasTable tbody").html("<tr class='nothing'><td>No existen elementos</td><tr>");



        if (row.impuesto != '0') {

            html += "<p><span class='stock'>Stock: " + row.stock_minimo + "</span>&nbsp;<input type='hidden' value='" + row.precio_compra + "' class='precio-compra-real'/><span class='precio-minimo'>C&oacute;digo de barra: " + row.codigo + "</span>  -  <span class='precio-minimo'>Precio + IVA: " + formatDollar(precioimpuesto) + "</span> &nbsp;<span class='ubicacion_producto'>Ubicaci&oacute;n: " + row.ubic + "</span> </p><input type='hidden' class='id_producto' value='" + row.id + "'/><input type='hidden' class='codigo' value='" + row.codigo + "'><input type='hidden' class='impuesto' value='" + row.impuesto + "'></p></td>";

        }

        html += "</tr>";



        return html;

    }*/

  function formatTable() {
    $("#facturasTable tbody").html(
      "<tr class='nothing'><td>No existen elementos</td><tr>"
    );
  }

  function paintInfo() {
    $(".dataTables_info").html(
      "Mostrando desde " +
        offset +
        " hasta " +
        getCount() +
        " de " +
        objDataLength(objData) +
        " elementos"
    );
  }

  /*Paginador*/

  function paintPagin() {
    $(".dataTables_paginate").html(
      getFirstPage() +
        getPrevPage() +
        getNextPage() +
        getLastPage() +
        getCleanPage()
    );
  }

  function getFirstPage() {
    if (offset > 0) {
      return '<a class="first paginate_button">Primero</a>';
    } else {
      return '<a class="first paginate_button paginate_button_disabled">Primero</a>';
    }
  }

  function getPrevPage() {
    if (pages > 0) {
      return '<a class="previous paginate_button" tabindex="0">Anterior</a>';
    } else {
      return '<a class="previous paginate_button paginate_button_disabled" tabindex="0">Anterior</a>';
    }
  }

  function getNextPage() {
    if (getCountPages() - 1 > pages) {
      return '<a class="next paginate_button" tabindex="0">Pr&oacute;ximo</a>';
    } else {
      return '<a class="next paginate_button paginate_button_disabled" tabindex="0">Pr&oacute;ximo</a>';
    }
  }

  function getLastPage() {
    if (getCountPages() - 1 > pages) {
      return '<a class="last paginate_button" tabindex="0">&Uacute;timo</a>';
    } else {
      return '<a class="last paginate_button paginate_button_disabled" tabindex="0">&Uacute;timo</a>';
    }
  }

  function getCleanPage() {
    return '<a class="clean paginate_button" tabindex="0">Mostrar Todo</a>';
  }
  // MARK: Eviando Factura
  function grabar() {
    console.log("en grabar 1");
    $("#grabar").prop("disabled", true);
    var max = 0;
    var imprimir = function(data) {
      $.fancybox.open({
        width: "85%",
        height: "85%",
        autoScale: false,
        transitionIn: "none",
        transitionOut: "none",
        href: $urlPrint + "/" + data.id,
        type: "iframe",
        afterClose: function() {
          divisionitem = data.divisionitem.split("_");
          if (divisionitem[0] > 0) {
            swal({
              position: "center",
              type: "success",
              title: "Items Pendientes",
              html:
                "Items Pendientes por facturar en la <strong>Sección:</strong>" +
                divisionitem[1] +
                "<strong>Mesa:</strong>" +
                divisionitem[2],
              showConfirmButton: false,
              timer: 2000
            });
            setTimeout(function() {
              location.reload(true);
            }, 2010);
          } else {
            window.location = $reloadThis + "?var=" + $buscador;
          }
        }
      });
    };
    // MARK : imprimir factura
    var imprimirFactura = function(data) {
      if(data.factura_electronica == "true") {

        if(data.status_electronic_invoice == 'Exito') {
          swal({
            position: "center",
            type: "success",
            title: "Factura electronica generada",
            html:
              "Se ha generado la factura electronica ingresa a <strong>facturaxion</strong> y descarga tu PDF", 
            showConfirmButton: false,
            timer: 3500
          });
          setTimeout(function() {
            location.reload(true);
          }, 3500);
          return false;
        } else {
          swal({
            position: "center",
            type: "warning",
            title: "Factura electronica no generada",
            html:
              "No ha sido posible generar tu factura electronica verifica en tu plataforma <strong>facturaxion</strong>", 
            showConfirmButton: false,
            timer: 3500
          });
          setTimeout(function() {
            location.reload(true);
          }, 3500);
          return false;
        }
      } else {
          if (facturaAutomatica == "estandar") {
            if (!confirm("Desea imprimir la factura de venta?")) {
              divisionitem = data.divisionitem.split("_");
              if (divisionitem[0] > 0) {
                swal({
                  position: "center",
                  type: "success",
                  title: "Items Pendientes",
                  html:
                    "Items Pendientes por facturar en la <strong>Sección:</strong>" +
                    divisionitem[1] +
                    "<strong>Mesa:</strong>" +
                    divisionitem[2],
                  showConfirmButton: false,
                  timer: 2000
                });
                setTimeout(function() {
                  location.reload(true);
                }, 2010);
              } else {
                window.location = $reloadThis + "?var=" + $buscador;
              }
            } else imprimir(data);
          } else if (facturaAutomatica == "auto") {
            imprimir(data);
          } else if (facturaAutomatica == "no") {
            divisionitem = data.divisionitem.split("_");
            if (divisionitem[0] > 0) {
              swal({
                position: "center",
                type: "success",
                title: "Items Pendientes",
                html:
                  "Items Pendientes por facturar en la <strong>Sección:</strong>" +
                  divisionitem[1] +
                  "<strong>Mesa:</strong>" +
                  divisionitem[2],
                showConfirmButton: false,
                timer: 2000
              });
              setTimeout(function() {
                location.reload(true);
              }, 2010);
            } else window.location = $reloadThis + "?var=" + $buscador;
          }
          cantidadsindescuentogeneral = 1;
          $("#dialog-forma-pago-form").dialog("close");
          $("#backPopUp").css("display", "none");
      }
    };

    $(this).attr("disabled", true);

    productos_list = new Array();

    // para almacenar los giftcard que se venderan

    var giftCardsObjs = [];

    var promocion = "",
      promocionId = "";

    if ($("#promocion").is(":visible")) {
      if ($("#promocion").val() != "0") {
        //promocion = $('#promocion option:selected').text();

        promocionId = $("#promocion").val();

        promocion = promocionId;

        var detalle = obtenerDetallePromocion(promocionId);

        promocionDescuento = detalle[0]["descuento"];

        //console.log('dentro de promocion');

        //console.log(detalle);
      }
    }

    $(".title-detalle").each(function(x) {
      var descuento = 0;
      var porcentaje_descuentop = 0;

      precioR = parseFloat(
        $(".precio-prod-real")
          .eq(x)
          .val()
      );
      precioNoC = parseFloat(
        $(".precio-prod-real-no-cambio")
          .eq(x)
          .val()
      );
      preciodescuento = parseFloat(
        $(".precio-prod-descuento")
          .eq(x)
          .val()
      );

      // if ($(".precio-prod-descuento").eq(x).val()>0){
      //calculando porcentaje general
      if (descuentogeneral != 0) {
        if (precioR > precioNoC) {
          descuento = precioR - preciodescuento;
        } else {
          descuento = precioNoC - preciodescuento;
        }
      } else {
        if (precioR > precioNoC) {
          descuento = precioNoC - preciodescuento;
        } else {
          descuento = precioNoC - precioR;
        }
      }

      if (precioR > precioNoC) {
        descuentopi = 0;
        porcentaje_descuentop = 0;
        precio = precioR;
      } else {
        descuentopi = precioNoC - precioR;
        porcentaje_descuentop =
          parseFloat(descuentopi * 100) / parseFloat(precioNoC);
        precio = precioNoC;
      }
      //calculando porcentaje interno
      // if ($(".precio-prod-real").eq(x).val() != 0) {
      //descuentopi = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

      /*     alert("descuentopi=" + descuentopi);
                alert("porcentaje_descuentop=" + porcentaje_descuentop);*/
      // } else {
      //porcentaje_descuentop = 0;
      //  }

      //}
      /*
            if(parseInt($(".precio-prod-real-no-cambio").eq(x).val()) < parseInt($(".precio-prod-real").eq(x).val())){              
                descuento = 0;
            }*/

      if (porcentaje_descuentop < 0) {
        porcentaje_descuentop = 0;
      }

      //alert("descuento=" + descuento);
      //alert("porcentaje_descuentop=" + porcentaje_descuentop);
      // Si es giftcard loa añadimos a la lista de fitcards que van a ser pagadas

      if (
        $("#productos-detail .giftcard")
          .eq(x)
          .val() == "1"
      ) {
        giftCardsObjs.push(
          $(".product_id")
            .eq(x)
            .val()
        );
      }

      //precio = redondear(Math.round($(".precio-prod-real-no-cambio").eq(x).val()));
      //precio = (parseFloat($(".precio-prod-real-no-cambio").eq(x).val()));

      if (promocionTipo == 1) {
        if (
          validarProductoPromocion(
            promocionId,
            $(".product_id")
              .eq(x)
              .val()
          )
        ) {
          //cuando tengo activa promociones de cantidad
          impusto = parseFloat(
            $(".impuesto-final")
              .eq(x)
              .val()
          );

          precioMostrar = Math.round(
            precio - Math.round((precio * descuentoPorcentajePromocion) / 100)
          );

          //console.log(precio+"--"+precioMostrar+"--"+descuentoPorcentajePromocion+"--"+(precio * descuentoPorcentajePromocion / 100));

          precioConIva = limpiarCampo(
            parseFloat(precio + parseFloat((precio * impuesto) / 100))
          );

          precioPromocion = $(".promocionPrecio")
            .eq(x)
            .val();

          //precioDescuento = Math.round(Math.round(precio*$(".cantidad").eq(x).text()) - precioPromocion)/Math.round($(".cantidad").eq(x).text());
          precioDescuento = descuento;

          arregloProductos = {
            codigo: $(".codigo-final")
              .eq(x)
              .val(),

            precio_venta: precio,

            unidades: parseFloat(
              $(".cantidad")
                .eq(x)
                .text()
            ),

            impuesto: $(".impuesto-final")
              .eq(x)
              .val(),

            nombre_producto: $(".title-detalle")
              .eq(x)
              .text(),

            product_id: $(".product_id")
              .eq(x)
              .val(),

            imei: $(".productoImei")
              .eq(x)
              .val(),

            descuento: precioDescuento,

            porcentaje_descuentop: porcentaje_descuentop,

            promocion: 0
          };

          productos_list.push(arregloProductos);
        } else {
          arregloProductos = {
            codigo: $(".codigo-final")
              .eq(x)
              .val(),

            precio_venta:
              descuento != 0
                ? $(".precio-prod-real-no-cambio")
                    .eq(x)
                    .val()
                : $(".precio-prod-real")
                    .eq(x)
                    .val(),

            unidades: parseFloat(
              $(".cantidad")
                .eq(x)
                .text()
            ),

            impuesto: $(".impuesto-final")
              .eq(x)
              .val(),

            nombre_producto: $(".title-detalle")
              .eq(x)
              .text(),

            product_id: $(".product_id")
              .eq(x)
              .val(),

            imei: $(".productoImei")
              .eq(x)
              .val(),

            descuento: descuento,

            porcentaje_descuentop: porcentaje_descuentop,

            promocion: 0
          };

          // console.log("entro por tipo1 pero no esta en producto en promocion");

          productos_list.push(arregloProductos);
        }
      } else {
        if (typeof promocionDescuento != "undefined") {
          if (detalle[0]["descuento"] == 1 && promocionTipo != 1) {
            if (
              validarProductoPromocion(
                promocionId,
                $(".product_id")
                  .eq(x)
                  .val()
              )
            ) {
              //console.log("sisi1");

              cantidad = $("span.cantidad")
                .eq(x)
                .html();

              cantidadPagar = 0;

              var cantidadPDescuento = parseInt(detalle[0]["cantidad"]), //cantidad para obsequio
                cantidadDDescuento = parseInt(detalle[0]["producto_pos"]); //cantidad de obsequio

              function recorrerCantidad(cantidadFaltante) {
                var cantidadContador = cantidadFaltante;

                for (i = 1; i <= cantidadFaltante; i++) {
                  if (i <= cantidadPDescuento) {
                    cantidadPagar++;

                    cantidadContador--;
                  } else if (i <= cantidadPDescuento + cantidadDDescuento) {
                    cantidadContador--;
                  } else if (i >= cantidadPDescuento + cantidadDDescuento) {
                    recorrerCantidad(cantidadContador);

                    break;
                  }
                }
              }

              recorrerCantidad(cantidad);

              if (cantidad == cantidadPagar) {
                arregloProductos = {
                  codigo: $(".codigo-final")
                    .eq(x)
                    .val(),

                  precio_venta:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  unidades: parseFloat(
                    $(".cantidad")
                      .eq(x)
                      .text()
                  ),

                  impuesto: $(".impuesto-final")
                    .eq(x)
                    .val(),

                  nombre_producto: $(".title-detalle")
                    .eq(x)
                    .text(),

                  product_id: $(".product_id")
                    .eq(x)
                    .val(),

                  imei: $(".productoImei")
                    .eq(x)
                    .val(),

                  descuento: descuento,

                  porcentaje_descuentop: porcentaje_descuentop,

                  promocion: 0
                };

                productos_list.push(arregloProductos);
              } else if (cantidad > cantidadPagar) {
                obsequios = parseInt(cantidad - cantidadPagar);
                arregloProductos = {
                  codigo: $(".codigo-final")
                    .eq(x)
                    .val(),

                  precio_venta:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  //'unidades': cantidadPagar,
                  unidades: obsequios,

                  impuesto: $(".impuesto-final")
                    .eq(x)
                    .val(),

                  nombre_producto: $(".title-detalle")
                    .eq(x)
                    .text(),

                  product_id: $(".product_id")
                    .eq(x)
                    .val(),

                  imei: $(".productoImei")
                    .eq(x)
                    .val(),

                  descuento:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  porcentaje_descuentop: 100,

                  promocion: -1
                };

                productos_list.push(arregloProductos);

                arregloProductos = {
                  codigo: $(".codigo-final")
                    .eq(x)
                    .val(),

                  precio_venta:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  //'unidades': parseInt(cantidad-cantidadPagar),
                  unidades: parseInt(cantidadPagar),

                  impuesto: $(".impuesto-final")
                    .eq(x)
                    .val(),

                  nombre_producto: $(".title-detalle")
                    .eq(x)
                    .text(),

                  product_id: $(".product_id")
                    .eq(x)
                    .val(),

                  imei: $(".productoImei")
                    .eq(x)
                    .val(),

                  descuento: descuento,

                  porcentaje_descuentop: porcentaje_descuentop,

                  promocion: 0
                };

                productos_list.push(arregloProductos);
              }
            } else {
              arregloProductos = {
                codigo: $(".codigo-final")
                  .eq(x)
                  .val(),

                precio_venta:
                  descuento != 0
                    ? $(".precio-prod-real-no-cambio")
                        .eq(x)
                        .val()
                    : $(".precio-prod-real")
                        .eq(x)
                        .val(),

                unidades: parseFloat(
                  $(".cantidad")
                    .eq(x)
                    .text()
                ),

                impuesto: $(".impuesto-final")
                  .eq(x)
                  .val(),

                nombre_producto: $(".title-detalle")
                  .eq(x)
                  .text(),

                product_id: $(".product_id")
                  .eq(x)
                  .val(),

                imei: $(".productoImei")
                  .eq(x)
                  .val(),

                descuento: descuento,

                porcentaje_descuentop: porcentaje_descuentop,

                promocion: 0
              };
              productos_list.push(arregloProductos);
            }
          } else if (detalle[0]["descuento"] == 0) {
            var cantidadPDescuento = parseInt(detalle[0]["cantidad"]), //cantidad para obsequio
              cantidadDDescuento = parseInt(detalle[0]["producto_pos"]); //cantidad de obsequio

            cantidadPagar = 0;

            if (
              $("input.precio-prod-real")
                .eq(x)
                .attr("data-promocion") == 1
            ) {
              arregloProductos = {
                codigo: $(".codigo-final")
                  .eq(x)
                  .val(),

                precio_venta:
                  descuento != 0
                    ? $(".precio-prod-real-no-cambio")
                        .eq(x)
                        .val()
                    : $(".precio-prod-real")
                        .eq(x)
                        .val(),

                unidades: parseFloat(
                  $(".cantidad")
                    .eq(x)
                    .text()
                ),

                impuesto: $(".impuesto-final")
                  .eq(x)
                  .val(),

                nombre_producto: $(".title-detalle")
                  .eq(x)
                  .text(),

                product_id: $(".product_id")
                  .eq(x)
                  .val(),

                imei: $(".productoImei")
                  .eq(x)
                  .val(),

                descuento: descuento,

                porcentaje_descuentop: porcentaje_descuentop,

                promocion: 0
              };

              productos_list.push(arregloProductos);
            } else if (
              $("input.precio-prod-real")
                .eq(x)
                .attr("data-promocion") == 2
            ) {
              // console.log("aca",$(".precio-prod-real-no-cambio").eq(x).val(),$('input.precio-prod-real').eq(x).attr('data-cantidad'),$(".cantidad").eq(x).text());

              arregloProductos = {
                codigo: $(".codigo-final")
                  .eq(x)
                  .val(),

                precio_venta:
                  descuento != 0
                    ? $(".precio-prod-real-no-cambio")
                        .eq(x)
                        .val()
                    : $(".precio-prod-real")
                        .eq(x)
                        .val(),

                unidades: $("input.precio-prod-real")
                  .eq(x)
                  .attr("data-cantidad"),

                impuesto: $(".impuesto-final")
                  .eq(x)
                  .val(),

                nombre_producto: $(".title-detalle")
                  .eq(x)
                  .text(),

                product_id: $(".product_id")
                  .eq(x)
                  .val(),

                imei: $(".productoImei")
                  .eq(x)
                  .val(),

                descuento: descuento,

                porcentaje_descuentop: porcentaje_descuentop,

                promocion: 0
              };

              productos_list.push(arregloProductos);

              /*if(parseFloat($(".cantidad").eq(x).text())-$('input.precio-prod-real').eq(x).attr('data-cantidad') == 0)

                            {

                                cantidadObsequio = $('input.precio-prod-real').eq(x).attr('data-cantidad');

                            }else

                            {

                                cantidadObsequio = parseFloat($(".cantidad").eq(x).text())-$('input.precio-prod-real').eq(x).attr('data-cantidad');

                            }*/

              cantidadObsequio =
                parseFloat(
                  $(".cantidad")
                    .eq(x)
                    .text()
                ) -
                $("input.precio-prod-real")
                  .eq(x)
                  .attr("data-cantidad");

              if (cantidadObsequio != 0) {
                arregloProductos = {
                  codigo: $(".codigo-final")
                    .eq(x)
                    .val(),

                  precio_venta:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  unidades: cantidadObsequio,

                  impuesto: $(".impuesto-final")
                    .eq(x)
                    .val(),

                  nombre_producto: $(".title-detalle")
                    .eq(x)
                    .text(),

                  product_id: $(".product_id")
                    .eq(x)
                    .val(),

                  imei: $(".productoImei")
                    .eq(x)
                    .val(),

                  descuento:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  porcentaje_descuentop: 100,

                  promocion: -1
                };

                productos_list.push(arregloProductos);
              }
            } else if (
              $("input.precio-prod-real")
                .eq(x)
                .attr("data-promocion") == 3
            ) {
              arregloProductos = {
                codigo: $(".codigo-final")
                  .eq(x)
                  .val(),

                precio_venta:
                  descuento != 0
                    ? $(".precio-prod-real-no-cambio")
                        .eq(x)
                        .val()
                    : $(".precio-prod-real")
                        .eq(x)
                        .val(),

                unidades: $("input.precio-prod-real")
                  .eq(x)
                  .attr("data-cantidad"),

                impuesto: $(".impuesto-final")
                  .eq(x)
                  .val(),

                nombre_producto: $(".title-detalle")
                  .eq(x)
                  .text(),

                product_id: $(".product_id")
                  .eq(x)
                  .val(),

                imei: $(".productoImei")
                  .eq(x)
                  .val(),

                descuento: descuento,

                porcentaje_descuentop: porcentaje_descuentop,

                promocion: 0
              };

              productos_list.push(arregloProductos);

              cantidadObsequio =
                parseFloat(
                  $(".cantidad")
                    .eq(x)
                    .text()
                ) -
                $("input.precio-prod-real")
                  .eq(x)
                  .attr("data-cantidad");

              if (cantidadObsequio != 0) {
                arregloProductos = {
                  codigo: $(".codigo-final")
                    .eq(x)
                    .val(),

                  precio_venta:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  unidades: cantidadObsequio,

                  impuesto: $(".impuesto-final")
                    .eq(x)
                    .val(),

                  nombre_producto: $(".title-detalle")
                    .eq(x)
                    .text(),

                  product_id: $(".product_id")
                    .eq(x)
                    .val(),

                  imei: $(".productoImei")
                    .eq(x)
                    .val(),

                  descuento:
                    descuento != 0
                      ? $(".precio-prod-real-no-cambio")
                          .eq(x)
                          .val()
                      : $(".precio-prod-real")
                          .eq(x)
                          .val(),

                  porcentaje_descuentop: 100,

                  promocion: -1
                };

                productos_list.push(arregloProductos);
              }
            } else {
              arregloProductos = {
                codigo: $(".codigo-final")
                  .eq(x)
                  .val(),

                precio_venta: precio,

                unidades: parseFloat(
                  $(".cantidad")
                    .eq(x)
                    .text()
                ),

                impuesto: $(".impuesto-final")
                  .eq(x)
                  .val(),

                nombre_producto: $(".title-detalle")
                  .eq(x)
                  .text(),

                product_id: $(".product_id")
                  .eq(x)
                  .val(),

                imei: $(".productoImei")
                  .eq(x)
                  .val(),

                descuento: descuento,

                porcentaje_descuentop: porcentaje_descuentop,

                promocion: 0
              };

              productos_list.push(arregloProductos);
            }
          }
        } else {
          arregloProductos = {
            codigo: $(".codigo-final")
              .eq(x)
              .val(),

            //'precio_venta':(descuento != 0) ? $(".precio-prod-real-no-cambio").eq(x).val() : $(".precio-prod-real").eq(x).val(),
            precio_venta: precio,

            unidades: parseFloat(
              $(".cantidad")
                .eq(x)
                .text()
            ),

            impuesto: $(".impuesto-final")
              .eq(x)
              .val(),

            nombre_producto: $(".title-detalle")
              .eq(x)
              .text(),

            product_id: $(".product_id")
              .eq(x)
              .val(),

            imei: $(".productoImei")
              .eq(x)
              .val(),

            descuento: descuento,

            porcentaje_descuentop: porcentaje_descuentop,

            promocion: 0
          };

          productos_list.push(arregloProductos);
        }
      }
    });

    //console.log(productos_list);

    var cambioBool = false;

    valorCambio = function(forma_pago) {
      if (forma_pago == "efectivo" && cambioBool == false) {
        cambioBool = true;

        return $("#sima_cambio_hidden").val();
      } else {
        return 0;
      }
    };

    pago = {
      valor_entregado: $("#valor_entregado").val(),

      cambio: valorCambio($("#forma_pago").val()),

      forma_pago: $("#forma_pago").val(),

      cod_gift: "",

      transaccion: $("#transaccion").val()
    };

    pago_1 = {
      valor_entregado: $("#valor_entregado1").val(),

      cambio: valorCambio($("#forma_pago1").val()),

      forma_pago: $("#forma_pago1").val(),

      cod_gift: "",

      transaccion: $("#transaccion1").val()
    };

    pago_2 = {
      valor_entregado: $("#valor_entregado2").val(),

      cambio: valorCambio($("#forma_pago2").val()),

      forma_pago: $("#forma_pago2").val(),

      cod_gift: "",

      transaccion: $("#transaccion2").val()
    };

    pago_3 = {
      valor_entregado: $("#valor_entregado3").val(),

      cambio: valorCambio($("#forma_pago3").val()),

      forma_pago: $("#forma_pago3").val(),

      cod_gift: "",

      transaccion: $("#transaccion3").val()
    };

    pago_4 = {
      valor_entregado: $("#valor_entregado4").val(),

      cambio: valorCambio($("#forma_pago4").val()),

      forma_pago: $("#forma_pago4").val(),

      cod_gift: "",

      transaccion: $("#transaccion4").val()
    };

    pago_5 = {
      valor_entregado: $("#valor_entregado5").val(),

      cambio: valorCambio($("#forma_pago5").val()),

      forma_pago: $("#forma_pago5").val(),

      cod_gift: "",

      transaccion: $("#transaccion5").val()
    };

    var pagos = validarMediosDePago();

    if (!pagos.resultado) {
      var errores = "";

      for (var i = 0; i < pagos.errores.length; i++) {
        errores += pagos.errores[i] + "\n";
      }

      swal({
        position: "center",
        type: "error",
        title: "No se pudo efectuar la compra debido a:",
        html: errores,
        showConfirmButton: false,
        timer: 3000
      });

      $("#btnGrandePagar").css("cursor", "pointer");
      $("#btnGrandePagar").bind("click", pagarVenta);

      $("#grabar").removeAttr("disabled");

      e.preventDefault();

      return false;
    }

    //-----------------------------------------------

    //  GiftCard

    //-----------------------------------------------

    //

    //Si el metodo de pago es GiftCard

    cantidadFormasGift = 0;

    //añadimos los codigos de los giftcard que seran canjeados

    giftCardsFormasObjs = [];

    // guardamos los codigos de las giftcards seleccionadas como formas de pago

    for (var k = 0; k < 6; k++) {
      if (formaGiftObj[k] == "pagada") {
        var ind;

        if (k == 0) ind = "";
        else ind = k;

        giftCardsFormasObjs.push($("#valor_entregado_gift" + ind).val());

        // agregamos el codigo de la giftcard a los metodos de pago respectivamente

        if (k == 0) pago.cod_gift = $("#valor_entregado_gift" + ind).val();

        if (k == 1) pago_1.cod_gift = $("#valor_entregado_gift" + ind).val();

        if (k == 2) pago_2.cod_gift = $("#valor_entregado_gift" + ind).val();

        if (k == 3) pago_3.cod_gift = $("#valor_entregado_gift" + ind).val();

        if (k == 4) pago_4.cod_gift = $("#valor_entregado_gift" + ind).val();

        if (k == 5) pago_5.cod_gift = $("#valor_entregado_gift" + ind).val();
      }
    }

    //  Validar que no hayan GiftCards Repetidas

    if (giftCardsFormasObjs.length > 1) {
      for (var j = 0; j < giftCardsFormasObjs.length; j++) {
        for (var k = 0; k < giftCardsFormasObjs.length; k++) {
          if (j != k) {
            if (giftCardsFormasObjs[j] == giftCardsFormasObjs[k]) {
              //alert( "No pueden haber GiftCards repetidas" );
              swal({
                position: "center",
                type: "error",
                title: "No pueden haber GiftCards repetidas",
                showConfirmButton: false,
                timer: 1500
              });

              return 0;
            }
          }
        }
      }
    }

    //Si hay metodos giftcard validados agregamos el metodo de pago giftcard

    if (giftCardsFormasObjs.length > 0) {
      $.ajax({
        url: $canjearGiftCard,

        dataType: "text",

        type: "POST",

        data: { cards: giftCardsFormasObjs },

        error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },

        success: function(data) {
          // console.log(data);
        }
      });
    }

    //Si hay productos giftcard para vender

    if (giftCardsObjs.length > 0) {
      $.ajax({
        url: $pagarGiftCard,

        dataType: "text",

        type: "POST",

        data: { cards: giftCardsObjs },

        error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },

        success: function(data) {
          //console.log(data);
        }
      });
    }

    //-----------------------------------------------

    // FIN GiftCard

    //-----------------------------------------------

    //-----------------------------------------------

    // INICIO Nota Credito

    //-----------------------------------------------

    //

    //Si el metodo de pago es Nota credito

    cantidadFormasNotaCredito = 0;

    //añadimos los codigos de los Nota Credito que seran canjeados

    notaCreditoFormasObjs = [];

    // guardamos los codigos de las nota seleccionadas como formas de pago

    for (var k = 0; k < 6; k++) {
      if (formaNotaCreditoObj[k] == "pagada") {
        var ind;

        if (k == 0) ind = "";
        else ind = k;

        notaCreditoFormasObjs.push(
          $("#valor_entregado_nota_credito" + ind).val()
        );

        // agregamos el codigo de la giftcard a los metodos de pago respectivamente

        if (k == 0)
          pago.nota_credito = $("#valor_entregado_nota_credito" + ind).val();

        if (k == 1)
          pago_1.nota_credito = $("#valor_entregado_nota_credito" + ind).val();

        if (k == 2)
          pago_2.nota_credito = $("#valor_entregado_nota_credito" + ind).val();

        if (k == 3)
          pago_3.nota_credito = $("#valor_entregado_nota_credito" + ind).val();

        if (k == 4)
          pago_4.nota_credito = $("#valor_entregado_nota_credito" + ind).val();

        if (k == 5)
          pago_5.nota_credito = $("#valor_entregado_nota_credito" + ind).val();
      }
    }

    //  Validar que no hayan Nota credito Repetidas

    if (notaCreditoFormasObjs.length > 1) {
      for (var j = 0; j < notaCreditoFormasObjs.length; j++) {
        for (var k = 0; k < notaCreditoFormasObjs.length; k++) {
          if (Number(k) !== Number(j)) {
            if (notaCreditoFormasObjs[j] == notaCreditoFormasObjs[k]) {
              //alert( "No pueden haber Notas de credito repetidas" );
              swal({
                position: "center",
                type: "error",
                title: "No pueden haber Notas de credito repetidas",
                showConfirmButton: false,
                timer: 1500
              });
              return 0;
            }
          }
        }
      }
    }

    //Si hay metodos nota credito validados agregamos el metodo de pago notacredito

    if (notaCreditoFormasObjs.length > 0) {
      $.ajax({
        async: false,
        url: $canjearNotaCredito,
        dataType: "text",
        type: "POST",
        data: { notas: notaCreditoFormasObjs },
        error: function(jqXHR, textStatus, errorThrown) {
          alert(errorThrown);
        },
        success: function(data) {
          //
        }
      });
    }

    //-----------------------------------------------

    // FIN Nota credito

    //-----------------------------------------------
    function comprobar_venta(ventaData) {
      $descuento_generalc = ventaData.descuento_general;
      $totalproductoc = 0;
      $totalpagosc = 0;
      $multiformapagoc = "si";
      $porcentaje_descuentoc = 0;
      $descuento_prodc = 0;
      $precio_ventac = 0;
      $propina_porcentajec = 0;
      $propinac = 0;

      ventaData.productos.forEach(element => {
        //precio
        if (element.impuesto != 0) {
          $precio_venta = parseFloat(
            element.precio_venta +
              parseFloat(element.precio_venta * (element.impuesto / 100))
          );
        } else {
          $precio_venta = element.precio_venta;
        }

        if (element.precio_venta != 0) {
          $porcentaje_descuentop = parseFloat(
            (element.descuento * 100) / element.precio_venta,
            20
          );
        } else {
          $porcentaje_descuentop = 0;
        }

        if ($descuento_generalc != 0) {
          $descuento_prodc = parseFloat(
            parseFloat(
              (parseFloat(
                element.precio_venta +
                  parseFloat((element.precio_venta * element.impuesto) / 100)
              ) *
                $porcentaje_descuentop) /
                100
            ) +
              (($precio_venta -
                parseFloat(
                  (parseFloat(
                    element.precio_venta +
                      parseFloat(
                        (element.precio_venta * element.impuesto) / 100
                      )
                  ) *
                    $porcentaje_descuentop) /
                    100
                )) *
                $descuento_generalc) /
                100
          );
        } else {
          $descuento_prodc = parseFloat(
            (parseFloat(
              element.precio_venta +
                parseFloat((element.precio_venta * element.impuesto) / 100)
            ) *
              $porcentaje_descuentop) /
              100
          );
        }
        // alert("descuento_prodc="+$descuento_prodc);
        if (element.impuesto != 0) {
          $iva =
            (element.precio_venta - $descuento_prodc) *
            (element.impuesto / 100);
          $precio_ventac =
            parseFloat(element.precio_venta - $descuento_prodc) + $iva;
          //$precio_ventac = Math.round(parseFloat((element.precio_venta) + parseFloat((element.precio_venta) * (element.impuesto / 100))));
        } else {
          $precio_ventac = parseFloat(element.precio_venta - $descuento_prodc);
        }

        //productos sin promo
        if (element.promocion != "-1") {
          //$totalc = Math.round(parseFloat((((($precio_ventac) - ($descuento_prodc)) * (element.impuesto / 100)) * element.unidades)));
          $totalc = $precio_ventac * element.unidades;
          $totalproductoc += Math.round($totalc);
        }
      });
      if (typeof ventaData.pago !== "undefined") {
        $valor_entregadoc = ventaData.pago.valor_entregado;
        $cambioc = ventaData.pago.cambio;
        $totalpagosc += $valor_entregadoc - $cambioc;
      }

      if ($multiformapagoc == "si") {
        if (ventaData.pago_1.valor_entregado != "0") {
          $valor_entregadoc = ventaData.pago_1.valor_entregado;
          $cambioc = ventaData.pago_1.cambio;
          $totalpagosc += $valor_entregadoc - $cambioc;
        }
        if (ventaData.pago_2.valor_entregado != "0") {
          $valor_entregadoc = ventaData.pago_2.valor_entregado;
          $cambioc = ventaData.pago_2.cambio;
          $totalpagosc += $valor_entregadoc - $cambioc;
        }
        if (ventaData.pago_3.valor_entregado != "0") {
          $valor_entregadoc = ventaData.pago_3.valor_entregado;
          $cambioc = ventaData.pago_3.cambio;
          $totalpagosc += $valor_entregadoc - $cambioc;
        }
        if (ventaData.pago_4.valor_entregado != "0") {
          $valor_entregadoc = ventaData.pago_4.valor_entregado;
          $cambioc = ventaData.pago_4.cambio;
          $totalpagosc += $valor_entregadoc - $cambioc;
        }
        if (ventaData.pago_5.valor_entregado != "0") {
          $valor_entregadoc = ventaData.pago_5.valor_entregado;
          $cambioc = ventaData.pago_5.cambio;
          $totalpagosc += $valor_entregadoc - $cambioc;
        }
      }

      if (typeof ventaData.subtotal_propina_input !== "undefined") {
        if (ventaData.subtotal_propina_input != 0) {
          $propinac = 0;
          $propina_porcentajec = ventaData.propina;
        }
      }

      if (db == 4017) {
        if (ventaData.total_venta != 0) {
          $total_ventac = ventaData.total_venta;
        } else {
          $total_ventac = ventaData.total_venta1;
        }
        /* alert("totalproductoc=" + $totalproductoc); 
                alert("total_ventac=" + $total_ventac); 
                alert("totalpagosc=" + $totalpagosc);     */

        if ($totalproductoc == $total_ventac && $total_ventac == $totalpagosc) {
          return 1; //bien
        } else {
          return 0; //mal no coinciden
        }
      }
    }

    if (descuentogeneral != 0) {
      $("#descuento_general").val(descuentogeneral);
    }
    if ((finalto == $("#total").val() && db == 4017) || db != 4017) {
      band = false;
      //console.log("grabar la venta");

      correoemail = "";
      if (_enviarFactura) {
        correoemail = $("#enviarFacturaModal")
          .find("#emailCliente")
          .val();
      }

      // MARK: datos de la venta
      var ventaData = {
        cliente: $("#id_cliente").val(),
        productos: productos_list,
        vendedor: $("#vendedor").val(),
        vendedor_2: $("#vendedor_2").val(),
        total_venta: $("#total").val(),
        total_venta1: finalto,
        pago: pago,
        pago_1: pago_1,
        pago_2: pago_2,
        pago_3: pago_3,
        pago_4: pago_4,
        pago_5: pago_5,
        fecha_v: $("#fecha_vencimiento_venta").val(),
        nota: $("#notas").val(),
        descuento_general: $("#descuento_general").val(),
        subtotal_propina_input: $("#subtotal_propina_input").val(),
        subtotal_input: $("#subtotal_input").val(),
        sobrecostos: $("#sobrecostos").val(),
        propina: $("#sobrecostos_input").val(),
        tipo_propina: $("input:radio[name=tipo_propina]:checked").val(),
        promocion: promocion,
        comandarestaurante: comandarestaurante,
        id_fact_espera: $("#id_fact_espera").val(),
        enviarFactura: correoemail,
        aleatorio: $("#aleatorio").val(),
        facturacion_electronica: $('#facturacion-electronica-check').is(':checked'),
        grabar_sin_pago: $("#venta_sin_pago").val(),
        domiciliario: $("#domiciliario").val(),
        id_cliente_domicilio: $("#id_cliente_domicilio").val(),
        presione_domicilio: $("#presione_domicilio").val(),
        datos_cliente_domicilio: $("#datos_cliente_domicilio").val(),
        telefono_domicilio: $("#telefono_domicilio").val(),
        direccion_domicilio: $("#direccion_domicilio").val(),
        user_puntos_leal: userPuntosLealSelected
      };

      if (__decimales__ == 0 && db == 4017) {
        band = false;
        //resp = comprobar_venta(ventaData);
        //alert(resp);
        resp = 1;
        if (resp == 1) {
          $.ajax({
            url: $sendventas,
            dataType: "json",
            type: "POST",
            data: ventaData,
            error: function(jqXHR, textStatus, errorThrown) {
              alert(errorThrown);
            },
            success: function(data) {
              if (data.success == true) {
                if (offline == "backup") {
                  // Eliminamos objeto giftcard para que no de error al guardar en local
                  delete ventaData["pago"]["cod_gift"];
                  delete ventaData["pago_1"]["cod_gift"];
                  delete ventaData["pago_2"]["cod_gift"];
                  delete ventaData["pago_3"]["cod_gift"];
                  delete ventaData["pago_4"]["cod_gift"];
                  delete ventaData["pago_5"]["cod_gift"];
                  appOffline.guardarVenta(
                    ventaData,
                    function() {
                      appOffline.truncateVentas(
                        function() {
                          imprimirFactura(data);
                        },
                        function() {
                          imprimirFactura(data);
                        }
                      );
                    },
                    function() {
                      imprimirFactura(data);
                    }
                  );
                } else {
                  imprimirFactura(data);
                }
              } else {
                if (data.id == 0) {
                  alert(data.mensaje);
                } else {
                  alert("Ha ocurrido un error venta no creada");
                }
              }
            }
          });
        } else {
          band = false;
          //alert("El valor a pagar no corresponde al total de la factura, por favor verifique");
          swal({
            position: "center",
            type: "error",
            title: "Error",
            html:
              "El valor a pagar no corresponde al total de la factura, por favor verifique",
            showConfirmButton: false,
            timer: 2000
          });
          $("#grabar").prop("disabled", false);
        }
      } else {
        band = true;
      }
      productos = productos_list.length;
      if (band && productos > 0) {
        //Si tenemos la db local creada entonces guardamos
        console.log($sendventas)
        $.ajax({
          url: $sendventas,
          dataType: "json",
          type: "POST",
          data: ventaData,
          error: function(jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
          },
          success: function(data) {
            if (data.success == true) {
              if (data.id == 1) {
                $.ajax({
                  url: $enviaCorreoPrimeraVenta,
                  dataType: "json",
                  type: "POST",
                  data: data.id,
                  success: function(data) {
                      console.log('Correo de primera venta enviado');
                  }
                });
              }
              console.log(data);
              if (new_fast_print) {
                let json = data.fastPrintJson;
                console.log(json);
                conn.send(json);
                window.location = $reloadThis + "?var=" + $buscador;
                return;
              } else if (data.impresion_rapida == "si") {
                window.location = $reloadThis + "?var=" + $buscador;
                return;
              }

              if (offline == "backup") {
                // Eliminamos objeto giftcard para que no de error al guardar en local
                delete ventaData["pago"]["cod_gift"];
                delete ventaData["pago_1"]["cod_gift"];
                delete ventaData["pago_2"]["cod_gift"];
                delete ventaData["pago_3"]["cod_gift"];
                delete ventaData["pago_4"]["cod_gift"];
                delete ventaData["pago_5"]["cod_gift"];
                appOffline.guardarVenta(
                  ventaData,
                  function() {
                    appOffline.truncateVentas(
                      function() {
                        imprimirFactura(data);
                      },
                      function() {
                        imprimirFactura(data);
                      }
                    );
                  },
                  function() {
                    imprimirFactura(data);
                  }
                );
              } else {
                imprimirFactura(data);
              }
            } else {
              if (data.id == 0) {
                alert(data.mensaje);
                location.reload(true);
              } else {
                //alert("Ha ocurrido un error venta no creada");
                swal({
                  position: "center",
                  type: "error",
                  title: "Ha ocurrido un error venta no creada",
                  showConfirmButton: false,
                  timer: 1500
                });
              }
            }
          }
        });
      } else {
        swal({
          position: "center",
          type: "error",
          title: "Error",
          html: "Debe seleccionar por lo menos un producto",
          showConfirmButton: false,
          timer: 1500
        });
        $("#grabar").prop("disabled", false);
      }
    } else {
      console.log("2");
      stop;
      return false;
      //alert("El valor a pagar no corresponde al total de la factura, por favor verifique");
      swal({
        position: "center",
        type: "error",
        title: "Error",
        html:
          "El valor a pagar no corresponde al total de la factura, por favor verifique",
        showConfirmButton: false,
        timer: 1500
      });
      $("#grabar").prop("disabled", false);
    }
  }

  $("#domicilio").click(function() {
    cliente = $("#id_cliente_domicilio").val();

    if (cliente != "") {
      $("#datos_cliente_domicilio").val($("#datos_cliente").val());
      $("#datos_cliente_domicilio").prop("disabled", true);
      //si tengo datos cambio a editar
      $("#icocambiar").removeClass("ico-plus");
      $("#icocambiar").addClass("ico-pencil");
      $("#add-new-client").data("id", "1");
    }

    $("#dialog-domicilio-form").dialog("open");
  });

  $("#puntos_leal").click(function() {
    $("#dialog-puntos-leal-form").dialog("open");
  });

  $("#grabar_sin_pago").click(function(e) {
    $("#grabar").prop("disabled", true);
    $("#grabar_sin_pago").prop("disabled", true);
    $("#venta_sin_pago").val(1);
    cantidadsindescuentogeneral = 1;
    $("#dialog-forma-pago-form").dialog("close");
    $("#backPopUp").css("display", "none");

    _enviarFactura = false;

    if (enviar_factura == "estandar") {
      enviarFacturaForm();
      $("#enviarFacturaModal").modal("show");
    } else if (enviar_factura == "auto") {
      enviarFacturaForm();
      enviarFacturaEmail();
    } else {
      var _enviarFactura = false;
      $("#grabar").prop("disabled", true);
      grabar();
    }
  });

  $("#grabar").click(function(e) {
    console.log("grabar 2");
    $("#grabar").prop("disabled", true);

    cantidadsindescuentogeneral = 1;
    $("#dialog-forma-pago-form").dialog("close");
    $("#backPopUp").css("display", "none");

    _enviarFactura = false;

    if (enviar_factura == "estandar") {
      enviarFacturaForm();
      $("#enviarFacturaModal").modal("show");
    } else if (enviar_factura == "auto") {
      enviarFacturaForm();
      enviarFacturaEmail();
    } else {
      var _enviarFactura = false;
      $("#grabar").prop("disabled", true);
      grabar();
    }
  });

  $("#cancelar").click(function() {
    cantidadsindescuentogeneral = 0;
    tipo_propina = $("input:radio[name=tipo_propina]:checked").val();
    if (tipo_propina == "valor") {
      $("#sobrecostos_input").val($("#sobrecostos_input_valor").val());
    }
    $("#dialog-forma-pago-form").dialog("close");
    $("#backPopUp").css("display", "none");
  });

  $("#grabar_plan").click(function() {
    $(this).attr("disabled", true);

    //============================================

    //

    //      VENTA PLAN SEPARE

    //

    //============================================

    $(this).attr("disabled", true);

    productos_list = new Array();

    $(".title-detalle").each(function(x) {
      var descuento = 0;
      var porcentaje_descuentop = 0;

      // if ($(".precio-prod-descuento").eq(x).val()>0){
      //calculando porcentaje general
      if (descuentogeneral != 0) {
        descuento =
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val() -
          $(".precio-prod-descuento")
            .eq(x)
            .val();
      } else {
        descuento =
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val() -
          $(".precio-prod-real")
            .eq(x)
            .val();
      }

      //calculando porcentaje interno
      // if ($(".precio-prod-real").eq(x).val() != 0) {
      descuentopi =
        $(".precio-prod-real-no-cambio")
          .eq(x)
          .val() -
        $(".precio-prod-real")
          .eq(x)
          .val();
      porcentaje_descuentop =
        parseFloat(descuentopi * 100) /
        parseFloat(
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val()
        );
      /*     alert("descuentopi=" + descuentopi);
                alert("porcentaje_descuentop=" + porcentaje_descuentop);*/
      // } else {
      //porcentaje_descuentop = 0;
      //  }

      //}

      //evaluar porcentaje_producto_interno

      //descuento = $(".precio-prod-real-no-cambio").eq(x).val() - $(".precio-prod-real").eq(x).val();

      if (
        parseInt(
          $(".precio-prod-real-no-cambio")
            .eq(x)
            .val()
        ) <
        parseInt(
          $(".precio-prod-real")
            .eq(x)
            .val()
        )
      ) {
        descuento = 0;
      }

      productos_list[x] = {
        codigo: $(".codigo-final")
          .eq(x)
          .val(),

        precio_venta:
          descuento != 0
            ? $(".precio-prod-real-no-cambio")
                .eq(x)
                .val()
            : $(".precio-prod-real")
                .eq(x)
                .val(),

        unidades: parseFloat(
          $(".cantidad")
            .eq(x)
            .text()
        ),

        impuesto: $(".impuesto-final")
          .eq(x)
          .val(),

        nombre_producto: $(".title-detalle")
          .eq(x)
          .text(),

        product_id: $(".product_id")
          .eq(x)
          .val(),

        descuento: descuento,

        porcentaje_descuentop: porcentaje_descuentop,

        margen_utilidad:
          ($(".precio-prod-real")
            .eq(x)
            .val() -
            $(".precio-compra-real-selected")
              .eq(x)
              .val()) *
          parseInt(
            $(".cantidad")
              .eq(x)
              .text()
          )
      };

      pago = {
        valor_entregado: $("#valor_entregado_plan").val(),

        cambio: 0,

        forma_pago: $("#forma_pago_plan").val()
      };
    });

    $sendventas = controladorSepare;

    $.ajax({
      url: $sendventas,
      dataType: "text",
      type: "POST",
      data: {
        cliente: $("#id_cliente").val(),
        productos: productos_list,
        vendedor: $("#vendedor").val(),
        total_venta: $("#total").val(),
        pago: pago,
        nota: $("#notas").val(),
        fecha_vencimiento: $("#fecha_vencimiento").val(),
        descuento_general: $("#descuento_general").val(),
        subtotal_propina_input: $("#subtotal_propina_input").val(),
        subtotal_input: $("#subtotal_input").val(),
        sobrecostos: $("#sobrecostos").val(),
        propina: $("#sobrecostos_input").val(),
        id_fact_espera: $("#id_fact_espera").val(),
        nota_plan_separe: $("#nota_plan_separe").val()
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert(errorThrown);
      },
      success: function(data) {
        var id = $id_user_mix;
        var email = $email_mix;
        var nombre_empresa = $empresa_mix;
        mixpanel.identify(id);

        mixpanel.track("Plan Separe", {
          $email: email,
          $empresa: nombre_empresa
        });

        $("#dialog-forma-pago-form").dialog("close");
        $("#dialog-plan-separe-form").dialog("close");
        $("#backPopUp").css("display", "none");

        swal({
          position: "center",
          type: "success",
          title: "Plan Separe",
          html: "Plan Separe Registrado Exitosamente",
          showConfirmButton: false,
          timer: 2000
        });
        setTimeout(function() {
          location.reload(true);
        }, 2010);
      }
    });
  });

  $("#promocion").on("change", function(e) {
    if (
      !$("#facturasTable tr")
        .eq(0)
        .hasClass("nothing")
    ) {
      pasarPromocion();

      calculate();
      actualizarEspera();
    }
  });

  $(".modal").on("shown.bs.modal", function() {
    $(this)
      .find("[autofocus]")
      .focus();
  });

  $("#facturasTable tr").live("click", function() {
    if (
      !$("#facturasTable tr")
        .eq(0)
        .hasClass("nothing")
    ) {
      //  alert($('.codigo').eq($(this).index()).val());
      //alert("tipo1");
      var val_stock = $(".val_stock")
        .eq($(this).index())
        .val();
      var vendernegativo = $(".vendernegativo")
        .eq($(this).index())
        .val();
      var tipo_producto = $(".tipo_producto")
        .eq($(this).index())
        .val();
      var imei = $(".imei")
        .eq($(this).index())
        .val();
      var id_p = $(".id_producto")
        .eq($(this).index())
        .val();
      //vaiables utilizada en funcion

      var isGiftCard = $("#facturasTable .giftcard")
        .eq($(this).index())
        .val();
      var matching = $('.product_id[value="' + id_p + '"]').index();
      var id_promocion = $("#promocion").val();
      var impuesto = $(".impuesto")
        .eq($(this).index())
        .val();
      var precio_real = parseFloat(
        $(".precio-real")
          .eq($(this).index())
          .val()
      );
      var nombre_p = $(".nombre_producto")
        .eq($(this).index())
        .text();
      var precio_compra_real = $(".precio-compra-real")
        .eq($(this).index())
        .val();
      var codigo = $(".codigo")
        .eq($(this).index())
        .val();
      var precio = $(".precio")
        .eq($(this).index())
        .text();
      bandera = 1;
      //alert("id_p=" + id_p);
      if (imei == 1) {
        $("#modal-imei").modal("show");
        var producto_imei_id = $(".id_producto")
          .eq($(this).index())
          .val();
        $("#modal-imei").attr("data-imei", producto_imei_id);
        return;
      }
      if ($("#" + id_p).length) {
        var unidades =
          parseFloat(
            $("#" + id_p)
              .find(".cantidad")
              .text()
          ) + parseFloat(1);
      } else {
        unidades = 1;
      }
      //var unidades = parseFloat($('.cantidad').eq($(this).index()).text()) + parseFloat(1);

      if (isNaN(unidades)) {
        unidades = 1;
      }

      bandera = 1;
      if (vendernegativo == 0 && val_stock < unidades) {
        if (tipo_producto == 3 || tipo_producto == 2) {
          //verifico mis ingredientes si tengo disponible
          $.ajax({
            async: false, //mostrar variables fuera de el function
            url: $buscarstockespeciales,
            type: "POST",
            dataType: "json",
            data: {
              id: id_p,
              tipo: tipo_producto,
              unidades: unidades
            },
            success: function(data) {
              if (data.success == 0) {
                bandera = 0;
                //alert("producto="+id_p);
                Swal({
                  title: "Alerta",
                  html: data.msj,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#5ca745",
                  cancelButtonColor: "#e62626",
                  confirmButtonText: "¿Deseas realizar la venta?",
                  cancelButtonText: "No vender"
                }).then(result => {
                  if (result.value) {
                    //si acepta vender
                    bandera = 1;
                    // alert("producto=" + id_p);
                    venderproducto(
                      bandera,
                      val_stock,
                      vendernegativo,
                      tipo_producto,
                      imei,
                      id_p,
                      isGiftCard,
                      matching,
                      id_promocion,
                      precio_real,
                      impuesto,
                      nombre_p,
                      precio_compra_real,
                      codigo,
                      precio,
                      unidades
                    );
                  }
                });
              }
            }
          });
        } else {
          bandera = 0;
          swal(
            "Alerta",
            "No posees stock para la venta de este producto",
            "warning"
          );
        }
      } //else{

      venderproducto(
        bandera,
        val_stock,
        vendernegativo,
        tipo_producto,
        imei,
        id_p,
        isGiftCard,
        matching,
        id_promocion,
        precio_real,
        impuesto,
        nombre_p,
        precio_compra_real,
        codigo,
        precio,
        unidades
      );
    }
  });

  function venderproducto(
    bandera,
    val_stock,
    vendernegativo,
    tipo_producto,
    imei,
    id_p,
    isGiftCard,
    matching,
    id_promocion,
    precio_real,
    impuesto,
    nombre_p,
    precio_compra_real,
    codigo,
    precio,
    unidades
  ) {
    // alert("ddd=" + id_p);
    if (bandera == 1) {
      var totalProducto = precio_real + (precio_real * impuesto) / 100;

      // matching = -1 -> aun no esta listado en la factura
      // matching =  1 -> = ya esta listado

      if (matching == -1) {
        if ($sobrecosto == "si" && $nit != "320001127839") {
          var nom;
          $.ajax({
            async: false, //mostrar variables fuera de el function
            url: $impuestosnom,
            type: "POST",
            dataType: "text",
            data: {
              //imp: $('.impuesto').eq($(this).index()).val()
              imp: impuesto
            },
            success: function(data) {
              nom = data;
            }
          });
        }
        //title-detalle
        rowHtml = "<tr id='" + id_p + "'>";
        rowHtml +=
          "<input type='hidden' class='vendernegativo_selected' value='" +
          vendernegativo +
          "'/><input type='hidden' class='tipo_producto_selected' value='" +
          tipo_producto +
          "'/><input type='hidden' class='stock_selected' value='" +
          val_stock +
          "'/><input type='hidden' class='precio-compra-real-selected' value='" +
          precio_compra_real +
          "'/><input type='hidden' value='" +
          id_p +
          "' class='product_id'/><input type='hidden' class='codigo-final' value='" +
          codigo +
          "'><input type='hidden' class='impuesto-final' value='" +
          impuesto +
          "'>";
        rowHtml +=
          "<td width='10%' ><a class='button red delete' href='#'><div class='icon'><span class='wb-close'></span></div></a></td>";
        rowHtml +=
          "<td width='40%'  style='text-align: left !important;'><span class='title-detalle text-info'><input type='hidden' value='" +
          impuesto +
          "' class='detalles-impuesto'>" +
          nombre_p +
          "</span><span class='imei-title'></span></td>";
        rowHtml +=
          "<td width='10%' ><span data-id='" +
          id_p +
          "' data-stock='" +
          val_stock +
          "' data-vendernegativo='" +
          vendernegativo +
          "' data-tipo_producto='" +
          tipo_producto +
          "' data-imei='" +
          $("#codigo_barra_imei").val() +
          "' class='label label-success cantidad'>" +
          1 +
          "</span><input type='hidden' class='nombre_impuesto' value='" +
          nom +
          "'>";
        rowHtml +=
          "<input type='hidden' class='promocionPrecio' value='0'><input type='hidden' class='promocionIva' value='0'><input type='hidden' class='productoImei' value='" +
          $("#codigo_barra_imei").val() +
          "'></td>";

        if (
          validarProductoPromocion(id_promocion, id_p) &&
          promocionTipo == 1
        ) {
          if ($sinprecio == "si") {
            rowHtml +=
              "<td width='20%'  class='contCalc'><input type='hidden' class='precio-prod-real' data-promocion='1' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-descuento' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" +
              precio_real +
              "'/></td>";
          } else {
            rowHtml +=
              "<td width='20%'  class='contCalc'><span class='label label-success precio-prod'  onClick='calculadora_descuento(" +
              totalProducto +
              ");'>" +
              precio +
              "</span><input type='hidden' data-promocion='1' class='precio-prod-real' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-descuento' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" +
              precio_real +
              "'/></td>";
          }
        } else {
          if ($sinprecio == "si") {
            rowHtml +=
              "<td width='20%'  class='contCalc'><span class='precio-prod'>" +
              precio +
              "</span><input type='hidden' class='precio-prod-real' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-descuento' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" +
              precio_real +
              "'/></td>";
          } else {
            rowHtml +=
              "<td width='20%'  class='contCalc'><span class='label label-success precio-prod'  onClick='calculadora_descuento(" +
              totalProducto +
              ");'>" +
              precio +
              "</span><input type='hidden' class='precio-prod-real' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-descuento' value='" +
              precio_real +
              "'/><input type='hidden' class='precio-prod-real-no-cambio' value='" +
              precio_real +
              "'/></td>";
          }
        }
        rowHtml +=
          "<td width='20%' ><span class='precio-calc'>" +
          precio +
          "</span><input type='hidden' value='precio-calc-real' value='" +
          precio_real +
          "'/></td>";
        rowHtml += "</tr>";
        var $objDom = null;
        if (
          $("#productos-detail tr")
            .eq(0)
            .hasClass("nothing")
        ) {
          $("#productos-detail").html("");
          $objDom = $(rowHtml)
            .hide()
            .appendTo("#productos-detail")
            .fadeIn("slow");
        } else {
          $objDom = $(rowHtml)
            .hide()
            .appendTo("#productos-detail")
            .fadeIn("slow");
        }
        // Si es giftcard ocultamos los botones de cambiar precio y cantidad de el listado de productos
        if (isGiftCard == "1") {
          $($objDom[0])
            .find(".cantidad")
            .hide();
          $($objDom[0])
            .find(".precio-prod")
            .hide();
        }
        // agregamos el si es giftcard a la lista de productos seleccionados
        $($objDom[0])
          .find(".product_id")
          .after(
            '<input type="hidden" class="giftcard" value="' + isGiftCard + '">'
          );
      } else {
        //var id_p = $('.id_producto').eq($(this).index()).val();
        bandera = 1;
        if ($("#" + id_p).length) {
          var cant = parseFloat(
            $("#" + id_p)
              .find(".cantidad")
              .text()
          );
          //var tipo_producto = $('.tipo_producto').eq($(this).index()).val();
          //var unidades = $('.id_producto').eq($(this).index()).val();

          if (bandera == 1) {
            // Si es giftcard no se le permitira añadir mas productos
            if (isGiftCard == "0") {
              //parent = $('.product_id[value="' + id_producto + '"]').parent().parent().index();
              parent = $('.product_id[value="' + id_p + '"]')
                .parent()
                .index();
              cantidad =
                parseInt(
                  $(".cantidad")
                    .eq(parent)
                    .text()
                ) + 1;
              id_p;
              $(".cantidad")
                .eq(parent)
                .text(cantidad);
            } else {
              $(".cantidad")
                .eq(parent)
                .text(cant + 1);
            }
          }
        }
      }
      pasarPromocion();
      calculate();
      actualizarEspera();
    } // End condition vender negativo
  }

  $(".vendedorHideButton").click(function() {
    $("#vendedorBlock").slideToggle("slow");
  });

  $(".clienteHideButton").click(function() {
    $("#clienteBlock").slideToggle("slow");
  });

  $("#clienteBlock, #vendedorBlock").slideUp();

  /*----------------------------------------------------

    | PAGAR VENTA                                        |

    ------------------------------------------------------*/

  pagarVenta = function() {
    //verificar si por lo menos hay un producto para la compra
    productos = $("#cantidad-total").html();
    // Reseteamos primer metodo de pago a efectivo
    if (productos > 0) {
      $("#backPopUp").css("display", "block");

      setDomGift("", 0);

      //debugger;

      $("#valor_entregado").removeAttr("disabled");

      $("#valor_entregado1").removeAttr("disabled");

      $("#valor_entregado2").removeAttr("disabled");

      $("#valor_entregado3").removeAttr("disabled");

      $("#valor_entregado4").removeAttr("disabled");

      $("#valor_entregado5").removeAttr("disabled");

      // Si el pago es estandar , se muestra el dialog

      if (pagoAutomatico == "estandar") {
        $(this).unbind("click");
        $("#btnGrandePagar").css("cursor", "not-allowed");
        pagar();
      } else if (pagoAutomatico == "auto") {
        $("#grabar").prop("disabled", true);

        $(this).unbind("click");

        $("#btnGrandePagar").css("cursor", "not-allowed");

        $("#pagarTitulo").html("Pagando...");

        $("#btnGrandePagar").unbind("click");

        pagar();

        $("#dialog-forma-pago-form")
          .parent()
          .css("visibility", "hidden");
        $("#backPopUp").css("display", "none");
        grabar();
      }
    } else {
      swal({
        position: "center",
        type: "error",
        title: "Error",
        html: "Debe seleccionar por lo menos un producto para facturar",
        showConfirmButton: false,
        timer: 1500
      });
    }
  };

  $("#btnGrandePagar").bind("click", pagarVenta);

  $("#cancelar").bind("click", function() {
    $("#btnGrandePagar").css("cursor", "pointer");
    $("#btnGrandePagar").bind("click", pagarVenta);
  });

  $("#planSepare").click(function() {
    //verificar si por lo menos hay un producto para la compra
    productos = $("#cantidad-total").html();

    if (productos > 0) {
      $("#dialog-plan-separe-form").dialog("open");
      $("#descuento_general").val("0");
      var propina = $("#sobrecostos_input").val() || 10,
        valorTotal = $("#subtotal_input").val(),
        total = Math.ceil(parseFloat((valorTotal * propina) / 100));

      $("#propina_output").html(propina + "% - " + total);

      $("#propina_input").val(propina);

      var propina_pro = $("#sobrecostos_input").val() || 10,
        valorTotal_pro = $("#subtotal_propina_input").val(),
        total_pro = parseFloat((valorTotal_pro * propina_pro) / 100);

      $("#propina_output_pro_plan").html(
        propina_pro + "% - " + formatDollar(total_pro)
      );

      $("#propina_input_pro_plan").val(propina_pro);

      $("#valor_pagar_propina_plan").html(
        formatDollar(parseFloat($("#total").val()) + parseFloat(total_pro))
      );

      var valor_total_entregado =
        parseFloat($("#total").val()) + parseFloat(total_pro);

      $("#valor_pagar_plan").val(formatDollar(parseFloat($("#total").val())));

      $("#valor_entregado_plan").val(Math.round(valor_total_entregado));

      $("#valor_pagar_hidden_plan").val(Math.round(valor_total_entregado));

      $("#sima_cambio_plan").val(parseInt("0"));
    } else {
      swal({
        position: "center",
        type: "error",
        title: "Error",
        html: "Debe seleccionar por lo menos un producto para facturar",
        showConfirmButton: false,
        timer: 1500
      });
    }
  });

  $("#nota").click(function() {
    $("#dialog-nota-form").dialog("open");
  });

  // si cerramos el dialog de nota guardamos la nota comanda para la respectiva factura espera si esta activa alguna

  $("#dialog-nota-form").on("dialogclose", function(event) {
    guardarNotaEspera();
  });

  $("#sobrecosto").click(function() {
    $("#dialog-sobrecosto-form").dialog("open");
  });

  /*----------------------------------------------------

    | VENTA PENDIENTE                                     |

     ------------------------------------------------------*/

  $(
    "#valor_entregado, #valor_entregado1, #valor_entregado2, #valor_entregado3, #valor_entregado4, #valor_entregado5"
  ).keyup(function(e) {
    ///cambioVentaPendiente();
  });

  $("#dialog-forma-pago-form").dialog({
    autoOpen: false,
    //height: 400,
    height: $height,
    width: $width,
    modal: true,
    close: function() {
      if (cantidadsindescuentogeneral == 0) {
        tipo_propina = $("input:radio[name=tipo_propina]:checked").val();
        if (tipo_propina == "valor") {
          $("#sobrecostos_input").val($("#sobrecostos_input_valor").val());
        }

        $(".descuento").val(0);
        $("#descuento_general").val(0);
        descuentogeneral = 0;
        // $("#valor_pagar").val(0);
        // $("#valor_entregado").val(0);

        boton = document.getElementById("btn-accept-descuento");
        if (boton === null) {
          $("body").append("<input type='hidden' id='btn-accept-descuento'>");
          $("body").append("<input type='hidden' class='descuento' value='0'>");
          $("#btn-accept-descuento").click();
          $("#btn-accept-descuento").remove();
          $(".descuento").remove();
        }

        if ($("#valor_entregado").val() >= 0) {
          $("#forma_pago")
            .val("efectivo")
            .trigger("change");
          $("#valor_entregado").val(0);
          $("#transaccion").val("");
          $("#valor_entregado_nota_credito").val("");
          $("#valor_entregado_gift").val("");
        }

        if ($("#valor_entregado1").val() >= 0) {
          $("#forma_pago1")
            .val("efectivo")
            .trigger("change");
          $("#valor_entregado1").val(0);
          $("#transaccion1").val("");
          $("#valor_entregado_nota_credito1").val("");
          $("#valor_entregado_gift1").val("");
          $("#contenido_a_mostrar1").hide();
        }

        if ($("#valor_entregado2").val() >= 0) {
          $("#forma_pago2")
            .val("efectivo")
            .trigger("change");
          $("#valor_entregado2").val(0);
          $("#transaccion2").val("");
          $("#valor_entregado_nota_credito2").val("");
          $("#valor_entregado_gift2").val("");
          $("#contenido_a_mostrar2").hide();
        }

        if ($("#valor_entregado3").val() >= 0) {
          $("#forma_pago3")
            .val("efectivo")
            .trigger("change");
          $("#valor_entregado3").val(0);
          $("#transaccion3").val("");
          $("#valor_entregado_nota_credito3").val("");
          $("#valor_entregado_gift3").val("");
          $("#contenido_a_mostrar3").hide();
        }

        if ($("#valor_entregado4").val() >= 0) {
          $("#forma_pago4")
            .val("efectivo")
            .trigger("change");
          $("#valor_entregado4").val(0);
          $("#transaccion4").val("");
          $("#valor_entregado_nota_credito4").val("");
          $("#valor_entregado_gift4").val("");
          $("#contenido_a_mostrar4").hide();
        }

        if ($("#valor_entregado5").val() >= 0) {
          $("#forma_pago5")
            .val("efectivo")
            .trigger("change");
          $("#valor_entregado5").val(0);
          $("#transaccion5").val("");
          $("#valor_entregado_nota_credito5").val("");
          $("#valor_entregado_gift5").val("");
          $("#contenido_a_mostrar5").hide();
        }
      }
    }
  });

  $("#cancelar").click(function() {
    $("#backPopUp").css("display", "none");
    cantidadsindescuentogeneral = 0;
    $("#dialog-forma-pago-form").dialog("close");
  });
});

/*--------------------------------------------------

| Clientes                                         |

---------------------------------------------------*/

var cliente_selecionado = {};
var cliente_grupo = {};
var sourceAutociomplete;

if (clientesCartera == 1) {
  sourceAutociomplete = $urlclienteCartera;
} else {
  sourceAutociomplete = $urlcliente;
}

//$("#datos_cliente").autocomplete({
$("#datos_cliente").autocomplete({
  source: sourceAutociomplete,
  minLength: 1,
  select: function(event, ui) {
    completar(event, ui, $(this));
  },
  change: function(event, ui) {
    if ($(this).val() == "") {
      cliente_selecionado = "";
      cliente_grupo = 0;
    } else {
      completar(event, ui, $(this));
    }
  }
});

//$("#datos_cliente_domicilio").autocomplete({
$("#datos_cliente_domicilio").keyup(function() {
  if ($(this).val() != "") {
    html = "";
    $.ajax({
      url: sourceAutociomplete,
      dataType: "json",
      type: "get",
      data: {
        term: $(this).val()
      },
      success: function(data) {
        console.log(data);
        $("#lista-clientes-domicilios").html("");
        $.each(data, function(key, val) {
          console.log(val.descripcion);
          html =
            '<li class="clientesdomicilios" data-value="' +
            val.value +
            '" data-descripcion="' +
            val.descripcion +
            '" data-grupo="' +
            val.grupo_clientes_id +
            '" data-lista="' +
            val.lista +
            '" data-id="' +
            val.id +
            '">' +
            val.value +
            "</li>";
          $("#lista-clientes-domicilios").append(html);
          $("#cliente_domicilio").css("height", "20vh");
        });
      }
    });
    $("#contenedor-lista-clientes-domicilios").css("display", "block");
  } else {
    $("#contenedor-lista-clientes-domicilios").css("display", "none");
  }
});

$(document).on("click", ".clientesdomicilios", function() {
  $("#contenedor-lista-clientes-domicilios").css("display", "none");
  value = $(this).data("value");
  descripcion = $(this).data("descripcion");
  grupo = $(this).data("grupo");
  lista = $(this).data("lista");
  id = $(this).data("id");

  $("#id_cliente").val(id);
  $("#id_cliente_domicilio").val(id);
  $("#id_cliente_plan").val(id);
  $("#otros_datos").val(descripcion);
  $("#datos_cliente").val(value);
  $("#datos_cliente_domicilio").val(value);

  descripcion = descripcion.split(",");
  direccion_domicilio = descripcion[1];
  dire = descripcion[2].trim();
  dire2 = descripcion[3].trim();

  if (dire != "" && dire2 != "") {
    telefono_domicilio = dire + " / " + dire2;
  } else {
    if (dire != "") {
      telefono_domicilio = dire;
    }
    if (dire2 != "") {
      telefono_domicilio = dire2;
    }
  }
  //si tengo datos cambio a editar
  $("#icocambiar").removeClass("ico-plus");
  $("#icocambiar").addClass("ico-pencil");
  $("#add-new-client").data("id", "1");
  $("#direccion_domicilio").val(direccion_domicilio);
  $("#direccion_domicilio_label").html(direccion_domicilio);
  $("#direccion_domicilio").hide();
  $("#direccion_domicilio_label").show();
  $("#telefono_domicilio").val(telefono_domicilio);
  $("#telefono_domicilio_label").html(telefono_domicilio);
  $("#telefono_domicilio").hide();
  $("#telefono_domicilio_label").show();

  $("#id_lista").val("0");
  filtrarCategoria(
    {
      categoria: "categoria",
      id: 0
    },
    0
  );
});

function completar(event, ui, $this) {
  cliente_selecionado = ui.item.id;
  cliente_grupo = ui.item.grupo_clientes_id;
  lista = ui.item.lista;

  $("#id_cliente").val(ui.item.id);
  $("#id_cliente_plan").val(ui.item.id);
  $("#otros_datos").val(ui.item.descripcion);
  $("#id_cliente_domicilio").val(ui.item.id);
  descripcion = ui.item.descripcion;
  descripcion = descripcion.split(",");
  direccion_domicilio = descripcion[1];
  dire = descripcion[2].trim();
  dire2 = descripcion[3].trim();

  if (dire != "" && dire2 != "") {
    telefono_domicilio = dire + " / " + dire2;
  } else {
    if (dire != "") {
      telefono_domicilio = dire;
    }
    if (dire2 != "") {
      telefono_domicilio = dire2;
    }
  }

  $("#direccion_domicilio").val(direccion_domicilio);
  $("#telefono_domicilio").val(telefono_domicilio);
  $("#telefono_domicilio_label").html(telefono_domicilio);
  $("#direccion_domicilio_label").html(direccion_domicilio);
  $("#direccion_domicilio").hide();
  $("#telefono_domicilio").hide();
  $("#direccion_domicilio_label").show();
  $("#telefono_domicilio_label").show();
  // Si no esta activa la opcion de cartera en clientes les descontamos normal
  if (clientesCartera == 0) {
    if (lista >= 1) {
      var filter = $this.val();
      $("#lista-clientes").html("");
      $("#facturasTable tbody").html("");
      $("#cliente-titulo").html("");
      $("#search").val("");
      $("#productos-detail").html(
        '<tr class="nothing"><td>No existen elementos</td></tr>'
      );
      $("#total-show").html("0.00");
      $("#subtotal").html("0.00");
      $("#iva-total").html("0.00");
      $("#id_lista").val(lista);

      filtrarCategoria(
        {
          categoria: "categoria",
          id: 0
        },
        lista
      );
    } else {
      $("#id_lista").val("0");
      filtrarCategoria(
        {
          categoria: "categoria",
          id: 0
        },
        0
      );
    }
  } else {
    // CAPTURAMOS SI EL CLIENTE TIENE CARTERA O NO

    var cartera = ui.item.cartera;

    if (cartera == 1) {
      if (lista >= 1) {
        var filter = $this.val();
        $("#lista-clientes").html("");
        $("#facturasTable tbody").html("");
        $("#cliente-titulo").html("");
        $("#search").val("");
        $("#productos-detail").html(
          '<tr class="nothing"><td>No existen elementos</td></tr>'
        );
        $("#total-show").html("0.00");
        $("#subtotal").html("0.00");
        $("#iva-total").html("0.00");
        $("#id_lista").val(lista);

        filtrarCategoria(
          {
            categoria: "categoria",
            id: 0
          },
          lista
        );
      } else {
        $("#id_lista").val("0");
        filtrarCategoria(
          {
            categoria: "categoria",
            id: 0
          },
          0
        );
      }

      $("#clienteCartera").html("");
    } else if (cartera == 1) {
      $("#clienteCartera").html(
        "El cliente " + ui.item.value + " está en mora"
      );
    }
  }
}

/*

 var cliente_selecionado = {}



 var lista_clientes = {



    prototype:{},

    init:function(){

        var li = ""; 

        for (var i = 0; i < this.prototype.length; i++) {

            li = li + "<li value='"+(i)+"' onclick='lista_clientes.select(this)'>"+this.prototype[i].nombre_comercial+"</li>";

        };

        $('#lista-clientes').html(li);

    },

    select:function(element){



       $("#buscar-cliente").val('');

       cliente_selecionado = this.prototype[element.value];

       $('#nombre_comercial').html(cliente_selecionado.nombre_comercial);

       $('#razon_social').html(cliente_selecionado.razon_social);

       $('#nif_cif').html(cliente_selecionado.razonif_cifn_social);

       $('#contacto').html(cliente_selecionado.contacto);

       $('#email').html(cliente_selecionado.email);

       $('#contenedor-lista-clientes').fadeOut('fast');

       //$('#cliente-titulo').html(cliente_selecionado.nombre_comercial);

       //$('#grupo_clientes_id').html(cliente.grupo_clientes_id);



      // console.log(cliente_selecionado.id);

    }

 }

*/

/*Buscar cliente

$("#datos_cliente").keyup(function(e){



    var filter = $(this).val();

    $('#lista-clientes').html('');

    $('#facturasTable tbody').html('');

    $('#cliente-titulo').html('');

    $('#search').val('');

    $('#productos-detail').html('<tr class="nothing"><td>No existen elementos</td></tr>');

    $('#total-show').html('0.00');

    $('#subtotal').html('0.00');

    $('#iva-total').html('0.00');

    

    if(filter!=''){

        $.ajax({

            type: "GET",

            url: "../clientes/get_clients_filter?filter="+filter

        }).done(function( response ) {

            console.log(response); 

            lista_clientes.prototype=response;

            lista_clientes.init();

        ;

        }); 

    }



});

*/

//Metodo REST API -> Estado inactivo

function getClient(element) {
  if (element.selectedIndex != 0) {
    $("#nombre_comercial").html(
      client[element.selectedIndex - 1].nombre_comercial
    );

    $("#razon_social").html(client[element.selectedIndex - 1].razon_social);

    $("#nif_cif").html(client[element.selectedIndex - 1].nif_cif);

    $("#contacto").html(client[element.selectedIndex - 1].contacto);

    $("#email").html(client[element.selectedIndex - 1].email);

    $("#grupo_clientes_id").html(
      client[element.selectedIndex - 1].grupo_clientes_id
    );

    $.ajax({
      url: $url,

      data: {
        filter: "",

        cliente: client[element.selectedIndex - 1].id_cliente,

        grupo: client[element.selectedIndex - 1].grupo_clientes_id
      },

      type: "POST",

      success: function(response) {
        for (var i = 0; i < response.length; i++) {
          $("#nombre-producto-" + response[i].id).text(response[i].nombre);

          $("#stock_minimo-producto-" + response[i].id).text(
            response[i].stock_minimo
          );

          $("#precio_venta-producto-" + response[i].id).text(
            response[i].precio_venta
          );

          $("#precio-real-" + response[i].id).val(response[i].precio_venta);
        }
      }
    });
  }
}

/*--------------------------------------------------

| CODIGO DE BARRAS                                 |

---------------------------------------------------*/

var sProduct = null;

//aqui revisar-------------------------------------------------------

$("#cod-container").click(function() {
  var cod_stock_minimo = $(".cod_stock_minimo").val();
  var cod_vendernegativo = $(".cod_vendernegativo").val();

  if (cod_stock_minimo <= 0 && cod_vendernegativo == 0) {
    swal("Alerta", "No posees stock para la venta de este producto", "warning");
  } else {
    renderFactura();

    actualizarEspera();

    calculate();
  }
});

/* Funcion para traer producto imei por codigo de barra*/

function imeiAddValue(cliente, grupo) {
  $.ajax({
    url: $url,
    dataType: "json",
    type: "post",
    data: {
      filter: $("#modal-imei")[0].dataset.imei,
      imei: $("#codigo_barra_imei").val(),
      type: "codificalo_imei",
      cliente: cliente,
      grupo: grupo
    },
    success: function(data) {
      if (data != null) {
        sProduct = data;
        var precioimpuesto =
          (parseInt(sProduct.precio_venta) * parseInt(sProduct.impuesto)) /
            100 +
          parseInt(sProduct.precio_venta);

        /*Descripcion*/
        $(".resultado_imei p").html("");
        $(".producto_imei").css("display", "block");
        $(".imagen-producto-imei").attr(
          "src",
          $urlImages + "/" + sProduct.imagen
        );
        $(".nombre-producto-imei").html(
          "<span class='item-imei'>Nombre: </span> " + sProduct.nombre
        );
        $(".codigo-producto-imei").html(
          "<span class='item-imei'>Codigo: </span>" + sProduct.codigo
        );
        $(".codigo-imei").html(
          "<span class='item-imei'>Imei: </span>" + sProduct.serial
        );

        $(".stock-imei").html(
          "<span class='item-imei'>Stock: </span>" + sProduct.stock_minimo
        );
        $(".precio-venta-imei").html(
          "$" + formatDollar(Math.round(sProduct.precio_venta))
        );

        $("#cod-compra").html(sProduct.precio_compra);

        if (sProduct.ubic != "") {
          $("#ubic").html(
            "<strong>Ubicaci&oacute;n:</strong> " + sProduct.ubic + "<br>"
          );
        } else {
          $("#ubic").html("");
        }
        /*$(".cod_stock_minimo").val(sProduct.stock_minimo);
                    $(".cod_vendernegativo").val(sProduct.vendernegativo);
                    $('#cod-precio-impuesto').html("<strong>Precio de venta:</strong> "+formatDollar(Math.round(sProduct.precio_venta))+"<br>");  
                    $('#cod-precio').html('$ '+Math.round(precioimpuesto));      
                    $('#cod-container').fadeIn('fast');
                    */
        if (sProduct.stock_minimo <= 0 && sProduct.vendernegativo == 0) {
          swal(
            "Alerta",
            "No posees stock para la venta de este producto",
            "warning"
          );
        } else {
          renderFactura();
          pasarPromocion();
          calculate();
          actualizarEspera();
        }
        $("#codigo_barra_imei").val("");
      } else {
        //alert('Imei o Serial no disponible para la venta.');
        swal("Alerta", "Imei o Serial no disponible para la venta.", "warning");
      }
    }
  });
}

function codificaloAddValue(cliente, grupo) {
  var estadoGift = function() {
    $.ajax({
      url: $estadoGiftCard,
      dataType: "json",
      type: "post",
      data: { codigo: $("#search").val() },
      success: function(data) {
        if (data.estado != "empty") {
          if (data.estado == "pagado") {
            //alert("La "+data.nombre+" ha sido vendida")
            swal({
              position: "center",
              type: "error",
              title: "Error",
              html: "La " + data.nombre + " ha sido vendida",
              showConfirmButton: false,
              timer: 1500
            });
          }
          if (data.estado == "cancelado") {
            //alert("La "+data.nombre+" ha sido canjeada")
            swal({
              position: "center",
              type: "error",
              title: "Error",
              html: "La " + data.nombre + " ha sido canjeada",
              showConfirmButton: false,
              timer: 1500
            });
          }
        } else {
          $("#cod-container").fadeOut("fast");
          //alert("Codigo de barras no encontrado");
          swal({
            position: "center",
            type: "error",
            title: "Código de barras no encontrado",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    });
  };

  $.ajax({
    url: $url,
    dataType: "json",
    type: "post",
    data: {
      filter: $("#search").val(),
      type: "codificalo",
      cliente: cliente,
      grupo: grupo
    },
    success: function(data) {
      if (data != null) {
        sProduct = data;

        if (sProduct.imei == 1) {
          $("#modal-imei").modal("show");
          $("#modal-imei").attr("data-imei", sProduct.id);
          return;
        }
        var precioimpuesto =
          (parseInt(sProduct.precio_venta) * parseInt(sProduct.impuesto)) /
            100 +
          parseInt(sProduct.precio_venta);

        /*Descripcion*/
        $("#cod-nombre").html(sProduct.nombre);
        $("#cod").html(sProduct.codigo);
        $("#cod-stock").html(sProduct.stock_minimo);
        $("#cod-compra").html(sProduct.precio_compra);

        if (sProduct.ubic != "") {
          $("#ubic").html(
            "<strong>Ubicaci&oacute;n:</strong> " + sProduct.ubic + "<br>"
          );
        } else {
          $("#ubic").html("");
        }

        $("#cod-img").attr("src", $urlImages + "/" + sProduct.imagen);
        $(".cod_stock_minimo").val(sProduct.stock_minimo);
        $(".cod_vendernegativo").val(sProduct.vendernegativo);
        $(".cod_tipo_producto").val(sProduct.tipo_producto);

        /*if(sProduct.impuesto != '0'){
                     $('#cod-precio-impuesto').html("<strong>Precio + IVA:</strong> "+formatDollar(Math.round(precioimpuesto))+"<br>");
                    }*/
        //if(sProduct.impuesto == '0'){

        $("#cod-precio-impuesto").html(
          "<strong>Precio de venta:</strong> " +
            formatDollar(Math.round(sProduct.precio_venta)) +
            "<br>"
        );

        //}
        //alert("tipo2");
        $("#cod-precio").html("$ " + Math.round(precioimpuesto));
        $("#cod-container").fadeIn("fast");
        //var unidades = parseFloat($('.cantidad').eq($(this).index()).text()) + parseFloat(1);
        var unidades =
          parseFloat(
            $("#" + sProduct.id)
              .find(".cantidad")
              .text()
          ) + parseFloat(1);
        //alert(unidades);
        if (isNaN(unidades)) {
          unidades = 1;
        }
        bandera = 1;
        if (sProduct.stock_minimo < unidades && sProduct.vendernegativo == 0) {
          if (sProduct.tipo_producto == 3) {
            //verifico mis ingredientes si tengo disponible
            $.ajax({
              async: false, //mostrar variables fuera de el function
              url: $buscarstockespeciales,
              type: "POST",
              dataType: "json",
              data: {
                id: sProduct.id,
                tipo: sProduct.tipo_producto,
                unidades: unidades
              },
              success: function(data) {
                console.log(data);
                if (data.success == 0) {
                  bandera = 0;
                  Swal({
                    title: "Alerta",
                    html: data.msj,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#5ca745",
                    cancelButtonColor: "#e62626",
                    confirmButtonText: "¿Deseas realizar la venta?",
                    cancelButtonText: "No vender"
                  }).then(result => {
                    if (result.value) {
                      //si acepta vender
                      bandera = 1;
                      if (bandera == 1) {
                        renderFactura();
                        pasarPromocion();
                        calculate();
                        actualizarEspera();
                      }
                    }
                  });
                }
              }
            });
          } else {
            swal(
              "Alerta",
              "No posees stock para la venta de este producto",
              "warning"
            );
            bandera = 0;
          }
        }
        if (bandera == 1) {
          renderFactura();
          pasarPromocion();
          calculate();
          actualizarEspera();
        }
        $("#search").val("");
      } else {
        estadoGift();
      }
    }
  });
}

/*--------------------------------------------------

| VITRINA                                          |

---------------------------------------------------*/

var productos_categoria = null;

function isAlphaNumeric(str) {
  var code, i, len;

  for (i = 0, len = str.length; i < len; i++) {
    code = str.charCodeAt(i);
    if (
      !(code > 47 && code < 58) && // numeric (0-9)
      !(code > 64 && code < 91) && // upper alpha (A-Z)
      !(code > 96 && code < 123)
    ) {
      // lower alpha (a-z)
      return false;
    }
  }
  return true;
}

function filtrarCategoria(element, cliente) {
  id_cliente =
    typeof cliente_selecionado == "object" &&
    typeof cliente_selecionado.id != "undefined"
      ? cliente_selecionado.id
      : "-";
  id_grupo = typeof cliente_grupo == "string" ? cliente_grupo : 0;

  $.ajax({
    url:
      $urlVitrina +
      "/" +
      element.id +
      "/" +
      $("#id_lista").val() +
      "/" +
      cliente +
      "/" +
      id_grupo,
    type: "GET",
    success: function(response) {
      productos_categoria = response;

      $("#vitrina").html("");

      for (var i = 0; i < response.length; i++) {
        var sProduct = response[i];

        if (__decimales__ == 0) {
          //var precioimpuesto = redondear(parseInt(sProduct.precio_venta) * parseInt(sProduct.impuesto) / 100 + parseInt(sProduct.precio_venta));
          var precioimpuesto = Math.round(
            parseFloat(sProduct.precio_venta) +
              parseFloat(sProduct.precio_venta) *
                (parseFloat(sProduct.impuesto) / 100)
          );
        } else {
          //var precioimpuesto = (parseInt(sProduct.precio_venta) * (parseInt(sProduct.impuesto) / 100 + parseInt(sProduct.precio_venta));
          var precioimpuesto =
            parseFloat(sProduct.precio_venta) *
              (parseFloat(sProduct.impuesto) / 100) +
            parseFloat(sProduct.precio_venta);
        }

        if (sProduct.impuesto != "0") {
          total = precioimpuesto;
        } else {
          total = sProduct.precio_venta;
        }

        if (response[i].imagen == "") {
          response[i].imagen = "product-dummy.png";
        }

        $("#vitrina").append(
          '<div class="col-md-3 col-sm-3 col-xs-4 vitrina-cuadro" onclick="categoria_producto(' +
            i +
            "," +
            response[i].stock_minimo +
            "," +
            response[i].vendernegativo +
            "," +
            response[i].tipo_producto +
            "," +
            response[i].imei +
            "," +
            response[i].id +
            ')">' +
            '<div class="cuadro">' +
            '<div class="imagen">' +
            '<img onerror="ImgError(this)"  src="' +
            response[i].imagen +
            '" >' +
            "</div>" +
            '<div id="pie-item">' +
            '<div class="text-center"><span class="item-nombre">' +
            response[i].nombre +
            "</span></div>" +
            '<div align="center"><h4>' +
            response[i].simbolo +
            " " +
            mostrarNumero(total) +
            "</h4></div>" +
            "</div>" +
            "</div>" +
            "</div>"
        );
      }
    }
  });
}

var offset = 0;

function siguiente_categorias() {
  offset += 3;

  $.ajax({
    type: "GET",

    url: $urlCategorias + "/" + offset
  }).done(function(response) {
    if (response.length != 0) {
      html =
        '<li id="0" onclick="filtrarCategoria(this)"><img onerror="ImgError(this)"  src="' +
        $urlImages +
        '/todos.jpg"><br>Todos</li>';

      html +=
        '<li id="2" onclick="filtrarCategoria(this)"><img onerror="ImgError(this)"  src="http://www.vendty.com/invoice/uploads/general.jpg"><br>General</li>';

      for (var i = 0; i < response.length; i++) {
        if (response[i].id != 2)
          html +=
            '<li id="' +
            response[i].id +
            '" onclick="filtrarCategoria(this)" ><img onerror="ImgError(this)"  src="' +
            $urlImagesCategoria +
            "/" +
            response[i].imagen +
            '"><br>' +
            response[i].nombre +
            "</li>";
      }

      $("#nav-categoria").html(html);
    } else {
      offset = -3;
      siguiente_categorias();
    }
  });
}

function categoria_producto(
  index,
  stock,
  vendernegativo,
  tipo_producto,
  imei,
  producto_id
) {
  /*alert("tipo3");
    alert("index=" + index);
    alert("producto_id=" + producto_id);*/
  if (imei == 1) {
    $("#modal-imei").modal("show");
    $("#modal-imei").attr("data-imei", producto_id);
    return;
  }
  //$(".precio-prod-descuento").eq(x).val()
  //var unidades = parseFloat($('.cantidad').eq(index).text()) + parseFloat(1);
  var unidades =
    parseFloat(
      $("#" + producto_id)
        .find(".cantidad")
        .text()
    ) + parseFloat(1);
  //alert(unidades);
  if (isNaN(unidades)) {
    unidades = 1;
  }
  bandera = 1;

  if (stock < unidades && vendernegativo == 0) {
    if (tipo_producto == 3) {
      //verifico mis ingredientes si tengo disponible
      $.ajax({
        async: false, //mostrar variables fuera de el function
        url: $buscarstockespeciales,
        type: "POST",
        dataType: "json",
        data: {
          id: producto_id,
          tipo: tipo_producto,
          unidades: unidades
        },
        success: function(data) {
          console.log(data);
          if (data.success == 0) {
            bandera = 0;
            Swal({
              title: "Alerta",
              html: data.msj,
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#5ca745",
              cancelButtonColor: "#e62626",
              confirmButtonText: "¿Deseas realizar la venta?",
              cancelButtonText: "No vender"
            }).then(result => {
              if (result.value) {
                bandera = 1;
                venderproductonegativo(
                  bandera,
                  index,
                  stock,
                  vendernegativo,
                  tipo_producto,
                  imei,
                  producto_id
                );
              }
            });
          }
        }
      });
    } else {
      swal(
        "Alerta",
        "No posees stock para la venta de este producto",
        "warning"
      );
      bandera = 0;
    }
  }

  venderproductonegativo(
    bandera,
    index,
    stock,
    vendernegativo,
    tipo_producto,
    imei,
    producto_id
  );
}

function venderproductonegativo(
  bandera,
  index,
  stock,
  vendernegativo,
  tipo_producto,
  imei,
  producto_id
) {
  if (bandera == 1) {
    sProduct = productos_categoria[index];

    renderFactura();

    pasarPromocion();

    calculate();

    actualizarEspera();
  }
}

function seleccionar_mesa(element) {
  id_mesa_seleccionada = $(element).attr("data-mesa");

  nombre_mesa = $(element).attr("data-nombre");

  $("#mesasSidebar").modal("hide");

  //console.log(id_mesa_seleccionada);

  //$("#pendiente span").html('Mesa '+nombre_mesa);

  $("#listadoProdcutos").html("Listado Productos - MESA: " + nombre_mesa);

  poner_venta_en_espera(id_mesa_seleccionada);
}

function cargar_modal_mesas() {
  $.ajax({
    type: "post",

    url: "armar_html_mesas/json",

    dataType: "json",

    success: function(result) {
      //console.log(result);

      $("#div_mesas_seccion").html(result.html);

      $("#mesasSidebar").modal("show");
    }
  });
}

function cerrarComanda() {
  $("#comandaSidebar").modal("hide");
}

function cerrar_mesas() {
  $("#mesasSidebar").modal("hide");
}
