<?php 
    if(isset($data["dominio"]) && $data["dominio"] != ""){
        $url_tienda = 'http://'.$data["dominio"];
    }else{
        $url_tienda = 'http://tienda.vendty.com/'.$data["shopname"];
    }

?> 
<style>
    .input_color{max-width:100%; margin-left:0px !important;}
    .panel-tienda ul li{width:100%;background-color: #f5f5f5;border-color: #ddd;}
    .map{width: 100%;padding: 20px;box-sizing: border-box;float: right;text-align: center;max-height: 350px;overflow: hidden;}
    .content-tienda{float: right;padding: 10px;background-color: #5cb85c;color: #fff;border-radius: 5px 5px;}
    .content-tienda a{color:#fff;font-size:17px;font-family:monospace; margin-right:3px;}
    .content-tienda a:hover{text-decoration:none;}
    .content-tienda:hover{opacity:0.8; cursor:pointer; -webkit-transition-duration: 0.5s; }
</style>


<div class="modal fade" tabindex="-1" role="dialog" id="modal-producto-envio">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Crear producto de envio</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="page-header">    
    <div class="icon">
        <img alt="Tienda Virtual" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_tienda_virtual']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("Tienda Virtual", "Tienda Virtual");?></h1>
</div>
<?php if (isset($data['id'])) { ?>
<div class="row-fluid">    
    <div class="col-md-12">
        <div class="block">
            <div class="col-md-6">
            </div>
            <div class="col-md-6 btnizquierda">
                <div class="col-md-2 col-md-offset-10">
                    <a href="<?php echo ($data['activo'] == 1)? $url_tienda : '';?>" target="_blank" data-tooltip="Mi Tienda Virtual">                            
                        <img alt="MI TIENDA VIRTUAL" class="btnimagenes" src="<?php echo $this->session->userdata('new_imagenes')['tienda_virtual_verde']['original'] ?>">                                                           
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!--
<div class="page-header">    
    <?php if (isset($data['id'])) { ?>
        <div class="content-tienda">
            <a href="<?php echo ($data['activo'] == 1)? $url_tienda : '';?>">
                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                MI TIENDA VIRTUAL

            </a>
        </div>
       
    <?php } ?>
</div>-->
<div class="row-fluid">    
    <div class="col-md-12">
        <div class="block">
<div class="title">
    <?php
    $message = $this->session->flashdata('message');
    if (!empty($message)):
        ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <?php
    $message = $this->session->flashdata('message_error');
    if (!empty($message)):
        ?>
        <div class="alert alert-error">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body panel-tienda">
                <!-- Nav tabs -->
                <ul class="col-md-3 nav nav-tabs">
                    <li class="active "><a href="#creartienda" data-toggle="tab">Crear Tienda</a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?> href="#logotienda"  data-toggle="tab"<?php } ?> >
                            Logo de Tienda
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?> href="#posicionamiento" data-toggle="tab" <?php } ?> >
                            Posicionamiento SEO
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#googlemap" data-toggle="tab" <?php } ?> >
                            Georeferencia (Google Maps)
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#redes" data-toggle="tab" <?php } ?> >
                            Redes Sociales
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#slider" data-toggle="tab" <?php } ?> >
                            Slider
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#plantilla" data-toggle="tab" <?php } ?> >
                            Plantilla
                        </a>
                    </li>

                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#formas_pago" data-toggle="tab" <?php } ?> >
                            Formas de Pago
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#quienessomos" data-toggle="tab" <?php } ?> >
                            Quienes Somos / Nosotros
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#terminosCondiciones" data-toggle="tab" <?php } ?> >
                            Términos y condiciones
                        </a>
                    </li>
                    <!--<li>
                        <a <?php if (isset($data['id'])) { ?>  href="#propiedadIntelectual" data-toggle="tab" <?php } ?> >
                            Propiedad Intelectual
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#cambiosDevoluciones" data-toggle="tab" <?php } ?> >
                            Cambios y devoluciones
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#tratamientoDatos" data-toggle="tab" <?php } ?> >
                            Tratamiento de datos
                        </a>
                    </li>-->
                    <!--<li>
                        <a <?php if (isset($data['id'])) { ?>  href="#envio" data-toggle="tab" <?php } ?> >
                            Envios
                        </a>
                    </li>-->
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#productos_destacados" data-toggle="tab" <?php } ?> >
                            Productos destacados
                        </a>
                    </li>
                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#marcas_destacadas" data-toggle="tab" <?php } ?> >
                            Marcas destacadas
                        </a>
                    </li>

                     <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#diseno" data-toggle="tab" <?php } ?> >
                            Opciones de diseño
                        </a>
                    </li>

                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#cobro_envio" data-toggle="tab" <?php } ?> >
                            Cobros por envio
                        </a>
                    </li>

                    <li>
                        <a <?php if (isset($data['id'])) { ?>  href="#configuracion" data-toggle="tab" <?php } ?> >
                            Configuración
                        </a>
                    </li>


                </ul>


            <div class="col-md-8">
               
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="creartienda">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/nuevo", array("id" => "tienda_crear")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sale_active', "Activar disponibilidad"); ?>:</div>
                                    <div class="span9"><input name="activo" type="checkbox"    id="activo" />
                                        <?php echo form_error('activo'); ?>
                                    </div>
                                </div>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_name', "Nombre de tienda"); ?>:</div>
                                        <div class="span9"><input type="text" value="<?php if (isset($data['shopname'])) echo $data['shopname']; ?>" placeholder="" name="shopname" id="shopname"/>
                                            <?php echo form_error('shopname'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_name', "Almacen"); ?>:</div>
                                        <div class="span9">
                                            <?php echo form_dropdown('almacen', $data['almacen'],isset($data['id_almacen'])?$data['id_almacen']:'', "id='almacen'"); ?>
                                            <?php echo form_error('almacen'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_name', "Mostrar stock todos los almacenes"); ?>:</div>
                                        <div class="span9">
                                            <input name="stock_almacen" type="checkbox" id="stock_almacen" <?= (isset($data['stock_almacen']) && $data['stock_almacen'] == 1)?'checked':''?>/>
                                            <?php echo form_error('stock_almacen'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_name', "Correo principal"); ?>:</div>
                                        <div class="span9"><input type="text" value="<?php if (isset($data['correo'])) echo $data['correo']; ?>" placeholder="" name="correo" id="correo"/>
                                            <?php echo form_error('correo'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_name', "Teléfono contacto"); ?>:</div>
                                        <div class="span9"><input type="text" value="<?php if (isset($data['telefono'])) echo $data['telefono']; ?>" placeholder="" name="telefono" id="telefono"/>
                                            <?php echo form_error('telefono'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_name', "Descripción | Contácto"); ?>:</div>
                                        <div class="span9"><textarea name="description" id="description"><?= isset($data['description'])? $data['description']:''; ?></textarea>
                                            <?php echo form_error('shopname'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <?php if (isset($data['id_user'])) { ?>
                                            <button class="btn enab btn-success"  type="button" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                        <?php } else { ?>
                                            <button class="btn enab btn-success" type="button" ><?php echo custom_lang("sima_submit", "Crear"); ?></button>
                                        <?php } ?>
                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="logotienda">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/logo", array("id" => "tienda_logo")); ?>
                                <div class="row-form">
                                    <div class="span6">
                                        <div class="row-form">
                                            <div class="span6"><?php echo custom_lang('sima_avatar', "Logotipo Tienda "); ?>: (250px)<br/>
                                            </div>
                                            <div class="span3">
                                                <div class="input-append file">
                                                    <input type="file"   name="logo" />
                                                    <input type="text" />
                                                    <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                                </div>
                                                <?php if (!empty($data['logo'])): ?>
                                                    <img src="<?php echo $data['url_uploads'].$data['logo']; ?>" alt="logo" width="250px"/>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="row-form">
                                            <div class="span4"><?php echo custom_lang('sima_avatar', "Fav Icon"); ?>: (.ico)<br/>
                                            </div>
                                            <div class="span8">
                                                <div class="input-append file">
                                                    <input type="file" name="favicon" />
                                                    <input type="text" />
                                                    <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                                </div>
                                                <?php if (!empty($data['favicon'])): ?>
                                                    <img src="<?php echo $data['url_uploads'].$data['favicon']; ?>" alt="favicon"/>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-form">
                                   <!-- <div class="span6">
                                        <div class="row-form">
                                            <div class="span6"><?php echo custom_lang('sima_avatar', "Imagen de Fondo "); ?>: (1900 X 1065)<br/>
                                            </div>
                                            <div class="span3">
                                                <div class="input-append file">
                                                    <input type="file"   name="fondo" />
                                                    <input type="text" />
                                                    <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                                </div>
                                                <?php if (!empty($data['fondo'])): ?>
                                                    <img src="<?php echo base_url("uploads/logotienda/" . $data['fondo']); ?>" alt="fondo" width="250px"/>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div>
                                            </br>
                                        </div>
                                        
                                    </div>
                                    <div class="span6">
                                        <div class="row-form">
                                            <div class="span4"><?php echo custom_lang('sima_avatar', "Logo Inferior"); ?>: (150 X 60)<br/>
                                            </div>
                                            <div class="span8">
                                                <div class="input-append file">
                                                    <input type="file" name="logo_inferior" />
                                                    <input type="text" />
                                                    <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                                </div>
                                                <?php if (!empty($data['logo_inferior'])): ?>
                                                    <img src="<?php echo base_url("uploads/logotienda/" . $data['logo_inferior']); ?>" alt="logo_inferior"/>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="toolbar bottom tar">
                                            <div>                                                
                                                <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                                <button class="btn logo_btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                            </div>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                                
                                
                                
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="posicionamiento">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/seo", array("id" => "tienda_seo")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_description', "Descripcion (description)"); ?>:</div>
                                    <div class="span9"><textarea name="seo_description" id="seo_description"><?php if (isset($data['seo_description'])) echo $data['seo_description']; ?></textarea>
                                        <?php echo form_error('seo_description'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_keywords', "Palabras claves (keywords)"); ?>:</div>
                                    <div class="span9"><textarea name="seo_keywords" id="seo_keywords"><?php if (isset($data['seo_keywords'])) echo $data['seo_keywords']; ?></textarea>
                                        <?php echo form_error('seo_keywords'); ?>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>                                        
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn seo_btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="googlemap">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/googlemap", array("id" => "tienda_googlemap")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_google_map', "Enlace Google Map"); ?>:</div>
                                    <div class="span9">
                                        <input type="text" value='<?php
                                        if (isset($data["google_map"])) {
                                            echo $data["google_map"];
                                        }
                                        ?>' placeholder="<iframe src='https://www.google.com/maps/ejemplo'></iframe>" name="google_map" id="google_map"/>
                                        <?php echo form_error('google_map'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <?php if (!empty($data['google_map'])) {  ?> 
                                        <div class="map">
                                            <?php echo $data['google_map'];?>
                                        </div>
                                        <!--<div id="map-canvas" style="width:550px;height:380px; float:right; "></div>-->
                                    <?php } ?>
                                    </br>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>                                                   
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn maps_btn btn-success" id="maps" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="redes">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/redes", array("id" => "tienda_redes")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_facebook', "Enlace Facebook"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['facebook'])) echo $dataRed['facebook']; ?>" placeholder="https://www.facebook.com/ejemplo" name="facebook" id="facebook"/>
                                        <?php echo form_error('redes_facebook'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_twitter', "Enlace Twitter"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['twitter'])) echo $dataRed['twitter']; ?>" placeholder="https://twitter.com/ejemplo" name="twitter" id="twitter"/>
                                        <?php echo form_error('redes_twitter'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_linkedin', "Enlace LinkedIn"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['linkedin'])) echo $dataRed['linkedin']; ?>" placeholder="https://co.linkedin.com/in/ejemplo" name="linkedin" id="linkedin"/>
                                        <?php echo form_error('redes_linkedin'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_drible', "Enlace Dribbble"); ?>:</div>
                                    <div class="span9"> <input   type="text" value="<?php if (isset($dataRed['drible'])) echo $dataRed['drible']; ?>" placeholder="https://dribbble.com/ejemplo" name="drible" id="drible"/>
                                        <?php echo form_error('redes_drible'); ?>
                                    </div>
                                </div>   
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_google', "Enlace Google +"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['google'])) echo $dataRed['google']; ?>" placeholder="https://plus.google.com/+ejemplo" name="google" id="google"/>
                                        <?php echo form_error('redes_google'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_instagram', "Enlace Instagram"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['instagram'])) echo $dataRed['instagram']; ?>" placeholder="https://www.instagram.com/ejemplo/" name="instagram" id="instagram"/>
                                        <?php echo form_error('redes_instagram'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_youtube', "Enlace You Tube"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['youtube'])) echo $dataRed['youtube']; ?>" placeholder="https://www.youtube.com/channel/ejemplo" name="youtube" id="youtube"/>
                                        <?php echo form_error('redes_youtube'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_redes_pinterest', "Enlace Pinterest"); ?>:</div>
                                    <div class="span9"><input type="text" value="<?php if (isset($dataRed['pinterest'])) echo $dataRed['pinterest']; ?>" placeholder="https://www.youtube.com/channel/ejemplo" name="pinterest" id="pinterest"/>
                                        <?php echo form_error('redes_pinterest'); ?>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn redes_btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="plantilla">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/plantilla", array("id" => "plantilla")); ?>
                                <div class="nuevo" >
                                    <div class="row-form">
                                        <div class="span2">Elija plantilla:</div>
                                        <div class="span10">
                                            <div class="row">
                                                <?php
                                                
                                               /* switch ($data["tipo_negocio"]){
                                                    case 'retail' :
                                                        $plantilla_desc = "Plantilla General";
                                                    break;

                                                    case 'restaurante' :
                                                        $plantilla_desc = "Plantilla Restaurante";
                                                    break;

                                                    case 'moda' :
                                                        $plantilla_desc = "Plantilla Moda";
                                                    break;
                                                } */
                                                    
                                                foreach ($plantillas as $key => $plantilla) { ?>
                                                        <div class="layout_container span3" >
                                                        <div class="chekeck no_select" id="<?php echo $plantilla->nombre ?>"></div>
                                                        <div class="col-md-10 col-sm-4 col-xs-4 border">
                                                            <img src="<?php echo $data['url_uploads'].$plantilla->ruta_img ?>" width="160" height="150"/>
                                                        </div>
                                                        <div style="text-align: center" >
                                                            <?php
                                                            echo $plantilla->nombre;
                                                            if ($plantilla->producto_atributo == 1)
                                                                echo "<br>Productos con atributo";
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <?php 
                                                    if (($key + 1) % 4 == 0) {
                                                        echo "</div><div class='row'>";
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <input type="hidden" name="layout" id="layout" value="<?php if (isset($data['layout'])) echo $data['layout'] ?>" />
                                        </div>
                                    </div>
                                </div>                                         
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>                                        
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>                                    
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                       
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="slider">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/slider", array("id" => "slider")); ?>
                                <?php
                                for ($i = 1; $i <= 6; $i++) {
                                    ?>
                                    <div class="row-form">
                                        <div class="span2"><?php echo custom_lang('sima_avatar', "Slider $i:"); ?>:<br/>
                                        </div>
                                        <div class="span3">   
                                            <div class="input-append file">
                                                <input type="file"   name="slider<?php echo $i ?>" />
                                                <input type="text" />
                                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                            </div>   
                                            <?php if (!empty($data['slider' . $i])): ?> 
                                                <img src="<?php echo $data['url_uploads'].$data['slider' . $i]; ?>" alt="slider1"   width="250px"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="span3">
                                            <input type="text" name="link_slider<?php echo $i ?>" placeholder="Url para el slider <?php echo $i;?> "/>
                                        </div>
                                    </div><br>     
                                    <?php
                                }
                                ?>
                                <div class="toolbar bottom tar">

                                    <div>        
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="formas_pago">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/formas_pago", array("id" => "formas_pago")); ?>
                                <div class="accordion ui-accordion ui-widget ui-helper-reset" role="tablist">
                                    <h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-accordion-header-active ui-state-active ui-corner-top" role="tab" id="ui-accordion-1-header-0" aria-controls="ui-accordion-1-panel-0" aria-selected="true" tabindex="0"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>PayU Latam</h3>
                                    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-1-panel-0" aria-labelledby="ui-accordion-1-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block;">
                                        <div class="row-form">
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Merchant Id"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['merchantId'])) echo $data['merchantId']; ?>" placeholder="" name="merchantId" id="merchantId"/>
                                                    <?php echo form_error('merchantId'); ?>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Account Id"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['accountId'])) echo $data['accountId']; ?>" placeholder="" name="accountId" id="accountId"/>
                                                    <?php echo form_error('accountId'); ?>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "ApiKey"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['ApiKey'])) echo $data['ApiKey']; ?>" placeholder="" name="ApiKey" id="ApiKey"/>
                                                    <?php echo form_error('ApiKey'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" role="tab" id="ui-accordion-1-header-2" aria-controls="ui-accordion-1-panel-2" aria-selected="false" tabindex="-1">
                                        <span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>ePayco
                                    </h3>
                                    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" id="ui-accordion-1-panel-2" aria-labelledby="ui-accordion-1-header-2" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
                                        <div class="row-form">
                                            <div class="span2"><?php echo custom_lang('sima_name', "Id Cliente EPayco"); ?>:</div>
                                            <div class="span9"><input type="text" value="<?php if (isset($data['idClienteEPayco'])) echo $data['idClienteEPayco']; ?>" placeholder="" name="idClienteEPayco" id="idClienteEPayco"/>
                                                <?php echo form_error('cuentabancaria'); ?>
                                            </div>
                                        </div>
                                        <div class="row-form">
                                            <div class="span2"><?php echo custom_lang('sima_name', "ApiKey"); ?>:</div>
                                            <div class="span9"><input type="text" value="<?php if (isset($data['apikeyEPayco'])) echo $data['apikeyEPayco']; ?>" placeholder="" name="apikeyEPayco" id="apikeyEPayco"/>
                                                <?php echo form_error('cuentabancaria'); ?>
                                            </div>
                                        </div>
                                        <div class="row-form">
                                            <div class="span2"><?php echo custom_lang('sima_name', "Public Key"); ?>:</div>
                                            <div class="span9"><input type="text" value="<?php if (isset($data['publickeyEPayco'])) echo $data['publickeyEPayco']; ?>" placeholder="" name="publickeyEPayco" id="publickeyEPayco"/>
                                                <?php echo form_error('cuentabancaria'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" role="tab" id="ui-accordion-1-header-3" aria-controls="ui-accordion-1-panel-2" aria-selected="false" tabindex="-1"><span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>Consignación</h3>
                                    <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom" id="ui-accordion-1-panel-3" aria-labelledby="ui-accordion-1-header-2" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
                                        <div class="row-form">
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Numero de Cuenta"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['cuentabancaria'])) echo $data['cuentabancaria']; ?>" placeholder="" name="cuentabancaria" id="cuentabancaria"/>
                                                    <?php echo form_error('cuentabancaria'); ?>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Banco"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['nombrebanco'])) echo $data['nombrebanco']; ?>" placeholder="" name="nombrebanco" id="nombrebanco"/>
                                                    <?php echo form_error('nombrebanco'); ?>
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Tipo de Cuenta"); ?>:</div>
                                                <div class="span9">
                                                    <select name="tipocuenta">
                                                        <option <?php
                                                        if ($data['tipocuenta'] == '1') {
                                                            echo "selected = 'selected'";
                                                        }
                                                        ?>  value="1">Ahorros</option>
                                                        <option <?php
                                                        if ($data['tipocuenta'] == '2') {
                                                            echo "selected = 'selected'";
                                                        }
                                                        ?> value="2">Corriente</option>  
                                                    </select>
                                                    </br><?php echo form_error('ApiKey'); ?> 
                                                </div>
                                            </div>
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Nombre  Titular"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['nombretitular'])) echo $data['nombretitular']; ?>" placeholder="" name="nombretitular" id="nombretitular"/>
                                                    <?php echo form_error('nombretitular'); ?>
                                                </div>

                                            </div>
                                            <div class="row-form">
                                                <div class="span2"><?php echo custom_lang('sima_name', "Correo"); ?>:</div>
                                                <div class="span9"><input type="text" value="<?php if (isset($data['correo'])) echo $data['correo']; ?>" placeholder="" name="correo" id="correo"/>
                                                    <?php echo form_error('correo'); ?>
                                                </div>
                                            </div>
                                        </div>                          
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>                                                                            
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="quienessomos">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/quienessomos", array("id" => "quienessomos")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Imagen 1:"); ?><br/>
                                    </div>
                                    <div class="span9">   
                                        <div class="input-append file">
                                            <input type="file"   name="imagenQuienesSomos1" />
                                            <input type="text" />
                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                        </div>   
                                        <?php if (!empty($data['imagenQuienesSomos1'])): ?> 
                                            <img src="<?php echo $data['url_uploads']. $data['imagenQuienesSomos1']; ?>" alt="imagenQuienesSomos1"   width="250px"/>
                                        <?php endif; ?>
                                    </div>
                                </div>   
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Imagen 2:"); ?><br/>
                                    </div>
                                    <div class="span9">   
                                        <div class="input-append file">
                                            <input type="file"   name="imagenQuienesSomos2" />
                                            <input type="text" />
                                            <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                        </div>
                                        <?php if (!empty($data['imagenQuienesSomos2'])): ?> 
                                            <img src="<?php echo $data['url_uploads']. $data['imagenQuienesSomos2']; ?>" alt="imagenQuienesSomos2"   width="250px"/>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Titulo :"); ?></div>
                                    <div class="span9">
                                        <input name="tituloQuienesSomos" value="<?= isset($data['tituloQuienesSomos'])?$data['tituloQuienesSomos']:'' ?>">
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Descripción :"); ?></div>
                                    <div class="span9">
                                        <textarea name="descripcionQuienesSomos" rows="10" cols="50" style="font-size: 12pt;line-height:normal "><?= isset($data['descripcionQuienesSomos'])?$data['descripcionQuienesSomos']:'' ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>    
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="terminosCondiciones">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/terminosCondiciones", array("id" => "terminosCondiciones")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Titulo :"); ?></div>
                                    <div class="span9">
                                        <input name="terminos_condiciones_titulo" value="<?= isset($data['terminos_condiciones_titulo'])?$data['terminos_condiciones_titulo']:'' ?>">
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Descripción :"); ?></div>
                                    <div class="span9">
                                        <textarea name="terminos_condiciones" rows="10" cols="50" style="font-size: 12pt;line-height:normal "><?= isset($data['terminos_condiciones'])?$data['terminos_condiciones']:'' ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>                                           
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="propiedadIntelectual">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/propiedadIntelectual", array("id" => "propiedadIntelectual")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Titulo :"); ?></div>
                                    <div class="span9">
                                        <input name="propiedad_intelectual_titulo" value="<?= isset($data['propiedad_intelectual_titulo'])?$data['propiedad_intelectual_titulo']:'' ?>">
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Descripción :"); ?></div>
                                    <div class="span9">
                                        <textarea name="propiedad_intelectual" rows="10" cols="50" style="font-size: 12pt;line-height:normal "><?= isset($data['propiedad_intelectual'])?$data['propiedad_intelectual']:'' ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>     
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="cambiosDevoluciones">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/CambiosDevoluciones", array("id" => "CambiosDevoluciones")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Titulo :"); ?></div>
                                    <div class="span9">
                                        <input name="cambios_devoluciones_titulo" value="<?= isset($data['cambios_devoluciones_titulo'])?$data['cambios_devoluciones_titulo']:'' ?>">
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Descripción :"); ?></div>
                                    <div class="span9">
                                        <textarea name="cambios_devoluciones" rows="10" cols="50" style="font-size: 12pt;line-height:normal "><?= isset($data['cambios_devoluciones'])?$data['cambios_devoluciones']:'' ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>                                                                 
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    

                     <div class="tab-pane fade" id="cobro_envio">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/cobro_envio", array("id" => "cobro_envio")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Envio gratis desde:"); ?></div>
                                    <div class="span9">
                                        <input style="border: solid 1px lightgrey;box-sizing: border-box;padding: 5px;border-radius: 5px 5px;" name="envio_gratis_desde" value="<?= isset($data['envio_gratis_desde'])?$data['envio_gratis_desde']:'' ?>" placeholder="$0.00">
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Producto de envio :"); ?></div>
                                    <div class="span9">
                                        <?php if(isset($data['producto_envio']) && $data['producto_envio'] != NULL ){ ?>
                                            <input type="hidden" name="id_producto_envio" value="<?php echo $data['producto_envio']['id']; ?>">
                                            <div>
                                                <p>
                                                    <span style="font-weight:bold;"> Código: </span> <span><?php echo $data['producto_envio']["codigo"];?></span><br>
                                                    <span style="font-weight:bold;"> Nombre: </span><span><?php echo $data['producto_envio']["nombre"];?></span><br>
                                                    <span style="font-weight:bold;"> Valor del envío: </span><span><?php echo '$'.number_format($data['producto_envio']["precio_venta"]);?></span>
                                                </p>
                                            </div>
                                        <?php }else{ ?>
                                            <input type="hidden" name="id_producto_envio" value="0">
                                            <a class="btn btn-success" href="<?php echo site_url('productos/nuevo')?>">Crear producto </a>
                                        <?php } ?>
                                        
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="tratamientoDatos">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/tratamientoDatos", array("id" => "tratamientoDatos")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Titulo :"); ?></div>
                                    <div class="span9">
                                        <input name="tratamiento_datos_titulo" value="<?= isset($data['tratamiento_datos_titulo'])?$data['tratamiento_datos_titulo']:'' ?>">
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Descripción :"); ?></div>
                                    <div class="span9">
                                        <textarea name="tratamiento_datos" rows="10" cols="50" style="font-size: 12pt;line-height:normal "><?= isset($data['tratamiento_datos'])?$data['tratamiento_datos']:'' ?></textarea>
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>                                        
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="envio">                       
                        <div class="block">
                            <div class="data-fluid">
                            <?php echo form_open_multipart("tienda/envio", array("id" => "envio")); ?>
                                <div class="row-form">
                                    <div class="span12">
                                        <label><?php echo custom_lang('sima_activar_envias','Cobro por  envios?') ?></label>
                                        <?php 
                                           echo form_dropdown('s_cobro_envios',array('0'=>'No','1'=>'Si'),$data['cobro_envios']); 

                                        ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3">
                                        Agregar nuevo envio
                                        <a class="agregarEnvio btn btn-default green white">
                                            <div class="icon">
                                                <span class="ico-plus"></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="row-form">
                                    <div class="span3">Nombre</div>
                                    <div class="span3">Valor</div>
                                    <div class="span3">Activo</div>
                                    <div class="span3">Eliminar</div>
                                </div>
                                <?php
                                foreach ($data['envio'] as $e) {
                                    ?>
                                    <div class="row-form">
                                        <div class="span3">
                                            <input type="hidden" name="id[]" value="<?php echo $e->id ?>">
                                            <input type="text" name="nombre[]" value="<?php echo $e->nombre ?>" class="validarEntrada">
                                        </div>
                                        <div class="span3">
                                            <input type="text" name="valor[]" value="<?php echo $e->valor ?>" class="validarEntrada">
                                        </div>
                                        <div class="span3">
                                            <select name="activo[]">
                                                <option value="1" <?php echo ($e->activo == 1) ? "selected" : "" ?>>Si</option>
                                                <option value="0" <?php echo ($e->activo == 1) ? "" : "selected" ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="span3">
                                            <a class="eliminarEnvio btn btn-default red" href="<?php echo site_url('tienda/eliminarEnvio') ?>" data-id="<?php echo $e->id ?>">
                                                <div class="icon">
                                                    <span class="ico-remove"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="row-form">
                                    <div class="span3">
                                        <input type="hidden" name="id[]"/>
                                        <input type="text" name="nombre[]" class="validarEntrada"/>
                                    </div>
                                    <div class="span3">
                                        <input type="text" name="valor[]" class="validarEntrada" value="0"/>
                                    </div>
                                    <div class="span3">
                                        <select name="activo[]">
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="span3">
                                        <a class="eliminarEnvio btn btn-default red" href="<?php echo site_url('tienda/eliminarEnvio') ?>" data-id="0">
                                            <div class="icon">
                                                <span class="ico-remove"></span>
                                            </div>
                                        </a>                                        
                                    </div>
                                </div>
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>  
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Guardar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="productos_destacados">                       
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("tienda/marcar_productos_destacados", array("id" => "f_productos_destacados")); ?>
                                <div class="row-form">
                                    <div class="col-xs-12">
                                        <select id="s_productos_destacados" name="s_productos_destacados[]" multiple="multiple">
                                            <?php foreach ($productos as $key => $value) {
                                                $selected = '';
                                                if($value->destacado_tienda == 1 ){
                                                    $selected ='selected';
                                                }
                                                ?>
                                            <option value="<?php echo $value->id ?>" <?php echo $selected ?>>
                                                <?php echo $value->nombre ?>
                                            </option>    
                                          <?php  } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    </br>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div>  
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar productos destacados"); ?></button>                                                                                   
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>


                    <!-- Marcas destacadas -->
                    <div class="tab-pane fade" id="marcas_destacadas">
                    <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/marcas_destacadas", array("id" => "marca")); ?>
                                <?php
                                for ($i = 1; $i <= 6; $i++) {
                                    ?>
                                    <div class="row-form">
                                        <div class="span2"><?php echo custom_lang('sima_avatar', "Marca $i:"); ?>:<br/>
                                        </div>
                                        <div class="span    3">   
                                            <div class="input-append file">
                                                <input type="file"   name="marca<?php echo $i ?>" />
                                                <input type="text" />
                                                <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                            </div>   
                                            <?php if (!empty($data['marca' . $i])): ?> 
                                                <img src="<?php echo $data['url_uploads'].$data['marca' . $i]; ?>" alt="Marca"   width="250px"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="span6">
                                        Url:
                                            <input type="text" name="link_marca<?php echo $i ?>" placeholder="Url para la marca <?php echo $i;?> " value="<?php echo (!empty($data['link_marca' . $i]))? $data['link_marca' . $i] : ''; ?> "/>
                                        </div>
                                    </div><br>     
                                    <?php
                                }
                                ?>
                                <div class="toolbar bottom tar">

                                    <div>        
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                    <!-- Fin marcas destacadas-->

                        <!-- ajustes de diseño -->
                      <div class="tab-pane fade" id="diseno">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/diseno", array("id" => "tienda_diseno")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_static_menu', "Menu estatico"); ?>:</div>
                                    <div class="span2">
                                        <select class="form-control" name="menu_estatico" id="menu_estatico">
                                            <option value="1" <?php echo (isset($data["menu_estatico"]) && $data["menu_estatico"] == 1)? 'selected' : '';?>>Si</option>
                                            <option value="0" <?php echo (isset($data["menu_estatico"]) && $data["menu_estatico"] == 0)? 'selected' : '';?>>No</option>
                                        </select>
                                        <?php echo form_error('menu_estatico'); ?>
                                    </div>
                                    <div class="span6" style="color:red; font-size:12px;">(Solo aplica para la plantilla "retail")</div>
                                </div>
                                                    
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_background_header', "Color de fondo del menu"); ?>:</div>
                                    <div class="span2">
                                        <input class="input_color" type="color" name="color_fondo_menu" id="color_fondo_menu" value="<?php echo (isset($data["color_fondo_menu"]))? $data["color_fondo_menu"] : '';?>">
                                        <?php echo form_error('color_fondo_menu'); ?>
                                    </div>
                                    <div class="span6" style="color:red; font-size:12px;">(Solo aplica para la plantilla "retail")</div>
                                </div>

                                 <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_color_letter_header', "Color de la letra del menu"); ?>:</div>
                                    <div class="span2">
                                        <input class="input_color" type="color" name="color_letra_menu" id="color_letra_menu" value="<?php echo (isset($data["color_letra_menu"]))? $data["color_letra_menu"] : '';?>">
                                        <?php echo form_error('color_letra_menu'); ?>
                                    </div>
                                    <div class="span6" style="color:red; font-size:12px;">(Solo aplica para la plantilla "retail")</div>
                                </div>

                                 <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_background_footer', "Color de fondo del pie de página"); ?>:</div>
                                    <div class="span2">
                                        <input class="input_color" type="color" name="color_fondo_pie_pagina" id="color_fondo_pie_pagina" value="<?php echo (isset($data["color_fondo_pie_pagina"]))? $data["color_fondo_pie_pagina"] : '';?>">
                                        <?php echo form_error('color_fondo_pie_pagina'); ?>
                                    </div>
                                    <div class="span6" style="color:red; font-size:12px;">(Solo aplica para la plantilla "retail")</div>
                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_color_letter_footer', "Color de la letra del pie de página"); ?>:</div>
                                    <div class="span2">
                                        <input class="input_color" type="color" name="color_letra_pie_pagina" id="color_letra_pie_pagina" value="<?php echo (isset($data["color_letra_pie_pagina"]))? $data["color_letra_pie_pagina"] : '';?>">
                                        <?php echo form_error('color_letra_pie_pagina'); ?>
                                    </div>
                                    <div class="span6" style="color:red; font-size:12px;">(Solo aplica para la plantilla "retail")</div>
                                </div>
                                
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_static_menu', "Imagen Parallax"); ?>:</div>
                                   <div class="span9">  
                                       <div class="input-append file">
                                           <input type="file"   name="imagen_parallax" />
                                           <input type="text" />
                                           <button class="btn btn-success" type="button"><?php echo custom_lang('sima_search', "Buscar"); ?></button>
                                       </div>   
                                       <?php if (!empty($data['imagen_parallax'])): ?> 
                                           <img src="<?php echo $data['url_uploads']. $data['imagen_parallax']; ?>" alt="imagen_parallax"   width="250px"/>
                                       <?php endif; ?>
                                   </div>
                                </div>
                                 
                                
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Texto parallax :"); ?></div>
                                    <div class="span9">
                                        <textarea name="texto_parallax" rows="10" cols="50" style="font-size: 12pt;line-height:normal "><?= isset($data['texto_parallax'])?$data['texto_parallax']:'' ?></textarea>
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Texto Botón Parallax :"); ?></div>
                                    <div class="span9">
                                        <input name="texto_boton_parallax" value="<?= isset($data['texto_boton_parallax'])?$data['texto_boton_parallax']:'' ?>">
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_avatar', "Link Parallax :"); ?></div>
                                    <div class="span9">
                                        <input name="link_parallax" value="<?= isset($data['link_parallax'])?$data['link_parallax']:'' ?>">
                                    </div>
                                </div>

                                <div class="toolbar bottom tar">
                                    <div>                                        
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn seo_btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                        


                      <div class="tab-pane fade" id="configuracion">
                        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open_multipart("tienda/configuracion", array("id" => "tienda_configuracion")); ?>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_color_letter_footer', "Nombre del dominio:"); ?>:</div>
                                    <div class="span4">
                                        <input class="dominio_configuracion" type="text" name="dominio_configuracion" id="dominio_configuracion" value="<?php echo (isset($data["dominio"]))? $data["dominio"] : '';?>">
                                        <?php echo form_error('dominio_configuracion'); ?>
                                    </div>
                                </div>
                                
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_static_menu', "Mostrar productos destacados"); ?>:</div>
                                    <div class="span2">
                                        <select class="form-control" name="ch_productos_destacados" id="ch_productos_destacados">
                                            <option value="1" <?php echo (isset($data["productos_destacados"]) && $data["productos_destacados"] == 1)? 'selected' : '';?>>Si</option>
                                            <option value="0" <?php echo (isset($data["productos_destacados"]) && $data["productos_destacados"] == 0)? 'selected' : '';?>>No</option>
                                        </select>
                                        <?php echo form_error('ch_productos_destacados'); ?>
                                    </div>
                                </div>

                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_static_menu', "Mostrar productos recientes"); ?>:</div>
                                    <div class="span2">
                                        <select class="form-control" name="ch_productos_recientes" id="ch_productos_recientes">
                                            <option value="1" <?php echo (isset($data["productos_recientes"]) && $data["productos_recientes"] == 1)? 'selected' : '';?>>Si</option>
                                            <option value="0" <?php echo (isset($data["productos_recientes"]) && $data["productos_recientes"] == 0)? 'selected' : '';?>>No</option>
                                        </select>
                                        <?php echo form_error('ch_productos_recientes'); ?>
                                    </div>
                                </div>

                                <!--<div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_seo_color_letter_footer', "Formas de pago activas"); ?>:</div>
                                    <div class="span9">
                                        <div class="span2">
                                            contra-entrega
                                            <input class="contraentrega" type="checkbox" name="contraentrega" id="contraentrega" <?php echo (isset($data["contraentrega"]))? 'checked': '';?>>
                                        </div>

                                        <div class="span2">
                                            consignación
                                        <input class="consignacion" type="checkbox" name="consignacion" id="consignacion" <?php echo (isset($data["consignacion"]))? 'checked': '';?>>
                                     
                                        </div>
                                        
                                    </div>
                                </div>-->

                                <div class="toolbar bottom tar">
                                    <div>                                        
                                        <button class="btn btn-default"   type="button" onclick="javascript:location.href = '<?php echo base_url() ?>index.php/frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar"); ?></button>
                                        <button class="btn seo_btn btn-success" type="submit" ><?php echo custom_lang("sima_submit", "Actualizar"); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            </div>
            <!-- /.panel -->
        </div>
    </div>
    </div>

    <script type='text/javascript' src="<?php echo base_url(); ?>public/js/plugins/multiselect/jquery.multi-select.min.js"></script> 

    <script type="text/javascript">

        var map;

        function initialize() {

            var cadena = $("#google_map").val();

            // var str = "Hello world, welcome to the universe.";

            var n = cadena.indexOf("@");

            var n2 = cadena.indexOf("z/");


            var sub = cadena.substring(n + 1, n2 - 2);

            var res = sub.split(",");

            var lat = res[0];
            var lon = res[1];
            //  alert(lat);
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: 8,
                center: {lat: -34.397, lng: 150.644}
            });
        }

        var layout = "";

<?php if (isset($data['id_user'])) { ?>

    <?php if ($data['activo'] == 1) { ?>

                $(".nuevo").show();


               //google.maps.event.addDomListener(window, 'load', initialize);
                $("#activo").attr('checked', 'checked');

                layout = "<?php echo $data['layout'] ?>";

                $.each($(".chekeck"), function (key, value) {

                    $(value).removeClass("select_check");

                });

                $("#" + layout).addClass('select_check');
    <?php } else { ?>

                layout = "<?php echo $data['layout'] ?>";
                //alert(layout);

                $.each($(".chekeck"), function (key, value) {

                    $(value).removeClass("select_check");
                });

                $("#" + layout).addClass('select_check');

                $(".enab").attr('disabled', 'disabled');

                $(".nuevo").hide();

    <?php } ?>

<?php } else { ?>

            $(".enab").attr('disabled', 'disabled');

            $(".nuevo").hide();

<?php } ?>

        $(function () {
            $("#maps").click(function () {

                var cadena = $("#google_map").val();

                // var str = "Hello world, welcome to the universe.";

                var n = cadena.indexOf("@");

                var n2 = cadena.indexOf("z/");

                //alert(n);

                //alert(n2);

                var sub = cadena.substring(n + 1, n2 - 2);

                var res = sub.split(",");

                var lat = res[0];
                var lon = res[1];

                //alert(lat);

                //alert(lon);

                //  initialize(lat,lon);
            });

            $("#activo").click(function () {

                if ($("#activo").is(':checked')) {

                    $(".enab").removeAttr('disabled');

                    $(".nuevo").show();
                } else
                    $(".nuevo").hide();

            });



            $(".chekeck").click(function () {

                obj = $(this);
                $.each($(".chekeck"), function (key, value) {

                    $(value).removeClass("select_check");

                });

                $(this).addClass("select_check");
                var id = $(this).attr("id");

                $("#layout").val(id);

            });





            $(".enab").click(function () {

                var check = true;

                if ($("#shopname").val() == "") {

                    $("#shopname").addClass("red");

                    check = false;

                }

                var exist = false;

                $.each($(".chekeck"), function (key, value) {

                    if ($(value).hasClass("select_check"))
                        exist = true;

                });

                if (check) {

                    //alert("si");

                    $("#tienda_crear").submit();
                }
            });

            //multiselect para productos destacados
            $('#s_productos_destacados').multiSelect({
                selectableHeader: "<div class='custom-header'>Lista de productos</div>",
                selectionHeader: "<div class='custom-header'>Selection items</div>",
                selectableFooter: "<div class='custom-header'>Selectable footer</div>",
                selectionFooter: "<div class='custom-header'>Selection footer</div>"
            });
        });


//Eliminar envio
        $(document).on('click', 'a.eliminarEnvio', function (event) {
            event.preventDefault();
            if (confirm("¿Esta seguro de eliminar este envio?"))
            {
                var $this = $(this);
                $.post
                        (
                                $this.attr("href"),
                                {'id': $this.attr('data-id')},
                        function (data)
                        {
                            if (data.resp == 1)
                            {
                                alert("El envio ha sido eliminado correctamente");
                                $this.parents("div.row-form").eq(0).remove();
                            }
                        }, 'json'
                                );
            }
        });

//Agregar nuevo envio
        $(document).on('click', 'a.agregarEnvio', function (event) {
            event.preventDefault();
            var agregar = true;
            $('form#envio').find('.validarEntrada').each(function (i, e)
            {
                if ($(e).val() == "")
                {
                    agregar = false;
                    return false;
                }
            });
            if (agregar)
            {
                var html = '<div class="row-form">' +
                        '<div class="span3">' +
                        '<input type="hidden" name="id[]"/>' +
                        '<input type="text" name="nombre[]"/>' +
                        '</div>' +
                        '<div class="span3">' +
                        '<input type="text" name="valor[]"/>' +
                        '</div>' +
                        '<div class="span3">' +
                        '<select name="activo[]">' +
                        '<option value="1">Si</option>' +
                        '<option value="0">No</option>' +
                        '</select>' +
                        '</div>' +
                        '<div class="span3">' +
                        '<a class="eliminarEnvio btn btn-default red white" href="<?php echo site_url('tienda/eliminarEnvio') ?>" data-id="0">' +
                        '<div class="icon">' +
                        '<span class="ico-remove"></span>' +
                        '</div>' +
                        '</a>' +
                        '</div>' +
                        '</div>',
                        ultimo = parseInt($('form#envio').find('.row-form').length) - 1;
                $('form#envio').find('.row-form').eq(ultimo).after(html);
            } else
            {
                alert("Complete los campos faltantes");
            }
        });

    </script>