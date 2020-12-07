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
        <h3><?php echo custom_lang("", "Contactos"); ?></h4>
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
            
            <?php if( in_array("32", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('clientes'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Clientes"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php if( in_array("45", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('vendedores'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-tags-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                            <?php echo custom_lang("", "Vendedores"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            <?php } ?>
            <?php if( in_array("62", $permisos ) || $isAdmin == 't'){ ?>
            <a href="<?php echo site_url('proveedores'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-book-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">
                        <h5>
                        <?php echo custom_lang("", "Proveedores"); ?>
                        </h5>
                    </div>
                </div>                                
            </a> 
            <?php } ?>


            
        </div>
    </div>
</div>