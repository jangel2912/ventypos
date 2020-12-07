<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>
<style>
    .modal-header {
        background-color: #5cb85c !important;   
        padding: 0px 0px !important;     
    }
</style>
<?php
$ci = &get_instance();
$ci->load->model("Opciones_model");
$data_moneda = $ci->opciones_model->getDataMoneda();
$total = 0;
$timp = 0;
$subtotal = 0;
$total_items = 0;
$html_tbody = '';

foreach ($data['venta_credito']["detalle_venta"] as $p) {

    $pv = $p['precio_venta'];
    $desc = $p['descuento'];
    $pvd = $pv - $desc;
    $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];
    $total_column = $pvd * $p['unidades'];
    $total_items += $total_column;
    $valor_total = $pvd * $p['unidades'] + $imp;
    $total = $total + $valor_total;
    $timp += $imp;


    $html_tbody = $html_tbody . " <tr>
           <td>" . $p["codigo_producto"] . "</td>
           <td >" . $p["nombre_producto"] . "</td>
           <td style='text-align:center;'>" . $p["unidades"] . "</td>
           <td style='text-align:right;'>" . $ci->opciones_model->formatoMonedaMostrar($p["precio_venta"]) . "</td>
           <td style='text-align:center;'>" . $ci->opciones_model->formatoMonedaMostrar($p['descuento']) . "</td>
           <td style='text-align:right;'>" . $ci->opciones_model->formatoMonedaMostrar($valor_total) . "</td>
        </tr>";
}

$pagos = 0;
$retenciones = 0;

foreach ($data['data'] as $row) {
    $pagos = $pagos + $row->cantidad;
    $retenciones = $retenciones + $row->importe_retencion;
}
$total_venta=$data['venta_credito']['venta']['total_venta'];
$pagos_totales = $pagos + $retenciones;
//$saldo = $total - $pagos_totales;
$saldo = $total_venta - $pagos_totales;
?>
<div class="page-header">    
    <div class="icon">
        <img alt="ver_pagos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_pagos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Facturas", "Pagos a Facturas");?></h1>
</div>
<div class="row">
    <div class="col-md-12">
        <a href="<?= site_url('credito') ?>" class="btn btn-success">Volver a creditos</a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block title">
                <div class="head">
                    <h2><?php echo custom_lang('sima_payment_list', "Detalle factura"); ?> <?php echo $data['numero']; ?></h2>                                          
                </div>
            </div>
            <div id="ticket_wrapper">
                <div class="block" style="margin-bottom: 0px;">
                    <div class="head blue">                        
                        <h2>Total Venta: <?= $ci->opciones_model->formatoMonedaMostrar($total); ?></h2>
                    </div>
                </div>
                <table  class='table'>
                    <tr>
                        <td  style='width: 15%;'><strong><?php echo "Pagos:" ?></strong></td>
                        <td  style='width: 18%;'><?php echo $ci->opciones_model->formatoMonedaMostrar($pagos); ?></td>
                        <td  style='width: 15%;'><strong><?php echo "Retenciones:" ?></strong></td>
                        <td  style='width: 20%;'><?php echo $ci->opciones_model->formatoMonedaMostrar($retenciones); ?></td>
                        <td  style='width: 12%;'><strong><?php echo "Total:" ?></strong></td>
                        <td  style='width: 18%;'><?php echo $ci->opciones_model->formatoMonedaMostrar($pagos_totales); ?><br /></td>
                    </tr>
                    <tr>
                        <td  style='width: 15%;'></td>
                        <td  style='width: 18%;'></td>
                        <td  style='width: 15%;'></td>
                        <td  style='width: 20%;'></td>
                        <td  style='width: 12%;'><strong><?php echo "Saldo:" ?></strong></td>
                        <td  style='width: 18%;'><?php echo $ci->opciones_model->formatoMonedaMostrar($saldo); ?><br />
                            <?php
                            if (($saldo) == '0') {
                                echo "<strong>Factura paga</strong>";
                            }
                            ?>				
                        </td>
                    </tr>

                </table>  

                <table  class='table'>
                    <tr>                    
                        <td style="width: 30%;"><?php echo "<strong>Fecha: </strong>" . $data['venta_credito']['venta']['fecha'] ?></td> 
                        <td style="width: 40%;"><?php echo "<strong>Factura No: </strong>" . $data['venta_credito']['venta']['factura'] ?></td> 
                        <td style="width: 30%;"><?php echo "<strong>Almacen: </strong>" . $data['venta_credito']['venta']['nombre'] ?></td>                                     
                    </tr>
                </table>  

                <table class='table'>
                    <tr>
                        <td style="width: 30%;">
                            <strong>Cliente:</strong> 
                            <?php
                            echo ($data['venta_credito']['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta_credito']['venta']["nombre_comercial"] )
                            ?>
                        </td> 
                        <td style="width: 40%;"><strong>CC: </strong>  <?php echo $data['venta_credito']['venta']["nif_cif"] == "" || $data['venta_credito']['venta']["nif_cif"] == 0 ? "indefinido" : $data['venta_credito']['venta']["nif_cif"] ?> </td>
                        <td style="width: 30%;"><strong>Tel&eacute;fono: </strong><?php echo $data['venta_credito']['venta']['cliente_telefono'] == "" ? "Indefinido" : $data['venta_credito']['venta']['cliente_telefono'] ?></td>                                     
                    </tr>
                </table>    

                <div class="block" style="margin-bottom: 0px;">
                    <div class="head blue">
                        <h2>Items</h2>
                    </div>
                </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="10%">Ref</th>
                                <th width="20%">Producto</th>
                                <th width="15%">Cant</th>
                                <th width="15%">Precio</th>
                                <th width="10%">Desc</th>
                                <th width="30%">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php echo $html_tbody; ?>
                        </tbody>
                    </table>

                    <div class="pagination pagination-centered">
                        <ul>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <div class="col-md-6">
    <div class="block title">
            <div class="head">
                <h2><?php echo custom_lang('sima_payment_list', "Listado de pagos a la factura"); ?> <?php echo $data['numero']; ?></h2>                                          
            </div>
        </div>
        <div class="block">
            <?php
            $message = $this->session->flashdata('message');
            $message1 = $this->session->flashdata('message1');
            if (!empty($message)):
                ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif;

            if (!empty($message1)):
                ?>
                <div class="alert alert-error">
                    <?php echo $message1; ?>
                </div>
            <?php endif; ?>
            
             <?php if($data["estado_caja"] == "cerrada"){ ?>
                <div style="color:#d32f2f; font-size:12px;">Para realizar un abono debe tener una caja abierta, haga clic <a target="_blank" href="<?php echo site_url('caja/apertura'); ?>">aquí</a> para aperturar caja </div>
            <?php }else{ ?>
                <?php if($saldo > 0) {?>
                <!--<a id="btn_nuevo_pago" role="button" class="btn btn-success">
                    <small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_payment', "Nuevo Pago"); ?>
                </a>-->                                    
                    <a id="btn_nuevo_pago" data-tooltip="Nuevo Pago" >                        
                        <img alt="Nuevo Pago" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                    </a>                 
            <?php }} ?>
    
            <div class="head blue">                
                <h2><?php echo custom_lang('sima_all_payment', "Todos los pagos"); ?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%"><?php echo custom_lang('sima_date', "Fecha"); ?></th>
                            <th width="25%"><?php echo custom_lang('sima_amount', "Cantidad"); ?></th>                             
                            <th width="25%"><?php echo custom_lang('sima_type', "Tipo"); ?></th>
                            <th width="25%"><?php echo custom_lang('sima_type', "Retención"); ?></th>                               
                            <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['data'] as $row): ?>
                            <tr>
                                <td><?php echo $row->fecha_pago; ?></td>
                                <td><?php echo $ci->opciones_model->formatoMonedaMostrar($row->cantidad); ?></td>                                                    
                                <td><?php echo $row->tipo; ?></td>
                                <td><?php echo $row->importe_retencion; ?></td>                                                                                      
                                <td>   
                                    <?php if (($saldo) != '0') {?>                                                                                      
                                    <a href="<?php echo site_url("pagos/eliminar/" . $row->id_pago . "?factura=" . $data["id_factura"]); ?>" class="button red acciones" onclick="if (confirm('<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>')) {
                                        return true;
                                    } else {
                                        return false
                                    }" ><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>                         
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $('#btn_nuevo_pago').on('click',function(){
        waitingDialog.show();
    });
});
var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOMp
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:8%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="padding-left:10px; color:#fff;">Nuevo pago a la factura: <?php echo $data['numero']; ?> - <span style="font-size: 20px;" > <?= $ci->opciones_model->formatoMonedaMostrar($total);?></span></h3></div>' +
			'<div class="modal-body">' +
                '<table class="table"> '+
                    '<tr>'+
                    '    <td  style="width: 15%;"><strong><?php echo "Pagos:" ?></strong></td>'+
                    '    <td  style="width: 18%;"><?php echo $ci->opciones_model->formatoMonedaMostrar($pagos); ?></td>'+
                    '    <td  style="width: 15%;"><strong><?php echo "Retenciones:" ?></strong></td>'+
                    '    <td  style="width: 20%;"><?php echo $ci->opciones_model->formatoMonedaMostrar($retenciones); ?></td>'+
                    '    <td  style="width: 12%;"><strong><?php echo "Total:" ?></strong></td>'+
                    '    <td  style="width: 18%;"><?php echo $ci->opciones_model->formatoMonedaMostrar($pagos_totales); ?><br /></td>'+
                    '</tr>'+
                    '<tr>'+
                    '    <td  style="width: 15%;"></td>'+
                    '    <td  style="width: 18%;"></td>'+
                    '    <td  style="width: 15%;"></td>'+
                    '    <td  style="width: 20%;"></td>'+
                    '    <td  style="width: 12%;"><strong><?php echo "Saldo:" ?></strong></td>'+
                    '    <td  style="width: 18%;"><?php echo $ci->opciones_model->formatoMonedaMostrar($saldo); ?><br />'+
                    '        <?php if (($saldo) == "0") { ?>'+
                    '            <?php echo "<strong>Factura paga</strong>"; } ?>'+
                    '    </td>'+
                    '</tr>'+
                '</table>'+
                '<?php echo form_open("pagos/nuevo/" . $data['id_factura'], array("id" => "validate")); ?>'+
                '<input type="hidden" name="id_factura" value="<?php echo $data['id_factura']; ?>"/>'+
                '<div class="row-fluid">'+
                '    <div class="span6">'+
                '        <div><?php echo custom_lang('sima_date', "Fecha"); ?>:</div>'+
                '        <div><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha_pago" id="fecha_pago"/>'+
                '            <?php echo form_error('fecha_pago'); ?>'+
                '        </div>'+
                '    </div>'+
                '    <div class="span6">'+
                '        <div><?php echo custom_lang('sima_type', "M&eacute;todo"); ?>:</div>'+
                '        <div>'+
                '            <select name="tipo" id="s_tipo">'+
                '                <?php foreach ($data['forma_pago'] as $f) { ?>'+
                '                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>'+
                '                    <?php } ?>'+
                '</select>'+
                '<?php echo form_error('tipo'); ?>'+
                '</div>'+
                '</div>'+
                '</div><div class="clearfix"></div> <br>'+
                '<div class="row-fluid">'+
                '    <div class="span6">'+
                '        <div id="div_otros_medios_pago" style="display: none;">'+
                '        </div>'+
                '        <div><?php echo custom_lang('sima_amount', "Pago"); ?>:</div>'+
                '        <div><input class="pago_text" type="text" name="cantidad" id="cantidad" placeholder=""/>'+
                '            <?php echo form_error('cantidad'); ?>'+
                '        </div>'+
                '    </div>'+
                '    <div class="span6">'+
                '        <div><?php echo custom_lang('sima_amount', "Retención (Opcional)"); ?>:</div>'+
                '        <div><input class="pago_text" type="text" value="0" name="importe_retencion" id="retencion" placeholder=""/>'+
                '            <?php echo form_error('importe_retencion'); ?>'+
                '        </div>'+
                '    </div>'+
                '</div><div class="clearfix"></div> <br>'+
                '<div class="row-fluid">'+
                '<div class="span6"></div>'+
                '<div class="span6">'+
                'Pago Total: <span id="span_pago_total"></span>'+
                '</div>'+
                '<div class="toolbar bottom tar">'+
                '<div>'+
                '        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>&nbsp;'+
                '<?php if($saldo > 0) {?>'+
                '        <button class="btn btn-success guardarabono" type="submit"><?php echo custom_lang("sima_submit", "Guardar"); ?></button>'+
                '        <?php }?>'+                
                '    </div>'+
                '</div>'+
                '</div><div class="clearfix"></div> <br>'+
                '<?php echo form_close(); ?>'+
			'</div>' +
		'</div></div></div>');

	return {
		/**
		 * Opens our dialog
		 * @param message Custom message
		 * @param options Custom options:
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
		 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
		 */
		show: function (message, options) {
        $dialog.find("#fecha_pago").datepicker({
            dateFormat: 'yy/mm/dd'
        });

        $dialog.find(".pago_text").keyup(function () {
            var cantidad = parseFloat($dialog.find("#cantidad").val())
            var retencion = parseFloat($dialog.find("#retencion").val());
            var pt = 0;

            if (isNaN(cantidad)) {
                cantidad = 0;
                $dialog.find("#cantidad").attr("value","");
            }
            if (isNaN(retencion)) {
                retencion = 0;
                $dialog.find("#retencion").attr("value","");
            }
            pt = cantidad + retencion;
            
            $dialog.find("#span_pago_total").html(pt);
        });


            	// Opening dialog
			$dialog.modal();
        }
    }

})(jQuery);

    var total = (<?php echo round($total,$data_moneda->decimales); ?>);
    var pagos = (<?php echo round($pagos,$data_moneda->decimales); ?>);
    var saldo = (<?php echo round($saldo,$data_moneda->decimales); ?>);;
//    console.log(total + "t" + pagos + "s" + saldo);
    Number.prototype.formatMoney = function (c, d, t) {
        var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
                j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    $(document).ready(function () {
        $("#s_tipo").change(function(){
            armar_medio_pago($(this).val());
        });
    });
    $(document).on('submit','#validate',function(event){        
            $(".guardarabono").prop('disabled',true);
            var cantidad = parseFloat($("#cantidad").val())
            var retencion = parseFloat($("#retencion").val());
            var pt = 0;
            
            if (isNaN(cantidad)) {
                cantidad = 0;
            }
            if (isNaN(retencion)) {
                retencion = 0;
            }
            pt = cantidad + retencion + pagos;
            
            if(cantidad>0){
                if (pt > 0 && pt <= parseFloat(total))
                    return true;
                else {     
                    swal({
                        position: 'center',
                        type: 'error',
                        title: "error",
                        html: 'La cantidad supera el saldo , saldo pendiente $' + (saldo).formatMoney(2, '.', ','),
                        showConfirmButton: false,
                        timer: 1500
                    })         
                    //alert('La cantidad supera el saldo , saldo pendiente $' + (saldo).formatMoney(2, '.', ','));
                    $(".guardarabono").prop('disabled',false);
                    return false;
                }
            }else{
                //alert('La cantidad debe ser mayor a 0');
                swal({
                    position: 'center',
                    type: 'error',
                    title: "error",
                    html: 'La cantidad debe ser mayor a 0',
                    showConfirmButton: false,
                    timer: 1500
                })  
                $(".guardarabono").prop('disabled',false);
                 return false;
            }

        });

function armar_medio_pago(medio){
    
    if(String(medio) ==='nota_credito'){
        html='<div>Numero de nota:</div>';
        html+= '<input type="text" id="t_nota_credito" name="valor_entregado_nota_credito" placeholder="Código Nota Credito">';
        html+='<a  href="javascript:void(0);" onclick="consultar_estado_nota_credito()" class="btn" index="" style="display: block !important"><span class="icon glyphicon glyphicon-search" style=""></span></a>';
        $("#div_otros_medios_pago").html(html);
        $("#div_otros_medios_pago").show();
    }
}

function consultar_estado_nota_credito(){
    if($("#t_nota_credito").val() != ''){
       $.ajax({
            type:"post",
            url: "<?php echo site_url("/notacredito/estadoNotaCredito"); ?>",
            dataType: "json",
            data: {"codigo":$("#t_nota_credito").val()},
            success: function(result){
                validar_estado_nota(result);
            }
        });     
    }else{
        //alert('se debe escribir el codigo de la nota credito');
        swal({
            position: 'center',
            type: 'error',
            title: "error",
            html: 'Se debe escribir el código de la nota crédito',
            showConfirmButton: false,
            timer: 1500
        })  
    }
    
}

function validar_estado_nota(result){

        var estado = result.estado;
        var nombre = result.nombre;
        var valor = result.valor;
            
        if( estado == "empty" ){
            //alert("La nota credito no existe");  
            swal({
                position: 'center',
                type: 'error',
                title: "error",
                html: "La nota credito no existe",
                showConfirmButton: false,
                timer: 1500
            })
        }
        if( estado == "cancelado" ){
           // alert("La "+nombre+" ya ha sido canjeada");
            swal({
                position: 'center',
                type: 'error',
                title: "error",
                html: "La "+nombre+" ya ha sido canjeada",
                showConfirmButton: false,
                timer: 1500
            })
        }
        if( estado == "activo" ){
            //alert("La "+nombre+" no ha sido pagada");
            swal({
                position: 'center',
                type: 'error',
                title: "error",
                html: "La "+nombre+" no ha sido pagada",
                showConfirmButton: false,
                timer: 1500
            })
        }
        //console.log(valor);
        if( estado == "pagado" ){
            $("#cantidad").prop('readonly', true);
            $("#cantidad").val( valor );
        }            

}

</script>
