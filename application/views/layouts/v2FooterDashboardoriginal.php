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

    

    

    $offline = getOffline(); 

    

    

?>

<script>

  window.offline = "<?php echo $offline; ?>";

</script>




</div>    

</div>

<!-- End Page -->





<!-- Footer -->

<div class="v2h">

    <footer class="site-footer">

        <div class="site-footer-legal">© 2016 <a href="javascript:void(0)"><strong>Vendty</strong></a></div>

        <div class="site-footer-right">

        </div>

    </footer>

</div>











<!-- Core  -->



<script src="<?php echo base_url("public/v2"); ?>/global/vendor/bootstrap/bootstrap.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/asscroll/jquery-asScroll.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/mousewheel/jquery.mousewheel.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/jquery.asScrollable.all.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/ashoverscroll/jquery-asHoverScroll.min.js"></script>



<!-- Plugins -->

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min.js"></script>  

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/screenfull/screenfull.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/jquery-slidePanel.min.js"></script>



<!-- Plugins For This Page -->

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/sparkline/jquery.sparkline.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/gauge-js/gauge.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/d3/d3.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/c3/c3.min.js"></script>  





<!-- Plugins For This Page 2 -->  

<script src="<?php echo base_url('public/export/js/plot/Chart.min.js'); ?>" type="text/javascript"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/chart-js/Chart.HorizontalBar.js"></script>  

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/jquery-placeholder/jquery.placeholder.min.js?v2.0.0"></script>

<script src="<?php echo base_url("public/v2"); ?>/guia/intro.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/jquery-animate-number/jquery.easing.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/jquery-animate-number/jquery.animateNumber.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/jquery-animate-colors/jquery.animate-colors-min.js"></script>





<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/slimScroll/jquery.slimscroll.min.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/jquery-flot/jquery.flot.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/jquery-flot/jquery.flot.resize.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/jquery-flot/jquery.flot.pie.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/flot.curvedlines/curvedLines.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/jquery.flot.spline/index.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/metisMenu/dist/metisMenu.min.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/iCheck/icheck.min.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/peity/jquery.peity.min.js"></script>

<script src="<?php echo base_url("public/template_amazon"); ?>/vendor/sparkline/index.js"></script>







<script src="<?php echo base_url("public/v2"); ?>/global/vendor/matchheight/jquery.matchHeight-min.js"></script>



<!-- Scripts -->

<script src="<?php echo base_url("public/v2"); ?>/global/js/core.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/base/assets/js/site.min.js"></script>



<script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menu.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menubar.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/gridmenu.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/sidebar.min.js"></script>



<script src="<?php echo base_url("public/v2"); ?>/global/js/configs/config-colors.min.js"></script>  



<script src="<?php echo base_url("public/v2"); ?>/global/js/components/asscrollable.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/js/components/animsition.min.js"></script>

<script src="<?php echo base_url("public/v2"); ?>/global/js/components/slidepanel.min.js"></script>





<script src="<?php echo base_url("public/v2"); ?>/global/js/components/matchheight.min.js"></script>



<script src="<?php echo base_url("public/js"); ?>/sweetalert2.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/sweetalert2.min.css">

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





// nunca imprimimos un dia negativo, solo 0

$dias = $data['diasCuentaDisponibles'] <= 0 ? 0 : $data['diasCuentaDisponibles'];

?>



<?php if(  $offline != 'false'){ ?>

<!--  OFFLINE -->

<script src="<?php echo base_url("public/v2"); ?>/appOffline.js?5.02"></script>

<?php } ?>





<?php if(  $estado != '1'){ ?>



    <!--Start of Zopim Live Chat Script-->

    <script type="text/javascript">

        

        

//        alert('Esta en periodo de prueba');

        $('#inicialVideo').modal('show')

        

    </script>

    <!--End of Zopim Live Chat Script-->



<?php } ?>



<!-- <script id="grv-widget">

/*<![CDATA[*/

window.groove = window.groove || {}; groove.widget = function(){ groove._widgetQueue.push(Array.prototype.slice.call(arguments)); }; groove._widgetQueue = [];

groove.widget('setWidgetId', '42b8d5e6-ef16-d5b5-f310-8199e4601887');

!function(g,r,v){var a,n,c=r.createElement("iframe");(c.frameElement||c).style.cssText="width: 0; height: 0; border: 0",c.title="",c.role="presentation",c.src="javascript:false",r.body.appendChild(c);try{a=c.contentWindow.document}catch(i){n=r.domain;var b=["javascript:document.write('<he","ad><scri","pt>document.domain=","\"",n,"\";</scri","pt></he","ad><bo","dy></bo","dy>')"];c.src=b.join(""),a=c.contentWindow.document}var d="https:"==r.location.protocol?"https://":"http://",s="http://groove-widget-production.s3.amazonaws.com".replace("http://",d);c.className="grv-widget-tag",a.open()._l=function(){n&&(this.domain=n);var t=this.createElement("script");t.type="text/javascript",t.charset="utf-8",t.async=!0,t.src=s+"/loader.js",this.body.appendChild(t)};var p=["<bo","dy onload=\"document._l();\">"];a.write(p.join("")),a.close()}(window,document)

/*]]>*/

</script>     -->

    



<!-- <script>

    

    window.intercomSettings = {

        app_id: "ujw0y52x"

    };

    

</script> -->

<!--
<script id="grv-widget">

/*<![CDATA[*/

window.groove = window.groove || {}; groove.widget = function(){ groove._widgetQueue.push(Array.prototype.slice.call(arguments)); }; groove._widgetQueue = [];

groove.widget('setWidgetId', '42b8d5e6-ef16-d5b5-f310-8199e4601887');

!function(g,r,v){var a,n,c=r.createElement("iframe");(c.frameElement||c).style.cssText="width: 0; height: 0; border: 0",c.title="",c.role="presentation",c.src="javascript:false",r.body.appendChild(c);try{a=c.contentWindow.document}catch(i){n=r.domain;var b=["javascript:document.write('<he","ad><scri","pt>document.domain=","\"",n,"\";</scri","pt></he","ad><bo","dy></bo","dy>')"];c.src=b.join(""),a=c.contentWindow.document}var d="https:"==r.location.protocol?"https://":"http://",s="http://groove-widget-production.s3.amazonaws.com".replace("http://",d);c.className="grv-widget-tag",a.open()._l=function(){n&&(this.domain=n);var t=this.createElement("script");t.type="text/javascript",t.charset="utf-8",t.async=!0,t.src=s+"/loader.js",this.body.appendChild(t)};var p=["<bo","dy onload=\"document._l();\">"];a.write(p.join("")),a.close()}(window,document)

/*]]>*/

</script>
-->



<!-- Start of vendty Zendesk Widget script -->

<!--script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var e=this.createElement("script");n&&(this.domain=n),e.id="js-iframe-async",e.src="https://assets.zendesk.com/embeddable_framework/main.js",this.t=+new Date,this.zendeskHost="vendty.zendesk.com",this.zEQueue=a,this.body.appendChild(e)},o.write('<body onload="document._l();">'),o.close()}();

/*]]>*/</script>

<!-- End of vendty Zendesk Widget script -->





<!-- Backup AAutomatico



<script>

  $( document ).ready(function(){ if( offline != 'false' ){ offlineBackup(); } })   

</script>



-->



<?php if( $offline != 'false' ){ ?>



<script>    

// ===================================================

// BUSCADOR

// ===================================================



    $('#buscador').keypress(function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);

        if(keycode == '13'){

            var text = $(this).val();

            

            if(text=="offline"){

                window.location = "<?php echo base_url(); ?>/offline/";

            }

            if(text=="guardar offline"){

                ctrlPressed = false;

                offlineBackup();

            }

            if(text=="borrar offline"){

                ctrlPressed = true;

                offlineBackup();

            }            

            

        }

    });

    



// ===================================================

// OFFLINE SINCRONIZACION

// ===================================================

    

    

    // variable para verificar si hay que hacer una actualizaciondel offline

    

    var makeBackupOffline = "<?php echo $data['offline']; ?>"



    var appOffline;

    appOffline = new classOffline();

    

    

    

    function goBorrarOffline(){

        window.location = "<?php echo site_url(); ?>/frontend/borrarOffline";

    }

    

    function offlineBackup(){

    

        // Si la tecla control esta presionada borramos cache

        // Si no sincronizamos

        if(ctrlPressed){

            

            window.location = "<?php echo site_url(); ?>/frontend/borrarOffline";

            

        }else{

            

            $("#modalSincCont").modal("show");

                            

            $.ajax({

                type: "POST",

                async: false,

                url: '<?php echo site_url("frontend/updateManifest/"); ?>',

                success: function (response) {                

                    console.log(response);

                    

                    //Ya no es necesario si añadimos dinamicamente el src al app

                    //document.getElementById('frameOffline').contentWindow.updateCache();

                    

                    setTimeout(function(){

                        document.getElementById('frameOffline').src = "<?php echo base_url(); ?>uploads/offline/offlineLoaderV2_<?php echo $this->session->userdata('user_id'); ?>.html?<?php echo date("ytdGis"); ?>";

                    },10);

                },

                error: function (xhr, textStatus, errorThrown) {

                    alert(textStatus + " : " + errorThrown);

                }

            });

                

            

            

        }

        

              

    }

    

    

    function sincronizarTablas() {

        

        $("#txtGuardandoSinc").html("Copiando Base de Datos...");

        

        //Cambiamos el estado offline a backup

        

        var data = {

            "tipo" : "guardar",

        }

        

        $.ajax({

            type: "POST",

            url: "<?php echo site_url(); ?>/ventasOffline/setOffline/",

            cache: false,

            data: data,

            dataType: 'json',

            success: function (response) {

                console.log(response);

            },

            error: function (xhr, textStatus, errorThrown) {

                console.log(xhr);

                console.log(textStatus);

                console.log(errorThrown);                

                alert(textStatus + " : " + errorThrown);

            }

        });





        setTimeout(function () {

            appOffline.guardarOffline(                    

                '<?php echo site_url("frontend/getOffline"); ?>',  // BACKUP DATABASE

                '<?php echo site_url("frontend/getOfflineExtraData"); ?>' // GET Extra DATA

            );           

        },1);

    }



    function sinc() {

    

        window.location = "<?php echo site_url(); ?>/frontend/";

    

        //$("#modalSincCont").modal("hide");

        $('#btnGuardarSinc').css("visibility", "hidden");

        /*

        setTimeout(function () {

            $('#txtGuardandoSinc').html("Guardando...");

            $('#modalSinc #cargando').show();

            

        }, 200);

        */

    }



    $('#btnGuardarSinc').click(function () {

        sinc();

    })

    

    

    var ctrlPressed = false;

    // AL PRESIONAR UNA TECLA    

    $(document).keydown(function(e){ if (e.keyCode == 17) ctrlPressed = true;});

    $(document).keyup(function(e){ if (e.keyCode == 17) ctrlPressed = false;});

    

// ===================================================

// ===================================================   



</script>



<?php } ?>



<!-- PRUEBA 7 DÍAS -->

<script>



    var dias = <?php echo $data['diasCuentaDisponibles']; ?>;

    var estado = <?php echo $estado; ?>;



    // Se Valida

    if (dias <= 0 && estado == "2" || dias <= 0 && estado == "3") {

        $("#modalFinPruebaCont").modal("show");

        $(".modal-backdrop").css("opacity", "0.8");

    }



</script>

<!-- PRUEBA 7 DÍAS -->





<?php

$resultPermisos = getPermisos();

$isAdmin = $resultPermisos["admin"];

if ($isAdmin == 't') {

    ?>

    <script>(function () {

            var w = window;

            var ic = w.Intercom;

            if (typeof ic === "function") {

                ic('reattach_activator');

                ic('update', intercomSettings);

            } else {

                var d = document;

                var i = function () {

                    i.c(arguments)

                };

                i.q = [];

                i.c = function (args) {

                    i.q.push(args)

                };

                w.Intercom = i;

                function l() {

                    var s = d.createElement('script');

                    s.type = 'text/javascript';

                    s.async = true;

                    s.src = 'https://widget.intercom.io/widget/ujw0y52x';

                    var x = d.getElementsByTagName('script')[0];

                    x.parentNode.insertBefore(s, x);

                }

                if (w.attachEvent) {

                    w.attachEvent('onload', l);

                } else {

                    w.addEventListener('load', l, false);

                }

            }

        })()</script>

<?php } ?>



<script type="text/javascript">





    function introJsStart() {





        var intro = introJs();



        intro.setOptions({

            steps: [

                {

                    intro: "Bienvenido, a continuación le mostraremos cómo empezar a utilizar <strong>VendTy</strong>"

                },

                {

                    element: document.querySelector('#dataStep1'),

                    intro: "Para navegar por la plataforma podrás utilizar el menú a la izquierda",

                    position: 'auto'

                },

                {

                    element: document.querySelector('#btnAyuda'),

                    intro: "El botón de ayuda te brindará la información necesaria para aprender a usar VendTy",

                    position: 'auto'

                },

                {

                    element: document.querySelector('#btnConfiguracion'),

                    intro: "Con el botón de configuración podrás administrar rápidamente VendTy",

                    position: 'auto'

                },

                {

                    element: document.querySelector('#listaAlmacenes'),

                    intro: "Esta lista te permitirá seleccionar las estadísticas según el tipo de almacén, además podrás consultar las estadísticas de las ventas y los reportes del día",

                    position: 'auto'

                },

                {

                    element: document.querySelector('#btnPeriodo'),

                    intro: "Aquí podrás seleccionar estadísticas semanales, quincenales o mensuales para las gráficas de abajo",

                    position: 'right'

                },

                {

                    intro: " <div id='mensajeFinal'><div><br><strong>¡Crea tu primer producto!</strong><br><br></div><a id='btnIrAProdutos' href='<?php echo site_url(); ?>/productos/nuevo/guia'>Crear Producto</a><br><br></div>"

                }



            ]

        });



        intro.start();

    }







</script>



<script>



//=========================================================================

//=========================================================================

//           AJAX - Guardar formulario inicial del usuario

//=========================================================================

//=========================================================================



    $("#btnGuardarDatosIniciales").click(function () {



        if ($('#completadoNit').val() == "") {

            alert("Por favor digite el nit de la empresa");

            return false;

        }

        if ($('#completadoNombre').val() == "") {

            alert("Por favor digite el nombre de la empresa");

            return false;

        }

        if ($('#inputLogo').val() == "") {

            alert("Por favor seleccione el logotipo de la empresa");

            return false;

        }



        guardarDatosIniciales();

    });







    function guardarDatosIniciales() {



        hideModalWizard();



        $.ajax({

            type: "POST",

            url: '<?php echo site_url("frontend/setNewUserData"); ?>',

            data: new FormData($("#msform")[0]),

            processData: false,

            contentType: false,

            success: function (response) {

                console.log(response);

                setTimeout(function () {



                }, 200);

            },

            error: function (xhr, textStatus, errorThrown) {

                alert(textStatus + " : " + errorThrown);

            }

        });



    }





//=========================================================================

//  IMAGE INPUT FILE PREVIEW

//=========================================================================





    var reloadPreview = function (inputFile) {



        var file = inputFile.files[0];

        var imagefile = file.type;

        var match = ["image/jpeg", "image/png", "image/jpg"];

        if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {

            $('#previewing').attr('src', 'noimage.png');

            return false;

        } else {

            var reader = new FileReader();

            reader.onload = imageIsLoaded;

            reader.readAsDataURL(inputFile.files[0]);

        }

    }



    function imageIsLoaded(e) {

        $('#previewImg').attr('src', e.target.result);

        $('.avatar img').attr('src', "" + e.target.result + "");

    }

    ;







//=========================================================================

//  >>>>>> IMAGE INPUT FILE PREVIEW FIN

//=========================================================================



// ===================================================

// Loader and Wizard ESCONDER

// ===================================================





    // Cerrar alerta de vendty version demo

    function cerrarPrueba() {

        $(".toast-warning").hide("slow");

    }





    function hideModalWizard() {

        $('#modalWizard').modal('hide');

        setTimeout(function () {

            $('#contWizardForm').fadeOut(200, function () {

                $("html,body").css("overflow-y", "auto");

                setTimeout(function () {

//                    introJsStart();// se oculta

                }, 200);

            });

        }, 100);

    }

    ;



    function hideLoader() {

        $('#modalWizard').modal('show');

    }





// ===================================================

// Completado

// ===================================================



    function validarCompletado() {



        var puntuacion = 0;



        puntuacion = $('#completadoNit').val() == "" ? (puntuacion + 0) : (puntuacion + 1);

        puntuacion = $('#completadoNombre').val() == "" ? (puntuacion + 0) : (puntuacion + 1);

        puntuacion = $('#inputLogo').val() == "" ? (puntuacion + 0) : (puntuacion + 1);



        if (puntuacion == 0)

            animarCompletado(25)

        if (puntuacion == 1)

            animarCompletado(50)

        if (puntuacion == 2)

            animarCompletado(75)

        if (puntuacion == 3)

            animarCompletado(97)

    }



    function animarCompletado(number) {



        var actual = $('#completadoTxt').text();



        if (number == 25)

            $("#completadoBar").css("cssText", "width: " + number + "%; background-color: #DE6E6E !important;");

        if (number == 50)

            $("#completadoBar").css("cssText", "width: " + number + "%; background-color: #DEBF6E !important;");

        if (number == 75)

            $("#completadoBar").css("cssText", "width: " + number + "%; background-color: #B4DE6E !important;");

        if (number == 97)

            $("#completadoBar").css("cssText", "width: " + number + "%; background-color: #6E8DDE !important;");



        if (number == 25)

            $("#contCompletadoTxt").css("cssText", " color: #DE6E6E !important;");

        if (number == 50)

            $("#contCompletadoTxt").css("cssText", " color: #DEBF6E !important;");

        if (number == 75)

            $("#contCompletadoTxt").css("cssText", " color: #B4DE6E !important;");

        if (number == 97)

            $("#contCompletadoTxt").css("cssText", " color: #6E8DDE !important;");





        setTimeout(function () {



            $('#completadoTxt').stop().prop('number', actual).animateNumber(

                    {

                        number: number,

                        easing: 'easeInOutExpo', // require jquery.easing



                        // optional custom step function

                        // using here to keep '%' sign after number

                        numberStep: function (now, tween) {

                            var floored_number = Math.floor(now);

                            var target = $(tween.elem);

                            target.text(floored_number);

                        }

                    },

                    1000

                    );



        }, 0);

    }





    $(document).ready(function ($) {

        

    <?php if( $offline != 'false' ){ ?>

            

        // ===================================================

        // Activar Backup Offline

        // ===================================================

        

        // Lee los parametros get ?offline=backup, para empezar a guardar

        if ( makeBackupOffline == "backup" ){

            offlineBackup();

        }

            

        

        // ===================================================

        // FIN Activar Backup Offline

        // ===================================================

    <?php } ?> 





        // ===================================================

        // Btn Logo Trigger

        // ===================================================



        $('#btnLogoInput,.imageDrag').click(function () {

            $('#inputLogo').trigger('click');

        });





        $('#completadoNombre,#completadoNit').keyup(function () {

            validarCompletado();

        });



        $('#inputLogo').change(function () {

            validarCompletado();

            reloadPreview(this);

        });



        // ===================================================

        // Loader and Wizard

        // ===================================================



<?php $estado = $data['estado']; ?>

<?php if ($estado == "3") { ?>



            //hideLoader(); // Cadena de acciones para escoonder el loader

			hideModalWizard()

            //$("#contWizard").hide(); //loader

            //$("#contWizardForm").hide(); // contformulario            

            //$('#modalWizard').modal('show'); // mostrar formulario





<?php } ?>



        // ===================================================

        // >>>>  Loader and Wizard FIN

        // ===================================================



        Site.run();



        // ===================================================

        // Togle Logo

        // ===================================================



        function toggleLogo(target) {

            var close = $(target).hasClass("unfolded");

            if (close) {

                $(".navbar-brand-logo").css("visibility", "hidden");

                $(".navbar-brand-logo2").css("visibility", "visible");

            } else {

                $(".navbar-brand-logo").css("visibility", "visible");

                $(".navbar-brand-logo2").css("visibility", "hidden");

            }

        }

        function toggleLogoInit(target) {

            var close = $(target).hasClass("unfolded");

            if (close) {

                $(".navbar-brand-logo").css("visibility", "visible");

                $(".navbar-brand-logo2").css("visibility", "hidden");

            } else {

                $(".navbar-brand-logo").css("visibility", "hidden");

                $(".navbar-brand-logo2").css("visibility", "visible");

            }

        }

        $('#btnToggleMenu').click(function () {

            toggleLogo(this);

        });





        toggleLogoInit("#btnToggleMenu");



        // ===================================================

        // Togle Logo

        // ===================================================            



        $(".navbar-brand-center").click(function () {

            window.location.replace("<?php echo site_url(); ?>");

        });



        $(".modal").on("click", function (e) {

            hideModalWizard();

        });



        $(".modal-content").on("click", function (e) {

            e.stopPropagation()

        });





    });





</script>



<?php if(  $estado == '1' and (!is_null($data['dias_licencia'])) ) { ?>

    <script type="text/javascript">

        $("#div_mensaje_renovacion").show();

        $("#a_modal_expiracion").on('click',function(){

            $("#modal_renovacion_licencia").modal('show');

        });

    </script>





<?php } ?>


</body>







</html>