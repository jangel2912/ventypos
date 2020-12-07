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
        <h3><?php echo custom_lang("", "Inventario"); ?></h4>
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
            
            <?php if( in_array("2", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('productos'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Productos"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php if( in_array("14", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('categorias'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-tags-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Categorías"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php if( in_array("67", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('inventario'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-book-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                        <?php echo custom_lang("", "Inventarios"); ?>
                        </h5>
                    </div>
                </div>                                
            </a> 
            <?php } ?>
            <?php if( in_array("68", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('ingredientes'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Materiales"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            
            <a href="<?php echo site_url('lista_precios/index'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-book-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Libro de precios"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php if( $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('informes/stock_minimo_maximo'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-barcode-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Stock actual"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
        </div>
    </div>
</div>