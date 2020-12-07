<?php  
include_once 'SEGURIDAD/db_connect.php';
include_once 'SEGURIDAD/functions.php';

sec_session_start(); if (login_check($mysqli) == true) {

$usu_login=$_SESSION["username"];
$usu_nombre= $_SESSION["username"];


require("../CLASES/factura.class.php");
require("../CLASES/entorno_grafico.class.php");

$factura = new factura();  


$consulta = $factura->consulta_datos("select iva_des from iva where iva_cod = 1 ");
                                                                   
 if($factura->num_rows_consulta($consulta)>0){  
  while($resultado = $factura->fetch_array_consulta($consulta)){ 
     $iva=$resultado['iva_des']; 

  }  
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<head>
 <meta charset="utf-8">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport"
        content="width=device-width, target-densitydpi=170, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="format-detection" content="telephone=no, email=no">
  <meta name="apple-mobile-web-app-capable" content="no">
<title></title>
<link rel="stylesheet" href="CSS-APP/ESTILO-MOVIL-1.css?act=11" type="text/css" media="all" />
<link rel="stylesheet" href="CSS-APP/FACTURACION-MOVIL.css?act=11" type="text/css" media="all" />

<link type="text/css" rel="stylesheet" href="../../../APLICATIVO-FACTURACION/CSS-APLICATIVO/estilo.css" />

<script type="text/javascript" src="../../../APLICATIVO-FACTURACION/SCRIPTS/VALIDACION/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../../../APLICATIVO-FACTURACION/SCRIPTS/VALIDACION/validacion.js"></script>

<link type="text/css" rel="stylesheet" href="CSS-APP/jquery-ui-1.8.4.custom-movil.css" />
<link type="text/css" rel="stylesheet" href="CSS-APP/estilo-movil-autocompletado.css" />

<script type="text/javascript" src="../MOVIL/AUTOCOMPLETADO_AJAX/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" src="../MOVIL/AUTOCOMPLETADO_AJAX/ajax_consult.js?act=9"></script> 


</head>
<body>
<?php
include("MENU-USUARIO.php");
?>
<div class="fondo-factura" style="margin:auto">
<table class="CABECERA-FACTURA"  style="margin:auto">
<tbody>
<tr>
<td colspan="2" align="center">
<input type="text" name="buscar_cliente" id="buscar_cliente" size="36" class="campo_auto_cliente_1 ui-autocomplete-input" value="Cliente" onClick="if(this.value=='Cliente') this.value=''" onBlur="if(this.value=='') this.value='Cliente'" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"> 
</td></tr>
<tr>
</tr>
</tbody>
</table>
<div id="resultados_cliente"><input type="hidden" name="txt" value="0" size="70" class="campo_oculto_factura" id="campo_oculto_factura" onKeyPress="return campo(event)"></div>
<table height="10px"><tr><td></td></tr></table>


<table class="DETALLE-FACTURA" style="margin:auto">
<thead>
<tr>
<td class="CABECERA-DETALLE-PRODUCTO">Descripción</td>
<td class="CABECERA-DETALLE-CANTIDAD">V Unitario</td>
<td class="CABECERA-DETALLE-CANTIDAD">V Total</td>
</tr>
</thead>
<tbody id="productos-detail">

</tbody>
</table>

<table class="VALOR-PAGAR-1">
<tbody><tr>
<td class="VALOR-PAGAR-2">Subtotal: &nbsp;<b class="subtotal">0</b>&nbsp;</td></tr>
<tr><td class="VALOR-PAGAR-2">Descuento: &nbsp;<b class="descuento">0</b>&nbsp;</td>
</tr><tr><td class="VALOR-PAGAR-2">Total: &nbsp;<b class="total_civa">0</b>&nbsp;</td></tr>
</tbody>
</table>
<table height="10px"><tr><td></td></tr></table>

<input type="hidden" name="descuento_final" size="5" class="descuento_final" id="descuento_final">
<div class="fondo-factura-agregar"><div class="contenido-factura-agregar"><center>
<input type="text" name="buscar_usuario" id="buscar_usuario" size="36" class="campo_auto_producto_1 ui-autocomplete-input" value="Producto" onClick="if(this.value=='Producto') this.value=''" onBlur="if(this.value=='') this.value='Producto'" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"> 
<br>&nbsp;  
Cantidad: <input type="text" name="txtcantidad" size="5" class="campo_cantidad" id="campo_cantidad" onKeyPress="return campo_numeros(event)"><br>
<br><input type="button" name="ingresar2" id="BOTON_AGREGAR" class="boton_login"  value="Agregar Producto">
</center></div></div><table height="5px"><tr><td></td></tr></table>
<br><br><div id="resultados"><input type="hidden" name="txt" value="0" size="70" class="campo_oculto_factura_prod" id="campo_oculto_factura_productos" onKeyPress="return campo(event)"></div><br>

<center>
<textarea name="comen" id="comen" rows="4" cols="35" maxlength="350"></textarea>
<table  style="width:200px">
<tr>
<?PHP
$consulta = $factura->consulta_datos("select * from tipo_cliente");
                                                                
  if($factura->num_rows_consulta($consulta)>0){  
    while($resultados = $factura->fetch_array_consulta($consulta)){
	
if($resultados['des_tipcli'] == 'E'){ $select = 'checked'; 	  
?>
<td valign="top" align="center"><? echo $resultados['des_tipcli']; ?><br><input type="radio" name="pago" id="pago" value="<? echo $resultados['cod_tipcli1']; ?>" <? echo $select; ?>> </td>
<?
}
else{
?>
<td valign="top" align="center"><? echo $resultados['des_tipcli']; ?><br><input type="radio" name="pago" id="pago" value="<? echo $resultados['cod_tipcli1']; ?>"> </td>
<?
}

  }  
}
?>
</tr>
</table>
<BR>
<input type="button" name="Submit" value="Guardar Factura" id="enviar_factura" class="boton_login" style="margin:auto"/>
</div><BR>
<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
	 
<script type="text/javascript">


        function eliminar(id){


                $("."+id+"").remove();

                calculate();

        }


$(document).ready(function(){
 
     
     function formatDollar(num) {
        num = parseInt(num);
        var p = num.toFixed(2).split(".");
        return p[0].split("").reverse().reduce(function(acc, num, i, orig) {
            return  num + (i && !(i % 3) ? "," : "") + acc;
        }, "") /*+ "." + p[1]*/;
    }

 
      $("#BOTON_AGREGAR").click(function(e){
	  
	  if($("#pro_desc").val() != "SI"){
	  
	   
	  	  
	   if($("#campo_oculto_factura_productos").val() != "0"){
	   
	   var repite = '';
	   
	    $(".nombrepro").each(function(x){
		
	      if($('#nombrepro').val() == $(".nombrepro").eq(x).val()){     
		    repite = 'si';
		  } 
	    
        });
	   	   
	    if($('#cantidad_actual').val() == "0"){     
		  alert('producto agotado');
		}  
	    else if(repite == "si"){     
		  alert('Ya agrego este producto a la factura');
		} 
	    else if($('#campo_cantidad').val() == ""){     
		  alert('No le ingreso cantidad al producto');
		} 
	    else if($('#nombrepro').val() == "undefined"){     
		  alert('Escoja un producto');
		} 			
	    else{
        document.getElementById("BOTON_AGREGAR").disabled = true;
			  
        rowHtml = '<tr  class="'+$('#id_producto').val()+'">';
        rowHtml += '<td class="DETALLE-PRODUCTO" style=" font-size:11;"> '+$('#nombrepro').val()+'</td>';
        rowHtml += '<td class="DETALLE-VALTOTAL"> '+formatDollar($('#pro_valventa').val())+' <input type="hidden" name="precio" size="5" class="precio_id" value="'+$('#pro_valventa').val()+'">  </td>';
        rowHtml += '<td class="DETALLE-VALTOTAL ptotal"> 0  </td>';						
		rowHtml += "</tr>";
		rowHtml += '<tr  class="'+$('#id_producto').val()+'">';
		rowHtml += '<td colspan="1" class="DETALLE-VALTOTAL" style="text-align:center"> Cantidad: '+$('#campo_cantidad').val()+' <input type="hidden" name="cant" size="5" class="cant_id" value="'+$('#campo_cantidad').val()+'"> </td>';	
		rowHtml += '<td colspan="4" class="DETALLE-VALTOTAL"   style="text-align: center;"><a class="delete"  onclick="eliminar('+$('#id_producto').val()+');" href="#"><img src="boton_eliminar.gif?con=2" width="80px" height="26px"></a><input type="hidden" name="id_producto[]" size="5" class="id_producto" value="'+$('#id_producto').val()+'"><input type="hidden" name="nombrepro[]" size="5" class="nombrepro" value="'+$('#nombrepro').val()+'"><input type="hidden" name="pro_valventa[]" size="5" class="pro_valventa" value="'+$('#pro_valventa').val()+'"><input type="hidden" name="totaliva[]" size="5" class="total_iva" ><input type="hidden" name="total_row[]" size="5" class="total_row" ><input type="hidden" name="pro_compra[]" size="5" class="pro_compra" value="'+$('#pro_compra').val()+'"><input type="hidden" name="categoria[]" size="5" class="categoria" value="'+$('#categoria').val()+'"><input type="hidden" name="descuento[]" size="5" class="desc" value="0"> </td>';	
		rowHtml += "</tr>";
		rowHtml += '';

        if($("#productos-detail tr").eq(0).hasClass("nothing")){
                    $("#productos-detail").html(rowHtml);
        }else{
                $("#productos-detail").append(rowHtml);
        }
		
		}
		
		$('#buscar_usuario').val(''); $('#campo_cantidad').val('');
		
		calculate();
		
		setTimeout(function(){  document.getElementById("BOTON_AGREGAR").disabled = false; }, 3000);
		
		 $('#resultados').hide();
		 
		}
     }

	  
	  if($("#pro_desc").val() == "SI"){
	  
	  
	   if($("#campo_oculto_factura_productos").val() != "0"){
	   
	   document.getElementById("BOTON_AGREGAR").disabled = true;
	   
        rowHtml = '<tr  class="'+$('#id_producto').val()+'">';
        rowHtml += '<td class="DETALLE-PRODUCTO" style=" font-size:11;"> '+$('#nombrepro').val()+'</td>';
        rowHtml += '<td class="DETALLE-VALTOTAL"> '+formatDollar($('#pro_valventa').val())+' <input type="hidden" name="precio" size="5" class="precio_id" value="0">  </td>';
        rowHtml += '<td class="DETALLE-VALTOTAL ptotal"> 0  </td>';						
		rowHtml += "</tr>";
		rowHtml += '<tr  class="'+$('#id_producto').val()+'">';
		rowHtml += '<td colspan="1" class="DETALLE-VALTOTAL" style="text-align:center"> Cantidad: '+$('#campo_cantidad').val()+' <input type="hidden" name="cant" size="5" class="cant_id" value="0"> </td>';	
		rowHtml += '<td colspan="4" class="DETALLE-VALTOTAL"   style="text-align: center;"><a class="delete"  onclick="eliminar('+$('#id_producto').val()+');" href="#"><img src="boton_eliminar.gif?con=2" width="80px" height="26px"></a><input type="hidden" name="id_producto[]" size="5" class="id_producto" value="'+$('#id_producto').val()+'"><input type="hidden" name="nombrepro[]" size="5" class="nombrepro" value="'+$('#nombrepro').val()+'"><input type="hidden" name="pro_valventa[]" size="5" class="pro_valventa" value="'+$('#pro_valventa').val()+'"><input type="hidden" name="totaliva[]" size="5" class="total_iva" ><input type="hidden" name="total_row[]" size="5" class="total_row" ><input type="hidden" name="pro_compra[]" size="5" class="pro_compra" value="'+$('#pro_compra').val()+'"><input type="hidden" name="categoria[]" size="5" class="categoria" value="'+$('#categoria').val()+'"><input type="hidden" name="descuento[]" size="5" class="desc" value="'+$('#pro_compra').val()+'"></td>';	
		rowHtml += "</tr>"; 
	
        if($("#productos-detail tr").eq(0).hasClass("nothing")){
                    $("#productos-detail").html(rowHtml);
        }else{
                $("#productos-detail").append(rowHtml);
        }
		
		$('#buscar_usuario').val(''); $('#campo_cantidad').val(''); $('#campo_oculto_factura_productos').val('0');
		
		calculate();
					
		setTimeout(function(){  document.getElementById("BOTON_AGREGAR").disabled = false; }, 3000);
		
		 $('#resultados').hide();
		 			  
	  }	
	  
	  } 
	 

     });
	 



        $(".delete").live("click",(function(){
             calculate();
         }));

        function calculate(tipo){

            var subtotal=0, ivatotal = 0;
           var descuento = 0, total_final=0;
            $(".precio_id").each(function(x){

                psi = parseInt($(".precio_id").eq(x).val());

                quantity = parseInt($(".cant_id").eq(x).val());

                //TOTAL FILA
                suma_row = psi *  quantity; //Precio + impuesto
				iva_row = Math.round((psi * <?php echo $iva; ?>) - psi) * quantity; //Precio + impuesto
				  
	           //  return ($this->vrneto * $this->iva) - $this->vrneto;  			
				//TOTALES
                subtotal += suma_row; //Precio - descuento sin impuestos
				ivatotal += iva_row;

                $(".iva_row").eq(x).text(formatDollar(iva_row));
				
				total_final =  suma_row;
				
				$(".ptotal").eq(x).text(formatDollar(total_final));
				
				$(".total_iva").eq(x).val(iva_row);
				$(".total_row").eq(x).val(suma_row);
				
			    descuento += parseInt($(".desc").eq(x).val());
						

            });
			
	     	descuento = Math.round(((parseInt(subtotal) * parseInt(descuento)) / 100));
			total_final = parseInt(subtotal) - parseInt(descuento);
			
			        
            $(".subtotal").html((formatDollar(subtotal)));

            $(".descuento").html(formatDollar(descuento));

            $(".total_civa").html(formatDollar(total_final));

            $("#descuento_final").val(descuento);

        }

 
      $("#enviar_factura").click(function () { 
		       
			 
			   
            productos_list = new Array();

             $(".id_producto").each(function(x){
               if($('.total_row').eq(x).val() > 0){
                productos_list[x] = {

                    'id_producto': $('.id_producto').eq(x).val() 
					,'nombrepro': $('.nombrepro').eq(x).val() 
					,'pro_valventa': $('.pro_valventa').eq(x).val() 
					,'total_iva': $('.total_iva').eq(x).val() 
                    ,'total_row': $('.total_row').eq(x).val()
				    ,'pro_compra': $('.pro_compra').eq(x).val()
					,'categoria': $('.categoria').eq(x).val()
					,'cant_id': $('.cant_id').eq(x).val()
					
                }
               }
            });
	

	         if($("#campo_oculto_factura").val() == "0"){

               alert("Escoja el cliente");

            }
			else  if(productos_list.length == "0"){

               alert("Esta factura no tiene productos");

            }
			else{
	
		           document.getElementById("enviar_factura").disabled = true; 
				   
			<?php  		 
				$hora = getdate(time());
                $hora_final = ( $hora["hours"] . ":" . $hora["minutes"] . ":" . $hora["seconds"] );
			 ?>	 		

            $.ajax({
                 
				  url: "FACTURA-GUARDAR.php"
				 
                 ,dataType: 'json'
				 
			     ,type: "POST"
		         
			     ,data: {

                     cmbcliente: $("#cmbcliente").val()
					 
					 ,txtusu: "<?php echo $usu_login; ?>"
					 
					 ,txtfecha: "<?php  echo @date("Y-m-d"); ?>"

					 ,txthora: "<?php  echo $hora_final; ?>" 
					 
					 ,comen: $("#comen").val()
					
                     ,datos: productos_list
					 
                     ,descuento: $("#descuento_final").val()
					 
					 ,pago: $('input:radio[name=pago]:checked').val()


                }

                ,error: function(jqXHR, textStatus, errorThrown ){
		
                     alert('Error vuelva a intentarlo otra vez')
                     location.href = "INGRESAR-FACTURA-MOVIL-PROTO.php";

                }

                ,success: function(data){
			
                     alert('La factura '+data["proc"]+' fue creada correctamente')
                     location.href = "INGRESAR-FACTURA-MOVIL-PROTO.php";

                }


            });	 
						
		}	

     }); 
	 
	 

});

        
</script>



<?php 

}
//fin session
else{

include_once('../errorsesion.html');

}
?>