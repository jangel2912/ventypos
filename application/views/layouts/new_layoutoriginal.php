<?php 


$resultPermisos = getPermisos(); 
$permisos = $resultPermisos["permisos"];
$isAdmin = $resultPermisos["admin"];
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vendty POS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/4e8d392ffb.js"></script>
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
    
</head>
<body class="page-session page-sound page-header-fixed page-sidebar-fixed demo-dashboard-session">
    <section id="wrapper">
        <header id="header">
            
            <div class="line-green" style="background-color: #62cb31; height: 3px;">&nbsp;&nbsp;</div>            
            <div class="header-left">
                <div class="navbar-minimize-mobile left">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="navbar-header">

                    <a id="tour-1" class="navbar-brand hidden-xs" href="#">
                        <img class="logo" src="<?php echo base_url("public/img"); ?>/logo_white.png" style="visibility: visible; width:70%; height: auto;" alt="brand logo">
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
                            <a href="dropdown-toggle">
                                <i class="fa fa-bell-o"></i>
                                <span class="count label label-danger rounded">6</span>
                            </a>
                        </li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>
        </header>
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
                    <?php if( in_array("10", $permisos ) || in_array("11", $permisos ) || in_array("66", $permisos ) || in_array("69", $permisos ) || in_array("27", $permisos ) || in_array("1024", $permisos ) || (in_array("1022", $permisos ) && $comanda["comanda"] == "si") || $isAdmin == 't'){ ?>
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-tag"></i>
                                <span>
                                    <span>VENTAS</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante'))){ ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/tomaPedidos'; ?>">
                                        <span>TOMAR PEDIDO</span>
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas'; ?>">
                                        <span>HISTORICO DE VENTAS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/caja/quickCerrarCaja'; ?>">
                                        <span>CERRAR CAJA (CAJERO)</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/caja/listado_cierres'; ?>">
                                        <span>CIERRE DE CAJAS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas_separe/facturas'; ?>">
                                        <span>PLAN SEPARE</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas_online/ventas'; ?>">
                                        <span>VENTAS ONLINE</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/presupuestos'; ?>">
                                        <span>COTIZACIONES</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/credito'; ?>">
                                        <span>CREDITOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/comanda'; ?>">
                                        <span>COMANDAS</span>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    <?php } ?>
                    <?php if( in_array("2", $permisos ) || in_array("14", $permisos ) || in_array("67", $permisos ) || in_array("68", $permisos ) || $isAdmin == 't'){ ?>    
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-credit-card-alt"></i>
                                <span>
                                    <span>INVENTARIO</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?php echo base_url().'index.php/productos'; ?>">
                                        <span>PRODUCTOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ProductoRestaurant'; ?>">
                                        <span>PRODUCTOS RESTAURANTE</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="<?php echo base_url().'index.php/caja/categorias'; ?>">
                                        <span>CATEGORIAS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/caja/inventario'; ?>">
                                        <span>MOVIMIENTOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/lista_precios/index'; ?>">
                                        <span>LIBRO DE PRECIOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/ventas_online/produccion'; ?>">
                                        <span>PRODUCCION</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/auditoria'; ?>">
                                        <span>AUDITORIA INVENTARIO</span>
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
                                <li>
                                    <a href="<?php echo base_url().'index.php/productos/listaGiftCards'; ?>">
                                        <span>GIFT CARDS</span>
                                    </a>
                                </li>
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
                                <li>
                                    <a href="<?php echo base_url().'index.php/proformas'; ?>">
                                        <span>GASTOS</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/orden_compra'; ?>">
                                        <span>ORDENES DE COMPRA</span>
                                    </a>
                                </li>
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
                                <li>
                                    <a href="<?php echo base_url().'index.php/clientes'; ?>">
                                        <span>CLIENTES</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/vendedores'; ?>">
                                        <span>VENDEDORES</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url().'index.php/proveedores'; ?>">
                                        <span>PROVEEDORES</span>
                                    </a>
                                </li>
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
            <!--ul class="sidebar-menu">
                <li class="submenu">
                    <a href="#">
                        <span class="icon"><i class="fa fa-shopping-basket"></i></span>
                        <span class="text">COMIDAS</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                    <ul>
                        <li><a href="<?php echo base_url(); ?>index.php/tomaPedidos">Tomar pedido</a></li>
                    </ul>
                </li>
                <li class="submenu active">
                    <a href="#">
                        <span class="icon"><i class="fa fa-credit-card-alt"></i></span>
                        <span class="text">INVENTARIO</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/ProductoRestaurant">Productos</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Categorias</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Movimientos</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Materiales</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Libro de precios</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Producción</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Auditoria Inventario</a>
                        </li>
                    </ul>    
                </li>
                
                <li class="submenu">
                    <a href="#">
                        <span class="icon"><i class="fa fa-hand-o-right"></i></span>
                        <span class="text">FIDELIZACION</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="#">
                        <span class="icon"><i class="fa fa-briefcase"></i></span>
                        <span class="text">COMPRAS</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="#">
                        <span class="icon"><i class="fa fa-users"></i></span>
                        <span class="text">CONTACTOS</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="#">
                        <span class="icon"><i class="fa fa-line-chart"></i></span>
                        <span class="text">INFORMES</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="#">
                        <span class="icon"><i class="fa fa-cogs"></i></span>
                        <span class="text">CONFIGURACIÓN</span>
                        <span class="arrow"></span>
                        <span class="selected"></span>
                    </a>
                </li>
            </ul-->
        </aside>
        <section id="page-content">
