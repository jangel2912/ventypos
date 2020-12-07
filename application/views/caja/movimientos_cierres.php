<div class="page-header">

    <div class="icon">

        <span class="ico-cabinet"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Movimientos del Cierres de Cajas");?><small><?php echo $this->config->item('site_title');?></small></h1>

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

            <?php $permisos = $this->session->userdata('permisos');

                $is_admin = $this->session->userdata('is_admin');

                if(in_array("46", $permisos) || $is_admin == 't'):?>

                

            <?php endif;?>

           

         <!--   <a href="#" class="btn" id="add-new-provider"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_provider_fast', "Nuevo proveedor(Rápido)");?></a>

             <a href="<?php echo site_url("proveedores/excel");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>

            <a href="<?php echo site_url("proveedores/import_excel");?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>-->

            <div class="head blue">

                <div class="icon"><i class="ico-group"></i></div>

                <h2><?php echo custom_lang('sima_all_provider', "Movimientos del Cierres de Cajas");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="proveedoresTable">

                        <thead>

                            <tr>
							
                                <th width="12%"><?php echo custom_lang('sima_name_comercial', "Fecha");?></th>   
								
                                <th width="12%"><?php echo custom_lang('sima_name_comercial', "Nombre caja");?></th>   															

                                <th width="12%"><?php echo custom_lang('sima_name_comercial', "Hora de movimiento");?></th>                                

                                <th width="22%"><?php echo custom_lang('sima_name_comercial', "Tipo de movimiento");?></th>

                                <th><?php echo custom_lang('sima_name_comercial', "Usuario");?></th>
								
								 <th><?php echo custom_lang('sima_name_comercial', "Valor");?></th>  
								 
								  <th><?php echo custom_lang('sima_name_comercial', "Forma de pago");?></th>

                            </tr>

                        </thead>

                        <tbody>                       

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

   <script type="text/javascript">

    var oTable;

    $(document).ready(function(){

       oTable = $('#proveedoresTable').dataTable( {

                "aaSorting": [[ 1, "asc" ]],
                "sAjaxSource": "<?php echo site_url("caja/get_ajax_data_movimientos_cierre/".$id);?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [

                    { "bSortable": false, "aTargets": [ 6 ], "bSearchable": false, 

                        "mRender": function ( data, type, row ) {

                                var buttons = "";

                                 <?php if(in_array('47', $permisos) || $is_admin == 't'):?>

                                    buttons += ''+data+'';

                                 <?php endif;?> 

                                 <?php /* if(in_array('48', $permisos) || $is_admin == 't'): 

                                    buttons += '<a href="<?php echo site_url("vendedores/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';

                                  endif; */ ?>

                            return buttons;

                        } 

                    }

                ]

        });

    });

</script>