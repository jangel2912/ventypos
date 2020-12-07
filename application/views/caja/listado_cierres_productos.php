<div class="page-header">

    <div class="icon">

        <span class="ico-cabinet"></span>

    </div>

    <h1><?php echo custom_lang("Contactos", "Listado de Cierres de Cajas X Producto");?><small><?php echo $this->config->item('site_title');?></small></h1>

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

            <a href="<?php echo site_url("caja/imprimir_cierre_productos/".$id_cierre);?>" class="btn blue btn-print"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Imprimir Tirilla");?></a>

         <!--   <a href="#" class="btn" id="add-new-provider"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_provider_fast', "Nuevo proveedor(Rápido)");?></a>

             <a href="<?php echo site_url("proveedores/excel");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>

            <a href="<?php echo site_url("proveedores/import_excel");?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>-->

            <div class="head blue">

                <div class="icon"><i class="ico-group"></i></div>

                <h2><?php echo custom_lang('sima_all_provider', "Listado de Cierres de Cajas X Producto");?></h2>

            </div>

                <div class="data-fluid">

                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="proveedoresTable">

                        <thead>

                            <tr>

                                <th width="8%"><?php echo custom_lang('sima_name_comercial', "Fecha");?></th>                                

                                <th width="9%"><?php echo custom_lang('sima_name_comercial', "Hora Apertura");?></th>

                                <th width="9%"><?php echo custom_lang('sima_name_comercial', "Hora Cierre");?></th>
								
								 <th><?php echo custom_lang('sima_name_comercial', "Usuario");?></th>  
								 
								  <th><?php echo custom_lang('sima_name_comercial', "Caja");?></th>

                                <th><?php echo custom_lang('sima_name_comercial', "Almacen");?></th>

                                <th><?php echo custom_lang('sima_name_comercial', "Producto");?></th>

                                <th><?php echo custom_lang('sima_name_comercial', "Total Cierre");?></th>

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

                "aaSorting": [[ 7, "desc" ]],
                "sAjaxSource": "<?php echo site_url("caja/get_ajax_data_listado_cierre_productos/".$id_cierre);?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100]

        });


        $('.btn-print').fancybox({
               'width' : '50%',
               'height' : '50%',
               'autoScale' : false,
               'transitionIn' : 'none',
               'transitionOut' : 'none',
               'type' : 'iframe'
             }
           );

    });


</script>