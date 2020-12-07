<?php 
	$id_user=$this->session->userdata['user_id'];
?>
<style>
	label.negrita{
		font-weight: 600;
	}
	
	.example-wrap{
        font-weight: 300;
        line-height: 16px;
        float: left;
    }
    
    .example-wrap label{
        float: left;
        line-height: 15px;
        cursor: pointer;
    }    
    
    .example-wrap > div{
        height: 25px;
    }

	.form-horizontal .radio {
		padding-top: 0px;
	}

	.example-wrap {
		margin-bottom: 7%;
	}
	input[type="text"]{
		margin-left: 0px;
	}
</style>

<div class="page-header">    
    <div class="icon">
        <img alt="info_fiscal" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_contactos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("info_fiscal", "Configuración Empresa");?></h1>
</div>
<div class="row-fluid">
<?php $message = $this->session->flashdata('message');
     if(!empty($message)){?>
        <div class="alert alert-success">
            <?php echo $message;?> 
        </div>
<?php } ?>
</div>
<div>
	<?php echo form_open("administracion_vendty/empresas/configuracion", array("id" =>"validate",'class'=>"form-horizontal"));?>
		<input type="hidden" name="bd" value="<?= $data['data']['bd'] ?>" />
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Offline:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("offline", 
							array('false' => 'no','active' => 'active','backup' => 'backup'),
							set_value('offline', $data['data']['offline'])); 
						?>	
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Precio x Almacén:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("precio_almacen", 
							array('0' => 'no','1' => 'si'),
							set_value('precio_almacen', $data['data']['precio_almacen'])); 
                        ?>
					</div>				
				</div>
			</div>	
		</div>	
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Resolución por Almacén:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("resolucion_factura_estado", 
							array('no' => 'no','si' => 'si'),
							set_value('resolucion_factura_estado', $data['data']['resolucion_factura_estado'])); 
                        ?>
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Plantilla (Órden Compra):</label>
					<div class="col-sm-7">
						<?php 
							echo form_dropdown("plantilla_orden_compra", array('Estandar' => 'Estandar','Detallado' => 'Detallado'), set_value('plantilla_orden_compra', $data['data']['plantilla_orden_compra']), " id='plantilla_orden_compra'");  
						?>
					</div>				
				</div>
			</div>	
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Plantilla General:</label>
					<div class="col-sm-7">
						<?php 
							echo form_dropdown("plantilla_general", array('media_carta' => 'Media Carta','tirilla' => 'Tirilla'), set_value('plantilla_general', $data['data']['plantilla_general']), " id='plantilla_general'");  
						?>
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Consecutivo General</label>
					<div class="col-sm-7">
					<?php                       
                      echo form_dropdown("numero", 
                      array('no' => 'no','si' => 'si'),
                       set_value('numero', $data['data']['numero']), " id='numero'");  
                    ?>
					</div>				
				</div>
			</div>	
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Redondear Precios:</label>
					<div class="col-sm-7">
						<?php 

                         echo form_dropdown("redondear_precios", 
                         array('0' => 'no','1' => 'si'),
                          set_value('redondear_precios', $data['data']['redondear_precios']), " id='redondear_precios'");  

                       ?>
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Precio (Órden Compra):</label>
					<div class="col-sm-7">
						<?php 
                          echo form_dropdown("orden_compra_precio", 
                            array('0' => 'Precio Compra','1' => 'Precio Venta'),
                            set_value('orden_compra_precio', $data['data']['orden_compra_precio'] ));  
                        ?>						
					</div>				
				</div>
			</div>	
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Simbolo:</label>
					<div class="col-sm-7">
						<input type="text" name="simbolo" placeholder="$" value="<?php echo set_value('simbolo', $data['data']['simbolo']) ?>">
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Módulo Plan Separe:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("plan_separe_m", 
							array('0' => 'no','1' => 'si'),
							set_value('plan_separe_m', $data['data']['plan_separe_m'])); 
                        ?>
					</div>				
				</div>
			</div>	
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Módulo Atributos:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("atributos_m", 
							array('0' => 'no','1' => 'si'),
							set_value('atributos_m', $data['data']['atributos_m'])); 
                        ?>
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Módulo Puntos:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("puntos_m", 
							array('0' => 'no','1' => 'si'),
							set_value('puntos_m', $data['data']['puntos_m'])); 
                        ?>
					</div>				
				</div>
			</div>	
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Cantidad Almacenes:</label>
					<div class="col-sm-7">
						<input type="number" name="cantidad_almacenes" value="<?php echo set_value('cantidad_almacenes', $data['data']['cantidad_almacenes']) ?>">
					</div>
				</div>
			</div>	
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Costo Promedio:</label>
					<div class="col-sm-7">
						<?php                           
							echo form_dropdown("costo_promedio", 
							array('0' => 'no','1' => 'si'),
							set_value('costo_promedio', $data['data']['costo_promedio'])); 
                        ?>
					</div>				
				</div>
			</div>		
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Cabecera Factura</label>
					<div class="col-sm-7">
						<textarea rows="4" cols="50" name="cabecera_factura" id="cabecera_factura"><?php echo $data['data']['cabecera_factura']; ?></textarea>
					</div>
				</div>
			</div>	
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Términos Condiciones:</label>
					<div class="col-sm-7">
						<textarea rows="4" cols="50" name="terminos_condiciones" id="terminos_condiciones"><?php echo $data['data']['terminos_condiciones']; ?></textarea>
					</div>				
				</div>
			</div>					
		</div>
		<div class="col-sm-12">			
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Prefijo Factura General:</label>
					<div class="col-sm-7">
						<input type="text" name="prefijo_factura" value="<?php echo set_value('prefijo_factura', $data['data']['prefijo_factura']) ?>">
					</div>				
				</div>
			</div>	
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Número Factura General:</label>
					<div class="col-sm-7">
						<input type="number" name="numero_factura" value="<?php echo set_value('numero_factura', $data['data']['numero_factura']) ?>">
					</div>
				</div>
			</div>						
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Resolución Factura General:</label>
					<div class="col-sm-7">
						<input type="text" name="resolucion_factura" value="<?php echo set_value('resolucion_factura', $data['data']['resolucion_factura']) ?>">
					</div>
				</div>
			</div>	
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Pago Automático:</label>
					<div class="col-sm-7">
						<div class="example-wrap">	
							<input type="radio" id="auto_pago_estandar" name="auto_pago" value="estandar" <?php echo ($data['data']['auto_pago'] == "estandar") ? "checked" : ""; ?>>
							<label for="auto_factura_estandar">Preguntar</label>
							<br><br>
							<input type="radio" id="auto_pago_automatico" name="auto_pago" value="auto" <?php echo ($data['data']['auto_pago'] == "auto") ? "checked" : ""; ?>>
							<label for="auto_factura_estandar">Automático</label>
							
						</div>
					</div>				
				</div>
			</div>						
		</div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Imprimir Factura:</label>
					<div class="col-sm-7">
						<div class="example-wrap">	
							<input type="radio" id="uniform-auto_factura_auto" name="auto_factura" value="estandar" <?php echo ($data['data']['auto_factura'] == "estandar") ? "checked" : ""; ?>> 
							<label for="auto_factura_estandar">Preguntar</label>
							<br><br>
							<input type="radio" id="auto_factura_auto" name="auto_factura" value="auto" <?php echo ($data['data']['auto_factura'] == "auto") ? "checked" : ""; ?>> 
							<label for="auto_factura_estandar">Automático</label>
							<br><br>
							<input type="radio" id="auto_factura_no" name="auto_factura" value="no" <?php echo ($data['data']['auto_factura'] == "no") ? "checked" : ""; ?>>
							<label for="auto_factura_estandar">No Imprimir</label>
						</div>
					</div>				
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="inputEmail3" class="col-sm-5 control-label negrita">Enviar Factura:</label>
					<div class="col-sm-7">
						<div class="example-wrap">	
							<input type="radio" id="enviar_factura_estandar" name="enviar_factura" value="estandar" <?php echo ($data['data']['enviar_factura'] == "estandar") ? "checked" : ""; ?>> 
							<label for="auto_factura_estandar">Preguntar</label>
							<br><br>
							 <input type="radio" id="enviar_factura_no" name="enviar_factura" value="no" <?php echo ($data['data']['enviar_factura'] == "no") ? "checked" : ""; ?>>
                                <label for="enviar_factura_no">No Enviar</label>
							
						</div>
					</div>				
				</div>
			</div>	
		</div>		
		<div class="col-sm-12 text-center">			
			<div class="form-group">
				<button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>		
				<button type="submit" id="guardar" class="btn btn-success">Guardar</button>			
			</div>
		</div>
	</form>
</div>
					
		
<script type="text/javascript">
	$(document).ready(function(){		
		$("#validate").submit(function(e){
			$("#buscar").prop('disabled',false);
		});
	});

</script>
