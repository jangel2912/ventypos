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



        <link href="<?php echo base_url(); ?>public/css/stylesheetsV2.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url(); ?>public/css/grumble/grumble.min.css" rel="stylesheet" type="text/css" />



        <link href="<?php echo base_url(); ?>public/css/grumble/crumble.css" rel="stylesheet" type="text/css" />



        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-1.9.1.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/jquery-migrate-1.1.1.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/jquery/globalize.js'></script>

        <script src="<?php echo base_url('/public/js/jquery.maskMoney.js'); ?>"></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/other/excanvas.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/other/jquery.mousewheel.min.js'></script>

        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/bootstrap/bootstrap.min.js'></script>

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



        </script>


        <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("fb524507ebb7cd3bc8c139af1cf06089");</script><!-- end Mixpanel -->

        <style>

            input[type="number"]{

                height: 28px;

            }

            .table th, .table td {

                text-align: left;

            }

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

                height: 40px;

                background-color: #fff;

                border-left: rgba(0,0,0,0.15) solid 1px;

                border-right: rgba(0,0,0,0.15) solid 1px;

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

                left: 30px;

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



            .body .content .btn.focus, .body .content .btn:focus, .body .content .btn:hover{

                color:#fff;

            }



            .body .content .btn{

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

            .body .content .btn:hover{

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

            table .button.blue span{ color: #009AD7 !important; font-size: 22px !important; }

            table .button.orange span{ color: #CA6D2A !important; font-size: 22px !important; }

            table .button.yellow span{ color: #FFAA31 !important; font-size: 22px !important; }

            table .button.green span{ color: #68AF27 !important; font-size: 22px !important; }

            table .button.red span{ color: #C22439 !important; font-size: 22px !important; }

            table .button.purple span{ color: #673499 !important; font-size: 22px !important; }







            .body .content .table td {

                padding: 0px 5px 0px 5px !important;

            }





            /*********************************/

            /* Mensaje de vendty demo 7 dias */

            /*********************************/



            .toast-warning {

                background-color: #FBF3CD;

                color: #5D5A5A;

                border: 1px solid rgba(0,0,0,.1);

                border-bottom-color: rgba(0, 0, 0, 0.0980392);

                border-bottom-style: solid;

                border-bottom-width: 1px;

                padding: 0px;

                overflow-y: hidden;

                height: 30px;

            }

            #logoAlertaDemo{

                margin-right: 20px;

                margin-left: 10px;

                color: #D6A963;

                float: left;

                height: 20px;

                font-size: 20px;

            }

            #cerrarAlertaDemo{

                font-size: 12px;

                float: right;

                color: #5D5A5A;

                margin-top: 5px;

                margin-right: 5px;

            }

            #msgAlertaDemo{

                margin-top: 4px;

                float: left;

            }



        </style>



    </head>



    <body class="dashboard">

        <!--[if lt IE 8]>

              <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>

          <![endif]-->





          <div class="v2h">



              <a id="btnToggleMenu" data-toggle="menubar" href="javascript:void(0)" role="button" class="hided" style="display:none"></a>



              <div id="logoCont">

                  <a href="<?php echo site_url(); ?>">

                      <img class="navbar-brand-logo2" src="/vendtyTest/public/v2/img/logo_solo.png" title="Vendtys">

                  </a>

              </div>



              <div class="site-menubar">

                  <div class="site-menubar-body">

                      <div>

                          <div>



                              <ul class="site-menu">



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url(); ?>">

                                          <i class="site-menu-icon wb-home" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Inicio</h5></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("submenu/ventas"); ?>">

                                          <i class="site-menu-icon wb-shopping-cart" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Ventas</h5></span>

                                          <span class="site-menu-arrow"></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("submenu/contactos"); ?>">

                                          <i class="site-menu-icon wb-payment" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Productos</h5></span>

                                          <span class="site-menu-arrow"></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("submenu/cotizacion"); ?>">

                                          <i class="site-menu-icon wb-stats-bars" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Cotización</h5></span>

                                          <span class="site-menu-arrow"></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("submenu/compras"); ?>">

                                          <i class="site-menu-icon wb-briefcase" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Compras</h5></span>

                                          <span class="site-menu-arrow"></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("submenu/contactos"); ?>">

                                          <i class="site-menu-icon wb-users" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Contactos</h5></span>

                                          <span class="site-menu-arrow"></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("informes"); ?>">

                                          <i class="site-menu-icon wb-graph-up" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Informes</h5></span>

                                      </a>

                                  </li>



                                  <li class="site-menu-item">

                                      <a href="<?php echo site_url("submenu/configuracion"); ?>">

                                          <i class="site-menu-icon wb-settings" aria-hidden="true"></i>

                                          <span class="site-menu-title"><h5>Configuración</h5></span>

                                      </a>

                                  </li>



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

