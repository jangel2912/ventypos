<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("client_detail", "Detalles del cliente");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('client_detail', "Detalles del cliente");?></h2>                                          
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
                <h2><?php echo custom_lang('client_detail', "Detalles del cliente");?></h2>
            </div>
                <div class="data-fluid">
                    <dl class="dl-horizontal">
                        <dt><?php echo custom_lang('sima_name', "Nombre");?></dt>
                        <dd><?php echo $data['nombre_comercial']?></dd>
                        <dt><?php echo custom_lang('sima_country', "Pais");?></dt>
                        <dd><?php echo $data['pais']?></dd>
                        <dt><?php echo custom_lang('sima_estado', "Provincia");?></dt>
                        <dd><?php echo $data['provincia']?></dd>
                        <dt><?php echo custom_lang('sima_reason', "Raz&oacute;n social");?></dt>
                        <dd><?php echo $data['razon_social']?></dd>
                        <dt><?php echo custom_lang('sima_nif', "NIF/CIF");?></dt>
                        <dd><?php echo $data['nif_cif']?></dd>
                        <dt><?php echo custom_lang('sima_email', "Correo electronico");?></dt>
                        <dd><?php echo $data['email']?></dd>
                        <dt><?php echo custom_lang('sima_addres', "Direcci&oacute;n");?></dt>
                        <dd><?php echo $data['direccion']?></dd>
                    </dl>
                </div>
            </div>
            
        </div>
    </div>