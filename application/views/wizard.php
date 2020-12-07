<?php
if (isset($data["estado"]) && ($data["estado"] == 2) && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0) {
    $active = "inicio";
} else {
    $active = "graficas";
    if (isset($data["tipo_negocio"])) {
        if ($data["tipo_negocio"] == "restaurante" && ($isAdmin == 't' || $isAdmin == 'a')) {
            $active = "graficas";
        } else if ($data["tipo_negocio"] == "restaurante" && ($isAdmin != 't' && $isAdmin != 'a')) {
            $active = "restaurante";
        }
    }
}
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

    .page.animsition {background-color:#fff !important;padding-top:2% !important;}
</style>

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
                        <div class="col-md-6 type_business">
                            <div class="content-type" data-type="restaurant">
                                <img src="<?= base_url() ?>public/img/wedding-reception.png" alt="restaurante">
                                <p>Bar - Restaurante</p>
                            </div>
                        </div>
                        <div class="col-md-6 type_business">
                            <div class="content-type" data-type="retail">
                                <img src="<?= base_url() ?>public/img/full-items-inside-a-shopping-bag.png"
                                     alt="restaurante">
                                <p>Retail</p>
                            </div>
                        </div>
                    <!-- <div class="col-md-4 type_business">
                            <div class="content-type" data-type="fashion">
                                <img src="<?= base_url() ?>public/img/scarf-on-hanger.png" alt="restaurante">
                                <p>Moda</p>
                            </div>
                        </div> -->
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
                    
                    <div class="form-group">
                            <label for="country" class="col-sm-4 control-label">País:</label>
                            <div class="col-sm-8">
                                <input type="text" name="country" id="country" placeholder="Ejemplo: Colombia" class="form-control ml-0"
                                       v-model="country">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="currency" class="col-sm-4 control-label">Moneda:</label>
                            <div class="col-sm-8">
                                <input type="text" name="currency" id="currency" placeholder="Ejemplo: COP" class="form-control ml-0"
                                       v-model="currency">
                                <span class="help-block"></span>
                            </div>
                        </div>
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
                            <input type="file" name="logo" ref="myFiles" class="form-control file-logo"
                                   id="file-logo" @change="previewFiles">
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
                            <p><b>Fecha: <?= date('m-d-Y') ?></b></p>
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
                                <input type="text" name="name" id="name" v-model="shop_name" placeholder="Ejemplo: mi_tienda"
                                       class="form-control ml-0 mb-1" @keyup="localDomain">
                                <small class="warning">{{error}}</small>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="local_domain" class="col-sm-4 control-label">Dominio Local:</label>
                            <div class="col-sm-8">
                                <input type="text" name="local_domain" id="local_domain" v-model="local_domain"
                                       class="ml-0 form-control" readonly="">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="dominio" class="col-sm-4 control-label">Dominio:</label>
                            <div class="col-sm-8">
                                <input type="text" name="dominio" id="dominio"
                                       v-model="domain" <?php echo ($data['type_licence'] != 3) ? '' : '' ?>
                                       class="form-control ml-0">
                                <?php if ($data['type_licence'] != 3): ?>
                                    <small>El DNS de su dominio debe estar configurado en el panel de su proveedor de dominio. <br> Puede hacer <a href="https://ayuda.vendty.com/es/articles/3222373-configuracion-de-dominio" style="color: #04156a;" target="_blank">clic aquí</a> si desea ver paso a paso cómo configurar su dominio.</small>
                                <?php endif; ?>

                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <br><br>
                        <div class="form-group">
                            <label for="local_domain" class="col-sm-4 control-label">Título de la tienda:</label>
                            <div class="col-sm-8">
                                <input type="text" class="ml-0 form-control" name="store_title" id="store_title" v-model="store_title" placeholder="Ejemplo: Mi Tienda">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="local_domain" class="col-sm-4 control-label">Descripcion:</label>
                            <div class="col-sm-8">
                                <textarea type="text" name="store_description" id="store_description" v-model="store_description" placeholder="Indica una breve descripción para tu tienda virtual, eso es lo que los buscadores como google mostraran sobre tu tienda" v-model="store_description" class="ml-0 form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="local_domain" class="col-sm-4 control-label">Keywords:</label>
                            <div class="col-sm-8">
                                <input type="text" name="keywords" id="keywords" v-model="keywords" placeholder="Ejemplo: zapatos, ropa, bolsos" class="ml-0 form-control">
                                <small>Puede indicar palabras descriptivas sobre la tienda separadas por coma (,). Esto ayudará al posicionamiento de tu tienda y a encontrarla mucho más rápido.</small>
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
                            <?php foreach (get_image_templates_shop() as $template): ?>
                                <div class="col-md-4" data-id="">
                                    <div class="template content-template"
                                         @click="activeTemplate('<?= $template->nombre; ?>')">
                                        <img src="http://admintienda.vendty.com/storage/templates/<?= $template->ruta_img; ?>"
                                             alt="image">
                                    </div>
                                </div>
                            <?php  endforeach; ?>
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
            <a class="btn btn-success" href="<?= site_url('frontend/skip_configuration') ?>">Saltar
                configuración</a>
        </div>
        <div class="col-md-3 pull-right text-left mt-1">
            <button class="btn btn-success" id="prev-step" step='1'>Anterior</button>
            <button class="btn btn-success" id="next-step" step='1'>Siguiente</button>
        </div>
    </div>
</div>

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

                // case 'fashion':
                //     $(".fashion-buttons").addClass("active-subcategorie");
                // break;
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
                    prevArrow: '<div class="slick-prev"><img style="width: 30px; height: 30px;" src="http://prueba2.vendty.com/uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                    nextArrow: '<div class="slick-next"><img style="width: 30px; height: 30px;" src="http://prueba2.vendty.com/uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
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
                    store_title: '',
                    store_description: '',
                    keywords: '',
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
                        store_title:step3.store_title,
                        store_description:step3.store_description,
                        keywords:step3.keywords,
                        propine:step2.propine,
                        email:this.user
                    })
                    .then(function (response) {
                        swal.close();
                        console.log(response);
                        if(type_business == "restaurant"){
                            location.href="<?= site_url("quickservice/index"); ?>";
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
</script>