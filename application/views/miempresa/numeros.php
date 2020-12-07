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
    
</style>

<div class="page-header">

    <div class="icon">

        <span class="ico-box"></span>

    </div>

    <h1><?php echo custom_lang("Configuracion", "Configuración");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_config_numbers', "Configurar números");?></h2>                                          

    </div>

</div>
<?php echo form_open("miempresa/numeros", array("id" =>"validate"));?>
<div class="row-fluid">
<div class="data-fluid tabbable">                    
    <ul class="nav nav-tabs">
        <li class="active"><a href="#factura" data-toggle="tab">Factura</a></li>
        <li><a href="#cotizacion" data-toggle="tab">Cotizacion</a></li>
        <li><a href="#nota_credito" data-toggle="tab">Nota Credito</a></li>
        <?php 
        if($existeSiigo){
            ?>
            <li><a href="#siigo" data-toggle="tab">Siigo</a></li>
            <?php 
        }
        ?>
    </ul>
    
    <div class="tab-content">
        <div class="tab-pane active" id="factura">
            <div class="span6">

                <div class="block">

                    <div class="head">

                        <h2><?php echo custom_lang("sima_config_numbers_factura", "Configurar número y prefijo para factura");?></h2>

                    </div>

                    <div class="data-fluid">

                        <div class="row-form">

                            <div class="span3"><?php echo custom_lang('sima_init_number', "Número de inicio");?>:</div>
                                <div class="span9"><input type="text"  value="<?php echo set_value('numero_factura', $numero_factura); ?>" placeholder="" name="numero_factura" />
                                    <?php echo form_error('numero_factura'); ?>
                            </div>
                        </div>

                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_prefix', "Prefijo");?>:</div>

                                <div class="span9"><input type="text" value="<?php echo set_value('prefijo_factura', $prefijo_factura); ?>" name="prefijo_factura"/>

                                    <?php echo form_error('prefijo_factura'); ?>

                                </div>

                            </div>

                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="head">

                        <h2><?php echo custom_lang("sima_config_numbers_factura", "Alertas");?></h2>

                    </div>

                    <div class="data-fluid">

                        <div class="row-form">

                            <div class="span3"><?php echo custom_lang('sima_aviso_fin_numero', "Avisarme cuando llegue al numero");?>:</div>
                                <div class="span9"><input type="text"  value="<?php echo set_value('numero_alerta_factura', $numero_alerta_factura); ?>" placeholder="" name="numero_alerta_factura" />
                                    <?php echo form_error('numero_factura'); ?>
                            </div>
                        </div>

                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_aviso_faltan_facturas', "Avisarme cuando falten ");?>:</div>

                                <div class="span9">
                                    <select name="dias_alerta_factura" value="">
                                        <option value="7" <?php echo ($dias_alerta_factura == 7) ? "selected":"" ?>>7 Dias</option>
                                        <option value="15" <?php echo ($dias_alerta_factura == 15) ? "selected":"" ?>>15 Dias</option>
                                        <option value="30" <?php echo ($dias_alerta_factura == 30) ? "selected":"" ?>>30 Dias</option>
                                    </select>

                                    <?php echo form_error('dias_alerta_factura'); ?>

                                </div>

                            </div>

                    </div>

                </div>
                

            </div>
            <div class="span6">

                <div class="block">

                    <div class="head">

                        <h2></h2>

                    </div>

                    <div class="data-fluid">

                        <div class="row-form">

                            <div class="span3"><?php echo custom_lang('sima_fin_number', "Número de Fin");?>:</div>
                                <div class="span9"><input type="text"  value="<?php echo set_value('numero_fin_factura', $numero_factura_fin); ?>" placeholder="" name="numero_factura_fin" />
                                    <?php echo form_error('numero_factura_fin'); ?>
                            </div>
                        </div>

                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_fecha_vencimiento_facturacion', "Fecha de vencimiento");?>:</div>

                                <div class="span9"><input type="text" value="<?php echo set_value('fecha_factura', $fecha_factura); ?>" name="fecha_factura" class="datepicker"/>

                                    <?php echo form_error('fecha_factura'); ?>

                                </div>

                            </div>

                    </div>

                </div>

            </div>
        </div>
        <div class="tab-pane" id="cotizacion">
            <div class="span6">
                <div class="block">

                    <div class="head">

                        <h2><?php echo custom_lang("sima_config_numbers_presupuesto", "Configurar número y prefijo para presupuesto");?></h2>

                    </div>

                    <div class="data-fluid">

                        <div class="row-form">

                            <div class="span3"><?php echo custom_lang('sima_init_number', "Número de inicio");?>:</div>

                                <div class="span9"><input type="text"  value="<?php echo set_value('numero_presupuesto', $numero_presupuesto); ?>" placeholder="" name="numero_presupuesto" />

                                        <?php echo form_error('numero_presupuesto'); ?>

                                </div>

                            </div>

                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_prefix', "Prefijo");?>:</div>

                                <div class="span9"><input type="text" value="<?php echo set_value('prefijo_presupuesto', $prefijo_presupuesto); ?>" name="prefijo_presupuesto"/>

                                    <?php echo form_error('prefijo_presupuesto'); ?>

                                </div>

                            </div>

                    </div>

                </div>

            </div>
        </div>
        <div class="tab-pane" id="nota_credito">
            <div class="span6">

                <div class="block">

                    <div class="head">

                        <h2><?php echo custom_lang("sima_config_numbers_presupuesto", "Configurar número y prefijo para devoluciones");?></h2>

                    </div>

                    <div class="data-fluid">

                        <div class="row-form">

                            <div class="span3"><?php echo custom_lang('sima_init_number', "Número de inicio");?>:</div>

                                <div class="span9"><input type="text"  value="<?php echo set_value('numero_devolucion', $numero_devolucion); ?>" placeholder="" name="numero_devolucion" />

                                        <?php echo form_error('numero_devolucion'); ?>

                                </div>

                            </div>

                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_prefix', "Prefijo");?>:</div>

                                <div class="span9"><input type="text" value="<?php echo set_value('prefijo_devolucion', $prefijo_devolucion); ?>" name="prefijo_devolucion"/>

                                    <?php echo form_error('prefijo_devolucion'); ?>

                                </div>

                            </div>

                    </div>

                </div>

            </div>
        </div>
        <?php 
        if($existeSiigo){
            ?>
        <div class="tab-pane" id="siigo">
            <div class="span6">
                <div class="block">
                    <div class="head">
                        <h2><?php echo custom_lang("sima_config_numbers_presupuesto", "Configurar codigos SIIGO para forma de pago");?></h2>
                    </div>

                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 1");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo1FP', $arregloSiigo[0]->codigo1); ?>" placeholder="" name="codigo1FP" />
                                    <?php echo form_error('codigo1FP'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 2");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo2FP', $arregloSiigo[0]->codigo2); ?>" placeholder="" name="codigo2FP" />
                                    <?php echo form_error('codigo2FP'); ?>
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 3");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo3FP', $arregloSiigo[0]->codigo3); ?>" placeholder="" name="codigo3FP" />
                                    <?php echo form_error('codigo3FP'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 4");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo4FP', $arregloSiigo[0]->codigo4); ?>" placeholder="" name="codigo4FP" />
                                    <?php echo form_error('codigo4FP'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 5");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo5FP', $arregloSiigo[0]->codigo5); ?>" placeholder="" name="codigo5FP" />
                                    <?php echo form_error('codigo5FP'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 6");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo6FP', $arregloSiigo[0]->codigo6); ?>" placeholder="" name="codigo6FP" />
                                    <?php echo form_error('codigo6FP'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_prefix', "Letra");?>:</div>

                                <div class="span9"><input type="text" value="<?php echo set_value('letraFP', $arregloSiigo[0]->letra); ?>" name="letraFP"/>

                                    <?php echo form_error('letraFP'); ?>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="span6">
                <div class="block">
                    <div class="head">
                        <h2><?php echo custom_lang("sima_config_numbers_presupuesto", "Configurar codigos SIIGO para inventario");?></h2>
                    </div>

                    <div class="data-fluid">
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 1");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo1I', $arregloSiigo[1]->codigo1); ?>" placeholder="" name="codigo1I" />
                                    <?php echo form_error('codigo1I'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 2");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo2I', $arregloSiigo[1]->codigo2); ?>" placeholder="" name="codigo2I" />
                                    <?php echo form_error('codigo2I'); ?>
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 3");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo3I', $arregloSiigo[1]->codigo3); ?>" placeholder="" name="codigo3I" />
                                    <?php echo form_error('codigo3I'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 4");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo4I', $arregloSiigo[1]->codigo4); ?>" placeholder="" name="codigo4I" />
                                    <?php echo form_error('codigo4I'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 5");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo5I', $arregloSiigo[1]->codigo5); ?>" placeholder="" name="codigo5I" />
                                    <?php echo form_error('codigo5I'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="span3"><?php echo custom_lang('sima_init_number', "Codigo 6");?>:</div>
                            <div class="span9"><input type="text"  value="<?php echo set_value('codigo6I', $arregloSiigo[1]->codigo6); ?>" placeholder="" name="codigo6I" />
                                    <?php echo form_error('codigo6I'); ?>
                            </div>
                        </div>
                        <div class="row-form">
                            <div class="row-form">

                                <div class="span3"><?php echo custom_lang('sima_prefix', "Letra");?>:</div>

                                <div class="span9"><input type="text" value="<?php echo set_value('letraI', $arregloSiigo[1]->letra); ?>" name="letraI"/>

                                    <?php echo form_error('letraI'); ?>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <?php 
        }
        ?>
        
    </div>
</div>
<br>
<div class="row-fluid">
    
    
    <div class="clearfix"></div>

    <div class="toolbar bottom tar">

        <div class="btn-group">

            <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>

 <button class="btn btn-warning"  type="button" onclick="javascript:location.href='../frontend/configuracion'"><?php echo custom_lang('sima_cancel', "Cancelar");?></button>

        </div>

    </div>

</form>

</div>