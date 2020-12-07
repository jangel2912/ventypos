<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("", "Licencias");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Editar Licencias");?></h2>    
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
                                <?php echo form_open_multipart("administracion_vendty/licencia_empresa/editar/".$data['datoslicencias'][0]->id_licencia, array("id" =>"validate"));?>

                                <input type="hidden" value="<?php echo set_value('idlicencias_empresa', $data['datoslicencias'][0]->id_licencia); ?>" name="idlicencias_empresa" />                                    
                                <input type="hidden" value="<?php echo set_value('idempresas_clientes', $data['datoslicencias'][0]->idempresas_clientes); ?>" name="idempresas_clientes" />                                    
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Empresa");?>:</div>
                                    <div class="span9"><input type="text"  readonly  value="<?php echo set_value('nombre_empresa',$data['datoslicencias'][0]->nombre_empresa); ?>" placeholder="" name="nombre_empresa" />
										<?php echo form_error('nombre_empresa'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Almacen");?>:</div>
                                    <div class="span9">
                                    <select name="id_almacen" id="id_almacen" readonly > 
                                        <option value="<?php echo set_value('id_almacen',$data['datoslicencias'][0]->id_almacen.'-'.$data['datoslicencias'][0]->idempresas_clientes); ?>" > aaa</option>
                                    </select>
                                   <?php echo form_error('id_almacen'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Plan");?>:</div>
                                    <div class="span9">
                                        <select name="id_plan" >
                                            <?php
                                                foreach($data['planes'] as $key => $value){ ?>
                                                    <option value="<?= $value->id ?>"  <?php echo ($data['datoslicencias'][0]->id_plan ==  $value->id) ? "selected":"" ?> ><?= $value->nombre_plan ?></option>
                                                <?php
                                                }
                                            ?>                                           
                                        </select>
                                        <?php echo form_error('id_plan'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Fecha Inicio");?>:</div>
                                    <div class="span9"><input type="text" class="fecha" readonly  value="<?php echo set_value('fecha_inicio_licencia',$data['datoslicencias'][0]->fecha_inicio_licencia); ?>" name="fecha_inicio_licencia" id="fecha_inicio_licencia" />
										<?php echo form_error('fecha_inicio_licencia'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Fecha Vencimiento");?>:</div>
                                    <div class="span9"><input type="text" class="fecha" readonly  value="<?php echo set_value('fecha_vencimiento',$data['datoslicencias'][0]->fecha_vencimiento); ?>"  name="fecha_vencimiento" id="fecha_vencimiento"/>
										<?php echo form_error('fecha_vencimiento'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Fecha Activación Licencia");?>:</div>
                                    <div class="span9"><input type="text" class="fecha"  value="<?php echo set_value('fecha_activacion',$data['datoslicencias'][0]->fecha_activacion); ?>"  name="fecha_activacion" id="fecha_activacion"/>
										<?php echo form_error('fecha_activacion'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Activa");?>:</div>
                                    <div class="span9">
                                        <select name="estado_licencia">
                                            <option value="1" <?php echo ($data['datoslicencias'][0]->estado_licencia == '1') ? "selected":"" ?>>Activa</option>
                                            <option value="15" <?php echo ($data['datoslicencias'][0]->estado_licencia == '15') ? "selected":"" ?>>Suspendida</option>                                                
                                        </select>
                                        <?php echo form_error('estado_licencia'); ?>
                                    </div>
                                </div>                                
                               
                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                    </div>
                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>
<script type="text/javascript">
    var url_consulta_almacenes = '<?php echo site_url('administracion_vendty/activaciones_licencia/consultar_almacen_empresa') ?>';
	
    $(document).ready(function(){      	
        almacen=$("#id_almacen").val();	        
        consultar_almacen_empresa(almacen); 
                     
        $(".fecha").datepicker({        
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            currentText: 'Hoy',           
            maxDate: "+2Y",           
            yearRange: "-1:+2"

        });

    });    

    function consultar_almacen_empresa(id){	       
        var id	=id;	
        var ids = id.split("-");        
		if(!isNaN(ids[1])){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_empresa:ids[1],activo:1},
				success: function(result){	
                    $("#id_almacen").html("");
					$.each(result,function(index,value){
						if(value.id==ids[0]){							
                           $("#id_almacen").append("<option value='"+ value.id +"' selected='selected' >"+ value.nombre +"</option>"); 
						}					
					});					
				}
			});
		}
	}

</script>