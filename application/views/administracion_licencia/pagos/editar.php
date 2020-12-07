<style>
    .text-pago{margin: 0 0 10px;font-family: sans-serif;text-align:justify; color: grey;font-size: 10px;align-items: flex-end;bottom: 0px;}
    .title-pago{padding:10px; box-sizing:border-box;}
</style>
<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("", "Pago");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Editar Pago Licencia");?></h2>    
    </div>
</div>
<style>
    .ui-datepicker{
        background: #FFF;
    }
</style>

<div class="row-fluid">
    <div class="span6">
        <div class="block">
            <div class="data-fluid">
                <?php echo form_open("administracion_vendty/pagos_factura/editar/".$data['datos_pago'][0]['idpagos_licencias'], array("id" =>"validate"));?>
                   
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Empresa");?>:</div>
                    <div class="span9">
                        <select name="idempresas_clientes"  id="idempresas_clientes" disabled>
                            <?php 
                                
                                foreach($data['empresas'] as $key => $value){ 
                                    if($value->idempresas_clientes==$data['datos_pago'][0]['idempresas_clientes']){
                                        $selected="selected";    
                                    }
                                    else{
                                        $selected="";
                                    }    
                                ?>
                                    <option <?= $selected ?> value="<?= $value->idempresas_clientes ?>"><?= $value->nombre_empresa ?></option>
                                <?php
                                }
                            ?>                                           
                        </select>                                       
                        <?php echo form_error('nombre_empresa'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('almacen', "Almacen");?>:</div>
                    <div class="span9">
                    <select name="id_almacen" id="id_almacen" disabled > 
                        
                    </select>
                    <?php echo form_error('id_almacen'); ?>
                    </div>
                </div>
                <?php                     
                    if(!empty($data['idplan'])&&($data['idplan']!=1)){
                       $disabled="disabled";
                        //$esconder="hidden";
                    }else{
                        $disabled="";
                        //$esconder="";
                    }
                    ?> 
                <div class="row-form ">
                    <div class="span3"><?php echo custom_lang('sima_name', "Plan");?>:</div>
                    <div class="span9">
                   
                        <select name="plan_id"  id="plan_id" <?= $disabled ?> >
                            <option value="">Seleccione</option>
                            <?php
                            
                                foreach($data['planes'] as $key => $value){ 
                                    if(!empty($data['idplan']) &&($data['idplan']==$value->id)){
                                        $selected="selected";
                                    }
                                    else{
                                        $selected="";
                                    }
                                    ?>
                                    <option <?= $selected?> value="<?= $value->id ?>"><?= $value->descripcion ?></option>
                                <?php
                                }
                            ?>                                           
                        </select>                                       
                        <?php echo form_error('nombre_empresa'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Forma de Pago");?>:</div>
                    <div class="span9">
                        <select name="formapago" id="formapago" disabled >                            
                            <option value="">Seleccione</option>                            
                            <?php            
                                foreach($data['formaspagos'] as $key => $value){ ?>                                    
                                    <option <?php echo($data['datos_pago'][0]['idformas_pago']==$value->idformas_pago) ? "selected": "" ?> value="<?= $value->idformas_pago ?>" ><?= $value->nombre_forma ?></option>
                                <?php
                                }
                            ?>                                           
                        </select>
                        <?php echo form_error('formapago'); ?>
                    </div>
                </div>
                <div class="hidden" id="epayco">
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('transaccion', "Id transacción");?>:</div>
                        <div class="span9"><input type="text" value="<?= $data['datos_pago'][0]['transaction_id'] ?>" id="transaction_id" name="transaction_id" >
                            <?php echo form_error('transaction_id'); ?>
                        </div>
                    </div>
                    <div class="row-form">
                        <div class="span3"><?php echo custom_lang('ref_payco', "Ref. Epayco");?>:</div>
                        <div class="span9"><input type="text" value="<?= $data['datos_pago'][0]['ref_payco'] ?>" id="ref_payco" name="ref_payco" >
                            <?php echo form_error('ref_payco'); ?>
                        </div>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('valorpago', "Valor del pago");?>:</div>
                    <div class="span9"><input type="text" value="<?= $data['datos_pago'][0]['monto_pago'] ?>" id="valorpago" name="valorpago" >
                        <?php echo form_error('valorpago'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('descuento', "Descuento de pago");?>:</div>
                    <div class="span9"><input type="text" value="<?= $data['datos_pago'][0]['descuento_pago'] ?>" id="descuento" name="descuento" >
                        <?php echo form_error('descuento'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('retencion', "Retención de pago");?>:</div>
                    <div class="span9"><input type="text" value="<?= $data['datos_pago'][0]['retencion_pago'] ?>" id="retencion" name="retencion" required>
                        <?php echo form_error('retencion'); ?>
                    </div>
                </div>
                        
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('estado',"Estado del pago");?>:</div>
                    <div class="span9">                             
                        <select id="estado" name="estado" required>                                
                            <option value="">Seleccione</option>
                            <option <?php echo($data['datos_pago'][0]['estado_pago']=="1") ? "selected": "" ?> value="1">Aprobado</option>                           
                            <option <?php echo($data['datos_pago'][0]['estado_pago']=="3") ? "selected": "" ?> value="3">Pendiente</option>                           
                        </select>
                        <?php echo form_error('estado'); ?>
                    </div>
                </div>               
                 
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Fecha Pago");?>:</div>
                    <div class="span9">
                    <input type="text" class="fecha" value="<?= $data['datos_pago'][0]['fecha_pago'] ?>" name="fecha_pago" id="fecha_pago" disabled>
                        <?php echo form_error('fecha_pago'); ?>
                    </div>
                </div>                    
                
                <div class="toolbar bottom tar">
                    <div>
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='<?= site_url() ?>/administracion_vendty/pagos_factura/'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                        <button class="btn btn-success" id="btn_agregar_licencia" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                    </div>
                </div>

            </div>

    </div>

    </div>

    

</div>
<script type="text/javascript">
    var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';
	
    $(document).ready(function(){      	
        empresa=$("#idempresas_clientes").val(); 
        idforma='<?= $data['datos_pago'][0]['idformas_pago'] ?>';
        if(idforma==3){
            $("#epayco").removeClass("hidden");
        }
        if((empresa !="") &&(empresa!=0)){
            consultar_almacen_empresa(empresa); 
        }  
    
        $("#idempresas_clientes").change(function(){  
            consultar_almacen_empresa($(this).attr('value'));     
        });

        $("#formapago").change(function(){  
            forma=$(this).attr('value');     
            if(forma==3){
                $("#epayco").removeClass("hidden");
            }else{
                 $("#epayco").addClass("hidden");
            }
        });

        $(".fecha").datepicker({        
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            currentText: 'Hoy',
            minDate: "-10", 
            maxDate: "+1D",           
            yearRange: "-1:+0"

        });

    });    

    function consultar_almacen_empresa(id){	 
         almacenSeleccionado='<?= $data['datos_pago'][0]['id_almacen']?>';            
		if(!isNaN(id)){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_empresa:id,activo:1},
				success: function(result){	
                    $("#id_almacen").html("");
					$.each(result,function(index,value){
                        
                        if(almacenSeleccionado==value.id){
                            sele="selected";
                        }else{
                            sele="";                           
                        }    
                          										
                        $("#id_almacen").append("<option "+ sele+" value='"+ value.id +"'>"+ value.nombre +"</option>"); 											
					});					
				}
			});
		}
	}   

</script>