



                            </div>

                            </div>



                    

                    

<script type="text/javascript" src="<?php echo base_url(); ?>public/js/ventasOffline.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>public/fancybox/jquery.fancybox.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>public/js/plugins/multiselect/jquery.multi-select.js"></script>
<script>
        $(document).ready(function(){
            $.ajax({ 
                'url': '<?php echo site_url("auth/check_tester")?>',
                data: {},
                type: "POST",
                async: false,
                success: function(data){

                      if ( data != '' ) {

                        if ( data == 'PRUEBAS' ) {

                            $('body').append($('<div class="alert alert-success" style="position: absolute; z-index: 1; width: 90%; left: 5%; bottom: 0;"> <h1><strong>PRUEBAS!</strong></h1> </div>').click(function(e){ $(this).fadeOut(); }));

                        };

                      }



                }



            });



});

</script>



                    

                    <!--  END OLD V1 --> 



                    

                </div>

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







        <!--////////////////////////////////////////////////////////////-->

        <!--  OFFLINE -->

        <!--////////////////////////////////////////////////////////////-->



				<div id="dialog-internet"  title="Conexion a Internet">

							<div class="">

								<h5>Ya dispone de conexión a Internet<br><br>Por favor <strong>sincronice</strong>.</h5>

							</div>

				</div>

				<div id="dialog-sincronizando"  title="Sincronizando">

							<div class="">

								<h5>Sincronizando</h5>

							</div>

				</div>



        <script src="<?php echo base_url(); ?>public/v2/appVentasOffline.js"></script>

        <script src="<?php echo base_url(); ?>offline/js/attrchange.js"></script>        

        <script>



            //Redirect en caso de no teer slash al final

            var url = document.URL;

            if (url.slice(-1) != "/") {

                window.location.replace(url + "/");

            }



            var appOffline;

            appOffline = new classVentaOffline();

            appOffline.conectarDB(function () { });

					

					

					

        </script>   



        <script type="text/javascript">



            //==================================================================

            // SWITCH  PARA DETECTAR SI ESTAMOS CONECTADOS ONLINE

            //==================================================================

					

					function dialogSinc(){

						

							$("#dialog-internet").dialog({

										modal: true,

										title: "Conexión a Internet",

										show: "fold",

										buttons: [

												{

													text: "SINCRONIZAR",

													icons: {

														primary: "ui-icon-heart"

													},

													click: function() {

														iniciarConexion();

													}

												}

											]							

									});

					}

					

					function dialogSincRuning(){

						

							$("#dialog-sincronizando").dialog({

										modal: true,						

									});

					}					

					

					

            function isOnline(){

									$("#btnSincCont").show();

									dialogSinc();

            }

            



            //-------------------------------------------------------------------------

            if (navigator.onLine){

                //isOnline();

								$("#btnSincCont").show();

            }else{

								$("#btnSincCont").hide();

						}

            

                

            window.addEventListener("online", function (e) {

								setTimeout(function(){

										isOnline();	

								},1200);

            }, false);

            window.addEventListener("offline", function (e) {

            }, false);

            //-------------------------------------------------------------------------



            //==================================================================

            //    FIN SWITCH

            //==================================================================        





            function sincronizar(){

                

                appOffline.queryVentas(function () {



                    var objVentas = appOffline.getObjVentas();

                    var objClientes = appOffline.getObjClientesOffline();





                        if (objClientes.length == 0) {

                           //alert("No hay ventas para sincronizar");

                           //window.location = "../index.php/ventas";

                        }else {



                           $("#btnSincronizar small").html("Sincronizando...");

                           $("#btnSincronizar small").css("background-color", "rgb(0, 139, 178)");                                    



                            var datos = { data : objClientes };

                            $.ajax({



                                url: '<?php echo site_url(); ?>/ventasOffline/importarClientes',

                                dataType: 'json',

                                type: 'POST',

                                data: datos,

                                async: false,

                                error: function (jqXHR, textStatus, errorThrown) {

                                    console.log(" Erro importar Cliente ");

                                    console.log(jqXHR)

                                    console.log(textStatus)

                                    console.log(errorThrown)

                                    $("#btnSincronizar small").html("Error. Inicie Sesión");

                                    $("#btnSincronizar small").css("background-color", "rgb(178, 0, 0)");

                                    alert("Error al Sincronizar Clientes");

                                },

                                success: function (data) {



                                    console.log( "Cliente: " );

                                    console.log( data );



                                    if (data.success == "true") {

                                        appOffline.truncateClientes(function () {});



                                        if (objVentas.length == 0) {

                                            alert("clientes Sincronizados");

                                            window.location = "<?php echo site_url(); ?>/clientes";

                                        }



                                    } else {

                                        alert("Ha ocurrido un error Cliente no creado");

                                    }

                                }

                            });



                        }







                        if (objVentas.length == 0) {

                            if (objClientes.length == 0) { 

                                alert("No hay ventas para sincronizar");

                                window.location = "<?php echo site_url(); ?>";

                            }



                        }else {



                           $("#btnSincronizar small").html("Sincronizando...");

                           $("#btnSincronizar small").css("background-color", "rgb(0, 139, 178)");                                    



                           var datos = { data : objVentas };                        

                           $.ajax({



                               url: '<?php echo site_url(); ?>/ventasOffline/importar',

                               dataType: 'json',

                               type: 'POST',

                               data: datos,

                               async: false,

                               error: function (jqXHR, textStatus, errorThrown) {

                                    console.log(" Erro importar Venta ");

                                    console.log(jqXHR)

                                    console.log(textStatus)

                                    console.log(errorThrown)                               

                                   $("#btnSincronizar small").html("Error. Inicie Sesión");

                                   $("#btnSincronizar small").css("background-color", "rgb(178, 0, 0)");

                                   alert("Error al Sincronizar Ventas");

                               },

                               success: function (data) {



                                   console.log( "Ventas: " );

                                   console.log( data );



                                   if (data.success == "true") {



                                       appOffline.truncateVentas(function () {

																				 	 $("#dialog-sincronizando").dialog("close");

                                           alert("Ventas Sincronizadas Correctamente");

                                           window.location = "<?php echo site_url(); ?>/ventas";

                                       });



                                   } else {

                                       alert("Ha ocurrido un error venta no creada");

                                   }



                               }

                           });



                        }







                });



            }



            function iniciarConexion(){

							

								$("#btnSincCont").hide();

								$("#dialog-internet").hide();

								

								dialogSincRuning();



                $.ajax({



                    url: '<?php echo site_url(); ?>/ventasOffline/queryOfflineAjax',

                    dataType: 'json',

                    type: 'POST',                                

                    async: false,

                    error: function (jqXHR, textStatus, errorThrown) {

                        console.log(" Erro Coneccion");

                        console.log(jqXHR)

                        console.log(textStatus)

                        console.log(errorThrown)

                        $("#btnSincronizar small").html("Error. Inicie Sesión");

                        $("#btnSincronizar small").css("background-color", "rgb(178, 0, 0)");

                        alert("Por favor inicie sesión en VendTy para sincronizar la información");

                    },

                    success: function (data) {



                        if (data.status == "false") {

                            alert("Aplicación Offline Desactivada");

                        } else {

                            

                            sincronizar();

                            

                        }

                    }

                });



            }



            



        </script>



        <!--////////////////////////////////////////////////////////////-->

        <!--  OFFLINE -->

        <!--////////////////////////////////////////////////////////////-->





        <!-- Core  -->

        <!--<script src="<?php echo base_url("public/v2"); ?>/global/vendor/jquery/jquery.min.js"></script>-->

        <!--<script src="<?php echo base_url("public/v2"); ?>/global/vendor/bootstrap/bootstrap.min.js"></script>-->

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/asscroll/jquery-asScroll.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/mousewheel/jquery.mousewheel.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/jquery.asScrollable.all.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/ashoverscroll/jquery-asHoverScroll.min.js"></script>



        <!-- Plugins -->

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min.js"></script>



        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/jquery-slidePanel.min.js"></script>



        <!-- Plugins For This Page -->





        <!-- Scripts -->

        <script src="<?php echo base_url("public/v2"); ?>/global/js/core.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/site.min.js"></script>



        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menu.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menubar.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/gridmenu.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/sidebar.min.js"></script>





        <script src="<?php echo base_url("public/v2"); ?>/global/js/components/asscrollable.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/global/js/components/animsition.min.js"></script>

        <script src="<?php echo base_url("public/v2"); ?>/global/js/components/slidepanel.min.js"></script>

        







        <script>

            

        $("#btnUi1,#btnUi2").click(function(){

            

            $.ajax({

                type: "POST",

                url: '<?php echo site_url("frontend/setUi/v1"); ?>',

                success: function (response) {                

                    location.reload();

                },

                error: function (xhr, textStatus, errorThrown) {

                    alert(textStatus + " : " + errorThrown);

                }

            });            

            

        });

        

      $(document).ready(function ($) {





            // ===================================================

            // FIX Dialog close

            // ===================================================

          

            $( "#dialog-forma-pago-form #cancelar").click(function(){

                    $( "#dialog-forma-pago-form" ).dialog( "close" );

            });

            $( "#dialog-plan-separe-form #cancelar").click(function(){

                    $( "#dialog-plan-separe-form" ).dialog( "close" );

            });            

            

            // ===================================================

            // ===================================================

			

            Site.run();            

            

            $('#btnToggleMenu').click();

            // ===================================================

            // Togle Logo

            // ===================================================

            

            function toggleLogo(target){                

               var close = $(target).hasClass("unfolded");               

               if(close){

                   $(".navbar-brand-logo").css("visibility","hidden");

                   $(".navbar-brand-logo2").css("visibility","visible");

               }else{

                   $(".navbar-brand-logo").css("visibility","visible");

                   $(".navbar-brand-logo2").css("visibility","hidden");

               }               

            }

            function toggleLogoInit(target){                

               var close = $(target).hasClass("unfolded");               

               if(close){

                   $(".navbar-brand-logo").css("visibility","visible");

                   $(".navbar-brand-logo2").css("visibility","hidden");

               }else{

                   $(".navbar-brand-logo").css("visibility","hidden");

                   $(".navbar-brand-logo2").css("visibility","visible");

               }               

            }            

            $('#btnToggleMenu').click(function(){

                toggleLogo(this);

            });

            

            toggleLogoInit("#btnToggleMenu");

            

            // ===================================================

            // Togle Logo

            // ===================================================  

            

            $('.site-menubar').show();           

          

            $(".navbar-brand-center").click(function(){

                window.location.replace("<?php echo site_url(); ?>");

            })

          

      });

        </script>



    </body>





    

</html>