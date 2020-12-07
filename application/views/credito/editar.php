<div class="page-header">
    <div class="icon">
        <span class="ico-files"></span>
    </div>
    <h1><?php echo custom_lang("Facturas", "Factura");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_edit_invoice', "Editar factura");?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("facturas/editar/".$data["data"]['id_factura'], array("id" =>"validate"));?>
                                    <input type="hidden" name="id_factura" id="id_factura" value="<?php echo set_value('id_factura', $data["data"]['id_factura']); ?>" />
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_invoice_number', "Numero de factura");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo set_value('numero', $data['data']['numero']); ?>" name="numero" readonly="readonly"/>
                                                <?php echo form_error('numero'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo date("Y/m/d", strtotime($data['data']['fecha'])); ?>" name="fecha" id="fecha" readonly="readonly"/>
                                                <?php echo form_error('numero'); ?>
                                        </div>
                                    </div>
                                
                               
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_customer', "Cliente");?>:</div>
                                        <div class="span9"><input type="text"  value="<?php echo set_value('datos_cliente', $data['data']['nombre_comercial']); ?>" name="datos_cliente" id="datos_cliente" readonly="readonly"/>
                                                <?php echo form_error('datos_cliente'); ?>
                                        </div>
                                    </div>
                                    <div class="row-form">
                                        <div class="span3"><?php echo custom_lang('sima_customer_data', "Datos del cliente");?>:</div>
                                        <div class="span9"><textarea name="otros_datos" id="otros_datos" placeholder="" readonly="readonly"><?php echo $data["data"]['nombre_comercial']." (".$data["data"]['razon_social'].") \n".$data["data"]['nif_cif'].", ".$data["data"]['direccion'].", ".$data["data"]['poblacion'].", ".$data["data"]['pais'].", ".$data["data"]['provincia'].", ".$data["data"]['cp'] ?></textarea>
                                        </div>
                                    </div>
                                    <table class="table aTable" cellpadding="0" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                            <th width="10"></th>
                                            <th width="20%"><?php echo custom_lang('sima_product_name', "Nombre del producto");?></th>
                                            <th width="25%"><?php echo custom_lang('sima_description', "Descripción");?></th>
                                            <th width="10%"><?php echo custom_lang('sima_amount', "Cantidad");?></th>
                                            <th width="10%" style="text-align:right" width="10%"><?php echo custom_lang('sima_price', "Precio");?></th>
                                            <th width="10%"><?php echo custom_lang('sima_discount', "Descuento(%)");?></th>
                                            <th width="10%"><?php echo custom_lang('sima_tax', "Impuesto");?></th>
                                            <th style="text-align:right" width="10%"><?php echo custom_lang('sima_total_price', "Precio Total");?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="detalle">
                                            <?php
                                              /*  echo "<pre>";
                                                    print_r($data['detail']);
                                                echo '</pre>';*/
                                                                    $total = 0;
                                                                    $iva = 0;
                                                                    foreach($data['detail'] as $k) 
                                                                    {
                                                                            $precio_t = $k['precio'] * $k['cantidad'];
                                                                            $impuesto = $k['impuesto'] * $precio_t / 100;
                                                                            
                                                                            $descuento = $k['descuento'] * $precio_t / 100;
                                                                            $total    = $impuesto + $precio_t - $descuento;
                                                                           
                                                                    ?>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td><?php echo $k['nombre']; ?></td>
                                                                        <td><?php echo $k['descripcion_d']; ?></td>
                                                                        <td><?php echo $k['cantidad']; ?></td>
                                                                        <td style="text-align:right"><?php echo $k['precio']; ?></td>
                                                                        <td style="text-align:right"><?php echo $k['descuento']; ?></td>
                                                                        <td style="text-align:right"><?php echo $k['impuesto']; ?></td>
                                                                        <td style="text-align:right"><?php echo $total; ?></td>
                                                                    </tr>
                                                                    <?php 

                                                                    }
                                                                    ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                    <th colspan="3"></th>
                                                <th style="text-align:right"><b><?php echo custom_lang('sima_total_without', "Total sin IVA");?></b></th>
                                                <th style="text-align:right"><b class="total_siva"><?php echo $data['data']['monto_siva']; ?></b></th>
                                                <th colspan="3">&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th style="text-align:right"><b> <?php echo custom_lang('sima_iva', "IVA");?></b></th>
                                                <th style="text-align:right"><b class="iva"><?php echo $data['data']['monto_iva']; ?></b></th>
                                                <th colspan="3">&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th colspan="3"></th>
                                                <th style="text-align:right"><b><?php echo custom_lang('sima_total_with', "Total con IVA");?></b></th>
                                                <th style="text-align:right"><b class="total_civa"><?php echo $data['data']['monto']; ?></b></th>
                                                <th colspan="3">&nbsp;</th>
                                            </tr>
                                        <tfoot>
                                    </table>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_state', "Estado");?>:</div>
                                    <div class="span9">
                                            <?php echo form_dropdown('estado', array('1' => "CERRADA", '0' => "ABIERTO", '2' => "ANULADO"), $data['data']['estado']);?>
                                            <?php echo form_error('estado'); ?>
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                                        <button class="btn btn-warning" type="reset"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
</div>