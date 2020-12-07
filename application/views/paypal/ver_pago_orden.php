<?php

$total = 0;

    $timp  = 0;

    $subtotal = 0;

    $total_items = 0;

    $html_tbody='';

    foreach ($data['venta_credito']["detalle_venta"] as $p) {

        $pv = $p['precio_venta'];

        $desc = $p['descuento'];

        $pvd = $pv - $desc;

        $imp = $pvd * $p['impuesto'] / 100 * $p['unidades'];

        $total_column = $pvd * $p['unidades'];

        $total_items += $total_column;

        $valor_total = $pvd * $p['unidades'] + $imp ;

        $total += $total + $valor_total;

        $timp+=$imp;


        $html_tbody = $html_tbody." <tr>

           <td>".$p["codigo_producto"]."</td>

           <td >".$p["nombre_producto"] ."</td>

           <td style='text-align:center;'>".$p["unidades"] ."</td>

           <td style='text-align:right;'>".number_format($p["precio_venta"])."</td>

           <td style='text-align:center;'>". $p['descuento']."</td>

           <td style='text-align:right;'>".number_format($valor_total)."</td>
       
        </tr>";

    }

    $pagos=0;

    foreach ($data['data'] as $row){
        $pagos = $pagos+ $row->cantidad;
    }


?>


<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Orden de Compra", "Orden de Compra");?><small><?php echo $this->config->item('site_title');?></small></h1>

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

                    <div class="icon"><i class="ico-files"></i></div>

                    <h2>Orden de Compra</h2>

                </div>
            </div>

            <table  class='table'>
                <tr>
                    <td  style='width: 12%;'><strong><?php echo "Total" ?></strong></td>
                    <td  style='width: 18%;'><?php echo number_format($total); ?></td>
                    <td  style='width: 20%;'><strong><?php echo "Total pagos" ?></strong></td>
                    <td  style='width: 20%;'><?php echo number_format($pagos); ?></td>
                    <td  style='width: 12%;'><strong><?php echo "Saldo" ?></strong></td>
                    <td  style='width: 18%;'>
					<?php echo number_format($total-$pagos ); ?><br />
					<?php if(($total-$pagos)=='0'){ 
					echo "<strong>Orden de compra paga</strong>";
					 } ?>
					</td>
                </tr>
            </table>  

            <table  class='table'>
                <tr>                    
                    <td style="width: 30%;"><?php echo "<strong>Fecha: </strong>" . $data['venta_credito']['venta']['fecha'] ?></td> 
                    <td style="width: 40%;"><?php echo "<strong>Orden de Compra No: </strong>" . $data['venta_credito']['venta']['factura'] ?></td> 
                    <td style="width: 30%;"><?php echo "<strong>Almacen: </strong>" . $data['venta_credito']['venta']['nombre'] ?></td>                                     
                </tr>

            </table>  

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
                
            <div class="block" style="margin-bottom: 0px;">
                <div class="head blue">

                    <div class="icon"><i class="ico-files"></i></div>

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

    <div class="span6">


        <div class="block title">

            <div class="head">

                <h2><?php echo custom_lang('sima_payment_list', "Listado de pagos a la Orden de Compra");?> <?php echo $data['numero'];?></h2>                                          

            </div>

        </div>

        <div class="block">

            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-success">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>
            <a href="#myModal" role="button" class="btn" data-toggle="modal"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_payment', "Nuevo Pago");?></a>
            
            <div class="head blue">

                <div class="icon"><i class="ico-files"></i></div>

                <h2><?php echo custom_lang('sima_all_payment', "Todos los pagos");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">

                        <thead>

                            <tr>

                                

                                <th width="30%"><?php echo custom_lang('sima_date', "Fecha");?></th>

                                <th width="25%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>

                              <!--   <th width="15%"><?php //echo custom_lang('careoftheretention', "Importe de la retenci&oacute;n");?></th> -->

                                <th width="25%"><?php echo custom_lang('sima_type', "Tipo");?></th>

                               <!--  <th width="30%"><?php //echo custom_lang('sima_notes', "Notas");?></th> -->

                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($data['data'] as $row):?>

                            <tr>

                                <td><?php echo $row->fecha_pago; ?></td>

                                <td><?php echo number_format($row->cantidad);?></td>

                          <!--        <td><?php //echo $row->importe_retencion;?></td> -->

                                <td><?php echo $row->tipo;?></td>

                                <!-- <td><?php //echo $row->notas;?></td> -->

                                <td>

                                

                                   <!--  <a href="<?php //echo site_url("pagos/editar/".$row->id_pago);?>" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a> -->

                                    

                                    <a href="<?php echo site_url("pagos/eliminar_orden_compra/".$row->id_pago."?factura=".$data["id_factura"]);?>" class="button red" onclick="if(confirm('<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>')){return true;}else{return false}" ><div class="icon"><span class="ico-remove"></span></div></a>

                                                                         

                                </td>

                            </tr>

                            <?php endforeach;?>                         

                        </tbody>

                    </table>

                    <div class="pagination pagination-centered">

                        <ul>

                            <?php

                                $config['base_url'] = site_url('pagos/index');

                                $config['total_rows'] = $data["total"];

                                $config['per_page'] = 8;

                                $config['num_tag_open'] = '<li>';  

                                $config['num_tag_close'] = '</li>';

                                $config['cur_tag_open'] = '<li class="active"><a href="#">';

                                $config['cur_tag_close'] = '</a></li>';

                                $config['prev_tag_open'] = '<li>';

                                $config['prev_tag_close'] = '</li>';

                                $config['next_tag_open'] = '<li>';

                                $config['next_tag_close'] = '</li>';

                                $config['last_tag_open'] = '<li>';

                                    $config['last_link'] = '»';

                                $config['last_tag_close'] = '</li>';

                                $config['first_tag_open'] = '<li>';

                                    $config['first_link'] = '«';

                                $config['first_tag_close'] = '</li>';

                                

                                $this->pagination->initialize($config); 

                                echo $this->pagination->create_links();

                            ?>

                        </ul>

                    </div>

                </div>

            </div>

            

        </div>

    </div>


    <!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Nuevo pago a la orden de compra</h3>
      </div>
      <div class="modal-body">

            <?php echo form_open("pagos/nuevo_orden_compra/".$data['id_factura'], array("id" =>"validate"));?>

                    <input type="hidden" name="id_factura" value="<?php echo $data['id_factura'];?>"/>

                    <div class="row-form">

                        <div><?php echo custom_lang('sima_date', "Fecha");?>:</div>

                        <div><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha_pago" id="fecha_pago"/>

                                <?php echo form_error('fecha_pago'); ?>

                        </div>

                    </div>

                    <div class="row-form">

                        <div><?php echo custom_lang('sima_amount', "Pago");?>:</div>

                        <div><input type="text" value="<?php echo set_value('cantidad'); ?>" name="cantidad" id='cantidad' placeholder=""/>

                            <?php echo form_error('cantidad'); ?>

                        </div>

                    </div>

                    <div class="row-form">

                        <div><?php echo custom_lang('sima_type', "M&eacute;todo");?>:</div>

                        <div>

                                <?php echo form_dropdown('tipo', $data['tipo'], $this->form_validation->set_value('tipo'));?>

                                <?php echo form_error('tipo'); ?>

                        </div>

                    </div>

                    <div class="toolbar bottom tar">

                        <div class="btn-group">

                            <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

                            <button class="btn btn-warning" type="reset">Cancelar</button>

                        </div>

                    </div>

            </form>
      </div>
    </div>

<script type="text/javascript">

    var total = <?php echo $total; ?>;
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

    $(document).ready(function(){

   
        $( "#fecha_pago" ).datepicker({

             dateFormat: 'yy/mm/dd'

        });

        $('#validate').submit(function() {

            console.log('cantidad: '+$('#cantidad').val()+' pagos: '+pagos+' = '+( parseInt($('#cantidad').val()) + parseInt(pagos)) ) ;
            if( ( parseInt($('#cantidad').val()) + parseInt(pagos) )  <= total )
                return true; 
            else{
                alert('La cantidad supera el saldo , saldo pendiente $'+(saldo).formatMoney(2, '.', ','));
                return false;
            }
                
        });

    });

</script>
                     