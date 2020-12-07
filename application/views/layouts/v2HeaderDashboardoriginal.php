<?php

    //=======================
    // ESTADOS
    //=======================
    //
    //  1 = Activo
    //  2 = Prueba y Ya completo el formulario inicial
    //  3 = Prueba y NO ha completado el formulario inicial
    //  4 = Prueba y entro por primera vez, por lo tanto envio a zoho, y google adwords
    //

    $estado = $data['estado'];

    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];

   // nunca imprimimos un dia negativo, solo 0
    $dias = $data['diasCuentaDisponibles'] <= 0 ? 0 : $data['diasCuentaDisponibles'];


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
        <!-- Actualizacion jquery bootstrap-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script src="<?php echo base_url('/public/js/jquery.maskMoney.js'); ?>"></script>

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

            $(document).on('click','#btnToggleMenu',function()
            {
                if($(this).hasClass('unfolded'))
                {
                    $('.avatar-onlineLogo').eq(0).parent().css("margin-left","5px");
                    $('.avatar-onlineLogo').eq(0).find('img').css("max-width","80px");
                    $('.nombreEmpresa').hide();
                }else
                {
                    $('.avatar-onlineLogo').eq(0).parent().css("margin-left","55px");

                    $('.avatar-onlineLogo').eq(0).find('img').css("max-width","2000px");
                    $('.nombreEmpresa').show();
                }
            });



        </script>

        <style>
        #logoAlertaDemo{
            color:red;
        }


        <?php if( $estado == "3"){ ?>
            html,body{
                overflow-y: hidden;
                background :#6a6c6f;
            }
        <?php } ?>

        <?php if( $estado == "1" ){ ?>

            .toast-warning{
                display:none;
            }

        <?php } ?>
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
        }
        .toast-warning{
            border-left: 6px solid red;
        }

        </style>

    </head>

    <body class="dashboard site-menubar-chaging site-menubar-unfold">
    <!-- ClickDesk Live Chat Service for websites -->
    <script type='text/javascript'>
        var glc =_glc || []; glc.push('all_ag9zfmNsaWNrZGVza2NoYXRyEgsSBXVzZXJzGICAoLPm-u4JDA');
        var glcpath = (('https:' == document.location.protocol) ? 'https://my.clickdesk.com/clickdesk-ui/browser/' : 
        'http://my.clickdesk.com/clickdesk-ui/browser/');
        var glcp = (('https:' == document.location.protocol) ? 'https://' : 'http://');
        var glcspt = document.createElement('script'); glcspt.type = 'text/javascript'; 
        glcspt.async = true; glcspt.src = glcpath + 'livechat-new.js';
        var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(glcspt, s);
    </script>
    <!-- End of ClickDesk -->        

        <!-- APP OFFLINE -->
	<iframe id="frameOffline" style="display: none;"></iframe>
        <!-- APP OFFLINE -->


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


                        $('document').on('mouseover','.navbar-toolbar .icon',function(){
                            $(this).parent().css("background","rgba(0,0,0,0.75)");
                        }).on('mouseover','.navbar-toolbar .icon',function(){
                            $(this).parent().css("background","rgba(0,0,0,0)");
                        });

                    });
        </script>
        <?php } ?>

        <!-- MODAL AVISO SINCRONIZACION -->
            <div class="modal fade in" id="modalSincCont" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" style="padding-right: 17px;">
                <div id="modalSinc" class="modal-dialog modal-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7); font-size: 28px; padding: 5px;">Guardando Aplicación Offline</h4>
                        </div>

                        <div class="modal-body" style="">
                            <form id="msform" accept-charset="utf-8" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <h5><span id="txtGuardandoSinc">  Guardando Aplicación...</span></h5>
                                    </div>
                                    <img onerror="ImgError(this)"  id="cargando" src="<?php echo base_url(); ?>/public/img/loaders/1d_2.gif" style="">
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer" style="">
                            <button id="btnGuardarSinc" type="button" class="btn btn-primary" style="margin: 0px 10px 0px 20px; padding: 5px 20px 5px 20px; visibility:hidden;"> Aceptar </button>
                        </div>

                    </div>
                </div>
            </div>
        <!-- MODAL AVISO SINCRONIZACION -->


        <!-- MODAL FINALIZACION PRUEBA 7 DIAS -->
            <div class="modal fade in" id="modalFinPruebaCont" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" style="padding-right: 17px;" >
                <div id="modalFinPrueba" class="modal-dialog modal-center">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7); font-size: 20px; padding: 5px; color:#AF8585;">¡TU PRUEBA HA FINALIZADO!</h4>
                        </div>
                        <div class="modal-body" style="padding:0px;">
                            <img onerror="ImgError(this)"  id="cargando" style="float:left; border-radius: 0px 0px 2px 2px;" src="<?php echo base_url(); ?>/public/img/prueba.jpg" width="600" height="300">
                            <div style="position:absolute; right: 0px; text-align: center; width: 360px">

                                <div style="font-size:16px;margin-top:30px;margin-left:20px;">Estimado <strong><?php echo $data["zoho"][0]->first_name." ".$data["zoho"][0]->last_name ?></strong> </div>
                                <div style=" font-size:17px; margin-top:20px;margin-left:20px;" ><strong>Gracias por utilizar VendTy</strong></div>
                                <div style="font-size:14px;margin-top:20px; width:250px; margin-left:80px;">Esperamos que haya sido de su agrado la prueba. Si gusta seguir disfutando de VendTy, <?php if($resultPermisos["admin"]=='t') {?>debe <strong>actualizar</strong> a un plan de pago haciendo click <strong> <a  href="<?php base_url()?>/index.php/frontend/configuracion" target="_blank">aquí</a>.</strong> <?php }else{ echo "Comunícate con el administrador del Sistema."; } ?></div>
                                <div style=" padding:0px; font-size:14px;margin-top:10px; width:260px; margin-left:75px;"><strong>Para resolver inquietudes, contáctenos:</strong></div>
                                <div style=" padding:0px; font-size:14px;margin-top:10px; width:250px; margin-left:80px; "><strong><i class="icon glyphicon glyphicon-earphone" aria-hidden="true" style="margin-right:10px;"></i></strong><strong style="font-size:16px"><a href="javascript:void(0)">+1 546 3898</a></strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- MODAL FINALIZACION PRUEBA 7 DIAS -->


<?php if( $estado == "3" && $dias > 0){ ?>


        <div id="contWizardForm" style="">

            <div class="modal fade in" id="modalWizard" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" style="padding-right: 17px;">
                <div id="modalWizard" class="modal-dialog modal-center">
                    <div class="modal-content">

                        <div class="modal-header">

                            <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7); font-size: 28px; padding: 5px;">¡Bienvenido a VendTy!</h4>


                        </div>

                        <div class="modal-body" style="">

                            <form id="msform" accept-charset="utf-8" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <span> <h4  style="color: #555;">El mejor software para administrar tu negocio, VendTy te permitirá:</h4></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-3 col-md-3 ">
                                        Generar Facturas
                                        <div style=" font-size: 46px;">
                                            <i class="icon glyphicon glyphicon-barcode" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-md-3">
                                        Controlar Inventario
                                        <div style=" font-size: 46px;">
                                            <i class="icon glyphicon glyphicon-list" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-md-3 ">
                                        Registrar Gastos
                                        <div style=" font-size: 46px;">
                                            <i class="icon glyphicon glyphicon-tags" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-md-3 ">
                                        Generar Informes
                                        <div style=" font-size: 46px;">
                                            <i class="icon wb-pie-chart" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <span> <h5> Completa el formulario:</h5></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="lineaPasos"></div>
                                    <!-- progressbar -->
                                    <ul id="progressbar">
                                        <li class="active"></li>
                                        <li class="active"></li>
                                        <li class="active"></li>
                                    </ul>


                                </div>
                                <div class="row">

                                    <div class="col-xs-4 col-md-4 contPaso">
                                        <fieldset class="well">
                                            <h3 class="fs-subtitle">Ingresa la información de tu empresa</h3>
                                            <hr>
                                            <div style=" padding: 0px 20px 0px 20px">
                                                <input id="completadoNombre" type="text" name="nombre" placeholder="Nombre Empresa" />
                                                <input id="completadoNit" type="text" name="nit" placeholder="Nit Empresa" />
                                            </div>
                                        </fieldset>

                                    </div>

                                    <div class="col-xs-4 col-md-4 contPaso">


                                        <fieldset class="well">
                                            <h3 class="fs-subtitle">Selecciona el logotipo de tu empresa</h3>
                                            <hr>


                                              <div id="contBtnLogoInput">
                                                <span id="btnLogoInput" class="btn btn-default">
                                                    <i class="icon wb-upload" aria-hidden="true"></i>
                                                </span>
                                                <input id="inputLogo" type="file" name="logo">
                                              </div>


                                            <div class="imageDrag">
                                                <img onerror="ImgError(this)"  id="previewImg" src="<?php echo base_url(); ?>/public/img/productos/product-dummy.png?v2.0">
                                            </div>

                                        </fieldset>

                                    </div>

                                    <div class="col-xs-4 col-md-4 contPaso">


                                        <fieldset class="well">
                                            <h3 class="fs-subtitle">Selecciona el tipo de factura</h3>
                                            <hr>

                                            <div class="row">
                                                <div class="col-xs-4 col-md-4" style="padding:0px">
                                                    <div class="radio-custom radio-inline">
                                                        <input type="radio" id="inputBasicFemale" name="factura"  checked="" value="ticket">
                                                        <label></label>
                                                    </div>
                                                    <div>
                                                        <label>Tirilla</label>
                                                    </div>
                                                    <div class="facturaIcon titilla"></div>

                                                </div>
                                                <div class="col-xs-4 col-md-4" style="padding:0px">
                                                    <div class="radio-custom radio-inline">
                                                        <input type="radio" id="inputBasicFemale" name="factura" value="moderna">
                                                        <label></label>
                                                    </div>
                                                    <div>
                                                        <label>Media Carta</label>
                                                    </div>
                                                    <div class="facturaIcon mediaCarta"></div>

                                                </div>
                                                <div class="col-xs-4 col-md-4" style="padding:0px">
                                                    <div class="radio-custom radio-inline">
                                                        <input type="radio" id="inputBasicFemale" name="factura" value="general">
                                                        <label></label>
                                                    </div>
                                                    <div>
                                                        <label>Carta</label>
                                                    </div>
                                                    <div class="facturaIcon carta"></div>

                                                </div>
                                        </fieldset>

                                    </div>
                                </div>




                            </form>

                            <div id="contCompletadoTxt" style="color:#DE6E6E;"><span id="completadoTxt">25</span>% Completado </div>
                            <div class="progress progress-xs margin-bottom-10">
                                <div id="completadoBar" class="progress-bar progress-bar-info bg-blue-600" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%" role="progressbar">
                                    <span class="sr-only"></span>
                                </div>
                            </div>

                        </div>



                        <div class="modal-footer" style="">
                            <span><a href="javascript:hideModalWizard();" style="color:#C77C2B;"> Saltar este paso! </a> </span>
                            <button id="btnGuardarDatosIniciales" type="button" class="btn btn-primary" style="margin: 0px 10px 0px 20px; padding: 5px 20px 5px 20px; "> ¡Empieza a usar software! </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>


<?php } ?>




        <!--[if lt IE 8]>
              <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
          <![endif]-->

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

                <div class="navbar-container hidden-xs">

                    <!-- Navbar Collapse -->
                    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
                        <!-- Navbar Toolbar -->
                        <ul class="nav navbar-toolbar">

                            <!--<li class="hidden-float" id="toggleMenubar" style="background:#f5f5f5 !important; height:50px;width:50px; border-left:1px solid #e4e5e7;">
                                <a id="btnToggleMenu" data-toggle="menubar" href="javascript:void(0)" role="button" style="margin-top: 7px !important; color:#6a6c6f !important; text-align: center; font-size:10px;">
                                    <i class="icon wb-menu" ></i>
                                </a>
                            </li>-->



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
                                <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
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
                                    <i class="glyphicon glyphicon-resize-small" title="Sincronizar" aria-hidden="true" style="color:#62cb31; font-weight: bold;"></i>
                                </a>
                                <?php } ?>

                                <a id="btnAyuda" class="btnG" href="https://vendtycom.freshdesk.com/helpdesk/tickets/new" target="_blank" role="button">
                                    <i class="glyphicon glyphicon-envelope" aria-hidden="true" ></i>
                                </a>

                                <?php if(  $isAdmin == 't'){ ?>
                                <!--<a id="btnConfiguracion" class="btnG" href="<?php echo site_url("frontend/configuracion"); ?>" role="button">
                                    <i class="icon glyphicon glyphicon-cog" aria-hidden="true"></i>
                                </a>-->
                                <?php } ?>

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


                                <?php if ( in_array("11", $permisos ) || $isAdmin == 't' ) { ?>
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
                                    
                                    

                                        <?php if (in_array("10", $permisos) || $isAdmin == 't') { ?>
                                        <li class="site-menu-item is-shown">
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
                                        <?php if( in_array("1009", $permisos ) || $isAdmin == 't'){ ?>
                                        <li class="site-menu-item is-shown">
                                            <a class="animsition-link" href="<?php echo site_url("caja/listado_cierres")?>">
                                                <span class="site-menu-title">CIERRE DE CAJAS</span>
                                            </a>
                                        </li>
                                        <?php } ?>
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
                                        <li class="site-menu-item is-shown">
                                            <a class="animsition-link" href="<?php echo site_url("productos"); ?>">
                                                <span class="site-menu-title">PRODUCTOS</span>
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
                                    
                                    
                                    
                                        <li class="site-menu-item is-shown">
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
                                        <li class="site-menu-item is-shown">
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
                                        <li class="site-menu-item is-shown">
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
        <!--                        
            <div class="toast-warning">
                <a id="cerrarAlertaDemo" href="javascript:cerrarPrueba();"><i class="icon wb-close" aria-hidden="true"></i></a>
                <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>
                <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Queda <strong><?php echo $dias; ?></strong> días de prueba. &nbsp;&nbsp; Adquiera un <strong>5%</strong> de descuento pagando <strong><a href="https://checkout.payulatam.com/ppp-web-gateway-payu//pr?dlink=0f8266M83278R6d" target="_blank">aquí</a>.</strong></span>
                <span id="msgAlertaDemo">
                    <h4 class="white">
                        <strong> ¡Quedan pocos días!</strong> Adquiere la promoción <strong>2x1</strong>, paga 1 licencia y te damos 2 o Paga 1 año y recibe 2. <strong><a href="http://vendty.com/promocion2x1.html" target="_blank">Más Información</a>.</strong>
                    </h4>
                </span>
            </div>-->
            <div class="toast-warning" style="margin-top: 1%;">    
                <a id="cerrarAlertaDemo" href="javascript:cerrarPrueba();"><i class="icon wb-close" aria-hidden="true"></i></a>
                <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>    
                <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Queda <strong><?php echo $dias; ?></strong> días de prueba. &nbsp;&nbsp;  <?php if($resultPermisos["admin"]=='t') {?>Para renovar tu licencia  <a id="a_modal_expiracion"  href="<?php base_url()?>/index.php/frontend/configuracion" >clic aquí</a> <?php }else{ echo "Comunícate con el administrador del Sistema."; } ?>  
                 <!--    <form style="display: inline-block;" method="post" action="https://gateway.payulatam.com/ppp-web-gateway/pb.zul" accept-charset="UTF-8">
                        <input type="image" border="0" alt="" src="http://www.payulatam.com/img-secure-2015/boton_pagar_pequeno.png" onClick="this.form.urlOrigen.value = window.location.href;"/>
                        <input name="buttonId" type="hidden" value="wp0HTU2FruaueuAYOXeRcbhVEhesK/t/e08IgwW3fqRJWKaOMjpRpw=="/>
                        <input name="merchantId" type="hidden" value="537208"/>
                        <input name="accountId" type="hidden" value="539236"/>
                        <input name="description" type="hidden" value="Vendty Plan Pyme 10 de Descuento"/>
                        <input name="referenceCode" type="hidden" value="001"/>
                        <input name="amount" type="hidden" value="1080000"/>
                        <input name="tax" type="hidden" value="148966"/>
                        <input name="taxReturnBase" type="hidden" value="931034"/>
                        <input name="shipmentValue" value="0" type="hidden"/>
                        <input name="currency" type="hidden" value="COP"/>
                        <input name="lng" type="hidden" value="es"/>
                        <input name="sourceUrl" id="urlOrigen" value="" type="hidden"/>
                        <input name="buttonType" value="SIMPLE" type="hidden"/>
                        <input name="signature" value="d7c28fa3a66bc26bef8e9f542de8d0bede6a2f360be0514f6b181ca792ef92fb" type="hidden"/>
                    </form> -->
                </span>
            </div>
            <?php if($estado == 1){ ?>            
                <div  class="toast-warning1" id="div_mensaje_renovacion" style="display: none; margin-top:1.2%;">    
                    <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>    
                    <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Tu licencia de prueba expirara en: <strong><?php echo $data['dias_licencia']; ?></strong> días. <?php if($resultPermisos["admin"]=='t') {?>Para renovar tu licencia  <a id="a_modal_expiracion" href="<?php base_url()?>/index.php/frontend/configuracion">clic aquí</a> <?php }else{ echo "Comunícate con el administrador del Sistema."; } ?></span>
                </div>
                
                <div id="inicialVideoDiv" style="">
                    <div class="modal fade in" id="modal_renovacion_licencia" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1" style="padding-right: 17px;">
                        <div id="modal_renovacion_licencia" class="modal-dialog modal-center">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" style=" text-align: center; color: rgba(0,0,0,0.7); font-size: 28px; padding: 5px;">Información para renovación</h4>
                                </div>
                                <div class="modal-body" >
                                    <center>
                                    <p>Tu licencia vence el <strong><?php echo $data['fecha_vencimiento']; ?></strong></p>
                                    <p>El valor de renovación es de: <h2>$<?php echo number_format($data['valor_renovacion']); ?></h2></p>
                                    <p>Colombia valor en pesos, demas paises valor en dolares</p>
                                        
                                    </center>
                                    <p>Puedes realizar tu renovación consignando el valor en las siguientes cuentas:</p>
                                    <ul>
                                        <li>Cuenta de ahorros <b>Davivienda</b>: 457500063096</li>
                                        <li>Cuenta de ahorros <b>Bancolombia</b>:  20072989822</li>
                                        <li>Cuenta de ahorros <b>Banco de Bogota</b>:  009-44301-1</li>
                                    </ul>
                                    <p>a nombre de VENDTY S.A.S Nit: 900.849.294-8</p>   
                                    <div class="alert alert-error">
                                        * Pasada la fecha de renovación si no se ha recibido el pago se bloqueara el acceso a los usuarios. 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?> 
            
            


            <div class="page-content">

            <div class="page-content">
