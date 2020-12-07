

                            </div>
                            </div>

                    
                    
<?php get_js('<script type="text/javascript" src="$1"></script>'); ?>

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
            <div class="site-footer-legal">© 2015 <a href="javascript:void(0)"><strong>Vendty</strong></a></div>
            <div class="site-footer-right">
            </div>
        </footer>
        </div>





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
        <script src="<?php echo base_url("public/v2"); ?>/global/js/components/switchery.min.js"></script>



        <script>
      $(document).ready(function ($) {
          
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