<?php 
    //print_r($data["domiciliarios"]); die();
?>
<style>
    .switchery>small {
        position: absolute;
        top: 2px;
        width: 14px;
        height: 14px;
        background: #fff;
        border-radius: 100%;
        -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.4);
        box-shadow: 0 1px 3px rgba(0,0,0,.4);
    }
    div.checker span {
        background-position: 0px -260px;
        height: 19px;
        width: 40px;
    }
    div.checker input:checked {
        background: #5ca745 !important;
    }
    div.checker span, div.radio span {
        background-image: none !important; 
    }
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="domiciliarios" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_vendedor']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("domiciliarios", "Domiciliarios");?></h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="block">
            <?php
                $message = $this->session->flashdata('message');
                $message1 = $this->session->flashdata('message1');
                if(!empty($message)):?>
                <div class="alert alert-success">
                    <?php echo $message;?>
                </div>
            <?php endif;             
            if(!empty($message1)):?>
                <div class="alert alert-error">
                    <?php echo $message1;?>
                </div>
            <?php endif; ?>
            <?php $permisos = $this->session->userdata('permisos');
                $is_admin = $this->session->userdata('is_admin');
                if(in_array("1004", $permisos) || $is_admin == 't'):?>
                   <a href="<?php echo site_url("domiciliarios/nuevo")?>" data-tooltip="Nuevo Domiciliario">                        
                        <img alt="Nuevo Domiciliario" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['mas_verde']['original'] ?>">                         
                    </a>   
                <?php endif;?>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="col-md-12">
        <div class="block">        
            <div class="head blue">
                <h2><?php echo custom_lang('sima_all_provider', "Listado de Domiciliarios");?></h2>
            </div>

                <div class="data-fluid">
                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%" id="domiciliariosTable">
                        <thead>
                            <tr>                                
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "ID");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Tipo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Teléfono");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Dirección");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Comisión");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Logo");?></th>
                                <th width="5%"><?php echo custom_lang('sima_name_comercial', "Activo");?></th>
                                <th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php 
                                if(!empty($data["domiciliarios"])){
                                    foreach ($data["domiciliarios"] as $key => $value) {
                            ?>
                                        <tr>
                                            <td><?= $value['id'] ?> </td> 
                                            <td><?= $value['tipo'] ?> </td> 
                                            <td><?= $value['descripcion'] ?> </td> 
                                            <td><?= $value['telefono'] ?> </td> 
                                            <td><?= $value['direccion'] ?> </td> 
                                            <td><?= $value['comision'] ?> </td> 
                                            <td><img alt="<?= $value['id']; ?>" src="<?php echo base_url('uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$value['logo']) ?>"> </td> 
                                             <td> <?php $activo= ($value['activo']==1) ?'checked':''?>                                                                   
                                                <div class="contListas grp2 contListasSwitch">                 
                                                        <div class="listasCont tablaR">                                                                   
                                                            <div class="t" >
                                                                <div class="c"><input name="checkbox_<?= $value['id'] ?>" id="checkbox_<?= $value['id'] ?>"  <?= $activo?>  type="checkbox" class="js-switch" /></div>                                                                   
                                                            </div>                                                                    
                                                        </div>                                                
                                                </div>    
                                            </td> 
                                            <td>
                                                <div class='btnacciones'>
                                                    <a data-tooltip="Editar" href="<?php echo site_url("domiciliarios/editar/".$value['id']); ?>" class="button default acciones"><div class="icon"><img alt="editar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['editar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['editar']['original'] ?>"></div></a>
                                                    <!--<a data-tooltip="Eliminar" href="<?php echo site_url("domiciliarios/eliminar/".$value['id']);?>" onclick="if(confirm(\'<?php echo custom_lang('sima_delete_question', "Esta seguro que desea eliminar el registro?");?>\')){return true;}else{return false;}" class="button red acciones"><div class="icon"><img alt="Eliminar" data-cambiar="<?php echo $this->session->userdata('new_imagenes')['eliminar']['cambio'] ?>" data-original="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>" class="iconacciones" src="<?php echo $this->session->userdata('new_imagenes')['eliminar']['original'] ?>"></div></a>-->
                                                </div>
                                            </td> 
                                        </tr> 
                            <?php
                                    }
                                }                                
                            ?>                            
                        </tbody>
                        <tfoot>
                            <tr>                                
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "ID");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Tipo");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Nombre");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Teléfono");?></th>
                                <th width="20%"><?php echo custom_lang('sima_name_comercial', "Dirección");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Comisión");?></th>
                                <th width="10%"><?php echo custom_lang('sima_name_comercial', "Logo");?></th>
                                <th width="5%"><?php echo custom_lang('sima_name_comercial', "Activo");?></th>
                                <th width="5%"><?php echo custom_lang('sima_action', "Acciones");?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

        </div>

    </div>

   <script type="text/javascript">
    var opcObj = {};

    $(document).ready(function(){

        $('#domiciliariosTable').dataTable();

    });

     function getOpc(){
        
        arrayOpc = [];
        
        var strOpciones = "";
        
        $(".contListasSwitch input").each( function(){
            
            var elementId = $(this).attr("id"); 
            var nombreCampo = $(this).attr("name"); 
            var val = $(this)[0].checked;
            
            arrayOpc.push( { "name" : elementId, "val" :  val } );    
            
            // generamos sl string de parametros GET
            if( $(this)[0].checked ){                
                strOpciones += nombreCampo+","
            }

        });
        
        //$("#generarExcel").attr('href', downloadExcel+strOpciones );

        localStorage.setItem("opciones", JSON.stringify( arrayOpc ) );
        
    }

    function getOpcSession(){
        if ( localStorage.getItem("opciones") != null ){
            
            var objOpc;
            console.log("ddd");
            try {
                objOpc = JSON.parse( localStorage.opciones );
                objOpc.forEach(function(val){ 
                    if( val.val ) $( '#'+val.name ).trigger('click');                    
                });                
            } catch (e) {
                console.log("error conversion json opciones")
            }
            
   
        }
    }    

    $(document).ready(function(){
         
        getOpcSession();
        
        defaults = {
            color             : '#5ca745'
          , secondaryColor    : '#f4f8f9'
          , jackColor         : '#fff'
          , jackSecondaryColor: null
          , className         : 'switchery'
          , disabled          : false
          , disabledOpacity   : 0.5
          , speed             : '0.1s'
          , size              : 'default'
        }
        
        
        // Convertimos a Switchery
        $(".contListasSwitch input").each( function(){
           
            // Obtenemos el id de cada input
            var elementId = $(this).attr("id"); 
            // convertimos el check a switchery
            new Switchery( $('#'+elementId)[0], defaults );
            
            // almacenamos el elemento dom en el array opcObj
            opcObj[elementId] = $(this);
            
        });
        
        
        // Si los checkbox cambian
        $( ".js-switch" ).change(function() {
            
            var elementId = $(this).attr("id");
            id=elementId.split('_');          
            activo="";
            if($('#'+elementId).prop('checked')) {
                activo=1;
            }else{
                activo=0;
            }
            //cambio la opcion en bd    
            url="<?php echo site_url('domiciliarios/desactivar/')?>";   
           
            $.ajax({
                url: url,
                dataType: 'json',
                type: 'POST',
                data: {
                    id: id[1],
                    activo: activo,
                }
            });
            //getOpc();
        });
        
        // Cargamos los estados de las opciones guardadas por los usuarios en session        
        
        //getOpc();
        
    });
</script>