<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("service_detail", "Detalles de servicios");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('service_detail', "Detalles de servicios");?></h2>                                          
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
                <h2><?php echo custom_lang('service_detail', "Detalles del servicio");?></h2>
            </div>
                <div class="data-fluid">
                    <dl class="dl-horizontal">
                        <dt><?php echo custom_lang('sima_name', "Nombre");?></dt>
                        <dd><?php echo $data['nombre']?></dd>
                        <dt><?php echo custom_lang('sima_codigo', "C&oacute;digo");?></dt>
                        <dd><?php echo $data['codigo']?></dd>
                        <dt><?php echo custom_lang('sale_price', "Precio de venta");?></dt>
                        <dd><?php echo $data['precio']?></dd>
                        <dt><?php echo custom_lang('sima_description', "Descripci&oacute;n");?></dt>
                        <dd><?php echo $data['descripcion']?></dd>
                    </dl>
                </div>
            </div>
            
        </div>
    </div>