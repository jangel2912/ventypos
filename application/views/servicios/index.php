<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Servicios", "Servicios");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_services_list', "Listado de servicios");?></h2>                                          
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
            <a href="<?php echo site_url("servicios/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small> <?php echo custom_lang('sima_new_services', "Nuevo servicio");?> </a>
            <a href="<?php echo site_url("servicios/excel");?>" class="btn"><small class="ico-circle-arrow-down icon-white"></small><?php echo custom_lang('sima_export', "Exportar a Excel");?></a>
            <a href="<?php echo site_url("servicios/import_excel");?>" class="btn"><small class=" ico-circle-arrow-up icon-white"></small><?php echo custom_lang('sima_import', "Importar excel");?></a>
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('sima_all_Services', "Todos los servicios");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="serviciosTable">
                        <thead>
                            <tr>
                                
                                <th width="30%"><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?></th>
                                <th width="10%"><?php echo custom_lang('sima_price', "Precio");?></th>
                                <th width="20%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                     
                        </tbody>
                         <tfoot>
                            <tr>
                                
                                <th><?php echo custom_lang('sima_name', "Nombre");?></th>
                                <th><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></th>
                                <th><?php echo custom_lang('sima_description', "Descripci&oacute;n");?></th>
                                <th><?php echo custom_lang('sima_price', "Precio");?></th>
                                <th><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                <th><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
             
                </div>
            </div>
            
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#serviciosTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("servicios/get_ajax_data");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 5 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                             var buttons = '<div class="btn-group"><button class="btn btn-medium dropdown-toggle" data-toggle="dropdown"><span class="ico-cog-2 icon-white">&nbsp;</span><span class="caret"></span></button><ul class="dropdown-menu">';
                                 buttons += '<li><a href="<?php echo site_url("servicios/editar/");?>/'+data+'"><span class="icon-pencil"></span>&nbsp;<?php echo custom_lang("sima_edit", "Editar") ?></a></li>';
                                 buttons += '<li class="divider"></li>';
                                 buttons += '<li><a href="<?php echo site_url("servicios/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}"><span class="icon-remove"></span>&nbsp;<?php echo custom_lang("sima_delete", "Eliminar") ?></a></li>';
                                 buttons += '</ul></div>';
                            return buttons;
                        } 
                    }
                ]
        });
    });
</script>