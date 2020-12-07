<div class="page-header">
    <div class="icon">
        <span class="ico-files"></span>
    </div>
    <h1><?php echo custom_lang("Facturas", "Factura");?><small><?php echo $this->config->item('site_title');?></small></h1>
</div>
<div class="block title">
    <div class="head">
        <h2><?php echo custom_lang('sima_update_payment', "Editar pago a la factura").' '. $data['numero'];?></h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="block">
                            <div class="data-fluid">
                                <?php echo form_open("pagos/editar/".$data['data']['id_factura'], array("id" =>"validate"));?>
                                <input type="hidden" name="id_pago" value="<?php echo $data['data']['id_pago'];?>"/>
                                <input type="hidden" name="id_factura" value="<?php echo $data['data']['id_factura'];?>"/>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>
                                    <div class="span9"><input type="text"  value="<?php echo $data['data']['fecha_pago']; ?>" name="fecha_pago" id="fecha_pago"/>
                                            <?php echo form_error('fecha_pago'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_amount', "Cantidad");?>:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('cantidad', $data['data']['cantidad']); ?>" name="cantidad" placeholder="Cantidad"/>
                                        <?php echo form_error('cantidad'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('careoftheretention', "Importe de la retenci&oacute;n");?>:</div>
                                    <div class="span9"><input type="text" value="<?php echo set_value('importe_retencion', $data['data']['importe_retencion']); ?>" name="importe_retencion" placeholder=""/>
                                        <?php echo form_error('importe_retencion'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_type', "Tipo");?>:</div>
                                    <div class="span9">
                                            <?php echo form_dropdown('tipo', $data['tipo'], $this->form_validation->set_value('tipo', $data['data']['tipo']));?>
                                            <?php echo form_error('tipo'); ?>
                                    </div>
                                </div>
                                <div class="row-form">
                                    <div class="span3"><?php echo custom_lang('sima_notes', "Notas");?>:</div>
                                    <div class="span9"><textarea name="notas" placeholder="Notas"><?php echo set_value('notas', $data['data']['notas']); ?></textarea>
                                        <?php echo form_error('notas'); ?>
                                    </div>
                                </div>
                                <div class="toolbar bottom tar">
                                    <div class="btn-group">
                                        <button class="btn" type="submit"><?php echo custom_lang("sima_submit", "Enviar");?></button>
                                        <button class="btn btn-warning" type="reset">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                            </form>
    </div>
    </div>
    
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $( "#fecha_pago" ).datepicker({
             dateFormat: 'yy/mm/dd'
        });
    });
</script>