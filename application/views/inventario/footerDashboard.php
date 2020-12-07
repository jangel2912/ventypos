


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
  
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/bootstrap/bootstrap.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/asscroll/jquery-asScroll.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/mousewheel/jquery.mousewheel.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/asscrollable/jquery.asScrollable.all.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/ashoverscroll/jquery-asHoverScroll.min.js"></script>

  <!-- Plugins -->
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/switchery/switchery.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/intro-js/intro.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/screenfull/screenfull.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/slidepanel/jquery-slidePanel.min.js"></script>

  <!-- Plugins For This Page -->
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/sparkline/jquery.sparkline.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/gauge-js/gauge.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/d3/d3.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/c3/c3.min.js"></script>  
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/flot/jquery.flot.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/flot/jquery.flot.resize.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/flot/jquery.flot.time.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/flot/jquery.flot.stack.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/flot/jquery.flot.pie.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/flot/jquery.flot.selection.js"></script>
  
  
  <!-- Plugins For This Page -->
  <!--<script src="<?php echo base_url("public/v2"); ?>/global/vendor/chart-js/Chart.min.js"></script>-->
  <script src="<?php echo base_url('public/export/js/plot/Chart.min.js'); ?>" type="text/javascript"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/chart-js/Chart.HorizontalBar.js"></script>
  
  
  <script src="<?php echo base_url("public/v2"); ?>/global/vendor/matchheight/jquery.matchHeight-min.js"></script>

  <!-- Scripts -->
  <script src="<?php echo base_url("public/v2"); ?>/global/js/core.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/site.min.js"></script>

  <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menu.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/menubar.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/gridmenu.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/sections/sidebar.min.js"></script>

  <script src="<?php echo base_url("public/v2"); ?>/global/js/configs/config-colors.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/base/assets/js/configs/config-tour.min.js"></script>

  <script src="<?php echo base_url("public/v2"); ?>/global/js/components/asscrollable.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/js/components/animsition.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/js/components/slidepanel.min.js"></script>
  <script src="<?php echo base_url("public/v2"); ?>/global/js/components/switchery.min.js"></script>

  <script src="<?php echo base_url("public/v2"); ?>/global/js/components/matchheight.min.js"></script>


  <script>
      /*
  var p3chat = p3chat || [];
  p3chat.push(['_setAccount', '081335174']);
  p3chat.push(['_trackPage']);
  (function (d, t, id) {
    if (d.getElementById(id)) return;
    var e = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    e.src = "//p3chat.com/dist/p3.js"; e.id = id;
    s.parentNode.insertBefore(e, s);
    
  }(document, 'script', 'p3chat-snippet'));
  */
</script>
  

        <script>
      $(document).ready(function ($) {
          
            Site.run();
            
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

          $(".navbar-brand-center").click(function(){
              window.location.replace("<?php echo site_url(); ?>");
          })
      });
        </script>

    </body>


    
</html>