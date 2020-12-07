<!--<div class="header-content">
    <h2></i>Estación de Pedidos <span>Clave</span></h2>
    <div class="breadcrumb-wrapper hidden-xs">
        <span class="">Estas aqui:</span>
        <ol class="breadcrumb">
            <li class="active">Estación de Pedido</li>
        </ol>
    </div>
</div>-->

<div class="panelBackgroundContainer overlayPanel" id="panelBackground">

    <!--CONTENEDOR GENERAL-->
    <div>


        <!--LOGO EMPRESA-->
        <div class="row">
            <div class="col-lg-12 col-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center">

                <div class="floatImageBack">
                    <?php if(!empty($data["datos_empresa"]['data']['logotipo'])){ ?>
                        <img src="<?php echo base_url('uploads').'/'.$data["datos_empresa"]['data']['logotipo']; ?>" alt=""/>
                    <?php } else { ?>
                        <div style="font-weight: bold">Mi Empresa</div>
                    <?php }?>
                </div>

            </div>
        </div>

        <!--CALCULADORA-->
        <div class="row">

            <div class="col-lg-12 col-12 col-md-12 col-sm-12 col-xs-12 tableroNumbers">

                <div class="row">
                    <div class="col-lg-12 col-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="error">
                            <?php
                            $message = $this->session->flashdata('message');
                            $message1 = $this->session->flashdata('message1');

                            if(!empty($message)):?>
                                <div class="alertMessages">
                                    <p><?php echo $message;?></p>
                                </div>
                            <?php endif;
                            if(!empty($message1) && $message1 !== 'El código no puede ser blanco, tiene que tener 4 Dígitos'):?>
                                <div class="alertMessages">
                                    <p><?php echo $message1;?></p>
                                </div>
                            <?php endif;
                            if(empty($message1) && empty($message) || $message1 === 'El código no puede ser blanco, tiene que tener 4 Dígitos'):?>
                                <div class="alertMessages">
                                    <p>Ingresa tu código</p>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!--ESCONDER INPUT-->
                <div class="row" style="position: absolute; z-index: -9999; opacity: 0">
                    <div class="col-lg-12 col-12 col-md-12 col-sm-12 col-xs-12">
                        <form id="formclave" method="POST" action="<?php echo site_url() ?>/tomaPedidos/mesero" class="form-inline input-group">
                            <span class="input-group-addon" id="basic-addon1" ><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="codigo" name="codigo" style="height:50px!important; font-size: 20px !important"type="number" class="form-control" placeholder="Digite su código" aria-describedby="basic-addon1" required>
                        </form>
                    </div>
                </div>


                <!--CALCULADORA-->
                <div class="containerRestaurant"> <!--CONTAINER-->

                    <div class="row dotsCode">
                        <div class="col-lg-12 col-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center">
                            <img src="<?php echo $this->session->userdata('new_imagenes')['btn_codigo_off']['original'] ?>" alt="cod_1" id="cod_1"/>
                            <img src="<?php echo $this->session->userdata('new_imagenes')['btn_codigo_off']['original'] ?>" alt="cod_2" id="cod_2"/>
                            <img src="<?php echo $this->session->userdata('new_imagenes')['btn_codigo_off']['original'] ?>" alt="cod_3" id="cod_3"/>
                            <img src="<?php echo $this->session->userdata('new_imagenes')['btn_codigo_off']['original'] ?>" alt="cod_4" id="cod_4"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="1"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_uno']['original'] ?>" alt="1"/></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="2"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_dos']['original'] ?>" alt="2"/></div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="3"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_tres']['original'] ?>" alt="3"/></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="4"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_cuatro']['original'] ?>" alt="4"/></div>
                        </div>
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="5"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_cinco']['original'] ?>" alt="5"/></div>
                        </div>
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="6"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_seis']['original'] ?>" alt="6"/></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="7"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_siete']['original'] ?>" alt="7"/></div>
                        </div>
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="8"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_ocho']['original'] ?>" alt="8"/></div>
                        </div>
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="9"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_nueve']['original'] ?>" alt="9"/></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer borrar" data-id="borrar"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_borrar']['original'] ?>" alt="Borrar"/></div>
                        </div>
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer" data-id="0"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_cero']['original'] ?>" alt="0"/></div>
                        </div>
                        <div class="col-4 col-md-4 col-xs-4 col-sm-4">
                            <div class="botonDesignContainer enviar" data-id="ok"><img src="<?php echo $this->session->userdata('new_imagenes')['btn_entrar']['original'] ?>" alt="OK"/></div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <!--LOGO-->
        <div class="row logoVendtyRest">
            <div class="col-lg-12 col-12 col-md-12 col-sm-12 col-xs-12 imageLogo">
                <img src="<?php echo $this->session->userdata('new_imagenes')['logo_rest_vendty']['original'] ?>" alt="logoVendty" id="logoVendty"/>
            </div>
        </div>

    </div>



    <!--
                    <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">

                        Logo y Nombre empresa
                        <?php
    if((isset($data["datos_empresa"]['data']['nombre']))&&(!empty($data["datos_empresa"]['data']['nombre']))){ ?>
                            <div class="text-center">
                                <h1><b><?php echo $data["datos_empresa"]['data']['nombre']; ?></b></h1>

                            </div>
                        <?php
    }
    if((isset($data["datos_empresa"]['data']['logotipo']))&&(!empty($data["datos_empresa"]['data']['logotipo']))){ ?>
                            <img src="<?php echo base_url('uploads').'/'.$data["datos_empresa"]['data']['logotipo']; ?>" alt=""/>
                        <?php
    }
    ?>
                    </div>-->
</div>

<script>


    var randomInt = Math.floor(Math.random() * 4);

    switch(randomInt) {
        case 0:
            var imageUrl = '<?php echo $this->session->userdata("new_imagenes")["back_uno"]["original"] ?>';
            $('#panelBackground').css('background-image', 'url(' + imageUrl + ')');
            break;
        case 1:
            var imageUrl = '<?php echo $this->session->userdata("new_imagenes")["back_dos"]["original"] ?>';
            $('#panelBackground').css('background-image', 'url(' + imageUrl + ')');
            break;
        case 2:
            var imageUrl = '<?php echo $this->session->userdata("new_imagenes")["back_tres"]["original"] ?>';
            $('#panelBackground').css('background-image', 'url(' + imageUrl + ')');
            break;
        case 3:
            var imageUrl = '<?php echo $this->session->userdata("new_imagenes")["back_cuatro"]["original"] ?>';
            $('#panelBackground').css('background-image', 'url(' + imageUrl + ')');
            break;
    }


    $("#codigo").val('');
    if ($(window).width() > 1000) {
        $("#codigo").focus();
    }

    $( "#codigo" ).keypress(function( e ) {

        debugger;

        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }

        var clave=$("#codigo").val() + e.key;

        if (clave.length > 4){
            return false;
        }

        paintDots(clave.length);

    });

    $(".botonDesignContainer").click(function(e){

        var a = $(this).attr("data-id");
        var tengo=$("#codigo").val();

        if (tengo.length < 4){
            $("#codigo").val(tengo+a);
            var nuevoCodigo = $("#codigo").val();
            paintDots(nuevoCodigo.length);
        }

        if ($(window).width() > 1000){
            $("#codigo").focus();
        }


    });

    $(".borrar").click(function(){
        $("#codigo").val("");
        paintDots(0);

        $(".alertMessages").text('Ingrese aquí tu código');
    });

    $(".enviar").click(function(e){
        clave=$.trim($("#codigo").val());

        if((clave!="")&&(clave.length==4)){
            $("#formclave").submit();
        }else{
            alert("La clave debe tener 4 dígitos");
        }

    });

    function paintDots(longitud) {

        var image_on = '<?php echo $this->session->userdata("new_imagenes")["btn_codigo_on"]["original"] ?>';
        var image_off = '<?php echo $this->session->userdata("new_imagenes")["btn_codigo_off"]["original"] ?>';
        //// pintar dots
        switch(longitud) {
            case 0:
                $('#cod_1').attr('src', image_off);
                $('#cod_2').attr('src', image_off);
                $('#cod_3').attr('src', image_off);
                $('#cod_4').attr('src', image_off);
                break;
            case 1:
                $('#cod_1').attr('src', image_on);
                ///otros
                $('#cod_2').attr('src', image_off);
                $('#cod_3').attr('src', image_off);
                $('#cod_4').attr('src', image_off);
                break;
            case 2:
                $('#cod_1').attr('src', image_on);
                $('#cod_2').attr('src', image_on);
                ///otros
                $('#cod_3').attr('src', image_off);
                $('#cod_4').attr('src', image_off);
                break;
            case 3:
                $('#cod_1').attr('src', image_on);
                $('#cod_2').attr('src', image_on);
                $('#cod_3').attr('src', image_on);
                ///otros
                $('#cod_4').attr('src', image_off);
                break;
            case 4:
                $('#cod_1').attr('src', image_on);
                $('#cod_2').attr('src', image_on);
                $('#cod_3').attr('src', image_on);
                $('#cod_4').attr('src', image_on);
                break;
        }

    }

</script>


<!--mixpanel-->
<script>
        var id='<?php echo $this->session->userdata('user_id') ?>';       
        var email='<?php echo $this->session->userdata('email') ?>';
        var nombre_empresa='<?php echo $this->session->userdata('nombre_empresa') ?>';

        mixpanel.identify(id);   
</script>

<?php 
    if($data['estado']==2){?>
    <script>
               
        mixpanel.track("Estacion Pedidos Prueba", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });  

    </script>    

<?php
    }else{ ?>

     <script>        

         mixpanel.track("Estacion Pedidos", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });   

    </script>        
    
<?php
    }?>
