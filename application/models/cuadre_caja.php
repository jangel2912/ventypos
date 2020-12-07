<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Informes", "Informes");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('cuadrecaja', "Cuadre de caja");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('ventasxclientes', "Cuadre de caja");?></h2>
            </div>
                <form action="<?php echo site_url("informes/cuadre_caja_data");?>" method="POST">
                <table>
                    <tr>
                        <td width="30%">Fecha : <input type="text" name="date" value="<?php echo date('Y-m-d');?>" class="datepicker"/>  </td>
                        <td width="30%">Tipo : 
                            <select name="tipo">
                                <option value="producto">Producto</option>
                                <option value="factura">Factura</option>
                            </select>
                        </td>
                        <td width="30%"><br/> <input type="submit" value="Enviar" class="btn btn-primary"/></td>
                    </tr>
                </table>
            </form>
            </div>
        </div>
											
    <div class="span12 block">
        <?php if(isset($data) && !empty($data['forma_pago']) && $tipo == 'factura'):?>
 <table class="table" width="50px" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>					
				<a href="<?php echo site_url("informes/imprimir_cuadre_caja/factura/".$fecha);?>" class="button blue btn-print" title="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>	
</th>
                        </tr>
</table>			
		
                    <div class="head blue">
                        <h2>Cuadre de Caja</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>#</th>
                            <th>Forma de pago</th>
                            <th>Valor</th>
                        </tr>
                        <?php $cantidad = 0; $total = 0;?>
                        <?php foreach ($data['forma_pago'] as $value):?>
                        <tr>
                            <td>
                                <?php echo $value->cantidad;?>
                                <?php $cantidad += $value->cantidad;?>
                            </td>
                            <td>
                                <?php echo $value->forma_pago?>
                            </td>
                            <td>
                                <?php echo number_format($value->total);?>
                                <?php $total += $value->total;?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $cantidad;?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de Ingreso</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($total); ?>
                            </td>
                        </tr>
                    </table>
                    <div class="head blue">
                        <h2>Impuestos por ventas</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Nombre</th>
                            <th>Valor</th>
                        </tr>
                        <?php $impuesto = 0; ?>
                        <?php foreach ($data['impuesto_result'] as $value):?>
                        <tr>
                            <td>
                                <?php echo $value->nombre_impuesto;?>
                                <?php //$cantidad += $value->cantidad;?>
                            </td>
                            <td>
                                  <?php echo number_format(($value->precio) * ($value->porciento / 100));?>
                                <?php $impuesto += ($value->precio) * ($value->porciento / 100);?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de Impuesto</strong>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($impuesto); ?>
                            </td>
                        </tr>
                    </table>
                    <?php
                        $vr_bruto = 0; $vr_impuesto = 0; $vr_total = 0;
                    ?>
                    <div class="head blue">
                        <h2>Facturas</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Factura</th>
                            <th>VR Bruto</th>
                            <th>VR Iva</th>
                            <th>VR Neto</th>
                        </tr>
                        
                        <?php foreach ($data['factura_data'] as $value):?>
                        <tr>
                            <td>
                                <?php echo $value['factura'];?>
                            </td>
                            <td>
                                <?php echo number_format($value['vr_valor'])?>
                                <?php $vr_bruto += $value['vr_valor'];?>
                            </td>
                            <td>
                                <?php echo number_format($value['vr_impuesto']);?>
                                <?php $vr_impuesto += $value['vr_impuesto'];?>
                            </td>
                            <td>
                                <?php echo number_format($value['total']);?>
                                <?php $vr_total += $value['total'];?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr >
                            <td style="border-top: 1px solid #000000;" >
                                <strong>Totales</strong>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($vr_bruto); ?>
                            </td>
                             <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($vr_impuesto); ?>
                            </td>
                              <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($vr_total); ?>
                            </td>
                        </tr>
                    </table>
        <?php elseif(isset($data) && !empty($data['forma_pago']) && $tipo == 'producto'):?>
 <table class="table" width="50px" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>					
				<a href="<?php echo site_url("informes/imprimir_cuadre_caja/producto/".$fecha);?>" class="button blue btn-print" title="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>	
</th>
                        </tr>
</table>			
        <div class="head blue">
                        <h2>Cuadre de Caja</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>#</th>
                            <th>Forma de pago</th>
                            <th>Valor</th>
                        </tr>
                        <?php $cantidad = 0; $total = 0;?>
                        <?php foreach ($data['forma_pago'] as $value):?>
                        <tr>
                            <td>
                                <?php echo number_format($value->cantidad);?>
                                <?php $cantidad += $value->cantidad;?>
                            </td>
                            <td>
                                <?php 
                                echo $value->forma_pago
                                ?>
                            </td>
                            <td>
                               
                                <?php echo number_format($value->total);?>
                                <?php $total += $value->total;?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $cantidad;?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total ventas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($total); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $cantidad;?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total gastos</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php 
                                    $gastos = 0;
                                    foreach ($data['gastos'] as $key => $value) {
                                        $gastos = $value->total;
                                       
                                    }
                                     echo number_format($gastos);

                                 ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $cantidad;?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total cierre</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo number_format($total-$gastos); ?>
                            </td>
                        </tr>
                    </table>
                    <div class="head blue">
                        <h2>Impuestos por ventas</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Nombre</th>
                            <th>Valor</th>
                        </tr>
                        <?php $impuesto = 0; ?>
                        <?php foreach ($data['impuesto_result'] as $value):?>
                        <tr>
                            <td>
                                <?php echo $value->nombre_impuesto;?>
                                <?php //$cantidad += $value->cantidad;?>
                            </td>
                            <td>
                                <?php echo number_format(($value->precio) * ($value->porciento / 100));?>
                                <?php $impuesto += ($value->precio) * ($value->porciento / 100);?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td>
                                <strong>Total de Impuesto</strong>
                            </td>
                            <td>
                                 <?php echo number_format($impuesto); ?>
                            </td>
                        </tr>
                    </table>
                    <div class="head blue">
                        <h2>Productos</h2>
                    </div>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Descripci&oacute;n</th>
                            <th>Cantidad</th>
                            <th>V.Unidad</th>
                            <th>Valor</th>
                           <!--  <th>Impuesto</th> -->
                            <th>V.Impuesto</th>
                           
                            <th>Total</th>
                            <th>Descuento</th>
                        </tr>
                        <?php $vr_valor = 0; $vr_descuento=0;?>


                        <?php foreach ($data['factura_data'] as $data):?>
                            <tr>
                                <td><?php echo $data->nombre_producto; ?></td>
                                <td><?php echo $data->unidades; ?></td>
                                <td><?php echo number_format($data->precio_unidad); ?></td>
                                <td><?php echo number_format($data->valor); ?></td>
                                <!-- <td><?php //echo $data->impuesto; ?></td> -->
                                <td><?php echo number_format($data->valor_impuesto); ?></td>
                                <td><?php echo number_format($data->total); $vr_valor += $data->total;?></td>
                                <td><?php echo number_format($data->descuento); $vr_descuento+=$data->descuento;?></td>
                            </tr>
                        <?php endforeach;?>
                        <tr>
                            <td colspan="2" class="pull-right"  style="border-top: 1px solid #000000;">
                                <strong>Descuento</strong>
                            </td>
                            <td style="border-top: 1px solid #000000;" >
                                <?php echo number_format($vr_descuento); ?>
                            </td>
                            <td  class="pull-right"  style="border-top: 1px solid #000000;">
                                <strong>Total</strong>
                            </td>
                            <td  style="border-top: 1px solid #000000;">
                                <?php echo number_format($vr_valor - $vr_descuento); ?>
                            </td>
                        </tr>
                    </table>
        <pre>
            <?php //print_r($data);?>
        </pre>
        <?php endif;?>
    </div>
    </div>
<script type="text/javascript">
    
</script>