
<style>

    .body .content .btn.btn-warning{
        background: #b75050 !important;
    }

    div.radio{
        float: left;
        margin-left: 20px;
        display:block !important;
    }

    .example-wrap{
        font-weight: 300;
        line-height: 16px;
        float: left;
    }

    .example-wrap label{
        float: left;
        line-height: 15px;
        cursor: pointer;
    }


    .example-wrap > div{
        height: 25px;
    }
    .div_datos_mexico{
      display: none;
    }

</style>

<div class="page-header">

    <div class="icon">

        <span class="ico-cogs"></span>

    </div>

    <h1><?php echo custom_lang("Configuracion", "Configuraci&oacute;n");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_company', "Configurar Empresa");?></h2>

    </div>

</div>
<div class="row-fluid">
<?php $message = $this->session->flashdata('messagemiempresa');
     if(!empty($message)){?>
        <div class="alert alert-success">
            <?php echo $message;?>
        </div>
<?php } ?>
</div>
<div class="row-fluid">
<?php echo form_open_multipart("miempresa/index", array("id" =>"validate"));?>

            <input type='hidden' name='sistema' value='Pos'>

<div class="data-fluid tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#general" data-toggle="tab">General</a></li>
        <li><a href="#personalizacion" data-toggle="tab">Personalización</a></li>
        <li><a href="#funcionalidades" data-toggle="tab">Funcionalidades</a></li>
        <li><a href="#alertas" data-toggle="tab">Alertas</a></li>
        <li><a href="#monedas" data-toggle="tab">Monedas</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="general">
        <div class="span6">
            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_name', "Nombre");?>:</div>
                <div class="span8"><input type="text"  value="<?php echo set_value('nombre', $data['data']['nombre']); ?>" placeholder="" name="nombre" />
                        <?php echo form_error('nombre'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_name', "Documento");?>:</div>
                <div class="span8"><input type="text"  value="<?php echo set_value('documento', $data['data']['documento']); ?>" placeholder="" name="documento" />
                        <?php echo form_error('documento'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_identificacion_empresa', "Nit");?>:</div>
                <div class="span8"><input type="text"  value="<?php echo set_value('nit', $data['data']['nit']); ?>" placeholder="" name="nit" />
                        <?php echo form_error('nit'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_resolution', "Resolución de factura");?>:</div>
                <div class="span8"><input type="text" value="<?php echo set_value('resolucion', $data['data']['resolucion']); ?>" name="resolucion" placeholder="Resolución de factura"/>
                    <?php echo form_error('resolucion'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_contact_name', "Nombre del contacto");?>:</div>
                <div class="span8"><input type="text" value="<?php echo set_value('contacto', $data['data']['contacto']); ?>" name="contacto" placeholder="Nombre del contacto"/>
                    <?php echo form_error('contacto'); ?>
                </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_email', "Correo electr&oacute;nico");?>:</div>
                <div class="span8"><input type="text" value="<?php echo set_value('email', $data['data']['email']); ?>" name="email" placeholder="Correo electr&oacute;nico"/>
                    <?php echo form_error('email'); ?>
                </div>
            </div>
            <div class="row-form">

                <div class="span4"><?php echo custom_lang('sima_addres', "Direcci&oacute;n");?>:</div>

                <div class="span8">
                  <input type="text" value="<?php echo set_value('direccion', $data['data']['direccion']); ?>" name="direccion" placeholder="Direcci&oacute;n"/>

                    <?php echo form_error('direccion'); ?>

                </div>
            </div>

            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_numero_exterior_direccion', "Numero Exterior"); ?>:
              </div>
              <div class="span8">
                <input type="text" name="t_num_exterior" id="t_num_exterior" value="<?php echo set_value('t_num_exterior',@$data['data']['num_exterior']) ?>" >
              </div>
            </div>
            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_numero_interior_direccion', "Numero Interior");?>:
              </div>
              <div class="span8">
                <input type="text" name="t_num_interior" id="t_num_interior" value="<?php echo set_value('t_num_interior',@$data['data']['num_interior']) ?>">
              </div>
            </div>
            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_colonia', "Colonia");?>:
              </div>
              <div class="span8">
                <input type="text" name="t_colonia" id="t_colonia" value="<?php echo set_value('t_colonia',@$data['data']['colonia']) ?>">
              </div>
            </div>
            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_localidad', "Localidad");?>:
              </div>
              <div class="span8">
                <input type="text" name="t_localidad" id="t_localidad" value="<?php echo set_value('t_localidad',@$data['data']['localidad']) ?>">
              </div>
            </div>


            <div class="row-form">

                <div class="span4"><?php echo custom_lang('sima_phone', "Tel&eacute;fono");?>:</div>

                <div class="span8"><input type="text" value="<?php echo set_value('telefono', $data['data']['telefono']); ?>" name="telefono" placeholder="Tel&eacute;fono"/>

                    <?php echo form_error('telefono'); ?>

                </div>

            </div>

            </div>
            <div class="span6">



                <div class="row-form">

                <div class="span4"><?php echo custom_lang('sima_fax', "Fax");?>:</div>

                <div class="span8"><input type="text" value="<?php echo set_value('Fax', $data['data']['fax']); ?>" name="fax" placeholder="Fax"/>

                    <?php echo form_error('fax'); ?>

                </div>

            </div>

            <div class="row-form">

                <div class="span4"><?php echo custom_lang('sima_web', "Web");?>:</div>

                <div class="span8"><input type="text" value="<?php echo set_value('web', $data['data']['web']); ?>" name="web" placeholder="Web"/>

                    <?php echo form_error('web'); ?>

                </div>

            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_money', "Zona horaria");?>:</div>
                <div class="span8">
                  <select name="zona_horaria" id="zona_horaria" data-value="<?php echo set_value('zona_horaria', $data['data']['zona_horaria']) ?>">
                    <option value="">seleccionar</option>
                    <?php
                        foreach ($data['data']['timezones'] as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                    ?>
                  </select>
                </div>
            </div>

            <div class="row-form">
                <div class="span4"><?php echo custom_lang('sima_pais', "Pais");?>:</div>
                <div class="span8">
                    <select name="pais" id="pais" data-value="<?php echo set_value('pais', $data['data']['pais']) ?>">
                    <?php
                        foreach ($data['data']['paises'] as $key => $value) {
                            echo '<option value="'.$key.'">'.$value.'</option>';
                        }
                    ?>
                  </select>

                </div>
            </div>
            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_estado', "Estado");?>:
              </div>
              <div class="span8">
                <input type="text" name="t_estado" id="t_estado" value="<?php echo set_value('t_estado',@$data['data']['estado']) ?>">
              </div>
            </div>
            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_municipio', "Municipio");?>:
              </div>
              <div class="span8">
                <input type="text" name="t_municipio" id="t_municipio" value="<?php echo set_value('t_municipio',@$data['data']['municipio']) ?>">
              </div>
            </div>
            <div class="row-form div_datos_mexico">
              <div class="span4">
                <?php echo custom_lang('sima_codigo_postal', "Codigo Postal");?>:
              </div>
              <div class="span8">
                <input type="text" name="t_codigo_posta" id="t_codigo_postal" value="<?php echo set_value('t_codigo_posta',@$data['data']['codigo_postal']) ?>">
              </div>
            </div>
          </div>
        </div>




        <div class="tab-pane" id="personalizacion">
            <div class="span6">
                <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_modules', "Tipo de venta");?>:</div>
                    <div class="span8"><?php echo form_dropdown("tipo_factura", array('estandar' => 'POS', 'clasico' => 'Servicios') , set_value('tipo_factura', $data['data']['tipo_factura']), "id='tipo_factura'"); ?>
                        <?php echo form_error('tipo_factura'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_plantilla', "Plantilla de factura");?>:</div>
                    <div class="span8">
                        <?php
                            echo form_dropdown("plantilla_pos",
                            array(
                                'ticket' => 'Ticket (Tirilla)',
                                'ticket_2' => 'Ticket plus (Tirilla) ',
                                'ticket_58N' => 'Ticket 58mm (Tirilla) ',
                                'ticket_58N3' => 'Ticket 58mm (Tirilla) Información Cliente',
                                'ticket_cafeterias' => 'Ticket Cafetería (Tirilla)',
                                '_imprime_ticket_cafeterias_decimales' => 'Ticket Cafetería Decimales (Tirilla)',
                                'ticket_internacional' => 'Ticket Internacional (Tirilla)',
                                'ticket_internacional_precioiva' => 'Ticket Internacional Precio con IVA (Tirilla)',
                                'ticket_promocion' => 'Ticket Promoción (Tirilla)',
                                'ticket_promocion_nuevo' => 'Ticket Promoción Nuevo (Tirilla)',
                                'ticket_promocion_decimal' => 'Ticket Promoción Decimal (Tirilla)',
                                'general' => 'Estándar (Carta)',
                                'general_2' => 'Estándar 2 (Carta)',
                                'moderna' => 'Moderna (Media carta)',
                                'moderna_izq' => 'Moderno Logo Izq (Media carta)',
                                'moderna_izq_discriminado_iva' => 'Moderno Logo Izq Discriminando Iva (Media carta)',
                                'moderna_codibarras' => 'Codigo de Barras (Media carta)',
                                'moderna_logo_redondo' => 'Moderna logo redondo (Media carta)',
                                'moderna_ingles' => 'Moderna en inglés (Carta)',
                                'moderna_completa_ingles' => 'Clásica en inglés (Carta)',
                                'modelo_factura_clasica' => 'Factura Clásica',
                                'modelo_factura_clasica2' => 'Factura Clásica Impuestos Discriminados',
                                'modelo_impresora_factura' => 'Factura Impresora (Matricial)',
                                'ticket_productos_atributos' => 'Ticket producto con atributos (Tirilla)',
                                'ticket_atributos_nuevo' => 'Ticket producto nuevo atributo (Tirilla)',
                                'ticket_distribuidor' => 'Ticket Distribuidor',
                                'ticket_dist_att' => 'Ticket Distribuidor Atributos',
                                'ticket_base_impuesto' => 'Ticket Base Impuesto (Tirilla)'
                            ),
                            set_value('plantilla', $data['data']['plantilla']), " id='pos'");
                        ?>
                        <?php echo form_error('plantilla'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_plantilla', "Plantilla de cotización");?>:</div>
                    <div class="span8">
                        <?php
                            echo form_dropdown("plantilla_cotizacion", array('Estandar' => 'Estandar','Clasico' => 'Clasico', 'Moderno' => 'Moderno', 'moderna_completa_ingles' => 'Clásica en inglés (Carta)'), set_value('plantilla_cotizacion', $data['data']['plantilla_cotizacion']), " id='cotizacion'");
                        ?>
                        <?php //echo form_error('plantilla'); ?>
                    </div>
                </div>
                <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_plantilla', "Plantilla orden compra");?>:</div>
                    <div class="span8">
                    <?php
                    echo form_dropdown("plantilla_orden_compra", array('Estandar' => 'Estandar','Detallado' => 'Detallado'), set_value('plantilla_orden_compra', $data['data']['plantilla_orden_compra']), " id='cotizacion'");
                    ?>
                    </div>
                </div>

                <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_plantilla', "Plantilla General");?>:</div>
                    <div class="span8">
                    <?php
                    echo form_dropdown("plantilla_general", array('media_carta' => 'Media Carta','tirilla' => 'Tirilla'), set_value('plantilla_general', $data['data']['plantilla_general']), " id='cotizacion'");
                    ?>
                    </div>
                </div>

                    <div class="row-form">

                    <div class="span4">Consecutivo general:</div>

                    <div class="span8">

                        <?php

                        echo form_dropdown("numero",
                        array('no' => 'no','si' => 'si'),
                        set_value('numero', $data['data']['numero']), " id='numero'");

                        ?>

                        <?php //echo form_error('plantilla'); ?>

                    </div>

                </div>
                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('paypal_email', "Titulo del documento");?>:</div>

                    <div class="span8"><input type="text" value="<?php echo set_value('titulo_venta', $data['data']['titulo_venta']); ?>" name="titulo_venta" placeholder=""/>

                        <?php echo form_error('paypal_email'); ?>

                    </div>

                </div>
                </div>
                <div class="span6">
                <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_avatar', "Logotipo");?>:<br/>
                    </div>
                    <div class="span8">
                        <div class="span6" style="margin-bottom: 0px !Important;">
                            <?php if(!empty($data['data']['logotipo'])): ?>
                            <ul class="thumbnails">
                            <li class="span12">
                                <a class="thumbnail">
                                <img src="<?php echo base_url("uploads/".$data['data']['logotipo']);?>" alt="logotipo" height="100%" width="100%"/>
                                </a>
                            </li>
                            </ul>
                            <?php endif;?>
                        </div>
                        <div class="span12">
                        <div class="input-append file">

                            <input type="file" name="logotipo"/>

                            <button class="btn" type="button"><?php echo custom_lang('sima_search', "Buscar en Mi PC");?></button>

                        </div>
                        <span class="bottom">Archivos permitidos : JPG/JPEG/PNG/GIF | Peso maximo: 1MB</span>
                        <?php echo $data['data']['upload_error']; ?>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        <div class="tab-pane" id="funcionalidades">
            <div class="span6">

                <div class="row-form">

                    <div class="span4">Filtro de ciudades:</div>

                    <div class="span8">

                        <?php

                          echo form_dropdown("filtro_ciudad",
                          array('no' => 'no','si' => 'si'),
                           set_value('filtro_ciudad', $data['data']['filtro_ciudad']), " id='filtro_ciudad'");

                        ?>

                        <?php //echo form_error('plantilla'); ?>

                    </div>

                </div>

                <div class="row-form">

                   <div class="span4">Sobrecosto:</div>

                   <div class="span8">

                       <?php

                         echo form_dropdown("sobrecosto",
                         array('no' => 'no','si' => 'si'),
                          set_value('sobrecosto', $data['data']['sobrecosto']), " id='sobrecosto'");

                       ?>

                       <?php //echo form_error('plantilla'); ?>

                   </div>

               </div>

                <div class="row-form">

                    <div class="span4">Sobrecosto todos los prodcutos:</div>

                    <div class="span8">

                        <?php
                          echo form_dropdown("sobrecosto_todos",
                          array('0' => 'no','1' => 'si'),
                          set_value('sobrecosto_todos', $data['data']['sobrecosto_todos'] ));
                        ?>

                    </div>

                </div>

                <input type="hidden" name="multiples_formas_pago" value="si">
                <!--<div class="row-form">

                      <div class="span4">Multiples formas de pago:</div>

                      <div class="span8">

                          <?php

                            echo form_dropdown("multiples_formas_pago",
                            array('no' => 'no','si' => 'si'),
                             set_value('multiples_formas_pago', $data['data']['multiples_formas_pago']), " id='multiples_formas_pago'");

                          ?>

                          <?php //echo form_error('plantilla'); ?>

                      </div>

                  </div>-->

                <div class="row-form">

                      <div class="span4">Vendedor o usuario:</div>

                      <div class="span8">

                          <?php

                            echo form_dropdown("vendedor_impresion",
                            array('1' => 'Vendedor', '2' => 'Usuario', '3' => 'Ambos'),
                             set_value('vendedor_impresion', $data['data']['vendedor_impresion']), " id='vendedor_impresion'");

                          ?>

                          <?php //echo form_error('plantilla'); ?>

                      </div>

                  </div>
                <div class="row-form">
                  <div class="span4"><?php echo custom_lang('sima_vendedores', "Multiples vendedores");?>:</div>
                  <div class="span8">
                    <select name="multiples_vendedores" id="multiples_vendedores" data-value="<?php echo set_value('multiples_vendedores', $data['data']['multiples_vendedores']) ?>">
                      <option value="">seleccionar</option>
                      <option value="0">no</option>
                      <option value="1">si</option>
                    </select>
                  </div>
              </div>

                <div class="row-form">

                      <div class="span4">Aperturar de caja para vender:</div>

                      <div class="span8">

                          <?php

                            echo form_dropdown("valor_caja",
                            array('si' => 'si','no' => 'no'),
                             set_value('valor_caja', $data['data']['valor_caja']), " id='valor_caja'");

                          ?>

                          <?php //echo form_error('plantilla'); ?>

                      </div>

                  </div>

                <div class="row-form">

                      <div class="span4">Cierre de caja automático:</div>

                      <div class="span8">

                          <?php

                            echo form_dropdown("cierre_automatico",
                            array('0' => 'no','1' => 'si'),
                             set_value('cierre_automatico', $data['data']['cierre_automatico']), " id='cierre_automatico'");

                          ?>


                      </div>

                  </div>

            </div>
            <div class="span6">

                <div class="row-form">

                    <div class="span4"> Tienda Electronica:</div>

                    <div class="span8">

                        <?php

                          echo form_dropdown("etienda",
                          array('no' => 'no','si' => 'si'),
                           set_value('etienda', $data['data']['etienda']), " id='etienda'");

                        ?>

                        <?php //echo form_error('plantilla'); ?>

                    </div>

                </div>

                 <div class="row-form">

                    <div class="span4"> Comanda :</div>

                    <div class="span8">

                        <?php

                          echo form_dropdown("comanda",
                          array('no' => 'no','si' => 'si'),
                           set_value('comanda', $data['data']['comanda']), " id='comanda'");

                        ?>

                        <?php //echo form_error('plantilla'); ?>

                    </div>

                </div>

                 <div class="row-form">
                    <div class="span4"><?php echo custom_lang('sima_mesas','Facturar con mesas') ?></div>
                    <div class="span8">
                        <?php

                          echo form_dropdown("facturar_mesas",
                          array('no' => 'no','si' => 'si'),
                           set_value('facturar_mesas', $data['data']['facturar_mesas']), " id='facturar_mesas'");

                        ?>
                    </div>
                </div>

                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('sima_money', "NIT/Resolución de factura (Almacen)");?>:</div>

                    <div class="span8">
                        <?php

                          echo form_dropdown("resolucion_factura_estado",
                          array('no' => 'no','si' => 'si'),
                           set_value('resolucion_factura_estado', $data['data']['resolucion_factura_estado']));

                        ?>
                        <?php echo form_error('moneda'); ?>

                    </div>

                </div>

                <div class="row-form">

                   <div class="span4">Redondear Precios:</div>

                   <div class="span8">

                       <?php

                         echo form_dropdown("redondear_precios",
                         array('0' => 'no','1' => 'si'),
                          set_value('redondear_precios', $data['data']['redondear_precios']), " id='redondear_precios'");

                       ?>

                       <?php //echo form_error('plantilla'); ?>

                   </div>

               </div>

                <!--<div class="row-form">

                    <div class="span4">Clientes Activar Cartera:</div>

                    <div class="span8">

                        <?php
                          echo form_dropdown("clientes_cartera",
                          array('0' => 'no','1' => 'si'),
                          set_value('clientes_cartera', $data['data']['clientes_cartera'] ));
                        ?>

                    </div>

                </div>-->
                <input type="hidden" value="clientes_cartera" value="0">

                <div class="row-form" style="display: none">

                   <div class="span4">Precios por almacén:</div>

                   <div class="span8">

                       <?php

                         echo form_dropdown("precio_almacen",
                         array('0' => 'no','1' => 'si'),
                          set_value('precio_almacen', $data['data']['precio_almacen']), " id='precio_almacen'");

                       ?>

                       <?php //echo form_error('plantilla'); ?>

                   </div>

                </div>

                <div class="row-form" >
                   <div class="span4">Costo Promedio:</div>
                   <div class="span8">
                       <?php
                         echo form_dropdown("costo_promedio",
                         array('0' => 'no','1' => 'si'),
                          set_value('costo_promedio', $data['data']['costo_promedio']), " id='costo_promedio'");
                       ?>
                   </div>
               </div>

                <div class="row-form">

                    <div class="span4">Orden de compra:</div>

                    <div class="span8">

                        <?php
                          echo form_dropdown("orden_compra_precio",
                            array('0' => 'Precio Compra','1' => 'Precio Venta'),
                            set_value('orden_compra_precio', $data['data']['orden_compra_precio'] ));
                        ?>

                    </div>

                </div>

                <div class="row-form">

                    <hr style=" margin-top: 30px;">

                    <div class="span4">

                        <!-- Radio Button-->
                        <h6 class="example-title">Imprimir Factura</h6>
                        <div class="example-wrap">
                            <div class="">
                                <input type="radio" id="auto_factura_estandar" name="auto_factura" value="estandar" <?php echo ($data['data']['auto_factura'] == "estandar") ? "checked" : ""; ?>>
                                <label for="auto_factura_estandar">Preguntar</label>
                            </div>
                            <div class="">
                                <input type="radio" id="auto_factura_auto" name="auto_factura" value="auto" <?php echo ($data['data']['auto_factura'] == "auto") ? "checked" : ""; ?>>
                                <label for="auto_factura_auto">Automático</label>
                            </div>
                            <div class="">
                                <input type="radio" id="auto_factura_no" name="auto_factura" value="no" <?php echo ($data['data']['auto_factura'] == "no") ? "checked" : ""; ?>>
                                <label for="auto_factura_no">No Imprimir</label>
                            </div>
                        </div>

                    </div>

                    <div class="span4">

                        <!-- Radio Button-->
                        <h6 class="example-title">Enviar Factura</h6>
                        <div class="example-wrap">
                            <div class="">
                                <input type="radio" id="enviar_factura_estandar" name="enviar_factura" value="estandar" <?php echo ($data['data']['enviar_factura'] == "estandar") ? "checked" : ""; ?>>
                                <label for="enviar_factura_estandar">Preguntar</label>
                            </div>
                            <!--
                            <div class="">
                                <input type="radio" id="enviar_factura_auto" name="enviar_factura" value="auto" <?php echo ($data['data']['enviar_factura'] == "auto") ? "checked" : ""; ?>>
                                <label for="enviar_factura_auto">Automático</label>
                            </div>
                            -->
                            <div class="">
                                <input type="radio" id="enviar_factura_no" name="enviar_factura" value="no" <?php echo ($data['data']['enviar_factura'] == "no") ? "checked" : ""; ?>>
                                <label for="enviar_factura_no">No Enviar</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row-form">
                    <div class="span4">

                        <!-- Radio Button-->
                        <h6 class="example-title">Pago Automático</h6>
                        <div class="example-wrap">
                            <div class="">
                                <input type="radio" id="auto_pago_estandar" name="auto_pago" value="estandar" <?php echo ($data['data']['auto_pago'] == "estandar") ? "checked" : ""; ?>>
                                <label for="auto_pago_estandar">Preguntar</label>
                            </div>
                            <div class="">
                                <input type="radio" id="auto_pago_automatico" name="auto_pago" value="auto" <?php echo ($data['data']['auto_pago'] == "auto") ? "checked" : ""; ?>>
                                <label for="auto_pago_automatico">Automático</label>
                            </div>
                        </div>

                    </div>
                    <div class="span4">
                        &nbsp;&nbsp;
                    </div>

                </div>


            </div>
        </div>
        <div class="tab-pane" id="alertas">
            <div class="span4">
                <div class="row-form">
                    <div class="span4"> Alerta de inventario:</div>
                    <div class="span8">
                        <select name="modulo_alertas" id="modulo_alertas" <?php echo $data['data']['alertas'] == -1 ? 'disabled' : '' ?> data-value="<?php echo $data['data']['alertas'] == -1 ? '' : $data['data']['alertas']?>">
                          <option value="">seleccionar</option>
                          <option value="1">si</option>
                          <option value="0">no</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="monedas">
            <div class="span6">
                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('sima_money', "Moneda");?>:</div>

                    <div class="span8"><?php echo form_dropdown("moneda", $data['moneda'], set_value('moneda', $data['data']['moneda'])); ?>

                        <?php echo form_error('moneda'); ?>

                    </div>

                </div>

                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('sima_simbolo', "Simbolo");?>:</div>

                    <div class="span8">
                        <input type="text" name="simbolo" placeholder="$" value="<?php echo set_value('simbolo', $data['data']['simbolo']) ?>">
                    </div>

                </div>


                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('sima_decimales', "Numero decimales");?>:</div>

                    <div class="span8">
                        <input type="number" min="0" max="3" name="decimales" value="<?php echo  $data['data']['decimales'] ?>">
                    </div>

                </div>

                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('sima_separadorDecimal', "Separador decimales");?>:</div>

                    <div class="span8">
                        <?php echo form_dropdown("separadorDecimales", array(','=>',(coma)','.'=>'.(punto)'), set_value('separadorDecimales', $data['data']['separadorDecimales'])); ?>
                    </div>
                </div>

                <div class="row-form">

                    <div class="span4"><?php echo custom_lang('sima_separadorMiles', "Separador Miles");?>:</div>

                    <div class="span8"><?php echo form_dropdown("separadorMiles", array(','=>',(coma)','.'=>'.(punto)'), set_value('separadorMiles', $data['data']['separadorMiles'])); ?>

                        <?php echo form_error('separadorMiles'); ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <!--
    TIPO SISTEMA

    <div class="row-form">

        <div class="span4"><?php //echo custom_lang('sima_modules', "Sistema");?>:</div>

        <div class="span8"><?php //echo form_dropdown("sistema", array('Servicios' => 'Servicios', 'Pos' => 'Pos'), set_value('sistema', $data['data']['sistema']), "id='sistema'"); ?>

            <?php //echo form_error('sistema'); ?>

        </div>

    </div>  -->

    <div class="toolbar bottom text-center">

        <div class="btn-group">
            <div class="span6">
            <button class="btn btn-success" type="submit"><?php echo custom_lang("sima_submit", "Guardar");?></button>
            </div>
            <div class="span6">
            <button class="btn btn-warning"  type="button" onclick="javascript:location.href='../frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
            </div>
        </div>

    </div>

    </form>

</div>

<script type="text/javascript">

    $(document).ready(function(){

        var sistema = "<?php echo $data['data']['sistema']?>";

        if(sistema == "Pos"){

            $("#servicios").css("display", "none");

        }

        else{

            $("#pos").css("display", "none");

        }

        var pais = "<?php echo $data['data']['pais'] ?>";
        if(pais == 103){
          $(".div_datos_mexico").css('display','block');
        }

        $("#pais").change(function(){
           if($(this).val()==103){
              $(".div_datos_mexico").css('display','block');
           }else{
              $(".div_datos_mexico").css('display','none');
           }
        });


        $("#sistema").change(function(){



            if($(this).val() == 'Pos'){

                $("#pos").css("display", "block");

                $("#servicios").css("display", "none");

            }

            else if($(this).val() == 'Servicios'){

                $("#servicios").css("display", "block");

                $("#pos").css("display", "none");

            }

        });

    });

</script>