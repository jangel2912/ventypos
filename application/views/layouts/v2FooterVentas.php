



        </div>

    </div>

</div>

<!-- End Page -->



<!-- Footer -->

<!--<div class="v2h">

    <footer class="site-footer">

        <div class="site-footer-legal">© 2016 <a href="javascript:void(0)"><strong>Vendty</strong></a></div>

        <div class="site-footer-right">

        </div>

    </footer>

</div>-->

<?php $this->load->view('layouts/modal_generico_ajax'); ?>





<?php get_js('<script type="text/javascript" src="$1"></script>'); ?>



<script>



        $(document).ready(function(){



            $.ajax({



                'url': '<?php echo site_url("auth/check_tester")?>',



                data: {},



                type: "POST",



                //async: false,



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







        <script src="<?php echo base_url().'public'?>/js/sweetalert2.min.js"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'public'?>/css/sweetalert2.min.css">


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

        <script src="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.js"></script>





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
        <script src="<?php echo base_url();?>public/js/newicons.js"></script>
        <script type="text/javascript">
            function goBorrarOffline(){
                window.location = "<?php echo site_url(); ?>/frontend/borrarOffline";
            }
        </script>
<!--
        <script type="text/javascript">
        var vsid = "sa33689";
        (function() {
            var vsjs = document.createElement('script'); vsjs.type = 'text/javascript'; vsjs.async = true; vsjs.setAttribute('defer', 'defer');
            vsjs.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'www.virtualspirits.com/vsa/chat-'+vsid+'.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(vsjs, s);
        })();
        </script>-->





        <script>



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



            if(!mobilecheck())

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



            $( "body" ).removeClass( "site-menubar-open" );

            if($(window).width() >= 768){

                $( "body" ).removeClass( "site-menubar-unfold" ).removeClass( "site-menubar-unfold" ).addClass( "site-menubar-unfold" );

            }



            $( window ).resize(function() {

                $( "body" ).removeClass( "site-menubar-open" )

                if($(window).width() > 768){

                    $( "body" ).removeClass( "site-menubar-unfold" ).removeClass( "site-menubar-unfold" ).addClass( "site-menubar-unfold" );

                }



            });



      });

        </script>

<script>
    document.getElementById('admin_shop').addEventListener('click', function(e) {
        e.preventDefault();
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("data", this.responseText);

                var popUp = window.open('http://admintienda.vendty.com/admin/crosslogin', '_blank');
                if (popUp == null || typeof(popUp) == 'undefined') {
                    document.getElementById('validate-popup').style.display = 'block';
                }
            }
        };

        xhttp.open("GET", '<?php echo site_url('tienda/crossDomain');?>', true);
        xhttp.send();
    });
</script>

    </body>







</html>