
                            </div>
    </div>    

</div>

<?php get_js("<script type='text/javascript' src='$1'></script>");?>



<script type="text/javascript">



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





    $('#change_languaje').change(function(){



        $.ajax({



                'url': '<?php echo site_url("auth/change_languaje")?>',



                data: {'languaje' :$('#change_languaje').val()},



                dataType: 'json',



                type: "POST",



                async: false,



                success: function(data){



                      location.href = "<?php echo current_url();?>";



                }



            });







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











<!-- Core  -->

<!--<script src="<?php echo base_url("public/v2"); ?>/global/vendor/jquery/jquery.min.js"></script>-->

<!--<script src="<?php echo base_url("public/v2"); ?>/global/vendor/bootstrap/bootstrap.min.js"></script>-->

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/animsition/animsition.min.js"></script>

<script src="<?php echo base_url("public/js"); ?>/vendty.js"></script>

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



<!-- Plugins For This Page -->

<script src="<?php echo base_url("public/v2"); ?>/global/vendor/toastr/toastr.min.js"></script>

<!-- Scripts -->



<script src="<?php echo base_url("public/js"); ?>/sweetalert2.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url("public/css"); ?>/sweetalert2.min.css">
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







$("#btnUi1,#btnUi2").click(function(){



$.ajax({

type: "POST",

url: '<?php echo site_url("frontend/setUi/v1"); ?>',

success: function (response) {                

location.reload(true);

},

error: function (xhr, textStatus, errorThrown) {

alert(textStatus + " : " + errorThrown);

}

});            



});



$(document).ready(function ($) {



// ===================================================

// FIX MODALS

// ===================================================



// Arreglando ventanas modal

$( ".modal" ).each(function(){     



// Los modal No deberian tener la clase hide, asi que la eliminamos y lo ocultamos inline

if( $(this).hasClass( "hide" ) ){

$(this).removeClass("hide");

$(this).css("display","none");

}

// añadimos el contenedor nativo de un modal en caso de que no lo tenga

if( $(this).children(".modal-body4").length == 0){

$(this).wrapInner('<div class="modal-dialog modal-center"><div class="modal-content"></div></div>');                                    

}                



});     



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