<?php
$ci =&get_instance();
$ci->load->model("Opciones_model",'opciones');
?>
<div class="page-header">    
    <div class="icon">
        <img alt="Informes" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_informes']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Informes", "Informes");?></h1>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
              $empresa='';
                $is_admin = $this->session->userdata('is_admin');
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>    
            <div class="head blue">
                <h2><?php echo custom_lang('ventasxclientes', "Cuadre de caja");?></h2>
            </div>
                <form action="<?php echo site_url("informes/cuadre_caja_data");?>" method="POST" id="validate">
                <table>
                    <tr>
                        <td width="30%">Fecha : <input type="text" name="date" value="<?php echo $fecha;?>" class="datepicker" placeholder="click para desplegar calendario" readonly/>  </td>
                        <td width="30%">Tipo : 
                            <select name="tipo">
                                <option value="producto" <?php if($tipo == 'producto'){ echo "selected"; }?> >Producto</option>
                                <option value="factura"  <?php if($tipo == 'factura'){ echo "selected"; }?> >Factura</option>
                            </select>
                        </td>
					<?php if( $is_admin == 't' || $is_admin == 'a'){ //administrador ?>
						<td width="30%">Almacen : 	<?php 
                            echo "<select  name='almacen' >";    
                            echo "<option value='0'>Todos los Almacenes</option>";    
                            foreach($data1['almacen'] as $f){
                                if($f->id == $this->input->post('almacen')){
                                    $selected = " selected=selected ";
                                } else {
                                    $selected = "";
                                }        
                                echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                            }    
                            echo "</select>";
                            ?>   
                        </td>
					<?php } ?>		
                        <!--<td width="30%"><br/> <input type="submit" value="Consultar" class="btn btn-success"/></td>-->
                        <td width="30%">
                            <a data-tooltip="Consultar" onclick="$('#validate').submit()">                        
                                <img alt="Consultar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['buscar_verde']['original'] ?>"> 
                            </a> 
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php if(isset($data) && !empty($data['forma_pago']) && $tipo == 'factura'):?>
<div class="col-md-12">   
    <div class="col-md-6">  
        <a data-tooltip="Imprimir" target="_blank" href="<?php echo site_url("informes/imprimir_cuadre_caja/factura/".$fecha."/".$this->input->post('almacen'));?>">                        
            <img alt="Imprimir" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['imprimir_verde']['original'] ?>"> 
        </a>  
    </div>
    <div class="col-md-6 btnizquierda">
        <div class="col-md-2 col-md-offset-10">            
            <a data-tooltip="Descargar Excel"href="<?php echo site_url("informes/cuadre_caja_excel/factura/".$fecha."/".$this->input->post('almacen'));?>">                        
                <img alt="Descargar" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>"> 
            </a> 
        </div>
    </div>         
</div>         
<div class="row-fluid">
    <div class="span12">
        <div class="block">    
                <!--
                <table class="table" width="50px" cellspacing="0" cellpadding="0">
                    <tr>
                            <th>					
                                <a href="<?php echo site_url("informes/imprimir_cuadre_caja/factura/".$fecha."/".$this->input->post('almacen'));?>" class="button blue btn-print" title="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>	
                                <a href="<?php echo site_url("informes/cuadre_caja_excel/factura/".$fecha."/".$this->input->post('almacen'));?>" class="button green btn-list-alt" title="Exportar"><div class="icon"><span class="ico-list-alt"></span></div></a>  

                    </th>
                        </tr>
                </table>	-->		
		
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
                                <?php echo $value['cantidad'];?>
                                <?php $cantidad += $value['cantidad'];?>
                            </td>
                            <td>
                                <?php $formpago=str_replace("_"," ",$value['forma_pago']);
								echo ucfirst($formpago) ?>
                            </td>
                            <td>
                                <?php echo $value['vr_valor'];?>
                                <?php $total += $value['vr_valor'] ;?>
                            </td>
						</tr>	
                        <?php endforeach;?>
                        <?php 
                        
                            $total_ventas_cierre = 0; 

                            foreach ($data['forma_pago_ventas'] as $value){
                                $total_ventas_cierre += $value['vr_valor'] ;
                            }
                        ?>
                                                
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $cantidad;?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total ventas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php 
                                $ci =&get_instance();
                                $ci->load->model("Opciones_model",'opciones');
                                echo $ci->opciones->formatoMonedaMostrar($total);
                                ?>
                            </td>
                        </tr>

                        
                        
                        <!-- Total ventas devueltas -->
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo count($data['ventas_devueltas']['data']); ?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Ventas devueltas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $ci->opciones->formatoMonedaMostrar($data['ventas_devueltas']['total']); ?>
                            </td>
                        </tr>

                        <!-- Total ventas anuladas -->
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo count($data['ventas_anuladas']['data']); ?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total ventas anuladas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $ci->opciones->formatoMonedaMostrar($data['ventas_anuladas']['total']); ?>
                            </td>
                        </tr>


                        
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total gastos</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php 
                                    $gastos = 0;
                                    if(isset($data['gastos']) && $data['gastos'] != 0)
                                    {
                                        foreach ($data['gastos'] as $key => $value) {
                                            $gastos = $value->total;
                                        }
                                    }
                                    echo $ci->opciones->formatoMonedaMostrar($gastos);
                                 ?>
                            </td>
                        </tr>
						
						<?php $total_creditos=0;
						foreach ($data['forma_pago_credito'] as $value):?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de pagos a creditos</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                  <?php  echo number_format($total_creditos = $value['total_credito']) ;?>
                            </td>
                        </tr>
						 <?php endforeach;?>
						<?php $total_proveedor=0;
						foreach ($data['forma_pago_proveedor'] as $value):?>						 
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de pagos a proveedores</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                  <?php  echo $ci->opciones->formatoMonedaMostrar($total_proveedor = $value['total_proveedor']);?>
                            </td>
                        </tr>						
						 <?php endforeach;?>					
												
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total cierre</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $ci->opciones->formatoMonedaMostrar( $total_ventas_cierre - $gastos + $total_creditos - $total_proveedor); ?>
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
                        <?php $impuesto = 0; $total_imp=0; ?>			
                        <?php foreach ($data['impuesto_result'] as $value):?>
                        <tr>
                            <td>
                                <?php echo $value->imp;?>
                                <?php $total_imp += ($value->total_precio_venta - $value->total_descuento) + $value->impuestos;?>
                            </td>
                            <td>
                                  <?php echo $ci->opciones->formatoMonedaMostrar(($value->total_precio_venta - $value->total_descuento) + $value->impuestos);?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de Impuesto</strong>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $ci->opciones->formatoMonedaMostrar($total_imp); ?>
                            </td>
                        </tr>
                    </table>
                    <?php
                        $vr_bruto = 0; $vr_impuesto = 0; $vr_total = 0; $vr_descuento=0;
                    ?>
                    <div class="head blue">
                        <h2>Facturas</h2>
                    </div>
					<?php foreach ($data['factura_data'] as $value):
					$empresa = $value['empresa'];
					 endforeach;?>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>Factura</th>
							
                           <?php if($empresa == 'TCC S.A.'){ ?>
							<th>Vendedor</th>
                             <th>Almacen</th>
							<?php } ?> 
							
                            <th>VR Bruto</th>
                            <th>VR Iva</th>
	                        <th>Descuento</th>						
                            <th>VR Neto</th>
                        </tr>
                        
                        <?php foreach ($data['factura_data'] as $value):?>
                        <tr>				
                            <td>
                                <?php echo $value['factura'];?>
                            </td>
                           <?php if($value['empresa'] == 'TCC S.A.'){ ?>
							   <td><?php echo $value['vendedor']; ?></td>
                                <td><?php echo $value['id_almacen']; ?></td>
							<?php } ?>   							
                            <td>
                                <?php echo $value['vr_bruto']?>
                            </td>
	                        <td>
                                <?php echo $value['vr_impuesto'];?>
                            </td>	
                            <td>
                                <?php echo $value['descuento'];?>
                            </td>																
                            <td>
                                <?php echo $value['vr_neto'];?>
				<?php
                                    if($value['venta_plan_activo'] == '1'){
                                        $vr_descuento += $value['descuento'];								
                                        $vr_impuesto += $value['vr_impuesto'];
                                        $vr_bruto += $value['vr_valor'];
                                        $vr_total += $value['vr_valor'] + $value['vr_impuesto'] ;
                                    } 
                                ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr >
                            <td style="border-top: 1px solid #000000;" >
                                <strong>Totales</strong>
                            </td>
							
                           <?php if($empresa == 'TCC S.A.'){ ?>
                            <td style="border-top: 1px solid #000000;">
                            </td>	
                             <td style="border-top: 1px solid #000000;" >
                                
                            </td>
							<?php } ?> 
													
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $data['vrBrutoTotal']; ?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $data['vrImpuestoTotal'] ?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $data['vrDescuentoTotal'] ?>
                            </td>							
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $data['vrTotal'] ?>
                            </td>
                        </tr>
	                     <tr>
                            <td colspan="10"  style="border-top: 1px solid #000000;">
                                <strong>Total con Descuento:  <?php echo $data['vrTotal']; ?></strong>
                            </td>
                        </tr>						
                    </table>
        <?php elseif(isset($data) && !empty($data['forma_pago']) && $tipo == 'producto'):?>
 <table class="table" width="50px" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>					
				<a href="<?php echo site_url("informes/imprimir_cuadre_caja/producto/".$fecha."/".$this->input->post('almacen'));?>" class="button blue btn-print" title="Imprimir"><div class="icon"><span class="ico-print"></span></div></a>	
                <a href="<?php echo site_url("informes/cuadre_caja_excel/producto/".$fecha."/".$this->input->post('almacen'));?>" class="button green btn-list-alt" title="Exportar"><div class="icon"><span class="ico-list-alt"></span></div></a>  

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
                                <?php echo $value['cantidad'];?>
                                <?php $cantidad += $value['cantidad'];?>
                            </td>
                            <td>
                                <?php $formpago=str_replace("_"," ",$value['forma_pago']);
								echo ucfirst($formpago) ?>
                            </td>
                            <td>
                                <?php echo $value['vr_valor'];?>
                                <?php $total += $value['vr_valor'] ;?>
                            </td>
						</tr>	
                        <?php endforeach;?>





						 <?php $total_ventas_cierre = 0; 
						 foreach ($data['forma_pago_ventas'] as $value):?>
						 <?php $total_ventas_cierre += $value['vr_valor'] ;?>
						  <?php endforeach;?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $cantidad;?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total ventas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $ci->opciones->formatoMonedaMostrar($total + $data['ventas_anuladas']['total']); ?>
                            </td>
                        </tr>

                        <!-- Total ventas anuladas -->
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo count($data['ventas_anuladas']['data']); ?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total ventas anuladas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $ci->opciones->formatoMonedaMostrar($data['ventas_anuladas']['total']); ?>
                            </td>
                        </tr>
                        
                        <!-- Total ventas devueltas -->
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo count($data['ventas_devueltas']['data']); ?>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Ventas devueltas</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php echo $ci->opciones->formatoMonedaMostrar($data['ventas_devueltas']['total']); ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total gastos</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <?php 
                                    $gastos = 0;
                                    if(isset($data['gastos']) && $data['gastos'] != 0)
                                    {
                                        foreach ($data['gastos'] as $key => $value) {
                                            $gastos = $value->total;
                                        }
                                    }
                                    echo $ci->opciones->formatoMonedaMostrar($gastos);

                                 ?>
                            </td>
                        </tr>
						
						<?php $total_creditos=0;
						foreach ($data['forma_pago_credito'] as $value):?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de pagos a creditos</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                  <?php  echo $ci->opciones->formatoMonedaMostrar($total_creditos = $value['total_credito']) ;?>
                            </td>
                        </tr>
						 <?php endforeach;?>


                        

						<?php $total_proveedor=0;
						foreach ($data['forma_pago_proveedor'] as $value):?>						 
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de pagos a proveedores</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                  <?php  echo $ci->opciones->formatoMonedaMostrar($total_proveedor = $value['total_proveedor']);?>
                            </td>
                        </tr>						
                         <?php endforeach;?>
                         
                          <?php $total_abonos_plan_separe=0;
						foreach ($data['abonos_plan_separe_array'] as $value):?>						 
                            <tr>
                                <td style="border-top: 1px solid #000000;">
                                </td>
                                <td style="border-top: 1px solid #000000;">
                                    <strong>Total de abonos plan separe</strong> 
                                </td>
                                <td style="border-top: 1px solid #000000;">
                                    <?php  echo $ci->opciones->formatoMonedaMostrar($total_abonos_plan_separe = $value['valor']);?>
                                </td>
                            </tr>						
						 <?php endforeach;?>
												
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total cierre</strong> 
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                <!-- Se cambio para  hacer la operacion directamente con las formas de pago -->
                                <!--  <?php echo $ci->opciones->formatoMonedaMostrar($total_ventas_cierre - $gastos + $total_creditos - $total_proveedor); ?> -->
                                 <?php echo $ci->opciones->formatoMonedaMostrar($total - $gastos + $total_creditos - $total_proveedor + $total_abonos_plan_separe); ?>
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
                        <?php $impuesto = 0; $total_imp=0; ?>			
                        <?php foreach ($data['impuesto_result'] as $value):?>
                        <tr>
                            <td>
                                <?php echo $value->imp; ?>
                                <?php $total_imp += ($value->total_precio_venta - $value->total_descuento) + $value->impuestos;?>
                            </td>
                            <td>
                                  <?php echo $ci->opciones->formatoMonedaMostrar(($value->total_precio_venta - $value->total_descuento) + $value->impuestos);?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td style="border-top: 1px solid #000000;">
                                <strong>Total de Impuesto</strong>
                            </td>
                            <td style="border-top: 1px solid #000000;">
                                 <?php echo $ci->opciones->formatoMonedaMostrar($total_imp); ?>
                            </td>
                        </tr>
                    </table>
                    <div class="head blue">

                        <h2>Productos</h2>
                    </div>
					
					<?php foreach ($data['factura_data'] as $value):
					$empresa = $value['empresa'];
					 endforeach;?>
                    <table class="table" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
						<th>factura</th>
						
                           <?php if($empresa == 'TCC S.A.'){ ?>
							<th>Vendedor</th>
                            <th>Almacen</th>
							<?php } ?> 
							
						    <th>Descripci&oacute;n</th>
                            <th>Cantidad</th>
                            <th>V.Unidad</th>
                            <th>Valor</th>
					        <th>Descuento</th>		
                            <th>V.Impuesto</th>
                           
                            <th>Total</th>

                        </tr>
                        <?php $vr_unidades = 0; $vr_precio_unidad=0; 
						$vr_valor=0; $vr_descuento=0; $vr_valor_impuesto=0; $vr_total=0; ?>


                        <?php foreach ($data['factura_data'] as $value):?>
                            <tr>
							   <td><?php echo $value['factura']; ?></td>
							   <?php if($empresa == 'TCC S.A.'){ ?>
							   <td><?php echo $value['vendedor']; ?></td>
                                <td><?php echo $value['almacen_id']; ?></td>
							   <?php } ?> 
                                <td><?php echo $value['nombre_producto']; ?></td>
                                <td><?php echo $value['unidades']; ?></td>
                                <td><?php echo $value['precio_unidad']; ?></td>
                                <td><?php echo $value['valor']; ?></td>
                                <td><?php echo $value['descuento']; ?></td>
                                <td><?php echo $value['valor_impuesto']; ?></td>
                                <td><?php echo $value['valorTotal']; 
                                    if($value['venta_plan_activo'] == '1'){
                                    $vr_unidades += $value['unidades'];
                                    $vr_precio_unidad += $value['precio_unidad'];
                                    $vr_valor += $value['valor'];
                                    $vr_descuento+=$value['descuento'];
                                    $vr_valor_impuesto+=$value['valor_impuesto'];
                                    $vr_total+=$value['valor'] + $value['valor_impuesto'];
                                    }
                                    ?>
                                </td>
                            </tr>
							
							<?php //echo $vr_tot += $data->total_final; ?>
                        <?php endforeach;?>
                        <tr>
                            <td colspan="2"  style="border-top: 1px solid #000000;">
                                <strong>Totales</strong>
                            </td>
                            <?php if($empresa == 'TCC S.A.'){ ?>
                            <td style="border-top: 1px solid #000000;" >
                                
                            </td>
                            <td style="border-top: 1px solid #000000;" >
                                
                            </td>
							<?php } ?> 
                            <td style="border-top: 1px solid #000000;" >
                                <?php echo $data['vrUnidades']; ?>
                            </td>
                            <td style="border-top: 1px solid #000000;" >
                                <?php echo $data['vrPrecioUnidad']; ?>
                            </td>														
                            <td style="border-top: 1px solid #000000;" >
                                <?php echo $data['vrValor']; ?>
                            </td>
                            <td style="border-top: 1px solid #000000;" >
                                <?php echo $data['vrDescuento']; ?>
                            </td>
                            <td  style="border-top: 1px solid #000000;">
                                <?php echo $data['vrValorImpuesto']; ?>
                            </td>
                            <td style="border-top: 1px solid #000000;" >
                                <?php echo $data['vrTotal']; ?>
                            </td>							
                        </tr>
	                     <tr>
                            <td colspan="10"  style="border-top: 1px solid #000000;">
                                <strong>Total con Descuento:  <?php echo $data['vrTotal']; ?></strong>
                            </td>
                        </tr>						
                    </table>
        <pre>
            <?php //print_r($data);?>
        </pre>       
        </div>
    </div>  
</div>
</div>
 <?php endif;?>
<script type="text/javascript">    
    mixpanel.track("Informe_cuadre_caja");  
</script>