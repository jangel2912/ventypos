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
        <h3><?php echo custom_lang("", "CRM"); ?></h3>
        <small>Seguimiento a clientes</small>
    </h1>

</div>


<div class="block title">
    <div class="head">        
    </div>
</div>



<div class="row-fluid">
    <div class="span12">
        <div class="widgets">
            <a href="<?php echo site_url('crm/dashboard'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-home"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Dashboard"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            
            <a href="<?php echo site_url('crm/contactos'); ?>" class="swidget well">
                <div class="icon">
                    <span class="ico-book-2"></span>
                </div>
                <div class="bottom">
                    <div class="text">   
                        <h5>     
                            <?php echo custom_lang("", "Contactos"); ?>
                        </h5>
                    </div>
                </div>                                
            </a>
            
        </div>
    </div>
</div>