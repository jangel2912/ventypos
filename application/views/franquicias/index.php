<?php

// Obtengo permisos del usuario.
$permisos = $this->session->userdata('permisos');
// Pregunto si es administrador o no.
$is_admin = $this->session->userdata('is_admin');

?>
<div class="page-header">
    <div class="icon"><span class="ico-box"></span></div>
    <h1><?php echo custom_lang("franquicias", "Franquicias");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('franquicias_list', "Listado de franquicias");?></h2>                                        
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="block">
            
            <?php $message = $this->session->flashdata('message'); 
            if(!empty($message)):?>
            <div class="alert alert-success">
                <?php echo $message;?>
            </div>
            <?php endif; ?>
            
            <?php //if(in_array("34", $permisos) || in_array("39", $permisos) || $is_admin == 't'): ?>
            <!--<a href="<?php //echo site_url("franquicia/nuevo")?>" class="btn"><small class="ico-plus icon-white"></small><?php //echo custom_lang('sima_new_franquicia', "Nueva franquicia");?></a>-->
            <?php //endif;?>
            
            <div class="head blue">
                <div class="icon"><i class="ico-box"></i></div>
                <h2><?php echo custom_lang('franquicias_all_list', "Todas las franquicias");?></h2>
            </div>
            <div class="data-fluid">
                <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="franquiciasTable">
                    <thead>
                        <tr>
                            <th ><?php echo custom_lang('sima_name', "Nombre");?></th>
                            <th ><?php echo custom_lang('sima_email', "Email");?></th>
                            <th ><?php echo custom_lang('sima_store_name', "Nombre Tienda");?></th>
                            <th ><?php echo custom_lang('sima_phone', "Teléfono");?></th>
                            <th ><?php echo custom_lang('sima_nit', "NIT");?></th>
                            <!--<th width="10%" class="TAC"><?php //echo custom_lang('sima_action', "Acciones");?></th>-->
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th ><?php echo custom_lang('sima_name', "Nombre");?></th>
                            <th ><?php echo custom_lang('sima_email', "Email");?></th>
                            <th ><?php echo custom_lang('sima_store_name', "Nombre Tienda");?></th>
                            <th ><?php echo custom_lang('sima_phone', "Teléfono");?></th>
                            <th ><?php echo custom_lang('sima_nit', "NIT");?></th>
                            <!--<th width="10%" class="TAC"><?php //echo custom_lang('sima_action', "Acciones");?></th>-->
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'index_scripts.php'; ?>