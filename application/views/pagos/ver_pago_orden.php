<script src="<?php echo base_url("index.php/OpcionesController/index")?>"></script>
<style> 
    .modal-header{
        background-color: #5cb85c !important;
    }
    .ui-datepicker table, .ui-datepicker {
        background-color: #fff !important;
    }
</style>
<?php 
$ci =&get_instance();
$ci->load->model("opciones_model");

    $total = 0;

    $timp  = 0;

    $subtotal = 0;

    $total_items = 0;

    $valor_total_p = 0;

    $html_tbody='';

    foreach ($data['venta_credito']["detalle_venta"] as $p) {

        $pv = $p['precio_venta'];

        $desc = $p['descuento'];

        $pvd = $pv - ($desc*$pv/100);

        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

        $total_column = $pvd * $p['unidades'];

       $total_items += $total_column + $imp;

        $valor_total = $pvd * $p['unidades'] + $imp ;

        $valor_total_p += $pvd * $p['unidades'] + $imp ;

        $total += $total + $valor_total;

        $timp+=$imp;


        $html_tbody = $html_tbody." <tr>

           <td>".$p["codigo_producto"]."</td>

           <td >".$p["nombre_producto"] ."</td>

           <td style='text-align:center;'>".$p["unidades"] ."</td>

           <td style='text-align:right;'>".$p["precio_venta"]."</td>

           <td style='text-align:center;'>". $p['descuento']."</td>

           <td style='text-align:right;'>".$valor_total."</td>
       
        </tr>";

    }   
    $total=$total_items;
    $pagos=0;
    
    foreach ($data['data'] as $row){
        $pagos = $pagos+ $row->cantidad;
    }
    
    $valor_total_p=round( $valor_total_p, 2, PHP_ROUND_HALF_UP);
    $venta_total=(float)($data["venta_credito"]["venta"]["total_venta"]);
    $devoluciones=($venta_total-$valor_total_p);   
    /*echo"venta_total=".$venta_total;
    echo"valor_total_p=".$valor_total_p;
   echo"devoluciones=".$devoluciones;*/

?>

<div class="page-header">    
    <div class="icon">
        <img alt="Orden de Compra" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_ordenes_compras']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Ventas", "Órdenes de Compras");?></h1>
</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block title">

            <div class="head">

                <h2><?php echo custom_lang('sima_payment_list', "Detalle Orden de Compra");?> <?php echo $data['numero'];?></h2>                                          

            </div>

        </div>

        <div id="ticket_wrapper">

            <div class="block" style="margin-bottom: 0px;">
                <div class="head blue">
                    <h2>Orden de Compra</h2>
                </div>
            <!--<div style="color:#d32f2f; font-size:12px;">El total a pagar no incluye devoluciones</div>-->
            <table  class='table'>
                <tr>
                    <td  style='width: 12%;'><strong><?php echo "Total" ?></strong></td>
                    <td  style='width: 18%;'><?php echo $venta_total-$devoluciones; ?></td>
                    <td  style='width: 20%;'><strong><?php echo "Total pagos" ?></strong></td>
                    <td  style='width: 20%;'><?php echo $pagos; ?></td>
                    <td  style='width: 12%;'><strong><?php echo "Saldo" ?></strong></td>
                    <td  style='width: 18%;'>
					<?php echo $venta_total-$pagos-$devoluciones; ?><br />
					<?php if(($venta_total-$pagos-$devoluciones)=='0'){ 
					echo "<strong>Orden de compra paga</strong>";
					 } ?>
					</td>
                </tr>
            </table>  

            <table  class='table'>
                <tr>                    
                    <td style="width: 30%;"><?php echo "<strong>Fecha: </strong>" . $data['venta_credito']['venta']['fecha'] ?></td> 
                    <td style="width: 40%;"><?php echo "<strong>Orden de Compra No: </strong>" . $data['venta_credito']['venta']['id_venta'] ?></td> 
                    <td style="width: 30%;"><?php echo "<strong>Almacen: </strong>" . $data['venta_credito']['venta']['nombre'] ?></td>                                     
                </tr>

            </table>  
                    <?php //print_r($data);?>
            <table class='table'>

                <tr>

                    <td style="width: 30%;">
                        <strong>Proveedor:</strong> 
                        <?php  
                         echo ($data['venta_credito']['venta']['nombre_comercial'] == "" ? "Mostrador" : $data['venta_credito']['venta']["nombre_comercial"] ) 
                        ?>
                    </td> 
                 
                    <td style="width: 40%;"><strong>CC: </strong>  <?php echo $data['venta_credito']['venta']["nif_cif"] == "" || $data['venta_credito']['venta']["nif_cif"] == 0? "indefinido" :$data['venta_credito']['venta']["nif_cif"] ?> </td>
                    <td style="width: 30%;"><strong>Tel&eacute;fono: </strong><?php echo $data['venta_credito']['venta']['proveedores_telefono'] == "" ? "Indefinido" : $data['venta_credito']['venta']['proveedores_telefono'] ?></td>                                     

                </tr>
            </table>
            <table class='table'>    
                <tr>
                    <td colspan="3"><b>Nota: </b><?php echo $data['venta_credito']['venta']['nota'] ?></td>
                </tr>
            </table>    
                  </div>
            <div class="block" style="margin-bottom: 0px;">
                <div class="head blue">
                    <h2>Items</h2>
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
            </div>
    </div>
        </div>
    </div>  

    <div class="span6">
        <div class="block title">
            <div class="head">
                <h2><?php echo custom_lang('sima_payment_list', "Listado de pagos a la Orden de Compra");?> <?php echo $data['numero'];?></h2> 
            </div>

        </div>

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
            if($data["estado_caja"] == "cerrada"){ ?>
                <div style="color:#d32f2f; font-size:12px;">Para realizar un pago a una orden de compra debe tener una caja abierta, haga clic <a target="_blank" href="<?php echo site_url('caja/apertura'); ?>">aqui</a> para aperturar caja </div>
            <?php }else { 
                
                if(($venta_total-$pagos-$devoluciones)>'0'){ ?>
                    <!--<a href="#myModal" role="button" class="btn btn-success" data-toggle="modal"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_payment', "Nuevo Pago");?></a>-->
                    <a href="#myModal" data-toggle="modal" data-tooltip="Nuevo Pago">                        
                        <img alt="Nuevo Pago" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>"> 
                    </a>   
            <?php } } ?>
            
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_payment', "Todos los pagos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="30%"><?php echo custom_lang('sima_date', "Fecha");?></th>
                                <th width="25%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>
                                <th width="25%"><?php echo custom_lang('sima_type', "Tipo");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['data'] as $row):?>
                            <tr>
                                <td><?php echo $row->fecha_pago; ?></td>
                                <td><?php echo $row->cantidad;?></td>
                                <td><?php echo $row->tipo;?></td>
                                <td>
                                    <a href="<?php echo site_url("pagos/eliminar_orden_compra/".$row->id_pago."?factura=".$data["id_factura"]);?>" class="button red acciones" onclick="if(confirm('<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>')){return true;}else{return false}" ><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>
                                </td>
                            </tr>
                            <?php endforeach;?>                         
                        </tbody>
                    </table>
                </div>
            </div>
    </div>


    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" style="font-size: 16px; color: #fff; padding-left: 3%;">Nuevo pago a la orden de compra</h3>
      </div>
      <div class="modal-body">

            <?php echo form_open("pagos/nuevo_orden_compra/".$data['id_factura'], array("id" =>"validate"));?>

                    <input type="hidden" name="id_factura" value="<?php echo $data['id_factura'];?>"/>

                    <div class="row-form">

                        <div><?php echo custom_lang('sima_date', "Fecha");?>:</div>

                        <div><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha_pago" readonly id="fecha_pago"/>

                                <?php echo form_error('fecha_pago'); ?>

                        </div>

                    </div>

                    <div class="row-form">

                        <div><?php echo custom_lang('sima_amount', "Pago");?>:</div>

                        <div><input type="text"  value="<?php echo set_value('cantidad'); ?>" class="dataMoneda" name="cantidad" id='cantidad' placeholder=""/>

                            <?php echo form_error('cantidad'); ?>

                        </div>

                    </div>

                    <input type="hidden"  value="<?php echo $data['venta_credito']['venta']["id"]; ?>" name="id_proveedor" id='id_proveedor' placeholder=""/>
                    <input type="hidden"  value="Nuevo pago a la orden de compra" name="descripcion" id='descripcion' placeholder=""/>
                    <input type="hidden"  value="" name="valor" id='valor' placeholder=""/>
                    <input type="hidden"  value="" name="fecha" id='fecha' placeholder=""/>
                    <input type="hidden"  value="" name="notas" id='notas' placeholder=""/>
                    <input type="hidden"  value="<?php echo $data['venta_credito']['venta']['id'] ?>" name="id_almacen" id='id_almacen' placeholder=""/>
                    <input type="hidden"  value="" name="forma_pago" id='forma_pago' placeholder=""/>


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

                    <div class="row-form"> 

                                        <div class="span3"><?php echo custom_lang('sima_tax', "Cuentas de Dinero");?>:</div>
                                        <div class="span9">
                                            <?php foreach($data['cuentas_dinero'] as $f): ?>
                                                <label class="radio-inline pl-0">
                                                    <input type="radio" class="mt-0 cuentas_dinero" name="cuentas_dinero" id="cuentas_dinero<?= $f->id; ?>" value="<?= $f->id; ?>"   <?= ($f->id == 1)? 'checked' : ''; ?>> 
                                                    <?= $f->nombre;?>
                                                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="<?= ($f->id == 1)? 'El gasto quedara asociado directamente al banco y se generará como un movimiento bancario,( No afectará la caja)' : 'El gasto se vera reflejado directamente en la caja'; ?>" data-trigger="hover"></span>
                    
                                                </label>
                                                
                                            <?php endforeach; ?>

                                            <input type="hidden" name="subcategoria_gasto_asociada" id="subcategoria_gasto_asociada">                          
                                        </div>
                                    </div>

                                    <div class="row-form d-none" id="asociar_banco">
                                        <div class="span12 content-asociar-banco outline-lightgray p-10 mb-10">
                                            <div class="form-group col-md-12">
                                                <label for="categoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                                                <div class="col-sm-8">
                                                    <select id="categoria_gasto" name='categoria_gasto' class="col-sm-8 form-control" name=''>
                                                        <option value="">Seleccione categoria</option>
                                                        <?php foreach($data["categorias_gastos"] as $categoria): ?>
                                                        <option value="<?= $categoria->id;?>"><?= ucfirst($categoria->nombre);?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="subcategoria_gasto" class="col-sm-4 control-label text-left">Sub-categoria del gasto:</label>
                                                <div class="col-sm-8">
                                                    <select id="subcategoria_gasto" name='subcategoria_gasto' class="col-sm-8 form-control" >
                                                        <option value="">Seleccione sub-categoria</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="banco" class="col-sm-4 control-label text-left">Banco:</label>
                                                <div class="col-sm-8">
                                                    <select id="banco" name='banco_asociado' class="col-sm-8 form-control">
                                                        <option value="">Seleccione banco</option>
                                                        <?php foreach($data["bancos"] as $banco): ?>
                                                        <option value="<?= $banco->id;?>"><?= ucfirst($banco->nombre_cuenta);?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                    <div class="toolbar bottom tar">                        
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success" id="guardar" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>                           
                    </div>
            </form>
      </div>
    </div>

<script type="text/javascript">

    var url_cargar_subcategorias = "<?= site_url('proformas/cargar_subcategorias');?>";

    var tipo_cuenta = 0;

    var total = <?php echo $venta_total-$devoluciones; ?>;
    var pagos = <?php echo $pagos; ?>;
    var saldo = total - pagos;

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

    $("#categoria_gasto").change(function(){
        $.post(url_cargar_subcategorias,{
            id_categoria: $("#categoria_gasto").val()
        },function(data){
            var subcategorias = JSON.parse(data);
            var options = '<option value="">Seleccione sub-categoria</option>';

            $.each(subcategorias,function(index,element){
                console.log(element);
                options += "<option value='"+element.id+"'>"+element.nombre+"</option>";
            })
            
            $("#subcategoria_gasto").html(options);
        })
    });

    $("#cuentas_dinero1").click(function(){
        $("#asociar_banco").delay(20).fadeOut();
        tipo_cuenta = 2;

        $("#subcategoria_gasto").change(function(){
            $("#subcategoria_gasto_asociada").val($("#subcategoria_gasto").val());
        })

        $("#banco").change(function(){
            $("#banco_asociado").val($("#banco").val());
        })  
    });

    $("#cuentas_dinero2").click(function(){
        tipo_cuenta = 1;
        $("#asociar_banco").delay(100).fadeIn();   
    })
    $(document).ready(function(){

        var checkInput = $(".cuentas_dinero:checked").attr('value');

        $('.dataMoneda').keyup(() => {
            $('#valor').val($('.dataMoneda').val());
        });
        $('#fecha').val("<?php echo date("Y-m-d", (strtotime ("-5 Hours"))); ?>");

        var total_venta = <?= $venta_total-$devoluciones;?>;

        $( "#fecha_pago" ).datepicker({
            dateFormat: 'yy/mm/dd'
        });

        $('#validate').submit(function() {
            let mensaje = '';
            let categoria_gasto = $('#categoria_gasto').val();
            let sub_categoria_gasto = $('#subcategoria_gasto').val();
            let banco = $('#banco').val();
            let success_status = true;

            $("#guardar").prop('disabled',true);

            if($(".cuentas_dinero:checked").attr('value') == '2'){
                if(!categoria_gasto){
                    mensaje += `<li>Debe seleccionar una categoría del gasto</li>`;
                    success_status = false;
                }
                if(!sub_categoria_gasto){
                    mensaje += `<li>Debe seleccionar una subcategoría del gasto</li>`;
                    success_status = false;
                }
                if(!banco){
                    mensaje += `<li>Debe seleccionar un banco</li>`;
                    success_status = false;
                }
            }

            if(($('#cantidad').val())>0){
                if((parseFloat($('#cantidad').val()) + (pagos))  <= (total_venta)){
                    if($(".cuentas_dinero:checked").attr('value') == '2'){
                        if(!categoria_gasto || !sub_categoria_gasto || !banco){
                            console.log(`mensaje ${mensaje}`);
                            swal({
                                position: 'center',
                                type: 'error',
                                title: "Error",
                                html: `${mensaje}`,
                                showConfirmButton: true
                            });
                            $("#guardar").prop('disabled',false);
                            success_status = false;
                        }
                    }
                }else{
                    mensaje += `La cantidad supera el saldo , saldo pendiente $ ${(saldo).formatMoney(2, '.', ',')}`;
                    swal({
                        position: 'center',
                        type: 'error',
                        title: "Error",
                        html: `${mensaje}`,
                        showConfirmButton: true
                    })
                    $("#guardar").prop('disabled',false);
                    success_status = false;
                }
            }else{
                mensaje += '<li>El pago debe ser mayor a 0</li>';
                swal({
                    position: 'center',
                    type: 'error',
                    title: "Error",
                    html: `${mensaje}`,
                    showConfirmButton: true
                })
                $("#guardar").prop('disabled',false);
                success_status = false;
            }

            if(!success_status){
                return false;
            }
        });



        if(checkInput === '1'){
            $("#asociar_banco").delay(20).fadeOut();
        }
    });

</script>