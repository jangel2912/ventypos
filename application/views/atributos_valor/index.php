<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("categorias", "Atributos Valores");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_product_list', "Listado de Valores");?></h2>                                          
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
                <?php $permisos = $this->session->userdata('permisos');
                    $is_admin = $this->session->userdata('is_admin');
                    if(in_array("15", $permisos) || $is_admin == 't'):?>
                     <a href="<?php echo site_url("atributos_valor/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small><?php echo custom_lang('sima_new_category', "Nuevo Valor");?></a>
                <?php endif;?>
           
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('sima_all_category', "Todos los Valores");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                        <thead>
                            <tr>
                                <th width="40%"><?php echo custom_lang('sima_image', "Nombre");?></th>
                                <th width="40%"><?php echo custom_lang('sima_image', "Valor");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>
                                                    
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo custom_lang('sima_image', "Nombre");?></th>
                                <th><?php echo custom_lang('sima_image', "Valor");?></th>
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
        $('#productosTable').dataTable( {
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "<?php echo site_url("atributos_valor/get_ajax_data");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [
                    { "bSortable": false, "aTargets": [ 2 ], "bSearchable": false, 
                        "mRender": function ( data, type, row ) {
                                
                            var buttons = "";
                                <?php if(in_array('16', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php echo site_url("atributos_valor/editar/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';
                                 <?php endif;?> 
                                 <?php if(in_array('17', $permisos) || $is_admin == 't'):?>
                                    buttons += '<a href="<?php echo site_url("atributos_valor/eliminar/");?>/'+data+'" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red"><div class="icon"><span class="ico-remove"></span></div></a>';
                                 <?php endif;?>
                            return buttons;
                        } 
                    }
                ]
        });
    });
</script>