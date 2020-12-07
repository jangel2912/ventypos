<style>	
    *{
        font-family: Arial, sans-serif;
        font-size:14px;
    }
    span{		
        width:100px;
        display:block;
        float:left;
    }
    div>span:first-child{
        width:60px;
        display:block;
        font-weight:bold;
    }
    .contData{
        float:left;
        height:15px;
        width:200px;
        clear:both;
    }

    .curved{
        border-radius: 4px !important;
    }

</style>





<div class="row" style="margin-top:40px;">


    <div class="col-xs-12 col-md-4"></div>

    <div class="col-xs-12 col-md-4">
        <form id="email" method="GET" action="<?php echo site_url('ventasOffline/queryOfflineAjax/'); ?>">    
            <div id="listaAlmacenes" class="example-col panel" style="margin-bottom:10px;">

                <div class="row" style=" padding-top: 0px;">
                    <h3 style=" text-align:center; margin: 5px; padding: 0px;"><?php echo getOffline(); ?>Asignar Offline</h3>
                    <hr>
                </div>

                <div class="row" style="padding-top: 0px;">

                    <div class="col-md-12">
                        <input id="mail" class="curved" type="text" value="" name="mail" placeholder="Correo..."/>
                    </div>
                </div>

                <div class="row" style="padding-top: 0px;">                                        
                    <div class="col-md-12">                        
                        
                    </div>                    
                </div>                



            </div>
        </form>                 
    </div>

    <div class="col-xs-12 col-md-4"></div>


</div>

<div id="desactivado" class="row" style="margin-top:0px;  display: none;">
    <div class="col-xs-12 col-md-4"></div>
    <div class="col-xs-12 col-md-4">
        <div id="listaAlmacenes" class="example-col panel" style=" background-color: #CA8A8A;  cursor: pointer">
                <div class="row" style=" padding-top: 0px;">                    
                    <h3 style=" text-align:center; margin: 5px; padding: 0px; color: #EFEFEF !important;">
                        <i class="site-menu-icon wb-alert-circle" aria-hidden="true" style=" font-size: 20px;"></i>
                        Desactivado
                    </h3>
                </div>
            </div>
    </div>
    <div class="col-xs-12 col-md-4"></div>
</div>


<div id="activado" class="row" style="margin-top:0px; display: none;">
    <div class="col-xs-12 col-md-4"></div>
    <div class="col-xs-12 col-md-4">
        <div id="listaAlmacenes" class="example-col panel" style=" background-color: #AAD085; cursor: pointer">
                <div class="row" style=" padding-top: 0px;">                    
                    
                    <h3 style=" text-align:center; margin: 5px; padding: 0px; color: #EFEFEF !important;">
                        <i class="site-menu-icon wb-check-circle" aria-hidden="true" style=" font-size: 20px;"></i>
                        Activado
                    </h3>
                    
                </div>
            </div>
    </div>
    <div class="col-xs-12 col-md-4"></div>
</div>



<script type="text/javascript">

    //=============================================

    $("#mail").keyup(function(e){
        if( e.keyCode != "13" ){
            ocultar();
        }            
    })

    $("#desactivado").click(function(){
        
        var data = {
            "email" : $("#mail").val(),
            "tipo" : "activar",
        }
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>/ventasOffline/setOffline/",
            cache: false,
            data: data,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if(response.status=="ok"){                    
                    $("#desactivado").slideUp("fast",function(){
                        $("#activado").slideDown("fast");    
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(xhr);
                console.log(textStatus);
                console.log(errorThrown);                
                alert(textStatus + " : " + errorThrown);
            }
        });
        
    });


    $("#activado").click(function(){

        var data = {
            "email" : $("#mail").val(),
            "tipo" : "desactivar",
        }
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>/ventasOffline/setOffline/",
            cache: false,
            data: data,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if(response.status=="ok"){                    
                    $("#activado").slideUp("fast",function(){
                        $("#desactivado").slideDown("fast");    
                    });
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(xhr);
                console.log(textStatus);
                console.log(errorThrown);                
                alert(textStatus + " : " + errorThrown);
            }
        });
        
    });


    $("#email").submit(function () {
        ocultar();
        enviar_correo($(this));
        return false;
    });


    //==============================================
    
    function enviar_correo(form) {

        $.ajax({
            type: "GET",
            url: $(form).attr('action'),
            cache: false,
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                console.log(response);
                set_response(response.status);
            },
            error: function (xhr, textStatus, errorThrown){
                console.log(xhr);
                console.log(textStatus);
                console.log(errorThrown);
                alert(textStatus + " : " + errorThrown);
            }
        });

    }

    var ocultar = function(){
        $("#desactivado").hide();
        $("#activado").hide();
    }
        
    function set_response(json_data){        
                
        // SI no existe el correo
        if (json_data == "empty"){
            ocultar();
            alert("EL correo no existe");
        }
            
        if (json_data == "false"){
            ocultar();
            $("#desactivado").slideDown("fast");
        }

        if (json_data == "active" || json_data == "backup"){
            ocultar();
            $("#activado").slideDown("fast");
        }
            
   
    }   

    $(document).ready(function ($) {

        $("#v2Cont.panel").removeClass("panel");

    });


</script>
