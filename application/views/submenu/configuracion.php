<?php 

       
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];
    
?>

<style>

    .well {        
        padding:0px !important;            
    }

    .well:hover {        
        padding:0px !important;                
    }                           
     
    
</style>

<div class="page-header">
    <div class="icon">
        <span class="ico-box"></span>
    </div>
    <h1>
        <?php echo custom_lang("Configuracion", "Configuración"); ?>
        <small>
            <?php echo $this->config->item('site_title'); ?>
        </small>
    </h1>
</div>


<div class="block title">
    <div class="head">        
    </div>
</div>



<div class="row-fluid">
    <div class="span12">
        
        <div class="widgets">
            
            <a href="<?php echo site_url('miempresa/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("Mi Empresa", "Mi Empresa"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <a href="<?php echo site_url('impuestos/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-tags-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("Impuesto", "Impuestos"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <a href="<?php echo site_url('miempresa/terms_headers'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-book-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                        <?php echo custom_lang("terms_headers", "Términos"); ?>
                        </h5>
                    </div>
                </div>                                
            </a> 

            <a href="<?php echo site_url('miempresa/numeros'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("numeros", "Números"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <a href="<?php echo site_url('almacenes/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-files"></span>
                </div>
                <div class="bottom">
                    <div class="text">        
                        <h5>                       
                            <?php echo custom_lang("almacenes", "Almacenes"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <a href="<?php echo site_url('atributos/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-pen-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>                
                            <?php echo custom_lang("atributos", "Atributos"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <?php if ($data['etienda'] == 'si') { ?> 
                <a href="<?php echo site_url('tienda/index'); ?>" class="swidget well">
                    <div class="icon">
                        <span class="ico-shopping-cart"></span>
                    </div>
                    <div class="bottom">
                        <div class="text">                  
                            <h5>                            
                                <?php echo custom_lang("tienda", "Tienda Virtual"); ?>
                            </h5>
                        </div>
                    </div>                                
                </a>
            <?php }  // aqui se envio controlador frontend(configuracion) una variable  ?>

            <a href="<?php echo site_url('usuarios/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-user"></span>
                </div>
                <div class="bottom">
                    <div class="text">                  
                        <h5>                           
                            <?php echo custom_lang("usuarios", "Usuarios"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <a href="<?php echo site_url('roles/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-locked"></span>
                </div>
                <div class="bottom">
                    <div class="text">     
                        <h5>                    
                            <?php echo custom_lang("roles", "Roles"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <?php if ($data['valor_caja'] == 'si') { ?>	
                <a href="<?php echo site_url('caja/index'); ?>" class="swidget well">
                    <div class="icon">
                        <span class="ico-locked"></span>
                    </div>
                    <div class="bottom">
                        <div class="text">              
                            <h5> 
                                <?php echo custom_lang("Cajas", "Cajas"); ?>
                            </h5>
                        </div>
                    </div>                                
                </a>
            <?php } ?>



            <a href="<?php echo site_url('cuentas_dinero/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-money"></span>
                </div>
                <div class="bottom">
                    <div class="text">               
                        <h5>
                            <?php echo custom_lang("Cuentas", "Cuentas"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            <a href="<?php echo site_url('restablecer/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-pen-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">               
                        <h5>           
                            <?php echo custom_lang("Reestablecer", "Reestablecer"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>

            
        </div>
        
    </div>
</div>