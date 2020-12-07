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
        <h2><?php echo custom_lang('sima_new_expenses', "Nuevo Gasto");?></h2>                                                  
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
                                <?php echo form_open("proformas/nuevo", array("id" =>"gastos"));?>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo set_value('descripcion'); ?>" name="descripcion" id="descripcion"/>
                                                    <?php echo form_error('descripcion'); ?>
                                        </div>
                                    </div>

                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('Proveedor', "Proveedor");?>:</div>
                                        <div class="span9">
                                                <div class="input-append"> 
                                                    <input type="text"  value="<?php echo set_value('datos_proveedor'); ?>" name="datos_proveedor" id="datos_proveedor"/>
                                                    <span class="add-on green" data-tooltip="Nuevo Proveedor" style="width: 35px!important;" id="add-new-provider"><img alt="Cliente" src="<?php echo $this->session->userdata('new_imagenes')['cliente_blanco']['original'] ?>" /></span>
                                                </div>
                                                <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?php echo set_value('id_proveedor'); ?>" />
                                                    <?php echo form_error('id_proveedor'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_provider_data', "Datos del Proveedor");?>:</div>
                                        <div class="span9"><textarea name="otros_datos" id="otros_datos" placeholder="" readonly="readonly"><?php echo set_value('otros_datos'); ?></textarea>
                                                    <?php echo form_error('otros_datos'); ?>                                        
                                        </div>
                                    </div>

                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha" id="fecha"/>                                                <?php echo form_error('fecha'); ?>								
                                        </div>
                                    </div>
									
									
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date', "Almacén");?>:</div>
                                        <div class="span9">					
                                        <?php 
                                            $is_admin = $this->session->userdata('is_admin');
                                                if($is_admin == 's'){
                                                echo $data['almacen_nombre']; 
                                        ?>
                                        <input type="hidden"  value="<?php echo $data['almacen_id']; ?>" name="almacen" id="almacen"/>
                                        <?php
                                                } else {
                                            echo "<select  name='almacen' >";    
                                                echo "<option value=''>Seleccione un almacén</option>";    
                                                foreach($data['almacen'] as $f){
                                                    if($f->id == $this->input->post('almacen')){
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
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                                            <option value="Tarjeta debito">Tarjeta Débito</option>
                                            <option value="Crédito">Crédito</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Consignación">Consignación</option>
                                            <option value="Transferencia">Transferencia</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_value', "Valor");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('valor'); ?>" class="dataMoneda" name="valor" id="valor"/>

                                                <?php echo form_error('valor'); ?>

                                        </div>

                                    </div>

                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_amount', "Cantidad");?>:</div>

                                        <div class="span9"><input type="text"  value="<?php echo set_value('cantidad'); ?>" name="cantidad" id="cantidad"/>

                                                <?php echo form_error('cantidad'); ?>

                                        </div>

                                    </div>

                                     <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto");?>:</div>

                                        <div class="span9">

                                                <?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto'));?>

                                                <?php echo form_error('id_impuesto'); ?>

                                        </div>

                                    </div>

                                     <div class="row-form"> 

                                        <div class="span3"><?php echo custom_lang('sima_tax', "Cuentas de Dinero");?>:</div>
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
                                    <?php /*echo "<select  name='cuentas_dinero' id='cuentas_dinero'>";      
                                            foreach($data['cuentas_dinero'] as $f){
                                                        
                                                echo "<option $selected value=" . $f->id . ">" . $f->nombre . "</option>";
                                                                                    }    
                                            echo "</select>"; */ ?>
                                    <div class="row-form">

                                        <div class="span3"><?php echo custom_lang('sima_notes', "Notas");?>:</div>

                                        <div class="span9"><textarea name="notas" id="notas" placeholder="Notas"><?php echo set_value('notas'); ?></textarea>
                                            <?php echo form_error('notas'); ?>
                                        </div>

                                    </div>


                                <br/>

                                <div class="toolbar bottom tar">                                    
                                    <button class="btn btn-default"  type="button" onclick="javascript:location.href='index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    <button class="btn btn-success" id="enviar" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
                                </div>

                            </div>

                            </form>

                            

                            <!--div id="dialog-provider-form" title="<?php echo custom_lang('sima_new_provider', "Adicionar proveedor");?>">

                                <div class="span6">

                                    <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                                    <form id="provider-form">

                                            <div class="row-form">

                                                <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>

                                                <div class="span3"><input type="text" name="nombre_comercial" id="nombre_comercial" class="validate[required]"/></div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>

                                                <div class="span3"><input type="text" name="email" id="email" class="validate[custom[email]]"/>

                                                    

                                                </div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2"><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?>:</div>

                                                <div class="span3"><input type="text" name="razon_social" id="razon_social"/>

                                                </div>

                                            </div>

                                            <div class="row-form">

                                                <div class="span2"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>

                                                <div class="span3"><input type="text" name="nif_cif" id="nif_cif" class="validate[required]"/>

                                                </div>

                                            </div>

                                    </form>

                                </div>

                            </div-->

    </div>

    </div>

</div>

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

    var waitingDialog = waitingDialog || (function ($) {
    'use strict';

    // Creating modal dialog's DOM
    var $dialog = $(
    '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
        '<div class="modal-header"><?php echo custom_lang('sima_new_provider', "Adicionar proveedor");?></div>' +
        '<div class="modal-body">' +
            '<p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos los campos son requeridos");?>.</p>'+
                '<form id="provider-form">'+
                        '<div class="row-form">'+
                            '<div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>'+
                            '<div class="span3"><input type="text" name="nombre_comercial" id="nombre_comercial" class="validate[required]"/></div>'+
                        '</div>'+
                        '<div class="row-form">'+
                            '<div class="span2"><?php echo custom_lang('sima_email', "Correo electronico");?>:</div>'+
                            '<div class="span3"><input type="text" name="email" id="email" class="validate[custom[email]]"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="row-form">'+
                            '<div class="span2"><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?>:</div>'+
                            '<div class="span3"><input type="text" name="razon_social" id="razon_social"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="row-form">'+
                            '<div class="span2"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>'+
                            '<div class="span3"><input type="text" name="nif_cif" id="nif_cif" class="validate[required]"/>'+
                            '</div>'+
                        '</div>'+
                '</form>'+
        '</div>' +
        '<div class="modal-footer">'+
            '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>'+
            '<button id="add-proveedor" type="button" class="btn btn-success">Aceptar</button>'+           
        '</div>'+
    '</div></div></div>');

    return {
    /**
        * Opens our dialog
        * @param message Custom message
        * @param options Custom options:
        * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
        * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
        */
    show: function (id, options) {
        // Assigning defaults
        if (typeof options === 'undefined') {
            options = {};
        }
        /*if (typeof message === 'undefined') {
            message = 'Loading';
        }*/
        var settings = $.extend({
            dialogSize: 'm',
            progressType: '',
            onHide: null // This callback runs after the dialog was hidden
        });
        $dialog.find('#add-proveedor').on('click',function(){
            if($("#provider-form").length > 0)

                {

                    $("#provider-form").validationEngine('attach',{promptPosition : "topLeft"});

                    if($("#provider-form").validationEngine('validate')){

                        $.ajax({

                            url: '<?php echo site_url('proveedores/add_ajax_provider');?>',

                            data: {nombre_comercial: $('#nombre_comercial').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val()},

                            dataType: 'json',

                            type: 'POST',

                            success: function(data){

                                $("#id_proveedor").val(data.id_proveedor);

                                $("#datos_proveedor").val($('#nombre_comercial').val() + "(" + $('#razon_social').val()+ ")");

                                $("#otros_datos").val($('#nif_cif').val() + ", " + $('#email').val());

                                

                                $dialog.modal('hide');

                            }

                        }); 

                    }

                }
        })

        // Configuring dialog
        $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
        $dialog.find('.progress-bar').attr('class', 'progress-bar');
        if (settings.progressType) {
            $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
        }
        //$dialog.find('h3').text(message);
        $dialog.find('#venta_id').val(id);
        // Adding callbacks
        if (typeof settings.onHide === 'function') {
            $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
                settings.onHide.call($dialog);
            });
        }
        // Opening dialog
        $dialog.modal();
    },
    /**
        * Closes dialog
        */
    hide: function () {
        $dialog.modal('hide');
    }
    };

    })(jQuery);

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

        $( "#dialog-provider-form" ).dialog({

			autoOpen: false,

			//height: 400,

			width: 620,

			modal: true,

			buttons: {

				"Aceptar": function() {

                                        

                                        if($("#provider-form").length > 0)

                                        {

                                            $("#provider-form").validationEngine('attach',{promptPosition : "topLeft"});

                                            if($("#provider-form").validationEngine('validate')){

                                                $.ajax({

                                                    url: '<?php echo site_url('proveedores/add_ajax_provider');?>',

                                                    data: {nombre_comercial: $('#nombre_comercial').val(), razon_social: $('#razon_social').val(), nif_cif: $('#nif_cif').val(), email: $('#email').val()},

                                                    dataType: 'json',

                                                    type: 'POST',

                                                    success: function(data){

                                                        $("#id_proveedor").val(data.id_proveedor);

                                                        $("#datos_proveedor").val($('#nombre_comercial').val() + "(" + $('#razon_social').val()+ ")");

                                                        $("#otros_datos").val($('#nif_cif').val() + ", " + $('#email').val());

                                                        

                                                        $("#dialog-provider-form").dialog( "close" );

                                                    }

                                                }); 

                                            }

                                        }

				},

				"Cancelar": function() {

					$( this ).dialog( "close" );

				}

			},

			close: function() {

                            $('#razon_social').val("");

                            $('#nif_cif').val("");

                            $('#email').val("");

                            $('#nombre_comercial').val("");

			}
            

             
        });
        
        $("#add-new-provider").click(function(){
            waitingDialog.show();
            //$( "#dialog-provider-form" ).dialog( "open" );

        });

        

        $("#validate").submit(function () { 
           
       if($('#almacenes').val() == '0'){
	          alert("debe escojer un almacen");
	          return false;  
        }
  
            if($('#id_proveedor').val() == ''){

                //

                if(confirm("<?php echo custom_lang("sima_not_valid_provider", "El proveedor no es válido, desea adicionarlo?") ?>")){

                    if($("#datos_proveedor").val() != ""){

                        $('#nombre_comercial').val($("#datos_proveedor").val());

                    }

                    $( "#dialog-provider-form" ).dialog( "open" );

                }

                return false; 

            }

            else{

                return true;

            }

        });
        
        //Bancos
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
                        confirmButtonColor: '#4cae4c',
                        cancelButtonColor: '#d33',
                        type: 'error',
                        title: 'Error',
                        text: 'Campos incompletos para banco. Verifique'
                    })
                    //$("#modal-gastos").modal("show");
                    $(".content-asociar-banco").addClass('error-outline');
                    result = false;
                }
            }
            
            if(result){
                document.getElementById('enviar').disabled=true;
                document.getElementById("gastos").submit();
            }
        });

    });


</script>