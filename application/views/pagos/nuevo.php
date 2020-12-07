<div class="page-header">

    <div class="icon">

        <span class="ico-files"></span>

    </div>

    <h1><?php echo custom_lang("Facturas", "Factura");?><small><?php echo $this->config->item('site_title');?></small></h1>

</div>

<div class="block title">

    <div class="head">

        <h2><?php echo custom_lang('sima_new_payment', "Nuevo pago a la factura") .' '. $data['numero'];?></h2>                                          

    </div>

</div>

<div class="row-fluid">

    <div class="span6">

        <div class="block">

            <?php

                $message = $this->session->flashdata('message');

                if(!empty($message)):?>

                <div class="alert alert-success">

                    <?php echo $message;?>

                </div>

            <?php endif; ?>

                            <div class="data-fluid">

                                <?php echo form_open("pagos/nuevo/".$data['id_factura'], array("id" =>"validate"));?>

                                <input type="hidden" name="id_factura" value="<?php echo $data['id_factura'];?>"/>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_date', "Fecha");?>:</div>

                                    <div class="span9"><input type="text"  value="<?php echo date("Y/m/d"); ?>" name="fecha_pago" id="fecha_pago"/>

                                            <?php echo form_error('fecha_pago'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_amount', "Cantidad");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('cantidad'); ?>" name="cantidad" placeholder=""/>

                                        <?php echo form_error('cantidad'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('careoftheretention', "Importe de la retenci&oacute;n");?>:</div>

                                    <div class="span9"><input type="text" value="<?php echo set_value('importe_retencion'); ?>" name="importe_retencion" placeholder=""/>

                                        <?php echo form_error('importe_retencion'); ?>

                                    </div>

                                </div>

                                <div class="row-form">

                                    <div class="span3"><?php echo custom_lang('sima_type', "M&eacute;todo");?>:</div>

                                    <div class="span9">

                                            <?php echo form_dropdown('tipo', $data['tipo'], $this->form_validation->set_value('tipo'));?>

                                            <?php echo form_error('tipo'); ?>

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