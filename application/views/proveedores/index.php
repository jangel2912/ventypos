<div class="page-header">    
    <div class="icon">
        <img alt="Proveedores" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_proveedores']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Proveedores", "Proveedores");?></h1>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
                <div class="col-md-6">
                <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                if(in_array("63", $permisos) || $is_admin == 't'):?>
                   <!-- <a href="<?php echo site_url("proveedores/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_provider', "Nuevo proveedor");?></a>
                    <a href="#" class="btn" id="add-new-provider"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_provider_fast', "Nuevo proveedor(Rápido)");?></a>-->
                     <div class="col-md-2">
                        <a href="<?php echo site_url("proveedores/nuevo")?>" data-tooltip="Nuevo Proveedor">                        
                            <img alt="Nuevo Proveedor" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                        </a> 
                    </div>   
                     <div class="col-md-2">
                        <a href="#" id="add-new-provider" data-tooltip="Nuevo Proveedor (Rápido)">                        
                            <img alt="Nuevo proveedor(Rápido)" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['proveedor_rapido_verde']['original'] ?>">                             
                        </a> 
                    </div>   
                <?php endif;?>
                </div> 
                <div class="col-md-6 btnizquierda">
                    <div class="col-md-2 col-md-offset-8">
                        <a href="<?php echo site_url("proveedores/excel")?>" data-tooltip="Exportar a Excel">                            
                            <img alt="Exportar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">                                                           
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo site_url("proveedores/import_excel")?>" data-tooltip="Importar a Excel">                       
                            <img alt="Importar a Excel" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['importar_excel_verde']['original'] ?>">                                                     
                        </a>
                    </div>
                </div>
                <!--
                <div style="float: right;">
                    <a href="<?php echo site_url("proveedores/excel");?>" class="btn default"><small class="ico-download-3 icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
                    <a href="<?php echo site_url("proveedores/import_excel");?>" class="btn default"><small class=" ico-upload-2 icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>
                </div>-->

        </div>

    </div>

</div>

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_provider', "Listado de Proveedores");?></h2>
            </div>



                <div class="data-fluid">



                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="proveedoresTable">
                        <thead>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre Comercial");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "Raz&oacute;n Social");?></th>
                                <th width="20%"><?php echo custom_lang('sima_nif', "NIF/CIF");?></th>
                                <th width="20%"><?php echo custom_lang('sima_contact', "Contacto");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Correo Electrónico");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                        <tfoot>
                            <tr>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre Comercial");?></th>
                                <th width="15%"><?php echo custom_lang('sima_reason', "Raz&oacute;n Social");?></th>
                                <th width="20%"><?php echo custom_lang('sima_nif', "NIF/CIF");?></th>
                                <th width="20%"><?php echo custom_lang('sima_contact', "Contacto");?></th>
                                <th width="15%"><?php echo custom_lang('sima_email', "Correo Electrónico");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>



<div id="dialog-provider-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-header">

    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

    <h4 style="padding:15px;" id="myModalLabel"><?php echo custom_lang('sima_new_provider', "Adicionar Proveedor");?></h4>

  </div>

  <div class="modal-body">

    <div class="span12">

        <form id="provider-form">



                <div class="row-form">



                    <div class="span2"><?php echo custom_lang('sima_name_comercial', "Nombre comercial");?>:</div>



                    <div class="span5"><input type="text" name="nombre_comercial" id="nombre_comercial" class="validate[required]"/></div>



                </div>



                <div class="row-form">



                    <div class="span2"><?php echo custom_lang('sima_email', "Correo Electrónico");?>:</div>



                    <div class="span5"><input type="text" name="email" id="email" class="validate[custom[email]]"/>







                    </div>



                </div>



                <div class="row-form">



                    <div class="span2"><?php echo custom_lang('sima_reason', "Raz&oacute;n Social");?>:</div>



                    <div class="span5"><input type="text" name="razon_social" id="razon_social" />



                    </div>



                </div>



                <div class="row-form">



                    <div class="span2"><?php echo custom_lang('sima_nif', "NIF/CIF");?>:</div>



                    <div class="span5"><input type="text" name="nif_cif" id="nif_cif" class="validate[required]"/>



                    </div>



                </div>

                <div class="row-form">

                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

                    <button class="btn btn-success"><?php echo custom_lang("sima_submit", "Guardar");?></button>

                </div>

        </form>

    </div>

  </div>

</div>

</div>



   <script type="text/javascript">



    var oTable;



    $(document).ready(function(){



        $("#provider-form").submit(function(){

            envio_formulario_proveedor_rapido();    

        });



       oTable = $('#proveedoresTable').dataTable( {



                "language": {

                    "url": url_spanish_datatable

                },



                "bProcessing": true,



                "bServerSide": true,



                "sAjaxSource": "<?php echo site_url("proveedores/get_ajax_data");?>",



                "sPaginationType": "full_numbers",



                "iDisplayLength": 5, "aLengthMenu": [5,10,25,50,100],



                "aoColumnDefs" : [



                    { "bSortable": false, "aTargets": [ 5 ], "bSearchable": false,



                        "mRender": function ( data, type, row ) {

                            console.log(data);



                            var buttons = "<div class='btnacciones'>";



                            <?php if(in_array('64', $permisos) || $is_admin == 't'):?>



                                buttons += '<a data-tooltip="Editar" href="<?php echo site_url("proveedores/editar/");?>/'+data+'" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>';



                             <?php endif;?>



                             <?php if(in_array('65', $permisos) || $is_admin == 't'):?>



                                buttons += '<a data-tooltip="Eliminar" href="<?php echo site_url("proveedores/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>';



                             <?php endif;?>

                            buttons += "</div>";

                            return buttons;



                        }



                    }



                ]



        });







        /*$( "#dialog-provider-form" ).dialog({



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



                                                        oTable.fnClearTable();



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



		});*/







        $("#add-new-provider").click(function(){



            $( "#dialog-provider-form" ).modal( "show" );



        });



    });





    function envio_formulario_proveedor_rapido(){

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



                        oTable.fnClearTable();



                        $("#dialog-provider-form").modal( "close" );



                    }



                });



            }



        }

    }



</script>

