<style type="text/css">

    #seleccionar-grupo-loader{
        padding-top: 30px;
    }

    #clientesTable{
        width: 100%;
        height: 270px;
        overflow: auto;
    }

    #clientesTable tbody {
        width: 100%;
        height: 270px;
        overflow: auto;
    }

    #clientesTable tbody tr td{
        width: 100%;
    }

    #clientesTable thead > tr, #clientesTable tbody{
        display:block;
    }

    .waiting{
        background:gray;
    }

    #crear-grupo-error{
        display: none;
    }

    #multiselect{
        width: 100%;   
        display: block;
    }

    #multiselect #ms-header{
      overflow: hidden;
    }

    #multiselect #ms-selected-container,
    #multiselect #ms-options-container,
    #multiselect #ms-hd-options,
    #multiselect #ms-hd-selected{
        float: left;
        width: 40%;
        margin-right: 3%;
    }

    #multiselect ul{      
        height: 276px;
        list-style: none;
        margin-left: 0px;      
        overflow-x: hidden;
        overflow-y: scroll;
        background: white;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
        -moz-transition: border linear 0.2s, box-shadow linear 0.2s;
        -ms-transition: border linear 0.2s, box-shadow linear 0.2s;
        -o-transition: border linear 0.2s, box-shadow linear 0.2s;
        transition: border linear 0.2s, box-shadow linear 0.2s;
        border: 1px solid #DDD;
    }

    #multiselect ul li{
        border-bottom: 1px #CCC solid;
        padding: 4px 10px;
        color: #555;
        font-size: 11px;
    }

    #multiselect ul li:hover{
        background: #009AD7;
        color: white;
        cursor:pointer; cursor: hand
    }

    #new-group-btn{
        width: 90px!important;
    }
   
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="Cliente" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_cliente']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Cliente", "Cliente");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_client_group', "Grupo de Clientes");?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span5">

        <div class="block">

            <div class="head blue">
                <!-- <div class="icon"><i class="ico-box"></i></div> -->
                <h2><?php echo custom_lang('sima_client_new_group', "Nuevo grupo");?></h2>
            </div>

            <div class="data-fluid">
                <br>
                <div id="form">
                    <div class="row-fluid">
                        <div class="span8">
                            <p>
                                <?php echo custom_lang('sima_name_group', "Nombre");?>: 
                                <input type="text" id='nombre_grupo' value="<?php echo set_value('name_group'); ?>" name="name_group" placeholder=""/>
                            </p>   
                        </div>

                        <div class="span4">
                            <div class="toolbar bottom tar" style="padding-top: 0px;">
                                <div class="btn-group">
                                    <br>
                                    <button id="new-group-btn" class="btn btn-success" onclick="group.crearGrupo()">
                                        <span id='new-group-btn-text'>Guardar</span>
                                    </button> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div id="crear-grupo-error" class="alert alert-success">
                                <?php

                                $message = $this->session->flashdata('message');

                                  if(!empty($message)):?>
                                    <?php echo $message;?>
                                    <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="clientesTable">
                <thead>
                    <tr style="width: 100%;">
                        <th style=" width: 20%;"></th>
                        <th style=" width: 100%;">Nombre</th>
                        <th style=" width: 100%;"></th>
                    </tr>
                </thead>
                <tbody id='tb-grupos-clientes'>
                    <?php 
                        $i=0;
                        foreach ($grupo_clientes as $key => $value) {
                            echo "<tr><td style=' width: 20%;'>".($i+1)."</td><td>".$value->nombre."</td><td style='width: 50px;' id='grupo-".$value->id."' 
                            onclick='group.deleteGroup(this)'>
                            <a href='javascript:void(0)' class='button red acciones'><div class='icon'><img alt='Eliminar' data-cambiar='".$this->session->userdata('new_imagenes')['eliminar']['cambio']."' data-original='".$this->session->userdata('new_imagenes')['eliminar']['original']."' class='iconacciones' src='".$this->session->userdata('new_imagenes')['eliminar']['original']."'></div></a></td></tr>"; 
                            $i++;
                        }
                    ?>
                </tbody>
            </table>

        </div>

   </div>

   <div class="span7" style="padding-bottom: 10px;padding-left: 15px;padding-right: 15px;">
        <div>
            <div class="block">

                <div class="head blue">
                    <!-- <div class="icon"><i class="ico-box"></i></div> -->
                    <h2><?php echo custom_lang('sima_assign_group', "Asignar grupo");?></h2>
                </div>

                <div class="row-form">

                    <div class="span8">
                        <span>Seleccione grupo:</span>
                        <select id="seleccionar-grupo" onchange="cambiar_grupo(this)">
                            <option value="0">Seleccione un Grupo</option>
                            <?php 
                                foreach ($grupo_clientes as $key => $value) {
                                        echo "<option value='".$value->id."'>".$value->nombre."</option>"; 
                                    }
                            ?>
                        </select>
                    </div>
                    <div class="span4">
                       <div id="seleccionar-grupo-loader"></div>
                    </div>

                </div>
              
            </div>
            <div class="span12">  
                <div id="multiselect">
                    <div id='ms-header'>
                        <div id="ms-hd-options">
                            <div class="input-prepend input-append">
                              <input type="text" placeholder="Nombre" name="ms-client" id="ms-client" style="width: 178px;">     
                              <span class="add-on green"><i class="icon-search icon-white"></i></span>
                            </div>
                        </div>
                        <div id="ms-hd-selected">
                            <p id="selected-header">Asignar</p>
                        </div>
                    </div>
                    <div id="ms-options-container">                      
                        <ul id='ms-options'></ul>
                    </div>
                    <div id="ms-selected-container">                  
                        <ul id='ms-selected'></ul>
                    </div>
                </div>
            </div> 

            <div class="span9">
                <div class="toolbar bottom tar" style="padding-top: 0px;">
                    <div class="btn-group">
                        <br>
                        <button class="btn btn-success" id="asignar-grupo-btn" onclick="asignar_grupo()"><?php echo custom_lang("sima_submit", "Guardar");?></button> 
                    </div>
                </div>
            </div>

            <div class="span9">
                <div id="alert-asignar-grupo" class="alert alert-success" style="display:none">    
                    <?php 
                        if(!empty($message)){
                            echo $message;
                        }
                    ?>
                </div>
            </div>

            
        </div> 

   </div>
    
</div>

<script type="text/javascript">


    function renderLoader(){
        var html = '<div id="circularG">'+
                        '<div id="circularG_1" class="circularG"></div>'+
                        '<div id="circularG_2" class="circularG"></div>'+
                        '<div id="circularG_3" class="circularG"></div>'+
                        '<div id="circularG_4" class="circularG"></div>'+
                        '<div id="circularG_5" class="circularG"></div>'+
                        '<div id="circularG_6" class="circularG"></div>'+
                        '<div id="circularG_7" class="circularG"></div>'+
                        '<div id="circularG_8" class="circularG"></div>'+
                    '</div>'+
                    '<span id="new-group-btn-text">Espere</span>';

        return html;
    }

    var group = {

        name:'',
        crearGrupo:function(){
             
            $("#new-group-btn-text").prop('disabled', true);
            this.name = $('#nombre_grupo').val();

            if(this.name!=''){

                //$('#nombre_grupo').val('');       
                $("#new-group-btn-text").html(renderLoader());
                $("#nombre_grupo").prop('disabled', true);
                $("#new-group-btn").prop('disabled', true);

                $.ajax({
                      type: "POST",
                      url: "<?php site_url('clientes/grupos') ?>",
                      data: { name_group: this.name}
                })
                .done(function( response ) {
                   
                    if(response.resp==1){
                        response = response.cliente;
                        $('#tb-grupos-clientes').html('');
                        for (var i = 0; i < response.length; i++){
                            $('#tb-grupos-clientes').append( 
                            '<tr><td style=" width: 33px;">'+(i+1)+'</td><td>'+response[i].nombre+'</td><td style="width: 50px;" id="grupo-'+response[i].id+'" onclick="group.deleteGroup(this)"><a href="javascript:void(0)" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a></td></tr>'
                            );
                        };
                        
                        $('#crear-grupo-error').removeClass( "alert-error" ).addClass( "alert-success" );
                        $('#crear-grupo-error').html('Grupo creado con éxito');
                        $('#crear-grupo-error').fadeIn('fast');
                        /*Agregar nuevo grupo a checkbox de grupos*/
                        $('#seleccionar-grupo').append('<option value="'+response[response.length - 1].id+'">'+response[response.length - 1].nombre+'</option>');                        
                        $("#nombre_grupo").val('');
                        $("#new-group-btn-text").html('Guardar');
                        $("#circularG").css('display','none');
                        $("#nombre_grupo").prop('disabled', false);
                        $("#new-group-btn").prop('disabled', false);
                        $("new-group-btn-text").prop('disabled', false);
                        
                        /*Agregar nuevo grupo a checkbox de grupos*/
                        
                        $.ajax({

                            url: "<?php echo site_url("clientes/get_clients_group_all")?>",

                            data: {group: asignar.grupo},

                            type: "POST",

                            success: function(response) {

                                multiSelect.init(response.clientes_sin_grupo); 

                            }

                        });
                    }else{
                        $('#crear-grupo-error').removeClass( "alert-success" ).addClass( "alert-error" );
                        $('#crear-grupo-error').html('Ya existe un grupo con este nombre');
                        $('#crear-grupo-error').fadeIn('fast');
                        $("#new-group-btn-text").html('Guardar');
                        $("#circularG").css('display','none');
                        $("#new-group-btn").prop('disabled', false);
                        $("#nombre_grupo").prop('disabled', false);
                    }
                    
                });
            }else{
                $('#crear-grupo-error').removeClass( "alert-success" ).addClass( "alert-error" );
                $('#crear-grupo-error').html('Por favor escriba el nombre del grupo');
                $('#crear-grupo-error').fadeIn('fast');
                $("new-group-btn-text").prop('disabled', false);
                $("new-group-btn").prop('disabled', false);
            }
        },
        deleteGroup:function(element){
           
            var group_id = String(element.id).split('-')[1];
           
            if(group_id!=0){
                $('.red').css('background','gray!important');
                $("#nombre_grupo").prop('disabled', true);
                $("#new-group-btn").prop('disabled', true);

                $.ajax({
                      type: "POST",
                      url: "<?php site_url('clientes/grupos') ?>",
                      data: { delete_group: group_id}
                })
                .done(function( response ) {
                     
                    if(response.resp == 1)
                    {       
                        response = response.cliente;
                        $('#tb-grupos-clientes').html('');
                        $('#seleccionar-grupo').html('');

                        for (var i = 0; i < response.length; i++) {
                            $('#tb-grupos-clientes').append( 
                            '<tr><td style=" width: 33px;">'+(i+1)+'</td><td>'+response[i].nombre+'</td><td style="width: 50px;" id="grupo-'+response[i].id+'" onclick="group.deleteGroup(this)"><a href="javascript:void(0)" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a></td></tr>'
                            );
                            $('#seleccionar-grupo').append("<option value='"+response[i].id+"'>"+response[i].nombre+"</option>");
                        };

                        $('#crear-grupo-error').removeClass( "alert-error" ).addClass( "alert-success" );
                        $('#crear-grupo-error').html('Grupo eliminado');
                        $('#crear-grupo-error').fadeIn('fast');
                    }
                    else {
                        if(response.resp == 0){//asociado a una lista
                           
                            $('#crear-grupo-error').removeClass( "alert-success" ).addClass( "alert-error" );
                            $('#crear-grupo-error').html('El grupo no puede ser eliminado porque esta asociado a una lista de precios');
                            $('#crear-grupo-error').fadeIn('fast');
                            $("#new-group-btn").prop('disabled', false);
                            $("#nombre_grupo").prop('disabled', false);
                        }else{
                            if(response.resp == 3){//grupo sin grupo                               
                                $('#crear-grupo-error').removeClass( "alert-success" ).addClass( "alert-error" );
                                $('#crear-grupo-error').html('No puede eliminar este grupo');
                                $('#crear-grupo-error').fadeIn('fast');
                                $("#new-group-btn").prop('disabled', false);
                                $("#nombre_grupo").prop('disabled', false);
                            }else{
                                if(response.resp == 2){//no existe grupo
                                    $('#crear-grupo-error').removeClass( "alert-success" ).addClass( "alert-error" );
                                    $('#crear-grupo-error').html('No puede eliminar este grupo');
                                    $('#crear-grupo-error').fadeIn('fast');
                                    $("#new-group-btn").prop('disabled', false);
                                    $("#nombre_grupo").prop('disabled', false);
                                }
                            }
                        }
                        
                    }
                });
            }else{                
                $('#crear-grupo-error').removeClass( "alert-success" ).addClass( "alert-error" );
                $('#crear-grupo-error').html('No puede eliminar este grupo');
                $('#crear-grupo-error').fadeIn('fast');
                $("#new-group-btn").prop('disabled', false);
                $("#nombre_grupo").prop('disabled', false);
            }
              
        }
    }




    var asignar = {
        grupo:0,
        clientes: new Array()
    }

    var desasignar = {
        clientes: new Array()
    }

    var multiSelect = {

        ms_clientes: {}, 
        ms_selected: new Array(),

        init:function(json){
            this.ms_clientes = json;

            $('#ms-options').html('');
            for (var i = 0; i < this.ms_clientes.length; i++) {
               $('#ms-options').append('<li id="ms-op-'+(i)+'" value="'+this.ms_clientes[i].id_cliente+'" onclick="multiSelect.select(this)">'+this.ms_clientes[i].nombre_comercial+'</li>');
            };

            $('#ms-selected').html('');
            for (var i = 0; i < this.ms_selected.length; i++) {
               $('#ms-selected').append('<li id="ms-sl-'+(i)+'" value="'+this.ms_selected[i].id_cliente+'" onclick="multiSelect.deselect(this)">'+this.ms_selected[i].nombre_comercial+'</li>');
            };

        },
        select:function(element){  

            if(this.beforeSelect(element)){
                var index = String(element.id).split("ms-op-")[1];
                this.ms_selected.push(this.ms_clientes[index]);
                this.ms_clientes.splice(index, 1);
                this.renderMultiselect();
            }
            
        },
        beforeSelect: function(element){

            if(document.getElementById('seleccionar-grupo').selectedIndex!=0){

                asignar.clientes.push(element.value);
                return true;

            }else{

                $('#alert-asignar-grupo').show();
                $('#alert-asignar-grupo').html('<b>Porfavor seleccione un grupo</b>');
                $('#selected-header').html('Grupo: <b>Selecione un grupo</b>');
                /*$('#custom-headers').multiSelect('deselect_all');*/
                return false;
                
            }

        },
        deselect:function(element){

            var index = String(element.id).split("ms-sl-")[1];
            this.ms_clientes.push(this.ms_selected[index]);
            this.ms_selected.splice(index, 1);
            desasignar.clientes.push(element.value);
            this.renderMultiselect();


        },

        renderMultiselect:function(){

            $('#ms-options').html('');
            for (var i = 0; i < this.ms_clientes.length; i++) {
               $('#ms-options').append('<li id="ms-op-'+(i)+'" value="'+this.ms_clientes[i].id_cliente+'" onclick="multiSelect.select(this)">'+this.ms_clientes[i].nombre_comercial+'</li>');
            };

            $('#ms-selected').html('');
            for (var i = 0; i < this.ms_selected.length; i++) {
               $('#ms-selected').append('<li id="ms-sl-'+(i)+'" value="'+this.ms_selected[i].id_cliente+'" onclick="multiSelect.deselect(this)">'+this.ms_selected[i].nombre_comercial+'</li>');
            };


        },
        ajax:function(){
           
        }
    }


    $( "#ms-client" ).keyup(function() {

        var filter = $("#ms-client").val();
       
        if(filter!=''){

            $("#ms-options").html('buscando...');
            $.ajax({
                type: "GET",
                url: "get_clients_group_filter?filter="+filter
            }).done(function( response ) {
                multiSelect.ms_clientes = {};
                multiSelect.init(response);  
            }); 

        }
      

    });
  
    /*Traer cliente del grupo seleccionado*/
    function cambiar_grupo(element){

        $('#custom-headers').multiSelect('deselect_all');
        asignar.clientes= new Array();

        //Cambiar header grupo
        var x = element.selectedIndex;
        var y = element.options;
        $('#selected-header').html('Grupo: <b>'+y[x].text+'</b>');
        //Asignar grupo a objeto asignar
         asignar.grupo = y[x].value;
        //Esconder mensajes de alerta
        $('#alert-asignar-grupo').html('');
        $('#alert-asignar-grupo').hide();
    
        $("#seleccionar-grupo-loader").html(renderLoader());  
        $('.circularG').css('background-color','#000000');
        $('#ms-client').val('');
        $("#ms-options").html('buscando...');
        $("#ms-selected").html('buscando...');

        $.ajax({

                url: "<?php echo site_url("clientes/get_clients_group_all")?>",

                data: {group: asignar.grupo},

                type: "POST",

                success: function(response) {
                    
                    multiSelect.ms_selected = response.clientes_grupo;
                    multiSelect.init(response.clientes_sin_grupo); 
                    $("#seleccionar-grupo-loader").html('');

                }

        });
       

    }

    function asignar_grupo(){

        console.log(asignar);
        console.log(desasignar);        

        var element = document.getElementById('seleccionar-grupo');

        if(element.selectedIndex!=0){


            $('#asignar-grupo-btn').html(renderLoader());

            $.ajax({

                url: "<?php echo site_url("clientes/asignar_grupo")?>",

                data: {asignar:asignar , desasignar:desasignar},

                type: "POST",

                success: function(response) {

                    $('#alert-asignar-grupo').show();
                  
                    if(response.done == 1){
                        $('#alert-asignar-grupo').html('<b>Grupos asignados con éxito</b>');
                        element.selectedIndex =0;
                        multiSelect.ms_clientes = {};
                        multiSelect.ms_selected = new Array();
                        multiSelect.init(response.data.clientes); 
                        asignar = {
                            grupo:0,
                            clientes: new Array()
                        };
                        desasignar = {
                            clientes: new Array()
                        }
                        $("#ms-client").val('');
                        $('#selected-header').html('');

                        $('#asignar-grupo-btn').html('Guardar');

                    }          
                    else {$('#alert-asignar-grupo').html('<b>Ocurrió un error vuelva a intentarlo</b>');}  

                }

            });
        }else{
                $('#alert-asignar-grupo').removeClass( "alert-success" ).addClass( "alert-error" );
                $('#alert-asignar-grupo').show();
                $('#alert-asignar-grupo').html('<b>Por favor seleccione un grupo</b>');
                $('#grupo-header').html('Grupo: <b>Selecione un grupo</b>');
                $('#custom-headers').multiSelect('deselect_all');
                
        }
  
    }



    $(document).ready(function(){

       $("#pais").change(function(){

           load_provincias_from_pais($(this).val());

       }); 

       var pais = $("#pais").val();

       if(pais != ""){

           load_provincias_from_pais(pais);

       }

        multiSelect.init(<?php echo json_encode($clientes)?>);

    });

    function load_provincias_from_pais(pais){

        $.ajax({

            url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",

            data: {"pais" : pais},

            dataType: "json",

            success: function(data) {

                $("#provincia").html('');

                $.each(data, function(index, element){

                    provincia = "<?php echo set_value('provincia');?>"

                    sel = provincia == element[0] ? "selected='selectted'" : '';

                   $("#provincia").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>"); 

                });

            }

        });

    }

</script>