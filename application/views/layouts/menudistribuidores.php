<nav class="navbar navbar-vendty">               
 <!--   <div class="navbar-collapse collapse" collapse="collapseMenu"> -->
        <ul class="nav navbar-nav">
            <!--<li class="hidden-xs">
                <a href="<?php echo base_url().'index.php/ventas/nuevo'; ?>">
                <img onerror="ImgError(this)"  class="navbar-brand-logo2" src="<?php echo base_url(); ?>/public/v2/img/logodas.fw.png" title="Vendtys" style="visibility: hidden; width: 79px;height: auto;margin: 0px 0px 0px -17px;">
                </a>
            </li>-->
        <?php if( in_array("11", $permisos ) || $isAdmin == 't'){ ?>
           <!-- <li>
                <a class="margen10" href="<?php echo base_url().'/administracion_vendty/distribuidores/nueva_suscripcion'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Nueva Suscripción</p>                   
                </a>
            </li>-->
            <li>
                <a class="margen10" href="<?php echo base_url().'/administracion_vendty/distribuidores/suscripciones'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Clientes</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo base_url().'/administracion_vendty/distribuidores/licencias'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Licencias</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo base_url().'/administracion_vendty/distribuidores/herramientas'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Herramientas</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo base_url().'/administracion_vendty/distribuidores/informes'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Informes</p>                   
                </a>
            </li>
            <li>
                <a class="margen10" href="<?php echo base_url().'/administracion_vendty/distribuidores/configuracion'; ?>">
                    <i class="site-menu-icons glyphicon-shopping-cart"></i>                  
                    <p class="titulomenu">Configuración</p>                   
                </a>
            </li>             
        <?php } ?>                               
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