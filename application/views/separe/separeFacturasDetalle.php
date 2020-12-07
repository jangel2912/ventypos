<?php


$ci = &get_instance();
$ci->load->model("opciones_model");
$total = 0;

    $timp  = 0;

    $subtotal = 0;

    $total_items = 0;

    $html_tbody='';
    
    
    $detalleVenta = (array) $data['factura_separe']["detalle_venta"];
        
    $estado = $data['factura_separe']["estado"];
    $idFactura = $data['factura_separe']["id_factura"];
    //var_dump($detalleVenta);
    foreach ( $detalleVenta as $p) {

        $pv = $p->precio_venta;

        $desc = $p->descuento;

        $pvd = $pv - $desc;

        $imp = $pvd * $p->impuesto / 100 * $p->unidades;

        $total_column = $pvd * $p->unidades;

        $total_items += $total_column;

        $valor_total = $pvd * $p->unidades + $imp ;
        
        $total = $total + $valor_total;
        $total = $ci->opciones_model->redondear($total);
        $valor_total = $ci->opciones_model->redondear($valor_total);
        $timp+=$imp;
        $html_tbody = $html_tbody." <tr>

           <td>".$p->codigo_producto."</td>

           <td >".$p->nombre_producto."</td>

           <td style='text-align:center;'>".$p->unidades."</td>

            <td style='text-align:right;'>".number_format($p->precio_venta)."</td>

           <td style='text-align:center;'>". $p->descuento."</td>

           <td style='text-align:right;'>".number_format($valor_total)."</td>
            <!--<td style='text-align:left;'>
                <a align='right' href='javascript: void(0)' id='".$p->id."' class='button red eliminarP'>
                    <div class='icon'><span class='ico-remove'></span></div>
                </a>
            </td>-->
       
        </tr>";

    }

    $pagos=0;

    foreach ($data['data'] as $row){
        $pagos = $pagos+ $row->valor_entregado;
    }
    
    $nota_plan_separe = '';
    $data_nota = json_decode($data['factura_separe']['venta']['nota']);
    if(isset($data_nota->nota_plan_separe)) {
        $nota_plan_separe = $data_nota->nota_plan_separe;
    }
?>
<style>
    .modal-header {
        background-color: #5cb85c !important;        
    }
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="Plan separe" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_plan_separe']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Factura Plan Separe", "Factura Plan Separe");?></h1>
</div>
<!--
<div class="row-fluid">
    <div class="block">
        <a href="<?php echo site_url("ventas_separe/facturas")?>" class="btn btn-success" style="margin-left: 10px;"> <?php echo custom_lang('sima_new_sales_man', " Facturas ");?></a> 
        <a href="<?php echo site_url("ventas/nuevo")?>" class="btn btn-success" style="margin-left: 10px;"> <?php echo custom_lang('sima_new_sales_man', " Ventas ");?></a> 
    </div>    
</div>-->
<div class="row-fluid">    
        <div class="col-md-12">
            <div class="block">
               <?php
                $message = $this->session->flashdata('message');
                $message1 = $this->session->flashdata('message1');

                if(!empty($message)):?>
                    <div class="alert alert-success">
                        <?php echo $message;?>
                    </div>
                <?php endif; 
                
                if(!empty($message1)):?>
                    <div class="alert alert-error">
                        <?php echo $message1;?>
                    </div>
                <?php endif; ?>

                <?php 
                    $permisos = $this->session->userdata('permisos');
                    $is_admin = $this->session->userdata('is_admin');
                    if(in_array("11", $permisos) || $is_admin == 't'):?>
                    <div class="col-md-1">
                        <a href="<?php echo site_url("ventas/nuevo")?>" data-tooltip="Nueva Venta">                        
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                        </a>                    
                    </div>
                    
                <?php endif;?>
                <div class="col-md-6 btnderecha">                    
                    <div class="col-md-2 col-md-offset-8">
                        <a href="<?php echo site_url("ventas_separe/facturas")?>" data-tooltip="Listado Plan Separe">                        
                            <img alt="Listado Plan Separe" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['plan_separe_verde']['original'] ?>">                             
                        </a>  
                    </div>  
                    <div class="col-md-2">                       
                        <a href="<?php echo site_url("ventas_separe/plan_separe_anulado")?>" data-tooltip="Plan Separe Anuladas">                            
                            <img alt="Plan Separe Anuladas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['ps_anuladas_verde']['original'] ?>">                                                           
                        </a>              
                    </div>                  
                </div>                
            </div>
        </div>
    </div> 
<div class="row-fluid">

    <div class="span7">

        <div class="block title">

            <div class="head">
                
                <h2><?php echo custom_lang('sima_payment_list', "Detalle factura");?> <?php echo $data['numero'];?></h2>                                          

            </div>

        </div>

                        
        <div id="ticket_wrapper">

            <div class="block" style="margin-bottom: 0px;">
                <div class="head blue">
                    <h2>Factura 
                        <?php if(($total-$pagos)=='0'){ 
                            echo "Paga";
                        } ?>	
                    </h2>
                </div>
            </div>

            <table  class='table'>
                <tr>
                    <td  style='width: 12%;'><strong><?php echo "Total" ?></strong></td>
                    <td  style='width: 18%;'><?php echo number_format($total); ?></td>
                    <td  style='width: 20%;'><strong><?php echo "Total pagos" ?></strong></td>
                    <td  style='width: 20%;'><?php echo number_format($pagos); ?></td>
                    <td  style='width: 12%;'><strong><?php echo "Saldo" ?></strong></td>
                    <td  style='font-size:16px; font-weight:bold; color: #449d44; width: 18%;'><?php echo number_format($total-$pagos ); ?><br />						
					</td>
                </tr>
            </table>  

            <table  class='table'>
                <tr>                    
                    <td style="width: 30%;"><?php echo "<strong>Fecha: </strong>" . $data['factura_separe']['venta']['fecha'] ?></td> 
                    <td style="width: 40%;"><?php echo "<strong>Factura No: </strong>" . $data['factura_separe']['venta']['factura'] ?></td> 
                    <td style="width: 30%;"><?php echo "<strong>Almacen: </strong>" . $data['factura_separe']['venta']['nombre'] ?></td>                                     
                </tr>

            </table>  

            <table class='table'>

                <tr>

                    <td style="width: 30%;">
                        <strong>Cliente:</strong> 
                        <?php  
                         echo ($data['factura_separe']['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['factura_separe']['venta']["nombre_comercial"] ) 
                        ?>
                    </td> 
                 
                    <td style="width: 40%;"><strong>CC: </strong>  <?php echo $data['factura_separe']['venta']["nif_cif"] == "" || $data['factura_separe']['venta']["nif_cif"] == 0? "indefinido" :$data['factura_separe']['venta']["nif_cif"] ?> </td>
                    <td style="width: 30%;"><strong>Tel&eacute;fono: </strong><?php echo $data['factura_separe']['venta']['cliente_telefono'] == "" ? "Indefinido" : $data['factura_separe']['venta']['cliente_telefono'] ?></td>     

                </tr>
            </table> 

            <table class='table'>
                <tr>
                    <td style="width:100%">  <strong>Nota: </strong> <?= $nota_plan_separe;?> </td>
                </tr>
            </table>   
                
            <div class="block" style="margin-bottom: 0px;">
                <div class="head blue">
                    <h2>Productos</h2>
                </div>
            </div>

            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Ref</th>
                            <th width="20%">Producto</th>
                            <th width="13%">Cant</th>
                            <th width="13%">Precio</th>
                            <th width="8%">Desc</th>
                            <th width="28%">Total</th>                           
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

    <div class="span5">
        <div class="block title">
            <div class="head">
                <h2><?php echo custom_lang('sima_payment_list', "Listado de pagos a la factura");?> <?php echo $data['numero'];?></h2>  
            </div>
        </div>
        <div class="block">
                        
            <?php if($data["estado_caja"] == "cerrada"){ ?>
                <div style="color:#d32f2f; font-size:12px;">Para realizar un abono debe tener una caja abierta, haga clic <a target="_blank" href="<?php echo site_url('caja/apertura'); ?>">aquí</a> para aperturar caja </div>
            <?php }else{ ?>
                <div class="col-md-12">
                    <div class="col-md-6">                        
                        <a id="btnAddPago" style="display:none" href="#myModal" role="button" data-toggle="modal" data-tooltip="Nuevo Pago" >                        
                            <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                        </a> 
                        <a id="btnFacturar" style="display:none" href="javascript:void(0)" role="button" data-toggle="modal" data-tooltip="Facturar">                        
                            <img alt="facturar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['facturar']['original'] ?>">                             
                        </a>    
                    </div>                   
                </div>
                <!--<a id="btnAddPago" style="display:none" href="#myModal" role="button" class="btn btn-success" data-toggle="modal"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_payment', "Nuevo Pago");?></a>
           
                <a id="btnFacturar" style="display:none" href="javascript:void(0)" role="button" class="btn green" data-toggle="modal"><small class="ico-plus icon-white" style="margin-right:10px;margin-top:2px;"></small><?php echo custom_lang('sima_new_payment', " FACTURAR ");?></a>
            -->
             <?php } ?>
           <!-- <div class="head blue">
                <h2><?php echo custom_lang('sima_all_payment', "Todos los pagos");?></h2>
            </div>-->
            <div class="block title">
                <div class="head blue">
                    <h2><?php echo custom_lang('sima_all_payment', "Todos los pagos");?></h2> 
                </div>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>                               
                                <th width="33%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="33%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>                            
                                <th width="33%"><?php echo custom_lang('sima_type', "Tipo");?></th>
                                <th width="33%"><?php echo custom_lang('sima_action', "Acciones"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $row):?>
                            <tr>
                                <td><?php echo $row->fecha; ?></td>
                                <td><?php echo number_format($row->valor_entregado);?></td>                         
                                <td><?php echo $row->forma_pago;?></td>
                                <td>  
                                    <?php if(in_array(13,$permisos) || $is_admin == 't' ){ ?>                                                                                       
                                    <a href="<?php echo site_url("ventas_separe/eliminarPago/".$row->id_pago."/".$idFactura); ?>" class="button red acciones eliminarabono" onclick="if (confirm('<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?"); ?>')) {
                                            return true;
                                        } else {
                                            return false
                                        }" ><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div>
                                    </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php endforeach;?>                        
                        </tbody>
                    </table>
                </div>
            </div>     
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel" style="padding-left: 5%; color:#fff">Nuevo pago a la factura</h3>
        </div>
        <div class="modal-body">

            <?php echo form_open("ventas_separe/nuevoPago/".$data['id_factura'], array("id" =>"validate"));?>
                    <input type="hidden" name="id_factura" value="<?php echo $data['id_factura'];?>"/>
                    <div class="row-form">
                        <div><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                        <div>
                            <input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha_pago" id="fecha_pago"/>
                            <?php echo form_error('fecha_pago'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div><?php echo custom_lang('sima_amount', "Pago");?>:</div>
                        <div>
                            <input type="number" min="1" value="<?php echo set_value('cantidad'); ?>" name="cantidad" id='cantidad' placeholder=""/>
                            <?php echo form_error('cantidad'); ?>
                        </div>
                    </div>

                    <div class="row-form">
                        <div><?php echo custom_lang('sima_type', "M&eacute;todo");?>:</div>
                        <div>
                            <select name="tipo" id="tipo">
                            <?php
                                foreach($data['forma_pago'] as $f)
                                {
                                    ?>
                                    <option value="<?php echo $f->codigo ?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre?></option>
                                    <?php
                                }
                            ?>
                            </select>             
                                <?php echo form_error('tipo'); ?>
                        </div>
                    </div>
                    <div class="toolbar bottom tar">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>                      
                        <button class="btn btn-success guardarabono" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                    </div>
            </form>
        </div>
    </div>
    <!-- fin Modal -->

<script type="text/javascript">
    
    var total = <?php echo round($total); ?>;
    var pagos = <?php echo round($pagos); ?>;
    var saldo = parseInt(total - pagos);
    var idPlanSepare = <?php echo $data["id_factura"]?>;
    
    var estado = <?php echo $estado; ?>;
    //console.log(saldo);
    //console.log(total);
    //console.log(pagos);
        

    if( saldo <= 0){
        
        $("#btnAddPago").hide();
                
        if( estado >= 0){
            $("#btnFacturar").show();
        }
        if( estado == 2){
            $("#btnFacturar").hide();
            $(".eliminarabono").hide();

        } 
    }else{
        $("#btnAddPago").show();
    }


    Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
        c = isNaN(c = Math.abs(c)) ? 2 : c, 
        d = d == undefined ? "." : d, 
        t = t == undefined ? "," : t, 
        s = n < 0 ? "-" : "", 
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
        j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

    $(document).ready(function(){

   
        $( "#fecha_pago" ).datepicker({

             dateFormat: 'yy/mm/dd'

        });

        $('#validate').submit(function(e) {
            $(".guardarabono").prop('disabled',true);
            //console.log('cantidad: '+$('#cantidad').val()+' pagos: '+pagos+' = '+( parseInt($('#cantidad').val()) + parseInt(pagos)) ) ;
            if( ( parseInt($('#cantidad').val()) + parseInt(pagos) )  <= total )
                return true; 
            else{
                //alert('La cantidad supera el saldo , saldo pendiente $'+(saldo).formatMoney(2, '.', ','));
                swal({
                    position: 'center',
                    type: 'error',
                    title: "error",
                    html: 'La cantidad supera el saldo , saldo pendiente $'+(saldo).formatMoney(2, '.', ','),
                    showConfirmButton: false,
                    timer: 1500
                })
                $(".guardarabono").prop('disabled',false);
                return false;
            }
                
        });

    });
    
    
    $("#btnFacturar").click(function(){       
        $("#btnFacturar").prop('disabled',true);
        $("#btnFacturar").hide();
        facturar();
    });
    
    
    
    function facturar() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('ventas_separe/setFacturar').'/'.$idFactura ?>",
            dataType: 'text',
            success: function (response) { 
                if(response==1){
                    //console.log(response);
                    //alert("Plan separe - Facturado");
                    swal({
                        position: 'center',
                        type: 'success',
                        title: "Plan separe",
                        html: "Plan separe - Facturado",
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setTimeout(function(){ 
                        window.location = "<?php echo site_url('ventas_separe/facturas') ?>";
                    }, 1600);         
                    
                }else{                    
                    //alert("Plan separe - No Facturado, debe tener una caja abierta para realizar este proceso");
                    swal({
                        position: 'center',
                        type: 'error',
                        title: "error",
                        html: "Plan separe - No Facturado, debe tener una caja abierta para realizar este proceso",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }            
                
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }
    
    

    $(document).on('click','.eliminarP',function(event)
    {
        event.preventDefault();
        var $this = $(this),
            id = $this.attr("id");
        console.log(id);
        if(confirm("¿Esta seguro que desea eliminar este producto?"))
        {
            $.post
            (
                "<?php echo site_url("ventas_separe/eliminarProducto") ?>/"+id,
                {},function(data)
                {
                    if(data.resp == 1)
                    {
                        //alert("El producto fue eliminado correctamente");
                        swal({
                            position: 'center',
                            type: 'success',
                            title: "success",
                            html: "El producto fue eliminado correctamente",
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $this.parents("tr").remove();
                    }
                },'json'
            );
        }
    });
    
    
</script>
                     