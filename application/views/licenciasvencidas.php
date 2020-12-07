<?php
$ip = "";
$paisip = "Colombia";
//busco la ip
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$res = file_get_contents('https://www.iplocate.io/api/lookup/' . $ip);
$res = json_decode($res);
$paisip = $res->country;

if (empty($paisip)) {
    $paisip = "Colombia";
}

$id_db_config = (!empty($this->session->userdata('db_config_id'))) ? $this->session->userdata('db_config_id') : 0;

if (($id_db_config == '11152') || ($id_db_config == '13606')) {
    $paisip = "Colombia1";
}

?>
<html class="no-js css-menubar">
    <head>
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

        <!-- Page -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/base/assets/examples/css/dashboard/v1.min081a.css?v2.0.0">

        <!-- Fonts -->
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/web-icons/web-icons.min081a.css?v2.0.0">
        <link rel="stylesheet" href="<?php echo base_url("public/v2"); ?>/global/fonts/glyphicons/glyphicons.min.css?v2.0.0">
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
        <link href="<?php echo base_url(); ?>public/css/video.css" rel="stylesheet" type="text/css" />

        <!-- Scripts -->

        <script type="text/javascript" src="https://checkout.epayco.co/checkout.js"></script>
        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/modernizr/modernizr.min.js"></script>
        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/breakpoints/breakpoints.min.js"></script>
        <script src="<?php echo base_url("public"); ?>/js/plugins/jquery/jquery-3.3.1.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url(); ?>public/js/plugins/bootstrap/bootstrap.min.js'></script>
        <script src="<?php echo base_url("public/js"); ?>/sweetalert2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/sweetalert2.min.css">

        <script src="<?php echo base_url(); ?>public/js/video.js"></script>
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>

        <script>
            //epayco
            var dataepa="";
            var licenciaSeleccionada=0;
            var ippais='<?php echo $paisip ?>';
            var currency="cop";

            if (ippais!="Colombia"){
                currency='usd';
            } else {
                currency="cop";
            }
            var epaycooption=1;
            var keyepayco = 'a9743da1bac57f18aeef6b484a2dec95';
            var keyepayco2 = '2815e60ed8e00cbfc47180d0d37a7a6c';
            var btnlicen="" ;


            function pagarLicenciapaypal(e){
                btnlicen=e;
                epaycooption=0;
                //$('#myModal').modal('show');
                var referenceCode = 'Licencia Vendty' + $('#licenciaId_'+ e).val()+"_"+$('#totalLicencia_' + e).val();
                var total = $('#totalLicencia_paypal_' + e).val();
                var totalp = $('#totalLicencia_' + e).val();
                var factura=Math.random()+"1238_"+Math.random();
                //paypal
                paypal.Button.render({
                    // Configure environment
                    //env: 'sandbox',
                    env: 'production',
                    client: {production: 'AUsz8hM_W5fTD4QmgE3GG9hk5jQ2I9Doc_TNW7pbgb10D1_PLURGjQDTwRSvanqLF6_E7u-9gZHmf97o'//arnulfoospino
                    },
                    // Customize button (optional)
                    //locale: 'en_US',
                    locale: 'es_CO',
                    style: {
                        label: 'paypal',
                        size:  'small', // small | medium | large | responsive
                        shape: 'rect',   // pill | rect
                        color: 'silver'   // gold | blue | silver | black
                    },
                    // Enable Pay Now checkout flow (optional)
                    commit: true,

                    // Set up a payment
                    payment: function(data, actions) {

                        return actions.payment.create({
                            transactions: [{
                            amount: {
                                total: total,
                                currency: 'USD'
                            },
                            description: referenceCode

                            }],
                            //note_to_payer: 'Contact us for any questions on your order.'
                        });
                    },
                    // Execute the payment
                    onAuthorize: function(data, actions) {
                    //onAuthorize: function(payload) {
                        return actions.payment.execute().then(function(data) {
                        //return actions.payment.get().then(function(data) {
                            var shipping = data.payer.payer_info.shipping_address;
                            var status = data.state;
                            // Show a confirmation message to the buyer
                            if (status=="approved"){
                                $.ajax({
                                    url: "<?php echo site_url("frontend/responsepaypal") ?>",
                                    data:  {'data':data,'referencia':referenceCode,'total':total}, //datos que se envian a traves de ajax
                                    type:  'post', //método de envio
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.success==1){
                                            swal({
                                                position: 'center',
                                                type: 'success',
                                                title: 'La licencia fue pagada exitosamente',
                                                showConfirmButton: false,
                                                timer:1500
                                            });
                                            setTimeout(function(){
                                                location.reload(true);
                                            }, 1510);
                                        } else {
                                            swal({
                                                position: 'center',
                                                type: 'error',
                                                title: 'Hubo un error',
                                                showConfirmButton: false,
                                                timer:1500
                                            });
                                            setTimeout(function(){
                                                location.reload(true);
                                            }, 1600);
                                        }
                                    }
                                });
                            }

                        });
                    },
                    onError: function (err) {
                        // Show an error page here, when an error occurs
                        //window.alert('Hubo un error');
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'Disculpe, hubo un error al realizar la transacción, intente más tarde',
                            showConfirmButton: false,
                            timer:1500
                        });
                    }
                }, 'pagar_paypal_nuevo');

            }

            var planVencido = "";
            var licenciaSeleccionada = 0;

            function pagarLicencia(e){
                epaycooption=1;
                //$('#myModal').modal('show');
                licenciaSeleccionada = e;
                var referenceCode = 'Licencia Vendty' + $('#licenciaId_'+ e).val();
                var totalp = $('#totalLicencia_' + e).val();
                var nombre_plan = $('#nombrePlan_' + e).val();
                planVencido = nombre_plan;
                var totald = $('#totalLicencia_paypal_' + e).val();
                var factura=Math.random()+"1238_"+Math.random();
                var extra1= "";

                if (currency=='cop'){
                    total=totalp;
                    extra1=totald;
                } else {
                    total=totald;
                    extra1=totalp;
                }

                dataepa={
                    //Parametros compra (obligatorio)
                    name: "Pago de Licencia",
                    description: referenceCode,
                    invoice: factura,
                    currency: currency,
                    extra1: extra1,
                    amount: total,
                    tax_base: "0",
                    tax: "0",
                    country: "co",
                    lang: "es",
                    external: "true",
                    confirmation: "http://pos.vendty.com/index.php/response",
                    response: "http://pos.vendty.com/index.php/response"
                }


                pagarLicenciapaypal(e);
                // console.log(dataepa);
                //ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
                mostrarMetodos2();
            }

            function pagoRecurrente2() {
                epaycooption = 1;

                var idPlanActual = $('#plan_cliente_id').val();
                var action = "<?=site_url('frontend/creditCardPayment')?>";
                var renovacion = "<?=site_url('responseCredit')?>";
                var primera = "<?=site_url('responseCreditDos')?>";
                var api_auth = JSON.parse(localStorage.getItem("api_auth"));
                var referenceCode = 'Licencia Vendty' + $('#licenciaId_'+ licenciaSeleccionada).val();
                var totalp = $('#totalLicencia_' + licenciaSeleccionada).val();
                var nombre_plan = $('#nombrePlan_' + licenciaSeleccionada).val();
                planVencido = nombre_plan;
                var totald = $('#totalLicencia_paypal_' + licenciaSeleccionada).val();
                var factura=Math.random()+"1238_"+Math.random();
                var extra1= "";

                if (currency=='cop'){
                    total=totalp;
                    extra1=totald;
                } else {
                    total=totald;
                    extra1=totalp;
                }

                dataepa={
                    //Parametros compra (obligatorio)
                    name: "Pago de Licencia",
                    description: referenceCode,
                    invoice: factura,
                    currency: currency,
                    extra1: extra1,
                    amount: total,
                    tax_base: "0",
                    tax: "0",
                    country: "co",
                    lang: "es",
                    external: "true",
                    confirmation: "http://pos.vendty.com/index.php/response",
                    response: "http://pos.vendty.com/index.php/response"
                }
                data = {
                    client: {
                        name: $('#card-name').val(), // vendty
                        doc_type: $('#doc-type').val(),
                        doc_number: $('#doc-number').val(),
                        email: api_auth.user.email, //$('#card-email').val(), //api_auth.user.email, // vendty
                        phone: $('#client-phone').val(), // vendty
                        address: api_auth.warehouse.address.address
                    },
                    creditCard: {
                        card_name: $('#card-name').val(), // vendty
                        card_email: api_auth.user.email, //$('#card-email').val(), //api_auth.user.email, // vendty
                        card_number: $('#card-number').val(),
                        card_cvc: $('#card-cvc').val(),
                        card_exp_month: $('#card-exp-month').val(),
                        card_exp_year: $('#card-exp-year').val(),
                        card_type: $('#card-type').val()
                    },
                    plan: planSeleccionado,
                    user_id: api_auth ? api_auth.user.id : '0' + $('#licenciaId_'+ licenciaSeleccionada).val(),
                    licencia: referenceCode,
                    numeroDeLicencias,
                    confirmation : "<?=site_url('responseCredit')?>",
                    test: $('#test').val()
                };

                // console.log(data);

                $("#buttons-recurrent-payment").hide();
                $("#loading").show();
                $.ajax({
                    url: action,
                    data:  data, //datos que se envian a traves de ajax
                    type:  'post', //método de envio
                    dataType: "json",
                    success: function(data) {
                        // // console.log(data);
                        if (data.success && data.data.data.estado == 'Aceptada'){
                            mostrarConfirmacion2({
                                success: true,
                                title:  'La licencia fue pagada exitosamente',
                                description: "En un plazo de 10 minutos su licencia se actualizara con el pago realizado",
                                data
                            })
                            $("#buttons-recurrent-payment").show();
                            $("#loading").hide();
                        } else {
                            mostrarConfirmacion2({
                                success: false,
                                title:  'No se ha podido realizar el pago',
                                description: "Por favor vuelve a realizar el pago en unos minutos si el error persiste comunicate con soporte",
                                message: data.data.message,
                                data
                            })
                            $("#buttons-recurrent-payment").show();
                            $("#loading").hide();
                        }
                    },
                    fail: function($response) {
                        //console.error($response);
                        $("#buttons-recurrent-payment").show();
                        $("#loading").hide();
                    },
                    complete: function () {
                        $("#buttons-recurrent-payment").show();
                        $("#loading").hide();
                    }
                });

            }

            htmlPagoCorrecto = '<div class="flex-padre col-md-12">'+
                    '<div class="flex-hijo col-md-12"><p class="col-md-4 col-md-offset-2 text-right">Referencia de pago</p><p class="col-md-4  text-left">{ref_epayco}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Descripcion</p><p class="col-md-4 text-left">{description}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Valor</p><p class="col-md-4 text-left">{value}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Estado del pago</p><p class="col-md-4  text-left">{state}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Nombre</p><p class="col-md-4  text-left">{name}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Documento</p><p class="col-md-4  text-left">{identification}</p>'+
                    '</div></div>';
            htmlPagoIncorrecto = '<div class="flex-padre col-md-12">'+
                    '<div class="flex-hijo col-md-12"><p class="col-md-4 col-md-offset-2 text-right">Mensaje</p><p class="col-md-4  text-left">{response}</p>'+
                    '</div></div>';


            function mostrarConfirmacion2(data) {
                $("#title-response2").text(data.title);
                $("#description-response2").text(data.description);
                if (data.success) {
                    $("#image-result2").html('<div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;"><div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span><span class="swal2-success-line-long"></span><div class="swal2-success-ring"></div><div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div></div>');
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{ref_epayco}',data.data.data.data.ref_payco);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{description}',data.data.data.data.descripcion);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{value}',data.data.data.data.valor);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{state}',data.data.data.data.respuesta);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{name}',data.data.data.data.nombres + " " + data.data.data.data.apellidos);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{identification}',data.data.data.data.tipo_doc + " " + data.data.data.data.documento);
                    $("#data2").html(htmlPagoCorrecto);
                    $("#debit-message2").show();
                } else {
                    $("#image-result2").html('<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>');
                    htmlPagoIncorrecto = htmlPagoIncorrecto.replace('{response}', data.message + '<br>' + data.data.data.data.errors);
                    $("#data2").html(htmlPagoIncorrecto);
                    $("#debit-message2").hide();
                }
                $("#pago-recurrente").hide();
                $("#panel-response2").show();
            }

            function mostrarMetodos2() {
                $("#licencias-vencidad").hide();
                $("#seleccion-metodo").show();
            }

            function mostrarPagotarjeta() {
                mostrarPlanVencido();
                $("#seleccion-metodo").hide();
                $("#pago-recurrente").show();
            }

            function cancelarPagosVencidos() {
                $("#seleccion-metodo").hide();
                $("#pago-recurrente").hide();
                $("#panel-response2").hide();
                $("#pagar_paypal_nuevo").html("");
                $("#licencias-vencidad").show();
            }

            function mostrarPlanVencido() {
                planes.map((plan) => {
                    if (plan.nombre_plan == planVencido) {
                        planSeleccionado = plan;
                        $periodo = plan.nombre_plan.includes("MENSUAL")
                        $("#nombrePlanVencido").text(nombrePlanCorto(plan.nombre_plan));
                        $("#precioPlanvencido").html('<span class="dollar">$</span>'+plan.valor_final);
                        $("#periodoVencido").text($periodo ? "MENSUAL" : "ANUAL");
                        $("#cajasvencidas").text(plan.cajas);
                        $("#usuariosvencidos").text(plan.usuarios);
                    }
                })


            }

            function pagoPSE2() {
                ePayco.checkout.configure({key: keyepayco2, test: false}).open(dataepa);
            }

            function pagoEfectivo() {
                ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
            }

            function pagoPaypal() {
                //alert('paypal');
            }

            function rand_code(chars, lon){
                code = "";
                for (x=0; x < lon; x++){
                    rand = Math.floor(Math.random()*chars.length);
                    code += chars.substr(rand, 1);
                }
                return code;
            }


            function seleccionarPlan(e){
                //$('#myModal').modal('show');

                var text = "Usted acaba de seleccionar el plan ";
                var valor = $(e).val();
                var result = valor.split('-');
                var factura=Math.random()+"1238_"+Math.random();
                $("#parrafo").empty();
                $("#tituloPlan").empty();
                $("#parrafo").append('$' + result[1]);
                $("#tituloPlan").append(result[2]);
                $('#divNuevoPlan').show();
                var referenceCode = 'Licencia Vendty';
                var total = result[1];
                referenceCode += $('#licenciaId').val() + "-" + result[0];
                text += result[2] + " el cual tiene un precio de $" + total + ".";
                var extra1= "";

                if (currency=='usd'){
                    extra1=result[3];
                }

                dataepa={
                    //Parametros compra (obligatorio)
                    name: "Pago de Licencia",
                    description: referenceCode,
                    invoice: factura,
                    //currency: "cop",
                    currency: currency,
                    amount: total,
                    extra1: extra1,
                    tax_base: "0",
                    tax: "0",
                    country: "co",
                    lang: "es",
                    external: "true",
                    confirmation: "http://pos.vendty.com/index.php/responseDos",
                    response: "http://pos.vendty.com/index.php/responseDos"
                }
                seleccionarPlanpaypal(e);
                mostrarMetodos();
                //alert("id plan "+result[0]);
                mostrarSeleccionado(result[0]);
                //ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);

            }

            function seleccionarPlanpaypal(e){
                //$('#myModal').modal('show');
                //'6-70000-LICENCIA BASICO MENSUAL-1836';
                var text = "Usted acaba de seleccionar el plan ";
                var valor = $(e).val();
                var result = valor.split('-');
                // console.log(result);
                var api_auth = JSON.parse(localStorage.getItem('api_auth'));

                plan_actual = $("#plan_actual").val();
                var referenceCode = "";
                if (plan_actual == 1) {
                    referenceCode = 'Licencia Vendty'+$('#licenciaId').val()+"-"+result[0]+"-"+result[1];
                } else {
                    referenceCode = 'Licencia Vendty'+$('#licenciaId').val()+"_"+result[1];
                }


                var factura=Math.random()+"1238_"+Math.random();
                //alert(referenceCode);
                var total = result[3];
                paypal.Button.render({
                    env: 'production',
                    client: {
                        production: 'AUsz8hM_W5fTD4QmgE3GG9hk5jQ2I9Doc_TNW7pbgb10D1_PLURGjQDTwRSvanqLF6_E7u-9gZHmf97o'//arnulfoospino
                    },
                    locale: 'es_CO',
                    style: {
                        label: 'paypal',
                        size:  'small', // small | medium | large | responsive
                        shape: 'rect',   // pill | rect
                        color: 'silver'   // gold | blue | silver | black
                    },
                    // Enable Pay Now checkout flow (optional)
                    commit: true,

                    // Set up a payment
                    payment: function(data, actions) {
                        return actions.payment.create({
                            transactions: [{
                            amount: {
                                total: total,
                                currency: 'USD'
                            },
                            description: referenceCode

                            }],
                            //note_to_payer: 'Contact us for any questions on your order.'
                        });
                    },
                    // Execute the payment
                    onAuthorize: function(data, actions) {
                    //onAuthorize: function(payload) {
                        return actions.payment.execute().then(function(data) {
                        //return actions.payment.get().then(function(data) {
                            var shipping = data.payer.payer_info.shipping_address;
                            var status = data.state;
                            // Show a confirmation message to the buyer
                            if (status=="approved"){
                                $.ajax({
                                    url: "<?php echo site_url("frontend/responseDospaypal") ?>",
                                    data:  {'data':data,'referencia':referenceCode,'total':total}, //datos que se envian a traves de ajax
                                    type:  'post', //método de envio
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.success==1){
                                            swal({
                                                position: 'center',
                                                type: 'success',
                                                title: 'La licencia fue pagada exitosamente',
                                                showConfirmButton: false,
                                                timer:1500
                                            });
                                            setTimeout(function(){
                                                location.reload(true);
                                            }, 1510);
                                        } else {
                                            swal({
                                                position: 'center',
                                                type: 'error',
                                                title: 'Hubo un error',
                                                showConfirmButton: false,
                                                timer:1500
                                            });
                                            setTimeout(function(){
                                                location.reload(true);
                                            }, 1600);
                                        }
                                    }
                                });
                            }

                        });
                    },
                    onError: function (err) {
                        // Show an error page here, when an error occurs
                        //window.alert('Hubo un error');
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'Disculpe, hubo un error al realizar la transacción, intente más tarde',
                            showConfirmButton: false,
                            timer:1500
                        });
                    }
                }, '#pagoPaypal');
            }

            function renderBotonPago(e){
                //$('#myModal').modal('show');
                //'6-70000-LICENCIA BASICO MENSUAL-1836';
                var text = "Usted acaba de seleccionar el plan ";
                var valor = e;
                var result = valor.split('-');

                var factura=Math.random()+"1238_"+Math.random();
                var referenceCode = 'Licencia Vendty'+result[3]+"-"+result[0]+"-"+result[4];
                var total = result[1];
                paypal.Button.render({
                    env: 'production',
                    client: {
                        production: 'AUsz8hM_W5fTD4QmgE3GG9hk5jQ2I9Doc_TNW7pbgb10D1_PLURGjQDTwRSvanqLF6_E7u-9gZHmf97o'//arnulfoospino
                    },
                    locale: 'es_CO',
                    style: {
                        label: 'paypal',
                        size:  'small', // small | medium | large | responsive
                        shape: 'rect',   // pill | rect
                        color: 'silver'   // gold | blue | silver | black
                    },
                    // Enable Pay Now checkout flow (optional)
                    commit: true,

                    // Set up a payment
                    payment: function(data, actions) {
                        return actions.payment.create({
                            transactions: [{
                            amount: {
                                total: total,
                                currency: 'USD'
                            },
                            description: referenceCode

                            }],
                            //note_to_payer: 'Contact us for any questions on your order.'
                        });
                    },
                    // Execute the payment
                    onAuthorize: function(data, actions) {
                    //onAuthorize: function(payload) {
                        return actions.payment.execute().then(function(data) {
                        //return actions.payment.get().then(function(data) {
                            var shipping = data.payer.payer_info.shipping_address;
                            var status = data.state;
                            // Show a confirmation message to the buyer
                            if (status=="approved"){
                                $.ajax({
                                    url: "<?php echo site_url("frontend/responseDospaypal") ?>",
                                    data:  {'data':data,'referencia':referenceCode,'total':total}, //datos que se envian a traves de ajax
                                    type:  'post', //método de envio
                                    dataType: "json",
                                    success: function(data) {
                                        if (data.success==1){
                                            swal({
                                                position: 'center',
                                                type: 'success',
                                                title: 'La licencia fue pagada exitosamente',
                                                showConfirmButton: false,
                                                timer:1500
                                            });
                                            setTimeout(function(){
                                                location.reload(true);
                                            }, 1510);
                                        } else {
                                            swal({
                                                position: 'center',
                                                type: 'error',
                                                title: 'Hubo un error',
                                                showConfirmButton: false,
                                                timer:1500
                                            });
                                            setTimeout(function(){
                                                location.reload(true);
                                            }, 1600);
                                        }
                                    }
                                });
                            }

                        });
                    },
                    onError: function (err) {
                        // Show an error page here, when an error occurs
                        //window.alert('Hubo un error');
                        swal({
                            position: 'center',
                            type: 'error',
                            title: 'Disculpe, hubo un error al realizar la transacción, intente más tarde',
                            showConfirmButton: false,
                            timer:1500
                        });
                    }
                }, '#paypal-button_p'+result[0]);
            }

            function seleccionTipoLicencia(sw){
                if (sw==1){
                    var ver = "mensual";
                    var ocultar = "anual";
                    $("#seccionMensual").css("display", "block");
                    $("#seccionAnual").css("display", "none");
                } else {
                    var ver = "anual";
                    var ocultar = "mensual";
                    $("#seccionMensual").css("display", "none");
                    $("#seccionAnual").css("display", "block");
                }
                var option1 = "#" + ver;
                var option2 = "#" + ocultar;
                $(option1).removeClass("color-option2");
                $(option1 + "Text").removeClass("color-text2");
                $(option1).addClass("color-option");
                $(option1 + "Text").addClass("color-text");
                /****************************************/
                $(option2).removeClass("color-option");
                $(option2 + "Text").removeClass("color-text");
                $(option2).addClass("color-option2");
                $(option2 + "Text").addClass("color-text2");
            }


            function load_provincias_from_pais(pais){
                $.ajax({
                    url: "<?php echo site_url("frontend/load_provincias_from_pais") ?>",
                    data: {"pais" : pais},
                    dataType: "json",
                    success: function(data) {
                        $("#ciudad_factura").html('');
                        $.each(data, function(index, element){
                            provincia = "<?php echo set_value('ciudad_factura'); ?>";
                            sel = provincia == element[0] ? "selected='selectted'" : '';
                            $('#ciudad_factura').append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");

                        });
                    }
                });
            }

            function cambiarLicencia(e){
                licenciaSeleccionada = $('#licenciaId_'+ e).val();
                //busco los usuarios asociados a la licencia
                $.ajax({
                    url: "<?php echo site_url("almacenes/usuarios_licencia") ?>",
                    data: {licencia : licenciaSeleccionada},
                    type:  'post',
                    dataType: "json",
                    success: function(data) {
                        if (data.success==1){
                            useralmacen=parseInt(data.cantusuarios);
                            if (data.cantusuarios>0){
                                //desabilito los planes
                                $(".planes").each(function() {
                                    users_planes=parseInt($(this).attr('data-users'));
                                    if (data.cantusuarios>users_planes){
                                        $(this).attr("disabled", true);
                                    }
                                });
                            }
                        }
                    }
                });

                $('#myModalplan').modal('show');

            }

            function desactivarLicencia(e){

                var licencia = $('#licenciaId_'+ e).val();
                var nombrealamcen = $('#almacen_nombre_'+ e).val();
                var id_almacen = $('#almacen_'+ e).val();


                const swalWithBootstrapButtons = swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons({
                title: '¿Está seguro de desactivar el almacén '+nombrealamcen+'?',
                text: "Solo podrá ver el histórico de la información de este almacén, No podrá facturar por él",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Desactivar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    ///ajax a desactivar la licencia
                    $.ajax({
                        url: "<?php echo site_url("almacenes/desactivar_almacen") ?>",
                        type:  'post', //método de envio
                        dataType: "json",
                        data: {"almacen" : id_almacen, "licencia":licencia},
                        success: function(data) {
                            // console.log(data);
                            if (data.success==1){
                                /*swalWithBootstrapButtons(
                                'Desactivada',
                                'La licencia del almacén '+nombrealamcen+' fue desactivada exitósamente',
                                'success'
                                )*/
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'La licencia del almacén '+nombrealamcen+' fue desactivada exitósamente',
                                    showConfirmButton: true
                                }).then((result) => {
                                        if (result.value) {
                                            window.location = "<?php echo site_url("frontend/index") ?>"
                                        }
                                    })

                            } else {
                                swalWithBootstrapButtons(
                                'Error',
                                'La licencia del almacén '+nombrealamcen+' no pudo ser desactivada, intente más tarde',
                                'error'
                                )
                            }
                        }
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })

            }

            function seleccionarPlanRenovacion(e){
                var valor = $(e).val();
                var result = valor.split('-');
                var plan=result[0];

                //Cambiar el plan a la licencia
                $('#myModalplan').modal('hide');

                if ((plan != "") &&(licenciaSeleccionada !="")){

                    $.ajax({
                        url: "<?php echo site_url("almacenes/cambiar_plan_licencia") ?>",
                        type:  'post',
                        dataType: "json",
                        data: { licencia: licenciaSeleccionada, plan: plan },
                        success: function(data) {
                            if (data.success==1){
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'El Plan fue cambiado exitósamente',
                                    showConfirmButton: false,
                                    timer:1500
                                })

                                setTimeout(function(){
                                    //$('#myModal').modal('show');
                                location.reload(true);
                                }, 1600);

                                //epayco
                                /*var referenceCode = 'Licencia Vendty' + licenciaSeleccionada;
                                var total = result[1];
                                var factura=Math.random()+"1238_"+Math.random();

                                dataepa={
                                    //Parametros compra (obligatorio)
                                    name: "Pago de Licencia",
                                    description: referenceCode,
                                    invoice: factura,
                                    currency: "cop",
                                    amount: total,
                                    tax_base: "0",
                                    tax: "0",
                                    country: "co",
                                    lang: "es",
                                    external: "true",
                                    confirmation: "http://pos.vendty.com/index.php/response",
                                    response: "http://pos.vendty.com/index.php/response"
                                }  */

                            /* setTimeout(function(){
                                    $('#myModal').modal('show');
                                }, 1600);*/

                            } else {
                                swal(
                                'Error',
                                'No se pudo actualizar al plan seleccionado, intente más tarde',
                                'error'
                                )
                            }
                        }
                    });
                }
            }

            $(document).ready(function () {
            /* plan= '<?php echo $licencias[0]['id_plan'] ?>';
                //pagarLicenciapaypal(2);
                if (plan!=1){
                    $('#myModal').modal('show');
                }*/
                $("#pais_factura").change(function () {
                    load_provincias_from_pais($(this).val());
                });

                var pais = $("#pais_factura").val();
                if (pais != "") {
                    load_provincias_from_pais(pais);
                } else {
                    load_provincias_from_pais('Colombia');
                }



                $("#formu_factura").submit(function (e) {
                    e.preventDefault();
                    nombre=$("#nombreempresa").val().trim();
                    direccion=$("#direccion_empresa").val().trim();
                    email=$("#email").val().trim();
                    tipo_identificacion=$("#tipo_identificacion").val().trim();
                    numero_identificacion=$("#numero_identificacion").val().trim();
                    pais=$("#pais_factura").val().trim();
                    ciudad=$("#ciudad_factura").val().trim();

                    if ((nombre!="")&&(direccion!="")&&(email!="")&&(tipo_identificacion!="")&&(numero_identificacion!="")&&(pais!="")&&(ciudad!="")){

                        url=$(this).attr('action');
                        $("#mensaje_error").addClass("hidden");
                        $("#mensaje_error").html("");
                        $.ajax({
                            url: url,
                            type:  'post',
                            dataType: "json",
                            data:  $("#formu_factura").serialize(),
                            success: function(data) {
                                if (data.success==1){
                                    $('#myModal').modal('hide');
                                    //epayco
                                    /*if (epaycooption==1){
                                        //ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
                                    } else {
                                        //paypal
                                        //$("#btn_pagar_"+btnlicen).addClass('hidden');
                                        //$("#paypal-button_"+btnlicen).removeClass('hidden');
                                    }*/


                                } else {
                                    $("#mensaje_error").removeClass("hidden");
                                    $("#mensaje_error").html("Todos los campos son obligatorios");
                                }
                            }
                        });

                    } else {
                        $("#mensaje_error").removeClass("hidden");
                        $("#mensaje_error").html("Todos los campos son obligatorios");
                    }
                });
            });

        </script>

        <style>
                #toogle-select {
                    width: 420px;
                    height: 50px;
                    border: 2px solid #8a8787;
                    border-radius: 30px;
                    margin: 7px;
                    text-align: center;
                }
                .button-select {
                    width: 180px;
                    height: 40px;
                    border-radius: 30px;
                    margin: 2px;
                    padding: 0px;
                }
                .color-option {
                    background: #404040;
                    border: 1px solid #404040;
                }
                .color-text {
                    color: white;
                }
                .color-option2 {
                    background: white;
                }
                .color-text2 {
                    color: #404040;
                }
                .divSelect {
                    display: inline-block !important;
                }
                #anual:hover, #mensual:hover {
                    background-color: #8A8787;
                }
                .tr-padding-cero {
                    padding: 0px;
                }
                .modal-title{
                    padding-left:2%;
                }
                .modal{
                    overflow: hidden;
                    min-height: 90%;
                }
                .modal-body{
                    max-height:70%;
                    overflow-y: scroll !important;
                    overflow-x: hidden !important;
                }

                .form-horizontal .control-label {
                    padding-top: 0px;
                    margin-bottom: 0;
                    text-align: left;
                    font-size:12px;
                }
                .form-horizontal .control-label span{
                    color:red;
                }
                button.salir{
                    margin-top: 2% !important;
                    margin-bottom: 2% !important;
                }
                table
                {
                    font-size: 12px;
                }
                td,th{
                    vertical-align: inherit !important;
                }
                body{
                    overflow-x: auto;
                    background-color: #F1F3F1 !important;
                }
                #seccionMensual, #seccionAnual{
                    text-align: -webkit-center;
                }
                .recuadro{
                    margin-top:2%;
                    background-color: #FFF !important;
                    border-radius: 5px;
                }
                /****radio**/

                /*tabla planes*/

                .table.gratis>tbody>tr>td, .table.gratis>tfoot>tr>td, .table.gratis>thead>tr>td{
                    border-top: 0px solid #e4eaec;
                    text-align: center;
                    padding: 3px !important;

                }
                .table.gratis>thead>tr>th h6{
                    color: #FFF !important;
                }

                .table.gratis{
                    -webkit-box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.1);
                    -moz-box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.1);
                    box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.1);
                }
                .table.gratis>thead>tr>th{
                    border-top: 0px solid #e4eaec;
                    text-align: center;

                }

                .bg-titulo{
                    padding: 0px;
                    text-align: center;
                    background-color: #5ca745;

                }
                .table.gratis{
                    border: #CCC 1px solid;
                }
                .promo{
                    margin-bottom: 0;
                }
                .preciop{
                    height: 85px;
                }
                .preciop h2{
                    font-weight: 800;
                }
                .radios{
                    padding-top: 2% !important;
                    padding-bottom: 2% !important;
                }
                /****/

                * {
                box-sizing: border-box;
                }

                input[type=radio] {
                display: none;
                }

                .circle {
                position: absolute;
                width: 20px;
                height: 20px;
                top: 50%;
                left: 0%;
                transform: translate(-50%, -50%);
                filter: url('#gooey');
                }

                @keyframes circle__in {
                0% {
                    transform: translate(-50%, -50%) scale(1);
                }

                16% {
                    transform: translate(-50%, -50%) scale(0.95, 1.05);
                }

                33% {
                    transform: translate(-50%, -50%) scale(1);
                }

                50% {
                    transform: translate(-50%, -50%) scale(1.05, 0.95);
                }

                66% {
                    transform: translate(-50%, -50%) scale(1);
                }

                83% {
                    transform: translate(-50%, -50%) scale(0.98, 1.02);
                }

                100% {
                    transform: translate(-50%, -50%) scale(1);
                }
                }


                input:checked + .circle {
                transform-origin: 50% 50%;
                animation-name: circle__in;
                animation-duration: 750ms;
                animation-timing-function: linear;
                }

                .circle {
                transform-origin: 50% 50%;
                animation-name: circle__out;
                animation-duration: 1000ms;
                animation-timing-function: ease;
                }

                .circle--outer {
                width: 20px;
                height: 20px;
                border-radius: 100%;
                border: 1px solid #5ca745;
                }

                .circle--inner {
                top: 5px;
                left: 5px;
                position: absolute;
                width: 10px;
                height: 10px;
                border-radius: 100%;
                background:  #5ca745;
                }

                @keyframes circle--inner__in {
                0% {
                    transform: scale(0.0);
                }

                80% {
                    transform: scale(1.02);
                }

                100% {
                    transform: scale(1);
                }
                }

                input:checked + .circle .circle--inner {
                transform-origin: 50% -20%;
                animation-name: circle--inner__in;
                animation-duration: 500ms;
                animation-timing-function: cubic-bezier(0.85, 0, 0.2, 1);
                }

                @keyframes circle--inner__out {
                0% {
                    transform: scale(1);
                }

                80% {
                    transform: scale(0.19);
                }

                99% {
                    transform: scale(0.21);
                }

                100% {
                    transform: scale(0);
                }
                }

                .circle--inner {
                    animation-name: circle--inner__out;
                    animation-duration: 500ms;
                    animation-timing-function: cubic-bezier(0.85, 0, 0.2, 1);
                    animation-fill-mode: forwards;
                }
                label{
                    height: 40px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .circle--inner__1 { transform-origin: -12% -8%; }
                .circle--inner__2 { transform-origin: -35% 50%; }
                .circle--inner__3 { transform-origin: 60% 130%; }
                .circle--inner__4 { transform-origin: 112% 90%; }
                .circle--inner__5 { transform-origin: 75% -30%; }

                .letrasradio{
                    margin-left: -40%;
                    padding-top: 12px;
                    position: absolute;
                }
                .letrasradio2{
                    padding-top: 12px;
                    position: absolute;
                }
                .btnradio{
                    margin-bottom: 1%;
                }

        </style>

        <script>

            var planes = <?php echo json_encode($planes) ?>;
            var planSeleccionado = {};
            var numeroDeLicencias = 0;

            function mostrarPlanes(id = false) {
                /*api_auth = JSON.parse(localStorage.getItem('api_auth'));
                plan_id = id ? id : api_auth.license.plan.id;
                total = 0;
                licencias = licenciasAPagar.split('y')
                licencias = licencias[1].split('-')
                numeroDeLicencias = licencias.length;
                licencias.map(item => {
                    items = item.split('_');
                    total += parseFloat(items[items.length - 1]);
                })

                if (plan_id == 1) {
                    $("#panel-planes").show();
                } else {
                    $("#panel-pagos").show();
                    mostrarSeleccionado(plan_id, total);
                }*/
                $("#panel-planes").show();
                $("#panel-licencias").hide();
            }

            function checkLicencia(lic, idplan) {
                // console.log('licencia', lic);
                // console.log('plan', idplan);
                licenciaSeleccionada = lic + "-" + idplan;
                // console.log(licencia)
            }

            function mostrarPagos() {
                $("#panel-pagos").show();
                $("#panel-planes").hide();
            }

            function cancelarPagos() {
                $("#panel-pagos").hide();
                $("#panel-pagos2").hide();
                $("#panel-response").hide();
                $("#buttons-recurrent-payment").show();
                $("#panel-metodos").hide();
                $("#loading").hide();
                $("#pagoPaypal").html("");
                $("#seleccion-metodo").hide();
                $("#panel-planes").show();
                volverPlanes();
            }

            $("#calcelarPago").click(function () {
                $("#panel-pagos").hide();
                $("#panel-pagos2").hide();
                $("#panel-response").hide();
                $("#buttons-recurrent-payment").show();
                $("#panel-metodos").hide();
                $("#loading").hide();
                $("#panel-planes").show();
                volverPlanes();
            });

            function closeResponse() {
                $("#panel-response").hide();
            }

            function selectPaymentMethod(e) {
                paymentMethod = $(e).val();
                switch(paymentMethod){
                    case 'credit-card' :
                        $('#form-cash-payment').hide();
                        $('#form-bank-account').hide();
                        $('#form-credit-card').show();
                    break;
                    case 'transfer' :
                        $('#form-cash-payment').hide();
                        $('#form-bank-account').show();
                        $('#form-credit-card').hide();
                    break;
                    case 'cash' :
                        $('#form-cash-payment').show();
                        $('#form-bank-account').hide();
                        $('#form-credit-card').hide();
                    break;
                }

            }

            function pagoBancario() {
                action="<?=site_url('frontend/accountPayment')?>"
                var api_auth = JSON.parse(localStorage.getItem("api_auth"));
                //// console.log(api_auth);
                renovacion = "<?=site_url('auth/responseRecurrentPayment')?>";
                primera = "<?=site_url('auth/responseRecurrentPayment')?>";

                // console.log(api_auth);

                if (api_auth.license.plan.id == 1) {
                    confirmation = primera;
                    // console.log('nuevo');
                } else {
                    confirmation = renovacion;
                    // console.log('renovacion');
                }

                licenciaEnviar = licenciasAPagar.replace(/_/g, "-");

                /**
                 * account-name
                    doc-type
                    client-document-number
                    account-type
                    account-number
                    bank-entity
                */

                data = {
                    client: {
                        name: $('#account-name').val(), // vendty
                        last_name: $('#client-last-name').val(), // vendty
                        doc_type: $('#account-doc-type').val(),
                        doc_number: $('#client-document-number').val(),
                        email: $('#account-email').val(), //api_auth.user.email, // vendty
                        address: api_auth.warehouse.address.address
                    },
                    bankAccount: {
                        account_type: $('#account-type').val(), // vendty
                        account_number: $('#account-number').val(), // vendty
                        bank_entity: $('#bank-entity').val(),
                        person_type: $('#person-type').val()
                    },
                    plan: planSeleccionado,
                    user_id: api_auth.user.id,
                    licencia: licenciaEnviar,
                    numeroDeLicencias,
                    confirmation,
                    test: $('#test').val()
                };

                // console.log(data);

                $.ajax({
                    url: action,
                    data:  data, //datos que se envian a traves de ajax
                    type:  'post', //método de envio
                    dataType: "json",
                    success: function(data) {
                    if (data.success==1){
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'La licencia fue pagada exitosamente',
                                showConfirmButton: false,
                                timer:1500
                            });
                        } else {
                            swal({
                                position: 'center',
                                type: 'error',
                                title: 'Hubo un error',
                                showConfirmButton: false,
                                timer:1500
                            });
                        }
                        // console.log(data);
                    },
                    fail: function() {

                    }
                });


            }

            function loadBanks() {

                $.ajax({
                    url: "https://secure.payco.co/restpagos/pse/bancos.json?public_key=2815e60ed8e00cbfc47180d0d37a7a6c",
                    type:  'get', //método de envio
                    dataType: "json",
                    success: function(response) {
                        // console.log(response);
                        if (response.success==1){
                            options = [];
                            response.data.map(item => {
                                options.push("<option value='"+item.bankCode+"'>"+item.bankName+"</option>");
                            })
                            $("#bank-entity").html(options.join(""));
                        } else {
                            swal({
                                position: 'center',
                                type: 'error',
                                title: data.description,
                                showConfirmButton: false,
                                timer:1500
                            });
                            setTimeout(function(){
                                //location.reload(true);
                            }, 1600);
                        }
                    },
                    fail: function($response) {
                        // console.error($response);
                    }
                });
            }

            htmlPagoCorrecto = '<div class="flex-padre col-md-12">'+
                    '<div class="flex-hijo col-md-12"><p class="col-md-4 col-md-offset-2 text-right">Referencia de pago</p><p class="col-md-4  text-left">{ref_epayco}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Descripción</p><p class="col-md-4 text-left">{description}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Valor</p><p class="col-md-4 text-left">{value}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Estado del pago</p><p class="col-md-4  text-left">{state}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Nombre</p><p class="col-md-4  text-left">{name}</p>'+
                    '</div><div class="flex-hijo"><p class="col-md-4 col-md-offset-2 text-right">Documento</p><p class="col-md-4  text-left">{identification}</p>'+
                    '</div></div>';
            htmlPagoIncorrecto = '<div class="flex-padre col-md-12">'+
                    '<div class="flex-hijo col-md-12"><p class="col-md-4 col-md-offset-2 text-right">Mensaje</p><p class="col-md-4  text-left">{response}</p>'+
                    '</div></div>';

            function mostrarConfirmacion(data) {
                $("#title-response").text(data.title);
                $("#description-response").text(data.description);
                if (data.success) {
                    $("#image-result").html('<div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;"><div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span><span class="swal2-success-line-long"></span><div class="swal2-success-ring"></div><div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div></div>');
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{ref_epayco}',data.data.data.data.ref_payco);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{description}',data.data.data.data.descripcion);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{value}',data.data.data.data.valor);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{state}',data.data.data.data.respuesta);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{name}',data.data.data.data.nombres + " " + data.data.data.data.apellidos);
                    htmlPagoCorrecto = htmlPagoCorrecto.replace('{identification}',data.data.data.data.tipo_doc + " " + data.data.data.data.documento);
                    $("#data").html(htmlPagoCorrecto);
                    $("#debit-message").show();
                } else {
                    $("#image-result").html('<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div>');
                    htmlPagoIncorrecto = htmlPagoIncorrecto.replace('{response}', data.message + '<br>' + data.data.data.data.errors);
                    $("#data").html(htmlPagoIncorrecto);
                    $("#debit-message").hide();
                }
                $("#panel-pagos").hide();
                $("#panel-pagos2").hide();
                $("#panel-planes").hide();
                $("#panel-licencias").hide();
                $("#buttons-recurrent-payment").show();
                $("#loading").hide();
                $("#panel-response").show();
            }

            function pagoRecurrente() {
                var idPlanActual = $('#plan_cliente_id').val();
                var action = "<?=site_url('frontend/creditCardPayment')?>";
                var renovacion = "<?=site_url('responseCredit')?>";
                var primera = "<?=site_url('responseCreditDos')?>";
                var api_auth = JSON.parse(localStorage.getItem("api_auth"));

                var totalSUM = 0;
                var referenceCode = 'Licencia Vendty';
                var n = 0;
                extra1="";

                $("tbody tr input:checkbox").each(function () {
                    var getValue = $(this).parent().parent().find("td:eq(6)").html();
                    if ($(this).is(':checked')) {
                        getValue2=$(this).data('id');
                        extra1 +=getValue2+"_";
                        active = true;
                        referenceCode += (n==0) ? $(this).data('id') + "_" + getValue : '-' + $(this).data('id') + "_" + getValue;
                        totalSUM +=Number(getValue);
                        n ++;
                    }
                });

                if (extra1!="") {
                    extra1=extra1.substr(0, extra1.length - 1);
                }

                var amount = totalSUM;
                // Update footer
                $( "#foot-table" ).empty();
                $('#foot-table').append('Total: ' + amount + '$');
                var factura=Math.random()+"1238_"+Math.random();

                dataepa={
                    //Parametros compra (obligatorio)
                    name: "Pago de Licencia",
                    description: referenceCode,
                    invoice: factura,
                    //currency: "cop",
                    currency: currency,
                    amount: amount,
                    extra1: extra1,
                    tax_base: "0",
                    tax: "0",
                    country: "co",
                    lang: "es",
                    external: "true",
                    confirmation: "http://pos.vendty.com/index.php/response",
                    response: "http://pos.vendty.com/index.php/response"
                }
                id_licencia = $('#licenciaId').val();
                if (idPlanActual == 1) {
                    confirmation = primera;
                    referenceCode = referenceCode.split("_")[0] + "-" + planSeleccionado.id;

                    referenceCode = 'Licencia Vendty'+id_licencia + "-" + planSeleccionado.id;
                    dataepa.description = referenceCode;
                    // console.log('nuevo');
                } else {
                    referenceCode = 'Licencia Vendty'+id_licencia + "-" + planSeleccionado.id;
                    confirmation = renovacion;
                    // console.log('renovacion');
                }

                data = {
                    client: {
                        name: $('#card-name').val(), // vendty
                        doc_type: $('#doc-type').val(),
                        doc_number: $('#doc-number').val(),
                        email: api_auth.user.email, // vendty
                        phone: $('#client-phone').val(), // vendty
                        address: api_auth.warehouse.address.address
                    },
                    creditCard: {
                        card_name: $('#client-name').val(), // vendty
                        card_email: api_auth.user.email, // vendty
                        card_number: $('#card-number').val(),
                        card_cvc: $('#card-cvc').val(),
                        card_exp_month: $('#card-exp-month').val(),
                        card_exp_year: $('#card-exp-year').val(),
                        card_type: $('#card-type').val()
                    },
                    plan: planSeleccionado,
                    user_id: api_auth.user.id,
                    licencia: referenceCode,
                    numeroDeLicencias,
                    confirmation,
                    test: $('#test').val()
                };

                // console.log(data);
                $("#buttons-recurrent-payment").hide();
                $("#loading").show();
                $.ajax({
                    url: action,
                    data:  data, //datos que se envian a traves de ajax
                    type:  'post', //método de envio
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        if (data.success == 1 && data.data.data.estado == 'Aceptada'){
                            mostrarConfirmacion({
                                success: true,
                                title:  'La licencia fue pagada exitosamente',
                                description: "En un plazo de 10 minutos su licencia se actualizara con el pago realizado",
                                data
                            })
                            $("#buttons-recurrent-payment").show();
                            $("#loading").hide();
                        } else {
                            mostrarConfirmacion({
                                success: false,
                                title:  'No se ha podido realizar el pago',
                                description: "Por favor vuelve a realizar el pago en unos minutos si el error persiste comunicate con soporte",
                                message: data.data.message,
                                data
                            })
                            $("#buttons-recurrent-payment").show();
                            $("#loading").hide();
                        }
                    },
                    fail: function($response) {
                        // console.error($response);
                    },
                });
            }

            function seleccionarPlan2(e){
                //$('#myModal').modal('show');
                var text = "Usted acaba de seleccionar el plan ";
                var valor = $(e).val();
                var result = valor.split('-');
                var factura=Math.random()+"1238_"+Math.random();
                $("#parrafo").empty();
                $("#tituloPlan").empty();
                $("#parrafo").append('$' + result[1]);
                $("#tituloPlan").append(result[2]);
                $('#divNuevoPlan').show();
                var referenceCode = 'Licencia Vendty';
                var total = result[1];
                referenceCode += $('#licenciaId').val() + "-" + result[0];
                text += result[2] + " el cual tiene un precio de $" + total + ".";
                var extra1= "";

                if (currency=='usd'){
                    extra1=result[3];
                }

                dataepa = {
                    //Parametros compra (obligatorio)
                    name: "Pago de Licencia",
                    description: referenceCode,
                    invoice: factura,
                    currency: currency,
                    amount: total,
                    extra1: extra1,
                    tax_base: "0",
                    tax: "0",
                    country: "co",
                    lang: "es",
                    external: "true",
                    confirmation: "http://pos.vendty.com/index.php/responseDos",
                    response: "http://pos.vendty.com/index.php/responseDos"
                }

                mostrarMetodos();
                //alert("id plan "+result[0]);
                mostrarSeleccionado(result[0]);
                //ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
            }

            function mostrarMetodos() {
                $("#panel-metodos").show();
                $("#panel-planes").hide();
                $("#panel-licencias").hide();
            }

            function mostrarFormCreditCard() {
                var idPlan = $('#plan_cliente_id').val();
                if (idPlan == 1) {
                    mostrarFormCreditCard2(planSeleccionado.id);
                } else {
                    nombrePlan = "";
                    $("tbody tr input:checkbox").each(function () {
                        //var getValue = $(this).parent().parent().find("td:eq(6)").html();
                        if ($(this).is(':checked')) {
                            // console.log($(this).attr('name'));
                            nombrePlan = $(this).attr('name');
                        }
                    });

                    mostrarSeleccionadoName(nombrePlan);
                    $("#panel-metodos").hide();
                    $("#panel-pagos2").show();
                }
            }

            function mostrarFormCreditCard2(idPlan) {
                mostrarSeleccionado(idPlan);
                $("#panel-metodos").hide();
                $("#panel-pagos2").show();
            }

            function nombrePlanCorto(nombre) {
                $plannombre=nombre;
                $findme="BASICO";
                $pos= $plannombre.includes($findme);
                if ($pos !== false) {
                    $plannombre="BÁSICO";
                } else {
                    $findme="PYME";
                    $pos= $plannombre.includes($findme);
                    if ($pos !== false) {
                        $plannombre="PYME";
                    } else {
                        $findme="EMPRESARIAL";
                        $pos= $plannombre.includes($findme);
                        if ($pos !== false) {
                            $plannombre="EMPRESARIAL";
                        } else {
                            $plannombre="BÁSICO";
                        }
                    }
                }
                // console.log($plannombre);
                return $plannombre;
            }

            function mostrarSeleccionadoName(name, total = 0) {

                planes.map((plan) => {
                    if (plan.nombre_plan == name) {
                        planSeleccionado = plan;
                        // console.log('plan', plan);
                        $periodo = plan.nombre_plan.includes("MENSUAL")
                        $('#nombrePlan2').text(nombrePlanCorto(plan.nombre_plan));
                        $('#precioPlan2').html('<span class="dollar">$</span>'+plan.valor_final);
                        $('#periodo').text($periodo ? "MENSUAL" : "ANUAL");
                        $('#cajas2').text(plan.cajas);
                        $('#usuarios2').text(plan.usuarios);
                    }
                })
            }

            function mostrarSeleccionado(iDplan, total = 0) {
                //alert("Plan seleccionado" + iDplan);
                iDplan = parseInt(iDplan);
                planes.map((plan) => {
                    if (plan.id == iDplan) {
                        planSeleccionado = plan;
                        $periodo = plan.nombre_plan.includes("MENSUAL")
                        // console.log('Plan seleccionado',plan);
                        $('#nombrePlan2').text(nombrePlanCorto(plan.nombre_plan));
                        $('#precioPlan2').html('<span class="dollar">$</span>'+ plan.valor_final );
                        $('#periodo').text($periodo ? "MENSUAL" : "ANUAL");
                        $('#cajas2').text(plan.cajas);
                        $('#usuarios2').text(plan.usuarios);
                    }
                })

            }

            function volverPlanes() {
                $('#form-cash-payment').hide();
                $('#form-bank-account').hide();
                //$('#form-credit-card').hide();
                $("#payment-type").val("0");
            }

            //loadBanks();

            function checkTipoPlan(tipo) {
                if (tipo == 1) {
                    $("#seccionMensual").show();
                    $("#seccionAnual").hide();
                } else {
                    $("#seccionMensual").hide();
                    $("#seccionAnual").show();
                }
            }

            $(function () {
                $('#pagoPSE').on('click',function(e){
                    // console.log('pago pse');
                    var idPlanActual = $('#plan_cliente_id').val();
                    if (idPlanActual == 1) {
                        api_auth = JSON.parse(localStorage.getItem('api_auth'));
                        licenciaAlmacen = api_auth.user.db_config.license.idlicencias_empresa;
                        referenceCode = dataepa.description;
                        referenceCode = 'Licencia Vendty' + licenciaAlmacen+ "-" + referenceCode.split('-')[1];
                        dataepa.description = referenceCode;
                        // console.log(dataepa);
                        ePayco.checkout.configure({key: keyepayco2, test: false}).open(dataepa);
                    } else {
                        if (!$.isEmptyObject(dataepa)) {
                            ePayco.checkout.configure({key: keyepayco2, test: false}).open(dataepa);
                        }
                    }
                })

                $('#pagoEfectivo').on('click',function(e){
                    // console.log('pago efectivo');
                    var idPlanActual = $('#plan_cliente_id').val();
                    if (idPlanActual == 1) {
                        api_auth = JSON.parse(localStorage.getItem('api_auth'));
                        licenciaAlmacen = api_auth.user.db_config.license.idlicencias_empresa;
                        referenceCode = dataepa.description;
                        referenceCode = 'Licencia Vendty' + licenciaAlmacen+ "-" + referenceCode.split('-')[1];
                        dataepa.description = referenceCode;
                        // console.log(dataepa);
                        ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
                    } else {
                        if (!$.isEmptyObject(dataepa)) {
                            ePayco.checkout.configure({key: keyepayco, test: false}).open(dataepa);
                        }
                    }
                })
            });
        </script>

        <style>
            .nuevoPago .texto-derecha{
                text-align: right;
            }

            .nuevoPago .row-form {
                padding: 2.5px;
            }

            .nuevoPago input[type='text'],
            .nuevoPago input[type='password'],
            .nuevoPago textarea,
            .nuevoPago select {
                font-size: 12px;
                height: 24px;
            }

        </style>

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet prefetch" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

        <style >
            #loading {
                display: inline-block;
                width: 25px;
                height: 25px;
                border: 3px solid #62cb31;
                border-radius: 50%;
                border-top-color: #AAFD83;
                animation: spin 1s ease-in-out infinite;
                -webkit-animation: spin 1s ease-in-out infinite;
            }

            @keyframes spin {
                to { -webkit-transform: rotate(360deg); }
            }
            @-webkit-keyframes spin {
                to { -webkit-transform: rotate(360deg); }
            }

            .nuevoPago .wrapper {
                position: relative;
                margin-left: auto;
                margin-right: auto;
            }

            .nuevoPago .package {
                box-sizing: border-box;
                width: 250px;
                /* height: 300px; */
                border: 3px solid #e8e8e8;
                border-radius: 7px;
                display: inline-block;
                padding: 20px;
                text-align: center;
                float: left;
                -webkit-transition: margin-top 0.5s linear;
                transition: margin-top 0.5s linear;
                position: relative;
                margin: 10px;
                /*margin-top: 20px;*/
            }

            /*.nuevoPago .package:hover {
                margin-top: -1px;
                -webkit-transition: margin-top 0.3s linear;
                transition: margin-top 0.3s linear;
            }*/

            .nuevoPago .name {
                color: #565656;
                font-weight: 300;
                font-size: 3rem;
                margin-top: -5px;
            }

            .nuevoPago .price {
                margin-top: 7px;
                font-weight: bold;
                font-size: 18px;
            }

            .nuevoPago .price::after {
                content: "";
                font-weight: normal;
            }

            .nuevoPago hr {
                background-color: #dedede;
                border: none;
                height: 1px;
            }

            .nuevoPago .trial {
                font-size: .9rem;
                font-weight: 600;
                padding: 2px 21px 2px 21px;
                color: #62cb31;
                border: 1px solid #e4e4e4;
                display: inline-block;
                border-radius: 15px;
                background-color: white;
                position: relative;
                bottom: -20px;
            }

            .nuevoPago ul {
                list-style: none;
                padding: 0;
                text-align: left;
                margin-top: 29px;
            }

            .nuevoPago li {
                margin-bottom: 15px;
            }

            .nuevoPago .checkIcon {
                font-family: "FontAwesome";
                content: "\f00c";
            }

            .nuevoPago li::before {
                font-family: "FontAwesome";
                content: "\f00c";
                font-size: 1.3rem;
                color: #62cb31;
                margin-right: 3px;
            }

            .nuevoPago .brilliant {
                border-color: #62cb31;
            }
            /* Triangle */

            .nuevoPago .brilliant::before {
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 64px 64px 0 0;
                border-color: #62cb31 transparent transparent transparent;
                position: absolute;
                left: 0;
                top: 0;
                content: "";
            }

            .nuevoPago .brilliant::after {
                font-family: "FontAwesome";
                content: "\f00c";
                color: white;
                position: absolute;
                left: 9px;
                top: 6px;
                text-shadow: 0 0 2px #62cb31;
                font-size: 1.4rem;
            }

            .text-left {
                text-align: left;
            }

            .text-right {
                text-align: right;
            }
            .center-text {
                text-align: center;
            }

            .payment-column {
                padding: 1em;
                display: flex;
                flex-direction: column;
                align-items: center;
                border-style: solid;
                border-width: 3px;
                border-color: #e8e8e8;
                border-radius: 7px;
                /* width: 210px; */
            }

            .payment-column-inner {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 210px;
                justify-content: space-around;
            }

            .payment-row{
                display: flex;
                flex-direction: row;
                justify-content: space-around;
                border-style: solid;
                border-width: 2;
                border-color: transparent;
                flex-wrap: wrap;
            }
        </style>

    </head>
    <body class="dashboard">
        <div class="container">
            <center>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-2 col-xs-3 col-md-offset-5 col-xs-offset-5">
                            <img alt="Vendty" src="<?php echo base_url('uploads/iconos/Restaurant/vendty-logo-blanco-fondo-transparente.svg') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <h4><?php echo $title; ?></h4>
                    </div>
                </div>

                <?php

if (isset($licencias[0]['id_licencia'])) {
    $licencia = $licencias[0]['id_licencia'];
} else {
    if (isset($licencias['id_licencia'])) {
        $licencia = $licencias['id_licencia'];
    } else {
        $licencia = 0;
    }
}

if (isset($licencias[0]['id_plan'])) {
    $id_plan = $licencias[0]['id_plan'];
} else {
    if (isset($licencias['id_plan'])) {
        $id_plan = $licencias['id_plan'];
    } else {
        $id_plan = 0;
    }
}

echo "<input value=$id_plan id='plan_actual' hidden/>";
if ($id_plan != 1) {
    ?>
                        <div class="row">
                            <div class="col-md-12 col-xs-12 text-center">
                                <div class="col-md-6 col-md-offset-3 col-xs-12">
                                    <!--<div>Estimado Usuario </div>
                                    <div><strong>Gracias por utilizar Vendty</strong></div>-->
                                    <div><?php echo $message; ?><strong><br><?php echo $message1; ?></strong> <?php echo $message2; ?></strong></div>
                                    <!--<div><strong>Para resolver inquietudes, contáctenos:</strong></div>
                                    <div><strong><i class="icon glyphicon glyphicon-earphone" aria-hidden="true" style="margin-right:10px;"></i></strong><strong style="font-size:16px"><a href="javascript:void(0)">+(57) 1 792 3225</a></strong></div>-->
                                </div>
                            </div>
                        </div>
                <?php
}
if (($logout) && ($licencia)) //para admin
{
    if ($id_plan != 1) {
        ?>
                        <script>



                        </script>
                        <div class="row recuadro">

                            <div class="col-xs-12" id="licencias-vencidad">
                                <table class="table table-striped table-hover text-center" width="100%">
                                    <thead>
                                        <th class="text-center" width="10%">Empresa</th>
                                        <th class="text-center" width="10%">Fecha Inicio</th>
                                        <th class="text-center" width="10%">Fecha Fin</th>
                                        <th class="text-center" width="10%">Licencia</th>
                                        <th class="text-center" width="5%">Estado</th>
                                        <th class="text-center" width="10%">Almacén</th>
                                        <th class="text-center" width="10%">Valor</th>
                                        <?php if ($config) {?>
                                            <th class="text-center" width="35%">Acciones</th>
                                        <?php }?>
                                    </thead>
                                    <tbody>
                                    <?php
$x = 0;
        foreach ($licencias as $licencia) {
            $x++;
            $estado = $licencia['estado_licencia'];
            if ($estado == 1) {
                $hoy = date('Y-m-d');
                if ($licencia['fecha_vencimiento'] < $hoy) {
                    $estado = "Inactiva";
                } else {
                    $estado = "Activa";
                }
            } else {
                if ($estado == 15) {
                    $estado = "Inactiva";
                } else {
                    $estado = $licencia['descripción'];
                }
            }

            ?>
                                        <tr>
                                            <td><?php echo ucwords($licencia['nombre_empresa']); ?></td>
                                            <td><?=$licencia['fecha_inicio_licencia'];?></td>
                                            <td><?=$licencia['fecha_vencimiento'];?></td>
                                            <td><?=$licencia['nombre_plan'];?></td>
                                            <td><?=$estado;?></td>
                                            <td><?php
$nombre_almacen = "";
            foreach ($almacentodos as $almacen) {
                if ($almacen->id == $licencia['id_almacen']) {
                    echo ucwords($almacen->nombre);
                    $nombre_almacen = $almacen->nombre;
                }
            }
            ?>
                                            </td>
                                            <?php
if ($paisip == "Colombia") {
                $valor_plan = $licencia['valor_plan'];
            } else {
                $valor_plan = $licencia['valor_plan_dolares'];
            }
            ?>
                                            <td>$<?=number_format($valor_plan, 0, ',', ".");?>
                                            </td>

                                            <?php if ($config) {?>

                                                <td> <input id="licenciaId_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['id_licencia']; ?>">
                                                    <input id="nombrePlan_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['nombre_plan']; ?>">
                                                    <input id="totalLicencia_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['valor_plan']; ?>">
                                                    <input id="totalLicencia_paypal_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['valor_plan_dolares']; ?>">
                                                    <input id="almacen_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['id_almacen']; ?>">
                                                    <input id="almacen_nombre_<?php echo $x; ?>"    type="hidden"  value="<?php echo strtolower($nombre_almacen); ?>">

                                                        <button type="submit" id="btn_pagar" class="btn btn-success" onclick="pagarLicencia(<?php echo $x; ?>)">Pagar</button>

                                                    <button type="submit" class="btn btn-default" onclick="cambiarLicencia(<?php echo $x; ?>)">Cambiar Plan</button>
                                                    <?php if (($btnconfig) && ($estado != "Activa")) {?>
                                                    <button type="submit" class="btn btn-default" onclick="desactivarLicencia(<?php echo $x; ?>)">Desactivar Almacén</button>
                                                    <?php }?>
                                                    <script> if (ippais!="Colombia"){ pagarLicenciapaypal('<?php echo $x; ?>'); }</script>
                                                </td>
                                            <?php
}?>
                                        </tr>
                                    <?php
}
        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-xs-12 nuevoPago" id="seleccion-metodo" style="display: none">
                                <div class="col-md-12" >
                                    <div class="center-text">
                                        <h4>Seleccione el metodo de pago</h4>
                                    </div>
                                    <div class="payment-row" style="padding-top: 1em>">
                                        <div class="payment-column">
                                            <div><h4>PSE</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <div>
                                                    <img src="https://registro.pse.com.co/PSEUserRegister/assets/logo-pse.png" />
                                                </div>
                                            </div>
                                            <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" onclick="pagoPSE2()">Pagar</button></div>
                                        </div>
                                        <div class="payment-column">
                                            <div><h4>TARJETA DE CREDITO</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/visa.png">
                                                <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/mastercard.png">
                                                <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/american.png">
                                            </div>
                                            <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" onclick="mostrarPagotarjeta()">Pagar</button></div>
                                        </div>
                                        <div class="payment-column">
                                            <div><h4>EFECTIVO</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <img width="80" src="https://seeklogo.com/images/B/Baloto-logo-7004A6EB29-seeklogo.com.png" />
                                                <img width="80" src="https://www.efecty.com.co:20009//Resource/image/button/1/eb422e66646abee8ef735c684aeca133.png?v=2" />
                                                <img width="80" src="https://secure.epayco.co/img/standard/franquicias/redservi.png" />
                                            </div>
                                            <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" onclick="pagoEfectivo()">Pagar</button></div>
                                        </div>
                                        <div class="payment-column">
                                            <div><h4>PAYPAL</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <div>
                                                    <img src="https://logosmarcas.com/wp-content/uploads/2018/03/PayPal-logo.png" />
                                                </div>
                                            </div>
                                            <div style="height: 3em;"><div style="margin-right: 0px;margin-top: 0.5em;" id="pagar_paypal_nuevo"></div></div>
                                        </div>
                                    </div>
                                    <div style="text-align: center;padding-top: 1em;padding-bottom: 1em;">
                                        <button class="btn btn-default" id="calcelarPago" onclick="cancelarPagosVencidos()">Regresar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 nuevoPago" id="pago-recurrente" style="display: none">
                                <div class="col-md-10 col-md-offset-1" style="padding-bottom: 1em;">
                                    <div class="col-md-4" style="padding-top: 2.5em">
                                        <div class="package brilliant">
                                            <div class="name" id="nombrePlanVencido"></div>
                                            <div class="price"><span id="precioPlanvencido"></span></div>
                                            <div class="trial" id="periodoVencido"></div>
                                            <hr>
                                            <ul>
                                                <li>
                                                    Facturas <strong>Ilimitadas</strong>
                                                </li>
                                                <li>
                                                    <td>Productos e Inventario</td>
                                                </li>
                                                <li>
                                                    <strong id="cajasvencidas"></strong> Caja(s)
                                                </li>
                                                <li>
                                                    <strong id="usuariosvencidos"></strong> Usuario(s)
                                                </li>

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-8" style="padding-top: 2.5em">
                                        <div id="form-credit-card">
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Titular</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-name" name="card-name" required>
                                                </div>
                                            </div>
                                            <!--div class="row-form">
                                                <div class="col-md-5 texto-derecha">Email</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-email" name="card-email" required>
                                                </div>
                                            </div-->
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Tipo de identificación</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <select id="doc-type"  name="doc-type" style="margin-left: 5px;" required>
                                                        <option value="0" selected disabled>Seleccione una opcion</option>
                                                        <option value="CC">CC - Cédula de ciudadanía</option>
                                                        <option value="CE">CE - Cédula de extranjeria</option>
                                                        <option value="NIT">NIT - Identificación tributaria</option>
                                                        <option value="TI">TI - Tarjeta de identidad</option>
                                                        <option value="PPN">PPN - Pasaporte</option>
                                                        <option value="DNI">DNI - Documento nacional de identificación</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Número de documento</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="doc-number" name="doc-number" required>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Teléfono</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="client-phone" value="" placeholder="" name="client-phone" required>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Tipo de tarjeta</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <select id="card-type" name="card-type" style="margin-left: 5px;" required>
                                                        <option value="0" selected disabled>Seleccione una opcion</option >
                                                        <option value="visa">Visa</option>
                                                        <option value="mcard">Master Card</option>
                                                        <option value="aex">American Express</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Número de la tarjeta</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-number" name="card-number" required>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Fecha de expiración</div>
                                                <div class="col-md-2 col-xs-5">
                                                    <select id="card-exp-month" name="card-exp-month" style="margin-left: 5px;" required>
                                                        <option value="01">01</option>
                                                        <option value="02">02</option>
                                                        <option value="03">03</option>
                                                        <option value="04">04</option>
                                                        <option value="05">05</option>
                                                        <option value="06">06</option>
                                                        <option value="07">07</option>
                                                        <option value="08">08</option>
                                                        <option value="09">09</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 col-xs-2">
                                                    /
                                                </div>
                                                <div class="col-md-3 col-xs-5">
                                                    <select id="card-exp-year" name="card-exp-year" style="margin-left: 5px;" required>
                                                        <?php
echo '<option value="' . date('Y') . '" selected>' . date('Y') . '</option>';

        for ($i = date('Y', strtotime('+1 years')); $i <= date('Y', strtotime('+50 years')); $i++) {
            echo '<option value="' . $i . '">' . $i . '</option>';
        }
        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Código de seguridad</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-cvc" name="card-cvc" required>
                                                </div>
                                            </div>
                                            <div style="text-align: center; padding: 1em">
                                                <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/visa.png" />
                                                <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/mastercard.png"  />
                                                <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/american.png"  />
                                            </div>
                                            <div style="padding-left: 5em; padding-right: 5em">
                                                <p style="text-align: justify;font-size: 11px;">
                                                    * Al hacer click en el boton pagar autorizas a Vendty a realizar el cobro automatico de acuerdo al plan que has seleccionado.
                                                </p>
                                            </div>
                                            <div style="text-align: center;" id="buttons-recurrent-payment">
                                                <button onclick="cancelarPagosVencidos()" class="btn">Cancelar</button>
                                                <button onclick="pagoRecurrente2()" class="btn btn-success">Pagar</button>
                                            </div>
                                            <div id="loading" style="display: none"></div>
                                            <div class="col-md-6" style="margin: auto;">
                                                <input id="test" name="test" value="s" type="hidden" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="nuevoPago" id="panel-response2" style="display: none;">
                                <div style="text-align: center; padding: 2em">
                                    <h2 id="title-response2"></h2>
                                    <h4 id="description-response2"></h4>

                                    <div style="padding-left: 5em; padding-right: 5em">
                                        <p style="text-align: center;font-size: 11px; display: none" id="debit-message2">
                                            Recuerda que el cobro se realizara automaticamente en la fecha establecida en el plan
                                        </p>
                                    </div>
                                    <div id="data2"></div>
                                    <div id="image-result2">
                                    </div>
                                    <button class="btn btn-default" onclick="cancelarPagosVencidos()">Regresar</button>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
if (($logout) && ($btnconfig)) {
            if (!$todas_vencidas) {?>
                                            <button class="btn btn-success salir btn-large" onClick="document.location.href = '<?php echo site_url(); ?>/frontend/configuracion';">Ir a Configuraciones</button>
                                        <?php
}
        } else {?>
                                        <button class="btn btn-default salir btn-large" onClick="document.location.href = '<?php echo site_url('auth/logout'); ?>';">Salir</button>
                                    <?php
}
        ?>
                            </div>
                        </div>
                <?php
} else {?>
                        <div class="row">
                            <div class="col-xs-12">
                                <div style="text-align: center; width: 500px">
                                    <div style="font-size:14px; text-align: center;">
                                        <?php if (!isset($sw_prueba)) {?>
                                            <strong>
                                                Selecciona el plan que más se adapte a tu negocio
                                                <!--Te recuerdo que nuestra promoción del 50% del plan Pyme finalizará el día 13 de Abril del 2019. Si deseas adquirir la promoción entonces comunícate con nosotros a los teléfono +(57)318 8018675 - +(57)317 5108254 o escríbenos un correo a <a href="mailto:asesor@vendty.com">asesor@vendty.com</a>
                                                <br>¡Quedan pocos días de Promoción!-->
                                            </strong>
                                        <?php } else {?>
                                            <strong>
                                              <?php echo $message . $message1 . "</br>" . $message2; ?>
                                            </strong>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--muestro los planes que se pueden comprar cuando es gratis-->
                    <div class="recuadro">

                        <div id="panel-planes">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12 btnradio">
                                        <div class="col-md-2 col-md-offset-5">
                                            <span class="letrasradio">Mesual</span>
                                            <label>
                                                <input type="radio" name="tipolicencia"  value="1" onclick="seleccionTipoLicencia(1)" checked="checked" />
                                                <div class="circle">
                                                    <div class="circle--inner circle--inner__1" ></div>
                                                    <div class="circle--inner circle--inner__2" ></div>
                                                    <div class="circle--inner circle--inner__3" ></div>
                                                    <div class="circle--inner circle--inner__4" ></div>
                                                    <div class="circle--inner circle--inner__5" ></div>
                                                    <div class="circle--outer" ></div>

                                                </div>
                                                <svg>
                                                    <defs>
                                                    <filter id="gooey">
                                                        <feGaussianBlur
                                                        in="SourceGraphic"
                                                        result="blur"
                                                        stdDeviation="3"
                                                        />
                                                        <feColorMatrix
                                                        in="blur"
                                                        mode="matrix"
                                                        values="
                                                            1 0 0 0 0
                                                            0 1 0 0 0
                                                            0 0 1 0 0
                                                            0 0 0 18 -7
                                                        "
                                                        result="gooey"
                                                        />
                                                        <feBlend
                                                        in2="gooey"
                                                        in="SourceGraphic"
                                                        result="mix"
                                                        />
                                                    </filter>
                                                    </defs>
                                                </svg>
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                        <span class="letrasradio">Anual (2 Meses gratis)</span>
                                            <label>

                                                <input type="radio" name="tipolicencia"  value="2" onclick="seleccionTipoLicencia(2)" />
                                                <div class="circle">
                                                    <div class="circle--inner circle--inner__1" ></div>
                                                    <div class="circle--inner circle--inner__2" ></div>
                                                    <div class="circle--inner circle--inner__3" ></div>
                                                    <div class="circle--inner circle--inner__4" ></div>
                                                    <div class="circle--inner circle--inner__5" ></div>
                                                    <div class="circle--outer" ></div>
                                                </div>
                                                <svg>
                                                    <defs>
                                                        <filter id="gooey">
                                                            <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="3" />
                                                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7 " result="gooey"  />
                                                            <feBlend in2="gooey" in="SourceGraphic" result="mix"/>
                                                        </filter>
                                                    </defs>
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="seccionMensual">
                                <div class="col-md-12">
                                    <div class="col-md-10 col-md-offset-2 nuevoPago" style="left: -3%;">
                                        <?php
foreach ($planes as $plan) {
        if (((($plan["mostrar"] == 1) || ($plan["mostrar"] == 3)) && $plan["dias_vigencia"] == 30)) {
            $plannombre = $plan["nombre_plan"];
            $findme = "BASICO MENSUAL";
            $pos = strpos($plannombre, $findme);
            if ($pos !== false) {
                $plannombre = "BÁSICO";
            } else {
                $findme = "MENSUAL PYME";
                $pos = strpos($plannombre, $findme);
                if ($pos !== false) {
                    $plannombre = "PYME";
                } else {
                    $findme = "EMPRESARIAL MENSUAL";
                    $pos = strpos($plannombre, $findme);
                    if ($pos !== false) {
                        $plannombre = "EMPRESARIAL";
                    } else {
                        $plannombre = "BÁSICO";
                    }
                }
            }

            ?>
                                        <div class="package <?=$plan["orden_mostrar"] == 2 ? 'brilliant' : ''?>">
                                            <div class="name"><?=$plannombre?></div>
                                            <?php if ($paisip == 'Colombia') {
                $valorpp = $plan["valor_plan"];
            } else {
                $valorpp = $plan["valor_plan_dolares"];
            }?>
                                            <?php $promo = (!empty($plan["promocion"])) ? 'promo' : '';?>
                                            <div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div>
                                            <?php
if (!empty($plan["promocion"])) {?>
                                                <span><b>Antes:</b><div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div></span>
                                            <?php
}
            ?>
                                            <div class="trial">Mensual</div>
                                            <hr>
                                            <ul>
                                                <li>
                                                    Facturas <strong>Ilimitadas</strong>
                                                </li>
                                                <li>
                                                    <td>Productos e Inventario</td>
                                                </li>
                                                <li>
                                                    <strong><?=$plan['cajas']?></strong> Caja(s)
                                                </li>
                                                <li>
                                                    <strong><?=$plan['usuarios']?></strong> Usuario(s)
                                                </li>

                                            </ul>
                                            <button type="button" class="btn btn-success" style="background-color: #31CC33; width: 100px; height: 30px; margin-bottom: 8px"
                                            onclick="seleccionarPlan(this)" value="<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>-<?=$plan['valor_plan_dolares']?>">
                                                <strong>Comprar</strong>
                                            </button>

                                        </div>
                                    <?php
}
    }
        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="seccionAnual" style="display: none">
                                <div class="col-md-12">
                                    <div class="col-md-10 col-md-offset-2 nuevoPago" style="left: -3%;">
                                        <?php
foreach ($planes as $plan) {
            if ((($plan["mostrar"] == 1) || ($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 365)) {
                $plannombre = $plan["nombre_plan"];
                $findme = "BASICO ANUAL";
                $pos = strpos($plannombre, $findme);
                if ($pos !== false) {
                    $plannombre = "BÁSICO";
                } else {
                    $findme = "STANDARD ANUAL";
                    $pos = strpos($plannombre, $findme);
                    if ($pos !== false) {
                        $plannombre = "PYME";
                    } else {
                        $findme = "EMPRESARIAL ANUAL";
                        $pos = strpos($plannombre, $findme);
                        if ($pos !== false) {
                            $plannombre = "EMPRESARIAL";
                        } else {
                            $plannombre = "BÁSICO";
                        }
                    }
                }

                ?>
                                        <div class="package <?=$plan["orden_mostrar"] == 2 ? 'brilliant' : ''?>">
                                            <div class="name"><?=$plannombre?></div>
                                            <?php if ($paisip == 'Colombia') {
                    $valorpp = $plan["valor_plan"];
                } else {
                    $valorpp = $plan["valor_plan_dolares"];
                }?>
                                            <?php $promo = (!empty($plan["promocion"])) ? 'promo' : '';?>
                                            <div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div>
                                            <?php
if (!empty($plan["promocion"])) {?>
                                                <span><b>Antes:</b><div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div></span>
                                            <?php
}
                ?>
                                            <div class="trial">Anual</div>
                                            <hr>
                                            <ul>
                                                <li>
                                                    Facturas <strong>Ilimitadas</strong>
                                                </li>
                                                <li>
                                                    <td>Productos e Inventario</td>
                                                </li>
                                                <li>
                                                    <strong><?=$plan['cajas']?></strong> Caja(s)
                                                </li>
                                                <li>
                                                    <strong><?=$plan['usuarios']?></strong> Usuario(s)
                                                </li>

                                            </ul>
                                            <button type="button" class="btn btn-success" style="background-color: #31CC33; width: 100px; height: 30px; margin-bottom: 8px"
                                            onclick="seleccionarPlan(this)" value="<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>-<?=$plan['valor_plan_dolares']?>">
                                                <strong>Comprar</strong>
                                            </button>
                                        </div>
                                    <?php
}
        }
        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="nuevoPago" id="panel-metodos" style="display: none">
                            <div class="col-md-12" style="background: white;padding-bottom: 20px;">
                                <div class="col-md-12" >
                                    <div class="center-text">
                                        <h4>Seleccione el metodo de pago</h4>
                                    </div>
                                    <div class="payment-row" style="padding-top: 1em>">
                                        <div class="payment-column">
                                            <div><h4>PSE</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <div>
                                                    <img src="https://registro.pse.com.co/PSEUserRegister/assets/logo-pse.png" />
                                                </div>
                                            </div>
                                            <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" id="pagoPSE">Pagar</button></div>
                                        </div>
                                        <div class="payment-column">
                                            <div><h4>TARJETA DE CREDITO</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/visa.png">
                                                <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/mastercard.png">
                                                <img width="80" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/american.png">
                                            </div>
                                            <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" onclick="mostrarFormCreditCard()">Pagar</button></div>
                                        </div>
                                        <div class="payment-column">
                                            <div><h4>EFECTIVO</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <img width="80" src="https://seeklogo.com/images/B/Baloto-logo-7004A6EB29-seeklogo.com.png" />
                                                <img width="80" src="https://www.efecty.com.co:20009//Resource/image/button/1/eb422e66646abee8ef735c684aeca133.png?v=2" />
                                                <img width="80" src="https://secure.epayco.co/img/standard/franquicias/redservi.png" />
                                            </div>
                                            <div style="height: 3em;"><button class="btn btn-default" style="margin-right: 0px;margin-top: 0.5em;" id="pagoEfectivo">Pagar</button></div>
                                        </div>
                                        <div class="payment-column">
                                            <div><h4>PAYPAL</h4></div>
                                            <div class="payment-column-inner" style="padding: 0.1em;height: 15em;">
                                                <div>
                                                    <img src="https://logosmarcas.com/wp-content/uploads/2018/03/PayPal-logo.png" />
                                                </div>
                                            </div>
                                            <div style="height: 3em;"><div style="margin-right: 0px;margin-top: 0.5em;" id="pagoPaypal"></div></div>
                                        </div>
                                    </div>
                                    <div style="text-align: center; padding-top: 2em;">
                                        <button class="btn btn-default" onclick="cancelarPagos()">Regresar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="nuevoPago" id="panel-pagos2" style="display: none">
                            <div class="col-md-12" style="background: white;padding-bottom: 20px">
                                <div class="col-md-10 col-md-offset-1" >
                                    <div class="col-md-4" style="padding-top: 2.5em">
                                        <div class="package brilliant">
                                            <div class="name" id="nombrePlan2"></div>
                                            <div class="price"><span id="precioPlan2"></span></div>
                                            <div class="trial" id="periodo"></div>
                                            <hr>
                                            <ul>
                                                <li>
                                                    Facturas <strong>Ilimitadas</strong>
                                                </li>
                                                <li>
                                                    <td>Productos e Inventario</td>
                                                </li>
                                                <li>
                                                    <strong id="cajas2"></strong> Caja(s)
                                                </li>
                                                <li>
                                                    <strong id="usuarios2"></strong> Usuario(s)
                                                </li>

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-8" style="padding-top: 2.5em">
                                        <div id="form-credit-card">
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Titular</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-name" name="card-name" required>
                                                </div>
                                            </div>
                                            <!--div class="row-form">
                                                <div class="col-md-5 texto-derecha">Email</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-email" name="card-email" required>
                                                </div>
                                            </div-->
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Tipo de identificación</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <select id="doc-type"  name="doc-type" style="margin-left: 5px;" required>
                                                        <option value="0" selected disabled>Seleccione una opcion</option>
                                                        <option value="CC">CC - Cédula de ciudadanía</option>
                                                        <option value="CE">CE - Cédula de extranjeria</option>
                                                        <option value="NIT">NIT - Identificación tributaria</option>
                                                        <option value="TI">TI - Tarjeta de identidad</option>
                                                        <option value="PPN">PPN - Pasaporte</option>
                                                        <option value="DNI">DNI - Documento nacional de identificación</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Número de documento</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="doc-number" name="doc-number" required>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Teléfono</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="client-phone" value="" placeholder="" name="client-phone" required>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Tipo de tarjeta</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <select id="card-type" name="card-type" style="margin-left: 5px;" required>
                                                        <option value="0" selected disabled>Seleccione una opcion</option >
                                                        <option value="visa">Visa</option>
                                                        <option value="mcard">Master Card</option>
                                                        <option value="aex">American Express</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Número de la tarjeta</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-number" name="card-number" required>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Fecha de expiración</div>
                                                <div class="col-md-2 col-xs-5">
                                                    <select id="card-exp-month" name="card-exp-month" style="margin-left: 5px;" required>
                                                        <option value="01">01</option>
                                                        <option value="02">02</option>
                                                        <option value="03">03</option>
                                                        <option value="04">04</option>
                                                        <option value="05">05</option>
                                                        <option value="06">06</option>
                                                        <option value="07">07</option>
                                                        <option value="08">08</option>
                                                        <option value="09">09</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1 col-xs-2">
                                                    /
                                                </div>
                                                <div class="col-md-3 col-xs-5">
                                                    <select id="card-exp-year" name="card-exp-year" style="margin-left: 5px;" required>
                                                        <?php
echo '<option value="' . date('Y') . '" selected>' . date('Y') . '</option>';

        for ($i = date('Y', strtotime('+1 years')); $i <= date('Y', strtotime('+50 years')); $i++) {
            echo '<option value="' . $i . '">' . $i . '</option>';
        }
        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="col-md-5 texto-derecha">Código de seguridad</div>
                                                <div class="col-md-6" style="margin: auto;">
                                                    <input type="text" id="card-cvc" name="card-cvc" required>
                                                </div>
                                            </div>
                                            <div style="text-align: center; padding: 1em">
                                                <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/visa.png" />
                                                <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/mastercard.png"  />
                                                <img width="60" src="https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/logos/franquicia/american.png"  />
                                            </div>
                                            <div style="padding-left: 5em; padding-right: 5em">
                                                <p style="text-align: justify;font-size: 11px;">
                                                    * Al hacer click en el boton pagar autorizas a Vendty a realizar el cobro automatico de acuerdo al plan que has seleccionado.
                                                </p>
                                            </div>
                                            <div style="text-align: center;" id="buttons-recurrent-payment">
                                                <button onclick="cancelarPagos()" class="btn">Cancelar</button>
                                                <button onclick="pagoRecurrente()" class="btn btn-success">Pagar</button>
                                            </div>
                                            <div id="loading" style="display: none"></div>
                                            <div class="col-md-6" style="margin: auto;">
                                                <input id="test" name="test" value="s" type="hidden" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="nuevoPago" id="panel-response" style="display: none; text-align: center;padding: 20px;">
                            <h2 id="title-response"></h2>
                            <h4 id="description-response"></h4>

                            <div style="padding-left: 5em; padding-right: 5em">
                                <p style="text-align: center;font-size: 11px; display: none" id="debit-message">
                                    Recuerda que el cobro se realizara automaticamente en la fecha establecida en el plan
                                </p>
                            </div>
                            <div id="data"></div>
                            <div id="image-result">
                            </div>
                            <button class="btn btn-default" onclick="cancelarPagos()">Regresar</button>
                        </div>

                    </div>
                    <!--fin de los planes a mostrar-->
                        <?php if ($id_plan == 1) {?>
                            <input id="licenciaId" type="hidden"  value="<?php echo $licencia; ?>">
                        <?php }
    } //else plan if ($id_plan != 1)
} //fin if (($logout) && ($licencia)) //para admin
?>
                <div class="row">
                    <div class="col-xs-12">

                        <?php
if ((!$logout) && (!$config)) {
    ?>
                                <button class="btn btn-default salir btn-large" onClick="document.location.href = '<?php echo site_url('auth/logout'); ?>';">Salir</button>
                        <?php
}
?>
                    </div>
                </div>

                <?php if ((($mostrar_salir)) && (isset($sw_prueba) || ($id_plan == 1))) {?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="text-align: center; width: 360px">
                               <button class="btn btn-default salir btn-large" onClick="document.location.href = '<?php echo site_url('auth/logout'); ?>';">Salir</button>
                            </div>
                        </div>
                    </div>
                <?php
}
if ((!$id_plan) && (!$licencia)) {
    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="text-align: center; width: 360px">
                                <div style="font-size:16px;margin-top:30px;margin-left:20px;">Estimado Usuario actualmente no posee Licencias por vencer</div>
                                <button class="btn btn-primary salir btn-large" onClick="document.location.href = '<?php echo site_url(); ?>';">Ir al inicio</button>
                            </div>
                        </div>
                    </div>
                    <?php }?>
            </center>
        </div>

    <!-- Modal Facturación-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Información de Facturación
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        </h4>
                        <span class="modal-title">La informacíon corresponde a tu identificación tributaria en tu país</span>
                    </div>
                    <form class="form-horizontal" id="formu_factura" action="<?=site_url("frontend/configuracion")?>"  method="post" >
                        <div class="modal-body">
                            <div class="alert alert-error hidden" id="mensaje_error">
                            </div>

                                    <input type="hidden" class="form-control" id="idempresa"  name="idempresa" value="<?php echo $info_factura[0]['id_empresa_cliente'] ?>" required>
                                    <input type='hidden' name='form' value='facturacion'>
                                    <input type='hidden' name='epayco' value='1'>

                                <div class="col-sm-12">
                                    <div class="form-group col-sm-6">
                                        <label class="col-sm-5 control-label">Tipo Identificación: <span>*</span></label>
                                        <div class="col-sm-7">
                                            <select name="tipo_identificacion" id="tipo_identificacion" required data-value="<?php echo set_value('tipo_identificacion', $info_factura[0]['tipo_identificacion']) ?>">
                                                <?php

$selected = "";
if ((empty($info_factura[0]['tipo_identificacion']))) {
    echo '<option value="" selected="selected" >Seleccione</option>';
}

foreach ($info_factura_tipo_identificacion as $key => $value) {
    if ($info_factura[0]["tipo_identificacion"] == $key) {
        $selected = "selected";
    } else { $selected = "";}
    echo "<option value='$key' $selected >$value</option>";
}
?>
                                            </select>
                                            <?php echo form_error('tipo_identificacion'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">Nombre Contacto:</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="contacto_factura"  name="contacto_factura" value="<?php echo $info_factura[0]['contacto'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">Número de Identificación:<span>*</span></label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="numero_identificacion"  name="numero_identificacion" value="<?php echo $info_factura[0]['numero_identificacion'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">Teléfono:</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="telefono"  name="telefono_factura" value="<?php echo $info_factura[0]['telefono'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-6">
                                        <label for="recipient-name" class="col-sm-5  control-label">Nombre/Razón Social:<span>*</span></label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="nombreempresa"  name="nombre_empresa" value="<?php echo $info_factura[0]['nombre_empresa'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">País:<span>*</span></label>
                                        <div class="col-sm-7">
                                            <?php
if (empty($info_factura[0]['pais'])) {
    $info_factura_pais[0] = "Seleccione";
    echo custom_form_dropdown('pais_factura', $info_factura_pais, $this->form_validation->set_value('pais_factura', 'Colombia'), "id='pais_factura'  class='select' style='width: 100%'");
} else {
    echo custom_form_dropdown('pais_factura', $info_factura_pais, $this->form_validation->set_value('pais_factura', $info_factura[0]['pais']), "id='pais_factura'  class='select' style='width: 100%'");
}?>
                                            <?php echo form_error('pais'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">Dirección:<span>*</span></label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="direccion_empresa"  name="direccion_factura" value="<?php echo $info_factura[0]['direccion'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">Ciudad/Provincia:<span>*</span></label>
                                        <div class="col-sm-7">
                                            <select name="ciudad_factura" id="ciudad_factura" required data-value="<?php echo set_value('ciudad_factura', $info_factura[0]['ciudad']) ?>">
                                                <?php
if (($info_factura[0]['ciudad'] == 0)) {
    echo '<option value="" selected >Seleccione</option>';
}
echo '<option value="' . $info_factura[0]['ciudad'] . '">' . $info_factura[0]['ciudad'] . '</option>';
?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                     <div class="form-group col-sm-6">
                                        <label for="message-text" class="col-sm-5  control-label">Email:<span>*</span></label>
                                        <div class="col-sm-7">
                                            <input type="email" class="form-control" id="email"  name="correo_factura" value="<?php echo $info_factura[0]['correo'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <?php
if (!empty($info_factura[0]['tipo_identificacion'])) {
    //$nombrebtn="Confirmar y Comprar";
    $nombrebtn = "Guardar";
} else {
    //$nombrebtn="Guardar y Comprar";
    $nombrebtn = "Guardar";
}
?>
                                <input type="submit" class="btn btn-success" value='<?=$nombrebtn?>' />

                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- modal facturación-->
<?php
if ($id_plan != 1) {
    ?>
    <!--modal Nuevo plan-->
        <div class="modal fade" id="myModalplan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2">Seleccione Plan <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding-right: 0.5em;"><span aria-hidden="true">&times;</span></button></h4>
                    </div>
                    <div class="modal-body">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 btnradio">
                                    <div class="col-md-2 col-md-offset-5">
                                     <span class="letrasradio2">Mesual</span>
                                        <label>
                                            <input type="radio" name="tipolicencia" id="1" value="1" onclick="seleccionTipoLicencia(1)" checked="checked"/>
                                            <div class="circle">
                                                <div class="circle--inner circle--inner__1" ></div>
                                                <div class="circle--inner circle--inner__2" ></div>
                                                <div class="circle--inner circle--inner__3" ></div>
                                                <div class="circle--inner circle--inner__4" ></div>
                                                <div class="circle--inner circle--inner__5" ></div>
                                                <div class="circle--outer" ></div>
                                            </div>
                                            <svg>
                                                <defs>
                                                    <filter id="gooey">
                                                        <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="3" />
                                                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7 " result="gooey"  />
                                                        <feBlend in2="gooey" in="SourceGraphic" result="mix"/>
                                                    </filter>
                                                </defs>
                                            </svg>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                    <span class="letrasradio2">Anual (2 Meses gratis)</span>
                                        <label>
                                            <input type="radio" name="tipolicencia" id="2" value="2" onclick="seleccionTipoLicencia(2)" />
                                            <div class="circle">
                                                <div class="circle--inner circle--inner__1" ></div>
                                                <div class="circle--inner circle--inner__2" ></div>
                                                <div class="circle--inner circle--inner__3" ></div>
                                                <div class="circle--inner circle--inner__4" ></div>
                                                <div class="circle--inner circle--inner__5" ></div>
                                                <div class="circle--outer" ></div>
                                            </div>
                                            <svg>
                                                <defs>
                                                    <filter id="gooey">
                                                        <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="3" />
                                                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7 " result="gooey"  />
                                                        <feBlend in2="gooey" in="SourceGraphic" result="mix"/>
                                                    </filter>
                                                </defs>
                                            </svg>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="seccionMensual">
                            <div class="col-md-12 nuevoPago">
                                <div class="col-md-12">
                                    <?php
foreach ($planes as $plan) {
        if ((($plan["mostrar"] == 2) || ($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 30)) {
            $plannombre = $plan["nombre_plan"];
            $findme = "BASICO MENSUAL";
            $pos = strpos($plannombre, $findme);
            if ($pos !== false) {
                $plannombre = "BÁSICO";
            } else {
                $findme = "MENSUAL PYME";
                $pos = strpos($plannombre, $findme);
                if ($pos !== false) {
                    $plannombre = "PYME";
                } else {
                    $findme = "EMPRESARIAL MENSUAL";
                    $pos = strpos($plannombre, $findme);
                    if ($pos !== false) {
                        $plannombre = "EMPRESARIAL";
                    } else {
                        $plannombre = "BÁSICO";
                    }
                }
            }
            ?>
                                    <div class="package <?=$plan["orden_mostrar"] == 2 ? 'brilliant' : ''?>">
                                        <div class="name"><?=$plannombre?></div>
                                        <?php if ($paisip == 'Colombia') {
                $valorpp = $plan["valor_plan"];
            } else {
                $valorpp = $plan["valor_plan_dolares"];
            }?>
                                        <?php $promo = (!empty($plan["promocion"])) ? 'promo' : '';?>
                                        <div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div>
                                        <?php
if (!empty($plan["promocion"])) {?>
                                            <span><b>Antes:</b><div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div></span>
                                        <?php
}
            ?>
                                        <div class="trial">Mensual</div>
                                        <hr>
                                        <ul>
                                            <li>
                                                Facturas <strong>Ilimitadas</strong>
                                            </li>
                                            <li>
                                                <td>Productos e Inventario</td>
                                            </li>
                                            <li>
                                                <strong><?=$plan['cajas']?></strong> Caja(s)
                                            </li>
                                            <li>
                                                <strong><?=$plan['usuarios']?></strong> Usuario(s)
                                            </li>

                                        </ul>
                                        <button type="button" class="btn btn-success planes" data-users="<?=$plan['usuarios']?>" style="background-color: #31CC33; width: 150px; height: 40px; margin-bottom: 8px"
                                        onclick="seleccionarPlanRenovacion(this)" value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                            <!--<strong><font size="3px">Cambiar y Pagar</font></strong>-->
                                            <strong><font size="3px">Cambiar</font></strong>
                                        </button>
                                    </div>


                                <?php
}
    }
    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="seccionAnual" style="display: none">
                            <div class="col-md-12 nuevoPago">
                                <div class="col-md-12">
                                    <?php
foreach ($planes as $plan) {
        if ((($plan["mostrar"] == 2) || ($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 365)) {
            $plannombre = $plan["nombre_plan"];
            $findme = "BASICO ANUAL";
            $pos = strpos($plannombre, $findme);
            if ($pos !== false) {
                $plannombre = "BÁSICO";
            } else {
                $findme = "STANDARD ANUAL";
                $pos = strpos($plannombre, $findme);
                if ($pos !== false) {
                    $plannombre = "PYME";
                } else {
                    $findme = "EMPRESARIAL ANUAL";
                    $pos = strpos($plannombre, $findme);
                    if ($pos !== false) {
                        $plannombre = "EMPRESARIAL";
                    } else {
                        $plannombre = "BÁSICO";
                    }
                }
            }

            ?>
                                    <div class="package <?=$plan["orden_mostrar"] == 2 ? 'brilliant' : ''?>">
                                        <div class="name"><?=$plannombre?></div>
                                        <?php if ($paisip == 'Colombia') {
                $valorpp = $plan["valor_plan"];
            } else {
                $valorpp = $plan["valor_plan_dolares"];
            }?>
                                        <?php $promo = (!empty($plan["promocion"])) ? 'promo' : '';?>
                                        <div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div>
                                        <?php
if (!empty($plan["promocion"])) {?>
                                            <span><b>Antes:</b><div class="price">$<?=number_format($valorpp, 0, ',', ".")?></div></span>
                                        <?php
}
            ?>
                                        <div class="trial">ANUAL</div>
                                        <hr>
                                        <ul>
                                            <li>
                                                Facturas <strong>Ilimitadas</strong>
                                            </li>
                                            <li>
                                                <td>Productos e Inventario</td>
                                            </li>
                                            <li>
                                                <strong><?=$plan['cajas']?></strong> Caja(s)
                                            </li>
                                            <li>
                                                <strong><?=$plan['usuarios']?></strong> Usuario(s)
                                            </li>

                                        </ul>
                                        <button type="button" class="btn btn-success planes" data-users="<?=$plan['usuarios']?>" style="background-color: #31CC33; width: 150px; height: 40px; margin-bottom: 8px"
                                        onclick="seleccionarPlanRenovacion(this)" value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                            <strong><font size="3px">Cambiar y Pagar</font></strong>
                                        </button>
                                    </div>


                                <?php
}
    }
    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <!--fin modal plan-->
<?php
}
?>
    <!--video-->
        <div class="social">
            <ul>
                <!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
                <li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>
            </ul>
        </div>
        <!-- vimeo-->
        <div id="myModalvideovimeo" class="container modal fade" style="margin-left:20%; margin-top:5%;">
                <div style="padding:48.81% 0 0 0;position:relative;">
                    <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266766987?color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                </div>
        </div>
    <!-- fin video -->

    </body>

</html>