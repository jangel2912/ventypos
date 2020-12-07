<style>
    .text-pago{margin: 0 0 10px;font-family: sans-serif;text-align:justify; color: grey;font-size: 10px;align-items: flex-end;bottom: 0px;}
    .title-pago{padding:10px; box-sizing:border-box;}
</style>

<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("", "Licencias");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Crear Licencia");?></h2>    
    </div>
</div>
<style>
    .ui-datepicker{
        background: #FFF;
    }
</style>
<?php $empresa=$data["id_empresa"]; ?>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
            <div class="data-fluid">
                <?php echo form_open("administracion_vendty/licencia_empresa/nuevo/$empresa", array("id" =>"validate","autocomplete"=>"off"));?>                   
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Empresa");?>:</div>
                    <div class="span9">
                        <select name="idempresas_clientes"  id="idempresas_clientes" required>
                            <?php
                                foreach($data['empresas'] as $key => $value){ ?>
                                    <option value="<?= $value->idempresas_clientes ?>"><?= $value->nombre_empresa ?></option>
                                <?php
                                }
                            ?>                                           
                        </select>                                       
                        <?php echo form_error('nombre_empresa'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Almacen");?>:</div>
                    <div class="span9">
                    <select name="id_almacen" id="id_almacen" required> 
                        
                    </select>
                    <?php echo form_error('id_almacen'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Plan");?>:</div>
                    <div class="span9">
                        <select name="id_plan" required>
                            <?php
                                foreach($data['planes'] as $key => $value){ ?>
                                    <option value="<?= $value->id ?>" ><?= $value->nombre_plan ?></option>
                                <?php
                                }
                            ?>                                           
                        </select>
                        <?php echo form_error('id_plan'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Fecha Inicio");?>:</div>
                    <div class="span9"><input type="text" class="fecha"  value="" name="fecha_inicio_licencia" id="fecha_inicio_licencia" required>
                        <?php echo form_error('fecha_inicio_licencia'); ?>
                    </div>
                </div>
                <!--
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Tiempo del plan");?>:</div>
                    <div class="span9">
                        <select name="fecha_vencimiento" >
                            <option value="mensual">Mensual</option>
                            <option value="trimestral">Trimestral</option>
                            <option value="semestral">Semestral</option>
                            <option value="anual">Anual</option>                                        
                        </select>
                    </div>
                </div>
                -->

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Fecha Vencimiento");?>:</div>
                    <div class="span9"><input type="text" class="fecha"  value=""  name="fecha_vencimiento" id="fecha_vencimiento" required>
                        <?php echo form_error('fecha_vencimiento'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_activo', "Activa");?>:</div>
                    <div class="span9">
                        <select name="estado_licencia" required>
                            <option value="1">Activa</option>
                            <option value="15">Suspendida</option>
                        </select>
                        <?php echo form_error('estado_licencia'); ?>
                    </div>
                </div>   
                
                <div class="toolbar bottom tar">
                    <div>
                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                        <button class="btn btn-success" id="btn_agregar_licencia" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
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
        consultar_almacen_empresa(empresa); 

        $("#idempresas_clientes").change(function(){           
            consultar_almacen_empresa($(this).attr('value'));     
        });
                     
        $(".fecha").datepicker({        
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            currentText: 'Hoy'
           // minDate: "-1Y", 
            //maxDate: "+1D",           
           // yearRange: "-1:+0"
        });

    });    

    function consultar_almacen_empresa(id){	 

		if(!isNaN(id)){
			$.ajax({
				type: "post",
				dataType: "json",
				url: url_consulta_almacenes,
				data:{id_empresa:id,activo:1},
				success: function(result){	
                    $("#id_almacen").html("");
					$.each(result,function(index,value){										
                        $("#id_almacen").append("<option value='"+ value.id +"'>"+ value.nombre +"</option>"); 											
					});					
				}
			});
		}
	}
/*
    $("#btn_agregar_licencia").click(function(e){
        e.preventDefault();

        var forma_pago = $("#modal_forma_pago").val();
        $("#forma_pago").attr('value',forma_pago);
        var monto_pago = $("#modal_monto_pago").val();
        $("#monto_pago").attr('value',monto_pago);
        var fecha_pago = $("#modal_fecha_pago").val();
        $("#fecha_pago").attr('value',fecha_pago);
        var fecha_conciliacion = $("#modal_fecha_conciliacion").val();
        $("#fecha_conciliacion").attr('value',fecha_conciliacion)
        var estado_pago = $("#modal_estado_pago").val();
        $("#estado_pago").attr('value',estado_pago);
        var descuento_pago = $("#modal_descuento_pago").val();
        $("#descuento_pago").attr('value',descuento_pago);
        var observacion_pago = $("#modal_observacion_pago").val();
        $("#observacion_pago").attr('value',observacion_pago);

        if(forma_pago == "" || monto_pago == "" || fecha_pago == "" || fecha_conciliacion == "" || estado_pago == "" || descuento_pago == "" || observacion_pago == ""){
            alert("Todos los campos de pago son obligatorios. Por favor verifique.");
            $("#modal-nuevo-pago").modal("show");
        }else{
            $("#validate").submit();
        }
    })*/

</script>