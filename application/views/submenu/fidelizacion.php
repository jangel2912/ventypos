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
        <h3><?php echo custom_lang("Ventas", "Fidelización"); ?></h4>
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
            <a href="<?php echo site_url('productos/listaGiftCards'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "GiftCards"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            
            <?php if( $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('puntos/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Puntos"); ?>
                        </h5>
                    </div>
                </div>                                 
            </a> 
            <a href="<?php echo site_url('promociones/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-pig"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Promociones"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
        </div>
    </div>
</div>