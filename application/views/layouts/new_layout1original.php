<?php 
/*
$mostrar="";
if($data['mostrarbarra']!=1){
    $mostrar='style="visibility: hidden;"';
}*/
$resultPermisos = getPermisos(); 
$permisos = $resultPermisos["permisos"];
$isAdmin = $resultPermisos["admin"];
$ventaOnline = existeVentasOnline();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vendty POS</title>
    <link rel="icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>
    <link rel="apple-touch-icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/4e8d392ffb.js"></script>
    <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/datatables.responsive.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/jasny-bootstrap-fileinput.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/fuelux.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/reset.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/layout.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/components.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/plugins.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/default.theme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/custom.css">
    <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="<?php echo base_url();?>public/js/video.js"></script>
    <link href="<?php echo base_url(); ?>public/css/video.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/restaurante.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick-theme.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/css/app/slick/slick.min.js"></script>
				
    <script>
        var baseUrl = '<?php echo site_url();?>';
    </script>
</head>
<body class="page-session page-sound page-header-fixed page-sidebar-fixed demo-dashboard-session">
    <section id="wrapper">
        <header id="header">            
            <div class="line-green" style="background-color: #62cb31; height: 3px;">&nbsp;&nbsp;</div>            
            <div class="header-left">
                <div class="navbar-minimize-mobile left" id="menucito" >
                    <i class="fa fa-bars"></i>
                </div>
                <div class="navbar-header">
                    <a id="tour-1" class="navbar-brand hidden-xs" href="<?php echo base_url().'index.php/frontend/index'; ?>">
                        <img class="logo" src="<?php echo base_url("public/img"); ?>/logo_white.png" style="visibility: visible; width:70%; height: auto;" alt="brand logo">
                    </a>
                    <a id="tour-1" class="navbar-brand visible-xs" href="<?php echo base_url().'index.php/frontend/index'; ?>">
                        <img class="logo" src="<?php echo base_url("public/img"); ?>/logo_white.png" style="visibility: visible; width:20%; height: auto;" alt="brand logo">
                    </a>
                </div>
                <div class="navbar-minimize-mobile right">
                    <i class="fa fa-cog"></i>
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="header-right">
                
                <div class="navbar navbar-toolbar">
                    <!--ul class="nav navbar-nav navbar-left">
                        <li id="" class="navbar-minimize">
                            <a href="javascript:void(0);" title="Minimize sidebar">
                                <img src="https://www.vendty.com/invoice//public/v2/img/logo_solo.png?v2" alt="" width="70%">
                            </a>
                        </li>
                    </ul-->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="navbar-notification">
                            <!--
                            <a href="dropdown-toggle">
                                <i class="fa fa-bell-o"></i>
                                <span class="count label label-danger rounded">8</span>
                            </a>-->
                            <!--
                            <?php if((isset($offline))&&($offline != 'false')){ ?>
                                <a id="btnOffline" class="btnG" href="javascript:goBorrarOffline();" role="button" style="display:block;">
                                    <i class="glyphicon glyphicon-refresh" title="Sincronizar" aria-hidden="true" style="color:#62cb31; font-weight: bold;"></i>
                                </a>
                                <?php } ?>

                                <a id="btnAyuda" class="btnG" href="https://vendtycom.freshdesk.com/support/tickets/new" target="_blank" role="button">
                                    <i class="glyphicon glyphicon-envelope" title="Nuevo Ticket" aria-hidden="true" ></i>
                                </a>                               

                                <a class="btnG" href="<?php echo site_url("auth/logout"); ?>" role="button" >
                                    <i class="icon glyphicon glyphicon-off" title="Salir" aria-hidden="true"></i>
                                </a>
                            -->
                        </li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="visible-xs">
                <div id="menucel" >
                    <link href="<?php echo base_url(); ?>public/css/menu.css" rel="stylesheet" type="text/css" />                    
                    <?php
                        include("menu.php");
                    ?>                    
                </div>
            </div>
        <aside id="sidebar-left" class="sidebar-circle">
            <nav class="navbar navbar-vendty">                
                <div class="navbar-collapse collapse" collapse="collapseMenu">
                    <ul class="nav navbar-nav">
                    
                    <?php if( in_array("11", $permisos ) || $isAdmin == 't'){ ?>
                        <li>
                            <a href="<?php echo base_url().'index.php/ventas/nuevo'; ?>">
                                <i class="fa fa-shopping-cart"></i>
                                <span>
                                    <span>VENDER</span>
                                </span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if( in_array("10", $permisos ) || in_array("11", $permisos ) || in_array("66", $permisos ) || in_array("69", $permisos ) || in_array("27", $permisos ) || in_array("1024", $permisos ) || in_array("1037", $permisos ) || (in_array("1022", $permisos ) && $comanda["comanda"] == "si") || $isAdmin == 't'){ ?>
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-tag"></i>
                                <span>
                                    <span>VENTAS</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php //if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante'))){ ?>
                                <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1037", $permisos ) || $isAdmin == 't')){ ?>                               
                                <li>
                                    <a href="<?php echo base_url().'index.php/tomaPedidos'; ?>">
                                        <span>TOMAR PEDIDO</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if (in_array("10", $permisos) || $isAdmin == 't') { ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas'; ?>">
                                        <span>HISTORICO DE VENTAS</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("1035", $permisos ) || $isAdmin == 't'){ ?>
                                    <li>
                                        <a href="<?php echo base_url().'index.php/caja/quickCerrarCaja'; ?>">
                                            <span>CERRAR CAJA (CAJERO)</span>
                                        </a>
                                    </li>
                                <?php } ?>     
                                <?php if( in_array("1009", $permisos ) || $isAdmin == 't'){ ?>
                                    <li>
                                        <a href="<?php echo base_url().'index.php/caja/listado_cierres'; ?>">
                                            <span>CIERRE DE CAJAS</span>
                                        </a>
                                    </li>
                                <?php } ?>                                 
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas_separe/facturas'; ?>">
                                        <span>PLAN SEPARE</span>
                                    </a>
                                </li>
                                <?php if($isAdmin == 't' && $ventaOnline){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas_online/ventas'; ?>">
                                        <span>VENTAS ONLINE</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("27", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/presupuestos'; ?>">
                                        <span>COTIZACIONES</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("69", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/credito'; ?>">
                                        <span>CRÉDITOS</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("1022", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/comanda'; ?>">
                                        <span>COMANDAS</span>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if( in_array("2", $permisos ) || in_array("14", $permisos ) || in_array("67", $permisos ) || in_array("68", $permisos ) || in_array("1036", $permisos ) || $isAdmin == 't'){ ?> 
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-credit-card-alt"></i>
                                <span>
                                    <span>INVENTARIO</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if( in_array("2", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/productos'; ?>">
                                        <span>PRODUCTOS</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php //if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante'))){ ?>
                                <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante')) && (in_array("1036", $permisos ) || $isAdmin == 't')){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ProductoRestaurant'; ?>">
                                        <span>PRODUCTOS RESTAURANTE</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("14", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/categorias'; ?>">
                                        <span>CATEGORÍAS</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("67", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/caja/inventario'; ?>">
                                        <span>MOVIMIENTOS</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/lista_precios/index'; ?>">
                                        <span>LIBRO DE PRECIOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas_online/produccion'; ?>">
                                        <span>PRODUCCIÓN</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/auditoria'; ?>">
                                        <span>AUDITORÍA INVENTARIO</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                      
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-thumbs-o-up"></i>
                                <span>
                                    <span>FIDELIZACION</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                            <?php if((isset($data['tipo_negocio']))&&($data['tipo_negocio']=='moda' || $data['tipo_negocio']=='retail')){ ?>     
                                <li>
                                    <a href="<?php echo base_url().'index.php/productos/listaGiftCards'; ?>">
                                        <span>GIFT CARDS</span>
                                    </a>
                                </li>
                            <?php } ?>
                                <?php if($isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/puntos/index'; ?>">
                                        <span>PUNTOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/promociones/index'; ?>">
                                        <span>PROMOCIONES</span>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>  
                        <?php if( in_array("58", $permisos ) || in_array("70", $permisos ) || in_array("71", $permisos ) || $isAdmin == 't'){ ?>
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-briefcase"></i>
                                <span>
                                    <span>COMPRAS</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if( in_array("58", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/proformas'; ?>">
                                        <span>GASTOS</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("70", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/orden_compra'; ?>">
                                        <span>ÓRDENES DE COMPRA</span>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?> 
                    <?php if( in_array("32", $permisos ) || in_array("45", $permisos ) || in_array("62", $permisos ) || $isAdmin == 't'){ ?>
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-users"></i>
                                <span>
                                    <span>CONTACTOS</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if( in_array("32", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/clientes'; ?>">
                                        <span>CLIENTES</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("45", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/vendedores'; ?>">
                                        <span>VENDEDORES</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( in_array("62", $permisos ) || $isAdmin == 't'){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/proveedores'; ?>">
                                        <span>PROVEEDORES</span>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>    
                    <?php if( in_array("1", $permisos ) || $isAdmin == 't'){ ?>
                        <li>
                            <a href="<?php echo base_url().'index.php/informes'; ?>">
                                <i class="fa fa-line-chart"></i>
                                <span>
                                    <span>INFORMES</span>
                                </span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if( $isAdmin == 't' ){ ?>
                        <li>
                            <a href="<?php echo base_url().'index.php/frontend/configuracion'; ?>">
                                <i class="fa fa-cog"></i>
                                <span>
                                    <span>CONFIGURACIÓN</span>
                                </span>
                            </a>
                        </li>
                        <?php } ?>                     
                    </ul>   
                </div>
            </nav>           
        </aside>
        <section id="page-content">
