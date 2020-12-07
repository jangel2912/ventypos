<div class="wizard">        
        <?php
$message = $this->session->flashdata('pa');
            if(!empty($message)):?>
            <div class="alert alert-error">
                <?php echo $message;?>
            </div>
        <?php endif;?>
        <div class="page-header">
            <img src="<?php echo base_url('public/img/logo_login.png');?>" alt="Logo"/>
        </div>        
    
        
    <div class="row-fluid" id="paso1">
            <div class="row-form">
                <div class="span12">
				Confirmaci&oacute;n de inicio de cuenta. Inserte nombre de empresa. 
                                <input type="text" name="empresa" id="empresa"  placeholder="Empresa" value="<?php echo $data['empresa'];?>">
                </div>
            </div>            
            
            <div class="row-form">
                <div class="span12 toolbar bottom tar">
                    <button class="btn btn_paso1" type="button">Continuar <span class="icon-arrow-next icon-white"></span></button>
                    <button class="btn btn-warning btn_cancelar" type="button">Cancelar <span class="icon-remove-circle icon-white"></span></button>
                </div>                
            </div>    
</div>
    <div class="row-fluid" id="paso2" style="display: none">
            <div class="page-header">
                <div class="icon">
                    <span class="ico-box"></span>
                </div>
                <h1><?php echo custom_lang("Productos", "Primer producto");?><small><?php echo $this->config->item('site_title');?></small></h1>
                           
            </div>        
        <!-- primer producto -->
        <div class="row-fluid">

    <div class="span12">
        <div class="block">
            <div class="data-fluid">
                <?php echo form_open_multipart("productos/primero", array("id" =>"primer_producto"));?>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('nombre'); ?>" placeholder="" name="nombre" />
                            <?php echo form_error('nombre'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "C&oacute;digo");?>:</div>
                    <div class="span9"><input type="text"  value="<?php echo set_value('codigo'); ?>" placeholder="" name="codigo" />
                            <?php echo form_error('codigo'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('price_of_purchase', "Precio de compra");?>:</div>
                    <div class="span9"><input type="text" value="<?php echo set_value('precio_compra'); ?>" name="precio_compra" placeholder=""/>
                        <?php echo form_error('precio_compra'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sale_price', "Precio de venta");?>:</div>
                    <div class="span9"><input type="text" value="<?php echo set_value('precio'); ?>" name="precio" placeholder=""/>
                        <?php echo form_error('precio'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_tax', "Impuesto");?>:</div>
                    <div class="span9">
                            <?php echo form_dropdown('id_impuesto', $data['impuestos'], $this->form_validation->set_value('id_impuesto'));?>
                            <?php echo form_error('id_impuesto'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_description', "Descripci&oacute;n");?>:</div>
                    <div class="span9"><textarea name="descripcion" placeholder=""><?php echo set_value('descripcion'); ?></textarea>
                       <?php echo form_error('descripcion'); ?>
                    </div>
                </div>                

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_image', "Imagen");?>:<br/>
                    </div>
                    <div class="span9">   
                        <div class="input-append file">
                            <input type="file" name="imagen"/>
                            <input type="text"/>
                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar");?></button>
                        </div> 
                        <?php //echo $data['data']['upload_error']; ?>
                    </div>
                </div>                

                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="50%">Almacen</th>
                                <th width="50%">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['almacenes'] as $key => $value) :?>
                                <tr>
                                   <td><?php echo $value;?></td><td><input name="Stock[<?php echo $key;?>]" min="0" type="number" value="<?php echo isset($_POST['Stock'][$key]) ? $_POST['Stock'][$key] : 0; ?>"/></td>
                               </tr>
                           <?php endforeach;?>
                        </tbody>  
                    </table>
                </div>               
            </div>
        </div>
        <div class="row-form">

         <?php echo custom_lang('sima_category', "Tipo producto");?>:
        </div>
        <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_category', "Categoria");?>:</div>
                    <div class="span9">
                        <select name='categoria_id'>
                            <?php 
                                foreach ($data['categorias'] as $key => $value) {
                                    echo "<option value='".$value->id."'>".($value->nombre)."</option>";
                                }
                             ?>
                        </select>
                        <?php echo form_error('categoria_id'); ?>
                    </div>
                </div>
        <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_unit', "Unidad");?>:</div>
                    <div class="span9">
                        <select name='unidad_id'>
                            <?php 
                                foreach ($data['unidades'] as $key => $value) {
                                    echo "<option value='".$value->id."'>".($value->nombre)."</option>";
                                }
                             ?>
                        </select>
                        <?php echo form_error('categoria_id'); ?>
                    </div>
                </div>        
    </div>    
</div>
                
        <!-- primer producto fin-->
    <div class="row-form">   
                <div class="span12 toolbar bottom tar">
                    <div class="span4">
                    <button class="btn btn_paso2atras left" type="button"> <span class="icon-arrow-back icon-white"></span>  Atras</button>
                    </div>
                    <button class="btn " type="submit">Enviar <span class="icon-arrow-next icon-white"></span></button>
                    <button class="btn btn-warning btn_cancelar" type="button">Cancelar <span class="icon-remove-circle icon-white"></span></button>
                </div>                
            </div>    
</div>
    <input type="hidden" id="estado" name="estado" value="<?php echo $data['estado'] ?>"/>
    
    
    <?php echo form_close();?>
    <style type="text/css">

    
    ul.autocomplete{
        display: none;
        z-index: 3000;
        list-style: none;
        margin-left: 10px;
        position: absolute;
        width: 300px;
        background: white;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
         border-radius: 10px;
         border-bottom: 1px solid #E9E9E9;
        cursor:pointer;
        cursor: hand;
    }

    ul.autocomplete li div{
        padding-left: 10px;
        padding-top: 7px;
        padding-right: 10px;
        padding-bottom: 7px;
        border-bottom: 1px solid #E9E9E9;
    }

    ul.autocomplete li div:hover{
        background: #F9F9F9;
    }

    ul.autocomplete li  div span#precio-venta-autocomplete{
        float: right;
    }

    
</style>
    <script type="text/javascript">
    $(function() {
      
      if($("#estado").val()==0){
          $("#paso1").show();
          $("#paso2").hide();
      }
      else if ($("#estado").val()==1){
          $("#paso2").show();
          $("#paso1").hide();
      }
          
          
           $(".btn_cancelar").click(function (){
               url = "<?php echo site_url('auth/logout/')?>"
               window.location = url;
                       
           });
           $(".btn_paso1").click(function(){
              //alert($("#empresa").val());
                if ($("#empresa").val()==""){
                    $("#empresa").attr('placeholder', 'Campo Empresa requerido');
                    $("#empresa").focus();
                    return ;
                }
              
                var url = "<?php echo site_url('miempresa/upd_nombre_empresa/')?>" ;
                $.getJSON(
                   url +'/'+ $("#empresa").val(),
                   function(data){ 
                       if(data='ok'){
                           
                           var url1 = "<?php echo site_url('backend/db_config/upd_estado/')?>" ;
                            $.getJSON(
                                url1 +'/1',
                                function(data){ 
                                    
                                });
                                
                            $("#paso1").hide();
                             $("#paso2").show();
                             $("#estado").val('2');// 0 iniciado 1 nombre empresa 2 listo
                         }
                         else
                         {
                             alert('Error de conexion en base de datos.');
                         }
                   });
              
              
                
           });
           $(".btn_paso2atras").click(function(){
              
                $("#paso2").hide();
                $("#paso1").show();
           });
           
           
           
       });       
    
    </script>
    