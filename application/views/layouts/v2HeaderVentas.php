<?php
    
    $estado = $data['estado'];
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
        <meta name="robots" content="noindex">



        <title>Vendty POS</title>


        <link rel="icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>

        <link rel="apple-touch-icon" type="image/ico" href="<?php echo base_url(); ?>public/img/favicon.ico?act=1"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick-theme.css"/>

        <script type="text/javascript" src="<?php echo base_url(); ?>public/css/app/slick/slick.min.js"></script>


        <!--  OLD V1  -->
    <link href="<?php echo base_url();?>public/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <style>
    /*video*/
    .social {
            position: fixed; /* Hacemos que la posición en pantalla sea fija para que siempre se muestre en pantalla*/
            right: 0; /* Establecemos la barra en la izquierda */
            top: 8vh; /* Bajamos la barra 200px de arriba a abajo */
            z-index: 2000; /* Utilizamos la propiedad z-index para que no se superponga algún otro elemento como sliders, galerías, etc */
        }
 
        .social ul {
            list-style: none;
        }
 
        .social ul li a {
            display: inline-block;
            color:#fff;
            background: #000;
            padding: 10px 15px;
            text-decoration: none;
            -webkit-transition:all 500ms ease;
            -o-transition:all 500ms ease;
            transition:all 500ms ease; /* Establecemos una transición a todas las propiedades */
            border-radius: 5px 0px 0px 5px;
        }
 
        .social ul li .glyphicon-play-circle {
            font-size: 17px;
            background: #5cb85c; /* Establecemos los colores de cada red social, aprovechando su class */        
        }
    
        .social ul li a:hover {
            background: #5cd29d; /* Cambiamos el fondo cuando el usuario pase el mouse */
            padding: 10px 20px; /* Hacemos mas grande el espacio cuando el usuario pase el mouse */
        }

        #myModalvideo iframe{            
            border-radius: 5px;
            width: 50vw;
            height: 60vh;
            left: 25%;
            
        }
        #myModalvideo .modal-content {           
          /*/  background: #5cb85c;
            background: #505050;*/
            background: transparent !important;
        }
    </style>

    <!--[if lte IE 7]>

        <link href="<?php echo base_url();?>public/css/ie.css" rel="stylesheet" type="text/css" />

        <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/lte-ie7.js'></script>

    <![endif]-->

        <script src="<?php echo base_url("public"); ?>/js/plugins/jquery/jquery-1.9.1.min.js"></script>

    <script src="<?php echo base_url('/public/js/jquery.maskMoney.js'); ?>"></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-migrate-1.1.1.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/globalize.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/vendty.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/excanvas.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/jquery.mousewheel.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/cookies/jquery.cookies.2.2.0.min.js'></script>

    <script type='text/javascript' src="<?php echo base_url();?>public/js/plugins/uniform/jquery.uniform.min.js"></script>

    <script type='text/javascript' src='<?php echo base_url("public/js/plugins/validationEngine/languages/jquery.validationEngine-es.js");?>'></script>

    <script type='text/javascript' src='<?php echo base_url("public/js/plugins/validationEngine/jquery.validationEngine.js")?>'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/XRegExp.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shCore.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shBrushXml.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shBrushJScript.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shBrushCss.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins.js'></script>

        <script type='text/javascript' src='<?php echo base_url();?>public/js/actions.js'></script>

    <?php get_css("<link rel='stylesheet' href='$1'>"); ?>



        <!--  END OLD V1  -->





        <!-- Stylesheets -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/bootstrap.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/css/bootstrap-extend.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/css/site.min081a.css?v2.0.0">
        <link href="<?php echo base_url(); ?>public/css/newicons.css" rel="stylesheet" type="text/css" />



        <!-- Plugins -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/asScrollable.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/slidePanel.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/flag-icon-css/flag-icon.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.css?v2.2.0">



        <!-- Plugins For This Page -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/guia/introjs.css">

        <script src="<?php echo base_url("public/v2"); ?>/guia/intro.js"></script>



        <!-- Page -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/dashboard/v1.min081a.css?v2.0.0">





        <!-- Fonts -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">

        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
        


        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/weather-icons/weather-icons.min081a.css?v2.0.0">









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

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick.css"/>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/css/app/slick/slick.min.js"></script>

          <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("fb524507ebb7cd3bc8c139af1cf06089");</script><!-- end Mixpanel -->


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



        </script>
    <style>
        .margen10{
            margin-left: 10%;
        }

        .toast-warning1{
            /*background-color: #FBF3CD;*/
            color: #5D5A5A;
            border: 1px solid rgba(0,0,0,.1);
            border-bottom-color: rgba(0, 0, 0, 0.0980392);
            border-bottom-style: solid;
            border-bottom-width: 1px;
            padding: 10px;
            overflow-y: hidden;
            height: auto;
            background: #fff;
            border-left: 6px solid red;
            width: 90%;
            margin-left: 7.5%;
        }

        nav.site-navbar {
            height: 0px;
        }
  
    </style>

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



    <body class="dashboard site-menubar-chaging site-menubar-unfold no-padding" style="overflow: hidden !important;">

        <!--[if lt IE 8]>

              <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>

          <![endif]-->

              <!--
            <div class="toast-warning1" style="margin-top: 1%;">    
                <a id="cerrarAlertaDemo" href="javascript:cerrarPrueba();"><i class="icon wb-close" aria-hidden="true"></i></a>
                <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>    
                <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Si tiene problemas al efectuar una venta, por favor elimine la caché de su navegador presionando <strong>Ctr+F5</strong> al mismo tiempo.</span>
            </div>-->

        <?php if(  $offline == 'backup'){ ?>



            <div id="dialog-internet"  title="Conexion a Internet" style="display:none">

                <div class="">

                  <h5>¿Desea ir a la versión Offline?</h5>

                </div>

            </div>



              <script type="text/javascript">



                    function dialogSinc(){



                        $("#dialog-internet").dialog({

                              modal: true,

                              title: "Sin Conexión a Internet",

                              show: "fold",

                              buttons: [

                                  {

                                    text: "OFFLINE",

                                    icons: {

                                      primary: "ui-icon-heart"

                                    },

                                    click: function() {

                                      window.location = "<?php echo site_url(); ?>/ventasOffline/nuevo";

                                    }

                                  }

                                ]

                            });

                    }



                    $( document ).ready(function(){



                        //==================================================================

                        // SWITCH  PARA DETECTAR SI ESTAMOS CONECTADOS ONLINE

                        //==================================================================







                        window.addEventListener("offline", function (e) {

                            dialogSinc();

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

        <div class="page">

                    <div class="wrapper">

