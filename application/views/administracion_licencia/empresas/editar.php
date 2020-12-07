<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Productos", "Empresa");?></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_product', "Editar Empresa");?></h2>    
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("administracion_vendty/empresas/editar/".$data['dataempresa'][0]->idempresas_clientes, array("id" =>"validate"));?>

                                <input type="hidden" value="<?php echo set_value('idempresas_clientes', $data['dataempresa'][0]->idempresas_clientes); ?>" name="idempresas_clientes" />                                    
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre_empresa',$data['dataempresa'][0]->nombre_empresa); ?>" placeholder="" name="nombre_empresa" />
										<?php echo form_error('nombre_empresa'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_name', "Razon Social");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('razon_social_empresa',$data['dataempresa'][0]->razon_social_empresa); ?>" placeholder="" name="razon_social_empresa" />
										<?php echo form_error('razon_social_empresa'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Tipo de Identificación");?>:</div>
                                    <div class="span9">
                                        <select name="tipo_identificacion"  value="">
                                            <option value="NIT" <?php echo ($data['dataempresa'][0]->tipo_identificacion == 'NIT') ? "selected":"" ?>>NIT</option>
                                            <option value="RUT" <?php echo ($data['dataempresa'][0]->tipo_identificacion== 'RUT') ? "selected":"" ?>>RUT</option>
                                            <option value="CC" <?php echo ($data['dataempresa'][0]->tipo_identificacion == 'CC') ? "selected":"" ?>>CC</option>                                        
                                        </select>
                                        <?php echo form_error('tipo_identificacion'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Documento identificación");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('identificacion_empresa',$data['dataempresa'][0]->identificacion_empresa); ?>" placeholder="" name="identificacion_empresa" />
                                    	<?php echo form_error('identificacion_empresa'); ?>
                                    </div>
                                </div>	
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Direccion");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('direccion_empresa',$data['dataempresa'][0]->direccion_empresa); ?>" placeholder="" name="direccion_empresa" />
                                        <?php echo form_error('direccion_empresa'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Telefono");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('telefono_contacto',$data['dataempresa'][0]->telefono_contacto); ?>" placeholder="" name="telefono_contacto" />
										<?php echo form_error('telefono_contacto'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Email");?>:</div>
                                    <div class="span9">
                                        <select name="id_db_config" value="<?php echo set_value('id_db_config',$data['dataempresa'][0]->idusuario_creacion.'-'.$data['dataempresa'][0]->id_db_config); ?>">
										<?php									
										foreach ($data['email'] as $key => $value) {
                                            $selected="";
                                            if($value->id==$data['dataempresa'][0]->idusuario_creacion){
                                                $selected="selected";
                                            }
											echo "<option ".$selected." value=".$value->id."-".$value->db_config_id.">".$value->email."</option>";
										}
										?>  
                                        </select>
                                        <?php echo form_error('id_db_config'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Distribuidor");?>:</div>
                                    <div class="span9">
										<select name="id_distribuidores_licencia" id="id_distribuidores_licencia" value="<?php echo set_value('id_distribuidores_licencia',$data['dataempresa'][0]->id_distribuidores_licencia); ?>">
										<?php									
											foreach ($data['distribuidor'] as $key => $value) {
                                                $selected="";
                                                if($value->id_distribuidores_licencia==$data['dataempresa'][0]->id_distribuidores_licencia){
                                                    $selected="selected";
                                                }
												echo "<option ".$selected." value=".$value->id_distribuidores_licencia.">".$value->nombre_distribuidor."</option>";
											}
										?>                                                                                    
                                        </select>
                                        <?php echo form_error('id_distribuidores_licencia'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "usuario distribuidor");?>:</div>
                                    <div class="span9">
                                        <select name="id_user_distribuidor" id="id_user_distribuidor" value="">
                                            
                                        </select>
                                        <?php echo form_error('id_user_distribuidor'); ?>
                                    </div>
                                </div>
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "País");?>:</div>
                                    <div class="span9">
                                        <select name="pais" id="pais" value="<?php echo set_value('pais',$data['dataempresa'][0]->pais); ?>">
										<?php									
											foreach ($data['pais'] as $value) {
                                                $selected="";
                                                if($value==$data['dataempresa'][0]->pais){
                                                    $selected="selected";
                                                }
												echo "<option ".$selected." value=".$value.">".$value."</option>";
											}
										?>     
                                        </select>
                                        <?php echo form_error('pais'); ?>
                                    </div>
                                </div>								
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Departamento/Estado");?>:</div>
                                    <div class="span9">
                                        <select name="provincia" id="provincia" value="<?php echo set_value('provincia',$data['dataempresa'][0]->departamento_empresa); ?>" >
                                        <?php// echo form_dropdown('provincia', array(), set_value('states', isset($data['dataempresa'][0]->departamento_empresa) ? $data['dataempresa'][0]->departamento_empresa:'' ), "id='provincia'");?>
                                        </select>
                                        <?php echo form_error('provincia'); ?>
                                    </div>
                                </div>			
								<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_codigo', "Ciudad");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo set_value('ciudad_empresa',$data['dataempresa'][0]->ciudad_empresa); ?>" placeholder="" name="ciudad_empresa" id="ciudad_empresa" />
										<?php echo form_error('ciudad_empresa'); ?>
                                    </div>
                                </div>	  
                                

                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                        <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                                    </div>
                                </div>

                            </div>

                            </form>

    </div>

    </div>

    

</div>
<script type="text/javascript">
    $(document).ready(function(){
		
		//Departamento
       $("#pais").change(function(){		  
           load_provincias_from_pais($(this).val());           
           $('#ciudad_empresa').attr('value','');
       });     

	   //distribuidores
	   $("#id_distribuidores_licencia").on('change',function(){ 		
			consultar_distribuidores_licencia();
		});		

    var id_user_distribuidor = $("#id_user_distribuidor").val();
    if(id_user_distribuidor != ""){
        consultar_distribuidores_licencia(id_user_distribuidor);
    }

    var pais = $("#pais").val();    
    if(pais != ""){
        load_provincias_from_pais(pais);      
    }

    });    

    function load_provincias_from_pais(pais){
        $.ajax({
            url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",
            data: {"pais" : pais},
            dataType: "json",
            success: function(data) {
                $("#provincia").html('');
                $.each(data, function(index, element){
                    var provincia = '<?php echo $data['dataempresa'][0]->departamento_empresa; ?>';
                   if(provincia==""){
                    provincia = "<?php echo set_value('provincia');?>"
                   }                                       
                    sel = provincia == element[0] ? "selected='selectted'" : '';
                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 
                  
                });
            }
        });
    }

	function consultar_distribuidores_licencia(){
		var url_distribuidores ='<?php echo site_url("administracion_vendty/licencia_empresa/consultar_usuarios_distribuidores") ?>';
		
		$.ajax({
			type: 'post',
			url: url_distribuidores,
			data: {distribuidor:$("#id_distribuidores_licencia").val()},
			dataType: 'json',
			success: function(result){
				$("#id_user_distribuidor").find('option').remove();
				$.each(result,function(index,value){
					$("#id_user_distribuidor").append($('<option>', { value : value.id }).text(value.email));
				});
				$("#id_user_distribuidor").trigger("chosen:updated");
			}
		});
	}	

</script>