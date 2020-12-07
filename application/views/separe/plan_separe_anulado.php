<div class="page-header">    
    <div class="icon">
        <img alt="Plan separe" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_plan_separe']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Plan Separe", "Plan Separe");?></h1>
</div>
<!--
<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_outstanding', "Listado Plan Separe Anulado");?></h2>                                          

    </div>

</div>-->
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
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');					
            ?>     

            <?php 
                if(in_array("11", $permisos) || $is_admin == 't'):?>
                <div class="col-md-6">
                    <a href="<?php echo site_url("ventas/nuevo")?>" data-tooltip="Nueva Venta">                        
                        <img alt="ventas" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                             
                    </a>                    
                </div>
            <?php endif;?>

                <div class="col-md-6 btnderecha">                    
                    <div class="col-md-2 col-md-offset-10">
                        <a href="<?php echo site_url("ventas_separe/facturas")?>" data-tooltip="Listado Plan Separe">                            
                            <img alt="Plan Separe" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['plan_separe_verde']['original'] ?>">                                                           
                        </a>
                    </div>                    
                </div>                
            </div>
        </div>
    </div> 

<div class="row-fluid">

    <div class="span12">

        <div class="block">

            <?php
                $message = $this->session->flashdata('message');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif; ?>
            <?php 
                $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');					
            ?>           

            <div class="head blue">
                <h2><?php echo custom_lang('sima_outstanding_all', "Plan separe anulados");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="ventasTable">
                        <thead>
                            <tr> 							
                                <th width="5%" ><?php echo custom_lang('sima_customer', "Consecutivo");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_user', "Creado por");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_user', "Eliminado por");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "C&eacute;dula");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_saldo', 'Fecha');?></th>
                                <th width="10%" ><?php echo custom_lang('sima_action', "Valor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Almacen");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Fecha de Vencimiento");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_action', "");?></th>
                                <th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr> 	
                                <th width="5%" ><?php echo custom_lang('sima_customer', "Consecutivo");?></th>						
                                <th width="10%" ><?php echo custom_lang('sima_customer', "Cliente");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_user', "Creado por");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_user', "Eliminado por");?></th>
                                <th width="10%"><?php echo custom_lang('sima_total_price', "C&eacute;dula");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_saldo', 'Fecha');?></th>
                                <th width="10%" ><?php echo custom_lang('sima_action', "Valor");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Almacen");?></th>
                                <th width="10%"><?php echo custom_lang('sima_action', "Fecha de Vencimiento");?></th>
                                <th width="10%" ><?php echo custom_lang('sima_action', "");?></th>
                                <th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>      
        </div>
    </div>
    
<script type="text/javascript">

    $(document).ready(function(){

        $('#ventasTable').dataTable( {

                "aaSorting": [[ 0, "desc" ]],

                "bProcessing": true,

                "sAjaxSource": "<?php echo site_url("ventas_separe/get_ajax_data_plan_separe_anulado");?>",

                "sPaginationType": "full_numbers",

                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],

                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 10 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                          
                           var buttons = "";

                            <?php if(in_array('', $permisos) || $is_admin == 't'):?>

                                buttons += '<a href="<?php echo site_url("ventas_separe/imprimir/");?>/'+data+'" target="_blank"  class="button default btn-print acciones" title="Imprimir"><div class="icon"><img alt="imprimir" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['imprimir']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['imprimir']['original'] ?>" ></div></a>';

                            <?php endif;?>	
							

                            return buttons;

                        } 

                    }

                ]

        });

              

            $('body').on('click','.anular',function(e){

                e.preventDefault();

                $("#venta_id").val($(this).attr('id'));

                $( "#dialog-motivo-form" ).dialog( "open" );

            });

            

            $( "#dialog-motivo-form" ).dialog({

			autoOpen: false,

			//height: 400,

			width: 620,

			modal: true,

			buttons: {

				"Aceptar": function() {

                                        

                                        if($("#motivo-form").length > 0)

                                        {

                                            $("#motivo-form").validationEngine('attach',{promptPosition : "topLeft"});

                                            if($("#client-form").validationEngine('validate')){

                                                $("#motivo-form").submit();

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

    });

</script>