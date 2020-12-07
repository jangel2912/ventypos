<?php
$resultPermisos = getPermisos();
$permisos = $resultPermisos["permisos"];
$isAdmin = $resultPermisos["admin"];
$ventaOnline = existeVentasOnline();
$comanda = getComanda();
//$offline= 'false';
$offline = getOffline();

$cimagenes =&get_instance();
$cimagenes->load->model('crm_imagenes_model');
$imagenes=$cimagenes->crm_imagenes_model->imagenes();
$this->session->set_userdata('new_imagenes',$imagenes);
//print_r($data["datos_empresa"][0]->nombre_empresa); die();
$nombre_empresa=(!empty($data["datos_empresa"][0]->nombre_empresa))? $data["datos_empresa"][0]->nombre_empresa : "No existe nombre";    
$this->session->set_userdata('nombre_empresa',$nombre_empresa);
$estado = $data['estado'];
$random = rand();
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
    <link href="<?php echo base_url();?>public/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/css/site.min081a.css?v2.0.0">
    <script src="https://use.fontawesome.com/4e8d392ffb.js"></script>
    <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/datatables.responsive.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/jasny-bootstrap-fileinput.min.css">
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app/restaurante.css?<?=$random?>">


    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/slick/slick-theme.css"/>
    <link href="<?php echo base_url(); ?>public/css/newicons.css" rel="stylesheet" type="text/css" />

    <!--CSS Restaurant Style Design-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/restaurantDesign.css?<?=$random?>">
    <!--CSS Restaurant Style Design MESERO 1 -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/restaurantDesignMesero.css?<?=$random?>">
    <!--CSS VENTAS MI ORDEN -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/restaurantMiOrden.css?<?=$random?>">

    <script type="text/javascript" src="<?php echo base_url(); ?>public/css/app/slick/slick.min.js"></script>
    <script src="<?php echo base_url("index.php/OpcionesController/index")?>"></script>


          <!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("fb524507ebb7cd3bc8c139af1cf06089");</script><!-- end Mixpanel -->


    <script>
        var baseUrl = '<?php echo site_url();?>';

        function tConvert (time) {
            // Check correct time format and split into components

            if (time){
                var timeFinalSplit = time.split(' ');
                var tiempo = timeFinalSplit[1];
                timeFinalSplit = tiempo.split(':');
                return timeFinalSplit[0]+':'+timeFinalSplit[1]; // return adjusted time or original string
            } else{
                return null;
            }

        }
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

<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation" >
    <div style="background-color: #5cb85c; height: 3px;">&nbsp;&nbsp;</div>
    <div class="navbar-header" style="cursor:pointer; background:#505050 !important; height: 60px !important;">
        <?php if($this->session->userdata('es_estacion_pedido')!=1){?>
            <button type="button" id="menucito" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided" data-toggle="menubar">
                <span class="sr-only">Toggle navigation</span>
                <span class="hamburger-bar"></span>
            </button>
            <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
                <i class="icon wb-more-horizontal" aria-hidden="true"></i>
            </button>
            <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-search" data-toggle="collapse">
                <span class="sr-only">Toggle Search</span>
                <i class="icon wb-search" aria-hidden="true"></i>
            </button>
        <?php }else{

            $url=explode("index.php",$_SERVER["REQUEST_URI"]);
            $url=trim($url[1],"/");
            $url=explode("/",$url);

            if($url[1]=='estacion_pedidos'){
                $href="auth/logout";
            }else{
                $href="tomaPedidos/salir_mesero";
            }
            ?>
            <a href="<?php echo site_url($href); ?>" class="navbar-toggle collapsed" data-target="#site-navbar-search" data-toggle="collapse">
                <i style="font-size: 2em;" class="glyphicon glyphicon-off" aria-hidden="true"></i>
            </a>

        <?php } ?>
        <a href="<?php echo site_url(); ?>">
            <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
                <img onerror="ImgError(this)"  class="navbar-brand-logo" src="<?php echo base_url("public/v2/img"); ?>/logodas.fw.png" title="Vendtys" style="visibility: visible; width: 79px;height: auto;margin-left: 15px;" >
                <img onerror="ImgError(this)"  class="navbar-brand-logo2" src="<?php echo base_url(); ?>/public/v2/img/logodas.fw.png" title="Vendtys" style="visibility: hidden; width: 79px;height: auto;margin: 0px 0px 0px -17px;">
            </div>
        </a>

    </div>
</nav>

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
<section id="site-menubar wrapper">
    <section id="page-content">

