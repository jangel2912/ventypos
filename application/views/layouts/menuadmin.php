<nav class="navbar navbar-vendty">               
 <!--   <div class="navbar-collapse collapse" collapse="collapseMenu"> -->
        <ul class="nav navbar-nav">
            <li class="hidden-xs">
                <a href="<?php echo base_url().'index.php/ventas/nuevo'; ?>">
                <img onerror="ImgError(this)"  class="navbar-brand-logo2" src="<?php echo base_url(); ?>/public/v2/img/logodas.fw.png" title="Vendtys" style="visibility: hidden; width: 79px;height: auto;margin: 0px 0px 0px -17px;">
                </a>
            </li>        
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/empresas/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Empresas</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/formas_pago/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Formas Pago</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/planes/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Planes</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/pagos_factura/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Pagos Licencias</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/almacenes_cliente/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Almacenes</p>                   
                </a>
            </li>  
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/licencia_empresa/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Licencias</p>                   
                </a>
            </li> 
            <li>
                <a class="margen10" href="<?php echo site_url("administracion_vendty/facturas_licencia/"); ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Facturas</p>                   
                </a>
            </li>                
        </ul>   
 <!--  </div>-->
 </nav>

 <script>
    $("#menucito").click(function(e){                    
        $("#menucel").toggle();
    });
    $(".submenu").click(function(e){ 
        
        var id=$(this).attr('data-id');    
        // alert(id);                  
        $("#"+id).toggle();

        $("#"+id+" .dropdown-menu").toggle();   
        
        if ($(this).hasClass('open')){
            //alert('Si');
            $(this).removeClass("open");
        }else{
            //alert('No');
            $(this).addClass("open");
        }
        
    });
</script>