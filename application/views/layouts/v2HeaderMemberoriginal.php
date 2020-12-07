<?php

    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];

    $offline = getOffline();
    $comanda = getComanda();
    $ventaOnline = existeVentasOnline();
?>

<!DOCTYPE html>
<html class="no-js css-menubar">

    <head>

        <?php include "./application/views/analytics.php"; ?>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="description" content="Vendty POS">
        <meta name="author" content="">

        <title>Vendty POS</title>


        <link rel="icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>
        <link rel="apple-touch-icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>


        <!--  OLD V1  -->

        <link href="<?php echo base_url(); ?>public/css/stylesheetsV2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/grumble/grumble.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/grumble/crumble.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/jquery/jquery.timepicker.css" rel="stylesheet" type="text/css" />
<!-- Actualizacion jquery bootstrap-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script  src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"  integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="  crossorigin="anonymous"></script>
        <script src="<?php echo base_url('/public/js/jquery.maskMoney.js'); ?>"></script>
        
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-migrate-1.1.1.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/globalize.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery.timepicker.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/other/excanvas.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/other/jquery.mousewheel.min.js'></script>
        
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/cookies/jquery.cookies.2.2.0.min.js'></script>
        <script type='text/javascript' src="<?php echo base_url(); ?>public/js/plugins/uniform/jquery.uniform.min.js"></script>
        <script type='text/javascript' src="<?php echo base_url(); ?>public/js/plugins/select/select2.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/jquery.grumble.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/jquery.crumble.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url("public/js/plugins/validationEngine/languages/jquery.validationEngine-es.js"); ?>'></script>
        <script type='text/javascript' src='<?php echo base_url("public/js/plugins/validationEngine/jquery.validationEngine.js") ?>'></script>
        <script type='text/javascript' src='<?php echo base_url("public/js/plugins/datatables/jquery.dataTables.min.js") ?>'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/shbrush/XRegExp.js'></script>
        <script type='text/javascript' src="<?php echo base_url(); ?>public/js/plugins/multiselect/jquery.multi-select.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/actions.js'></script>
        <script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyD-k1mpKTbouO_3ipiL4s-JjKgnxtXVp9w'></script>
        <?php get_css("<link rel='stylesheet' href='$1'>"); ?>

        <!--  END OLD V1  -->


        <!-- Stylesheets -->
        <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/bootstrap-extend.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/css/site.min081a.css?v2.0.0">

        <!-- Plugins -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/asScrollable.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/intro-js/introjs.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/slidePanel.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/flag-icon-css/flag-icon.min081a.css?v2.0.0">

        <!-- Plugins For This Page -->


        <!-- Page -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/dashboard/v1.min081a.css?v2.0.0">


        <!-- Fonts -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>




        <!--[if lt IE 9]>
          <script src="<?php echo base_url("public/v2"); ?>/global/vendor/html5shiv/html5shiv.min.js"></script>
          <![endif]-->

        <!--[if lt IE 10]>
          <script src="<?php echo base_url("public/v2"); ?>/global/vendor/media-match/media.match.min.js"></script>
          <script src="<?php echo base_url("public/v2"); ?>/global/vendor/respond/respond.min.js"></script>
          <![endif]-->

        <!-- Scripts -->
        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/modernizr/modernizr.min.js"></script>
        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/breakpoints/breakpoints.min.js"></script>
        <script>

            Breakpoints();

            $(document).ready(function(){
                $('img').on("error", function () {
                    $(this).attr('src', '<?php echo base_url(); ?>uploads/default.png');

                });
            });

            function ImgError(source){
                    source.src = "<?php echo base_url(); ?>uploads/default.png";
                    source.onerror = "";
                    return true;
            }
        var url_spanish_datatable ='<?php echo base_url().'public/js/plugins/datatables/spanish.json' ?>';    
        </script>

    </head>

    <body class="dashboard site-menubar-chaging site-menubar-unfold">
        <!--[if lt IE 8]>
              <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
          <![endif]-->


        <?php if(  $offline == 'backup'){ ?>

        <!-- MODAL CONEXION A INTERNET -->
            <div class="modal fade in" id="modalInternetCont" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" style="padding-right: 17px;">
                <div id="modalInternet" class="modal-dialog modal-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7); font-size: 28px; padding: 5px;"><i class="icon wb-alert-circle" aria-hidden="true" style="color: #555;"></i> Sin Conexion a Internet</h4>
                        </div>

                        <div class="modal-body" style="">
                            <h4> ¿Desea ir a la versión Offline? </h4>
                        </div>

                        <div class="modal-footer" style="">
                            <button id="btnGoOffline" type="button" class="btn btn-primary" style="margin: 0px 10px 0px 20px; padding: 5px 20px 5px 20px;"> Aceptar </button>
                            <button id="btnNoOffline" type="button" class="btn btn-danger" style="margin: 0px 10px 0px 20px; padding: 5px 20px 5px 20px;"> Cancelar </button>
                        </div>

                    </div>
                </div>
            </div>
        <!-- MODAL CONEXION A INTERNET -->

              <script type="text/javascript">

                    $( document ).ready(function(){

                        //==================================================================
                        // SWITCH  PARA DETECTAR SI ESTAMOS CONECTADOS ONLINE
                        //==================================================================
                        $("#btnGoOffline").click(function(){
                            window.location.href = "<?php echo site_url(); ?>/ventasOffline/nuevo/";
                        });

                        $("#btnNoOffline").click(function(){
                            $("#modalInternetCont").modal("hide");
                        });

                        function isOffline() {
                            $("#modalInternetCont").modal("show");
                            $(".modal-backdrop").css("opacity", "0.8");
                        }

                        window.addEventListener("offline", function (e) {
                            isOffline();
                        }, false);
                        //-------------------------------------------------------------------------

                        //==================================================================
                        // 	  FIN SWITCH
                        //==================================================================

                    });
        </script>
        <?php } ?>



                <div class="v2h">


                    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation" style="">
                <div style="background-color: #62cb31; height: 3px;">&nbsp;&nbsp;</div>
                <div class="navbar-header" style="cursor:pointer; background:#505050 !important; height: 60px !important;">

                    <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided" data-toggle="menubar">
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


                        <div class="navbar-container">

                            <!-- Navbar Collapse -->
                            <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
                                <!-- Navbar Toolbar -->
                                <ul class="nav navbar-toolbar">

                                    



                                    <li class="hidden-float">
                                        <!--<div class="input-group" style=" width: 300px;" >
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-outline dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

                                                    <i class="icon glyphicon glyphicon-search" aria-hidden="true" style=" font-size: 12px; margin: 0px; margin-bottom: 2px;"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li role="presentation"><a href="javascript:void(0)" role="menuitem">Productos</a></li>
                                                    <li role="presentation"><a href="javascript:void(0)" role="menuitem">Clientes</a></li>
                                                    <li role="presentation"><a href="javascript:void(0)" role="menuitem">Vendedores</a></li>
                                                    <li role="presentation"><a href="javascript:void(0)" role="menuitem">Informes</a></li>
                                                </ul>
                                            </div>
                                            <input id="buscador" type="text" class="form-control" placeholder="Buscar...">
                                        </div>-->



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
                                        <i class="icon glyphicon glyphicon-bell" aria-hidden="true"></i>
                                    </a>

                                    <!--<li class="dropdown">
                                        <a style="padding:10px 0px 0px 0px;" class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false"
                                           data-animation="scale-up" role="button">
                                            <span class="avatar avatar-online">
                                                <img onerror="ImgError(this)"  height="34" width="100" src="<?php echo base_url("uploads"); ?>/<?php getLogoAlmacen(); ?>" alt="" >
                                            </span>
                                        </a>

                                    </li>-->

                                    <li >
                                        <a class="nombreAlm" href="javascript:void(0)" role="button" style=" margin-top: 6px;">
                                            <?php getNombreAlmacenCliente(); ?>
                                        </a>
                                    </li>




                                    <li >
                                        <div class="separatorBar"></div>
                                    </li>
                                    <li  style=" margin-right: 0px !important;" style="height:50">

                                        <?php if(  $offline != 'false'){ ?>
                                        <a id="btnOffline" class="btnG" href="javascript:goBorrarOffline();" role="button" style="display:block;">
                                            <i class="glyphicon glyphicon-refresh" aria-hidden="true" style="color:#D53F26; font-weight: bold;"></i>
                                        </a>
                                        <?php } ?>

                                        <a id="btnAyuda" class="btnG" href="https://vendtycom.freshdesk.com/helpdesk/tickets/new" target="_blank" role="button">
                                            <i class="glyphicon glyphicon-envelope" aria-hidden="true" ></i>
                                        </a>

                                        <?php if(  $isAdmin == 't'){ ?>
                                        <a id="btnConfiguracion" class="btnG" href="<?php echo site_url("frontend/configuracion"); ?>" role="button">
                                            <i class="icon glyphicon glyphicon-cog" aria-hidden="true"></i>
                                        </a>
                                        <?php } ?>

                                        <a class="btnG" href="<?php echo site_url("auth/logout"); ?>" role="button" >
                                            <i class="icon glyphicon glyphicon-off" aria-hidden="true"></i>
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


                     <div class="site-menubar" style=" background-color: #505050;">
                <div class="site-menubar-body">
                    <div>
                        <div>
                            <!-- <center>
                                <div style="margin-top: 25px">
                                    <a class="nombreEmpresa" href="javascript:void(0)" role="button" style="margin-top: 16px;text-aling:center">
                                    <?php getNombreEmpresa(); ?>
                                    </a>
                                </div>
                            </center> -->
                            <!-- <div style="text-align: center; margin: 10px 0px 20px;">
                                <span class="avatarLogo avatar-onlineLogo">
                                    <img onerror="ImgError(this)" height="34" width="100" src="<?php echo base_url("uploads"); ?>/<?php getLogoAlmacen(); ?>" alt="">
                                </span>
                            </div> -->
                            <ul class="site-menu" id="dataStep1">
                                <br>
                                <!-- <li class="site-menu-item mayuscula menu-items">
                                    <a href="<?php echo site_url(); ?>">
                                        <i id="menuIconA" class="site-menu-icon wb-home" aria-hidden="true"  style="color:#f18a30;"></i>
                                        <span class="site-menu-title"><h5>Inicio</h5></span>
                                    </a>
                                </li> -->


                                <?php if( in_array("11", $permisos ) || $isAdmin == 't'){ ?>
                                <li class="site-menu-item mayuscula menu-items" style=" margin-top: 0px;">
                                    <a href="<?php echo site_url("ventas/nuevo"); ?>">
                                        <center><i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Vender</h5></span></center>
                                    </a>
                                </li>
                                <?php } ?>

                                <?php if( in_array("10", $permisos ) || in_array("11", $permisos ) || in_array("66", $permisos ) || in_array("69", $permisos ) || in_array("27", $permisos ) || in_array("1024", $permisos ) || (in_array("1022", $permisos ) && $comanda["comanda"] == "si") || $isAdmin == 't'){ ?>
                                <li class="site-menu-item has-sub  menu-items">
                                    <a href="javascript:void(0)">
                                        <center><i id="menuIconC" class="site-menu-icon glyphicon-tag" aria-hidden="true" style="color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Ventas</h5></span></center>
                                        
                                    </a>
                                    <ul class="site-menu-sub">
                                        <?php if((isset($data['tipo_negocio'])&&($data['tipo_negocio']=='restaurante'))){ ?>
                                        <li class="site-menu-item is-shown">
                                            <a class="animsition-link" href="<?php echo base_url();?>index.php/tomaPedidos">
                                                <span class="site-menu-title">TOMAR PEDIDO</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("10", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item is-shown hover">
                                            <a class="animsition-link" href="<?php echo site_url("ventas"); ?>">
                                                <span class="site-menu-title">HISTORICO DE VENTAS</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("1035", $permisos ) || $isAdmin == 't'){ ?>
                                            <li class="site-menu-item">
                                                <a class="animsition-link" href="<?php echo site_url("caja/quickCerrarCaja"); ?>">
                                                    <span class="site-menu-title">CERRAR CAJA (CAJERO)</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li class="site-menu-item is-shown">
                                            <a class="animsition-link" href="<?php echo site_url("caja/listado_cierres")?>">
                                                <span class="site-menu-title">CIERRE DE CAJAS</span>
                                            </a>
                                        </li>
                                        <li class="site-menu-item is-shown">
                                            <a class="animsition-link" href="<?php echo site_url("ventas_separe/facturas")?>">
                                                <span class="site-menu-title">PLAN SEPARE</span>
                                            </a>
                                        </li>
                                        <?php if($isAdmin == 't' && $ventaOnline){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("ventas_online/ventas"); ?>">
                                                <span class="site-menu-title">VENTAS ONLINE</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("27", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("presupuestos"); ?>">
                                                <span class="site-menu-title">COTIZACIONES</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        
                                        <?php if( in_array("69", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item" title="Créditos">
                                            <a class="animsition-link" href="<?php echo site_url("credito"); ?>">
                                                <span class="site-menu-title">CREDITOS</span>

                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("1022", $permisos ) || $isAdmin == 't'){ ?>
                                        <?php if( $comanda["comanda"] == "si" && $comanda["push"] == "1" ){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("comanda"); ?>">
                                                <span class="site-menu-title">COMANDAS</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php } ?>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if( in_array("2", $permisos ) || in_array("14", $permisos ) || in_array("67", $permisos ) || in_array("68", $permisos ) || $isAdmin == 't'){ ?>
                                <li class="site-menu-item has-sub  menu-items">
                                    <a href="javascript:void(0)">
                                        <center><i id="menuIconD" class="site-menu-icon wb-payment" aria-hidden="true" style="color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style=" color: #f9f9f9;">Inventario</h5></span></center>
                                        
                                    </a>
                                    <ul class="site-menu-sub">
                                    
                                    
                                    
                                        <?php if( in_array("2", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item is-shown hover">
                                            <a class="animsition-link" href="<?php echo site_url("productos"); ?>">
                                                <span class="site-menu-title">PRODUCTOS</span>
                                            </a>
                                        </li>
                                        <li class="site-menu-item is-shown">
                                            <a class="animsition-link" href="<?php echo base_url().'index.php/ProductoRestaurant' ?>">
                                                <span class="site-menu-title">PRODUCTO RESTAUNRANTE</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("14", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("categorias"); ?>">
                                                <span class="site-menu-title">CATEGORIAS</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("67", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("inventario"); ?>">
                                                <span class="site-menu-title">MOVIMIENTOS</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("lista_precios/index"); ?>">
                                                <span class="site-menu-title">LIBRO DE PRECIOS</span>
                                            </a>
                                        </li>
                                        
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("produccion"); ?>">
                                                <span class="site-menu-title">PRODUCCION</span> 
                                            </a>
                                        </li>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("auditoria"); ?>">
                                                <span class="site-menu-title">AUDITORIA INVENTARIO</span>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                <li class="site-menu-item has-sub  menu-items">
                                    <a href="javascript:void(0)">
                                        <center><i id="menuIconC" class="site-menu-icon glyphicon-thumbs-up" aria-hidden="true" style="color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style="color: #f9f9f9;">Fidelización</h5></span></center>
                                        
                                    </a>
                                    <ul class="site-menu-sub">
                                    
                                    
                                    
                                        <li class="site-menu-item is-shown hover">
                                            <a class="animsition-link" href="<?php echo site_url("productos/listaGiftCards"); ?>">
                                                <span class="site-menu-title">GIFT CARDS</span>
                                            </a>
                                        </li>
                                        <?php if($isAdmin == 't'){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("puntos/index"); ?>">
                                                <span class="site-menu-title">PUNTOS</span>
                                            </a>
                                        </li>

                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("promociones/index"); ?>">
                                                <span class="site-menu-title">PROMOCIONES</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if( in_array("58", $permisos ) || in_array("70", $permisos ) || in_array("71", $permisos ) || $isAdmin == 't'){ ?>
                                <li class="site-menu-item has-sub  menu-items">
                                    <a href="javascript:void(0)">
                                        <center><i id="menuIconF" class="site-menu-icon wb-briefcase" aria-hidden="true" style=" color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style="color: #f9f9f9;">Compras</h5></span></center>
                                        
                                    </a>
                                    <ul class="site-menu-sub">
                                    
                                    
                                    
                                        <?php if( in_array("58", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item is-shown hover">
                                            <a class="animsition-link" href="<?php echo site_url("proformas"); ?>">
                                                <span class="site-menu-title">GASTOS</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("70", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("orden_compra"); ?>">
                                                <span class="site-menu-title">ORDENES DE COMPRA</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if( in_array("32", $permisos ) || in_array("45", $permisos ) || in_array("62", $permisos ) || $isAdmin == 't'){ ?>
                                <li class="site-menu-item has-sub  menu-items">
                                    <a href="javascript:void(0)">
                                        <center><i id="menuIconG" class="site-menu-icon wb-users" aria-hidden="true" style="color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style="color: #f9f9f9;">Contactos</h5></span></center>
                                        
                                    </a>
                                    <ul class="site-menu-sub">
                                    
                                    
                                    
                                        <?php if( in_array("32", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item is-shown hover">
                                            <a class="animsition-link" href="<?php echo site_url("clientes"); ?>">
                                                <span class="site-menu-title">CLIENTES</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("45", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item">
                                            <a class="animsition-link" href="<?php echo site_url("vendedores"); ?>">
                                                <span class="site-menu-title">VENDEDORES</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if( in_array("62", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item" title="Informe de orden de compra">
                                            <a class="animsition-link" href="<?php echo site_url("proveedores"); ?>">
                                                <span class="site-menu-title">PROVEEDORES</span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if( in_array("1", $permisos ) || $isAdmin == 't'){ ?>
                                <li class="site-menu-item  menu-items">
                                    <a href="<?php echo site_url("informes"); ?>">
                                        <center><i id="menuIconH" class="site-menu-icon wb-graph-up" aria-hidden="true" style="color: #f9f9f9 !important;font-size:20px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style="color: #f9f9f9;">Informes</h5></span></center>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if( $isAdmin == 't' ){ ?>
                                <li class="site-menu-item  menu-items">
                                    <a href="<?php echo site_url("frontend/configuracion"); ?>">
                                        <center><i id="menuIconI" class="site-menu-icon wb-settings" aria-hidden="true"  style="color: #f9f9f9 !important;font-size:16px;padding: 10px 0px 0px 0px;"></i></br>
                                        <span class="site-menu-title mayuscula" style=" margin-top: -20px;"><h5 style="color: #f9f9f9;">Configuración</h5></span></center>
                                    </a>
                                </li>
                                <?php } ?>

                            </ul>

                        </div>
                    </div>
                </div>

            </div>


        </div>


        <!-- Page -->
        <div class="page animsition">
            <div class="page-content">
                <div id="v2Cont" class="panel">




                    <!--  OLD V1 -->

                    <div class="wrapper">
                    <div class="body" style="margin: 0px; padding: 0px;">
                            <div class="content">
