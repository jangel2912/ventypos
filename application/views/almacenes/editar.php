<div class="page-header">
    <div class="icon">
        <img alt="Almacenes" class="iconimg"
            src="<?php echo $this->session->userdata('new_imagenes')['titulo_almacen']['original'] ?>">
    </div>
    <h1 class="sub-title"><?php echo custom_lang("sima_edit_product", "Editar Almacén");?></h1>
</div>
<div class="col-md-12">
    <ul class="nav nav-pills">
        <li class="active"><a href="#tab1default" data-toggle="pill">Información general</a></li>
        <li><a href="#tab2default" data-toggle="pill">Información de facturación</a></li>
        <li><a href="#tab3default" data-toggle="pill">Información adicional</a></li>
        <li><a href="#tab4default" data-toggle="pill">Restaurante</a></li>
        <li><a href="#tab5default" data-toggle="pill">Facturación electrónica</a></li>
    </ul>
    <?php echo form_open_multipart("almacenes/editar/".$data['data']['id'], array("id" =>"validate"));?>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="tab1default">
            <input type="hidden" value="<?php echo set_value('id', $data['data']['id']); ?>" name="id" />

            <?php if ($data['data']['miempresa_data']['data']['resolucion_factura_estado'] == 'si' || "1" === set_value('facturacion_electronica', $data['data']['facturacion_electronica'])) { ?>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_name', "NIT");?>:</div>
                <div class="span9"><input type="text" value="<?php echo set_value('NIT', $data['data']['nit']); ?>"
                        name="nit" id="nit" class="f-electronica i-general" />
                    <?php echo form_error('nit'); ?>
                </div>
            </div>

            <?php } ?>
            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_name', "Razon Social");?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('razon_social', $data['data']['razon_social']); ?>"
                        name="razon_social" class="i-general" maxlength="100"/>
                    <?php echo form_error('razon_social'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('nombre', $data['data']['nombre']); ?>"
                        name="nombre" class="i-general" maxlength="150"/>
                    <?php echo form_error('nombre'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Teléfono");?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('telefono', $data['data']['telefono']); ?>"
                        name="telefono" class="i-general" pattern="(?=.*[0-9])[- +()0-9]+$" 
                        oninvalid="this.setCustomValidity('El teléfono tiene un formato incorrecto.')" 
                        oninput="this.setCustomValidity('')"
                        maxlength="45" id="telefono"/>
                    <?php echo form_error('telefono'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Correo electrónico");?>:</div>
                <div class="span9"><input type="email"
                        value="<?php echo set_value('correo_electronico', $data['data']['correo_electronico']); ?>"
                        name="correo_electronico" class="i-general"
                        maxlength="250" id="correo_electronico"/>
                    <?php echo form_error('correo_electronico'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Dirección");?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('direccion', $data['data']['direccion']); ?>"
                        name="direccion" class="i-general" maxlength="45"/>
                    <?php echo form_error('direccion'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Pais");?>:</div>
                <div class="span9">
                    <select name="pais_almacen" id="pais">
                        <?php foreach ($data["paises"] as $pais): ?>
                        <option value="<?= $pais;?>"
                            <?php echo (isset($data["data"]["pais"]) && ($data["data"]["pais"] == $pais))? 'selected' : '';?>>
                            <?= $pais;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Ciudad");?>:</div>
                <div class="span9">
                    <?php echo form_dropdown('provincia', array(), $this->form_validation->set_value('provincia', $data['data']['ciudad']), "id='provincia'");?>
                    <?php echo form_error('meta_diaria'); ?>
                </div>
            </div>
            <?php
                    $band=false;
                    foreach ($data['licenciavencidas'] as $key => $value) {
                        if ($value['id_almacen']==$data['data']['id']) {
                            $band=true;
                        }
                    }
                    if (!$band) {
                        ?>
            <div class="row-form">
                <div class="span12">
                    <?php echo custom_lang('sale_active', "Activo"); ?>:
                    <input name="activo" type="checkbox"
                        value="<?php echo set_value('activo', $data['data']['activo']); ?>"
                        <?php echo "1" == set_value('activo', $data['data']['activo']) ? "checked='checked'" : ""; ?> />
                    <?php echo form_error('activo'); ?>
                </div>
            </div>
            
            
        </div>
        <div class="tab-pane fade" id="tab2default">
            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_name', "Resolución #"); ?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('resolucion_factura', $data['data']['resolucion_factura']); ?>"
                        name="resolucion_factura" maxlength="250"/>
                    <?php echo form_error('resolucion_factura'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Prefijo"); ?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('prefijo', $data['data']['prefijo']); ?>"
                        name="prefijo" class="i-facturacion" maxlength="30"/>
                    <?php echo form_error('prefijo'); ?>
                </div>
            </div>
            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Número de Inicio"); ?>:</div>
                <div class="span9"><input type="number"
                        value="<?php echo set_value('consecutivo', $data['data']['consecutivo']); ?>"
                        name="consecutivo" class="i-facturacion"
                        min="0" max="50000000" id="consecutivo"/>
                    <?php echo form_error('consecutivo'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Numero de Final"); ?>:</div>
                <div class="span9"><input type="number"
                        value="<?php echo set_value('numero_fin', $data['data']['numero_fin']); ?>"
                        name="numero_fin" class="i-facturacion"
                        min="2" max="50000000" id="numero_fin"/>
                    <?php echo form_error('numero_fin'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Fecha Vencimiento"); ?>:</div>
                <div class="span9"><input type="text"
                        value="<?php echo set_value('fecha_vencimiento', $data['data']['fecha_vencimiento']); ?>"
                        name="fecha_vencimiento" class="datepicker i-facturacion" />
                    <?php echo form_error('fecha_vencimiento'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3">
                    <?php echo custom_lang('sima_codigo', "Avisarme cuando llegue al número:"); ?>:</div>
                <div class="span9"><input type="number"
                        value="<?php echo set_value('numero_alerta', $data['data']['numero_alerta']); ?>"
                        name="numero_alerta" id="numero_alerta"
                        min="2" max="50000000" 
                        required/>
                    <?php echo form_error('numero_alerta'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Avisarme cuando falten"); ?>:</div>
                <div class="span9">
                    <select name="fecha_alerta" value="">
                        <option value="7" <?php echo ($data['data']['fecha_alerta'] == 7) ? "selected":"" ?>>7
                            Dias</option>
                        <option value="15" <?php echo ($data['data']['fecha_alerta'] == 15) ? "selected":"" ?>>
                            15 Dias</option>
                        <option value="30" <?php echo ($data['data']['fecha_alerta'] == 30) ? "selected":"" ?>>
                            30 Dias</option>
                    </select>
                    <?php echo form_error('fecha_alerta'); ?>
                </div>

            </div>
        </div>
        <div class="tab-pane fade" id="tab3default">
            <div class="row-form">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Meta Diaria"); ?>:</div>
                <div class="span9">
                    <input
                        type="number" name="meta_diaria" id="meta_diaria"
                        value="<?php echo set_value('meta_diaria', $data['data']['meta_diaria']); ?>"
                    />
                    <?php echo form_error('meta_diaria'); ?>
                </div>
            </div>

            <?php
                $checked=($data['data']['activar_consecutivo_cierre_caja'] == "si") ? 'checked="checked"' : ''; ?>
            <div class="row-form">
                <div class="span12">
                    <?php echo custom_lang('sima_codigo', "Activar Consecutivo Cierre Caja"); ?>:
                    <input type="checkbox"
                        value="<?php echo set_value('activar_consecutivo_cierre_caja', $data['data']['activar_consecutivo_cierre_caja']); ?>"
                        <?= $checked ?> name="activar_consecutivo_cierre_caja" id="activar_consecutivo_cierre_caja" />
                    <?php echo form_error('activar_consecutivo_cierre_caja'); ?>
                </div>
            </div>

            <div class="row-form consecutivo_cierre_caja">
                <div class="span3"><?php echo custom_lang('sima_codigo', "Consecutivo Cierre Caja"); ?>:</div>
                <div class="span9"><input type="number"
                        value="<?php echo set_value('consecutivo_cierre_caja', $data['data']['consecutivo_cierre_caja']); ?>"
                        name="consecutivo_cierre_caja" id="consecutivo_cierre_caja"
                        />
                    <?php echo form_error('consecutivo_cierre_caja'); ?>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab4default">
            <?php } if ($data["tipo_negocio"]=="restaurante") { ?>
            <div class="row-form">
                <div class="span3">
                    <?php echo custom_lang('consecutivo_orden', "Consecutivo Orden Restaurante"); ?>:</div>
                <div class="span9"><input type="number"
                        value="<?php echo set_value('consecutivo_orden_restaurante', $data['data']['consecutivo_orden_restaurante']); ?>"
                        name="consecutivo_orden_restaurante" min="1"/>
                    <?php echo form_error('consecutivo_orden_restaurante'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span3">
                    <?php echo custom_lang('reiniciar_consecutivo_orden', "Reiniciar Consecutivo Orden Restaurante"); ?>:
                </div>
                <div class="span9"><input type="number"
                        value="<?php echo set_value('reiniciar_consecutivo_orden_restaurante', (isset($data['data']['reiniciar_consecutivo_orden_restaurante']) && $data['data']['reiniciar_consecutivo_orden_restaurante'] > 0) ? $data['data']['reiniciar_consecutivo_orden_restaurante'] : 100 ); ?>"
                        name="reiniciar_consecutivo_orden_restaurante" />
                    <?php echo form_error('reiniciar_consecutivo_orden_restaurante'); ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="tab-pane fade" id="tab5default">
        <div class="row-form">
                <div class="span12">
                    Responsable de Iva:
                    <input id="responsable_iva" name="responsable_iva" type="checkbox"
                        value="1"
                        <?php echo "1" == set_value('responsable_iva', $data['data']['responsable_iva']) ? "checked='checked'" : ""; ?>
                        />
                    <?php echo form_error('responsable_iva'); ?>
                </div>
            </div>
            <div class="row-form">
                <div class="span12">
                    <?php echo custom_lang('electronic_invoicing', "Facturación electrónica");?>:
                    <input id="facturacion-electronica" name="facturacion_electronica" type="text"
                        value="<?php echo set_value('facturacion_electronica', $data['data']['facturacion_electronica']); ?>"
                        <?php echo "1" === set_value('facturacion_electronica', $data['data']['facturacion_electronica']) ? "checked='checked'" : ""; ?> />
                    <?php echo form_error('facturacion_electronica'); ?>
                </div>
            </div>
            

            <div id="facturacion-electronica-campos" style="display:none">
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Número de autorización de la DIAN");?>:</div>
                    <div class="span9"><input type="number" id="numero-autorizacion-dian"
                            value="<?php echo set_value('numero_autorizacion_dian', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->numero_autorizacion_dian); ?>"
                            name="numero_autorizacion_dian" class="f-electronica" max="99999999999999999999999999999999999999999999999999"/>
                        <?php echo form_error('numero_autorizacion_dian'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Régimen fiscal"); ?>:</div>
                    <div class="span9">
                        <select name="regimen_fiscal" id="regimen-fiscal">
                            <option value="simple" <?php echo count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : ($data['data']['facturacion_electronica_campos']->regimen_fiscal === 'simple' ? 'selected' : '') ?>>Regimen simple de tributacion</option>
                            <option value="ordinario" <?php echo count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : ($data['data']['facturacion_electronica_campos']->regimen_fiscal === 'ordinario' ? 'selected' : '') ?>>Régimen común</option>
                        </select>
                        <?php echo form_error('regimen_fiscal'); ?>
                    </div>
                </div>
                <?php /*?>
                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Prefijo DIAN");?>:</div>
                    <div class="span9"><input type="text" id="prefijo-dian"
                            value="<?php echo set_value('prefijo_dian', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->prefijo_dian); ?>"
                            name="prefijo_dian" class="f-electronica" maxlength="30"/>
                        <?php echo form_error('prefijo_dian'); ?>
                    </div>
                </div>
                <?php */ ?>
                <div class="row-form">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="span3"><?php echo custom_lang('sima_codigo', "Utiliza Prefijo DIAN");?>:</div>
                            <div class="span9">
                                <input type="text" value='1' <?php (isset($data['data']['facturacion_electronica_campos']->prefijo_dian) && !empty($data['data']['facturacion_electronica_campos']->prefijo_dian)) ? "checked='checked'" : "" ?> name="use_dian_prefix" id="use_dian_prefix"/>
                            </div>
                        </div>
                        <div id="use_dian_prefix_container" class="col-sm-7" style="display: none;">
                            <div class="span3"><?php echo custom_lang('sima_codigo', "Prefijo DIAN");?>:</div>
                            <div class="span7"><input type="text" id="prefijo-dian"
                                    value="<?php echo set_value('prefijo_dian', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->prefijo_dian); ?>"
                                    name="prefijo_dian" class="f-electronica" maxlength="30"/>
                                <?php echo form_error('prefijo_dian'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <a href="http://ayuda.vendty.com/es/articles/3279440-configurar-la-facturacion-electronica-en-vendty">Consulte el siguiente artículo en caso de dudas.</a>
                    </div>
                </div>


                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Consecutivo inicial");?>:</div>
                    <div class="span9"><input type="number" id="consecutivo-desde"
                            value="<?php echo set_value('consecutivo_desde', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->consecutivo_desde); ?>"
                            name="consecutivo_desde" class="f-electronica col-md-12" 
                            min="1" max="99999999"/>
                        <?php echo form_error('consecutivo_desde'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Consecutivo actual");?>:</div>
                    <div class="span9"><input type="number" id="consecutivo-actual"
                            value="<?php echo set_value('consecutivo_actual', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->consecutivo_actual); ?>"
                            name="consecutivo_actual" class="f-electronica col-md-12"
                            min="1" max="99999999"/>
                        <?php echo form_error('consecutivo_actual'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Consecutivo final");?>:</div>
                    <div class="span9"><input type="number" id="consecutivo-hasta"
                            value="<?php echo set_value('consecutivo_hasta', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->consecutivo_hasta); ?>"
                            name="consecutivo_hasta" class="f-electronica col-md-12"
                            min="1" max="99999999"/>
                        <?php echo form_error('consecutivo_hasta'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Fecha inicial");?>:</div>
                    <div class="span9"><input type="date" id="fecha-desde"
                            value="<?php echo set_value('fecha_desde', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->fecha_desde); ?>"
                            name="fecha_desde" class="f-electronica col-md-12" 
                            min="2018-01-01"/>
                        <?php echo form_error('fecha_desde'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Fecha final");?>:</div>
                    <div class="span9"><input type="date" id="fecha-hasta"
                            value="<?php echo set_value('fecha_hasta', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->fecha_hasta); ?>"
                            name="fecha_hasta" class="f-electronica col-md-12"
                            />
                        <?php echo form_error('fecha_hasta'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Observaciones en la factura");?>:</div>
                    <div class="span9">
                        <textarea id="observaciones" name="observaciones" class="f-electronica" maxlength="250" rows="10" cols="50"><?php echo set_value('fecha_hasta', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->observaciones); ?></textarea>
                        <?php echo form_error('observaciones'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Empresa");?>:</div>
                    <div class="span9"><input type="text" id="empresa"
                            value="<?php echo set_value('empresa', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->empresa); ?>"
                            name="empresa" class="f-electronica" maxlength="50"/>
                        <?php echo form_error('empresa'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Cuenta");?>:</div>
                    <div class="span9"><input type="text" id="cuenta"
                            value="<?php echo set_value('cuenta', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->cuenta); ?>"
                            name="cuenta" class="f-electronica" maxlength="50"/>
                        <?php echo form_error('cuenta'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span3"><?php echo custom_lang('sima_codigo', "Usuario");?>:</div>
                    <div class="span9"><input type="text" id="usuario"
                            value="<?php echo set_value('usuario', count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : $data['data']['facturacion_electronica_campos']->usuario); ?>"
                            name="usuario" class="f-electronica" maxlength="50"/>
                        <?php echo form_error('usuario'); ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span12">
                        <?php echo custom_lang('sima_codigo', "Producción"); ?>:
                        <input name="produccion" type="checkbox"
                            value="1"
                            <?php echo count(get_object_vars($data['data']['facturacion_electronica_campos'])) < 1 ? '' : ("1" == set_value('produccion', $data['data']['facturacion_electronica_campos']->produccion) ? "checked='checked'" : ""); ?> />
                        <?php echo form_error('produccion'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toolbar bottom tar">
        <div>
            <button class="btn btn-default" type="button"
                onclick="javascript:location.href='../index'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
            <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
        </div>
    </div>
    </form>
</div>

<div class="social">
    <ul>
        <li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>
    </ul>
</div>
<div id="myModalvideovimeo" class="modal fade">
    <div style="padding:56.25% 0 0 0;position:relative;">
        <iframe id="cartoonVideovimeo"
            src="https://player.vimeo.com/video/266923959?loop=1&color=ffffff&title=0&byline=0&portrait=0"
            style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen
            mozallowfullscreen allowfullscreen></iframe>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#facturacion-electronica').get(0).type = 'checkbox';
    $('#use_dian_prefix').get(0).type = 'checkbox';
    
    $("#pais").change(function() {
        load_provincias_from_pais($(this).val());
    });

    if($("#facturacion-electronica").is(':checked')) {
        $("#facturacion-electronica-campos").show();
    }

    if($("#prefijo-dian").val() != "") {
        $("#use_dian_prefix_container").show();
        $("#use_dian_prefix").prop('checked', true);
    }

    $('#use_dian_prefix').change(function() {
        if($(this).is(":checked")) {
            $("#use_dian_prefix_container").show();
        }
        else {
            $("#use_dian_prefix_container").hide();
            $("#prefijo-dian").val("");
        }
    });

    $("#validate").on('submit', function(e) {
        let ToDate = new Date();
        let UserDate = $("#fecha-hasta").val();
        if (ToDate.getTime() > new Date(UserDate).getTime()) {
            alertPrevent(e, 'La fecha del Consecutivo Final no puede ser de inferior a la actual');
        }
        const num_inicial = parseInt($('#consecutivo').val());
        const num_final = parseInt($('#numero_fin').val());
        const num_alerta = parseInt($('#numero_alerta').val());
        if(num_final < num_inicial) {
            alertPrevent(e, 'El número final no puede ser menor que el número de inicio.');
        }
        if(num_alerta < num_inicial) {
            alertPrevent(e, 'El número de alerta no puede ser menor que el número de inicio.');
        }
        if(num_alerta > num_final) {
            alertPrevent(e, 'El número de alerta debe ser menor o igual al número final.');
        }
        if($("#meta_diaria").val() == "" || parseInt($('#meta_diaria').val()) < 0) {
            alertPrevent(e, 'El campo meta diaria en la pestaña información adicional debe ser un número mayor o igual a cero.');
        }
        if($("#consecutivo_cierre_caja").val() == "" || parseInt($('#consecutivo_cierre_caja').val()) < 0) {
            alertPrevent(e, 'El campo consecutivo de cierre de caja en la pestaña información adicional debe ser un número mayor o igual a cero.');
        }
        $(".i-general").each(function(){
            if($(this).val() == "") {
                alertPrevent(e, 'Faltan campos por llenar en la pestaña Información general.');
            }
        });
        $(".i-facturacion").each(function(){
            if($(this).val() == "") {
                alertPrevent(e, 'Faltan campos por llenar en la pestaña Información facturación.');
            }
        });
        if($("#facturacion-electronica").is(':checked')) {
            $(".f-electronica").each(function(){
                if($(this).val() == "" && $(this)[0].name !== 'prefijo_dian') {
                    alertPrevent(e, 'Debe llenar todos los campos para activar la facturación electrónica.');
                }
            });

            if($("#prefijo-dian").val() == "" && $("#use_dian_prefix").is(":checked")) {
                alertPrevent(e, 'Debe llenar el Prefijo DIAN si la opcion Utiliza Prefijo DIAN esta activa.');
            }

            if(!$("#nit").val().match('(^[0-9]+-{1}[0-9]{1})')) {
                alertPrevent(e, 'Debe digitar el NIT con digito de verificación');
            }
            const date1 = $('#fecha-desde').val();
            const date2 = $('#fecha-hasta').val();
            if(date1 > date2) {
                alertPrevent(e, 'La fecha inicial, no puede ser mayor a la fecha final.');
            }
            const consecutivo1 = parseInt($('#consecutivo-desde').val());
            const consecutivo2 = parseInt($('#consecutivo-hasta').val());
            const consecutivo3 = parseInt($('#consecutivo-actual').val());
            
            if(consecutivo1 > consecutivo3) {
                alertPrevent(e, 'El consecutivo inicial no puede ser mayor al consecutivo actual.');
            }
            if(consecutivo1 > consecutivo2) {
                alertPrevent(e, 'El consecutivo inicial no puede ser mayor al consecutivo final.');
            }
            if(consecutivo3 > consecutivo2) {
                alertPrevent(e, 'El consecutivo actual no puede ser mayor al consecutivo final.');
            }
        }

        if($('input[name="reiniciar_consecutivo_orden_restaurante"]').length > 0) {
            if($('input[name="reiniciar_consecutivo_orden_restaurante"]').val() < 3) {
                alertPrevent(e, 'El campo Reiniciar Consecutivo Orden Restaurante debe ser mayor a 2.');
            }
        }
    });
    function alertPrevent(e, message) {
        swal({
            type: 'error',
            title: 'Lo sentimos',
            text: message
        })
        e.preventDefault();
    }
    $("#facturacion-electronica").change(function() {
        if(this.checked) {
            $("#facturacion-electronica-campos").show();
        } else {
            $("#facturacion-electronica-campos").hide();
        }
    });
    var pais = $("#pais").val();
    if (pais != "") {
        load_provincias_from_pais(pais);
    }
    $('.checker').css('display', 'inline');
});
function load_provincias_from_pais(pais) {
    $.ajax({
        url: "<?php echo site_url("frontend/load_provincias_from_pais")?>",
        data: {
            "pais": pais
        },
        dataType: "json",
        success: function(data) {
            $("#provincia").html('');
            $.each(data, function(index, element) {
                provincia = "<?php echo set_value('provincia', $data["data"]["ciudad"]);?>"
                sel = provincia == element[0] ? "selected='selectted'" : '';
                $("#provincia").append("<option value='" + element[0] + "' " + sel + ">" + element[
                    0] + "</option>");
            });
        }
    });
}
</script>

<style type="text/css">
.nav-pills>li>a {
    color: #4c4c4c;
}
.active>a {
    color: #939598;
    background-color: #5ca745 !important;
    border-bottom-color: #5ca745 !important;
}
input {
    width: 100%;
}
textarea {
  resize: none;
}
input, textarea, select {
    margin-left: 5px;
}
</style>
