<?php

    $ci =&get_instance();
    $ci->load->model('opciones_model');         
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
    $diasDisponibles = $data['diasCuentaDisponibles'];
    $almacenSel = $data['almacen'];
    $diasSel = $data['dias'];
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];    
    $nombre_empresa=(!empty($data["datos_empresa_ap"][0]->nombre_empresa))? $data["datos_empresa_ap"][0]->nombre_empresa : "No existe nombre";
    $simbolo=(!empty($data['simbolo'])) ? $data['simbolo'] : '$';
    $terms_condition = $this->session->userdata('terms_condition');
 ?>
<style>
    .success{
        float: right;
        margin-right: 20px;
        background-color: #67b168;
        color: #fff;
        width: 120px;
        text-align: center;
        border-radius: 3px;
        display:none;
    }



    .opcGraph{

        text-align: center;

        margin-bottom: 5px;

    }



    .panel-title{

        text-align: center;

        padding: 11px;

        color: #848484;

        background: #fbfbfb;

        border-radius: 4px 4px 0px 0px;

    }



    .list-group-item{

        margin: 1px 0px 1px 0px !important;

        padding: 10px 0px !important;

        border-bottom: 1px solid #eaeaea;

    }

    .diaSel{

        color: #35650B;

        text-decoration: underline;

    }



    #listaAlmacenes .example-col.panel{

        padding: 0px;

        border-radius: 4px;

    }



    #listaAlmacenes .panel-body{

        padding: 10px 15px;

    }



    //#listaAlmacenes .headB .panel-title{ background-color: transparent !important;}

    //#listaAlmacenes .headA .panel-title{ background-color: transparent !important;}

    //#listaAlmacenes .headC .panel-title{ background-color: transparent !important;}



    //#listaAlmacenes .panel.panelB{ border-color: rgba(158, 201, 253,0.8) !important;}

    //#listaAlmacenes .panel.panelA{ border-color: rgba(255, 201, 109,0.8) !important;}

    //#listaAlmacenes .panel.panelC{ border-color: rgba(203, 228, 96,0.8) !important;}

    /*#listaAlmacenes .panel{ border: 0px !important;}*/



    .counter-number-group{

        font-size: 36px;

        font-weight: 700;

    }



    .counter-md .counter-icon {

        font-size: 30px;

    }

    .content-type{transition: all 0.6s;}
    .loading_state{display:none;}
    .loading_state .img_loading{max-width:150px; }
    .block{display:block;}

    .content-type{position:relative; border:solid 1px lightgray; padding:10px; box-sizing:border-box; cursor:pointer; border-radius: 5px 5px;}
    .content-type p{font-size:16px; margin-top:18px;}
    .content-type:hover{background-color:#f1f8e9;}

    .webinar{margin-top:2px; border-left: 6px solid #62cb31;}
    .alert-webinar{background-color:#FFF; color:#333;}   
    .icon-webinar{color:#62cb31;}
    .link-webinar{color:#62cb31; font-weight:bold;}
    .datos-facturas{margin-top:2px; border-left: 6px solid #dee222;}
    .alert-datos-facturas{background-color:#FFF; color:#333;}
    .link-datos-facturas{color:#dee222; font-weight:bold;}
    .icon-datos-facturas{color:#dee222;}
    .page-inicio{background-color: #fff;outline: solid 1px lightgray;padding: 6px;box-sizing: border-box;}        
    
    #formu_info_negocio{font-size: 12px !important;}
    #formu_info_negocio label span,.obligatorio{color:red !important;}    
    img.tipo_negocio_especializado{width: 30% !important;}
    .modal-title {font-size: 20px;font-weight: 300;}
    .modal-header {padding: 15px 15px !important;}
    .separar{margin-bottom: 2% !important;}
    .modal{z-index: 1 !important;}
    
    .content-terms-conditions{background-color: rgba(80, 80, 80, 0.75);color: #fff;position: fixed bottom:0;position: fixed;bottom: 0;z-index: 1000;width: 100%;}
    .content-terms-conditions .message{display: flex;align-items: center;vertical-align: middle;justify-content: center;padding: 5px;}
    .content-terms-conditions .message p{margin-right: 8px;margin-top: 8px;}
    .content-terms-conditions .message p a{font-weight: bold;color: #fff;text-decoration: underline;}
    
    /* General Styles */
    .mt-1{margin-top:1rem;}
    .ml-0{margin-left:0px !important;}
    .pl-0{padding-left:0px;}
    .pr-0{padding-right:0px;}

    /*Steps configuration*/
    .close-wizard{ font-size: 12px;float: right;color: #5D5A5A;margin-top: 0px;margin-right: 3px;}
    .warning-wizard{background-color: #00baff;color: #fff; text-align: center;padding: 5px;}
    .content-config-steps{background:#fff;}
    .content-wizard-steps{ border: solid 1px #bbb;border-radius: 5px;margin-top: 15px;padding:10px; box-sizing:border-box;}
    .steps{display: flex;margin-bottom:0px; flex-direction: row;justify-content: space-between;align-items: center;padding: 0 17%;}
    .steps .step.active{color: #5ca745;}
    .steps .step {color: #bbb;font-size: 2.5rem;text-align: center;width: 45px;height: 45px;border: solid 4px;border-radius: 50%;background-color: white;margin-top: 15px;position: relative;}
    .steps .step .line-status {width: 472%;border: dashed 2px #bbb;position: absolute;left: -193px;top: 15px;}
    .steps .step .line-status.active { border: solid 2px #5ca745;}
    .steps .step p{ position: absolute;top: 0px;margin: 0 auto;left: 14px;}
    .type-business-active{border: 2px solid #5ca745 !important;box-shadow: 0 0 5px #5ca745;}
    .mask-type-business{position: absolute;top: 0;width: 100%;left: 0;height: 100%;background: #fff;opacity: 0.9;    display: flex;align-items: center;flex-direction: column;color: #333;font-size: 13px;justify-content: center;}
    .mask-type-business .subcategory{width:63%; font-size:14px; margin:0 auto;text-align:left;display: flex;align-items: center; margin-bottom:8px;}
    .mask-type-business .subcategory .subcategory-business{margin-right:5px;}
    .hide-mask{visibility:hidden;}
    .step-content{display:none;}
    .active-step{display:block;}
    .button-subcategorie{display:flex; align-items:center;justify-content:center; display:none;}
    .active-subcategorie{display:flex;}
    .subcategory{background-color:#fff !important; color:#333 !important;}
    .selected-subcategorie{background-color:#5cb85c !important; color:#fff !important;}
    .type_business{ margin-bottom: 15px;margin-top: 15px;}
    
    /* Step 2*/
    .file-logo{height:auto !important; width:100% !important; line-height:0px !important;}
    .actions-invoice{display:flex; align-items:center; justify-content:center;}
    .actions-invoice button{margin-left:7px;}
    .content-items{border: solid 1px lightgray;margin: 0px; padding: 10px;box-sizing: border-box;margin-bottom: 10px;border-radius: 5px 5px;}
    .title-header{width: 70%;margin-left: 15%;background-color: #fff;color: #333;padding: 2px;box-sizing: border-box;margin-top:8px;}
    .title-header h5{color: #00baff;}
    .invoice-preview{width: 70%;margin-left: 15%; border: solid 1px lightgray; border-bottom-style:dotted;}
    .invoice-preview .content-logo{text-align:center; margin-top:5px;position: relative;}
    .invoice-preview .content-logo img{max-width:60px;}
    .invoice-preview .content-logo input[type="file"] {width: 100% !important;height: 100% !important;position: absolute;opacity: 0;}
    .invoice-preview p{font-size:13px;}
    .title-store{margin-bottom:1px;}
    .items-invoice p{margin-bottom:1px;}
  
    /*Step 3*/
    .content-template{height:20rem; overflow:hidden; margin-bottom:10px;}
    .content-template img{width:100%;}
    .multiple-templates .slick-prev{display: inline-block;position: absolute;top: 41%; cursor:pointer;z-index: 10;left: -20px;}
    .multiple-templates .slick-next{display: inline-block;position: absolute;top: 41%; cursor:pointer;z-index: 10;right: -20px;}
    .active-template{border: solid 2px green; border-radius:5px;}
    .multiple-templates .col-md-4:focus{border:none;outline:none;}
    .btn-content{padding: 12px;margin-right:20px; opacity:0.8;background: #5cb85c;border:none;border-radius: 5px;font-size: 17px;box-sizing: border-box;font-weight: 400;color: #fff;}
    .btn-content:hover{opacity:1;}
    .btn-content:focus{outline:none;}
    .warning{color:#a94442;}
</style>

<?php if($terms_condition != "Si"): ?>
    <div class="row content-terms-conditions">
        <div class="col-md-12">
            <div class="message">
                <p> Aún no has aceptado nuestros <a target="_blank" href="http://vendty.com/terminosycondiciones.pdf">Términos y Condiciones</a> </p>
                <button class="btn btn-succes" id="accept_terms_conditions">Aceptar</button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (($this->session->userdata('bd_estado') == '1') && (($this->session->userdata('is_admin')=='t')) &&($data['datos_factura']==0)){  ?>
    <div class="datos-facturas">
            <div class="alert alert-datos-facturas  alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="glyphicon glyphicon-edit icon-datos-facturas" aria-hidden="true"></span>
                Actualiza los datos para la facturación de tu licencia en
                <a class="link-datos-facturas" href="<?php echo site_url('frontend/configuracion?seleccionado=1') ?>"> CONFIGURACIONES</a>
            </div>
    </div>

<?php } ?>

<?php //if ($this->session->userdata('bd_estado') == '2'){  ?>
    <!--<div class="webinar">
            <div class="alert alert-webinar  alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="glyphicon glyphicon-calendar icon-webinar" aria-hidden="true"></span>
                <strong> [WEBINAR] - </strong>
                Comienza a configurar tu tienda virtual en vendty.
                <a target='_blank' class="link-webinar" href="https://my.demio.com/ref/ra2emWMsPyK2z6Go"> ¡INSCRIBETE AQUI! </a>
            </div>
    </div>-->

<?php // } ?>




<script src="<?php echo base_url("index.php/OpcionesController/index") ?>"></script>


<?php

    if(isset($data["estado"]) && ($data["estado"] == 2) && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0 ){
        $active = "inicio";
    }else{
        $active = "graficas";
        if(isset($data["tipo_negocio"])){
            if($data["tipo_negocio"] == "restaurante" && ($isAdmin == 't' || $isAdmin == 'a')){
                $active = "graficas";
            }else if($data["tipo_negocio"] == "restaurante" && ($isAdmin != 't' && $isAdmin != 'a')){
                $active = "restaurante";
            }
        }
    }



?>

<div>
    <?php if(isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante"){?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs tabs-restaurante" role="tablist">
            <?php if(isset($data["estado"]) && ($data["estado"] == 2 ) && $isAdmin == 't' && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0){?>
                <li role="presentation" data-id="inicio_home" class="<?php echo ($active == "inicio")? 'active' : ''; ?>"><a href="#inicio_home" aria-controls="inicio_home" role="tab" data-toggle="tab">Inicio</a></li>
            <?php } ?>
            <?php if( in_array("1000", $permisos ) || $isAdmin == 't'){  ?>
                <li role="presentation" data-id="graficos_home" class="<?php echo ($active == "graficas")? 'active' : ''; ?>"><a href="#graficos_home" aria-controls="graficos_home" role="tab" data-toggle="tab">Dashboard</a></li>
            <?php } if(in_array("1038", $permisos ) || $isAdmin == 't'):?>
                <li role="presentation" data-id="restaurante_home" class="<?php echo ($active == "restaurante")? 'active' : '';?>"><a href="#restaurante_home" aria-controls="restaurante_home" role="tab" data-toggle="tab">Mesas y Toma Pedido</a></li>
            <?php endif;?>
            <?php if(isset($data["estado"]) && ($data["estado"] != 2 )){?>
            <?php } ?>
            </ul>

    <?php }else{ ?>
        <ul class="nav nav-tabs tabs-restaurante" role="tablist">
            <?php if(isset($data["estado"]) && ($data["estado"] == 2 ) && $isAdmin == 't' && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0){?>
                <li role="presentation" data-id="inicio_home" class="<?php echo ($active == "inicio")? 'active' : ''; ?>"><a href="#inicio_home" aria-controls="inicio_home" role="tab" data-toggle="tab">Inicio</a></li>
            <?php }else if( in_array("1000", $permisos ) || $isAdmin == 't'){ ?>
                <li role="presentation" data-id="graficos_home" class="<?php echo ($active == "graficas")? 'active' : ''; ?>"><a href="#graficos_home" aria-controls="graficos_home" role="tab" data-toggle="tab">Dashboard</a></li>
            <?php } ?>
            <?php if(isset($data["estado"]) && ($data["estado"] != 2 )){?>
            <li class="access pull-right">
                    <div class="col-md-12">
                        <center>
                            <a href="<?php echo site_url("frontend/recomendar"); ?>">
                                <img src="<?php echo $this->session->userdata('new_imagenes')['recomendar_verde']['original'] ?>" alt="recomendar">
                                <br>Recomendar a un amigo
                            </a>
                        </center>
                    </div>
                </li>
                <li class="access pull-right">
                    <div class="col-md-12">
                        <center>
                        <a class="first" href="<?php echo site_url("frontend/inicio"); ?>">
                            <img src="<?php echo $this->session->userdata('new_imagenes')['primerospasos_verde']['original'] ?>" alt="Primeros pasos">
                            <br>Primeros pasos
                        </a>
                        </center>
                    </div>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>


<?php if( in_array("1000", $permisos ) || $isAdmin == 't'){ ?>
  <!-- Tab panes -->
  <div class="tab-content content-config-steps">
    <!--inicio-->
        <?php if(isset($data["estado"]) && ($data["estado"] == 2 )): ?>
        <div role="tabpanel">
            <!--<div class="row">
                <div class="col-md-12 col-xs-12 text-center titulo">
                    <h3 class="content-title">Bienvenido a Vendty Guía de inicio</h3>
                    <h4 class="sub-title">Configura tu negocio y comienza a vender</h4>
                </div>
                <div class="col-md-12 col-xs-12 text-center">
                    <div class="col-md-6 col-md-offset-1 col-xs-12 link">
                        <?php foreach($data["inicio_home"] as $link){ ?>
                        <div class="col-md-4 col-xs-4" style="margin-bottom:  5%;">
                            <a href='<?php echo site_url("$link->url"); ?>' id="<?php echo strtolower(str_replace(' ', '_',$link->nombre)); ?>">
                                <img src="<?php echo base_url('/uploads/inicio/'); ?>/<?= $link->imagen ?>">
                                <h6><?= $link->nombre ?></h6>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="col-md-12 col-xs-6 col-xs-offset-3 tablero">
                            <div class="col-md-12 col-xs-12 checklist">
                                <div class="col-md-9 col-xs-9">
                                    <img src="<?php echo base_url('/uploads/inicio/'); ?>/checklist.svg">
                                </div>
                            </div>
                            <div class="col-md-10 col-md-offset-2 col-xs-9 col-xs-offset-2 title_lista">
                                <h3>Completa tus primeros pasos</h3>
                            </div>
                            <div>
                                <?php foreach($data["inicio_home_tablero"] as $link){
                                    $imagen="/checklist-no.svg";
                                    if(!empty($data["tareas_realizadas"])){
                                        foreach ($data["tareas_realizadas"] as $value) {
                                            if($value->id_paso==$link->id){
                                                $imagen="/checklist-ok.svg";
                                                break;
                                            }
                                        }
                                    }
                                ?>

                                <p>
                                    <a href='<?php echo site_url("$link->url"); ?>' id="<?php echo strtolower(str_replace(' ', '_','link_'.$link->nombre)); ?>">
                                        <img src="<?php echo base_url('/uploads/inicio/').$imagen ?>"><?= $link->nombre ?>
                                    </a>
                                </p>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12 col-xs-12 text-center informacioninicio">

                    <div class="col-md-4 col-xs-12">
                        <div class="col-md-12 col-xs-12">
                            <h4 class="content-title">Ayuda</h4>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info">
                            <div class="col-md-8 col-md-offset-2 col-xs-12">
                                <a href="https://app.hubspot.com/meetings/demostracion/general" target="_blank">
                                    <img src="<?php echo base_url('/uploads/inicio/'); ?>/ayuda-demo.svg">
                                    <h5>Agenda Demo</h5>
                                </a>
                                <span>Agenda una demo con un especialista de Vendty y resuelve todas tus dudas. <b>Duración:</b> 15 min.</span>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info">
                            <div class="col-md-8 col-md-offset-2 col-xs-12">
                                <a href="https://www.youtube.com/playlist?list=PL9IVF-H3Eg6yKAJLTnMIJvB19thFyzoCF" target="_blank">
                                    <img src="<?php echo base_url('/uploads/inicio/'); ?>/ayuda-videos.svg">
                                    <h5>Canal de Videos</h5>
                                </a>
                                <span>Accede a nuestro canal y conoce todas las funcionalidades de Vendty para tu negocio.</span>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info">
                            <div class="col-md-8 col-md-offset-2 col-xs-12">
                                <a href="https://vendty.com/ayuda" target="_blank">
                                    <img src="<?php echo base_url('/uploads/inicio/'); ?>/ayuda-blog.svg">
                                    <h5>Blog de ayuda</h5>
                                </a>
                                <span>Cientos de artículos para que se vuelva todo un experto en el manejo de Vendty.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12 text-left">
                        <div class="col-md-12 col-xs-12">
                            <h4 class="content-title">Video Tutoriales</h4>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info videos_tutoriales">
                            <a href="https://www.youtube.com/watch?v=Ukii3s-tjGE&list=PL9IVF-H3Eg6yKAJLTnMIJvB19thFyzoCF&index=39" target="_blank">
                                <img src="<?php echo base_url('/uploads/inicio/'); ?>/video-tuoriales.svg">
                                <span>Crear Productos</span>
                            </a>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info videos_tutoriales">
                            <a href="https://www.youtube.com/watch?v=FJ3d7YQiavU&index=13&list=PL9IVF-H3Eg6yKAJLTnMIJvB19thFyzoCF" target="_blank">
                                <img src="<?php echo base_url('/uploads/inicio/'); ?>/video-tuoriales.svg">
                                <span>Cómo crear órdenes de compra</span>
                            </a>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info videos_tutoriales">
                            <a href="https://www.youtube.com/watch?v=W8ObIh7A-Ak&index=30&list=PL9IVF-H3Eg6yKAJLTnMIJvB19thFyzoCF" target="_blank">
                                <img src="<?php echo base_url('/uploads/inicio/'); ?>/video-tuoriales.svg">
                                <span>Cómo vender</span>
                            </a>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info videos_tutoriales">
                            <a href="https://www.youtube.com/watch?v=K_IfFgHwKtE&index=49&list=PL9IVF-H3Eg6yKAJLTnMIJvB19thFyzoCF" target="_blank">
                                <img src="<?php echo base_url('/uploads/inicio/'); ?>/video-tuoriales.svg">
                                <span>Informes de ventas</span>
                            </a>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info videos_tutoriales">
                            <a href="https://www.youtube.com/watch?v=QWRiQMgv4V4&list=PL9IVF-H3Eg6yKAJLTnMIJvB19thFyzoCF&index=48" target="_blank">
                                <img src="<?php echo base_url('/uploads/inicio/'); ?>/video-tuoriales.svg">
                                <span>Cómo hacer descuentos en Vendty</span>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <div class="col-md-12 col-xs-12">
                            <h4 class="content-title">Preguntas Frecuentes</h4>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Puedo manejar varios almacenes desde Vendty?</h5>
                                <p>Si. Vendty esta diseñado para que puedas tener tantos almacenes como desees.</p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Qué impresoras soporta Vendty?</h5>
                                <p>Cualquier tipo de impresora de tirillas ejemplo: epson 220, solo debe estar instalada en tu computador</p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Si la impresion me sale larga que hago?</h5>
                                <p>sigue este link y mira el video. <b><a href="" target="_blank">Ver Video</a></b></p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Qué informes le puedo enviar a mi contador?</h5>
                                <p>Vendty no es contable, pero genera informes especializados para el contador los que recomendamos son : informe de transacciones, informe de gastos, informes de órdenes de compra.</p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Cómo abro el cajón monedero?</h5>
                                <p>Conecta tu cajón monedero a la impresora mediante el cable RJ11 y luego a través del software de tu impresora escoge si lo abres antes de imprimir la tirilla o después <b><a href="" target="_blank">Ver Video</a></b></p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Cómo compro Vendty?</h5>
                                <p>Click <b><a href="" target="_blank">Aquí</a></b> y paga a través de tarjeta débito, crédito, Efecty o Baloto.</p>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 item_info preguntas">
                            <div class="col-md-12 col-xs-12">
                                <h5>¿Qué pasa después que pago?</h5>
                                <p>Te llegara un correo para que agendes tu capacitación y puesta en marcha con uno de nuestros especialistas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->

            <?php if(isset($data["estado"]) && $data["estado"] == 2 && $isAdmin == 't' && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 1 && isset($data["steps_complete"]) && $data["steps_complete"] != "finalizado" ): ?>
                <div class="row text-center warning-wizard">
                    <div class="col-md-12">
                        <i class="icon wb-warning" aria-hidden="true"></i>
                         <?= $data["steps_complete"]; ?>
                         <a style="color:#fff; text-decoration:underline;" href="<?= site_url('frontend/init_configuration');?>">TERMINAR MI CONFIGURACIÓN</a>
                         <a class="close-wizard"><i class="ic   on wb-close" aria-hidden="true"></i></a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($data["estado"]) && $data["estado"] == 2 && $isAdmin == 't' && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0 ): ?>
            <div class="container">

                <h3 class="text-center">Asistente de configuración</h3>
                <div class="steps">
                    <div class="step active" data-id="1">
                        <p>1</p>
                    </div>
                    <div class="step" data-id="2">
                        <p>2</p>
                        <span class="line-status"></span>
                    </div>
                    <div class="step" data-id="3">
                        <p>3</p>
                        <span class="line-status"></span>
                    </div>
                    <div class="step" data-id="4">
                        <p>4</p>
                        <span class="line-status"></span>
                    </div>
                </div>
                <div class="content-wizard-steps">
                    <div class="container">

                        <!-- step -->
                        <div class="container step-content active-step" data-id="1">
                            <h5 class="text-center">Selecciona tu tipo de negocio</h5>
                            <div class="row text-center content-type-business">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="col-md-4 type_business">
                                        <div class="content-type" data-type="restaurant">
                                            <img src="<?= base_url()?>public/img/wedding-reception.png" alt="restaurante">
                                            <p>Bar - Restaurante</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 type_business">
                                        <div class="content-type" data-type="retail">
                                            <img src="<?= base_url()?>public/img/full-items-inside-a-shopping-bag.png" alt="restaurante">
                                            <p>Retail</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 type_business">
                                        <div  class="content-type" data-type="fashion">
                                            <img src="<?= base_url()?>public/img/scarf-on-hanger.png" alt="restaurante">
                                            <p>Moda</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons-subcategories">
                                <!--<div class="restaurant-buttons button-subcategorie" data-id="1">
                                    <button class="btn btn-success subcategory" data-name="fast_food">Comida rapida</button>
                                    <button class="btn btn-success subcategory" data-name="buffet">Buffet</button>
                                    <button class="btn btn-success subcategory" data-name="gourmet">Gourmet</button>
                                </div>-->

                                <div class="retail-buttons button-subcategorie" data-id="2">
                                    <button class="btn btn-success subcategory" data-name="stationery">Papeleria</button>
                                    <button class="btn btn-success subcategory" data-name="hardware_store">Ferreteria</button>
                                    <!--<button class="btn btn-success subcategory" data-name="micro_market">Micro mercado</button>-->
                                </div>

                                <!--<div class="fashion-buttons button-subcategorie" data-id="3">
                                    <button class="btn btn-success subcategory" data-name="underwear">Ropa interior</button>
                                    <button class="btn btn-success subcategory" data-name="sports">Deportivo</button>
                                    <button class="btn btn-success subcategory" data-name="formalwear">Formal</button>
                                </div>-->

                            </div>
                        </div>

                        <!-- step 2 -->
                        <div class="container step-content" id="invoice" data-id="2">
                            <div class="col-md-6">
                                <h4 class="text-left"><b>Configuración de plantilla</b></h4>
                                    <div class="row content-items">
                                        <div class="form-group">
                                            <label for="invoice-title" class="col-sm-4 control-label">Nombre empresa</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="invoice-title" v-model="title">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="invoice-address" class="col-sm-4 control-label">Dirección</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="invoice-address" v-model="address">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="invoice-phone" class="col-sm-4 control-label">Teléfono</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="invoice-phone" v-model="phone">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="invoice-footer" class="col-sm-4 control-label">Términos</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="invoice-footer" v-model="footer">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h6>Configuración general</h6>
                                            <div class="form-group content-propine hidden">
                                                <div class="checkbox pl-0">
                                                    <label>
                                                        <input type="checkbox" id="propine" v-model="propine"> Propina
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p>(*) La factura se actualiza automaticamente a medida que se editan los campos.
                                        <br>
                                        (*) Para cambiar el logo haga clic en la imagen de la factura de prueba.
                                    </p>
                            </div>


                            <div class="col-md-6 content-invoice">
                                <div class="title-header">
                                    <h5 class="text-center"><b>Así quedará tu factura de venta!</b></h5>
                                </div>
                                <div class="row invoice-preview">
                                    <!--<div class="content-logo">
                                        <img v-bind:src="image" class="logo" alt="Logo">
                                    </div>-->
                                    <div class="content-logo" id="load-logo">
                                        <input type="file" name="logo" ref="myFiles" class="form-control file-logo" id="file-logo" @change="previewFiles">
                                        <img v-bind:src="image" class="img-responsive img-thumbnail">
                                    </div>
                                    <h4 class="text-center title-store"><b>{{title}}</b></h4>
                                    <p class="text-center">Almacen: Boutique</p>
                                    <div class="col-md-6 text-right pr-0">
                                        <p><b>{{address}}</b></p>
                                    </div>
                                    <div class="col-md-6 text-left">
                                        <p><b>{{phone}}</b></p>
                                    </div>

                                    <div class="col-md-6 text-center">
                                        <p><b>Factura N° 1</b></p>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <p><b>Fecha: <?= date('m-d-Y')?></b></p>
                                    </div>

                                    <div class="col-md-12 items-invoice">
                                        <div class="col-md-3 text-center">
                                            <h5>Ref.</h5>
                                            <p>Quatro</p>
                                            <p>Burger</p>
                                            <p>Nachos</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h5>Cantidad</h5>
                                            <p>1</p>
                                            <p>3</p>
                                            <p>2</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h5>Precio</h5>
                                            <p>$2,500</p>
                                            <p>$10,700</p>
                                            <p>$4,000</p>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <h5>Total</h5>
                                            <p>$2,500</p>
                                            <p>$32,100</p>
                                            <p>$8,000</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12 ">
                                        <div class="col-md-9 pull-right text-right">
                                            <div class="col-md-9">
                                                <p> Valor sin impuesto:<br>
                                                    Efectivo:<br>
                                                    Cambio:<br>
                                                    Total venta:<br>
                                                </p>
                                            </div>
                                            <div class="col-md-2 pl-0">
                                                <p> $70,000 <br>
                                                    $70,000<br>
                                                    $0<br>
                                                    $70,000<br>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="text-center mt-1" style="font-weight: bold;margin-top:0px;">{{footer}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step 3 -->
                        <div class="container step-content" id="shop" data-id="3">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <h4><b>Configuración general</b></h4>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-4 control-label">Nombre de tu tienda:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="name" id="name" v-model="shop_name" class="form-control ml-0 mb-1" @keyup="localDomain">
                                            <small class="warning">{{error}}</small>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="local_domain" class="col-sm-4 control-label">Dominio Local:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="local_domain" id="local_domain" v-model="local_domain" class="ml-0 form-control" readonly="">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dominio" class="col-sm-4 control-label">Dominio:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="dominio" id="dominio" v-model="domain" <?php echo ($data['type_licence'] != 3)? 'disabled' : '' ?> class="form-control ml-0">
                                            <?php if($data['type_licence'] != 3): ?>
                                                <small>Para poder configurar un dominio personalizado debe tiener una Plan Empresarial. </small>
                                            <?php endif; ?>

                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <br><br>
                                    <div class="form-group">
                                        <label for="country" class="col-sm-4 control-label">País:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="country" id="country" class="form-control ml-0" v-model="country">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="currency" class="col-sm-4 control-label">Moneda:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="currency" id="currency" class="form-control ml-0" v-model="currency">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row ml-0">
                                        <div class="col-md-12">
                                            <h4><b>Plantilla</b></h4>
                                            <p>Selecciona la plantilla que mas se ajuste a tu tipo de negocio</p>
                                        </div>
                                    </div>
                                    <div class="multiple-templates">
                                        <?php foreach(get_image_templates_shop() as $template):  ?>
                                            <div class="col-md-4" data-id="">
                                                <div class="template content-template" @click="activeTemplate('<?= $template->nombre;?>')">
                                                    <img src="http://admintienda.vendty.com/storage/templates/<?= $template->ruta_img; ?>" alt="image">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- step 4 -->
                        <div class="container step-content  text-center" id="finalize" data-id="4">
                                <h2>FELICITACIONES!</h2>
                                <h4>Has terminado el proceso de configuración correctamente</h4>
                                <br>
                                <div class="actions">
                                    <button class="btn-content" id="sale_now" v-on:click="sale_now">VENDER AHORA</button>
                                    <button class="btn-content" v-on:click="sale_now">FINALIZAR</button>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="actions_configuration">
                    <div class="col-md-3 pull-left mt-1">
                        <a class="btn btn-success" href="<?= site_url('frontend/skip_configuration')?>">Saltar configuración</a>
                    </div>
                    <div class="col-md-3 pull-right text-left mt-1">
                        <button class="btn btn-success" id="prev-step" step='1'>Anterior</button>
                        <button class="btn btn-success" id="next-step" step='1'>Siguiente</button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <!--fin inicio-->

    <div role="tabpanel" class="tab-pane <?= ($active == "graficas" || in_array("1000", $permisos))? 'active' : ''; ?>" id="graficos_home">
        <!-- Gráficos -->

    <div class="row">

        <div class="col-xs-12 col-md-4 col-md-offset-4">

            <div id="listaAlmacenes" class="example-col panel" style="border:none;">

                <div class="row" style="height:20px; padding-top: 0px;">

                    <div class="col-xs-4 col-md-4">
                        <div class="opcGraph"><strong>Almacenes</strong></div>
                    </div>

                    <div class="col-xs-8 col-md-8">

                        <select id="almacenesSelect" class="form-control input-sm" style=" height: 24px;">

                            <?php if( $isAdmin == 't' || $isAdmin == 'a'){ ?>

                            <option value="0">Todos los almacenes</option>

                            <?php } ?>

                            <?php foreach ($data['almacenes'] as $value) { ?>

                                <option  value="<?php echo $value->id ?>"><?php echo $value->nombre ?></option>

                            <?php } ?>

                        </select>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-xs-12 col-md-4">
            <a href="<?php echo base_url('index.php/frontend/index') ?>">
                <h4 style="color:#139dbf;">¡Echale un vistazo nuestro nuevo Dashboard!</h4>
            </a>
        </div>


        <div class="col-xs-12 col-md-12">
            <div class="alert alert-info fade in alert-dismissible" style="background: #00baff">
                <a href="#" class="close" data-dismiss="alert" aria-label="cerrar" title="cerrar">×</a>
                <strong><i class="fa fa-camera" aria-hidden="true"></i></strong> Maneja el inventario de tu negocio de forma eficiente con Vendty ¡Inscríbete aquí! - <a style="color: white;text-decoration: underline;" href="https://my.demio.com/ref/ooZr5MYikNpWGCzF" target="_blank" aria-label="https://my.demio.com/ref/ooZr5MYikNpWGCzF" title="https://my.demio.com/ref/ooZr5MYikNpWGCzF">Agendar webinar</a>
            </div>
        </div>




       <!--  <div class="col-xs-6 col-md-4" style="float:right">

            <div id="btnPeriodo" class="example-col panel" style="float:right; width:250px">

                <div class="row" style="height:20px; padding-top: 0px;">

                    <div class="col-xs-4"><div class="opcGraph"><a href="javascript:setDias(7)"><strong <?php if($diasSel == "7") echo 'class="diaSel"'; ?> >7 días</strong></a></div></div>

                    <div class="col-xs-4"><div class="opcGraph"><a href="javascript:setDias(14)"><strong <?php if($diasSel == "14") echo 'class="diaSel"'; ?> >14 días</strong></a></div></div>

                    <div class="col-xs-4"><div class="opcGraph"><a href="javascript:setDias(30)"><strong <?php if($diasSel == "30") echo 'class="diaSel"'; ?> >30 días</strong></a></div></div>

                </div>

            </div>

        </div> -->

    </div>





    <div id="listaAlmacenes" class="row">

        <div class="col-xs-12 col-md-9">



            <div class="example-col panel panelA">

                <hr>

                <div class="panel-body">

                    <div class="counter-number-group margin-bottom-10" style="text-align: center;margin-top: -20px;">

                        <p style="font-weight: 300;font-size: 20px;margin-top: -20px;">

                            Gráfico de ventas por hora del día de hoy

                        </p>

                    </div>

                    <div class="flot-chart" style="height: 160px">

                        <div class="flot-chart-content" id="flot-line-chart"></div>

                    </div>



                </div>



            </div>



        </div>

        <div class="col-xs-12 col-md-3">

            <div class="panel panelA">

                <div class="panel-body text-center h-200">

                    <i class="pe-7s-graph1 fa-4x"></i>

                   <!-- <h1 id="str_m_total_factura" class="m-xs">$<?php echo number_format($data['meta_diaria']["total_ventas"]-$data['meta_diaria']["total_devolucion"] )?></h1>-->
                    <h1 id="str_m_total_factura" class="m-xs"><?php echo $simbolo; echo $ci->opciones_model->formatoMonedaMostrar(($data['meta_diaria']["total_ventas"]-$data['meta_diaria']["total_devolucion"] ));?></h1>

                    <h3 class="font-extra-bold no-margins text-success">Venta del Día</h3>

                    <p>Esta cifra corresponde a

                        <strong id="str_m_totalfacturas" style="color: #62cb31;font-size: 20px;">

                            <?php echo $data['meta_diaria']['total_facturas'] ?>

                        </strong> facturas

                    </p>

                </div>

            </div>

        </div>

    </div>

        <!--
        <link href="<?php // echo base_url(); ?>public/charts/app.5e179a90.css" rel=preload as=style>
        <link href="<?php // echo base_url(); ?>public/charts/js/app.54ed4312.js" rel=preload as=script>
        <link href="<?php // echo base_url(); ?>public/charts/js/chunk-vendors.47ff23d9.js" rel=preload as=script>
        <link href="<?php // echo base_url(); ?>public/charts/app.5e179a90.css" rel=stylesheet>

        <div id=app></div>
        <script src="<?php // echo base_url(); ?>public/charts/js/chunk-vendors.47ff23d9.js"></script>
        <script src="<?php // echo base_url(); ?>public/charts/js/app.54ed4312.js"></script>
        -->

    <?php

        if ($data['meta_diaria']["meta_almacen"] == 0){
            $porcentaje = 0.01 ;

          $metaFaltan = $data['meta_diaria']["meta_almacen"] ;

        }else{
            $porcentaje = ( $data['meta_diaria']["total_ventas"]-$data['meta_diaria']["total_devolucion"] ) * 100 / ( $data['meta_diaria']["meta_almacen"] ) ;

            $metaFaltan = ($data['meta_diaria']["meta_almacen"] - ($data['meta_diaria']["total_ventas"]-$data['meta_diaria']["total_devolucion"]));

        }

    ?>

    <script type="text/javascript">

        var porcentaje_meta = <?php echo $porcentaje; ?>;

    </script>

    <div id="listaAlmacenes" class="row">

        <div class="col-xs-12 col-md-4">

            <div class="example-col panel panelB">

                <hr>

                <div class="panel-body">

                    <div class="ct-chart chart-pie-right width-150 height-150" style="position: relative; margin:0px; width: auto !important; text-align: center;">



                        <div id="gauge"></div>



                    </div>

                    <div class="counter counter-sm text-left">

                        <div class="counter-number-group">

                            <div class="counter-number-related" style="text-align: center;"><i class="counter-icon orange-600 margin-right-5 wb-stats-bars"></i> Meta <strong id="meta_almacen"><?php echo $simbolo; echo $ci->opciones_model->formatoMonedaMostrar( $data['meta_diaria']["meta_almacen"] ); ?></strong></div>



                            <?php if( intval($metaFaltan) > 0){ ?>

                                <div class="counter-number-related" style="text-align: center;"><i class="counter-icon green-600 margin-right-5 wb-graph-up"></i> Faltan <strong id="faltante_almacen"><?php echo $simbolo;  echo $ci->opciones_model->formatoMonedaMostrar( $metaFaltan ); ?></strong></div>

                            <?php }else{ ?>

                                <div class="counter-number-related" style="text-align: center;"> <strong style=" color: #67b168;">¡Has completado la meta!</strong></div>

                            <?php } ?>



                        </div>

                    </div>

                </div>



            </div>

        </div>

        <div class="col-xs-12 col-md-4">

            <div class="example-col panel panelC " id="messge">

                <h3  style="background: #fff;color: #62cb31;"> Productos mas vendidos</h3>


                <div class="panel-body list-item-container" style="padding-bottom:0px !important;">

                <?php



                    if (count($data['productos_relevantes_hoy']) != 0) {



                    $pr1 = isset( $data['productos_relevantes_hoy'][0] ) ? $data['productos_relevantes_hoy'][0] : false;

                    $pr2 = isset( $data['productos_relevantes_hoy'][1] ) ? $data['productos_relevantes_hoy'][1] : false;

                    $pr3 = isset( $data['productos_relevantes_hoy'][2] ) ? $data['productos_relevantes_hoy'][2] : false;



                    $img1 = $pr1 ? $pr1["imagen"] == "" ? "dragDrop.jpg" : $pr1["imagen"] : 0;

                    $img2 = $pr2 ? $pr2["imagen"] == "" ? "dragDrop.jpg" : $pr2["imagen"] : 0;

                    $img3 = $pr3 ? $pr3["imagen"] == "" ? "dragDrop.jpg" : $pr3["imagen"] : 0;



                ?>
                    <p class="">El precio que se muestra es el precio configurado en el producto, no aplica para promociones.</p>

                    <?php if($pr1) { ?>

                        <div class="list-item">
                        <small><b>Precio - Utilidad</b></small>
                        <!--<h3 class="no-margins font-extra-bold text-success">$<?php $pr1 ? print_r( number_format( $pr1["precio_venta"] ) ) : 0; ?> - $<?php $pr1 ? print_r( number_format( $pr1["utilidad"] ) ) : 0; ?></h3>-->
                        <h3 class="no-margins font-extra-bold text-success"><?php echo $simbolo; ?><?php $pr1 ? print_r( $ci->opciones_model->formatoMonedaMostrar(( $pr1["precio_venta"] ) )) : 0; ?> - $<?php $pr1 ? print_r( $ci->opciones_model->formatoMonedaMostrar(( $pr1["utilidad"] ) )) : 0; ?></h3>

                        <!--<h3 class="no-margins font-extra-bold text-success">$ <?php $pr1 ? print_r( number_format( $pr1["utilidad"] ) ) : 0; ?></h3>-->

                        <small><?php $pr1 ? print_r( $pr1["nombre"] ) : 0; ?></small>

                        <div class="pull-right font-bold"><?php $pr1 ? print_r( $ci->opciones_model->formatoMonedaMostrar(( $pr1["count_productos"] )) ) : 0; ?> unidades vendidas</div>

                        </div>

                    <?php } ?>

                    <?php if($pr2) { ?>

                        <div class="list-item">
                        <small><b>Precio - Utilidad</b></small>
                        <h3 class="no-margins font-extra-bold text-success"><?php echo $simbolo; ?><?php $pr2 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr2["precio_venta"] ) ) : 0; ?> - $<?php $pr2 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr2["utilidad"] ) ) : 0; ?></h3>
                        <!--<h3 class="no-margins font-extra-bold text-success">$ <?php $pr2 ? print_r( number_format( $pr2["utilidad"] ) ) : 0; ?></h3>-->

                        <small><?php $pr2 ? print_r( $pr2["nombre"] ) : 0; ?></small>

                        <div class="pull-right font-bold"><?php $pr2 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr2["count_productos"] ) ) : 0; ?> unidades vendidas</div>

                    </div>

                    <?php } ?>

                    <?php if($pr3) { ?>

                        <div class="list-item">
                        <small><b>Precio - Utilidad</b></small>
                        <h3 class="no-margins font-extra-bold text-success"><?php echo $simbolo; ?><?php $pr3 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr3["precio_venta"] ) ) : 0; ?> - $<?php $pr3 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr3["utilidad"] ) ) : 0; ?></h3>
                        <!--<h3 class="no-margins font-extra-bold text-success">$ <?php $pr3 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr3["utilidad"] ) ) : 0; ?></h3>-->

                        <small><?php $pr3 ? print_r( $pr3["nombre"] ) : 0; ?></small>

                        <div class="pull-right font-bold"><?php $pr3 ? print_r( $ci->opciones_model->formatoMonedaMostrar( $pr3["count_productos"] ) ) : 0; ?> unidades vendidas</div>

                        </div>

                    <?php } ?>

             <?php

                    // fin si no hay productos vendidos el dia de hoy

                } else {



              ?>

                <div class="list-item">

                    <h4 style=" text-align: center; color: #b31217;">No hay productos vendidos el día de hoy</h4>

                </div>



            <?php

                    // Si no hay productos vendidos

                }

            ?>



                    </ul>

                </div>

            </div>

        </div>

        <div class="col-xs-12 col-md-4">

            <div class="hpanel stats panel panelA">

                    <div class="panel-body h-200">

                        <div class="stats-title pull-left">

                            <h3 style="text-align: center;background: #fff;color: #62cb31;">Gastos</h3>

                        </div>

                        <div class="stats-icon pull-right">

                            <i class="pe-7s-cash fa-4x"></i>

                        </div>

                        <div class="clearfix"></div>

                        <div class="flot-chart">

                            <div class="flot-chart-content" id="flot-income-chart"></div>

                        </div>

                        <div class="m-t-xs">



                            <div class="row">

                                <div class="col-xs-5">

                                    <small class="stat-label">Ayer</small>

                                    <h4 id="total_gastos_ayer"><?php echo $simbolo; echo $ci->opciones_model->formatoMonedaMostrar($data['total_gastos_ayer']) ?></h4>



                                </div>

                                <div class="col-xs-7">

                                    <small class="stat-label">Hoy</small>

                                    <h4 id="total_gastos"><?php echo $simbolo;  echo $ci->opciones_model->formatoMonedaMostrar($data['total_gastos']) ?> </h4>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

        </div>

    </div>

    <div class="row">



        <div style="float:left" class="col-xs-6 col-md-4">

            <div style="float: left;width:250px;height: auto;" class="example-col panel" id="btnPeriodo">

                <div style="height:20px; padding-top: 0px;" class="row">

                    <div class="col-xs-4"><div class="opcGraph"><a  href="javascript:setDias(7)"><strong id="strong_7_dias" class="diaSel strong_dias">7 días</strong></a></div></div>

                    <div class="col-xs-4"><div class="opcGraph"><a href="javascript:setDias(14)"><strong id="strong_14_dias" class="strong_dias ">14 días</strong></a></div></div>

                    <div class="col-xs-4"><div class="opcGraph"><a href="javascript:setDias(30)"><strong id="strong_30_dias" class="strong_dias ">30 días</strong></a></div></div>

                </div>

            </div>

        </div>

    </div>





    <div id="listaAlmacenes" class="row">





        <div class="col-xs-12 col-md-4">

            <div class="hpanel">

                <div class="panel-body text-center h-200">

                    <div class="stats-title pull-left">

                            <h4 style="background: #fff;color: #62cb31;">Total de ventas</h4>

                        </div>

                    <?php



                        $totalVentas = 0;

                        foreach ( $data['ventas'] as $value ) {

                            $totalVentas = $totalVentas + $value["total_venta"];

                        }



                        $totalVentas = $ci->opciones_model->formatoMonedaMostrar($totalVentas);



                    ?>

                    <div class="clearfix"></div>

                    <i class="pe-7s-global fa-4x"></i>

                    <h1 class="m-xs text-success" id="str_total_ventas"><?php echo $simbolo;  echo $totalVentas; ?></h1>

                    <small id="small_texto_total_venta">El total de las ventas en el periodo, es el total pagado en facturas, no se tienen en cuenta devoluciones, ni pagos con notas credito.</small>

                </div>

            </div>

        </div>

        <div class="col-xs-12 col-md-4">

            <div class="hpanel">

                <div class="panel-body text-center h-200">

                    <div class="stats-title pull-left">

                            <h4 style="background: #fff;color: #62cb31;">Total de utilidad</h4>

                        </div>

                        <?php



                            $totalUtilidad = $ci->opciones_model->formatoMonedaMostrar( $data['utilidad'][0]['total_utilidad']);



                        ?>

                    <div class="clearfix"></div>

                    <i class="pe-7s-graph2 fa-4x"></i>

                    <h1 class="m-xs" id="str_total_utilidad"><?php echo $simbolo;  echo $totalUtilidad; ?></h1>

                    <small id="small_texto_utilidad">El margen de utilidad se calcula por los productos vendidos, los productos devueltos restan del valor de utilidad, no se tienen en cuenta formas de pago.</small>

                </div>

            </div>

        </div>

        <div class="col-xs-12 col-md-4">

                <div class="hpanel stats">

                    <div class="panel-body h-200">

                        <div class="stats-title pull-left">

                            <h4 style="background: #fff;color: #62cb31;">Categorías más vendida </h4>

                        </div>

                        <div class="stats-icon pull-right">

                            <i class="pe-7s-shuffle fa-4x"></i>

                        </div>

                        <div class="clearfix"></div>

                        <div class="flot-chart">

                            <div class="flot-chart-content" id="flot-pie-chart" style="height: 112px"></div>

                        </div>

                    </div>

                </div>

        </div>



    </div>



    <!-- <div id="listaAlmacenes" class="row">



        <div class="col-xs-12 col-md-4">

            <div class="example-col panel panelHeightFixed">

                <div class="panel-heading">

                    <h3 class="panel-title">Ventas por Almacén</h3>

                </div>

                <hr>

                <div class="counter counter-sm text-left">

                    <div class="counter-number-group" style=" text-align: center;">

                        <span class="counter-icon orange-600 margin-right-5"><i class="wb-stats-bars"></i></span>

                        <span class="counter-number-related">El almacén con mayor ventas es <strong><?php echo $data['ventas_almacen'][0]["nombre"] ?></strong> <strong>( $ <?php echo number_format( $data['ventas_almacen'][0]["total_venta"] ) ?> )</strong></span>

                    </div>







                </div>

                <div class="example text-center">





                    <div id="chart_pie_almacenes" class="c3" style="height: 280px; position: relative; opacity:0.8;">



                    </div>





                </div>



            </div>











        </div>



        <div class="col-xs-12 col-md-6">

            <div class="example-col panel panelHeightFixed">

                <div class="panel-heading">

                    <h3 class="panel-title"><span class="counter-icon margin-right-5"><i class="wb-shopping-cart"></i></span>Productos Más Populares</h3>

                </div>

                <hr>

                <div class="counter counter-sm text-left">

                    <div class="counter-number-group" style=" text-align: center;">

                        <span class="counter-icon orange-600 margin-right-5"><i class="wb-stats-bars"></i></span>

                        <span class="counter-number-related">El producto más popular es: <strong>( <?php echo $data['prod_populares'][0]["nombre"] ?> )</strong></span>

                    </div>

                </div>

                <div class="example text-center">

                    <canvas id="chart-bar" style="width: 100%"/>

                </div>



            </div>

        </div>



    </div>



    <div id="listaAlmacenes" class="row">



        <div class="col-xs-12 col-md-6">

            <div class="example-col panel panelHeightFixed">



                <div class="panel-heading">

                    <h3 class="panel-title"><span class="counter-icon margin-right-5"><i class="wb-clipboard"></i></span>Stock Mínimo</h3>

                </div>



                <hr>

                <div class="counter counter-sm text-left">

                    <div class="counter-number-group" style=" text-align: center;">

                        <span class="counter-icon orange-600 margin-right-5"><i class="wb-stats-bars"></i></span>



                        <?php if ( count($data['stock_minimo']) ){ ?>



                            <span class="counter-number-related">Sólo quedan <strong> <?php  echo $data['stock_minimo'][0]["unidades"]; ?> </strong> unidades del producto: <strong>( <?php  echo $data['stock_minimo'][0]["nombre"]; ?> )</strong> </span>



                        <?php }else{ ?>



                            <span class="counter-number-related"> No tiene productos creados</span>



                        <?php } ?>

                    </div>

                </div>

                <div class="example text-center">



                    <canvas id="chart-bar-h" style="width: 100%"/>



                </div>







            </div>

        </div>



        <div class="col-xs-12 col-md-6">

            <div class="example-col panel panelHeightFixed">

                <div class="panel-heading">

                    <h3 class="panel-title"><span class="counter-icon margin-right-5"><i class="wb-shopping-cart"></i></span> Categoría más Vendida</h3>

                </div>

                <hr>

                <div class="counter counter-sm text-left">

                    <div class="counter-number-group" style=" text-align: center;">

                        <span class="counter-icon orange-600 margin-right-5"><i class="wb-stats-bars"></i></span>

                        <span class="counter-number-related">La categoría más vendida es <strong><?php echo $data['categorias_vendidas'][0]["nombre"] ?></strong> <strong>( $ <?php echo number_format( $data['categorias_vendidas'][0]["total"] ) ?> )<strong></span>

                    </div>

                </div>

                <div class="example text-center">

                    <canvas id="chart-polar" style="width: 100%"/>

                </div>



            </div>

        </div>



    </div>

 -->


        <script>
            $(".modal-wizard").modal('show');
            $(".close-wizard").click(function(){
                $(".modal-wizard").modal('hide');
            })
        </script>


    <script type="text/javascript">



        var almacenSeleccionado = <?php echo $almacenSel; ?>;

        var diasSeleccionados = <?php echo $diasSel; ?>;





        // SET Select option value

        $("#almacenesSelect").val( almacenSeleccionado );





        $('#almacenesSelect').on('change', function (e) {



            almacenSeleccionado = $(" option:selected",this).val();

            getDataAjax();



        });





        function setDias(dias){

            $(".strong_dias").removeClass('diaSel');

            $("#strong_"+dias+"_dias").addClass('diaSel');

            diasSeleccionados = dias;

            getDataAjax();



        }





        function getDataAjax() {



            //window.location.replace("<?php echo site_url("frontend/index"); ?>/?alm="+almacenSeleccionado+"&dia="+diasSeleccionados);

            $.ajax({

                type: "POST",

                url: '<?php echo site_url("frontend/getAjaxDashboard"); ?>/',

                data: {almacen:almacenSeleccionado,dias_seleccionados: diasSeleccionados},

                cache: false,

                dataType: 'json',

                success: function (response) {

                   console.log(response);

                    updateReport(response);
                    renderPlotxHora(response)

                    var meta = ((response.meta_diaria.total_ventas-response.meta_diaria.total_devolucion) * 100) / response.meta_diaria.meta_almacen;
                    var metaFaltan = (response.meta_diaria.meta_almacen - (response.meta_diaria.total_ventas-response.meta_diaria.total_devolucion));

                    $('#meta_almacen').html(response.simbolo+' '+response.meta_diaria.meta_almacen);
                    $('#faltante_almacen').html(response.simbolo+' '+metaFaltan);
                    gaugeMeta(meta);


                },

                error: function (xhr, textStatus, errorThrown) {

                    alert(textStatus + " : " + errorThrown);

                }

            });



        }



        function updateReport( respuesta ){

            //total de ventas periodo

            var total_ventas = 0;

            var datos_categorias = [];

            var total_utilidad = 0;

            var m_total_factura = 0;



          //  var colores = ['#9b59b6','#3498db','#62cb31','#ffb606','#e67e22'];
            var colores = ['#62cb31','#505050','#c0eaad','#0e0e0e','#316b16'];


            $.each(respuesta.ventas,function(index,value){

                total_ventas+=Number(value.total_venta);

            });


            $.each(respuesta.utilidad,function(index,value){

                total_utilidad+=Number(value.total_utilidad);

            });
           //total_utilidad = Number(respuesta.utilidad.total_utilidad);



            $.each(respuesta.categorias_vendidas,function(index,value){

                datos_categorias.push({label:value.nombre,data:value.total,color:colores[index]})

            });
            //Productos Relevantes
            var html = '';
            $.each(respuesta.productos_relevantes_hoy,function(index,value){
                html += '<div class="list-item">'+
                            /*'<h3 class="no-margins font-extra-bold text-success">$ '+value.utilidad+'</h3>'+*/
                            '<small><b>Precio - Utilidad</b></small>'+
                            '<h3 class="no-margins font-extra-bold text-success">'+respuesta.simbolo+''+number_format(value.precio_venta)+' - '+respuesta.simbolo+number_format(value.utilidad)+'</h3>'+
                            '<small>'+value.nombre+'</small>'+
                            '<div class="pull-right font-bold">'+value.count_productos+' unidades vendidas</div>'+
                        '</div>';
            });
            m_total_factura = respuesta.meta_diaria.total_ventas-respuesta.meta_diaria.total_devolucion;

            $('.list-item-container').html(html);


            $("#str_total_utilidad").html(respuesta.simbolo+''+number_format(total_utilidad));

            $("#str_total_ventas").html(respuesta.simbolo+''+number_format(total_ventas));
            $("#str_m_total_factura").html(respuesta.simbolo+''+number_format(m_total_factura));
            $("#str_m_totalfacturas").html(number_format(respuesta.meta_diaria.total_facturas));
            $("#total_gastos").html(respuesta.simbolo+''+number_format(respuesta.total_gastos));
            $("#total_gastos_ayer").html(respuesta.simbolo+''+number_format(respuesta.total_gastos_ayer));
            categorias_mas_vendidas(datos_categorias);

        }



        function number_format (number) {
            // Strip all characters but numerical ones.

            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');

            var n = !isFinite(+number) ? 0 : +number,

                prec = !isFinite(+0) ? 0 : Math.abs(0),

                sep =  ',' ,

                dec ='.' ,

                s = '',

                toFixedFix = function (n, prec) {
                    alert("aqui");
                    var k = Math.pow(10, prec);
                    if (__decimales__ == 0) {
                        return '' + Math.round(n * k) / k;
                    }else{
                        return '' + redondear(n * k) / k;
                    }

                };

            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            if (__decimales__ == 0) {
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            }else{
                 s = (prec ? toFixedFix(n, prec) : '' + redondear(n)).split('.');
            }

            if (s[0].length > 3) {

                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);

            }

            if ((s[1] || '').length < prec) {

                s[1] = s[1] || '';

                s[1] += new Array(prec - s[1].length + 1).join('0');

            }



            return s.join(dec);

        }





        // ================================================

        // CHART JS

        // ================================================





        var randomScalingFactor = function () {
            if (__decimales__ == 0) {
                 return Math.round(Math.random() * 100)
            }else{
                 return redondear(Math.random() * 100)
            }


        };

        // INFO DATA





        // Ventas

        var lineChartData = {

            labels: [

                <?php foreach ( $data['ventas'] as $value ) { ?>

                   "<?php echo $value["dia"] ?>",

                <?php } ?>

            ],

            datasets: [

                {

                    label: "Primero",

                    fillColor: "rgba(50, 166, 193,0.05)",

                    strokeColor: "rgba(50, 166, 193,0.4)",

                    pointColor: "rgba(50, 166, 193,0.9)",

                    segmentStrokeWidth: 5,

                    pointStrokeColor: "#fff",

                    pointHighlightFill: "#fff",

                    pointHighlightStroke: "rgba(220,220,220,1)",

                    data: [

                        <?php foreach ( $data['ventas'] as $value ) { ?>

                            <?php echo $value["total_venta"] ?>,

                        <?php } ?>

                    ]

                }

            ]



        }













        // Categoria más vendida

        var radarChartData = {

            labels: [

                <?php foreach ( $data['categorias_vendidas'] as $value ) { ?>

                    "<?php echo $value["nombre"] ?>",

                <?php } ?>

                ],

            datasets: [

                {

                    label: "Segundo",

                    fillColor: "rgba(151,187,205,0.2)",

                    strokeColor: "rgba(151,187,205,1)",

                    pointColor: "rgba(151,187,205,1)",

                    pointStrokeColor: "#fff",

                    pointHighlightFill: "#fff",

                    pointHighlightStroke: "rgba(151,187,205,1)",

                    data: [

                        <?php foreach ( $data['categorias_vendidas'] as $value ) { ?>

                            <?php echo $value["total"] ?>,

                        <?php } ?>

                    ]

                }

            ]

        };







        // Productos más populares

        var barChartData = {

            labels: [

                <?php foreach ( $data['prod_populares'] as $value ) { ?>

                    '<?php echo $value["nombre"] ?>',

                <?php } ?>

            ],

            datasets: [

                {

                    fillColor: "rgba(70, 190, 138,0.6)",

                    data: [

                        <?php foreach ( $data['prod_populares'] as $value ) { ?>

                            <?php echo $value["count_productos"] ?>,

                        <?php } ?>

                    ]

                }

            ]



        }











        var dataPolar = [

        <?php if (isset($data['categorias_vendidas'][0]) ){ ?>



            {

                value: <?php echo $data['categorias_vendidas'][0]["total"]; ?>,

                color: "rgba(209,109,95,1)",

                highlight: "rgba(209,109,95,0.6)",

                label: "<?php echo $data['categorias_vendidas'][0]["nombre"]; ?>"

            },



        <?php } ?>

        <?php if (isset($data['categorias_vendidas'][1]) ){ ?>

            {

                value: <?php echo $data['categorias_vendidas'][1]["total"]; ?>,

                color: "rgba(202,223,137,1)",

                highlight: "rgba(202,223,137,6)",

                label: "<?php echo $data['categorias_vendidas'][1]["nombre"]; ?>"

            },

        <?php } ?>

        <?php if (isset($data['categorias_vendidas'][2]) ){ ?>

            {

                value: <?php echo $data['categorias_vendidas'][2]["total"]; ?>,

                color: "rgba(190,162,235,1)",

                highlight: "rgba(190,162,235,0.6)",

                label: "<?php echo $data['categorias_vendidas'][2]["nombre"]; ?>"

            },

        <?php } ?>

        <?php if (isset($data['categorias_vendidas'][3]) ){ ?>

            {

                value: <?php echo $data['categorias_vendidas'][3]["total"]; ?>,

                color: "rgba(137,213,223,1)",

                highlight: "rgba(137,213,223,0.6)",

                label: "<?php echo $data['categorias_vendidas'][3]["nombre"]; ?>"

            },

        <?php } ?>

        <?php if (isset($data['categorias_vendidas'][4]) ){ ?>

            {

                value: <?php echo $data['categorias_vendidas'][4]["total"]; ?>,

                color: "rgba(138,205,171,1)",

                highlight: "rgba(138,205,171,0.6)",

                label: "<?php echo $data['categorias_vendidas'][4]["nombre"]; ?>"

            },

        <?php } ?>



        ];





        //================================================================





        // Ventas

        function graphLines() {



            var ctxLine = document.getElementById("chart-line").getContext("2d");

            window.myLine = new Chart(ctxLine).Line(lineChartData, {

                responsive: true,

                maintainAspectRatio: false,

                animation: false,

                scaleGridLineColor: "rgba( 20,20,20,0.10)",

                scaleFontColor: "rgba( 20,20,20,0.6 )",

            });

        }











        // Categoría más vendida

        function graphPolar() {



            var ctxPolar = document.getElementById("chart-polar").getContext("2d");

            window.myPolarArea = new Chart(ctxPolar).PolarArea(dataPolar, {

                scaleLineColor: "rgba(20,20,20,.1)",

                scaleShowLabels: false,

                responsive: true,

                animation: false,

                segmentStrokeColor: "transparent",

                animationEasing: "easeOutQuart"

            });

        }



        //

        function graphBar() {



            var ctxBar = document.getElementById("chart-bar").getContext("2d");

            window.myBar = new Chart(ctxBar).Bar(barChartData, {

                responsive: true,

                animation: false,

                scaleFontSize: 8,

                scaleGridLineColor: "rgba(20,20,20,0.10)",

                scaleFontColor: "rgba(20,20,20,0.60)",

                segmentStrokeColor: "transparent",

            });

        }





        function graphBarH() {



            var ctx = $('#chart-bar-h').get(0).getContext('2d');

            var data = {

                labels: [

                    <?php foreach ( $data['stock_minimo'] as $value ) { ?>

                        '<?php echo $value["nombre"] ?>',

                    <?php } ?>

                ],

                datasets: [

                    {

                        label: 'Months',

                        fillColor: 'rgba(4,151,179,0.5)',

                        highlightFill: 'rgba(0,163,124,0.5)',

                        data: [

                            <?php foreach ( $data['stock_minimo'] as $value ) { ?>

                                <?php echo $value["unidades"] ?>,

                            <?php } ?>

                        ]

                    }

                ]

            };



            var options = {

                barStrokeWidth: 1,

                scaleFontSize: 8,

                responsive: true,

                animation: false,

                barShowStroke: false

            };



            new Chart(ctx).HorizontalBar(data, options);





        }



        // ================================================

        // CHART JS FIN

        // ================================================





        // ================================================

        // C3 CHART

        // ================================================



        function c3Lines() {

            var chart = c3.generate({

                bindto: '#chart_utilidad',

                    data: {

                        x : 'x',

                        columns: [

                            ['x',

                                <?php echo $data['utilidad'][0]['dia'] ?>

                            ],

                            ['Utilidad',

                                <?php echo $data['utilidad'][0]['total_utilidad'] ?>



                            ],

                        ],

                        type: 'area'

                    },

                    axis: {

                        x: {

                            type: 'category',

                            tick: {

                                rotate: 0,

                                multiline: false

                            },

                            height: 30

                        }

                    }

            });

        }





        function c3Pie() {

            var chart = c3.generate({

                bindto: '#chart_pie_almacenes',

                data: {

                    // iris data from R

                    columns: [

                        <?php foreach ( $data['ventas_almacen'] as $value ) { ?>

                            ['<?php echo $value["nombre"] ?>', <?php echo $value["total_venta"] ?> ],

                        <?php } ?>

                    ],

                    type: 'pie',

                },

                pie: {

                    label: {

                        format: function (value, ratio, id) {

                            return "$ " + value;

                        }

                    }

                },

                color: {

                    /*pattern: ['#1f77b4', '#aec7e8']*/

                }

            });

        }











        // ================================================

        // GAUGE - Meta diaria

        // ================================================



        var gauge;



        function gaugeMeta(meta = null) {
            if(meta != null )
                porcentaje_meta = meta;
            /*var opts = {

                lines: 12, // The number of lines to draw

                angle: 0.5, // The length of each line

                lineWidth: 0.1, // The line thickness

                colorStart: '#28A048', // Colors

                colorStop: '#46be8a', // just experiment with them

                strokeColor: '#EEEEEE', // to see which ones work best for you

                generateGradient: false,

                type: 'gauge'

            };



            var target = document.getElementById('gauge'); // your canvas element



            gauge = new Donut(target).setOptions(opts); // create sexy gauge!

            gauge.maxValue = <?php echo $data['meta_diaria']["meta_almacen"]; ?> // set max gauge value

            gauge.animationSpeed = 50; // set animation speed (32 is default value)

            gauge.set(0.1); // set actual value

            //gauge.setTextField( document.getElementById("gaugeTxt") );



            var textRenderer = new TextRenderer(document.getElementById('gaugeTxt'))

            textRenderer.render = function (gauge) {

                percentage = gauge.displayedValue / gauge.maxValue

                this.el.innerHTML = (percentage * 100).toFixed(1) + ""

            };

            gauge.setTextField(textRenderer);*/

            var chart = c3.generate({

                bindto: '#gauge',

                data: {

                    // iris data from R

                    columns: [ ['data',porcentaje_meta]



                    ],

                    type: 'gauge'

                },

                color: {

                    pattern: ['#62cb31', '#BABABA']

                }

            });





        }



        // ================================================

        // GAUGE FIN

        // ================================================



    function ventas_dia_hora(){
        <?php

            $arreglo =array();

            foreach ($data['ventas_por_hora'] as $key => $value) {

                    $arreglo[]=array($key,intval($value));

                }

        ?>

        var datos_horas = <?php echo json_encode($arreglo) ?>;
        //console.log(datos_horas);
        var chartUsersOptions = {

            series: {

                splines: {

                    show: true,

                    tension: 0.4,

                    lineWidth: 1,

                    fill: 0.4

                },

            },

            grid: {

                tickColor: "#f0f0f0",

                borderWidth: 1,

                borderColor: 'f0f0f0',

                color: '#6a6c6f'

            },

            colors: [ "#62cb31", "#efefef"],

        };

        $.plot($("#flot-line-chart"), [datos_horas], chartUsersOptions);
        //gastos es una grafica pero es siempre la misma
        var chartIncomeData = [

            {

                label: "line",

                data: [ [1, 10], [2, 26], [3, 16], [4, 36], [5, 32], [6, 51] ]

            }

        ];

        var chartIncomeOptions = {

            series: {

                lines: {

                    show: true,

                    lineWidth: 0,

                    fill: true,

                    fillColor: "#64cc34"



                }

            },

            colors: ["#62cb31"],

            grid: {

                show: false

            },

            legend: {

                show: false

            }

        };

        $.plot($("#flot-income-chart"), chartIncomeData, chartIncomeOptions);

    }


    function renderPlotxHora(response){
        $arreglo = [];
        //response.ventas_por_hora
        $.each(response.ventas_por_hora,function(index,value){

            //$arreglo[]=[index,value];
            $arreglo[index] = [index,value];
            //console.log(index);
        });
       // console.log($arreglo);

        //var datos_horas = <?php echo json_encode($arreglo) ?>;
        var datos_horas = $arreglo;
        var chartUsersOptions = {

                        series: {

                            splines: {

                                show: true,

                                tension: 0.4,

                                lineWidth: 1,

                                fill: 0.4

                            },

                        },

                        grid: {

                            tickColor: "#f0f0f0",

                            borderWidth: 1,

                            borderColor: 'f0f0f0',

                            color: '#6a6c6f'

                        },

                        colors: [ "#62cb31", "#efefef"],

                    };

        $.plot($("#flot-line-chart"), [datos_horas], chartUsersOptions);
        var chartIncomeData = [

                        {

                            label: "line",

                            data: [ [1, 10], [2, 26], [3, 16], [4, 36], [5, 32], [6, 51] ]

                        }

                    ];

        var chartIncomeOptions = {

            series: {

                lines: {

                    show: true,

                    lineWidth: 0,

                    fill: true,

                    fillColor: "#64cc34"



                }

            },

            colors: ["#62cb31"],

            grid: {

                show: false

            },

            legend: {

                show: false

            }

        };

        $.plot($("#flot-income-chart"), chartIncomeData, chartIncomeOptions);
    }


    function categorias_mas_vendidas($datos){

        /**

         * Pie Chart Data

         */

        var pieChartData = $datos;



        /**

         * Pie Chart Options

         */

        var pieChartOptions = {

            series: {

                pie: {

                    show: true

                }

            },

            grid: {

                hoverable: true

            },

            tooltip: true,

            tooltipOpts: {

                content: "%p.0%, %s", // show percentages, rounding to 2 decimal places

                shifts: {

                    x: 20,

                    y: 0

                },

                defaultTheme: false

            }

        };



        $.plot($("#flot-pie-chart"), pieChartData, pieChartOptions);

    }



    $(document).ready(function () {




            // chart js

           /* graphLines();

            graphBar();

            graphPolar();

            graphBarH();*/



            // c3

            //c3Lines();

            c3Pie();



            // gauge
            // Grafico meta y faltante
            gaugeMeta();
            // Grafico Ventas por hora y dia
            ventas_dia_hora();

            <?php

                $datos_categorias = array();

               // $arreglo_colores=array('#9b59b6','#3498db','#62cb31','#ffb606','#e67e22');
               $arreglo_colores = array('#62cb31','#505050','#c0eaad','#0e0e0e','#316b16');

                foreach ($data['categorias_vendidas'] as $key => $value) {

                    $datos_categorias[]=array('label'=>$value['nombre'],'data'=>$value['total'],'color'=>$arreglo_colores[$key]);

                }

            ?>

            var datos_iniciales = <?php echo json_encode($datos_categorias) ?> ;

            categorias_mas_vendidas(datos_iniciales);

            // Si estamos haciendo backup de offline no mostramos el gauge por que se renderiza muy lento

            <?php  if ( $data['offline'] != "backup" ){ ?>



                // Mostramos la grafica de meta diaria despues de 1 Segundo

               /* setTimeout(function () {

                    <?php if( intval($metaFaltan) > 0){ ?>

                        gauge.set(<?php echo $data['meta_diaria']["total_ventas"]; ?>); // set actual value

                    <?php }else{ ?>

                        gauge.set( <?php echo $data['meta_diaria']["meta_almacen"]; ?> ); // set actual value

                    <?php } ?>



                }, 1000);*/



            <?php  }  ?>



        });
    </script>

    <script>
        $("#load_restaurante").click(function(){
            $(".btn-type").addClass('disabled');
            var type = 'restaurante';
            $(".loading_state").addClass('block');
            $.post('<?= site_url('frontend/cargar_tipo_negocio'); ?>',{
                type:type
            },function(data){
                $(".loading_state").removeClass('block');
                if(data){
                    location.reload();
                }else{
                    swal(
                        "error",
                        "Ocurrio un error al momento de cargar el tipo de negocio",
                        "error"
                    );
                }
            })
        })

        $("#load_retail").click(function(){
            $(".btn-type").addClass('disabled');
            var type = 'retail';
            $(".loading_state").addClass('block');
            $.post('<?= site_url('frontend/cargar_tipo_negocio'); ?>',{
                type:type
            },function(data){
                $(".loading_state").removeClass('block');
                if(data){
                    location.reload();
                }else{
                    swal(
                        "error",
                        "Ocurrio un error al momento de cargar el tipo de negocio",
                        "error"
                    );
                }
            })
        })

        $("#load_moda").click(function(){
            $(".btn-type").addClass('disabled');
            var type = 'moda';
            $(".loading_state").addClass('block');
            $.post('<?= site_url('frontend/cargar_tipo_negocio'); ?>',{
                type:type
            },function(data){
                $(".loading_state").removeClass('block');
                if(data){
                    location.reload();
                }else{
                    swal(
                        "error",
                        "Ocurrio un error al momento de cargar el tipo de negocio",
                        "error"
                    );
                }
            })
        })

        $("#type_hiden").click(function(){
            $(".btn-type").addClass('disabled');
            var type = 'retail';
            $(".loading_state").addClass('block');
            $.post('<?= site_url('frontend/cargar_tipo_negocio'); ?>',{
                type:type
            },function(data){
                $(".loading_state").removeClass('block');
                if(data){
                    location.reload();
                }else{
                    swal(
                        "error",
                        "Ocurrio un error al momento de cargar el tipo de negocio",
                        "error"
                    );
                }
            })
        })



    </script>
        <!-- End Gráficos -->
    </div>
    <?php } ?>


    <?php if(isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante"  && (in_array("1038", $permisos ) || $isAdmin == 't')): ?>
        <div role="tabpanel" class="tab-pane <?php echo ($active == "restaurante")? 'active' : '';?>" id="restaurante_home">


                <!--TABS-->
                <!--
                <div class="col-lg-offset-3 col-lg-9 col-md-offset-3 col-md-9 col-sm-12">

                    <div class="containerTabsR">
                        <div class="row">
                            <?php
                            foreach ($data['zonas'] as $key => $value) {
                                $active="";
                                if($value->id==$data['zonas'][0]->id)
                                    $active="activeTabMesero";
                                echo'<div role="zonas" class="individualTab col-xs-12 col-sm-12 col-md-2 col-lg-2 col-2 '.$active.'" data-id="'.$value->id.'" id="tabMesero'.$value->id.'" id="">
                                <a onclick="selectTab('.$value->id.')" aria-controls="'.$value->id.'" role="tab" data-toggle="tab" href="#'.$value->id.'">'.strtoupper($value->nombre_seccion).'</a>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                -->
                <!--tabs-->
                <!--
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 col-lg-offset-5 col-md-offset-5 col-sm-offset-5 tab_panel" >
                    <div class="row centrarbtn">
                        <?php
                        foreach ($data['zonas'] as $key => $value) {
                            $active="";
                            if($value->id==$data['zonas'][0]->id)
                                $active="activeTabMesero";
                            echo'<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2"><a class="tz" onclick="selectTab('.$value->id.')" aria-controls="home" role="tab" data-toggle="tab" href="#'.$value->id.'">
                                <div role="zonas" class="btnmesas '.$active.'" data-id="'.$value->id.'" id="tabMesero'.$value->id.'" id="">'.strtoupper($value->nombre_seccion).'</div></a></div>';
                        }
                        ?>
                    </div>
                </div> -->

            <!-- ROW INFO-->
            <div class="row">
                <!--INFO USER-->
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                    <!--
                    <div class="containerMeseroInfo">
                        <div class="row">
                            <div class="col-12">
                                Hola <?php echo $this->session->userdata('username')?>
                            </div>
                        </div>
                        <div class="row logoEmpresa">
                            <div class="col-12">
                                <?php if(!empty($data["datos_empresa_ap"]['data']['logotipo'])){ ?>
                                    <img src="<?php echo base_url('uploads').'/'.$data["datos_empresa_ap"]['data']['logotipo']; ?>" alt=""/>
                                <?php } else { ?>
                                    <div style="font-weight: bold">Mi Empresa</div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <img class="logoVendty" src="<?php echo $this->session->userdata('new_imagenes')['logo_vendty_color']['original'] ?>" alt="logo">
                            </div>
                        </div>
                    </div>
                    -->
                    <!--ordenes a pagar -->
                    <div class="containerBoxBottom">
                        <h5>Órdenes Pendientes</h5>
                        <div class="content_ordenes_pendientes" style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                    <tr>
                                        <th>Fecha de Creación</th>
                                        <th>Zona</th>
                                        <th>Mesa</th>
                                        <!--<th>Productos</th>-->
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="ordenes_pendientes" class="ordenes_pendientes"></tbody>
                            </table>
                        </div>
                    </div>
                    <!--ordenes por formas de pago-->
                    <?php if($data['permitir_formas_pago_pendiente']=='si'){?>
                    <div class="containerBoxBottom">
                        <h5>Facturas Pendientes por Forma de Pago</h5>
                        <div class="content_ordenes_pendientes" style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                    <tr>
                                        <th>Factura</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="facturas_pendientes_pago" class="ventas_pendientes"></tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!--INFO MESAS-->
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 tab_panel">
                     <div class="row centrarbtn">
                        <?php
                        foreach ($data['zonas'] as $key => $value) {
                            $active="";
                            if($value->id==$data['zonas'][0]->id)
                                $active="activeTabMesero";
                            echo'<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2"><a class="tz" onclick="selectTab('.$value->id.')" aria-controls="home" role="tab" data-toggle="tab" href="#'.$value->id.'">
                                <div role="zonas" class="btnmesas '.$active.'" data-id="'.$value->id.'" id="tabMesero'.$value->id.'" id="">'.strtoupper($value->nombre_seccion).'</div></a></div>';
                        }
                        ?>
                    </div>

                    <div class="tab-content" style="height: 50vh; overflow-y: auto;">
                        <!-- Zonas -->
                        <?php
                           // print_r($data["mesas_secciones"]);
                        ?>
                        <?php  $i = 0; foreach($data["zonas"] as $zona):
                            $active="";
                            $classmesa="content-mesa";
                        ?>

                            <div role="tabpanel" class="tab-pane <?php echo ($i==0)? 'active' : '';?>" id="<?= $zona->id;?>">
                                    <?php $j = 0;  foreach($data["mesas_secciones"] as $mesa):?>
                                        <?php if($zona->id == $mesa->id_seccion):
                                        $comensales = ( $mesa->comensales > 0)? $mesa->comensales : '';
                                        $estado = ($mesa->pedidos>0)? 'verde' : 'gris';
                                        $classmesa=($mesa->pedidos>0)? "content-mesa-active" : "content-mesa";
                                        $fecha_creacion  = '';
                                        if (!empty($mesa->fecha_creacion)) {
                                            $fecha_creacion = new DateTime($mesa->fecha_creacion);
                                            $fecha_creacion = $fecha_creacion->format('H:i');
                                        }
                                            if($j == 0){
                                                $ref_mesa = site_url('orden_compra/mi_orden/').'/-1/'.strtotime("now");?>
                                                <div class="col-md-2 col-sm-6 col-xs-6 text-center panel_mesa">
                                                    <div class="<?= $classmesa ?>">
                                                        <a href="<?= $ref_mesa ?>">
                                                            <!--<img class="mesa" src="<?= base_url().'uploads/mesa-'.$estado.'.svg';?>" alt="">-->
                                                            <img class="mesa" src="<?= base_url().'uploads/tables/mesa'.get_option('table_selected').'_gris.svg'; ?>" alt="barra">
                                                            <span class="nombre_mesa">Barra</span>
                                                        </a>
                                                        <!--<a onclick='location.href="<?php echo $ref_mesa;?>"'>
                                                            <div class="panelMesas <?php echo ($mesa->pedidos > 0)? 'panel-danger-new':'panel-success-new';?> rounded shadow">
                                                                <div class="text-center ">
                                                                    <img src="<?php echo $this->session->userdata('new_imagenes')['rest_barra']['original'] ?>" alt="barra">
                                                                </div>
                                                            </div>
                                                            <b style="color: #000; text-transform: capitalize !important;"> Barra </b>
                                                        </a>-->
                                                    </div>
                                                </div>
                                            <?php $j++;} ?>


                                            <?php if($mesa->id != '-1'):
                                                $ref_mesa = site_url('orden_compra/mi_orden/').'/'.$zona->id.'/'.$mesa->id;?>
                                                <div class="col-md-2 col-sm-6 col-xs-6 text-center panel_mesa">
                                                    <div class="<?= $classmesa ?>">
                                                        <a href="<?= $ref_mesa ?>">
                                                            <!--<img class="mesa" src="<?= base_url().'uploads/mesa-'.$estado.'.svg';?>" alt="">-->
                                                            <img class="mesa" src="<?= base_url().'uploads/tables/mesa'.get_option('table_selected').'_'.$estado.'.svg'; ?>" alt="mesa">
                                                            <span class="nombre_mesa"><?= $mesa->nombre_mesa; ?></span>
                                                            <div class="fecha_creacion_comanda">
                                                                <?= $fecha_creacion ?>
                                                            </div>
                                                        </a>
                                                        <?php if($mesa->comensales > 0){ ?>
                                                            <div class="comensales"><?= $comensales ?></div>
                                                        <?php } ?>

                                                    </div>
                                                </div>

                                        <?php endif;?>
                                        <?php endif;?>
                                    <?php endforeach;?>

                            </div>
                            <?php $i++; endforeach;?>
                    </div>

                </div>
            </div>

            <div class="row">

                 <!-- Pedidos a pagar -->
                <div class="row">
                <!--
                    <div class="col-md-6 containerBoxBottom">
                        <h5>Órdenes Pendientes</h5>
                        <div class="col-md-12 content_ordenes_pendientes" style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                    <tr>
                                        <th>Fecha de Creación</th>
                                        <th>Zona</th>
                                        <th>Mesa</th>
                                        <th>Productos</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="ordenes_pendientes" class="ordenes_pendientes"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 containerBoxBottom">
                        <h5>Facturas Pendientes por Forma de Pago</h5>
                        <div class="col-md-12 content_ordenes_pendientes" style="overflow-x: hidden !important; overflow-y: auto !important;">
                            <table id="table_ordenes_pendientes" cellspacing="0" cellpadding="0" width="100%">
                                <thead style="font-weight:bold;">
                                    <tr>
                                        <th>Factura</th>
                                        <th>Fecha de Creación</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="facturas_pendientes_pago" class="ventas_pendientes"></tbody>
                            </table>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php
    $impuestopredeterminado=$data['impuesto']->porciento;
?>
<div class="modal fade" tabindex="-1" role="dialog" id="modal-forma-pago">
 <div class="modal-dialog modal-lg" role="document">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close close-modal-forma-pago" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
       <h4 class="modal-title">Formas de Pagos Pendientes a la Factura N° <span id="factura_forma"></span></h4>
     </div>
     <div class="modal-body">
        <div class="container">
    <div class="col-md-12">
        <form class="form-horizontal" id="form_pago" method="POST">
            <input type="hidden" class="form-control" id="factura" name="factura" value="0">
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Valor a Pagar:</label>
                <div class="col-md-10">
                <input type="number" disabled index="" class="form-control" id="valor_a_pagar" name="valor_a_pagar" placeholder="valor_a_pagar" value="">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                <div class="col-md-10">
                    <select name="forma_pago" id="forma_pago" class="form-control forma_pago" data-id="">
                        <?php
                        foreach ($data['forma_pago'] as $f) {
                            if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                            ?>
                            <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group" id="pago_datafono" style="display:none">
                <div class="col-md-2"> </div>
                <div class="col-md-10">
                    <div class="col-md-3">
                    Subtotal <input id="subtotal" class="subtotal" type="text" disabled="true" value="0">
                    </div>
                    <div class="col-md-3">
                    IVA <input class="impuesto" id="impuesto" type="text" disabled="true" value="0" >
                    </div>
                    <div class="col-md-3">
                    Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono" id="impuestoDatafono" data-id="" value="<?php echo $impuestopredeterminado; ?>">
                    </div>
                    <div class="col-md-3">
                    N° Transacción <input type="text" name="transaccion" id="transaccion" value="">
                    </div>
                </div>
            </div>
            <div id="fecha_vencimiento_credito" class="form-group" style="display:none">
                <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                <div class="col-md-10">
                    <input type="text" name="fecha_vencimiento_venta" id="fecha_vencimiento_venta" />
                </div>
            </div>
            <div id="nota_credito" class="form-group" style="display:none">
                <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                <div class="col-md-9">
                   <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito" id="valor_entregado_nota_credito"  index="" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                </div>
                <div class="col-md-1">
                    <a id="valor_entregado_nota_creditob" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                </div>

            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                <div class="col-md-10">
                    <input type="number" class="form-control valor_entregado" id="valor_entregado" name="valor_entregado" data-id="" placeholder="valor Entregado" value="">
                </div>
            </div>

            <div id="contenido_a_mostrar1" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago1" id="forma_pago1" class="form-control forma_pago" data-id="1">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer" class="eliminar_forma_pago">
                            <i class="glyphicon glyphicon-trash" data-id="1" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado1" name="valor_entregado1" data-id="1" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono1" style="display:none">
                    <div class="col-md-2"> </div>
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal1" class="subtotal" type="text" disabled="true" value="0">
                        </div>
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto1" type="text" disabled="true" value="0" >
                        </div>
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono1" data-id="1" id="impuestoDatafono1" value="<?php echo $impuestopredeterminado; ?>">
                        </div>
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion1" id="transaccion1" value="">
                        </div>
                    </div>
                </div>
                <div id="fecha_vencimiento_credito1" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">
                        <input type="text" name="fecha_vencimiento_venta1" id="fecha_vencimiento_venta1" />
                    </div>
                </div>
                <div id="nota_credito1" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito1" id="valor_entregado_nota_credito1"  index="" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob1" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                    </div>
                </div>
            </div>

            <div id="contenido_a_mostrar2" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago2" id="forma_pago2" class="form-control forma_pago" data-id="2">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="1" style="cursor: pointer">
                            <i class="eliminar_forma_pago glyphicon glyphicon-trash" data-id="2" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado2" name="valor_entregado2" data-id="1" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono2" style="display:none">
                    <div class="col-md-2"> </div>
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal2" class="subtotal" type="text" disabled="true" value="0">
                        </div>
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto2" type="text" disabled="true" value="0" >
                        </div>
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono2" id="impuestoDatafono2"  data-id="2" value="<?php echo $impuestopredeterminado; ?>">
                        </div>
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion2" id="transaccion2" value="">
                        </div>
                    </div>
                </div>
                <div id="fecha_vencimiento_credito2" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">
                        <input type="text" name="fecha_vencimiento_venta2" id="fecha_vencimiento_venta2" />
                    </div>
                </div>
                <div id="nota_credito2" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito2" id="valor_entregado_nota_credito2"  index="2" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob2" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                    </div>
                </div>
            </div>

            <div id="contenido_a_mostrar3" style="display:none">
                <hr style="margin-top: 5px"/>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-2 control-label">Forma de Pago:</label>
                    <div class="col-md-9">
                        <select name="forma_pago3" id="forma_pago3" class="form-control forma_pago" data-id="3">
                            <option value="0" data-tipo="">Seleccione</option>
                            <?php
                            foreach ($data['forma_pago'] as $f) {
                                if((($f->codigo!="nota_credito") &&($f->codigo!="Puntos")&&($f->codigo!="Saldo_a_Favor"))){
                                ?>
                                <option value="<?php echo $f->codigo?>" data-tipo="<?php echo $f->tipo ?>"><?php echo $f->nombre ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a data-id="3" style="cursor: pointer" class="eliminar_forma_pago">
                            <i class="glyphicon glyphicon-trash" data-id="3" style="font-size: 12px; color: green; margin-left: -20px; color: green !important;"></i>
                        </a>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-2 control-label">Valor Entregado:</label>
                    <div class="col-md-10">
                        <input type="number" class="form-control valor_entregado" id="valor_entregado3" name="valor_entregado3" data-id="3" placeholder="valor Entregado" value="0">
                    </div>
                </div>
                <div class="form-group" id="pago_datafono3" style="display:none">
                    <div class="col-md-2"> </div>
                    <div class="col-md-10">
                        <div class="col-md-3">
                        Subtotal <input id="subtotal3" class="subtotal" type="text" disabled="true" value="0">
                        </div>
                        <div class="col-md-3">
                        IVA <input class="impuesto" id="impuesto3" type="text" disabled="true" value="0" >
                        </div>
                        <div class="col-md-3">
                        Impuesto <input class="impuestoDatafono" type="text" name="impuestoDatafono3" id="impuestoDatafono3"  data-id="3" value="<?php echo $impuestopredeterminado; ?>">
                        </div>
                        <div class="col-md-3">
                        N° Transacción <input type="text" name="transaccion3" id="transaccion3" value="">
                        </div>
                    </div>
                </div>
                <div id="fecha_vencimiento_credito3" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Fecha de vencimiento"); ?>:</div>
                    <div class="col-md-10">
                        <input type="text" name="fecha_vencimiento_venta3" id="fecha_vencimiento_venta3" />
                    </div>
                </div>
                <div id="nota_credito3" class="form-group" style="display:none">
                    <div class="col-md-2"><?php echo custom_lang('sima_cambio', "Nota Crédito"); ?>:</div>
                    <div class="col-md-9">
                    <input class="codigoNotaCredito" type="text" name="valor_entregado_nota_credito3" id="valor_entregado_nota_credito3"  index="3" value="" placeholder=" C&oacute;digo Nota Crédito"/>
                    </div>
                    <div class="col-md-1">
                        <a id="valor_entregado_nota_creditob3" href="javascript:void(0);" class="btn btn-success btnBuscarNotaCredito2" ><span class="icon glyphicon glyphicon-search" style=""></span></a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-md-2 control-label">Cambio:</label>
                <div class="col-md-10">
                    <input type="number" disabled class="form-control" id="cambio" name="cambio" placeholder="cambio" value="0">
                    <p style="color: red; display: none;" class="validate_pay">Cuando la forma de pago no es “Efectivo” el "cambio" debería ser cero (0).</p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="button" class="btn btn-default close-modal-forma-pago">Cancelar</button>
                    <?php if ($data['multiples_formas_pago'] == 'si') { ?>
                        <button type="button" class="btn btn-success" onClick="mostrar();" >Agregar Forma de Pago</button>
                    <?php } ?>
                    <button type="button" id="pagar_pendiente" class="btn btn-success">Pagar</button>
                </div>
            </div>
        </form>
    </div>
</div>
     </div>
     <div class="modal-footer">
      <!-- <button type="button" id="save-changues" class="btn btn-primary">Save changes</button>-->
     </div>
   </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
    .apexcharts-canvas svg {
        max-height: 340px !important;
    }
</style>

<?php if($this->session->userdata("soy_nuevo")==1) { ?>
    <iframe src="https://vendty.com/gracias.html" width="800" height="1000" scrolling="no" class="hidden"></iframe>
<?php
    $this->session->set_userdata('soy_nuevo', 0);
    } ?>

<script>

    function cargar_modal_pagos(id){
        //busco los datos de la factura
       //$("#modal-forma-pago").modal('show');
        $.ajax({
            url: "<?php echo site_url("ventas/formaspago/"); ?>",
            dataType: 'html',
            type: 'POST',
            data: {"id":id},
            success: function (data) {
                data=JSON.parse(data);
                console.log(data);
                console.log(data[0]);
                console.log(data[0].id);
                if(data[0].id!=0){
                    idfactura=data[0].id;
                    factura=data[0].factura;
                    valor_entregado=parseFloat(data[0].valor_entregado);
                    $("#factura").val(idfactura);
                    $("#factura_forma").html("");
                    $("#factura_forma").html(factura);
                    $("#valor_a_pagar").val(valor_entregado);
                    $("#valor_entregado").val(valor_entregado);
                    $("#modal-forma-pago").modal('show');

                }else{
                    alert("error");
                }
               /* $("#modal-forma-pago .modal-body").html("");
                $("#modal-forma-pago .modal-body").append(data);
                */
            }
        });
   }

   $(".close-modal-forma-pago").click(function(){
       $("#modal-forma-pago").modal('hide');
   })
    $(document).ready(function() {

        //$(".page").css("background-color", "#f1f3f6");
        activo='<?php echo $active; ?>';
        if(activo=='inicio'){
            $("#graficos_home").removeClass("active");
            $(".page").css("background-color", "White");

        }
        /* Cargamos ventas pendientes por zona por defecto*/
        //cargar_ventas_pendientes_por_zona(<?php //echo $zona_defecto;?>);
        cargar_ventas_pendientes();

        $("#fecha_vencimiento_venta").datepicker({
            dateFormat: 'yy/mm/dd'
        });

       // $('#fecha_vencimiento_venta').datetimepicker();


    /*formas de pagos*/
        $('.forma_pago').on('change', function() {
            forma=$(this).val();
            id=$(this).attr('data-id');
            //bloquear_opciones_forma(id);
            $( "#forma_pago"+id+" option:selected" ).each(function() {
                tipo=$(this).data('tipo');
            });

            $("#valor_entregado").prop("disabled", false);

            if(tipo=='Datafono'){
                $("#pago_datafono"+id).css('display','block');
                discriminado(id);
            }else{
                $("#pago_datafono"+id).css('display','none');
            }

            if(forma=='Credito'){
                $("#fecha_vencimiento_credito"+id).css('display','block');
            }else{
                $("#fecha_vencimiento_credito"+id).css('display','none');
            }

            if(forma=='nota_credito'){
                $("#nota_credito"+id).css('display','block');
            }else{
                $("#nota_credito"+id).css('display','none');
            }

        });

        $(".btnBuscarNotaCredito2").click(function(  ) {
            var index = "" ;
            var codigo = $("#valor_entregado_nota_credito").val() ;

            $.ajax({
                url: "<?php echo site_url("notacredito/estadoNotaCredito"); ?>",
                dataType: 'json',
                type: 'POST',
                data: {"codigo":codigo},
                error: function(jqXHR, textStatus, errorThrown ){
                    //alert(errorThrown);
                    swal({
                        position: 'center',
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                success: function(data){

                    setEstadoNotaCredito( data, index );
                }
            });
        });

        $("#pagar_pendiente").click(function(e){
            $("#pagar_pendiente").prop("disabled", true);
            valor_entregado1=0;
            valor_entregado2=0;
            valor_entregado3=0;
            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());
            cambio=parseFloat($("#cambio").val());
            url='<?php echo site_url("frontend/index") ?>';
            if(isNaN(valor_entregado)){
                $("#valor_entregado").val(0);
                $("#cambio").val(0);
            }

            //me paseo por todos los pagos
            if (document.getElementById('contenido_a_mostrar1').style.display == 'block') {
                valor_entregado1=parseFloat($("#valor_entregado1").val());
            }
            if (document.getElementById('contenido_a_mostrar2').style.display == 'block') {
                valor_entregado2=parseFloat($("#valor_entregado2").val());
            }
            if (document.getElementById('contenido_a_mostrar3').style.display == 'block') {
                valor_entregado3=parseFloat($("#valor_entregado3").val());
            }

            valor_entregadototal=valor_entregado+valor_entregado1+valor_entregado2+valor_entregado3;


            if(!isNaN(valor_entregadototal)){
                if((((valor_a_pagar!="") &&(valor_entregadototal!="")) && (valor_a_pagar<=valor_entregadototal))){

                        $("#cambio").prop("disabled", false);
                        $("#valor_entregado_nota_credito").prop("disabled", false);
                        $("#valor_a_pagar").prop("disabled", false);
                        $("#valor_entregado").prop("disabled", false);
                        //ajax
                        $.ajax({
                            url: "<?php echo site_url("ventas/registrarformaspago"); ?>",
                            type: "POST",
                            dataType: "json",
                            data: $("#form_pago").serialize(),
                            success: function (data) {
                                console.log(data);
                                if(data.success==1){
                                    swal({
                                        position: 'center',
                                        type: 'success',
                                        title: 'La Forma de pago se registro Exitosamente',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    setTimeout(function(){

                                        cargar_ventas_pendientes();
                                        $("#modal-forma-pago").modal('hide');
                                        $("#pagar_pendiente").prop("disabled", false);
                                    }, 1600);
                                }else{
                                    swal({
                                        position: 'center',
                                        type: 'error',
                                        title: data.mgs,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    $("#pagar_pendiente").prop("disabled", false);
                                }
                            }
                        });
                    }
                    else{
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'El valor entregado debe ser igual al valor a pagar',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        $("#pagar_pendiente").prop("disabled", false);
                    }

            }else{
                swal({
                    position: 'center',
                    type: 'error',
                    title: 'El valor entregado debe ser mayor o igual al valor de la factura',
                    showConfirmButton: false,
                    timer: 1500
                })
                $("#pagar_pendiente").prop("disabled", false);
            }
        });

        $(".valor_entregado").keyup(function(e){

            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());
            valor_entregado1=parseFloat($("#valor_entregado1").val());
            valor_entregado2=parseFloat($("#valor_entregado2").val());
            valor_entregado3=parseFloat($("#valor_entregado3").val());

            if(isNaN(valor_entregado)){
                valor_entregado=0;
            }
            if(isNaN(valor_entregado1)){
                valor_entregado1=0;
            }
            if(isNaN(valor_entregado2)){
                valor_entregado2=0;
            }
            if(isNaN(valor_entregado3)){
                valor_entregado3=0;
            }
        
            cambio=parseFloat(valor_a_pagar-(valor_entregado+valor_entregado1+valor_entregado2+valor_entregado3));
            cambio=cambio*(-1);
            console.log("cambio", cambio);
            
            if($('#forma_pago').val() != '0' && $('#forma_pago').val() != 'efectivo'){
                console.log("cambio2", cambio);
                if(cambio >= 1){
                    console.log("no puede ser mayor valor_entregado:" + valor_entregado + ", cambio:" +cambio);
                    $('#pagar_pendiente').prop('disabled', true);
                    $('.validate_pay').show();
                }else{
                    $('#pagar_pendiente').prop('disabled', false);
                    $('.validate_pay').hide();
                }
            }
                
                $('.forma_pago').each(function (val){
                    if($(this).val() != 'efectivo' && $(this).val() != '0'){
                        console.log($(this).val());                        
                    }
                });
            $("#cambio").val(cambio);
        });

        $(".impuestoDatafono").keyup(function(){
            id=$(this).attr('data-id');
            //alert(id);
            discriminado(id);
        });

        $(".eliminar_forma_pago").click(function(e){

            id=$(this).attr('data-id');
            $("#contenido_a_mostrar"+id).css('display','none');
            //eliminar datos del eliminado
            $("#valor_entregado"+id).val(0);
            $( "#forma_pago"+id+" option:selected" ).each(function() {
                tipo=$(this).data('tipo');
            });
            if(tipo=='Datafono'){
                    $("#impuestoDatafono"+id).val(0);
                    $("#transaccion"+id).val("");
                    $("#impuesto"+id).val(0);
                    $("#subtotal"+id).val(0);
                $("#pago_datafono"+id).css('display','none');
            }

            if(forma=='Credito'){
                $("#fecha_vencimiento_venta"+id).val("");
                $("#fecha_vencimiento_credito"+id).css('display','none');
            }
            $("#forma_pago"+id).val(0);
        });

    });

    function setEstadoNotaCredito( datos, index ){

        var estado = datos.estado;

        var nombre = datos.nombre;

        var valor = datos.valor;

        $("#valor_entregado").val( 0 );

        $("#valor_entregado").prop('disabled', false);

        $("#valor_entregado").css("cursor", "default");

        $("#valor_entregado").hide();

        $("#valor_entregado_gift").attr('disabled');

        $("#valor_entregado_nota_credito").css("cursor", "default");

        $("#valor_entregado_nota_credito").attr('style','display: block !important');

       // setNotaCreditoObj( index, null );
        if( estado == "empty" ){
            //alert("La nota credito no existe");
            $("#valor_entregado_nota_credito").val('');
            swal({
                position: 'center',
                type: 'error',
                title: 'La nota credito no existe',
                showConfirmButton: false,
                timer: 1500
            })
        }

        if( estado == "cancelado" ){
            //alert("La "+nombre+" ya ha sido canjeada");
            $("#valor_entregado_nota_credito").val('');
            swal({
                position: 'center',
                type: 'error',
                title: "La "+nombre+" ya ha sido canjeada",
                showConfirmButton: false,
                timer: 1500
            })
        }
        if( estado == "activo" ){
            //alert("La "+nombre+" no ha sido pagada");
            swal({
                position: 'center',
                type: 'error',
                title: "La "+nombre+" no ha sido pagada",
                showConfirmButton: false,
                timer: 1500
            })
        }
        if( estado == "pagado" ){

            $("#valor_entregado").val( valor );

            $("#valor_entregado").prop('disabled', true);

            $("#valor_entregado").css("cursor", "not-allowed");

            $("#valor_entregado").show();

            $("#valor_entregado_nota_credito").prop('disabled', true);

            $("#valor_entregado_nota_credito").css("cursor", "not-allowed");

            $("#valor_entregado_nota_creditob").attr('style','display: none !important');
           // setNotaCreditoObj(index,"pagada");
            valor_a_pagar=parseFloat($("#valor_a_pagar").val());
            valor_entregado=parseFloat($("#valor_entregado").val());
            cambio=parseFloat(valor_a_pagar-(valor_entregado));
            cambio=cambio*(-1);
            $("#cambio").val(cambio);

        }
        //validarMediosDePago(0);
    }

    function bloquear_opciones_forma(id){
        forma0=$("#forma_pago").val();

        for(i=id;i<=id;i++){

            $( "#forma_pago"+i+" option" ).each(function() {
                nombre=$(this).val();
                if(nombre==forma0){
                    $(this).attr('disabled', true);

                }else{
                    $(this).attr('disabled', false);
                }
            });
        }
    }

    function discriminado(id){
            //alert(id);
        valorEntregado=parseFloat($("#valor_entregado"+id).val());
        impuesto=parseFloat($("#impuestoDatafono"+id).val());
        subtotal=valorEntregado;
        x=String(impuesto).length;

        if(x == 2)
        {
            subtotal = valorEntregado / parseFloat("1."+impuesto);
        }else if(x == 1)
        {
            subtotal = valorEntregado / parseFloat("1.0"+impuesto);
        }

        iva = valorEntregado - subtotal;
        $("#subtotal"+id).val(parseInt(subtotal));
        $("#impuesto"+id).val(parseInt(iva));
    }

    function mostrar() {

        if (document.getElementById('contenido_a_mostrar1').style.display == 'none') {
            document.getElementById('contenido_a_mostrar1').style.display = 'block';
            //bloquear las opciones anteriores
           // bloquear_opciones_forma(1);
        } else if (document.getElementById('contenido_a_mostrar2').style.display == 'none') {
            document.getElementById('contenido_a_mostrar2').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar3').style.display == 'none') {
            document.getElementById('contenido_a_mostrar3').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar4').style.display == 'none') {
            document.getElementById('contenido_a_mostrar4').style.display = 'block';
        } else if (document.getElementById('contenido_a_mostrar5').style.display == 'none') {
            document.getElementById('contenido_a_mostrar5').style.display = 'block';
        }
    }

        function cargar_ventas_pendientes(){
        $("#ordenes_pendientes").html("");
        $("#facturas_pendientes_pago").html("");
        //facturas pendientes por pagos
        $.get("<?php echo site_url().'/ventas/getAllFacturasPendientesxPago';?>",function(data){
          //  console.log(data);
            if(data.length > 0){
                $.each(data,function(index,el){
                //var href_pagar = "<?php echo site_url('ventas/formaspago/')?>/"+el.id_venta;
                var href_pagar = el.id_venta;
                var row = "<tr>";
                    row += "<td>"+el.factura+"</td>";
                    row += "<td>"+el.fecha+"</td>";
                    row += "<td><div class='centrando'><a onclick='cargar_modal_pagos("+href_pagar+")' id='modal-pago' class='btn btn-success'>Asignar Forma de Pago</a></div></td>";
                    //row += "<td><div class='centrando'><a href='"+href_pagar+"' class='btn btn-success'>Asignar Forma de Pago</a></div>";
                    row += "</tr>";
                $("#facturas_pendientes_pago").append(row);
                })
            }else{
                $("#facturas_pendientes_pago").append('No se encontro ninguna venta pendiente Por forma de pago');
            }
        })
        //ordenes pendientes
        $.get("<?php echo site_url().'/ventas/getAllOrdenes';?>",function(data){
            //var ordenes = JSON.stringify(data);
            if(data.length > 0){
                $.each(data,function(index,el){
                //var href_pagar = "<?php echo site_url('ventas/nuevo/')?>/"+el.zona+'/'+el.mesa_id;
                var href_pagar = "<?php echo site_url('ventas/nuevo/')?>/"+el.zona+'/'+el.mesa_id;
                var href_editar = "<?php echo site_url('orden_compra/mi_orden/')?>/"+el.zona+'/'+el.mesa_id;
                var row = "<tr>";
                    row += "<td>"+el.created_at+"</td>";
                    row += "<td>"+el.zona_mesa+"</td>";
                    row += "<td>"+el.nombre_mesa+"</td>";
                   // row += "<td>"+el.cantidad+"</td>";
                    row += "<td>"+el.monto+"</td>";
                    //row += "<td><a href='"+href_pagar+"'><img src='<?php echo $this->session->userdata("new_imagenes")["btn_pagar_mesa"]["original"] ?>' alt='logo'></a>";
                    row += "<td><div class='centrando'><a href='"+href_pagar+"' class='btn btn-success'>Pagar</a>";
                    //row += "<a href='"+href_editar+"'><img src='<?php echo $this->session->userdata("new_imagenes")["btn_editar_mesa"]["original"] ?>' alt='logo'></a></td>";
                    row += "<a href='"+href_editar+"' class='btn btn-success'>Editar</a></div></td>";
                    row += "</tr>";
                $("#ordenes_pendientes").append(row);
                })
            }else{
                $("#ordenes_pendientes").append('No se encontro ninguna venta pendiente');
            }

        })
    }

</script>
<!--mixpanel-->
<script type="text/javascript">
    var id='<?php echo $this->session->userdata('user_id') ?>';
    var email='<?php echo $this->session->userdata('email') ?>';
    var nombre_empresa='<?php echo $nombre_empresa ?>';

    mixpanel.identify(id);

    mixpanel.track_links('#configura_tu_tirilla', 'Configura Tirilla',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#crea_tus_productos', 'Crear Productos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#abre_tu_caja', 'Abrir Caja',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#realiza_tu_primera_venta', 'Realizar Primera Venta',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#registra_tus_gastos', 'Registrar Gastos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#revisa_tu_cierre_de_caja', 'Cierre de Caja',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_carga_tu_logo', 'Link Cargar Logo',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_aperturar_una_caja', 'Link Abrir Caja',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_un_producto', 'Link Crear Productos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_un_vendedor', 'Link Crear Vendedor',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_mi_primera_factura', 'Link Realizar Primera Venta',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_un_gasto', 'Link Registrar Gastos',{
        "$email": email,
        "$empresa": nombre_empresa,
    });
    mixpanel.track_links('#link_crear_una_orden_de_compra', 'Link Registrar Orden de Compra',{
        "$email": email,
        "$empresa": nombre_empresa,
    });

    function selectTab(id) {
        $('[role="zonas"]').removeClass().addClass( "btnmesas col-xs-12 col-sm-12 col-md-2 col-lg-2 col-2" );
        $("#" + 'tabMesero' + id).addClass( "activeTabMesero" );
    }

    $(".tabs-restaurante>li").click(function(e){
        data=$(this).attr("data-id");
        //console.log($(this));
        //esconderpasos
       // alert(data);
        if(data=="restaurante_home"){
            $(".esconderpasos").css('display','none');
        }else{
            $(".esconderpasos").css('display','block');
        }
    })
    $("#admin_shop").click(function(e){
        e.preventDefault();
        var url_shop = "http://admintienda.vendty.com/admin/crosslogin";
        //var url_shop = "http://192.168.0.15:4200/";

        var url_cross = "<?php echo site_url('tienda/crossDomain');?>";
        var a = document.createElement('a');

        $.get(url_cross,function(data){
            localStorage.setItem("data",data);
            a.target="_blank";
            a.href=url_shop;
            a.click();
        })
    })


    $("#accept_terms_conditions").click(function(){
        let url_terms_conditions = "<?= site_url('frontend/accept_terms_conditions');?>";
        $.get(url_terms_conditions,function(data){
            location.reload();
        });
    })



    var type_business = '';
    var subcategory_business = '';
    var step = 1;

    /* Steps -  type businnes sleceted*/
    $(".content-type-business .content-type").each(function(index,element){
        $(this).click(function(){
            clear_business();
            $(".content-type-business .content-type").each(function(index,element){
                $(this).removeClass("type-business-active");
            });
            $(this).addClass("type-business-active");
            type_business = $(this).data('type');
            switch(type_business){
                case 'restaurant':
                    $(".restaurant-buttons").addClass("active-subcategorie");
                break;

                case 'retail':
                    $(".retail-buttons").addClass("active-subcategorie");
                break;

                case 'fashion':
                    $(".fashion-buttons").addClass("active-subcategorie");
                break;
            }

        })
    })




    $(".subcategory").each(function(index,element){
        $(this).click(function(){
            clear_subcategory();
            subcategory_business = $(this).data('name');
            $(this).addClass('selected-subcategorie');
        })
    })

    function clear_subcategory(){
        $(".subcategory").each(function(index,element){
            $(this).removeClass('selected-subcategorie');
        })
    }
    function clear_business(){
        type_business = '';
        subcategory_business = '';

        $('.button-subcategorie').each(function(){
            $(this).removeClass('active-subcategorie');
        })
    }



    /* End step invoice */

    $("#next-step").click(function(){

        $("#prev-step").css('visibility','visible');
        step++;
        axios.post('<?= site_url("frontend/save_step");?>', {
            step:step,
            type_business:type_business
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
        switch(step){
            case 2:
                mixpanel.track("Paso 1 - Tipo de negocio", {
                    "$email": email,
                    "$empresa": nombre_empresa,
                    "$tipo_negocio": type_business,
                });
                if(type_business == 'restaurant'){
                    $(".content-propine").removeClass('hidden');
                }else{
                    $(".content-propine").addClass('hidden');
                }
                load_step();
            break;

            case 3:
                mixpanel.track("Paso 2 - Configuración  de factura", {
                    "$email": email,
                    "$empresa": nombre_empresa,
                    "$tipo_negocio": type_business,
                });
                load_step();
                $('.multiple-templates').slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    vertical: false,
                    prevArrow: '<div class="slick-prev"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                    nextArrow: '<div class="slick-next"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
                });
            break;

            case 4:
                mixpanel.track("Paso 3 - Configuración  de tienda", {
                    "$email": email,
                    "$empresa": nombre_empresa,
                    "$tipo_negocio": type_business,
                });
                load_step();
                $("#next-step").css('visibility','hidden');
            break;
        }
    })

    $("#prev-step").click(function(){
        $("#next-step").css('visibility','visible');
        if(step > 1){step--;}

        switch(step){
            case 1:
                $("#prev-step").css('visibility','hidden');
                load_step();
            break;

            case 2:
                if(type_business == 'restaurant'){
                    $(".content-propine").removeAttr('disabled');
                }
                load_step();
            break;

            case 3:
                load_step();
                $('.multiple-templates').slick({
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    vertical: false,
                    prevArrow: '<div class="slick-prev"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                    nextArrow: '<div class="slick-next"><img style="width: 30px; height: 30px;" src="http://pos.vendty.com/uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
                });
            break;
        }
    })

    function load_step(){
        $("#next-step").attr('step',step);
        $(".steps .step").each(function(index,element){
            if(index != 0 && step > index){
                $(this).addClass('active');
                $(this).find('.line-status').addClass('active');
            }
        })

        $(".step-content").each(function(index,element){
            $(this).removeClass('active-step');
            let step_id = $(this).data('id');
            if(step_id == step){
                $(this).addClass('active-step');
            }
        })
    }

    $(".close-wizard").click(function(){
        $(".warning-wizard").hide("slow");
    })

    $(document).ready(function(){
        var step2 = new Vue({
            el: '#invoice',
            data: {
                    title: 'Boutique Fashion',
                    footer: 'Software POS Cloud: Vendty.com',
                    image: '<?= base_url("uploads/default.png")?>',
                    address: 'Calle 127 #33-22',
                    phone: '(1) 235-6666',
                    propine: false,
                },
            methods: {
                previewFiles(e) {
                    const file = e.target.files[0];
                    this.image = URL.createObjectURL(file);
                }
            }
        })

        var step3 = new Vue({
            el: '#shop',
            data: {
                    shop_name: '',
                    local_domain: 'http://tienda.vendty.com/',
                    domain: '',
                    country: 'Colombia',
                    currency: 'COP',
                    stores_avaible: <?= $data["stores_avaibles"]; ?>,
                    template: '',
                    error: '',
                },
            methods: {
                localDomain() {
                    let avaible = true;
                    let store = this.shop_name;
                    $.each(this.stores_avaible,function(index,element){
                        let str = ""+$(this)[0].shopname;
                        let str2 = ""+store;
                        if(str == str2){
                            avaible = false;
                        }
                    })

                    if(avaible){
                        this.error = '';
                        this.local_domain = 'http://tienda.vendty.com/'+this.shop_name;
                    }else{
                        this.error = '(*) Nombre no disponible';
                    }
                },
                activeTemplate(template){
                    this.template = template;
                    $(".template").each(function(index,element){
                        $(this).click(function(){
                            $('.template').each(function(){
                                $(this).removeClass('active-template');
                            })
                            $(this).addClass("active-template");
                        })
                    })
                }
            }
        })

        var step4 = new Vue({
            el: '#finalize',
            data: {
                    url: "<?= site_url().'/frontend/load_settings'?>",
                    urlEmailSend: "<?= site_url().'/frontend/load_settings'?>",
                    user: "<?= $this->session->userdata('email') ?>"
                },
            methods: {
                sale_now: function () {
                    mixpanel.track("Paso 4 - Vender", {
                        "$email": email,
                        "$empresa": nombre_empresa,
                        "$tipo_negocio": type_business,
                    });
                    let store = '';
                    swal({
                        title: 'Estamos configurando tu negocio!',
                        text: 'No cierres la ventana, Esto puede tardar un momento.',
                        imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                        imageWidth: 200,
                        imageHeight: 200,
                        imageAlt: 'Cargando',
                        animation: false,
                        showConfirmButton: false
                    })

                    if(step3.error == ''){
                        store = step3.shop_name;
                    }
                    axios.post(this.url, {
                        type_business: type_business,
                        subcategory_business: subcategory_business,
                        title:step2.title,
                        footer:step2.footer,
                        image:step2.image,
                        address:step2.address,
                        phone:step2.phone,
                        shop_name:store,
                        local_domain:step3.local_domain,
                        domain:step3.domain,
                        country:step3.country,
                        currency:step3.currency,
                        template:step3.template,
                        propine:step2.propine,
                        email:this.user
                    })
                    .then(function (response) {
                        swal.close();
                        console.log(response);
                        if(type_business == "restaurant"){
                            location.href="<?= site_url("orden_compra/mi_orden/-1/".strtotime("now")); ?>";
                        }else{
                            location.href="<?= site_url('ventas/nuevo'); ?>";
                        }
                    })
                    .catch(function (error) {
                        swal.close();
                        console.log(error);
                        swal(
                            'Error inesperado!',
                            'Ocurrio un error al intentar cargar la información.',
                            'error'
                        )
                    });
                }
            }
        })
    })

    $(document).ready(function() {
        $.getJSON('<?php echo site_url('frontend/get_api_auth'); ?>', function (data) {
            localStorage.setItem('api_auth', JSON.stringify(data));
        })
    });
</script>

