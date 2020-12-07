<?php 
        $ip="";
        $paisip="Colombia";
        //busco la ip
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }       
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }  
        
        $res = file_get_contents('https://www.iplocate.io/api/lookup/'.$ip);
        $res = json_decode($res);
        $paisip= $res->country;

        if(empty($paisip)){
            $paisip="Colombia";
        }
        $id_db_config = (!empty($this->session->userdata('db_config_id')))?$this->session->userdata('db_config_id'):0;
        if(($id_db_config=='11152') || ($id_db_config=='13606')){
            $paisip="Colombia1";
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
        <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/bootstrap/bootstrap.min.js'></script>
        <script src="<?php echo base_url("public/js"); ?>/sweetalert2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/sweetalert2.min.css">
        
        <script src="<?php echo base_url();?>public/js/video.js"></script>
        <script src="https://www.paypalobjects.com/api/checkout.js"></script>
                 
        <script>
        //epayco
            var dataepa="";            
            var licenciaSeleccionada=0; 
            var ippais='<?php echo $paisip ?>';
            var currency="cop";

            if(ippais!="Colombia"){
                currency='usd';
            }else{
                currency="cop";
            }
            var epaycooption=1;        
            var handlerepa = ePayco.checkout.configure({
                key: 'a9743da1bac57f18aeef6b484a2dec95',
                test: false
            })  
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
                client: {
                //sandbox: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
                //sandbox: 'AcSRYJUbpgZvmkBDQHn7v9WzZtJCQMhUX0RwFKFHNQo-yDxagdwHHlqxSJ1P6LLBtB31h8nSwFa4LJFM',//desarrollo
                //sandbox: 'Aa2EMMw-BiUCVpwm1l28zklLP2IjeZVoOkiN9uo8u4eRYIleS7uWpSre0DL7toqBYrQgBYP4PY2Sixz9',//facilitador
                production: 'AUsz8hM_W5fTD4QmgE3GG9hk5jQ2I9Doc_TNW7pbgb10D1_PLURGjQDTwRSvanqLF6_E7u-9gZHmf97o'//arnulfoospino
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
                        if(status=="approved"){
                            $.ajax({
                                url: "<?php echo site_url("frontend/responsepaypal")?>",                                                   
                                data:  {'data':data,'referencia':referenceCode,'total':total}, //datos que se envian a traves de ajax                                   
                                type:  'post', //método de envio
                                dataType: "json",
                                success: function(data) {                                      
                                    if(data.success==1){
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
                                    }else{
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
            }, '#paypal-button_'+e);
            
        }     

        function pagarLicencia(e){                          
            epaycooption=1; 
            //$('#myModal').modal('show');
            var referenceCode = 'Licencia Vendty' + $('#licenciaId_'+ e).val();
            var totalp = $('#totalLicencia_' + e).val();
            var totald = $('#totalLicencia_paypal_' + e).val();
            var factura=Math.random()+"1238_"+Math.random();
            var extra1= "";
            
            if(currency=='cop'){
                total=totalp;
                extra1=totald;
            }else{
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
            handlerepa.open(dataepa);
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

            if(currency=='usd'){                   
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

            handlerepa.open(dataepa);

        }

        function seleccionarPlanpaypal(e){
            
            //$('#myModal').modal('show');
            //'6-70000-LICENCIA BASICO MENSUAL-1836';
            var text = "Usted acaba de seleccionar el plan ";
            var valor = e;                
            var result = valor.split('-');               
            
            var factura=Math.random()+"1238_"+Math.random();
            /*$("#parrafo").empty();
            $("#tituloPlan").empty();
            $("#parrafo").append('$' + result[1]);
            $("#tituloPlan").append(result[2]);
            $('#divNuevoPlan').show();*/
            var referenceCode = 'Licencia Vendty'+result[3]+"-"+result[0]+"-"+result[4];
            var total = result[1];
            //referenceCode += $('#licenciaId').val() + "-" + result[0];
            //text += result[2] + " el cual tiene un precio de $" + total + ".";
            paypal.Button.render({
                // Configure environment
                //env: 'sandbox',
                env: 'production',
                client: {
                //sandbox: 'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
                //sandbox: 'AcSRYJUbpgZvmkBDQHn7v9WzZtJCQMhUX0RwFKFHNQo-yDxagdwHHlqxSJ1P6LLBtB31h8nSwFa4LJFM',//desarrollo
                //sandbox: 'Aa2EMMw-BiUCVpwm1l28zklLP2IjeZVoOkiN9uo8u4eRYIleS7uWpSre0DL7toqBYrQgBYP4PY2Sixz9',//facilitador
                production: 'AUsz8hM_W5fTD4QmgE3GG9hk5jQ2I9Doc_TNW7pbgb10D1_PLURGjQDTwRSvanqLF6_E7u-9gZHmf97o'//arnulfoospino
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
                        if(status=="approved"){
                            $.ajax({
                                url: "<?php echo site_url("frontend/responseDospaypal")?>",                                                   
                                data:  {'data':data,'referencia':referenceCode,'total':total}, //datos que se envian a traves de ajax                                   
                                type:  'post', //método de envio
                                dataType: "json",
                                success: function(data) {                                      
                                    if(data.success==1){
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
                                    }else{
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
            if(sw==1){
                var ver = "mensual";
                var ocultar = "anual";
                $("#seccionMensual").css("display", "block");
                $("#seccionAnual").css("display", "none");
            }else {
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
                url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",               
                data: {"pais" : pais},
                dataType: "json",
                success: function(data) {
                    $("#ciudad_factura").html('');
                    $.each(data, function(index, element){
                        provincia = "<?php echo set_value('ciudad_factura');?>";                       
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
                url: "<?php echo site_url("almacenes/usuarios_licencia")?>",               
                data: {licencia : licenciaSeleccionada},
                type:  'post',
                dataType: "json",
                success: function(data) {                   
                    if(data.success==1){
                        useralmacen=parseInt(data.cantusuarios);
                        if(data.cantusuarios>0){
                            //desabilito los planes
                            $(".planes").each(function() {
                                users_planes=parseInt($(this).attr('data-users'));                                
                                if(data.cantusuarios>users_planes){
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
                    url: "<?php echo site_url("almacenes/desactivar_almacen")?>",
                    type:  'post', //método de envio
                    dataType: "json",
                    data: {"almacen" : id_almacen, "licencia":licencia},
                    success: function(data) {
                        console.log(data);
                        if(data.success==1){                           
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
                                         window.location = "<?php echo site_url("frontend/index")?>"
                                    }
                                })
                           
                        }else{
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
           
            if((plan != "") &&(licenciaSeleccionada !="")){
            
                $.ajax({
                    url: "<?php echo site_url("almacenes/cambiar_plan_licencia")?>",    
                    type:  'post',
                    dataType: "json",
                    data: { licencia: licenciaSeleccionada, plan: plan },
                    success: function(data) {                        
                        if(data.success==1){  
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
                                                   
                        }else{                            
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
            if(plan!=1){
                $('#myModal').modal('show'); 
            }*/
            $("#pais_factura").change(function () {       
                load_provincias_from_pais($(this).val());
            });

            var pais = $("#pais_factura").val();
            if (pais != "") {                 
                load_provincias_from_pais(pais);
            }else{
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

                if((nombre!="")&&(direccion!="")&&(email!="")&&(tipo_identificacion!="")&&(numero_identificacion!="")&&(pais!="")&&(ciudad!="")){
                    
                    url=$(this).attr('action');
                    $("#mensaje_error").addClass("hidden");
                    $("#mensaje_error").html("");
                    $.ajax({
                        url: url,    
                        type:  'post', 
                        dataType: "json",
                        data:  $("#formu_factura").serialize(),
                        success: function(data) {
                            if(data.success==1){
                                $('#myModal').modal('hide');                                
                                //epayco 
                                /*if(epaycooption==1){
                                    //handlerepa.open(dataepa);
                                }else{
                                    //paypal                                    
                                    //$("#btn_pagar_"+btnlicen).addClass('hidden');                                    
                                    //$("#paypal-button_"+btnlicen).removeClass('hidden');                                    
                                }*/
                                
                                
                            }else{
                                $("#mensaje_error").removeClass("hidden");
                                $("#mensaje_error").html("Todos los campos son obligatorios");
                            }
                        }
                    });

                }else{                    
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

    </head>
    <body class="dashboard">     
        <div class="container">    
            <center>  
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="col-md-2 col-xs-3 col-md-offset-5 col-xs-offset-5">
                            <img alt="Vendty" src="<?php echo base_url('uploads/iconos/Restaurant/vendty-logo-blanco-fondo-transparente.svg')?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <h4><?php echo $title; ?></h4>
                    </div>
                </div>
                
                <?php 
               
                if(isset($licencias[0]['id_licencia'])){
                    $licencia=$licencias[0]['id_licencia'];
                }else{
                    if(isset($licencias['id_licencia'])){
                        $licencia= $licencias['id_licencia'];
                    }
                    else{
                        $licencia=0;
                    }
                }

                if(isset($licencias[0]['id_plan'])){
                    $id_plan=$licencias[0]['id_plan'];
                }
                else{
                    if(isset($licencias['id_plan'])){
                         $id_plan=$licencias['id_plan'];
                    }
                    else{
                        $id_plan=0;
                    }                   
                }  
                    if($id_plan != 1){
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
                if(($logout) && ($licencia)) //para admin
                {
                    if($id_plan != 1){                         
                ?>
                        
                        <div class="row recuadro">
                            <div class="col-xs-12">
                               <h3></h3>
                                <table class="table table-striped table-hover text-center" width="100%">
                                    <thead>
                                        <th class="text-center" width="10%">Empresa</th>
                                        <th class="text-center" width="10%">Fecha Inicio</th>
                                        <th class="text-center" width="10%">Fecha Fin</th>
                                        <th class="text-center" width="10%">Licencia</th>
                                        <th class="text-center" width="5%">Estado</th>
                                        <th class="text-center" width="10%">Almacén</th>
                                        <th class="text-center" width="10%">Valor</th>                                
                                        <?php if($config){ ?>                               
                                            <th class="text-center" width="35%">Acciones</th>
                                        <?php } ?>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $x = 0;
                                        foreach ($licencias as $licencia) { 
                                            $x++;
                                            $estado=$licencia['estado_licencia'];                                            
                                            if($estado==1){ 
                                                $hoy=date('Y-m-d');
                                                if($licencia['fecha_vencimiento'] < $hoy){
                                                    $estado="Inactiva";
                                                }else{
                                                    $estado="Activa";
                                                }   
                                            }else
                                            {
                                                if ($estado==15){
                                                    $estado="Inactiva";
                                                }
                                                else{
                                                    $estado=$licencia['descripcion'];
                                                }    
                                            }                                    

                                            ?>
                                        <tr>
                                            <td><?php echo ucwords($licencia['nombre_empresa']);?></td>
                                            <td><?= $licencia['fecha_inicio_licencia'];?></td>
                                            <td><?= $licencia['fecha_vencimiento'];?></td>
                                            <td><?= $licencia['nombre_plan'];?></td>
                                            <td><?= $estado; ?></td>
                                            <td><?php 
                                                $nombre_almacen=""; 
                                                foreach($almacentodos as $almacen){
                                                    if($almacen->id == $licencia['id_almacen']){
                                                        echo ucwords($almacen->nombre);
                                                        $nombre_almacen=$almacen->nombre;
                                                    }                                            
                                                }
                                            ?>
                                            </td>
                                            <?php 
                                                if($paisip=="Colombia"){ 
                                                    $valor_plan=$licencia['valor_plan'];
                                                }else{
                                                    $valor_plan=$licencia['valor_plan_dolares'];
                                                }
                                            ?> 
                                            <td>$<?= number_format ($valor_plan, 0,',',"."); ?>
                                            </td>   
                                            
                                            <?php if($config){?>                                      
                                                
                                                <td> <input id="licenciaId_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['id_licencia']; ?>">
                                                    <input id="totalLicencia_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['valor_plan']; ?>">                                       
                                                    <input id="totalLicencia_paypal_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['valor_plan_dolares']; ?>">                                       
                                                    <input id="almacen_<?php echo $x; ?>"    type="hidden"  value="<?php echo $licencia['id_almacen']; ?>">                                       
                                                    <input id="almacen_nombre_<?php echo $x; ?>"    type="hidden"  value="<?php echo strtolower($nombre_almacen); ?>"> 
                                                    <?php if($paisip=="Colombia"){ ?>               
                                                        <button type="submit" id="btn_pagar" class="btn btn-success" onclick="pagarLicencia(<?php echo $x; ?>)">Pagar</button>
                                                    <?php }else{                                                            
                                                                ?>    
                                                            <div id="paypal-button_<?php echo $x; ?>" ></div>                                                            
                                                            <!--<button type="submit" id="btn_pagar_<?php echo $x; ?>" class="btn btn-success" onclick="pagarLicenciapaypal(<?php echo $x; ?>)">Datos de Factura</button>-->
                                                            <!--<button type="submit" id="btn_pagar" class="btn btn-success" onclick="pagarLicencia(<?php echo $x; ?>)">Pagar Safecty</button>-->
                                                            <input type=image src="<?php echo base_url("uploads/inicio/safetyfinal.png") ?>" width="100" height="30" onclick="pagarLicencia(<?php echo $x; ?>)">
                                                    <?php } ?>
                                                    <button type="submit" class="btn btn-default" onclick="cambiarLicencia(<?php echo $x; ?>)">Cambiar Plan</button>                                            
                                                    <?php if(($btnconfig) && ($estado !="Activa")){ ?>
                                                    <button type="submit" class="btn btn-default" onclick="desactivarLicencia(<?php echo $x; ?>)">Desactivar Almacén</button>                                            
                                                    <?php }?>
                                                    <script> if(ippais!="Colombia"){ pagarLicenciapaypal('<?php echo $x; ?>'); }</script>
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
                        </div>                        
                        <div class="row">
                            <div class="col-xs-12">                           
                                <?php
                                    if(($logout) && ($btnconfig)){
                                        if(!$todas_vencidas){ ?>
                                            <button class="btn btn-success salir btn-large" onClick="document.location.href = '<?php echo site_url();?>/frontend/configuracion';">Ir a Configuraciones</button>
                                        <?php  
                                        }
                                    }else{  ?>
                                        <button class="btn btn-default salir btn-large" onClick="document.location.href = '<?php echo site_url('auth/logout');?>';">Salir</button>
                                    <?php
                                        }
                                    ?>                        
                            </div>
                        </div>                    
                <?php    
                    }
                    else 
                    {  ?>                     
                        <div class="row">
                            <div class="col-xs-12">
                                <div style="text-align: center; width: 500px">
                                    <div style="font-size:14px; text-align: center;">
                                        <?php if (!isset($sw_prueba)){ ?>
                                            <strong>
                                                Selecciona el plan que más se adapte a tu negocio
                                                <!--Te recuerdo que nuestra promoción del 50% del plan Pyme finalizará el día 13 de Abril del 2019. Si deseas adquirir la promoción entonces comunícate con nosotros a los teléfono +(57)318 8018675 - +(57)317 5108254 o escríbenos un correo a <a href="mailto:asesor@vendty.com">asesor@vendty.com</a>
                                                <br>¡Quedan pocos días de Promoción!-->
                                            </strong>
                                        <?php }else { ?>
                                            <strong>
                                                <?php echo $message . $message1 . "</br>" . $message2; ?>                                    
                                            </strong>
                                        <?php } ?>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!--muestro los planes que se pueden comprar cuando es gratis-->
                    <div class="recuadro">
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
                                <div class="col-md-8 col-md-offset-2">
                                    <?php  
                                        foreach($planes as $plan){ 
                                        if(((($plan["mostrar"] == 1)||($plan["mostrar"] == 3)) && $plan["dias_vigencia"] == 30)){
                                            $plannombre=$plan["nombre_plan"]; 
                                            $findme="BASICO MENSUAL";
                                            $pos=strpos($plannombre, $findme);
                                            if ($pos !== false) {
                                                $plannombre="BÁSICO";
                                            }else{
                                                $findme="MENSUAL PYME";
                                                $pos=strpos($plannombre, $findme);
                                                if ($pos !== false) {
                                                    $plannombre="PYME";                                                                       
                                                }else{
                                                    $findme="EMPRESARIAL MENSUAL";
                                                    $pos=strpos($plannombre, $findme);
                                                    if ($pos !== false) {
                                                        $plannombre="EMPRESARIAL";
                                                    }
                                                    else{
                                                        $plannombre="BÁSICO";                                                                     
                                                    }
                                                }
                                            }  
                                        
                                    ?>
                                    <div class="col-md-4">                                        
                                        <table class="table gratis">
                                            <thead>
                                                <tr class="bg-titulo">
                                                    <th><h6><?=$plannombre?></h6></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <?php if($paisip=='Colombia'){
                                                        $valorpp=$plan["valor_plan"];
                                                    }else{
                                                        $valorpp=$plan["valor_plan_dolares"];
                                                    } ?>
                                                <tr class="preciop">                                                
                                                    <td>
                                                    <?php $promo=(!empty($plan["promocion"]))? 'promo':''; ?>
                                                        <h2 class="<?= $promo ?>" >$<?= number_format ($valorpp, 0,',',".")?></h2>
                                                        <?php 
                                                            if(!empty($plan["promocion"])){ ?> 
                                                            <span><b>Antes:</b><strike>$<?= number_format ($plan["promocion"], 0,',',".") ?></strike></span> 
                                                            <?php
                                                            }
                                                            ?>                                                        
                                                    </td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Facturas Ilimitadas</td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Productos e Inventario</td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Gastos</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['cajas'] ?> Caja(s)</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['usuarios'] ?> Usuario(s)</td>
                                                </tr>  
                                                <tr class="tr-padding-cero">
                                                    <td>
                                                    <?php if($paisip=='Colombia'){ ?>
                                                        <button type="button" class="btn btn-success" style="background-color: #31CC33; width: 100px; height: 30px; margin-bottom: 8px" 
                                                        onclick="seleccionarPlan(this)" value="<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>">
                                                            <strong>Comprar</strong>
                                                        </button>
                                                    <?php }else { ?>
                                                            <div id="paypal-button_p<?=$plan['id']?>"></div>
                                                            <input type=image src="<?php echo base_url("uploads/inicio/safetyfinal.png") ?>" width="100" height="30" onclick="seleccionarPlan(this)" value="<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>-<?=$plan["valor_plan"] ?>">
                                                    <?php } ?>
                                                    <script> if(ippais!="Colombia"){ seleccionarPlanpaypal("<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>-<?=$licencia?>-<?=$plan['valor_plan']?>"); }</script>
                                                    </td>
                                                </tr>
                                            </tbody>                                
                                        </table>
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
                                <div class="col-md-8 col-md-offset-2">
                                    <?php  
                                        foreach($planes as $plan){ 
                                        if((($plan["mostrar"] == 1)||($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 365)){ 
                                            $plannombre=$plan["nombre_plan"]; 
                                            $findme="BASICO ANUAL";
                                            $pos=strpos($plannombre, $findme);
                                            if ($pos !== false) {
                                                $plannombre="BÁSICO";
                                            }else{
                                                $findme="STANDARD ANUAL";
                                                $pos=strpos($plannombre, $findme);
                                                if ($pos !== false) {
                                                    $plannombre="PYME";                                                                       
                                                }else{
                                                    $findme="EMPRESARIAL ANUAL";
                                                    $pos=strpos($plannombre, $findme);
                                                    if ($pos !== false) {
                                                        $plannombre="EMPRESARIAL";
                                                    }
                                                    else{
                                                        $plannombre="BÁSICO";                                                                     
                                                    }
                                                }
                                            }  
                                        
                                    ?>
                                    <div class="col-md-4">                                        
                                        <table class="table gratis">
                                            <thead>
                                                <tr class="bg-titulo">
                                                    <th><h6><?=$plannombre?></h6></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    <?php if($paisip=='Colombia'){
                                                        $valorpp=$plan["valor_plan"];
                                                    }else{
                                                        $valorpp=$plan["valor_plan_dolares"];
                                                    } ?>
                                                <tr class="preciop">
                                                    <td>
                                                        <h2>$<?= number_format ($valorpp, 0,',',".")?></h2>                                                        
                                                    </td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Facturas Ilimitadas</td>
                                                </tr>
                                               
                                                <tr class="tr-padding-cero">
                                                    <td>Productos e Inventario</td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Gastos</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['cajas'] ?> Caja(s)</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['usuarios'] ?> Usuario(s)</td>
                                                </tr>  
                                                <tr class="tr-padding-cero">
                                                    <td>
                                                    <?php if($paisip=='Colombia'){ ?>
                                                        <button type="button" class="btn btn-success" style="background-color: #31CC33; width: 100px; height: 30px; margin-bottom: 8px" 
                                                        onclick="seleccionarPlan(this)" value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                                            <strong>Comprar</strong>
                                                        </button>
                                                    <?php }else{ ?>
                                                        <div id="paypal-button_p<?=$plan['id']?>"></div>
                                                        <input type=image src="<?php echo base_url("uploads/inicio/safetyfinal.png") ?>" width="100" height="30" onclick="seleccionarPlan(this)" value="<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>-<?=$plan["valor_plan"] ?>">
                                                    <?php } ?>
                                                    <script> if(ippais!="Colombia"){ seleccionarPlanpaypal("<?=$plan['id']?>-<?=$valorpp?>-<?=$plan['descripcion']?>-<?=$licencia?>-<?=$plan['valor_plan']?>"); } </script>
                                                    </td>
                                                </tr>
                                            </tbody>                                
                                        </table>
                                    </div> 
                                <?php 
                                    }
                                }
                                ?>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <!--fin de los planes a mostrar-->
                        <?php if ($id_plan == 1) { ?>             
                            <input id="licenciaId" type="hidden"  value="<?php echo $licencia; ?>">
                        <?php } 
                    }//else plan if($id_plan != 1)
                }//fin if(($logout) && ($licencia)) //para admin
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        
                        <?php
                            if((!$logout) && (!$config)){
                        ?>
                                <button class="btn btn-default salir btn-large" onClick="document.location.href = '<?php echo site_url('auth/logout');?>';">Salir</button>                                  
                        <?php    
                            }
                        ?>                        
                    </div>
                </div>
                
                <?php if ((($mostrar_salir)) && (isset($sw_prueba) || ($id_plan == 1))){ ?>                    
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="text-align: center; width: 360px">
                               <button class="btn btn-default salir btn-large" onClick="document.location.href = '<?php echo site_url('auth/logout');?>';">Salir</button>
                            </div>
                        </div>
                    </div>
                <?php 
                    } 
                    if((!$id_plan) && (!$licencia)){
                ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div style="text-align: center; width: 360px">        
                                <div style="font-size:16px;margin-top:30px;margin-left:20px;">Estimado Usuario actualmente no posee Licencias por vencer</div>	
                                <button class="btn btn-primary salir btn-large" onClick="document.location.href = '<?php echo site_url();?>';">Ir al inicio</button>  
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
                    <form class="form-horizontal" id="formu_factura" action="<?= site_url("frontend/configuracion") ?>"  method="post" >                        
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
                                                  
                                                    $selected="";                                       
                                                    if((empty($info_factura[0]['tipo_identificacion']))){
                                                        echo '<option value="" selected="selected" >Seleccione</option>';                                                        
                                                    }

                                                    foreach ($info_factura_tipo_identificacion as $key => $value) {
                                                        if($info_factura[0]["tipo_identificacion"] == $key)
                                                        {   
                                                            $selected="selected";
                                                        }else{ $selected="";  }
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
                                            if(empty($info_factura[0]['pais'])){                                       
                                                    $info_factura_pais[0]="Seleccione";                                                      
                                                    echo custom_form_dropdown('pais_factura', $info_factura_pais, $this->form_validation->set_value('pais_factura', 'Colombia'), "id='pais_factura'  class='select' style='width: 100%'");
                                                }else{                                             
                                                    echo custom_form_dropdown('pais_factura', $info_factura_pais, $this->form_validation->set_value('pais_factura', $info_factura[0]['pais']), "id='pais_factura'  class='select' style='width: 100%'"); 
                                                } ?>
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
                                                    if(($info_factura[0]['ciudad']==0)){
                                                        echo '<option value="" selected >Seleccione</option>';
                                                    }                                             
                                                    echo '<option value="'.$info_factura[0]['ciudad'].'">'.$info_factura[0]['ciudad'].'</option>';                                                    
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
                                if(!empty($info_factura[0]['tipo_identificacion'])){       
                                    //$nombrebtn="Confirmar y Comprar";   
                                    $nombrebtn="Guardar";   
                                }else{
                                    //$nombrebtn="Guardar y Comprar";  
                                    $nombrebtn="Guardar";  
                                }
                            ?>
                                <input type="submit" class="btn btn-success" value='<?= $nombrebtn ?>' />
                            
                        </div> 
                    </form>                    
                </div>
            </div>
        </div>
    <!-- modal facturación-->
<?php 
if($id_plan != 1){ 
?>
    <!--modal Nuevo plan-->    
        <div class="modal fade" id="myModalplan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2">Seleccione Plan <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>                        
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
                            <div class="col-md-12">
                                <div class="col-md-10 col-md-offset-1">
                                    <?php  
                                        foreach($planes as $plan){ 
                                        if((($plan["mostrar"] == 2) ||($plan["mostrar"] == 3)) && ($plan["dias_vigencia"] == 30)){  
                                            $plannombre=$plan["nombre_plan"]; 
                                            $findme="BASICO MENSUAL";
                                            $pos=strpos($plannombre, $findme);
                                            if ($pos !== false) {
                                                $plannombre="BÁSICO";
                                            }else{
                                                $findme="MENSUAL PYME";
                                                $pos=strpos($plannombre, $findme);
                                                if ($pos !== false) {
                                                    $plannombre="PYME";                                                                       
                                                }else{
                                                    $findme="EMPRESARIAL MENSUAL";
                                                    $pos=strpos($plannombre, $findme);
                                                    if ($pos !== false) {
                                                        $plannombre="EMPRESARIAL";
                                                    }
                                                    else{
                                                        $plannombre="BÁSICO";                                                                     
                                                    }
                                                }
                                            }                                          
                                    ?>
                                    <div class="col-md-4">                                        
                                        <table class="table gratis">
                                            <thead>
                                                <tr class="bg-titulo">
                                                    <th><h6><?=$plannombre?></h6></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <tr class="preciop">                                                
                                                    <td>
                                                    <?php $promo=(!empty($plan["promocion"]))? 'promo':''; ?>
                                                        <h2 class="<?= $promo ?>" >$<?= number_format ($plan["valor_plan"], 0,',',".")?></h2>
                                                        <?php 
                                                            if(!empty($plan["promocion"])){ ?> 
                                                            <span><b>Antes:</b><strike>$<?= number_format ($plan["promocion"], 0,',',".") ?></strike></span> 
                                                            <?php
                                                            }
                                                            ?>                                                        
                                                    </td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Facturas Ilimitadas</td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Productos e Inventario</td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Gastos</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['cajas'] ?> Caja(s)</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['usuarios'] ?> Usuario(s)</td>
                                                </tr>  
                                                <tr class="tr-padding-cero">
                                                    <td>
                                                        <button type="button" class="btn btn-success planes" data-users="<?=$plan['usuarios']?>" style="background-color: #31CC33; width: 150px; height: 40px; margin-bottom: 8px" 
                                                        onclick="seleccionarPlanRenovacion(this)" value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                                            <!--<strong><font size="3px">Cambiar y Pagar</font></strong>-->
                                                            <strong><font size="3px">Cambiar</font></strong>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>                                
                                        </table>
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
                                <div class="col-md-10 col-md-offset-1">
                                    <?php  
                                        foreach($planes as $plan){ 
                                        if((($plan["mostrar"] == 2) || ($plan["mostrar"] == 3))  && ($plan["dias_vigencia"] == 365)){     
                                            $plannombre=$plan["nombre_plan"]; 
                                            $findme="BASICO ANUAL";
                                            $pos=strpos($plannombre, $findme);
                                            if ($pos !== false) {
                                                $plannombre="BÁSICO";
                                            }else{
                                                $findme="STANDARD ANUAL";
                                                $pos=strpos($plannombre, $findme);
                                                if ($pos !== false) {
                                                    $plannombre="PYME";                                                                       
                                                }else{
                                                    $findme="EMPRESARIAL ANUAL";
                                                    $pos=strpos($plannombre, $findme);
                                                    if ($pos !== false) {
                                                        $plannombre="EMPRESARIAL";
                                                    }
                                                    else{
                                                        $plannombre="BÁSICO";                                                                     
                                                    }
                                                }
                                            } 
                                        
                                    ?>
                                    <div class="col-md-4">                                        
                                        <table class="table gratis">
                                            <thead>
                                                <tr class="bg-titulo">
                                                    <th><h6><?=$plannombre?></h6></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="preciop">
                                                    <td>
                                                        <h2>$<?= number_format ($plan["valor_plan"], 0,',',".")?></h2>        
                                                    </td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Facturas Ilimitadas</td>
                                                </tr>
                                               
                                                <tr class="tr-padding-cero">
                                                    <td>Productos e Inventario</td>
                                                </tr>
                                                <tr class="tr-padding-cero">
                                                    <td>Gastos</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['cajas'] ?> Caja(s)</td>
                                                </tr>
                                                <tr class='tr-padding-cero'>
                                                    <td><?= $plan['usuarios'] ?> Usuario(s)</td>
                                                </tr>  
                                                <tr class="tr-padding-cero">
                                                    <td>
                                                       <button type="button" class="btn btn-success planes" data-users="<?=$plan['usuarios']?>" style="background-color: #31CC33; width: 150px; height: 40px; margin-bottom: 8px" 
                                                        onclick="seleccionarPlanRenovacion(this)" value="<?=$plan['id']?>-<?=$plan['valor_plan']?>-<?=$plan['descripcion']?>">
                                                            <strong><font size="3px">Cambiar y Pagar</font></strong>
                                                        </button>
                                                </tr>
                                            </tbody>                                
                                        </table>
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