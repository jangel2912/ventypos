<?php
//limpiarCampo
echo "var __decimales__ = ".$data->decimales.",
            __separadorDecimal__ = '".$data->tipo_separador_decimales."',
            __separadorMiles__ = '".$data->tipo_separador_miles."',
            __redondear__ = '".$data->redondear."';
            db = '".$this->session->userdata('db_config_id')."';

function redondear(num)
{
    if(__redondear__ == 1)
    {
        num = limpiarCampo(num);
        numero = String(num);
        decena = numero.substring(numero.length -2, numero.length);
        num = Math.round(num / 100) * 100;
        if(parseInt(decena) <= 50 && parseInt(decena) >= 1)
        {
            num = parseInt(numero.substring(0, numero.length - 2) + '50');
        }
    }else
    {
        if(__decimales__>0){
            num = parseFloat(limpiarCampo(num));
        }       
       num = parseFloat(num.toFixed(__decimales__)); 
    }
    return num
}
function limpiarCampo(num)
{   
    if(num != '')
    {
        var num = parseFloat(num).toString();
        if(num.split('.').length > 1)
        {
            arrayNum  = num.split('.')[0],
            arrayDecimal  = num.split('.')[1],
            arrayDecimal = arrayDecimal.split('');
            if( __decimales__ < arrayDecimal.length + 1)
            {
                num = arrayNum+'.';
                for(i= 0;i < __decimales__;i++){
                    num += arrayDecimal[i];
                };
            }
        }
        return parseFloat(num);
    }else
    {
        return num.toFixed($data->decimales);
    }
}
function fijarNumero(num){
    return parseFloat(parseFloat(num).toFixed($data->decimales));     
}

function mostrarNumero(number){
    number = limpiarCampo(fijarNumero(number));    
    /*console.log(number+'primero');
    console.log(number_format(number)+'segundo');*/
    return number_format(number,$data->decimales, '$data->tipo_separador_decimales', '$data->tipo_separador_miles');
}
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    
    return s.join(dec);
}
";
if ($data->decimales == 0) {
    
echo 'function calculate() {
    
    iva = 0;
    subtotal = 0;
    total = 0;
    valporcen1 = 0;
    total_porcen = 0;
    total_productos = 0;
    $(".title-detalle").each(function(index, item) {
       
        /**********************************************/
        if(db != 4017)
        {   //para los demas 
                
            precio = (parseFloat($(".precio-prod-real").eq(index).val()));
            
            impuesto = $(".impuesto-final").eq(index).val();
            cantidad = $(".cantidad").eq(index).text();
            porcentaje = $(".precio-porcentaje").eq(index).text();
            subtotalP = parseFloat(precio * cantidad);
            nombre_impuesto = $(".nombre_impuesto").eq(index).val();
            nombre_impuesto = nombre_impuesto.toUpperCase();

            if(($("input.precio-prod-real").eq(index).attr("data-promocion") == 1 || $("input.precio-prod-real").eq(index).attr("data-promocion") == 2 || $("input.precio-prod-real").eq(index).attr("data-promocion") == 3))
            {
            
                
                subtotal = parseFloat($(".promocionPrecio").eq(index).val()) + Math.round(subtotal);
                vimpuesto = parseFloat($(".promocionIva").eq(index).val());               
                total = parseFloat($(".promocionPrecio").eq(index).val()) + vimpuesto + parseFloat(total);
                subtotalP = parseFloat($(".promocionPrecio").eq(index).val());
                precioProd = redondear((precio)+Math.round(precio * impuesto / 100));
            }else{  
                if (porcentaje > 1) {
                
                    valporcen1 = redondear(precio - ((porcentaje * precio) / 100));
                    subtotal += redondear(valporcen1 * cantidad);
                    console.log("1sub"+subtotal);
                    vimpuesto = (valporcen1 * impuesto / 100 * cantidad);
                    total += redondear(subtotal + vimpuesto);
                    precioProd = redondear((precio)+Math.round(precio * impuesto / 100));
                
                } else {                                     
                    subtotal+=redondear(precio * cantidad);  
                    vimpuesto = redondear((precio * cantidad) * (impuesto / 100));// Cambiamos la formula para ajustar el peso                                       
                    if(db!=3990){ 
                        total += redondear((precio+ ((precio* impuesto) /100)) * cantidad); // Cambiamos la formula para ajustar el peso (07032017)
                    }
                    else{
                        total += redondear(redondear(precio+(precio * (impuesto/ 100)))* cantidad);
                    }
                    precioProd = redondear((precio)+((precio * impuesto)/ 100 ));   
                }
            }
            //finalto=redondear(total);
        }
        else{  //para Ceresco
            precio = parseFloat($(".precio-prod-real").eq(index).val());            
            impuesto = $(".impuesto-final").eq(index).val();
            cantidad = $(".cantidad").eq(index).text();
            porcentaje = $(".precio-porcentaje").eq(index).text();        
            subtotalP = parseFloat(precio * cantidad);
            nombre_impuesto = $(".nombre_impuesto").eq(index).val();
            nombre_impuesto = nombre_impuesto.toUpperCase();

            if(($("input.precio-prod-real").eq(index).attr("data-promocion") == 1 || $("input.precio-prod-real").eq(index).attr("data-promocion") == 2 || $("input.precio-prod-real").eq(index).attr("data-promocion") == 3))
            {      
                //precio1=redondear(parseFloat($(".promocionPrecio").eq(index).val()));             
                precio1=(parseFloat($(".promocionPrecio").eq(index).val()));                             
                subtotal = precio1 + Math.round(subtotal);           
                vimpuesto = ((precio1 /100) * impuesto);          
                total = redondear(precio1 + vimpuesto + parseFloat(total));
                subtotalP =precio1;                        
               // precioProd = redondear((precio)+(((precio) * impuesto)/ 100 ));    
                precioProd = redondear((precio1)+(((precio1) * impuesto)/ 100 ));       

            }else{              
                if (porcentaje > 1) {         
                   // alert("porcentaje1");       
                    valporcen1 = redondear(precio - ((porcentaje * precio) / 100));
                    subtotal += redondear(valporcen1 * cantidad);
                    console.log("1sub"+subtotal);
                    vimpuesto = (valporcen1 * impuesto / 100 * cantidad);
                    total += redondear(subtotal + vimpuesto);
                    precioProd = redondear((precio)+Math.round(precio * impuesto / 100));                
                } else { 
                    //alert("dentro inde3x"+precio);
                    subtotal+=redondear(precio * cantidad);
                    vimpuesto = redondear((precio * cantidad) * (impuesto / 100));// Cambiamos la formula para ajustar el peso
                   // total += redondear((precio+ ((precio* impuesto) /100)) * cantidad) // Cambiamos la formula para ajustar el peso (07032017)
                    //total1 = redondear((precio+ ((precio* impuesto) /100))); // Cambiamos la formula para ajustar el peso (07032017)
                    total1 = redondear(precio+(precio * (impuesto/ 100)));
                    total +=(total1 *cantidad);
                  // precioProd = redondear((precio)+(((precio) * impuesto)/ 100 ));      
                   precioProd = redondear(precio+(precio * (impuesto/ 100)));  
                   
                }
            }            
        }
        finalto=total;
        if(total<=0){
            $("#grabar_sin_pago").prop("disabled",true);
        }else{
            $("#grabar_sin_pago").prop("disabled",false);
        }
        //alert(finalto);
        if ($sobrecosto == "si" && $nit != "320001127839") {        
            if ( sobrecostoTodos == 1){            
                total_porcen += precio * cantidad;                
            }else if ( sobrecostoTodos == 0){            
                if (nombre_impuesto.trim().toLowerCase() == "IAC".toLowerCase() || nombre_impuesto.trim().toLowerCase() == "IMPOCONSUMO".toLowerCase() || nombre_impuesto.trim().toLowerCase() == "IMPUESTO AL CONSUMO".toLowerCase()) {
                    total_porcen += precio * cantidad;
                }                
            }            
        }

        iva += vimpuesto;
        $(".precio-prod").eq(index).text(formatDollar(precioProd));
        $(".precio-calc").eq(index).html(formatDollar(redondear(subtotalP)));
        $(".precio-calc-real").eq(index).html((parseFloat(precio) * parseFloat(cantidad)));      
        total_productos+=Number(cantidad);
        
    });
    
    $("#total").val(redondear(total));
    $("#total-show").html(formatDollar(redondear(total)));
    $("#iva-total").html(formatDollar(iva));
    $("#show_iva").val(formatDollar(iva));
    $("#show_subtotal").val(formatDollar(subtotal));
    $("#subtotal_input").val(redondear(subtotal));
    $("#subtotal_propina_input").val(total_porcen);
    $("#subtotal").html(formatDollar(redondear(subtotal)));
    $("#cantidad-total").html(total_productos);
}

function formatDollar(num) {
    num = parseFloat(num);
    (num % 1 == 0) ? p = num.toFixed(0).split("."): p = num.toFixed(0).split(".");
    return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return num + (i && !(i % 3) ? "," : "") + acc;
    }, "") /*+ "." + p[1]*/ ;
}
function calcularValor(a,b){
    return Math.round(a - b);
}
function formatRows(row){

        var imageName = "default.png";
        
        if(row.imagen != "")
            imageName = row.imagen;
        
        if(imageName=="")
            imageName = "product-dummy.png";

        if(db != 4017){ //para los demas 
            
            var precioimpuesto =   formatDollar(Math.round((parseFloat(row.precio_venta) * (row.impuesto) / 100) + parseFloat(row.precio_venta)));
            //console.log(precioimpuesto+"pp"+row.precio_venta+"ii"+row.impuesto);
            var precioimpuesto =   formatDollar(Math.round((parseFloat(row.precio_venta) * (row.impuesto) / 100) + parseFloat(row.precio_venta)));
            
            image =  imageName; 
            html = "<tr><td width=\'20%\'><img src=\'"+image+"\' class=\'grid-image\'/></td>";
            html += "<td><p><span class=\'nombre_producto\'>"+row.nombre+"</span>&nbsp;<input type=\'hidden\' value=\'"+parseFloat(row.precio_venta)+"\' class=\'precio-real\'/><span class=\'precio\'>"+(row.impuesto != "0" ? (precioimpuesto) : formatDollar(row.precio_venta))+"</span></p>";
            html += "<p><span class=\'stock\'>Stock: "+row.stock_minimo+"</span>&nbsp;<input type=\'hidden\' value=\'"+row.precio_compra+"\' class=\'precio-compra-real\'/><span class=\'precio-minimo\'>C&oacute;digo de barra: "+row.codigo+"</span>  -  <span class=\'precio-minimo\'>Precio de venta: "+formatDollar(row.precio_venta) +"</span> &nbsp;<span class=\'ubicacion_producto\'>Ubicaci&oacute;n: "+row.ubic+"</span> </p><input type=\'hidden\' class=\'id_producto\' value=\'"+row.id+"\'/><input type=\'hidden\' class=\'codigo\' value=\'"+row.codigo+"\'><input type=\'hidden\' class=\'impuesto\' value=\'"+row.impuesto+"\'></p></td>";
            html += "</tr>";
        }
        else{ //para cerescos
           // alert(row.precio_venta);
           precio_venta=parseFloat(row.precio_venta);
           // var precioimpuesto =   formatDollar(Math.round((parseFloat(row.precio_venta) * (row.impuesto) / 100) + parseFloat(row.precio_venta)));           
           // var precioimpuesto = formatDollar(Math.round((parseFloat(row.precio_venta))+Math.round((parseFloat(row.precio_venta)* row.impuesto/100))));
           // var precioimpuesto =   formatDollar( Math.round(row.precio_venta) + Math.round((parseFloat(row.precio_venta) * (row.impuesto) / 100)));           
            //console.log(precioimpuesto+"pp"+row.precio_venta+"ii"+row.impuesto);
            //alert(precioimpuesto);
            var precioimpuesto = Math.round(precio_venta+(precio_venta * (row.impuesto/ 100)));
            image =  imageName; 
            html = "<tr><td width=\'20%\'><img src=\'"+image+"\' class=\'grid-image\'/></td>";
            html += "<td><p><span class=\'nombre_producto\'>"+row.nombre+"</span>&nbsp;<input type=\'hidden\' value=\'"+(row.precio_venta)+"\' class=\'precio-real\'/><span class=\'precio\'>"+(row.impuesto != "0" ? (precioimpuesto) : formatDollar(row.precio_venta))+"</span></p>";
            html += "<p><span class=\'stock\'>Stock: "+row.stock_minimo+"</span>&nbsp;<input type=\'hidden\' value=\'"+row.precio_compra+"\' class=\'precio-compra-real\'/><span class=\'precio-minimo\'>C&oacute;digo de barra: "+row.codigo+"</span>  -  <span class=\'precio-minimo\'>Precio de venta: "+formatDollar(row.precio_venta) +"</span> &nbsp;<span class=\'ubicacion_producto\'>Ubicaci&oacute;n: "+row.ubic+"</span> </p><input type=\'hidden\' class=\'id_producto\' value=\'"+row.id+"\'/><input type=\'hidden\' class=\'codigo\' value=\'"+row.codigo+"\'><input type=\'hidden\' class=\'impuesto\' value=\'"+row.impuesto+"\'></p></td>";
            html += "</tr>";
        }
        return html;
}
function pagar()
{           
    $("#descuento_general").val("0");

    if((negocio!="Restaurante") && (negocio!="restaurante")){
        $("#sobrecostos_input").val(0);
    }
    else{
         if($sobrecosto=="no"){
            $("#sobrecostos_input").val(0);
        }
    }
    
    var tipo_propina=$("input:radio[name=tipo_propina]:checked").val();
    
    if(tipo_propina=="porcentaje"){

        var propina = $("#sobrecostos_input").val() || 10;
            valorTotal = $("#subtotal_input").val();
            total = Math.ceil(parseFloat((valorTotal * propina) / 100));
        
        $("#propina_output").html(propina + "% - " + total);
        $("#propina_input").val(propina);

        var propina_pro = $("#sobrecostos_input").val() || 10;
            //valorTotal_pro = $("#subtotal_propina_input").val();
            valorTotal_pro = $("#subtotal_input").val();
            total_pro = parseFloat((valorTotal_pro * propina_pro) / 100);

        $("#propina_output_pro").html(propina_pro + "% - " + formatDollar(total_pro));
        $("#propina_input_pro").val(propina_pro);
        
        $("#valor_pagar_propina").html(formatDollar(parseFloat($("#total").val()) + parseFloat(total_pro)));
    }
    else{ //valor
        var propina = $("#sobrecostos_input").val() || 1000;
        $("#sobrecostos_input_valor").val($("#sobrecostos_input").val());  //guardo el valor que se colocó  
            valorTotal = $("#subtotal_input").val();
            total = Math.ceil(parseFloat((propina * 100) / valorTotal));

        $("#propina_output").html((total) + "% - " + propina);
        $("#propina_input").val(total);

        var propina_pro = $("#sobrecostos_input").val() || 1000,
           // valorTotal_pro = $("#subtotal_propina_input").val();
            valorTotal_pro = $("#subtotal_input").val();
            total_pro = parseFloat((propina_pro *100)/valorTotal_pro); 

        $("#propina_output_pro").html((total_pro) + "% - " + formatDollar(propina_pro));
        $("#propina_input_pro").val(propina_pro);

        $("#valor_pagar_propina").html(formatDollar(parseFloat($("#total").val()) + parseFloat(propina_pro)));         
        $("#sobrecostos_input").val(total_pro);
        total_pro=propina_pro;       
    }

    var valor_total_entregado = parseFloat($("#total").val()) + parseFloat(total_pro);    

    $("#valor_pagar").val(formatDollar(($("#total").val())));
    $("#valor_pagar_label").html(formatDollar(($("#total").val())));
    $("#valor_entregado").val(Math.round(valor_total_entregado));
    $("#receipt_money").val(valor_total_entregado);
    $("#valor_pagar_hidden").val(Math.round(valor_total_entregado));
    $("#sima_cambio").val(parseInt("0"));
    $("#sima_cambio_label").html(parseInt("0"));
    $("#dialog-forma-pago-form").dialog("open");
}
validarMediosDePago = function(e)
{
    var total_acumulado_con_cambio = 0;
    var total_acumulado_sin_cambio = 0;
    var total = $("#valor_pagar_hidden").val() * 1;
    var total_superado = false;
    var total_superado_error = false;
    var valor_a_pagar = 0;
    var estado = {
        resultado: true,
        errores: []
    }

    for(var i=0; i<=5; i++)
    {
        var selector = i == 0 ? "" : i;

        $("#valor_entregado"+selector).css("border-color", "#ccc");

        if(($("#forma_pago"+selector).val()!="") && ($("#valor_entregado"+selector).val()>0)){ 
            switch($("#forma_pago"+selector).val())
            {
                /* parte donde coloca todas las formas de pago
                case "Credito":
                case "tarjeta_credito":
                case "tarjeta_debito":
                case "Visa_crédito":
                case "MasterCard_débito":
                case "MasterCard Crédito":
                case "Visa_débito":
                case "American_Express":
                case "Gift_Card":
                case "MercadoPago":
                case "Linio":
                case "Sodexo":
                case "Saldo_a_Favor":
                case "Efecty":
                case "Baloto":
                case "Bancolombia":
                case "Interrapidisimo":
                case "Maestro_Debito":
                case "Tarjeta_Codensa":
                case "Diners_Club":
                case "PayU":
                    total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
                break;*/
                case "efectivo":
                    total_acumulado_con_cambio += ($("#valor_entregado"+selector).val() * 1);
                break;
                case "Puntos":
                    total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
                    var max = $("#valor_entregado"+selector).prop("max") * 1;
                    var valor = $("#valor_entregado"+selector).val() * 1;
                    if (valor > max)
                    {
                        $("#valor_entregado"+selector).css("border-color", "#dd0000");
                        estado.resultado = false;
                        estado.errores.push("La cantidad de puntos seleccionada es superior a la que tiene el cliente actualmente.");
                    }
                break;
                case "nota_credito":                
                    if($("#valor_entregado_nota_credito"+selector).val()!=""){
                        total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
                    }
                    else{
                        estado.resultado = false;
                        estado.errores.push("Debe canjear una nota de crédito");
                    }
                break;
                default:
                    total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
                // console.log(total_acumulado_sin_cambio);
                    break;
            }
        }else{
            if(($("#forma_pago"+selector).val()=="") && ($("#valor_entregado"+selector).val()>0)){                
                estado.resultado = false;
                estado.errores.push("Hay una forma de pago sin método de pago asociado");
            }
        }
        if(total_acumulado_sin_cambio > total || (total_acumulado_con_cambio >= total && total_acumulado_sin_cambio > 0))
            $("#valor_entregado"+selector).css("border-color", "#dd0000");

        if(total_acumulado_con_cambio + total_acumulado_sin_cambio > valor_a_pagar && total_superado)
            total_superado_error = true;

        if(total_acumulado_con_cambio + total_acumulado_sin_cambio >= total && !total_superado)
        {
            valor_a_pagar = total_acumulado_con_cambio + total_acumulado_sin_cambio
            total_superado = true;
        }
    }

    if(total_acumulado_sin_cambio > total || (total_acumulado_con_cambio >= total && total_acumulado_sin_cambio > 0))
    {
        estado.resultado = false;
        estado.errores.push("Los medios de pago seleccionados tienen valores invalidos.");
    }

    if(total_superado_error)
    {
        estado.resultado = false;
        estado.errores.push("Ha seleccionado mas medios de pago de los necesarios para completar el valor de la compra.");
    }

    var subtotal = total_acumulado_sin_cambio + total_acumulado_con_cambio;

    if(estado.resultado)
    {
        if(subtotal < total)
        {
            estado.resultado = false;
            estado.errores.push("El total de los medios de pago no es igual o superior al total de la compra.");
        }
    }

    if(!estado.resltado)
        //console.log(estado);

    $("#sima_cambio_hidden").val(Math.round(subtotal - total));    
    
    var cambiofalta = mostrarNumero(fijarNumero(subtotal - total));
    if (cambiofalta.slice(0,1) === "-"){
        $("#labelCambioFalta").text("Faltante");
        $("#labelCambioFalta").addClass( "redColorAp" );
        $("#sima_cambio").addClass( "redColorAp" );
    } else {
        $("#labelCambioFalta").text("Cambio");
        $("#labelCambioFalta").removeClass( "redColorAp" );
        $("#sima_cambio").removeClass( "redColorAp" );
    }
    
    $("#sima_cambio").val(formatDollar(Math.round(subtotal - total)));
    $("#sima_cambio_label").html(formatDollar(Math.round(subtotal - total)));    
    return estado;
}
function cambioVentaPendiente()
{
    $("#sima_cambio_hidden").val(Math.round((

        (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val()) + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

    )));

    $("#sima_cambio").val(formatDollar(Math.round((

        (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val()) + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

    ))));
    $("#sima_cambio_label").html(formatDollar(Math.round((

        (parseInt($("#valor_entregado").val()) + parseInt($("#valor_entregado1").val()) + parseInt($("#valor_entregado2").val()) + parseInt($("#valor_entregado3").val()) + parseInt($("#valor_entregado4").val()) + parseInt($("#valor_entregado5").val())) - $("#valor_pagar_hidden").val()

    ))));
}
';
}else{ 
echo ' 
function calculate() 
{
    iva = 0;
    subtotal = 0;
    total = 0;
    valporcen1 = 0;
    total_porcen = 0;
    total_productos = 0;
    $(".title-detalle").each(function(index, item) {
      
        /*****nuevo*****/
            precio = parseFloat($(".precio-prod-real").eq(index).val());            
            impuesto = $(".impuesto-final").eq(index).val();
            cantidad = $(".cantidad").eq(index).text();
            porcentaje = $(".precio-porcentaje").eq(index).text();        
            subtotalP = parseFloat(precio * cantidad);
            nombre_impuesto = $(".nombre_impuesto").eq(index).val();
            nombre_impuesto = nombre_impuesto.toUpperCase();

            if($("input.precio-prod-real").eq(index).attr("data-promocion") == 1 || $("input.precio-prod-real").eq(index).attr("data-promocion") == 2 || $("input.precio-prod-real").eq(index).attr("data-promocion") == 3)
            {
                subtotal = parseFloat($(".promocionPrecio").eq(index).val()) + parseFloat(subtotal);
                vimpuesto = parseFloat($(".promocionIva").eq(index).val());
                total = parseFloat($(".promocionPrecio").eq(index).val()) + vimpuesto + parseFloat(total);
                subtotalP = parseFloat($(".promocionPrecio").eq(index).val());
            }else{
            if (porcentaje > 1) {
                valporcen1 = (precio - ((porcentaje * precio) / 100));
                subtotal += valporcen1 * cantidad;
                vimpuesto = valporcen1 * impuesto / 100 * cantidad;
                total += subtotal + vimpuesto;
            } else {         
                      
                //subtotal+=quitarcomas(formatDollar(parseFloat(precio * cantidad)));
                subtotal+=(parseFloat(precio * cantidad));
                //vimpuesto = quitarcomas(formatDollar(parseFloat((precio * cantidad) * (impuesto / 100))));// Cambiamos la formula para ajustar el peso                  
                vimpuesto = parseFloat((precio * cantidad) * (impuesto / 100));// Cambiamos la formula para ajustar el peso                  
                precioProd = (parseFloat(precio+(precio * (impuesto/ 100))));                               
                //total +=(quitarcomas(precioProd *cantidad));  
                total +=(parseFloat(precioProd *cantidad));  
            }                  
        }
        finalto=total;
       
        if ($sobrecosto == "si" && $nit != "320001127839") {
        
            if ( sobrecostoTodos == 1){
            
                total_porcen += precio * cantidad;                
                
            }else if ( sobrecostoTodos == 0){
            
                if (nombre_impuesto.trim().toLowerCase() == "IAC".toLowerCase() || nombre_impuesto.trim().toLowerCase() == "IMPOCONSUMO".toLowerCase() || nombre_impuesto.trim().toLowerCase() == "IMPUESTO AL CONSUMO".toLowerCase()) {
                    total_porcen += precio * cantidad;
                }
                
            }            
        }

        iva += vimpuesto;
        $(".precio-prod").eq(index).text(mostrarNumero(precio+(precio * impuesto / 100)));
        $(".precio-calc").eq(index).html(mostrarNumero(subtotalP));
        $(".precio-calc-real").eq(index).html(precio * cantidad);
        total_productos+=Number(cantidad);
        if($(item).find(".input.precio-prod-real").attr("data-promocion") == 1)
        {
            $(".precio-calc").eq(index).html(mostrarNumero($(item).find(".promocionPrecio").val()));
        }
    });
   
    total=total.toFixed(__decimales__);     
    $("#total").val(((total)));     
    finalto=total; 
    if(total<=0){
        $("#grabar_sin_pago").prop("disabled",true);
    }else{
        $("#grabar_sin_pago").prop("disabled",false);
    }
    //alert("totalFinala="+total);
    $("#total-show").html(mostrarNumero(total));
    $("#iva-total").html(mostrarNumero(iva));
    $("#subtotal_input").val(subtotal);
    $("#subtotal_propina_input").val(total_porcen);
    $("#subtotal").html(mostrarNumero(subtotal));
    $("#cantidad-total").html(total_productos);
}
function calcularValor(a,b){
    return (a - b);
}
function quitarcomas(number){      
  // alert("numberA="+number);
    number=mostrarNumero(number);
   // alert("numberD="+number);
    number=(number.replace(__separadorMiles__,""));

    if(__separadorMiles__==","){
      //  alert("if");        
       number=parseFloat(number);
    }
    else{     
      //  alert("else");   
        number=(number.replace(",","."));
        number=parseFloat(number);
    }       
    //alert("numberS="+number);
    return number;
}
function mostrarNumero(number){    
    //number = limpiarCampo(fijarNumero(number));
    return number_format(number,'.$data->decimales.', "'.$data->tipo_separador_decimales.'", "'.$data->tipo_separador_miles.'");
}
function formatRows(row){
       
        var imageName = "default.png";
        
        if(row.imagen != "")
            imageName = row.imagen;
        
        if(imageName=="")
            imageName = "product-dummy.png";
            
        var precioimpuesto =   (parseFloat(row.precio_venta) * parseFloat(row.impuesto) / 100 +parseFloat(row.precio_venta) );        
        image = imageName;
        html = "<tr><td width=\'20%\'><img src=\'"+image+"\' class=\'grid-image\'/></td>";
        html += "<td><p><span class=\'nombre_producto\'>"+row.nombre+"</span>&nbsp;<input type=\'hidden\' value=\'"+row.precio_venta+"\' class=\'precio-real\'/><span class=\'precio\'>"+(row.impuesto != "0" ? mostrarNumero(precioimpuesto) : mostrarNumero(row.precio_venta))+"</span></p>";
        html += "<p><span class=\'stock\'>Stock: "+row.stock_minimo+"</span>&nbsp;<input type=\'hidden\' value=\'"+row.precio_compra+"\' class=\'precio-compra-real\'/><span class=\'precio-minimo\'>C&oacute;digo de barra: "+row.codigo+"</span>  -  <span class=\'precio-minimo\'>Precio de venta: "+mostrarNumero(row.precio_venta) +"</span> &nbsp;<span class=\'ubicacion_producto\'>Ubicaci&oacute;n: "+row.ubic+"</span> </p><input type=\'hidden\' class=\'id_producto\' value=\'"+row.id+"\'/><input type=\'hidden\' class=\'codigo\' value=\'"+row.codigo+"\'><input type=\'hidden\' class=\'impuesto\' value=\'"+row.impuesto+"\'></p></td>";
        html += "</tr>";
        
        return html;
}
function pagar()
{   
    $("#descuento_general").val("0");

    if((negocio!="Restaurante") && (negocio!="restaurante")){
        $("#sobrecostos_input").val(0);
    }
    else{
         if($sobrecosto=="no"){
            $("#sobrecostos_input").val(0);
        }
    }

    var tipo_propina=$("input:radio[name=tipo_propina]:checked").val();   
    if(tipo_propina=="porcentaje"){//por porcentaje
    
        var propina = $("#sobrecostos_input").val() || 10;
            valorTotal = $("#subtotal_input").val();
            total = limpiarCampo(parseFloat((valorTotal * propina) / 100));
        
        $("#propina_output").html(propina + "% - " + mostrarNumero(total));
        $("#propina_input").val(propina);

        var propina_pro = $("#sobrecostos_input").val() || 10;
            //valorTotal_pro = $("#subtotal_propina_input").val();
            valorTotal_pro = $("#subtotal_input").val();
            total_pro = parseFloat((valorTotal_pro * propina_pro) / 100);

        $("#propina_output_pro").html(propina_pro + "% - " + mostrarNumero(total_pro));
        $("#propina_input_pro").val(propina_pro);    
        $("#valor_pagar_propina").html(mostrarNumero(parseFloat($("#total").val()) + parseFloat(total_pro)));        
        
    }
    else{//por valor

        var propina = $("#sobrecostos_input").val() || 1000;
        $("#sobrecostos_input_valor").val($("#sobrecostos_input").val());  //guardo el valor que se colocó  
            valorTotal = $("#subtotal_input").val();
            total = limpiarCampo(parseFloat((propina*100) / valorTotal));
        
        $("#propina_output").html(mostrarNumero(total) + "% - " + propina);
        $("#propina_input").val(total);

        var propina_pro = $("#sobrecostos_input").val() || 1000;
            //valorTotal_pro = $("#subtotal_propina_input").val();
            valorTotal_pro = $("#subtotal_input").val();
            total_pro = parseFloat((propina_pro * 100) / valorTotal_pro);

        $("#propina_output_pro").html(mostrarNumero(total_pro) + "% - " + propina_pro);
        $("#propina_input_pro").val(propina_pro);    
        $("#valor_pagar_propina").html(mostrarNumero(parseFloat($("#total").val()) + parseFloat(propina_pro)));        
        $("#sobrecostos_input").val(total_pro);
        total_pro=propina_pro;       
    } 

    var valor_total_entregado = limpiarCampo(parseFloat($("#total").val()) + parseFloat(total_pro));  
    /****nuevo*****/
    $("#valor_pagar").val(formatDollar(($("#total").val())));
    $("#valor_pagar_label").html(formatDollar(($("#total").val())));
    $("#valor_entregado").val(limpiarCampo(valor_total_entregado));
    $("#receipt_money").val(limpiarCampo(valor_total_entregado));
    $("#valor_pagar_hidden").val(limpiarCampo(valor_total_entregado));
    $("#sima_cambio").val(parseInt("0"));
    $("#sima_cambio_label").html(mostrarNumero("0"));
    $("#dialog-forma-pago-form").dialog("open");


};
validarMediosDePago = function(e)
{
    //console.log("aqui");
    var total_acumulado_con_cambio = 0;
    var total_acumulado_sin_cambio = 0;
    var total = $("#valor_pagar_hidden").val() * 1;
    var total_superado = false;
    var total_superado_error = false;
    var valor_a_pagar = 0;
    var estado = {
        resultado: true,
        errores: []
    }
    
    for(var i=0; i<5; i++)
    {
        var selector = i == 0 ? "" : i;

        $("#valor_entregado"+selector).css("border-color", "#ccc");        
        if(($("#forma_pago"+selector).val()!="") && ($("#valor_entregado"+selector).val()>0)){            
            switch($("#forma_pago"+selector).val())
            {
                case "efectivo":
                    total_acumulado_con_cambio += ($("#valor_entregado"+selector).val() * 1);
                    //console.log($("#valor_entregado"+selector).val()+"case2");
                break;
                case "Puntos":
                    total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
                    //console.log($("#valor_entregado"+selector).val()+"case3");
                    var max = $("#valor_entregado"+selector).prop("max") * 1;
                    var valor = $("#valor_entregado"+selector).val() * 1;
                    if (valor > max)
                    {
                        $("#valor_entregado"+selector).css("border-color", "#dd0000");
                        estado.resultado = false;
                        estado.errores.push("La cantidad de puntos seleccionada es superior a la que tiene el cliente actualmente.");
                    }
                break;
                case "nota_credito":                
                    if($("#valor_entregado_nota_credito"+selector).val()!=""){
                        total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
                    }
                    else{
                        estado.resultado = false;
                        estado.errores.push("Debe canjear una nota de crédito");
                    }
                break;
                default:
                    total_acumulado_sin_cambio += ($("#valor_entregado"+selector).val() * 1);
            }
        }else{
            if(($("#forma_pago"+selector).val()=="") && ($("#valor_entregado"+selector).val()>0)){                
                estado.resultado = false;
                estado.errores.push("Hay una forma de pago sin método de pago asociado");
            }
        }
        if(total_acumulado_sin_cambio > total || (total_acumulado_con_cambio >= total && total_acumulado_sin_cambio > 0))
            $("#valor_entregado"+selector).css("border-color", "#dd0000");

        if(total_acumulado_con_cambio + total_acumulado_sin_cambio > valor_a_pagar && total_superado)
            total_superado_error = true;

        if(total_acumulado_con_cambio + total_acumulado_sin_cambio >= total && !total_superado)
        {
            valor_a_pagar = total_acumulado_con_cambio + total_acumulado_sin_cambio
            total_superado = true;
        }
    }

    if(total_acumulado_sin_cambio > total || (total_acumulado_con_cambio >= total && total_acumulado_sin_cambio > 0))
    {
        estado.resultado = false;
        estado.errores.push("Los medios de pago seleccionados tienen valores invalidos.");
    }
   
    if(total_superado_error)
    {
        estado.resultado = false;
        estado.errores.push("Ha seleccionado mas medios de pago de los necesarios para completar el valor de la compra.");
    }

    var subtotal = fijarNumero(total_acumulado_sin_cambio + total_acumulado_con_cambio);
    
    if(estado.resultado)
    {
        if(subtotal < total)
        {
            estado.resultado = false;
            estado.errores.push("El total de los medios de pago no es igual o superior al total de la compra.");
        }
    }

    if(!estado.resltado)
        //console.log(estado);
        
    $("#sima_cambio_hidden").val(fijarNumero(subtotal - total));
    
    var cambiofalta = mostrarNumero(fijarNumero(subtotal - total));
    if (cambiofalta.slice(0,1) === "-"){
        $("#labelCambioFalta").text("Faltante");
        $("#labelCambioFalta").addClass( "redColorAp" );
        $("#sima_cambio").addClass( "redColorAp" );
    } else {
        $("#labelCambioFalta").text("Cambio");
        $("#labelCambioFalta").removeClass( "redColorAp" );
        $("#sima_cambio").removeClass( "redColorAp" );
    }
    
    $("#sima_cambio").val(mostrarNumero(fijarNumero(subtotal - total)));
    $("#sima_cambio_label").html(mostrarNumero(fijarNumero(subtotal - total)));
    return estado;
}
function cambioVentaPendiente()
{
    var cambio = (parseFloat($("#valor_entregado").val()) + parseFloat($("#valor_entregado1").val()) + parseFloat($("#valor_entregado2").val()) + parseFloat($("#valor_entregado3").val()) + parseFloat($("#valor_entregado4").val()) + parseFloat($("#valor_entregado5").val())) - parseFloat($("#valor_pagar_hidden").val());
    //console.log(parseFloat($("#valor_entregado").val())+"--cambio");
    $("#sima_cambio_hidden").val(cambio);

    $("#sima_cambio").val(mostrarNumero(cambio));
    $("#sima_cambio_label").html(mostrarNumero(cambio));
}
function formatDollar(num) {
    return mostrarNumero(num);
}
';
}