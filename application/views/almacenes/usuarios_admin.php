<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Usuarios", "Usuarios");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_product_list', "Listado de Usuarios");?></h2>                                          
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
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('sima_all_category', "Todas los almacenes");?></h2>
            </div>
                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="productosTable">
                        <thead>
                            <tr>
                                <th ><?php echo custom_lang('name', "Nombre de la empresa");?></th>
                                <th ><?php echo custom_lang('name', "Nombre");?></th>
                                <th ><?php echo custom_lang('email', "Correo");?></th>
                                <th ><?php echo custom_lang('phone', "Teléfono");?></th>                                
                                <th ><?php echo custom_lang('created', "Fecha de creación");?></th>                        
                                <th ><?php echo custom_lang('login', "Ultimo Ingreso");?></th>
                                <th ><?php echo custom_lang('state', "Estado");?></th>
                                <th ><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th ><?php echo custom_lang('name', "Nombre de la empresa");?></th>
                                <th ><?php echo custom_lang('name', "Nombre");?></th>
                                <th ><?php echo custom_lang('email', "Correo");?></th>
                                <th ><?php echo custom_lang('phone', "Teléfono");?></th>
                                <th ><?php echo custom_lang('created', "Fecha de creación");?></th>
                                <th ><?php echo custom_lang('login', "Ultimo Ingreso");?></th>
                                <th ><?php echo custom_lang('state', "Estado");?></th>
                                <th  class="TAC"><?php echo custom_lang('sima_action', "Acciones");?></th>
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
                "aaSorting": [[2, "desc" ]],
                "bProcessing": true,
                "sAjaxSource": "<?php echo site_url("almacenes/get_ajax_data_admin_usuarios");?>",
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10, "aLengthMenu": [5,10,25,50,100],
                "aoColumnDefs" : [{ 
                    "bSortable": false, 
                    "aTargets": [ 7 ], 
                    "bSearchable": false, 
                    "mRender": function ( data, type, row ) {
                        var buttons = '<a href="<?php echo site_url("almacenes/editar_usuarios_admin/");?>/'+data+'" class="button green"><div class="icon"><span class="ico-pencil"></span></div></a>';
                        return buttons;
                    } 
                }]
        });
    });

</script>