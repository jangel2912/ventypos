<style>
    .pl-0{padding-left:0px;}
    .p-10{padding:10px; box-sizing:border-box;}
    .mb-10{margin-bottom:10px;}
    .mt-0{margin-top:0px;}
    .radio{margin-top:0px !important;}
    .d-flex{display:flex;align-items:center;}
    .d-none{display:none;}
    .outline-lightgray{outline:solid 1px lightgray;}
    .error-outline{outline:solid 2px #ebcccc;}
    .alert-danger{    background-color: #f2dede;border-color: #ebcccc;color: #a94442;}
    .modal-content {padding-bottom:10px; box-sizing:border-box;}
    .content-modal .titulo-modal{font-weight:bold;}
    .content-modal .modal-title{padding-top:10px;margin-bottom: 10px;}
    .content-modal .close-modal{ position: absolute;right: 10px;top: 10px;}
    .content-modal input,select{margin:5px;}
    .content-modal button{margin-top:10px;}
</style>
<script src="<?php echo base_url("index.php/OpcionesController/index")?>"></script>
<script>
    $(document).on('blur','.dataMoneda',function(){
        $(this).val(limpiarCampo($(this).val()));
    });
</script>
<div class="page-header">    
    <div class="icon">
        <img alt="Gastos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_gastos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Gastos", "Gastos");?></h1>
</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_edit_expenses', "Editar Gasto");?></h2>                                          
        
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
    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

                            <div class="data-fluid">

                                <?php echo form_open("proformas/editar/".$data["data"]['id_proforma'], array("id" =>"validate"));?>

                                    <input type="hidden" name="id_proforma" id="id_factura" value="<?php echo set_value('id_proforma', $data["data"]['id_proforma']); ?>" />

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('descripcion', $data['data']['descripcion']); ?>" name="descripcion" id="descripcion"/>

                                                    <?php echo form_error('descripcion'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('Proveedor', "Proveedor");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('datos_proveedor', $data['data']['nombre_comercial']); ?>" name="datos_proveedor" id="datos_proveedor"/>

                                             <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?php echo set_value('id_proveedor', $data['data']['id_proveedor']); ?>" />    

                                            <?php echo form_error('id_proveedor'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_provider_data', "Datos del poveedor");?>:</div>

                                        <div class="span9"><textarea name="otros_datos" id="otros_datos" placeholder="Datos del proveedor" readonly="readonly"><?php echo $data['data']['otros_datos']  ?></textarea>


                                        <?php echo form_error('datos_proveedor'); ?>
                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('fecha', $data['data']['fecha']); ?>" name="fecha" id="fecha" />

                                                <?php echo form_error('fecha'); ?>

                                        </div>

                                    </div>
									
                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_date', "Almac&eacute;n");?>:</div>

                                        <div class="span9">
					 <?php   $is_admin = $this->session->userdata('is_admin');
							  if($is_admin == 's'){
								echo $data['almacen_nombre']; 
								  ?><input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen" id="almacen"/><?php
								 } 
								 else{
  
	echo "<select  name='almacen' >";       
    foreach($data['almacen'] as $f){
        if($f->id == set_value('almacen', $data['data']['id_almacen'])){
            $selected = " selected=selected ";
        } else {
            $selected = "";
        }        
        echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
    }    
    echo "</select>";
	
		
								  }
							  ?>	
                              <?php echo form_error('almacen'); ?>
                                        </div>

                                    </div>									
									
                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_date', "Forma de Pago");?>:</div>

                                        <div class="span9">
<select name="forma_pago" id="forma_pago">
<option <?php if($data['data']['forma_pago'] == 'Efectivo'){ echo "selected=selected"; } ?> value="Efectivo">Efectivo</option>
<option <?php if($data['data']['forma_pago'] == 'Tarjeta de crédito'){ echo "selected=selected"; } ?>  value="Tarjeta de crédito">Tarjeta de crédito</option>
<option <?php if($data['data']['forma_pago'] == 'Tarjeta debito'){ echo "selected=selected"; } ?> value="Tarjeta debito">Tarjeta debito</option>
<option <?php if($data['data']['forma_pago'] == 'Crédito'){ echo "selected=selected"; } ?>  value="Crédito">Crédito</option>
<option <?php if($data['data']['forma_pago'] == 'Cheque'){ echo "selected=selected"; } ?> value="Cheque">Cheque </option>
<option <?php if($data['data']['forma_pago'] == 'Consignación'){ echo "selected=selected"; } ?> value="Consignación">Consignación</option>
<option <?php if($data['data']['forma_pago'] == 'Transferencia'){ echo "selected=selected"; } ?> value="Transferencia">Transferencia</option>
</select>
                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_value', "Valor");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('valor', $data['data']['valor']); ?>" class="dataMoneda" name="valor" id="valor"/>

                                                <?php echo form_error('valor'); ?>

                                        </div>

                                    </div>

                                     <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_amount', "Cantidad");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('cantidad', $data['data']['cantidad']); ?>" name="cantidad" id="cantidad"/>

                                                <?php echo form_error('cantidad'); ?>

                                        </div>

                                    </div>

                                     <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto");?>:</div>

                                        <div class="span9">
                                                <?php echo form_dropdown('id_impuesto', $data['impuestos'], set_value('id_impuesto', $data['data']['id_impuesto']));?>
                                                <?php echo form_error('id_impuesto'); ?>

                                        </div>

                                    </div>

                                     <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_tax', "Cuentas de dinero");?>:</div>

                                        <div class="span9">

                                        
                                             <?php foreach($data['cuentas_dinero'] as $f): ?>
                                                <label class="radio-inline pl-0">
                                                    <input type="radio" class="mt-0" name="cuentas_dinero" id="cuentas_dinero<?= $f->id; ?>" value="<?= $f->id; ?>"   <?= ($f->id == 1)? 'checked' : ''; ?>> 
                                                    <?= $f->nombre;?>
                                                    <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="right" data-content="<?= ($f->id == 1)? 'El gasto se vera reflejado directamente en la caja' : 'El gasto quedara asociado directamente al banco y se generará como un movimiento bancario,( No afectará la caja)'; ?>" data-trigger="hover"></span>
                    
                                                </label>
                                            <?php endforeach; ?>

                                            <input type="hidden" name="banco_asociado" id="banco_asociado">
                                            <input type="hidden" name="subcategoria_gasto_asociada" id="subcategoria_gasto_asociada">                                 
                                        </div>

                                    </div>

                                     <div class="row-form d-none" id="asociar_banco">
                                        <div class="span12 content-asociar-banco outline-lightgray p-10 mb-10">
                                            <div class="form-group">
                                                <label for="categoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                                                <div class="col-sm-8">
                                                    <select id="categoria_gasto" class="col-sm-8 form-control">
                                                        <option value="">Seleccione categoria</option>
                                                        <?php foreach($data["categorias_gastos"] as $categoria): ?>
                                                        <option value="<?= $categoria->id;?>"><?= ucfirst($categoria->nombre);?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="subcategoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                                                <div class="col-sm-8">
                                                    <select id="subcategoria_gasto" class="col-sm-8 form-control">
                                                        <option value="">Seleccione sub-categoria</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="banco" class="col-sm-4 control-label text-left">Banco:</label>
                                                <div class="col-sm-8">
                                                    <select id="banco" class="col-sm-8 form-control">
                                                        <option value="">Seleccione banco</option>
                                                        <?php foreach($data["bancos"] as $banco): ?>
                                                        <option value="<?= $banco->id;?>"><?= ucfirst($banco->nombre_cuenta);?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_notes', "Notas");?>:</div>

                                        <div class="span9"><textarea name="notas" id="notas" placeholder="Notas"><?php echo set_value('notas', $data['data']['notas']); ?></textarea>

                                        </div>

                                    </div>
                                    <!--<div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_notes', "Asociar a bancos");?>:</div>
                                        <div class="span9">
                                                <input type="checkbox" id="asociar_banco" name="asociar_banco" <?= ($data['data']['movimiento_asociado'] != "")? 'checked' : '';?>>          
                                                <input type="hidden" name="banco_asociado" id="banco_asociado" value="<?= $data['data']['banco_asociado'];?>">
                                                <input type="hidden" na me="subcategoria_gasto_asociada" id="subcategoria_gasto_asociada" value="<?= $data['data']['subcategoria_asociada'];?>" >                          
                                        </div>
                                    </div>  -->                                             
                                <div class="toolbar bottom tar">                                    
                                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    <button class="btn btn-success" id="enviar" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                </div>

                                </div>

                            </div>

                            </form>

    </div>

    </div>

</div>

<!-- 
<div class="modal fade content-modal" tabindex="-1" role="dialog"  id="modal-gastos">
  <div class="" role="document">
    <div class="">
      <div class="modal-header">
        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Asociar gasto a banco</h4>
      </div>

        <div class="">
            <div class="form-group">
                <label for="categoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                <div class="col-sm-8">
                    <select id="categoria_gasto" class="col-sm-8 form-control">
                        <option value="">Seleccione categoria</option>
                        <?php foreach($data["categorias_gastos"] as $categoria): ?>
                        <option value="<?= $categoria->id;?>" <?= (isset($data['data']['detalle_categorias']) && ($data['data']['detalle_categorias']->id_categoria == $categoria->id))? 'selected' : '';?>><?= ucfirst($categoria->nombre);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

             <div class="form-group">
                <label for="subcategoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                <div class="col-sm-8">
                    <select id="subcategoria_gasto" class="col-sm-8 form-control">
                        <option value="">Seleccione sub-categoria</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="banco" class="col-sm-4 control-label text-left">Banco:</label>
                <div class="col-sm-8">
                    <select id="banco" class="col-sm-8 form-control">
                        <option value="">Seleccione banco</option>
                        <?php foreach($data["bancos"] as $banco): ?>
                        <option value="<?= $banco->id;?>" <?= ($data['data']['banco_asociado'] == $banco->id)? 'selected' : '';?>><?= ucfirst($banco->nombre_cuenta);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" data-dismiss="modal">Guardar</button>
        </div>
    </div><
  </div>
</div>-->



<!-- modal gasto a banco -->
<div class="modal fade content-modal" tabindex="-1" role="dialog"  id="modal-gastos">
  <div class="" role="document">
    <div class="">
      <div class="modal-header">
        <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Asociar gasto a banco</h4>
      </div>

        <div class="">
            <div class="form-group">
                <label for="categoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                <div class="col-sm-8">
                    <select id="categoria_gasto" class="col-sm-8 form-control">
                        <option value="">Seleccione categoria</option>
                        <?php foreach($data["categorias_gastos"] as $categoria): ?>
                        <option value="<?= $categoria->id;?>"><?= ucfirst($categoria->nombre);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>

             <div class="form-group">
                <label for="subcategoria_gasto" class="col-sm-4 control-label text-left">Categoria del gasto:</label>
                <div class="col-sm-8">
                    <select id="subcategoria_gasto" class="col-sm-8 form-control">
                        <option value="">Seleccione sub-categoria</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="banco" class="col-sm-4 control-label text-left">Banco:</label>
                <div class="col-sm-8">
                    <select id="banco" class="col-sm-8 form-control">
                        <option value="">Seleccione banco</option>
                        <?php foreach($data["bancos"] as $banco): ?>
                        <option value="<?= $banco->id;?>"><?= ucfirst($banco->nombre_cuenta);?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" data-dismiss="modal">Guardar</button>
        </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
    var url_cargar_subcategorias = "<?= site_url('proformas/cargar_subcategorias');?>";
    var tipo_cuenta = $("#cuentas_dinero").val();

    $(document).ready(function(){

        $("#datos_proveedor").autocomplete({

			source: "<?php echo site_url("proveedores/get_ajax_proveedores"); ?>",

			minLength: 2,

			select: function( event, ui ) {

                                $("#id_proveedor").val(ui.item.id);

				$("#otros_datos").val(ui.item.descripcion);

			}

		});

        $( "#fecha" ).datepicker({

             dateFormat: 'yy-mm-dd'

        });
        
        $("#cuentas_dinero1").click(function(){
            $("#asociar_banco").delay(20).fadeOut();
            tipo_cuenta = 1;
        });

         $("#cuentas_dinero2").click(function(){
            tipo_cuenta = 2;
            $("#asociar_banco").delay(100).fadeIn();
                
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
            })

            $("#subcategoria_gasto").change(function(){
                $("#subcategoria_gasto_asociada").val($("#subcategoria_gasto").val());
            })

            $("#banco").change(function(){
                $("#banco_asociado").val($("#banco").val());
                })     
        })

        $("#enviar").click(function(e){
            e.preventDefault();
            let result = true;
            if(tipo_cuenta == 2){
                if($("#subcategoria_gasto_asociada").val() == '' || $("#banco_asociado").val() == ''){
                    swal({
                        cancelButtonColor: '#d33',
                        type: 'error',
                        title: 'Error',
                        text: 'Campos incompletos para banco. Verifique'
                    })
                    $(".content-asociar-banco").addClass('error-outline');
                    result = false;
                }
            }
            
            if(result){
                document.getElementById('enviar').disabled=true;
                document.getElementById("validate").submit();
            }
        });
    });

        


       /* $("#asociar_banco").click(function(){
            if($("#asociar_banco").attr('checked') == "checked"){
                $("#modal-gastos").modal("show");
                
                $("#categoria_gasto").change(function(){
                    $.post(url_cargar_subcategorias,{
                        id_categoria: $("#categoria_gasto").val()
                    },function(data){
                        var subcategorias = JSON.parse(data);
                        var options = '';

                        $.each(subcategorias,function(index,element){
                            console.log(element);
                            options += "<option value='"+element.id+"'>"+element.nombre+"</option>";
                        })
                        
                        $("#subcategoria_gasto").html(options);
                    })
                })

                $("#subcategoria_gasto").change(function(){
                    $("#subcategoria_gasto_asociada").val($("#subcategoria_gasto").val());
                })

                $("#banco").change(function(){
                    $("#banco_asociado").val($("#banco").val());
                })
            
            }
        })*/

</script>