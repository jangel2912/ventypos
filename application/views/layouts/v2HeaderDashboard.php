<?php
    //die("das");
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

    $nombre_empresa=(!empty($data["datos_empresa"][0]->nombre_empresa))? $data["datos_empresa"][0]->nombre_empresa : "No existe nombre";
    //$offline= 'false';

    //imagenes a utilizar
    $cimagenes =&get_instance();
    $cimagenes->load->model('crm_imagenes_model');
    $imagenes=$cimagenes->crm_imagenes_model->imagenes();
    $this->session->set_userdata('new_imagenes',$imagenes);
?>

<!DOCTYPE html>
<!--
    algo
-->

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
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
        <link href="<?php echo base_url(); ?>public/css/inicio.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/css/newicons.css?<?php echo rand();?>" rel="stylesheet" type="text/css" />

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
        <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>
        <!--CSS Restaurant Style Design MESAS -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>public/css/app/restaurantDesignMeseroDashboard.css">

          <!-- start Mixpanel -->
          <script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
mixpanel.init("fb524507ebb7cd3bc8c139af1cf06089");</script>
<!-- end Mixpanel -->


        <script>


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

            Breakpoints();
            /*$(document).ready(function(){
                $('img').on("error", function () {
                    $(this).attr('src', '<?php echo base_url(); ?>uploads/product-dummy.png?v2.0');

                });
            });*/

            function ImgError(source){
                    source.src = "<?php echo base_url(); ?>uploads/product-dummy.png";
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

        <!-- ConvertLoop
        <script>
        !function(t,e,n,s) { t.DPEventsFunction=s,t[s]=t[s] || function() { (t[s].q=t[s].q||[]).push(arguments) }; var c=e.createElement("script"),o=e.getElementsByTagName("script")[0]; c.async=1,c.src=n,o.parentNode.insertBefore(c,o); }(window, document, "https://www.convertloop.co/v1/loop.min.js", "_dp");

        _dp("configure", { appId: "73a39be3", autoTrack: true });
        _dp("pageView");



        </script>
        End ConvertLoop -->

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

        <?php if( $estado == "2" ){ ?>

            body{padding:0px !important;}
            .navbar-fixed-top{top:0px !important;}
            .site-menubar {
                top: 63px !important;
            }
            .nav-tabs{margin-top:1.5%;}
            .line-border{background-color: #00baff !important;}
            .tab-content #graficos_home{
                margin-top: 1%;
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

        .message-test{
            position: fixed;
            background-color: #00baff;
            color: #fff;
            top: 0;
            z-index: 999;
            width: 100%;
            border: none;
            text-align:center;
            padding:4px;
            font-size: 14px;
        }

        .message-test a{color:#FFF;  text-decoration:underline;}
        .message-test a:hover{color:black;}

        .display_none{display:none !important;}
        .padding_top_40{padding-top:40px !important;}
        .top_0{top:0 !important;}
        .top_60{top:60px !important;}
        .margin_top_0{margin-top:0px !important;}
        .line-border{background-color: #62cb31; height: 3px;}
        .line_border_default{background-color: #62cb31 !important; height: 3px;}
        .link_pagar{text-decoration: cursive;}

        .modal-comprar{margin-top:130px; padding:10px; padding-bottom: 3px; box-sizing:border-box;}
        .modal-comprar h3{text-transform: uppercase;}
        .modal-comprar hr{margin-bottom:30px;}
        .modal-comprar .content-steps{}
        .modal-comprar .titulo{font-size: 17px;margin-top:18px; padding: 3px;box-sizing: border-box;color: #62cb31;/*color: #00baff;*/margin-bottom: 9px;border-bottom: solid 1px lightgray;}
        .modal-comprar .small-text{font-size: 11px; text-align:center;}
        .modal-comprar .content-small{border-top: solid 1px lightblue; margin-top:4px;}
        .content-steps .col-md-4{padding:25px; box-sizing:border-box;}
        .content-steps .col-md-4 img{max-width:100px;}
        .content-steps .enlace{text-decoration: underline; color: #62cb31; }

        .new-float{
            position: absolute;
            background-color: red;
            border-radius: 50%;
            padding: 2px;
            color: #fff;
            box-sizing: border-box;
            font-size: 7px;
            left: -17px;
            padding-top: 0px;
            padding-bottom: 0px;
            top: 4px;
            font-weight: bold;

        }

        .shop{width: 190px !important;}
        .shop .admin-shop{float:left;}
        .shop img{width:21px !important; min-width: 21px;}
        .shop span{font-weight:bold;}
        #msgAlertaDemo strong{
            font-weight:700;
        }
        </style>

        <script>
            var beamer_config = {
                product_id : 'ZPXaeSWQ4574', //DO NOT CHANGE: This is your product code on Beamer
                button_position: 'bottom-right', /*Position for the default notification button. Other possible values are 'bottom-left', 'top-left' and 'top-right'.*/
                selector: 'notification'
            };
        </script>
        <script type="text/javascript" src="https://app.getbeamer.com/js/beamer-embed.js" defer="defer"></script>



        <!--Start of Tawk.to Script-->
        <!--
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/59c42932c28eca75e46217e9/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        -->
        <!--End of Tawk.to Script-->
        <!--intercom-->
        <!--
        <script>
            var APP_ID = "na75p10i";
            var current_user_email = '<?php echo $this->session->userdata('email') ?>';
            var current_user_name = '<?php echo $this->session->userdata('username') ?>';
            var current_user_id = '<?php echo $this->session->userdata('user_id') ?>';
            var current_empresa = '<?php echo $nombre_empresa ?>';

            window.intercomSettings = {
                app_id: APP_ID,
                name: current_user_name, // Full name
                email: current_user_email, // Email address
                user_id: current_user_id, // current_user_id
                empresa: current_empresa //nombre empresa
            };
        </script>
        <script>
                (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/na75p10i';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
        </script>-->
        <!-- final intercom-->
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

            <!-- Smartsupp Live Chat script  Ayuda-->
            <!--<script type="text/javascript">
            var _smartsupp = _smartsupp || {};
            _smartsupp.key = 'b851a23cdf3a58a5801e6129a5b8fd1675063717';
            window.smartsupp||(function(d) {
            var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
            s=d.getElementsByTagName('script')[0];c=d.createElement('script');
            c.type='text/javascript';c.charset='utf-8';c.async=true;
            c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
            })(document);
            </script>-->

        <?php } ?>



    </head>

    <body class="dashboard site-menubar-chaging site-menubar-unfold">

<?php if(isset($data["estado"]) && $data["estado"] == 1 && (($isAdmin == 't') || ($isAdmin == 'a')) && isset($data["tiponegocio_infoactualizar"]) && $data["tiponegocio_infoactualizar"] == 0 ){ ?>
    <!--modal info negocio-->
    <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="form-horizontal" id="formu_info_negocio" action="<?= site_url("frontend/info_negocio") ?>"  method="post">
                    <div class="modal-header text-center">
                        <h6 class="modal-title" id="myModalLabel">Actualización de Información dash</h6>
                        <span>Para brindarte una mejor experiencia de uso, te solicitamos actualizar la siguiente información</span>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tu negocio se especializa en:<span>*</span></label>
                            <div class="col-sm-10">
                                <select id="tipo_negocio_especializado" name="tipo_negocio_especializado" required>
                                    <option value="" selected >Seleccione</option>
                                    <?php foreach($data["tipo_negocio_especializado"] as $key => $negocio_especializado){
                                        if($negocio_especializado!='Seleccione'){
                                    ?>
                                    <option value="<?php echo $key ?>" ><?php echo $negocio_especializado ?></option>
                                        <?php } }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Identificación Tributaria:<span>*</span></label>
                            <div class="col-sm-10">
                                <select id="identificacion_tributaria" name="identificacion_tributaria" required>
                                    <option value="" selected >Seleccione</option>
                                    <?php foreach($data["identificacion_tributaria"] as $value => $tributaria): ?>
                                    <option value="<?php echo $tributaria['valor_opcion'];?>"><?php echo $tributaria['mostrar_opcion'];?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">País:<span>*</span></label>
                            <div class="col-sm-10">
                            <select id="pais_negocio" name="pais_negocio" required>
                                <?php foreach($data["paises"] as $value => $pais): ?>
                                <option value="<?php echo $pais;?>"><?php echo $pais;?></option>
                                <?php endforeach; ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Cuidad/Provincia:<span>*</span></label>
                            <div class="col-sm-10">
                                <select id="provincia" name="provincia" required>
                                    <option value="" selected >Seleccione</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <strong class="obligatorio">Nota:</strong> <span>Esta actualización no modificará ninguna de las funcionalidades del sistema</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
    <!-- fin modal info negocio-->

    <!-- <script>
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        })

        $('#myModal').modal('show');

        $(".close").click(function(){
            $('#myModal').modal('hide');
        });

        $("#pais_negocio").change(function(){
            pais=$("#pais_negocio").val();
            load_provincias_from_pais_almacen(pais);
        });

        load_provincias_from_pais_almacen("Colombia");

        function load_provincias_from_pais_almacen(pais){
            $.ajax({
                url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",
                data: {"pais" : pais},
                dataType: "json",
                success: function(data) {
                    $("#provincia").html('');
                    $.each(data, function(index, element){
                        provincia = "<?php echo set_value('provincia');?>";
                        sel = provincia == element[0] ? "selected='selectted'" : '';
                        $('#provincia').append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");
                    });
                }
            });
        }

        $("#formu_info_negocio").submit(function(e){

            e.preventDefault();
            tipo_negocio_especializado=$('#tipo_negocio_especializado').val();
            identificacion_tributaria=$('#identificacion_tributaria').val();
            pais=$('#pais_negocio').val();
            ciudad=$('#provincia').val();
            if((tipo_negocio_especializado!="")&&(identificacion_tributaria!="")&&(pais!="")&&(ciudad!="")){
                $.ajax({
                    url: $(this).attr('action'),
                    type:  'post', //método de envio
                    dataType: "json",
                    data:  $("#formu_info_negocio").serialize(),
                    success: function(data) {
                        console.log(data);
                        if(data.success==1){
                            $('#myModal').modal('hide');
                            swal({
                                position: 'top-center',
                                type: 'success',
                                title: 'Muchas Gracias por su tiempo.',
                                showConfirmButton: false,
                                timer: 2000
                                });
                        }else{
                            swal(
                                "error",
                                "Ocurrio un error al momento de guardar la información, intente más tarde",
                                "error"
                            );
                        }
                    }
                });
            }else{
                swal(
                    'Alerta',
                    'Seleccione al menos una liccencia para Pagar',
                    'warning'
                );
            }
        });

    </script> -->
<?php } ?>

        <!-- modal como comprar -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-comprar">
                    <button type="button" id="close-modal-comprar" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="text-center">¿Cómo comprar tu licencia en vendty?</h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 content-steps">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <img src="<?php echo base_url().'uploads/numero_uno.png'?>" alt="">
                                </div>
                                <div class="text-center titulo">PRIMER PASO</div>
                                <p class="text-center">Haga clic en "COMPRALO AQUÍ" y selecciona el plan que mejor se adapte a tu modelo de negocio.</p>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center">
                                <img src="<?php echo base_url().'uploads/numero_dos.png'?>" alt="">
                                </div>
                                <div class="text-center titulo">SEGUNDO PASO</div>
                                <p class="text-center">Paga tu licencia mediante PSE, Tarjeta de credito, Baloto o Efecty. <a class="enlace" target='_blank' href="https://www.youtube.com/watch?v=d4jnVjGp5bU&t=25s">Ver video</a></p>
                            </div>

                            <div class="col-md-4">
                                <div class="text-center">
                                     <img src="<?php echo base_url().'uploads/numero_tres.png'?>" alt="">
                                </div>
                                <div class="text-center titulo">TERCER PASO</div>
                                <p class="text-center">Una vez realizado el pago, revisa tu correo electrónico, agenda tu capacitación y puesta en marcha.</p>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="content-small">
                                <span class="small-text">* Ten en cuenta que si pagas via efecty o baloto el tiempo mientras se procesa tu pago es de 15 minutos.</span>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>




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
        <!--
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
                                <div style=" padding:0px; font-size:14px;margin-top:10px; width:250px; margin-left:80px; "><strong><i class="icon glyphicon glyphicon-earphone" aria-hidden="true" style="margin-right:10px;"></i></strong><strong style="font-size:16px"><a href="javascript:void(0)">+1 792 3225</a></strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
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
                                                <img onerror="ImgError(this)"  id="previewImg" src="<?php echo base_url(); ?>public/img/productos/product-dummy.png?v2.0">
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
            <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation" >
                <div class="line-border">&nbsp;&nbsp;</div>
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
                       <!-- <ul class="nav navbar-toolbar">
                            <li class="hidden-float">
                            </li>
                        </ul>-->

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
                        <!-- Navbar Toolbar left -->

                        <?php
                        if ($_SERVER['REQUEST_URI'] == "/index.php/frontend/index") {
                            include "application/views/info-license.php";
                        }
                        ?>

                        <!--
                        <ul class="nav navbar-toolbar navbar-left navbar-toolbar-right" style="margin-left:17%">
                            <li class="shop">
                                <a id="admin_shop" class="admin-shop" class="" role="button">
                                    <img alt="tienda" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['tienda_virtual_verde']['original'] ?>">
                                    <span>Activa tu tienda virtual</span>
                                </a>
                                <div class="new-float">Nuevo</div>
                            </li>
                        </ul>-->
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
            <!--<div class="toast-warning" style="margin-top: 1%;">
                <a id="cerrarAlertaDemo" href="javascript:cerrarPrueba();"><i class="icon wb-close" aria-hidden="true"></i></a>
                <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>
                <span id="msgAlertaDemo"><strong> ¡Importanteeee! </strong> Queda <strong><?php echo $dias; ?></strong> días de prueba. &nbsp;&nbsp;  <?php if($resultPermisos["admin"]=='t') {?>Para renovar tu licencia  <a id="a_modal_expiracion"  href="<?php echo site_url("frontend/pagarPrueba"); ?>" >clic aquí</a> <?php }else{ echo "Comunícate con el administrador del Sistema."; } ?>

                </span>
            </div>-->
             <!--
            <div class="toast-warning1" style="margin-top: 1%;">
                <a id="cerrarAlertaDemo" href="javascript:cerrarPrueba();"><i class="icon wb-close" aria-hidden="true"></i></a>
                <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>
                <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Si tiene problemas al efectuar una venta, por favor elimine la caché de su navegador presionando <strong>Ctr+F5</strong> al mismo tiempo.</span>
            </div>-->
            <?php if($estado == 1){ ?>

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
            <!--Para anucio del cierre de caja-->
            <?php // if(($data['data_empresa']['data']['valor_caja']=="si") && ($data['data_empresa']['data']['cierre_automatico']==0)){?>
                <!--
                <div  class="toast-warning1" id="" style="">
                    <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>
                    <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Actualmente no tienes la configuración para el cierre automático de caja, si deseas activar esta opción <?php // if($resultPermisos["admin"]=='t') {?> haz <a href="<?php // echo site_url("frontend/configuracion?seleccionado=3"); ?>"><b>clic aquí</b></a> <?php // }else{ echo ". Comunícate con el administrador del Sistema."; } ?></span>
                </div>
                -->
            <?php // } ?>
            <!--fin Para anucio del cierre de caja-->
            <!--Para anucio del cierre de caja cuando el valor_caja sea no-->
            <?php // if($data['data_empresa']['data']['valor_caja']=="no"){?>
                <!--
                <div  class="toast-warning1" id="" style="">
                    <span id="logoAlertaDemo"><i class="icon wb-warning" aria-hidden="true"></i></span>
                     <span id="msgAlertaDemo"><strong> ¡Importante! </strong> Actualmente no tienes activa la configuración para guardar los procesos del cierre de caja, si deseas activar esta opción <?php // if($resultPermisos["admin"]=='t') {?> haz <a href="<?php // echo site_url("frontend/configuracion?seleccionado=3"); ?>"><b>clic aquí</b></a> <?php // }else{ echo ". Comunícate con el administrador del Sistema."; } ?></span>
                </div>
                -->
            <?php // } ?>
            <!--fin Para anucio del cierre de caja-->
             <?php if(isset($data["estado"]) && ($data["estado"] == 2 )){ ?>
                 <div style="padding-top: 1%;">
            <?php }else{ ?>
                <div class="page-content">
            <?php } ?>

<script>
     $(".close_test").click(function(e){
        e.preventDefault();
        //$(".message-test").css("display","none");
        //$("body").css("padding-top", "40px !important");
        //$(".navbar-fixed-top").css("top", "0px !important");
        //$(".site-menubar").css("top", "60px !important");
        //$(".tab-content").css("margin-top", "0px !important");


        $(".message-test").addClass("display_none");
        $("body").addClass("padding_top_40");
        $(".navbar-fixed-top").addClass("top_0");
        $(".site-menubar").addClass("top_60");
        $(".nav-tabs").addClass("margin_top_0");
        $(".line-border").addClass("line_border_default");
    })

    $("#modal-como-comprar").click(function(e){
        e.preventDefault();
        $('.bs-example-modal-lg').modal('show');
    })

    $("#close-modal-comprar").click(function(){
        $('.bs-example-modal-lg').modal('hide');
    })

</script>

                    <style>
                        #div_mensaje_renovacion {
                            margin-top: 2px;
                            /* width: 70%; */
                            position: absolute;
                            top: 3px;
                            left: 18%;
                            border: none;
                        }
                    </style>