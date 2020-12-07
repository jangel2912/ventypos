<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1><?php echo custom_lang("Configuracion", "Configuración");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_config_option', "Configurar todas las opciones");?></h2>
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

            <a href="<?php echo site_url('configuracion/mis_planes');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-coins"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("mis_planes", "Mis Pagos");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('miempresa/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text"><?php echo custom_lang("Mi Empresa", "Mi Empresa");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('impuestos/index');?>" class="swidget orange">
                <div class="icon">
                    <span class="ico-tags-2"></span>
                </div>
                <div class="bottom">
                    <div class="text"><?php echo custom_lang("Impuesto", "Impuestos");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('miempresa/terms_headers');?>" class="swidget green">
                <div class="icon">
                    <span class="ico-book-2"></span>
                </div>
                <div class="bottom">
                    <div class="text" style="line-height: 13px;"> Términos<br>Cabecera</div>
                </div>
            </a>
            <a href="<?php echo site_url('miempresa/numeros');?>" class="swidget purple">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("numeros", "Números");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('almacenes/index');?>" class="swidget dblue">
                <div class="icon">
                    <span class="ico-files"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("almacenes", "Almacenes");?></div>
                </div>
            </a>
            <!-- BOTON AZUL DE ATRIBUTOS -->
            <?php if($data['atributos']): ?>
                <a href="<?php echo site_url('atributos/index'); ?>" class="swidget blue">
                    <div class="icon">
                        <span class="ico-pen-2"></span>
                    </div>
                    <div class="bottom">
                        <div class="text"> <?php echo custom_lang("atributos", "Atributos"); ?></div>
                    </div>
                </a>
                <!--<a href="<?php echo site_url('atributos_valor/index');?>" class="swidget blue">
                    <div class="icon">
                        <span class="ico-list-alt"></span>
                    </div>
                    <div class="bottom">
                        <div class="text"> <?php echo custom_lang("atributos", "Atributos Valor");?></div>
                    </div>
                </a>-->
            <?php endif; ?>
            <?php if($data['etienda']=='si'){ ?> 
            <a href="<?php echo site_url('tienda/index');?>" class="swidget purple">
                <div class="icon">
                    <span class="ico-shopping-cart"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("tienda", "Tienda Virtual");?></div>
                </div>
            </a>
            <?php   }  // aqui se envio controlador frontend(configuracion) una variable ?>
            <a href="<?php echo site_url('usuarios/index');?>" class="swidget yellow">
                <div class="icon">
                    <span class="ico-user"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("usuarios", "Usuarios");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('roles/index');?>" class="swidget red">
                <div class="icon">
                    <span class="ico-locked"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("roles", "Roles");?></div>
                </div>
            </a>
            <?php if($data['valor_caja']=='si'){ ?> 
            <a href="<?php echo site_url('caja/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-cabinet"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("Cajas", "Cajas");?></div>
                </div>
            </a>
            <?php } ?>
            <a href="<?php echo site_url('cuentas_dinero/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-money"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("Cuentas", "Cuentas");?></div>
                </div>
            </a>
            <a href="<?php echo site_url('forma_pago/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-dollar-2"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("Formas de pago", "Formas de pago");?></div>
                </div>
            </a>
            
             <a href="<?php echo site_url('mesas_secciones/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-glass"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("mesas", "Mesas");?></div>
                </div>
            </a> 
            
            <a href="<?php echo site_url('configuracion/carga_de_datos');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-file"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("Importación", "Importación");?></div>
                </div>
            </a>
            <!--
            <a href="<?php echo site_url('promociones/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-pig"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("Promociones", "Promociones");?></div>
                </div>
            </a>-->
            <?php if($data['mexico']){ ?>
               <a href="<?php echo site_url('factura_electronica/index');?>" class="swidget blue">
                    <div class="icon">
                        <span class="ico-stop"></span>
                    </div>
                    <div class="bottom">
                        <div class="text"> <?php echo custom_lang("factura_eletronica", "Factura electronica");?></div>
                    </div>
                </a> 
            <?php } ?>
            <?php if($data['franquicias']) {?>
            <a href="<?php echo site_url('franquicias/index');?>" class="swidget blue">
                <div class="icon">
                    <span class="ico-stop"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("franquicias", "Franquicias");?></div>
                </div>
            </a>
            <?php } ?>
            <a href="<?php echo site_url('restablecer/index');?>" class="swidget orange">
                <div class="icon">
                    <span class="ico-off"></span>
                </div>
                <div class="bottom">
                    <div class="text"> <?php echo custom_lang("Reestablecer", "Reiniciar sistema");?></div>
                </div>
            </a>
        </div>
    </div>
</div>
