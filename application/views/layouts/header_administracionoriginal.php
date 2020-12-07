<!DOCTYPE html>

<html>

<head>

    <?php include "./application/views/analytics.php"; ?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Vendty POS">
<meta name="author" content="">
<title>Vendty POS Administrador</title>

<link rel="icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>
        <link rel="apple-touch-icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>


        <!--  OLD V1  -->

        <link href="<?php echo base_url(); ?>public/css/stylesheetsV2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/grumble/grumble.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/grumble/crumble.css" rel="stylesheet" type="text/css" />

        <?php get_css("<link rel='stylesheet' href='$1'>"); ?>
        <!--  END OLD V1  -->

        <!-- Stylesheets -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/bootstrap.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/bootstrap-extend.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/css/site.min081a.css?v2.0.0">

        <!-- Plugins -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/asScrollable.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/slidePanel.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/flag-icon-css/flag-icon.min081a.css?v2.0.0">

        <!-- Plugins For This Page -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/chartist-js/chartist.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/c3/c3.min.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/charts/flot.min.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/guia/introjs.css">

         <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url().'public/template_amazon/' ?>fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
        <link rel="stylesheet" href="<?php echo base_url().'public/template_amazon/' ?>fonts/pe-icon-7-stroke/css/helper.css" />
        <link rel="stylesheet" href="<?php echo base_url().'public/template_amazon/' ?>styles/style.css">
         <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url().'public/template_amazon/' ?>vendor/fontawesome/css/font-awesome.css" />

        <!-- Page -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/dashboard/v1.min081a.css?v2.0.0">

        <!-- Fonts -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
<?php 
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];

    foreach($gc->css_files as $file): ?>

        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

    <?php endforeach; ?>


<script src="<?php echo base_url("public/v2"); ?>/global/vendor/modernizr/modernizr.min.js"></script>
        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/breakpoints/breakpoints.min.js"></script>
        <!-- Actualizacion jquery bootstrap-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script src="<?php echo base_url('/public/js/jquery.maskMoney.js'); ?>"></script>
</head>

<body class="dashboard site-menubar-chaging site-menubar-unfold">
	<div class="v2h">	
            <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation" >
                <div style="background-color: #62cb31; height: 3px;">&nbsp;&nbsp;</div>
                <div class="navbar-header" style="cursor:pointer; background:#505050 !important; height: 60px !important;">

                    <button type="button" id="menucito" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided" data-toggle="menubar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="hamburger-bar"></span>
                    </button>

                    <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
                        <i class="icon wb-more-horizontal" aria-hidden="true"></i>
                    </button>

                    <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
                        <img onerror="ImgError(this)"  class="navbar-brand-logo" src="<?php echo base_url("public/v2/img"); ?>/logodas.fw.png" title="Vendtys" style="visibility: visible; width: 79px;height: auto;margin-left: 15px;" >
                        <img onerror="ImgError(this)"  class="navbar-brand-logo2" src="<?php echo base_url(); ?>/public/v2/img/logodas.fw.png" title="Vendtys" style="visibility: hidden; width: 79px;height: auto;margin: 0px 0px 0px -17px;">
                    </div>

                    <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-search" data-toggle="collapse">
                        <span class="sr-only">Toggle Search</span>
                        <i class="icon wb-search" aria-hidden="true"></i>
                    </button>

                </div>

                <div class="navbar-container hidden-xs">

                    <!-- Navbar Collapse -->
                    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
                        <!-- Navbar Toolbar -->
                        <ul class="nav navbar-toolbar">
                            <li class="hidden-float">                             
                            </li>
                        </ul>

                        <!-- End Navbar Toolbar -->

                        <div class="widgetsMenu">

                            <a href="<?php echo site_url(); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-home"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("Mi Empresa", "Inicio"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('submenu/ventas'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-tag"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("Impuesto", "Ventas"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('submenu/productos'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-book-2"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("terms_headers", "Productos"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('submenu/cotizacion'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-barcode-2"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("numeros", "Cotizaciones"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('informes'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-files"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("almacenes", "Informes"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('submenu/compras'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-pen-2"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("atributos", "Compras"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('submenu/contactos'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-user"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("usuarios", "Contactos"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>

                            <a href="<?php echo site_url('frontend/configuracion'); ?>" class="swidget well">
                                <div class="icon">
                                    <span class="ico-locked"></span>
                                </div>
                                <div class="bottom">
                                    <div class="text">
                                        <h5>
                                            <?php echo custom_lang("roles", "Configuración"); ?>
                                        </h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Navbar Toolbar Right -->
                        <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                            <li class="">
                                <div class="separatorBar"></div>
                            </li>

                            <a class="btnG" href="#" role="button" >
                                <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
                            </a>                          

                            <li >
                                <a class="nombreAlm" href="javascript:void(0)" role="button" style=" margin-top: 6px;">
                                    <?php getNombreAlmacenCliente(); ?>
                                </a>
                            </li>
                            <li >
                                <div class="separatorBar"></div>
                            </li>
                            <li  style=" margin-right: 0px !important;" style="height:50"> 	                              
                                <a class="btnG" href="<?php echo site_url("auth/logout"); ?>" role="button" >
                                    <i class="icon glyphicon glyphicon-off" title="Salir" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li >
                                <div class="separatorBar"></div>
                            </li>
                        </ul>
                        <!-- End Navbar Toolbar Right -->
                    </div>
                    <!-- End Navbar Collapse -->

                    <!-- Site Navbar Seach -->
                    <div class="collapse navbar-search-overlap" id="site-navbar-search">
                        <form role="search">
                            <div class="form-group">
                                <div class="input-search">
                                    <i class="input-search-icon wb-search" aria-hidden="true"></i>
                                    <input type="text" class="form-control" name="site-search" placeholder="Search...">
                                    <button type="button" class="input-search-close icon wb-close" data-target="#site-navbar-search"
                                            data-toggle="collapse" aria-label="Close"></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Site Navbar Seach -->
                </div>
            </nav>
			<div class="visible-xs">
                <div id="menucel" >
                    <link href="<?php echo base_url(); ?>public/css/menu.css" rel="stylesheet" type="text/css" />                    
                    <?php
                        include("menuadmin.php");
                    ?>                    
                </div>
            </div>
            <?php
                include("menuescritorioadmin.php");
            ?>  
	</div>
          
	<div class="page animsition" style="animation-duration: 800ms; opacity: 1;">  