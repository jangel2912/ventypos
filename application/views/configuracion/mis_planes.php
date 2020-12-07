<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("adminisrador_licencia", "Administracion de licencia");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('gestion_licencias_pagos', "Gestion de licencias y pagos");?></h2>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="widgets">
            <?php
            $message = $this->session->flashdata('message');
            if(!empty($message)):?>

                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>

            <?php endif; ?>

            <a href="<?php echo site_url('administracion_vendty/administracion_clientes/mis_licencias');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-coins"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("mis_licencias", "Mis Licencias");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('administracion_vendty/administracion_clientes/facturas_pendientes');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text"><?php echo custom_lang("facturas_pendientes", "facturas pendientes de pago");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('administracion_vendty/administracion_clientes/mis_pagos');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text"><?php echo custom_lang("historico_pagos", "Historico de pagos");?></div>
                </div>
            </a>
        </div>
    </div>
</div>           