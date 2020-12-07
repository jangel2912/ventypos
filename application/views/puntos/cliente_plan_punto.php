<style>
    .ui-autocomplete {
        z-index:9999 !important;
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Puntos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_puntos']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Puntos", "Puntos");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
				if($message == 'Este cliente no se puede eliminar porque tiene facturas registradas'){
				 echo "<script> alert('Este cliente no se puede eliminar porque tiene facturas registradas'); </script>";
                 }
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>            
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                if(in_array("34", $permisos) || in_array("39", $permisos) || $is_admin == 't'):?>
                <div class="col-md-6">
                <!--
                 <a id="add-new-plan" class="btn btn-success"><?php echo custom_lang('sima_new_client', "Asignar Plan de Puntos");?> </a>				 
                 <a href="<?php echo site_url("puntos/index/");?>/"  class="btn btn-success"><?php echo custom_lang('sima_new_client', "Planes de Puntos");?> </a>-->
                    <a id="add-new-plan" data-tooltip="Asignar Plan de Puntos">                        
                        <img alt="Asignar Plan de Puntos" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                    
                    </a>
                </div>
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-10">
                        <a href="<?php echo site_url("puntos/index/");?>" data-tooltip="Listado Planes Puntos">                            
                            <img alt="Listado plan punto" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['listado_plan_punto_verde']['original'] ?>">                                                           
                        </a>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_client', "Clientes Asignados a Plan de Puntos");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="clientesTable">
                        <thead>
                            <tr>
                                <th width="15%"><?php echo custom_lang('sima_name_comercial', "Cliente");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "Identificación");?></th>
                                <th width="15%"><?php echo custom_lang('sima_contact', "Plan de Puntos");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Código de Tarjeta");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Puntos acumulados");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Total redimible");?></th>
                                <th width="10%"><?php echo custom_lang('sima_nif', "Acciones");?></th>			
                            </tr>
                        </thead>
                        <tbody> </tbody>
                        <tfoot>
                            <tr>
                                <th width="15%"><?php echo custom_lang('sima_name_comercial', "Cliente");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "Identificación");?></th>
                                <th width="15%"><?php echo custom_lang('sima_contact', "Plan de Puntos");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Código de Tarjeta");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Puntos acumulados");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Total redimible");?></th>
                                <th width="10%"><?php echo custom_lang('sima_nif', "Acciones");?></th>		
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>        
    </div>
</div>

<script type="text/javascript">

    var oTable;

    $(document).ready(function(){

      oTable =  $('#clientesTable').dataTable( {

                "aaSorting": [[ 0, "desc" ]],

                "bProcessing": true,

                "bServerSide": true,

                "sAjaxSource": "<?php echo site_url("puntos/get_ajax_cliente_plan_puntos");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                      var buttons = "<div class='btnacciones'>";

                            <?php if(in_array('', $permisos) || $is_admin == 't'):?>

                                buttons += '<a  id="'+data+'" class="button default editar acciones" data-tooltip="Editar Cliente" ><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';

                             <?php endif;?>

                             <?php if(in_array('', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("puntos/eliminar_cliente_plan/");?>/'+data+'" data-tooltip="Eliminar" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';

                             <?php endif;?>
                            buttons += "</div>";
                            return buttons;

                        } 

                    }

                ]

        });


        
		
	
	  $('body').on('click','.editar',function(e){
               e.preventDefault();
               id = $(this).attr('id');
               clienteDialog.show(id);
                /*$.ajax({
                    url: "<?php echo site_url("puntos/get_datos_clientes_punto_plan")?>",
                    data: {  id: id  },
                        type: "POST",
                        success: function(response) {	   
                        $("#datos_cliente_1").val(response.nombre_comercial);  
                        $("#id_cliente_1").val(response.id_cliente); 
                        $("#plan_puntos").select2().select2('val',response.plan_id);   
                        $("#cod").val(response.codinterna);     
                        $("#id").val(response.id);    
                        }

                });	*/
		    //$( "#dialog-edit-form" ).dialog( "open" );
	 });                

         $( "#dialog-edit-form" ).dialog({

			autoOpen: false,

			width: 620,

			modal: true,

			buttons: {

				"Actualizar": function() {

                 $("#edit-form").submit();

				},

				"Cancelar": function() {

					$( this ).dialog( "close" );

				}

			},

			close: function() { }

		});


         $( "#dialog-ingresar-form" ).dialog({

			autoOpen: false,

			width: 620,

			modal: true,

			buttons: {

				"Ingresar": function() {

                 $("#ingresar-form").submit();

				},

				"Cancelar": function() {

					$( this ).dialog( "close" );

				}

			},

			close: function() { 	}

		});


         $( "#dialog-punto-form" ).dialog({

			autoOpen: false,

			width: 620,

			modal: true,

			buttons: {

				"Actualizar": function() {

                 $("#actualizar-form").submit();

				},

				"Cancelar": function() {

					$( this ).dialog( "close" );

				}

			},

			close: function() { }

		});

        var clienteDialog = clienteDialog || (function ($) {
            'use strict';
            // Creating modal dialog's DOM
            
            var $dialog = $(
            '<div id="cliente-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;"><h4><?php echo custom_lang('sima_motivo_form', "Plan de Puntos");?></h4></div>' +
                '<div class="modal-body">' +
                    '<form id="ingresar-form-new"  action="" method="POST" >'+
                    '    <div class="row-form">'+
                    '        <div class="span2"><?php echo custom_lang('sima_name_comercial', "Cliente");?>:</div>'+
                    '        <div class="span3"><input type="text" name="nombre" id="datos_cliente" class="validate[required]"/>'+
                    '                            <input type="hidden" name="id_cliente" class="id_cliente" id="id_cliente_edit" value="<?php echo set_value('id_cliente'); ?>" />'+
                    '        </div>'+
                    '    </div>'+
                    '    <div class="row-form">'+
                    '        <div class="span2"><?php echo custom_lang('sima_name_comercial', "Plan de puntos");?>:</div>'+
                    '        <div class="span3">'+
                    '<select  name="plan_puntos" id="plan_puntos" class="select" style="width: 100%" >'+
                    '        <?php foreach($data['plan_puntos'] as $f){ ?>'+
                    '         <option value="<?php echo $f->id_puntos; ?>"><?php echo  $f->nombre; ?></option>'+
                    '         <?php } ?> '+
                    '</select>'+				
                    '        </div>'+
                    '    </div>	'+
                    '    <div class="row-form">'+
                    '        <div class="span2"><?php echo custom_lang('sima_name_comercial', "Código de tarjeta");?>:</div>'+
                    '        <div class="span3"><input type="text" name="cod" id="cod" class="validate[required]"/></div>'+
                    '    </div>'+	
                    '</div>'+								
                    '<div class="modal-footer">'+
                    '<div class="pull-right"> '+
                        '<input type="button" value="Cancelar" data-dismiss="modal"  id="cancelar" class="btn btn-default"/> '+
                        '<input id="plan_puntos_cliente" type="button" value="Guardar"  class="btn btn-success"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '+
                    '</div>'+
                    '</form>'+
                '</div>' +
            '</div></div></div>');
            return {
                show:function(id){
                    
                    $dialog.find("#ingresar-form").trigger('reset');
                    $dialog.find("#id_cliente_edit").val('');
                    var editUrl = "<?php echo site_url('puntos/cliente_plan_nuevo');?>";
                    if(id != null){
                        editUrl = "<?php echo site_url('puntos/editar_cliente_plan_nuevo');?>";

                        $.ajax({
                        url: "<?php echo site_url("puntos/get_datos_clientes_punto_plan")?>",
                        data: {  id: id  },
                            type: "POST",
                            success: function(response) {
                                $dialog.find("#ingresar-form-new").attr("action", editUrl);
                                $dialog.find("#datos_cliente").val(response.nombre_comercial);  
                                $dialog.find("#id_cliente_edit").val(response.id_cliente); 
                                $dialog.find("select#plan_puntos").val(response.plan_id);
                                //$dialog.find("#plan_puntos").val(response.plan_id);   
                                $dialog.find("#cod").val(response.codinterna);     
                                $dialog.find("#id").val(response.id);    
                            }

                        });	
                    }else{
                        $dialog.find("#ingresar-form-new").attr("action", editUrl); 
                    }
                    
                    $dialog.find("#plan_puntos_cliente").click(function(e){
                        e.preventDefault();
                        //$( "#dialog-ingresar-form" ).dialog( "open" );
                        $( "#ingresar-form-new" ).submit();

                    });

                    $dialog.find("#datos_cliente").autocomplete({
                        source: "<?php echo site_url("clientes/get_ajax_clientes"); ?>",
                        minLength: 1,
                        select: function( event, ui ) {
                            $(".id_cliente").val(ui.item.id);
                        }
                    });
                    /*$.ajax({
                            async: false, //mostrar variables fuera de el function 
                            url: "<?php echo site_url("clientes/get_ajax_clientes_correo"); ?>",
                            type: "post",
                            dataType: "json",
                            data: {  idventa: id},
                            success: function(data2) {
                                $dialog.find("#correo_cliente").html(data2);  
                            }
                    });               */                                              
                

                        $dialog.modal();
                },
                hide:function(){
                        $dialog.hide();
                }
            }
        })(jQuery);

        $("#add-new-plan").click(function(){
            //$( "#dialog-ingresar-form" ).dialog( "open" );
            clienteDialog.show(null);
        });

        /*$("#cliente-form").find('#datos_cliente').autocomplete({
            source: "<?php echo site_url("clientes/get_ajax_clientes"); ?>",
            minLength: 1,
            select: function( event, ui ) {
                $(".id_cliente").val(ui.item.id);
            }
        });*/

        

        $("#open-punto").click(function(){
		   $.ajax({
               url: "<?php echo site_url("puntos/get_datos_punto_valor")?>",
                 success: function(response) {	   
				  $("#punto_val").val(response.valor_opcion);    
			    }

	        });	
		
            $( "#dialog-punto-form" ).dialog( "open" );

        });

    });


</script>


        <div id="dialog-edit-form" title="<?php echo custom_lang('sima_new_client', "Actualizar");?>">

            <div class="span6">

                <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                 <form id="edit-form"  action="<?php echo site_url('puntos/editar_cliente_plan_nuevo');?>" method="POST" >
                        <input type="hidden"  name="id" id="id"  />
                       <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Cliente");?>:</div>

                            <div class="span3"><input type="text" name="nombre" id="datos_cliente_1" class="validate[required]"/>
							                    <input type="hidden" name="id_cliente" class="id_cliente" id="id_cliente_1"  />
							</div>

                        </div>

                        <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Plan de puntos");?>:</div>

                            <div class="span3">
 						<?php 
					echo "<select  name='plan_puntos' id='plan_puntos' class='select' style='width: 100%' >";      
  						  foreach($data['plan_puntos'] as $f){
   						     echo "<option value=" . $f->id_puntos . ">" . $f->nombre . "</option>";
   						 }    
						echo "</select>";
												
						?>  							
							</div>

                        </div>	

                        <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Codigo de targeta");?>:</div>

                            <div class="span3"><input type="text" name="cod" id="cod" class="validate[required]"/></div>

                        </div>									

                </form>

            </div>

        </div>
		
		
        <div id="dialog-ingresar-form" title="<?php echo custom_lang('sima_new_client', "Asignar Plan de puntos");?>">

            <div class="span6">

                <p class="validateTips"><?php echo custom_lang('sima_all_fields', "Todos campos son requeridos");?>.</p>

                 <form id="ingresar-form"  action="<?php echo site_url('puntos/cliente_plan_nuevo');?>" method="POST" >
                        <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Cliente");?>:</div>

                            <div class="span3"><input type="text" name="nombre" id="datos_cliente" class="validate[required]"/>
							                    <input type="hidden" name="id_cliente" class="id_cliente" id="id_cliente_edit" value="<?php echo set_value('id_cliente'); ?>" />
							</div>

                        </div>

                        <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Plan de puntos");?>:</div>

                            <div class="span3">
 						<?php 
					echo "<select  name='plan_puntos' id='plan_puntos' class='select' style='width: 100%' >";      
  						  foreach($data['plan_puntos'] as $f){
   						     echo "<option value=" . $f->id_puntos . ">" . $f->nombre . "</option>";
   						 }    
						echo "</select>";
												
						?>  							
							</div>

                        </div>	

                        <div class="row-form">

                            <div class="span2"><?php echo custom_lang('sima_name_comercial', "Codigo de targeta");?>:</div>

                            <div class="span3"><input type="text" name="cod" id="cod" class="validate[required]"/></div>

                        </div>									

                </form>

            </div>

        </div>	
		