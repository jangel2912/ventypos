<?php

    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];

   	$offline = getOffline();
    $comanda = getComanda();
    $ventaOnline = existeVentasOnline();
    //$offline= 'false';
    
    //imagenes a utilizar    
     $cimagenes =&get_instance();
    $cimagenes->load->model('crm_imagenes_model');       
    $imagenes=$cimagenes->crm_imagenes_model->imagenes();  
    $this->session->set_userdata('new_imagenes',$imagenes);
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
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/newicons.css?<?php echo rand();?>" rel="stylesheet" type="text/css" />

        <!-- Actualizacion jquery bootstrap-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


        
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
        <script type='text/javascript' src='https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/shbrush/XRegExp.js'></script>
        <script type='text/javascript' src="<?php echo base_url(); ?>public/js/plugins/multiselect/jquery.multi-select.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins.js'></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/actions.js'></script>
        <!--<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyD-k1mpKTbouO_3ipiL4s-JjKgnxtXVp9w'></script>-->

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
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/intro-js/introjs.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/slidePanel.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/flag-icon-css/flag-icon.min081a.css?v2.0.0">

        <!-- Plugins For This Page -->


        <!-- Page -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/dashboard/v1.min081a.css?v2.0.0">


        <!-- Fonts -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>




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
                    $(this).attr('src', '<?php echo base_url(); ?>/uploads/product-dummy.png?v2.0');

                });
            });

            function ImgError(source){
                    source.src = "<?php echo base_url(); ?>/uploads/product-dummy.png";
                    source.onerror = "";
                    return true;
            }
            var url_spanish_datatable ='<?php echo base_url().'public/js/plugins/datatables/spanish.json' ?>';    
        </script>

        <?php if(isset($data["estado"]) && ($data["estado"] == 2 )){?> 
            <!-- video Hotjar Tracking Code for http://pos.vendty.com --> 
            <script>
                (function(h,o,t,j,a,r){
                    h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                    h._hjSettings={hjid:983042,hjsv:6};
                    a=o.getElementsByTagName('head')[0];
                    r=o.createElement('script');r.async=1;
                    r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                    a.appendChild(r);
                })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
            </script>
        <?php } ?>

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
                            <li class="li-user">
                                <a class="btnGis linkicon" href="javascript:void(0)" role="button">
                                    <img alt="user" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['cliente_gris']['original'] ?>">
                                    <?php getNombreAlmacenCliente(); ?>
                                </a>
                            </li>                               
                            <?php if(  $offline != 'false'){ ?>
                            <li>                                    
                                <a id="btnOffline" class="btnG linkicon" href="javascript:goBorrarOffline();" role="button">
                                    <img title="Sincronizar" alt="Sincronizar" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['sincronizacion_verde']['original'] ?>">                                            
                                    Sincronización
                                </a>
                            </li>
                            <?php } ?>
                            <li>                                            
                                <a id="btnAyuda" class="btnG linkicon" href="https://vendtycom.freshdesk.com/support/tickets/new" target="_blank" role="button">
                                    <img title="Nuevo Ticket" alt="Nuevo Ticket"  class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['ticket_verde']['original'] ?>">                                                                             
                                    Ticket de soporte
                                </a>  
                            </li> 
                                                        
                            <li data-toggle="tooltip" data-placement="bottom" title="Ayuda" class="li-medios">                                       
                                <a  id="btnAyuda" class="btnG " href="https://ayuda.vendty.com/" target="_blank" role="button">
                                    <img title="Ayuda" alt="ayuda"  class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['ayuda_verde']['original'] ?>">                                                                                                                     
                                </a>  
                            </li> 
                            
                            <li class="li-salir">                                    
                                <a class="btnB linkicon" href="<?php echo site_url("auth/logout"); ?>" role="button" >
                                    <img style="width: 25%;" title="Salir" alt="Salir" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['salir_blanco']['original'] ?>">
                                    Salir
                                </a>
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
                        include("menu.php");
                    ?>                    
                </div>
            </div>
            <?php
                include("menuescritorio.php");
            ?>  
        </div>
        <!-- Page -->
        <div class="page animsition">
            <div class="page-content">
                <div id="v2Cont" class="">
                    <!--  OLD V1 -->

                    <div class="wrapper">
                        <div class="body" style="margin: 0px; padding: 0px;">
                            <div class="content">
