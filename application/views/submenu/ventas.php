<?php 

       
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];
    $comanda = getComanda(); 
    
    $ventaOnline = existeVentasOnline();
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
        <h3><?php echo custom_lang("Ventas", "Ventas"); ?></h4>
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
            
            <?php if( in_array("10", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('ventas'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Histórico Ventas"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php if( $isAdmin == 't' && $ventaOnline){ ?>
            <a href="<?php echo site_url('ventas_online/ventas'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Ventas Online"); ?>
                        </h5>
                    </div>
                </div>                                 
            </a> 
            <?php } ?>
            <?php if( in_array("27", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('presupuestos'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Cotización"); ?>
                        </h5>
                    </div>
                </div>                                 
            </a> 
            <?php } ?>
            
            <!--<?php if( in_array("11", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('ventas/nuevo'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-tags-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Nueva Venta"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>-->
            <?php if( in_array("1024", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('devoluciones/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-tags-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Devoluciones"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
           
            <?php if( in_array("69", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('credito'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Creditos"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php if( in_array("1022", $permisos ) || $isAdmin == 't'){ ?>
            <?php if( $comanda["comanda"] == "si" && $comanda["push"] == "1" ){ ?>  
            <a href="<?php echo site_url('comanda'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Comandas"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>