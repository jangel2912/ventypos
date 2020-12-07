<?php
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];

    $offline = getOffline();
    $comanda = getComanda();
    $ventaOnline = existeVentasOnline();
    //$offline= 'false';

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
        <meta name="robots" content="noindex">

        <title>Vendty POS</title>


        <link rel="icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>
        <link rel="apple-touch-icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>


        <!--  OLD V1  -->

        <link href="<?php echo base_url(); ?>public/css/stylesheetsV2.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/grumble/grumble.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/grumble/crumble.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/jquery/jquery.timepicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/video.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/newicons.css?<?php echo rand();?>" rel="stylesheet" type="text/css" />
        
<!-- Actualizacion jquery bootstrap-->
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script  src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"  integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="  crossorigin="anonymous"></script>
        <!--<script src="<?php echo base_url("public"); ?>/js/plugins/jquery/jquery-1.9.1.min.js"></script>-->
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
        <!--<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyD-k1mpKTbouO_3ipiL4s-JjKgnxtXVp9w'></script>-->
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
        <script src="<?php echo base_url();?>public/js/video.js"></script>       
        <script type="text/javascript" src="https://app.getbeamer.com/js/beamer-embed.js" defer="defer"></script> 
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>
        <script>

            Breakpoints();

            /*$(document).ready(function(){
                $('img').on("error", function () {
                    $(this).attr('src', '<?php echo base_url(); ?>uploads/default.png');
                    
                });
            });*/

            function ImgError(source){
                    source.src = "<?php echo base_url(); ?>uploads/default.png";
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
        
        <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("fb524507ebb7cd3bc8c139af1cf06089");</script><!-- end Mixpanel -->

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
                                <!-- Navbar Toolbar left -->
                         <!-- Navbar Toolbar left -->
                        <!-- Navbar Toolbar Right -->
                        <?php include "application/views/menu-header.php"; ?>
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
                <div id="v2Cont" class="panel">




                    <!--  OLD V1 -->

                    <div class="wrapper">
                        <div class="body" style="margin: 0px; padding: 0px;">
                            <div class="content">
