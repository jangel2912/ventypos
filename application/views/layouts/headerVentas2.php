<?php



    $resultPermisos = getPermisos();

    $permisos = $resultPermisos["permisos"];

    $isAdmin = $resultPermisos["admin"];







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





    <link href="<?php echo base_url();?>public/css/stylesheets.css" rel="stylesheet" type="text/css" />

    <!--[if lte IE 7]>

        <link href="<?php echo base_url();?>public/css/ie.css" rel="stylesheet" type="text/css" />

        <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/lte-ie7.js'></script>

    <![endif]-->

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-1.9.1.min.js'></script>

    <script src="<?php echo base_url('/public/js/jquery.maskMoney.js'); ?>"></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-migrate-1.1.1.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/globalize.js'></script>

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



        <!-- Plugins -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/asScrollable.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/slidePanel.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/vendor/flag-icon-css/flag-icon.min081a.css?v2.0.0">



        <!-- Plugins For This Page -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/guia/introjs.css">

        <script src="<?php echo base_url("public/v2"); ?>/guia/intro.js"></script>



        <!-- Page -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/dashboard/v1.min081a.css?v2.0.0">





        <!-- Fonts -->

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">

        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">

        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>



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

        <script>



            Breakpoints();



        </script>



        <style>



            .panel, .widget{

                border: 1px solid rgba(0, 0, 0,0.15) !important;

            }

            .page {

                background: rgb(240,240,240) !important;

            }



            .body .panel{

                overflow: auto;

                overflow-x: hidden;

            }



            .site-menu{

                margin-top: 10px !important;

            }



            #p3chat-launcher-float{

                top:auto !important;

                bottom: 10px !important;

            }



            .site-menubar-unfold .site-menu>.site-menu-item>a {

                padding: 0 20px !important;

                line-height: 40px !important;

            }



            #toggleMenubar a{

                padding-left: 0px;

                padding-right: 0px;

            }



            #toggleMenubar i{

                font-size: 17px;

            }



            .panel-body {

                padding: 10px 10px;

            }



            .site-footer{

                text-align: right;

            }



            .tableCont {

                width: 100%;

                display:table;

                table-layout: fixed;

                overflow: hidden;

            }



            .cellIzq {

                display:table-cell;

                width: 20px;

                overflow: hidden;

            }



            .cellDer {

                display:table-cell;

                width: 100%;

                overflow: hidden;

            }

            .cellContent {

                width: 100%;

                height: 100%;

                float: left;

            }



            .page-content{

                padding: 20px;

            }



            .panel{

                padding: 10px;

                margin-bottom: 20px;

                box-shadow: 0 2px 6px -3px rgba(0,0,0,0.1) !important;

            }





            .page-header .h1,

            .page-header .h2,

            .page-header .h3,

            .page-header .h4,

            .page-header .h5,

            .page-header .h6,

            .page-header h1,

            .page-header h2,

            .page-header h3,

            .page-header h4,

            .page-header h5,

            .page-header h6 {

                font-family: Roboto,sans-serif;

                font-weight: 400;

                line-height: 1.2;

                margin: 0px;

                margin-top: 4px;

            }



            hr{

                border-color: #eee;

                margin: 0px;

                margin-bottom: 8px;

            }



            #site-navbar-collapse ul,#site-navbar-collapse ol{

                margin: 0px;

            }





            /* ====================================== */

            /*      MENUBAR

            /* ====================================== */

            #p3chat-launcher-float{

                display: none !important;

            }

            body{

                padding-top: 40px !important;

            }

            .site-menubar{

                top: 40px !important;

            }

            .navbar-avatar .avatar{

                width: auto !important;

                height: 34px;

            }



            .avatar{ margin-top: 3px;}

            .avatar img{

                border-radius: 2px;

                max-width:200px;

                max-height:34px;

                width:auto;

                height:auto;

            }

            .avatar{

                height: 34px;

            }



            .navbar-collapse a{

                padding: 0px !important;

                margin-left: 0px;

                margin-right: 0px;

            }

            .navbar-collapse li{

                margin-right: 15px;

            }

            .navbar-toolbar .glyphicon{ font-size: 14px; }

            .navbar-toolbar .webIcon{ font-size: 14px !important; font-weight: bold !important; }

            .navbar-toolbar .icon:hover{ color:rgba(0,0,0,0.75)}



            .navbar-toolbar .btnG{

                margin-top: 13px !important;

                float: left;

                margin-right: 15px;

            }



            .wb-menu{

                font-weight: bold;

                margin-top: 10px;

            }



            .contBtnBar{

                padding-left: 15px;

                background-color: rgba(0,0,0,0.025);

                height: 40px;

                box-shadow: 0px -1px 10px -2px rgba(0,0,0,0.5) !important;

            }



            .nombreAlm{

                margin-top: 10px !important;

                font-weight: 200;

                color: #333;

            }



            nav.site-navbar{

                min-height: 40px !important;

                /*margin-bottom: 22px;*/

            }

            .navbar-brand{

                padding:0px;

            }



            /* imagen logo */

            .navbar-brand-logo{

                height:30px;

                margin-top:5px;

                margin-left: 35px;

            }



            .navbar-brand-logo2{

                position: absolute;

                height:30px;

                width:30px;

                top: 5px;

                left: 32px;

            }



            .navbar-header > button, .navbar-header > div{

                height: 40px !important;

            }



            .separatorBar{

                height: 40px;

                width: 1px;

                border-left: rgba(0,0,0,0.15) solid 1px;

            }



            .input-group{

                margin-top: 8px;

            }

            .input-group button{



                padding-top: 0px !important;

                padding-bottom: 0px !important;

                border-color: #C9CBCC !important;

                border-right-color: transparent !important;

            }

            .input-group input{

                border: 1px solid #CCCFD0;

                background-color: #F7F7F7 !important;

                padding-top: 0px !important;

                padding-bottom: 0px !important;

                height: 24px;

            }

            .btn.disabled, .open .btn.dropdown-toggle {

                background-color: #CBD9DE !important;

            }



            /* ====================================== */

            /* MENU BOTONES PEQUEÑOS */

            /* ====================================== */





            /* ====================================== */

            /* PRE */



            .widgetsMenu{

                display:none;

            }



            .widgetsMenu{

                width: auto !important;

                float: left !important;

                margin: 5px 0px 0px 15px;

            }



            .widgetsMenu .swidget

            {

                -webkit-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                -moz-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                transition: box-shadow 0.1s linear !important;



                display: inline-block !important;

                position: relative !important;

                text-decoration: none !important;

                margin: 0px !important;

                padding: 0px !important;

                background-color: #F6F6F6 !important;



            }



            .widgetsMenu .swidget .icon {

                border: 3px solid #62CB31 !important;

                transition: border 0.2s linear !important;

            }

            .widgetsMenu .swidget .icon [class^="ico-"]

            {

                color: #62CB31 !important;

                transition: color 0.2s linear !important;

            }



            .widgetsMenu .swidget

            {

                -webkit-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                -moz-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                transition: box-shadow 0.1s linear !important;

            }



            .widgetsMenu .swidget h5{

                color: #5E7882 !important;

                text-align: center !important;

            }



            /* BOTONES HOVER */



            .widgetsMenu .swidget:hover .icon {

                border: 3px solid #5E7882 !important;

            }

            .widgetsMenu .swidget:hover .icon [class^="ico-"],

            .widgetsMenu .swidget:hover h5

            {

                color: #5E7882 !important;

            }



            .widgetsMenu .swidget:hover

            {

                -webkit-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                -moz-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75) !important;

                box-shadow: 0px 4px 15px -4px rgba(0,0,0,0.75) !important;

            }





            /* ====================================== */

            /* Configuracion                          */







            .widgetsMenu{

                height: 58px !important ;

            }



            .widgetsMenu .swidget

            {

                box-shadow: 0px 2px 5px -4px rgba(0,0,0,0.5) !important ;

                width: 80px !important ;

                min-width: 80px !important ;

                height: 55px !important ;

                margin: 0px 5px 0px 0px !important ;

            }



            .widgetsMenu .swidget .icon {

                border: none !important;

                margin: 0px !important;

                width: 100% !important;

                min-width: max-content !important;

                height: 25px !important;

                line-height: 40px !important;

                text-align: center !important;

                margin-top: 5px !important;



            }

            .widgetsMenu .swidget .bottom {

                height: 20px !important;

                width: 100% !important;

                min-width: max-content !important;

                margin: 0px !important;

                margin-top: 0px !important;

                box-sizing: border-box !important;

            }



            .widgetsMenu .swidget .text,

            .widgetsMenu .swidget .text h5{

                margin: 0px !important;

                padding: 0px !important;

                height: 10px !important;

            }



            .widgetsMenu .swidget .icon [class^="ico-"]{

                font-size: 22px !important;

                line-height: 0px !important;

                height: 20px !important;

            }





            .widgetsMenu .swidget .icon [class^="ico-"]

            {

                color: #62CB31 !important;

                text-align: center !important;

                transition: color 0.2s linear !important;

            }



            .widgetsMenu .swidget h5{

                font-size: 11px !important;

            }





            /* BOTONES HOVER */



            .widgetsMenu .swidget:hover .icon {

                border: none !important;

            }

            .widgetsMenu .swidget:hover .icon [class^="ico-"],

            .widgetsMenu .swidget:hover h5

            {

                color: #5E7882 !important;

            }



            .widgetsMenu .swidget:hover{

                box-shadow: 0px 2px 5px -3px rgba(0,0,0, 0.6) !important;

            }



            /* ====================================== */

            /*     FIN MENU BOTONES PEQUEÑOS          */

            /* ====================================== */







            /* ============================================================================ */

            /*                               REESCRIBIR OLD V1                              */

            /* ============================================================================ */



            .page-content{

                padding-top: 6px;

            }



            input[type="number"]{

                height: auto ;

            }



            #logoCont{

                top:0px;

                left:0px;

                width: 90px;

                height: 45px;

                background-color: #62CB31;

                position: fixed;

            }



            body{

                padding-top: 0px !important;

            }



            .site-menubar{

                display: none;

                top: 45px !important;

            }



            .toolbar, .toolbar-fluid {

                float: left;

                clear: both;

            }



            .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {

                font-family: Roboto,sans-serif !important;

                color: #696969 !important;

            }



            .page-header{

                padding:4px;

                display: inline-block;

            }

            .page-header h1{

                font-size: 18px;

            }



            .table a {

                text-decoration: none;

            }



            /* ====================================== */

            /* BOTONES */



            .body .content .swidget .icon {

                border: 3px solid #62CB31;

                transition: border 0.2s linear;

            }

            .body .content .swidget .icon [class^="ico-"]

            {

                color: #62CB31;

                transition: color 0.2s linear;

            }

            .swidget h5{

                color: #5E7882 !important;

                text-align: center !important;

            }



            .body .content .swidget

            {

                -webkit-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75);

                -moz-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75);

                box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75);

                transition: box-shadow 0.1s linear;

            }



            /* BOTONES HOVER */



            .body .content .swidget:hover .icon {

                border: 3px solid #5E7882;

            }

            .body .content .swidget:hover .icon [class^="ico-"]

            {

                color: #5E7882;

            }



            .body .content .swidget:hover

            {

                -webkit-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75);

                -moz-box-shadow: 0px 3px 10px -4px rgba(0,0,0,0.75);

                box-shadow: 0px 4px 15px -4px rgba(0,0,0,0.75);

            }







            /*====================================== */



            /* Para los titulos largos en los botones*/

            .dobleTit{

                font-size: 14px;

                margin-top: -1px;

                line-height: 13px;

            }



            .bg-blue-600 {

                background-color: #79B4D6!important;

            }



            .list-group-item-heading .pull-right{

                color: #DE8E30;

            }



            .site-navbar {



                background-color: #62CB31 !important;



            }



            .well {

                background-color: #F6F6F6;

                display: flex;

            }



            .widgets .blue, .widgets .green, .widgets .yellow, .widgets .orange, .widgets .red, .widgets .dblue, .widgets .purple{

                background: #EBEEF1 !important;

                border-radius: 5px;

            }









            .head.blue {

                background: #A2B0B7 !important;

            }

            .btn-primary{

                border-color: transparent;

            }





            /*  BOTONES  */



            .btn.focus, .btn:focus, .btn:hover{

                color:#fff;

            }



            .btn{

                background: #88BF6C !important;

                border-color: transparent !important;

                padding:5px 10px;

                margin-bottom: 4px;

            }

            .btn-warning {

                color: #fff;

                background-color: #f2a654 !important;

                border-color: transparent !important;

            }

            .btn-success {

                color: #fff;

                background-color: #46be8a !important;

                border-color: transparent !important;

            }





            /*  BOTONES HOVER  */

            .btn:hover{

                background: #6E9E55 !important;

            }

            .btn-success:hover {

                background-color: #349C6F !important;

            }

            .btn-warning:hover {

                background-color: #C77C2B !important;

            }



            /*  FIN BOTONES */



            .bottom .text {

                width: 100%;

            }



            /* Botones DataTables */

            /* Fondo */

            table .button{

                background: rgba(0,0,0,0.0) !important;

                width: 22px !important;

                height: 22px !important;

                margin-right: 3px;

            }

            /* Botones Circulos */

            table .button .icon{

                border: transparent !important;

                margin: 0px !important;

            }



            /* Botones Iconos */

            table .button.blue span{ color: #009AD7 !important; }

            table .button.yellow span{ color: #FFAA31 !important; }

            table .button.green span{ color: #68AF27 !important; }

            table .button.red span{ color: #C22439 !important; }

            table .button.purple span{ color: #673499 !important; }



            .body .content .table td {

                padding: 0px 5px 0px 5px !important;

            }



            .navbar-brand-logo2 {

                position: absolute !important;

                height: 35px !important;

                width: 35px !important;

                top: 5px !important;

                left: 28px !important;

            }



        </style>



    </head>



    <body class="dashboard">

        <!--[if lt IE 8]>

              <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>

          <![endif]-->







                  <div class="v2h">





                      <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation" style="">

                          <div class="color-line">&nbsp;&nbsp;</div>

                          <div class="navbar-header" style="cursor:pointer; background:#f5f5f5 !important;">



                              <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided" data-toggle="menubar">

                                  <span class="sr-only">Toggle navigation</span>

                                  <span class="hamburger-bar"></span>

                              </button>



                              <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">

                                  <i class="icon wb-more-horizontal" aria-hidden="true"></i>

                              </button>



                              <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">

                                  <img onerror="ImgError(this)"  class="navbar-brand-logo" src="<?php echo base_url("public/v2/img"); ?>/logo_white_bg.png?v3" title="Vendtys" style="visibility: visible;" >

                                  <img onerror="ImgError(this)"  class="navbar-brand-logo2" src="<?php echo base_url(); ?>/public/v2/img/vendty-icon.png?v3" title="Vendtys" style="visibility: hidden">

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



                                      <li class="hidden-float" id="toggleMenubar" style="background:#f5f5f5 !important; height:50px;width:50px; border-left:1px solid #e4e5e7;">

                                          <a id="btnToggleMenu" data-toggle="menubar" href="javascript:void(0)" role="button" style="margin-top: 7px !important; color:#6a6c6f !important; text-align: center; font-size:10px;">

                                              <i class="icon wb-menu" ></i>

                                          </a>

                                      </li>







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



                                      <li class="hidden-float">

                                          <a class="nombreAlm" href="javascript:void(0)" role="button" style=" margin-top: 6px;">

                                              <?php getNombreAlmacenCliente(); ?>

                                          </a>

                                      </li>









                                      <li class="hidden-float">

                                          <div class="separatorBar"></div>

                                      </li>

                                      <li class="hidden-float" style=" margin-right: 0px !important;" style="height:50">



                                          <?php if(  $offline != 'false'){ ?>

                                          <a id="btnOffline" class="btnG" href="javascript:goBorrarOffline();" role="button" style="display:block;">

                                              <i class="glyphicon glyphicon-refresh" aria-hidden="true" style="color:#D53F26; font-weight: bold;"></i>

                                          </a>

                                          <?php } ?>



                                          <a id="btnAyuda" class="btnG" href="http://vendty.com/ayuda/" target="_blank" role="button">

                                              <i class="webIcon icon wb-help" aria-hidden="true" ></i>

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

                                      <li class="hidden-float">

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



                      <div class="site-menubar">

                          <div class="site-menubar-body">

                              <div>

                                  <div>

                                      <center>

                                          <div style="margin-top: 25px">

                                              <a class="nombreEmpresa" href="javascript:void(0)" role="button" style="margin-top: 16px;text-aling:center">

                                              <?php getNombreEmpresa(); ?>

                                              </a>

                                          </div>

                                      </center>

                                      <div style="text-align: center; margin: 10px 0px 20px;">

                                          <span class="avatarLogo avatar-onlineLogo">

                                              <img onerror="ImgError(this)" height="34" width="100" src="<?php echo base_url("uploads"); ?>/<?php getLogoAlmacen(); ?>" alt="">

                                          </span>

                                      </div>

                                      <ul class="site-menu" id="dataStep1">

                                          <br>

                                          <li class="site-menu-item mayuscula menu-items">

                                              <a href="<?php echo site_url(); ?>">

                                                  <i id="menuIconA" class="site-menu-icon wb-home" aria-hidden="true"  style="color:#f18a30;"></i>

                                                  <span class="site-menu-title"><h5>Inicio</h5></span>

                                              </a>

                                          </li>





                                          <?php if( in_array("11", $permisos ) || $isAdmin == 't'){ ?>

                                          <li class="site-menu-item mayuscula menu-items">

                                              <a href="<?php echo site_url("ventas/nuevo"); ?>">

                                                  <i id="menuIconB" class="site-menu-icon glyphicon-shopping-cart" aria-hidden="true" style=" color: #f0f0f0;"></i>

                                                  <span class="site-menu-title"><h5>Vender</h5></span>

                                              </a>

                                          </li>

                                          <?php } ?>



                                          <?php if( in_array("10", $permisos ) || in_array("11", $permisos ) || in_array("66", $permisos ) || in_array("69", $permisos ) || in_array("27", $permisos ) || in_array("1024", $permisos ) || (in_array("1022", $permisos ) && $comanda["comanda"] == "si") || $isAdmin == 't'){ ?>

                                          <li class="site-menu-item has-sub  menu-items">

                                              <a href="javascript:void(0)">

                                                  <i id="menuIconC" class="site-menu-icon glyphicon-tag" aria-hidden="true" style="color:#b2d62a;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Ventas</h5></span>

                                                  <span class="site-menu-arrow"></span>

                                              </a>

                                              <ul class="site-menu-sub">



                                                  <?php if( in_array("10", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("ventas"); ?>">

                                                          <span class="site-menu-title">Histórico de Ventas</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("66", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("ventas/ventas_anuladas"); ?>">

                                                          <span class="site-menu-title">Ventas Anuladas</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if($isAdmin == 't' && $ventaOnline){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("ventas_online/ventas"); ?>">

                                                          <span class="site-menu-title">Ventas Online</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("27", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("presupuestos"); ?>">

                                                          <span class="site-menu-title">Cotizaciones</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("1024", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("devoluciones/index"); ?>">

                                                          <span class="site-menu-title">Devoluciones</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("69", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item" title="Créditos">

                                                      <a class="animsition-link" href="<?php echo site_url("credito"); ?>">

                                                          <span class="site-menu-title">Créditos</span>



                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("1022", $permisos ) || $isAdmin == 't'){ ?>

                                                  <?php if( $comanda["comanda"] == "si" && $comanda["push"] == "1" ){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("comanda"); ?>">

                                                          <span class="site-menu-title">Comandas</span>

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

                                                  <i id="menuIconD" class="site-menu-icon wb-payment" aria-hidden="true" style="color:#e4b834;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Inventario</h5></span>

                                                  <span class="site-menu-arrow"></span>

                                              </a>

                                              <ul class="site-menu-sub">

                                                  <?php if( in_array("2", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("productos"); ?>">

                                                          <span class="site-menu-title">Productos</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("14", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("categorias"); ?>">

                                                          <span class="site-menu-title">Categorías</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("67", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("inventario"); ?>">

                                                          <span class="site-menu-title">Movimientos</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("68", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("ingredientes"); ?>">

                                                          <span class="site-menu-title">Materiales</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("lista_precios/index"); ?>">

                                                          <span class="site-menu-title">Libro de precios</span>

                                                      </a>

                                                  </li>

                                                  <?php if($isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("informes/stock_minimo_maximo"); ?>">

                                                          <span class="site-menu-title">Stock actual</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                              </ul>

                                          </li>

                                          <li class="site-menu-item has-sub  menu-items">

                                              <a href="javascript:void(0)">

                                                  <i id="menuIconC" class="site-menu-icon glyphicon-thumbs-up" aria-hidden="true" style="color:#e4b834;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Fidelización</h5></span>

                                                  <span class="site-menu-arrow"></span>

                                              </a>

                                              <ul class="site-menu-sub">

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("productos/listaGiftCards"); ?>">

                                                          <span class="site-menu-title">GiftCards</span>

                                                      </a>

                                                  </li>

                                                  <?php if($isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("puntos/index"); ?>">

                                                          <span class="site-menu-title">Puntos</span>

                                                      </a>

                                                  </li>



                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("promociones/index"); ?>">

                                                          <span class="site-menu-title">Promociones</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                              </ul>

                                          </li>

                                          <?php } ?>



                                          <?php if( in_array("58", $permisos ) || in_array("70", $permisos ) || in_array("71", $permisos ) || $isAdmin == 't'){ ?>

                                          <li class="site-menu-item has-sub  menu-items">

                                              <a href="javascript:void(0)">

                                                  <i id="menuIconF" class="site-menu-icon wb-briefcase" aria-hidden="true" style=" color: #a5d633;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Compras</h5></span>

                                                  <span class="site-menu-arrow"></span>

                                              </a>

                                              <ul class="site-menu-sub">

                                                  <?php if( in_array("58", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("proformas"); ?>">

                                                          <span class="site-menu-title">Gastos</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("70", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("orden_compra"); ?>">

                                                          <span class="site-menu-title">Orden de compra</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                              </ul>

                                          </li>

                                          <?php } ?>



                                          <?php if( in_array("32", $permisos ) || in_array("45", $permisos ) || in_array("62", $permisos ) || $isAdmin == 't'){ ?>

                                          <li class="site-menu-item has-sub  menu-items">

                                              <a href="javascript:void(0)">

                                                  <i id="menuIconG" class="site-menu-icon wb-users" aria-hidden="true" style="color: #5881b5;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Contactos</h5></span>

                                                  <span class="site-menu-arrow"></span>

                                              </a>

                                              <ul class="site-menu-sub">

                                                  <?php if( in_array("32", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("clientes"); ?>">

                                                          <span class="site-menu-title">Clientes</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("45", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item">

                                                      <a class="animsition-link" href="<?php echo site_url("vendedores"); ?>">

                                                          <span class="site-menu-title">Vendedores</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                                  <?php if( in_array("62", $permisos ) || $isAdmin == 't'){ ?>

                                                  <li class="site-menu-item" title="Informe de orden de compra">

                                                      <a class="animsition-link" href="<?php echo site_url("proveedores"); ?>">

                                                          <span class="site-menu-title">Proveedores</span>

                                                      </a>

                                                  </li>

                                                  <?php } ?>

                                              </ul>

                                          </li>

                                          <?php } ?>



                                          <?php if( in_array("1", $permisos ) || $isAdmin == 't'){ ?>

                                          <li class="site-menu-item  menu-items">

                                              <a href="<?php echo site_url("informes"); ?>">

                                                  <i id="menuIconH" class="site-menu-icon wb-graph-up" aria-hidden="true" style="color: #d6b434;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Informes</h5></span>

                                              </a>

                                          </li>

                                          <?php } ?>

                                          <?php if( $isAdmin == 't' ){ ?>

                                          <li class="site-menu-item  menu-items">

                                              <a href="<?php echo site_url("frontend/configuracion"); ?>">

                                                  <i id="menuIconI" class="site-menu-icon wb-settings" aria-hidden="true"  style="color: #da0404;"></i>

                                                  <span class="site-menu-title mayuscula"><h5>Configuración</h5></span>

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

                        <<div class="body" style="margin: 0px; padding: 0px;">
