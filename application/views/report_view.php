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
</style>

<form id="email" method="POST" action="<?php echo site_url('report_controller/email/'); ?>">
    <input type="text" value="" name="mail" />
    <button type="submit" value="Submit">Enviar</button>    
</form>
<div>
    <div class="contData"><span>idUser: </span><span id="idUser" class="dynamicData"></span> </div>
    <div class="contData"><span>mail: </span><span id="mailFromDb" class="dynamicData"></span> </div>
    <div class="contData"> </div>
    <div class="contData"><span>idDb: </span><span id="iddb" class="dynamicData"></span> </div>
    <div class="contData"><span>db: </span><span id="db" class="dynamicData"></span> </div>	
    <div class="contData"><span>server: </span><span id="server" class="dynamicData"></span> </div>    
</div>
<div style=" clear: both; margin-top: 130px;">
<hr>    
</div>


<div style=" clear: both; margin-top: 20px;">
    <div style="margin: 10px;">
        <input id="txtPass" type="text" value="" name="pass" />   
    </div>
    <div style="margin: 10px;">
        <button type="button" onclick="getPass()" value="pass">Pass</button>    
    </div>
    <div style="margin: 10px;">
        <span id="labelPass">Pass</span>
    </div>
</div>

<script src="<?php echo base_url('public/js/plugins/jquery/jquery-1.9.1.min.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">

    $(function () {
        $("#email").submit(function () {
            enviar_correo($(this));
            return false;
        });
    });


    function enviar_correo(form) {

        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            cache: false,
            data: form.serialize(),
            dataType: 'text',
            success: function (response) {
                set_response(response);
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });

    }

    function set_response(json_data) {

        // SI no existe el correo
        if (json_data == "Mail not Found") {
            alert("Mail not Found");
            $(".dynamicData").text("");
        } else {

            var obj;
            //Validar coversion json
            try {
                obj = jQuery.parseJSON(json_data);
            } catch (e) {
                alert("error: " + e);
                alert(json_data);
                return false;
            }
            ;

            $("#idUser").text(obj.idUser);
            $("#mailFromDb").text(obj.email);

            $("#db").text(obj.db);
            $("#iddb").text(obj.idDb);
            $("#server").text(obj.server);

        }
    }
    
    function getPass(){
        
        var pass = $("#txtPass").val();
        
         $.ajax({
            type: "POST",
            url: "<?php echo site_url('report_controller/pass'); ?>",
            cache: false,
            data: {pass:pass},
            dataType: 'text',
            success: function ( response ) {

                $("#labelPass").html( response );

            },
            error: function (xhr, textStatus, errorThrown) {
                alert(textStatus + " : " + errorThrown);
            }
        });
        
    }

</script>
